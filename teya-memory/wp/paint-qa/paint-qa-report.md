# Paint QA Report — zhiraf-ufa (full static preview)

| Поле | Значение |
|------|----------|
| mode | **local-only** |
| preview_base | `http://127.0.0.1:8765/preview/` |
| page_build_ref | `teya-memory/wp/page-build-report.md` |
| checked_at | 2026-10-18 |
| agent | aurora-team-paint-evidence (`bc-646a0fe3-3bb0-5e28-bdf3-d4cafb2cc25e`) |

## Pages captured (scroll → fullpage)

| slug | preview file | 1440 artifact | 390 artifact |
|------|--------------|---------------|--------------|
| home | `home.html` | `/opt/cursor/artifacts/zhiraf-home-1440.png` | `zhiraf-home-390.png` |
| tour | `tour.html` | `zhiraf-tour-1440.png` | `zhiraf-tour-390.png` |
| o-nas | `o-nas.html` | `zhiraf-o-nas-1440.png` | … |
| kontakty | `kontakty.html` | `zhiraf-kontakty-*` | … |
| blog | `blog.html` | `zhiraf-blog-*` | … |
| tour-turciya | `tour-turciya.html` | `zhiraf-tour-turciya-*` | … |
| tour-oae | `tour-oae.html` | … | … |
| tour-egipet | `tour-egipet.html` | … | … |
| tour-tailand | `tour-tailand.html` | … | … |
| tour-vietnam | `tour-vietnam.html` | … | … |
| politika-konfidenczialnosti | `politika-konfidenczialnosti.html` | … | … |
| politika-cookies | `politika-cookies.html` | … | … |

**Total:** 12 pages × 2 viewports = **24** PNG (mirrored in `teya-memory/wp/paint-qa/page-*` / `home-*-fullpage.png`).

## Network

- Theme `style.css` HTTP 200 on all pages.
- Local Manrope `@font-face` inline in preview head.

## Verdict

**pass** — `paint-evidence.json` lists all paths; build + 5 tour landings + legal covered on static preview.
