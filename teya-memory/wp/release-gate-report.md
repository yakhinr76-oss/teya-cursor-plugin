# Release Gate Report — `zhiraf-ufa`

| Field | Value |
|-------|-------|
| command | `python3 scripts/teya_release_gate.py --project-root /workspace` |
| exit_code | **0** |
| status | **PASS** |
| theme_slug | `zhiraf-ufa` |
| checked_at | 2026-10-18T13:50:00+00:00 |
| agent_bc_id | `bc-53277b3d-9e1c-5fd6-9cab-24e622e7203e` |
| deploy | **not run** (`public_site_url` empty → live checks skipped) |
| theme_dir | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` via `site-spec.json` `theme_path` |

## Stdout

```
TEYA RELEASE GATE PASS
```

## Notes

- После `git pull`: media-map `allow_duplicate_of` + gate registry-aware duplicate check.
- Gate resolves full theme from `site-spec.theme_path` when `teya-memory/wp/theme/zhiraf-ufa/` — только packaged assets.
