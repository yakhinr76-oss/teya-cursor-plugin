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

## Вьетнам — что добавить в парсер

1. Подключить map: theme `data/vietnam-tourvisor-hotels.json` или `data/tourvisor-parser-map.vietnam.json` (25 строк, все `tourvisor_id > 0`).
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