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

Флаг `curated_landing_v1` включает layout v1 **для любого slug** (не только `turciya`). Каталог из 6 отелей появляется, когда `zhiraf_ufa_curated_catalog_hotels( $slug )` возвращает непустой массив (Турция — из PHP; другие страны — через фильтр `zhiraf_ufa_curated_catalog_hotels`).

## Порядок блоков (сверху вниз)

| # | Блок | Примечание |
|---|------|------------|
| 1 | Hero | H1, lead, badges, опционально resort chips, **одна primary** («Смотреть 6 отелей») + outline (сравнение курортов → модалка), trust-триггеры под кнопками |
| 2 | Curated hub | Pick-карточки курортов, фильтры, **6 карточек отелей**, fill-карточка, полоска под сеткой |
| 3 | Kids | Только если `curated_kids_section` (сейчас шаблон `kids-turkey.php` — контент Турции; для других стран — свой template или ACF) |
| 4 | Expert nuances | Только если `curated_expert_section` |
| 5 | FAQ | Заголовок «Вопросы по направлению», белая панель-аккордеон (класс `dest-landing-faq--panel`) |
| 6 | SEO prose | Аккордеоны + **сетка курортов** (`.dest-resort-link-hub`) + «Полезные гиды» |
| 7 | Compare lead | Баннер + кнопка → **модалка** `#turciya-lead-modal` (переименование в `curated-*` — в backlog) |
| — | Sticky mobile | WhatsApp + «Заявка» открывает модалку |

**Не показывается** на curated v1: старый `#dest-lead` с `<select>` канала, conversion stack (battle/season/vibe под каталогом).

## Конверсия и кнопки

- **Solid (primary):** hero «Смотреть 6 отелей», kids «Запросить 3 семейных отеля», нижний баннер «Оставить заявку».
- **Ghost (outline):** все CTA на карточках отелей «Узнать даты / цену», fill-карточка, hero «Сравнить …».
- Заявки с карточек и sticky — `data-turciya-lead-open` / `data-curated-lead-open` → модалка с **MAX / WhatsApp / Telegram**, без прямых `wa.me` на CTA карточек.

## ACF (тираж на другие страны)

На странице направления или термине таксономии (если подключите taxonomy):

| Поле | Назначение |
|------|------------|
| `resort_compare_lead_title` | H2 нижнего баннера + default title модалки |
| `resort_compare_btn_text` | Текст outline-кнопки в hero |
| `resort_compare_strip_text` | Текст полоски под каталогом |

Примеры: ОАЭ — «Сравним Дубай и Рас-эль-Хайму…»; Египет — «Сравним Шарм-эль-Шейх и Хургаду…».

### ОАЭ (каркас, `/tour/oae/`)

- `curated_landing_v1` включён; каталог из **`data/uae_hotels_25.json`** (`inc/curated-oae-landing.php`): 6 стартовых слотов + пул 25, pick-cards по 5 эмиратам (как у Турции), в toolbar — стартовый топ + «потребности» (эмираты только в pick-cards / hero chips).
- Включены `curated_kids_section` (`kids-uae.php`), `curated_expert_section` (налоги, депозиты, трансферы), `curated_resort_chips` в hero.
- Hero ОАЭ: только primary + chips (`curated_hero_show_compare` / `curated_hero_show_trust` = false); сравнение — в pick-cards и нижнем баннере.
- Дефолты сравнения: `curated_compare_btn_default`, `curated_compare_lead_default`, `curated_hero_trust`, `resort_links` (URL эмиратов — на `/tour/oae/` до появления курортных дочерних страниц).
- Следующий шаг: 6 карточек из `data/uae_hotels_25.json` + ACF на странице ОАЭ в WP.

## Контент-пакет на новую страну

1. Запись в `zhiraf_ufa_destination_landing_data()` + `page-{slug}.php` (как у `page-turciya.php`).
2. FAQ, `resort_links`, hero, meta description.
3. Массив 6 отелей (слоты, honest nuance, uon, prefill) — пока через фильтр или новый `inc/curated-{slug}.php`.
4. ACF тексты сравнения курортов.
5. Деплой темы → приёмка по чеклисту ниже → прод.

## Чеклист приёмки (стейдж)

- [ ] Hero: trust-строки, чипы (если включены), primary + outline открывают модалку с правильным заголовком (ACF или дефолт).
- [ ] 6 карточек: ghost CTA, «Честный нюанс», модалка с prefill и `uon_hotel_id`.
- [ ] Под сеткой: полоска + ссылка модалки (не обрезанный текст).
- [ ] Kids / Expert — по флагам.
- [ ] FAQ в белой панели, без дубля «голого» SEO-текста между FAQ и формой.
- [ ] Курорты: карточки `.dest-resort-link-card`, hover border `#C85A32`.
- [ ] Нижний баннер + модалка; sticky «Заявка» → модалка.
- [ ] Версия темы в query `style.css?ver=` обновилась после деплоя.

## Технический долг (после тиража)

- Переименовать `turciya-*` template parts и JS в нейтральные `curated-*`.
- Универсальный `kids-destination.php` вместо `kids-turkey.php`.
- Данные каталога не только в `turciya-curated-landing.php`, а реестр по slug.

## Репозиторий и деплой

- Источник правды разработки: `teya-memory/wp/theme/zhiraf-ufa/`.
- SFTP-деплой: `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` → `scripts/deploy_zhiraf_b2b_theme_sftp.py`.