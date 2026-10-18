# Paint QA Report — zhiraf-ufa (Wordstat 20 routes)

| Поле | Значение |
|------|----------|
| mode | **local-only** |
| preview_base | `http://127.0.0.1:8765/` (path rewrites → `deliverables/zhiraf-ufa/preview/*.html`) |
| page_build_ref | `teya-memory/wp/page-build-report.md` |
| checked_at | 2026-10-18 |
| agent | aurora-team-design-guardian paint QA (`bc-91bdee58-5338-5e99-96bc-c55e5d7d1505`) |

## Wordstat routes (scroll → fullpage)

| path | slug | 1440 artifact | 390 artifact |
|------|------|---------------|--------------|
| `/` | home | `zhiraf-home-1440.png` | `zhiraf-home-390.png` |
| `/tour/` | tour | `zhiraf-tour-1440.png` | `zhiraf-tour-390.png` |
| `/goryashchie-tury/` | goryashchie-tury | `zhiraf-goryashchie-tury-1440.png` | `zhiraf-goryashchie-tury-390.png` |
| `/vse-vklyucheno/` | vse-vklyucheno | `zhiraf-vse-vklyucheno-1440.png` | `zhiraf-vse-vklyucheno-390.png` |
| `/tour/turciya/` | tour-turciya | `zhiraf-tour-turciya-1440.png` | `zhiraf-tour-turciya-390.png` |
| `/tour/oae/` | tour-oae | `zhiraf-tour-oae-1440.png` | `zhiraf-tour-oae-390.png` |
| `/tour/egipet/` | tour-egipet | `zhiraf-tour-egipet-1440.png` | `zhiraf-tour-egipet-390.png` |
| `/tour/tailand/` | tour-tailand | `zhiraf-tour-tailand-1440.png` | `zhiraf-tour-tailand-390.png` |
| `/tour/vietnam/` | tour-vietnam | `zhiraf-tour-vietnam-1440.png` | `zhiraf-tour-vietnam-390.png` |
| `/tour/turciya/antalya/` | tour-turciya-antalya | `zhiraf-tour-turciya-antalya-1440.png` | `zhiraf-tour-turciya-antalya-390.png` |
| `/tour/egipet/hurgada/` | tour-egipet-hurgada | `zhiraf-tour-egipet-hurgada-1440.png` | `zhiraf-tour-egipet-hurgada-390.png` |
| `/tour/egipet/sharm-el-sheyh/` | tour-egipet-sharm-el-sheyh | `zhiraf-tour-egipet-sharm-el-sheyh-1440.png` | `zhiraf-tour-egipet-sharm-el-sheyh-390.png` |
| `/tour/tailand/phuket/` | tour-tailand-phuket | `zhiraf-tour-tailand-phuket-1440.png` | `zhiraf-tour-tailand-phuket-390.png` |
| `/tour/vietnam/nyachang/` | tour-vietnam-nyachang | `zhiraf-tour-vietnam-nyachang-1440.png` | `zhiraf-tour-vietnam-nyachang-390.png` |
| `/tour/vietnam/fukuok/` | tour-vietnam-fukuok | `zhiraf-tour-vietnam-fukuok-1440.png` | `zhiraf-tour-vietnam-fukuok-390.png` |
| `/o-nas/` | o-nas | `zhiraf-o-nas-1440.png` | `zhiraf-o-nas-390.png` |
| `/kontakty/` | kontakty | `zhiraf-kontakty-1440.png` | `zhiraf-kontakty-390.png` |
| `/blog/` | blog | `zhiraf-blog-1440.png` | `zhiraf-blog-390.png` |
| `/politika-konfidenczialnosti/` | politika-konfidenczialnosti | `zhiraf-politika-konfidenczialnosti-1440.png` | `zhiraf-politika-konfidenczialnosti-390.png` |
| `/politika-cookies/` | politika-cookies | `zhiraf-politika-cookies-1440.png` | `zhiraf-politika-cookies-390.png` |

Все пути артефактов: `/opt/cursor/artifacts/<имя-из-таблицы>`. Зеркала: `teya-memory/wp/paint-qa/home-*-fullpage.png` и `page-<slug>-*-fullpage.png`.

**Total:** 20 routes × 2 viewports = **40** PNG.

## Network

- Theme `style.css` HTTP 200 на всех маршрутах (через `/wp-theme/zhiraf-ufa/assets/dist/style.css`).
- Manrope `@font-face` в preview head.

## Failed routes

Нет (`failed_routes: []` в `paint-evidence.json`).

## Verdict

**pass** — 40/40 screenshots, nested `/tour/*` rewrites на локальном preview-server.
