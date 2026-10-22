# AURA Source Analysis — Globe Trekker → Жираф

**Дата:** 2026-10-18  
**Режим:** source-first replication

## Вход

| Источник | Статус |
|----------|--------|
| `teya-memory/00-brief.md` | отсутствует в ветке |
| `teya-memory/site.inv` | отсутствует |
| `teya-memory/research/*` | ✅ (dossier, offers, audience, facts) |
| `teya/reference-globe-trekker.jpg` | отсутствует в workspace |
| `GLOBE-TREKKER-REDESIGN.md` | ✅ |
| Тема `zhiraf-ufa` | ✅ |

## Визуальный DNA референса (Globe Trekker)

1. **Палитра:** молочный фон `#FAF8F5`, акцент терракот `#E87A5D`, текст `#2B2B2B`.
2. **Типографика:** жирный geometric sans (в теме — Manrope 800).
3. **Hero:** асимметричный коллаж 3 фото + декоративная линия полёта; один pill-CTA.
4. **Направления:** горизонтальные табы + **вертикальные** фото-карточки 3:4.
5. **Контент:** editorial blog — одна большая статья + компактный список.
6. **Social proof zone:** в референсе — testimonials; для Жираф — **переинтерпретация** в highlights + ссылки на ВК/контакты (anti-haltura).
7. **Подписка:** full-bleed lifestyle photo, форма по центру.
8. **Футер:** светлый, колонки, соцсети.

## Gap analysis (тема vs референс)

| Элемент | Тема | Действие Aurora |
|---------|------|-----------------|
| Hero collage | ✅ | maintain |
| Dest vertical | ✅ | prefer `dest-*-new.jpg` |
| Trust/highlights | ❌ нет в `front-page.php` | добавить `section--trust` |
| Manrope only | ⚠️ Unbounded в SCSS | убрать Unbounded |
| Container | theme.json 1240 / scss 1280 | unify 1240 |

## MCP

`mcp-kv` needsAuth — новые изображения не генерировались. Registry описывает **существующие** файлы темы.
