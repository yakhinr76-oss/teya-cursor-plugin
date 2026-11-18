# Drop-in для локального «parser tourvisor» (Windows / Mac)

Сайт и карты — в `teya-cursor-plugin`. Парсер с Tourvisor API — **на вашем ПК**. Эта папка — то же, что делали для **Турции** и **ОАЭ**: готовые `output/hotels_map_*.json` + сниппет для `hotels_config.json`.

## Скопировать на компьютер

Из клона репо (или после `git pull` на ветке Vietnam PR):

```
teya-cursor-plugin/deliverables/zhiraf-ufa/tourvisor-parser-dropin/
```

В каталог вашего парсера:

| Отсюда | Куда у парсера |
|--------|----------------|
| `output/hotels_map_vietnam.json` | `output/hotels_map_vietnam.json` |
| (при необходимости обновить и другие map) | `output/hotels_map_*.json` |
| `hotels_config.countries.snippet.json` | влить блок `vietnam` в `hotels_config.json` |

**Вьетнам:** `tourvisor_country_id: 16`, ключи webhook `vietnam:1`…`vietnam:25`, поиск по `tourvisor_id` из map (не по названию с сайта).

## Обновить map после правок темы

На любой машине с репо:

```bash
python3 scripts/export_tourvisor_parser_map.py --country vietnam \
  --parser-dropin deliverables/zhiraf-ufa/tourvisor-parser-dropin/output
```

Все curated-страны разом:

```bash
python3 scripts/export_tourvisor_parser_map.py \
  --country turkey --country uae --country egypt --country thailand --country vietnam \
  --parser-dropin deliverables/zhiraf-ufa/tourvisor-parser-dropin/output
```

## Прогон (как раньше)

1. `fetch-by-map` / батч — читает `output/hotels_map_vietnam.json`, в Tourvisor ищет по **id**, не по имени.
2. Сборка `output/tourvisor-prices.json` — в турах должны быть `country_slug` + `zhiraf_id` (join по `hotelcode` ↔ map).
3. `push_tourvisor_prices.py` → `https://b2b-tour.ru/wp-json/zhiraf/v1/update-prices` (см. `deliverables/zhiraf-ufa/TOURVISOR_WEBHOOK_SETUP.md`).

Полная таблица стран: `deliverables/zhiraf-ufa/TOURVISOR-CURATED-PARSER-CHECKLIST.md`.