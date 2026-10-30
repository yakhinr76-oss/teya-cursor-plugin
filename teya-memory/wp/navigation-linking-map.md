# Navigation & Internal Linking Map — «Жираф», Уфа

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (staging: b2b-tour.ru) |
| theme_slug | `zhiraf-ufa` |
| semantic_run | `teya-memory/semantic-core/zhiraf-ufa-2026/` |
| blueprint_ref | `teya-memory/wp/aurora-team-blueprint.md` §7, §19–20 |
| url_map_ref | `06-url-map.csv` |
| author | aurora-team-navigation |
| date | 2026-10-18 |
| status | ✅ READY для Aurora / WP Theme Builder |

---

## 1. Принципы

- **URL:** только пути из `06-url-map.csv` и `site.inv`; без противоречий Ядрышку.
- **Anchors:** описательные («туры в Турцию с вылетом из Уфы»), не «подробнее» / «здесь» / «читать» без контекста.
- **Hub-and-spoke:** `/` и `/tour/` — хабы; лендинги `/tour/{country}/` и статьи ссылаются назад на хаб + соседние spoke.
- **Breadcrumbs:** видимый trail **не выводить**; только JSON-LD `BreadcrumbList` на inner pages и `single.php` (см. §8).
- **Blog:** маршрут `/blog/` обязателен; карточки главной — **планируемые production URL** статей Excalibur (BT-01…06), без текста «скоро» / «placeholder» на UI; до публикации постов teaser ведёт на slug из `11-blog-topics.md`, архив — на `/blog/`.
- **Legal:** в футере обязательны `/politika-konfidenczialnosti/` и `/politika-cookies/` (шаблоны в теме; P2 по индексации — см. indexing agent).

---

## 2. Primary menu (WP: `primary`)

Плоское меню, **5 пунктов** (без выпадающего списка стран в test build).

| # | Label (RU) | URL | `menu_order` | Notes |
|---|------------|-----|--------------|-------|
| 1 | Главная | `/` | 10 | Текущая страница на home |
| 2 | Направления | `/tour/` | 20 | Единый hub; не дублировать 5 стран в header |
| 3 | О нас | `/o-nas/` | 30 | E-E-A-T |
| 4 | Блог | `/blog/` | 40 | DEPTH entry |
| 5 | Контакты | `/kontakty/` | 50 | NAP + форма |

**Header chrome (не пункты меню):**

| Элемент | Target | Attribute / class |
|---------|--------|-------------------|
| Logo | `/` | header only (T13) |
| Телефон | `tel:+79018105018` | `phone_click` |
| Pill CTA | `#quiz-modal` | `data-quiz-open`, label «Подобрать тур» |

**Mobile:** тот же набор; sticky bar после scroll мимо `trust-highlights` → `data-quiz-open`.

---

## 3. Footer menu

Колоночная структура (можно 3–4 WP menus или один footer с секциями).

### 3.1 Направления (`footer-destinations`)

| Label | URL |
|-------|-----|
| Турция | `/tour/turciya/` |
| ОАЭ | `/tour/oae/` |
| Египет | `/tour/egipet/` |
| Таиланд | `/tour/tailand/` |
| Вьетнам | `/tour/vietnam/` |
| Все направления | `/tour/` |

### 3.2 Компания (`footer-company`)

| Label | URL |
|-------|-----|
| О турагентстве | `/o-nas/` |
| Контакты и офис | `/kontakty/` |
| Блог | `/blog/` |
| ВКонтакте | `https://vk.com/zhiraf_ufa` | external, `rel="noopener"` |

### 3.3 Правовое (`footer-legal`) — обязательно

| Label | URL |
|-------|-----|
| Политика конфиденциальности | `/politika-konfidenczialnosti/` |
| Политика cookies | `/politika-cookies/` |

### 3.4 NAP block (не меню, дублирует schema)

- ООО «Жираф», ул. Гагарина, 74/1, Уфа  
- `tel:+79018105018`, `office@zhiraf-ufa.ru`  
- Часы: Пн–Пт 10–19, Сб 11–15  
- РТА 0012822  

### 3.5 Footer CTA

| Label | Target |
|-------|--------|
| Подобрать тур | `#quiz-modal` (`data-quiz-open`) |

---

## 4. CTA map

| Зона | Label | Target | Analytics (ref funnel) |
|------|-------|--------|-------------------------|
| Header pill | Подобрать тур | `#quiz-modal` | `cta_header_quiz_open` |
| Hero primary | Подобрать тур | `#quiz-modal` | `cta_hero_quiz_open` |
| Audience tabs | Подобрать тур | `#quiz-modal` | per-tab micro |
| Dest cards (home + tour) | (card) | `/tour/{slug}/` | `dest_card_click` |
| Tour hub / dest landings | Подобрать тур | `#quiz-modal` | quiz + optional country prefill |
| Lead strip (home) | Подобрать тур / Отправить | form AJAX | `lead_form_*` |
| Sticky mobile | Подобрать тур | `#quiz-modal` | `cta_sticky_quiz_open` |
| Header / footer tel | +7 901 810-50-18 | `tel:+79018105018` | `phone_click` |
| Blog teaser (home) | Читать: как купить тур в Уфе | `/blog/kak-kupit-tur-v-ufe/` | `blog_teaser_click` |
| Blog archive | (post title) | `/blog/{slug}/` | `blog_card_click` |
| Article end | Оставить заявку на подбор | `#quiz-modal` | Excalibur CTA block |
| Subscribe (home) | Подписаться | hold if newsletter unconfirmed | secondary only |

**Правило:** один primary pill above the fold на главной; телефон в шапке — не второй primary pill в hero.

---

## 5. Homepage — blog links (`editorial-blog-teaser`)

Минимум **3**, рекомендовано **6** тем из `11-blog-topics.md`. Featured + компактные карточки / ссылки в блоке DEPTH (после `lead-action`).

| Priority | topic_id | Title (UI) | URL | Anchor (пример) |
|----------|----------|------------|-----|-----------------|
| Featured | BT-01 | Как купить тур в Уфе | `/blog/kak-kupit-tur-v-ufe/` | пошаговая покупка тура в офисе на Гагарина |
| 2 | BT-02 | Что входит в «всё включено» | `/blog/chto-vhodit-v-tur-vse-vklyucheno/` | состав пакета AI и UAI из Уфы |
| 3 | BT-06 | Турция или Египет из Уфы | `/blog/turciya-ili-egipet-iz-ufy/` | сравнение Турции и Египета с вылетом из Уфы |
| 4 | BT-03 | Документы для заграницы | `/blog/dokumenty-dlya-zagranicy-iz-ufy/` | чек-лист документов перед вылетом |
| 5 | BT-04 | Туры с детьми | `/blog/tury-s-detmi-iz-ufy/` | отдых с детьми: отель и перелёт |
| 6 | BT-05 | Страховка и рассрочка | `/blog/strahovka-i-rassrochka-na-tur/` | страховка и оплата тура частями |

**Archive link:** «Все статьи» → `/blog/`.

**FAQ home (6–8):** в ответах — contextual links на BT-01, BT-03, `/kontakty/`, `/tour/` (см. content pack).

---

## 6. Breadcrumbs policy

| Правило | Значение |
|---------|----------|
| Visible trail under header | **Запрещён** |
| JSON-LD `BreadcrumbList` | Разрешён на `/tour/*`, `/o-nas/`, `/kontakty/`, `/blog/`, `single`, legal |
| Implementation | `inc/breadcrumbs.php` — emit JSON-LD only |

**Примеры JSON-LD иерархии:**

| Page | Breadcrumb chain |
|------|------------------|
| `/tour/turciya/` | Главная → Направления → Турция |
| `/blog/kak-kupit-tur-v-ufe/` | Главная → Блог → {post title} |
| `/politika-cookies/` | Главная → Политика cookies |

---

## 7. Internal linking matrix (3–8 ссылок на страницу)

Легенда: **in-body** = текст/FAQ/проза; **component** = карточка/меню/CTA-зона (входит в перелинковку, но anchors в body — descriptive).

### 7.1 `/` (front-page)

| # | Target | Anchor (RU) | Source block |
|---|--------|-------------|--------------|
| 1 | `/tour/` | каталог направлений с вылетом из Уфы | destinations CTA / prose |
| 2 | `/tour/turciya/` | туры в Турцию из Уфы | dest card |
| 3 | `/tour/oae/` | отдых в ОАЭ из Уфы | dest card |
| 4 | `/tour/egipet/` | туры в Египет из Уфы | dest card |
| 5 | `/tour/tailand/` | Таиланд с вылетом из Уфы | dest card |
| 6 | `/tour/vietnam/` | туры во Вьетнам из Уфы | dest card |
| 7 | `/kontakty/` | офис турагентства на ул. Гагарина | trust / FAQ |
| 8 | `/blog/kak-kupit-tur-v-ufe/` | как купить тур в Уфе пошагово | editorial teaser + FAQ |

**Incoming (orphan guard):** все P0/P1 страницы и footer ссылаются на `/`.

---

### 7.2 `/tour/` (catalog hub)

| # | Target | Anchor | Source |
|---|--------|--------|--------|
| 1 | `/tour/turciya/` | подбор тура в Турцию | dest grid |
| 2 | `/tour/oae/` | туры в ОАЭ и Дубай | dest grid |
| 3 | `/tour/egipet/` | отдых в Египете на море | dest grid |
| 4 | `/tour/tailand/` | Таиланд: маршрут и отель | dest grid |
| 5 | `/tour/vietnam/` | Вьетнам и Нячанг из Уфы | dest grid |
| 6 | `/` | туры с вылетом из Уфы на главной | prose «как подбираем» |
| 7 | `/blog/turciya-ili-egipet-iz-ufy/` | сравнение Турции и Египета | in-body |
| 8 | `#quiz-modal` | подобрать тур за 2 минуты | quiz-cta (не generic «здесь») |

**Incoming:** primary «Направления», home dest cards, all dest landings, footer «Все направления», articles BT-02/05/06.

---

### 7.3 `/tour/turciya/`

| # | Target | Anchor | Source |
|---|--------|--------|--------|
| 1 | `/tour/` | все направления отдыха из Уфы | breadcrumb JSON-LD + in-body |
| 2 | `/` | подбор тура на главной | prose CTA |
| 3 | `/blog/turciya-ili-egipet-iz-ufy/` | Турция или Египет — что выбрать | FAQ / prose |
| 4 | `/blog/tury-s-detmi-iz-ufy/` | туры с детьми из Уфы | «для кого» family |
| 5 | `/blog/chto-vhodit-v-tur-vse-vklyucheno/` | что входит в «всё включено» | питание AI/UAI |
| 6 | `/kontakty/` | консультация в офисе Уфы | CTA strip |
| 7 | `/tour/egipet/` | альтернатива — Египет из Уфы | spoke cross-link |

**Incoming:** home card, `/tour/` grid, footer, BT-02/04/06.

---

### 7.4 `/tour/oae/`

| # | Target | Anchor | Source |
|---|--------|--------|--------|
| 1 | `/tour/` | каталог направлений | hub back |
| 2 | `/` | индивидуальный подбор тура | prose |
| 3 | `/blog/chto-vhodit-v-tur-vse-vklyucheno/` | пакет «всё включено» в ОАЭ | season / hotel block |
| 4 | `/blog/kak-kupit-tur-v-ufe/` | оформление тура в Уфе | FAQ |
| 5 | `/kontakty/` | телефон и адрес офиса | CTA |
| 6 | `/tour/turciya/` | пляжный отдых в Турции | comparison spoke |

**Incoming:** home, hub, footer, BT-02.

---

### 7.5 `/tour/egipet/`

| # | Target | Anchor | Source |
|---|--------|--------|--------|
| 1 | `/tour/` | направления с вылетом из Уфы | hub |
| 2 | `/blog/turciya-ili-egipet-iz-ufy/` | сравнение с Турцией | prose |
| 3 | `/blog/tury-s-detmi-iz-ufy/` | Египет с детьми | family |
| 4 | `/` | заявка на подбор тура | CTA |
| 5 | `/kontakty/` | офис «Жираф» в Уфе | NAP link |
| 6 | `/tour/turciya/` | туры в Турцию из Уфы | cross-spoke |

**Incoming:** home, hub, footer, BT-04/06.

---

### 7.6 `/tour/tailand/`

| # | Target | Anchor | Source |
|---|--------|--------|--------|
| 1 | `/tour/` | вернуться к каталогу | hub |
| 2 | `/blog/chto-vhodit-v-tur-vse-vklyucheno/` | длинный перелёт и питание в пакете | honesty block |
| 3 | `/blog/dokumenty-dlya-zagranicy-iz-ufy/` | документы перед дальним вылетом | FAQ |
| 4 | `/kontakty/` | обсудить маршрут в офисе | CTA |
| 5 | `/` | квиз подбора тура | CTA |
| 6 | `/tour/vietnam/` | Вьетнам как альтернатива в Азии | spoke |

**Incoming:** home, hub, footer, BT-02/03.

---

### 7.7 `/tour/vietnam/`

| # | Target | Anchor | Source |
|---|--------|--------|--------|
| 1 | `/tour/` | все страны каталога | hub |
| 2 | `/blog/chto-vhodit-v-tur-vse-vklyucheno/` | состав тура во Вьетнам | prose |
| 3 | `/blog/kak-kupit-tur-v-ufe/` | покупка тура через агентство | FAQ |
| 4 | `/kontakty/` | запись на консультацию | CTA |
| 5 | `/` | подбор на главной | CTA |
| 6 | `/tour/tailand/` | Таиланд из Уфы | cross-spoke |

**Incoming:** home, hub, footer, BT-01/02.

---

### 7.8 `/o-nas/`

| # | Target | Anchor | Source |
|---|--------|--------|--------|
| 1 | `/kontakty/` | контакты и карта офиса | team / CTA |
| 2 | `/` | подобрать тур с вылетом из Уфы | cta-strip |
| 3 | `/tour/` | направления, с которыми работаем | prose |
| 4 | `/blog/kak-kupit-tur-v-ufe/` | как проходит покупка в «Жираф» | trust |
| 5 | `/tour/turciya/` | туры в Турцию | destinations mention |

**Incoming:** primary menu, footer, `/kontakty/`, BT-01.

---

### 7.9 `/kontakty/`

| # | Target | Anchor | Source |
|---|--------|--------|--------|
| 1 | `/blog/kak-kupit-tur-v-ufe/` | как купить тур в офисе на Гагарина | answer block |
| 2 | `/` | заявка через квиз на сайте | prose |
| 3 | `/tour/` | выбрать страну отдыха | prose |
| 4 | `/o-nas/` | о команде турагентства | sidebar / prose |
| 5 | `/blog/dokumenty-dlya-zagranicy-iz-ufy/` | документы перед поездкой | FAQ |

**Incoming:** primary menu, footer, all landings, BT-01/03, home FAQ.

---

### 7.10 `/blog/` (archive)

| # | Target | Anchor | Source |
|---|--------|--------|--------|
| 1 | `/blog/kak-kupit-tur-v-ufe/` | (title поста) | card |
| 2 | `/blog/chto-vhodit-v-tur-vse-vklyucheno/` | (title) | card |
| 3 | `/blog/turciya-ili-egipet-iz-ufy/` | (title) | card |
| 4 | `/blog/dokumenty-dlya-zagranicy-iz-ufy/` | (title) | card |
| 5 | `/blog/tury-s-detmi-iz-ufy/` | (title) | card |
| 6 | `/blog/strahovka-i-rassrochka-na-tur/` | (title) | card |
| 7 | `/tour/` | подбор направления после чтения | intro prose |
| 8 | `/` | вернуться на главную | archive hero |

**Empty state (до publish):** CTA квиз + ссылка на `/tour/`; **не** показывать fake post cards.

**Incoming:** primary menu, footer, home teaser, all singles.

---

### 7.11 Blog singles (`/blog/{slug}/`) — template `single.php`

Общее правило: **2–3 обязательных** commercial links + hub links.

| slug | Outbound (descriptive) |
|------|------------------------|
| `kak-kupit-tur-v-ufe` | `/`, `/kontakty/`, `/tour/`, `/blog/dokumenty-dlya-zagranicy-iz-ufy/` |
| `chto-vhodit-v-tur-vse-vklyucheno` | `/tour/turciya/`, `/tour/egipet/`, `/blog/tury-s-detmi-iz-ufy/` |
| `dokumenty-dlya-zagranicy-iz-ufy` | `/blog/kak-kupit-tur-v-ufe/`, `/kontakty/` |
| `tury-s-detmi-iz-ufy` | `/tour/turciya/`, `/tour/egipet/`, `/blog/chto-vhodit-v-tur-vse-vklyucheno/` |
| `strahovka-i-rassrochka-na-tur` | `/blog/kak-kupit-tur-v-ufe/`, `/tour/` |
| `turciya-ili-egipet-iz-ufy` | `/tour/turciya/`, `/tour/egipet/`, `/` |

**Incoming:** home teasers, `/blog/` grid, contextual links from landings and sibling posts.

---

### 7.12 Legal (P2 index; обязательны в footer)

| Page | Min inbound | Outbound |
|------|-------------|----------|
| `/politika-konfidenczialnosti/` | footer, forms consent | `/politika-cookies/` |
| `/politika-cookies/` | footer, cookie banner | `/politika-konfidenczialnosti/` |

---

## 8. Orphan check (входящие ссылки)

| URL | Status in build | ≥1 incoming from |
|-----|-----------------|------------------|
| `/` | test P0 | menu, все landings, articles |
| `/tour/` | test P0 | menu, home, footer, blog intro |
| `/tour/turciya/` | backlog content, **paths in theme** | home, hub, footer, BT-02/04/06, cross-spokes |
| `/tour/oae/` | same | home, hub, footer, BT-02 |
| `/tour/egipet/` | same | home, hub, footer, BT-04/06 |
| `/tour/tailand/` | same | home, hub, footer, BT-02/03 |
| `/tour/vietnam/` | same | home, hub, footer, BT-01/02 |
| `/o-nas/` | test P1 | menu, footer, kontakty |
| `/kontakty/` | test P1 | menu, footer, landings, BT-01/03 |
| `/blog/` | test P1 | menu, footer, home «Все статьи» |
| `/blog/*` (6) | Excalibur planned | home, archive, landings, siblings |
| `/politika-konfidenczialnosti/` | template ready | footer, forms |
| `/politika-cookies/` | template ready | footer, banner |

**Результат:** ✅ нет orphan среди Phase 1 URL из `site.inv` и `06-url-map.csv`.

---

## 9. WP implementation checklist (Aurora)

- [ ] Register `primary` menu (5 items §2).
- [ ] Register footer menus / markup per §3.
- [ ] `wp_nav_menu` locations: `primary`, `footer-destinations`, `footer-company`, `footer-legal` (или эквивалент в `site-spec.json`).
- [ ] Все CTA «Подобрать тур» → `data-quiz-open` + `href="#quiz-modal"` для a11y fallback.
- [ ] Home dest cards: `href` = `/tour/{turciya|oae|egipet|tailand|vietnam}/`.
- [ ] Blog teaser URLs = §5 table (не lorem).
- [ ] Breadcrumbs: JSON-LD only §6.
- [ ] Consent links in forms → `/politika-konfidenczialnosti/`.

---

## 10. Blockers

| ID | Severity | Note |
|----|----------|------|
| N1 | low | Destination pages вне 5-page test count — перелинковка заложена; контент/шаблоны — Aurora backlog |
| N2 | low | Blog cards на архиве — только после Excalibur publish; до того empty state §7.10 |
| N3 | info | Legal pages P2 indexing — navigation links обязательны независимо |

**Navigation map status:** ✅ READY
