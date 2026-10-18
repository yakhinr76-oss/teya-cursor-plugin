// Bundled to assets/dist/main.js
(function () {
  'use strict';

  var cfg = window.zhirafUfa || {};
  var CONSENT_KEY = 'zhiraf_cookie_consent';
  var pendingGoals = [];

  function qs(sel, ctx) {
    return (ctx || document).querySelector(sel);
  }
  function qsa(sel, ctx) {
    return Array.prototype.slice.call((ctx || document).querySelectorAll(sel));
  }

  function metrikaId() {
    var meta = window.zhirafMetrika || {};
    if (!meta.id) return null;
    var n = parseInt(meta.id, 10);
    return n ? n : null;
  }

  function flushPendingGoals() {
    if (!metrikaId() || typeof window.ym !== 'function') return;
    while (pendingGoals.length) {
      var item = pendingGoals.shift();
      try {
        window.ym(metrikaId(), 'reachGoal', item.name, item.params || {});
      } catch (e) { /* ignore */ }
    }
  }

  window.zhirafTrack = function zhirafTrack(eventName, params) {
    params = params || {};
    var id = metrikaId();
    if (id && typeof window.ym === 'function') {
      try {
        window.ym(id, 'reachGoal', eventName, params);
      } catch (e) { /* ignore */ }
    } else if (id && localStorage.getItem(CONSENT_KEY)) {
      pendingGoals.push({ name: eventName, params: params });
    }
    if (typeof window.gtag === 'function') {
      try {
        window.gtag('event', eventName, params);
      } catch (e) { /* ignore */ }
    }
  };

  document.addEventListener('zhiraf:cookie-accepted', function () {
    setTimeout(flushPendingGoals, 1500);
  });
  if (localStorage.getItem(CONSENT_KEY)) {
    setInterval(function () {
      if (window.zhirafMetrika && window.zhirafMetrika.ready) {
        flushPendingGoals();
      }
    }, 500);
  }

  function phonePlacement(el) {
    if (el.closest('.site-header') || el.classList.contains('header-phone')) return 'header';
    if (el.closest('#sticky-mobile-cta')) return 'sticky';
    if (el.closest('.trust-social')) return 'trust';
    if (el.closest('.site-footer')) return 'footer';
    return 'other';
  }

  function quizOpenGoal(btn) {
    var ctx = btn.getAttribute('data-cta-context');
    if (ctx) {
      window.zhirafTrack(ctx);
      return;
    }
    if (btn.closest('.site-header')) {
      window.zhirafTrack('cta_header_quiz_open');
      return;
    }
    if (btn.closest('.hero-premium')) {
      window.zhirafTrack('cta_hero_quiz_open');
      return;
    }
    if (btn.closest('#sticky-mobile-cta')) {
      window.zhirafTrack('cta_sticky_quiz_open');
      return;
    }
    if (btn.closest('.site-footer')) {
      window.zhirafTrack('cta_footer_quiz_open');
      return;
    }
    var panel = btn.closest('.audience-tabs__panel');
    if (panel) {
      window.zhirafTrack('cta_audience_quiz_open', {
        segment: panel.getAttribute('data-audience-panel') || 'unknown',
      });
      return;
    }
    if (btn.closest('#lead-action')) {
      window.zhirafTrack('cta_lead_section_quiz_open');
      return;
    }
    if (btn.closest('.how-it-works')) {
      window.zhirafTrack('cta_section_quiz_open', { section: 'how-it-works' });
      return;
    }
    if (document.body.classList.contains('page-template-page-tour') || btn.closest('.tour-hub')) {
      window.zhirafTrack('cta_tour_hub_quiz_open');
      return;
    }
    if (document.body.classList.contains('page-template-page-o-nas')) {
      window.zhirafTrack('cta_about_quiz_open');
      return;
    }
    window.zhirafTrack('cta_section_quiz_open', { section: 'other' });
  }

  function messengerChannel(el) {
    var ch = el.getAttribute('data-channel');
    if (ch) return ch;
    var href = (el.getAttribute('href') || '').toLowerCase();
    if (href.indexOf('vk.com') !== -1) return 'vk';
    if (href.indexOf('t.me') !== -1 || href.indexOf('telegram') !== -1) return 'telegram';
    if (href.indexOf('wa.me') !== -1 || href.indexOf('whatsapp') !== -1) return 'whatsapp';
    return 'unknown';
  }

  document.addEventListener('click', function (e) {
    var phone = e.target.closest('a[data-track="phone"]');
    if (phone) {
      window.zhirafTrack('phone_click', { placement: phonePlacement(phone) });
      return;
    }
    var email = e.target.closest('a[data-track="email"]');
    if (email) {
      window.zhirafTrack('email_click', { placement: email.closest('.site-footer') ? 'footer' : 'other' });
      return;
    }
    var blog = e.target.closest('a[data-track="blog_teaser"]');
    if (blog) {
      window.zhirafTrack('blog_teaser_click');
      return;
    }
    var messenger = e.target.closest('a[data-track="messenger"]');
    if (!messenger) {
      var maybe = e.target.closest('a[href*="vk.com"], a[href*="t.me"], a[href*="wa.me"], a[href*="whatsapp"]');
      if (maybe && !maybe.getAttribute('data-track')) messenger = maybe;
    }
    if (messenger) {
      window.zhirafTrack('messenger_click', { channel: messengerChannel(messenger) });
    }
  });

  qsa('.dest-premium-card, .dest-card').forEach(function (card) {
    card.addEventListener('click', function () {
      var country = card.getAttribute('data-dest') || 'unknown';
      window.zhirafTrack('dest_card_click', { country: country });
    });
  });

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
    var cards = qsa('.dest-card, .dest-premium-card', section);
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
  qsa('.dest-tabs').forEach(function (bar) { bindDestFilter(bar, '.tab-btn'); });
  qsa('.chip-filter').forEach(function (bar) { bindDestFilter(bar, '.chip'); });

  /* Audience tabs */
  var audienceRoot = qs('.audience-tabs');
  if (audienceRoot) {
    var aTabs = qsa('.audience-tabs__tab', audienceRoot);
    var aPanels = qsa('.audience-tabs__panel', audienceRoot);
    aTabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var id = tab.getAttribute('data-audience');
        aTabs.forEach(function (t) {
          var on = t === tab;
          t.classList.toggle('is-active', on);
          t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        aPanels.forEach(function (panel) {
          var show = panel.getAttribute('data-audience-panel') === id;
          panel.classList.toggle('is-active', show);
          panel.hidden = !show;
        });
        if (id) {
          window.zhirafTrack('audience_tab_' + id);
        }
      });
    });
  }

  /* Sticky mobile CTA after trust block */
  var trustBlock = qs('.trust-social');
  var stickyCta = qs('#sticky-mobile-cta');
  if (trustBlock && stickyCta && window.matchMedia('(max-width: 768px)').matches) {
    var stickyObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting && entry.boundingClientRect.top < 0) {
          stickyCta.removeAttribute('hidden');
        } else {
          stickyCta.setAttribute('hidden', '');
        }
      });
    }, { threshold: 0 });
    stickyObs.observe(trustBlock);
  }

  /* Cookie banner */
  var banner = qs('#cookie-banner');
  var accept = qs('#cookie-accept');
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
  function trackQuizStep(stepIndex) {
    window.zhirafTrack('quiz_step_' + (stepIndex + 1), { step: stepIndex + 1 });
  }
  function openQuiz() {
    if (!modal || !quizForm) return;
    goStep(0);
    trackQuizStep(0);
    modal.removeAttribute('hidden');
    document.body.style.overflow = 'hidden';
  }
  function closeQuiz() {
    if (!modal) return;
    modal.setAttribute('hidden', '');
    document.body.style.overflow = '';
  }
  qsa('[data-quiz-open]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var prefill = btn.getAttribute('data-quiz-prefill');
      if (prefill && quizForm) {
        var wishes = qs('[name="quiz_wishes"]', quizForm);
        if (wishes && !wishes.value) {
          wishes.value = prefill;
        }
      }
      quizOpenGoal(btn);
      openQuiz();
    });
  });
  qsa('[data-quiz-close]').forEach(function (btn) {
    btn.addEventListener('click', closeQuiz);
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeQuiz();
  });

  var steps = qsa('.quiz-step', quizForm);
  var current = 0;
  function validateQuizStep(stepEl) {
    if (!stepEl) return false;
    var fields = qsa('input, select, textarea', stepEl).filter(function (f) {
      return f.type !== 'hidden' && !f.classList.contains('zhiraf-hp');
    });
    var ok = true;
    fields.forEach(function (field) {
      if (!field.checkValidity()) {
        ok = false;
        field.reportValidity();
      }
    });
    return ok;
  }
  function goStep(n) {
    steps.forEach(function (step, i) {
      var active = i === n;
      step.classList.toggle('is-active', active);
      step.hidden = !active;
    });
    current = n;
    var activeBtn = qs('.quiz-next', steps[n]);
    if (activeBtn) {
      activeBtn.disabled = false;
      activeBtn.removeAttribute('aria-busy');
    }
  }
  qsa('.quiz-next', quizForm).forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      btn.disabled = false;
      btn.removeAttribute('aria-busy');
      if (!validateQuizStep(steps[current])) {
        return;
      }
      if (current < steps.length - 1) {
        goStep(current + 1);
        trackQuizStep(current);
      }
    });
  });
  qsa('.quiz-prev', quizForm).forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (current > 0) goStep(current - 1);
    });
  });

  var leadFormStarted = {};
  qsa('.zhiraf-form').forEach(function (form) {
    var action = form.getAttribute('data-action');
    if (action !== 'zhiraf_lead') return;
    var ctx = form.id || form.getAttribute('data-form-context') || 'lead';
    qsa('input, textarea, select', form).forEach(function (field) {
      field.addEventListener('focus', function () {
        if (leadFormStarted[ctx]) return;
        leadFormStarted[ctx] = true;
        window.zhirafTrack('lead_form_start', { form_context: ctx });
      });
    });
  });

  function fieldValue(form, name) {
    var el = form.querySelector('[name="' + name + '"]');
    if (!el) return '';
    if (el.type === 'radio') {
      var checked = form.querySelector('[name="' + name + '"]:checked');
      return checked ? checked.value : '';
    }
    return el.value || '';
  }

  function trackFormSuccess(form, action) {
    if (action === 'zhiraf_quiz') {
      window.zhirafTrack('quiz_submit', {
        composition: fieldValue(form, 'quiz_composition'),
        month: fieldValue(form, 'quiz_month'),
        budget: fieldValue(form, 'quiz_budget'),
      });
      return;
    }
    if (action === 'zhiraf_lead') {
      var ctx = form.id || form.getAttribute('data-form-context') || 'lead';
      window.zhirafTrack('lead_form_submit', { form_context: ctx });
      return;
    }
    if (action === 'zhiraf_subscribe') {
      window.zhirafTrack('subscribe_submit');
    }
  }

  /* AJAX forms */
  function postForm(form, action) {
    var status = qs('.form-status', form);
    var submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.setAttribute('aria-busy', 'true');
    }
    var fd = new FormData(form);
    fd.append('action', action);
    fd.append('nonce', cfg.nonce || '');
    fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (status) {
          status.textContent = data.data && data.data.message ? data.data.message : (data.success ? 'OK' : 'Ошибка');
        }
        if (data.success) {
          trackFormSuccess(form, action);
          form.reset();
        }
      })
      .catch(function () {
        if (status) status.textContent = 'Ошибка сети';
      })
      .finally(function () {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.removeAttribute('aria-busy');
        }
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
