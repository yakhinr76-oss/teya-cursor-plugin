# AURA Font Match — кириллица

**Skill:** `aura-cyrillic-google-fonts`

## Источник

Globe Trekker: geometric sans для UI и секций сайта; **editorial blog** — serif display на заголовках статей (H1–H3), тёплый журнальный контраст с Manrope body.

## Выбранные пары

| Роль | Шрифт | Кириллица | Scope |
|------|-------|-----------|--------|
| Site display / H1–H2 секций | **Manrope** 800 | ✅ Cyrillic | hero, section-title, tour, footer |
| Body / UI / meta sans | **Manrope** 400–700 | ✅ | весь сайт + тело статей |
| Blog display H1–H3 | **Cormorant Garamond** 600–700 | ✅ Cyrillic | только блог (см. blueprint selectors) |

**Пара editorial:** Cormorant Garamond + Manrope (skill каталог «Editorial / Журнал»).

## Файлы в теме (documented paths)

| Гарнитура | Путь в теме |
|-----------|-------------|
| Manrope variable | `assets/fonts/manrope/Manrope-Cyrillic-Variable.woff2` |
| Cormorant SemiBold | `assets/fonts/cormorant-garamond/CormorantGaramond-SemiBold.woff2` |
| Cormorant Bold | `assets/fonts/cormorant-garamond/CormorantGaramond-Bold.woff2` |

CSS variable: `--font-sans` (Manrope), `--font-blog-display` (Cormorant Garamond).

**Cormorant:** подключать только через `@font-face` в SCSS — **без** `fonts.googleapis.com` для blog display (taste gate).

**Manrope:** локальный variable предпочтительнее одного woff2; Google CDN допустим как fallback до self-host в enqueues.

## Отклонения в текущей теме

- `main.scss` задаёт `--font-head: 'Unbounded'` — **не соответствует** GLOBE-TREKKER-REDESIGN. Aurora: Manrope 800 для `--font-head`.
- Блог без serif и без `blog-meta-row` — см. `AURA_BLOG_WIREFRAMES_WP-THEME-BUILDER.md`.

## Запрещено

- Playfair
- Google CDN для Cormorant в production blog headings
- Serif на H1 hero главной или на карточках направлений
- Inter / Space Grotesk как default body
