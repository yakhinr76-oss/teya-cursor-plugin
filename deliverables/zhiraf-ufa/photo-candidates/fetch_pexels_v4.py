#!/usr/bin/env python3
"""Fetch 2 Pexels photos per query; landscape + size=large."""
from __future__ import annotations

import json
import os
import re
import subprocess
import time
import urllib.parse
from typing import Any

API = os.environ.get("PEXELS_API_KEY", "")

# (block, tab, title, [(group_name, [queries])])
CATALOG: list[tuple[str, str | None, str, list[tuple[str, list[str]]]]] = [
    (
        "dest",
        "ufa",
        "Турция",
        [
            (
                "Пляж",
                [
                    "oludeniz blue lagoon aerial",
                    "antalya turquoise beach sunshine",
                    "kas kaputas beach coastal cliffs",
                    "turkey mediterranean beach sun loungers",
                    "turkish riviera yacht crystal clear sea",
                ],
            ),
            (
                "Атмосфера",
                [
                    "cappadocia hot air balloons sunrise",
                    "goreme cave hotel rooftop breakfast",
                    "cappadocia fairy chimneys golden hour",
                    "turkish tea terrace scenic view",
                    "cappadocia valley horseback riding",
                ],
            ),
        ],
    ),
    (
        "dest",
        "ufa",
        "ОАЭ",
        [
            (
                "Город / статус",
                [
                    "dubai burj khalifa night skyline",
                    "dubai marina luxury yachts",
                    "dubai rooftop infinity pool skyline",
                    "dubai downtown luxury hotel view",
                    "dubai palm jumeirah aerial",
                ],
            ),
            (
                "Пляж",
                [
                    "dubai jbr beach sunny",
                    "dubai beach burj al arab",
                    "palm jumeirah luxury beach resort",
                    "dubai beach cabana sea view",
                    "dubai beachfront infinity pool",
                ],
            ),
        ],
    ),
    (
        "dest",
        "ufa",
        "Египет",
        [
            (
                "Море / риф",
                [
                    "red sea coral reef colorful fish",
                    "egypt snorkeling crystal clear water",
                    "red sea diving underwater sun rays",
                    "egypt turquoise lagoon aerial",
                    "red sea boat trip swimming",
                ],
            ),
            (
                "Отель",
                [
                    "sharm el sheikh resort swimming pool",
                    "hurghada beachfront hotel palm trees",
                    "egypt resort water park aerial",
                    "red sea hotel beach sun loungers",
                    "egypt luxury resort tropical garden",
                ],
            ),
        ],
    ),
    (
        "dest",
        "ufa",
        "Таиланд",
        [
            (
                "Якоря",
                [
                    "phi phi longtail boat turquoise water",
                    "railay beach limestone cliffs",
                    "phuket tropical beach palm trees",
                    "thailand island hopping speedboat",
                    "thai beach restaurant ocean sunset",
                ],
            ),
        ],
    ),
    (
        "dest",
        "ufa",
        "Россия (Сочи)",
        [
            (
                "Якоря",
                [
                    "sochi palm trees seaside promenade",
                    "sochi mountain cable car panorama",
                    "sochi black sea beach sunny",
                    "sochi arboretum tropical garden",
                    "sochi seaport sunset yachts",
                ],
            ),
        ],
    ),
    (
        "dest",
        "ufa",
        "Абхазия",
        [
            (
                "Якоря",
                [
                    "lake ritsa mountain reflections",
                    "gagra seaside colonnade sunny",
                    "new athos monastery aerial",
                    "pitsunda beach pine trees sea",
                    "abkhazia mountain waterfall forest",
                ],
            ),
        ],
    ),
    (
        "dest",
        "mow",
        "Мальдивы",
        [
            (
                "Якоря",
                [
                    "maldives overwater villas aerial",
                    "maldives white sandbank turquoise ocean",
                    "maldives floating breakfast private pool",
                    "maldives hammock tropical beach",
                    "maldives snorkeling sea turtle",
                    "maldives overwater villas turquoise lagoon aerial",
                    "maldives white sand beach palm trees sunny",
                    "maldives private island resort panoramic view",
                    "maldives sandbank crystal clear ocean",
                    "maldives tropical reef snorkeling colorful fish",
                ],
            ),
        ],
    ),
    (
        "dest",
        "mow",
        "Шри-Ланка",
        [
            (
                "Якоря",
                [
                    "sri lanka tea plantation green hills",
                    "mirissa coconut tree hill ocean",
                    "sri lanka train nine arch bridge",
                    "sri lanka elephants safari",
                    "weligama surfing tropical beach",
                    "sri lanka tea plantations green hills sunny",
                    "mirissa coconut tree hill turquoise ocean",
                    "nine arch bridge ella train tropical forest",
                    "sri lanka elephants safari national park",
                    "unawatuna beach palm trees golden sand",
                ],
            ),
        ],
    ),
    (
        "dest",
        "mow",
        "Вьетнам",
        [
            (
                "Якоря",
                [
                    "ha long bay cruise limestone islands",
                    "hoi an colorful lanterns evening",
                    "da nang beach sunny coastline",
                    "vietnam terraced rice fields green",
                    "phu quoc tropical beach palm trees",
                    "ha long bay limestone islands cruise sunny",
                    "phu quoc white sand beach turquoise water",
                    "hoi an ancient town colorful lanterns",
                    "nha trang beach skyline sunny panorama",
                    "vietnam rice terraces green mountains",
                ],
            ),
        ],
    ),
    (
        "dest",
        "mow",
        "Китай (Хайнань)",
        [
            (
                "Якоря",
                [
                    "sanya yalong bay turquoise water white sand",
                    "hainan tropical beach coconut palm trees",
                    "sanya luxury resort infinity pool ocean",
                    "wuzhizhou island crystal clear sea aerial",
                    "nanshan guanyin statue sanya ocean",
                ],
            ),
        ],
    ),
    (
        "dest",
        "mow",
        "Индия (Гоа)",
        [
            (
                "Якоря",
                [
                    "goa palolem beach palm trees sunny",
                    "goa colorful beach huts ocean",
                    "agonda beach golden sand sunset",
                    "old goa portuguese church sunny",
                    "dudhsagar waterfall tropical forest india",
                ],
            ),
        ],
    ),
    (
        "dest",
        "mow",
        "Индонезия (Бали)",
        [
            (
                "Якоря",
                [
                    "nusa penida kelingking beach aerial",
                    "ubud rice terraces morning sunlight",
                    "bali jungle infinity pool",
                    "uluwatu temple ocean cliff sunset",
                    "bali tropical waterfall swimming",
                    "bali kelingking beach nusa penida aerial",
                    "ubud rice terraces green morning sunlight",
                    "bali jungle villa infinity pool",
                    "ulun danu beratan temple lake mountains",
                    "bali tropical waterfall turquoise pool",
                ],
            ),
        ],
    ),
    (
        "dest",
        "mow",
        "Сейшелы",
        [
            (
                "Якоря",
                [
                    "anse source d argent granite rocks turquoise",
                    "seychelles white sand beach palm trees",
                    "la digue island tropical beach aerial",
                    "seychelles giant tortoise tropical island",
                    "anse lazio praslin crystal clear water",
                ],
            ),
        ],
    ),
    (
        "dest",
        "mow",
        "Узбекистан",
        [
            (
                "Якоря",
                [
                    "samarkand registan square sunny blue tiles",
                    "bukhara poi kalyan minaret golden hour",
                    "khiva old town city walls panorama",
                    "shah i zinda samarkand turquoise mosaics",
                    "uzbekistan traditional bazaar spices colorful",
                ],
            ),
        ],
    ),
    (
        "dest",
        "mow",
        "Армения",
        [
            (
                "Якоря",
                [
                    "yerevan cascade mount ararat sunny",
                    "lake sevan sevanavank monastery blue water",
                    "garni temple armenia mountain landscape",
                    "geghard monastery armenia rocky cliffs",
                    "armenian lavash baking traditional food",
                ],
            ),
        ],
    ),
    (
        "scenario",
        None,
        "Семья с детьми",
        [
            (
                "Семейный отдых",
                [
                    "happy family building sandcastle beach",
                    "child splashing sea water laughing",
                    "parents children swimming resort pool",
                    "father daughter playing beach waves",
                    "family eating ice cream seaside",
                ],
            ),
        ],
    ),
    (
        "scenario",
        None,
        "Для пар и релакса",
        [
            (
                "Романтика",
                [
                    "couple holding hands beach sunset",
                    "romantic candlelight dinner ocean",
                    "couple relaxing private infinity pool",
                    "couple sailing yacht golden hour",
                    "couple breakfast balcony sea view",
                ],
            ),
        ],
    ),
    (
        "scenario",
        None,
        "Хит «Цена / Качество»",
        [
            (
                "Аквапарк",
                [
                    "child water slide joyful splash",
                    "family inflatable raft water park",
                    "colorful resort aqua park slides",
                    "kids splash playground water fountains",
                    "couple lazy river swimming pool",
                ],
            ),
        ],
    ),
    (
        "scenario",
        None,
        "Город и шопинг",
        [
            (
                "Премиум / VIP",
                [
                    "private villa infinity pool ocean",
                    "luxury yacht champagne sunset",
                    "beach cabana butler service",
                    "luxury hotel suite panoramic sea view",
                    "fine dining terrace ocean sunset",
                ],
            ),
        ],
    ),
    (
        "scenario",
        None,
        "Всё включено / Релакс",
        [
            (
                "Релакс",
                [
                    "woman reading book beach sunbed",
                    "tropical resort cocktail poolside",
                    "beach hammock palm trees ocean",
                    "outdoor spa massage sea view",
                    "resort breakfast terrace sea view",
                ],
            ),
        ],
    ),
    (
        "scenario",
        None,
        "Экскурсионные туры",
        [
            (
                "Экскурсии",
                [
                    "traveler exploring historic old town",
                    "tour guide small group ancient ruins",
                    "tourist photographing ornate architecture",
                    "travelers tasting food local market",
                    "backpacker walking cobblestone street sunny",
                ],
            ),
        ],
    ),
]

DULL = re.compile(r"\b(grey|gray|fog|overcast|monochrome)\b", re.I)
WRONG_GEO = {
    "oludeniz": re.compile(r"\b(bacalar|mexico|caribbean)\b", re.I),
    "turkey": re.compile(r"\b(mexico|spain only)\b", re.I),
}


def search(q: str) -> list[dict[str, Any]]:
    if not API:
        return []
    params = {
        "query": q,
        "per_page": "25",
        "orientation": "landscape",
        "size": "large",
    }
    url = "https://api.pexels.com/v1/search?" + urllib.parse.urlencode(params)
    try:
        out = subprocess.check_output(
            ["curl", "-s", "-H", f"Authorization: {API}", url],
            text=True,
            timeout=45,
        )
        data = json.loads(out)
    except (subprocess.CalledProcessError, json.JSONDecodeError, subprocess.TimeoutExpired):
        return []
    if "photos" not in data:
        return []
    return data["photos"]


def lum(p: dict) -> float:
    c = p.get("avg_color") or "#888888"
    try:
        r, g, b = int(c[1:3], 16), int(c[3:5], 16), int(c[5:7], 16)
        return 0.299 * r + 0.587 * g + 0.114 * b
    except ValueError:
        return 128.0


def photo_dict(p: dict, query: str, score: int) -> dict[str, Any]:
    pid = p["id"]
    ext = "png" if p.get("src", {}).get("original", "").endswith(".png") else "jpeg"
    orig = f"https://images.pexels.com/photos/{pid}/pexels-photo-{pid}.{ext}"
    thumb = f"{orig}?auto=compress&cs=tinysrgb&w=480&h=300&fit=crop"
    return {
        "query": query,
        "score": score,
        "id": pid,
        "page": p["url"],
        "original": orig,
        "thumb": thumb,
        "width": p["width"],
        "height": p["height"],
        "author": p["photographer"],
        "alt": p.get("alt") or "",
        "avg_color": p.get("avg_color"),
    }


def score_photo(p: dict, query: str, scenario: bool) -> int:
    alt = p.get("alt") or ""
    al = alt.lower()
    w, h = p.get("width", 0), p.get("height", 0)
    if h and w / h < 1.25:
        return -999
    s = 0
    for tok in re.findall(r"[a-z0-9]+", query.lower()):
        if len(tok) > 3 and tok in al:
            s += 2
    for key, rx in WRONG_GEO.items():
        if key in query.lower() and rx.search(alt):
            s -= 20
    s -= 4 * len(DULL.findall(alt))
    L = lum(p)
    if scenario:
        if any(x in al for x in ("smil", "laugh", "joy", "happy", "romantic", "couple", "family", "child")):
            s += 4
        if "faceless" in al or "laptop" in al:
            s -= 10
    else:
        if L < 75:
            s -= 4
        elif L > 100:
            s += 2
    if w >= 5000:
        s += 1
    return s


def top_two(query: str, scenario: bool) -> list[dict]:
    ranked: list[tuple[int, dict]] = []
    for p in search(query):
        sc = score_photo(p, query, scenario)
        if sc < 0:
            continue
        ranked.append((sc, p))
    ranked.sort(key=lambda x: -x[0])
    out: list[dict] = []
    seen: set[int] = set()
    for sc, p in ranked:
        if p["id"] in seen:
            continue
        seen.add(p["id"])
        out.append(photo_dict(p, query, sc))
        if len(out) >= 2:
            break
    return out


def main() -> None:
    here = os.path.dirname(os.path.abspath(__file__))
    v3_path = os.path.join(here, "pexels-home-v3-picks.json")
    v3 = json.load(open(v3_path, encoding="utf-8")) if os.path.isfile(v3_path) else {"cards": []}
    v3_by_title = {c["title"]: c for c in v3.get("cards", [])}

    cards_out: list[dict] = []
    total_q = sum(len(qs) for _, _, _, groups in CATALOG for _, qs in groups)
    done = 0

    for block, tab, title, groups in CATALOG:
        scenario = block == "scenario"
        groups_out: list[dict] = []
        for gname, queries in groups:
            # dedupe queries preserving order
            seen_q: set[str] = set()
            uq: list[str] = []
            for q in queries:
                k = q.strip().lower()
                if k not in seen_q:
                    seen_q.add(k)
                    uq.append(q.strip())
            queries_out: list[dict] = []
            for q in uq:
                done += 1
                photos = top_two(q, scenario)
                queries_out.append({"query": q, "photos": photos})
                print(f"[{done}/{total_q}] {title} / {q[:42]} -> {len(photos)}", flush=True)
                time.sleep(0.18)
            groups_out.append({"name": gname, "queries": queries_out})

        legacy = v3_by_title.get(title, {})
        cards_out.append(
            {
                "block": block,
                "tab": tab,
                "title": title,
                "recommended_id": legacy.get("recommended_id"),
                "recommended_query": legacy.get("recommended_query"),
                "groups": groups_out,
            }
        )

    out = {"version": 4, "photos_per_query": 2, "cards": cards_out}
    out_path = os.path.join(here, "pexels-home-v4-picks.json")
    json.dump(out, open(out_path, "w", encoding="utf-8"), ensure_ascii=False, indent=2)
    print("written", out_path, "cards", len(cards_out))


if __name__ == "__main__":
    main()
