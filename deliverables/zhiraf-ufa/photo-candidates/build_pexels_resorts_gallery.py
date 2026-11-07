#!/usr/bin/env python3
"""Build pexels-resorts-table-gallery.html from pexels-resorts-table-picks.json."""
from __future__ import annotations

import html
import json
import os
from typing import Any

HERE = os.path.dirname(os.path.abspath(__file__))
JSON_NAME = "pexels-resorts-table-picks.json"
OUT_NAME = "pexels-resorts-table-gallery.html"


def large_url(original: str) -> str:
    o = original.strip()
    sep = "&" if "?" in o else "?"
    return f"{o}{sep}auto=compress&cs=tinysrgb&w=1400"


def escape_js_string(s: str) -> str:
    return json.dumps(s, ensure_ascii=False)


def main() -> None:
    path = os.path.join(HERE, JSON_NAME)
    data: dict[str, Any] = json.load(open(path, encoding="utf-8"))

    sections = []
    for d in data.get("directions", []):
        label = d.get("label") or d.get("slug") or ""
        slug = d.get("slug") or ""
        resorts_out = []
        for r in d.get("resorts", []):
            photos = []
            for i, p in enumerate(r.get("photos") or [], start=1):
                orig = p.get("original") or ""
                photos.append(
                    {
                        "slot": i,
                        "id": p.get("id"),
                        "thumb": p.get("thumb") or orig,
                        "large": large_url(orig) if orig else "",
                        "page": p.get("page") or "",
                        "original": orig,
                        "author": p.get("author") or "",
                        "alt": p.get("alt") or "",
                        "query_used": p.get("query_used") or "",
                    }
                )
            resorts_out.append(
                {
                    "key": r.get("key"),
                    "label": r.get("label_ru") or r.get("key"),
                    "photos": photos,
                }
            )
        sections.append({"title": label, "slug": slug, "resorts": resorts_out})

    payload_obj = {"sections": sections}
    # JSON inside <script type="application/json"> must not contain the literal </script>
    json_for_html = json.dumps(payload_obj, ensure_ascii=False).replace("</", "<\\/")

    # Load DATA from a separate script block so raw.githubusercontent.com and file:// stay valid.
    json_block = f'<script id="resorts-data" type="application/json">\n{json_for_html}\n</script>\n'
    boot_js = """const DATA = JSON.parse(document.getElementById('resorts-data').textContent);
"""

    page = f"""<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Pexels — таблица курортов (все превью)</title>
<style>
:root{{--bg:#060a0f;--panel:#101820;--text:#e8eef4;--muted:#7d8fa3;--accent:#4ab0ff;--border:#1e2a38}}
*{{box-sizing:border-box}}
body{{margin:0;font:13px/1.35 system-ui,sans-serif;background:var(--bg);color:var(--text)}}
header{{position:sticky;top:0;z-index:20;background:rgba(6,10,15,.97);backdrop-filter:blur(8px);border-bottom:1px solid var(--border);padding:10px 14px}}
header h1{{margin:0 0 4px;font-size:1rem}}
header p{{margin:0;color:var(--muted);font-size:.72rem}}
nav{{display:flex;flex-wrap:wrap;gap:5px;margin-top:8px;max-height:24vh;overflow-y:auto}}
nav a{{color:var(--accent);text-decoration:none;font-size:.68rem;border:1px solid var(--border);padding:2px 7px;border-radius:999px}}
main{{max-width:1400px;margin:0 auto;padding:12px 8px 48px}}
.sec{{margin-bottom:22px;scroll-margin-top:88px}}
.sec>h2{{font-size:.88rem;color:var(--muted);margin:0 0 8px;font-weight:600}}
.card{{background:var(--panel);border:1px solid var(--border);border-radius:10px;padding:10px 12px;margin-bottom:10px}}
.r{{margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid #182430}}
.r:last-child{{border-bottom:none}}
.r>h3{{margin:0 0 8px;font-size:.92rem}}
.code{{font-size:.62rem;color:var(--muted);word-break:break-word;margin:0 0 6px}}
.grid{{display:grid;grid-template-columns:1fr 1fr;gap:6px}}
.tile{{position:relative;border-radius:6px;overflow:hidden;cursor:pointer;background:#000;border:1px solid #243041}}
.tile img{{width:100%;aspect-ratio:16/10;object-fit:cover;display:block}}
.tile .tag{{position:absolute;top:4px;left:4px;background:rgba(0,0,0,.65);font-size:.58rem;padding:1px 4px;border-radius:3px}}
#lb{{display:none;position:fixed;inset:0;background:rgba(0,0,0,.93);z-index:50;flex-direction:column;align-items:center;justify-content:center;padding:14px}}
#lb.open{{display:flex}}
#lb img{{max-width:98vw;max-height:82vh;object-fit:contain}}
#lb div{{max-width:720px;text-align:center;color:var(--muted);font-size:.78rem;margin-top:8px}}
#lb button{{position:absolute;top:8px;right:8px;padding:6px 10px;cursor:pointer}}
</style>
</head>
<body>
<header>
<h1>Pexels — таблица курортов · все превью</h1>
<p>Источник: {html.escape(JSON_NAME)} · клик по миниатюре — крупно</p>
<nav id="nav"></nav>
</header>
<main id="app"></main>
<div id="lb"><button type="button" onclick="document.getElementById('lb').classList.remove('open')">Закрыть</button><img id="lb-img" alt=""/><div id="lb-txt"></div></div>
{json_block}<script>
{boot_js}
const nav = document.getElementById('nav');
const app = document.getElementById('app');
const lb = document.getElementById('lb');
const lbImg = document.getElementById('lb-img');
const lbTxt = document.getElementById('lb-txt');
for (const s of DATA.sections) {{
  const id = 'sec-' + (s.slug || s.title).replace(/[^a-z0-9_-]/gi, '-');
  const a = document.createElement('a');
  a.href = '#' + id;
  a.textContent = s.title;
  nav.appendChild(a);
  const sec = document.createElement('section');
  sec.className = 'sec'; sec.id = id;
  sec.innerHTML = '<h2>' + s.title + '</h2>';
  const box = document.createElement('div');
  box.className = 'card';
  for (const r of s.resorts || []) {{
    const rd = document.createElement('div'); rd.className = 'r';
    const h = document.createElement('h3'); h.textContent = r.label || r.key;
    rd.appendChild(h);
    const qs = [...new Set((r.photos || []).map(p => p.query_used).filter(Boolean))];
    if (qs.length) {{
      const c = document.createElement('p'); c.className = 'code';
      c.textContent = qs.length === 1 ? ('Запрос: ' + qs[0]) : ('Запросы: ' + qs.join(' · '));
      rd.appendChild(c);
    }}
    const gr = document.createElement('div'); gr.className = 'grid';
    for (const p of r.photos || []) {{
      const t = document.createElement('div'); t.className = 'tile';
      t.innerHTML = '<span class="tag">#' + p.slot + ' · ' + p.id + '</span><img loading="lazy" src="' + p.thumb + '" alt=""/>';
      t.onclick = () => {{
        lbImg.src = p.large || p.original;
        const esc = (x) => String(x).replace(/</g, '&lt;');
        lbTxt.innerHTML = '<strong>' + esc(r.label) + '</strong><br><strong>ID ' + p.id + '</strong> · ' + esc(p.author) +
          '<br>' + esc(p.alt || '') +
          (p.query_used ? '<br><code style="font-size:.65rem">' + esc(p.query_used) + '</code>' : '') +
          '<br><a href="' + p.page + '" target="_blank" rel="noopener" style="color:#4ab0ff">Pexels</a> · ' +
          '<a href="' + p.original + '" style="color:#4ab0ff" target="_blank" rel="noopener">Оригинал</a>';
        lb.classList.add('open');
      }};
      gr.appendChild(t);
    }}
    rd.appendChild(gr);
    box.appendChild(rd);
  }}
  sec.appendChild(box);
  app.appendChild(sec);
}}
lb.addEventListener('click', (e) => {{ if (e.target === lb) lb.classList.remove('open'); }});
</script>
</body>
</html>
"""

    out_path = os.path.join(HERE, OUT_NAME)
    open(out_path, "w", encoding="utf-8").write(page)
    print("written", out_path)


if __name__ == "__main__":
    main()
