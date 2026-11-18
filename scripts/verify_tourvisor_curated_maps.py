#!/usr/bin/env python3
"""CI check: curated Tourvisor map JSON + parser drop-in (no network, no secrets)."""

from __future__ import annotations

import json
import sys
from pathlib import Path

REPO = Path(__file__).resolve().parents[1]
THEME_DATA = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data"
DROPIN = REPO / "deliverables/zhiraf-ufa/tourvisor-parser-dropin/output"

TV_COUNTRY_ID = {"turkey": 4, "uae": 9, "egypt": 1, "thailand": 2, "vietnam": 16}

MAPS = {
    slug: (THEME_DATA / f"{slug}-tourvisor-hotels.json", TV_COUNTRY_ID[slug])
    for slug in TV_COUNTRY_ID
}

# Countries that must have 25/25 tourvisor_id before CI fails (Thailand may stay scaffold).
STRICT_READY = {"turkey", "uae", "egypt", "vietnam"}


def check_theme_map(slug: str, path: Path, expected_tv_country: int) -> list[str]:
    errs: list[str] = []
    if not path.is_file():
        return [f"{slug}: missing {path.relative_to(REPO)}"]
    doc = json.loads(path.read_text(encoding="utf-8"))
    hotels = doc.get("hotels") or []
    if 25 != len(hotels):
        errs.append(f"{slug}: expected 25 hotels, got {len(hotels)}")
    tv_c = int(doc.get("tourvisor_country_id") or 0)
    if slug in STRICT_READY and tv_c != expected_tv_country:
        errs.append(f"{slug}: tourvisor_country_id {tv_c} != {expected_tv_country}")
    zids = []
    ready = 0
    for h in hotels:
        zid = int(h.get("zhiraf_id") or 0)
        tid = int(h.get("tourvisor_id") or 0)
        if tid > 0:
            ready += 1
        elif slug in STRICT_READY:
            errs.append(f"{slug}: zhiraf_id={zid} has tourvisor_id=0")
        zids.append(zid)
    if slug in STRICT_READY and ready != 25:
        errs.append(f"{slug}: only {ready}/25 tourvisor_id filled")
    if zids != list(range(1, 26)):
        errs.append(f"{slug}: zhiraf_id not 1..25 in order")
    return errs


def check_dropin(slug: str) -> list[str]:
    path = DROPIN / f"hotels_map_{slug}.json"
    errs: list[str] = []
    if not path.is_file():
        return [f"dropin: missing hotels_map_{slug}.json"]
    doc = json.loads(path.read_text(encoding="utf-8"))
    if doc.get("country_slug") != slug:
        errs.append(f"dropin {slug}: country_slug mismatch")
    theme_path = MAPS[slug][0]
    theme = json.loads(theme_path.read_text(encoding="utf-8"))
    expected = sum(1 for h in theme.get("hotels") or [] if int(h.get("tourvisor_id") or 0) > 0)
    got = int(doc.get("hotel_count") or 0)
    if got != expected:
        errs.append(f"dropin {slug}: hotel_count {got} != theme ready {expected}")
    return errs


def main() -> int:
    errors: list[str] = []
    for slug, (path, tv_id) in MAPS.items():
        errors.extend(check_theme_map(slug, path, tv_id))
        errors.extend(check_dropin(slug))
    if errors:
        for e in errors:
            print(e, file=sys.stderr)
        return 1
    print(f"OK: {len(MAPS)} curated maps + drop-in ({', '.join(MAPS)})")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())