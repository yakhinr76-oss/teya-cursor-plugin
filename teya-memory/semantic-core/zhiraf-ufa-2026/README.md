# Semantic core — Жираф Уфа (`zhiraf-ufa-2026`)

**Дата:** 2026-10-18 | **Режим:** CONVERSION-FIRST | **Wordstat:** degraded (0 calls)

## С чего начать

1. **`index.html`** — сводный отчёт  
2. **`06-url-map.csv`** — URL и мета  
3. **`11-blog-topics.md`** — 6 тем Excalibur P0  
4. **`07-content-briefs.md`** — брифы страниц  
5. **`10-todo.md`** — задачи по ролям  

## Ключевые цифры

- 51 keyword (clean), 17 clusters, 16 URLs в карте  
- P0 направления: Турция, ОАЭ, Египет, Таиланд, Вьетнам  
- 6 blog topics = slug в `excalibur-articles/`

## Ограничение

Частотности `not_available` до авторизации MCP `mcp-kv` Wordstat.

## Сборка отчётов

```bash
python vendor/yadryshko/scripts/build_core_html_report.py teya-memory/semantic-core/zhiraf-ufa-2026
python vendor/yadryshko/scripts/build_semantic_core_xlsx.py teya-memory/semantic-core/zhiraf-ufa-2026
```
