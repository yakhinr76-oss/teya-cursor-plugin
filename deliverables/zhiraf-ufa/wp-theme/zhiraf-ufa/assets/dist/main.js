(function () {
  'use strict';

  var cfg = window.zhirafUfa || {};

  function qs(sel, ctx) {
    return (ctx || document).querySelector(sel);
  }
  function qsa(sel, ctx) {
    return Array.prototype.slice.call((ctx || document).querySelectorAll(sel));
  }

  /* Mobile nav */
  var toggle = qs('.nav-toggle');
  var menuWrap = qs('#primary-menu-wrap');
  if (toggle && menuWrap) {
    toggle.addEventListener('click', function () {
      var open = menuWrap.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* Destination filter (tabs or legacy chips) */
  function bindDestFilter(bar, tabSelector) {
    var tabs = qsa(tabSelector, bar);
    var section = bar.closest('.destinations') || bar.parentElement;
    var cards = qsa('.dest-card', section);
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (c) { c.classList.remove('is-active'); });
        tab.classList.add('is-active');
        var filter = tab.getAttribute('data-filter');
        cards.forEach(function (card) {
          var dest = card.getAttribute('data-dest');
          var show = filter === 'all' || dest === filter;
          card.classList.toggle('is-hidden', !show);
        });
      });
    });
  }
  qsa('.dest-tabs').forEach(function (bar) { bindDestFilter(bar, '.dest-tab'); });
  qsa('.chip-filter').forEach(function (bar) { bindDestFilter(bar, '.chip'); });

  /* Cookie banner */
  var banner = qs('#cookie-banner');
  var accept = qs('#cookie-accept');
  var CONSENT_KEY = 'zhiraf_cookie_consent';
  function showBanner() {
    if (!banner) return;
    if (!localStorage.getItem(CONSENT_KEY)) {
      banner.removeAttribute('hidden');
    }
  }
  if (accept) {
    accept.addEventListener('click', function () {
      localStorage.setItem(CONSENT_KEY, '1');
      if (banner) banner.setAttribute('hidden', '');
      document.dispatchEvent(new CustomEvent('zhiraf:cookie-accepted'));
    });
  }
  showBanner();

  /* Quiz modal */
  var modal = qs('#quiz-modal');
  var quizForm = qs('#quiz-form');
  function openQuiz() {
    if (!modal) return;
    modal.removeAttribute('hidden');
    document.body.style.overflow = 'hidden';
    qs('.quiz-step.is-active', modal)?.focus?.();
  }
  function closeQuiz() {
    if (!modal) return;
    modal.setAttribute('hidden', '');
    document.body.style.overflow = '';
  }
  qsa('[data-quiz-open]').forEach(function (btn) {
    btn.addEventListener('click', openQuiz);
  });
  qsa('[data-quiz-close]').forEach(function (btn) {
    btn.addEventListener('click', closeQuiz);
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeQuiz();
  });

  var steps = qsa('.quiz-step', quizForm);
  var current = 0;
  function goStep(n) {
    steps.forEach(function (step, i) {
      var active = i === n;
      step.classList.toggle('is-active', active);
      step.hidden = !active;
    });
    current = n;
  }
  qsa('.quiz-next', quizForm).forEach(function (btn) {
    btn.addEventListener('click', function () {
      var step = steps[current];
      if (!step.checkValidity()) {
        step.reportValidity();
        return;
      }
      if (current < steps.length - 1) goStep(current + 1);
    });
  });
  qsa('.quiz-prev', quizForm).forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (current > 0) goStep(current - 1);
    });
  });

  /* AJAX forms */
  function postForm(form, action) {
    var status = qs('.form-status', form);
    var fd = new FormData(form);
    fd.append('action', action);
    fd.append('nonce', cfg.nonce || '');
    fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (status) {
          status.textContent = data.data && data.data.message ? data.data.message : (data.success ? 'OK' : 'Ошибка');
        }
        if (data.success) form.reset();
      })
      .catch(function () {
        if (status) status.textContent = 'Ошибка сети';
      });
  }

  qsa('.zhiraf-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var action = form.getAttribute('data-action');
      if (!action) return;
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      postForm(form, action);
    });
  });

  if (quizForm) {
    quizForm.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!quizForm.checkValidity()) {
        quizForm.reportValidity();
        return;
      }
      postForm(quizForm, 'zhiraf_quiz');
    });
  }
})();
