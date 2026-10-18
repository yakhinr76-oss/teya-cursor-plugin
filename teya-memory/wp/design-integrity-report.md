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
| **status** | **❌ DESIGN BLOCKER** |
| design_score | **62/100** |

## Executive summary

Тема несёт Globe Trekker-палитру и hero-коллаж, но **не закрывает CONVERSION-FIRST blueprint**: отсутствуют `audience-tabs`, `trust-highlights` / trust-social, `faq-home`; порядок секций на `front-page.php` не совпадает с `aurora-team-blueprint.md` (editorial и subscribe стоят до финального lead-action). Типографика расходится с AURA: **Unbounded** через Google CDN в `header.php` и `--font-head: Unbounded` в SCSS. Paint evidence (local preview) подтверждает терракотовый CTA и молочный фон, но H1 рендерится Unbounded и meaningful images < budget.

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
| `AURA_PAGE_PLAN.md` / blueprint section order | ❌ P0 gaps |
| `AURA_STYLE_MATCH_SCORECARD.md` | ❌ trust-social planned 4 vs min 7 |
| `AURA_VISUAL_INVENTORY.json` | ❌ trust-visual zone not in `front-page.php` |
| `AURA_FONT_MATCH.md` | ❌ Unbounded drift |
| Visible breadcrumbs | ✅ None |

## Token drift (P0/P1)

| Issue | Location | Fix |
|-------|----------|-----|
| Google Fonts Unbounded | `header.php` L8–10 | Remove CDN; Manrope local only |
| `--font-head: Unbounded` | `assets/src/scss/main.scss` | Manrope 800 |
| Hero H1 copy vs semantic brief | `front-page.php` | H1 из `page-content-pack.md` / blueprint |
| Missing audience-tabs | `front-page.php` | Секция по blueprint + funnel |
| Missing trust-highlights | `front-page.php` | office-ufa + РТА/VK facts |
| Missing faq-home | `front-page.php` | 6–8 FAQ |
| Section order | `front-page.php` | hero → audience → dest → how → trust → faq → lead → editorial (1) → subscribe |

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
| P0 | `front-page.php` | Нет trust-social / audience-tabs / faq | blueprint §4, `AURA_VISUAL_INVENTORY` |
| P0 | `header.php`, `main.scss` | Unbounded / CDN | `AURA_FONT_MATCH.md` |
| P0 | `front-page.php` | Funnel section order | `conversion-funnel-map.md` |
| P1 | H1/hero copy | Не semantic «туры из Уфы» | `page-content-pack.md` |
| P1 | paint-qa | Inner pages 1440/375 | `visual-paint-qa-gate.md` |
| P2 | `preview/home.html` | Drift from `hero-premium` theme | sync or deprecate preview |

## Ready for final QA

**no** — status `❌ DESIGN BLOCKER`.
