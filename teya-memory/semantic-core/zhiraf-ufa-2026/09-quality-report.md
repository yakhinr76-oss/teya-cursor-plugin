# Quality report — zhiraf-ufa-2026

## Wordstat API smoke test (обязательный)

| Параметр | Значение |
|----------|----------|
| Endpoint | `POST https://searchapi.api.cloud.yandex.net/v2/wordstat/topRequests` |
| HTTP status | **200** |
| `key_configured` | **yes** (`YANDEX_WORDSTAT_API_KEY`, не логировался) |
| Test body | phrase `туры из уфы`, regionId **172**, numPhrases **5** |
| Sample phrases in response | **5** (results) + associations |
| MCP `mcp-kv` | **needsAuth** (не использовался; прямой API) |

## Coverage summary

| Метрика | Значение |
|---------|----------|
| Wordstat calls (successful) | **45** |
| Raw rows (`03-wordstat-raw.csv`) | **3043** |
| Keywords unique / included clean | **2482** / **1754** |
| Clusters | **24** |
| URL map rows (страницы) | **19** (7 NEW commercial) |
| Blog topics (P0 Excalibur) | 6 |
| P0 commercial landings | home + catalog + 5 направлений + offers |

## Data limitations

- Частотности — только из Wordstat API v2 (регион 172); без GSC/Вебмастера.
- SERP не снимался live; `serp_check_status=not_checked`.
- В clean отфильтрованы association-шум и off-topic (турецкий язык/сериалы, МФЦ и т.п.).
- Порог ≥100: `/tour/tailand/phuket/` **снят** с отдельного URL (макс. коммерческий запрос **58**); контент — секция на `/tour/tailand/`.

## Quality checklist

| Проверка | Статус |
|----------|--------|
| Slug блога = Excalibur folders | ✅ |
| 5 направлений в url-map | ✅ |
| CONVERSION-FIRST на лендингах | ✅ |
| Нет выдуманных частотностей | ✅ |
| Anti-haltura (цены/отзывы) | ✅ |
| index.html без битых замен | проверить после build |

## Main risks

1. Часть association в сырье остаётся в `03` для аудита; в `04` не попадает в include.
2. Низкий спрос на Таиланд/ОАЭ из Уфы — опора на конверсию и квиз, не на объём SEO.
3. Анталья: опора на totalCount **344** по seed (мало прямых top_requests с точной фразой).

## Completeness

Ядро **готово для AURA и контент-внедрения** с реальными частотностями Wordstat Уфа 172.
