#!/usr/bin/env python3
"""Publish all Excalibur articles to b2b-tour.ru via WordPress REST API."""
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

ARTICLE_DIRS = [
    "deliverables/zhiraf-ufa/excalibur-articles/articles",
    "teya-memory/blog/articles",
]


def auth_header() -> str:
    password = os.environ.get("B2B_TOUR_WP_APP_PASSWORD", "").strip()
    if not password:
        print("BLOCKER: B2B_TOUR_WP_APP_PASSWORD not set", file=sys.stderr)
        sys.exit(2)
    token = base64.b64encode(f"{USER}:{password}".encode()).decode()
    return f"Basic {token}"


def request(method: str, path: str, data: dict | None = None):
    url = SITE.rstrip("/") + path
    headers = {"Authorization": auth_header(), "Accept": "application/json"}
    body = None
    if data is not None:
        body = json.dumps(data).encode("utf-8")
        headers["Content-Type"] = "application/json"
    req = urllib.request.Request(url, data=body, headers=headers, method=method)
    with urllib.request.urlopen(req, timeout=120) as resp:
        raw = resp.read().decode("utf-8")
        return json.loads(raw) if raw else {}


def find_articles_root(project_root: Path) -> Path | None:
    for rel in ARTICLE_DIRS:
        root = project_root / rel
        if root.is_dir() and any(root.iterdir()):
            return root
    return None


def publish_one(articles_root: Path, slug_dir: Path) -> dict:
    meta_path = slug_dir / "article.meta.json"
    html_path = slug_dir / "article.html"
    if not meta_path.is_file() or not html_path.is_file():
        raise FileNotFoundError(slug_dir)
    meta = json.loads(meta_path.read_text(encoding="utf-8"))
    content = html_path.read_text(encoding="utf-8")
    slug = meta.get("slug", slug_dir.name)
    title = meta.get("meta_ab", {}).get("title_seo", slug).split("|")[0].strip()
    excerpt = meta.get("meta_ab", {}).get("description_seo", "")
    existing = request("GET", f"/wp-json/wp/v2/posts?slug={slug}&per_page=1")
    payload = {
        "title": title,
        "slug": slug,
        "status": "publish",
        "content": content,
        "excerpt": excerpt,
    }
    if existing:
        return request("POST", f"/wp-json/wp/v2/posts/{existing[0]['id']}", payload)
    return request("POST", "/wp-json/wp/v2/posts", payload)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--project-root", type=Path, default=Path.cwd())
    args = parser.parse_args()
    root = args.project_root.resolve()
    articles_root = find_articles_root(root)
    if not articles_root:
        print("No articles directory found", file=sys.stderr)
        return 1

    me = request("GET", "/wp-json/wp/v2/users/me")
    print(f"Auth OK: {me.get('slug')}")

    published = []
    for slug_dir in sorted(articles_root.iterdir()):
        if not slug_dir.is_dir():
            continue
        try:
            post = publish_one(articles_root, slug_dir)
            published.append(post.get("slug"))
            print(f"OK: {post.get('slug')} -> {post.get('link')}")
        except Exception as exc:
            print(f"FAIL {slug_dir.name}: {exc}", file=sys.stderr)

    print(f"\nPublished/updated: {len(published)} posts")
    return 0 if published else 1


if __name__ == "__main__":
    raise SystemExit(main())
