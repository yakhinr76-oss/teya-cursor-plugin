# Conversion Tracking Map — «Жираф», Уфа

## Meta

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (staging: b2b-tour.ru, noindex) |
| theme | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa` |
| mode | **CONVERSION-FIRST** |
| funnel_ref | `teya-memory/wp/conversion-funnel-map.md` ✅ |
| blueprint_ref | `teya-memory/wp/aurora-team-blueprint.md` |
| content_ref | `teya-memory/wp/page-content-pack.md` |
| north_star | `quiz_submit` + `phone_click` |
| primary_cta | **Подобрать тур** → `#quiz-modal` |
| phone | `tel:+79018105018` (+7 901 810-50-18) |
| office_email | office@zhiraf-ufa.ru |
| date | 2026-10-18 |
| author | aurora-team-conversion |
| metrika_id | **45658173** (owner-provided; theme_mod default `zhiraf_metrika_id`) |
| implementation_status | **complete** — Metrika после consent, `zhirafTrack` / `reachGoal` в `assets/dist/main.js` |
| legal_consent_status | **✅** — баннер с ссылками на обе политики; Метрика только после accept |

---

## CTA map

| Зона | Элемент | Label / copy | Target | DOM / hook | Событие |
|------|---------|--------------|--------|------------|---------|
| Header | Pill CTA | Подобрать тур | `#quiz-modal` | `[data-quiz-open]` | `cta_header_quiz_open` |
| Header | Телефон | +7 901 810-50-18 | `tel:+79018105018` | `.header-phone` `data-track="phone"` | `phone_click` |
| Hero | Primary pill | Подобрать тур | modal | `[data-quiz-open]` в `.hero-premium` | `cta_hero_quiz_open` |
| Audience tabs | Micro-CTA per segment | Подобрать тур / сегментный microcopy | modal | `[data-quiz-open]` в панели | `cta_audience_quiz_open` + param `segment` |
| How it works | Text-link (optional) | Подобрать тур | modal | `[data-quiz-open]` | `cta_section_quiz_open` + param `section=how-it-works` |
| Destinations | Card click | — | `/tour/{slug}/` | `.dest-premium-card` | `dest_card_click` + `country` |
| Trust | VK | Мы ВКонтакте | https://vk.com/zhiraf_ufa | external link | `messenger_click` + `channel=vk` (рекомендация) |
| Trust | Tel in block | +7… | `tel:` | `data-track="phone"` | `phone_click` |
| Lead-action | Short form submit | Отправить | AJAX `zhiraf_lead` | `.zhiraf-form` `#lead-action` | `lead_form_submit` |
| Lead-action | Alt | Или пройти квиз | modal | `[data-quiz-open]` | `cta_lead_section_quiz_open` |
| Footer | Legal + CTA | Подобрать тур | modal | `[data-quiz-open]` | `cta_footer_quiz_open` |
| Footer | NAP | tel / email | `tel:` / `mailto:` | `data-track="phone"` / `email` | `phone_click` / `email_click` |
| Mobile sticky | После `trust-highlights` | Позвонить + Подобрать тур | tel + modal | `#sticky-mobile-cta` | `phone_click` + `cta_sticky_quiz_open` |
| `/tour/` hub | Quiz strip | Подобрать тур | modal | `[data-quiz-open]` | `cta_tour_hub_quiz_open` |
| `/kontakty/` | Contact form | Отправить | AJAX `zhiraf_lead` | `.zhiraf-form` | `lead_form_submit` + `form_context=kontakty` |
| `/o-nas/` | CTA strip | Подобрать тур | modal | `[data-quiz-open]` | `cta_about_quiz_open` |
| Blog teaser | Все статьи | — | `/blog/` | `data-track="blog_teaser"` | `blog_teaser_click` |
| Single post | End CTA (контент) | Подобрать тур | modal | `[data-quiz-open]` | `cta_article_quiz_open` + `post_slug` |

**Правила CONVERSION-FIRST:** один primary pill above fold; телефон в шапке — не второй primary в hero. Все `[data-quiz-open]` используют единый label «Подобрать тур» (customizer `zhiraf_cta_text`).

---

## Form map

### 1. Квиз (North Star) — `#quiz-modal` / `zhiraf_quiz`

| Поле | name | required | validation |
|------|------|----------|------------|
| Состав | `quiz_composition` | да | radio |
| Месяц | `quiz_month` | да | select |
| Бюджет | `quiz_budget` | да | radio (диапазоны из fact-bank) |
| Пожелания | `quiz_wishes` | нет | textarea; prefill с destination landing |
| Имя | `name` | да | text |
| Телефон | `phone` | да | tel |
| Согласие ПДн | `consent_pd` | да | checkbox + ссылка на `/politika-konfidenczialnosti/` |
| Маркетинг 38-ФЗ | `consent_marketing` | нет | отдельный unchecked checkbox |
| Honeypot | `zhiraf_hp` | — | пустое; спам → 400 |

**Шаги:** 4 (`quiz_step_1` … `quiz_step_4` на «Далее»; submit → `quiz_submit`).

**Success:** inline message в modal («Заявка отправлена!…»); отдельная thank-you страница **не обязательна** (inline OK для MVP).

**Error:** JSON `message`; fallback copy с телефоном на 500 для lead, generic для quiz.

**Backend:** `wp_ajax_zhiraf_quiz` / `nopriv`; `check_ajax_referer( 'zhiraf_ufa_forms' )`; rate limit quiz 5 / 900 с.

### 2. Lead form — `zhiraf_lead`

| Размещение | Поля |
|------------|------|
| `#lead-action` / `form-premium` | `name`, `phone`, consents, honeypot |
| `/kontakty/` | + optional `message` (если в разметке) |

**Events:** `lead_form_start` (first focus в форме), `lead_form_submit` (успех AJAX).

**CRM:** `Zhiraf_UON_CRM::send_lead()` только для lead (не quiz) — stub без ключей.

### 3. Subscribe — `zhiraf_subscribe` (DEPTH, hold)

| Поле | required |
|------|----------|
| `email` | да |
| `consent_pd` | да |

**Статус продукта:** рассылка **needs_user_fact** — блок `subscribe-premium` на главной не должен конкурировать с квизом; до подтверждения клиентом — без активного CTA или скрыт.

**Event:** `subscribe_submit` (только если блок включён).

### 4. Поле `role` (B2B)

**Не использовать** — Phase 1 B2C (`conversion-funnel-map.md`).

---

## Visual / AURA (forms)

| Block | AURA id | Требование |
|-------|---------|------------|
| Lead section | `front-page:form-premium` | Белый фон, **1 form card** (не заменять plain card на голый input row без карточки) |
| Subscribe | `front-page:subscribe` | `subscribe-bg.jpg` full bleed — только DEPTH после ACTION |
| Quiz | custom P07 | Модальный multi-step UI — обязательный custom offer flow |

Form-side callout copy (из content pack): под квизом/формой — «Менеджер свяжется в рабочее время: Пн–Пт 10:00–19:00, Сб 11:00–15:00».

---

## Consent & legal (152-ФЗ / cookies)

### Стандартные страницы

| Страница | URL | Шаблон темы | Bootstrap slug |
|----------|-----|-------------|----------------|
| Политика конфиденциальности | `/politika-konfidenczialnosti/` | `page-politika-konfidenczialnosti.php` | `politika-konfidenczialnosti` |
| Политика cookies | `/politika-cookies/` | `page-politika-cookies.php` | `politika-cookies` |

**Footer:** обе ссылки в колонке «Правовая информация» — ✅ в `footer.php`.

### Формы (152-ФЗ)

- Обязательный checkbox `consent_pd` с ссылкой на политику конфиденциальности — ✅ `zhiraf_ufa_consent_fields()`.
- Маркетинг 38-ФЗ — отдельный optional checkbox — ✅.
- Без предустановленных галочек — ✅.

### Cookie banner

| Требование | Статус |
|------------|--------|
| Видимый баннер до согласия | ✅ `#cookie-banner` |
| Кнопка «Принять» / «Принять cookies» | ✅ текст «Принять» |
| Хранение согласия | ✅ `localStorage` key `zhiraf_cookie_consent` |
| Ссылка «Политика cookies» | ✅ в тексте баннера |
| Ссылка «Политика конфиденциальности» | ✅ в тексте баннера (`footer.php`) |
| Метрика только после accept | ✅ `footer.php` + event `zhiraf:cookie-accepted` |

### Cookie consent flow (runtime)

1. При первом визите `#cookie-banner` показывается, если в `localStorage` нет ключа `zhiraf_cookie_consent`.
2. Клик **Принять** → `localStorage.setItem('zhiraf_cookie_consent','1')` → `CustomEvent('zhiraf:cookie-accepted')` (`assets/dist/main.js`).
3. Слушатель в `footer.php` вызывает `loadMetrika()`: подключает `https://mc.yandex.ru/metrika/tag.js` (async/defer), затем `ym(<id>,'init',{ webvisor:true, clickmap, trackLinks, accurateTrackBounce })`.
4. ID счётчика: `get_theme_mod('zhiraf_metrika_id','45658173')`; пустое значение в Customizer отключает загрузку (staging).
5. Цели `reachGoal` отправляются через `window.zhirafTrack()` только при наличии consent; до инициализации `ym` цели буферизуются и сбрасываются после загрузки тега.

**Рекомендуемый copy баннера (Aurora):**

> Мы используем cookies и localStorage для работы сайта и (после вашего согласия) аналитики. [Политика cookies](/politika-cookies/) · [Политика конфиденциальности](/politika-konfidenczialnosti/).  
> Кнопка: **Принять cookies** (допустимо «Принять»).

### Thank-you / post-submit SEO

- Отдельный URL thank-you: **не планируется** в test build; при добавлении — `noindex, follow` + canonical self.
- Staging `b2b-tour.ru`: глобальный noindex (`inc/seo.php`).

---

## Anti-spam

| Механизм | Реализация |
|----------|------------|
| Nonce | `wp_create_nonce( 'zhiraf_ufa_forms' )` → `zhirafUfa.nonce`; `check_ajax_referer` |
| Honeypot | `zhiraf_hp` в формах + `zhiraf_ufa_is_honeypot_spam()` |
| Rate limit | `zhiraf_ufa_rate_limit_allow()` — lead 8/15m, quiz 5/15m, subscribe 5/15m (`inc/security.php`) |
| Captcha | **не требуется** на старте; добавить Turnstile/hCaptcha только при спаме в логах |

---

## Delivery & handoff

| Канал | Назначение | Конфиг |
|-------|------------|--------|
| Email | Все заявки | `wp_mail` → `office@zhiraf-ufa.ru`; темы: «Заявка на подбор тура», «Квиз: подбор тура», «Подписка на рассылку» |
| SMTP | Production deliverability | **needs_user_fact** — WP SMTP plugin или `SMTP_*` на хостинге; не хардкодить пароли в теме |
| U-ON CRM | Lead only | `ZHIRAF_UON_API_URL`, `ZHIRAF_UON_API_KEY` в `wp-config.php` — **не заданы** (stub) |
| Telegram / WhatsApp | Click-to-chat | **не в MVP**; только VK + tel + email |
| Webhook / Make | — | `CRM_WEBHOOK_URL` в `shared/teya.env.example` — пусто |

**Fallback при ошибке mail:** показать телефон +7 901 810-50-18 в error JSON (lead 500 — уже есть).

---

## Analytics goals

### Общие правила

1. Яндекс.Метрика грузится **только** после `zhiraf_cookie_consent` (или повторного визита с consent).
2. ID Метрики: theme mod `zhiraf_metrika_id` (Customizer), default **45658173**.
3. GA4: **не подключён в теме** — добавить gtag.js с тем же consent gate, если клиент даст `G-XXXXXXXX`.
4. Единая JS-функция `zhirafTrack(eventName, params)` → `ym(METRIKA_ID,'reachGoal', eventName, params)` и `gtag('event', eventName, params)`.

### События (имена = цели `reachGoal` в Метрике 45658173)

Создайте в интерфейсе Метрики **JavaScript-цели** с идентификаторами, совпадающими с `event_name` (тип «JavaScript-событие»).

| event_name (reachGoal) | Триггер | params (optional) | Приоритет |
|------------------------|---------|-------------------|-----------|
| `quiz_submit` | quiz AJAX success | `composition`, `month`, `budget` | **North Star** |
| `phone_click` | `a[data-track="phone"]` | `placement` | **North Star** |
| `lead_form_submit` | lead AJAX success | `form_context` | P0 |
| `messenger_click` | VK (`data-track="messenger"` / href vk.com) | `channel` (`vk`) | P0 |
| `cta_hero_quiz_open` | `[data-quiz-open]` в `.hero-premium` | — | P1 |
| `cta_header_quiz_open` | header pill | — | P1 |
| `cta_sticky_quiz_open` | `#sticky-mobile-cta` | — | P1 |
| `cta_footer_quiz_open` | footer | — | P1 |
| `cta_audience_quiz_open` | audience panel CTA | `segment` | P1 |
| `cta_lead_section_quiz_open` | `#lead-action` | — | P1 |
| `cta_section_quiz_open` | прочие секции | `section` | P2 |
| `quiz_step_1` … `quiz_step_4` | открытие шага / «Далее» | `step` | P2 |
| `lead_form_start` | first focus lead field | `form_context` | P2 |
| `email_click` | `a[data-track="email"]` | `placement` | P2 |
| `dest_card_click` | `.dest-premium-card` | `country` | P2 |
| `blog_teaser_click` | `data-track="blog_teaser"` | — | P2 |
| `audience_tab_*` | выбор таба аудитории | — | P2 |
| `subscribe_submit` | subscribe success | — | hold |

### Implementation (тема, 2026-10-18)

| Item | Файл | Статус |
|------|------|--------|
| Default Metrika ID 45658173 | `inc/customizer.php` | ✅ |
| Metrika loader + `ym(init)` webvisor after consent | `footer.php` | ✅ |
| `zhirafTrack` → `ym(...,'reachGoal',...)` | `assets/src/js/main.js` → `assets/dist/main.js` | ✅ |
| `phone_click`, `messenger_click`, `quiz_submit`, `lead_form_submit` | main.js | ✅ |
| Quiz / CTA / step goals | main.js | ✅ |
| GA4 gtag hook (если появится `gtag`) | main.js | stub ✅ |
| GA4 script | — | **не подключён** (needs_user_fact) |

---

## Eng optional (post-MVP)

| Событие | Условие |
|---------|---------|
| `scroll_50` | scroll depth 50% homepage |
| `faq_expand` | details open в `faq-home` |

---

## Credentials & missing data

| Credential | Статус |
|------------|--------|
| `zhiraf_metrika_id` | **45658173** (default в теме; override в Customizer) |
| GA4 measurement ID | needs_user_fact |
| SMTP / SPF | needs_user_fact |
| `ZHIRAF_UON_API_URL` / `KEY` | not configured |
| Newsletter product owner | needs_user_fact → hold subscribe |

---

## Legal blockers (release gate)

| # | Blocker | Owner |
|---|---------|-------|
| — | Нет блокеров по трекингу в теме | — |

**Forms + 152-ФЗ checkbox:** ✅ при опубликованных legal pages.

**Post-deploy:** в кабинете Метрики 45658173 создать JS-цели с именами из таблицы выше; smoke: accept cookies → Network `tag.js` → клик tel → `reachGoal phone_click`.

---

## Handoff

- **Aurora PAGE BUILDER:** исправить copy cookie banner; wire `zhirafTrack`; не ломать `form-premium` card; quiz CRM optional.
- **aurora-team-qa:** consent required, honeypot hidden, rate limit smoke, banner links ×2, Metrika deferred until accept.
- **aurora-team-performance-a11y:** cookie dialog `role="dialog"`, focus trap в quiz modal (улучшение).
- **Deploy:** создать WP-страницы politika-* через `scripts/bootstrap_zhiraf_b2b_wp.py` если отсутствуют на staging.
