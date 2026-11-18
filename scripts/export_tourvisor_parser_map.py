#!/usr/bin/env python3
"""Export flat hotel list for the Tourvisor price parser from theme JSON maps."""

from __future__ import annotations

import argparse
import json
import sys
from pathlib import Path

REPO = Path(__file__).resolve().parents[1]
THEME_DATA = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data"

TV_COUNTRY_ID = {
    "turkey": 4,
    "uae": 9,
    "egypt": 1,
    "thailand": 2,
    "vietnam": 16,
    "maldives": 8,
}

GITHUB_RAW = (
    "https://raw.githubusercontent.com/yakhinr76-oss/teya-cursor-plugin/{ref}/"
    "deliverables/zhiraf-ufa/tourvisor-parser-dropin/output/hotels_map_{slug}.json"
)

MAPS: dict[str, Path] = {
    "turkey": THEME_DATA / "turkey-tourvisor-hotels.json",
    "uae": THEME_DATA / "uae-tourvisor-hotels.json",
    "egypt": THEME_DATA / "egypt-tourvisor-hotels.json",
    "thailand": THEME_DATA / "thailand-tourvisor-hotels.json",
    "vietnam": THEME_DATA / "vietnam-tourvisor-hotels.json",
    "maldives": THEME_DATA / "maldives-tourvisor-hotels.json",
}


def load_map(country: str) -> dict:
    path = MAPS.get(country)
    if not path or not path.is_file():
        raise SystemExit(f"Map not found for {country}: {path}")
    return json.loads(path.read_text(encoding="utf-8"))


def parser_hotels_map(doc: dict) -> dict:
    """Shape for local parser output/hotels_map_*.json (fetch-by-map, join on hotelcode)."""
    slug = str(doc.get("country_slug", ""))
    rows: list[dict] = []
    by_tv: dict[str, dict] = {}
    ids: list[int] = []
    for h in doc.get("hotels") or []:
        if not isinstance(h, dict):
            continue
        tid = int(h.get("tourvisor_id") or 0)
        zid = int(h.get("zhiraf_id") or 0)
        if zid <= 0 or tid <= 0:
            continue
        place = str(h.get("resort") or h.get("tourvisor_region") or "")
        row = {
            "zhiraf_id": zid,
            "country_slug": slug,
            "tourvisor_id": tid,
            "hotel_name": str(h.get("hotel_name") or ""),
            "place": place,
            "search_query": str(h.get("search_query") or ""),
            "tourvisor_name": str(h.get("tourvisor_name") or ""),
            "price_key": f"{slug}:{zid}",
        }
        rows.append(row)
        by_tv[str(tid)] = row
        ids.append(tid)
    ids.sort()
    return {
        "schema": "zhiraf.tourvisor-parser.hotels_map.v1",
        "country_slug": slug,
        "tourvisor_country_id": doc.get("tourvisor_country_id"),
        "identified_at": doc.get("identified_at"),
        "departure_city": "Уфа",
        "hotel_count": len(rows),
        "hotel_ids": ",".join(str(i) for i in ids),
        "hotels": rows,
        "by_tourvisor_id": by_tv,
    }


def flatten(doc: dict) -> list[dict]:
    rows: list[dict] = []
    for h in doc.get("hotels") or []:
        if not isinstance(h, dict):
            continue
        tid = int(h.get("tourvisor_id") or 0)
        zid = int(h.get("zhiraf_id") or 0)
        if zid <= 0:
            continue
        rows.append(
            {
                "zhiraf_id": zid,
                "tourvisor_id": tid,
                "hotel_name": h.get("hotel_name", ""),
                "search_query": h.get("search_query", ""),
                "tourvisor_name": h.get("tourvisor_name", ""),
                "resort": h.get("resort", ""),
                "region": h.get("region", ""),
                "match_status": h.get("match_status", ""),
                "price_key": f"{doc.get('country_slug', 'turkey')}:{zid}",
                "search_ok": tid > 0,
            }
        )
    return rows


def main() -> None:
    parser = argparse.ArgumentParser(description="Export parser-friendly Tourvisor hotel map")
    parser.add_argument(
        "--country",
        choices=sorted(MAPS),
        action="append",
        dest="countries",
        help="Repeat for multiple countries; default all with valid maps",
    )
    parser.add_argument(
        "--out",
        type=Path,
        help="Write JSON file; default stdout",
    )
    parser.add_argument(
        "--flat",
        action="store_true",
        help="Single list under \"hotels\" (for parsers that iterate one array)",
    )
    parser.add_argument(
        "--parser-dropin",
        type=Path,
        metavar="DIR",
        help="Write output/hotels_map_{country}.json for local «parser tourvisor» (DIR=output parent)",
    )
    args = parser.parse_args()
    countries = args.countries or sorted(MAPS)

    if args.flat:
        flat_rows: list[dict] = []
        for c in countries:
            doc = load_map(c)
            slug = str(doc.get("country_slug", c))
            for row in flatten(doc):
                flat_rows.append(
                    {
                        **row,
                        "country_slug": slug,
                        "tourvisor_country_id": doc.get("tourvisor_country_id"),
                    }
                )
        payload = {
            "schema": "zhiraf.tourvisor-parser-map.flat.v1",
            "countries": countries,
            "hotel_count": len(flat_rows),
            "hotels": flat_rows,
        }
    else:
        payload = {
            "schema": "zhiraf.tourvisor-parser-map.export.v1",
            "countries": {},
        }
        for c in countries:
            doc = load_map(c)
            rows = flatten(doc)
            payload["countries"][c] = {
                "country_slug": doc.get("country_slug", c),
                "tourvisor_country_id": doc.get("tourvisor_country_id"),
                "identified_at": doc.get("identified_at"),
                "webhook_price_prefix": f"{doc.get('country_slug', c)}:",
                "hotel_count": len(rows),
                "search_ready_count": sum(1 for r in rows if r["search_ok"]),
                "hotels": rows,
            }

    if args.parser_dropin:
        out_dir = args.parser_dropin
        out_dir.mkdir(parents=True, exist_ok=True)
        for c in countries:
            doc = load_map(c)
            slug = str(doc.get("country_slug", c))
            path = out_dir / f"hotels_map_{slug}.json"
            path.write_text(
                json.dumps(parser_hotels_map(doc), ensure_ascii=False, indent=2) + "\n",
                encoding="utf-8",
            )
            print(f"Wrote {path}", file=sys.stderr)
        snippet = {
            "schema": "zhiraf.tourvisor-parser.hotels_config.snippet.v1",
            "note": "Merge into local hotels_config.json. hotels_map_url — скачать с GitHub без копипаста после merge в main.",
            "github_ref": "main",
            "countries": {},
        }
        for c in countries:
            doc = load_map(c)
            slug = str(doc.get("country_slug", c))
            tv_id = int(doc.get("tourvisor_country_id") or 0) or TV_COUNTRY_ID.get(slug, 0)
            snippet["countries"][c] = {
                "enabled": True,
                "country_slug": slug,
                "tourvisor_country_id": tv_id,
                "hotels_map": f"output/hotels_map_{slug}.json",
                "hotels_map_url": GITHUB_RAW.format(ref="main", slug=slug),
            }
        snippet_path = out_dir.parent / "hotels_config.countries.snippet.json"
        snippet_path.write_text(json.dumps(snippet, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")
        print(f"Wrote {snippet_path}", file=sys.stderr)
        return

    text = json.dumps(payload, ensure_ascii=False, indent=2) + "\n"
    if args.out:
        args.out.parent.mkdir(parents=True, exist_ok=True)
        args.out.write_text(text, encoding="utf-8")
        print(f"Wrote {args.out}", file=sys.stderr)
    else:
        sys.stdout.write(text)


if __name__ == "__main__":
    main()