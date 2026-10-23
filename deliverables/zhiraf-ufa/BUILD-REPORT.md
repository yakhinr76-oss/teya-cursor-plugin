# ООО «Жираф» — сборка Teya Phase 1 (тест b2b-tour.ru)

**Дата:** 2026-10-17  
**Пайплайн:** Research → Core/AURA → Aurora → Excalibur (1 статья) → Release Gate (локально) → QA (частично)

## Секрет деплоя

`B2B_TOUR_WP_APP_PASSWORD` **не был задан** в среде агента → **деплой на b2b-tour.ru не выполнялся**. Тема и bootstrap-скрипт готовы в репозитории.

## Что сделано

| Этап | Статус |
| --- | --- |
| Teya Research (dossier, competitors, fact-bank) | ✅ |
| Ядрышко semantic-core `zhiraf-ufa-2026` | ✅ |
| AURA design pack (Globe Trekker → молочный/терракот) | ✅ |
| Aurora Team maps (контент, SEO, noindex staging, формы 152/38) | ✅ |
| WP-тема `zhiraf-ufa` (64 файла) | ✅ в `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` |
| Excalibur BT-01 `kak-kupit-tur-v-ufe` | ✅ в `deliverables/zhiraf-ufa/excalibur-articles/` |
| Квиз подбора тура + формы → `office@zhiraf-ufa.ru` | ✅ |
| U-ON CRM stub (`inc/class-uon-crm.php`) | ✅ |
| Cookie-баннер, политики ПДн/cookies | ✅ шаблоны |
| Локальные шрифты Playfair + Manrope | ✅ |
| Яндекс.Метрика заглушка (без ID) | ✅ |
| Google Analytics | ❌ не ставится |
| 301 legacy URL (`/kontacty`, `/rasskazy`, …) | ✅ `inc/redirects.php` |
| noindex на b2b-tour.ru | ✅ meta в теме + `blog_public=0` в bootstrap |

## Планируемые страницы на b2b-tour.ru (после деплоя)

| URL | Назначение |
| --- | --- |
| `/` | Главная (коллаж, направления, блог-слот, отзывы-плейсхолдеры, подписка) |
| `/o-nas/` | О компании |
| `/kontakty/` | Контакты, реквизиты |
| `/tour/` | Каталог направлений |
| `/blog/` | Архив блога |
| `/kak-kupit-tur-v-ufe/` | Статья Excalibur (пост) |
| `/politika-konfidenczialnosti/` | 152-ФЗ |
| `/politika-cookies/` | Cookies |

**Редиректы:** `/kontacty` → `/kontakty/`, `/rasskazy` → `/blog/` (см. `old-url-map.csv`).

## Не сделано / блокеры

- Активация темы и публикация страниц на **b2b-tour.ru** (нет Application Password).
- WP Media import, paint-evidence на live URL, Design Guardian live screenshots.
- Остальные P0-статьи Excalibur (BT-02…BT-06) — вне одной статьи Phase 1.
- MCP KV (Wordstat / генерация картинок) — needsAuth; использованы stock-фото с `assets/images/SOURCES.md`.

## Деплой (после секрета)

1. Загрузить папку темы на хостинг: `wp-content/themes/zhiraf-ufa/`
2. `export B2B_TOUR_WP_APP_PASSWORD='…'`
3. `python3 scripts/bootstrap_zhiraf_b2b_wp.py --project-root /workspace`
4. В админке WP: активировать тему **Zhiraf Ufa**, назначить главную и страницу записей `/blog/`.

## QA (локально / код-ревью)

| Проверка | Результат |
| --- | --- |
| Два неотмеченных чекбокса под формами | ✅ `zhiraf_ufa_consent_fields()` |
| 38-ФЗ текст на маркетинговом чекбоксе | ✅ |
| Нет Google Fonts CDN | ✅ |
| Нет GA | ✅ |
| Отзывы не выдуманы | ✅ PLACEHOLDER в шаблоне |
| zhiraf-ufa.ru не трогали | ✅ |
