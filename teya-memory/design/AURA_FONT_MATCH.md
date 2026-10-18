# AURA Font Match — кириллица

**Skill:** `aura-cyrillic-google-fonts`

## Источник

Globe Trekker: bold geometric sans, tight tracking, без serif display.

## Выбранная пара

| Роль | Шрифт | Кириллица |
|------|-------|-----------|
| Display / H1–H4 | **Manrope** 800 | ✅ Cyrillic |
| Body / UI | **Manrope** 400–600 | ✅ |

**Пара:** Manrope + Manrope (editorial travel, русский B2C).

## Подключение

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
```

Тема также использует локальный variable woff2: `assets/fonts/manrope/Manrope-Cyrillic-Variable.woff2` — предпочтительно для production (меньше запросов).

## Отклонения в текущей теме

- `main.scss` задаёт `--font-head: 'Unbounded'` — **не соответствует** GLOBE-TREKKER-REDESIGN («Manrope 800, без Playfair»). Aurora: заменить на Manrope 800.

## Запрещено

- Playfair для русских заголовков без проверки
- Inter как default body
- Space Grotesk / Plus Jakarta для RU
