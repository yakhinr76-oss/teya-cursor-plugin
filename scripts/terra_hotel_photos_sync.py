#!/usr/bin/env python3
"""One-time Terra photo fetch → JSON manifest for WP (CDN URLs, link to Tripadvisor)."""
from __future__ import annotations

import argparse
import csv
import json
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

from terra_api import project_root, resolve_api_key, terra_get
from terra_photos import (
    location_display_name,
    normalize_photo_entries,
    pick_photos_for_display,
    tripadvisor_property_url,
)


def _iso_now() -> str:
    return datetime.now(timezone.utc).replace(microsecond=0).isoformat().replace("+00:00", "Z")


def load_input_rows(path: Path) -> list[dict[str, str]]:
    if path.suffix.lower() == ".json":
        data = json.loads(path.read_text(encoding="utf-8"))
        if isinstance(data, list):
            return [dict(row) for row in data]
        if isinstance(data, dict) and "hotels" in data:
            return [dict(row) for row in data["hotels"]]
        raise ValueError("JSON must be a list or {hotels: [...]}")
    rows: list[dict[str, str]] = []
    with path.open(encoding="utf-8", newline="") as handle:
        reader = csv.DictReader(handle)
        for row in reader:
            rows.append({k: (v or "").strip() for k, v in row.items()})
    return rows


def sync_hotel(
    *,
    api_key: str,
    location_id: int,
    slug: str,
    tripadvisor_url: str,
    photo_limit: int,
    management_first: bool,
) -> dict[str, Any]:
    loc_status, loc_body = terra_get(f"/locations/{location_id}", api_key=api_key, params={})
    if loc_status >= 400 or not isinstance(loc_body, dict):
        return {
            "slug": slug,
            "location_id": location_id,
            "status": "error",
            "http_status": loc_status,
            "error": loc_body if isinstance(loc_body, (dict, str)) else "location fetch failed",
        }

    ta_url = tripadvisor_url or tripadvisor_property_url(loc_body)
    ph_status, ph_body = terra_get(
        f"/locations/{location_id}/photos",
        api_key=api_key,
        params={"page": 1, "size": max(photo_limit, 5)},
    )
    if ph_status >= 400 or not isinstance(ph_body, dict):
        return {
            "slug": slug,
            "location_id": location_id,
            "tripadvisor_url": ta_url,
            "name": location_display_name(loc_body),
            "status": "error",
            "http_status": ph_status,
            "error": ph_body if isinstance(ph_body, (dict, str)) else "photos fetch failed",
        }

    all_photos = normalize_photo_entries(ph_body)
    photos = pick_photos_for_display(all_photos, limit=photo_limit, management_first=management_first)
    lead = photos[0]["cdn_url"] if photos else ""

    return {
        "slug": slug,
        "location_id": location_id,
        "name": location_display_name(loc_body),
        "tripadvisor_url": ta_url,
        "lead_photo_url": lead,
        "photos": photos,
        "fetched_at": _iso_now(),
        "status": "ok" if photos else "no_photos",
        "billable_entities_estimate": 2,
    }


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Fetch Terra hotel photos once; write CDN URLs for WP embed (no re-host)."
    )
    parser.add_argument("--project-root", type=Path, default=None)
    parser.add_argument(
        "--output",
        type=Path,
        default=None,
        help="Default: <project-root>/teya-memory/wp/ta-hotel-photos.json",
    )
    parser.add_argument("--photo-limit", type=int, default=3, help="Max photos per hotel (display)")
    parser.add_argument(
        "--all-sources",
        action="store_true",
        help="Do not prefer Management photos first",
    )
    group = parser.add_mutually_exclusive_group(required=True)
    group.add_argument("--location-id", type=int)
    group.add_argument("--input", type=Path, help="CSV or JSON list: location_id, slug [, tripadvisor_url]")
    parser.add_argument("--slug", type=str, help="Required with --location-id")
    args = parser.parse_args()

    try:
        api_key, source = resolve_api_key(root=args.project_root)
    except RuntimeError as exc:
        print(str(exc), file=sys.stderr)
        return 2

    root = project_root(args.project_root)
    out = args.output or (root / "teya-memory" / "wp" / "ta-hotel-photos.json")
    out.parent.mkdir(parents=True, exist_ok=True)

    jobs: list[dict[str, str]] = []
    if args.location_id is not None:
        if not args.slug:
            print("--slug required with --location-id", file=sys.stderr)
            return 2
        jobs.append({"location_id": str(args.location_id), "slug": args.slug, "tripadvisor_url": ""})
    else:
        jobs = load_input_rows(args.input)

    results: list[dict[str, Any]] = []
    errors = 0
    for row in jobs:
        loc_raw = row.get("location_id") or row.get("id") or ""
        slug = row.get("slug") or row.get("post_slug") or ""
        if not loc_raw or not slug:
            errors += 1
            results.append({"slug": slug or "?", "status": "error", "error": "missing location_id or slug"})
            continue
        item = sync_hotel(
            api_key=api_key,
            location_id=int(loc_raw),
            slug=slug,
            tripadvisor_url=row.get("tripadvisor_url") or row.get("ta_url") or "",
            photo_limit=max(1, min(args.photo_limit, 10)),
            management_first=not args.all_sources,
        )
        if item.get("status") != "ok":
            errors += 1
        results.append(item)

    manifest = {
        "generated_at": _iso_now(),
        "key_source": source,
        "photo_policy": "cdn_embed_link_to_tripadvisor_no_rehost",
        "billable_entities_note": "≈2 per hotel (location + photos) on first sync",
        "hotels": results,
    }
    out.write_text(json.dumps(manifest, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")
    print(f"wrote {out}")
    print(f"hotels: {len(results)}, errors: {errors}")
    return 1 if errors else 0


if __name__ == "__main__":
    raise SystemExit(main())
