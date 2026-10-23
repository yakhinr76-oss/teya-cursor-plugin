=== WORDSTAT URL-MAP PIPELINE (ЖИРАФ УФА) ===
## Статус: ✅ local test build (gate PASS, deploy нет)
Дата: 18.10.2026 | Ветка: cursor/zhiraf-b2b-site-a2bf | git HEAD после gate: af9ce66

## Семантика / координатор
- URL-map: teya-memory/semantic-core/zhiraf-ufa-2026/wordstat/url-map.md
- CSV: teya-memory/semantic-core/zhiraf-ufa-2026/06-url-map.csv
- Коммит: 0b2883c (Уфа 172, 833 фразы, 8 новых URL)

## Контент
- Агент: aurora-team-content (bc-96c7bc04-…)
- Коммит: 82b6778 — page-content-pack.md, briefs, fragment aurora-team-content.md
- Охват: 20 URL (12 есть + 8 NEW)

## 8 NEW URL
/goryashchie-tury/ | /vse-vklyucheno/ | /tour/turciya/antalya/ | /tour/egipet/hurgada/ | /tour/egipet/sharm-el-sheyh/ | /tour/tailand/phuket/ | /tour/vietnam/nyachang/ | /tour/vietnam/fukuok/

## PAGE BUILDER
- Агент: bc-78870dfb-… | Коммит: a2c6672
- tour-routing.php, resort/offer landings, php -l 49/0
- Отчёт: teya-memory/wp/page-build-report.md

## Paint
- Агент: bc-91bdee58-… | Коммит: d5cf815
- 20 маршрутов × 2 viewport = 40 PNG → /opt/cursor/artifacts/

## Release gate
- Агент: bc-5ed0ebcc-… | Коммит: af9ce66
- teya_release_gate.py: 07628b4 (SHA256 50f71547…, без правок скрипта)
- exit 0 PASS — только восстановление артефактов темы в teya-memory/wp/theme/zhiraf-ufa/
- Отчёт: teya-memory/wp/release-gate-report.md

## Deploy
- zhiraf-ufa.ru: **нет**
