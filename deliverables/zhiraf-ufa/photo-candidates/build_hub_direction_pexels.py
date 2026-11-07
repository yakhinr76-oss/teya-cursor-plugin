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
    large = orig + ("&" if "?" in orig else "?") + "auto=compress&cs=tinysrgb&w=1400"
    return {
        "variant": variant,
        "id": p.get("id"),
        "page": p.get("page"),
        "original": orig,
        "thumb": p.get("thumb") or orig,
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

    payload = json.dumps({"tabs": tabs_out}, ensure_ascii=False).replace("</", "<\\/")
    json_block = f'<script id="hub-data" type="application/json">\n{payload}\n</script>\n'
    page = f"""<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Хаб направлений — 2 фото на замену (кандидаты)</title>
<style>
:root{{--bg:#0a0e14;--panel:#141c26;--text:#e8eef4;--muted:#8fa3b8;--accent:#ff8c42;--ok:#5ec8ff;--border:#243041}}
*{{box-sizing:border-box}}
body{{margin:0;font:14px/1.4 system-ui,sans-serif;background:var(--bg);color:var(--text)}}
header{{padding:16px;border-bottom:1px solid var(--border);position:sticky;top:0;background:rgba(10,14,20,.95);z-index:5}}
h1{{margin:0 0 6px;font-size:1.05rem}}
p{{margin:0;color:var(--muted);font-size:.8rem}}
main{{max-width:1100px;margin:0 auto;padding:16px 12px 48px}}
.tab{{margin-bottom:28px}}
.tab>h2{{font-size:.95rem;color:var(--accent);margin:0 0 12px}}
.card{{background:var(--panel);border:1px solid var(--border);border-radius:12px;padding:14px;margin-bottom:14px}}
.card h3{{margin:0 0 4px;font-size:1rem}}
.meta{{font-size:.72rem;color:var(--muted);margin-bottom:10px}}
.grid{{display:grid;grid-template-columns:1fr 1fr;gap:10px}}
@media(max-width:640px){{.grid{{grid-template-columns:1fr}}}}
.variant{{border:2px solid transparent;border-radius:8px;overflow:hidden;cursor:pointer;background:#000}}
.variant.is-focus{{border-color:var(--accent)}}
.variant img{{width:100%;aspect-ratio:16/10;object-fit:cover;display:block}}
.cap{{padding:8px 10px;font-size:.72rem;color:var(--muted)}}
.cap strong{{color:var(--text)}}
#lb{{display:none;position:fixed;inset:0;background:rgba(0,0,0,.94);z-index:20;align-items:center;justify-content:center;flex-direction:column;padding:16px}}
#lb.open{{display:flex}}
#lb img{{max-width:96vw;max-height:78vh;object-fit:contain}}
#lb p{{color:var(--muted);font-size:.8rem;max-width:560px;text-align:center}}
#lb button{{position:absolute;top:12px;right:12px}}
.warn{{color:#f5a623}}
</style>
</head>
<body>
<header>
<h1>Кандидаты для карточек «Актуальные предложения»</h1>
<p>По 2 снимка из базы курортов на направление. Клик — крупно. Это <strong>не</strong> сайт: только согласование перед заменой `dest-*-new.jpg` в теме.</p>
</header>
<main id="app"></main>
<div id="lb"><button type="button" onclick="document.getElementById('lb').classList.remove('open')">Закрыть</button><img id="lb-img" alt=""/><p id="lb-txt"></p></div>
{json_block}
<script>
const DATA = JSON.parse(document.getElementById('hub-data').textContent);
const app = document.getElementById('app');
const lb = document.getElementById('lb');
const lbImg = document.getElementById('lb-img');
const lbTxt = document.getElementById('lb-txt');
function openLb(p, title, variant) {{
  lbImg.src = p.large || p.original;
  lbTxt.innerHTML = '<strong>' + title + '</strong> · вариант ' + variant + '<br>ID ' + p.id + ' · ' + (p.resort_label_ru||'') + '<br>' + (p.alt||'') + '<br><a style="color:#5ec8ff" href="' + p.page + '" target="_blank" rel="noopener">Pexels</a>';
  lb.classList.add('open');
}}
for (const tab of DATA.tabs) {{
  const sec = document.createElement('section');
  sec.className = 'tab';
  sec.innerHTML = '<h2>' + tab.tab_label + '</h2>';
  for (const c of tab.cards) {{
    const card = document.createElement('div');
    card.className = 'card';
    const ready = c.photos && c.photos.length >= 2;
    card.innerHTML = '<h3>' + c.title_ru + '</h3><div class="meta">Сейчас: <code>' + c.current_wp_image + '</code>'
      + (ready ? '' : ' · <span class="warn">меньше 2 кандидатов в базе</span>') + '</div>';
    const gr = document.createElement('div');
    gr.className = 'grid';
    (c.photos||[]).forEach(p => {{
      const v = document.createElement('div');
      v.className = 'variant';
      v.innerHTML = '<img loading="lazy" src="' + p.thumb + '" alt=""/><div class="cap"><strong>Вариант ' + p.variant + '</strong> · ' + (p.resort_label_ru||'') + ' · ID ' + p.id + '</div>';
      v.onclick = () => openLb(p, c.title_ru, p.variant);
      gr.appendChild(v);
    }});
    card.appendChild(gr);
    sec.appendChild(card);
  }}
  app.appendChild(sec);
}}
lb.onclick = (e) => {{ if (e.target === lb) lb.classList.remove('open'); }};
</script>
</body>
</html>
"""
    html_path = os.path.join(HERE, "pexels-hub-direction-candidates.html")
    open(html_path, "w", encoding="utf-8").write(page)
    print("written", json_path, md_path, html_path)


if __name__ == "__main__":
    main()
