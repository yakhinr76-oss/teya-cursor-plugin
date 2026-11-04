=== AURORA-TEAM-SECURITY-RELEASE (БЕЗОПАСНЫЙ РЕЛИЗ) ===
## Статус: ✅
Security/release map: teya-memory/wp/security-release-map.md
Required reports:
- teya-memory/wp/site-spec.json
- teya-memory/wp/build-report.json
Rollback required: yes
Release blockers: missing site-spec/build-report; visual_inventory not 14/14 (11/14 — editorial thumbs + trust-social); per-page meaningful images below budget (12/6/2/1/4); incomplete paint QA (only home 1440/375, pages[] empty); wp-media-map/import before live MCP-free HTML; MCP/tempfile URLs in public HTML; unstyled live paint; missing theme CSS/JS/images subresources; missing local assets; secrets in package; stale theme_slug/site_name/public_site_url across reports; teya_release_gate.py non-zero or missing; generic inner templates; placeholders/anti-haltura violations
