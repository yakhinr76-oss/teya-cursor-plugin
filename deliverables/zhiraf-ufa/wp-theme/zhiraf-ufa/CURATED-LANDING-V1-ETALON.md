# Curated destination landing v1 (эталон)

Эталон зафиксирован по странице **Турция** (`/tour/turciya/`, стейдж b2b-tour.ru). Новые страны повторяют структуру и UX; контент и данные — свои.

## Включение в коде

В `inc/destination-landings.php` для slug страны:

```php
'curated_landing_v1'       => true,
'hide_landing_prose'       => false,
'seo_prose_after_faq'      => true,
'curated_resort_chips'     => true,  // опционально: чипы в hero
'curated_kids_section'     => true,  // опционально: блок «с детьми»
'curated_expert_section'   => true,  // опционально: нюансы эксперта
```

Флаг `curated_landing_v1` включает layout v1 **для любого slug** (не только `turciya`). Каталог из 6 отелей появляется, когда `zhiraf_ufa_curated_catalog_hotels( $slug )` возвращает непустой массив (Турция — из PHP; другие страны — через `inc/curated-{slug}-landing.php` + `curated-destination-landing.php`).

## Порядок блоков (сверху вниз)

| # | Блок | Примечание |
|---|------|------------|
| 1 | Hero | H1, lead, badges, опционально resort chips, **одна primary** («Смотреть 6 отелей») + outline (сравнение курортов → модалка), trust-триггеры под кнопками |
| 2 | Curated hub | Pick-карточки курортов/эмиратов (**≤5 в одну строку** на desktop), фильтры, **6 карточек отелей**, fill-слот (при 2 отелях в ряду курорта), полоска под сеткой |
| 3 | Kids | Только если `curated_kids_section` (`kids-turkey.php` / `kids-uae.php` / `kids-egypt.php` …) |
| 4 | Expert nuances | Только если `curated_expert_section` |
| 5 | FAQ | Заголовок «Вопросы по направлению», белая панель-аккордеон (класс `dest-landing-faq--panel`) |
| 6 | SEO prose | Аккордеоны + **сетка курортов** (`.dest-resort-link-hub`) + **журнальный блок гидов** `template-parts/sections/useful-guides.php` (2 или 3 статьи, обложки, теги — не «Без рубрики») |
| 7 | Compare lead | Баннер + кнопка → **модалка** `#turciya-lead-modal` (переименование в `curated-*` — в backlog) |
| — | Sticky mobile | WhatsApp + «Заявка» открывает модалку |

**Не показывается** на curated v1: старый `#dest-lead` с `<select>` канала, conversion stack (battle/season/vibe под каталогом).

## Конверсия и кнопки

- **Solid (primary):** hero «Смотреть 6 отелей», kids «Запросить 3 семейных отеля», нижний баннер «Оставить заявку».
- **Ghost (outline):** все CTA на карточках отелей «Узнать даты / цену», fill-карточка, hero «Сравнить …».
- Заявки с карточек и sticky — `data-turciya-lead-open` / `data-curated-lead-open` → модалка с **MAX / WhatsApp / Telegram**, без прямых `wa.me` на CTA карточек.

## Цены на карточках каталога (Tourvisor + BG)

Единая цепочка для curated hub (как на Турции и ОАЭ):

1. **Tourvisor** — если в `data/tourvisor-prices.json` (webhook парсера) есть строка `{country_slug}:{zhiraf_id}` и совпадение с отелем.
2. **Библио-Глобус** — `zhiraf_ufa_get_hotel_min_tour` / `zhiraf_ufa_get_country_min_tour` (кэш BG API).
3. **Редакционный ориентир** — плейсхолдер в `inc/curated-*-landing.php`, если API пусто.

### База сопоставления отелей (обязательна для TV)

| Файл | Назначение |
|------|------------|
| `data/{country}-tourvisor-hotels.json` | `zhiraf_id` (id из `*_hotels_25.json`) ↔ `tourvisor_id` для батч-поиска и нормализации webhook |
| `data/tourvisor-prices.json` | Живые минимумы с вылетом Уфы; ключи **`turkey:1`**, **`uae:3`**, **`egypt:16`** (country_slug в инбоксе — **не** slug лендинга `egipet`/`oae`) |

Скрипты сборки map (autocomplete Tourvisor):

- `scripts/map_uae_tourvisor_hotels.py`
- `scripts/map_egypt_tourvisor_hotels.py`
- `scripts/map_thailand_tourvisor_hotels.py`
- `scripts/map_vietnam_tourvisor_hotels.py`
- Турция: map уже в репо (`turkey-tourvisor-hotels.json`)

В теме: `zhiraf_ufa_tourvisor_overlay_*_card()` в начале `*_apply_bg_pricing()`; страны с map — `turkey`, `uae`, `egypt`, `thailand`, `vietnam`.

**Парсер:** для каждой новой curated-страны добавить в выгрузку 25 `tourvisor_id` из map-файла; в payload — `country_slug` + `zhiraf_id` (или `tourvisor_id` / `hotelcode` — тема сведёт через map).

## Карта тиража curated v1 по странам

Статусы ориентировочные (стейдж b2b-tour.ru / main темы).

| Приоритет | Лендинг | Slug | Curated v1 | Модуль каталога | JSON 25 отелей | TV map | Kids | Expert | Pick / курорты | Полезные гиды |
|-----------|---------|------|------------|-----------------|----------------|--------|------|--------|----------------|---------------|
| — | **Турция** | `turciya` | ✅ | `turciya-curated-landing.php` | `turkey_hotels_25.json` | ✅ | ✅ | ✅ | 6 курортов | 2 статьи (симметрия) |
| — | **ОАЭ** | `oae` | ✅ | `curated-oae-landing.php` | `uae_hotels_25.json` | ✅ | ✅ | ✅ | 5 эмиратов (ряд) | 2 статьи |
| — | **Египет** | `egipet` | ✅ | `curated-egypt-landing.php` | `egypt_hotels_25.json` | ✅ | ✅ | ✅ | 4 курорта (ряд) + resort pages | 3 статьи (1+2) |
| — | **Таиланд** | `tailand` | ✅ | `curated-thailand-landing.php` | `thailand_hotels_25.json` | ✅ scaffold | ✅ | ✅ | 4 курорта (ряд) | 2 статьи |
| — | **Вьетнам** | `vietnam` | ✅ | `curated-vietnam-landing.php` | `vietnam_hotels_25.json` | ✅ map (TV countryid **16**) | ✅ | ✅ | 4 курорта (ряд) | 3 статьи |
| 3+ | Мальдивы, Шри-Ланка, Индия, … | … | ⬜ | — | есть `*_hotels_25.json` | ⬜ | ⬜ | ⬜ | по контенту | по контенту |

Дочерние **курортные** страницы — отдельно (`inc/resort-landings.php`); для Египта заведены минимальные `makadi-bay`, `sahl-hasheesh` (помимо Хургады/Шарма).

## ACF (тираж на другие страны)

На странице направления или термине таксономии (если подключите taxonomy):

| Поле | Назначение |
|------|------------|
| `resort_compare_lead_title` | H2 нижнего баннера + default title модалки |
| `resort_compare_btn_text` | Текст outline-кнопки в hero |
| `resort_compare_strip_text` | Текст полоски под каталогом |

Примеры: ОАЭ — «Сравним Дубай и Рас-эль-Хайму…»; Египет — «Сравним Шарм-эль-Шейх и Хургаду…».

### ОАЭ (`/tour/oae/`)

- Каталог: **`data/uae_hotels_25.json`**, 6 стартовых + пул; pick-cards по **5 эмиратам**; toolbar — топ + «потребности» (эмираты в pick/chips).
- Kids `kids-uae.php`, expert (налоги, депозиты).
- Hero: primary + chips; сравнение в pick-cards и нижнем баннере.
- Цены: Tourvisor → BG → редакция (`zhiraf_ufa_tourvisor_overlay_uae_card`).
- `blog_links` с `tag` для `useful-guides.php`.

### Египет (`/tour/egipet/`)

- Каталог: **`data/egypt_hotels_25.json`**, стартеры `1,3,4,6,13,21`; pick **4 курорта** (Хургада, Шарм, Макади, Сахл).
- Kids `kids-egypt.php`, expert, `resort_links` на 4 курорта, SEO-ссылки на `/vse-vklyucheno/`, `/goryashchie-tury/`, блог.
- Цены: Tourvisor → BG → редакция (`zhiraf_ufa_tourvisor_overlay_egypt_card`), map `egypt-tourvisor-hotels.json`.
- **3** полезных гида (журнальная сетка).

## Контент-пакет на новую страну (чеклист одного прохода)

1. Запись в `zhiraf_ufa_destination_landing_data()`: флаги curated, FAQ, `resort_links`, hero, `blog_links` (+ явные `tag` для гидов).
2. **`inc/curated-{slug}-landing.php`**: 6 слотов, фильтры, pick-cards, fill labels, `*_apply_bg_pricing` с Tourvisor overlay.
3. Ветки в `inc/curated-destination-landing.php` (catalog, filters, pick, expert, chips).
4. **`data/{country}_hotels_25.json`** — если ещё не в репо.
5. **`data/{country}-tourvisor-hotels.json`** — `python3 scripts/map_{country}_tourvisor_hotels.py --match` (или scaffold + ручные правки).
6. Kids / expert template parts при необходимости.
7. Курорты в `resort-landings.php` (если нужны дочерние URL).
8. Сборка `style.css` / `main.js`, bump версии, деплой, приёмка по чеклисту ниже.
9. **Парсер Tourvisor:** включить 25 id из map в батч; ключи `egypt:{zhiraf_id}` (или свой country_slug).

## Чеклист приёмки (стейдж)

- [ ] Hero: trust-строки, чипы (если включены), primary + outline открывают модалку с правильным заголовком (ACF или дефолт).
- [ ] 6 карточек: ghost CTA, «Честный нюанс», модалка с prefill и `uon_hotel_id`.
- [ ] Под сеткой: полоска + ссылка модалки (не обрезанный текст).
- [ ] Pick-курорты: **≤5 в одну строку** на desktop; fill-слот при 2 отелях в фильтре курорта.
- [ ] Kids / Expert — по флагам.
- [ ] FAQ в белой панели, без дубля «голого» SEO-текста между FAQ и формой.
- [ ] Курорты: карточки `.dest-resort-link-card`.
- [ ] **Полезные гиды:** обложки, осмысленные теги, белые карточки; 3 статьи = layout 1+2, 2 статьи = пара.
- [ ] Цены: при наличии строк в `tourvisor-prices.json` на карточке приоритет TV (см. `price_source` в devtools/данных карточки).
- [ ] Нижний баннер + модалка; sticky «Заявка» → модалка.
- [ ] Версия темы в query `style.css?ver=` обновилась после деплоя.

## Технический долг (после тиража)

- Переименовать `turciya-*` template parts и JS в нейтральные `curated-*` (частично сделано через `curated-destination-landing.php`).
- Универсальный `kids-destination.php` с данными из конфига вместо отдельных файлов по стране.
- Реестр curated-модулей по slug (не разрастание if в `curated-destination-landing.php`).

## Репозиторий и деплой

- Источник правды разработки: `teya-memory/wp/theme/zhiraf-ufa/` (синхронизировать с deliverables при мерже).
- SFTP-деплой: `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` → `scripts/deploy_zhiraf_b2b_theme_sftp.py`.