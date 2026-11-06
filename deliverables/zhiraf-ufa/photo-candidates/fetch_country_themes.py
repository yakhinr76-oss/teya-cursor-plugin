#!/usr/bin/env python3
"""Fetch 2 landscape Pexels photos per theme query (country-theme-queries.json)."""
from __future__ import annotations

import json
import os
import re
import sys
import time
import urllib.parse
import urllib.request
from typing import Any

ROOT = os.path.dirname(os.path.abspath(__file__))
QUERIES_PATH = os.path.join(ROOT, "country-theme-queries.json")
OUT_PATH = os.path.join(ROOT, "country-theme-picks.json")

THEME_HINTS = {
    "пляж": re.compile(
        r"beach|sea|coast|shore|lagoon|turquoise|yacht|mediterranean|"
        r"sand|lounger|umbrella|marina|bay|water",
        re.I,
    ),
    "атмосфера": re.compile(
        r"cappadocia|balloon|goreme|göreme|fairy chimney|chimney|"
        r"tea|terrace|valley|horse|sunrise|golden hour|breakfast|cave hotel",
        re.I,
    ),
}

COUNTRY_PATTERNS: dict[str, re.Pattern[str]] = {
    "Турция": re.compile(
        r"turkey|türkiye|turkiye|antalya|alanya|muğla|mugla|fethiye|"
        r"ölüdeniz|oludeniz|kaputa[sş]|ka[sş]\b|bodrum|marmaris|"
        r"cappadocia|kapadokya|goreme|göreme|uçhisar|uchisar|nevşehir|"
        r"riviera|mediterranean turkey",
        re.I,
    ),
}

WRONG_PLACE = re.compile(
    r"bacalar|mexico|philippines|sihanoukville|cambodia|maldives|"
    r"seychelles|bali|indonesia|egypt|hurghada|dubai|thailand|vietnam|"
    r"krakow|poland|sahara(?!.*turkey)",
    re.I,
)


def score_photo(query: str, theme: str, alt: str, url: str, w: int, h: int) -> int:
    text = f"{alt} {url}".lower()
    q_words = [w for w in query.lower().split() if len(w) > 3]
    s = 0
    for word in q_words:
        if word in text:
            s += 2
    hint = THEME_HINTS.get(theme)
    if hint and hint.search(text):
        s += 3
    country = None
    for name, pat in COUNTRY_PATTERNS.items():
        if pat.search(text):
            country = name
            break
    if country:
        s += 4
    if WRONG_PLACE.search(text) and not COUNTRY_PATTERNS.get("Турция", re.compile("")).search(
        text
    ):
        s -= 12
    if w >= 2400:
        s += 1
    if w >= 1600 and w > h:
        s += 2
    if "aerial" in query and ("aerial" in text or "drone" in text or "bird" in text):
        s += 2
    if "sunrise" in query and "sunrise" in text:
        s += 2
    if "golden hour" in query and ("golden" in text or "sunset" in text):
        s += 2
    if "breakfast" in query and "breakfast" in text:
        s += 4
    if "rooftop" in query and "rooftop" in text:
        s += 3
    if "tea" in query and "tea" in text:
        s += 2
    return s


def pexels_search(query: str, api_key: str, per_page: int = 40) -> list[dict[str, Any]]:
    out: list[dict[str, Any]] = []
    for page in (1, 2):
        params = urllib.parse.urlencode(
            {"query": query, "per_page": per_page, "page": page, "orientation": "landscape"}
        )
        req = urllib.request.Request(
            f"https://api.pexels.com/v1/search?{params}",
            headers={"Authorization": api_key, "User-Agent": "zhiraf-photo-candidates/1.0"},
        )
        with urllib.request.urlopen(req, timeout=45) as resp:
            data = json.load(resp)
        out.extend(data.get("photos") or [])
        time.sleep(0.35)
    return out


def photo_option(query: str, theme: str, country: str, p: dict[str, Any]) -> dict[str, Any]:
    w, h = p["width"], p["height"]
    orig = p["src"]["large2x"]
    pid = p["id"]
    return {
        "query": query,
        "theme": theme,
        "score": score_photo(query, theme, p.get("alt") or "", p.get("url") or "", w, h),
        "id": pid,
        "page": p["url"],
        "original": p["src"]["original"],
        "thumb": (
            f"https://images.pexels.com/photos/{pid}/pexels-photo-{pid}.jpeg"
            f"?auto=compress&cs=tinysrgb&w=560&h=360&fit=crop"
        ),
        "width": w,
        "height": h,
        "author": p["photographer"],
        "alt": p.get("alt") or "",
        "avg_color": p.get("avg_color") or "",
    }


def pick_two(
    query: str,
    theme: str,
    country: str,
    photos: list[dict[str, Any]],
    used_ids: set[int],
) -> list[dict[str, Any]]:
    pat = COUNTRY_PATTERNS.get(country)
    scored: list[tuple[int, dict[str, Any]]] = []
    for p in photos:
        if p["id"] in used_ids:
            continue
        w, h = p["width"], p["height"]
        if w < 1600 or w <= h:
            continue
        text = f"{p.get('alt', '')} {p.get('url', '')}"
        if pat and not pat.search(text):
            continue
        if WRONG_PLACE.search(text) and not (pat and pat.search(text)):
            continue
        opt = photo_option(query, theme, country, p)
        if opt["score"] < 4:
            continue
        scored.append((opt["score"], opt))
    scored.sort(key=lambda x: (-x[0], -x[1]["width"]))
    picks: list[dict[str, Any]] = []
    for _, opt in scored:
        if opt["id"] in used_ids:
            continue
        used_ids.add(opt["id"])
        picks.append(opt)
        if len(picks) >= 2:
            break
    return picks


def main() -> int:
    api_key = os.environ.get("PEXELS_API_KEY")
    if not api_key:
        print("PEXELS_API_KEY missing", file=sys.stderr)
        return 1
    spec = json.load(open(QUERIES_PATH, encoding="utf-8"))
    per_query = int(spec.get("images_per_query", 2))
    existing: dict[str, Any] = {}
    if os.path.isfile(OUT_PATH):
        existing = json.load(open(OUT_PATH, encoding="utf-8"))
    out = {
        "version": spec.get("version", 1),
        "images_per_query": per_query,
        "countries": existing.get("countries") or {},
    }
    only = sys.argv[1:] if len(sys.argv) > 1 else list(spec["countries"].keys())

    for country in only:
        if country not in spec["countries"]:
            print("skip unknown", country)
            continue
        meta = spec["countries"][country]
        entry = {
            "tab": meta.get("tab"),
            "themes": {},
        }
        used_ids: set[int] = set()
        for theme, queries in meta["themes"].items():
            entry["themes"][theme] = []
            for q in queries:
                print(f"search: {country} / {theme} / {q}")
                photos = pexels_search(q, api_key)
                images = pick_two(q, theme, country, photos, used_ids)
                if len(images) < per_query:
                    print(
                        f"  WARN only {len(images)}/{per_query} for {q}",
                        file=sys.stderr,
                    )
                entry["themes"][theme].append({"query": q, "images": images})
        out["countries"][country] = entry

    json.dump(out, open(OUT_PATH, "w", encoding="utf-8"), ensure_ascii=False, indent=2)
    print("wrote", OUT_PATH)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
