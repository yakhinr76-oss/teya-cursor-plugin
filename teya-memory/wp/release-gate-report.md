# Release Gate Report — `zhiraf-ufa`

| Field | Value |
|-------|-------|
| command | `python3 scripts/teya_release_gate.py --project-root /workspace` |
| exit_code | **1** |
| status | **RELEASE BLOCKER** |
| theme_slug | `zhiraf-ufa` |
| checked_at | 2026-10-18T13:48:00+00:00 |
| agent_bc_id | `bc-53277b3d-9e1c-5fd6-9cab-24e622e7203e` |
| deploy | **not run** (`public_site_url` empty in `site-spec.json`) |

## Summary

Повторный прогон после правки `artifact-readiness-report.md` (identity `zhiraf-ufa`, убраны agent-slug токены из текста отчёта). **Slug lint по artifact-readiness — PASS.** Остаются blockers: **duplicate bytes** hero/dest в `wp-media-map.json` и theme `media-map.json` (допустимо в `AURA_ASSET_REGISTRY.json` via `allow_duplicate_of`; gate пока не читает registry).

## Stdout (полный вывод)

```
TEYA RELEASE GATE FAILED
- wp-media-map.json: duplicate image bytes for distinct assets ['dest-uae', 'hero-uae']: sha256=7860dfdb837ffa8f5ec3ef4c39f745646a298d2386985f8a92c3ea4fbc40f021
- wp-media-map.json: duplicate image bytes for distinct assets ['dest-turkey', 'hero-turkey']: sha256=e6ffc39d279a1c9e4d528bf9c310c4debb1c98d53ba04a1500800c3ed657071c
- wp-media-map.json: duplicate image bytes for distinct assets ['dest-thailand', 'hero-thailand']: sha256=2a2bb7758d5ee948148115418f9bd138703932cdbbc0a42795846c43aaa2090c
- theme media-map.json: duplicate image bytes for distinct assets ['dest-uae', 'hero-uae']: sha256=7860dfdb837ffa8f5ec3ef4c39f745646a298d2386985f8a92c3ea4fbc40f021
- theme media-map.json: duplicate image bytes for distinct assets ['dest-turkey', 'hero-turkey']: sha256=e6ffc39d279a1c9e4d528bf9c310c4debb1c98d53ba04a1500800c3ed657071c
- theme media-map.json: duplicate image bytes for distinct assets ['dest-thailand', 'hero-thailand']: sha256=2a2bb7758d5ee948148115418f9bd138703932cdbbc0a42795846c43aaa2090c
```

## Next

Align `teya_release_gate.py` duplicate check with registry `allow_duplicate_of`, or regenerate distinct dest files — затем re-run gate.
