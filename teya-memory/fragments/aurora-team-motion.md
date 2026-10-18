=== AURORA-TEAM-MOTION (GSAP/THREE ANIMATION) ===
## Статус: ✅ MOTION READY
Mode: MOTION PLAN
Motion map: teya-memory/wp/animation-motion-map.md
Implementation report: *(MOTION IMPLEMENT — не начат)*
Libraries: GSAP 3.12.5 (local vendor, front page only) + ScrollTrigger; Three.js **planned** (`hero-ambient-flight`, dynamic import)
threejs_scene_status: **planned** (hero particle field along flight SVG; off on reduced-motion & mobile)
Reduced motion: **planned** (CSS + gsap.matchMedia + no WebGL)
Performance blockers: нет на этапе PLAN; исправить drift `motion-home.js` (`.collage-tile`/`.dest-card` vs `.hero-collage__img`/`.dest-premium-card`) и duration 1.2s→≤0.8s при IMPLEMENT
Aurora handoff: сохранить `.reveal`, `.reveal-delay-*`, `.dest-premium-card`; добавить `data-motion="hero"`, `[data-three-canvas="hero-ambient"]`, `.reveal` на trust/audience/faq после P0 секций; не грузить GSAP вне главной
Theme path: deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/
