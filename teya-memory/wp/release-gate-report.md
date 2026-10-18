# Release Gate Report — `zhiraf-ufa`

| Field | Value |
|-------|-------|
| command | `python3 scripts/teya_release_gate.py --project-root /workspace` |
| exit_code | **0** |
| status | **PASS** |
| theme_slug | `zhiraf-ufa` |
| gate_script_sha256 | `50f71547469a0ccbf3ca9f11cddf07c42e90dcd44e0ad7cde64dcfc27a5c1a3d` |
| pillow | installed (`pip3 install Pillow` in runner env) |
| checked_at | 2026-10-18T16:04:40+00:00 |
| git_ref | `a06c6d5` (chore(paint-qa): 19 Wordstat routes, drop Phuket screenshots) |
| deploy | **not run** (`public_site_url` empty) |

## Stdout

```
TEYA RELEASE GATE PASS
```

## Artifact fixes (this run)

None — gate passed on existing artifacts after Pillow was available for raster decode-verify.
