# AURA Visual QA

**Scope:** design artifacts + static theme file audit (no PHP edits this run)

## Automated / manual checks

| Check | Result |
|-------|--------|
| `theme.json` palette | ✅ #FAF8F5, #E87A5D |
| Hero 3 images in front-page.php | ✅ |
| subscribe-bg.jpg exists | ✅ |
| dest-*-new.jpg set exists | ✅ |
| Reviews placeholder removed | ✅ per GLOBE doc |
| Logo watermark in theme images | ✅ none detected |
| AURA_ASSET_REGISTRY paths exist on disk | ✅ sampled |

## Mobile 375px

Described in `AURA_VISUAL_DIFF.md` — Aurora must run browser QA post trust block.

## Blockers

- mcp-kv auth
- trust section implementation
- reference JPG for optional wave divider

## Status

**QA design docs:** pass  
**QA production fidelity:** fail until Aurora + MCP
