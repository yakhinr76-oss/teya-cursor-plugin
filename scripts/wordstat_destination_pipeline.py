#!/usr/bin/env python3
"""Yandex Wordstat (region 172) → 03/04/05/06 CSV refresh for Жираф Уфа directions."""
from __future__ import annotations

import csv
import json
import os
import re
import sys
import time
import urllib.error
import urllib.request
from collections import defaultdict
from datetime import date
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
CORE = ROOT / "teya-memory/semantic-core/zhiraf-ufa-2026"
API_URL = "https://searchapi.api.cloud.yandex.net/v2/wordstat/topRequests"
REGION_ID = 172
REGION_LABEL = "Уфа"
DEVICE = "DEVICE_ALL"
NUM_PHRASES = 40

# slug → seed name(s), target_url, display name for url-map
DESTINATIONS: list[dict] = [
    {"slug": "turciya", "url": "/tour/turciya/", "names": ["турцию", "турции"]},
    {"slug": "oae", "url": "/tour/oae/", "names": ["оаэ", "дубай", "дубае"]},
    {"slug": "egipet", "url": "/tour/egipet/", "names": ["египет", "египта", "египте"]},
    {"slug": "tailand", "url": "/tour/tailand/", "names": ["таиланд", "таиланда", "таиланде"]},
    {"slug": "vietnam", "url": "/tour/vietnam/", "names": ["вьетнам", "вьетнама", "вьетнаме"]},
    {"slug": "kitaj", "url": "/tour/kitaj/", "names": ["китай", "китая", "хайнань", "санью"]},
    {"slug": "indiya", "url": "/tour/indiya/", "names": ["гоа", "индию", "индии"]},
    {"slug": "shri-lanka", "url": "/tour/shri-lanka/", "names": ["шри-ланку", "шри ланку", "шри-ланке", "шри ланке"]},
    {"slug": "indoneziya", "url": "/tour/indoneziya/", "names": ["бали", "индонезию", "индонезии"]},
    {"slug": "maldivy", "url": "/tour/maldivy/", "names": ["мальдивы", "мальдив", "мальдивах"]},
    {"slug": "sejshely", "url": "/tour/sejshely/", "names": ["сейшелы", "сейшел", "сейшелах"]},
    {"slug": "uzbekistan", "url": "/tour/uzbekistan/", "names": ["узбекистан", "узбекистана", "узбекистане"]},
    {"slug": "armeniya", "url": "/tour/armeniya/", "names": ["армению", "армении", "армении"]},
    {"slug": "rossiya", "url": "/tour/rossiya/", "names": ["сочи", "россию", "россии", "крым"]},
    {"slug": "abkhaziya", "url": "/tour/abkhaziya/", "names": ["абхазию", "абхазии", "гагру", "пицунду"]},
    {"slug": "gruziya", "url": "/tour/gruziya/", "names": ["грузию", "грузии", "батуми", "тбилиси"]},
    {"slug": "kuba", "url": "/tour/kuba/", "names": ["кубу", "кубы", "кубе", "варадеро"]},
    {"slug": "dominikana", "url": "/tour/dominikana/", "names": ["доминикану", "доминиканы", "пунта кану"]},
    {"slug": "tunis", "url": "/tour/tunis/", "names": ["тунис", "туниса", "тунисе"]},
    {"slug": "mauritius", "url": "/tour/mauritius/", "names": ["маврикий", "маврикия", "маврикии"]},
]

# Курортные лендинги /tour/{country}/{resort}/ — частоты Уфа 172, API 2026-11-20 (якорь ≥100).
RESORTS: list[dict] = [
    {
        "slug": "dubai",
        "country_slug": "oae",
        "url": "/tour/oae/dubai/",
        "priority": "P0",
        "h1": "Туры в Дубай из Уфы",
        "title_name": "Дубай",
        "description_in": "Дубай",
        "names": ["дубай", "дубае"],
        "freq_anchor": 1229,
        "intent_block": "kids",
        "primary_queries": "дубай из уфы 1229|туры в дубай из уфы 253|путевки в дубай из уфы 59|горящие туры дубай из уфы 42|дубай из уфы на двоих 25",
        "parent_url": "/tour/oae/",
        "peer_notes": "no_canonical_to_parent;no_redirect;peer_hub=/tour/oae/",
    },
    {
        "slug": "hurgada",
        "country_slug": "egipet",
        "url": "/tour/egipet/hurgada/",
        "priority": "P0",
        "h1": "Туры в Хургаду из Уфы",
        "title_name": "Хургада",
        "description_in": "Хургаду",
        "names": ["хургада", "хургаду", "хургаде"],
        "freq_anchor": 763,
        "intent_block": "kids",
        "primary_queries": "хургада из уфы 763|туры в хургаду из уфы 109|хургада все включено из уфы 27|путевки в хургаду из уфы 15|горящие туры хургада из уфы 10",
        "parent_url": "/tour/egipet/",
        "peer_notes": "parent_peer_only",
    },
    {
        "slug": "sharm-el-sheyh",
        "country_slug": "egipet",
        "url": "/tour/egipet/sharm-el-sheyh/",
        "priority": "P0",
        "h1": "Туры в Шарм-эль-Шейх из Уфы",
        "title_name": "Шарм-эль-Шейх",
        "description_in": "Шарм-эль-Шейх",
        "names": ["шарм", "шарм эль шейх", "шарм-эль-шейх"],
        "freq_anchor": 746,
        "intent_block": "all_inclusive",
        "primary_queries": "шарм эль шейх из уфы 746|туры в шарм эль шейх из уфы 195|шарм эль шейх все включено из уфы 75|путевки в шарм эль шейх из уфы 24|купить тур в шарм эль шейх из уфы 31",
        "parent_url": "/tour/egipet/",
        "peer_notes": "parent_peer_only",
    },
    {
        "slug": "fukuok",
        "country_slug": "vietnam",
        "url": "/tour/vietnam/fukuok/",
        "priority": "P0",
        "h1": "Туры на Фукуок из Уфы",
        "title_name": "Фукуок",
        "description_in": "Фукуок",
        "names": ["фукуок", "фукуоке"],
        "freq_anchor": 760,
        "intent_block": "kids",
        "primary_queries": "фукуок из уфы 760|туры на фукуок из уфы 146|путевки на фукуок из уфы 19|горящие туры фукуок из уфы 14|купить тур на фукуок из уфы 10",
        "parent_url": "/tour/vietnam/",
        "peer_notes": "parent_peer_only",
    },
    {
        "slug": "phuket",
        "country_slug": "tailand",
        "url": "/tour/tailand/phuket/",
        "priority": "P0",
        "h1": "Туры на Пхукет из Уфы",
        "title_name": "Пхукет",
        "description_in": "Пхукет",
        "names": ["пхукет", "пхукете"],
        "freq_anchor": 642,
        "intent_block": "kids",
        "primary_queries": "пхукет из уфы 642|туры на пхукет из уфы 116|горящие туры пхукет из уфы 21|путевки на пхукет из уфы 11|купить тур на пхукет из уфы 14",
        "parent_url": "/tour/tailand/",
        "peer_notes": "parent_peer_only",
    },
    {
        "slug": "nyachang",
        "country_slug": "vietnam",
        "url": "/tour/vietnam/nyachang/",
        "priority": "P0",
        "h1": "Туры в Нячанг из Уфы",
        "title_name": "Нячанг",
        "description_in": "Нячанг",
        "names": ["нячанг", "нячанге"],
        "freq_anchor": 515,
        "intent_block": "kids",
        "primary_queries": "нячанг из уфы 515|туры в нячанг из уфы 87|путевки в нячанг из уфы 15|горящие туры нячанг из уфы 3|купить тур в нячанг из уфы 5",
        "parent_url": "/tour/vietnam/",
        "peer_notes": "parent_peer_only",
    },
    {
        "slug": "pattaya",
        "country_slug": "tailand",
        "url": "/tour/tailand/pattaya/",
        "priority": "P1",
        "h1": "Туры в Паттайю из Уфы",
        "title_name": "Паттайя",
        "description_in": "Паттайю",
        "names": ["паттайя", "паттайю", "паттайе"],
        "freq_anchor": 324,
        "intent_block": "kids",
        "primary_queries": "паттайя из уфы 324|туры в паттайю из уфы 99|путевки в паттайю из уфы 4|горящие туры паттайя из уфы 2|купить тур в паттайю из уфы 2",
        "parent_url": "/tour/tailand/",
        "peer_notes": "parent_peer_only",
    },
    {
        "slug": "antalya",
        "country_slug": "turciya",
        "url": "/tour/turciya/antalya/",
        "priority": "P1",
        "h1": "Туры в Анталью из Уфы",
        "title_name": "Анталья",
        "description_in": "Анталью",
        "names": ["анталья", "анталия", "анталью"],
        "freq_anchor": 257,
        "intent_block": "kids",
        "primary_queries": "анталья из уфы 257|туры в анталью из уфы 17|алания из уфы 61|путевки в анталью из уфы 2|купить тур в анталью из уфы 1",
        "parent_url": "/tour/turciya/",
        "peer_notes": "parent_peer_only",
    },
    {
        "slug": "sharjah",
        "country_slug": "oae",
        "url": "/tour/oae/sharjah/",
        "priority": "P1",
        "h1": "Туры в Шарджу из Уфы",
        "title_name": "Шарджа",
        "description_in": "Шарджу",
        "names": ["шарджа", "шарджу"],
        "freq_anchor": 101,
        "intent_block": "kids",
        "primary_queries": "шарджа из уфы 101|туры в шарджу из уфы 44|путевки в шарджу из уфы 6|горящие туры шарджа из уфы 2",
        "parent_url": "/tour/oae/",
        "peer_notes": "parent_peer_only",
    },
]

INTENT_KEYWORDS = {
    "kids": ("с детьми", "детям", "семейн", "аквапарк", "песчаный пляж", "детский"),
    "couples": ("для двоих", "свадеб", "вилл", "романт", "медов", "16+", "honeymoon"),
    "all_inclusive": ("всё включено", "все включено", "всё-включено", "5 звезд", "5 звёзд", "all inclusive"),
    "excursion": ("экскурс", "выходные", "достопримеч", "городской", "самарканд", "бухар"),
}

OFF_TOPIC = re.compile(
    r"(сериал|турецк|мфц|азино|форум|википед|перевод|песня|клип|ютуб|рецепт)",
    re.I,
)
COMMERCIAL = re.compile(r"(тур|путевк|отдых|отел|цен|вылет|из уф)", re.I)

DEFAULT_INTENT_OVERRIDE = {
    "turciya": "kids",
    "egipet": "kids",
    "oae": "kids",
    "tailand": "kids",
    "vietnam": "kids",
    "abkhaziya": "kids",
    "indiya": "kids",
    "shri-lanka": "kids",
    "maldivy": "couples",
    "sejshely": "couples",
    "indoneziya": "couples",
    "mauritius": "couples",
    "kuba": "all_inclusive",
    "dominikana": "all_inclusive",
    "tunis": "all_inclusive",
    "kitaj": "all_inclusive",
    "uzbekistan": "excursion",
    "armeniya": "excursion",
    "gruziya": "excursion",
    "rossiya": "excursion",
}

MAX_ASSOC_FREQ = 5000


def seeds_for_name(name: str) -> list[str]:
    n = name.strip().lower()
    in_country = n
    if n in ("оаэ", "дубай", "дубае", "гоа", "сочи", "крым", "батуми", "тбилиси", "гагру", "пицунду", "варadero", "варадеро"):
        in_country = n
    elif n.endswith(("у", "ю")):
        in_country = n
    else:
        in_country = n
    return [
        f"туры в {in_country} из уфы",
        f"туры на {in_country} из уфы",
        f"{n} из уфы цены",
        f"{n} с детьми из уфы",
        f"{n} всё включено из уфы",
        f"отдых в {in_country} из уфы",
        f"отдых на {in_country} из уфы",
    ]


def fetch_phrase(phrase: str, api_key: str) -> dict:
    body = json.dumps({"phrase": phrase, "regionId": REGION_ID, "numPhrases": NUM_PHRASES}).encode()
    req = urllib.request.Request(
        API_URL,
        data=body,
        headers={"Authorization": f"Bearer {api_key}", "Content-Type": "application/json"},
        method="POST",
    )
    with urllib.request.urlopen(req, timeout=60) as resp:
        return json.loads(resp.read().decode())


def read_csv(path: Path) -> tuple[list[str], list[dict]]:
    if not path.exists():
        return [], []
    with path.open(encoding="utf-8", newline="") as f:
        reader = csv.DictReader(f)
        return reader.fieldnames or [], list(reader)


def write_csv(path: Path, fieldnames: list[str], rows: list[dict]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    with path.open("w", encoding="utf-8", newline="") as f:
        w = csv.DictWriter(f, fieldnames=fieldnames, extrasaction="ignore")
        w.writeheader()
        w.writerows(rows)


def normalize_query(q: str) -> str:
    q = q.lower().strip()
    q = re.sub(r"\s+", " ", q)
    return q


def is_ufa_commercial(q: str, row: dict) -> bool:
    q = normalize_query(q)
    if OFF_TOPIC.search(q):
        return False
    if "уф" not in q and "башкорт" not in q:
        return False
    if not COMMERCIAL.search(q):
        return False
    source = row.get("source_sources", "")
    if "association" in source:
        freq = int(row.get("frequency_value", 0) or 0)
        if freq > MAX_ASSOC_FREQ:
            return False
    return True


def slug_for_query(query: str, slug_terms: dict[str, set[str]]) -> str | None:
    q = normalize_query(query)
    best = None
    best_len = 0
    for slug, terms in slug_terms.items():
        for t in terms:
            if t in q and len(t) > best_len:
                best = slug
                best_len = len(t)
    return best


def resort_cluster_id(resort: dict) -> str:
    country = resort["country_slug"].upper().replace("-", "_")
    slug = resort["slug"].upper().replace("-", "_")
    return f"CL-{country}_{slug}"


def apply_resorts_to_url_map(by_url: dict[str, dict], today: str) -> None:
    """Embed curated resort rows (Wordstat Уфа 172); no API required."""
    for resort in RESORTS:
        url = resort["url"]
        parent = resort["parent_url"]
        by_url[url] = {
            "target_url": url,
            "url_status": "create",
            "page_type": "resort",
            "cluster_ids": resort_cluster_id(resort),
            "primary_queries": resort["primary_queries"],
            "recommended_h1": resort["h1"],
            "title_draft": f"{resort['title_name']} из Уфы — подбор тура «Жираф»",
            "description_draft": (
                f"Пакетные туры в {resort['description_in']} с вылетом из Уфы. "
                f"Отдельная страница курорта; перелинковка со страницей страны {parent}."
            ),
            "internal_links_from": f"{parent}|/",
            "internal_links_to": f"{parent}|/goryashchie-tury/|/vse-vklyucheno/",
            "implementation_task": "curated-resort-landing",
            "owner_hint": "conversion",
            "notes": (
                f"priority={resort['priority']};intent_block={resort['intent_block']};"
                f"freq_anchor={resort['freq_anchor']};regionId=172;resort_landing;"
                f"{resort.get('peer_notes', '')};wordstat_slice=2026-11-20"
            ),
        }


def main() -> int:
    dry = "--dry-run" in sys.argv
    regen_only = "--regenerate-only" in sys.argv
    api_key = os.environ.get("YANDEX_WORDSTAT_API_KEY", "")
    if not api_key and not regen_only:
        print("YANDEX_WORDSTAT_API_KEY is not set", file=sys.stderr)
        return 1
    today = date.today().isoformat()

    slug_terms: dict[str, set[str]] = {}
    all_seeds: list[tuple[str, str]] = []  # slug, phrase
    seen_seed: set[str] = set()
    for dest in DESTINATIONS:
        terms: set[str] = set()
        for name in dest["names"]:
            terms.add(name.lower())
            for seed in seeds_for_name(name):
                if seed not in seen_seed:
                    seen_seed.add(seed)
                    all_seeds.append((dest["slug"], seed))
        slug_terms[dest["slug"]] = terms

    raw_fields = [
        "date_collected",
        "seed_phrase",
        "query",
        "raw_frequency",
        "region",
        "device",
        "source",
        "source_call",
        "notes",
    ]
    _, raw_rows = read_csv(CORE / "03-wordstat-raw.csv")
    existing_raw = {(r["seed_phrase"], r["query"], r.get("source", "")) for r in raw_rows}

    call_idx = len({r.get("source_call", "") for r in raw_rows}) + 1
    new_raw: list[dict] = []
    seed_totals: dict[str, int] = {}

    for slug, seed in all_seeds:
        if dry or regen_only:
            continue
        for attempt in range(4):
            try:
                data = fetch_phrase(seed, api_key)
                break
            except urllib.error.HTTPError as e:
                if e.code == 429 and attempt < 3:
                    time.sleep(2 ** (attempt + 1))
                    continue
                print(f"HTTP {e.code} for {seed}", file=sys.stderr)
                data = None
                break
        if not data:
            continue
        call_id = f"call_wave2_{call_idx:03d}"
        call_idx += 1
        total = int(data.get("totalCount", 0) or 0)
        seed_totals[seed] = total
        notes = f"regionId={REGION_ID};slug={slug};wave2"
        for row in data.get("results", []):
            q = row.get("phrase", "")
            freq = int(row.get("count", 0) or 0)
            key = (seed, q, "top_requests")
            if key in existing_raw:
                continue
            new_raw.append(
                {
                    "date_collected": today,
                    "seed_phrase": seed,
                    "query": q,
                    "raw_frequency": freq,
                    "region": REGION_LABEL,
                    "device": DEVICE,
                    "source": "top_requests",
                    "source_call": call_id,
                    "notes": notes,
                }
            )
        for row in data.get("associations", []):
            q = row.get("phrase", "")
            freq = int(row.get("count", 0) or 0)
            key = (seed, q, "association")
            if key in existing_raw:
                continue
            new_raw.append(
                {
                    "date_collected": today,
                    "seed_phrase": seed,
                    "query": q,
                    "raw_frequency": freq,
                    "region": REGION_LABEL,
                    "device": DEVICE,
                    "source": "association",
                    "source_call": call_id,
                    "notes": notes + ";association",
                }
            )
        time.sleep(0.35)

    if not dry and not regen_only:
        raw_rows.extend(new_raw)
        write_csv(CORE / "03-wordstat-raw.csv", raw_fields, raw_rows)
        print(f"03-wordstat-raw: +{len(new_raw)} rows")

    clean_fields = [
        "query",
        "canonical_query",
        "source_sources",
        "frequency_region",
        "frequency_value",
        "intent_initial",
        "intent_confidence",
        "cluster_candidate",
        "include_status",
        "exclude_reason",
        "notes",
    ]
    _, clean_rows = read_csv(CORE / "04-keywords-clean.csv")
    clean_by_q = {normalize_query(r["query"]): r for r in clean_rows}

    if not regen_only:
        for row in new_raw:
            q = normalize_query(row["query"])
            freq = int(row["raw_frequency"])
            source = row["source"]
            seed = row["seed_phrase"]
            draft = {
                "query": row["query"],
                "canonical_query": q,
                "source_sources": f"{source}|{seed}",
                "frequency_region": REGION_LABEL,
                "frequency_value": str(freq),
                "intent_initial": "commercial",
                "intent_confidence": "medium" if source == "top_requests" else "low",
                "cluster_candidate": "",
                "include_status": "include",
                "exclude_reason": "",
                "notes": f"regionId={REGION_ID};{source}",
            }
            slug = slug_for_query(q, slug_terms) or slug_for_query(seed, slug_terms) or ""
            if slug:
                draft["cluster_candidate"] = f"CL-{slug.upper().replace('-', '_')}"
                draft["notes"] += f";slug={slug}"
            if not is_ufa_commercial(q, draft):
                draft["include_status"] = "exclude"
                draft["exclude_reason"] = "off-topic"
            elif freq < 1:
                draft["include_status"] = "exclude"
                draft["exclude_reason"] = "low-volume"
            clean_by_q[q] = draft

    # Re-apply Ufa commercial gate to all rows (fixes polluted includes).
    for q, row in list(clean_by_q.items()):
        if row.get("include_status") == "include" and not is_ufa_commercial(q, row):
            row["include_status"] = "exclude"
            row["exclude_reason"] = "off-topic"

    clean_rows = list(clean_by_q.values())
    if not dry:
        write_csv(CORE / "04-keywords-clean.csv", clean_fields, clean_rows)

    intent_scores: dict[str, dict[str, int]] = defaultdict(lambda: defaultdict(int))
    commercial_totals: dict[str, int] = defaultdict(int)
    for row in clean_rows:
        if row.get("include_status") != "include":
            continue
        q = row.get("canonical_query", row.get("query", ""))
        slug = slug_for_query(q, slug_terms)
        if not slug:
            m = re.search(r"slug=([a-z0-9-]+)", row.get("notes", ""))
            if m:
                slug = m.group(1)
        if not slug or slug not in slug_terms:
            continue
        if not any(t in q for t in slug_terms[slug]):
            continue
        freq = min(int(row.get("frequency_value", 0) or 0), MAX_ASSOC_FREQ)
        commercial_totals[slug] += freq
        for intent, kws in INTENT_KEYWORDS.items():
            if any(k in q for k in kws):
                intent_scores[slug][intent] += freq

    intent_assignment: dict[str, str] = {}
    for dest in DESTINATIONS:
        slug = dest["slug"]
        if slug in DEFAULT_INTENT_OVERRIDE:
            intent_assignment[slug] = DEFAULT_INTENT_OVERRIDE[slug]
            continue
        scores = intent_scores.get(slug, {})
        if scores:
            intent_assignment[slug] = max(scores.items(), key=lambda x: x[1])[0]
        else:
            intent_assignment[slug] = "kids"

    report = {
        "date": today,
        "region_id": REGION_ID,
        "new_raw_rows": len(new_raw),
        "seed_totals_sample": dict(list(sorted(seed_totals.items(), key=lambda x: -x[1]))[:40]),
        "intent_scores": {k: dict(v) for k, v in intent_scores.items()},
        "intent_assignment": intent_assignment,
        "commercial_totals": dict(sorted(commercial_totals.items(), key=lambda x: -x[1])),
    }

    out_json = CORE / "10-intent-wave2-report.json"
    if not dry:
        out_json.write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")

    # Update 05 clusters for destination slugs
    cluster_fields = [
        "cluster_id",
        "cluster_name",
        "primary_query",
        "secondary_queries",
        "intent",
        "frequency_total",
        "region_note",
        "page_type",
        "priority",
        "business_value",
        "target_url",
        "url_status",
        "serp_engine",
        "serp_check_status",
        "top_competitors",
        "geo_questions",
        "content_status",
        "last_review",
        "notes",
    ]
    _, cluster_rows = read_csv(CORE / "05-clusters.csv")
    by_id = {r["cluster_id"]: r for r in cluster_rows}
    for dest in DESTINATIONS:
        slug = dest["slug"]
        cid = f"CL-{slug.upper().replace('-', '_')}"
        total = commercial_totals.get(slug, 0)
        primary = max(
            (r for r in clean_rows if r.get("cluster_candidate") == cid and r.get("include_status") == "include"),
            key=lambda r: int(r.get("frequency_value", 0) or 0),
            default={"query": f"туры {dest['names'][0]} из уфы", "frequency_value": "0"},
        )
        block_intent = intent_assignment.get(slug, "kids")
        by_id[cid] = {
            "cluster_id": cid,
            "cluster_name": f"Туры {dest['names'][0]} из Уфы",
            "primary_query": primary.get("query", ""),
            "secondary_queries": "",
            "intent": block_intent,
            "frequency_total": str(total),
            "region_note": REGION_LABEL,
            "page_type": "landing",
            "priority": "P1" if total < 200 else "P0",
            "business_value": "high" if total >= 100 else "medium",
            "target_url": dest["url"],
            "url_status": "create" if total >= 50 else "merge",
            "serp_engine": "yandex",
            "serp_check_status": "not_checked",
            "top_competitors": "coral;pegas;travelata",
            "geo_questions": "",
            "content_status": "planned",
            "last_review": today,
            "notes": f"intent_block={block_intent};Wordstat wave2",
        }
    cluster_rows = list(by_id.values())
    if not dry:
        write_csv(CORE / "05-clusters.csv", cluster_fields, cluster_rows)

    url_fields = [
        "target_url",
        "url_status",
        "page_type",
        "cluster_ids",
        "primary_queries",
        "recommended_h1",
        "title_draft",
        "description_draft",
        "internal_links_from",
        "internal_links_to",
        "implementation_task",
        "owner_hint",
        "notes",
    ]
    _, url_rows = read_csv(CORE / "06-url-map.csv")
    by_url = {r["target_url"]: r for r in url_rows if r.get("target_url")}

    H1_TITLES = {
        "kids": "с детьми из Уфы",
        "couples": "для двоих из Уфы",
        "all_inclusive": "всё включено из Уфы",
        "excursion": "экскурсионные туры из Уфы",
    }

    for dest in DESTINATIONS:
        slug = dest["slug"]
        url = dest["url"]
        cid = f"CL-{slug.upper().replace('-', '_')}"
        primary = by_id.get(cid, {}).get("primary_query", "")
        freq = commercial_totals.get(slug, 0)
        block = intent_assignment.get(slug, "kids")
        name_cap = dest["names"][0].title()
        top_kw = sorted(
            [
                r
                for r in clean_rows
                if slug_for_query(r.get("canonical_query", ""), slug_terms) == slug
                and r.get("include_status") == "include"
            ],
            key=lambda r: int(r.get("frequency_value", 0) or 0),
            reverse=True,
        )[:5]
        kw_pipe = "|".join(f"{r['canonical_query']} {r['frequency_value']}" for r in top_kw)
        h1_suffix = H1_TITLES.get(block, "из Уфы")
        row = {
            "target_url": url,
            "url_status": "ok" if slug in by_url else "create",
            "page_type": "destination",
            "cluster_ids": cid,
            "primary_queries": kw_pipe or f"{primary} {freq}",
            "recommended_h1": f"{name_cap}: туры {h1_suffix}",
            "title_draft": f"{name_cap} из Уфы — подбор тура «Жираф»",
            "description_draft": f"Пакетные туры в {name_cap} с вылетом из Уфы. Подбор без давления, актуальные цены Wordstat {today}.",
            "internal_links_from": "/",
            "internal_links_to": "/goryashchie-tury/|/vse-vklyucheno/",
            "implementation_task": "curated-landing",
            "owner_hint": "conversion",
            "notes": f"intent_block={block};freq_total={freq};regionId=172",
        }
        by_url[url] = row

    apply_resorts_to_url_map(by_url, today)

    if not dry:
        write_csv(CORE / "06-url-map.csv", url_fields, list(by_url.values()))

    print(json.dumps(report, ensure_ascii=False, indent=2))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())