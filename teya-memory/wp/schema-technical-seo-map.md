# Schema & Technical SEO Map — «Жираф», Уфа (`zhiraf-ufa`)

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (prod) · b2b-tour.ru (staging, noindex) |
| theme_slug | `zhiraf-ufa` |
| mode | CONVERSION-FIRST |
| refs | `aurora-team-blueprint.md` §18–21, `page-content-pack.md`, `fact-bank.md`, `site.inv`, `semantic-core/zhiraf-ufa-2026/06-url-map.csv` |
| navigation_ref | `navigation-linking-map.md` — **отсутствует**; меню/перелинковка — blueprint §7, §19–20 |
| theme_seo_impl | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc/seo.php` |
| author | aurora-team-schema |
| date | 2026-10-18 |
| status | ✅ READY (контракт для Aurora / wp-theme-builder) |

**Правило фактов:** NAP, юрлицо, часы, РТА, sameAs, автор статей — только `fact-bank.md` / видимый контент. **Запрещено:** AggregateRating, Review, цены «от», выдуманные geo, фейковые авторы-люди.

---

## 1. Meta policy (глобально)

### 1.1 Title

| Правило | Значение |
|---------|----------|
| Источник правды (тексты) | `page-content-pack.md` + `06-url-map.csv` (`title_draft` при расхождении — content pack для test build) |
| Длина | 50–60 символов целевой диапазон; не обрезать юридическое имя бренда |
| Шаблон | `{Тема страницы} \| «Жираф»` или `{Тема} — «Жираф» Уфа` для local |
| Дубликаты | Один уникальный title на indexable URL |
| WP | `pre_get_document_title` в `inc/seo.php` — **синхронизировать** с таблицей §2 (сейчас расходится с content pack) |

### 1.2 Meta description

| Правило | Значение |
|---------|----------|
| Источник | `page-content-pack.md` (приоритет) → `zhiraf_ufa_meta_descriptions()` |
| Длина | 150–160 символов (допустимо 140–165) |
| Содержание | Запрос + Уфа + офис/квиз без цен «от» и без гарантий |
| Пусто | `search`, `404`, thank-you — description не выводить |

### 1.3 Canonical

| Контекст | Policy |
|----------|--------|
| Production `zhiraf-ufa.ru` | Self-canonical на каждый indexable URL (`link rel="canonical"`) |
| Staging `b2b-tour.ru` | Canonical на staging URL **или** на prod при cutover (см. `indexing-crawl-map`); до cutover — noindex + canonical self на staging OK |
| Пагинация блога | Canonical на страницу 1 архива `/blog/`; `rel=prev/next` не обязателен |
| Параметры | `?s=`, UTM — не в canonical; поиск — noindex |
| WP | `rel_canonical` core отключён; вывод в `zhiraf_ufa_head_meta()` |

### 1.4 Robots meta (страница)

| URL / тип | robots |
|-----------|--------|
| Indexable P0/P1 + опубликованные посты | `index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1` |
| Staging host | `noindex, nofollow` (уже в `seo.php` по `HTTP_HOST`) |
| `/search`, 404 | `noindex, nofollow` |
| Thank-you / служебные | `noindex, nofollow` |
| Пустой `/blog/` (0 постов) | `noindex, follow` до первой публикации (blueprint) |
| politika-* draft | `noindex` до готовности |

### 1.5 Open Graph / Twitter

| Тег | Policy |
|-----|--------|
| `og:type` | `website` для страниц; `article` для `single.php` |
| `og:title` | = document title |
| `og:description` | = meta description |
| `og:url` | = canonical |
| `og:locale` | `ru_RU` |
| `og:image` | Один дефолт `hero-turkey.jpg` или per-page featured при наличии; абсолютный URL |
| `twitter:card` | `summary_large_image` |

### 1.6 Hreflang

Один язык `ru`; отдельный `hreflang` не требуется.

---

## 2. Per-page meta (test build + backlog)

Canonical base: `https://zhiraf-ufa.ru` (на staging подставлять `home_url()`).

| URL | index | Title (content pack) | Description (content pack) | H1 (видимый) |
|-----|-------|----------------------|----------------------------|--------------|
| `/` | yes* | Туры из Уфы — подбор пакетных туров \| «Жираф» | Пакетные туры с вылетом из Уфы: подбор 2–4 вариантов, офис на Гагарина. Квиз за 2 минуты. Турагентство «Жираф» с 2011 года. | Туры с вылетом из Уфы — подбор без давления |
| `/tour/` | yes* | Направления отдыха с вылетом из Уфы \| «Жираф» | Турция, ОАЭ, Египет, Таиланд и Вьетнам — пакетные туры из Уфы. Подбор без прайс-борда на сайте. Квиз за 2 минуты. | Направления отдыха с вылетом из Уфы |
| `/o-nas/` | yes* | О турагентстве «Жираф» в Уфе — с 2011 года | Семейное турагентство в Уфе: команда, ценности, реестр РТА 0012822. Подбор туров с вылетом из Уфы без давления. | О нас — турагентство «Жираф» в Уфе |
| `/kontakty/` | yes* | Контакты «Жираф» — офис в Уфе, Гагарина 74/1 | Телефон +7 901 810-50-18, office@zhiraf-ufa.ru. Офис ул. Гагарина 74/1. Часы работы и форма заявки. | Контакты турагентства «Жираф» |
| `/blog/` | yes† | Блог о путешествиях из Уфы \| «Жираф» | Как купить тур, документы, «всё включено», отдых с детьми — статьи турагентства «Жираф» без рекламных цен «от». | Блог о путешествиях из Уфы |
| `/blog/{slug}/` | yes* | из Excalibur / `06-url-map.csv` | из meta.json статьи | H1 статьи |
| `/tour/{country}/` | yes* (backlog) | из `06-url-map.csv` | из `06-url-map.csv` | recommended_h1 из CSV |

\* На staging — noindex. † Пустой архив — noindex до BT-01.

---

## 3. Стабильные `@id` (GEO / entity graph)

Использовать на всех страницах одни и те же идентификаторы (prod origin):

| @id | Тип | Назначение |
|-----|-----|------------|
| `{origin}/#organization` | `TravelAgency` | Юрлицо + NAP (subtype LocalBusiness) |
| `{origin}/#website` | `WebSite` | Сайт + SearchAction |
| `{origin}/#webpage` | `WebPage` | Текущая страница (per request) |

`{origin}` = `home_url( '/' )` без trailing slash + path при необходимости для WebPage: `{permalink}#webpage`.

**Не дублировать** отдельные Organization и TravelAgency с разными именами — один узел `#organization`.

---

## 4. JSON-LD — глобальный граф (каждая indexable страница)

Вывод: один `<script type="application/ld+json">` с `@graph` (как в теме) **или** несколько скриптов — допустимо, если валидатор не видит конфликтов. Предпочтение: **единый `@graph` на страницу**.

### 4.1 TravelAgency / Organization (`#organization`)

Обязательные поля (Yandex + Google rich results baseline):

| Поле | Значение (fact-bank) |
|------|----------------------|
| `@type` | `TravelAgency` (допустимо; LocalBusiness-совместимо) |
| `name` | Турагентство «Жираф» |
| `legalName` | ООО «Жираф» |
| `url` | `https://zhiraf-ufa.ru/` (на staging — `home_url`) |
| `email` | office@zhiraf-ufa.ru |
| `telephone` | +7-901-810-50-18 (E.164 в JSON-LD) |
| `address` | PostalAddress: ул. Юрия Гагарина, 74/1, офис 3; Уфа; 450068; RU |
| `openingHoursSpecification` | Пн–Пт 10:00–19:00; Сб 11:00–15:00 |
| `sameAs` | https://vk.com/zhiraf_ufa |
| `identifier` (опционально) | РТА 0012822 как `PropertyValue` **только если** видно на странице (footer/kontakty) |

**Не добавлять до подтверждения:** `geo` / `GeoCoordinates`, `aggregateRating`, `review`, `priceRange`, `image` логотипа если нет стабильного public URL.

**Расширение на `/kontakty/`:** тот же `#organization` — не второй LocalBusiness; при желании усилить local — duplicate `@id`, не второй объект.

### 4.2 WebSite + SearchAction (`#website`)

| Поле | Policy |
|------|--------|
| `publisher` | `@id` → `#organization` |
| `potentialAction` | `SearchAction` → `/?s={search_term_string}` (P2; в теме уже есть) |
| Отключить SearchAction | Если поиск скрыт или noindex — убрать action, оставить WebSite |

### 4.3 WebPage (per URL)

На каждой indexable странице (кроме чистого редиректа):

```json
{
  "@type": "WebPage",
  "@id": "{canonical}#webpage",
  "url": "{canonical}",
  "name": "{document title}",
  "description": "{meta description}",
  "isPartOf": { "@id": "{origin}/#website" },
  "about": { "@id": "{origin}/#organization" },
  "inLanguage": "ru-RU"
}
```

Главная: `WebPage` + `@type` можно `CollectionPage` **не использовать** — оставить `WebPage`.

---

## 5. JSON-LD per route (test build)

| URL | Типы в `@graph` | Примечания |
|-----|-----------------|------------|
| `/` | Organization, WebSite, WebPage, **FAQPage** | FAQ = секция `faq-home` (8 Q&A видимы) |
| `/tour/` | Organization, WebSite, WebPage, BreadcrumbList, **FAQPage** | FAQ = 4 вопроса в content pack (`how-pick` / блок FAQ) — только если выведены в HTML |
| `/o-nas/` | Organization, WebSite, WebPage, BreadcrumbList, **FAQPage** | 4 FAQ из content pack — если в шаблоне |
| `/kontakty/` | Organization, WebSite, WebPage, BreadcrumbList, **FAQPage** | 3 FAQ; NAP дублирует schema |
| `/blog/` | Organization, WebSite, WebPage, BreadcrumbList | FAQPage нет |
| `/blog/{slug}/` | Organization, WebSite, WebPage, BreadcrumbList, **Article** | FAQPage только если FAQ блок в теле Excalibur |
| `/tour/{country}/` | Organization, WebSite, WebPage, BreadcrumbList | FAQPage только при видимом FAQ на лендинге |

### 5.1 FAQPage

| Правило | |
|---------|--|
| Условие | Каждый `Question` / `Answer` **дословно** из видимого DOM (accordion или статичный список) |
| Главная | 8 пар из `page-content-pack.md` § `faq-home` |
| Запрет | FAQ только в JSON-LD без UI |
| Формат | `@type: FAQPage`, `mainEntity: [ { @type: Question, name, acceptedAnswer: { @type: Answer, text } } ]` |

### 5.2 Article (`single.php`)

| Поле | Policy |
|------|--------|
| `headline` | post_title |
| `datePublished` / `dateModified` | ISO 8601 из WP |
| `author` | `{ "@type": "Organization", "name": "Турагентство «Жираф»" }` — не выдумывать персон |
| `publisher` | `@id` `#organization` |
| `image` | featured image URL если есть |
| `mainEntityOfPage` | canonical post URL |
| Запрет | `rating`, произвольный `Person` author |

### 5.3 BreadcrumbList (JSON-LD only)

**Visible top breadcrumbs: запрещены** (blueprint §20, content pack `visible_top_breadcrumbs_policy`).

| URL | itemListElement (name → item) |
|-----|-------------------------------|
| `/tour/` | Главная → `/` · Направления → `/tour/` |
| `/o-nas/` | Главная → `/` · О нас → `/o-nas/` |
| `/kontakty/` | Главная → `/` · Контакты → `/kontakty/` |
| `/blog/` | Главная → `/` · Блог → `/blog/` |
| `/blog/{slug}/` | Главная → `/` · Блог → `/blog/` · {заголовок} → permalink |
| `/tour/turciya/` (backlog) | Главная → `/` · Направления → `/tour/` · Турция → `/tour/turciya/` |

**Текущий gap темы:** `zhiraf_ufa_breadcrumb_json_ld()` — только 2 уровня (Главная + текущий). Aurora **должна** развернуть цепочки для blog и tour hub/destinations.

Реализация: `inc/breadcrumbs.php` — генерация массива + echo JSON-LD; **без** HTML trail.

---

## 6. Yandex

| Требование | Реализация |
|------------|------------|
| Регион | Уфа; контент и NAP на русском |
| `yandex-verification` | `<meta name="yandex-verification" content="…">` из `get_theme_mod( 'zhiraf_yandex_verification' )` — **ID needs_user_fact** |
| Яндекс.Метрика | Counter ID needs_user_fact; скрипт **defer/async**, не блокировать LCP (`conversion-tracking-map`) |
| Organization / LocalBusiness | Поля §4.1 на каждой странице через глобальный граф |
| Внутренние ссылки | Все URL из schema map должны быть достижимы `<a href>` из меню/футера/контента (blueprint §19; детали — navigation agent) |
| Вебмастер | После deploy: sitemap URL в Яндекс.Вебмастер |

---

## 7. Google

| Требование | Policy |
|------------|--------|
| Valid JSON-LD | [Rich Results Test](https://search.google.com/test/rich-results) / Schema Markup Validator — без errors |
| Content parity | Телефон, адрес, часы в schema = footer и `/kontakty/` |
| BreadcrumbList | JSON-LD соответствует §5.3; visible crumbs не требуются |
| FAQ | Только при видимом FAQ (главная P0) |
| Article | Только `post` |
| Запрет | Fake ratings, prices, reviews, fake addresses |
| CWV | См. §9 и `performance-accessibility-map.md` |
| Search Console | Sitemap после prod |

---

## 8. robots.txt

```
User-agent: *
Disallow: /wp-admin/
Allow: /wp-admin/admin-ajax.php
Disallow: /?s=
Disallow: /search/

Sitemap: https://zhiraf-ufa.ru/sitemap.xml
```

| Среда | Policy |
|-------|--------|
| Production | Allow public paths; disallow admin, search, internal |
| Staging `b2b-tour.ru` | `Disallow: /` **или** весь сайт noindex (предпочтение: noindex в meta + disallow в robots) |
| AI crawlers | По `indexing-crawl-map` (обычно allow read для публичного контента) |

Генерация: Yoast/RankMath **не** обязательны — `inc/robots.php` или core filter `robots_txt`.

---

## 9. Sitemap.xml

| Включить | Исключить |
|----------|-----------|
| `/`, `/tour/`, `/o-nas/`, `/kontakty/`, `/blog/` | Search, 404, thank-you, draft politika |
| Опубликованные `/blog/{slug}/` | Staging URLs |
| P1 `/tour/{country}/` когда опубликованы | `noindex` страницы |
| `lastmod` из `post_modified_gmt` | Attachment URLs без контента |

**Частота:** `changefreq` optional; приоритет: home 1.0, tour/kontakty 0.9, o-nas/blog 0.8, posts 0.7.

**Staging:** не отдавать prod sitemap на b2b-tour.ru или отдавать с noindex URLs only.

Детали canonical/redirect — `indexing-crawl-map.md` (parallel).

---

## 10. Core Web Vitals (theme SEO baseline)

Синхронизация с `performance-accessibility-map.md`:

| Метрика | Target | SEO связь |
|---------|--------|-----------|
| LCP | < 2.5s mobile | Hero one `fetchpriority=high`; локальные шрифты |
| INP | < 200ms | Квиз/меню не блокируют main thread |
| CLS | < 0.1 | width/height на img; зарезервировать hero height |

**JSON-LD:** в `<head>`, синхронный вывод OK (малый размер); не дублировать mega-graph на каждый тип отдельным огромным скриптом.

**Metrika / third-party:** после `DOMContentLoaded` или defer; не в critical path.

---

## 11. GEO / AEO (answer blocks)

| Элемент | Policy |
|---------|--------|
| Видимые answer-blocks | 40–60 слов под ключевыми H2 на страницах (content pack) |
| FAQ | Покрывают возражения; schema FAQPage зеркалит текст |
| `@id` organization | Стабильный для цитирования в AI overviews |
| `llms.txt` | Не schema — см. indexing agent |

---

## 12. Gap analysis: тема vs контракт

| ID | Severity | Gap | Owner |
|----|----------|-----|-------|
| S1 | medium | Title/description в `seo.php` ≠ `page-content-pack.md` | Aurora |
| S2 | medium | BreadcrumbList — плоский 2-level | Aurora `inc/breadcrumbs.php` |
| S3 | low | Нет `WebPage` node per URL | Aurora |
| S4 | low | FAQPage не выводится (только org+site+article) | Aurora |
| S5 | low | `geo` отсутствует (корректно до координат от клиента) | local-entity |
| S6 | blocker deploy | yandex-verification, Metrika ID | client / conversion |

---

## 13. Handoff checklist (Aurora)

1. Синхронизировать `zhiraf_ufa_document_title()` и meta map с §2.
2. Добавить `WebPage` + условный `FAQPage` в `zhiraf_ufa_json_ld()` или вынести в `inc/schema.php`.
3. Реализовать breadcrumb JSON-LD по §5.3.
4. На главной после рендера `faq-home` — включить FAQPage.
5. На `single.php` — Article + publisher `@id`.
6. Проверить staging noindex + canonical.
7. После prod — sitemap URL в GSC и Вебмастер.

---

## 14. Machine snippet (`site-spec.json` seeds)

```yaml
schema:
  organization_id: "/#organization"
  organization_type: TravelAgency
  website_id: "/#website"
  global_graph: [TravelAgency, WebSite, WebPage]
  per_page:
    front_page: [FAQPage]
    page-tour: [BreadcrumbList, FAQPage?]
    page-o-nas: [BreadcrumbList, FAQPage?]
    page-kontakty: [BreadcrumbList, FAQPage?]
    home_blog: [BreadcrumbList]
    single: [BreadcrumbList, Article, FAQPage?]
  visible_breadcrumbs: false
  staging_noindex: true
```
