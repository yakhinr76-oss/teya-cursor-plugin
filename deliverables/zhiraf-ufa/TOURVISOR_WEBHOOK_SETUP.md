# Tourvisor → сайт (webhook цен)

Сейчас стейджинг: **https://b2b-tour.ru**. Прод **zhiraf-ufa.ru** — отдельно, после проверки на b2b.

Один и тот же секрет должен быть **на сайте** и **в парсере**. В чат и в git его не кладём.

## 1. Сайт (WordPress)

**Вариант A — админка (удобнее)**

1. Войти в админку **b2b-tour.ru**.
2. **Настройки → Цены Tourvisor**.
3. **Сгенерировать новый токен** (показывается один раз) или вставить свой в поле «Свой токен» → **Сохранить**.

**Вариант B — wp-config.php (если без админки)**

```php
define( 'ZHIRAF_TOURVISOR_WEBHOOK_TOKEN', 'ваш-длинный-секрет-минимум-16-символов' );
```

Endpoint (тот же для парсера):

`POST https://b2b-tour.ru/wp-json/zhiraf/v1/update-prices`

Заголовок: `X-Zhiraf-Token: <токен>`  
Тело: JSON из `output/tourvisor-prices.json` (схема — `data/tourvisor-prices.example.json` в теме).

Пока токен не задан, ответ **503** `token_not_configured` — это нормально.

## 2. Парсер (другой репозиторий, `push_tourvisor_prices.py`)

Локально — файл **`.env`** в корне проекта парсера (в `.gitignore`):

```env
ZHIRAF_PRICE_WEBHOOK_URL=https://b2b-tour.ru/wp-json/zhiraf/v1/update-prices
ZHIRAF_TOURVISOR_WEBHOOK_TOKEN=тот_же_токен_что_на_сайте
```

Имена переменных в вашем `push_tourvisor_prices.py` могут отличаться — сверьте с `TOURVISOR_SITE_SYNC.md` в репозитории парсера; смысл тот же: **URL стейджинга + тот же секрет**.

**Cursor Cloud Agent / CI:** те же две переменные в секретах окружения агента парсера (не в репозитории).

## 3. Проверка

```bash
curl -sS -X POST "$ZHIRAF_PRICE_WEBHOOK_URL" \
  -H "Content-Type: application/json" \
  -H "X-Zhiraf-Token: $ZHIRAF_TOURVISOR_WEBHOOK_TOKEN" \
  --data @deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/tourvisor-prices.example.json
```

Ожидается JSON с `"ok": true` и `"count" > 0`.

## 4. Что не делать

- Не присылать токен в чат агенту.
- Не коммитить `.env` и не писать токен в `hotels_config.json` / PHP темы.
- На прод **zhiraf-ufa.ru** не выкатывать, пока не договорились переключить `ZHIRAF_PRICE_WEBHOOK_URL`.