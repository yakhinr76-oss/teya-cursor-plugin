# Release Gate Report — `zhiraf-ufa`

| Field | Value |
|-------|-------|
| command | `python3 scripts/teya_release_gate.py --project-root /workspace` |
| exit_code | **1** |
| status | **RELEASE BLOCKER** (артефакты / layout темы, не скрипт gate) |
| theme_slug | `zhiraf-ufa` |
| gate_script | `scripts/teya_release_gate.py` — **идентичен 07628b4** (без локальных правок) |
| pillow | installed (`pip install Pillow`) |
| checked_at | 2026-10-18T13:54:00+00:00 |
| agent_bc_id | `bc-53277b3d-9e1c-5fd6-9cab-24e622e7203e` |
| deploy | **not run** |

## Stdout

```
TEYA RELEASE GATE FAILED
- wp-media-map.json: duplicate image bytes for distinct assets ['dest-uae', 'hero-uae']: sha256=7860dfdb837ffa8f5ec3ef4c39f745646a298d2386985f8a92c3ea4fbc40f021
- wp-media-map.json: duplicate image bytes for distinct assets ['dest-turkey', 'hero-turkey']: sha256=e6ffc39d279a1c9e4d528bf9c310c4debb1c98d53ba04a1500800c3ed657071c
- wp-media-map.json: duplicate image bytes for distinct assets ['dest-thailand', 'hero-thailand']: sha256=2a2bb7758d5ee948148115418f9bd138703932cdbbc0a42795846c43aaa2090c
- theme media-map.json: duplicate image bytes for distinct assets ['dest-uae', 'hero-uae']: sha256=7860dfdb837ffa8f5ec3ef4c39f745646a298d2386985f8a92c3ea4fbc40f021
- theme media-map.json: duplicate image bytes for distinct assets ['dest-turkey', 'hero-turkey']: sha256=e6ffc39d279a1c9e4d528bf9c310c4debb1c98d53ba04a1500800c3ed657071c
- theme media-map.json: duplicate image bytes for distinct assets ['dest-thailand', 'hero-thailand']: sha256=2a2bb7758d5ee948148115418f9bd138703932cdbbc0a42795846c43aaa2090c
- missing css file: /workspace/teya-memory/wp/theme/zhiraf-ufa/style.css
- missing css file: /workspace/teya-memory/wp/theme/zhiraf-ufa/assets/dist/style.css
```

## Артефактные blockers (owner: packager / theme layout, не gate)

1. **Media maps:** одинаковые байты у пар hero/dest (3 пары × 2 map = 6 ошибок) — нужны distinct files или согласованный контракт registry без дублей в maps.
2. **Theme path:** gate ожидает полную тему в `teya-memory/wp/theme/zhiraf-ufa/`; сейчас packaged `assets/` + `media-map.json`, PHP/CSS в `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/`.
