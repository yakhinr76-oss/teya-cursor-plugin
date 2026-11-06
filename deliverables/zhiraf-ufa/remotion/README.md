# Hero Remotion Player

Orbit collage for the homepage hero (`OrbitPhotos` + `@remotion/player`).

## Build

```bash
cd deliverables/zhiraf-ufa/remotion
npm install
npm run build
```

Output: `../wp-theme/zhiraf-ufa/assets/dist/hero-remotion-player.js`

WordPress enqueues it on the front page when the bundle and three hero images exist. Image URLs are passed via `zhirafHeroRemotion` (`wp_localize_script`).

## Theme CSS

After SCSS changes under the theme, recompile:

```bash
cd deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa
npx sass assets/src/scss/main.scss assets/dist/style.css --no-source-map
```

## Fallback

- No bundle → legacy `hero-orbit-carousel.js` + `data-hero-orbit`
- Mobile / `prefers-reduced-motion` → static stack inside `#hero-remotion-root`
