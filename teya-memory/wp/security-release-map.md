# Security & Release Map — «Жираф», Уфа (`zhiraf-ufa`)

## Meta

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (staging: **b2b-tour.ru**, noindex) |
| site_name | Жираф |
| theme_slug | `zhiraf-ufa` |
| mode | CONVERSION-FIRST |
| blueprint_ref | `teya-memory/wp/aurora-team-blueprint.md` |
| data_flow | `shared/agent-data-flow-contract.md` |
| media_contract | `shared/wp-media-upload-contract.md` |
| paint_gate | `shared/visual-paint-qa-gate.md` |
| playbook | `shared/wp-theme-builder-playbook.md` |
| theme_staging_path | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` |
| theme_memory_path | `teya-memory/wp/theme/zhiraf-ufa/` (канон после Aurora THEME BASE) |
| public_site_url (staging) | `https://b2b-tour.ru/` |
| public_site_url (production target) | `https://zhiraf-ufa.ru/` |
| selected_build_pages | `/`, `/tour/`, `/o-nas/`, `/kontakty/`, `/blog/` |
| author | aurora-team-security-release |
| date | 2026-10-18 |
| status | ✅ READY (контракт для Aurora / wp-theme-builder / release-gate) |

---

## 1. Роль карты

Aurora **до** записи PHP темы создаёт `teya-memory/wp/site-spec.json`. **После** сборки, проверок и (при наличии credentials) деплоя — `teya-memory/wp/build-report.json`. Оба файла обязаны переносить visual data fields из `agent-data-flow-contract.md` и значения из AURA inventory/budget (без выдуманных цифр).

Director / `teya_release_gate.py` / Design Guardian / QA читают эту карту как источник истины для allowlist, secrets, backup, rollback и release blockers.

---

## 2. Контракт `site-spec.json` (до сборки)

**Путь:** `teya-memory/wp/site-spec.json`  
**Когда:** первый артефакт Aurora PAGE BUILDER, до изменения файлов темы.  
**Статус проекта в spec:** `planned` | `building` — не `success` / `ready` до завершения сборки.

### 2.1 Обязательная структура (верхний уровень)

```json
{
  "schema_version": "teya-site-spec-1",
  "generated_at": "ISO-8601",
  "generated_by": "aurora",
  "theme_slug": "zhiraf-ufa",
  "public_site_url": "https://b2b-tour.ru/",
  "project": {
    "site_name": "Жираф",
    "domain_target": "zhiraf-ufa.ru",
    "domain_staging": "b2b-tour.ru",
    "region": "Уфа",
    "language": "ru",
    "theme_slug": "zhiraf-ufa",
    "status": "planned",
    "mode": "CONVERSION-FIRST",
    "public_site_url": "https://b2b-tour.ru/"
  },
  "pages": [],
  "menus": {},
  "templates": {},
  "schema_types": [],
  "forms": [],
  "indexing": {},
  "analytics": {},
  "deploy": {},
  "visual": {},
  "assets": {},
  "security": {},
  "checks_planned": []
}
```

### 2.2 `pages[]` (детерминизм из blueprint)

Каждая selected/build page:

| slug | template | build_in_test |
|------|----------|---------------|
| `/` | `front-page.php` | yes |
| `/tour/` | `page-tour.php` | yes |
| `/o-nas/` | `page-o-nas.php` | yes |
| `/kontakty/` | `page-kontakty.php` | yes |
| `/blog/` | `home.php` | yes |

Поля элемента: `slug`, `wp_slug` (`tour`, `o-nas`, …), `template`, `title`, `menu_placement`, `schema_types[]`, `indexing` (`index|noindex`), `content_pack_section_ids[]`.

### 2.3 Visual data block (`visual` + дубли на корне для gate)

**Источник чисел:** `AURA_VISUAL_INVENTORY.json`, `AURA_VISUAL_BUDGET.json`.

| Поле | Значение для spec (план) | Источник |
|------|--------------------------|----------|
| `visual_inventory_status` | `pending` → Aurora ставит `ready` только если все `zones[].ready` true | inventory |
| `required_visual_zones_count` | **14** | `zones.length` |
| `ready_visual_zones_count` | **11** (на момент Team; Aurora обновляет после реализации) | inventory `ready` |
| `meaningful_image_count` | план: **12** на `/` | homepage budget |
| `minimum_meaningful_image_assets_homepage` | **12** | inventory |
| `meaningful_image_gap` | `max(0, minimum - count)` | вычисление |
| `section_transitions_status` | `specified` | `AURA_SECTION_TRANSITIONS.json` |
| `asset_registry_status` | `specified` | `AURA_ASSET_REGISTRY.json` |
| `paint_evidence_status` | `not_run` | до paint-evidence |
| `visual_budget_status` | `specified` | `AURA_VISUAL_BUDGET.json` |
| `section_blueprints_status` | `specified` | `AURA_SECTION_BLUEPRINTS.json` |
| `style_match_scorecard_status` | `specified` | `AURA_STYLE_MATCH_SCORECARD.md` |
| `per_page_visual_budget_status` | `specified` | budget `pages[]` |
| `per_page_section_blueprints_status` | `specified` | blueprints |
| `per_page_meaningful_image_counts` | см. §2.4 | budget |
| `per_page_visual_gaps` | `{}` до сборки | Aurora заполняет |
| `local_asset_files_status` | `pending` | asset-packager |
| `missing_local_asset_files` | `[]` или список из registry | asset-packager |
| `browser_subresources_status` | `not_run` | до live/preview |
| `unstyled_live_paint_status` | `not_run` | до paint |
| `wp_media_map_status` | `not_required` до deploy | после deploy → `pending` |
| `wp_media_import_status` | `not_required` до deploy | |
| `missing_wp_media_attachments` | `[]` | |

Дублировать те же ключи на **корне** JSON (flat), если `teya_release_gate.py` читает корень — значения должны совпадать с `visual.*`.

### 2.4 `per_page_meaningful_image_counts` (минимумы, не считать CSS-карточки)

```json
{
  "/": 12,
  "/tour/": 6,
  "/o-nas/": 2,
  "/kontakty/": 1,
  "/blog/": 4
}
```

### 2.5 Deploy / indexing в spec

- `deploy.mode`: `ftp` | `sftp` | `local-only` | `playground`
- `deploy.allow_publish`: только если `TEYA_ALLOW_PUBLISH=yes` в `teya-memory/teya.env.local` (секреты **не** в spec)
- `indexing.staging_noindex`: **true** для b2b-tour.ru
- `indexing.robots_ref`: `teya-memory/wp/indexing-crawl-map.md`

### 2.6 `security` в spec

- `file_allowlist_extensions`: см. §5
- `secrets_excluded`: `true` (обязательно)
- `package_includes_teya_memory`: **false**

---

## 3. Контракт `build-report.json` (после сборки)

**Путь:** `teya-memory/wp/build-report.json`  
**Когда:** после THEME BASE + PAGE BUILDER (+ motion implement по playbook), обновляется после deploy / wp-media / paint.

### 3.1 Обязательная структура

```json
{
  "schema_version": "teya-build-report-1",
  "generated_at": "ISO-8601",
  "generated_by": "aurora",
  "status": "success | blocked | ready_for_deploy",
  "theme_slug": "zhiraf-ufa",
  "public_site_url": "https://b2b-tour.ru/",
  "project": {
    "site_name": "Жираф",
    "theme_slug": "zhiraf-ufa",
    "public_site_url": "https://b2b-tour.ru/",
    "status": "success"
  },
  "files": { "generated": [], "skipped": [] },
  "pages_built": [],
  "templates": [],
  "menus": {},
  "forms": {},
  "schema": {},
  "indexing": {},
  "package": { "path": "", "checksum": "" },
  "deploy": {
    "status": "not_run | success | failed | skipped_credentials",
    "remote_theme_path": "",
    "backup_artifact": "",
    "previous_active_theme": ""
  },
  "rollback": {
    "previous_theme_slug": "",
    "backup_zip_path": "",
    "activation_steps": []
  },
  "checks": {
    "php_lint": { "status": "pass|fail|skipped", "log": "" },
    "theme_check": { "status": "pass|fail|skipped", "log": "" },
    "security_review": { "status": "pass|fail", "notes": [] }
  },
  "visual": {},
  "animation_motion_status": "pending",
  "animation_dependency_status": "pending",
  "reduced_motion_status": "pending",
  "threejs_scene_status": "not_used|pass|fail"
}
```

### 3.2 Visual data fields (корень + `visual` — идентичны site-spec после факта)

Все поля из §2.3, но со **фактическими** статусами:

| Поле | PASS критерий |
|------|----------------|
| `visual_inventory_status` | `ready` и `ready_visual_zones_count === required_visual_zones_count` (14/14) |
| `meaningful_image_count` | ≥ `minimum_meaningful_image_assets_homepage` (12) на live/preview DOM |
| `meaningful_image_gap` | `0` на главной и 0 на каждой build page vs per-page minimum |
| `paint_evidence_status` | `pass` только с файлами скринов + subresources |
| `local_asset_files_status` | `pass`, `missing_local_asset_files` пуст |
| `browser_subresources_status` | `pass`: theme CSS/JS/images 200 после cache-bust |
| `unstyled_live_paint_status` | `pass`: не default HTML |
| `wp_media_map_status` | `pass` после deploy |
| `wp_media_import_status` | `pass`, нет пустых `attachment_id` для MCP assets |
| `missing_wp_media_attachments` | `[]` |

**Запрет:** `status: success` или `deploy.status: success` при любом visual/paint/wp-media blocker.

### 3.3 Split reports (связь)

При финальном `build-report.json` на диске должны существовать (gate):

- `theme-base-report.md`
- `asset-packaging-report.md`
- `page-build-report.md`
- `artifact-readiness-report.md`
- `animation-implementation-report.md` (если motion включён)

### 3.4 Identity lock

`theme_slug`, `project.site_name`, `public_site_url` в `site-spec.json`, `build-report.json`, `paint-evidence.json`, `wp-media-map.json`, `design-integrity-report.md` — **одинаковые**. Stale slug другого проекта = release blocker.

---

## 4. WP Media contract (после deploy)

По `shared/wp-media-upload-contract.md`:

| Артефакт | Назначение |
|----------|------------|
| `teya-memory/wp/wp-media-map.json` | registry_id → attachment_id, URL, alt |
| `teya-memory/wp/wp-media-import-log.md` | человекочитаемый лог импорта |
| `teya-memory/wp/theme/zhiraf-ufa/media-map.json` | staging map в пакете темы |

**Закон:** публичный HTML **не** содержит MCP KV / tempfile / remote gen URLs. Theme path `/wp-content/themes/zhiraf-ufa/assets/images/` допустим до импорта; на **live** для MCP-generated — только Media Library или `wp_get_attachment_image()`.

Поля в build-report: `wp_media_map_status`, `wp_media_import_status`, `missing_wp_media_attachments`.

---

## 5. Paint QA contract

По `shared/visual-paint-qa-gate.md` — обязательно **после** Design Guardian / `aurora-team-paint-evidence` при наличии public или preview URL.

### 5.1 Файлы на диске

```text
teya-memory/wp/paint-qa/paint-evidence.json
teya-memory/wp/paint-qa/paint-qa-report.md
teya-memory/wp/paint-qa/home-1440-fullpage.png
teya-memory/wp/paint-qa/home-375-fullpage.png
teya-memory/wp/paint-qa/page-tour-1440-fullpage.png
teya-memory/wp/paint-qa/page-tour-375-fullpage.png
teya-memory/wp/paint-qa/page-o-nas-1440-fullpage.png
teya-memory/wp/paint-qa/page-o-nas-375-fullpage.png
teya-memory/wp/paint-qa/page-kontakty-1440-fullpage.png
teya-memory/wp/paint-qa/page-kontakty-375-fullpage.png
teya-memory/wp/paint-qa/page-blog-1440-fullpage.png
teya-memory/wp/paint-qa/page-blog-375-fullpage.png
```

`paint-evidence.json` с `verdict: pass` **недействителен**, если любой путь из `screenshots` отсутствует, `screenshots.pages` пуст при 5 build pages, или `css_network` не подтверждает theme CSS/JS/images.

### 5.2 Текущий gap (pre-Aurora close)

Частичные evidence: только home screenshots; `screenshots.pages: []` — **release blocker** до полного набора.

---

## 6. Safe file allowlist (theme package / deploy)

Разрешённые расширения в FTP/SFTP upload и zip backup темы:

`.php`, `.css`, `.js`, `.json`, `.svg`, `.png`, `.jpg`, `.jpeg`, `.webp`, `.woff`, `.woff2`, `.txt`, `.md` (только `readme.txt` / license в корне темы, не произвольные md)

**Запрещено в пакете деплоя:**

- `teya-memory/` целиком
- `.env`, `teya.env.local`, `hosting.credentials.local`, `smtp.credentials.local`
- `.git/`, `node_modules/`, `*.map` (если не нужны в prod)
- `assets/src/` (исходники SCSS/TS) — только `assets/dist/` в prod, если playbook требует dist-only
- логи, дампы SQL, бэкапы с credentials

---

## 7. `.deployignore` / `.distignore`

Создать в корне темы `teya-memory/wp/theme/zhiraf-ufa/.deployignore` (и зеркально в `deliverables/...` при sync):

```text
node_modules
.git
.DS_Store
*.map
assets/src
artifacts
.env
*.log
teya-memory
hosting.credentials.local
smtp.credentials.local
```

Скрипты: `scripts/deploy_theme_ftp.py`, `scripts/deploy_theme_sftp.py` читают `.deployignore` (SFTP) или фиксированный ignore set (FTP).

**`.distignore`** (опционально, для zip в WordPress.org style): то же + `tests`, `package.json`, dev-only vendor sources.

---

## 8. Secrets policy

| Правило | Действие |
|---------|----------|
| Credentials | Только `teya-memory/teya.env.local` или hosting secret store; **никогда** в git, theme, site-spec, build-report |
| Логи deploy | Маскировать FTP user/host; **не** печатать password в `deploy-log.md` |
| SMTP / CRM | UI + nonce; webhook URL из env; document `missing_integration` без значений |
| Spec/report | `secrets_excluded: true`; пустые placeholder для IDs |

---

## 9. Backup / snapshot перед удалённым деплоем

1. **DB + uploads** (хостинг): snapshot или `wp db export` + tar `wp-content/uploads` — вне репозитория, путь в `deploy-log.md`.
2. **Тема:** zip текущей активной темы на сервере → `teya-memory/wp/backups/theme-<slug>-<YYYYMMDD-HHMM>.zip` (локально) или remote path в логе.
3. **WP options:** записать `template`, `stylesheet`, `siteurl`, `home` в `deploy-log.md` (без secrets).
4. **Staging:** для b2b-tour.ru — backup обязателен перед перезаписью `zhiraf-ufa`.

Без backup artifact в `build-report.json` → `deploy.status` не может быть `success`.

---

## 10. Rollback plan

| Шаг | Действие |
|-----|----------|
| 1 | Деактивировать `zhiraf-ufa`, активировать `rollback.previous_theme_slug` (из build-report / deploy-log) |
| 2 | Восстановить файлы из `rollback.backup_zip_path` в `wp-content/themes/` |
| 3 | Очистить opcode cache / CDN cache при наличии |
| 4 | Проверить `home` + одну inner URL |
| 5 | Записать инцидент в `deploy-log.md` |

`Rollback required` для любого remote deploy: **yes**.

---

## 11. PHP lint / syntax

```bash
find teya-memory/wp/theme/zhiraf-ufa -name '*.php' -print0 | xargs -0 -n1 php -l
```

Все файлы → exit 0. Результат в `build-report.checks.php_lint`.

Если `php` недоступен: `skipped` + причина; **не** ставить overall `success` на production deploy без lint на CI/агенте с PHP.

---

## 12. WordPress Theme Check / structure

Минимум по `shared/wp-theme-builder-playbook.md`:

- Валидный `style.css` header, `Text Domain` = slug
- `ABSPATH` guard во всех PHP кроме style.css
- `inc/setup.php`, `inc/enqueues.php`, `inc/security.php`, `inc/seo.php`
- `main#primary` в шаблонах
- `screenshot.png|jpg`

Theme Check CLI: `wp theme review teya-memory/wp/theme/zhiraf-ufa` — если WP-CLI есть; иначе `skipped` + ручной чеклист в `verification.md`.

---

## 13. Security checks (код темы)

| Область | Требование |
|---------|------------|
| Output escaping | `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` |
| Forms | `wp_nonce_field`, `check_ajax_referer` / `wp_verify_nonce` |
| Input | `sanitize_text_field`, `sanitize_email`, `absint` |
| SQL | только `$wpdb->prepare` |
| Files | no arbitrary upload handlers in theme |
| JSON-LD | `wp_json_encode` с флагами Unicode |
| Capabilities | `current_user_can` для admin-only hooks |
| Direct access | `ABSPATH` guard |

`inc/security.php` — центральные хелперы; не дублировать слабую санитизацию в template-parts.

---

## 14. Preview / sandbox policy

Порядок предпочтения:

1. Локальный static/preview (`127.0.0.1`) — только для раннего paint, **не** заменяет live gate при заявленном deploy.
2. WordPress Playground / Studio — если Director разрешил.
3. Staging `https://b2b-tour.ru/` — канон для Phase 1 close (noindex).

`public_site_url` в отчётах должен соответствовать среде, по которой сняты скрины.

---

## 15. Release gates (что блокирует publish)

| Gate | Блокер |
|------|--------|
| Artifacts | Нет `site-spec.json` / `build-report.json` / split reports |
| Visual inventory | `visual_inventory_status` ≠ ready или zones < 14 |
| Visual budget / blueprints | Не реализованы на любой selected/build page |
| Meaningful images | Count < per-page minimum (12/6/2/1/4) |
| Generic inner page | Внутренняя страница = default `page.php` без AURA language |
| One-hero-only | Source требует multi-zone, в теме один hero |
| Local assets | `missing_local_asset_files` не пуст |
| Paint | Нет per-page screenshots 1440+375 |
| Unstyled paint | `unstyled_live_paint_status` ≠ pass |
| Subresources | Нет theme CSS/JS/images в network |
| WP media | MCP URL в HTML или пустой attachment_id |
| Secrets | Credentials в пакете или логах |
| Identity | theme_slug / site_name / URL mismatch между отчётами |
| Release script | `teya/scripts/teya_release_gate.py` exit ≠ 0 |
| Anti-haltura | placeholders «скоро», фейковые отзывы (`quality-anti-haltura.md`) |

---

## 16. Inventory-driven blockers (текущий снимок Team)

Зоны `ready: false` в `AURA_VISUAL_INVENTORY.json` (Aurora must fix before 14/14):

- `editorial-thumb-1`, `editorial-thumb-2`, `editorial-thumb-3` (секция trust-social / editorial)
- Плюс секция `trust-social` на главной (см. `performance-accessibility-map.md` CONT-01)

До закрытия: `ready_visual_zones_count` = 11, `required_visual_zones_count` = 14 → **visual_inventory_status** не может быть `ready`.

---

## 17. Команды проверки (release)

```bash
# из корня репозитория
find teya-memory/wp/theme/zhiraf-ufa -name '*.php' -print0 | xargs -0 -n1 php -l
python3 teya/scripts/teya_release_gate.py --project-root .
# локальная сборка без live:
python3 teya/scripts/teya_release_gate.py --project-root . --no-live
```

Записать вывод в `teya-memory/wp/release-gate-report.md`.

---

## 18. Связанные артефакты

| Артефакт | Связь |
|----------|-------|
| `performance-accessibility-map.md` | LCP images, fonts, allowlist overlap |
| `conversion-tracking-map.md` | Form nonces, Metrika без утечки секретов |
| `indexing-crawl-map.md` | staging noindex в spec |
| `asset-packaging-report.md` | `local_asset_files_status` |
| `design-integrity-report.md` | post-guardian visual verdict |
| `release-gate-report.md` | hard gate exit code |

---

## 19. Чеклист Aurora (кратко)

- [ ] `site-spec.json` до кода, visual fields + per_page counts
- [ ] Тема в `teya-memory/wp/theme/zhiraf-ufa/`
- [ ] `.deployignore` в корне темы
- [ ] `build-report.json` после сборки, checks + rollback object
- [ ] После deploy: `wp-media-map.json`, import log, theme `media-map.json`
- [ ] Paint QA: 5×2 screenshots + evidence JSON
- [ ] `teya_release_gate.py` → `release-gate-report.md`
- [ ] Нет secrets в git/p package
