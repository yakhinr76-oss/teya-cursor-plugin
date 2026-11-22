#!/usr/bin/env python3
"""
Optional: enrich hotel JSON in data/ with Tripadvisor photo URLs (Terra or legacy Content API).

The WordPress import (import-hotels.php) sideloads images into the media library.
It accepts either placeholder basenames in photos[] or direct https:// URLs (see
import-hotels.php). Server-side import can also pull photos via Terra when
ZHIRAF_TA_API_KEY is set in wp-config.php.

Usage (from theme root):
  export ZHIRAF_TA_API_KEY='...'   # Terra Discover (preferred)
  python3 bin/fetch-hotel-photos-content-api.py --audit
  python3 bin/fetch-hotel-photos-content-api.py --dry-run --max-hotels 3 --file egypt_hotels_25.json --backend terra
  python3 bin/fetch-hotel-photos-content-api.py --file uae_hotels_25.json --backend terra

Quota (Mode A): ≤2 HTTP calls per hotel that needs API —
  0 if ta_location_id + tacdn already in photos[];
  1 search OR 1 photos if half-known;
  1 search + 1 photos if location unknown. No /locations/{id} details call
  (tripadvisor_url comes from search). No second search without geo.

Also loads (if present, gitignored): teya-memory/teya.env.local,
.tripadvisor.env.local in theme root or repo root.

Requires: pip install requests
"""

from __future__ import annotations

import argparse
import json
import os
import re
import sys
import time
from pathlib import Path
from typing import Any

from hotel_photos_quota import (
    hotel_has_mode_a,
    hotel_needs_api,
    print_audit_report,
    audit_hotel_files,
)

try:
    import requests
except ImportError:
    print("Install requests: pip install requests", file=sys.stderr)
    sys.exit(1)

_BIN_DIR = Path(__file__).resolve().parent
if str(_BIN_DIR) not in sys.path:
    sys.path.insert(0, str(_BIN_DIR))

THEME_ROOT = _BIN_DIR.parent
DATA_DIR = THEME_ROOT / "data"

DEFAULT_JSON_FILES = (
    "turkey_hotels_25.json",
    "egypt_hotels_25.json",
    "uae_hotels_25.json",
    "thailand_hotels_25.json",
    "vietnam_hotels_25.json",
    "china_hotels_25.json",
    "india_hotels_25.json",
    "maldives_hotels_25.json",
    "seychelles_hotels_25.json",
    "sri_lanka_hotels_25.json",
    "indonesia_hotels_25.json",
    "uzbekistan_hotels_25.json",
    "armenia_hotels_25.json",
    "russia_hotels_50.json",
    "abkhazia_hotels_25.json",
)

CONTENT_SEARCH_URL = "https://api.content.tripadvisor.com/api/v1/location/search"
CONTENT_PHOTOS_URL_TEMPLATE = "https://api.content.tripadvisor.com/api/v1/location/{location_id}/photos"
TERRA_BASE = "https://terra.tripadvisor.com"

STAR_RE = re.compile(r"\s*\d[+★].*$")

UAE_EMIRATE_GEO: dict[str, str] = {
    "dubai": "Dubai",
    "abu_dhabi": "Abu Dhabi",
    "sharjah": "Sharjah",
    "ajman": "Ajman",
    "ras_al_khaimah": "Ras Al Khaimah",
    "fujairah": "Fujairah",
    "umm_al_quwain": "Umm Al Quwain",
}

EGYPT_REGION_GEO: dict[str, str] = {
    "sharm": "Sharm El Sheikh",
    "hurghada": "Hurghada",
    "makadi": "Hurghada",
    "soma_bay": "Soma Bay",
    "sahl_hasheesh": "Hurghada",
}

# Fallback: resort text may name Makadi / Soma for hurghada rows.
EGYPT_RESORT_GEO_HINTS: tuple[tuple[str, str], ...] = (
    ("макади", "Makadi Bay"),
    ("makadi", "Makadi Bay"),
    ("сома", "Soma Bay"),
    ("soma bay", "Soma Bay"),
    ("сахл", "Hurghada"),
    ("sahl", "Hurghada"),
    ("эль-гун", "El Gouna"),
    ("gouna", "El Gouna"),
)

ENV_FILE_CANDIDATES = (
    THEME_ROOT / ".tripadvisor.env.local",
    THEME_ROOT.parents[3] / "teya-memory" / "teya.env.local",
    THEME_ROOT.parents[4] / "teya-memory" / "teya.env.local",
    Path("/workspace/teya-memory/teya.env.local"),
    Path("/workspace/.tripadvisor.env.local"),
)


def load_local_env_files() -> None:
    """Populate os.environ from teya.env.local / .tripadvisor.env.local (no overwrite)."""
    for path in ENV_FILE_CANDIDATES:
        if not path.is_file():
            continue
        try:
            for line in path.read_text(encoding="utf-8").splitlines():
                line = line.strip()
                if not line or line.startswith("#") or "=" not in line:
                    continue
                key, _, value = line.partition("=")
                key = key.strip()
                value = value.strip().strip("'\"")
                if key and key not in os.environ:
                    os.environ[key] = value
        except OSError as exc:
            print(f"WARN: could not read {path}: {exc}", file=sys.stderr)


def api_key() -> str:
    load_local_env_files()
    for name in (
        "ZHIRAF_TA_API_KEY",
        "TRIPADVISOR_API_KEY",
        "TRIPADVISOR_CONTENT_API_KEY",
    ):
        val = os.environ.get(name, "").strip()
        if val:
            return val
    return ""


def referer() -> str:
    return (
        os.environ.get("TRIPADVISOR_REFERER")
        or os.environ.get("ZHIRAF_TA_REFERER")
        or "https://zhiraf-ufa.ru"
    ).strip()


def headers() -> dict[str, str]:
    return {
        "accept": "application/json",
        "Referer": referer(),
    }


def clean_hotel_name(hotel_name: str) -> str:
    name = STAR_RE.sub("", hotel_name)
    return name.replace("★", "").strip()


def terra_geo_name(hotel: dict[str, Any]) -> str:
    country = str(hotel.get("country") or "").strip().lower()
    if country == "uae":
        emirate = str(hotel.get("emirate") or "").strip().lower()
        if emirate and emirate in UAE_EMIRATE_GEO:
            return UAE_EMIRATE_GEO[emirate]
    if country == "egypt":
        region = str(hotel.get("region") or "").strip().lower()
        if region and region in EGYPT_REGION_GEO:
            return EGYPT_REGION_GEO[region]
        resort_l = str(hotel.get("resort") or "").lower()
        for needle, geo in EGYPT_RESORT_GEO_HINTS:
            if needle in resort_l:
                return geo
    resort = str(hotel.get("resort") or "").strip()
    if not resort:
        return ""
    first = resort.split(",")[0].strip()
    if first.isascii():
        return first
    return ""


def terra_row_location_id(row: dict[str, Any]) -> str:
    loc = row.get("location") if isinstance(row.get("location"), dict) else row
    for candidate in (loc.get("id"), loc.get("location_id"), row.get("location_id"), row.get("id")):
        if candidate:
            return str(candidate)
    return ""


def terra_row_web_url(row: dict[str, Any]) -> str:
    loc = row.get("location") if isinstance(row.get("location"), dict) else row
    urls = loc.get("urls") if isinstance(loc.get("urls"), dict) else {}
    if not urls and isinstance(row.get("urls"), dict):
        urls = row["urls"]
    ta = urls.get("tripadvisor") if isinstance(urls.get("tripadvisor"), dict) else {}
    if isinstance(ta, dict):
        for key in ("main", "url", "property"):
            if ta.get(key):
                return str(ta[key]).strip()
    for key in ("web_url", "tripadvisor_url"):
        if loc.get(key):
            return str(loc[key]).strip()
        if row.get(key):
            return str(row[key]).strip()
    return ""


def photos_already_remote(photos: list[Any]) -> bool:
    """Deprecated alias — use hotel_has_mode_a on full row."""
    if not photos:
        return False
    first = photos[0]
    return isinstance(first, str) and first.lower().startswith(("http://", "https://"))


def terra_headers(key: str) -> dict[str, str]:
    return {
        "accept": "application/json",
        "X-API-Key": key,
    }


class ApiStats:
    """Terra/Content API call counter for quota reporting."""

    def __init__(self) -> None:
        self.requests = 0
        self.search = 0
        self.photos = 0
        self.details = 0
        self.other = 0
        self.hotels_touched = 0

    def track(self, response: requests.Response, kind: str = "other") -> None:
        self.requests += 1
        bucket = kind if kind in ("search", "photos", "details") else "other"
        setattr(self, bucket, getattr(self, bucket) + 1)


def content_search_location_id(
    session: requests.Session,
    hotel_name: str,
    resort: str,
    key: str,
    stats: ApiStats | None = None,
) -> str | None:
    query = f"{clean_hotel_name(hotel_name)} {resort}".strip()
    params = {
        "key": key,
        "searchQuery": query,
        "category": "hotels",
        "language": "ru",
    }
    try:
        response = session.get(CONTENT_SEARCH_URL, headers=headers(), params=params, timeout=15)
        if stats:
            stats.track(response, "search")
        if response.status_code != 200:
            return None
        data = response.json()
        items = data.get("data") or []
        if items:
            return str(items[0].get("location_id") or "")
    except requests.RequestException as exc:
        print(f"  search error: {exc}")
    return None


def terra_search_location(
    session: requests.Session,
    hotel_name: str,
    resort: str,
    key: str,
    stats: ApiStats | None = None,
    geo_name: str = "",
) -> dict[str, str] | None:
    clean = clean_hotel_name(hotel_name)
    geo = geo_name or (resort.split(",")[0].strip() if resort else "")
    params: dict[str, Any] = {"version": 1, "query": clean, "page": 1, "size": 5}
    if geo:
        params["geo_name"] = geo
    try:
        response = session.get(
            f"{TERRA_BASE}/api/catalog/locations/search",
            headers=terra_headers(key),
            params=params,
            timeout=20,
        )
        if stats:
            stats.track(response, "search")
        if response.status_code != 200:
            return None
        data = response.json()
        items = data.get("data") or []
        if not items:
            return None
        row = items[0]
        loc_id = terra_row_location_id(row)
        if not loc_id:
            return None
        web_url = terra_row_web_url(row)
        return {"id": loc_id, "web_url": web_url}
    except requests.RequestException as exc:
        print(f"  terra search error: {exc}")
    return None


def search_location_once(
    session: requests.Session,
    hotel_name: str,
    resort: str,
    key: str,
    backend: str,
    stats: ApiStats | None,
    geo_name: str,
) -> dict[str, str] | None:
    """Exactly one search call per hotel (no geo fallback, no terra→content retry)."""
    if backend == "content":
        loc_id = content_search_location_id(session, hotel_name, resort, key, stats)
        return {"id": loc_id, "web_url": ""} if loc_id else None
    return terra_search_location(session, hotel_name, resort, key, stats, geo_name)


def content_fetch_photo_urls(
    session: requests.Session,
    location_id: str,
    key: str,
    limit: int,
    stats: ApiStats | None = None,
) -> list[str]:
    url = CONTENT_PHOTOS_URL_TEMPLATE.format(location_id=location_id)
    params = {"key": key, "language": "ru", "limit": max(limit, 8)}
    urls: list[str] = []
    try:
        response = session.get(url, headers=headers(), params=params, timeout=15)
        if stats:
            stats.track(response, "photos")
        if response.status_code != 200:
            return urls
        data = response.json()
        for item in data.get("data") or []:
            images = item.get("images") or {}
            for size in ("original", "large", "medium"):
                block = images.get(size) or {}
                u = block.get("url")
                if u:
                    urls.append(u)
                    break
            if len(urls) >= limit:
                break
    except requests.RequestException as exc:
        print(f"  photos error: {exc}")
    return urls


def terra_fetch_photo_urls(
    session: requests.Session, location_id: str, key: str, limit: int, stats: ApiStats | None = None
) -> list[str]:
    urls: list[str] = []
    try:
        response = session.get(
            f"{TERRA_BASE}/api/locations/{location_id}/photos",
            headers=terra_headers(key),
            params={"version": 1, "page": 1, "size": max(limit, 12)},
            timeout=20,
        )
        if stats:
            stats.track(response, "photos")
        if response.status_code != 200:
            return urls
        data = response.json()
        for item in data.get("data") or []:
            images = item.get("images") or {}
            u = ""
            photo_block = item.get("photo") if isinstance(item.get("photo"), dict) else {}
            if photo_block.get("original_size_url"):
                u = str(photo_block["original_size_url"])
            elif photo_block.get("url"):
                u = str(photo_block["url"])
            if not u:
                for size in ("large", "original", "medium"):
                    block = images.get(size) or {}
                    u = block.get("url") or ""
                    if u:
                        break
            if not u and item.get("url"):
                u = str(item["url"])
            if u:
                urls.append(u)
            if len(urls) >= limit:
                break
    except requests.RequestException as exc:
        print(f"  terra photos error: {exc}")
    return urls[:limit]


def fetch_photo_urls(
    session: requests.Session,
    location_id: str,
    key: str,
    limit: int,
    backend: str,
    stats: ApiStats | None = None,
) -> list[str]:
    if backend == "content":
        return content_fetch_photo_urls(session, location_id, key, limit, stats)
    return terra_fetch_photo_urls(session, location_id, key, limit, stats)


def pick_unique_photo_urls(
    pool: list[str],
    location_id: str,
    used_lead_urls: set[str],
    location_pools: dict[str, list[str]],
    limit: int,
) -> list[str]:
    """Assign up to `limit` CDN URLs; dedupe lead image across hotels sharing a TA location."""
    if location_id not in location_pools:
        location_pools[location_id] = list(pool)
    merged = location_pools[location_id]
    for u in pool:
        if u not in merged:
            merged.append(u)
    picked: list[str] = []
    for u in merged:
        if u in picked:
            continue
        if not picked and u in used_lead_urls:
            continue
        picked.append(u)
        if len(picked) == 1:
            used_lead_urls.add(u)
        if len(picked) >= limit:
            break
    return picked


def process_file(
    path: Path,
    session: requests.Session,
    key: str,
    *,
    dry_run: bool,
    limit: int,
    sleep_s: float,
    force: bool,
    backend: str,
    stats: ApiStats | None = None,
    max_hotels: int | None = None,
) -> tuple[int, list[str]]:
    print(f"\n--- {path.name} ---")
    with path.open(encoding="utf-8") as fh:
        hotels = json.load(fh)
    if not isinstance(hotels, list):
        print("  skip: root is not a JSON array")
        return 0, []

    updated = 0
    not_found: list[str] = []
    used_lead_urls: set[str] = set()
    location_pools: dict[str, list[str]] = {}
    api_hotel_budget = 0
    skipped_mode_a = sum(
        1 for h in hotels if isinstance(h, dict) and not hotel_needs_api(h, force=force)
    )
    if skipped_mode_a:
        print(f"  skip Mode A complete: {skipped_mode_a}")

    effective_backend = backend
    if backend == "auto":
        effective_backend = "terra" if os.environ.get("ZHIRAF_TA_API_KEY", "").strip() else "content"

    for hotel in hotels:
        if not isinstance(hotel, dict):
            continue
        if max_hotels is not None and api_hotel_budget >= max_hotels:
            print(f"  --max-hotels {max_hotels} reached, stop file")
            break
        name = str(hotel.get("hotel_name") or "")
        resort = str(hotel.get("resort") or "")
        geo_name = terra_geo_name(hotel)
        current = hotel.get("photos") or []
        if not hotel_needs_api(hotel, force=force):
            continue

        print(f"  {name} ({resort})")
        existing_id = hotel.get("ta_location_id")
        loc_id = ""
        ta_url = str(hotel.get("tripadvisor_url") or hotel.get("ta_property_url") or "").strip()
        calls_before = stats.requests if stats else 0

        if existing_id:
            loc_id = str(existing_id)
        else:
            found = search_location_once(
                session, name, resort, key, effective_backend, stats, geo_name
            )
            if found:
                loc_id = found["id"]
                if found.get("web_url"):
                    ta_url = found["web_url"]
        if not loc_id:
            print("    location not found (≤1 search)")
            not_found.append(name)
            api_hotel_budget += 1
            if stats:
                stats.hotels_touched += 1
            time.sleep(sleep_s)
            continue

        pool = fetch_photo_urls(session, loc_id, key, limit, effective_backend, stats)
        photo_urls = pick_unique_photo_urls(pool, loc_id, used_lead_urls, location_pools, limit)
        if not photo_urls:
            print(f"    no photos for location {loc_id}")
            not_found.append(name)
            api_hotel_budget += 1
            if stats:
                stats.hotels_touched += 1
            time.sleep(sleep_s)
            continue

        used = (stats.requests - calls_before) if stats else (0 if existing_id else 2)
        print(f"    {len(photo_urls)} URL(s), ta={loc_id}, HTTP this hotel: {used}")
        api_hotel_budget += 1
        if stats:
            stats.hotels_touched += 1
        if not dry_run:
            old_photos = current if isinstance(current, list) else []
            local_names = [
                str(p)
                for p in old_photos
                if isinstance(p, str) and p and not p.lower().startswith(("http://", "https://"))
            ]
            if local_names:
                hotel["photo_files"] = local_names
            hotel["photos"] = photo_urls
            hotel["ta_location_id"] = int(loc_id)
            if ta_url:
                hotel["tripadvisor_url"] = ta_url
        updated += 1
        time.sleep(sleep_s)

    if not dry_run and updated:
        with path.open("w", encoding="utf-8") as fh:
            json.dump(hotels, fh, ensure_ascii=False, indent=2)
            fh.write("\n")
        print(f"  saved ({updated} hotels updated)")
    elif dry_run:
        print(f"  dry-run: would update {updated} hotels")
    if not_found:
        print(f"  not found / no photos: {len(not_found)}")

    return updated, not_found


def main() -> int:
    parser = argparse.ArgumentParser(description="Fetch Tripadvisor photo URLs into data/*.json")
    parser.add_argument("--file", action="append", dest="files", help="Process one data file (repeatable)")
    parser.add_argument("--dry-run", action="store_true", help="Do not write JSON files")
    parser.add_argument(
        "--audit",
        action="store_true",
        help="Offline: print Mode A stats per data file and exit (no API)",
    )
    parser.add_argument("--force", action="store_true", help="Replace photos even if URLs already present")
    parser.add_argument("--limit", type=int, default=3, help="Max CDN photos per hotel in JSON (default 3)")
    parser.add_argument(
        "--max-hotels",
        type=int,
        default=None,
        metavar="N",
        help="Process at most N hotels that need API per run (safe test: --max-hotels 3 --dry-run)",
    )
    parser.add_argument("--sleep", type=float, default=0.3, help="Delay between API calls in seconds")
    parser.add_argument(
        "--backend",
        choices=("auto", "terra", "content"),
        default="auto",
        help="Terra API (X-API-Key), legacy Content API, or auto (default)",
    )
    args = parser.parse_args()

    names = args.files if args.files else list(DEFAULT_JSON_FILES)
    paths = [DATA_DIR / name for name in names]

    if args.audit:
        existing = [p for p in paths if p.is_file()]
        print_audit_report(audit_hotel_files(existing))
        return 0

    key = api_key()
    if not key:
        print(
            "Set ZHIRAF_TA_API_KEY, TRIPADVISOR_API_KEY, or TRIPADVISOR_CONTENT_API_KEY "
            "(Cloud Agent secrets or teya-memory/teya.env.local).",
            file=sys.stderr,
        )
        return 1

    session = requests.Session()
    stats = ApiStats()
    total = 0
    all_not_found: list[str] = []
    for path in paths:
        if not path.is_file():
            print(f"Missing {path}, skip.")
            continue
        count, missing = process_file(
            path,
            session,
            key,
            dry_run=args.dry_run,
            limit=max(1, args.limit),
            sleep_s=max(0.0, args.sleep),
            force=args.force,
            backend=args.backend,
            stats=stats,
            max_hotels=args.max_hotels,
        )
        total += count
        all_not_found.extend(missing)

    print(f"\nDone. Hotels updated: {total}")
    print(
        f"Terra/Content HTTP: {stats.requests} total "
        f"(search={stats.search}, photos={stats.photos}, details={stats.details}, other={stats.other})"
    )
    print(f"Hotels with ≥1 API attempt: {stats.hotels_touched}")
    if stats.hotels_touched:
        print(f"Avg HTTP per touched hotel: {stats.requests / stats.hotels_touched:.2f}")
    if all_not_found:
        print(f"Hotels without CDN match ({len(all_not_found)}):")
        for name in all_not_found:
            print(f"  - {name}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
