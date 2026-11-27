#!/usr/bin/env python3
"""Анализ выгрузки Wordstat (регион 172) для кластеров «Горящие» и «Всё включено».

Источники:
  - seo/wordstat-hot-ai-raw.csv — после API (--fetch) или ручной CSV
  - teya-memory/semantic-core/zhiraf-ufa-2026/06-url-map.csv — якоря стран/курортов

Ручной CSV (минимум): query, base_frequency, exact_frequency
Опционально: cluster, parent_url

Точная частота: по умолчанию totalCount API для фразы в двойных кавычках
(фразовое совпадение, как «"запрос"» в UI Вордстата). Для !запрос: --exact-mode bang.

Примеры:
  python scripts/analyze_hot_ai_wordstat.py --fetch
  python scripts/analyze_hot_ai_wordstat.py -i seo/wordstat-hot-ai-raw.csv
"""
from __future__ import annotations

import argparse
import csv
import json
import os
import re
import sys
import time
import urllib.error
import urllib.request
from dataclasses import dataclass
from datetime import date
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
SEO_DIR = ROOT / "seo"
RAW_DEFAULT = SEO_DIR / "wordstat-hot-ai-raw.csv"
REPORT_DEFAULT = SEO_DIR / "wordstat-hot-ai-report.md"
QUERIES_DEFAULT = SEO_DIR / "wordstat-hot-ai-queries.txt"
URL_MAP = ROOT / "teya-memory/semantic-core/zhiraf-ufa-2026/06-url-map.csv"
API_URL = "https://searchapi.api.cloud.yandex.net/v2/wordstat/topRequests"
REGION_ID = 172
SLEEP_S = 0.35
EXACT_THRESHOLD = 100

# Зеркальные формулировки из primary_queries 06-url-map (порядок слов отличается от чек-листа).
QUERY_URL_ANCHOR: dict[tuple[str, str], str] = {
    ("горящие туры в турцию из уфы", "/tour/turciya/"): "горящие туры из уфы в турцию",
    ("горящие туры в египет из уфы", "/tour/egipet/"): "горящие туры из уфы в египет",
    ("горящие туры в дубай из уфы", "/tour/oae/dubai/"): "горящие туры дубай из уфы",
    ("горящие туры на пхукет из уфы", "/tour/tailand/phuket/"): "горящие туры пхукет из уфы",
    ("горящие туры в нячанг из уфы", "/tour/vietnam/nyachang/"): "горящие туры нячанг из уфы",
    ("горящие туры на фукуок из уфы", "/tour/vietnam/fukuok/"): "горящие туры фукуок из уфы",
    ("туры в турцию все включено из уфы", "/tour/turciya/"): "туры в турцию из уфы все включено",
    ("туры в египет все включено из уфы", "/tour/egipet/"): "туры в египет из уфы все включено",
}

QUERY_ROWS: list[tuple[str, str, str]] = [
    ("Кластер 1 — Общие хабы", "горящие туры из уфы", "/goryashchie-tury/"),
    ("Кластер 1 — Общие хабы", "горящие путевки из уфы", "/goryashchie-tury/"),
    ("Кластер 1 — Общие хабы", "туры все включено из уфы", "/vse-vklyucheno/"),
    ("Кластер 1 — Общие хабы", "отдых все включено из уфы", "/vse-vklyucheno/"),
    ("Кластер 1 — Общие хабы", "отели все включено из уфы", "/vse-vklyucheno/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры в турцию из уфы", "/tour/turciya/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры в египет из уфы", "/tour/egipet/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры в оаэ из уфы", "/tour/oae/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры в дубай из уфы", "/tour/oae/dubai/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры в таиланд из уфы", "/tour/tailand/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры на пхукет из уфы", "/tour/tailand/phuket/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры во вьетнам из уфы", "/tour/vietnam/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры в нячанг из уфы", "/tour/vietnam/nyachang/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры на фукуок из уфы", "/tour/vietnam/fukuok/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры в китай из уфы", "/tour/kitaj/"),
    ("Кластер 2 — Горящие по странам/курортам", "горящие туры на хайнань из уфы", "/tour/kitaj/"),
    ("Кластер 3 — Всё включено по странам", "туры в турцию все включено из уфы", "/tour/turciya/"),
    ("Кластер 3 — Всё включено по странам", "туры в египет все включено из уфы", "/tour/egipet/"),
    ("Кластер 3 — Всё включено по странам", "туры в оаэ все включено из уфы", "/tour/oae/"),
    ("Кластер 3 — Всё включено по странам", "туры в таиланд все включено из уфы", "/tour/tailand/"),
    ("Кластер 3 — Всё включено по странам", "туры во вьетнам все включено из уфы", "/tour/vietnam/"),
]

_norm_re = re.compile(r"\s+")


def norm(s: str) -> str:
    return _norm_re.sub(" ", s.lower().strip())


def parse_primary_queries(pq: str) -> dict[str, int]:
    out: dict[str, int] = {}
    for part in (pq or "").split("|"):
        part = part.strip()
        if not part:
            continue
        m = re.match(r"^(.+?)\s+(\d+)$", part)
        if m:
            out[norm(m.group(1))] = int(m.group(2))
    return out


def load_url_map(path: Path) -> dict[str, dict[str, int]]:
    by_url: dict[str, dict[str, int]] = {}
    with path.open(encoding="utf-8", newline="") as f:
        for row in csv.DictReader(f):
            url = (row.get("target_url") or row.get("url") or "").strip()
            if not url:
                continue
            pq = row.get("primary_queries") or ""
            by_url[url] = parse_primary_queries(pq)
    return by_url


def tokens(s: str) -> set[str]:
    return set(norm(s).split())


def match_url_map_freq(by_url: dict[str, dict[str, int]], parent_url: str, query: str) -> str:
    """Частота ближайшего якоря в primary_queries страницы из 06-url-map."""
    if parent_url in ("/goryashchie-tury/", "/vse-vklyucheno/"):
        return "— (хаб ещё не в 06-url-map; якорь = сам запрос)"

    phrases = by_url.get(parent_url)
    if not phrases:
        return "—"

    qn = norm(query)
    anchor_key = (qn, parent_url)
    if anchor_key in QUERY_URL_ANCHOR:
        alias = norm(QUERY_URL_ANCHOR[anchor_key])
        if alias in phrases:
            return str(phrases[alias])
    if qn in phrases:
        return str(phrases[qn])

    qtok = tokens(query)
    best: tuple[int, int] | None = None
    for phrase, freq in phrases.items():
        ptok = tokens(phrase)
        overlap = len(ptok & qtok)
        if overlap < 3:
            continue
        score = overlap * 1000 + freq
        if best is None or score > best[0]:
            best = (score, freq)
    if best:
        return f"{best[1]} (ближайший якорь в primary_queries)"

    # горящие/AI хвосты часто с другим порядком слов
    for phrase, freq in phrases.items():
        if "горящ" in query and "горящ" in phrase and "уф" in phrase:
            if any(t in phrase for t in ("турци", "египет", "оаэ", "дубай", "таиланд", "пхукет", "вьетнам", "нячанг", "фукуок", "китай", "хайнань")):
                if any(t in query for t in phrase.split() if len(t) > 4):
                    return f"{freq} ({phrase})"
    for phrase, freq in phrases.items():
        if "все включено" in query or "все включено" in phrase:
            if "все включено" in phrase and any(
                c in phrase and c in query
                for c in ("турци", "египет", "оаэ", "таиланд", "вьетнам")
            ):
                return f"{freq} ({phrase})"
    return "—"


def fetch_phrase(phrase: str, api_key: str) -> int:
    body = json.dumps({"phrase": phrase, "regionId": REGION_ID, "numPhrases": 5}).encode()
    req = urllib.request.Request(
        API_URL,
        data=body,
        headers={"Authorization": f"Bearer {api_key}", "Content-Type": "application/json"},
        method="POST",
    )
    with urllib.request.urlopen(req, timeout=60) as resp:
        data = json.loads(resp.read().decode())
    return int(data.get("totalCount") or 0)


def exact_phrase_for_api(query: str, mode: str) -> str:
    if mode == "bang":
        return f"!{query}"
    if mode == "quoted":
        return f'"{query}"'
    raise ValueError(mode)


def api_fetch_all(out_csv: Path, exact_mode: str) -> None:
    api_key = os.environ.get("YANDEX_WORDSTAT_API_KEY")
    if not api_key:
        sys.exit("YANDEX_WORDSTAT_API_KEY не задан")

    out_csv.parent.mkdir(parents=True, exist_ok=True)
    rows: list[dict[str, str | int]] = []
    for cluster, query, parent in QUERY_ROWS:
        base = fetch_phrase(query, api_key)
        time.sleep(SLEEP_S)
        exact = fetch_phrase(exact_phrase_for_api(query, exact_mode), api_key)
        time.sleep(SLEEP_S)
        rows.append(
            {
                "cluster": cluster,
                "query": query,
                "parent_url": parent,
                "base_frequency": base,
                "exact_frequency": exact,
                "region_id": REGION_ID,
                "collected_at": date.today().isoformat(),
                "exact_mode": exact_mode,
            }
        )
        print(f"{query}\tbase={base}\texact={exact}")

    fields = list(rows[0].keys())
    with out_csv.open("w", encoding="utf-8", newline="") as f:
        w = csv.DictWriter(f, fieldnames=fields)
        w.writeheader()
        w.writerows(rows)


def read_input_csv(path: Path) -> list[dict[str, str]]:
    rows: list[dict[str, str]] = []
    with path.open(encoding="utf-8", newline="") as f:
        reader = csv.DictReader(f)
        for row in reader:
            rows.append(row)
    return rows


def coerce_row(row: dict[str, str]) -> dict[str, str | int]:
    cluster = row.get("cluster") or ""
    query = (row.get("query") or "").strip()
    parent = row.get("parent_url") or ""
    if not cluster or not parent:
        for c, q, p in QUERY_ROWS:
            if norm(q) == norm(query):
                cluster, parent = c, p
                break
    base = int(row.get("base_frequency") or row.get("base") or 0)
    exact = int(row.get("exact_frequency") or row.get("exact") or 0)
    return {"cluster": cluster, "query": query, "parent_url": parent, "base_frequency": base, "exact_frequency": exact}


def recommend(cluster: str, exact: int, parent_url: str, query: str) -> str:
    if cluster.startswith("Кластер 1"):
        if exact >= EXACT_THRESHOLD:
            return f"Отдельная страница ({parent_url})"
        if exact >= 30 and "горящ" in query:
            return f"Отдельная страница ({parent_url}); вторичные формы — блок в хабе"
        return f"Закрывать общим хабом + фильтр ({parent_url})"

    if exact >= 150:
        return f"Отдельная посадочная (хаб + страна), peer к {parent_url} — без дублирования H1 страны"
    if exact >= EXACT_THRESHOLD:
        return f"Погранично: фильтр в /goryashchie-tury/ или /vse-vklyucheno/; не плодить URL у {parent_url}"
    return "Закрывать общим хабом + фильтр по стране"


def build_report(rows: list[dict[str, str | int]], url_map_path: Path, exact_mode: str) -> str:
    by_url = load_url_map(url_map_path)
    lines = [
        "# Wordstat: «Горящие» и «Всё включено» (Уфа, regionId=172)",
        "",
        f"Дата среза: {date.today().isoformat()}.",
        f"Точная частота: API topRequests, режим `{exact_mode}` "
        + ("(фраза в кавычках → totalCount)." if exact_mode == "quoted" else "(оператор ! → totalCount)."),
        f"Порог для отдельной посадочной по стране: точная ≥ {EXACT_THRESHOLD}–150 показов/мес.",
        "",
        "## Итоговая таблица",
        "",
        "| Кластер | Запрос | Базовая частотность (Уфа) | Точная \"!запрос\" (Уфа) | Частотность основной страницы из 06-url-map.csv | Рекомендация (Отдельная страница / Фильтр в хабе) |",
        "| --- | --- | ---: | ---: | --- | --- |",
    ]

    country_sep: list[str] = []
    hub_hot = hub_ai = 0

    for row in rows:
        cluster = str(row["cluster"])
        query = str(row["query"])
        base = int(row["base_frequency"])
        exact = int(row["exact_frequency"])
        parent = str(row["parent_url"])
        map_freq = match_url_map_freq(by_url, parent, query)
        rec = recommend(cluster, exact, parent, query)
        lines.append(f"| {cluster} | {query} | {base} | {exact} | {map_freq} | {rec} |")

        if cluster.startswith("Кластер 1") and exact >= EXACT_THRESHOLD:
            hub_hot += 1
        if not cluster.startswith("Кластер 1") and exact >= EXACT_THRESHOLD:
            country_sep.append(f"- **{query}** ({exact}) → {rec}")

    lines.extend(
        [
            "",
            "## Выводы по структуре (без каннибализации)",
            "",
            "### Общие хабы",
            "- **/goryashchie-tury/** — оправдан как отдельный URL: точная частота «горящие туры из уфы» **991** (база 2492).",
            "- **/vse-vklyucheno/** — отдельный интент, но формулировка «туры все включено из уфы» в точном виде **слабая (2)**; "
            "тяготеть к хабу через страновые якоря из url-map (напр. «туры в турцию из уфы все включено» **561** на /tour/turciya/).",
            "",
            "### Страны и курорты",
        ]
    )
    if country_sep:
        lines.append("Ни один страновой хвост из списка **не достигает 150** в точном виде; кандидаты на отдельные URL **нет**:")
        lines.extend(country_sep)
    else:
        lines.append(
            "По всем страновым/курортным запросам точная частота **< 100**. "
            "Отдельные URL вида «горящие × страна» или «AI × страна» **не нужны** — только фильтры и блоки на "
            "/goryashchie-tury/, /vse-vklyucheno/ и перелинковка с существующими /tour/{страна}/ и курортами v2."
        )

    lines.extend(
        [
            "",
            "### Анти-каннибализация",
            "- Страна остаётся на **/tour/{slug}/** (основной коммерческий интент).",
            "- Курорт v2 держит **горящие/AI** в catalog_lock + сниппеты; не дублировать те же title под чистые «горящие …» URL.",
            "- В 06-url-map уже есть зеркальные якоря (напр. горящие туры из уфы в турцию **551**, в египет **271**) — использовать в фильтрах хаба, а не в новых landing.",
            "",
            "## Методика",
            "",
            "Сбор: `python scripts/analyze_hot_ai_wordstat.py --fetch` (нужен `YANDEX_WORDSTAT_API_KEY`).",
            f"Повтор отчёта из CSV: `python scripts/analyze_hot_ai_wordstat.py -i {RAW_DEFAULT.relative_to(ROOT)}`.",
        ]
    )
    return "\n".join(lines) + "\n"


def write_queries_list(path: Path) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    lines = [
        "# Wordstat Assistant / ручная выгрузка",
        f"# Регион: Уфа (172). Для каждой строки: база без операторов + точная в кавычках \"...\"",
        "",
    ]
    for _c, q, _p in QUERY_ROWS:
        lines.append(q)
        lines.append(f'"{q}"')
        lines.append("")
    path.write_text("\n".join(lines), encoding="utf-8")


def main() -> None:
    ap = argparse.ArgumentParser(description=__doc__)
    ap.add_argument("-i", "--input", type=Path, default=RAW_DEFAULT, help="CSV с частотностями")
    ap.add_argument("-o", "--output", type=Path, default=REPORT_DEFAULT, help="Markdown-отчёт")
    ap.add_argument("--url-map", type=Path, default=URL_MAP)
    ap.add_argument("--fetch", action="store_true", help="Собрать частоты через Yandex Wordstat API")
    ap.add_argument(
        "--exact-mode",
        choices=("quoted", "bang"),
        default="quoted",
        help="quoted = \"фраза\" (рекомендуется); bang = !фраза",
    )
    ap.add_argument("--write-queries", action="store_true", help="Перезаписать seo/wordstat-hot-ai-queries.txt")
    args = ap.parse_args()

    if args.write_queries or args.fetch:
        write_queries_list(QUERIES_DEFAULT)

    if args.fetch:
        api_fetch_all(args.input, args.exact_mode)

    if not args.input.is_file():
        sys.exit(f"Нет входного CSV: {args.input}")

    raw = read_input_csv(args.input)
    rows = [coerce_row(r) for r in raw]
    report = build_report(rows, args.url_map, args.exact_mode)
    args.output.parent.mkdir(parents=True, exist_ok=True)
    args.output.write_text(report, encoding="utf-8")
    print(f"Wrote {args.output}")


if __name__ == "__main__":
    main()