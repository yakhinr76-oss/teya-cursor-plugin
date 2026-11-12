#!/usr/bin/env python3
"""Match curated turkey_hotels_25.json rows to BG catalog (and Ufa price feed)."""

from __future__ import annotations

import argparse
import json
import re
import sys
import unicodedata
from dataclasses import dataclass
from pathlib import Path
from typing import Any

STOP = frozenset(
    """
    hotel hotels resort spa beach golf club and the ultra all inclusive adults only
    family eco suites premium
    """
    .split()
)

# Curated title → BG hotelsJson id when fuzzy match is risky or rename-heavy.
MANUAL_BG_HOTEL_ID: dict[str, str] = {
    "Liberty Lykia — Adults Only 5★": "102610208678",
    "Rixos Premium Göcek 5★": "102616061210",
    "Lujo Hotel Bodrum 5★": "102610111693",
    "DoubleTree by Hilton Antalya-Kemer 5★": "102616040109",
    "Club Tuana Fethiye 5★": "102616370470",
    "Barut B Suites 4★": "102610085649",
    "Hillside Beach Club 5★": "102625330687",
    "Cullinan Belek 5★": "102610188919",
    "Akra Kemer 5★": "102626196946",
    "Akra Antalya 5★": "102626219120",
    "Side Star Elegance Hotel 5★": "102610108941",
}

# Not present under searchable BG names (or ambiguous); do not attach a catalog id.
MANUAL_NO_BG_MATCH: frozenset[str] = frozenset(
    {
        "AQI Pegasos Royal 5★",
        "TUI MAGIC LIFE Bodrum — Adults Only 5★",
        "TUI BLUE Seno 4+★",
        "Kamelya Aishen Club 5★",
    }
)

RESORT_TOKENS = frozenset(
    """
    fethiye bodrum kemer belek antalya side alanya gocek göcek dalaman sarigerme
    marmaris didim kusadasi lara muratpasa tekirdag
    """
    .split()
)

MIN_FUZZY_SCORE = 460


def strip_accents(text: str) -> str:
    text = unicodedata.normalize("NFKD", text)
    return "".join(c for c in text if not unicodedata.combining(c))


def normalize_hotel_match_key(name: str) -> str:
    name = strip_accents(str(name)).lower()
    name = re.sub(r"[★⭐*]+", " ", name)
    name = re.sub(r"\b\d\s*\*+\b", " ", name)
    name = re.sub(
        r"\b(hotel|hotels|resort|spa|suites|club|golf|beach|apartments)\b",
        " ",
        name,
        flags=re.I,
    )
    name = name.replace("&", " ")
    name = re.sub(r"[^\w]+", "", name, flags=re.UNICODE)
    return name


def hotel_match_tokens(name: str) -> list[str]:
    raw = strip_accents(str(name)).lower()
    tokens = re.findall(r"[a-zа-яё]{3,}", raw, flags=re.I)
    return [t for t in tokens if t not in STOP]


def needle_brand_token(needle: str) -> str:
    ta = hotel_match_tokens(needle)
    if not ta:
        return ""
    for token in sorted(ta, key=len, reverse=True):
        if token not in RESORT_TOKENS:
            return token
    return max(ta, key=len)


def hotel_names_match(needle: str, haystack: str) -> bool:
    ka = normalize_hotel_match_key(needle)
    kb = normalize_hotel_match_key(haystack)
    if not ka or not kb:
        return False
    if ka == kb:
        return True
    min_len = min(len(ka), len(kb))
    if min_len >= 8 and (ka in kb or kb in ka):
        return True

    ta = hotel_match_tokens(needle)
    tb = hotel_match_tokens(haystack)
    if not ta or not tb:
        return False

    brand = needle_brand_token(needle)
    if len(brand) < 4 or brand not in tb:
        return False

    shared = set(ta) & set(tb)
    if len(shared) >= 2:
        return True
    if len(shared) == 1 and len(brand) >= 5:
        return brand in shared
    return False


def ex_name_variants(name: str) -> list[str]:
    name = str(name).strip()
    out: list[str] = [name]
    m = re.search(r"\(ex\.\s*([^)]+)\)", name, re.I)
    if m:
        out.append(m.group(1).strip())
    base = re.sub(r"\s*\([^)]*\)\s*", " ", name).strip()
    if base and base not in out:
        out.append(base)
    return [x for x in out if x]


def curated_title_for_match(hotel_name: str) -> str:
    """Strip resort suffix accidentally baked into display title."""
    title = str(hotel_name).strip()
    title = re.sub(
        r"\s+(Fethiye|Bodrum|Kemer|Belek|Antalya)\s*(—.*)?(\s+\d.*)?$",
        "",
        title,
        flags=re.I,
    )
    return title.strip() or str(hotel_name).strip()


def curated_aliases(hotel_name: str) -> list[str]:
    hotel_name = curated_title_for_match(hotel_name)
    """Extra BG search titles (renames / dropped prefixes)."""
    aliases = [hotel_name]
    n = hotel_name
    if n.startswith("AQI "):
        aliases.append(re.sub(r"^AQI\s+", "", n))
    if "TUI MAGIC LIFE" in n.upper():
        aliases.append(re.sub(r"^TUI\s+", "", n, flags=re.I))
        aliases.append(re.sub(r"^TUI\s+MAGIC\s+LIFE\s+", "Magic Life ", n, flags=re.I))
    if "TUI BLUE" in n.upper():
        aliases.append(re.sub(r"^TUI\s+BLUE\s+", "Tui Blue ", n, flags=re.I))
        aliases.append(re.sub(r"^TUI\s+BLUE\s+", "", n, flags=re.I))
    if "Göcek" in n or "Gocek" in n:
        aliases.append(n.replace("Göcek", "Gocek"))
    if "—" in n:
        aliases.append(re.sub(r"\s*—\s*.*", "", n))
    return list(dict.fromkeys(aliases))


def cross_match(a_variants: list[str], b_variants: list[str]) -> bool:
    return any(hotel_names_match(a, b) for a in a_variants for b in b_variants)


@dataclass
class CatalogRow:
    hotel_id: str
    name: str
    stars: str


def load_catalog(snapshot: dict[str, Any]) -> list[CatalogRow]:
    rows: list[CatalogRow] = []
    for hid, row in (snapshot.get("catalog") or {}).items():
        if not isinstance(row, dict) or not row.get("name"):
            continue
        rows.append(
            CatalogRow(
                hotel_id=str(hid),
                name=str(row["name"]),
                stars=str(row.get("stars") or ""),
            )
        )
    return rows


def tour_price_index(tours: list[dict[str, Any]]) -> dict[str, list[dict[str, Any]]]:
    out: dict[str, list[dict[str, Any]]] = {}
    for t in tours:
        hid = str(t.get("hotel_id") or "")
        if hid:
            out.setdefault(hid, []).append(t)
        hn = str(t.get("hotel_name") or "")
        if hn:
            out.setdefault(f"name:{hn}", []).append(t)
    return out


def min_price_for_id(tour_idx: dict[str, list[dict[str, Any]]], hotel_id: str) -> int | None:
    tours = tour_idx.get(hotel_id, [])
    prices = [
        int(t["price_rur"])
        for t in tours
        if t.get("price_rur") is not None and str(t.get("price_rur")).isdigit()
    ]
    return min(prices) if prices else None




def score_candidate(
    curated_title: str,
    curated_variants: list[str],
    row: CatalogRow,
) -> int:
    cat_vars = ex_name_variants(row.name)
    if not cross_match(curated_variants, cat_vars):
        return -1

    nk = normalize_hotel_match_key(curated_title)
    ck = normalize_hotel_match_key(row.name)
    if nk and nk == ck:
        return 1000

    if nk and ck and (nk in ck or ck in nk) and min(len(nk), len(ck)) >= 8:
        return 900

    ta = set(hotel_match_tokens(curated_title))
    tb = set(hotel_match_tokens(row.name))
    shared = ta & tb
    # Prefer distinctive token overlap; penalize resort-only overlap.
    score = 400 + 40 * len(shared)
    score += sum(len(t) for t in shared if t not in RESORT_TOKENS)
    score -= abs(len(ck) - len(nk)) // 5
    brand = needle_brand_token(curated_title)
    if brand and brand not in tb:
        return -1
    if re.search(r"\(ex\.", row.name, re.I):
        score += 5
    return score


def pick_match(
    hotel_name: str,
    catalog: list[CatalogRow],
    tour_idx: dict[str, list[dict[str, Any]]],
) -> dict[str, Any]:
    if hotel_name in MANUAL_NO_BG_MATCH:
        return {
            "bg_hotel_id": "",
            "bg_hotel_name": "",
            "bg_match_method": "none",
            "bg_match_score": 0,
            "bg_in_price_feed": False,
            "bg_min_price_rur": None,
            "bg_match_note": "not_in_bg_catalog",
        }

    if hotel_name in MANUAL_BG_HOTEL_ID:
        hid = MANUAL_BG_HOTEL_ID[hotel_name]
        cat_name = next((c.name for c in catalog if c.hotel_id == hid), "")
        min_p = min_price_for_id(tour_idx, hid)
        return {
            "bg_hotel_id": hid,
            "bg_hotel_name": cat_name,
            "bg_match_method": "manual",
            "bg_match_score": 9999,
            "bg_in_price_feed": min_p is not None,
            "bg_min_price_rur": min_p,
        }

    title = curated_title_for_match(hotel_name)
    aliases = curated_aliases(hotel_name)
    curated_variants: list[str] = []
    for a in aliases:
        curated_variants.extend(ex_name_variants(a))

    best: CatalogRow | None = None
    best_score = -1
    for row in catalog:
        s = score_candidate(title, curated_variants, row)
        if s > best_score:
            best_score = s
            best = row

    if best is None or best_score < MIN_FUZZY_SCORE:
        return {
            "bg_hotel_id": "",
            "bg_hotel_name": "",
            "bg_match_method": "none",
            "bg_match_score": 0,
            "bg_in_price_feed": False,
            "bg_min_price_rur": None,
        }

    min_p = min_price_for_id(tour_idx, best.hotel_id)

    method = "exact" if best_score >= 1000 else "fuzzy"
    return {
        "bg_hotel_id": best.hotel_id,
        "bg_hotel_name": best.name,
        "bg_match_method": method,
        "bg_match_score": best_score,
        "bg_in_price_feed": min_p is not None,
        "bg_min_price_rur": min_p,
    }


def main() -> int:
    parser = argparse.ArgumentParser()
    repo = Path(__file__).resolve().parents[1]
    default_hotels = (
        repo / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/turkey_hotels_25.json"
    )
    default_snap = repo / "deliverables/zhiraf-ufa/bg-snapshot-export.json"
    parser.add_argument("--hotels", type=Path, default=default_hotels)
    parser.add_argument("--snapshot", type=Path, default=default_snap)
    parser.add_argument("--write", action="store_true", help="Update turkey_hotels_25.json")
    parser.add_argument(
        "--report",
        type=Path,
        default=repo
        / "deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/turkey_hotels_bg_map.json",
    )
    args = parser.parse_args()

    if not args.snapshot.is_file():
        print(f"Missing snapshot: {args.snapshot}", file=sys.stderr)
        return 2

    snapshot = json.loads(args.snapshot.read_text(encoding="utf-8"))
    hotels = json.loads(args.hotels.read_text(encoding="utf-8"))
    catalog = load_catalog(snapshot)
    tour_idx = tour_price_index(snapshot.get("turciya_tours") or [])

    report_rows: list[dict[str, Any]] = []
    for row in hotels:
        name = str(row.get("hotel_name") or "")
        match = pick_match(name, catalog, tour_idx)
        report_rows.append(
            {
                "id": row.get("id"),
                "hotel_name": name,
                "resort": row.get("resort"),
                **match,
            }
        )
        if args.write:
            for key in (
                "bg_hotel_id",
                "bg_hotel_name",
                "bg_match_method",
                "bg_match_score",
                "bg_in_price_feed",
            ):
                if match.get(key) in ("", None, 0, False) and key.startswith("bg_"):
                    row.pop(key, None)
                elif match.get(key) not in (None, ""):
                    row[key] = match[key]

    args.report.write_text(
        json.dumps(
            {
                "snapshot_exported_at": snapshot.get("exported_at"),
                "matches": report_rows,
            },
            ensure_ascii=False,
            indent=2,
        )
        + "\n",
        encoding="utf-8",
    )

    if args.write:
        args.hotels.write_text(
            json.dumps(hotels, ensure_ascii=False, indent=2) + "\n", encoding="utf-8"
        )

    feed = sum(1 for r in report_rows if r.get("bg_in_price_feed"))
    matched = sum(1 for r in report_rows if r.get("bg_hotel_id"))
    print(f"Matched {matched}/25 catalog ids; {feed}/25 in Ufa turciya price feed.")
    for r in report_rows:
        flag = "€" if r.get("bg_in_price_feed") else "—"
        print(
            f"{r['id']:>2} {flag} {r['hotel_name'][:40]:40} → {r.get('bg_hotel_id') or '—':15} "
            f"{(r.get('bg_hotel_name') or '')[:48]} [{r.get('bg_match_method')}]"
        )
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
