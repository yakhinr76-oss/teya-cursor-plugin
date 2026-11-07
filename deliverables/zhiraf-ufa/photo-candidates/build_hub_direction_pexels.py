#!/usr/bin/env python3
"""
Build hub-direction Pexels candidates: 2 photos per homepage hub card direction.

Source: pexels-resorts-table-picks.json + mapping to cards in home-destinations-hub.php
Outputs: pexels-hub-direction-candidates.{json,md,html}
"""
from __future__ import annotations

import html
import json
import os
import re
from typing import Any

HERE = os.path.dirname(os.path.abspath(__file__))
PICKS = os.path.join(HERE, "pexels-resorts-table-picks.json")

# (hub_slug, json_direction_slug, title_ru, current_wp_image, resort_key_1, resort_key_2)
# Resort keys pick slot #1 from each (diverse geography within the direction).
HUB_CARDS: dict[str, list[tuple[str, str, str, str, str, str]]] = {
    "ufa": [
        ("turciya", "turkey", "Турция", "dest-turkey-new.jpg", "antalya", "alanya"),
        ("oae", "uae", "ОАЭ", "dest-uae-new.jpg", "dubai", "ras_al_khaimah"),
        ("egipet", "egypt", "Египет", "dest-egypt-new.jpg", "sharm_el_sheikh", "hurghada"),
        ("tailand", "thailand", "Таиланд", "dest-thailand-new.jpg", "phuket", "krabi"),
        ("rossiya", "russia", "Россия (Сочи)", "hero-collage-1.jpg", "sochi", "krasnaya_polyana"),
        ("abkhaziya", "abkhazia", "Абхазия", "alt-armeniya-3.jpg", "gagra", "pitsunda"),
    ],
    "mow": [
        ("maldivy", "maldives", "Мальдивы", "resort-maldivy-atoll-1.jpg", "ari_atoll", "maafushi"),
        ("shri-lanka", "sri_lanka", "Шри-Ланка", "country-shri-lanka.jpg", "mirissa", "unawatuna"),
        ("vietnam", "vietnam", "Вьетнам", "dest-vietnam-new.jpg", "phu_quoc", "nha_trang"),
        ("kitaj", "china_hainan", "Китай (Хайнань)", "resort-sanya-2.jpg", "sanya", "yalong_bay"),
        ("indiya", "india_goa", "Индия (Гоа)", "country-indiya.jpg", "palolem", "calangute"),
        ("indoneziya", "indonesia_bali", "Индонезия (Бали)", "alt-indoneziya-beach-1.jpg", "seminyak", "nusa_dua"),
        ("sejshely", "seychelles", "Сейшелы", "country-sejshely.jpg", "praslin", "mahe"),
        ("uzbekistan", "uzbekistan", "Узбекистан", "resort-samarkand.jpg", "samarkand", "bukhara"),
        ("armeniya", "armenia", "Армения", "alt-armeniya-yerevan.jpg", "sevan", "yerevan"),
    ],
}

TAB_LABEL = {"ufa": "Вылет из Уфы", "mow": "Вылет из Москвы"}


def load_resorts_by_key(data: dict[str, Any]) -> dict[str, dict[str, Any]]:
    out: dict[str, dict[str, Any]] = {}
    for d in data.get("directions", []):
        slug = d.get("slug") or ""
        for r in d.get("resorts", []):
            key = r.get("key")
            if isinstance(key, str):
                out[f"{slug}:{key}"] = {**r, "direction_slug": slug, "direction_label": d.get("label")}
    return out


def photo_card(p: dict[str, Any], resort: dict[str, Any], variant: int) -> dict[str, Any]:
    orig = p.get("original") or ""
    q = "&" if "?" in orig else "?"
    large = f"{orig}{q}auto=compress&cs=tinysrgb&w=1400"
    preview = f"{orig}{q}auto=compress&cs=tinysrgb&w=960&h=600&fit=crop"
    return {
        "variant": variant,
        "id": p.get("id"),
        "page": p.get("page"),
        "original": orig,
        "thumb": p.get("thumb") or orig,
        "preview": preview,
        "large": large,
        "author": p.get("author"),
        "alt": p.get("alt"),
        "resort_key": resort.get("key"),
        "resort_label_ru": resort.get("label_ru"),
        "direction_label": resort.get("direction_label"),
    }


def pick_two(direction_slug: str, rk1: str, rk2: str, by_key: dict[str, dict[str, Any]]) -> list[dict[str, Any]]:
    def slot(resort_key: str, index: int) -> tuple[dict[str, Any], dict[str, Any]] | None:
        resort = by_key.get(f"{direction_slug}:{resort_key}")
        if not resort:
            return None
        photos = resort.get("photos") or []
        if len(photos) <= index:
            return None
        return resort, photos[index]

    first = slot(rk1, 0)
    second = slot(rk2, 0)
    picks: list[dict[str, Any]] = []
    if first:
        picks.append(photo_card(first[1], first[0], 1))
    if second:
        p = second[1]
        if picks and p.get("id") == picks[0].get("id"):
            alt = slot(rk2, 1)
            if alt:
                p = alt[1]
                second = alt
        picks.append(photo_card(p, second[0], 2))
    if len(picks) < 2:
        resort2 = by_key.get(f"{direction_slug}:{rk2}") or {}
        photos2 = resort2.get("photos") or []
        if len(picks) == 1 and len(photos2) > 1:
            picks.append(photo_card(photos2[1], resort2, 2))
        elif len(picks) == 0 and len(photos2) >= 2:
            picks.append(photo_card(photos2[0], resort2, 1))
            picks.append(photo_card(photos2[1], resort2, 2))
    # Deduplicate by id while keeping order.
    seen: set[int] = set()
    uniq: list[dict[str, Any]] = []
    for item in picks:
        pid = item.get("id")
        if pid in seen:
            continue
        seen.add(pid)
        uniq.append(item)
    return uniq[:2]


def main() -> None:
    data = json.load(open(PICKS, encoding="utf-8"))
    by_key = load_resorts_by_key(data)
    tabs_out: list[dict[str, Any]] = []

    for tab, cards in HUB_CARDS.items():
        card_rows: list[dict[str, Any]] = []
        for hub_slug, dir_slug, title, wp_img, rk1, rk2 in cards:
            photos = pick_two(dir_slug, rk1, rk2, by_key)
            card_rows.append(
                {
                    "hub_slug": hub_slug,
                    "title_ru": title,
                    "current_wp_image": wp_img,
                    "direction_slug": dir_slug,
                    "resort_keys_used": [rk1, rk2],
                    "photos": photos,
                    "ready_for_swap": len(photos) >= 2,
                }
            )
        tabs_out.append({"tab": tab, "tab_label": TAB_LABEL[tab], "cards": card_rows})

    out = {
        "version": 1,
        "description": "2 Pexels candidates per homepage hub direction (before replacing dest-hub card photos)",
        "source": "pexels-resorts-table-picks.json",
        "tabs": tabs_out,
    }

    json_path = os.path.join(HERE, "pexels-hub-direction-candidates.json")
    json.dump(out, open(json_path, "w", encoding="utf-8"), ensure_ascii=False, indent=2)

    md_lines = [
        "# Хаб направлений — 2 кандидата с Pexels на замену фона карточки",
        "",
        "После вашего выбора (вариант 1 или 2) кладём файл в `assets/images/` и меняем `image` в `home-destinations-hub.php`.",
        "",
    ]
    for tab in tabs_out:
        md_lines.append(f"## {tab['tab_label']}")
        md_lines.append("")
        md_lines.append("| Направление | Сейчас в теме | Вариант 1 | Вариант 2 |")
        md_lines.append("| --- | --- | --- | --- |")
        for c in tab["cards"]:
            p = c["photos"]
            v1 = "—"
            v2 = "—"
            if len(p) > 0:
                v1 = f"[{p[0]['id']}]({p[0]['page']}) · {p[0].get('resort_label_ru', '')}"
            if len(p) > 1:
                v2 = f"[{p[1]['id']}]({p[1]['page']}) · {p[1].get('resort_label_ru', '')}"
            md_lines.append(
                f"| {c['title_ru']} | `{c['current_wp_image']}` | {v1} | {v2} |"
            )
        md_lines.append("")

    md_path = os.path.join(HERE, "pexels-hub-direction-candidates.md")
    open(md_path, "w", encoding="utf-8").write("\n".join(md_lines))

    page = render_static_hub_html(tabs_out)
    for name in (
        "pexels-hub-direction-candidates.html",
        "СМОТРЕТЬ_КАНДИДАТЫ_ХАБ.html",
    ):
        open(os.path.join(HERE, name), "w", encoding="utf-8").write(page)

    index_html = """<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="refresh" content="0;url=СМОТРЕТЬ_КАНДИДАТЫ_ХАБ.html"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Кандидаты хаба направлений</title>
</head>
<body>
  <p><a href="СМОТРЕТЬ_КАНДИДАТЫ_ХАБ.html">Открыть все кандидаты (Уфа + Москва) одной страницей</a></p>
</body>
</html>
"""
    open(os.path.join(HERE, "index.html"), "w", encoding="utf-8").write(index_html)
    print("written", json_path, md_path, "pexels-hub-direction-candidates.html", "СМОТРЕТЬ_КАНДИДАТЫ_ХАБ.html", "index.html")


def render_static_hub_html(tabs_out: list[dict[str, Any]]) -> str:
    nav: list[str] = []
    body: list[str] = []
    for tab in tabs_out:
        tid = html.escape(tab["tab"])
        label = html.escape(tab["tab_label"])
        nav.append(f'<a href="#tab-{tid}">{label}</a>')
        body.append(f'<section class="tab" id="tab-{tid}"><h2>{label}</h2>')
        for card in tab["cards"]:
            title = html.escape(card["title_ru"])
            wp = html.escape(card["current_wp_image"])
            warn = ""
            if len(card.get("photos") or []) < 2:
                warn = ' <span class="warn">— меньше 2 кандидатов в базе</span>'
            body.append(
                f'<article class="card"><h3>{title}</h3>'
                f'<p class="meta">Сейчас в теме: <code>{wp}</code>{warn}</p>'
                f'<div class="grid">'
            )
            for p in card.get("photos") or []:
                alt = html.escape(p.get("alt") or title)
                src = html.escape(p.get("preview") or p.get("thumb") or "")
                page_url = html.escape(p.get("page") or "")
                resort = html.escape(p.get("resort_label_ru") or "")
                vid = int(p.get("variant") or 0)
                pid = html.escape(str(p.get("id") or ""))
                body.append(
                    f'<figure class="variant">'
                    f'<div class="badge">Вариант {vid}</div>'
                    f'<a href="{page_url}" target="_blank" rel="noopener">'
                    f'<img src="{src}" alt="{alt}" width="960" height="600" loading="lazy" decoding="async"/>'
                    f"</a>"
                    f'<figcaption><strong>{resort}</strong> · ID {pid}'
                    f' · <a href="{page_url}" target="_blank" rel="noopener">Pexels</a></figcaption>'
                    f"</figure>"
                )
            body.append("</div></article>")
        body.append("</section>")

    return f"""<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Все кандидаты — хаб направлений (2 на карточку)</title>
<style>
:root{{--bg:#0a0e14;--panel:#141c26;--text:#e8eef4;--muted:#8fa3b8;--accent:#ff8c42;--border:#243041}}
*{{box-sizing:border-box}}
body{{margin:0;font:15px/1.45 system-ui,sans-serif;background:var(--bg);color:var(--text)}}
header{{padding:14px 16px;border-bottom:1px solid var(--border);position:sticky;top:0;background:rgba(10,14,20,.97);z-index:5}}
header h1{{margin:0 0 8px;font-size:1.1rem}}
header p{{margin:0 0 10px;color:var(--muted);font-size:.82rem;max-width:52rem}}
nav{{display:flex;flex-wrap:wrap;gap:8px}}
nav a{{color:var(--accent);text-decoration:none;border:1px solid var(--border);padding:4px 12px;border-radius:999px;font-size:.8rem}}
main{{max-width:1200px;margin:0 auto;padding:18px 14px 56px}}
.tab{{margin-bottom:36px;scroll-margin-top:88px}}
.tab>h2{{font-size:1.05rem;color:var(--accent);margin:0 0 16px}}
.card{{background:var(--panel);border:1px solid var(--border);border-radius:14px;padding:16px;margin-bottom:18px}}
.card h3{{margin:0 0 6px;font-size:1.05rem}}
.meta{{font-size:.78rem;color:var(--muted);margin:0 0 14px}}
.grid{{display:grid;grid-template-columns:1fr 1fr;gap:14px}}
@media(max-width:720px){{.grid{{grid-template-columns:1fr}}}}
.variant{{margin:0;position:relative;background:#000;border-radius:10px;overflow:hidden;border:1px solid #2a3544}}
.badge{{position:absolute;top:10px;left:10px;z-index:2;background:rgba(0,0,0,.72);color:#fff;font-size:.75rem;font-weight:700;padding:4px 10px;border-radius:6px}}
.variant img{{width:100%;display:block;aspect-ratio:16/10;object-fit:cover}}
.variant a{{display:block;line-height:0}}
figcaption{{padding:10px 12px;font-size:.78rem;color:var(--muted)}}
figcaption a{{color:#5ec8ff}}
.warn{{color:#f5a623}}
</style>
</head>
<body>
<header>
<h1>Кандидаты для «Актуальные предложения» — всё на одной странице</h1>
<p>Прокрутите вниз: у каждого направления сразу видны <strong>вариант 1</strong> и <strong>вариант 2</strong> (крупные превью). Ничего не нужно открывать по отдельности. После выбора пишите в чат: «Турция — 2, ОАЭ — 1» и т.д.</p>
<nav>{"".join(nav)}</nav>
</header>
<main>
{"".join(body)}
</main>
</body>
</html>
"""


if __name__ == "__main__":
    main()
