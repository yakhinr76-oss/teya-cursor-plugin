# Conversion Funnel Map

> Шаблон для `teya-memory/wp/conversion-funnel-map.md`  
> Агент: `aurora-team-conversion-architect`

## Meta

| Поле | Значение |
|------|----------|
| project | |
| mode | CONVERSION-FIRST \| standard |
| north_star_metric | form_submit, phone_click |
| primary_cta_label | |
| secondary_cta_label | |
| brief_ref | teya-memory/00-brief.md |
| status | ✅ READY \| ⚠️ NEEDS FACTS \| ❌ BLOCKER |

## Воронка (нарратив)

Одним абзацем для команды и заказчика:

```text
Сначала показываем проблему клиента (PAIN), затем наше решение (SOLUTION),
затем доказательства надёжности (PROOF), снимаем возражения (OBJECTION)
и ведём к заявке (ACTION). SEO-глубина — на внутренних страницах и в блоге.
```

## Метрики

| Метрика | Цель / ориентир | Событие Metrika/GA4 |
|---------|-----------------|---------------------|
| Primary CTA click (hero) | | cta_hero_click |
| Header CTA click | | cta_header_click |
| Phone click | | phone_click |
| Form start | | form_start |
| Form submit | | form_submit |
| Role tab HR | | role_tab_hr |
| Role tab CFO | | role_tab_cfo |
| Role tab Admin | | role_tab_admin |

**Time-to-first-CTA (прокси):** целевой ориентир ≤15–20 сек для занятого B2B-ЛПР.

## Role routing

| role_id | label | primary_pain | barrier_addressed | homepage_block | dedicated_url | cta_microcopy |
|---------|-------|--------------|-------------------|----------------|---------------|---------------|
| hr | HR | | | role-tabs / tab-hr | | |
| cfo | Финдиректор | | | role-tabs / tab-cfo | | |
| admin | Админ | | | role-tabs / tab-admin | | |

## SEO depth routing

| intent / cluster | главная (кратко) | полная глубина URL | знаков target |
|------------------|------------------|--------------------|---------------|
| | eyebrow + ссылка | /uslugi/... | 4000–8000 |

## Homepage — порядок секций

| # | section_id | funnel_stage | target_roles | why_here | barrier_addressed | primary_cta | remove_if |
|---|------------|--------------|--------------|----------|-------------------|-------------|-----------|
| 1 | hero-pain | PAIN | all | | | no | |
| 2 | offer-bullets | SOLUTION | all | | | optional | |
| 3 | role-tabs | SOLUTION | hr,cfo,admin | После общей боли — язык под роль | | per tab | нет сегментов в audience-map |
| 4 | process | PROOF | all | | | no | |
| 5 | trust-facts | PROOF | all | | | no | нет фактов в fact-bank |
| 6 | faq | OBJECTION | all | | | no | |
| 7 | lead-form | ACTION | all | | | yes | |

### Запрещено на главной (cut_list)

- 
- 

### Оставить / перенести (keep_list)

| section_id | решение | куда |
|------------|---------|------|
| services-grid-8 | cut / move | /uslugi/ |

## Form spec

| Поле | required | notes |
|------|----------|-------|
| name | yes | |
| phone | yes | |
| company | yes | |
| trips_per_month | yes | квалификация лида |
| role | optional | HR / CFO / Admin / Другое |
| comment | no | |
| consent | yes | ссылка на privacy + cookies |

**Размещение:** footer form + sticky CTA после `role-tabs` (да/нет)

## Per-page funnel (inner pages)

### page: `{slug}`

| # | section_id | funnel_stage | why_here |
|---|------------|--------------|----------|
| 1 | | | |

## Current site gaps (если редизайн)

| gap | severity | fix |
|-----|----------|-----|
| Два primary CTA в hero | high | один primary |
| Форма только в footer | high | sticky / mid-page form |
| Дубль блоков услуг | medium | один каталог |

## Conflict resolution

| conflict | winner | action |
|----------|--------|--------|
| AURA: 8 service cards on homepage | funnel-map | 4 карточки + ссылка «все услуги» |
| anti-haltura: 9000 chars homepage | brief CONVERSION-FIRST | 3000–4000 плотных знаков на главной |

## Handoff

- **aurora-team-lead:** …
- **aurora-team-content:** …
- **aurora-team-conversion:** …
- **aurora-team-navigation:** …
- **Aurora:** …
- **aurora-team-qa:** проверить single primary CTA, funnel order, role-tabs

## Missing facts / needs_user_fact

- 

## Blockers

- 
