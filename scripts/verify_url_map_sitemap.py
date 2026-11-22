#!/usr/bin/env python3
"""Diff 06-url-map.csv indexable URLs vs theme routing (mirrors inc/sitemaps.php)."""
from __future__ import annotations

import csv
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
CSV_PATH = ROOT / "teya-memory/semantic-core/zhiraf-ufa-2026/06-url-map.csv"
THEME = ROOT / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa"


def norm(path: str) -> str:
    path = (path or "").strip()
    if not path:
        return ""
    if not path.startswith("/"):
        path = "/" + path
    if not path.endswith("/"):
        path += "/"
    return path


def parse_php_string_array(content: str, fn_name: str) -> list[str]:
    m = re.search(
        rf"function\s+{re.escape(fn_name)}\s*\(\s*\)\s*\{{\s*return\s+array\s*\((.*?)\)\s*;",
        content,
        re.DOTALL,
    )
    if not m:
        return []
    inner = m.group(1)
    return [s.strip() for s in re.findall(r"'([a-z0-9\-]+)'", inner)]


def parse_resort_index(content: str) -> list[str]:
    paths: list[str] = []
    for country, resort in re.findall(
        r"'country'\s*=>\s*'([^']+)',\s*'resort'\s*=>\s*'([^']+)'", content
    ):
        paths.append(norm(f"/tour/{country}/{resort}/"))
    return paths


def parse_retired_redirects(redirects_php: str) -> set[str]:
    block = set()
    for path in re.findall(r"'(/tour/[^']+/)'\s*=>", redirects_php):
        block.add(norm(path))
    return block


def theme_indexable_paths() -> set[str]:
    dest_php = (THEME / "inc/destination-landings.php").read_text(encoding="utf-8")
    resort_php = (THEME / "inc/resort-landings.php").read_text(encoding="utf-8")
    redirects_php = (THEME / "inc/redirects.php").read_text(encoding="utf-8")
    sitemaps_php = (THEME / "inc/sitemaps.php").read_text(encoding="utf-8")

    backlog = set(parse_php_string_array(sitemaps_php, "zhiraf_ufa_sitemap_backlog_destination_slugs"))
    dest_slugs = parse_php_string_array(dest_php, "zhiraf_ufa_destination_landing_slugs")
    retired = parse_retired_redirects(redirects_php)

    paths = {norm("/goryashchie-tury/"), norm("/vse-vklyucheno/")}
    for slug in dest_slugs:
        if slug in backlog:
            continue
        paths.add(norm(f"/tour/{slug}/"))
    paths.update(parse_resort_index(resort_php))
    return {p for p in paths if p not in retired}


def csv_indexable_paths() -> set[str]:
    indexable: set[str] = set()
    with CSV_PATH.open(encoding="utf-8", newline="") as f:
        for row in csv.DictReader(f):
            status = (row.get("url_status") or "").strip().lower()
            if status != "create":
                continue
            indexable.add(norm(row.get("target_url", "")))
    return indexable


def main() -> int:
    if not CSV_PATH.is_file():
        print(f"BLOCKER: missing {CSV_PATH}", file=sys.stderr)
        return 1
    csv_urls = csv_indexable_paths()
    theme_urls = theme_indexable_paths()

    only_csv = sorted(csv_urls - theme_urls)
    only_theme = sorted(theme_urls - csv_urls)

    print(f"CSV indexable (url_status=create): {len(csv_urls)}")
    print(f"Theme routing indexable:           {len(theme_urls)}")
    print()
    if only_csv:
        print("In CSV, not in theme/sitemap provider:")
        for u in only_csv:
            print(f"  - {u}")
    else:
        print("In CSV, not in theme: (none)")
    print()
    if only_theme:
        print("In theme/sitemap, not in CSV (create):")
        for u in only_theme:
            print(f"  - {u}")
    else:
        print("In theme, not in CSV: (none)")
    return 1 if (only_csv or only_theme) else 0


if __name__ == "__main__":
    sys.exit(main())