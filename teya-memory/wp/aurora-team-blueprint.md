# Aurora Team Blueprint — «Жираф», Уфа (test build)

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (staging: b2b-tour.ru) |
| theme_slug | `zhiraf-ufa` |
| mode | **CONVERSION-FIRST** |
| semantic_run | `teya-memory/semantic-core/zhiraf-ufa-2026/` |
| funnel_ref | `teya-memory/wp/conversion-funnel-map.md` ✅ READY |
| taste_ref | `teya-memory/design/AURA_TASTE_PROFILE.md` ✅ READY |
| design_ref | `teya-memory/design/AURA_PAGE_PLAN.md` + AURA pack |
| brief_ref | `teya-memory/site.inv`, `teya-memory/00-brief.md` |
| date | 2026-10-18 |
| author | aurora-team-lead |
| status | ✅ READY для parallel `aurora-team-*` |

---

## 1. Выбор страниц test build (max 5)

| # | URL | slug | template | priority | Зачем в тесте |
|---|-----|------|----------|----------|----------------|
| 1 | `/` | — | `front-page.php` | P0 | North Star: квиз + каталог направлений; CL-HOME «туры из Уфы» |
| 2 | `/tour/` | `tour` | `page-tour.php` | P0 | CL-CATALOG hub; единственный вход в 5 стран P0 (лендинги — backlog) |
| 3 | `/o-nas/` | `o-nas` | `page-o-nas.php` | P1 | CL-ABOUT / E-E-A-T; PROOF для сегмента `experienced` |
| 4 | `/kontakty/` | `kontakty` | `page-kontakty.php` | P1 | CL-CONTACT NAP + форма; local entity |
| 5 | `/blog/` | `blog` (posts page) | `home.php` | P1 | DEPTH + Excalibur archive; SEO support |

**Связь с `AURA_PAGE_PLAN.md`:** совпадает 1:1 с test limit (главная + tour + o-nas + kontakty + blog).

**Связь с Ядрышком (`06-url-map.csv`, `07-content-briefs.md`):** P0 URL покрыты; destination landings `/tour/{country}/` и `single.php` — **вне счётчика 5**, но обязательны в theme map для Phase 1 контента/Excalibur.

**Backlog (не в test page count):** `/tour/turciya|oae|egipet|tailand|vietnam/`, `single.php`, politika-*.

---

## 2. Sitemap и hierarchy

```text
/                          [front-page.php]     P0 conversion landing
├── /tour/                 [page-tour.php]      P0 catalog hub
│   ├── /tour/turciya/     [page.php + template backlog]  P1
│   ├── /tour/oae/
│   ├── /tour/egipet/
│   ├── /tour/tailand/
│   └── /tour/vietnam/
├── /o-nas/                [page-o-nas.php]     P1 trust
├── /kontakty/             [page-kontakty.php]  P1 NAP + lead
└── /blog/                 [home.php]           P1 archive
    └── /blog/{slug}/      [single.php]         P1 Excalibur ×6
```

**Иерархия меню:** плоская верхняя навигация (без выпадающего «Туры» на test build — hub `/tour/` одной ссылкой).

---

## 3. Page template map

| WP route | PHP template | Fallback | Notes |
|----------|--------------|----------|-------|
| Front page | `front-page.php` | — | CONVERSION-FIRST section order ниже |
| Page `tour` | `page-tour.php` | `page.php` | Catalog hero + grid |
| Page `o-nas` | `page-o-nas.php` | `page.php` | Team + office visuals |
| Page `kontakty` | `page-kontakty.php` | `page.php` | Form + map + requisites |
| Posts page `/blog/` | `home.php` | — | `show_on_front` + `page_for_posts` |
| Single post | `single.php` | — | Excalibur HTML import; Article schema |
| Generic page | `page.php` | — | Destination landings when added |
| Search | `search.php` | — | Optional P2 |
| 404 | theme default | — | noindex |

**Запрет:** не заменять test templates на голый `page.php` без визуального бюджета (`AURA_VISUAL_BUDGET.json`).

---

## 4. Главная — порядок секций (CONVERSION-FIRST wins)

Источник: `conversion-funnel-map.md` § Homepage. **Не вставлять DEPTH между PAIN и ACTION.**

| # | section_id | funnel | blocks / components | CTA |
|---|------------|--------|---------------------|-----|
| 1 | `hero-premium` | AWARE+PAIN | H1 semantic, ≤120 слов до CTA, collage×3 + flight SVG | Primary pill → `#quiz-modal` |
| 2 | `audience-tabs` | PAIN→SOLUTION | 4–5 tabs: family, couple, cautious, experienced (+ flexible опц.) | Micro-CTA «Подобрать тур» per tab |
| 3 | `destinations` | SOLUTION | Tabs + 5× `dest-premium-card` → `/tour/{slug}/` | Card click = `dest_card_click` |
| 4 | `how-it-works` | SOLUTION+PROOF | 4 numbered steps 01–04 | Optional text-link CTA |
| 5 | `trust-highlights` | PROOF | `trust-social`: РТА, с 2011, офис, VK, tel; office/team photo | Links only |
| 6 | `faq-home` | OBJECTION | 6–8 FAQ + links to blog BT | — |
| 7 | `lead-action` | ACTION | Short form name+phone **or** repeat primary; pairs with `form-premium` | Primary |
| 8 | `editorial-blog-teaser` | DEPTH | **1** feature card BT-01 + «Все статьи» → `/blog/` | `blog_teaser_click` |
| 9 | `subscribe-premium` | DEPTH | Full-bleed; **hold** CTA if newsletter unconfirmed | Secondary only |

**Global:** `quiz-modal` (4 steps), sticky mobile bar after scroll past `trust-highlights`.

**Cut list (главная):** цены «от», fake reviews, второй primary в hero, editorial 1+3 mid-funnel, SLA «15 минут», legacy stats 782/16.

**H1 (semantic):** «Туры с вылетом из Уфы — подбор без давления» (`07-content-briefs.md`).

**Объём текста главной:** 3000–4500 знаков плотного + FAQ (не раздувать до 8k).

---

## 5. Блоки по внутренним страницам

### `/tour/` (`page-tour.php`)

| # | section_id | Содержание |
|---|------------|------------|
| 1 | `catalog-hero` | H1 «Направления отдыха с вылетом из Уфы» + `catalog-hub.jpg` |
| 2 | `dest-grid` | Chips + 5 vertical cards (язык как на главной) |
| 3 | `how-pick` | «Как подбираем» без прайс-борда |
| 4 | `quiz-cta` | Primary «Подобрать тур» |

**Объём:** 2500–4000 знаков. **Links:** все `/tour/*`, BT-06.

### `/o-nas/`

| # | section_id | Содержание |
|---|------------|------------|
| 1 | `page-hero` | Editorial hero, sand/milky |
| 2 | `prose-trust` | С 2011, founders, values — только fact-bank |
| 3 | `team-grid` | Имена из fact-bank; `team-travel.jpg` |
| 4 | `office-visual` | `office-ufa.jpg` |
| 5 | `cta-strip` | Квиз |

**Объём:** 3000–5000 знаков.

### `/kontakty/`

| # | section_id | Содержание |
|---|------------|------------|
| 1 | `contact-hero` | H1 + NAP summary |
| 2 | `contact-form` | name, phone, consent 152 (+38 opt.) |
| 3 | `map-embed` | Яндекс/Google map (staging OK placeholder policy) |
| 4 | `requisites` | ИНН, ОГРН, банк — fact-bank |
| 5 | `answer-block` | GEO: «Как купить в офисе на Гагарина» → BT-01 |

**Объём:** 2000–3500 знаков.

### `/blog/` (`home.php`)

| # | section_id | Содержание |
|---|------------|------------|
| 1 | `archive-hero` | H1 «Блог турагентства «Жираф»» |
| 2 | `post-grid` | Cards 16:9 covers; **только реальные опубликованные посты** |

**Правило:** без fake posts, lorem, «скоро». До Excalibur publish — пустое состояние `content-none` + CTA квиз, не заглушки-карточки.

### `single.php` (вне 5-page count, обязателен в теме)

- Excalibur body as-is; CTA в конце; 2–3 internal links на `/tour/*`, `/`, `/kontakty/`.
- Article schema; FAQPage если видимый FAQ в статье.
- **Объём:** 8000–15000 знаков по intent (`11-blog-topics.md`).

---

## 6. Blog section (обязательно)

| Элемент | Требование |
|---------|------------|
| Route | `/blog/` → `home.php`, `page_for_posts` |
| Главная | `editorial-blog-teaser`: **BT-01** `kak-kupit-tur-v-ufe` + link «Все статьи» |
| Темы для teaser/grid (3–6 из semantic) | BT-01, BT-02, BT-06, BT-03, BT-04, BT-05 |
| Меню/футер | Ссылка «Блог» в main **или** footer (рекомендация: main nav) |
| Templates | `home.php`, `single.php`; опционально `page-blog.php` не нужен при posts page |
| Excalibur | Publish из `deliverables/zhiraf-ufa/excalibur-articles/` — только BLOG INTEGRATOR |

**Homepage blog block topics (teaser + будущая сетка):**

1. BT-01 — как купить тур в Уфе (featured on home)
2. BT-02 — всё включено
3. BT-06 — Турция или Египет
4. BT-03 — документы
5. BT-04 — с детьми
6. BT-05 — страховка и рассрочка

---

## 7. Navigation

### Main menu (primary)

| Label | URL | Notes |
|-------|-----|-------|
| Главная | `/` | — |
| Направления | `/tour/` | Не дублировать 5 отдельных пунктов в test |
| О нас | `/o-nas/` | — |
| Блог | `/blog/` | DEPTH entry |
| Контакты | `/kontakty/` | — |

**Header chrome:** logo, `tel:+79018105018`, pill «Подобрать тур» `data-quiz-open`, sticky 80px blur (`AURA_SECTION_BLUEPRINTS` global:header).

### Footer menu (secondary)

| Column | Links |
|--------|-------|
| Направления | `/tour/turciya/`, `/tour/oae/`, `/tour/egipet/`, `/tour/tailand/`, `/tour/vietnam/` |
| Компания | `/o-nas/`, `/kontakty/`, `/blog/` |
| Правовое (P2 backlog) | politika-* когда готовы |

**NAP block (footer):** адрес Гагарина 74/1, часы, телефон, email, VK.

---

## 8. CTA strategy

| Уровень | Label | Target | Events |
|---------|-------|--------|--------|
| Primary | Подобрать тур | `#quiz-modal` | `cta_hero_quiz_open`, `cta_header_quiz_open`, `cta_sticky_quiz_open`, `quiz_submit` |
| Secondary | Позвонить | `tel:+79018105018` | `phone_click` |
| Lead form | Отправить / Подобрать тур | AJAX lead | `lead_form_start`, `lead_form_submit` |
| Blog | Читать статью | post URL | `blog_teaser_click` |

**North Star:** `quiz_submit` + `phone_click`. Один primary pill above fold; телефон в шапке — не второй primary pill в hero.

Формы: см. `conversion-funnel-map.md` § Form spec (квиз 4 шага, honeypot, consent 152/38).

---

## 9. `taste_profile_compliance`

Обязательно: `teya-memory/design/AURA_TASTE_PROFILE.md`.

| Mandate | Blueprint binding |
|---------|-------------------|
| T01 | Нет generic 3-icon row; steps = 4 numbered cards |
| T04/T05 | No `transition: all`; no ease-in on modals |
| T07/T08 | Primary contrast AA; touch ≥44px |
| T09 | Manrope-only; strip Unbounded in SCSS (Aurora) |
| T13 | Hero collage ≥3 images + flight SVG |
| T14 | No fake reviews/prices «от»/lorem |
| P01/P12 | ≥5 layout families homepage; no 3× white text-only streak |
| P04 | Homepage ≥12 meaningful images (`AURA_VISUAL_BUDGET`) |
| P07 | Quiz/tabbed dest = custom offer UI |
| P10 | Inner pages distinct families vs homepage paste |

**Conflict resolver:** funnel-map wins **order**; taste wins **form** (no slop simplification).

---

## 10. Visual budget per page

Источник: `AURA_VISUAL_BUDGET.json`, `AURA_VISUAL_INVENTORY.json`.

| Page | min colored sections | min meaningful images | motifs | overlaps | custom cards | non-rect transitions |
|------|---------------------|----------------------|--------|----------|--------------|---------------------|
| `/` | 3 | **12** (inventory 14 instances) | milky/sand/terracotta, flight SVG, dest scrims, subscribe bleed | 2 (collage internal + subscribe overlay) | 10+ (5 dest + 4 steps + form card) | 1+ |
| `/tour/` | 2 | 6 | dest vertical cards, hub hero | 0 | 5 | 0 |
| `/o-nas/` | 2 | 2 | sand section, team/office photos | 0 | 4 team cards | 0 |
| `/kontakty/` | 1 | 1 | map/form side visual | 0 | 2 | 0 |
| `/blog/` | 1 | 4 (6 instances with thumbs) | unified 16:9 cover series | 0 | 4+ post cards | 0 |

**Forbidden simplifications:** см. JSON per page (e.g. one-hero-only, text-only about, unstyled blog list, price board on tour).

---

## 11. Source decomposition (must-match / must-not)

Источник: `AURA_SOURCE_DECOMPOSITION.json`. Кратко per page:

### `/`

| Must match | Must not |
|------------|----------|
| 50/50 hero + 3-photo collage + dashed flight path | Single full-bleed hero only |
| One terracotta pill primary | Dual competing hero CTAs |
| 5 vertical dest cards + tab filter + gradient captions | Price grid / second catalog |
| Editorial asymmetric (home: **teaser 1**, not full 1+3 mid-funnel) | Logo watermark on sections |
| Subscribe full-bleed photo + overlay | Playfair / AI purple gradients |
| trust-social facts + VK + tel | Fake quotes, AggregateRating |

### `/tour/`

| Must match | Must not |
|------------|----------|
| `catalog-hub.jpg` hero distinct from homepage hero | Copy-paste homepage hero |
| dest card language inherited | Plain `page.php` wall of text |

### `/o-nas/`, `/kontakty/`, `/blog/`

См. decomposition entries `page-about`, `page-contact`, `blog-archive` — editorial inner, light NAP, card grid with covers.

---

## 12. Section blueprints per page

Источник: `AURA_SECTION_BLUEPRINTS.json`. Implementation blockers:

| section_id | page | Blocker |
|------------|------|---------|
| `front-page:trust-social` | `/` | **Not in `front-page.php` yet** — Aurora P0 |
| `front-page:audience-tabs` | `/` | New vs legacy theme — content + Aurora |
| `front-page:faq-home` | `/` | Missing on theme — Aurora P0 |
| `front-page:editorial` | `/` | Shrink to teaser; thumbs depend on WP posts |
| `front-page:subscribe` | `/` | Hold if newsletter unconfirmed |
| global:header | * | Logo header-only |

**Motion:** optional `.reveal` / GSAP; `prefers-reduced-motion` static fallback (`AURA_TASTE_PROFILE`).

**Transitions (`AURA_SECTION_TRANSITIONS.json`):** replicate `collage-overlap-internal`, `dest-card-scrim`, `subscribe-overlay`; wave optional (ref JPG missing).

---

## 13. Style match scorecard thresholds

Источник: `AURA_STYLE_MATCH_SCORECARD.md`.

| Rule | Threshold |
|------|-----------|
| Per-dimension planned | ≥ minimum on all rows post-build |
| P0 blockers | trust-social shipped; no fake social proof |
| Target planned avg | ≥ 8.0 after trust + Manrope-only + blog covers |
| Current gate | ❌ DESIGN GATE pre-build — expected; QA recalc after Aurora |

---

## 14. Visual inventory requirements

Перенести в `site-spec.json` → `per_page_meaningful_image_counts`:

| slug | min meaningful | key zones |
|------|----------------|-----------|
| `/` | 12 | hero×3, dest×5, trust photo, editorial feature, subscribe bg |
| `/tour/` | 6 | hub + 5 dest |
| `/o-nas/` | 2 | team-travel, office-ufa |
| `/kontakty/` | 1 | map or office contextual |
| `/blog/` | 4 | post featured images (Excalibur covers) |

Form-side visuals: optional on `/kontakty/` — не «bare form only».

---

## 15. Assets and blockers

| Asset / topic | Status | Owner |
|---------------|--------|-------|
| Theme heroes, dest, subscribe | ✅ `AURA_ASSET_REGISTRY.json` local paths | asset-packager |
| Blog cover series unity | ⚠️ MCP KV needsAuth | asset-packager / post-auth |
| `trust-social` section PHP | ❌ missing | Aurora PAGE BUILDER |
| Yandex Metrika ID | needs_user_fact | conversion |
| Email newsletter confirmed | needs_user_fact | hold subscribe CTA |
| Reference JPG (wave) | missing | waive flat transition |

---

## 16. Тексты и объём

| Page / type | Знаки (полезный текст) | H1 / meta |
|-------------|------------------------|-----------|
| `/` | 3000–4500 + FAQ | Title 50–60, Desc 150–160, один H1 |
| `/tour/` | 2500–4000 | см. `07-content-briefs` |
| `/o-nas/` | 3000–5000 | E-E-A-T, fact-bank only |
| `/kontakty/` | 2000–3500 | NAP + answer block 40–60 слов |
| Blog archive | 500–1200 intro | — |
| Single article | 8000–15000+ | per `11-blog-topics.md` |

**GEO:** question-style H2; 40–60 word direct answers after key questions; visible FAQ; schema только по видимому контенту.

---

## 17. SEO / GEO (Google + Yandex)

- **Yandex:** регион Уфа; NAP на каждой странице в footer; Organization/LocalBusiness; verification meta when ID known; Metrika goals = funnel table.
- **Google:** helpful content; clean H1–H3; JSON-LD; mobile CWV; no doorway thin pages.
- **AI/GEO:** `llms.txt` + policy; stable `@id` for Organization; answer blocks in FAQ and blog.
- **Internal links:** 3–8 descriptive links per SEO page; blog ↔ tour ↔ kontakty ↔ home.
- **Cannibalization:** commercial «купить тур» → `/` + `/kontakty/`; BT-01 = informational how-to.

---

## 18. Schema map

| Type | Where | Notes |
|------|-------|-------|
| Organization | sitewide `@id` | ООО «Жираф», sameAs VK |
| LocalBusiness | `/kontakty/`, footer | NAP, hours, geo |
| WebSite | home | SearchAction optional P2 |
| WebPage | each indexed page | |
| BreadcrumbList | JSON-LD only | см. breadcrumbs policy |
| FAQPage | `/` faq-home, posts with FAQ | visible Q&A only |
| Article | `single.php` | Excalibur metadata |

---

## 19. Internal linking policy

| From | To (priority) |
|------|----------------|
| `/` | `/tour/`, `/kontakty/`, BT-01, destination cards |
| `/tour/` | each `/tour/{country}/`, `/`, quiz |
| `/o-nas/` | `/kontakty/`, `/`, quiz |
| `/kontakty/` | BT-01, `/`, `/tour/` |
| `/blog/` | all published BT; hub `/tour/` |
| Articles | 2–3× `/tour/*`, `/`, `/kontakty/`, sibling BT |

Anchor text: descriptive («туры в Турцию из Уфы»), не «здесь».

---

## 20. Breadcrumbs policy

- **Видимые верхние крошки:** **не выводить** (нет безопасного editorial-паттерна в AURA).
- **JSON-LD `BreadcrumbList`:** разрешён на inner pages и single, если иерархия однозначна (`Home > Направления > Турция`).
- Тема: `inc/breadcrumbs.php` — только JSON-LD path, без visible trail (проверить QA).

---

## 21. Crawl / indexing policy

| Item | Policy |
|------|--------|
| `robots.txt` | Allow production; staging `b2b-tour.ru` disallow or noindex |
| `sitemap.xml` | All indexable P0/P1 + published posts |
| canonical | Self-canonical; staging → prod canonical when cutover |
| noindex | Thank-you, search, politika draft, empty blog if no posts |
| redirects | Legacy junk URLs 301 per `inc/redirects.php` |
| `llms.txt` | Excalibur generator; entity summary + key URLs |
| AI crawlers | Allow read; no hidden paywall content |

---

## 22. Local entity policy

| Channel | Action |
|---------|--------|
| NAP | Единый формат: Гагарина 74/1, +79018105018, office@zhiraf-ufa.ru |
| Yandex Business | Link + same NAP (local-entity agent) |
| Google Business Profile | Same |
| 2GIS | Same |
| Maps | Embed on `/kontakty/` |
| Reviews | **No on-site fake reviews**; VK link only until verified embed |

---

## 23. Performance / accessibility

| Metric | Target |
|--------|--------|
| LCP | < 2.5s |
| INP | < 200ms |
| CLS | < 0.1 |
| Images | WebP where possible; width/height; lazy below fold |
| Fonts | Manrope single import; preload critical |
| JS/CSS | Defer non-critical; no blocking quiz on first paint |
| WCAG | AA contrast primary CTA; focus-visible; keyboard quiz modal |

Передать в `performance-accessibility-map.md`: per-page image counts from visual inventory.

---

## 24. Conversion policy

- Forms: max 5 fields + consent; honeypot `zhiraf_hp`.
- Delivery: email `office@zhiraf-ufa.ru`; anti-spam server-side.
- Analytics: events from funnel-map table; staging test container OK.
- Sticky CTA mobile after `trust-highlights`.
- Subscribe: secondary; do not compete with quiz.

---

## 25. Security / release (team input)

- `site-spec.json` / `build-report.json`: theme version, template list, per_page_meaningful_image_counts, AURA artifact hashes.
- PHP lint, Theme Check, deployignore, backup/rollback — `aurora-team-security-release`.
- **Не править тему в Lead** — только blueprint; реализация Aurora.

---

## 26. Data-flow → Aurora artifacts

Aurora обязана перенести из этого blueprint + AURA pack:

| Artifact | Fields |
|----------|--------|
| `site-spec.json` | pages[], templates[], menu IDs, `per_page_meaningful_image_counts`, funnel section order `/`, taste mandate IDs |
| `build-report.json` | visual gaps, trust-social status, scorecard snapshot |
| `content-completeness-report.md` | block checklist per page, blog publish state |

Refs: `shared/agent-data-flow-contract.md`.

---

## 27. Parallel team agent tasks

### aurora-team-content

- Copy по funnel stages; H1 главной semantic; audience-tabs уникальные буллеты; FAQ 6–8; убрать «15 минут»; fact-bank only; volumes per §16; blog teasers BT-01…06 titles from `11-blog-topics.md`.

### aurora-team-navigation

- Register menus per §7; `data-quiz-open` on CTAs; footer destination links; blog in main nav; tel link.

### aurora-team-schema

- JSON-LD map §18; FAQPage home; Article single; BreadcrumbList JSON-LD only.

### aurora-team-indexing

- robots/sitemap/canonical/llms.txt; staging noindex; blog index rules.

### aurora-team-local-entity

- NAP consistency §22; map embed; Yandex/Google/2GIS checklist.

### aurora-team-performance-a11y

- CWV budget; image policy from visual inventory; Manrope; reduced motion; 44px targets.

### aurora-team-conversion

- Wire events §8; quiz AJAX; sticky bar; form delivery; consent fields.

### aurora-team-security-release

- site-spec contract; Theme Check; rollback; deployignore.

### aurora-team-asset-packager

- Package registry assets; blog cover pipeline when MCP auth.

### aurora-team-motion

- MOTION PLAN: reveal/stagger per taste; GSAP non-blocking.

### aurora-team-qa

- Funnel order §4; single primary hero CTA; no visible breadcrumbs; no fake posts; visual budget mins; taste T01–T15 spot check.

### Aurora (Director)

- THEME BASE → PAGE BUILDER: implement `trust-social`, `audience-tabs`, `faq-home`, funnel order, editorial teaser; Manrope-only; do not edit outside assignment.

---

## 28. Conflict log (resolved)

| Conflict | Winner | Action |
|----------|--------|--------|
| AURA editorial 1+3 mid-page | funnel-map | 1× BT-01 after `lead-action` |
| AURA subscribe placement | funnel-map | After ACTION; hold if no newsletter |
| AURA vs audience-tabs | funnel-map | Add `audience-tabs` §4 #2 |
| Scorecard trust gap | Aurora build | P0 before QA pass |

---

## 29. Blockers

| ID | Severity | Note |
|----|----------|------|
| B1 | medium | trust-social not in theme PHP yet |
| B2 | low | MCP KV auth for new blog covers |
| B3 | low | Metrika ID, newsletter confirmation — user facts |

**Blueprint status:** ✅ READY (blockers assigned to downstream agents, not structural).
