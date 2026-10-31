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
    var section = bar.closest('.section--unified-catalog') || bar.closest('.section--hot-tours') || bar.closest('.section--dest-tours') || bar.parentElement;
    var wraps = qsa('.dest-tour-card-wrap', section);
    var cards = qsa('.tour-card', section);
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (c) {
          c.classList.remove('is-active');
          c.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('is-active');
        tab.setAttribute('aria-selected', 'true');
        var filter = tab.getAttribute('data-tour-filter');
        function applyFilter(card) {
          var dest = card.getAttribute('data-dest') || '';
          if (filter === 'all') {
            return card.getAttribute('data-catalog-all') === '1';
          }
          if (filter === 'rossiya-domestic') {
            return dest === 'rossiya' || dest === 'abkhaziya';
          }
          var region = card.getAttribute('data-tour-region') || '';
          return dest === filter || (region && region === filter);
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
    var activeTab = tabs.filter(function (t) { return t.classList.contains('is-active'); })[0] || tabs[0];
    if (activeTab) {
      activeTab.click();
    }
  }
  qsa('.tour-tabs, .dest-tour-tabs').forEach(function (bar) {
    if (bar.id === 'tour-tabs') {
      return;
    }
    bindTourFilter(bar);
  });

  /* Homepage unified catalog: #tour-tabs + #tours-grid (data-filter / data-category) */
  function initHomeUnifiedCatalog() {
    var tabsContainer = document.getElementById('tour-tabs');
    var grid = document.getElementById('tours-grid');
    if (!tabsContainer || !grid) {
      return;
    }
    var cards = qsa('.tour-card', grid);
    if (!cards.length) {
      return;
    }
    var baseLimit = parseInt(grid.getAttribute('data-visible-limit') || '6', 10);
    var moreStep = parseInt(grid.getAttribute('data-more-step') || '3', 10);
    var maxVisible = parseInt(grid.getAttribute('data-max-visible') || '9', 10);
    var visibleLimit = baseLimit;
    var showMoreBtn = document.getElementById('catalog-show-more');
    var countryLink = document.getElementById('catalog-country-link');
    var countryLinkText = document.getElementById('catalog-country-link-text');
    var tabLinks = [];
    try {
      tabLinks = JSON.parse(grid.getAttribute('data-tab-links') || '[]');
    } catch (e) {
      tabLinks = [];
    }

    function setActiveTab(activeBtn) {
      qsa('.tab-btn', tabsContainer).forEach(function (btn) {
        var on = btn === activeBtn;
        btn.classList.toggle('is-active', on);
        btn.setAttribute('aria-selected', on ? 'true' : 'false');
      });
    }

    function cardMatches(card, filter) {
      if (filter === 'all') {
        return card.getAttribute('data-catalog-all') === '1';
      }
      return card.getAttribute('data-category') === filter;
    }

    function matchingCards(filter) {
      return cards
        .filter(function (card) {
          return cardMatches(card, filter);
        })
        .sort(function (a, b) {
          var pa = parseInt(a.getAttribute('data-priority') || '99', 10);
          var pb = parseInt(b.getAttribute('data-priority') || '99', 10);
          if (pa !== pb) {
            return pa - pb;
          }
          return 0;
        });
    }

    function showCard(card) {
      card.classList.add('is-catalog-shown');
      card.removeAttribute('hidden');
    }

    function hideCard(card) {
      card.classList.remove('is-catalog-shown');
      card.setAttribute('hidden', '');
    }

    function updateCountryLink(filter) {
      if (!countryLink || !countryLinkText) {
        return;
      }
      var meta = tabLinks.filter(function (t) {
        return t.id === filter;
      })[0];
      if (!meta) {
        return;
      }
      countryLink.href = meta.url || countryLink.href;
      countryLinkText.textContent = meta.cta || countryLinkText.textContent;
    }

    function refreshCatalogView(filter) {
      var matched = matchingCards(filter);
      matched.forEach(function (card, idx) {
        if (idx < visibleLimit) {
          showCard(card);
        } else {
          hideCard(card);
        }
      });
      cards.forEach(function (card) {
        if (matched.indexOf(card) === -1) {
          hideCard(card);
        }
      });
      if (showMoreBtn) {
        showMoreBtn.hidden = matched.length <= visibleLimit || visibleLimit >= maxVisible;
      }
      updateCountryLink(filter);
    }

    function filterCards(filter) {
      visibleLimit = baseLimit;
      refreshCatalogView(filter);
    }

    tabsContainer.addEventListener('click', function (e) {
      var clickedBtn = e.target.closest('.tab-btn');
      if (!clickedBtn || !tabsContainer.contains(clickedBtn)) {
        return;
      }
      var filterValue = clickedBtn.getAttribute('data-filter');
      if (!filterValue) {
        return;
      }
      setActiveTab(clickedBtn);
      filterCards(filterValue);
    });

    if (showMoreBtn) {
      showMoreBtn.addEventListener('click', function () {
        var active = qs('.tab-btn.is-active', tabsContainer);
        var filterValue = active ? active.getAttribute('data-filter') || 'all' : 'all';
        visibleLimit = Math.min(visibleLimit + moreStep, maxVisible);
        refreshCatalogView(filterValue);
      });
    }

    var defaultActive = qs('.tab-btn.is-active', tabsContainer) || qs('.tab-btn', tabsContainer);
    if (defaultActive) {
      setActiveTab(defaultActive);
      filterCards(defaultActive.getAttribute('data-filter') || 'all');
    }
  }
  initHomeUnifiedCatalog();

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

  /* Quiz wizard (inline #home-quiz + modal #quiz-form) */
  var modal = qs('#quiz-modal');
  var quizForm = qs('#quiz-form');
  var homeQuizSection = qs('#quiz') || qs('#home-quiz');

  function trackQuizStep(stepIndex) {
    window.zhirafTrack('quiz_step_' + (stepIndex + 1), { step: stepIndex + 1 });
  }

  function fieldValue(form, name) {
    var el = form.querySelector('[name="' + name + '"]');
    if (!el) return '';
    if (el.type === 'radio') {
      var checked = form.querySelector('[name="' + name + '"]:checked');
      return checked ? checked.value : '';
    }
    return el.value || '';
  }

  function syncQuizWishes(form) {
    var wishes = qs('[name="quiz_wishes"]', form);
    if (!wishes) return;
    var parts = [
      fieldValue(form, 'quiz_destination') ? 'Куда: ' + fieldValue(form, 'quiz_destination') : '',
      fieldValue(form, 'quiz_composition') ? 'Состав: ' + fieldValue(form, 'quiz_composition') : '',
      fieldValue(form, 'quiz_child_age') ? 'Ребёнок: ' + fieldValue(form, 'quiz_child_age') : '',
      fieldValue(form, 'quiz_timing') ? 'Срок: ' + fieldValue(form, 'quiz_timing') : '',
      fieldValue(form, 'quiz_duration') ? 'Ночей: ' + fieldValue(form, 'quiz_duration') : '',
      fieldValue(form, 'quiz_channel') ? 'Связь: ' + fieldValue(form, 'quiz_channel') : '',
    ].filter(Boolean);
    wishes.value = parts.join('. ');
    var month = qs('[name="quiz_month"]', form);
    if (month && fieldValue(form, 'quiz_timing')) {
      month.value = fieldValue(form, 'quiz_timing');
    }
  }

  function updateQuizSubmitLabel(form) {
    var btn = qs('[data-quiz-submit-label]', form);
    var channel = fieldValue(form, 'quiz_channel') || 'WhatsApp';
    if (!btn) return;
    if (channel === 'Telegram') {
      btn.textContent = 'Получить расчёт туров в Telegram →';
    } else {
      btn.textContent = 'Получить расчёт туров в мессенджер →';
    }
  }

  function initQuizWizard(form) {
    if (!form) return;
    if (form.dataset.quizWizardInit === '1') {
      return;
    }
    form.dataset.quizWizardInit = '1';
    var steps = qsa('.quiz-step', form);
    if (!steps.length) return;
    var current = 0;
    var progressBar = qs('.quiz-wizard__progress-bar', form);
    var progressLabel = qs('[data-quiz-progress-label]', form);

    var progressPercent = qs('[data-quiz-progress-percent]', form);
    var stepTitles = [
      'Шаг 1 из 4: Куда планируете полететь?',
      'Шаг 2 из 4: В каком составе летите?',
      'Шаг 3 из 4: Даты вылета и длительность',
      'Финальный шаг: Получение подборки',
    ];
    var stepPercents = ['25%', '50%', '75%', '100%'];

    function updateProgress(n) {
      if (progressBar) {
        progressBar.style.width = stepPercents[n] || '25%';
      }
      if (progressPercent) {
        progressPercent.textContent = stepPercents[n] || '25%';
      }
      if (progressLabel) {
        progressLabel.textContent = stepTitles[n] || stepTitles[0];
      }
    }

    function validateQuizStep(stepEl) {
      if (!stepEl) return false;
      var radioGroups = {};
      qsa('input[type="radio"]', stepEl).forEach(function (field) {
        if (field.required || field.hasAttribute('required')) {
          radioGroups[field.name] = true;
        }
      });
      var ok = true;
      Object.keys(radioGroups).forEach(function (name) {
        if (!form.querySelector('[name="' + name + '"]:checked')) {
          ok = false;
        }
      });
      qsa('input[type="tel"], select, textarea', stepEl).forEach(function (field) {
        if (field.required && !field.checkValidity()) {
          ok = false;
          field.reportValidity();
        }
      });
      return ok;
    }

    function goStep(stepNumber) {
      var n = stepNumber - 1;
      if (n < 0 || n >= steps.length) return;
      steps.forEach(function (step) {
        var num = parseInt(step.getAttribute('data-step') || '0', 10);
        var active = num === stepNumber;
        step.classList.toggle('is-active', active);
        step.hidden = !active;
      });
      current = n;
      updateProgress(n);
      trackQuizStep(n);
    }

    qsa('[data-quiz-go]', form).forEach(function (btn) {
      btn.addEventListener('click', function () {
        var target = parseInt(btn.getAttribute('data-quiz-go') || '1', 10);
        if (target > current + 1 && !validateQuizStep(steps[current])) {
          return;
        }
        goStep(target);
      });
    });

    qsa('input[name="quiz_channel"]', form).forEach(function (input) {
      input.addEventListener('change', function () {
        updateQuizSubmitLabel(form);
      });
    });

    form.addEventListener('submit', function () {
      syncQuizWishes(form);
    });

    goStep(1);
    updateQuizSubmitLabel(form);
  }

  qsa('.js-quiz-wizard').forEach(initQuizWizard);

  function openQuiz() {
    if (!modal || !quizForm) return;
    modal.removeAttribute('hidden');
    modal.removeAttribute('inert');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('quiz-open');
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

  function applyQuizPrefillToForm(form, btn) {
    if (!form) return;
    var vibeField = qs('[name="vibe"]', form);
    var vibeSlug = btn.getAttribute('data-vibe') || '';
    var prefill = btn.getAttribute('data-quiz-prefill') || '';
    if (vibeField) vibeField.value = vibeSlug;
    if (prefill) {
      var wishes = qs('[name="quiz_wishes"]', form);
      if (wishes) wishes.value = prefill;
    }
    var map = vibeQuizMap[vibeSlug] || null;
    if (map && map.composition) {
      var target = qs('[name="quiz_composition"][value="' + map.composition + '"]', form);
      if (target) target.checked = true;
    }
  }

  qsa('[data-quiz-open], [data-modal-open="quiz"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      quizOpenGoal(btn);
      if (homeQuizSection && btn.getAttribute('data-quiz-modal-only') !== '1') {
        var homeForm = qs('#home-quiz-form');
        applyQuizPrefillToForm(homeForm, btn);
        homeQuizSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        return;
      }
      applyQuizPrefillToForm(quizForm, btn);
      openQuiz();
    });
  });
  qsa('[data-quiz-close]').forEach(function (btn) {
    btn.addEventListener('click', closeQuiz);
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeQuiz();
  });

  qsa('[data-lead-channel]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var form = btn.closest('form');
      if (!form) return;
      var ch = qs('[name="contact_channel"]', form);
      if (ch) ch.value = btn.getAttribute('data-lead-channel') || '';
    });
  });

  function bindRuPhoneMask(input) {
    if (!input || input.dataset.phoneMaskBound === '1') return;
    input.dataset.phoneMaskBound = '1';
    input.addEventListener('input', function (e) {
      var val = e.target.value.replace(/\D/g, '');
      if (val.charAt(0) === '8') val = '7' + val.slice(1);
      if (val.charAt(0) !== '7') val = '7' + val;
      val = val.slice(0, 11);
      var parts = val.match(/^(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})$/);
      if (!parts) return;
      if (!parts[2]) {
        e.target.value = parts[1] ? '+7 (' : '';
        return;
      }
      e.target.value = '+7 (' + parts[2] + (parts[3] ? ') ' + parts[3] : '') + (parts[4] ? '-' + parts[4] : '') + (parts[5] ? '-' + parts[5] : '');
    });
  }

  qsa('[data-phone-mask]').forEach(bindRuPhoneMask);

  var leadMagnetForm = qs('#lead-magnet-form');
  if (leadMagnetForm) {
    leadMagnetForm.addEventListener('submit', function () {
      var picked = leadMagnetForm.querySelector('[name="lead_messenger"]:checked');
      var channel = qs('[name="contact_channel"]', leadMagnetForm);
      if (picked && channel) {
        channel.value = picked.value;
      }
      var dest = qs('[name="destination"]', leadMagnetForm);
      var msg = qs('[name="message"]', leadMagnetForm);
      if (dest && msg && dest.value.trim()) {
        msg.value = msg.value + '. ' + dest.value.trim();
      }
    });
  }

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

  qsa('[data-dest-season-module]').forEach(function (root) {
    var tabs = qsa('[data-season-tab]', root);
    var cards = qsa('[data-season-card]', root);
    if (!tabs.length || !cards.length) {
      return;
    }
    function activate(index) {
      tabs.forEach(function (tab, i) {
        var on = i === index;
        tab.classList.toggle('is-active', on);
        tab.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      cards.forEach(function (card, i) {
        var on = i === index;
        card.classList.toggle('is-active', on);
        if (on) {
          card.removeAttribute('hidden');
        } else {
          card.setAttribute('hidden', '');
        }
      });
    }
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var idx = parseInt(tab.getAttribute('data-season-index') || '0', 10);
        if (!isNaN(idx)) {
          activate(idx);
        }
      });
    });
    cards.forEach(function (card) {
      card.addEventListener('click', function () {
        var idx = parseInt(card.getAttribute('data-season-index') || '0', 10);
        if (!isNaN(idx)) {
          activate(idx);
        }
      });
    });
  });
})();
