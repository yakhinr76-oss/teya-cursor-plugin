# AURA Brand Kit Image Prompts

**MCP:** `gpt-image-2` (после OAuth mcp-kv)  
**Negative global:** watermark, logo, readable text, emoji, fake review stars

## Hero collage tiles (если обновление)

Per tile, 4:5 vertical, warm grade:

```
Warm editorial travel photo, [DESTINATION: UAE beach / Turkey resort / Egypt Red Sea], soft morning light, milky ambient tone matching #FAF8F5, subtle terracotta #E87A5D color grade 15%, photorealistic, no people faces prominent, no text, no logo
```

`requires_background_removal: false` (rectangular crop in layout).

## Subscribe background

```
Wide cinematic travel scene, family-friendly resort or coastline, golden hour, space center for text overlay, darkening edges for overlay compatibility, no text, no logo, no watermark
```

Aspect: ~21:9 or crop in CSS `object-fit: cover`.

## Trust block (optional)

Reuse `office-ufa.jpg` / `team-travel.jpg` from registry — **не генерировать** дубликат без need.

## Blog series

См. `AURA_BLOG_COVER_CONCEPT.json` + `AURA_BLOG_COVER_PROMPTS.json`.
