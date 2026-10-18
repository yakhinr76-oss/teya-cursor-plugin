# Asset Packaging Report — zhiraf-ufa

**Theme slug:** `zhiraf-ufa`
**Generated at:** 2026-10-18T13:37:31+00:00
**Script:** `teya/scripts/package_mcp_assets.py`
**Verdict:** READY

## Summary

| Metric | Value |
| --- | ---: |
| Registry assets | 17 |
| Ready assets | 17 |
| Blockers | 0 |

## Download Contract

- Probe: `Range: bytes=0-15`.
- File size: only `Content-Range */total` is authoritative for range responses.
- Chunks: 8192-byte Range requests with retries.
- Verification: byte signature + Pillow `verify()` + second `load()`.
- Format mismatch: WebP/JPEG/GIF to `.png` is re-encoded, never renamed.

## Per Asset

| id | status | source | format | bytes | WxH | local_path |
| --- | --- | --- | --- | ---: | ---: | --- |
| hero-uae | ok | user_provided_copy | jpeg | 192739 | 800x1200 | `assets/images/hero-uae.jpg` |
| hero-turkey | ok | user_provided_copy | jpeg | 96848 | 800x533 | `assets/images/hero-turkey.jpg` |
| hero-egypt | ok | user_provided_copy | jpeg | 93908 | 800x533 | `assets/images/hero-egypt.jpg` |
| hero-thailand | ok | user_provided_copy | jpeg | 102322 | 800x554 | `assets/images/hero-thailand.jpg` |
| hero-vietnam | ok | user_provided_copy | jpeg | 98587 | 800x500 | `assets/images/hero-vietnam.jpg` |
| dest-turkey | ok | user_provided_copy | jpeg | 96848 | 800x533 | `assets/images/dest-turkey-new.jpg` |
| dest-uae | ok | user_provided_copy | jpeg | 192739 | 800x1200 | `assets/images/dest-uae-new.jpg` |
| dest-egypt | ok | user_provided_copy | jpeg | 82565 | 800x534 | `assets/images/dest-egypt-new.jpg` |
| dest-thailand | ok | user_provided_copy | jpeg | 102322 | 800x554 | `assets/images/dest-thailand-new.jpg` |
| dest-vietnam | ok | user_provided_copy | jpeg | 93381 | 800x533 | `assets/images/dest-vietnam-new.jpg` |
| subscribe-bg | ok | user_provided_copy | jpeg | 217817 | 1600x1064 | `assets/images/subscribe-bg.jpg` |
| catalog-hub | ok | user_provided_copy | jpeg | 177401 | 1200x797 | `assets/images/catalog-hub.jpg` |
| team-travel | ok | user_provided_copy | jpeg | 115868 | 1200x800 | `assets/images/team-travel.jpg` |
| office-ufa | ok | user_provided_copy | jpeg | 117648 | 1200x801 | `assets/images/office-ufa.jpg` |
| blog-cover-01 | ok | user_provided_copy | jpeg | 211071 | 1200x1500 | `assets/images/blog-cover-01.jpg` |
| article-main | ok | user_provided_copy | jpeg | 179943 | 1200x800 | `assets/images/article-main.jpg` |
| logo-client | ok | user_provided_copy | png | 6028 | 167x56 | `assets/images/logo-zhiraf-client.png` |

## Theme folder decode audit (`deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa`)

**Source:** `existing_theme_asset` (локальные файлы темы; MCP KV `needsAuth`, скачивание не выполнялось).

| Metric | Value |
| --- | ---: |
| Raster files scanned | 38 |
| `decode_verified` | 38 |
| Blockers | 0 |

### Repairs (0-byte placeholders)

| File | Action |
| --- | --- |
| `assets/images/dest-uae-new.jpg` | Восстановлен из `dest-uae.jpg` (192739 bytes, JPEG 800×1200) |
| `assets/images/hero-collage-1.jpg` | Восстановлен из `hero-uae.jpg` (legacy collage asset, не в PHP) |

### Registry duplicate bytes (allowed)

Пары hero/dest с одним stock-файлом помечены `allow_duplicate_of` в `AURA_ASSET_REGISTRY.json` и в `wp-media-map.json` / theme `media-map.json`: `hero-thailand`→`dest-thailand`, `hero-uae`→`dest-uae`, `hero-turkey`→`dest-turkey`. `teya_release_gate.py` учитывает это при проверке sha256.

Packaged copies: `teya-memory/wp/theme/zhiraf-ufa/assets/images/` (17 registry assets).
