(function () {
  'use strict';

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
