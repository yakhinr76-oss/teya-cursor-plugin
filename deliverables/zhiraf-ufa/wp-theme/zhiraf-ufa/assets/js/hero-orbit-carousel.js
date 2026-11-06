(function () {
  'use strict';

  var ORBIT_DURATION_MS = 12000;
  var CARD_COUNT = 3;
  var booted = false;

  function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  function shouldRunOrbit() {
    if (prefersReducedMotion()) {
      return false;
    }
    return window.matchMedia('(min-width: 769px)').matches;
  }

  function clearTileMotionStyles(tiles) {
    if (typeof window.gsap !== 'undefined') {
      window.gsap.set(tiles, { clearProps: 'transform,x,y,scale,opacity' });
    }
    tiles.forEach(function (tile) {
      tile.style.removeProperty('transform');
      tile.style.removeProperty('opacity');
    });
  }

  function initHeroOrbit(collage) {
    var tiles = collage.querySelectorAll('.hero-collage__img');
    if (tiles.length !== CARD_COUNT) {
      return null;
    }

    var state = {
      collage: collage,
      tiles: Array.prototype.slice.call(tiles),
      rafId: 0,
      start: performance.now(),
      rx: 165,
      ry: 110,
    };

    function resize() {
      var w = collage.clientWidth;
      var h = collage.clientHeight;
      if (w < 1 || h < 1) {
        return false;
      }
      state.rx = Math.max(120, w * 0.34);
      state.ry = Math.max(72, h * 0.22);
      return true;
    }

    function applyFrame(now) {
      if (!resize()) {
        return;
      }
      var elapsed = (now - state.start) % ORBIT_DURATION_MS;
      var progress = elapsed / ORBIT_DURATION_MS;
      var centerX = collage.clientWidth / 2;
      var centerY = collage.clientHeight / 2;

      state.tiles.forEach(function (tile, index) {
        var angle =
          -progress * Math.PI * 2 +
          (index / CARD_COUNT) * Math.PI * 2 -
          Math.PI / 2;
        var x = Math.cos(angle) * state.rx;
        var y = Math.sin(angle) * state.ry;
        tile.style.left = centerX + x + 'px';
        tile.style.top = centerY + y + 'px';
        tile.style.transform = 'translate(-50%, -50%)';
        tile.style.zIndex = String(Math.round(y + 200));
      });
    }

    function frame(now) {
      applyFrame(now);
      state.rafId = window.requestAnimationFrame(frame);
    }

    function start() {
      if (!shouldRunOrbit()) {
        return;
      }
      stop(false);
      clearTileMotionStyles(state.tiles);
      collage.classList.add('is-orbit-active');
      state.start = performance.now();
      applyFrame(performance.now());
      state.rafId = window.requestAnimationFrame(frame);
    }

    function stop(clearClass) {
      if (state.rafId) {
        window.cancelAnimationFrame(state.rafId);
        state.rafId = 0;
      }
      if (clearClass !== false) {
        collage.classList.remove('is-orbit-active');
      }
      state.tiles.forEach(function (tile) {
        tile.style.left = '';
        tile.style.top = '';
        tile.style.transform = '';
        tile.style.zIndex = '';
      });
    }

    state.startLoop = start;
    state.stopLoop = function () {
      stop(true);
    };

    return state;
  }

  function boot() {
    if (booted) {
      return;
    }
    var collage = document.querySelector('[data-hero-orbit]');
    if (!collage) {
      return;
    }

    var orbit = initHeroOrbit(collage);
    if (!orbit) {
      return;
    }
    booted = true;

    function sync() {
      if (shouldRunOrbit() && collage.clientWidth > 0) {
        orbit.startLoop();
      } else {
        orbit.stopLoop();
      }
    }

    if (typeof window.ResizeObserver !== 'undefined') {
      var ro = new window.ResizeObserver(function () {
        sync();
      });
      ro.observe(collage);
    }

    window.addEventListener('resize', sync, { passive: true });

    var mqMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    var mqWidth = window.matchMedia('(min-width: 769px)');
    if (mqMotion.addEventListener) {
      mqMotion.addEventListener('change', sync);
      mqWidth.addEventListener('change', sync);
    }

    window.addEventListener('zhiraf:hero-intro-done', sync);
    window.setTimeout(sync, 900);
    sync();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
  window.addEventListener('load', boot);
})();
