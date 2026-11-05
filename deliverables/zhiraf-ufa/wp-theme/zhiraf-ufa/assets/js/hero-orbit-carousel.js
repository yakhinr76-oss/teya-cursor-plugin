(function () {
  'use strict';

  var ORBIT_DURATION_MS = 12000;
  var CARD_COUNT = 3;

  function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  }

  function shouldRunOrbit() {
    if (prefersReducedMotion()) {
      return false;
    }
    return window.matchMedia('(min-width: 769px)').matches;
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
      rx: 115,
      ry: 75,
    };

    function resize() {
      var w = collage.clientWidth;
      var h = collage.clientHeight;
      var scale = Math.min(w / 420, h / 320, 1.15);
      state.rx = 115 * scale;
      state.ry = 75 * scale;
    }

    function frame(now) {
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

      state.rafId = window.requestAnimationFrame(frame);
    }

    function start() {
      resize();
      collage.classList.add('is-orbit-active');
      state.rafId = window.requestAnimationFrame(frame);
    }

    function stop() {
      if (state.rafId) {
        window.cancelAnimationFrame(state.rafId);
        state.rafId = 0;
      }
      collage.classList.remove('is-orbit-active');
      state.tiles.forEach(function (tile) {
        tile.style.left = '';
        tile.style.top = '';
        tile.style.transform = '';
        tile.style.zIndex = '';
      });
    }

    state.startLoop = start;
    state.stopLoop = stop;
    state.onResize = resize;

    return state;
  }

  document.addEventListener('DOMContentLoaded', function () {
    var collage = document.querySelector('[data-hero-orbit]');
    if (!collage) {
      return;
    }

    var orbit = initHeroOrbit(collage);
    if (!orbit) {
      return;
    }

    function sync() {
      if (shouldRunOrbit()) {
        if (!orbit.rafId) {
          orbit.startLoop();
        } else {
          orbit.onResize();
        }
      } else {
        orbit.stopLoop();
      }
    }

    sync();
    window.addEventListener('resize', sync, { passive: true });

    var mqMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    var mqWidth = window.matchMedia('(min-width: 769px)');
    if (mqMotion.addEventListener) {
      mqMotion.addEventListener('change', sync);
      mqWidth.addEventListener('change', sync);
    }
  });
})();
