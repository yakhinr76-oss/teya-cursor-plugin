=== AURORA-TEAM-PERFORMANCE-A11Y (CWV/A11Y) ===
## Статус: ✅
Performance/a11y map: teya-memory/wp/performance-accessibility-map.md
CWV targets: LCP<2.5s INP<200ms CLS<0.1
LCP (home): hero-uae.jpg eager+fetchpriority=high; local Manrope Cyrillic preload contract
Fonts: local woff2 only (Manrope variable); no Google CDN prod; drop Unbounded from --font-head
Focus-visible: extend to all controls; skip-link focus styles required
Reduced-motion: GSAP no-op ✅; add global CSS reduce block
Accessibility blockers: A11Y-01 skip-link CSS; A11Y-02 dest tab semantics; CONT-01 trust-social section missing
Performance blockers: PERF-01 WebP/srcset; PERF-02 font preload; PERF-04 Unbounded token; ASSET-01 editorial thumbs
