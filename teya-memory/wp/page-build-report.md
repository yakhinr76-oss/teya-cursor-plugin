# Page Build Report — `zhiraf-ufa`

| Поле | Значение |
|------|----------|
| theme_slug | `zhiraf-ufa` |
| theme_path | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` |
| mirror_path | `teya-memory/wp/theme/zhiraf-ufa/` |
| content_ref | `teya-memory/wp/page-content-pack.md` (20 URL, 8 NEW) |
| date | 2026-10-18 |
| generated_by | wp-theme-builder (bc-78870dfb) |
| branch | `cursor/zhiraf-b2b-site-a2bf` |

## 8 NEW landings

| URL | Template / routing | Status |
|-----|-------------------|--------|
| `/goryashchie-tury/` | `page-goryashchie-tury.php` → `templates/offer-landing.php` | ✅ |
| `/vse-vklyucheno/` | `page-vse-vklyucheno.php` → `templates/offer-landing.php` | ✅ |
| `/tour/turciya/antalya/` | rewrite → `page-tour-resort.php` | ✅ |
| `/tour/egipet/hurgada/` | rewrite → `page-tour-resort.php` | ✅ |
| `/tour/egipet/sharm-el-sheyh/` | rewrite → `page-tour-resort.php` | ✅ |
| `/tour/tailand/phuket/` | rewrite → `page-tour-resort.php` | ✅ |
| `/tour/vietnam/nyachang/` | rewrite → `page-tour-resort.php` | ✅ |
| `/tour/vietnam/fukuok/` | rewrite → `page-tour-resort.php` | ✅ |

## Rewrite approach

- `inc/tour-routing.php`: `^tour/([^/]+)/([^/]+)/?$` → query vars `zhiraf_tour_country`, `zhiraf_tour_resort`; `template_include` loads `page-tour-resort.php` when `zhiraf_ufa_get_resort_landing()` matches.
- Registry: `inc/resort-landings.php` (6 курортов); офферы: `inc/offer-landings.php`.
- `after_switch_theme` → `flush_rewrite_rules()` (активация темы на WP).
- Страны `/tour/{slug}/` — по-прежнему `page-{slug}.php` + расширенный `inc/destination-landings.php` (секции Kemer 82, Alanya 58, Pattaya 49, s detmi 20, Dubai 87).

## Country page updates

| Страна | Below-threshold sections |
|--------|-------------------------|
| Турция | Кемер 82, Аланья 58, с детьми 20; Анталья → child URL |
| ОАЭ | Дубай 87 |
| Египет | с детьми 20; таблица Хургада/Шарм |
| Таиланд | Паттайя 49; Пхукет → child URL |
| Вьетнам | ссылки Нячанг 193, Фукуок 183 |

## Nav / internal links

- Header fallback: `/goryashchie-tury/`, `/vse-vklyucheno/`
- Footer: колонка «Форматы и курорты», Вьетнам в направлениях
- `/tour/` hub: чипы офферов + список курортов
- Главная: секция `section--offers-wordstat`

## Build QA

| Check | Result |
|-------|--------|
| `npx sass` → `assets/dist/style.css` | ✅ |
| `php -l` all `*.php` in theme | ✅ **49 files**, 0 errors |
| Deploy zhiraf-ufa.ru | ❌ not run (per task) |
| `teya_release_gate.py` | ❌ not run (per task) |

## Files added (theme)

- `inc/tour-routing.php`, `inc/resort-landings.php`, `inc/offer-landings.php`
- `page-goryashchie-tury.php`, `page-vse-vklyucheno.php`, `page-tour-resort.php`
- `templates/offer-landing.php`, `templates/resort-landing.php`

## Verdict

**success** — Phase 1 page-build: 8 NEW URLs + country sections + linking; mirror synced to `teya-memory/wp/theme/zhiraf-ufa/`.
