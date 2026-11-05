(function () {
  'use strict';

  var QUIZ_MODAL_SELECTION_CSS =
    '#quiz-modal{--quiz-terracotta:#c85a32;--quiz-ivory:#faf7f2;--quiz-border:#eae4dc;--quiz-muted:#524c48;color:#221f1e}' +
    '#quiz-modal .quiz-tile input:checked+.quiz-tile__inner,#quiz-modal .quiz-tile.is-quiz-selected .quiz-tile__inner,#quiz-modal .quiz-tile:has(input:checked) .quiz-tile__inner{border-color:#c85a32;background:#faf7f2;box-shadow:0 1px 3px rgba(200,90,50,.12)}' +
    '#quiz-modal .quiz-chip.is-quiz-selected,#quiz-modal .quiz-chip:has(input:checked){border-color:#c85a32;background:#faf7f2;color:#c85a32}' +
    '#quiz-modal .quiz-channel.is-quiz-selected,#quiz-modal .quiz-channel:has(input:checked){border-color:#c85a32;background:#faf7f2;color:#c85a32}' +
    '#quiz-modal .quiz-channel--wa.is-quiz-selected,#quiz-modal .quiz-channel--wa:has(input:checked){border-color:#25d366;background:rgba(37,211,102,.08);color:#128c7e}' +
    '#quiz-modal .quiz-channel--tg.is-quiz-selected,#quiz-modal .quiz-channel--tg:has(input:checked){border-color:#229ed9;background:rgba(34,158,217,.08);color:#229ed9}' +
    '#quiz-modal .quiz-channel--max.is-quiz-selected,#quiz-modal .quiz-channel--max:has(input:checked){border-color:#5b4bff;background:rgba(91,75,255,.08);color:#3d33cc}';

  function ensureQuizModalSelectionStyles() {
    if (
      document.getElementById('quiz-modal-selection-styles') ||
      document.getElementById('zhiraf-quiz-modal-selection-inline')
    ) {
      return;
    }
    var style = document.createElement('style');
    style.id = 'zhiraf-quiz-modal-selection-inline';
    style.textContent = QUIZ_MODAL_SELECTION_CSS;
    document.head.appendChild(style);
  }

  function qs(sel, ctx) {
    return (ctx || document).querySelector(sel);
  }
  function qsa(sel, ctx) {
    return Array.prototype.slice.call((ctx || document).querySelectorAll(sel));
  }

  function applyScenarioPrefill(form, btn) {
    var step1 = btn.getAttribute('data-quiz-step-1') || '';
    if (!step1) {
      return false;
    }
    var compositionMap = {
      'Семья с детьми': 'Семья с детьми',
      'Для пар / Релакс': 'Пара',
    };
    var comp = compositionMap[step1];
    if (comp) {
      var compInput = qs('[name="quiz_composition"][value="' + comp + '"]', form);
      if (compInput) {
        compInput.checked = true;
      }
    }
    var wishes = qs('[name="quiz_wishes"]', form);
    if (wishes) {
      wishes.value = 'Сценарий: ' + step1;
    }
    return true;
  }

  function setQuizStep(form, stepNumber) {
    if (typeof form._zhirafQuizGoStep === 'function') {
      form._zhirafQuizGoStep(stepNumber);
      return;
    }
    qsa('.quiz-step', form).forEach(function (step) {
      var num = parseInt(step.getAttribute('data-step') || '0', 10);
      var active = num === stepNumber;
      step.classList.toggle('is-active', active);
      step.hidden = !active;
    });
    var percents = { 1: '25%', 2: '50%', 3: '75%', 4: '100%' };
    var bar = qs('.quiz-wizard__progress-bar', form);
    var pct = qs('[data-quiz-progress-percent]', form);
    if (bar) {
      bar.style.width = percents[stepNumber] || '25%';
    }
    if (pct) {
      pct.textContent = percents[stepNumber] || '25%';
    }
  }

  function refreshQuizSelectionHighlight(form) {
    if (!form) {
      return;
    }
    qsa('.quiz-tile, .quiz-chip, .quiz-channel', form).forEach(function (el) {
      el.classList.remove('is-quiz-selected');
    });
    qsa('input[type="radio"]:checked', form).forEach(function (input) {
      var wrap = input.closest('.quiz-tile, .quiz-chip, .quiz-channel');
      if (wrap) {
        wrap.classList.add('is-quiz-selected');
      }
    });
  }

  function bindQuizSelectionHighlight(form) {
    if (!form || form.dataset.quizHighlightBound === '1') {
      return;
    }
    form.dataset.quizHighlightBound = '1';
    form.addEventListener('change', function () {
      refreshQuizSelectionHighlight(form);
    });
    refreshQuizSelectionHighlight(form);
  }

  function openQuizFromTrigger(btn) {
    var modal = qs('#quiz-modal');
    var form = qs('#quiz-form');
    if (!modal || !form) {
      return;
    }
    ensureQuizModalSelectionStyles();
    bindQuizSelectionHighlight(form);
    var fromScenario = applyScenarioPrefill(form, btn);
    setQuizStep(form, fromScenario ? 2 : 1);
    refreshQuizSelectionHighlight(form);
    modal.removeAttribute('hidden');
    modal.removeAttribute('inert');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('quiz-open');
  }

  function init() {
    ensureQuizModalSelectionStyles();
    bindQuizSelectionHighlight(qs('#quiz-form'));

    qsa('[data-quiz-trigger]').forEach(function (btn) {
      if (btn.dataset.quizTriggerBound === '1') {
        return;
      }
      btn.dataset.quizTriggerBound = '1';
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        openQuizFromTrigger(btn);
      });
    });

    qsa('[data-quiz-open], [data-modal-open="quiz"]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        window.setTimeout(function () {
          bindQuizSelectionHighlight(qs('#quiz-form'));
          refreshQuizSelectionHighlight(qs('#quiz-form'));
        }, 0);
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
