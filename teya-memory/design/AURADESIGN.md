---
name: "Жираф Уфа — Globe Trekker Replication"
version: "1.0.0"
updated: "2026-10-18"
source_mode: "reference-first"
reference:
  label: "Globe Trekker travel editorial"
  colors_anchor: ["#FAF8F5", "#E87A5D"]
  image: "teya/reference-globe-trekker.jpg (missing — decomposed from theme + GLOBE-TREKKER-REDESIGN)"
project:
  brand: "Турагентство «Жираф», Уфа"
  theme_slug: "zhiraf-ufa"
  domains:
    production: "zhiraf-ufa.ru"
    staging: "b2b-tour.ru"
colors:
  primary: "#E87A5D"
  primary-hover: "#D4684F"
  on-primary: "#FFFFFF"
  secondary: "#2B2B2B"
  on-secondary: "#FFFFFF"
  background: "#FAF8F5"
  on-background: "#2B2B2B"
  surface: "#F5F2ED"
  surface-lowest: "#FFFFFF"
  outline: "#E8E3DC"
  outline-variant: "#E8E3DC"
  muted: "#666666"
  error: "#DC2626"
  on-error: "#FFFFFF"
typography:
  font_import: "https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
  display-lg:
    fontFamily: "Manrope"
    fontSize: "clamp(2.25rem, 5vw, 4rem)"
    fontWeight: "800"
    lineHeight: "1.15"
    letterSpacing: "-0.03em"
  headline-md:
    fontFamily: "Manrope"
    fontSize: "2.5rem"
    fontWeight: "800"
    lineHeight: "1.2"
    letterSpacing: "-0.02em"
  body-md:
    fontFamily: "Manrope"
    fontSize: "17px"
    fontWeight: "400"
    lineHeight: "1.6"
  label-sm:
    fontFamily: "Manrope"
    fontSize: "12px"
    fontWeight: "700"
    lineHeight: "1.2"
    letterSpacing: "0.05em"
  accent-italic:
    fontFamily: "Manrope"
    fontStyle: "italic"
    fontWeight: "800"
    color: "{colors.primary}"
rounded:
  sm: "8px"
  DEFAULT: "12px"
  md: "16px"
  lg: "24px"
  full: "9999px"
spacing:
  base: "8px"
  section-y: "80px"
  gutter: "20px"
  container-max: "1240px"
  header-h: "80px"
shadows:
  collage-tile: "0 20px 40px rgba(0,0,0,0.10)"
  card-hover: "none"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    rounded: "{rounded.full}"
    typography: "{typography.body-md}"
    fontWeight: "700"
    padding: "12px 24px"
  dest-card:
    rounded: "{rounded.lg}"
    aspectRatio: "3/4"
    captionGradient: "linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 60%)"
  subscribe-overlay:
    background: "rgba(0,0,0,0.5)"
motion:
  duration-fast: "0.2s"
  duration-medium: "0.3s"
  easing: "ease"
  reveal: "opacity + translateY 24px, stagger 0.1s"
assets:
  policy: "MCP KV for new production images; existing theme files in AURA_ASSET_REGISTRY.json"
  watermark_logo: "FORBIDDEN"
---

# AURADESIGN — Жираф × Globe Trekker

## Source Replication Doctrine

Источник — **Globe Trekker**: тёплый «молочный» фон, терракотовый акцент, hero с **коллажем из 3 фото** и пунктирной траекторией, **вертикальные карточки направлений** с табами, **editorial-блок блога** (крупная + список), зона **highlights/социального доверия** (у нас — факты + CTA на ВК/телефон, без выдуманных отзывов), **подписка на full-bleed фото**, светлый футер.

Не улучшать референс до generic SaaS. Не добавлять watermark логотипа на слайды/секции.

Референс-файл `teya/reference-globe-trekker.jpg` в workspace отсутствует; декомпозиция выполнена по `GLOBE-TREKKER-REDESIGN.md`, preview и текущей теме.

## Composition Lock

См. `AURA_COMPOSITION_LOCK.json`. Ключевое:

- Контент **1240px** (`theme.json` layout.contentSize), padding 20px.
- Hero: 50/50 grid, коллаж absolute с z-index choreography.
- Destinations: 5 колонок desktop, aspect 3:4, hover scale фото.
- Editorial: 1.5fr / 1fr.
- Subscribe: overlay 50%, centered form, pill inputs.

## Philosophy & Vibe

**Характер:** семейное турагентство Уфы, подача как **travel-журнал** — воздух, крупная типографика Manrope 800, фото как доказательство направлений, один уверенный CTA-цвет.

**Эмоция:** тепло, спокойная уверенность, «можно доверить отпуск», без агрессивных прайс-бордов.

**Не:** neon tech, dark mode, brutal borders, gaming 3D, фейковые звёзды отзывов.

## Color Guidance

| Токен | Где | Запрет |
|-------|-----|--------|
| `#FAF8F5` | body, чередование секций | не делать чисто #FFF весь скролл |
| `#E87A5D` | primary CTA, категории, акцент в H1, SVG полёта | не заливать большие фоны |
| `#2B2B2B` | текст | не использовать как фон hero |
| `#F5F2ED` | sand blocks (о нас, trust) | — |

## Typography Hierarchy

- **Display/H1:** Manrope 800, negative tracking, italic accent word допустим.
- **H2 section:** 2.5rem, 800.
- **Body:** 17px Manrope 400, muted `#666` для вторичного текста.
- **Кириллица:** только пары из `aura-cyrillic-google-fonts` — здесь **Manrope + Manrope** (без Playfair, без Unbounded в целевом контракте; в SCSS темы ещё есть Unbounded — Aurora: выровнять на Manrope 800 per GLOBE-TREKKER-REDESIGN).

Подключение: один Google Fonts URL с `display=swap`, preconnect.

## Layout & Grid

- Sticky header 80px, blur milky.
- Section padding 80px vertical (60px hero top).
- Breakpoints: 1440 reference, 768 tablet (dest 2–3 col), 375 mobile stack.

## Elevation & Depth

- Тени только на collage tiles (мягкая).
- Карточки направлений — gradient scrim, не box-shadow.
- Header — border 1px `#E8E3DC`, не тяжёлая тень.

## Component States & Behaviors

- **btn--primary:** hover `#D4684F`, focus visible ring 2px offset.
- **tab-btn:** active = text + bottom border 2px.
- **dest-premium-card:** image scale 1.05 on hover.
- **Cookie banner:** fixed bottom full width (угол в референсе — theme cookie corner OK).

## Motion

- CSS transitions 0.2–0.3s on links/buttons.
- Optional GSAP ScrollTrigger `.reveal` — не блокировать без JS.

## Responsive Behavior

- 375px: hero title clamp; collage уменьшить высоту; dest horizontal scroll или 2-col; editorial list thumbs остаются слева.
- Subscribe form: column stack, full-width input.

## Accessibility

- Контраст terracotta on white для кнопок: проверить AA (крупный текст OK).
- Subscribe: белый текст on overlay — min 4.5:1 с overlay.
- `alt` на всех tourism photos (русский).
- Не использовать emoji в UI.

## Do / Don't

**Do:** коллаж, vertical dest, editorial blog, VK CTA, РТА в футере, честные формулировки.

**Don't:** watermark logo, fake testimonials, AggregateRating, цены «от» без даты, Playfair, stock «самолёт на белом» как единственный visual.

## QA Checklist

- [ ] 12+ meaningful images on homepage inventory
- [ ] Trust block без цитат-выдумок
- [ ] MCP blog covers единой серии после auth
- [ ] Manrope-only headlines
- [ ] No watermark

## AI Prompt Integration

Brand kit / blog: `AURA_BRAND_KIT_IMAGE_PROMPT.md`, `AURA_BLOG_COVER_CONCEPT.json`. MCP KV обязателен для **новых** production assets.

## Related Artifacts

`AURA_PAGE_PLAN.md`, `AURA_SECTION_BLUEPRINTS.json`, `AURA_ASSET_REGISTRY.json`, `AURA_STYLE_MATCH_SCORECARD.md`
