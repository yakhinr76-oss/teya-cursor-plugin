# Asset Packaging Report — zhiraf-ufa

**Theme slug:** `zhiraf-ufa`
**Generated at:** 2026-10-18T13:46:03+00:00
**Script:** local decode verify (distinct hero/dest stock files)
**Verdict:** READY

## Summary

| Metric | Value |
| --- | ---: |
| Registry assets | 17 |
| Ready assets | 17 |
| Unique sha256 (registry) | 17 |
| Blockers | 0 |

## Hero vs dest bytes

Восстановлены **разные** stock-файлы для карточек направлений (git `731a983` / `SOURCES.md` dest-* IDs). `dest-*-new.jpg` синхронизированы с `dest-*.jpg` для UAE, Turkey, Thailand. `allow_duplicate_of` **удалён** из registry и media-map.

| pair | hero sha256 (prefix) | dest sha256 (prefix) |
| --- | --- | --- |
| uae | `7860dfdb…` | `31eccccb…` |
| turkey | `e6ffc39d…` | `a0e46761…` |
| thailand | `2a2bb775…` | `ce79897b…` |

## Per Asset

| id | status | format | bytes | WxH | local_path | decode_verified |
| --- | --- | --- | ---: | ---: | --- | --- |
| hero-uae | ok | jpeg | 192739 | 800x1200 | `assets/images/hero-uae.jpg` | true |
| hero-turkey | ok | jpeg | 96848 | 800x533 | `assets/images/hero-turkey.jpg` | true |
| hero-egypt | ok | jpeg | 90067 | 800x533 | `assets/images/hero-egypt.jpg` | true |
| hero-thailand | ok | jpeg | 102322 | 800x554 | `assets/images/hero-thailand.jpg` | true |
| hero-vietnam | ok | jpeg | 63970 | 800x500 | `assets/images/hero-vietnam.jpg` | true |
| dest-turkey | ok | jpeg | 86134 | 900x598 | `assets/images/dest-turkey-new.jpg` | true |
| dest-uae | ok | jpeg | 112572 | 900x600 | `assets/images/dest-uae-new.jpg` | true |
| dest-egypt | ok | jpeg | 104085 | 900x600 | `assets/images/dest-egypt-new.jpg` | true |
| dest-thailand | ok | jpeg | 113307 | 900x601 | `assets/images/dest-thailand-new.jpg` | true |
| dest-vietnam | ok | jpeg | 153217 | 900x600 | `assets/images/dest-vietnam-new.jpg` | true |
| subscribe-bg | ok | jpeg | 217817 | 1600x1064 | `assets/images/subscribe-bg.jpg` | true |
| catalog-hub | ok | jpeg | 177401 | 1200x797 | `assets/images/catalog-hub.jpg` | true |
| team-travel | ok | jpeg | 115868 | 1200x800 | `assets/images/team-travel.jpg` | true |
| office-ufa | ok | jpeg | 117648 | 1200x801 | `assets/images/office-ufa.jpg` | true |
| blog-cover-01 | ok | jpeg | 211071 | 1200x1500 | `assets/images/blog-cover-01.jpg` | true |
| article-main | ok | jpeg | 179943 | 1200x800 | `assets/images/article-main.jpg` | true |
| logo-client | ok | png | 6028 | 167x56 | `assets/images/logo-zhiraf-client.png` | true |

## Theme folder decode audit

`deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/assets/images`: **38/38** raster files `decode_verified` (Pillow verify + load).
