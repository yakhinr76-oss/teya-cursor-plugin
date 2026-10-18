(function () {
  'use strict';
  if (typeof gsap === 'undefined') return;

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;

  if (typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
  }

  // Premium fade ups for sections
  gsap.utils.toArray('.reveal').forEach(function(section) {
    gsap.from(section, {
      scrollTrigger: { trigger: section, start: 'top 85%', once: true },
      opacity: 0,
      y: 40,
      duration: 1.2,
      ease: 'power3.out',
    });
  });

  // Staggered images in hero
  var tiles = document.querySelectorAll('.collage-tile');
  if (tiles.length) {
    gsap.from(tiles, {
      opacity: 0,
      y: 30,
      scale: 0.95,
      duration: 1.2,
      stagger: 0.2,
      ease: 'power3.out',
      delay: 0.2
    });
  }

  // Destination cards
  var cards = gsap.utils.toArray('.dest-card');
  if (cards.length) {
    gsap.from(cards, {
      scrollTrigger: { trigger: cards[0], start: 'top 85%', once: true },
      opacity: 0,
      y: 30,
      duration: 1,
      stagger: 0.1,
      ease: 'power3.out',
    });
  }

})();
