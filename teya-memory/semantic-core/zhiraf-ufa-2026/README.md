# Semantic core — Жираф Уфа (`zhiraf-ufa-2026`)

**Дата:** 2026-10-18 | **Режим:** CONVERSION-FIRST | **Wordstat:** API v2 Уфа **172** (45 calls)

## С чего начать

1. **`index.html`** — сводный отчёт  
2. **`wordstat/url-map.md`** — итоговая URL-карта с частотностями  
3. **`06-url-map.csv`** — URL и мета для внедрения  
4. **`11-blog-topics.md`** — 6 тем Excalibur P0  
5. **`07-content-briefs.md`** — брифы страниц  
6. **`10-todo.md`** — задачи по ролям  

## Ключевые цифры

- **1754** included keywords (2482 unique clean, 3043 raw rows)  
- **24** clusters, **19** URLs в карте (**7 NEW**)  
- P0 направления: Турция, ОАЭ, Египет, Таиланд, Вьетнам  
- 6 blog topics = slug в `excalibur-articles/`

## Сборка отчётов

```bash
python vendor/yadryshko/scripts/build_core_html_report.py teya-memory/semantic-core/zhiraf-ufa-2026
python vendor/yadryshko/scripts/build_semantic_core_xlsx.py teya-memory/semantic-core/zhiraf-ufa-2026
```
