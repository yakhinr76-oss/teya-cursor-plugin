# Release Gate Report — `zhiraf-ufa`

| Field | Value |
|-------|-------|
| command | `python3 scripts/teya_release_gate.py --project-root /workspace` |
| exit_code | **0** |
| status | **PASS** |
| theme_slug | `zhiraf-ufa` |
| gate_script_sha256 | `50f71547469a0ccbf3ca9f11cddf07c42e90dcd44e0ad7cde64dcfc27a5c1a3d` |
| pillow | installed (`pip install Pillow` in runner env) |
| checked_at | 2026-10-18T16:20:00+00:00 |
| git_ref | `aa54024` (SEO merge) + paint refresh (pipeline-log §15) |
| deploy | **not run** (`public_site_url` empty) |

## Stdout

```
TEYA RELEASE GATE PASS
```

## Artifact fixes (this run)

None — first gate attempt failed only because Pillow was missing in the runner; after `pip install Pillow`, gate passed without changing repo artifacts.

## Context

- Integrated branch tip after merge: **aa54024** (`fix(seo): audit 19 URL`)
- Metrika **45658173** on **70f05ad** (ancestor of tip)
- Paint re-captured **38** PNG (19 Wordstat routes × 1440/390) post-merge PHP SEO changes; `paint-evidence.json` updated
