#!/usr/bin/env python3
"""Live check for hero fullbleed deploy."""
from __future__ import annotations

import json
import re
import urllib.error
import urllib.request

PUBLIC = "https://zhiraf-b2b.ru/"
THEME = "ecotravelsystem"


def fetch(url: str, *, redirects: bool = True) -> tuple[int, str, str, int]:
    req = urllib.request.Request(url, headers={"User-Agent": "TeyaAuroraDeploy/1.0"})
    if not redirects:

        class NoRedirect(urllib.request.HTTPRedirectHandler):
            def redirect_request(self, req, fp, code, msg, headers, newurl):  # noqa: ANN001
                return None

        opener = urllib.request.build_opener(NoRedirect)
    else:
        opener = urllib.request.build_opener()
    try:
        with opener.open(req, timeout=60) as resp:
            body = resp.read().decode("utf-8", errors="replace")
            return resp.status, resp.geturl(), body, len(body)
    except urllib.error.HTTPError as exc:
        body = exc.read().decode("utf-8", errors="replace")
        return exc.code, exc.geturl(), body, len(body)


def main() -> None:
    https_s, https_f, html, https_len = fetch(PUBLIC)
    http_s, http_f, _, _ = fetch(PUBLIC.replace("https://", "http://"), redirects=False)
    css_s, _, _, css_len = fetch(f"{PUBLIC}wp-content/themes/{THEME}/style.css")
    dist_css_s, _, dist_css_body, dist_css_len = fetch(f"{PUBLIC}wp-content/themes/{THEME}/assets/dist/style.css")
    wpjson_s, _, _, wpjson_len = fetch(f"{PUBLIC}wp-json/")

    checks = {
        "https_homepage": {"status": https_s, "final": https_f, "body_len": https_len},
        "http_homepage": {"status": http_s, "final": http_f},
        "theme_css": {"status": css_s, "bytes": css_len},
        "dist_css": {"status": dist_css_s, "bytes": dist_css_len},
        "wp_json": {"status": wpjson_s, "bytes": wpjson_len},
        "theme_in_html": THEME in html,
        "hero_fullbleed_class": "hero--fullbleed" in html,
        "hero_fullbleed_media": "hero--fullbleed__media" in html,
        "trust_bar_white": "hero-trust-bar--white" in html,
        "cta_primary": "Получить персональное предложение" in html,
        "cta_secondary": "Узнать больше" in html,
        "hero_title": "Организация командировок для бизнеса" in html,
        "blog_cover_optimizatsiya": "blog-cover-optimizatsiya-byudzheta-komandirovok.png" in html,
        "blog_cover_trevel": "blog-cover-trevel-dokumenty-dlya-buhgalterii.png" in html,
        "blog_cover_trendy": "blog-cover-trendy-delovogo-turizma-2026.png" in html,
        "hero_fullbleed_img_url": bool(re.search(r"hero-ecotravelsystem-fullbleed[^\"']*\.png", html)),
        "css_min_height_fullbleed": "min-height" in dist_css_body and "hero--fullbleed" in dist_css_body,
    }
    hero_src = re.search(r'hero--fullbleed__bg-img[^>]+src="([^"]+)"', html)
    checks["hero_bg_src"] = hero_src.group(1) if hero_src else ""
    min_h = re.search(r"\.hero--fullbleed[^{]*\{[^}]*min-height:\s*([^;}\s]+)", dist_css_body)
    checks["hero_min_height_css"] = min_h.group(1) if min_h else ""
    print(json.dumps(checks, ensure_ascii=False, indent=2))


if __name__ == "__main__":
    main()
