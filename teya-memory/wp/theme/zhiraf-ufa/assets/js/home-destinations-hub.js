(function () {
  'use strict';

  function initDestinationsHub() {
    var root = document.getElementById('destinations-hub');
    if (!root) {
      return;
    }
    var tabs = root.querySelectorAll('[data-departure-tab]');
    var grids = root.querySelectorAll('[data-departure-grid]');
    if (!tabs.length || !grids.length) {
      return;
    }

    function activate(departure) {
      tabs.forEach(function (tab) {
        var on = tab.getAttribute('data-departure-tab') === departure;
        tab.classList.toggle('is-active', on);
        tab.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      grids.forEach(function (grid) {
        var on = grid.getAttribute('data-departure-grid') === departure;
        grid.classList.toggle('is-active', on);
        if (on) {
          grid.removeAttribute('hidden');
        } else {
          grid.setAttribute('hidden', 'hidden');
        }
      });
    }

    root.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-departure-tab]');
      if (!btn || !root.contains(btn)) {
        return;
      }
      var dep = btn.getAttribute('data-departure-tab');
      if (dep) {
        activate(dep);
      }
    });

    activate('ufa');
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDestinationsHub);
  } else {
    initDestinationsHub();
  }
})();
