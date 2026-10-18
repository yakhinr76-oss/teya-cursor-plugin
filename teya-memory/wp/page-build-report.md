# Page Build Report — `zhiraf-ufa`

| Поле | Значение |
|------|----------|
| theme_slug | `zhiraf-ufa` |
| theme_path | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` |
| mirror_path | `teya-memory/wp/theme/zhiraf-ufa/` |
| content_ref | `teya-memory/wp/page-content-pack.md` (19 URL, 7 NEW) |
| wordstat_ref | `teya-memory/semantic-core/zhiraf-ufa-2026/wordstat/url-map.md` |
| date | 2026-10-18 |
| generated_by | wp-theme-builder (page-builder worker) |
| branch | `cursor/zhiraf-b2b-site-a2bf` |

## 7 NEW landings (≥100, без Пхукета)

| URL | Template / routing | Status |
|-----|-------------------|--------|
| `/goryashchie-tury/` | `page-goryashchie-tury.php` → `templates/offer-landing.php` | ✅ |
| `/vse-vklyucheno/` | `page-vse-vklyucheno.php` → `templates/offer-landing.php` | ✅ |
| `/tour/turciya/antalya/` | rewrite → `page-tour-resort.php` | ✅ |
| `/tour/egipet/hurgada/` | rewrite → `page-tour-resort.php` | ✅ |
| `/tour/egipet/sharm-el-sheyh/` | rewrite → `page-tour-resort.php` | ✅ |
| `/tour/vietnam/nyachang/` | rewrite → `page-tour-resort.php` | ✅ |
| `/tour/vietnam/fukuok/` | rewrite → `page-tour-resort.php` | ✅ |

**Снято с URL-карты:** `/tour/tailand/phuket/` → 301 на `/tour/tailand/`; контент — секции Пхукет (58+55) и Паттайя (119).

## Rewrite approach

- `inc/tour-routing.php`: `^tour/([^/]+)/([^/]+)/?$` → query vars `zhiraf_tour_country`, `zhiraf_tour_resort`; `template_include` loads `page-tour-resort.php` when `zhiraf_ufa_get_resort_landing()` matches.
- Registry: `inc/resort-landings.php` (**5** курортов: antalya, hurgada, sharm-el-sheyh, nyachang, fukuok); офферы: `inc/offer-landings.php`.
- `inc/redirects.php`: legacy + retired `/tour/tailand/phuket/` → `/tour/tailand/`.
- `after_switch_theme` → `flush_rewrite_rules()` (активация темы на WP).
- Страны `/tour/{slug}/` — `page-{slug}.php` + `inc/destination-landings.php` (секции Kemer, Alanya, Dubai, **Phuket/Pattaya**, s detmi).

## Country page updates (Wordstat 18.10)

| Страна | Секции / дочерние URL |
|--------|------------------------|
| Турция | Кемер, Аланья, с детьми; Анталья → child URL (344) |
| ОАЭ | Дубай (секция) |
| Египет | с детьми; Хургада 853, Шарм 233 |
| Таиланд | **Пхукет 58+55**, **Паттайя 119** — только секции; hub lead **84** |
| Вьетнам | Нячанг 481, Фукуок 386; hub **614** |

## Nav / internal links

- Header fallback: `/goryashchie-tury/`, `/vse-vklyucheno/`
- Footer: колонка «Форматы и курорты» — **без** Пхукета (5 курортов)
- `/tour/` hub: чипы офферов + список курортов
- Preview: удалён `tour-tailand-phuket.html` и маршрут в `wordstat-routes.mjs`

## Semantic core sync

- `06-url-map.csv` — без строки phuket (секции в notes `/tour/tailand/`)
- `05-clusters.csv` — `CL-TH-PKT` → `section-only` на `/tour/tailand/`

## Build QA

| Check | Result |
|-------|--------|
| `npx sass` → `assets/dist/style.css` | ✅ |
| `php -l` all `*.php` in theme | ✅ |
| Deploy zhiraf-ufa.ru | ❌ not run (per task) |
| `teya_release_gate.py` | ❌ not run (per task) |

## Verdict

**success** — 19-page map: 7 NEW resort/offer URLs, Phuket demoted to Thailand sections, Wordstat freqs in PHP, mirror `teya-memory/wp/theme/zhiraf-ufa/`.
