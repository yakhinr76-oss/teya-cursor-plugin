#!/usr/bin/env python3
"""Bootstrap Zhiraf theme content on https://b2b-tour.ru via WordPress REST API.

Requires:
  B2B_TOUR_WP_APP_PASSWORD — application password for user site-admin

Theme files must already exist on the server under wp-content/themes/zhiraf-ufa/
(upload via hosting panel / SFTP from deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/).

This script:
  - Sets blog_public=0 (discourage indexing) via /wp/v2/settings
  - Creates/updates pages and blog post from teya-memory artifacts
  - Attempts to note theme activation (manual step if REST cannot switch theme)
"""
from __future__ import annotations

import argparse
import base64
import json
import os
import sys
import urllib.error
import urllib.request
from pathlib import Path

SITE = "https://b2b-tour.ru"
USER = "site-admin"


def auth_header() -> str:
    password = os.environ.get("B2B_TOUR_WP_APP_PASSWORD", "").strip()
    if not password:
        print("BLOCKER: set B2B_TOUR_WP_APP_PASSWORD", file=sys.stderr)
        sys.exit(2)
    token = base64.b64encode(f"{USER}:{password}".encode()).decode()
    return f"Basic {token}"


def request(method: str, path: str, data: dict | None = None) -> dict:
    url = SITE.rstrip("/") + path
    body = None
    headers = {"Authorization": auth_header(), "Accept": "application/json"}
    if data is not None:
        body = json.dumps(data).encode("utf-8")
        headers["Content-Type"] = "application/json"
    req = urllib.request.Request(url, data=body, headers=headers, method=method)
    try:
        with urllib.request.urlopen(req, timeout=60) as resp:
            raw = resp.read().decode("utf-8")
            return json.loads(raw) if raw else {}
    except urllib.error.HTTPError as exc:
        err = exc.read().decode("utf-8", errors="replace")
        raise RuntimeError(f"{method} {path} -> {exc.code}: {err}") from exc


def find_page_by_slug(slug: str) -> dict | None:
    items = request("GET", f"/wp-json/wp/v2/pages?slug={slug}&per_page=1")
    return items[0] if items else None


def upsert_page(slug: str, title: str, status: str = "publish") -> dict:
    existing = find_page_by_slug(slug)
    payload = {"title": title, "slug": slug, "status": status}
    if existing:
        return request("POST", f"/wp-json/wp/v2/pages/{existing['id']}", payload)
    return request("POST", "/wp-json/wp/v2/pages", payload)


def publish_excalibur_post(project_root: Path) -> dict | None:
    meta_path = project_root / "teya-memory/blog/articles/kak-kupit-tur-v-ufe/article.meta.json"
    html_path = project_root / "teya-memory/blog/articles/kak-kupit-tur-v-ufe/article.html"
    if not meta_path.is_file() or not html_path.is_file():
        print("WARN: Excalibur article not found, skipping post")
        return None
    meta = json.loads(meta_path.read_text(encoding="utf-8"))
    content = html_path.read_text(encoding="utf-8")
    slug = meta["slug"]
    title = meta["meta_ab"]["title_seo"].split("|")[0].strip()
    existing = request("GET", f"/wp-json/wp/v2/posts?slug={slug}&per_page=1")
    payload = {
        "title": title,
        "slug": slug,
        "status": "publish",
        "content": content,
        "excerpt": meta["meta_ab"]["description_seo"],
    }
    if existing:
        return request("POST", f"/wp-json/wp/v2/posts/{existing[0]['id']}", payload)
    return request("POST", "/wp-json/wp/v2/posts", payload)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--project-root", type=Path, default=Path.cwd())
    args = parser.parse_args()
    root = args.project_root.resolve()

    print("Checking REST auth…")
    me = request("GET", "/wp-json/wp/v2/users/me")
    print(f"Authenticated as: {me.get('name')} ({me.get('slug')})")

    print("Setting blog_public=0 (noindex staging)…")
    request("POST", "/wp-json/wp/v2/settings", {"blog_public": False})

    pages = [
        ("o-nas", "О нас"),
        ("kontakty", "Контакты"),
        ("tour", "Туры"),
        ("politika-konfidenczialnosti", "Политика конфиденциальности"),
        ("politika-cookies", "Политика cookies"),
    ]
    for slug, title in pages:
        upsert_page(slug, title)
        print(f"Page OK: /{slug}/")

    post = publish_excalibur_post(root)
    if post:
        print(f"Post OK: /blog/{post.get('slug')}/")

    print(
        "\nMANUAL: Upload & activate theme zhiraf-ufa in WP Admin → Внешний вид → Темы, "
        "then set static front page and posts page /blog/."
    )
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
