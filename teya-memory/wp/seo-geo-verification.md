# SEO / GEO / WP Verification — zhiraf-ufa

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa |
| theme_slug | `zhiraf-ufa` |
| mode | **local-only** |
| design_guardian | `design-integrity-report.md` → **❌ DESIGN BLOCKER** |
| paint_qa | `paint-evidence.json` → **verdict: fail** |
| checked_at | 2026-10-18 |
| agent | aurora-team-qa (run bc-646a0fe3-3bb0-5e28-bdf3-d4cafb2cc25e) |
| **status** | **❌ BLOCKER** |
| ready_to_publish | **no** |

## Release gate

| Check | Result |
|-------|--------|
| `teya/scripts/teya_release_gate.py` | ⚠️ Script **not found** in repo — gate not executed |

## Theme contract (file review)

| Check | Result |
|-------|--------|
| `ABSPATH` guards | ✅ |
| `wp_head` / `wp_footer` / `wp_body_open` | ✅ |
| `main#primary` | ✅ `front-page.php`, templates |
| PHP lint (34 files) | ✅ |

## Design Guardian dependency

Final QA cannot pass while design status ≠ `✅ DESIGN OK`. **Blocked.**

Critical design issues: missing blueprint sections, Unbounded typography, paint image minimum, no inner-page screenshots.

## Maps & artifacts presence

| Artifact | Status |
|----------|--------|
| `aurora-team-blueprint.md` | ✅ |
| `page-content-pack.md` | ✅ |
| `performance-accessibility-map.md` | ✅ |
| `conversion-funnel-map.md` | ✅ |
| `navigation-linking-map.md` | ❌ missing |
| `schema-technical-seo-map.md` | ❌ missing |
| `indexing-crawl-map.md` | ❌ missing |
| `local-entity-map.md` | ❌ missing |
| `conversion-tracking-map.md` | ❌ missing |
| `security-release-map.md` | ❌ missing |
| `site-spec.json` / `build-report.json` | ❌ missing |
| `content-completeness-report.md` | ❌ missing |
| `wp-media-map.json` | ❌ N/A local-only |

## Content & anti-haltura

| Check | Result |
|-------|--------|
| Public `lorem` / `TODO` / fake reviews | ✅ Not found in theme templates |
| Form placeholders | ✅ OK (input placeholders allowed) |
| H1 on front-page vs content pack | ⚠️ Mismatch — generic hero title |
| FAQ visible on home | ❌ Section missing |
| Blog section | ⚠️ Editorial present; funnel order wrong |

## SEO / technical (static)

| Check | Result |
|-------|--------|
| `inc/seo.php` meta + JSON-LD hooks | ✅ present |
| Legal links in footer | ✅ politika URLs in `footer.php` |
| Cookie banner + accept | ✅ `footer.php` |
| Live robots/sitemap/canonical | ⚠️ Not verified (no deploy URL) |

## Performance / a11y (contract)

See `performance-accessibility-map.md`. Implementation gaps: font preload, skip-link CSS, WebP/srcset, trust section.

## Paint / screenshots

| Asset | Status |
|-------|--------|
| `/opt/cursor/artifacts/zhiraf-home-1440.png` | ✅ |
| `/opt/cursor/artifacts/zhiraf-home-390.png` | ✅ |
| Inner pages paint | ❌ |

## Critical issues

1. Design Guardian **❌ DESIGN BLOCKER** (blueprint sections, typography, visual budget).
2. Aurora Team WP maps incomplete (navigation, schema, indexing, local, conversion, security).
3. No `site-spec.json` / `build-report.json` data-flow fields.
4. Paint QA fail: image minimum, H1 font, no inner pages.
5. Release gate script absent — cannot claim hard gate pass.

## Warnings

- Static `preview/home.html` diverges from `front-page.php` — do not use preview alone for sign-off.
- Google Fonts Unbounded still in `header.php` (performance + design).

## Ready to publish

**no**
