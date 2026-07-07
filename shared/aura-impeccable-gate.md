# AURA Impeccable Gate — Teya

Машинный anti-slop gate для WP-тем Aurora. Дополняет LLM-аудит `aurora-team-design-taste`, не заменяет Design Guardian.

## Когда запускать (MVP)

После **Aurora PAGE BUILDER**, когда локальная тема существует:

```text
teya-memory/wp/theme/<theme-slug>/
```

Опционально также:

```text
teya-memory/design/index.html
```

## Команда

По умолчанию сканируется только `teya-memory/wp/theme/<theme-slug>/`.

Опционально (устаревшее AURA preview):

```bash
python teya/scripts/teya_aura_impeccable_detect.py --project-root <PROJECT_ROOT> --theme-slug <theme-slug> --include-design-preview
```

Или напрямую (если скрипт недоступен):

```bash
npx impeccable detect --json teya-memory/wp/theme/<theme-slug>/
```

Требования: Node.js 24+ (для `npx impeccable`). Без Node — записать `detect_status: skipped`, продолжить LLM checklist only.

## Выход

```text
teya-memory/design/AURA_IMPECCABLE_REPORT.json
```

Минимальная схема:

```json
{
  "status": "pass | fail | skipped",
  "tool": "impeccable",
  "scanned_paths": [],
  "finding_count": 0,
  "findings": [],
  "exit_code": 0,
  "skipped_reason": null
}
```

## BLOCKER policy (MVP)

Статус `❌ BLOCKER` в `AURA_TASTE_AUDIT.md`, если:

- `impeccable` exit code `2` и есть **critical** findings (см. ниже), и нет waiver в `design-lock` / audit
- LLM checklist имеет **3+** hard fails из skill

Critical Impeccable classes (типовые):

- purple/violet AI gradient slop на primary UI
- `transition: all` на интерактивных элементах (массово)
- nested cards pattern
- WCAG contrast fail на primary CTA
- bounce/elastic easing на UI transitions
- overused-font Inter **только если** не в `AURADESIGN.md`

Non-blocking (warning):

- line length, minor padding
- findings в vendor/minified файлах, если путь в `detector.ignoreFiles`

## Waivers

Разрешено игнорировать finding, если:

- цвет/шрифт совпадает с `AURADESIGN.md` tokens (document in audit)
- `design-lock.json` explicitly allows pattern
- файл помечен `impeccable-disable` inline (редко; предпочитать fix)

## Не путать с

| Gate | Вопрос |
|------|--------|
| `aurora-team-design-taste` PLAN | Craft contract before build → `AURA_TASTE_PROFILE.md` |
| `aurora-team-design-taste` AUDIT | Generic AI slop + detect vs profile |
| `aurora-team-design-guardian` | AURA fidelity + paint |
| `teya_release_gate.py` | Machine release (отдельный этап) |

## Design system hook (future)

`.impeccable/config.json` may point `detector.designSystem.enabled` at `teya-memory/design/AURADESIGN.md`. MVP: optional, not required for PASS.
