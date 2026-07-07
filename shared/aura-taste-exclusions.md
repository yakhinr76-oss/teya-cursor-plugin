# AURA Taste Exclusions — WP / Teya

Правила из Taste Skill, Impeccable и Emil skills, которые **не применять** к WordPress-темам Aurora без явного указания в brief.

## Authority order

При конфликте:

1. `teya-memory/design/design-lock.json`
2. `teya-memory/design/AURADESIGN.md`
3. `teya-memory/wp/conversion-funnel-map.md` (при CONVERSION-FIRST)
4. Taste / Impeccable heuristics
5. Generic «modern SaaS» defaults

## Stack — не тащить

- React, Next.js, RSC, `"use client"`, Tailwind как обязательный стек
- shadcn/ui, Radix Themes, Fluent, Carbon, Material Web как default install
- `motion/react`, Framer Motion `x`/`y` shorthands как default для WP
- `next/font`, `next/image`
- Radix CSS variables (`--radix-popover-content-transform-origin`) без адаптации под WP markup

## Typography — не глобально банить

- Глобальный ban **Inter** — использовать только шрифты из `AURADESIGN.md` / `AURA_FONT_MATCH.md`
- Глобальный ban **serif** — если AURA/brief задаёт serif (например Lora), это закон
- Пары Geist/Satoshi/Cabinet без кириллицы — не подменять `aura-cyrillic-google-fonts`

## Layout / hero — не для B2B WP

- Hero subtext max 20 words как hard gate
- Hero max 4 text elements как hard gate
- Anti-center hero при variance > 4 — если `design-lock` / funnel требуют corporate centered hero
- Agency presets: decoration strip `BRAND. MOTION. SPATIAL.`, locale/time strips, scroll cues
- Em-dash zero-tolerance в PHP — контент-зона Excalibur/content pack; в UI strings по brief

## Color — не перебивать бренд

- «Max 1 accent» — не применять, если AURADESIGN задаёт brand green + navy + gradient CTA
- Premium-consumer beige+brass ban — не релевантно для B2B travel
- LILA rule (no purple) — только если нет в brand; у проекта смотреть `design-lock.forbidden_ui`

## Assets — не stock defaults

- Picsum / Unsplash / Simple Icons CDN как production assets
- Div-based fake product screenshots
- Hand-rolled decorative SVG **запрещено только** если AURA требует MCP/SVG из `AURA_SHAPE_MAP.json` — shape replication имеет приоритет

## Motion — не React patterns

- React GSAP skeletons (StickyStack, HorizontalPan components) — идеи переносить в `aurora-team-motion`, не копировать JSX
- «Marquee max 1 per page» — если `animation-motion-map.md` явно требует иначе, побеждает motion map
- Perpetual micro-loops на каждой карточке при B2B brief — снижать ожидания

## Pre-flight Taste (50+ items)

Не копировать полный Taste Pre-Flight в theme gate. Использовать сокращённый чеклист из `aurora-team-design-taste` skill + `impeccable detect`.

## Когда exclusions не действуют

- Brief явно просит «Awwwards / agency / experimental»
- `AURA_TASTE_PROFILE.md` (режим **TASTE PLAN**) переопределяет dials для Aurora Team / Page Builder
- Design Guardian / AURA visual gates требуют иное для source fidelity
