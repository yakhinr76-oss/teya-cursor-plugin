# Release Gate Report — `zhiraf-ufa`

| Field | Value |
|-------|-------|
| command | `python3 scripts/teya_release_gate.py --project-root /workspace` |
| exit_code | **1** |
| status | **RELEASE BLOCKER** |
| checked_at | 2026-10-18T13:45:00+00:00 |
| agent_bc_id | `bc-53277b3d-9e1c-5fd6-9cab-24e622e7203e` |
| deploy | **not run** (local-only; `public_site_url` empty in `site-spec.json`) |
| pillow | installed in gate VM (`pip install Pillow`) for decode-verify |
| theme_note | Packaged assets under `teya-memory/wp/theme/zhiraf-ufa/`; full PHP/CSS theme in `deliverables/` |

## Summary

Машинный gate **не прошёл**. Paint evidence (`verdict: pass`, home screenshots on disk) и split reports присутствуют; blockers — **identity lint** в `artifact-readiness-report.md`, **допустимые duplicate bytes** hero/dest (registry `allow_duplicate_of`), не live URL.

## Stdout / stderr (полный вывод)

```
TEYA RELEASE GATE FAILED
- stale report identity in teya-memory/wp/artifact-readiness-report.md: found ['teya-researcher'], expected zhiraf-ufa
- wp-media-map.json: duplicate image bytes for distinct assets ['dest-uae', 'hero-uae']: sha256=7860dfdb837ffa8f5ec3ef4c39f745646a298d2386985f8a92c3ea4fbc40f021
- wp-media-map.json: duplicate image bytes for distinct assets ['dest-turkey', 'hero-turkey']: sha256=e6ffc39d279a1c9e4d528bf9c310c4debb1c98d53ba04a1500800c3ed657071c
- wp-media-map.json: duplicate image bytes for distinct assets ['dest-thailand', 'hero-thailand']: sha256=2a2bb7758d5ee948148115418f9bd138703932cdbbc0a42795846c43aaa2090c
- theme media-map.json: duplicate image bytes for distinct assets ['dest-uae', 'hero-uae']: sha256=7860dfdb837ffa8f5ec3ef4c39f745646a298d2386985f8a92c3ea4fbc40f021
- theme media-map.json: duplicate image bytes for distinct assets ['dest-turkey', 'hero-turkey']: sha256=e6ffc39d279a1c9e4d528bf9c310c4debb1c98d53ba04a1500800c3ed657071c
- theme media-map.json: duplicate image bytes for distinct assets ['dest-thailand', 'hero-thailand']: sha256=2a2bb7758d5ee948148115418f9bd138703932cdbbc0a42795846c43aaa2090c
```

## Remediation hints (not applied in this run)

| Error | Suggested owner |
|-------|-----------------|
| `teya-researcher` slug lint | Reword artifact-readiness paths or extend gate allowlist for agent names |
| Duplicate hero/dest bytes | Gate should honor `allow_duplicate_of` in registry, or use distinct files per asset id |
| Live checks | Skipped — no `public_site_url` in compiled spec (no deploy) |

## Handoff

`build-report.json` → `release_gate_status: failed`, `status: blocked`. Design Guardian **DESIGN OK** (home local) остаётся; production publish — **no**.
