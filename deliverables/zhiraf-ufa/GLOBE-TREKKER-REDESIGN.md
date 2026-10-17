# Globe Trekker redesign — отчёт

**Дата:** 2026-10-17  
**PR:** https://github.com/yakhinr76-oss/teya-cursor-plugin/pull/2

## Скриншоты (локальный preview, залейте тему и перепроверьте на b2b-tour.ru)

| Viewport | Артефакт |
|----------|----------|
| Desktop 1440 | `/opt/cursor/artifacts/screenshots/zhiraf-desktop-1440.png` |
| Mobile 390 | `/opt/cursor/artifacts/screenshots/zhiraf-mobile-390.png` |

Preview-страница (без WP): `deliverables/zhiraf-ufa/preview/home.html`

## Follow-up после [Live QA b2b-tour.ru](bc-5c27e2d5-6bb4-508c-8de0-81c186e96d68)

- **Квиз:** исправлена валидация шагов (`div` не поддерживает `checkValidity()`), сброс на шаг 1 при открытии, `finally` на AJAX submit.
- **Статьи:** опубликовать `python3 scripts/publish_zhiraf_excalibur_posts.py` (нужен `B2B_TOUR_WP_APP_PASSWORD` в среде агента).

## Сверка с референсом Globe Trekker

| Блок | Статус |
|------|--------|
| Manrope 800, без Playfair | ✅ |
| Контейнер ~1240px | ✅ |
| Hero: текст + 3 фото, карта/пунктир/самолёт | ✅ (SVG) |
| H1 акцент + подчёркивание | ✅ |
| Одна терракотовая pill-кнопка в hero | ✅ |
| Табы направлений + 4 вертикальные карточки | ✅ (5-я по фильтру) |
| Статьи: большая + 3 мини | ✅ |
| Отзывы без PLACEHOLDER | ✅ → CTA на ВК/контакты |
| Подписка full-bleed фото | ✅ |
| Светлый футер, колонки, соцсети | ✅ |
| Cookie — угол | ✅ |
| Тех. метки BT/Excalibur | ✅ убраны |

## Файлы темы для заливки на хостинг

```
front-page.php
footer.php
inc/assets.php
inc/enqueues.php
assets/dist/style.css
assets/dist/main.js
theme.json
assets/images/* (обновлённые dest/hero/subscribe/blog)
```
