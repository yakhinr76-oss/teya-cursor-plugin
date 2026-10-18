# Release Gate Report — `zhiraf-ufa`

| Field | Value |
|-------|-------|
| command | `python3 scripts/teya_release_gate.py --project-root /workspace` |
| exit_code | **0** |
| status | **PASS** |
| theme_slug | `zhiraf-ufa` |
| gate_script_sha256 | `50f71547469a0ccbf3ca9f11cddf07c42e90dcd44e0ad7cde64dcfc27a5c1a3d` |
| pillow | installed (`Pillow-13.0.0` in runner env) |
| checked_at | 2026-10-18T21:40:00+00:00 |
| git_ref | **51f63c3** (§18 edits release verification: resort photos, single-operator subtitles, paint QA & release gate) |
| deploy | **not run** (`public_site_url` empty) |

## Stdout

```
TEYA RELEASE GATE PASS
```

## Gate Checks Summary (§18)

1. **PHP Syntax Linter (`php -l`)**:
   - Команда: `find deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/ -name "*.php" -exec php -l {} \;`
   - Результат: **PASS** (55/55 PHP файлов синтаксически корректны, 0 ошибок).
2. **Текстовый линтер видимого контента (`zhiraf_copy_lint.sh`)**:
   - Команда: `bash scripts/zhiraf_copy_lint.sh`
   - Результат: **PASS** (0 утечек служебных токенов «показ», «показов/мес», «slug», «URL-map», «секция», «Wordstat», exit code 0).
3. **Hard Release Gate (`teya_release_gate.py`)**:
   - Команда: `python3 scripts/teya_release_gate.py --project-root /workspace`
   - Результат: **PASS (exit code 0)**. Скрипт НЕ модифицирован (SHA256: `50f71547469a0ccbf3ca9f11cddf07c42e90dcd44e0ad7cde64dcfc27a5c1a3d`).
4. **Аудит и подсчёт слов на главной странице (`front-page.php`, `assets.php`, `home.html`)**:
   - Прозаический полезный контент / смысловые блоки: **694–793 слова** (соответствует целевому коридору 600–800 слов).
   - Полный статический рендер главной страницы (с карточками туров, шапкой, футером, аккордеонами FAQ и тизерами блога): **1 205–1 237 слов**.
   - FAQ блок: **8 Q&A пунктов** с JSON-LD разметкой `FAQPage`.
   - Блок отзывов: честный плейсхолдер `#reviews-placeholder` без заказных отзывов со ссылками на Яндекс.Карты, 2ГИС, ВКонтакте.
   - Карточки туров: 6 карточек спецпредложений со строками 38-ФЗ и фиксацией цены на 18.10.2026.

## Artifact fixes (this run)

None — zero gate blockers, Pillow установлен в окружении раннера, все бинарные сигнатуры изображений и media maps валидированы, скрипт `teya_release_gate.py` без модификаций.

## Context

- Branch tip: **51f63c3** (`chore(zhiraf-ufa): update agent_bc_id in capture-all-paint script` + resort photos & subtitles)
- Pipeline Stage: **§18 Globe Trekker Tour Price Engine, Expanded Home Copy, Paint QA & Release Gate PASS (Edits Verified)**
- Theme mirrored: `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` → `teya-memory/wp/theme/zhiraf-ufa/` (55 PHP файлов, BG API ценовой движок с WP-Cron и transients кэшем, `card-tour-price.php`, FAQPage schema JSON-LD, zero SEO leaks)
- Copy quality: 19 канонических URL word counts соответствуют пороговым значениям, проверены фактологические параметры (климат, визы, перелеты из Уфы UFA), `scripts/zhiraf_copy_lint.sh` exit 0
- Paint evidence: **19 маршрутов × 1440px / 390px** (38 PNG) в `deliverables/zhiraf-ufa/paint/` и `teya-memory/wp/paint-qa/` (`paint-evidence.json` verdict: pass)
- SEO/GEO: `teya-memory/wp/seo-geo-verification.md` и `teya-memory/wp/seo-qa-tour-prices-report.md` верифицированы, 19 URL verified, BreadcrumbList, WebSite, FAQPage, TravelAgency JSON-LD, цели Яндекс.Метрики `45658173`, staging `b2b-tour.ru` noindex.
