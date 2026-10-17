(function () {
  'use strict';
  if (typeof gsap === 'undefined') return;

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return;

  if (typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
  }

  var collage = document.querySelector('[data-gsap-collage]');
  if (collage) {
    var imgs = collage.querySelectorAll('.collage-img');
    gsap.from(imgs, {
      opacity: 0,
      y: 24,
      duration: 0.8,
      stagger: 0.12,
      ease: 'power2.out',
    });
  }

  gsap.utils.toArray('.dest-card').forEach(function (card, i) {
    gsap.from(card, {
      scrollTrigger: { trigger: card, start: 'top 90%', once: true },
      opacity: 0,
      y: 16,
      duration: 0.5,
      delay: i * 0.03,
      ease: 'power1.out',
    });
  });
})();
