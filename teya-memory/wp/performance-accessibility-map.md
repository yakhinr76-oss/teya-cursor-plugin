# Performance & Accessibility Map — «Жираф», Уфа (`zhiraf-ufa`)

## Meta

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (staging: b2b-tour.ru, noindex) |
| theme_slug | `zhiraf-ufa` |
| mode | CONVERSION-FIRST (`teya-memory/wp/conversion-funnel-map.md`) |
| blueprint_ref | `teya-memory/wp/aurora-team-blueprint.md` |
| design_ref | `teya-memory/design/AURADESIGN.md`, `AURA_PAGE_PLAN.md`, `AURA_VISUAL_INVENTORY.json`, `AURA_ASSET_REGISTRY.json` |
| research_ref | `teya-memory/research/site-research-dossier.md`, `audience-map.md` |
| theme_path | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` |
| playbook | `shared/wp-theme-builder-playbook.md` |
| author | aurora-team-performance-a11y |
| date | 2026-10-18 |
| status | ✅ READY (контракт для Aurora / wp-theme-builder) |

---

## 1. Core Web Vitals — цели и измерение

| Метрика | Порог «хорошо» | Приоритет страниц test build |
|---------|----------------|------------------------------|
| **LCP** | < 2.5 s (p75 mobile) | `/` → `/tour/` → `/kontakty/` |
| **INP** | < 200 ms | Квиз, мобильное меню, табы направлений |
| **CLS** | < 0.1 | Hero-коллаж, карточки направлений, шрифты, sticky bar |

**Инструменты:** Lighthouse (mobile), PageSpeed Insights, Chrome UX Report после выкладки; WebPageTest с профилем 4G / Moto G.

**Бюджет JS (ориентир главной):** `main.js` defer; GSAP + `motion-home.js` только `is_front_page()` — целевой суммарный transfer JS motion-пакета ≤ 120 KB gzip; при превышении — отключить ScrollTrigger на below-fold или упростить до CSS.

**Связь с аудиторией:** сегменты «осторожные» и 35–55 (`audience-map.md`) чаще на mid-tier Android и слабом LTE — mobile p75 важнее desktop; квиз и `tel:` должны отзываться без «залипания» (INP).

---

## 2. LCP — план

### 2.1 Элемент LCP по шаблонам

| URL / template | LCP-кандидат | asset_id / файл | Примечание |
|----------------|--------------|-----------------|------------|
| `/` `front-page.php` | Hero collage tile #1 (DOM) | `hero-uae.jpg` (`hero-uae`) | Первый `<img>` с `priority=true` в `zhiraf_ufa_img()` |
| `/` (fallback) | H1 + системный шрифт | — | Если изображения блокируются — не допускать пустого hero |
| `/tour/` | `catalog-hero` | `catalog-hub.jpg` | Eager, `fetchpriority="high"` |
| `/o-nas/` | Editorial hero / team | `team-travel.jpg` или `office-ufa.jpg` | Одно eager above-fold |
| `/kontakty/` | Текст + форма (без крупного фото) | — | LCP = текст; следить за шрифтами |
| `/blog/` `home.php` | Первая карточка / H1 | `article-main.jpg` или thumbnail поста | Lazy только для 2+ карточек |
| `single.php` | Featured image поста | per-post | `loading="eager"` на hero изображении статьи |

**Главная — правило:** ровно **один** hero-tile с `fetchpriority="high"` + `loading="eager"` (сейчас: `hero-uae.jpg`). Остальные два коллажа (`hero-turkey`, `hero-egypt`) — eager без high priority или eager с явным width/height (из `getimagesize`).

### 2.2 CSS / шрифт и LCP

1. **Не** подключать Google Fonts на production — только локальные woff2 (`inc/enqueues.php` → `zhiraf_ufa_font_faces`).
2. **Preload** (добавить в `wp_head`, priority 1):  
   `Manrope-Cyrillic-Variable.woff2` — единственный preload шрифта (кириллический H1).
3. Критический путь: один блокирующий CSS `assets/dist/style.css` с `filemtime` версией; inline `@font-face` после enqueue style (как сейчас).
4. Hero: зарезервировать min-height коллажа в CSS до загрузки img (aspect-ratio по `media-map.json`) — снижение CLS.

### 2.3 Запрещено для LCP

- `loading="lazy"` на LCP-изображении.
- Remote MCP / temp URL в `src` на production.
- Скрывать LCP за анимацией `opacity:0` без `prefers-reduced-motion` fallback (контент виден сразу при reduce).

---

## 3. INP — интерактив

| Зона | Риск | Контракт |
|------|------|----------|
| `#quiz-modal` | focus trap, много полей | Открытие < 100 ms; focus на H2/первое поле; Esc закрывает; `aria-modal="true"` (есть в `quiz-modal.php`) |
| `data-quiz-open` | дубли CTA | Один delegated listener, без sync layout thrashing |
| `.nav-toggle` | меню | `aria-expanded` toggle; закрытие по Esc |
| Destination tabs (главная) | ложные `<button>` без panel | Либо декоративные `aria-hidden` + один grid, либо полноценный tablist (роли + keyboard) |
| Sticky mobile CTA | fixed + scroll | `transform` для появления, не `top` анимация layout |
| Формы AJAX | блок main thread | Ответ UI через `role="status"` + `aria-live="polite"` (частично есть) |

**jQuery:** не подключать. **GSAP:** только главная; при `prefers-reduced-motion: reduce` скрипт не инициализируется (`motion-home.js` — уже early return).

---

## 4. CLS — стабильность

| Источник | Мера |
|----------|------|
| Hero collage | `width`/`height` на всех img (`zhiraf_ufa_theme_image`) |
| Destination cards | Фиксированное соотношение карточки в CSS; img `object-fit: cover` |
| Web fonts | `font-display: swap`; preload кириллицы; убрать ссылку на **Unbounded** без локального файла — единый **Manrope** (`AURA_FONT_MATCH.md`) |
| Cookie banner | `hidden` до показа; не сдвигать layout (overlay/fixed) |
| Яндекс-карта iframe | `loading="lazy"`, фиксированная высота 400px (`page-kontakty.php`) |
| Sticky bar | Зарезервировать safe-area; не вставлять в поток документа |

---

## 5. Политика изображений

### 5.1 Общие правила

| Правило | Реализация |
|---------|------------|
| Форматы | Production: **JPEG/WebP**; при сборке dist — дубли `.webp` + `<picture>` или content negotiation на хостинге; **AVIF** — опционально P2 |
| Размеры | Исходники ≤ 1200 px по длинной стороне для full-width; карточки 800×1200 вертикаль |
| Атрибуты | Всегда `width`, `height`, осмысленный `alt` (из `AURA_ASSET_REGISTRY.json`) |
| Декоративное | `alt=""` + `aria-hidden` для чисто декоративных SVG (flight path — ок) |
| Below-fold | `loading="lazy"` через `zhiraf_ufa_img( ..., lazy=true )` |
| LCP | `priority=true` → eager + `fetchpriority="high"` |
| Srcset | P1: добавить `srcset` для hero и dest cards (400w / 800w / 1200w) из одного master или `-new` variants |
| OG / schema | Не смешивать с LCP; отдельные размеры в schema-map |

### 5.2 Self-host / cache (`AURA_ASSET_REGISTRY.json`)

- Все `source: existing_theme_asset` — **только** `theme_slug/assets/images/` или пути из registry; **нулевые** production-зависимости от MCP KV.
- `pending_mcp_generation` (blog-cover-series, trust optional) — **blocker для Phase 1 close** только если на фронте остаётся broken `src`; до OAuth — fallback `blog-cover-01.jpg` / `article-main.jpg`.
- Cache-Control на статику темы: `public, max-age=31536000, immutable` при версионировании через `filemtime` в query (CSS/JS уже так).

---

## 6. Visual zones — loading & dimensions

Источник зон: `AURA_VISUAL_INVENTORY.json`. Размеры verified: `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/assets/media-map.json`.

### 6.1 Главная `/`

| zone_id | section | LCP? | Файл / id | WxH (verified) | loading | alt policy |
|---------|---------|------|-----------|----------------|---------|------------|
| hero-collage-1 | hero | **да** | hero-uae.jpg | 1200×800 | eager, high | «ОАЭ — пляж и небоскрёбы» |
| hero-collage-2 | hero | нет | hero-turkey.jpg | 1200×800 | eager | «Турция — курорт» |
| hero-collage-3 | hero | нет | hero-egypt.jpg | 800×533 | eager | «Египет — море» |
| dest-card-* ×5 | destinations | нет | dest-{country}.jpg | 800×1200 / 1200×800 | lazy | Название страны + контекст отдыха |
| trust-visual | trust-highlights | нет | office-ufa.jpg | 1200×801 | lazy | «Офис на Гагарина, Уфа» |
| editorial-feature | editorial | нет | article-main.jpg | из registry | lazy | Заголовок статьи BT-01 |
| editorial-thumb-1..3 | editorial | нет | post thumb / fallback | 16:9 | lazy | Заголовок поста |
| subscribe-bg | subscribe | нет | subscribe-bg.jpg | — | lazy | Декоративный full-bleed; при чисто декоративном — `alt=""` |

### 6.2 `/tour/`

| zone | LCP | Файл | loading |
|------|-----|------|---------|
| catalog-hero | да | catalog-hub.jpg 1200×797 | eager, high |
| dest-grid | нет | dest-* | lazy |

### 6.3 `/o-nas/`

| zone | LCP | Файл | loading |
|------|-----|------|---------|
| team / office | да (один) | team-travel.jpg 1200×800, office-ufa.jpg | eager above-fold |

### 6.4 `/kontakty/`

| zone | LCP | loading |
|------|-----|---------|
| map iframe | нет | lazy |
| office photo (если есть) | опц. | lazy |

### 6.5 `/blog/` + single

| zone | loading | alt |
|------|---------|-----|
| Archive cards | lazy | Название статьи |
| Single featured | eager | H1 / title |
| In-content images Excalibur | lazy | Описательный RU alt |

---

## 7. Шрифты (локальные, кириллица)

| Параметр | Контракт |
|----------|----------|
| Семейство | **Manrope** variable только (`AURADESIGN.md`, `AURA_FONT_MATCH.md`) |
| Файлы | `assets/fonts/manrope/Manrope-Cyrillic-Variable.woff2` + `Manrope-Variable.woff2` (latin/punct) |
| `font-display` | `swap` на всех `@font-face` |
| Веса | 400 body, 600 UI/labels, 800 display — **не** грузить 200/300/700 отдельными файлами (variable axis) |
| Google CDN | **Запрещено** на production (убрать из `AURADESIGN font_import` в реализации, не дублировать link в header) |
| Preload | Только Cyrillic woff2; latin подтянется при необходимости |
| CSS tokens | `--font-sans` и `--font-head` → оба `'Manrope', system-ui, sans-serif` (**убрать Unbounded** из `main.scss`) |
| FOUT | Допустим краткий swap; H1 не должен прыгать > 0.1 CLS — задать `size-adjust` / min-height hero title P2 |

---

## 8. CSS / JS

| Политика | Детали |
|----------|--------|
| CSS | Один bundled `style.css`; критические hero/header стили уже в bundle; не добавлять второй render-blocking |
| JS | `main.js` — `defer`, in_footer; localize только nonce/ajax |
| GSAP | Только front page; не блокировать парсинг |
| Critical | Не инлайнить весь SCSS; при регрессии LCP — inline только `.hero-premium` + `.site-header` (< 4 KB) |
| WordPress | `wp_enqueue_*` с версиями filemtime; emoji/embeds отключить в `security-release-map` scope |
| Third-party | Яндекс карта — единственный тяжёлый iframe; Metrika — async/defer по conversion-map |

---

## 9. Анимации и `prefers-reduced-motion`

| Слой | Поведение |
|------|-----------|
| JS (GSAP) | `matchMedia('(prefers-reduced-motion: reduce)')` → **no-op** (реализовано) |
| CSS | Добавить глобально: `@media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; scroll-behavior: auto !important; } }` |
| Hover | Только `transform` / `opacity`; не анимировать `width`, `height`, `top` |
| Hero flight SVG | Статичный при reduce; без dash-offset анимации |
| `.reveal` | Без GSAP контент **видим** (не `opacity:0` в базовом CSS) |
| Carousel / tabs | Нет автопрокрутки без паузы и без кнопки stop |

Согласовать детали таймингов с `teya-memory/wp/animation-motion-map.md` (когда появится).

---

## 10. Accessibility (WCAG 2.2 AA — практический контракт)

### 10.1 Приоритеты из research

| Аудитория | A11y-акцент |
|-----------|-------------|
| Осторожные / 35–55 | Читаемость, крупные touch targets, явные labels форм, телефон как текст |
| Семья | Понятные ошибки форм, квиз без тайм-давления |
| Мобильные Уфа | 44–48 px targets, sticky CTA не перекрывает focus |

### 10.2 Обязательные паттерны

| Требование | Статус темы | Действие Aurora |
|------------|-------------|-----------------|
| Skip link → `#primary` | HTML есть | **Стили** `.skip-link:focus` visible (сейчас класс без CSS) |
| Landmarks | `header`, `nav`, `main#main`, `footer` | Сохранить; один `main` на страницу |
| Heading hierarchy | Частично | Один H1; секции H2; карточки H3 |
| Focus visible | `main.scss` :focus-visible | Расширить на `select`, `textarea`, `[tabindex]`; контраст outline ≥ 3:1 |
| Keyboard | Квиз, меню, cookie | Focus trap в modal; return focus на trigger; Esc закрывает |
| Forms | quiz + lead | Видимые `<label>` или `aria-label`; ошибки `aria-invalid` + связь с полем |
| Contrast | Primary #E87A5D on #FAF8F5 | Проверить мелкий текст muted #666 — на грани; body ≥ 4.5:1 |
| Touch | Кнопки | `.btn` min-height **44px**; `.btn--sm` min 44×44 tap area |
| Images | alt в registry | Не дублировать заголовок в alt карточки |
| Motion | reduce | CSS block выше |
| Cookie banner | `role="dialog"` | Focus management при открытии |
| Hover-only | Нет критичного контента только на hover | Карточки: текст всегда виден |

### 10.3 WordPress accessibility-ready

- Без плагинов, ломающих focus outline глобально.
- Меню — клавиатурная навигация; мобильное меню не убирает focus с экрана.
- Масштаб 200%: reflow без горизонтального скролла на 320px (проверка `lead-action`, квиз).
- Не полагаться только на цвет для ошибок форм (текст + icon).

---

## 11. Semantic HTML checklist

```text
body
├── a.skip-link → #primary
├── header.site-header
│   └── nav[aria-label]
├── main#primary.site-main
│   └── sections (hero, …)
├── footer
└── dialogs: #quiz-modal, #cookie-banner
```

- Логотип: `alt` «Турагентство Жираф» (`logo-client`).
- Телефон: `tel:+79018105018` + видимый текст.
- Breadcrumbs: только JSON-LD (без визуального trail) — не ломать heading order.

---

## 12. Gap / blockers (трекинг)

| ID | Тип | Описание | Владелец |
|----|-----|----------|----------|
| PERF-01 | Performance | Нет WebP/srcset в `zhiraf_ufa_img` | Aurora |
| PERF-02 | Performance | Нет preload Cyrillic woff2 в head | Aurora |
| PERF-03 | Performance | GSAP weight на главной — мониторить INP | Aurora + motion |
| PERF-04 | CLS | `--font-head: Unbounded` без локального файла | Aurora |
| A11Y-01 | A11y | `.skip-link` / `.screen-reader-text` без стилей в SCSS | Aurora |
| A11Y-02 | A11y | Destination tab buttons без tab semantics | Aurora |
| A11Y-03 | A11y | Нет глобального CSS `prefers-reduced-motion` | Aurora |
| CONT-01 | Content | `trust-social` секция не в `front-page.php` (inventory gap) | Aurora P0 blueprint |
| ASSET-01 | Assets | `editorial-thumb-1..3` — post thumbs / MCP series | asset-packager |

---

## 13. Приёмка (QA чеклист)

- [ ] Lighthouse Accessibility ≥ 90 на `/` mobile
- [ ] LCP element = hero-uae (или текст) в PSI
- [ ] CLS < 0.1 на `/` и `/tour/`
- [ ] Tab: header → main → footer → quiz open/close → return focus
- [ ] `prefers-reduced-motion`: нет scroll-driven fade, контент читаем сразу
- [ ] Network: нет fonts.googleapis.com / gstatic
- [ ] Все img из `AURA_ASSET_REGISTRY` отдают 200 с темы, не 404
- [ ] Touch targets ≥ 44px на primary CTA и nav

---

## 14. Связанные артефакты

| Артефакт | Связь |
|----------|-------|
| `animation-motion-map.md` | Тайминги vs reduce |
| `conversion-tracking-map.md` | Metrika без блокировки main thread |
| `asset-packaging-report.md` | decode_verified для raster |
| `schema-technical-seo-map.md` | ImageObject / logo |
