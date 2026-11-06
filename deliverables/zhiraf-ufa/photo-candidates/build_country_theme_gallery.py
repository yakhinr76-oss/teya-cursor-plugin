#!/usr/bin/env python3
"""Embed country-theme-picks.json into country-theme-gallery.html."""
import json
import os

ROOT = os.path.dirname(os.path.abspath(__file__))
DATA = json.load(open(os.path.join(ROOT, "country-theme-picks.json"), encoding="utf-8"))
DATA_JS = json.dumps(DATA, ensure_ascii=False)

HTML = f"""<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>Pexels — темы: пляж / атмосфера (2 фото × запрос)</title>
<style>
:root{{--bg:#080c12;--panel:#121922;--text:#edf2f7;--muted:#8fa3b8;--accent:#4ec4ff;--border:#243041;}}
*{{box-sizing:border-box}} body{{margin:0;font:14px/1.4 system-ui,sans-serif;background:var(--bg);color:var(--text)}}
header{{position:sticky;top:0;z-index:9;background:rgba(8,12,18,.95);backdrop-filter:blur(8px);border-bottom:1px solid var(--border);padding:12px 16px}}
header h1{{margin:0 0 4px;font-size:1.05rem}} header p{{margin:0;color:var(--muted);font-size:.78rem}}
nav a{{font-size:.75rem;color:var(--accent);margin-right:10px}}
main{{max-width:1200px;margin:0 auto;padding:16px 12px 48px}}
.country{{margin-bottom:32px;scroll-margin-top:80px}}
.country h2{{margin:0 0 12px;font-size:1.1rem}}
.theme{{background:var(--panel);border:1px solid var(--border);border-radius:12px;padding:12px;margin-bottom:12px}}
.theme h3{{margin:0 0 10px;font-size:.95rem;color:var(--muted);text-transform:uppercase;letter-spacing:.04em}}
.query-block{{margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border)}}
.query-block:last-child{{border-bottom:0;margin-bottom:0;padding-bottom:0}}
.query-block .q{{font-size:.8rem;color:var(--accent);margin:0 0 8px}}
.grid{{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}}
@media(max-width:640px){{.grid{{grid-template-columns:1fr}}}}
.tile{{border-radius:8px;overflow:hidden;border:1px solid var(--border);cursor:pointer;background:#000}}
.tile img{{width:100%;aspect-ratio:3/2;object-fit:cover;display:block}}
.cap{{font-size:.65rem;color:var(--muted);padding:6px 4px 0;line-height:1.25}}
#lb{{display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:50;flex-direction:column;align-items:center;justify-content:center;padding:16px}}
#lb.open{{display:flex}} #lb img{{max-width:96vw;max-height:75vh;object-fit:contain}}
#lb p{{color:var(--muted);font-size:.85rem;text-align:center;max-width:720px}}
#lb button{{position:absolute;top:12px;right:12px}}
</style>
</head>
<body>
<header>
<h1>Темы направлений — 2 фото на запрос</h1>
<p>Блоки «пляж» и «атмосфера». Добавляй страны в <code>country-theme-queries.json</code>, затем <code>python3 fetch_country_themes.py</code>.</p>
<nav><a href="index.html">← v3 якоря</a></nav>
</header>
<main id="app"></main>
<div id="lb"><button type="button" onclick="lb.classList.remove('open')">Закрыть</button><img id="lb-img" alt=""/><p id="lb-meta"></p></div>
<script>
const DATA={DATA_JS};
const app=document.getElementById('app');
const lb=document.getElementById('lb');
const lbImg=document.getElementById('lb-img');
const lbMeta=document.getElementById('lb-meta');
function openImg(country, theme, q, o){{
  lbImg.src=o.original;
  lbMeta.innerHTML='<strong>'+country+'</strong> · '+theme+'<br>'+o.alt+'<br><code>'+q+'</code> · ID '+o.id+' · <a href="'+o.page+'" target="_blank" rel="noopener" style="color:#4ec4ff">Pexels</a>';
  lb.classList.add('open');
}}
for (const [country, block] of Object.entries(DATA.countries)) {{
  const sec=document.createElement('section');
  sec.className='country';
  sec.id=country.replace(/\\s+/g,'-');
  sec.innerHTML='<h2>'+country+'</h2>';
  for (const [theme, queries] of Object.entries(block.themes)) {{
    const th=document.createElement('div');
    th.className='theme';
    th.innerHTML='<h3>'+theme+'</h3>';
    for (const row of queries) {{
      const qb=document.createElement('div');
      qb.className='query-block';
      qb.innerHTML='<p class="q">'+row.query+'</p>';
      const g=document.createElement('div');
      g.className='grid';
      for (const o of row.images) {{
        const t=document.createElement('div');
        t.className='tile';
        t.innerHTML='<img loading="lazy" src="'+o.thumb+'" alt=""/><div class="cap">#'+o.id+' · score '+o.score+' · '+o.author+'</div>';
        t.onclick=()=>openImg(country, theme, row.query, o);
        g.appendChild(t);
      }}
      qb.appendChild(g);
      th.appendChild(qb);
    }}
    sec.appendChild(th);
  }}
  app.appendChild(sec);
}}
</script>
</body>
</html>
"""

open(os.path.join(ROOT, "country-theme-gallery.html"), "w", encoding="utf-8").write(HTML)
print("wrote country-theme-gallery.html")
