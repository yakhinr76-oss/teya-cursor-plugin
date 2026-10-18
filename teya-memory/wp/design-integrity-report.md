# Design Integrity Report — zhiraf-ufa

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (staging b2b-tour.ru) |
| theme_slug | `zhiraf-ufa` |
| theme_path | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` |
| mode | **local-only** |
| paint_ref | `teya-memory/wp/paint-qa/paint-qa-report.md` |
| checked_at | 2026-10-18 |
| agent | aurora-team-design-guardian (run bc-646a0fe3-3bb0-5e28-bdf3-d4cafb2cc25e) |
| **status** | **⚠️ DESIGN FIXES NEEDED** |
| design_score | **74/100** |

## Executive summary

После merge motion-handoff (`front-page.php`) главная закрывает funnel P0: `audience-tabs`, `trust-social`, `faq-home`, `lead-action` → editorial teaser → subscribe (hold). H1 semantic из content pack. Остаётся **token drift**: **Unbounded** через Google CDN в `header.php` и `--font-head: Unbounded` в SCSS (`AURA_FONT_MATCH.md`). Paint evidence снят со **static** `preview/home.html` (устаревший `hero-globe`, 8 meaningful img) — не заменяет WP paint темы; inner pages без screenshots.

## PHP / package

| Check | Result |
|-------|--------|
| `php -l` all theme PHP (34 files) | ✅ No syntax errors |

## Paint gate (local)

| Check | Result |
|-------|--------|
| Screenshots 1440 + 390 | ✅ `/opt/cursor/artifacts/` + `paint-qa/` |
| Theme CSS loaded in browser | ✅ |
| Unstyled HTML | ✅ No |
| H1 font = Manrope 800 | ❌ Unbounded |
| Homepage meaningful images ≥ 12 | ❌ preview 8; theme missing trust block |
| Inner pages screenshots | ❌ Not captured |

## Authority & blueprint

| Artifact | Verdict |
|----------|---------|
| `AURADESIGN.md` colors/CTA | ✅ Mostly |
| `AURA_PAGE_PLAN.md` / blueprint section order | ✅ post-motion |
| `AURA_STYLE_MATCH_SCORECARD.md` | ⚠️ trust row still low until live paint |
| `AURA_VISUAL_INVENTORY.json` | ✅ trust-visual `office-ufa` on home |
| `AURA_FONT_MATCH.md` | ❌ Unbounded drift |
| Visible breadcrumbs | ✅ None |

## Token drift (P0/P1)

| Issue | Location | Fix |
|-------|----------|-----|
| Google Fonts Unbounded | `header.php` L8–10 | Remove CDN; Manrope local only |
| `--font-head: Unbounded` | `assets/src/scss/main.scss` | Manrope 800 |
| Hero H1 copy vs semantic brief | `front-page.php` | H1 из `page-content-pack.md` / blueprint |
| Stale static preview | `preview/home.html` | Regenerate from `front-page` markup or drop |
| Inner-page paint | paint-qa | `/tour/`, `/o-nas/`, … 1440+375 |

## Responsive (static review + 390 screenshot)

- 390px full-page: без горизонтального скролла на preview.
- Sticky/quiz not exercised in static preview — verify after WP integration.

## Accessibility (design lens)

- `:focus-visible` present in SCSS ✅
- Skip-link markup ✅; skip-link visible styles ❌ (see performance-accessibility-map A11Y-01)
- Cookie banner in `footer.php` ✅ with accept button

## Required Aurora fixes

| Pri | Page / file | Problem | AURA ref |
|-----|-------------|---------|----------|
| P0 | `header.php`, `main.scss` | Unbounded / CDN | `AURA_FONT_MATCH.md` |
| P1 | paint-qa | WP home paint + inner pages | `visual-paint-qa-gate.md` |
| P1 | paint-qa | Inner pages 1440/375 | `visual-paint-qa-gate.md` |
| P2 | `preview/home.html` | Drift from `hero-premium` theme | sync or deprecate preview |

## Ready for final QA

**no** — status `⚠️ DESIGN FIXES NEEDED` (re-run paint on WP/static export after font fix).
