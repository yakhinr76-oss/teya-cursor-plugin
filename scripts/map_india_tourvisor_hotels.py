#!/usr/bin/env python3
"""
Build or refresh data/india-tourvisor-hotels.json from india_hotels_25.json.

  python3 scripts/map_india_tourvisor_hotels.py --scaffold
  python3 scripts/map_india_tourvisor_hotels.py --match

Output: deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/india-tourvisor-hotels.json
"""

from __future__ import annotations

import argparse
import json
import os
import re
import time
import urllib.parse
import urllib.request
from datetime import date
from pathlib import Path

REPO = Path(__file__).resolve().parents[1]
CATALOG_PATH = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/india_hotels_25.json"
OUT_PATH = REPO / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/india-tourvisor-hotels.json"

# Tourvisor autocomplete: Индия (Гоа) (country id TODO).
TOURVISOR_INDONESIA_COUNTRY_ID = int(os.environ.get("TOURVISOR_INDONESIA_COUNTRY_ID", "0"))
AUTOCOMPLETE_URL = "https://tourvisor.ru/xml/autocomplete.php"

TR = str.maketrans(
    {
        "ö": "o",
        "ü": "u",
        "ğ": "g",
        "ş": "s",
        "ı": "i",
        "ç": "c",
        "é": "e",
        "è": "e",
        "â": "a",
        "Ö": "o",
        "Ü": "u",
        "Ğ": "g",
        "Ş": "s",
        "İ": "i",
        "Ç": "c",
        "—": " ",
        "–": " ",
        "-": " ",
        "★": " ",
        "*": " ",
        "+": " ",
        "&": " ",
    }
)
STOP = {
    "hotel",
    "resort",
    "spa",
    "beach",
    "club",
    "the",
    "by",
    "and",
    "adults",
    "adult",
    "only",
    "all",
    "inclusive",
    "ex",
    "collection",
    "apartments",
    "apartment",
    "suites",
    "suite",
    "villas",
    "villa",
    "sentido",
    "pickalbatros",
    "albatros",
}


def strip_stars(name: str) -> str:
    name = name.strip()
    name = re.sub(r"\s*[0-9]\s*[★*+]\s*$", "", name)
    name = re.sub(r"\s*[★*]\s*$", "", name)
    return name.strip()


def norm(s: str) -> str:
    s = (s or "").translate(TR).lower()
    s = re.sub(r"\d", " ", s)
    s = re.sub(r"[^a-z\s]", " ", s)
    s = re.sub(r"\s+", " ", s).strip()
    return s


def tokens(s: str) -> list[str]:
    return [t for t in norm(s).split() if t not in STOP and len(t) > 1]


def search_query(name: str) -> str:
    q = strip_stars(name)
    return re.sub(r"\s+", " ", q).strip()[:80]


def match_names(name: str, tv_name: str = "") -> list[str]:
    base = strip_stars(name)
    names = [name.strip(), base]
    if "&" in base:
        names.append(base.replace(" & ", " and "))
    if tv_name:
        names.append(tv_name.strip())
    out: list[str] = []
    seen: set[str] = set()
    for n in names:
        n = n.strip()
        if n and n.lower() not in seen:
            seen.add(n.lower())
            out.append(n)
    return out


def autocomplete_fetch(query: str) -> list[dict]:
    url = AUTOCOMPLETE_URL + "?" + urllib.parse.urlencode({"citymode": 1, "search": query.lower()})
    req = urllib.request.Request(
        url,
        headers={"User-Agent": "Mozilla/5.0", "Referer": "https://tourvisor.ru/search.php"},
    )
    with urllib.request.urlopen(req, timeout=25) as resp:
        data = json.loads(resp.read().decode("utf-8"))
    hotels = (((data.get("lists") or {}).get("searchresults") or {}).get("hotels") or [])
    return [h for h in hotels if int(h.get("countryid", 0)) == TOURVISOR_INDONESIA_COUNTRY_ID]


def score_candidate(query_tokens: list[str], cand: dict) -> float:
    ct = tokens(str(cand.get("name") or ""))
    if not query_tokens or not ct:
        return 0.0
    qs, cs = set(query_tokens), set(ct)
    inter = qs & cs
    if not inter:
        return 0.0
    recall = len(inter) / len(qs)
    precision = len(inter) / len(cs)
    return round(0.65 * recall + 0.35 * precision, 3)


def build_queries(row: dict) -> list[str]:
    hotel_name = str(row.get("hotel_name", ""))
    base = search_query(hotel_name)
    queries: list[str] = []
    for q in [base, hotel_name, norm(base), " ".join(tokens(base))]:
        q = re.sub(r"\s+", " ", q).strip()
        if q and q.lower() not in [x.lower() for x in queries]:
            queries.append(q)
    sig = tokens(base)
    if len(sig) >= 2:
        queries.append(" ".join(sig[:2]))
    if sig:
        queries.append(sig[0] if len(sig[0]) > 4 else " ".join(sig[:2]))
    norm_name = norm(hotel_name)
    if "sierra" in norm_name and "resort" not in norm_name:
        queries.append("Sierra Resort")
    return queries


def pick_best(row: dict, min_score: float, sleep_s: float) -> tuple[dict | None, float, str]:
    base = search_query(str(row.get("hotel_name", "")))
    qtoks = tokens(base)
    best_by_id: dict[int, dict] = {}
    for q in build_queries(row):
        try:
            hits = autocomplete_fetch(q)
        except Exception as exc:  # noqa: BLE001
            print(f"  ERR zhiraf {row.get('id')} query={q!r}: {exc}", file=__import__("sys").stderr)
            hits = []
        for hit in hits:
            sc = score_candidate(qtoks, hit)
            if sc < min_score:
                continue
            tid = int(hit.get("id", 0))
            prev = best_by_id.get(tid)
            if not prev or sc > float(prev["score"]):
                best_by_id[tid] = {"score": sc, "query": q, **hit}
        if sleep_s > 0:
            time.sleep(sleep_s)
    if not best_by_id:
        return None, 0.0, ""
    top = max(best_by_id.values(), key=lambda x: (x["score"], x.get("name", "")))
    return top, float(top["score"]), str(top.get("query", ""))


def scaffold_hotel(row: dict) -> dict:
    zid = int(row["id"])
    hotel_name = str(row.get("hotel_name", ""))
    return {
        "zhiraf_id": zid,
        "country_slug": "india",
        "hotel_name": hotel_name,
        "resort": str(row.get("resort", "")),
        "region": str(row.get("region", "")),
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


def hotel_from_pick(row: dict, hit: dict, status: str = "auto") -> dict:
    item = scaffold_hotel(row)
    item["tourvisor_id"] = int(hit.get("id", 0))
    item["tourvisor_name"] = str(hit.get("name", ""))
    item["tourvisor_region"] = str(hit.get("regionname", ""))
    item["tourvisor_region_code"] = int(hit.get("regioncode", 0) or 0)
    item["tourvisor_stars"] = int(hit.get("stars", 0) or 0)
    item["match_score"] = float(hit.get("score", 0))
    item["match_status"] = status
    item["match_names"] = match_names(item["hotel_name"], item["tourvisor_name"])
    if hit.get("query"):
        item["search_query"] = str(hit["query"])
    return item


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
        "country_slug": "india",
        "identified_at": date.today().isoformat(),
        "usage": (
            "tourvisor_id — id отеля в базе Tourvisor для батч-поиска. "
            "В webhook prices ключ india:{zhiraf_id} (country_slug india, не egipet). "
            "Парсер может слать tourvisor_id/hotelcode — тема сведёт к zhiraf_id через этот файл."
        ),
        "tourvisor_country_id": TOURVISOR_INDONESIA_COUNTRY_ID,
        "hotels": hotels,
    }


def run_scaffold() -> None:
    hotels = [scaffold_hotel(row) for row in load_catalog()]
    doc = build_document(hotels, "india-catalog-scaffold")
    OUT_PATH.parent.mkdir(parents=True, exist_ok=True)
    with OUT_PATH.open("w", encoding="utf-8") as f:
        json.dump(doc, f, ensure_ascii=False, indent=2)
        f.write("\n")
    print(f"Wrote {len(hotels)} scaffold rows → {OUT_PATH}")


def run_match(min_score: float, sleep_s: float) -> None:
    matched = 0
    hotels: list[dict] = []
    for row in load_catalog():
        zid = int(row["id"])
        name = str(row.get("hotel_name", ""))
        hit, score, _q = pick_best(row, min_score=min_score, sleep_s=sleep_s)
        print(f"#{zid:02d} {name}")
        if hit and int(hit.get("id", 0)) > 0:
            print(f"    {score:.3f} tv={hit['id']} {hit.get('name')} | {hit.get('regionname')} via {hit.get('query')!r}")
            hotels.append(hotel_from_pick(row, hit))
            matched += 1
        else:
            print("    NO HIT")
            item = scaffold_hotel(row)
            item["match_status"] = "unmatched"
            hotels.append(item)

    doc = build_document(hotels, "tourvisor-autocomplete")
    with OUT_PATH.open("w", encoding="utf-8") as f:
        json.dump(doc, f, ensure_ascii=False, indent=2)
        f.write("\n")
    print(f"Matched {matched}/{len(hotels)} (min_score={min_score}) → {OUT_PATH}")


def main() -> None:
    parser = argparse.ArgumentParser(description="India zhiraf_id ↔ Tourvisor hotel map (public autocomplete)")
    mode = parser.add_mutually_exclusive_group(required=True)
    mode.add_argument("--scaffold", action="store_true", help="Write 25 rows with tourvisor_id=0")
    mode.add_argument("--match", action="store_true", help="Fill tourvisor_id via tourvisor.ru/xml/autocomplete.php")
    parser.add_argument("--min-score", type=float, default=0.72, help="Token overlap threshold")
    parser.add_argument("--sleep", type=float, default=0.12, help="Pause between autocomplete calls (seconds)")
    args = parser.parse_args()

    if not CATALOG_PATH.is_file():
        raise SystemExit(f"Missing catalog: {CATALOG_PATH}")

    if args.scaffold:
        run_scaffold()
    else:
        run_match(args.min_score, args.sleep)


if __name__ == "__main__":
    main()