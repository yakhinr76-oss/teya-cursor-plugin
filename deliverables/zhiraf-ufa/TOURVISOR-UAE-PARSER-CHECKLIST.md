# ОАЭ (и Турция): чеклист парсера Tourvisor → сайт

Карты id лежат в теме. Парсер **не** в этом репозитории (локальный проект / чат *Tourvisor prices integration*).

## 1. Файлы на сайте (после деплоя темы)

| Файл | Назначение |
|------|------------|
| `wp-content/themes/zhiraf-ufa/data/uae-tourvisor-hotels.json` | 25 отелей ОАЭ: `zhiraf_id` → `tourvisor_id` |
| `wp-content/themes/zhiraf-ufa/data/turkey-tourvisor-hotels.json` | 25 отелей Турции |
| `wp-content/themes/zhiraf-ufa/data/tourvisor-prices.json` | Снимок цен (создаёт webhook) |
| `inc/tourvisor-prices.php` | Приём webhook, overlay на карточки |

Обновить карту id ОАЭ из каталога (без логина Tourvisor):

```bash
python3 scripts/map_uae_tourvisor_hotels.py --match
# → deliverables/.../data/uae-tourvisor-hotels.json
```

Экспорт «плоского» конфига для парсера:

```bash
python3 scripts/export_tourvisor_parser_map.py --country uae
python3 scripts/export_tourvisor_parser_map.py --country turkey
```

## 2. Поиск туров (парсер)

- В запрос Tourvisor подставляй **`tourvisor_id`** из карты, не `zhiraf_id`.
- Страна в autocomplete Tourvisor: **ОАЭ `countryid=9`**, Турция `4`.
- Название на сайте и в Tourvisor часто различаются — для людей в payload используй `hotel_name` из **нашего** JSON, id — из **`tourvisor_id`**.

## 3. Webhook на сайт

| Окружение | URL |
|-----------|-----|
| Стейдж (сейчас) | `POST https://b2b-tour.ru/wp-json/zhiraf/v1/update-prices` |
| Прод (когда выкатите ту же тему) | `POST https://zhiraf-ufa.ru/wp-json/zhiraf/v1/update-prices` |

Заголовок:

```http
Content-Type: application/json
X-Zhiraf-Token: <тот же секрет, что на сайте>
```

Секрет сайта:

- **Настройки → Цены Tourvisor** (сгенерировать / сохранить), или
- `define( 'ZHIRAF_TOURVISOR_WEBHOOK_TOKEN', '…' );` в `wp-config.php`.

Без токена endpoint отвечает **401** (на b2b это нормально до настройки).

### Тело JSON

Схема: `zhiraf.tourvisor-prices.v1` (см. `data/tourvisor-prices.example.json`).

Ключи цен:

- Турция: `turkey:1` … `turkey:25`
- ОАЭ: **`uae:1` … `uae:25`** (slug **`uae`**, не `oae`)

Минимальный пример для ОАЭ:

```json
{
  "schema": "zhiraf.tourvisor-prices.v1",
  "source": "tourvisor",
  "currency": "RUB",
  "generated_at": "2026-11-18T08:00:00Z",
  "prices": {
    "uae:1": {
      "country_slug": "uae",
      "zhiraf_id": 1,
      "tourvisor_id": 76213,
      "hotel_name": "Centara Mirage Beach Resort Dubai 4★",
      "place": "Дубай",
      "price_rub": 185000,
      "nights": 7,
      "adults": 2,
      "departure_city": "Уфа",
      "departure_date": "2026-12-10",
      "meal": "AI"
    }
  }
}
```

Допустимо:

- массив строк в `prices: [ … ]` вместо объекта;
- поля `price_rur`, `hotelcode`, `country: "oae"` — тема нормализует в `uae` и `zhiraf_id` по карте.

После успешного POST тема пишет `data/tourvisor-prices.json` и на **/tour/oae/** цена Tourvisor перекрывает BG, если есть совпадение по `uae:{zhiraf_id}`.

## 4. Локально у парсера (`.env`, не в git)

Те же имена, что уже используете для Турции; для стейджа:

```env
ZHIRAF_PRICE_WEBHOOK_URL=https://b2b-tour.ru/wp-json/zhiraf/v1/update-prices
ZHIRAF_TOURVISOR_WEBHOOK_TOKEN=<секрет с сайта>
```

Путь к копии карты (удобно symlink или клон репо):

```text
.../data/uae-tourvisor-hotels.json
.../data/turkey-tourvisor-hotels.json
```

## 5. Проверка после прогона

1. Админка: **Настройки → Цены Tourvisor** — время последней доставки и число строк.
2. На сервере есть `wp-content/themes/zhiraf-ufa/data/tourvisor-prices.json`.
3. Страница https://b2b-tour.ru/tour/oae/ — у отеля с ценой в JSON обновилась сумма (жёсткое обновление в браузере).
4. В теме версия не ниже `1.0.39-uae-tourvisor-map`.

## 6. Деплой темы на b2b

```bash
pip install paramiko   # один раз
python3 scripts/deploy_zhiraf_b2b_theme_sftp.py
```

Нужны секреты: `B2B_TOUR_SSH_HOST`, `B2B_TOUR_SSH_USER`, `B2B_TOUR_SSH_PASS`.

Прод **zhiraf-ufa.ru** — отдельный хост/путь; перед переключением webhook в `.env` парсера смените URL на прод.

## 7. Типичные ошибки

| Симптом | Причина |
|---------|---------|
| Цена не на карточке ОАЭ | Ключ не `uae:N`, или нет `tourvisor_id` в карте, или тема старая |
| Webhook 401 | Неверный / пустой `X-Zhiraf-Token` |
| Парсер «не находит отель» | Ищет по нашему названию вместо `tourvisor_id` из JSON |
| Дубли / чужой отель | Повторить `map_uae_tourvisor_hotels.py --match` и ручная правка строк с `match_note` |