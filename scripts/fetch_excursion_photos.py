#!/usr/bin/env python3
"""
Download Pexels candidates for excursion landings (hero + program cards).

Saves optimized JPEG/WebP to:
  deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/assets/img/excursions/candidates/{country}/

Env: PEXELS_API_KEY (from shell or repo .env / teya.env.local)

Generates: scripts/preview_excursion_photos.html
"""
from __future__ import annotations

import io
import json
import os
import re
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
from dataclasses import dataclass, field
from pathlib import Path
from typing import Any

REPO_ROOT = Path(__file__).resolve().parent.parent
THEME_ROOT = REPO_ROOT / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa"
OUT_ROOT = THEME_ROOT / "assets/img/excursions/candidates"
PREVIEW_HTML = Path(__file__).resolve().parent / "preview_excursion_photos.html"
PREVIEW_INDEX = OUT_ROOT / "index.html"

MAX_WIDTH = 1200
JPEG_QUALITY = 82
WEBP_QUALITY = 82


def load_dotenv() -> None:
    for path in (
        REPO_ROOT / ".env",
        REPO_ROOT / "teya-memory/teya.env.local",
    ):
        if not path.is_file():
            continue
        for line in path.read_text(encoding="utf-8").splitlines():
            line = line.strip()
            if not line or line.startswith("#") or "=" not in line:
                continue
            key, _, val = line.partition("=")
            key = key.strip()
            val = val.strip().strip('"').strip("'")
            if key and key not in os.environ:
                os.environ[key] = val


@dataclass
class SearchSlot:
    slot_id: str
    query: str
    orientation: str
    count: int = 1
    fallback: str | None = None


@dataclass
class CountryPlan:
    slug: str
    hero: list[SearchSlot] = field(default_factory=list)
    programs: list[SearchSlot] = field(default_factory=list)


PLANS: list[CountryPlan] = [
    CountryPlan(
        slug="gruziya",
        hero=[
            SearchSlot("hero", "Tbilisi old town sunset", "portrait", 2),
            SearchSlot("hero", "Kazbegi mountains Georgia", "portrait", 1),
        ],
        programs=[
            SearchSlot("GE-CLASSIC", "Tbilisi old town architecture", "landscape", 2),
            SearchSlot("GE-WEEKEND", "Tbilisi balconies street", "landscape", 2),
            SearchSlot("GE-WINE", "Georgia vineyard mountains", "landscape", 2),
            SearchSlot("GE-KAZBEGI", "Caucasus mountains road", "landscape", 2),
            SearchSlot("GE-BUS", "mountain road scenic Caucasus", "landscape", 2),
        ],
    ),
    CountryPlan(
        slug="uzbekistan",
        hero=[
            SearchSlot("hero", "Registan Samarkand blue", "portrait", 2),
            SearchSlot("hero", "Samarkand architecture sunlight", "portrait", 1),
        ],
        programs=[
            SearchSlot("UZ-CLASSIC", "Registan Square Samarkand", "landscape", 2),
            SearchSlot("UZ-WEEKEND", "Shah-i-Zinda Samarkand", "landscape", 2),
            SearchSlot(
                "UZ-GASTRO",
                "Uzbek pilaf plov",
                "landscape",
                2,
                fallback="oriental spice market",
            ),
            SearchSlot(
                "UZ-KHIVA",
                "Khiva Uzbekistan minaret",
                "landscape",
                2,
                fallback="silk road desert fortress",
            ),
            SearchSlot(
                "UZ-MOUNTAINS",
                "Chimgan mountains Uzbekistan",
                "landscape",
                2,
                fallback="mountain lake Central Asia",
            ),
        ],
    ),
    CountryPlan(
        slug="armeniya",
        hero=[
            SearchSlot("hero", "Yerevan Mount Ararat", "portrait", 2),
            SearchSlot("hero", "Armenia monastery mountains", "portrait", 1),
        ],
        programs=[
            SearchSlot(
                "AM-CLASSIC",
                "Garni temple Armenia",
                "landscape",
                2,
                fallback="Lake Sevan monastery",
            ),
            SearchSlot(
                "AM-WEEKEND",
                "Yerevan Cascade pink city",
                "landscape",
                2,
                fallback="Yerevan street cafe",
            ),
            SearchSlot(
                "AM-WINE",
                "Khor Virap Ararat",
                "landscape",
                2,
                fallback="red wine tasting rustic",
            ),
            SearchSlot(
                "AM-TATEV",
                "Tatev monastery Armenia",
                "landscape",
                2,
                fallback="mountain gorge cable car",
            ),
            SearchSlot(
                "AM-NATURE",
                "Dilijan forest Armenia",
                "landscape",
                2,
                fallback="ancient stone monastery mountains",
            ),
        ],
    ),
]

# Extra batches (append to manifest; GE-WINE без «Georgia» — иначе US state).
SUPPLEMENTAL: list[tuple[str, SearchSlot]] = [
    (
        "gruziya",
        SearchSlot("GE-WINE", "red wine pouring glass rustic", "landscape", 2),
    ),
    (
        "gruziya",
        SearchSlot("GE-WINE", "wine barrels cellar warm light", "landscape", 2),
    ),
    (
        "gruziya",
        SearchSlot("GE-KAZBEGI", "Gergeti Trinity Church", "landscape", 2),
    ),
    (
        "gruziya",
        SearchSlot("GE-KAZBEGI", "Mount Kazbek Georgia", "landscape", 2),
    ),
]


def slugify(text: str, max_len: int = 48) -> str:
    s = re.sub(r"[^a-zA-Z0-9]+", "-", text.strip().lower()).strip("-")
    return s[:max_len] or "photo"


class PexelsClient:
    def __init__(self, api_key: str) -> None:
        self.api_key = api_key
        self._seen_ids: set[int] = set()

    def search(
        self, query: str, orientation: str, per_page: int = 15, page: int = 1
    ) -> list[dict[str, Any]]:
        params = {
            "query": query,
            "per_page": str(per_page),
            "page": str(page),
            "orientation": orientation,
        }
        url = "https://api.pexels.com/v1/search?" + urllib.parse.urlencode(params)
        req = urllib.request.Request(
            url,
            headers={
                "Authorization": self.api_key,
                "User-Agent": "zhiraf-ufa-excursion-fetch/1.0",
            },
        )
        try:
            with urllib.request.urlopen(req, timeout=45) as resp:
                data = json.load(resp)
        except urllib.error.HTTPError as e:
            body = e.read().decode("utf-8", errors="replace")
            raise RuntimeError(f"Pexels HTTP {e.code} for {query!r}: {body}") from e
        photos = data.get("photos") or []
        return [p for p in photos if isinstance(p, dict)]

    def pick_photos(
        self, slot: SearchSlot, need: int, extra_query: str | None = None
    ) -> list[dict[str, Any]]:
        picked: list[dict[str, Any]] = []
        queries = [slot.query]
        if extra_query:
            queries.append(extra_query)
        for q in queries:
            for page in (1, 2, 3):
                if len(picked) >= need:
                    break
                batch = self.search(q, slot.orientation, per_page=20, page=page)
                for photo in batch:
                    pid = int(photo.get("id") or 0)
                    if pid <= 0 or pid in self._seen_ids:
                        continue
                    w = int(photo.get("width") or 0)
                    h = int(photo.get("height") or 0)
                    if slot.orientation == "portrait" and w > h:
                        continue
                    if slot.orientation == "landscape" and h > w:
                        continue
                    self._seen_ids.add(pid)
                    picked.append(photo)
                    if len(picked) >= need:
                        break
                time.sleep(0.35)
            if len(picked) >= need:
                break
        return picked


def download_bytes(url: str) -> bytes:
    req = urllib.request.Request(url, headers={"User-Agent": "zhiraf-ufa-excursion-fetch/1.0"})
    with urllib.request.urlopen(req, timeout=90) as resp:
        return resp.read()


def save_optimized(raw: bytes, dest_jpg: Path, dest_webp: Path) -> tuple[int, int]:
    from PIL import Image

    img = Image.open(io.BytesIO(raw))
    if img.mode in ("RGBA", "P"):
        img = img.convert("RGB")
    w, h = img.size
    if w > MAX_WIDTH:
        nh = int(h * (MAX_WIDTH / w))
        img = img.resize((MAX_WIDTH, nh), Image.Resampling.LANCZOS)
    dest_jpg.parent.mkdir(parents=True, exist_ok=True)
    img.save(dest_jpg, format="JPEG", quality=JPEG_QUALITY, optimize=True)
    img.save(dest_webp, format="WEBP", quality=WEBP_QUALITY, method=4)
    return img.size


@dataclass
class SavedFile:
    country: str
    slot_id: str
    query: str
    filename_jpg: str
    filename_webp: str
    rel_jpg: str
    pexels_id: int
    photographer: str
    alt: str


def process_slot(
    client: PexelsClient,
    country: str,
    slot: SearchSlot,
    manifest: list[SavedFile],
) -> None:
    photos = client.pick_photos(slot, slot.count, slot.fallback)
    if len(photos) < slot.count:
        print(
            f"WARN {country}/{slot.slot_id}: got {len(photos)}/{slot.count} for {slot.query!r}",
            file=sys.stderr,
        )
    subdir = OUT_ROOT / country / slot.slot_id
    subdir.mkdir(parents=True, exist_ok=True)
    qslug = slugify(slot.query)
    for idx, photo in enumerate(photos, start=1):
        src = (photo.get("src") or {}).get("large2x") or (photo.get("src") or {}).get(
            "original"
        )
        if not src:
            continue
        pid = int(photo.get("id") or 0)
        base = f"{slot.slot_id}-{idx:02d}-{qslug}-pexels-{pid}"
        jpg = subdir / f"{base}.jpg"
        webp = subdir / f"{base}.webp"
        print(f"  → {jpg.relative_to(REPO_ROOT)}")
        raw = download_bytes(src)
        save_optimized(raw, jpg, webp)
        rel = jpg.relative_to(THEME_ROOT).as_posix()
        manifest.append(
            SavedFile(
                country=country,
                slot_id=slot.slot_id,
                query=slot.query,
                filename_jpg=jpg.name,
                filename_webp=webp.name,
                rel_jpg=rel,
                pexels_id=pid,
                photographer=str(photo.get("photographer") or ""),
                alt=str(photo.get("alt") or slot.query),
            )
        )
        time.sleep(0.2)


def manifest_item_candidates_path(item: SavedFile) -> str:
    """Path relative to assets/img/excursions/candidates/ (for index.html beside photos)."""
    prefix = "assets/img/excursions/candidates/"
    if item.rel_jpg.startswith(prefix):
        return item.rel_jpg[len(prefix) :]
    return f"{item.country}/{item.slot_id}/{item.filename_jpg}"


def load_manifest_rows() -> list[SavedFile]:
    path = OUT_ROOT / "_manifest.json"
    if not path.is_file():
        return []
    raw = json.loads(path.read_text(encoding="utf-8"))
    out: list[SavedFile] = []
    for row in raw:
        if not isinstance(row, dict):
            continue
        out.append(
            SavedFile(
                country=str(row.get("country", "")),
                slot_id=str(row.get("slot_id", "")),
                query=str(row.get("query", "")),
                filename_jpg=str(row.get("filename_jpg", "")),
                filename_webp=str(row.get("filename_webp", "")),
                rel_jpg=str(row.get("rel_jpg", "")),
                pexels_id=int(row.get("pexels_id") or 0),
                photographer=str(row.get("photographer", "")),
                alt=str(row.get("alt", "")),
            )
        )
    return out


def build_preview_html(manifest: list[SavedFile]) -> None:
    by_country: dict[str, dict[str, list[SavedFile]]] = {}
    for row in manifest:
        by_country.setdefault(row.country, {}).setdefault(row.slot_id, []).append(row)

    sections: list[str] = []
    for country in ("gruziya", "uzbekistan", "armeniya"):
        if country not in by_country:
            continue
        blocks: list[str] = [f"<h2>{country}</h2>"]
        slot_order = ["hero"] + sorted(
            k for k in by_country[country].keys() if k != "hero"
        )
        for slot_id in slot_order:
            items = by_country[country].get(slot_id, [])
            if not items:
                continue
            blocks.append(f"<h3>{slot_id}</h3><div class='grid'>")
            for item in items:
                src_candidates = manifest_item_candidates_path(item)
                blocks.append(
                    f"<figure data-src-candidates='{src_candidates}'>"
                    f"<a href='{src_candidates}' target='_blank' rel='noopener'>"
                    f"<img src='{src_candidates}' alt='' loading='lazy' /></a>"
                    f"<figcaption><code>{item.filename_jpg}</code><br>"
                    f"<small>{item.query} · pexels {item.pexels_id} · {item.photographer}</small>"
                    f"</figcaption></figure>"
                )
            blocks.append("</div>")
        sections.append("".join(blocks))

    css = """
    body { font-family: system-ui, sans-serif; margin: 24px; background: #f6f4f0; color: #1a1a1a; }
    h1 { font-size: 1.5rem; }
    .note { background: #fff3e6; border: 1px solid #e8c9a8; padding: 12px 14px; border-radius: 10px; font-size: 14px; }
    h2 { margin-top: 2.5rem; border-bottom: 2px solid #c85a32; padding-bottom: 6px; text-transform: capitalize; }
    h3 { margin-top: 1.5rem; color: #555; font-size: 1rem; }
    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
    figure { margin: 0; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e0dbd4; }
    img { width: 100%; height: 200px; object-fit: cover; display: block; }
    figcaption { padding: 10px 12px; font-size: 13px; line-height: 1.4; }
    code { font-size: 11px; word-break: break-all; }
    """

    intro = """
  <div class="note">
    <strong>Как открыть (Windows):</strong> двойной клик по этому файлу в Проводнике
    (<code>…/assets/img/excursions/candidates/index.html</code>), не в превью Cursor и не на GitHub.<br>
    Нужна ветка <code>cursor/excursion-program-photos-7ec1</code> или новее — на <code>main</code> картинок пока нет.
  </div>
"""

    html_index = f"""<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <title>Экскурсии — кандидаты Pexels</title>
  <style>{css}</style>
</head>
<body>
  <h1>Кандидаты фото для экскурсионных лендингов</h1>
  {intro}
  {''.join(sections)}
</body>
</html>
"""

    # Primary: next to JPEGs (relative paths gruziya/hero/…)
    OUT_ROOT.mkdir(parents=True, exist_ok=True)
    PREVIEW_INDEX.write_text(html_index, encoding="utf-8")

    # Legacy copy in scripts/: rewrite img src to ../deliverables/… for http.server from repo root
    html_scripts = html_index
    for row in manifest:
        rel_c = manifest_item_candidates_path(row)
        preview_to_theme = os.path.relpath(THEME_ROOT, PREVIEW_HTML.parent).replace("\\", "/")
        long_src = f"{preview_to_theme}/{row.rel_jpg}"
        html_scripts = html_scripts.replace(f"href='{rel_c}'", f"href='{long_src}'", 1)
        html_scripts = html_scripts.replace(f"src='{rel_c}'", f"src='{long_src}'", 1)
    html_scripts = html_scripts.replace(
        "index.html</code>",
        "index.html</code> (копия для сервера из корня репо)",
        1,
    )
    PREVIEW_HTML.write_text(html_scripts, encoding="utf-8")

    print(f"Preview (откройте этот): {PREVIEW_INDEX}")
    print(f"Also: {PREVIEW_HTML}")


def merge_manifest(existing: list[SavedFile], fresh: list[SavedFile]) -> list[SavedFile]:
    seen_names = {row.filename_jpg for row in existing}
    seen_ids = {row.pexels_id for row in existing}
    out = list(existing)
    for row in fresh:
        if row.filename_jpg in seen_names or row.pexels_id in seen_ids:
            continue
        out.append(row)
        seen_names.add(row.filename_jpg)
        seen_ids.add(row.pexels_id)
    return out


def save_manifest(manifest: list[SavedFile]) -> Path:
    manifest_path = OUT_ROOT / "_manifest.json"
    manifest_path.parent.mkdir(parents=True, exist_ok=True)
    manifest_path.write_text(
        json.dumps([row.__dict__ for row in manifest], ensure_ascii=False, indent=2),
        encoding="utf-8",
    )
    return manifest_path


def run_supplement(client: PexelsClient) -> list[SavedFile]:
    added: list[SavedFile] = []
    for country, slot in SUPPLEMENTAL:
        print(f"\n== supplement {country}/{slot.slot_id}: {slot.query!r} ==")
        process_slot(client, country, slot, added)
    return added


def main() -> int:
    if "--preview-only" in sys.argv:
        manifest = load_manifest_rows()
        if not manifest:
            print(f"No manifest at {OUT_ROOT / '_manifest.json'}", file=sys.stderr)
            return 1
        build_preview_html(manifest)
        return 0

    load_dotenv()
    api_key = os.environ.get("PEXELS_API_KEY", "").strip()
    if not api_key:
        print("PEXELS_API_KEY missing (.env or environment)", file=sys.stderr)
        return 1
    try:
        from PIL import Image  # noqa: F401
    except ImportError:
        print("Install Pillow: pip install Pillow", file=sys.stderr)
        return 1

    client = PexelsClient(api_key)

    if "--supplement-only" in sys.argv:
        existing = load_manifest_rows()
        for row in existing:
            client._seen_ids.add(row.pexels_id)
        added = run_supplement(client)
        manifest = merge_manifest(existing, added)
        manifest_path = save_manifest(manifest)
        build_preview_html(manifest)
        print(f"\nSupplement: +{len(added)} new, total {len(manifest)}. Manifest: {manifest_path}")
        return 0

    manifest: list[SavedFile] = []
    for plan in PLANS:
        print(f"\n== {plan.slug} ==")
        for slot in plan.hero:
            process_slot(client, plan.slug, slot, manifest)
        for slot in plan.programs:
            process_slot(client, plan.slug, slot, manifest)

    manifest_path = save_manifest(manifest)
    build_preview_html(manifest)
    print(f"\nDone. {len(manifest)} files. Manifest: {manifest_path}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())