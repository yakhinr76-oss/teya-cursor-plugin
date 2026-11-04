(function () {
  'use strict';
  if (typeof gsap === 'undefined') {
    return;
  }

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var mm = gsap.matchMedia();

  if (typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
  }

  var delayMap = {
    'reveal-delay-1': 0.1,
    'reveal-delay-2': 0.2,
    'reveal-delay-3': 0.3,
  };

  function revealDelay(el) {
    var d = 0;
    Object.keys(delayMap).forEach(function (cls) {
      if (el.classList.contains(cls)) {
        d = delayMap[cls];
      }
    });
    return d;
  }

  mm.add('(prefers-reduced-motion: reduce)', function () {
    gsap.set('.reveal', { clearProps: 'all', opacity: 1, y: 0 });
  });

  mm.add('(prefers-reduced-motion: no-preference)', function () {
    var hero = document.querySelector('[data-motion="hero"]');
    if (hero) {
      var heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } });
      var heroCopy = hero.querySelector('.hero-premium__content');
      var tiles = hero.querySelectorAll('.collage-tile, .hero-collage__img');
      if (heroCopy) {
        heroTl.from(heroCopy, { opacity: 0, y: 24, duration: 0.7 }, 0);
      }
      if (tiles.length) {
        heroTl.from(
          tiles,
          { opacity: 0, y: 24, scale: 0.97, duration: 0.7, stagger: 0.12 },
          0.15
        );
      }
      var flightPath = hero.querySelector('[data-motion="flight-path"] path');
      if (flightPath && typeof flightPath.getTotalLength === 'function') {
        var len = flightPath.getTotalLength();
        gsap.set(flightPath, { strokeDasharray: len, strokeDashoffset: len });
        heroTl.to(flightPath, { strokeDashoffset: 0, duration: 0.8 }, 0.25);
      }
    }

    gsap.utils.toArray('.reveal').forEach(function (el) {
      if (hero && hero.contains(el)) {
        return;
      }
      gsap.from(el, {
        scrollTrigger: { trigger: el, start: 'top 85%', once: true },
        opacity: 0,
        y: 24,
        duration: 0.65,
        delay: revealDelay(el),
        ease: 'power3.out',
      });
    });

    var destRow = document.querySelector('.dest-premium-row');
    if (destRow) {
      var cards = gsap.utils.toArray('.dest-premium-card', destRow);
      if (cards.length) {
        gsap.from(cards, {
          scrollTrigger: { trigger: destRow, start: 'top 85%', once: true },
          opacity: 0,
          y: 24,
          duration: 0.6,
          stagger: 0.08,
          ease: 'power3.out',
        });
      }
    }
  });
})();
