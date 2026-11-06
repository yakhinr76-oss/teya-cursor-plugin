#!/usr/bin/env python3
"""
Fetch 2 Pexels photos per resort (landscape, size=large) for the home hub table.
Run: PEXELS_API_KEY=... python3 fetch_pexels_resorts_table.py
"""
from __future__ import annotations

import json
import os
import re
import subprocess
import time
import urllib.parse
from typing import Any

API = os.environ.get("PEXELS_API_KEY", "")
# Optional: comma-separated resort keys to fetch only (e.g. "ajman" for partial reruns).
_ONLY_KEYS = {k.strip().lower() for k in os.environ.get("PEXELS_RESORT_ONLY", "").split(",") if k.strip()}

# (direction_slug, direction_label, [(resort_key, label_ru, search_queries, alt_must_substrings?)])
ROWS: list[tuple[str, str, list[tuple[str, str, list[str], list[str] | None]]]] = [
    (
        "turkey",
        "Турция",
        [
            ("antalya", "Анталья", ["antalya turkey mediterranean beach resort sunny"], None),
            ("kemer", "Кемер", ["kemer turkey beach mountains resort sunny"], None),
            ("side", "Сиде", ["side turkey ancient ruins beach resort sunny"], None),
            ("belek", "Белек", ["belek turkey golf resort beach palm sunny"], None),
            ("alanya", "Аланья", ["alanya turkey kleopatra beach castle sunny"], None),
        ],
    ),
    (
        "uae",
        "ОАЭ",
        [
            ("dubai", "Дубай", ["dubai marina skyline beach luxury sunny"], None),
            ("sharjah", "Шарджа", ["sharjah uae corniche mosque waterfront sunny"], None),
            (
                "ras_al_khaimah",
                "Рас-эль-Хайма",
                ["ras al khaimah uae beach hotel waterfront sunny", "al hamra beach ras al khaimah uae"],
                ["khaimah"],
            ),
            (
                "fujairah",
                "Фуджейра",
                ["fujairah uae beach corniche gulf of oman", "al aqah beach fujairah uae"],
                ["fujairah"],
            ),
            (
                "ajman",
                "Аджман",
                [
                    "ajman corniche beach united arab emirates",
                    "ajman city beach uae skyline",
                    "al nuaimiya beach ajman uae",
                    "ajman uae fishing harbour waterfront",
                ],
                # Pexels alts rarely spell "Ajman"; rely on query + mismatch penalties instead.
                None,
            ),
        ],
    ),
    (
        "egypt",
        "Египет",
        [
            ("sharm_el_sheikh", "Шарм-эль-Шейх", ["sharm el sheikh red sea resort coral beach sunny"], None),
            ("hurghada", "Хургада", ["hurghada egypt red sea resort beach palm"], None),
            ("makadi_bay", "Макади-Бей", ["makadi bay egypt red sea resort beach"], None),
            ("marsa_alam", "Марса-Алам", ["marsa alam egypt red sea diving coral beach"], None),
            ("sahl_hasheesh", "Сахл-Хашиш", ["sahl hasheesh hurghada egypt luxury resort"], None),
        ],
    ),
    (
        "thailand",
        "Таиланд",
        [
            ("phuket", "Пхукет", ["phuket thailand tropical beach palm turquoise"], None),
            ("pattaya", "Паттайя", ["pattaya thailand beach waterfront city sunset"], None),
            (
                "samui",
                "Самуи",
                ["koh samui thailand chaweng beach palm", "lamai beach koh samui thailand"],
                ["samui"],
            ),
            ("khao_lak", "Као Лак", ["khao lak thailand beach national park"], None),
            ("krabi", "Краби", ["krabi thailand railay phi phi limestone turquoise"], None),
        ],
    ),
    (
        "russia",
        "Россия",
        [
            ("sochi", "Сочи", ["sochi russia black sea palm promenade sunny"], None),
            ("adler", "Адлер", ["adler sochi russia beach olympic park sea"], None),
            ("imeretinka", "Имеретинка", ["imeretinsky sochi russia olympic park coast"], None),
            ("krasnaya_polyana", "Красная Поляна", ["krasnaya polyana sochi mountains cable car summer"], None),
            ("lazarevskoye", "Лазаревское", ["lazarevskoye sochi russia black sea beach"], None),
        ],
    ),
    (
        "abkhazia",
        "Абхазия",
        [
            ("gagra", "Гагра", ["gagra abkhazia seaside colonnade palm"], None),
            ("pitsunda", "Пицунда", ["pitsunda abkhazia pine beach black sea"], None),
            ("gudauta", "Гудаута", ["gudauta abkhazia black sea coast mountains"], None),
            ("new_afon", "Новый Афон", ["new athos monastery abkhazia coast"], None),
            ("sukhum", "Сухум", ["sukhumi abkhazia seaside boulevard black sea"], None),
        ],
    ),
    (
        "maldives",
        "Мальдивы",
        [
            ("ari_atoll", "Ари", ["maldives ari atoll overwater villa turquoise"], None),
            ("baa_atoll", "Баа", ["maldives baa atoll hanifaru lagoon aerial"], None),
            ("north_male", "Северный Мале", ["north male atoll maldives resort aerial"], None),
            ("south_male", "Южный Мале", ["south male atoll maldives island lagoon"], None),
            ("maafushi", "Маафуши", ["maafushi maldives local island beach guesthouse"], None),
        ],
    ),
    (
        "sri_lanka",
        "Шри-Ланка",
        [
            ("bentota", "Бентота", ["bentota sri lanka beach river palm"], None),
            ("hikkaduwa", "Хиккадува", ["hikkaduwa sri lanka coral beach snorkeling"], None),
            ("unawatuna", "Унаватуна", ["unawatuna sri lanka beach palm golden sand"], None),
            ("wadduwa", "Ваддува", ["wadduwa sri lanka beach coast palm"], None),
            ("mirissa", "Мирисса", ["mirissa sri lanka coconut tree hill beach"], None),
        ],
    ),
    (
        "vietnam",
        "Вьетнам",
        [
            ("nha_trang", "Нячанг", ["nha trang vietnam beach bay city sunny"], None),
            ("phu_quoc", "Фукуок", ["phu quoc vietnam white sand beach turquoise"], None),
            ("mui_ne", "Муйне", ["mui ne vietnam sand dunes beach"], None),
            ("phan_thiet", "Фантьет", ["phan thiet vietnam fishing village beach"], None),
            ("da_nang", "Дананг", ["da nang vietnam my khe beach skyline"], None),
        ],
    ),
    (
        "china_hainan",
        "Китай (Хайнань)",
        [
            ("sanya", "Санья", ["sanya hainan china tropical beach palm"], None),
            ("dadunhai", "Дадунхай", ["dadonghai sanya hainan beach bay"], None),
            ("yalong_bay", "Ялунвань", ["yalong bay sanya hainan resort beach"], None),
            ("sanya_bay", "Санья-Бэй", ["sanya bay hainan beach city coast"], None),
            ("haitang_bay", "Хайтанвань", ["haitang bay sanya hainan luxury resort"], None),
        ],
    ),
    (
        "india_goa",
        "Индия (Гоа)",
        [
            ("calangute", "Калангут", ["calangute goa india beach palm"], None),
            ("candolim", "Кандолим", ["candolim goa india beach sunset"], None),
            ("morjim", "Морджим", ["morjim goa india beach palm quiet"], None),
            ("colva", "Колва", ["colva goa south india beach palm"], None),
            ("palolem", "Палолем", ["palolem goa india beach coconut palm"], None),
        ],
    ),
    (
        "indonesia_bali",
        "Индонезия (Бали)",
        [
            ("nusa_dua", "Нуса-Дуа", ["nusa dua bali beach luxury resort"], None),
            ("sanur", "Санур", ["sanur bali sunrise beach calm"], None),
            ("jimbaran", "Джимбаран", ["jimbaran bali beach seafood sunset"], None),
            ("seminyak", "Семиньяк", ["seminyak bali beach club sunset"], None),
            ("ubud", "Убуд", ["ubud bali rice terraces jungle temple"], None),
        ],
    ),
    (
        "seychelles",
        "Сейшелы",
        [
            ("mahe", "Маэ", ["mahe seychelles beach beau vallon tropical"], None),
            ("praslin", "Праслин", ["praslin seychelles anse lazio beach"], None),
            ("la_digue", "Ла-Диг", ["la digue seychelles granite beach bicycle"], None),
            ("beau_vallon", "Бо-Валлон", ["beau vallon mahe seychelles beach"], None),
            ("cote_dor", "Кот-д'Ор", ["cote dor praslin seychelles beach palm"], None),
        ],
    ),
    (
        "uzbekistan",
        "Узбекистан",
        [
            ("samarkand", "Самарканд", ["registan samarkand uzbekistan sunny"], None),
            ("bukhara", "Бухара", ["bukhara uzbekistan poi kalyan minaret"], None),
            ("khiva", "Хива", ["khiva uzbekistan itchan kala old town"], None),
            ("tashkent", "Ташкент", ["tashkent uzbekistan independence square metro"], None),
        ],
    ),
    (
        "armenia",
        "Армения",
        [
            ("yerevan", "Ереван", ["yerevan armenia mount ararat cascade"], None),
            ("sevan", "Севан", ["lake sevan armenia sevanavank monastery"], None),
            ("dilijan", "Дилижан", ["dilijan armenia forest mountains resort"], None),
            ("jermuk", "Джермук", ["jermuk armenia waterfall mountains spa"], None),
            ("tsaghkadzor", "Цахкадзор", ["tsaghkadzor armenia ski resort summer green"], None),
        ],
    ),
]

DULL = re.compile(r"\b(grey|gray|fog|overcast|monochrome)\b", re.I)


def search(q: str) -> list[dict[str, Any]]:
    if not API:
        return []
    params = {
        "query": q,
        "per_page": "20",
        "orientation": "landscape",
        "size": "large",
    }
    url = "https://api.pexels.com/v1/search?" + urllib.parse.urlencode(params)
    backoff = 2.0
    for attempt in range(10):
        try:
            out = subprocess.check_output(
                ["curl", "-s", "-H", f"Authorization: {API}", url],
                text=True,
                timeout=40,
            )
            data = json.loads(out)
        except (subprocess.CalledProcessError, json.JSONDecodeError, subprocess.TimeoutExpired):
            return []
        if data.get("status") == 429 or data.get("code") == "Too Many Requests":
            time.sleep(min(backoff, 120.0))
            backoff *= 1.8
            continue
        return data.get("photos", [])
    return []


def lum(p: dict) -> float:
    c = p.get("avg_color") or "#888888"
    try:
        r, g, b = int(c[1:3], 16), int(c[3:5], 16), int(c[5:7], 16)
        return 0.299 * r + 0.587 * g + 0.114 * b
    except ValueError:
        return 128.0


def score(p: dict, q: str, alt_must: list[str] | None) -> int:
    alt = (p.get("alt") or "").lower()
    ql = q.lower()
    w, h = p.get("width", 0), p.get("height", 0)
    if h and w / h < 1.2:
        return -999
    if alt_must and not all(sub in alt for sub in alt_must):
        return -999

    s = 0
    for tok in re.findall(r"[a-z0-9]+", q.lower()):
        if len(tok) > 3 and tok in alt:
            s += 1
    # Geo mismatches (common Pexels noise)
    if "ras al khaimah" in ql and ("oman" in alt or "musandam" in alt) and "ras" not in alt:
        s -= 25
    if "fujairah" in ql and "khor fakkan" in alt and "fujairah" not in alt:
        s -= 10
    if "ajman" in ql:
        if "ajman" in alt:
            s += 12
        if any(
            x in alt
            for x in (
                "sharjah",
                "dubai",
                "abu dhabi",
                "khor fakkan",
                "ras al khaimah",
                "fujairah",
                "al ain",
            )
        ):
            s -= 25
        # Queries name UAE; reject obvious wrong countries (common vector / stock mismatches).
        if any(
            x in alt
            for x in (
                "florida",
                "apollo beach",
                "usa",
                "united states",
                "california",
                "texas",
            )
        ):
            s -= 40
    if "pattaya" in ql and "phuket" in alt and "pattaya" not in alt:
        s -= 18
    if ("samui" in ql or "koh samui" in ql) and ("pha ngan" in alt or "phangan" in alt) and "samui" not in alt:
        s -= 15
    s -= 4 * len(DULL.findall(alt))
    L = lum(p)
    if L < 70:
        s -= 2
    elif L > 95:
        s += 1
    if w >= 4500:
        s += 2
    elif w >= 3000:
        s += 1
    return s


def photo_dict(p: dict, query_used: str) -> dict[str, Any]:
    pid = p["id"]
    ext = "png" if p.get("src", {}).get("original", "").endswith(".png") else "jpeg"
    orig = f"https://images.pexels.com/photos/{pid}/pexels-photo-{pid}.{ext}"
    thumb = f"{orig}?auto=compress&cs=tinysrgb&w=420&h=260&fit=crop"
    return {
        "id": pid,
        "page": p["url"],
        "original": orig,
        "thumb": thumb,
        "width": p["width"],
        "height": p["height"],
        "author": p["photographer"],
        "alt": p.get("alt") or "",
        "avg_color": p.get("avg_color"),
        "query_used": query_used,
    }


def top_two(queries: list[str], alt_must: list[str] | None) -> tuple[list[dict], str]:
    best: list[tuple[int, dict, str]] = []
    for q in queries:
        for p in search(q):
            sc = score(p, q, alt_must)
            # -999 = hard reject (aspect ratio, alt_must). Soft score may be negative.
            if sc == -999:
                continue
            best.append((sc, p, q))
        time.sleep(0.45)
    best.sort(key=lambda x: -x[0])
    out: list[dict] = []
    seen: set[int] = set()
    for sc, p, q in best:
        if p["id"] in seen:
            continue
        seen.add(p["id"])
        out.append(photo_dict(p, q))
        if len(out) >= 2:
            break
    qnote = queries[0] if queries else ""
    return out, qnote


def load_full_list_pexels_ids(md_path: str) -> set[int]:
    if not os.path.isfile(md_path):
        return set()
    try:
        text = open(md_path, encoding="utf-8").read()
    except OSError:
        return set()
    return {int(m) for m in re.findall(r"\[ID\s+(\d+)\]", text)}


def load_existing_resorts(json_path: str) -> dict[str, dict[str, Any]]:
    if not os.path.isfile(json_path):
        return {}
    try:
        data = json.load(open(json_path, encoding="utf-8"))
    except (json.JSONDecodeError, OSError):
        return {}
    by_key: dict[str, dict[str, Any]] = {}
    for d in data.get("directions", []):
        for r in d.get("resorts", []):
            k = r.get("key")
            if isinstance(k, str):
                by_key[k] = r
    return by_key


def main() -> None:
    here = os.path.dirname(os.path.abspath(__file__))
    json_path = os.path.join(here, "pexels-resorts-table-picks.json")
    existing = load_existing_resorts(json_path) if _ONLY_KEYS else {}

    directions: list[dict[str, Any]] = []

    for slug, label, resorts in ROWS:
        resorts_out: list[dict[str, Any]] = []
        for key, label_ru, queries, alt_must in resorts:
            if _ONLY_KEYS and key.lower() not in _ONLY_KEYS:
                prev = existing.get(key)
                photos = list(prev.get("photos", [])) if prev else []
                resorts_out.append(
                    {
                        "key": key,
                        "label_ru": label_ru,
                        "queries": queries,
                        "photos": photos,
                    }
                )
                continue
            photos, _ = top_two(queries, alt_must)
            resorts_out.append(
                {
                    "key": key,
                    "label_ru": label_ru,
                    "queries": queries,
                    "photos": photos,
                }
            )
            print(f"{label} / {key}: {len(photos)}", flush=True)
        directions.append({"slug": slug, "label": label, "resorts": resorts_out})

    total_photos = sum(len(r["photos"]) for d in directions for r in d["resorts"])
    all_photo_ids: list[int] = []
    for d in directions:
        for r in d["resorts"]:
            for p in r["photos"]:
                all_photo_ids.append(int(p["id"]))
    id_set = set(all_photo_ids)
    dup_within = len(all_photo_ids) - len(id_set)
    full_list_md = os.path.join(here, "pexels-home-v4-picks-FULL-LIST.md")
    full_list_ids = load_full_list_pexels_ids(full_list_md)
    overlap = sorted(id_set & full_list_ids)
    out = {
        "version": 1,
        "description": "2 Pexels photos per resort (hub geography table)",
        "photos_per_resort_target": 2,
        "total_resorts": sum(len(d["resorts"]) for d in directions),
        "total_photos": total_photos,
        "unique_photo_ids": len(id_set),
        "duplicate_slots_within_this_batch": dup_within,
        "full_list_overlap_reference": "pexels-home-v4-picks-FULL-LIST.md",
        "overlap_with_full_list_count": len(overlap),
        "overlap_with_full_list_ids": overlap,
        "directions": directions,
    }

    json.dump(out, open(json_path, "w", encoding="utf-8"), ensure_ascii=False, indent=2)

    # Markdown supplement for humans
    lines = [
        "# Pexels — курорты по направлениям (по 2 фото)",
        "",
        f"Всего курортов: **{out['total_resorts']}**, слотов снимков: **{out['total_photos']}**, уникальных ID: **{out['unique_photo_ids']}**.",
        f"Пересечение с `pexels-home-v4-picks-FULL-LIST.md` (280): **{out['overlap_with_full_list_count']}** уникальных ID; дубликаты только внутри этого набора: **{out['duplicate_slots_within_this_batch']}** слотов.",
        "",
    ]
    for d in directions:
        lines.append(f"## {d['label']}")
        lines.append("")
        lines.append("| Курорт | #1 | #2 |")
        lines.append("| --- | --- | --- |")
        for r in d["resorts"]:
            p = r["photos"]
            c1 = f"[{p[0]['id']}]({p[0]['page']})" if len(p) > 0 else "—"
            c2 = f"[{p[1]['id']}]({p[1]['page']})" if len(p) > 1 else "—"
            name = r.get("label_ru") or r["key"].replace("_", " ")
            lines.append(f"| {name} | {c1} | {c2} |")
        lines.append("")

    md_path = os.path.join(here, "pexels-resorts-table-picks.md")
    open(md_path, "w", encoding="utf-8").write("\n".join(lines))

    print("written", json_path, md_path, "photos", total_photos)


if __name__ == "__main__":
    main()
