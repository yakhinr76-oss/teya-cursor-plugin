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


def _article_paths(project_root: Path, slug: str) -> tuple[Path, Path]:
    candidates = [
        (
            project_root / f"deliverables/zhiraf-ufa/excalibur-articles/articles/{slug}/article.meta.json",
            project_root / f"deliverables/zhiraf-ufa/excalibur-articles/articles/{slug}/article.html",
        ),
        (
            project_root / f"teya-memory/blog/articles/{slug}/article.meta.json",
            project_root / f"teya-memory/blog/articles/{slug}/article.html",
        ),
    ]
    for meta_path, html_path in candidates:
        if meta_path.is_file() and html_path.is_file():
            return meta_path, html_path
    return candidates[0][0], candidates[0][1]


def publish_excalibur_post(project_root: Path, slug: str = "kak-kupit-tur-v-ufe") -> dict | None:
    meta_path, html_path = _article_paths(project_root, slug)
    if not meta_path.is_file() or not html_path.is_file():
        print(f"WARN: Excalibur article {slug} not found, skipping post")
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


def trash_default_hello_post() -> None:
    """Remove WP default «Привет, мир!» / hello-world if still present."""
    for slug in ("hello-world", "privet-mir"):
        existing = request("GET", f"/wp-json/wp/v2/posts?slug={slug}&status=any&per_page=1")
        if not existing:
            continue
        post = existing[0]
        request("DELETE", f"/wp-json/wp/v2/posts/{post['id']}?force=true")
        print(f"Trashed default post: {slug} (id={post['id']})")


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--project-root", type=Path, default=Path.cwd())
    args = parser.parse_args()
    root = args.project_root.resolve()

    print("Checking REST auth…")
    me = request("GET", "/wp-json/wp/v2/users/me")
    print(f"Authenticated as: {me.get('name')} ({me.get('slug')})")

    print("Setting blog_public=0 (noindex staging) and site_logo=2671…")
    request(
        "POST",
        "/wp-json/wp/v2/settings",
        {"blog_public": False, "site_logo": 2671},
    )

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

    for slug_dir in sorted((root / "deliverables/zhiraf-ufa/excalibur-articles/articles").glob("*")):
        if slug_dir.is_dir():
            post = publish_excalibur_post(root, slug_dir.name)
            if post:
                print(f"Post OK: {post.get('link')}")

    print(
        "\nMANUAL: Upload & activate theme zhiraf-ufa in WP Admin → Внешний вид → Темы, "
        "then set static front page and posts page /blog/."
    )
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
