# Animation Motion Map — «Жираф» (`zhiraf-ufa`)

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (staging: b2b-tour.ru) |
| theme_slug | `zhiraf-ufa` |
| theme_path | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` |
| mode | **MOTION PLAN** (implementation deferred) |
| design_ref | `teya-memory/design/AURADESIGN.md`, `AURA_SECTION_BLUEPRINTS.json`, `AURA_SECTION_TRANSITIONS.json`, `AURA_TASTE_PROFILE.md` |
| blueprint_ref | `teya-memory/wp/aurora-team-blueprint.md` |
| performance_ref | `teya-memory/wp/performance-accessibility-map.md` *(ещё не создан — бюджеты ниже согласованы с blueprint §performance-a11y и AURA taste)* |
| author | aurora-team-motion |
| date | 2026-10-18 |
| status | ✅ **MOTION PLAN READY** |

---

## 1. Motion principles (brand fit)

| Principle | Rule |
|-----------|------|
| Personality | Спокойный **editorial travel magazine** (Globe Trekker), не SaaS и не gaming 3D (`AURADESIGN` «Не: gaming 3D»). |
| Tempo | Быстрые UI: **0.2s** links/buttons; вход секций **0.5–0.8s** max (исправить текущие 1.2s в `motion-home.js`). |
| Easing | `power2.out` / `power3.out` для reveal; **запрещены** bounce/elastic и `ease-in` на open (`AURA_TASTE_PROFILE` T05, T15). |
| Depth | Коллаж hero + пунктирная траектория + лёгкий parallax; карточки — scale фото, не layout shift. |
| Conversion | Motion **не** блокирует квиз, формы, якоря, sticky CTA; без scroll hijack / forced pin. |
| LCP | Hero H1, CTA и фото коллажа **видимы без JS**; анимация только усиливает, не скрывает контент. |

---

## 2. Technology split (CSS vs GSAP vs Three.js)

| Layer | Где | Почему |
|-------|-----|--------|
| **CSS** | `:hover`/`:focus` кнопок, табов, `dest-premium-card` image scale, `step-card` lift, header blur, mobile nav bar morph, `prefers-reduced-motion` static overrides | Дешёво, не трогает main thread при idle; уже в `assets/src/scss/main.scss`. |
| **GSAP + ScrollTrigger** | Homepage sequenced scroll (`.reveal`), hero intro timeline, SVG flight-path draw, destination row stagger, optional scrub parallax коллажа | Sequencing, stagger, `once: true` triggers, `matchMedia` для reduced motion — проще и стабильнее чем CSS-only для 15+ узлов. |
| **Three.js (WebGL)** | **Одна** сцена: `hero-premium` ambient «flight dust» — мягкие частицы вдоль SVG-траектории, depth за коллажом | Даёт production wow-depth без «игрового» 3D; согласуется с dashed flight path storytelling. **Не** на внутренних страницах test build. |

**CDN:** запрещён. GSAP уже локально: `assets/vendor/gsap/`. Three.js — добавить `assets/vendor/three/` или esbuild bundle в `MOTION IMPLEMENT`.

---

## 3. Текущее состояние темы (audit)

| Item | State | Action в IMPLEMENT |
|------|-------|-------------------|
| `inc/enqueues.php` | GSAP + ScrollTrigger + `motion-home.js` **только** `is_front_page()` | Сохранить условную загрузку; расширить после refactor entry. |
| `assets/js/motion-home.js` | `.reveal` ScrollTrigger; **мертвые** селекторы `.collage-tile`, `.dest-card` (legacy preview) | Переписать на `.hero-collage__img`, `.dest-premium-card`; единый модуль. |
| `front-page.php` | Классы `.reveal`, `.reveal-delay-*` на hero, dest, steps, editorial | Сохранить; добавить hooks из §6 для новых секций blueprint. |
| Durations | 1.2s в motion-home | Урезать до ≤0.8s, translateY **24px** (AURA motion spec). |
| `reveal-delay-*` | В HTML, **нет** обработки в JS/CSS | GSAP `stagger` или `data-reveal-delay` + timeline offset. |
| `main.js` | Квиз, cookie, dest filter — без motion | Dynamic `import('./motion/home.js')` только на front page. |
| Three.js | Отсутствует | `threejs_scene_status: planned` (§5). |

---

## 4. Global hooks & contracts (сохранить Aurora Page Builder)

### 4.1 Классы и data-атрибуты

| Hook | Назначение | Страницы |
|------|------------|----------|
| `.reveal` | ScrollTrigger fade-up (opacity + translateY) | `*`, где не мешает LCP |
| `.reveal-delay-1` … `.reveal-delay-3` | Stagger offset (steps, hero collage) | `/` |
| `[data-motion="hero"]` | Root hero timeline (опционально на `<section class="hero-premium">`) | `/` |
| `[data-motion="flight-path"]` | SVG `.hero-collage__flight-path path` для draw animation | `/` |
| `[data-three-canvas="hero-ambient"]` | Container для WebGL canvas **под** коллажом, `pointer-events: none` | `/` |
| `[data-quiz-open]` | Без изменений — motion не перехватывает click | global |
| `.dest-premium-card` | Card hover CSS + optional scroll stagger | `/` |
| `.dest-card` | Catalog grid `/tour/` | `/tour/` |
| `.tab-btn` / `[data-filter]` | Filter без layout animation (toggle `.is-hidden`) | `/`, `/tour/` |

**Запрет:** не вешать `opacity: 0` в CSS на LCP-узлы без `@media (prefers-reduced-motion)` fallback visible.

### 4.2 Reduced motion

```text
@media (prefers-reduced-motion: reduce) {
  .reveal { opacity: 1 !important; transform: none !important; }
}
```

GSAP: `gsap.matchMedia({ "(prefers-reduced-motion: reduce)": () => { /* no-op / instant set */ } })` + не регистрировать ScrollTrigger для reduce.

Three.js: **не инициализировать**; canvas container `display: none` или не монтировать.

### 4.3 Performance budget (per route)

| Route | Extra JS (gzip est.) | Main-thread rule | LCP |
|-------|----------------------|------------------|-----|
| `/` | GSAP ~45kb + ST ~15kb + motion chunk ~4kb + Three **dynamic** ~150kb (parse once, idle after hero) | Только `transform`/`opacity` на DOM; WebGL capped DPR ≤1.5, pause offscreen | Hero images + H1 не ждут GSAP |
| `/tour/`, `/o-nas/`, `/kontakty/`, `/blog/` | **0** GSAP/Three (только `main.js` + CSS) | CSS hover only | N/A |
| `single.php` | 0 motion libs | — | — |

**ScrollTrigger:** `once: true`, `start: 'top 85%'`, без pin/snap на test build.

---

## 5. Three.js scene — `hero-ambient-flight`

| Field | Value |
|-------|-------|
| `threejs_scene_status` | **planned** (IMPLEMENT) |
| Purpose | Ambient particles / soft glow dots drifting along hero flight curve — «вылет из Уфы», не globe mesh |
| Container | `[data-three-canvas="hero-ambient"]` inside `.hero-premium__collage`, `z-index: 0`, images `z-index: 1–3`, SVG `z-index: 4` |
| Assets | Procedural `Points`; no external GLB |
| DPR | `Math.min(devicePixelRatio, 1.5)` |
| Start | `IntersectionObserver` when `.hero-premium` ≥10% visible |
| Pause | `document.hidden`, offscreen, `prefers-reduced-motion`, width `<768` (optional static SVG only) |
| Cleanup | `dispose()` geometry/material/renderer; cancel `requestAnimationFrame` |
| Fallback | Static dashed SVG + collage CSS shadows (`AURA_SECTION_TRANSITIONS` collage-overlap) |

**Blocker note:** если esbuild/vendor Three невозможен на хостинге — статус станет `MOTION THREEJS BLOCKER`; wow остаётся на GSAP flight-path + collage parallax.

---

## 6. Per-page / per-section plan

### 6.1 Global (`header.php`, `footer.php`)

| Zone | Tech | Selector / hook | Trigger | Fallback |
|------|------|-----------------|---------|----------|
| Sticky header | CSS | `.site-header` backdrop blur | scroll (CSS `position: sticky`) | static header |
| Nav toggle | CSS | `.nav-toggle__bar` | click | instant open |
| Footer links | CSS | `.footer-menu a` | hover 0.2s color | — |

### 6.2 Homepage `/` — `front-page.php`

| section_id | Tech | Elements | Animation | Trigger | Reduced motion |
|------------|------|----------|-----------|---------|----------------|
| `hero-premium` | GSAP timeline + CSS | `.hero-premium__content`, `.hero-collage__img--1..3`, `.hero-collage__flight-path path` | Load: stagger imgs y:24→0, opacity; draw path `strokeDashoffset`; subtle float loop imgs ±4px (yoyo) | `DOMContentLoaded` + optional `ScrollTrigger` scrub 0–15% viewport | Static collage + full path visible |
| `hero-premium` | Three.js | `[data-three-canvas="hero-ambient"]` | Particles along sampled SVG path | IO visible | Off |
| `audience-tabs` *(Aurora P0)* | CSS + micro GSAP | `.audience-tabs`, `.audience-panel` | Tab: cross-fade opacity panel; underline slide `transform: scaleX` | click | Instant swap |
| `section--destinations` | GSAP | `.dest-premium-card` | Stagger reveal **или** batch from `.dest-premium-row` | ST once | Visible |
| `section--destinations` | CSS | `.dest-premium-card__bg img` | scale 1.05 hover | hover | — |
| `section--how-it-works` | GSAP | `.step-card` | stagger 0.1s, translateY 24px | ST `trigger: .steps-row` | Visible |
| `trust-social` *(P0)* | GSAP | `.trust-card`, `.trust-visual` | single `.reveal` batch | ST once | Visible |
| `faq-home` *(P0)* | CSS | `details` / accordion | height/opacity max 0.25s | click | no animation |
| `lead-action` | CSS | `.form-premium` focus rings | focus-visible | — | — |
| `section--editorial` | GSAP + CSS | `.editorial-main`, `.editorial-list-item` | reveal + img hover scale | ST | Visible |
| `section--subscribe` | CSS | overlay | none (static scrim per transitions) | — | — |
| `quiz-modal` | CSS | `#quiz-modal` | fade backdrop 0.2s ease-out | open/close | instant |

**Section transitions (`AURA_SECTION_TRANSITIONS.json`):** implement as CSS only (milky flat, scrim, collage overlap) — **no** GSAP between sections.

### 6.3 `/tour/` — `page-tour.php`

| Zone | Tech | Hook | Notes |
|------|------|------|-------|
| `catalog-hero` | optional `.reveal` | add class in Page Builder | **No** GSAP enqueue unless extended policy |
| `dest-grid` | CSS hover on `.dest-card__img` | match homepage card scale | Filter chips: class toggle only |

### 6.4 `/o-nas/`, `/kontakty/`

| Zone | Tech | Notes |
|------|------|-------|
| `page-hero`, team/office | `.reveal` optional | CSS-only acceptable; **no** extra libs |
| Contact form | focus CSS | — |

### 6.5 `/blog/` — `home.php`, `single.php`

| Zone | Tech | Notes |
|------|------|-------|
| Post cards | CSS img hover | No scroll storytelling |

---

## 7. Implementation architecture (MOTION IMPLEMENT)

```text
assets/src/js/
  main.js                          # dynamic import guard
  motion/
    home.js                        # gsap.context, initHomeMotion()
    hero-flight-path.js            # SVG draw helper
    hero-ambient-three.js          # dynamic import('three')
assets/vendor/three/               # or npm + esbuild bundle
inc/enqueues.php                   # front page: vendor gsap + bundled motion chunk
```

**Build:** esbuild bundle `assets/src/js/main.js` → `assets/dist/main.js`; motion chunk code-split or separate `motion-home.bundle.js`.

**Hooks для Page Builder (добавить при сборке секций):**

1. `<section class="hero-premium" data-motion="hero">`
2. `<div class="hero-premium__collage" data-three-canvas="hero-ambient" aria-hidden="true"></div>` (canvas injected by JS)
3. Новые секции: `trust-social`, `audience-tabs`, `faq-home` — обернуть карточки в `.reveal` по таблице §6.2.

---

## 8. QA checklist (post-IMPLEMENT)

- [ ] Hero readable with JS disabled (no `opacity:0` in critical CSS)
- [ ] `prefers-reduced-motion: reduce` — no ScrollTrigger, no WebGL
- [ ] Mobile 375px — flight path hidden per SCSS; Three off &lt;768
- [ ] No pin/snap; quiz/form focusable during scroll anim
- [ ] Lighthouse: motion scripts deferred, not render-blocking
- [ ] Selectors `.dest-premium-card` / `.hero-collage__img` animated (not legacy `.dest-card` only)

---

## 9. Handoff

| Consumer | Action |
|----------|--------|
| **Aurora PAGE BUILDER** | Сохранить/добавить hooks §4–§6; не удалять `.reveal`; вставить P0 секции с классами из blueprint |
| **aurora-team-motion IMPLEMENT** | Refactor motion bundle, fix selector drift, add Three scene, `animation-implementation-report.md` |
| **aurora-team-performance-a11y** | Сверить с будущим `performance-accessibility-map.md` (LCP image priority hero) |

**Plan status:** ✅ MOTION PLAN READY — implementation intentionally not started in this run.
