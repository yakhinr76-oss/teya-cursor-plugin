#!/usr/bin/env python3
"""
Optional: enrich hotel JSON in data/ with Tripadvisor Content API photo URLs.

The WordPress import (import-hotels.php) sideloads images into the media library.
It accepts either placeholder basenames in photos[] or direct https:// URLs (see
import-hotels.php). Server-side import can also pull photos via Terra API when
ZHIRAF_TA_API_KEY is set — this script is for Content API credentials only.

Usage (from theme root):
  export TRIPADVISOR_API_KEY='...'
  export TRIPADVISOR_REFERER='https://yourdomain.ru'
  python3 bin/fetch-hotel-photos-content-api.py --dry-run
  python3 bin/fetch-hotel-photos-content-api.py --file turkey_hotels_25.json

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

try:
    import requests
except ImportError:
    print("Install requests: pip install requests", file=sys.stderr)
    sys.exit(1)

THEME_ROOT = Path(__file__).resolve().parent.parent
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

SEARCH_URL = "https://api.content.tripadvisor.com/api/v1/location/search"
PHOTOS_URL_TEMPLATE = "https://api.content.tripadvisor.com/api/v1/location/{location_id}/photos"

STAR_RE = re.compile(r"\s*\d[+★].*$")


def api_key() -> str:
    key = os.environ.get("TRIPADVISOR_API_KEY") or os.environ.get("TRIPADVISOR_CONTENT_API_KEY", "")
    return key.strip()


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


def photos_already_remote(photos: list[Any]) -> bool:
    if not photos:
        return False
    first = photos[0]
    return isinstance(first, str) and first.lower().startswith(("http://", "https://"))


def search_location_id(session: requests.Session, hotel_name: str, resort: str, key: str) -> str | None:
    query = f"{clean_hotel_name(hotel_name)} {resort}".strip()
    params = {
        "key": key,
        "searchQuery": query,
        "category": "hotels",
        "language": "ru",
    }
    try:
        response = session.get(SEARCH_URL, headers=headers(), params=params, timeout=15)
        if response.status_code != 200:
            return None
        data = response.json()
        items = data.get("data") or []
        if items:
            return str(items[0].get("location_id") or "")
    except requests.RequestException as exc:
        print(f"  search error: {exc}")
    return None


def fetch_photo_urls(session: requests.Session, location_id: str, key: str, limit: int) -> list[str]:
    url = PHOTOS_URL_TEMPLATE.format(location_id=location_id)
    params = {"key": key, "language": "ru", "limit": limit}
    urls: list[str] = []
    try:
        response = session.get(url, headers=headers(), params=params, timeout=15)
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


def process_file(
    path: Path,
    session: requests.Session,
    key: str,
    *,
    dry_run: bool,
    limit: int,
    sleep_s: float,
    force: bool,
) -> int:
    print(f"\n--- {path.name} ---")
    with path.open(encoding="utf-8") as fh:
        hotels = json.load(fh)
    if not isinstance(hotels, list):
        print("  skip: root is not a JSON array")
        return 0

    updated = 0
    for hotel in hotels:
        if not isinstance(hotel, dict):
            continue
        name = str(hotel.get("hotel_name") or "")
        resort = str(hotel.get("resort") or "")
        current = hotel.get("photos") or []
        if not force and photos_already_remote(current):
            continue

        print(f"  {name} ({resort})")
        loc_id = search_location_id(session, name, resort, key)
        if not loc_id:
            print("    location not found")
            time.sleep(sleep_s)
            continue

        photo_urls = fetch_photo_urls(session, loc_id, key, limit)
        if not photo_urls:
            print(f"    no photos for location {loc_id}")
            time.sleep(sleep_s)
            continue

        print(f"    {len(photo_urls)} URL(s)")
        if not dry_run:
            hotel["photos"] = photo_urls
            hotel["ta_location_id"] = loc_id
        updated += 1
        time.sleep(sleep_s)

    if not dry_run and updated:
        with path.open("w", encoding="utf-8") as fh:
            json.dump(hotels, fh, ensure_ascii=False, indent=2)
            fh.write("\n")
        print(f"  saved ({updated} hotels updated)")
    elif dry_run:
        print(f"  dry-run: would update {updated} hotels")

    return updated


def main() -> int:
    parser = argparse.ArgumentParser(description="Fetch Tripadvisor Content API photo URLs into data/*.json")
    parser.add_argument("--file", action="append", dest="files", help="Process one data file (repeatable)")
    parser.add_argument("--dry-run", action="store_true", help="Do not write JSON files")
    parser.add_argument("--force", action="store_true", help="Replace photos even if URLs already present")
    parser.add_argument("--limit", type=int, default=3, help="Max photos per hotel (default 3)")
    parser.add_argument("--sleep", type=float, default=0.3, help="Delay between API calls in seconds")
    args = parser.parse_args()

    key = api_key()
    if not key:
        print(
            "Set TRIPADVISOR_API_KEY or TRIPADVISOR_CONTENT_API_KEY in the environment.",
            file=sys.stderr,
        )
        return 1

    names = args.files if args.files else list(DEFAULT_JSON_FILES)
    paths = [DATA_DIR / name for name in names]

    session = requests.Session()
    total = 0
    for path in paths:
        if not path.is_file():
            print(f"Missing {path}, skip.")
            continue
        total += process_file(
            path,
            session,
            key,
            dry_run=args.dry_run,
            limit=max(1, args.limit),
            sleep_s=max(0.0, args.sleep),
            force=args.force,
        )

    print(f"\nDone. Hotels updated: {total}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
