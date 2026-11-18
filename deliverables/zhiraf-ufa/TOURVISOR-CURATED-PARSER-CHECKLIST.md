# Curated: парсер Tourvisor → webhook сайта

Код парсера **не** в `teya-cursor-plugin` (отдельный локальный проект). В репозитории — карты id, экспорт для батча и контракт webhook.

## Быстрый экспорт (после `--match`)

```bash
# Один curated-рынок
python3 scripts/export_tourvisor_parser_map.py --country vietnam \
  --out deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/tourvisor-parser-map.vietnam.json

# Все страны с map-файлами (flat — один массив hotels для цикла парсера)
python3 scripts/export_tourvisor_parser_map.py --flat \
  --out deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/tourvisor-parser-map.export.json
```

После деплоя темы парсер может читать с стейджа:

`https://b2b-tour.ru/wp-content/themes/zhiraf-ufa/data/tourvisor-parser-map.export.json`

## Webhook

| Окружение | URL |
|-----------|-----|
| Стейдж | `POST https://b2b-tour.ru/wp-json/zhiraf/v1/update-prices` |
| Прод | `POST https://zhiraf-ufa.ru/wp-json/zhiraf/v1/update-prices` |

Заголовок: `X-Zhiraf-Token: <секрет из Настройки → Цены Tourvisor>`.

Ключи в `prices`: **`{country_slug}:{zhiraf_id}`** — slug из map-файла, **не** slug URL лендинга (`egipet`, `tailand`, `oae`).

| Curated | `country_slug` в webhook | Landing slug | Tourvisor `countryid` | Map JSON |
|---------|-------------------------|--------------|----------------------|----------|
| Турция | `turkey` | `turciya` | 4 | `turkey-tourvisor-hotels.json` |
| ОАЭ | `uae` | `oae` | 9 | `uae-tourvisor-hotels.json` |
| Египет | `egypt` | `egipet` | 1 | `egypt-tourvisor-hotels.json` |
| Таиланд | `thailand` | `tailand` | 2 | `thailand-tourvisor-hotels.json` |
| **Вьетнам** | **`vietnam`** | **`vietnam`** | **16** | **`vietnam-tourvisor-hotels.json`** |
| **Мальдивы** | **`maldives`** | **`maldivy`** | **8** | **`maldives-tourvisor-hotels.json`** |

### Modsearch: **не больше 5 отелей** в одном запросе

Tourvisor modsearch при **6+ `tourvisor_id` в одном поиске** часто отдаёт **пустой результат**. Договорённость с парсером: **≤ 5 id на запрос**, для 25 curated — **5 запросов** (или цикл по `search_batches` в map).

| Где | Что |
|-----|-----|
| `hotels_config.json` | `modsearch_hotels_per_request`: **5** (см. drop-in snippet) |
| `hotels_map_{country}.json` | поле **`search_batches`**: пачки по 5 с `tourvisor_ids` / `zhiraf_ids` |
| `fetch_prices_by_map.py` | для каждой страны: **цикл по `search_batches`** (или нарезка map на 5), merge в `tourvisor-prices.json` |

Пауза 1–2 с между modsearch — по желанию, если API душит.

### Мальдивы: modsearch из **Москвы**

Пакеты на Мальдивы в Tourvisor для Уфы часто **пустые**; на сайте честно: вылет **из Уфы со стыковкой в Москве** (hero, `geo_answer`).

В парсере для **`fetch_prices_by_map.py --country maldives`**:

- в **`hotels_config.json`** → `countries.maldives` поставить **тот же `departure` / city id, что у других направлений «Из Москвы»** (см. `tourvisor_departure.py` или блок Сейшел/Бали в конфиге);
- в map-файле и webhook поле **`departure_city`: `"Москва"`** (drop-in из teya уже так для MV);
- ключи webhook по-прежнему **`maldives:{zhiraf_id}`** — landing `maldivy` не меняем.

Цена на карточке — **ориентир пакета из Москвы**; в текстах сайта — что смета из Уфы = Москва + стыковка (как bento «Из Москвы»).

## Локальный проект `parser tourvisor` (ваш ПК)

Подробности — **`TOURVISOR_SITE_SYNC.md`** в корне парсера. Типовая цепочка для сайта:

```text
карты id  →  fetch_prices_by_map.py  →  push_tourvisor_prices.py
```

| Скрипт | Роль |
|--------|------|
| `find_hotel_ids.py` | Собрать/обновить `output/hotels_map_*.json` (если map нет — берём drop-in из teya-cursor-plugin) |
| `apply_hotel_aliases.py` | Поправить алиасы в карте (редко) |
| **`fetch_prices_by_map.py`** | Карта → modsearch Tourvisor → **`output/tourvisor-prices.json`** (см. **батчи по 5 отелей** ниже) |
| **`push_tourvisor_prices.py`** | POST на webhook b2b / prod |
| `run_min_prices_5.py` | Пакет минимумов по 5 направлениям (если внутри уже дергает fetch+merge — смотрите `TOURVISOR_SITE_SYNC.md`) |
| `run_egypt_december.py` | Разовый прогон Египта (не для VN/MV) |
| `tourvisor_parser.py` | Открытый поиск → `output/latest_tours.json` (не curated webhook) |

### Подготовка карт (из teya-cursor-plugin)

Готовый drop-in: **`deliverables/zhiraf-ufa/tourvisor-parser-dropin/`**

```bash
# В клоне teya-cursor-plugin (ветка с VN/MV drop-in)
python3 scripts/export_tourvisor_parser_map.py --country vietnam --country maldives \
  --parser-dropin deliverables/zhiraf-ufa/tourvisor-parser-dropin/output
```

Скопировать в **`parser tourvisor/output/`**:

- `hotels_map_vietnam.json`
- `hotels_map_maldives.json`
- (при необходимости обновить turkey/uae/egypt/thailand)

В **`hotels_config.json`** влить блоки `vietnam` и `maldives` из  
`deliverables/zhiraf-ufa/tourvisor-parser-dropin/hotels_config.countries.snippet.json`  
(`tourvisor_country_id`: **16** / **8**, ключи webhook **`vietnam:*`** / **`maldives:*`**).

### Прогон (в корне `parser tourvisor`)

```bash
cd "parser tourvisor"

# 1) Уточнить флаги (версии скриптов у вас локальные)
python fetch_prices_by_map.py --help

# 2) Батч — обычно одна или обе страны (сверьте с TOURVISOR_SITE_SYNC.md)
python fetch_prices_by_map.py --country vietnam
python fetch_prices_by_map.py --country maldives
# или один общий прогон по всем enabled в hotels_config.json:
# python fetch_prices_by_map.py

# 3) Проверить, что в output/tourvisor-prices.json есть vietnam:1 и maldives:1…
# 4) Push на стейдж
python push_tourvisor_prices.py
```

`.env` в корне парсера (см. `TOURVISOR_WEBHOOK_SETUP.md`):

- `ZHIRAF_PRICE_WEBHOOK_URL=https://b2b-tour.ru/wp-json/zhiraf/v1/update-prices`
- `ZHIRAF_TOURVISOR_WEBHOOK_TOKEN=<как в WP → Настройки → Цены Tourvisor>`

Облачный агент **не** имеет Tourvisor login — батч и push только на вашем ПК.

## Вьетнам — что добавить в парсер

1. Подключить map: drop-in `hotels_map_vietnam.json` или theme `data/vietnam-tourvisor-hotels.json` (25 строк, все `tourvisor_id > 0`).
2. В поиск Tourvisor передавать **`tourvisor_id`** из map (страна TV **16**).
3. В webhook для каждого найденного минимума — ключ **`vietnam:1` … `vietnam:25`**, поля `country_slug: "vietnam"`, `zhiraf_id`, `tourvisor_id`, `price_rub`, `nights`, `departure_city: "Уфа"`, …
4. Допускается слать только `tourvisor_id` / `hotelcode` — тема сведёт к `zhiraf_id` через map.

Пример строки webhook:

```json
"vietnam:1": {
  "country_slug": "vietnam",
  "zhiraf_id": 1,
  "tourvisor_id": 73974,
  "hotel_name": "ALMA Resort Cam Ranh 5★",
  "place": "Камрань",
  "price_rub": 312000,
  "nights": 11,
  "adults": 2,
  "departure_city": "Уфа",
  "departure_date": "2026-12-15",
  "meal": "AI"
}
```

## `.env` парсера (фрагмент)

```env
ZHIRAF_PRICE_WEBHOOK_URL=https://b2b-tour.ru/wp-json/zhiraf/v1/update-prices
ZHIRAF_TOURVISOR_WEBHOOK_TOKEN=<секрет с сайта>
# Списки отелей — symlink на theme/data или URL export.json после деплоя
```

Расширьте конфиг батча curated-стран: добавьте блок `vietnam` с теми же правилами, что `egypt` / `thailand` (25 отелей, merge в общий payload перед POST).

## Проверка

1. Админка WP: **Настройки → Цены Tourvisor** — свежая доставка, число строк.
2. На сервере: `data/tourvisor-prices.json` содержит ключи `vietnam:*`.
3. https://b2b-tour.ru/tour/vietnam/ — не круглые редакционные суммы, `price_source: tourvisor` в данных карточки.

## Деплой map + export на b2b

```bash
python3 scripts/deploy_zhiraf_b2b_theme_sftp.py
```

Секреты: `B2B_TOUR_SSH_HOST`, `B2B_TOUR_SSH_USER`, `B2B_TOUR_SSH_PASS`.