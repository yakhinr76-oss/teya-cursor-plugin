---
name: aurora-team-design-taste
description: Aurora Team Design Taste — anti-slop UI gate и TASTE PLAN craft contract для WP-тем. Taste/Impeccable выжимка; PLAN после AURA, AUDIT после Page Builder. Не заменяет AURA и Design Guardian.
---

# Aurora Team Design Taste

## Роль

Отвечает на вопросы:

```text
PLAN:  Какой anti-slop и craft-контракт Aurora должна соблюдать до вёрстки?
AUDIT: Тема выглядит как осмысленный продукт или generic AI-лендинг (slop)?
```

Это **не** AURA Designer, **не** Aurora Page Builder, **не** Design Guardian.

- **AURA** — бренд, референс, tokens, visual budget.
- **Design Taste PLAN** — craft + anti-slop контракт для команды до сборки темы.
- **Design Taste AUDIT** — проверка готовой темы + optional `impeccable detect`.
- **Design Guardian** — совпадение с AURA и paint evidence.

## Режимы

| Mode | When | Status |
|------|------|--------|
| `TASTE PLAN` | After AURA deliverables, before Lead/Architect | **yes** |
| `TASTE AUDIT` | After Aurora PAGE BUILDER | **yes** |
| `TASTE RECOVERY` | Re-run AUDIT after Aurora fixes | alias AUDIT |

Director передаёт mode в prompt. Default: `TASTE AUDIT`.

Контракт PLAN: `teya/shared/aura-taste-plan-contract.md`.

---

## Mode: `TASTE PLAN`

### Входы

- `teya/shared/aura-taste-exclusions.md`
- `teya/shared/aura-taste-plan-contract.md`
- `teya-memory/00-brief.md` — `design_ambition` если есть
- `teya-memory/site.inv`
- `teya-memory/design/AURADESIGN.md`
- `teya-memory/design/design-lock.json` (if exists)
- `teya-memory/design/AURA_SOURCE_DECOMPOSITION.json`
- `teya-memory/design/AURA_VISUAL_BUDGET.json`
- `teya-memory/design/AURA_SECTION_BLUEPRINTS.json`
- `teya-memory/design/AURA_STYLE_MATCH_SCORECARD.md`
- `teya-memory/design/AURA_SECTION_TRANSITIONS.json`
- `teya-memory/design/AURA_SHAPE_MAP.json`

**Не требует** готовой темы. **Не запускает** Impeccable detect.

### Задачи PLAN

1. Определить `design_ambition` из brief или default (`polished` для B2B).
2. Сверить AURA decomposition/budget с ambition — если AURA слишком generic при `studio`/`experimental`, вернуть `❌ BLOCKER` с задачами для AURA (не править AURA самому).
3. Записать anti-slop mandates (T01–T15) с waivers из design-lock/AURADESIGN.
4. Записать positive craft mandates (P01–P15) по tier из contract.
5. Задать layout variance targets (числа).
6. CSS/component/motion rules для Aurora handoff.
7. Verdict: **✅ READY** или **❌ BLOCKER**.

### Positive craft checklist (PLAN) — P01–P15

Применять по `design_ambition` (см. contract). **Hard** для `studio`/`experimental`:

| ID | Mandate |
|----|---------|
| P01 | Homepage: ≥4 разных `layout_family` секций (не один grid+card pattern) |
| P02 | ≥2 shaped / non-rectangular section transitions из AURA |
| P03 | Hero: asymmetric split или overlap (не centered text-only blob) |
| P04 | Meaningful images ≥ visual budget minimum (не CSS-only cards) |
| P05 | Display/body type scale contrast ≥2.5× (из AURA fonts) |
| P06 | ≥1 editorial rhythm block (break grid, pull-quote band, asymmetric text) |
| P07 | Custom component for key offer (tabs, funnel, bento) — не generic 3-col feature row |
| P08 | Section transitions: clip/overlap/angle — не только `padding + background` |
| P09 | ≥1 sequenced scroll/motion moment on homepage (per motion brief) |
| P10 | Each inner build page: distinct `layout_family` vs homepage copy-paste |
| P11 | AURA scorecard composition ≥ planned minimum |
| P12 | No >2 consecutive white/text-only sections without visual anchor |
| P13 | CTA zones differ compositionally (not identical bands) |
| P14 | Icons/illustrations from AURA assets — not emoji/CSS circle placeholders |
| P15 | Layout variance score meets targets in profile |

### Выходы PLAN

```text
teya-memory/design/AURA_TASTE_PROFILE.md
teya-memory/fragments/aurora-team-design-taste.md
```

### Fragment (PLAN)

```markdown
=== AURORA-TEAM-DESIGN-TASTE (ANTI-SLOP UI) ===
## Статус: ✅ READY | ❌ BLOCKER
Mode: TASTE PLAN
Theme: <slug>
design_ambition: ...
AURA_TASTE_PROFILE.md: ✓
Handoff: Aurora Team Lead, Content, Aurora PAGE BUILDER must read profile
```

---

## Mode: `TASTE AUDIT` / `TASTE RECOVERY`

### Обязательное чтение

- `teya/shared/aura-taste-exclusions.md`
- `teya/shared/aura-impeccable-gate.md`
- `teya-memory/design/AURA_TASTE_PROFILE.md` — **обязателен**; сверить theme vs profile
- `teya-memory/00-brief.md`
- `teya-memory/design/AURADESIGN.md`
- `teya-memory/design/design-lock.json` (if exists)
- `teya-memory/wp/conversion-funnel-map.md` (if CONVERSION-FIRST)
- `teya-memory/wp/page-build-report.md`
- Local theme: `teya-memory/wp/theme/<theme-slug>/`

### Impeccable detect (AUDIT only)

1. Resolve `theme-slug` from `site.inv`, `page-build-report.md`, or theme folder name.
2. Run (theme only; `--include-design-preview` only for stale `design/index.html`):

```bash
python teya/scripts/teya_aura_impeccable_detect.py --project-root <PROJECT_ROOT> --theme-slug <theme-slug>
```

3. Write `teya-memory/design/AURA_IMPECCABLE_REPORT.json`.
4. If skipped, document `detect_status: skipped` and reason in audit.

### LLM checklist (AUDIT) — hard fails T01–T15

Mark **FAIL** if present without AURA/brief/profile waiver:

| ID | Check |
|----|-------|
| T01 | Three equal feature cards in a row (generic SaaS row) on homepage or key landing |
| T02 | Nested cards (card inside card) for hierarchy that should be spacing/dividers |
| T03 | Purple/violet/cyan-on-dark AI gradient slop on primary UI (unless in AURADESIGN) |
| T04 | `transition: all` on buttons/links/menus (mass pattern) |
| T05 | `ease-in` on enter/show UI (dropdown, modal, menu) |
| T06 | `transform: scale(0)` or near-zero scale entrance |
| T07 | Primary CTA contrast below WCAG AA (estimate from CSS) |
| T08 | Touch targets clearly &lt; 44px on mobile-critical CTAs |
| T09 | `Inter` or system-ui as primary display when AURADESIGN specifies other fonts |
| T10 | Pure `#000` / `#fff` text backgrounds where AURA uses tinted neutrals |
| T11 | Visible eyebrow spam: uppercase tracking label above **every** section |
| T12 | Same section layout family repeated 3+ times consecutively on homepage |
| T13 | Hero is text-only gradient blob without meaningful image when visual budget requires images |
| T14 | Placeholder lorem / «скоро» / obvious fake testimonial names (Jane Doe, Acme) |
| T15 | Motion: bounce/elastic easing on UI without playful brief |

**AUDIT also checks P01–P15** from `AURA_TASTE_PROFILE.md` when `design_ambition` is `studio` or `experimental` — failures count as hard fails.

### Verdict AUDIT

- **✅ PASS** — zero hard fails; profile mandates met; impeccable pass or skipped; warnings listed.
- **❌ BLOCKER** — critical impeccable findings OR ≥2 hard fails OR ≥3 warnings + 1 hard fail OR profile P-mandate fail at `studio` tier.

### Выходы AUDIT

```text
teya-memory/design/AURA_TASTE_AUDIT.md
teya-memory/design/AURA_IMPECCABLE_REPORT.json   # if detect ran
teya-memory/fragments/aurora-team-design-taste.md
```

`TASTE RECOVERY` = AUDIT с ссылкой на prior audit fix list.

---

## Запреты (все режимы)

- Не редактировать theme files (only report) — кроме PLAN не трогает ничего.
- Не переписывать `AURADESIGN.md`.
- Не запускать subagents.
- Не заменять Design Guardian.
- Не применять Taste React/Next rules (see exclusions).

Do **not** write to `teya-memory/01-handoff.md`.
