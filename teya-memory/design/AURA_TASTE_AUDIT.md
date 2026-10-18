# AURA Taste Audit

- mode: TASTE AUDIT → **RECOVERY**
- project: Турагентство «Жираф», Уфа
- theme_slug: `zhiraf-ufa`
- theme_path: `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/`
- date: 2026-10-18
- profile: `teya-memory/design/AURA_TASTE_PROFILE.md` (design_ambition: **polished**)
- initial_auditor: `bc-fb384920` — **❌ BLOCKER** (T04, T09)
- recovery_agent: `bc-9773db71-f3bd-5d80-831c-70ba4f27911b`
- verdict: **✅ READY FOR RE-QA**

## Initial hard fails (resolved)

| ID | Was | Fix (2026-10-18) |
|----|-----|------------------|
| T04 | `.btn { transition: all }` | `background-color`, `color`, `border-color`, `opacity` 0.2s |
| T09 | Unbounded Google CDN + `--font-head` Unbounded | CDN removed from `header.php`; Manrope local only; headings 800 |
| P11 | trust-social missing | `front-page.php` section + SCSS |
| — | `--color-sand` | `#f5f2ed` in `:root` |
| Preview | stale `preview/home.html` | P0 funnel sync, 12 img paths |

## Re-check checklist (for bc-fb384920 / Guardian)

- [ ] No `fonts.googleapis.com` / Unbounded
- [ ] `.btn` computed `transition-property` ≠ `all`
- [ ] `preview/home.html` — audience, trust, faq visible
- [ ] Homepage meaningful images ≥12 (hero×3, dest×5, trust×2, blog, subscribe)

## Open warnings (non-blocker)

- **T08:** `btn--sm` height &lt;44px on some header CTAs — optional min-height fix.
- **§8–9 QA maps / paint:** re-run Guardian after preview refresh.

## Prior audit notes (T01–T15)

Impeccable detect on deliverables theme: **0 findings**. T01–T03, T05–T08 (warn), T10–T15 passed on initial pass except T04/T09.
