(function () {
  'use strict';

  var cfg = window.zhirafFeaturedHotels || {};
  var hotelsByTab = cfg.hotels || {};
  var defaultTab = cfg.defaultTab || 'turkey';
  var ctaLabel = cfg.ctaLabel || 'Узнать цену на мои даты в WhatsApp';
  var caveatLead = cfg.caveatLead || 'Важно:';

  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }

  function qsa(sel, root) {
    return Array.prototype.slice.call((root || document).querySelectorAll(sel));
  }

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function buildCard(hotel) {
    if (!hotel || !hotel.name) {
      return '';
    }
    var pros = Array.isArray(hotel.pros) ? hotel.pros.slice(0, 3) : [];
    var prosHtml = pros
      .map(function (pro) {
        return (
          '<li><span class="featured-hotel-card__check" aria-hidden="true">✓</span>' +
          escapeHtml(pro) +
          '</li>'
        );
      })
      .join('');
    var photo = hotel.photoUrl
      ? '<img class="featured-hotel-card__photo" src="' +
        escapeHtml(hotel.photoUrl) +
        '" alt="' +
        escapeHtml(hotel.name) +
        '" loading="lazy" decoding="async" width="640" height="360" />'
      : '';
    var badge = hotel.badge
      ? '<span class="featured-hotel-card__badge">' + escapeHtml(hotel.badge) + '</span>'
      : '';
    var rating = hotel.rating
      ? '<span class="featured-hotel-card__rating"><span aria-hidden="true">★</span> ' +
        escapeHtml(hotel.rating) +
        ' / 5.0</span>'
      : '';
    var caveat = hotel.caveat
      ? '<p class="featured-hotel-card__caveat"><strong>' +
        escapeHtml(caveatLead) +
        '</strong> ' +
        escapeHtml(hotel.caveat) +
        '</p>'
      : '';
    var wa = hotel.waUrl || '#';

    return (
      '<article class="featured-hotel-card">' +
      '<div class="featured-hotel-card__media aspect-video">' +
      photo +
      badge +
      '</div>' +
      '<div class="featured-hotel-card__body">' +
      '<div class="featured-hotel-card__meta">' +
      '<span class="featured-hotel-card__resort">' +
      escapeHtml(hotel.resort || '') +
      '</span>' +
      rating +
      '</div>' +
      '<h3 class="featured-hotel-card__title">' +
      escapeHtml(hotel.name) +
      '</h3>' +
      (hotel.usp ? '<p class="featured-hotel-card__usp">' + escapeHtml(hotel.usp) + '</p>' : '') +
      (prosHtml ? '<ul class="featured-hotel-card__pros">' + prosHtml + '</ul>' : '') +
      caveat +
      '<a class="btn btn--whatsapp featured-hotel-card__cta" href="' +
      escapeHtml(wa) +
      '" target="_blank" rel="noopener noreferrer" data-track="messenger" data-channel="whatsapp" data-cta-context="home_featured_hotel_wa">' +
      escapeHtml(ctaLabel) +
      '</a>' +
      '</div></article>'
    );
  }

  function renderGrid(grid, tabId) {
    var rows = hotelsByTab[tabId];
    if (!rows || rows.length !== 3) {
      return false;
    }
    grid.innerHTML = rows.map(buildCard).join('');
    return true;
  }

  function initFeaturedHotelsCatalog() {
    var tabsContainer = document.getElementById('tour-tabs');
    var grid = document.getElementById('featured-hotels-grid');
    if (!tabsContainer || !grid) {
      return;
    }

    var fadeMs = 220;
    var activeTab = defaultTab;

    function setActiveTab(btn) {
      qsa('.tab-btn', tabsContainer).forEach(function (b) {
        var on = b === btn;
        b.classList.toggle('is-active', on);
        b.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      if (btn) {
        grid.setAttribute('aria-labelledby', btn.id || '');
      }
    }

    function switchTab(tabId, clickedBtn) {
      if (!hotelsByTab[tabId] || tabId === activeTab) {
        if (clickedBtn) {
          setActiveTab(clickedBtn);
        }
        return;
      }
      grid.classList.remove('is-ready');
      grid.classList.add('is-fading');
      window.setTimeout(function () {
        if (renderGrid(grid, tabId)) {
          activeTab = tabId;
          if (clickedBtn) {
            setActiveTab(clickedBtn);
          }
        }
        grid.classList.remove('is-fading');
        requestAnimationFrame(function () {
          grid.classList.add('is-ready');
        });
      }, fadeMs);
    }

    tabsContainer.addEventListener('click', function (e) {
      var btn = e.target.closest('.tab-btn');
      if (!btn || !tabsContainer.contains(btn)) {
        return;
      }
      var tabId = btn.getAttribute('data-filter');
      if (!tabId) {
        return;
      }
      switchTab(tabId, btn);
    });

    var initial = grid.getAttribute('data-default-tab') || defaultTab;
    if (!grid.children.length) {
      renderGrid(grid, initial);
    }
    activeTab = initial;
    var activeBtn = qs('.tab-btn.is-active', tabsContainer) || qs('.tab-btn', tabsContainer);
    if (activeBtn) {
      setActiveTab(activeBtn);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFeaturedHotelsCatalog);
  } else {
    initFeaturedHotelsCatalog();
  }
})();
