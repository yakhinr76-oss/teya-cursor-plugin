# Run brief — semantic core «Жираф» Уфа

| Поле | Значение |
|------|----------|
| Run slug | `zhiraf-ufa-2026` |
| Дата прогона | 2026-10-18 |
| Агент | yadryshko-semantic-core |
| Регион Wordstat | Уфа (id 172 — уточнить при MCP) |
| Цель | CONVERSION-FIRST: квиз + телефон |
| Направления P0 | Турция, ОАЭ, Египет, Таиланд, Вьетнам |

## Входы

- `teya-memory/00-brief.md`, `site.inv` (восстановлены в этом прогоне)
- `teya-memory/research/*`
- Excalibur: `deliverables/zhiraf-ufa/excalibur-articles/articles/*`

## Ограничения

- Wordstat MCP (`mcp-kv`): **needsAuth** → частотности `not_available`, 0 успешных вызовов
- Без цен «от», без выдуманных отзывов (anti-haltura)

## Выход

Полный набор файлов run-folder + fragment `teya-memory/fragments/core.md`.
