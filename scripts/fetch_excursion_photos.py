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


def build_preview_html(manifest: list[SavedFile]) -> None:
    by_country: dict[str, dict[str, list[SavedFile]]] = {}
    for row in manifest:
        by_country.setdefault(row.country, {}).setdefault(row.slot_id, []).append(row)

    preview_to_theme = os.path.relpath(THEME_ROOT, PREVIEW_HTML.parent).replace("\\", "/")

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
                src = f"{preview_to_theme}/{item.rel_jpg}"
                blocks.append(
                    f"<figure><a href='{src}' target='_blank' rel='noopener'>"
                    f"<img src='{src}' alt='' loading='lazy' /></a>"
                    f"<figcaption><code>{item.filename_jpg}</code><br>"
                    f"<small>{item.query} · pexels {item.pexels_id} · {item.photographer}</small>"
                    f"</figcaption></figure>"
                )
            blocks.append("</div>")
        sections.append("".join(blocks))

    html = f"""<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <title>Excursion photo candidates — Pexels</title>
  <style>
    body {{ font-family: system-ui, sans-serif; margin: 24px; background: #f6f4f0; color: #1a1a1a; }}
    h1 {{ font-size: 1.5rem; }}
    h2 {{ margin-top: 2.5rem; border-bottom: 2px solid #c85a32; padding-bottom: 6px; text-transform: capitalize; }}
    h3 {{ margin-top: 1.5rem; color: #555; font-size: 1rem; }}
  .grid {{ display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }}
    figure {{ margin: 0; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e0dbd4; }}
    img {{ width: 100%; height: 200px; object-fit: cover; display: block; }}
    figcaption {{ padding: 10px 12px; font-size: 13px; line-height: 1.4; }}
    code {{ font-size: 11px; word-break: break-all; }}
  </style>
</head>
<body>
  <h1>Кандидаты фото для экскурсионных лендингов</h1>
  <p>Откройте этот файл с диска или через <code>python3 -m http.server</code> в корне репозитория.</p>
  {''.join(sections)}
</body>
</html>
"""
    PREVIEW_HTML.write_text(html, encoding="utf-8")
    print(f"Preview: {PREVIEW_HTML}")


def main() -> int:
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
    manifest: list[SavedFile] = []
    for plan in PLANS:
        print(f"\n== {plan.slug} ==")
        for slot in plan.hero:
            process_slot(client, plan.slug, slot, manifest)
        for slot in plan.programs:
            process_slot(client, plan.slug, slot, manifest)

    manifest_path = OUT_ROOT / "_manifest.json"
    manifest_path.parent.mkdir(parents=True, exist_ok=True)
    manifest_path.write_text(
        json.dumps([row.__dict__ for row in manifest], ensure_ascii=False, indent=2),
        encoding="utf-8",
    )
    build_preview_html(manifest)
    print(f"\nDone. {len(manifest)} files. Manifest: {manifest_path}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())