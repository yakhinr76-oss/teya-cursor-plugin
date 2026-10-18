# AURA Replication TODO

## P0 — композиция главной

- [ ] Добавить секцию `trust-social` (highlights + CTA ВК/телефон/офис) между editorial и form — blueprint `front-page:trust-social`
- [ ] Унифицировать `--container-max` 1240px в SCSS с `theme.json`
- [ ] Заголовки: Manrope 800 only (удалить Unbounded из `--font-head`)
- [ ] Dest images: использовать `dest-*-new.jpg` везде для единого кропа

## P1 — внутренние страницы test build

- [ ] `/tour/` — визуально связать chip-filter с tab-btn стилем главной
- [ ] `/blog/` — card grid 16:9 + blog cover series lock
- [ ] `/kontakty/` — hero с `office-ufa.jpg` если нет

## P2 — assets (MCP KV после OAuth)

- [ ] Сгенерировать 6 обложек Excalibur 16:9 по `AURA_BLOG_COVER_PROMPTS.json`
- [ ] Style anchor `assets/blog-cover-style-anchor.png` (optional)
- [ ] 2-step cutout pipeline только если нужны overlap cutouts (сейчас не требуется)

## Запрещено

- [ ] Watermark логотипа на секциях
- [ ] Фейковые отзывы / звёзды
- [ ] Писать «assets downloaded» без файлов на диске

## Blockers

| ID | Описание |
|----|----------|
| MCP-KV-AUTH | `mcp-kv` needsAuth — gpt-image-2 недоступен |
| REF-IMAGE-MISSING | `teya/reference-globe-trekker.jpg` нет в repo |
