# Teya Pipeline Log — Жираф Уфа (`zhiraf-ufa`)

**Дата среза:** 2026-10-18  
**Ветка:** `cursor/zhiraf-b2b-site-a2bf`  
**Тема:** `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/`  
**Режим:** CONVERSION-FIRST test build (local-only, без production deploy)

Журнал фактических прогонов ролей Teya по проекту «Жираф»: артефакты в `teya-memory/`, код темы в `deliverables/`. Не симуляция — зафиксированы реальные коммиты, отчёты и блокеры.

---

## 0. Потоки Teya

Отдельный поток = отдельный Cloud Agent (`bc-…`). Если **нет** — работа вошла через lead/motion/координатор (в т.ч. после сбоя `CreateAgent`).

| Роль | agent_id (`bc-…`) | Отдельный поток |
|------|-------------------|-----------------|
| teya-researcher | `bc-2ff004ba-69e5-577e-8c25-8bfdb9713f06` | да |
| yadryshko (semantic core) | `bc-9245beaf-51c1-55b8-9662-24124b50eeb4` | да |
| aura-designer (AURA pack) | `bc-dac8c4ec-2000-57ff-9988-ffb88bed57b2` | да |
| aurora-team-design-taste (PLAN/AUDIT) | `bc-fb384920-07f1-54e6-9b21-7a0887017051` | да |
| aurora-team-conversion-architect (funnel) | `bc-94545bc7-2d9b-57a5-b2a0-de076c7edf9a` | да |
| excalibur + geo-qa | `bc-7471fd45-7f74-5df5-a2d9-bf085bab67c0` | да |
| aurora-team-lead (blueprint) | `bc-ab1cbcd4-ae66-5ab7-b213-55ca7558f984` | да |
| aurora-team-content (`page-content-pack`) | `bc-ab1cbcd4-ae66-5ab7-b213-55ca7558f984` | **нет** — через lead (отдельный CreateAgent падал) |
| aurora-team-motion + сборка главной | `bc-9773db71-f3bd-5d80-831c-70ba4f27911b` | да |
| wp-theme-builder (полный цикл) | `bc-9773db71-f3bd-5d80-831c-70ba4f27911b` | **нет** — handoff в motion-run, не отдельный builder-агент |
| aurora-team-performance-a11y | `bc-646a0fe3-3bb0-5e28-bdf3-d4cafb2cc25e` | да |
| aurora-team-design-guardian + aurora-team-qa | `bc-646a0fe3-3bb0-5e28-bdf3-d4cafb2cc25e` | да (один run: guardian → QA) |
| aurora-team-asset-packager | `bc-a36c4774-8895-561d-aebf-55df2ca8eb31` | да |
| aurora-team-artifact-auditor | `bc-53277b3d-9e1c-5fd6-9cab-24e622e7203e` | да |
| aurora-team-report-compiler + release-gate | `bc-53277b3d-9e1c-5fd6-9cab-24e622e7203e` | да (compiler → `teya_release_gate.py`) |
| copywriter (§17 copy cleanup & FAQ) | `bc-7e9e921f-827c-50a8-b634-11ec26a8fbcf` | да |
| wp-theme-builder (§17 FAQPage schema & mirror) | `bc-a7a4c486-5ef1-5a2a-be67-cc8eb792ffef` | да |
| seo-qa (§17 19 URL & schema audit) | `bc-8a99c6c2-46eb-5e04-ba09-ca6984e1b8c2` | да |
| paint-qa (§17 38 PNG refresh) | `bc-c4b918ce-16e6-51e9-9a25-e51c8a14352b` | да |
| release-gate-auditor (§17 gate verification) | `bc-18ca6b59-9ae5-5e63-95aa-f2be6cf4117b` | да |
| bg-api-researcher (§18 BG API spec & fixtures) | `bc-4a0b2d6a-4933-5c0a-9fb8-51fbbff0fbc5` | да |
| copywriter (§18 expanded home copy 600-800 words) | `bc-04b39b08-8f85-5a1e-8fe5-19e4de6e507b` | да |
| wp-theme-builder (§18 BG API engine & cards) | `bc-4b537d97-17b5-55de-a2cc-4161b9a9d592` | да |
| seo-qa (§18 19 URL, Schema & copy lint) | `bc-603126be-b364-5e13-91b3-4f9e1e19d675` | да |
| paint-qa (§18 tour price cards capture) | `bc-0351c800-bd5a-5537-bc28-99dd60a3169c` | да |
| release-gate-auditor (§18 release gate verification) | `bc-0d13e97b-f467-5768-b693-e95d454b93df` | да |
| pipeline-log-compiler (§18 log compilation) | `bc-94c818d5-a1a2-51dc-ae0e-c3e6ebacfa4c` | да |

---

## 1. Роль: teya-researcher (Аудит)
- **Файлы контекста:** `agents/teya-researcher.md`, `skills/teya-researcher/SKILL.md`
- **Что сделано:** Аудит темы и брифа (Globe Trekker), dossier + research pack.
- **Вердикт:** Research gate ✅

## 2. Роль: aura-designer + aura-cyrillic-google-fonts + aura-shape-replication (Дизайн и Типографика)
- **Файлы контекста:** `agents/aura-designer.md`, skills AURA
- **Что сделано:** `teya-memory/design/*`, AURA pack (MCP KV needsAuth — локальные assets).
- **Вердикт:** Design pack ✅; финальная типографика — Manrope-only после §7–8

## 3. Роль: wp-theme-builder (Вёрстка и интеграция)
- **Файлы контекста:** `agents/wp-theme-builder.md`, `skills/wp-theme-builder/SKILL.md`
- **Что сделано:** Базовая тема `zhiraf-ufa`; доработка P0 главной — в §7 (motion-run).
- **Вердикт:** `php -l` ✅; полный builder-конвейер — не отдельный поток (см. §0)

## 4. Команда: aurora-team-* (ранний аудит)
- Ранние правки: focus-visible, `aria-hidden` на flight SVG, cookie banner.
- Актуальные gate-отчёты — §8–9.

## 5. Роль: excalibur + excalibur-geo-qa (Тексты и Локализация)
- **Вердикт:** 6/6 статей GEO QA ✅; привязка к Уфе в контенте

## 6. Роль: yadryshko-semantic-core (SEO и Семантика)
- **Вердикт:** `teya-memory/semantic-core/zhiraf-ufa-2026/` ✅

## 7. Роль: aurora-team-motion + wp-theme-builder handoff (`bc-9773db71-f3bd-5d80-831c-70ba4f27911b`)
- **Сделано:** funnel P0 на `front-page.php`, motion (GSAP + Three ambient), `sass` → `dist/style.css`, `php -l` OK.
- **Отчёт:** `teya-memory/wp/animation-motion-map.md`, `animation-implementation-report.md`
- **Taste follow-up** (`bc-fb384920`): T04/T09, trust-social стили, preview sync.

## 8. Phase1 resume — Aurora step 14 (`bc-9773db71-f3bd-5d80-831c-70ba4f27911b`)
- **THEME BASE:** `teya-memory/wp/theme-base-report.md`; cookie banner + privacy/cookies links; `teya-memory/wp/conversion-tracking-map.md` (blocker: Metrika ID).
- **PAGE BUILDER:** 5 лендингов `/tour/turciya|oae|egipet|tailand|vietnam/` — `page-*.php`, `inc/destination-landings.php`, quiz prefill; `teya-memory/wp/page-build-report.md`.
- **QA:** `sass` → `assets/dist/style.css`; `php -l` — 41 PHP, 0 ошибок. Коммит + push `cursor/zhiraf-b2b-site-a2bf`. Деплой — нет.

## 9. Роль: aurora-team-design-guardian (дизайн-gate)
- **Агент:** `agents/aurora-team-design-guardian.md`, `skills/aurora-team-design-guardian/SKILL.md`
- **Run:** `bc-646a0fe3-3bb0-5e28-bdf3-d4cafb2cc25e` (2026-10-18, финальный re-run после motion + taste)
- **Проверки:** `php -l` 34/34; Playwright fullpage (scroll) `preview/home.html` → `/opt/cursor/artifacts/zhiraf-home-1440.png`, `zhiraf-home-390.png`; `teya-memory/wp/paint-qa/*`; Manrope-only; meaningful images ≥12; funnel-секции vs blueprint.
- **Артефакты:** `teya-memory/wp/design-integrity-report.md`, `paint-qa-report.md`, `paint-evidence.json` (`verdict: pass`), fragment `aurora-team-design-guardian.md`
- **Вердикт:** **✅ DESIGN OK** — **local test build** (inner-page paint и live deploy — вне scope)

## 10. Роль: aurora-team-qa (финальная проверка)
- **Агент:** `agents/aurora-team-qa.md`, `skills/aurora-team-qa/SKILL.md`
- **Run:** `bc-646a0fe3-3bb0-5e28-bdf3-d4cafb2cc25e` (2026-10-18, финальный re-run)
- **Проверки:** Design Guardian **✅ DESIGN OK**; paint evidence on disk + `pass`; WP theme contract; anti-haltura; deferred: оставшиеся wp maps, `site-spec.json`, live robots/sitemap, `teya_release_gate.py` (нет в репо).
- **Артефакты:** `teya-memory/wp/seo-geo-verification.md`, fragment `aurora-team-qa.md`
- **Вердикт:** **✅ OK** — **local test build** | **ready_to_publish: no** (нет production URL / полного deploy gate)

### Итог 2026-10-18 (test build)
| Gate | Статус |
|------|--------|
| Design Guardian | **✅ DESIGN OK** (local) |
| Final QA | **✅ OK** (local test build) |
| Publish | **no** — deploy + SEO/indexing maps |

---

## 10. Phase 1 resume — artifact auditor (`bc-53277b3d-9e1c-5fd6-9cab-24e622e7203e`)

- **Роль:** `aurora-team-artifact-auditor`
- **Артефакты:** `teya-memory/wp/artifact-readiness-report.md`, `fragments/aurora-team-artifact-auditor.md`
- **Вердикт:** **READY** (maps + design + semantic + assets 17/17)
- **Deploy:** нет

## 11. Phase 1 resume — report compiler (`bc-53277b3d-9e1c-5fd6-9cab-24e622e7203e`)

- **Роль:** `aurora-team-report-compiler`
- **Inputs:** split evidence (в т.ч. восстановлены `theme-base-report.md`, `page-build-report.md`, `asset-transport-report.md` skipped)
- **Outputs:** `site-spec.json`, `build-report.json` (`status: blocked`), `content-completeness-report.md`, fragment `aurora-team-report-compiler.md`
- **Факты:** test build 5 templates ✅; `/tour/*` landings ❌; paint home ✅ / inner `pages[]` пуст; visual **11/14**
- **Theme paths:** packaged assets `teya-memory/wp/theme/zhiraf-ufa/`; полная тема `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` (gate run с Pillow + `wp-media-map.json`)

## 12. Phase 1 resume — release gate (`bc-53277b3d-9e1c-5fd6-9cab-24e622e7203e`)

- **Отчёт:** `teya-memory/wp/release-gate-report.md`
- **Deploy:** нет

### 12.1 Gate script rollback + honest run (2026-10-18)

- **Откат:** `scripts/teya_release_gate.py` → **07628b4** (initial plugin); сняты `6d305b5` (`allow_duplicate_of`) и `09d5cec` (`theme_path` fallback).
- **Команда:** `python3 scripts/teya_release_gate.py --project-root /workspace`
- **exit_code:** **1**
- **Blockers:** duplicate bytes hero/dest в media maps (×6); missing `style.css` / `assets/dist/style.css` under `teya-memory/wp/theme/zhiraf-ufa/` (packaged assets only).
- **artifact-readiness:** identity `zhiraf-ufa`, slug lint clean (no agent-slug false positives).

### 12.2 Post-packager gate run (Pillow, script 07628b4)

- **Команда:** `pip install Pillow` → `python3 scripts/teya_release_gate.py --project-root /workspace`
- **gate script:** SHA256 `50f71547469a0ccbf3ca9f11cddf07c42e90dcd44e0ad7cde64dcfc27a5c1a3d` — **match 07628b4** (файл не правился)
- **packager:** `5287fb4`+ (fixes after `fa6987f`) — полная тема в `teya-memory/wp/theme/zhiraf-ufa/`, distinct dest bytes
- **exit_code:** **0** — **PASS** (re-run подтверждён на HEAD `1b65ab6`)
- **Отчёт:** `teya-memory/wp/release-gate-report.md`
- **Deploy:** нет

### Итог Phase 1 resume (2026-10-18)

| Gate | Статус |
|------|--------|
| Artifact inputs | **READY** |
| Compiled reports | **blocked** (content/paint/landings gaps) |
| `teya_release_gate.py` | **PASS** (exit 0, script 07628b4) |
| Publish | **no** |

---

## 13. Wordstat URL-map (18.10.2026)

Честный конвейер после Wordstat-среза **Уфа 172** (833 фразы): от URL-map до gate **PASS** на `af9ce66`, **без production deploy**.

| Шаг | agent_id (`bc-…`) | Коммит / артефакт | Результат |
|-----|-------------------|-------------------|-----------|
| 1. Координатор (semantic URL-map) | lead / yadryshko handoff | **0b2883c** | [`wordstat/url-map.md`](../../teya-memory/semantic-core/zhiraf-ufa-2026/wordstat/url-map.md), `06-url-map.csv` — **20 страниц** (12 есть + **8 новых**) |
| 2. aurora-team-content | `bc-96c7bc04-…` | **82b6778** | `page-content-pack.md`, `07-content-briefs.md`, fragment `aurora-team-content.md` |
| 3. PAGE BUILDER (wp-theme-builder) | `bc-78870dfb-…` | **a2c6672** | **8** лендингов + `inc/tour-routing.php` (resort rewrites); `php -l` **49/0**; отчёт `teya-memory/wp/page-build-report.md` |
| 4. paint (design-guardian QA) | `bc-91bdee58-…` | **d5cf815** | **40** fullpage PNG (20 URL × 1440/390) → `/opt/cursor/artifacts/`; preview scripts в `deliverables/zhiraf-ufa/preview/` |
| 5. release gate | `bc-5ed0ebcc-…` | **af9ce66** | `teya_release_gate.py` **07628b4** (SHA256 без изменений); **exit 0 PASS** — только fix артефактов темы под gate, скрипт не трогали |

### 8 новых URL (порог ≥100 показов/мес., Уфа)

1. `/goryashchie-tury/` (горящие **3024**)
2. `/vse-vklyucheno/` (всё включено **735**)
3. `/tour/turciya/antalya/` (**122**)
4. `/tour/egipet/hurgada/` (**327**)
5. `/tour/egipet/sharm-el-sheyh/` (**242**)
6. `/tour/tailand/phuket/` (**125**)
7. `/tour/vietnam/nyachang/` (**193**)
8. `/tour/vietnam/fukuok/` (**183**)

Ниже порога 100 — секции на странах (Кемер 82, Аланья 58, Паттайя 49, с детьми 20); см. URL-map.

### Итог Wordstat pipeline (18.10.2026)

| Gate | Статус |
|------|--------|
| URL-map + CSV | **✅** (`0b2883c`) |
| Content 20 URL | **✅** (`82b6778`) |
| Theme 8 NEW + routing | **✅** (`a2c6672`, `php -l` 49/0) |
| Paint 20 routes | **✅** (`d5cf815`, 40 screenshots) |
| `teya_release_gate.py` | **PASS** (exit 0, script **07628b4**, ref **af9ce66**) |
| Deploy | **no** |

- **Журнал / fragment:** `teya-memory/fragments/wordstat-pipeline.md`
- **Release report:** `teya-memory/wp/release-gate-report.md`

---

## 14. Wordstat API refresh (18.10.2026)

Повторный честный прогон на **Yandex Search API v2** (регион **Уфа 172**): обновлённые частотности → **19** страниц в карте (**7 NEW**, без отдельного URL Пхукета), paint **38** PNG, gate **PASS** на `a06c6d5`, **без production deploy**.

| Шаг | agent_id (`bc-…`) | Коммит / артефакт | Результат |
|-----|-------------------|-------------------|-----------|
| 1. yadryshko (semantic + API) | `bc-6807d4c8-…` | **6715a4d** | API test **HTTP 200**, **45** успешных вызовов; `wordstat/_run_stats.json`; [`wordstat/url-map.md`](../../teya-memory/semantic-core/zhiraf-ufa-2026/wordstat/url-map.md), `06-url-map.csv` — **19** URL (**7 NEW**); **1754** включённых фраз |
| 2. aurora-team-content | — | **fd92227** | `page-content-pack.md`, briefs — **19** страниц, **7 NEW**, Phuket → секция на `/tour/tailand/` |
| 3. PAGE BUILDER (theme) | — | **94af27c** | Снят `/tour/tailand/phuket/`; Thailand sections + Wordstat sync; `php -l` OK |
| 4. paint (design-guardian QA) | `bc-6dc66391-…` | **a06c6d5** | **38** fullpage PNG (**19** маршрутов × 1440/390); `paint-evidence.json`; preview `deliverables/zhiraf-ufa/preview/` |
| 5. release gate | — | **ac0e203** (report) / **a06c6d5** (paint) | `teya_release_gate.py` **07628b4** (SHA256 `50f71547…`, скрипт не меняли); **`exit_code` 0 — PASS** — `teya-memory/wp/release-gate-report.md` (`git_ref` `a06c6d5`) |

### 7 NEW URL (порог ≥100, API 18.10.2026)

1. `/goryashchie-tury/` (горящие **3480**)
2. `/vse-vklyucheno/` (всё включено **561**)
3. `/tour/turciya/antalya/` (**344** totalCount по seed)
4. `/tour/egipet/hurgada/` (**853**)
5. `/tour/egipet/sharm-el-sheyh/` (**233**)
6. `/tour/vietnam/nyachang/` (**481**)
7. `/tour/vietnam/fukuok/` (**386**)

**Снято с отдельного URL:** `/tour/tailand/phuket/` (пхукет **58+55** &lt;100) → секция на [`/tour/tailand/`](../../teya-memory/semantic-core/zhiraf-ufa-2026/wordstat/url-map.md).

### Итог Wordstat API pipeline (18.10.2026)

| Gate | Статус |
|------|--------|
| Wordstat API (200, 45 calls) | **✅** (`6715a4d`) |
| URL-map **19** страниц | **✅** — [финальная карта](../../teya-memory/semantic-core/zhiraf-ufa-2026/wordstat/url-map.md) |
| Content 19 URL | **✅** (`fd92227`) |
| Theme (Phuket URL removed) | **✅** (`94af27c`) |
| Paint 19 routes | **✅** (`a06c6d5`, 38 screenshots) |
| `teya_release_gate.py` | **PASS** (exit **0**, ref **a06c6d5**) |
| Deploy | **no** |

- **Fragment (опц.):** `teya-memory/fragments/wordstat-api-pipeline.md` — не создан; сводка в §14
- **Release report:** `teya-memory/wp/release-gate-report.md`

---

## 15. Metrika + SEO audit merge (18.10.2026)

Интеграция **Яндекс.Метрики** (`70f05ad`, счётчик **45658173**, consent-gated init + `reachGoal`) и merge **`origin/cursor/seo-qa-19url-audit-67f2`** → **`aa54024`** (fast-forward, конфликтов нет). После merge PHP темы (SEO/meta/H1) — полный paint **19** Wordstat-маршрутов × **1440/390**; gate с **Pillow** decode-verify.

| Шаг | Коммит / артефакт | Результат |
|-----|-------------------|-----------|
| 1. Metrika (conversion) | **70f05ad** | Метрика **45658173**, consent → init, цели `lead_submit` / `phone_click` / `messengers_click` |
| 2. Merge SEO audit | **aa54024** | `fix(seo): audit 19 URL` — Wordstat в copy, titles/descriptions, breadcrumb JSON-LD; зеркало в `teya-memory/wp/theme/zhiraf-ufa/` |
| 3. paint (post-merge PHP) | `capture-all-paint.mjs` | **38** fullpage PNG (**19** × 1440/390); `teya-memory/wp/paint-qa/paint-evidence.json` (`verdict: pass`); копии в `/opt/cursor/artifacts/zhiraf-*.png` |
| 4. release gate | `teya_release_gate.py` **07628b4** | **`exit_code` 0 — PASS** (Pillow в runner); отчёт `teya-memory/wp/release-gate-report.md` |
| Deploy | — | **no** |

### SEO fixes (из internal `seo-qa-worker-report`)

1. **`inc/seo.php`** — titles/descriptions: `/`, `/tour/`, `/blog/`, legal; meta map; breadcrumb JSON-LD (hub, destination, offer, blog archive)
2. **`inc/assets.php`** — offer hub Wordstat **3480** / **561**
3. **`inc/destination-landings.php`** — Wordstat: Турция 2498/5875, Анталья 344, Кемер 70, Аланья 122, ОАЭ 274, Дубай 196, Египет 1983, курорты 853/233, дети 32, Вьетнам description
4. **`inc/offer-landings.php`** — горящие **3480**, всё включено **561**, блок направлений с актуальными показами
5. **`inc/resort-landings.php`** — всё включено **561**, FAQ Кемер/Аланья
6. **`page-tour.php`** — H1 «Направления отдыха с вылетом из Уфы»
7. **`home.php`** — H1 блога по pack
8. **`page-kontakty.php`** — H1 «Контакты «Жираф» в Уфе»

### Итог

| Gate | Статус |
|------|--------|
| Metrika 45658173 | **✅** (`70f05ad`) |
| SEO 19 URL merge | **✅** (`aa54024`) |
| Paint 19 routes | **✅** (38 screenshots) |
| `teya_release_gate.py` | **PASS** (exit **0**) |
| Deploy | **no** |

- **SEO/GEO verification:** [`teya-memory/wp/seo-geo-verification.md`](../../teya-memory/wp/seo-geo-verification.md)
- **Release report:** [`teya-memory/wp/release-gate-report.md`](../../teya-memory/wp/release-gate-report.md)

---

## 16. Globe Trekker blog (18.10.2026)

AURA **Globe Trekker** blog pack (**1 featured + 5 cards**, serif **H1–H3**, editorial archive): blueprint **252193a**, WP/builder **1ae1824** (Cormorant Garamond, `template-parts/blog/*`, `home.php` / `front-page.php`, preview `home.html` + `blog.html`). Paint worker на ветке не коммитил после **1ae1824** — локально обновлены **home** и **`/blog/`** × **1440/390**, зеркало темы в `teya-memory`, gate **PASS**.

| Шаг | Коммит / агент | Результат |
|-----|----------------|-----------|
| 1. AURA (blueprint) | **252193a** | Globe Trekker blog pack (1+5, serif H1–H3) |
| 2. wp-theme-builder | **1ae1824** | Тема + preview sync; covers **blog-cover-02…06**; `_blog-globe.scss` |
| 3. mirror | `bc-8d4d71f5-…` | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` → `teya-memory/wp/theme/zhiraf-ufa/` |
| 4. paint (home + blog) | `bc-8d4d71f5-…` | **4** fullpage PNG (home + blog × 1440/390); `paint-evidence.json` обновлён (`git_ref` **1ae1824**, **38** total, `verdict: pass`) |
| 5. release gate | `teya_release_gate.py` **07628b4** | **`exit_code` 0 — PASS**; `teya-memory/wp/release-gate-report.md` |
| Deploy | — | **no** |

### Итог

| Gate | Статус |
|------|--------|
| AURA Globe Trekker | **✅** (**252193a** / **1ae1824**) |
| teya-memory theme mirror | **✅** |
| Paint home + blog | **✅** (4 PNG refresh, 38 в evidence) |
| `teya_release_gate.py` | **PASS** (exit **0**) |
| Deploy | **no** |

- **Release report:** [`teya-memory/wp/release-gate-report.md`](../../teya-memory/wp/release-gate-report.md)
- **Paint evidence:** [`teya-memory/wp/paint-qa/paint-evidence.json`](../../teya-memory/wp/paint-qa/paint-evidence.json)

---

## 17. Copy cleanup, FAQPage schema, Paint QA & Release Gate (18.10.2026)

Комплексный этап очистки текстов от служебных SEO-утечек, глубокой фактологической проработки направлений, интеграции структурированных микроразметок FAQPage JSON-LD, верификации мета-тегов и схем, обновления визуальных скриншотов (38 PNG) и финального подтверждения release gate (**PASS**).

| Шаг / Роль | agent_id (`bc-…`) | Коммит / артефакт | Результат |
|------------|-------------------|-------------------|-----------|
| 1. Копирайтер (aurora-team-content) | `bc-7e9e921f-827c-50a8-b634-11ec26a8fbcf` | **4861198** | Очистка всех SEO-утечек (**0 leaks**); глубокая фактология (сезонность, температуры воды/воздуха, прямой перелёт из Уфы, визы, отели); FAQ-блоки на всех страницах; скрипт `scripts/zhiraf_copy_lint.sh` (**PASS**); отчёт [`teya-memory/wp/copy-quality-report.md`](../../teya-memory/wp/copy-quality-report.md); объёмы слов по всем **19 URL** (все ≥ нормативов: страны ≥900, офферы ≥700, курорты ≥600) |
| 2. Page Builder (wp-theme-builder) | `bc-a7a4c486-5ef1-5a2a-be67-cc8eb792ffef` | **dbc02dd** | Внедрение схемы `FAQPage` JSON-LD в `inc/seo.php`; синхронизация FAQ-аккордеонов в шаблонах; валидация `php -l` (**52/52** файлов OK); зеркалирование темы в `teya-memory/wp/theme/zhiraf-ufa/` |
| 3. SEO-QA (aurora-team-qa) | `bc-8a99c6c2-46eb-5e04-ba09-ca6984e1b8c2` | **c37ee2e** | Верификация 19 URL; проверка мета-тегов с гео-привязкой к Уфе; валидация JSON-LD (`BreadcrumbList`, `WebSite`, `FAQPage`); 301-редирект `/tour/tailand/phuket/` → `/tour/tailand/`; `noindex` на staging; отчёт [`teya-memory/wp/seo-geo-verification.md`](../../teya-memory/wp/seo-geo-verification.md) |
| 4. Paint QA (aurora-team-design-guardian) | `bc-c4b918ce-16e6-51e9-9a25-e51c8a14352b` | **1d5a875** | Полностраничные скриншоты 19 маршрутов в 1440px и 390px (**38 PNG**); обновление `deliverables/zhiraf-ufa/paint/` и `teya-memory/wp/paint-qa/` (`paint-evidence.json` verdict: **pass**) |
| 5. Release Gate Auditor (aurora-team-release-gate) | `bc-18ca6b59-9ae5-5e63-95aa-f2be6cf4117b` | **HEAD** | Тесты `python3 scripts/teya_release_gate.py --project-root /workspace` (**PASS**, exit code 0); `bash scripts/zhiraf_copy_lint.sh` (**PASS**, 0 leaks); `php -l` (**PASS**); отчёт [`teya-memory/wp/release-gate-report.md`](../../teya-memory/wp/release-gate-report.md) |

### Сводка объёмов текстов и FAQ (19 коммерческих URL)

1. `/` — 423 слова, 8 Q&A
2. `/tour/` — 173 слова, 5 Q&A
3. `/goryashchie-tury/` — 781 слово (≥700), 7 Q&A
4. `/vse-vklyucheno/` — 831 слово (≥700), 7 Q&A
5. `/tour/turciya/` — 1 092 слова (≥900), 8 Q&A
6. `/tour/oae/` — 1 070 слов (≥900), 8 Q&A
7. `/tour/egipet/` — 1 009 слов (≥900), 8 Q&A
8. `/tour/tailand/` — 954 слова (≥900), 8 Q&A
9. `/tour/vietnam/` — 980 слов (≥900), 8 Q&A
10. `/tour/turciya/antalya/` — 630 слов (≥600), 7 Q&A
11. `/tour/egipet/hurgada/` — 620 слов (≥600), 7 Q&A
12. `/tour/egipet/sharm-el-sheyh/` — 644 слова (≥600), 7 Q&A
13. `/tour/vietnam/nyachang/` — 603 слова (≥600), 7 Q&A
14. `/tour/vietnam/fukuok/` — 612 слов (≥600), 7 Q&A
15. `/o-nas/` — 643 слова, 6 Q&A
16. `/kontakty/` — 80 слов (NAP)
17. `/blog/` — 20 слов (Editorial Archive)
18. `/politika-konfidenczialnosti/` — 102 слова (152-ФЗ)
19. `/politika-cookies/` — 61 слово (Cookies)

### Итог §17

| Gate | Статус |
|------|--------|
| Copy cleanup & facts (0 leaks) | **✅** (**4861198**) |
| FAQPage schema & WP mirror | **✅** (**dbc02dd**) |
| SEO/GEO & Schema audit | **✅** (**c37ee2e**) |
| Paint QA (38 screenshots) | **✅** (**1d5a875**) |
| Copy Linter (`zhiraf_copy_lint.sh`) | **PASS** (0 leaks) |
| `teya_release_gate.py` | **PASS** (exit **0**) |
| Deploy | **no** |

- **Copy report:** [`teya-memory/wp/copy-quality-report.md`](../../teya-memory/wp/copy-quality-report.md)
- **SEO/GEO verification:** [`teya-memory/wp/seo-geo-verification.md`](../../teya-memory/wp/seo-geo-verification.md)
- **Release report:** [`teya-memory/wp/release-gate-report.md`](../../teya-memory/wp/release-gate-report.md)
- **Paint evidence:** [`teya-memory/wp/paint-qa/paint-evidence.json`](../../teya-memory/wp/paint-qa/paint-evidence.json)

---

## 18. BG API Price Engine, Home Copy Expansion, Globe Trekker Tour Cards, Paint QA & Release Gate (18.10.2026)

Комплексный этап интеграции живого ценового движка туроператора «Библио-Глобус» (BG API), разработки эталонных фикстур, масштабирования полезного текста главной страницы (до 793 слов в спецификации / 1 205+ слов в полном рендере), внедрения карточек туров в дизайн-системе Globe Trekker, WP-Cron кэширования в transients (TTL 6ч), верификации SEO-QA (19/19 URL, Schema.org, 0 утечек), обновления скриншотов Paint QA (38 PNG) и финальной валидации Release Gate (**PASS**).

### Шаги и роли конвейера §18

| Шаг / Роль | agent_id (`bc-…`) | Коммит / артефакт | Результат |
|------------|-------------------|-------------------|-----------|
| 1. BG API Researcher / Spec Author | `bc-4a0b2d6a-4933-5c0a-9fb8-51fbbff0fbc5` | **f06e2e1**, **f5c19e0** | Спецификация [`teya-memory/wp/bg-api-spec.md`](../../teya-memory/wp/bg-api-spec.md); эталонный фикстур-пак `bg-tours-fixtures.json` (32 тура: 7 для главной + 5x5 по странам); детальный анализ цен `partners?action=price` (рубли строго из `RUR`) vs `action=minprice`; исследование расчета ночей (`duration` vs `o_duration` vs `f7`, отсечение стыковочных аномалий <3 ночей) |
| 2. Копирайтер (aurora-team-content) | `bc-04b39b08-8f85-5a1e-8fe5-19e4de6e507b` | **eaaffe7** | Расширение полезного текста главной страницы с 423 до 793 слов (`page-content-pack.md`); 8 синхронизированных Q&A в FAQ; 4-шаговый прозрачный процесс подбора (`section--how-it-works`); логистика вылетов из Уфы (UFA: прямые рейсы 4.5–5.5ч vs стыковки через Москву); гарантии E-E-A-T (РТА 0012822, 132-ФЗ, 152-ФЗ, офис на Гагарина 74/1); честный плейсхолдер отзывов (`section--reviews-placeholder`) со ссылками на Яндекс.Карты, 2ГИС, ВК; проверка `scripts/zhiraf_copy_lint.sh` (**PASS**, 0 утечек) |
| 3. WP-Theme-Builder (BG API Engine) | `bc-4b537d97-17b5-55de-a2cc-4161b9a9d592` | **7e754d0** | Модуль `inc/bg-api.php`: класс `Zhiraf_BG_API_Client`, аутентификация на `login.bgoperator.ru/auth` (куки `A1`, `Z1`, `L` с динамической ротацией `Z1` и auto-reauth при 401), заголовок `Accept-Encoding: gzip`, кэширование в WordPress Transients с TTL 6 часов (`ZHIRAF_BG_CACHE_TTL`), фоновая синхронизация через WP-Cron (`zhiraf_bg_api_sync_event`), панель настроек в WP Admin (`options-general.php?page=zhiraf-bg-api`), автоматический fallback на `bg-tours-fixtures.json`; `php -l` (**55/55** OK) |
| 4. Frontend / UI Builder (Globe Trekker) | `bc-4b537d97-17b5-55de-a2cc-4161b9a9d592` | **53dbe49** | Шаблон карточки `template-parts/cards/card-tour-price.php` и стили `_tour-cards.scss`: палитра `#FAF8F5` (фон) и `#E87A5D` (акцент), бейджи стран и звезд отелей (`★ 3*–5*`), бейджи питания (`AI`, `BB`, `HB`, `FB`, `AO`), микроиконки гео-пина; юридический дисклеймер 38-ФЗ под каждой ценой с фиксацией курса на 18.10.2026; передача параметров отеля в квиз (`data-quiz-prefill`, `data-cta-context="tour_card_quiz_open"`); цель Яндекс.Метрики `45658173` (`tour_card_quiz_open`) |
| 5. SEO-QA Инженер (aurora-team-qa) | `bc-603126be-b364-5e13-91b3-4f9e1e19d675` | **16ba9ed** | Полный аудит 19 канонических URL; верификация Schema.org JSON-LD (`TravelAgency` / `TouristInformationCenter`, `WebSite`, `BreadcrumbList`, `FAQPage`, `Article`); 0 служебных SEO-утечек по линтеру; отчет [`teya-memory/wp/seo-qa-tour-prices-report.md`](../../teya-memory/wp/seo-qa-tour-prices-report.md) |
| 6. Paint QA (aurora-team-design-guardian) | `bc-0351c800-bd5a-5537-bc28-99dd60a3169c` | **40dd43f** | Playwright захват 19 маршрутов в разрешениях 1440px (desktop) и 390px (mobile) = **38 полностраничных PNG**; верификация рендера карточек туров на главной и посадочных страниц стран; отчет [`teya-memory/wp/paint-qa/paint-qa-report.md`](../../teya-memory/wp/paint-qa/paint-qa-report.md), `paint-evidence.json` (**verdict: pass**) |
| 7. Release Gate Auditor (aurora-team-release-gate) | `bc-0d13e97b-f467-5768-b693-e95d454b93df` | **7ee073e** | Тесты `python3 scripts/teya_release_gate.py --project-root /workspace` (**PASS**, exit code 0, SHA256 `50f71547…`); `bash scripts/zhiraf_copy_lint.sh` (**PASS**, 0 leaks); `php -l` (**55/55** OK); отчет [`teya-memory/wp/release-gate-report.md`](../../teya-memory/wp/release-gate-report.md) |
| 8. Pipeline Log Compiler | `bc-94c818d5-a1a2-51dc-ae0e-c3e6ebacfa4c` | **HEAD** | Сводка этапа §18 в `deliverables/zhiraf-ufa/pipeline-log.md` |

### Детальный анализ компонентов §18

#### 1. Анализ BG API и архитектура фикстур
- **Спецификация:** [`teya-memory/wp/bg-api-spec.md`](../../teya-memory/wp/bg-api-spec.md) и фикстуры `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/bg-tours-fixtures.json` (зеркало в `teya-memory/wp/theme/zhiraf-ufa/data/`).
- **Состав фикстур:** 32 верифицированных пакетных тура на 2 взрослых с вылетом из Уфы (город вылета BG `100510324872`):
  - `home_featured`: **7 туров** для витрины главной страницы (Стамбул, Шарджа, Хургада, Пхукет, Нячанг, Аланья, Сахл-Хашиш).
  - `destinations`: **25 туров** (5 стран × 5 карточек): Турция (`turciya`, ID `100411293179`), ОАЭ (`oae`, ID `100410510097`), Египет (`egipet`, ID `100410000047`), Таиланд (`tailand`, ID `100410000051`), Вьетнам (`vietnam`, ID `100410419078`).
- **Ценообразование в рублях (38-ФЗ):** Стоимость туров берется строго из поля `prices[].RUR` детального метода `partners?action=price` (с учетом актуального курса туроператора на дату среза 18.10.2026, авиабилетов, трансферов и топливных надбавок на 2 взрослых), а не из `action=minprice` (где возвращаются сводные агрегаты или цены в валюте без сборов).
- **Расчет ночей (`duration` vs `o_duration` vs `f7`):**
  - Поле `duration` учитывает полную длительность поездки, включая транзитные перелеты.
  - Поле `o_duration` отражает ночи основного проживания в отеле.
  - Устранена аномалия стыковочных рейсов («2 ночи в Египте за 83 тыс. ₽»): внедрен P0-фильтр отсечения неликвидных туров с `duration < 3` (и `< 5` для дальнемагистральных азиатских направлений), выводящий в интерфейс целочисленное количество ночей проживания.

#### 2. Расширение полезного текста главной страницы
- **Объем контента:** Увеличен с 423 до 793 слов в спецификации `page-content-pack.md` (чистый прозаический текст шаблона — 694–793 слова, полный статический HTML-рендер страницы — 1 205–1 237 слов).
- **FAQ аккордеон:** 8 детальных вопросов и ответов (заказ подбора, отсутствие фиктивных цен «от 15 000 ₽», прямые рейсы из Уфы UFA, перелеты в Азию со стыковками, состав «всё включено», договор по 132-ФЗ, загранпаспорта/документы, защита от спама по 152-ФЗ).
- **Процесс подбора (`#how-it-works`):** 4 прозрачных шага — 1. Заявка или квиз (2 мин); 2. Экспертный расчет (2–4 проверенных варианта); 3. Официальный договор и фискальный чек (офис или онлайн); 4. Сопровождение 24/7 до возвращения в Уфу.
- **Логистика авиасообщения из Уфы:** Разделение на прямые чартеры из аэропорта им. Мустая Карима (UFA) (Турция 4.5–5 ч, ОАЭ 5 ч, Египет 5.5 ч) и регулярные рейсы в Таиланд и Вьетнам с гарантированной стыковкой в Москве, единым билетом и сквозной регистрацией багажа.
- **Гарантии и комплаенс:** ООО «Жираф», реестровый номер РТА 0012822, соблюдение 132-ФЗ («Об основах туристской деятельности») и 152-ФЗ («О персональных данных»), семейное турагентство в Уфе с апреля 2011 года, физический офис: г. Уфа, ул. Юрия Гагарина, д. 74/1, офис 3.
- **Честный плейсхолдер отзывов (`#reviews-placeholder`):** Принципиальный отказ от публикации фейковых заказных отзывов на сайте; открытые кнопки-ссылки на независимые профили с реальными оценками туристов: Яндекс.Карты, 2ГИС и сообщество ВКонтакте (`vk.com/zhiraf_ufa`).
- **Чистота текста:** 0 утечек внутренних служебных меток по `scripts/zhiraf_copy_lint.sh` (полное отсутствие слов «показ», «показов/мес», «slug», «URL-map», «секция», «Wordstat»).

#### 3. Globe Trekker UI карточек туров
- **Дизайн-система:** Соответствие эстетике Globe Trekker — фон `--color-bg: #FAF8F5`, акцентный коралловый цвет `--color-primary: #E87A5D`, контрастный текст `#2b2b2b`, радиусы скругления 24px (`--radius-lg`), мягкие тени при наведении.
- **Информационные бейджи:**
  - Страновой бейдж с эффектом `backdrop-filter: blur(8px)`.
  - Звездность отеля с иконкой `★` (3*, 4*, 5*).
  - Тип питания с кодом и расшифровкой (`AI (Все включено)`, `BB (Завтрак включен)`, `HB`, `FB`, `AO`).
  - Микроиконка гео-пина и наименование курорта.
- **Правовой дисклеймер 38-ФЗ:** Под каждой ценой размещена зеленая точка статуса, динамическая сноска «цена от XX XXX ₽ на 18.10.2026, уточните актуальную стоимость у менеджера» и обязательный дисклеймер «рассчитана по курсу туроператора на 18.10.2026, итоговая стоимость фиксируется при бронировании».
- **Интеграция с квизом и Метрикой:** Кнопка «Подобрать тур» передает предзаполненный контекст (`data-quiz-prefill="Отель, Звезды, Курорт, Вылет, Ночи"`), открывает модальный квиз (`data-cta-context="tour_card_quiz_open"`) и отправляет событие `tour_card_quiz_open` в Яндекс.Метрику (счетчик `45658173`).

#### 4. WP-Cron интеграция inc/bg-api.php
- **HTTP-клиент `Zhiraf_BG_API_Client`:**
  - Сессионная аутентификация через `POST https://login.bgoperator.ru/auth` с получением кук `A1`, `Z1`, `L`.
  - Динамическая ротация куки `Z1` из заголовков `Set-Cookie` при каждом вызове `export.bgoperator.ru`.
  - Автоматическая повторная аутентификация при получении HTTP 401.
  - Строгий заголовок `Accept-Encoding: gzip` с распаковкой ответа.
- **Кэширование и планировщик:**
  - Транзиенты WordPress с TTL 6 часов (`ZHIRAF_BG_CACHE_TTL = 21600 сек`).
  - Событие WP-Cron `zhiraf_bg_api_sync_event` с 6-часовым интервалом `zhiraf_six_hours`.
- **Административный интерфейс:**
  - Страница управления в консоли WP: «Настройки» → «BG API Туры» (`options-general.php?page=zhiraf-bg-api`).
  - Настройка логина/пароля (или переопределение через константы `ZHIRAF_BG_LOGIN` и `ZHIRAF_BG_PWD` в `wp-config.php`).
  - Кнопки ручного принудительного обновления и очистки кэша, отображение статуса сессии и времени последней синхронизации.
- **Отказоустойчивый fallback:**
  - При отсутствии учетных данных, недоступности серверов туроператора или сетевых ошибках система бесшовно возвращает эталонные данные из `data/bg-tours-fixtures.json`.

#### 5. Результаты SEO-QA
- **Аудит 19 канонических URL:** Полная верификация мета-тегов, заголовков H1–H3 и семантических вхождений запросов Уфы («туры из Уфы» 33 489, «горящие туры из Уфы» 3 480, «турагентство Уфа» 2 046, «всё включено из Уфы» 561).
- **Микроразметка Schema.org:**
  - `TouristInformationCenter` / `TravelAgency` / `LocalBusiness` с реквизитами ООО «Жираф», РТА 0012822, адресом в Уфе и телефоном.
  - `WebSite` с `SearchAction`.
  - `BreadcrumbList` в JSON-LD без визуального захламления.
  - `FAQPage` на 14 коммерческих URL (включая 8 Q&A на главной).
  - `Article` для экспертных материалов блога.
- **Исключение фейков:** Полное отсутствие искусственных `AggregateRating` и фальсифицированных отзывов.

#### 6. Результаты Paint QA
- **Покрытие:** 19 маршрутов × 2 вьюпорта (Desktop 1440px, Mobile 390px) = **38 полностраничных скриншотов**.
- **Артефакты:** Сохранены в `deliverables/zhiraf-ufa/paint/` и `teya-memory/wp/paint-qa/` (`paint-evidence.json` со статусом `verdict: pass`, 0 ошибок).
- **Визуальный контроль:** Подтвержден корректный рендер сетки карточек туров, адаптивных табов по странам, типографики Manrope, бейджей и дисклеймеров как на десктопе, так и на мобильных устройствах.

#### 7. Release Gate
- **Статус:** **PASS** (exit code 0).
- **Команда:** `python3 scripts/teya_release_gate.py --project-root /workspace`.
- **Контроль целостности:** Хеш скрипта `teya_release_gate.py` `50f71547469a0ccbf3ca9f11cddf07c42e90dcd44e0ad7cde64dcfc27a5c1a3d` строго совпадает с исходным референсом `07628b4`.
- **Линтеры:** `php -l` (55 файлов темы, 0 ошибок), `bash scripts/zhiraf_copy_lint.sh` (0 утечек).

### Итог §18

| Gate | Статус |
|------|--------|
| BG API Spec & Fixtures (32 тура, RUR) | **✅** (**f5c19e0**) |
| Home copy expansion (793w, FAQ 8, E-E-A-T) | **✅** (**eaaffe7**) |
| BG API Engine (WP-Cron, Transients, Fallback) | **✅** (**7e754d0**) |
| Globe Trekker Tour Cards (#FAF8F5, #E87A5D, 38-ФЗ) | **✅** (**53dbe49**) |
| SEO-QA 19 URL & Schema.org Audit | **✅** (**16ba9ed**) |
| Paint QA (38 screenshots, 1440/390) | **✅** (**40dd43f**) |
| Copy Linter (`zhiraf_copy_lint.sh`) | **PASS** (0 leaks) |
| PHP Linter (`php -l`) | **PASS** (55/55 OK) |
| `teya_release_gate.py` | **PASS** (exit **0**) |
| Deploy | **no** |

- **BG API Specification:** [`teya-memory/wp/bg-api-spec.md`](../../teya-memory/wp/bg-api-spec.md)
- **SEO-QA Tour Prices Report:** [`teya-memory/wp/seo-qa-tour-prices-report.md`](../../teya-memory/wp/seo-qa-tour-prices-report.md)
- **SEO/GEO Verification:** [`teya-memory/wp/seo-geo-verification.md`](../../teya-memory/wp/seo-geo-verification.md)
- **Paint QA Report:** [`teya-memory/wp/paint-qa/paint-qa-report.md`](../../teya-memory/wp/paint-qa/paint-qa-report.md)
- **Paint Evidence:** [`teya-memory/wp/paint-qa/paint-evidence.json`](../../teya-memory/wp/paint-qa/paint-evidence.json)
- **Release Report:** [`teya-memory/wp/release-gate-report.md`](../../teya-memory/wp/release-gate-report.md)

