#!/usr/bin/env python3
"""
Build or refresh data/uae-tourvisor-hotels.json from uae_hotels_25.json.

Scaffold (no API creds):
  python3 scripts/map_uae_tourvisor_hotels.py --scaffold

Match via Tourvisor list API (needs login/password in env):
  export TOURVISOR_LOGIN=... TOURVISOR_PASSWORD=...
  python3 scripts/map_uae_tourvisor_hotels.py --match

Output: deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/uae-tourvisor-hotels.json
"""

from __future__ import annotations

import argparse
import json
import os
import re
import sys
import urllib.error
import urllib.parse
import urllib.request
from datetime import date
from difflib import SequenceMatcher
from pathlib import Path

REPO = Path(__file__).resolve().parents[1]
CATALOG_PATH = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/uae_hotels_25.json"
OUT_PATH = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/uae-tourvisor-hotels.json"

# Tourvisor XML list.php: ОАЭ / UAE (same id used in partner integrations).
TOURVISOR_UAE_COUNTRY_ID = os.environ.get("TOURVISOR_UAE_COUNTRY_ID", "9")
LIST_URL = "http://tourvisor.ru/xml/list.php"


def strip_stars(name: str) -> str:
    name = name.strip()
    name = re.sub(r"\s*[0-9]\s*[★*]\s*$", "", name)
    name = re.sub(r"\s*[★*]\s*$", "", name)
    return name.strip()


def search_query(name: str) -> str:
    q = strip_stars(name)
    q = re.sub(r"\s+", " ", q)
    return q[:80]


def match_names(name: str) -> list[str]:
    base = strip_stars(name)
    names = [name.strip(), base]
    if "&" in base:
        names.append(base.replace(" & ", " and "))
    out: list[str] = []
    seen: set[str] = set()
    for n in names:
        n = n.strip()
        if n and n not in seen:
            seen.add(n)
            out.append(n)
    return out


def scaffold_hotel(row: dict) -> dict:
    zid = int(row["id"])
    hotel_name = str(row.get("hotel_name", ""))
    return {
        "zhiraf_id": zid,
        "country_slug": "uae",
        "hotel_name": hotel_name,
        "resort": str(row.get("resort", "")),
        "emirate": str(row.get("emirate", "")),
        "tourvisor_id": 0,
        "tourvisor_name": "",
        "tourvisor_region": "",
        "tourvisor_region_code": 0,
        "tourvisor_stars": 0,
        "search_query": search_query(hotel_name),
        "match_names": match_names(hotel_name),
        "match_status": "pending",
        "match_score": 0.0,
    }


def load_catalog() -> list[dict]:
    with CATALOG_PATH.open(encoding="utf-8") as f:
        data = json.load(f)
    if not isinstance(data, list):
        raise SystemExit(f"Expected list in {CATALOG_PATH}")
    return data


def build_document(hotels: list[dict], source: str) -> dict:
    return {
        "schema": "zhiraf.tourvisor-hotels-map.v1",
        "source": source,
        "country_slug": "uae",
        "identified_at": date.today().isoformat(),
        "usage": (
            "tourvisor_id — id отеля в базе Tourvisor для батч-поиска. "
            "В webhook prices ключ uae:{zhiraf_id} (country_slug uae, не oae). "
            "Парсер может слать tourvisor_id/hotelcode — тема сведёт к zhiraf_id через этот файл."
        ),
        "tourvisor_country_id": int(TOURVISOR_UAE_COUNTRY_ID),
        "hotels": hotels,
    }


def tv_request(login: str, password: str, params: dict) -> dict | list:
    q = {
        "authlogin": login,
        "authpass": password,
        "format": "json",
        **params,
    }
    url = LIST_URL + "?" + urllib.parse.urlencode(q)
    req = urllib.request.Request(url, headers={"User-Agent": "zhiraf-uae-map/1.0"})
    try:
        with urllib.request.urlopen(req, timeout=120) as resp:
            raw = resp.read().decode("utf-8", errors="replace")
    except urllib.error.HTTPError as exc:
        body = exc.read().decode("utf-8", errors="replace")[:500]
        raise SystemExit(f"Tourvisor HTTP {exc.code}: {body}") from exc
    except urllib.error.URLError as exc:
        raise SystemExit(f"Tourvisor request failed: {exc}") from exc

    try:
        parsed = json.loads(raw)
    except json.JSONDecodeError as exc:
        raise SystemExit(f"Tourvisor response is not JSON (maybe Authorization Error): {raw[:200]}") from exc

    if isinstance(parsed, dict) and parsed.get("error"):
        raise SystemExit(f"Tourvisor error: {parsed.get('errormessage', parsed)}")

    return parsed


def normalize_tv_hotels(payload: dict | list) -> list[dict]:
    if isinstance(payload, list):
        return [h for h in payload if isinstance(h, dict)]
    if not isinstance(payload, dict):
        return []
    for key in ("hotels", "hotel", "data", "list"):
        block = payload.get(key)
        if isinstance(block, list):
            return [h for h in block if isinstance(h, dict)]
        if isinstance(block, dict):
            hotels = block.get("hotel")
            if isinstance(hotels, list):
                return [h for h in hotels if isinstance(h, dict)]
    return []


def tv_hotel_id(row: dict) -> int:
    for key in ("id", "hotelcode", "code", "hotel_id"):
        if key in row and str(row[key]).isdigit():
            return int(row[key])
    return 0


def tv_hotel_name(row: dict) -> str:
    for key in ("name", "hotelname", "hotel_name", "title"):
        if key in row and row[key]:
            return str(row[key]).strip()
    return ""


def tv_region(row: dict) -> str:
    for key in ("region", "regionname", "resort", "city"):
        if key in row and row[key]:
            return str(row[key]).strip()
    return ""


def loose_key(name: str) -> str:
    name = name.lower()
    name = name.replace("★", "").replace("*", "")
    name = re.sub(r"[^\w\s]", " ", name, flags=re.UNICODE)
    name = re.sub(r"\s+", " ", name).strip()
    for drop in (" hotel", " resort", " apartments", " apartment"):
        if name.endswith(drop):
            name = name[: -len(drop)].strip()
    return name


def best_match(query: str, tv_hotels: list[dict], min_score: float = 0.72) -> tuple[dict | None, float]:
    q = loose_key(query)
    best: dict | None = None
    best_score = 0.0
    for row in tv_hotels:
        name = tv_hotel_name(row)
        if not name:
            continue
        cand = loose_key(name)
        score = SequenceMatcher(None, q, cand).ratio()
        if q and (q in cand or cand in q):
            score = max(score, 0.85)
        if score > best_score:
            best_score = score
            best = row
    if best is None or best_score < min_score:
        return None, best_score
    return best, best_score


def run_scaffold() -> None:
    hotels = [scaffold_hotel(row) for row in load_catalog()]
    doc = build_document(hotels, "uae-catalog-scaffold")
    OUT_PATH.parent.mkdir(parents=True, exist_ok=True)
    with OUT_PATH.open("w", encoding="utf-8") as f:
        json.dump(doc, f, ensure_ascii=False, indent=2)
        f.write("\n")
    print(f"Wrote {len(hotels)} scaffold rows → {OUT_PATH}")


def run_match(min_score: float) -> None:
    login = os.environ.get("TOURVISOR_LOGIN", "").strip()
    password = os.environ.get("TOURVISOR_PASSWORD", "").strip()
    if not login or not password:
        raise SystemExit("Set TOURVISOR_LOGIN and TOURVISOR_PASSWORD")

    payload = tv_request(
        login,
        password,
        {"type": "hotel", "hotcountry": TOURVISOR_UAE_COUNTRY_ID},
    )
    tv_hotels = normalize_tv_hotels(payload)
    if not tv_hotels:
        raise SystemExit(
            "Tourvisor returned no UAE hotels — check TOURVISOR_UAE_COUNTRY_ID "
            f"(now {TOURVISOR_UAE_COUNTRY_ID}) and API access."
        )

    matched = 0
    hotels: list[dict] = []
    for row in load_catalog():
        item = scaffold_hotel(row)
        hit, score = best_match(item["search_query"], tv_hotels, min_score=min_score)
        item["match_score"] = round(score, 3)
        if hit:
            tid = tv_hotel_id(hit)
            tname = tv_hotel_name(hit)
            if tid > 0:
                item["tourvisor_id"] = tid
                item["tourvisor_name"] = tname
                item["tourvisor_region"] = tv_region(hit)
                item["match_status"] = "auto"
                matched += 1
            else:
                item["match_status"] = "no_id"
        else:
            item["match_status"] = "unmatched"

        hotels.append(item)

    doc = build_document(hotels, "tourvisor-list-match")
    with OUT_PATH.open("w", encoding="utf-8") as f:
        json.dump(doc, f, ensure_ascii=False, indent=2)
        f.write("\n")

    print(f"Matched {matched}/{len(hotels)} (min_score={min_score}) → {OUT_PATH}")
    for h in hotels:
        if h["match_status"] != "auto":
            print(f"  [ ] zhiraf {h['zhiraf_id']:2d} {h['search_query']!r} — {h['match_status']} ({h['match_score']})")


def main() -> None:
    parser = argparse.ArgumentParser(description="UAE zhiraf_id ↔ Tourvisor hotel map")
    mode = parser.add_mutually_exclusive_group(required=True)
    mode.add_argument("--scaffold", action="store_true", help="Write 25 rows with tourvisor_id=0")
    mode.add_argument("--match", action="store_true", help="Fill tourvisor_id via Tourvisor list API")
    parser.add_argument("--min-score", type=float, default=0.72, help="Fuzzy match threshold for --match")
    args = parser.parse_args()

    if not CATALOG_PATH.is_file():
        raise SystemExit(f"Missing catalog: {CATALOG_PATH}")

    if args.scaffold:
        run_scaffold()
    else:
        run_match(args.min_score)


if __name__ == "__main__":
    main()