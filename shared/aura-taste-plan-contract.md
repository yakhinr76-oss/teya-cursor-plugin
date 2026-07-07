# AURA Taste Plan Contract — Teya

Контракт режима **TASTE PLAN** для `aurora-team-design-taste`. Дополняет `aura-taste-exclusions.md` и `aura-impeccable-gate.md`.

## Когда

После AURA deliverables (шаг 7 gate), **до** `aurora-team-conversion-architect` / `aurora-team-lead`.

## Выход

```text
teya-memory/design/AURA_TASTE_PROFILE.md
teya-memory/fragments/aurora-team-design-taste.md   # mode: TASTE PLAN
```

## Кто обязан читать профиль

| Агент | Обязанность |
|-------|-------------|
| `aurora-team-conversion-architect` | Не ломать visual craft ради CTA; waivers в профиле |
| `aurora-team-lead` | Blueprint: layout families, taste mandates per section |
| `aurora-team-content` | Без eyebrow-spam, fake proof, generic SaaS copy patterns |
| `aurora` PAGE BUILDER | CSS/компоненты по mandates + anti-slop |
| `aurora-team-motion` MOTION PLAN | Easing/tempo из профиля |
| `aurora-team-design-taste` AUDIT | Сверка theme vs profile |

## `design_ambition` (из brief или default)

| Tier | Описание | Positive craft (P01–P15) |
|------|----------|----------------------------|
| `safe` | Минимальный anti-slop | P-checks = warnings only |
| `polished` | B2B premium | P01,P04,P07,P12 = soft mandates |
| `studio` | Студийный B2B / agency-lite | P01–P08,P10,P12 = hard mandates |
| `experimental` | Awwwards / bold | P01–P15 hard; exclusions частично сняты |

Default если не указано в brief: `polished` для B2B lead-gen, `safe` для legal-only sites.

## Минимальная структура `AURA_TASTE_PROFILE.md`

```markdown
# AURA Taste Profile

- mode: TASTE PLAN
- project: ...
- theme_slug: ...
- date: ...
- design_ambition: safe | polished | studio | experimental
- verdict: ✅ READY | ❌ BLOCKER

## Summary
(2-4 sentences)

## Anti-slop mandates (T01–T15)
| ID | Mandate for Aurora | Waiver |

## Positive craft mandates (P01–P15)
| ID | Target | Homepage min | Inner pages |

## Layout variance targets
| Metric | Target |
| section_layout_families_min | N |
| consecutive_same_family_max | N |
| shaped_transitions_min | N |
| meaningful_images_homepage_min | N |
| colored_surface_ratio_min | 0.0-1.0 |

## CSS / component rules for Aurora
- explicit transitions (no `transition: all`)
- ...

## Motion taste
- easing, tempo, forbidden patterns

## Waivers (design-lock / AURADESIGN / funnel-map)
| Pattern | Authority | Reason |

## Aurora handoff checklist
1. ...
```

## Gate

Director **не запускает** `aurora-team-lead` без `AURA_TASTE_PROFILE.md` со статусом `✅ READY`.

При `❌ BLOCKER` — дозапустить AURA на недостающие decomposition/budget items или уточнить brief.

## Authority order

1. `design-lock.json`
2. `AURADESIGN.md`
3. `conversion-funnel-map.md` (порядок блоков)
4. `AURA_TASTE_PROFILE.md` (форма блоков, anti-slop, craft)
5. Generic Taste heuristics

Funnel-map побеждает **порядок** секций; taste profile побеждает **форму** (не generic simplification).
