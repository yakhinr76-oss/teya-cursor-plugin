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
    if (el.closest('.site-main--contacts')) return 'contacts';
    return 'other';
  }

  function quizOpenGoal(btn) {
    var ctx = btn.getAttribute('data-cta-context');
    if (ctx) {
      window.zhirafTrack(ctx, {
        hotel: btn.getAttribute('data-tour-hotel') || '',
        context: ctx,
      });
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
    // Any tel: link on the site (header, footer, contacts page, cards) counts as phone_click.
    var phone = e.target.closest('a[href^="tel:"]');
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
  qsa('.ai-meal-tabs').forEach(function (bar) {
    var tabs = qsa('.tab-btn', bar);
    var section = bar.closest('.section') || bar.parentElement;
    var cards = qsa('.tour-card', section);
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (c) { c.classList.remove('is-active'); });
        tab.classList.add('is-active');
        var filter = tab.getAttribute('data-filter');
        cards.forEach(function (card) {
          var dest = card.getAttribute('data-dest') || '';
          var show = filter === 'all';
          if (!show && filter === 'islands') {
            show = dest === 'tailand' || dest === 'vietnam';
          } else if (!show) {
            show = dest === filter;
          }
          card.classList.toggle('is-hidden', !show);
        });
      });
    });
  });
  qsa('.chip-filter').forEach(function (bar) {
    if (bar.classList.contains('chip-filter--offers')) {
      return;
    }
    bindDestFilter(bar, '.chip');
  });

  /* Tour price filter tabs */
  function bindTourFilter(bar) {
    var tabs = qsa('[data-tour-filter]', bar);
    var section = bar.closest('.section--hot-tours') || bar.closest('.section--dest-tours') || bar.parentElement;
    var wraps = qsa('.dest-tour-card-wrap', section);
    var cards = qsa('.tour-card', section);
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (c) { c.classList.remove('is-active'); });
        tab.classList.add('is-active');
        var filter = tab.getAttribute('data-tour-filter');
        function applyFilter(card) {
          var dest = card.getAttribute('data-dest');
          var region = card.getAttribute('data-tour-region') || '';
          return filter === 'all' || dest === filter || (region && region === filter);
        }
        if (wraps.length) {
          wraps.forEach(function (wrap) {
            var card = qs('.tour-card', wrap) || wrap;
            wrap.classList.toggle('is-hidden', !applyFilter(card));
          });
        } else {
          cards.forEach(function (card) {
            card.classList.toggle('is-hidden', !applyFilter(card));
          });
        }
      });
    });
  }
  qsa('.tour-tabs, .dest-tour-tabs').forEach(function (bar) { bindTourFilter(bar); });

  qsa('[data-tour-show-more]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var sel = btn.getAttribute('data-target');
      var grid = sel ? qs(sel) : null;
      if (!grid) return;
      qsa('.dest-tour-card-wrap.is-collapsed', grid).forEach(function (wrap) {
        wrap.classList.remove('is-collapsed');
      });
      btn.hidden = true;
    });
  });

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
    modal.removeAttribute('inert');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('quiz-open');
    var first = qs('.quiz-step.is-active input, .quiz-step.is-active select', quizForm);
    if (first) {
      try { first.focus({ preventScroll: true }); } catch (e) { /* ignore */ }
    }
  }
  function closeQuiz() {
    if (!modal) return;
    modal.setAttribute('hidden', '');
    modal.setAttribute('inert', '');
    document.body.style.overflow = '';
    document.body.classList.remove('quiz-open');
  }

  var vibeQuizMap = {
    family: { composition: 'Семья с детьми', wishTag: '' },
    couple: { composition: 'Пара', wishTag: '' },
    city: { composition: '', wishTag: '[Шопинг и движ]' },
    party: { composition: 'Компания друзей', wishTag: '[Шопинг и движ]' },
    active: { composition: '', wishTag: '[Активный отдых / Экскурсии]' },
  };

  function applyQuizVibeFromButton(btn) {
    if (!quizForm) return;
    var vibeField = qs('[name="vibe"]', quizForm);
    var vibeSlug = btn.getAttribute('data-vibe') || '';
    var prefill = btn.getAttribute('data-quiz-prefill') || '';
    var vibeTitle = btn.getAttribute('data-vibe-title') || '';
    var wishes = qs('[name="quiz_wishes"]', quizForm);

    qsa('[name="quiz_composition"]', quizForm).forEach(function (radio) {
      radio.checked = false;
    });
    if (vibeField) {
      vibeField.value = vibeSlug;
    }

    var map = vibeQuizMap[vibeSlug] || null;
    if (map && map.composition) {
      var target = qs('[name="quiz_composition"][value="' + map.composition + '"]', quizForm);
      if (target) {
        target.checked = true;
      }
    }

    if (wishes) {
      var parts = [];
      if (prefill) {
        parts.push(prefill);
      }
      if (vibeTitle) {
        parts.push('Вайб: ' + vibeTitle);
      }
      if (map && map.wishTag) {
        parts.push(map.wishTag);
      }
      wishes.value = parts.join('. ').trim();
    } else if (prefill && wishes) {
      wishes.value = prefill;
    }
  }

  function applyQuizPrefillOnly(btn) {
    if (!quizForm) return;
    var vibeField = qs('[name="vibe"]', quizForm);
    if (vibeField) {
      vibeField.value = '';
    }
    var prefill = btn.getAttribute('data-quiz-prefill');
    if (prefill) {
      var wishes = qs('[name="quiz_wishes"]', quizForm);
      if (wishes) {
        wishes.value = prefill;
      }
    }
  }

  qsa('[data-quiz-open], [data-modal-open="quiz"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (btn.getAttribute('data-vibe')) {
        applyQuizVibeFromButton(btn);
      } else {
        applyQuizPrefillOnly(btn);
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
      // Metrika goal id: quiz_submit (JS event). Fired only after server returned success.
      window.zhirafTrack('quiz_submit', {
        composition: fieldValue(form, 'quiz_composition'),
        month: fieldValue(form, 'quiz_month'),
        budget: fieldValue(form, 'quiz_budget'),
      });
      return;
    }
    if (action === 'zhiraf_lead') {
      var ctx = form.id || form.getAttribute('data-form-context') || 'lead';
      // Metrika goal id: lead_form (JS event). Fired only after server returned success.
      window.zhirafTrack('lead_form', { form_context: ctx });
      return;
    }
    if (action === 'zhiraf_subscribe') {
      window.zhirafTrack('subscribe_submit');
    }
  }

  /* AJAX forms */
  var PHONE_FALLBACK = 'Не удалось отправить. Позвоните нам: +7 901 810-50-18';
  function setStatus(status, text, ok) {
    if (!status) return;
    status.textContent = text;
    status.classList.toggle('is-success', ok === true);
    status.classList.toggle('is-error', ok === false);
  }
  function parseResponse(r) {
    return r.text().then(function (txt) {
      var data = null;
      try { data = JSON.parse(txt); } catch (e) { data = null; }
      if (!data || typeof data !== 'object') {
        // admin-ajax returns bare "-1" (bad nonce) or "0" (unknown action).
        data = { success: false, data: { code: txt === '-1' ? 'nonce' : 'bad_response' } };
      }
      return data;
    });
  }
  function refreshNonce() {
    var fd = new FormData();
    fd.append('action', 'zhiraf_form_nonce');
    return fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(parseResponse)
      .then(function (d) {
        if (d && d.success && d.data && d.data.nonce) {
          cfg.nonce = d.data.nonce;
          return true;
        }
        return false;
      });
  }
  function sendForm(form, action) {
    var msgField = form.querySelector('[name="message"]');
    if (msgField && form.classList.contains('hot-lead-form')) {
      var dest = fieldValue(form, 'hot_destination');
      var budget = fieldValue(form, 'hot_budget');
      var parts = [];
      if (dest) parts.push('Куда: ' + dest);
      if (budget) parts.push('Бюджет: ' + budget);
      if (parts.length) {
        msgField.value = parts.join('. ');
      }
    }
    if (msgField && form.classList.contains('ai-lead-form')) {
      var travelers = fieldValue(form, 'ai_travelers');
      var month = fieldValue(form, 'ai_month');
      var aiParts = [];
      if (travelers) aiParts.push('Состав: ' + travelers);
      if (month) aiParts.push('Месяц: ' + month);
      if (aiParts.length) {
        msgField.value = aiParts.join('. ');
      }
    }
    var fd = new FormData(form);
    fd.append('action', action);
    fd.append('nonce', cfg.nonce || '');
    return fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' }).then(parseResponse);
  }
  function postForm(form, action) {
    var status = qs('.form-status', form);
    var submitBtn = form.querySelector('button[type="submit"]');
    if (!cfg.ajaxUrl) {
      setStatus(status, PHONE_FALLBACK, false);
      return;
    }
    if (submitBtn) {
      if (submitBtn.disabled) return;
      submitBtn.disabled = true;
      submitBtn.setAttribute('aria-busy', 'true');
    }
    setStatus(status, 'Отправляем…', null);
    sendForm(form, action)
      .then(function (data) {
        if (!data.success && data.data && data.data.code === 'nonce') {
          return refreshNonce().then(function (ok) {
            return ok ? sendForm(form, action) : data;
          });
        }
        return data;
      })
      .then(function (data) {
        var msg = data.data && data.data.message ? data.data.message : (data.success ? 'Спасибо! Заявка отправлена.' : PHONE_FALLBACK);
        setStatus(status, msg, !!data.success);
        if (data.success) {
          trackFormSuccess(form, action);
          form.reset();
        }
      })
      .catch(function () {
        setStatus(status, 'Нет связи с сервером. ' + PHONE_FALLBACK, false);
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

  /* Destination express picker */
  var expressRoot = qs('[data-express-picker]');
  if (expressRoot) {
    var matrixRaw = expressRoot.getAttribute('data-matrix');
    var matrix = {};
    try { matrix = JSON.parse(matrixRaw || '{}'); } catch (e) { matrix = {}; }
    var who = '';
    var prio = '';
    var resultBox = qs('[data-express-result]', expressRoot);
    var areaEl = qs('[data-express-area]', expressRoot);
    var conceptEl = qs('[data-express-concept]', expressRoot);
    var waLink = qs('[data-express-wa]', expressRoot);
    var toFormBtn = qs('[data-express-to-form]', expressRoot);
    var basePrefill = (qs('#dest-express-prefill-base') || {}).value || '';

    function renderExpress() {
      if (!who || !prio || !matrix[who] || !matrix[who][prio]) return;
      var row = matrix[who][prio];
      if (areaEl) areaEl.textContent = row.area || '';
      if (conceptEl) conceptEl.textContent = row.concept || '';
      if (resultBox) resultBox.removeAttribute('hidden');
      var msg = 'Здравствуйте! Экспресс-подбор: ' + (row.prefill || '') + '. ' + (row.area || '') + ' — ' + (row.concept || '');
      if (waLink) waLink.setAttribute('href', 'https://wa.me/79018105018?text=' + encodeURIComponent(msg));
      expressRoot.dataset.expressPrefill = row.prefill || '';
    }

    qsa('[data-express-who]', expressRoot).forEach(function (btn) {
      btn.addEventListener('click', function () {
        who = btn.getAttribute('data-express-who') || '';
        qsa('[data-express-who]', expressRoot).forEach(function (b) { b.classList.toggle('is-active', b === btn); });
        renderExpress();
      });
    });
    qsa('[data-express-prio]', expressRoot).forEach(function (btn) {
      btn.addEventListener('click', function () {
        prio = btn.getAttribute('data-express-prio') || '';
        qsa('[data-express-prio]', expressRoot).forEach(function (b) { b.classList.toggle('is-active', b === btn); });
        renderExpress();
      });
    });
    if (toFormBtn) {
      toFormBtn.addEventListener('click', function () {
        var leadForm = qs('#dest-lead .zhiraf-form');
        var tourInput = leadForm ? qs('[data-lead-tour]', leadForm) : null;
        var expressInput = leadForm ? qs('[data-lead-express]', leadForm) : null;
        var pre = expressRoot.dataset.expressPrefill || '';
        if (tourInput && pre) tourInput.value = (basePrefill ? basePrefill + ' · ' : '') + pre;
        if (expressInput) expressInput.value = pre;
        var lead = qs('#dest-lead');
        if (lead) lead.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    }
  }

  qsa('[data-tier-prefill]').forEach(function (link) {
    link.addEventListener('click', function () {
      var tag = link.getAttribute('data-tier-prefill') || '';
      var leadForm = qs('#dest-lead .zhiraf-form');
      var tourInput = leadForm ? qs('[data-lead-tour]', leadForm) : null;
      if (tourInput && tag) tourInput.value = tag;
    });
  });

  var stickyDest = qs('#sticky-mobile-dest');
  var destHero = qs('.site-main--destination .dest-landing-hero, .site-main--resort .dest-landing-hero');
  if (stickyDest && destHero && window.matchMedia('(max-width: 768px)').matches) {
    var destStickyObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting && entry.boundingClientRect.top < 0) {
          stickyDest.removeAttribute('hidden');
        } else {
          stickyDest.setAttribute('hidden', '');
        }
      });
    }, { threshold: 0 });
    destStickyObs.observe(destHero);
  }
})();
