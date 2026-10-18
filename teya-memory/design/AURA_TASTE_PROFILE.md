# AURA Taste Profile

- mode: TASTE PLAN
- project: Турагентство «Жираф», Уфа (Globe Trekker replication)
- theme_slug: `zhiraf-ufa`
- date: 2026-10-18
- design_ambition: **polished** (B2B premium travel lead-gen; brief default)
- verdict: **✅ READY**

## Summary

AURA-пакет задаёт **editorial travel magazine**, не generic SaaS: milky `#FAF8F5`, terracotta `#E87A5D`, hero-коллаж 3 фото + dashed flight SVG, вертикальные dest 3:4 с табами, asymmetric editorial 1+3, full-bleed subscribe. Для tier `polished` этого достаточно, чтобы Aurora собирала тему без упрощения до «белый longscroll + три карточки».

Остающиеся gaps — **реализация** (trust-social в PHP, Manrope-only в SCSS, editorial thumbs), не слабая декомпозиция. **MCP KV needsAuth** не блокирует PLAN: production-ready пути в `AURA_ASSET_REGISTRY.json` + theme assets; новые blog covers — отложить или локальные placeholders до auth.

Director может запускать Lead / Conversion Architect / Aurora PAGE BUILDER с этим профилем.

## Anti-slop mandates (T01–T15)

| ID | Mandate for Aurora | Waiver |
|----|-------------------|--------|
| T01 | Не ставить на главной (и key landings) ряд из **трёх одинаковых feature-карточек** «иконка + заголовок + текст» как единственный value prop | `front-page:steps` — 4 numbered step-cards с крупными цифрами 01–04 по AURA; не смешивать с generic SaaS row |
| T02 | Не вкладывать card-in-card для иерархии, где достаточно spacing, divider, scrim | Dest cards: caption на gradient scrim, не вложенная «карточка в карточке» |
| T03 | Нет purple/violet/cyan-on-dark **AI gradient slop** на primary UI | AURADESIGN: warm editorial only; dark только subscribe overlay |
| T04 | Явные `transition` по свойствам; **запрет `transition: all`** на кнопках, ссылках, меню, табах | — |
| T05 | Не использовать `ease-in` на enter/show (dropdown, modal, mobile menu) | Допустимо `ease` / `ease-out` per AURADESIGN motion |
| T06 | Нет entrance `transform: scale(0)` / near-zero scale на UI | Hover scale 1.05 на фото dest — OK |
| T07 | Primary CTA `#E87A5D` on white/milky — контраст **≥ WCAG AA** (крупный текст / bold 700) | Проверить subscribe white-on-overlay 4.5:1 |
| T08 | Touch targets критичных CTA **≥ 44px** на 375px | Header phone + pill «Подобрать тур» |
| T09 | Display/headlines: **Manrope** per AURADESIGN; убрать Unbounded из SCSS | AURADESIGN + replication TODO |
| T10 | Фоны/текст: tinted neutrals `#FAF8F5`, `#F5F2ED`, `#2B2B2B` — не чистый `#000`/`#fff` на весь скролл | `#FFFFFF` допустим для отдельных секций (steps, form) по decomposition |
| T11 | Не спамить uppercase eyebrow над **каждой** секцией | Category pills только editorial + dest tabs context |
| T12 | На главной не повторять **один layout_family 3+ раза подряд** | Чередовать: hero split → dest grid → steps row → editorial asymmetric → trust → form → subscribe bleed |
| T13 | Hero не «только текст на градиенте» — **коллаж ≥3 meaningful images** + flight SVG | `AURA_VISUAL_BUDGET` homepage min 12 images |
| T14 | Нет lorem, «скоро», Jane Doe / Acme; нет выдуманных отзывов и звёзд | brief + fact-bank; trust = facts + VK/phone/office |
| T15 | Нет bounce/elastic на UI без playful brief | GSAP reveal: opacity + translateY, stagger 0.1s max |

## Positive craft mandates (P01–P15)

Tier **polished**: **P01, P04, P07, P12 = soft** (цель сборки; AUDIT — предупреждения). Остальные P — рекомендации.

| ID | Target | Homepage min | Inner pages |
|----|--------|--------------|-------------|
| P01 | ≥4 разных `layout_family` на главной | hero 50/50 collage, dest tabs+vertical cards, editorial 1.5/1fr, subscribe full-bleed (+ steps numerals row, trust band) | `/tour/` catalog hero + grid ≠ копия homepage hero |
| P02 | ≥2 shaped / non-rect transitions | collage object-overlap + subscribe overlay scrim; optional wave **не** обязателен (ref JPG missing) | tour grid может flat |
| P03 | Hero asymmetric split или overlap | 50/50 + absolute collage z-index choreography | tour: catalog hero с hub image |
| P04 | Meaningful images ≥ visual budget | **≥12** assets with alt (registry + hero×3 + dest×5 + editorial + subscribe) | tour ≥6; o-nas ≥2 |
| P05 | Display/body scale contrast ≥2.5× | Manrope 800 clamp H1 vs 17px body | сохранить на inner H1 |
| P06 | ≥1 editorial rhythm block | front-page:editorial break grid | about: team + office visual block |
| P07 | Custom component for key offer | **Квиз / funnel «Подобрать тур»** или tabbed dest — не generic 3-col features | quiz on dedicated flow OK |
| P08 | Section transitions beyond padding+flat bg | gradient scrim on cards; subscribe overlay; collage overlap | soft: milky↔white alternation per AURA |
| P09 | ≥1 sequenced scroll moment homepage | `.reveal` stagger optional; не блокировать без JS | reduced motion: static OK |
| P10 | Inner pages distinct layout_family vs homepage paste | tour grid, kontakty NAP+map/form, blog list | Lead blueprint must assign families |
| P11 | Scorecard composition ≥ planned minimum | Implement trust-social → planned trust ≥7 | после trust block пересчёт |
| P12 | Не >2 подряд white/text-only без visual anchor | После steps вставить editorial или trust с фото | o-nas: min 1 hero visual |
| P13 | CTA zones compositional variety | header pill vs hero CTA vs form strip vs subscribe — разная композиция | не дублировать идентичные full-width bands |
| P14 | Icons/illustrations from AURA assets | SVG flight path; dest photos; no emoji/CSS circle placeholders | VK icon — brand-consistent |
| P15 | Layout variance score meets targets below | см. таблицу variance | tour/blog не collapse в один template |

## Layout variance targets

| Metric | Target |
|--------|--------|
| section_layout_families_min (homepage) | **5** (hero, destinations, steps, editorial, subscribe; trust counts when shipped) |
| consecutive_same_family_max | **2** |
| shaped_transitions_min | **2** (collage-overlap-internal + subscribe-overlay; dest-card-scrim counts as in-card) |
| meaningful_images_homepage_min | **12** (`AURA_VISUAL_BUDGET.json` `/`) |
| colored_surface_ratio_min | **0.35** (≥3 non-pure-white section backgrounds: milky, sand `#F5F2ED`, subscribe photo) |

## CSS / component rules for Aurora

- Transitions: `background-color`, `color`, `border-color`, `opacity`, `transform` — **per-property**; max 0.2–0.3s on UI chrome.
- Buttons: pill `border-radius: 9999px`; primary hover `#D4684F`; focus-visible ring 2px offset.
- Cards: dest `aspect-ratio: 3/4`, `rounded 24px`, caption via gradient pseudo-element — **no** heavy box-shadow on cards (only collage tiles).
- Header: sticky 80px, `backdrop-filter` blur, border `1px #E8E3DC`.
- Container: `max-width: 1240px`, padding inline 20px — lock per `AURA_COMPOSITION_LOCK.json`.
- Typography: single Google Fonts import Manrope; headings 800, body 17px/1.6, muted `#666`.
- **Forbidden:** logo watermark on sections; Playfair; AggregateRating; fake testimonial quotes; price «от» без даты.
- Align SCSS: remove Unbounded; headlines Manrope 800 only.

## Motion taste

- Easing: `ease` or `ease-out` for reveals; **no** `ease-in` on open; **no** bounce/elastic.
- Duration: 0.2s links/buttons; 0.3s max for section reveals.
- Homepage: optional one sequenced scroll (stagger 0.1s, translateY ~24px + opacity).
- Respect `prefers-reduced-motion`: disable stagger/translate.
- Handoff `aurora-team-motion`: GSAP ScrollTrigger only if non-blocking fallback exists.

## Waivers (design-lock / AURADESIGN / funnel-map)

| Pattern | Authority | Reason |
|---------|-----------|--------|
| MCP KV blog covers blocked | User + AURADESIGN assets policy | Локальные theme/registry assets OK для PLAN/build; MCP после auth |
| Reference JPG missing | `AURA_SECTION_TRANSITIONS.json` | Flat milky↔white transitions OK; wave not required |
| trust-social not in PHP yet | `AURA_SECTION_BLUEPRINTS.json` | Documented P0 Aurora task; не generic AURA failure |
| 4 step cards in a row | AURA decomposition `how-it-works` | Numbered steps ≠ T01 generic SaaS trio |
| CONVERSION-FIRST quiz order | brief / future funnel-map | Funnel wins **order**; taste wins **form** (no slop simplification) |

## Aurora handoff checklist

1. Прочитать этот профиль + `AURADESIGN.md` + `AURA_SECTION_BLUEPRINTS.json` + `AURA_VISUAL_BUDGET.json`.
2. Реализовать **front-page:trust-social** (facts + VK + phone + office photo) без fake reviews.
3. Hero: 50/50 + 3-photo collage + dashed flight SVG per composition lock.
4. Destinations: 5 vertical cards + tabs; gradient scrim captions.
5. Editorial: 1.5fr feature + 3 thumbs — не uniform 3-col blog grid.
6. Subscribe: full-bleed `subscribe-bg.jpg` + 50% overlay + pill email field.
7. Manrope-only; strip Unbounded from theme SCSS.
8. Homepage image inventory ≥12 meaningful alts; verify against registry.
9. Primary CTA contrast + 44px touch targets on mobile.
10. No `transition: all`; no AI purple gradients; no watermark logo.
11. После сборки — **TASTE AUDIT** сверка с этим профилем + optional impeccable detect.

## Gate notes (AURA vs Taste)

| Source | Status |
|--------|--------|
| AURA design fidelity gate | Open (trust section, MCP covers) — **не** taste BLOCKER at `polished` |
| Taste PLAN gate | **✅ READY** — craft contract defined; build may proceed |
