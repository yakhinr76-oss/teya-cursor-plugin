# Conversion Funnel Map — турагентство «Жираф», Уфа

## Meta

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (staging: b2b-tour.ru) |
| mode | **CONVERSION-FIRST** |
| north_star_metric | `quiz_submit` (квиз = главная заявка) + `phone_click` |
| primary_cta_label | **Подобрать тур** |
| secondary_cta_label | Позвонить **+7 901 810-50-18** (text link / `tel:` в шапке, outline в hero — не второй primary) |
| brief_ref | `teya-memory/00-brief.md`, `teya-memory/site.inv` |
| semantic_ref | `teya-memory/semantic-core/zhiraf-ufa-2026/` (`06-url-map.csv`, `07-content-briefs.md`) |
| design_constraint | `teya-memory/design/AURA_PAGE_PLAN.md` (подчиняется этому map на главной) |
| status | ✅ READY |

## Воронка (нарратив)

Сначала за 3–5 секунд фиксируем «туры из Уфы, честный подбор» (AWARE) и узнаваемую боль — переплата на агрегаторах, страх «не тот отель», нехватка времени на десятки вкладок (PAIN). Дальше показываем механику: квиз + 2–4 варианта от менеджера, вход в каталог направлений без прайс-борда (SOLUTION). Процесс «заявка → подбор → договор → отдых», юрлицо, РТА, офис на Гагарина, VK вместо фейковых отзывов (PROOF). FAQ снимает возражения про OTA, навязчивые звонки и документы (OBJECTION). Одна primary CTA ведёт в квиз; дублируем короткой формой и sticky на mobile (ACTION). SEO-глубина (статьи Excalibur, лендинги стран, «как купить тур») — **после** ACTION или на отдельных URL, не между PAIN и ACTION.

## Метрики

| Метрика | Цель / ориентир | Событие Metrika/GA4 |
|---------|-----------------|---------------------|
| Primary CTA click (hero) | ≥8% от engaged sessions (ориентир) | `cta_hero_quiz_open` |
| Header CTA click | отслеживать долю от quiz opens | `cta_header_quiz_open` |
| Sticky CTA click (mobile) | после scroll 50% | `cta_sticky_quiz_open` |
| Quiz step 1 complete | drop-off по шагам | `quiz_step_1` … `quiz_step_4` |
| Quiz submit (North Star) | главная конверсия | `quiz_submit` |
| Phone click (North Star) | второй равный North Star | `phone_click` |
| Lead form start | mid-page форма | `lead_form_start` |
| Lead form submit | горячие без квиза | `lead_form_submit` |
| Destination card click | intent по стране | `dest_card_click` + param `country` |
| Audience tab — семья | сегмент | `audience_tab_family` |
| Audience tab — пара | сегмент | `audience_tab_couple` |
| Audience tab — первая поездка | сегмент | `audience_tab_cautious` |
| Audience tab — опытные | сегмент | `audience_tab_experienced` |
| Blog teaser click | DEPTH | `blog_teaser_click` |
| Subscribe submit | только если рассылка подтверждена | `subscribe_submit` |

**Прокси time-to-first-CTA:** ≤10 сек на mobile 375px (hero CTA без скролла; телефон в шапке).

**Form completion (квиз):** целевой ориентир ≥55% от открывших шаг 1 (после запуска Метрики).

---

## Role routing (B2B)

**HR / CFO / Admin:** не применяется — Phase 1 B2C (`audience-map.md` § B2B note). Поле `role` в формах **не добавлять**.

## Audience segment routing (B2C)

Замена `role-tabs` для розницы: табы **не** копируют один абзац; у каждого сегмента свои буллеты боли и micro-CTA в квиз.

| segment_id | label | primary_pain | barrier_addressed | homepage_block | dedicated_url | cta_microcopy |
|------------|-------|--------------|-------------------|----------------|---------------|---------------|
| family | Семья с детьми | «Не тот отель / перелёт с детьми» | Недоверие к агентствам; сравнение с OTA | `audience-tabs` / tab-family | `/blog/tury-s-detmi-iz-ufy/` | Подобрать семейный тур |
| couple | Пара | «Скрытые доплаты, нет времени сравнивать» | Переплата на агрегаторах | `audience-tabs` / tab-couple | `/tour/turciya/` | Подобрать тур для двоих |
| cautious | Первая заграница | «Запутаюсь в документах» | Страх онлайн; нужен офис | `audience-tabs` / tab-cautious | `/blog/kak-kupit-tur-v-ufe/` | Разобраться с менеджером |
| experienced | Опытные | «Нужен эксперт, а не кассир» | Нехватка экспертизы | `audience-tabs` / tab-experienced | `/o-nas/` | Описать пожелания в квизе |
| flexible | Гибкие даты | «Пропущу акцию» | Боятся переплатить без контекста | `audience-tabs` / tab-flexible (опц.) | `/kontakty/` | Узнать актуальные варианты |
| local | Уфа / РБ | «Где надёжное агентство» | «Где надёжное агентство не в ТЦ» | trust-highlights + footer NAP | `/kontakty/` | Приехать на Гагарина |

---

## SEO depth routing

| intent / cluster | главная (кратко) | полная глубина URL | знаков target (inner) |
|------------------|------------------|--------------------|------------------------|
| CL-HOME «туры из уфы» | H1 + hero 80–120 слов до CTA | `/` не раздувать | 3000–4500 плотных на главной (override anti-haltura) |
| CL-CATALOG направления | табы + 5 карточек → `/tour/` | `/tour/`, `/tour/turciya/` … | 2500–4000 на hub; 4000–6000 на destination |
| CL-QUIZ подбор | квиз modal + audience-tabs | — | квиз = UX, не SEO-текст |
| CL-BLOG-BUY «как купить» | 1 teaser-карточка BT-01 | `/blog/kak-kupit-tur-v-ufe/` | ~8000 (Excalibur) |
| CL-AI «всё включено» | ссылка из FAQ / блога | `/blog/chto-vhodit-v-tur-vse-vklyucheno/` | ~8000 |
| CL-DOCS документы | FAQ пункт + ссылка | `/blog/dokumenty-dlya-zagranicy-iz-ufy/` | ~8000 |
| CL-KIDS дети | tab-family + ссылка | `/blog/tury-s-detmi-iz-ufy/` | ~8000 |
| CL-CMP сравнение | FAQ / блог | `/blog/turciya-ili-egipet-iz-ufy/` | ~8000 |
| CL-ABOUT доверие | trust bullets | `/o-nas/` | 3000–5000 |
| CL-CONTACT NAP | footer + trust | `/kontakty/` | 2000–3500 |

---

## Homepage — порядок секций

**Счётчик CONVERSION-FIRST:** 7 секций воронки + 2 блока DEPTH **после** ACTION (не вставлять между PAIN и ACTION).

| # | section_id | funnel_stage | target_roles | why_here | barrier_addressed | primary_cta | remove_if | seo_owner |
|---|------------|--------------|--------------|----------|-------------------|-------------|-----------|-----------|
| 1 | `hero-premium` | AWARE + PAIN | all | Первый экран: «туры с вылетом из Уфы» (semantic H1 из `07-content-briefs`) + боль «устали от безликого поиска и скрытых доплат». До первого CTA ≤120 слов. Коллаж направлений — визуальный якорь, не каталог цен. | «Обманут с ценой»; «Не знаю, куда ехать» | **да** — «Подобрать тур» → `#quiz-modal` | Убрать вторую pill-кнопку равного веса с квизом | homepage |
| 2 | `audience-tabs` | PAIN → SOLUTION | family, couple, cautious, experienced | После общего оффера — язык сегмента из `audience-map.md`. Каждый таб: 3 буллета боли + 1 micro-CTA (открыть квиз с тем же primary label). Следующий шаг: пользователь узнаёт себя и жмёт CTA. | Недоверие; страх документов; OTA-сравнение | per tab — «Подобрать тур» (тот же primary) | Нет сегментов в research (не наш случай) | homepage |
| 3 | `destinations` | SOLUTION | all | Один вход в каталог: табы + карточки 5 стран P0 → `/tour/{slug}/`. Не дублировать отдельный «services grid». Показывает «куда летим из Уфы» без цен «от». | «Не знаю страну» | нет (клик по карточке = secondary intent) | Дубль второго каталога на главной | inner_page (`/tour/*`) |
| 4 | `how-it-works` | SOLUTION + PROOF | all | 4 шага из темы: заявка/квиз → 2–4 варианта → договор (офис/онлайн) → отдых. Снимает «что будет после клика». После шагов — **одна** text-link CTA «Подобрать тур», не вторая primary pill в том же блоке. | «Завалят вариантами» (контраст: 2–4); «Нет времени» | optional text-link | — | homepage |
| 5 | `trust-highlights` | PROOF | all, local, cautious | Факты только из fact-bank: ООО «Жираф», **РТА 0012822**, с **апреля 2011**, офис **ул. Гагарина, 74/1**, часы Пн–Пт 10–19 / Сб 11–15, ссылка **VK** — без AggregateRating и цитат отзывов. | «А вдруг агентство закроется»; недоверие к ТА | нет | Нет подтверждённых фактов (не наш случай) | homepage + `/o-nas/` |
| 6 | `faq-home` | OBJECTION | all | 6–8 вопросов из dossier §10 / `07-content-briefs`: OTA vs агентство, навязчивые звонки (38-ФЗ), состав пакета, виза (без гарантий), документы → ссылки на блог. «Кому не подходит»: ищущим мгновенный прайс без менеджера. | Travelata дешевле; страх звонков; документы | нет | — | blog (глубина ответов) |
| 7 | `lead-action` | ACTION | all | Финальный якорь: короткая форма (имя + телефон) **или** повтор primary «Подобрать тур». Sticky bar mobile: `tel:` + квиз после scroll мимо `trust-highlights`. | «Нет времени ехать в офис» (квиз); горячий лид | **да** | — | homepage |
| 8 | `editorial-blog-teaser` | DEPTH | all | **После** ACTION: 1 главная карточка BT-01 + ссылка «Все статьи» → `/blog/`. Не разворачивать 1+3 на CONVERSION-FIRST без согласования (см. conflict). | Исследователи до заявки | нет | Нет опубликованных статей | blog |
| 9 | `subscribe-premium` | DEPTH | all | Email-подписка — **secondary**; не конкурирует с квизом. | — | нет | `needs_user_fact`: рассылка не подтверждена | homepage |

### Запрещено на главной (cut_list)

- Цены «от … ₽» и блоки горящих без витрины в теме.
- Фейковые отзывы, рейтинги, AggregateRating.
- Дублирующий grid «8 услуг» / второй каталог направлений.
- Маркетинговые цифры legacy (782 клиента, 16 экспертов) без `needs_user_fact`.
- Обещание SLA «перезвоним за 15 минут» (нет в fact-bank) — убрать из `form-premium` copy.
- Полноценный editorial 1+3 **между** PROOF и ACTION (перенос после `lead-action` или сокращение до 1 карточки).

### Оставить / перенести (keep_list)

| section_id | решение | куда |
|------------|---------|------|
| `hero-premium` + collage | keep | синхронизировать H1 с semantic brief |
| `destinations` | keep | единственный hub направлений на главной |
| `how-it-works` | keep | PROOF процесса |
| `quiz-modal` (global) | keep | North Star |
| `editorial` 1+3 | move / shrink | DEPTH после ACTION → teaser 1+link |
| `subscribe-premium` | hold | до подтверждения рассылки клиентом |
| Legacy FAQ длинный | move | сжать на главной; детали в блог |

---

## Form spec

### Primary: квиз «Подбор тура за 2 минуты» (`#quiz-modal`)

| Поле | required | notes |
|------|----------|-------|
| `quiz_composition` | yes | Пара / Семья с детьми / Один / Компания друзей |
| `quiz_month` | yes | select месяцев |
| `quiz_budget` | yes | диапазоны ₽/чел. из fact-bank (не цена оффера) |
| `quiz_wishes` | no | направление, пожелания; prefill из destination landing |
| `name` | yes | |
| `phone` | yes | |
| `consent_152` | yes | политика конфиденциальности |
| `consent_38` | optional separate checkbox | маркетинг |
| `zhiraf_hp` | honeypot | hidden |

**Submit event:** `quiz_submit` → email `office@zhiraf-ufa.ru` (тема).

### Secondary: lead form (`lead-action` / `form-premium`)

| Поле | required | notes |
|------|----------|-------|
| `name` | yes | |
| `phone` | yes | |
| `consent_152` | yes | |
| `consent_38` | optional | |

Max 5 полей + consent — **соответствует**.

### Subscribe (DEPTH)

| Поле | required | notes |
|------|----------|-------|
| `email` | yes | только если рассылка подтверждена |
| `consent_152` | yes | |

**Размещение:** квиз — global modal (header, hero, footer, sticky). Короткая форма — секция `lead-action` на главной + полная на `/kontakty/`. **Sticky CTA:** да — mobile после `trust-highlights` (`phone_click` + `cta_sticky_quiz_open`).

---

## Per-page funnel (inner P0)

### `/tour/` (hub)

| # | section_id | funnel_stage | why_here |
|---|------------|--------------|----------|
| 1 | catalog-hero | AWARE | H1 «Направления с вылетом из Уфы» |
| 2 | dest-grid | SOLUTION | чипы + карточки 5 стран |
| 3 | how-pick | PROOF | «как подбираем» без цен |
| 4 | quiz-cta | ACTION | primary «Подобрать тур» |

### `/tour/{country}/` (destination)

| # | section_id | funnel_stage | why_here |
|---|------------|--------------|----------|
| 1 | dest-hero | AWARE + SOLUTION | страна + вылет из Уфы |
| 2 | for-whom | PAIN | семья / пара / первая поездка |
| 3 | package-notes | PROOF | что уточняем (даты, AI/UAI) |
| 4 | faq-geo | OBJECTION | 4–5 вопросов |
| 5 | quiz-cta | ACTION | квиз (+ prefill country) |

### `/kontakty/`

NAP + карта + форма + реквизиты → ACTION; ссылка BT-01.

### `/o-nas/`

E-E-A-T команда с 2011 → PROOF; CTA квиз внизу.

### `/blog/` + статьи Excalibur

DEPTH → CTA в конце статьи + 2–3 ссылки на `/tour/*`.

---

## Current site gaps (редизайн: legacy + текущая тема)

| gap | severity | fix |
|-----|----------|-----|
| Hero H1 эмоциональный, не semantic «туры из Уфы» | medium | H1 из `07-content-briefs` без потери Globe Trekker тона |
| Нет `faq-home` и `trust-highlights` на главной в теме | high | добавить по blueprint lead |
| Обещание «15 минут» в форме | medium | убрать или заменить нейтральным «свяжемся в рабочее время» |
| Editorial 1+3 до формы | medium | перенести после ACTION / teaser |
| Subscribe без подтверждённой рассылки | low | hold или noindex CTA до facts |
| Legacy: отзывы, цены «от», мусорные контакты | high | cut_list (уже в brief) |

---

## Conflict resolution

| conflict | winner | action |
|----------|--------|--------|
| AURA: editorial 1+3 mid-page | **funnel-map** | 1 карточка BT-01 после `lead-action`; полная сетка — `/blog/` |
| AURA: subscribe full-bleed | **funnel-map** | оставить после ACTION; не ставить перед квизом |
| anti-haltura: 9 секций + 5000+ знаков главная | **brief CONVERSION-FIRST** | 7 воронка + 2 DEPTH; 3000–4500 знаков плотного текста + FAQ; остальной объём — `/tour/*`, блог |
| anti-haltura: 2 CTA в hero | **funnel-map** | один primary pill; телефон только header `tel:` |
| Semantic: blog на главной | **funnel-map** | teaser только DEPTH после ACTION |
| Audience-map: 6 сегментов, нет HR/CFO | **funnel-map** | `audience-tabs` вместо `role-tabs`; без поля `role` |

---

## Missing facts / needs_user_fact

- Подтверждение email-рассылки (`subscribe-premium`).
- Список туроператоров для усиления PROOF (опциональный блок).
- ID Яндекс.Метрики для production.
- Приоритет 2–3 направлений в hero tabs (сезон).
- Отзывы ВК / карт — когда будут URL.

## Blockers

- Нет (PROOF опирается на fact-bank; B2B role routing N/A by design).

---

## Handoff

- **aurora-team-lead:** порядок секций = таблица Homepage; не вставлять DEPTH между PAIN и ACTION; добавить `faq-home`, `trust-highlights`, `audience-tabs`; sticky CTA mobile.
- **aurora-team-content:** тексты по `funnel_stage` и `segment_id`; H1 главной из semantic brief; FAQ 6–8; убрать «15 минут»; разные буллеты в audience-tabs; без цен «от» и отзывов.
- **aurora-team-conversion:** события из таблицы метрик; wire quiz + lead AJAX; sticky; не дублировать primary label.
- **aurora-team-navigation:** header/footer CTA → `data-quiz-open`; телефон `tel:+79018105018`; blog teaser → BT-01; hub `/tour/`.
- **Aurora PAGE BUILDER:** не дублировать destination grid; hero ≤120 слов до CTA; subscribe не конкурирует с квизом.
- **aurora-team-qa:** checklist funnel order, single primary CTA above fold, quiz 4 шага, consent 152/38, PROOF только fact-bank.
