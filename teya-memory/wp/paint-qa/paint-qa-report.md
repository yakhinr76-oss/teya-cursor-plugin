# Paint QA Report — zhiraf-ufa (local-only)

| Поле | Значение |
|------|----------|
| mode | **local-only** (public URL отсутствует) |
| preview | `deliverables/zhiraf-ufa/preview/home.html` via `http://127.0.0.1:8765` |
| theme | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` |
| checked_at | 2026-10-18 |
| agent | bc-646a0fe3-3bb0-5e28-bdf3-d4cafb2cc25e |

## Screenshots

| Viewport | Path | Artifacts |
|----------|------|-----------|
| 1440 full-page (post-scroll) | `teya-memory/wp/paint-qa/home-1440-fullpage.png` | `/opt/cursor/artifacts/zhiraf-home-1440.png` |
| 390 full-page (post-scroll) | `teya-memory/wp/paint-qa/home-375-fullpage.png` | `/opt/cursor/artifacts/zhiraf-home-390.png` |

Внутренние selected pages (`/tour/`, `/o-nas/`, …) **не сняты** — нет static preview/WP runtime в этом run.

## Network / CSS

- `style.css` HTTP 200, браузер запросил theme CSS.
- JS (`main.js`, GSAP) в static preview **не** подключён — INP/motion не валидируются paint-гейтом.

## Computed styles (1440)

| Token | Observed | AURA expected |
|-------|----------|---------------|
| body background | `#FAF8F5` | ✅ |
| primary CTA | `#E87A5D` | ✅ |
| body font | Manrope | ✅ |
| H1 font | **Unbounded** first in stack | ❌ Manrope 800 (`AURA_FONT_MATCH.md`) |

## Image density (preview DOM)

- Meaningful `<img>`: **8**
- `AURA_VISUAL_INVENTORY.json` minimum homepage: **12**
- **Gap:** −4 (preview укорочен vs полная главная темы)

## Verdict

**fail** — typography drift (Unbounded), image minimum не закрыт на preview, нет inner-page paint evidence, preview ≠ `front-page.php` section set.
