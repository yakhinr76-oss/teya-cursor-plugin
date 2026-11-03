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

  function openQuizFromTrigger(btn) {
    var modal = qs('#quiz-modal');
    var form = qs('#quiz-form');
    if (!modal || !form) {
      return;
    }
    var fromScenario = applyScenarioPrefill(form, btn);
    setQuizStep(form, fromScenario ? 2 : 1);
    modal.removeAttribute('hidden');
    modal.removeAttribute('inert');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('quiz-open');
  }

  function init() {
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
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
