# AURA Blog Cover System — техника

Контракт: `teya/shared/blog-cover-mcp-contract.md`

## Формат

- **16:9**, min width 1920px export
- WebP/JPEG в теме: `assets/images/blog-cover-{slug}.jpg`

## MCP pipeline (когда mcp-kv доступен)

1. `gpt-image-2` с prompt = `global_prompt_prefix` + `topic_scene_descriptor` + `global_prompt_suffix`
2. Для overlap cutout (не требуется для `editorial_photo` flat) → `recraft_remove_background`
3. Запись в `AURA_ASSET_REGISTRY.json` с URL

## Style anchor (optional)

`teya-memory/design/assets/blog-cover-style-anchor.png` — первый approved cover; последующие сверять визуально (grain, grade).

## WordPress

Featured image per post; fallback `blog-cover-01.jpg`.

## QA серии

- Одна color temperature across 6 topics
- No text rendered in image
- No watermark / logo
- Alt from `AURA_BLOG_COVER_PROMPTS.json`

## Blocker

**mcp-kv needsAuth** — генерация отложена; prompts готовы.
