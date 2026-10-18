# AURA Taste Audit

- mode: TASTE AUDIT
- project: Турагентство «Жираф», Уфа
- theme_slug: `zhiraf-ufa`
- theme_path: `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/`
- date: 2026-10-18
- profile: `teya-memory/design/AURA_TASTE_PROFILE.md` (design_ambition: **polished**)
- impeccable: `AURA_IMPECCABLE_REPORT.json` — **pass** (0 findings on deliverables theme)
- verdict: **❌ BLOCKER**

## Summary

Тема в целом следует Globe Trekker craft: hero-коллаж + flight SVG, vertical dest 3:4 с scrim, editorial 1+3, subscribe full-bleed, квиз-модал 4 шага, warm palette без AI-slop. Impeccable detect чистый.

**BLOCKER** из‑за **2 hard fail** по anti-slop (T04, T09): массовый `transition: all` на `.btn` и **Unbounded** как display вместо Manrope-only lock. Дополнительно P0 gap: **нет секции trust-social** (была в профиле/handoff), `--color-sand` не объявлен в `:root`.

## Checklist T01–T15

| ID | Result | Evidence |
|----|--------|----------|
| T01 | PASS | Steps — 4 numbered cards (waiver); нет generic 3-icon feature row |
| T02 | PASS | Dest hierarchy via scrim `::after`, не nested cards |
| T03 | PASS | Нет purple/violet AI gradients; warm terracotta editorial |
| T04 | **FAIL** | `main.scss` `.btn { transition: all 0.3s ease; }` |
| T05 | PASS | Нет `ease-in` на enter UI в theme SCSS/JS (GSAP `power3.out`) |
| T06 | PASS | Нет `scale(0)` entrance; hover/entrance scale 0.95–1.05 OK |
| T07 | PASS (estimate) | `#E87A5D` on white/milky — крупный bold CTA; subscribe white on 50% overlay — визуально OK, verify in Guardian |
| T08 | WARN | `.btn--sm` padding 8×16 → высота &lt;44px (header CTA desktop); mobile header actions скрыты |
| T09 | **FAIL** | `header.php` Google Unbounded; `--font-head: 'Unbounded', 'Manrope'`; headings use `var(--font-head)` — против AURADESIGN Manrope 800 |
| T10 | PASS | Body `#FAF8F5`, sections alternate milky/white/sand intent |
| T11 | PASS | `editorial-cat` только в blog blocks, не на каждой секции |
| T12 | PASS | Layout families чередуются (hero split → dest grid → steps → editorial → form → subscribe) |
| T13 | PASS | Hero 3 photos + SVG path (flight hidden only &lt;768px CSS) |
| T14 | PASS | Нет lorem/Jane Doe; fallback blog copy generic но не fake reviews |
| T15 | PASS | GSAP `power3.out`, no bounce/elastic |

**Hard fails:** 2 (T04, T09) → **BLOCKER** per gate policy.

## Positive craft P01–P15 (polished)

| ID | Result | Notes |
|----|--------|-------|
| P01 | PASS (soft) | ≥5 families on homepage |
| P02 | PASS | Collage overlap + subscribe overlay + dest scrim |
| P03 | PASS | Hero 50/50 + collage |
| P04 | PASS (soft) | ~13 meaningful `<img>` on front-page (3+5+4+1) |
| P05 | PASS | H1 ~4rem vs body 16–17px |
| P06 | PASS | Editorial asymmetric block |
| P07 | PASS (soft) | `template-parts/quiz-modal.php` + `data-quiz-open` |
| P08 | PASS | Scrim/overlap/collage per AURA |
| P09 | PASS | `.reveal` + GSAP ScrollTrigger on homepage |
| P10 | PASS | `page-tour.php` catalog hero + grid distinct from home hero |
| P11 | WARN | Trust-social **отсутствует** в `front-page.php` (scorecard gap) |
| P12 | WARN (soft) | Form strip text-only между editorial и subscribe (1 блок — OK); без trust слабее social proof craft |
| P13 | PASS | Header pill / hero CTA / inline form / subscribe center — varied |
| P14 | PASS | Flight SVG + dest photos; no emoji placeholders |
| P15 | PASS | Variance targets met except trust section optional in count |

## Layout variance vs profile

| Metric | Target | Actual | Status |
|--------|--------|--------|--------|
| section_layout_families_min | 5 | 6 (без trust) | OK |
| consecutive_same_family_max | 2 | hero+dest both milky but different families | OK |
| shaped_transitions_min | 2 | collage overlap + subscribe overlay | OK |
| meaningful_images_homepage_min | 12 | ~13 | OK |
| colored_surface_ratio_min | 0.35 | milky×2, white steps, sand form*, subscribe photo | OK (*sand token broken) |

## Impeccable detect

```text
npx impeccable detect --json deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/
→ findings: []
```

Wrapper script skipped (`teya-memory/wp/theme/zhiraf-ufa` missing); отчёт записан с фактическим путём deliverables.

## Required fixes (TASTE RECOVERY)

1. **T09:** Удалить Unbounded из `header.php`; `--font-head: 'Manrope', sans-serif`; headings weight 800; один источник шрифта — `inc/enqueues.php` local Manrope.
2. **T04:** Заменить `transition: all` на `.btn` → `background-color`, `color`, `border-color`, `transform` по отдельности (≤0.3s).
3. **P11 / handoff:** Добавить `front-page:trust-social` (факты, VK, телефон, office/team photo, без fake quotes).
4. **CSS:** Объявить `--color-sand: #F5F2ED` (или заменить на token из AURADESIGN).
5. **T08 (warn):** `min-height: 44px` на header/footer primary CTAs или убрать `btn--sm` с conversion buttons.
6. **Optional:** Подключить motion hero к `.hero-collage__img` (сейчас JS ищет `.collage-tile` / `.dest-card` — мёртвые селекторы).

## Waivers retained

- MCP blog covers — unchanged.
- Steps 4-card row — per profile waiver.
- Reference wave — not required.

## Verdict rationale

- Impeccable: pass.
- Hard fails ≥2 (T04, T09) → **❌ BLOCKER**.
- После фиксов 1–3 → re-run TASTE AUDIT / RECOVERY.
