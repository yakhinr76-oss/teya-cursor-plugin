#!/usr/bin/env python3
"""Create missing /tour/{slug}/ child pages on b2b-tour.ru via WP REST."""
from __future__ import annotations

import base64
import json
import os
import sys
import urllib.error
import urllib.request

SITE = "https://b2b-tour.ru"
USER = "site-admin"

# Must match zhiraf_ufa_destination_landing_slugs() on deployed theme.
DESTINATIONS: tuple[tuple[str, str], ...] = (
    ("turciya", "Турция"),
    ("oae", "ОАЭ"),
    ("egipet", "Египет"),
    ("tailand", "Таиланд"),
    ("vietnam", "Вьетнам"),
    ("kitaj", "Китай (Хайнань)"),
    ("indiya", "Индия"),
    ("shri-lanka", "Шри-Ланка"),
    ("indoneziya", "Индонезия"),
    ("maldivy", "Мальдивы"),
    ("sejshely", "Сейшелы"),
    ("uzbekistan", "Узбекистан"),
    ("armeniya", "Армения"),
    ("rossiya", "Россия"),
    ("abkhaziya", "Абхазия"),
)


def auth_header() -> str:
    password = os.environ.get("B2B_TOUR_WP_APP_PASSWORD", "").strip()
    if not password:
        print("BLOCKER: set B2B_TOUR_WP_APP_PASSWORD", file=sys.stderr)
        sys.exit(2)
    token = base64.b64encode(f"{USER}:{password}".encode()).decode()
    return f"Basic {token}"


def request(method: str, path: str, data: dict | None = None) -> dict | list:
    url = SITE.rstrip("/") + path
    headers = {"Authorization": auth_header(), "Accept": "application/json"}
    body = None
    if data is not None:
        body = json.dumps(data).encode("utf-8")
        headers["Content-Type"] = "application/json"
    req = urllib.request.Request(url, data=body, headers=headers, method=method)
    with urllib.request.urlopen(req, timeout=60) as resp:
        raw = resp.read().decode("utf-8")
        return json.loads(raw) if raw else {}


def tour_parent_id() -> int:
    pages = request("GET", "/wp-json/wp/v2/pages?slug=tour&per_page=1")
    if not pages:
        raise RuntimeError("Parent page /tour/ not found")
    return int(pages[0]["id"])


def child_exists(parent_id: int, slug: str) -> bool:
    pages = request(
        "GET",
        f"/wp-json/wp/v2/pages?parent={parent_id}&slug={slug}&per_page=1",
    )
    return bool(pages)


def ensure_child(parent_id: int, slug: str, title: str) -> None:
    if child_exists(parent_id, slug):
        print(f"OK exists: /tour/{slug}/")
        return
    created = request(
        "POST",
        "/wp-json/wp/v2/pages",
        {
            "title": title,
            "slug": slug,
            "status": "publish",
            "parent": parent_id,
        },
    )
    link = created.get("link", f"/tour/{slug}/")
    print(f"Created: {link} (id={created.get('id')})")


def main() -> int:
    parent = tour_parent_id()
    print(f"Parent /tour/ id={parent}")
    for slug, title in DESTINATIONS:
        ensure_child(parent, slug, title)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
