/**
 * Regenerate preview/home.html to match current front-page.php structure (static).
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dir = path.dirname(fileURLToPath(import.meta.url));
const img = (name) => `../wp-theme/zhiraf-ufa/assets/images/${name}`;
const today = new Date().toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' });

function bentoTile({ mod, slug, title, image, kicker, label, icon, meta, price, href }) {
  const kickerHtml = kicker ? `<span class="hot-bento__kicker">${kicker}</span>` : '';
  const priceHtml = price
    ? `<p class="hot-bento__sub">от ${price} ₽ за 2 взрослых</p>`
    : `<p class="hot-bento__sub">уточните актуальную стоимость у менеджера</p>`;
  const metaHtml = meta ? `<p class="hot-bento__meta">${meta}</p>` : '';
  return `
        <article class="hot-bento__tile hot-bento__tile--${mod}">
          <img class="hot-bento__img" src="${img(image)}" alt="${title}" loading="lazy" />
          <span class="hot-bento__shade" aria-hidden="true"></span>
          <span class="hot-bento__label"><span class="hot-bento__icon" aria-hidden="true">${icon}</span>${label}</span>
          <div class="hot-bento__body">
            ${kickerHtml}
            <h3 class="hot-bento__title">${title}</h3>
            ${metaHtml}
            ${priceHtml}
            <a class="btn btn--primary btn--sm hot-bento__cta" href="${href}">Смотреть туры<span aria-hidden="true"> →</span></a>
          </div>
        </article>`;
}

const ufaBento = `
      <h3 class="hot-bento__group">Из Уфы</h3>
      <div class="hot-bento hot-bento--ufa">
        ${bentoTile({ mod: 'wide', slug: 'turciya', title: 'Турция', image: 'country-turciya.jpg', icon: '✈', label: 'Прямой из Уфы', meta: '7 ночей · Grand Ant Hotel 3* · AO', price: '89 851', href: 'tour-turciya.html' })}
        ${bentoTile({ mod: 'tall', slug: 'egipet', title: 'Египет', image: 'country-egipet.jpg', icon: '✈', label: 'Прямой из Уфы', meta: '6 ночей · Minamark Beach 4* · AI', price: '112 400', href: 'tour-egipet.html' })}
        ${bentoTile({ mod: 'small', slug: 'oae', title: 'ОАЭ', image: 'country-oae.jpg', icon: '✈', label: 'Прямой из Уфы', meta: '5 ночей · Al Seef 3* · BB', price: '99 760', href: 'tour-oae.html' })}
        ${bentoTile({ mod: 'small', slug: 'tailand', title: 'Таиланд', image: 'country-tailand.jpg', icon: '↔', label: 'Со стыковкой в Москве', meta: '10 ночей · 4* · AI', price: '156 200', href: 'tour-tailand.html' })}
        ${bentoTile({ mod: 'banner', slug: 'vietnam', title: 'Вьетнам', image: 'country-vietnam.jpg', icon: '↔', label: 'Со стыковкой в Москве', meta: '11 ночей · 4* · BB', price: '148 900', href: 'tour-vietnam.html' })}
      </div>
      <h3 class="hot-bento__group">Из Москвы</h3>
      <p class="hot-bento__group-note">Цены указаны за прямой вылет из Москвы. Поможем состыковать с удобным рейсом из аэропорта «Уфа» и посчитаем полную смету до договора.</p>
      <div class="hot-bento hot-bento--mow">
        ${bentoTile({ mod: 'wide', slug: 'maldivy', title: 'Мальдивы', image: 'country-maldivy.jpg', icon: '✈', label: 'Из Москвы', meta: '7 ночей · 5* · AI', price: '312 000', href: 'tour.html' })}
        ${bentoTile({ mod: 'small', slug: 'sejshely', title: 'Сейшелы', image: 'country-sejshely.jpg', icon: '✈', label: 'Из Москвы', meta: '9 ночей · 4* · BB', price: '285 500', href: 'tour.html' })}
        ${bentoTile({ mod: 'small', slug: 'shri-lanka', title: 'Шри-Ланка', image: 'country-shri-lanka.jpg', icon: '✈', label: 'Из Москвы', meta: '10 ночей · 4* · HB', price: '198 400', href: 'tour.html' })}
        ${bentoTile({ mod: 'small', slug: 'indoneziya', title: 'Бали', kicker: 'Индонезия', image: 'country-indoneziya.jpg', icon: '✈', label: 'Из Москвы', meta: '12 ночей · 4* · BB', price: '176 800', href: 'tour.html' })}
        ${bentoTile({ mod: 'wide', slug: 'indiya', title: 'Гоа', kicker: 'Индия', image: 'country-indiya.jpg', icon: '✈', label: 'Из Москвы', meta: '9 ночей · 3* · HB', price: '142 300', href: 'tour.html' })}
        ${bentoTile({ mod: 'small', slug: 'kitaj', title: 'Санья', kicker: 'Китай, о. Хайнань', image: 'country-kitaj.jpg', icon: '✈', label: 'Из Москвы', meta: '8 ночей · 4* · BB', price: '165 900', href: 'tour.html' })}
        ${bentoTile({ mod: 'wide', slug: 'uzbekistan', title: 'Узбекистан', image: 'country-uzbekistan.jpg', icon: '✈', label: 'Из Москвы', meta: '6 ночей · 4* · BB', price: '98 200', href: 'tour.html' })}
        ${bentoTile({ mod: 'wide', slug: 'armeniya', title: 'Армения', image: 'country-armeniya.jpg', icon: '✈', label: 'Из Москвы', meta: '5 ночей · 3* · BB', price: '72 500', href: 'tour.html' })}
      </div>`;

const html = `<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Жираф Уфа — preview главной</title>
  <link rel="stylesheet" href="../wp-theme/zhiraf-ufa/assets/dist/style.css" />
  <style>
    @font-face{font-family:'Manrope';src:url('../wp-theme/zhiraf-ufa/assets/fonts/manrope/Manrope-Cyrillic-Variable.woff2') format('woff2');font-weight:200 800;font-style:normal;font-display:swap;}
    @font-face{font-family:'Manrope';src:url('../wp-theme/zhiraf-ufa/assets/fonts/manrope/Manrope-Variable.woff2') format('woff2');font-weight:200 800;font-style:normal;font-display:swap;}
    @font-face{font-family:'Cormorant Garamond';src:url('../wp-theme/zhiraf-ufa/assets/fonts/cormorant-garamond/CormorantGaramond-SemiBold.woff2') format('woff2');font-weight:600;font-style:normal;font-display:swap;}
    @font-face{font-family:'Cormorant Garamond';src:url('../wp-theme/zhiraf-ufa/assets/fonts/cormorant-garamond/CormorantGaramond-Bold.woff2') format('woff2');font-weight:700;font-style:normal;font-display:swap;}
  </style>
</head>
<body>
<header class="site-header">
  <div class="container site-header__inner">
    <div class="site-branding">
      <a href="#"><img src="${img('logo-zhiraf-client.png')}" alt="Жираф" width="167" height="56" style="height:40px;width:auto"></a>
    </div>
    <nav class="site-nav" aria-label="Главная навигация">
      <ul class="primary-menu" style="display:flex;gap:32px">
        <li><a href="tour.html">Туры</a></li>
        <li><a href="o-nas.html">О нас</a></li>
        <li><a href="blog.html">Блог</a></li>
        <li><a href="kontakty.html">Контакты</a></li>
      </ul>
    </nav>
    <div class="site-header__actions">
      <a class="header-phone" href="tel:+79018105018">+7 901 810-50-18</a>
      <button type="button" class="btn btn--outline btn--sm" data-quiz-open>Подобрать тур</button>
    </div>
  </div>
</header>

<main id="primary" class="site-main site-main--home">

  <section class="hero-premium" data-motion="hero">
    <div class="container hero-premium__inner">
      <div class="hero-premium__content reveal">
        <h1 class="hero-premium__title">Туры с вылетом из Уфы — подбор без давления</h1>
        <p class="hero-premium__subtitle">Подберём 2–4 проверенных варианта с вылетом из Уфы — с фиксацией цены в договоре и сопровождением до возвращения. Офис на Гагарина, 74/1.</p>
        <ul class="hero-trust-strip" aria-label="Гарантии агентства">
          <li>РТА 0012822</li>
          <li>С 2011 года</li>
          <li>Договор и чек</li>
        </ul>
        <div class="hero-premium__actions">
          <button type="button" class="btn btn--primary btn--lg" data-quiz-open>Подобрать тур</button>
        </div>
      </div>
      <div class="hero-premium__collage reveal reveal-delay-1" data-three-canvas="hero-ambient">
        <div class="hero-collage__img hero-collage__img--1 collage-tile">
          <img src="${img('hero-uae.jpg')}" alt="ОАЭ" width="800" height="600" loading="eager" />
        </div>
        <div class="hero-collage__img hero-collage__img--2 collage-tile">
          <img src="${img('hero-turkey.jpg')}" alt="Турция" width="800" height="600" loading="eager" />
        </div>
        <div class="hero-collage__img hero-collage__img--3 collage-tile">
          <img src="${img('hero-egypt.jpg')}" alt="Египет" width="800" height="600" loading="eager" />
        </div>
        <svg class="hero-collage__flight-path" viewBox="0 0 400 200" fill="none" aria-hidden="true">
          <path d="M10,180 Q100,50 380,80" stroke="var(--color-primary)" stroke-width="2" stroke-dasharray="6,6" fill="none"/>
          <path d="M370,75 L385,81 L370,87 Z" fill="var(--color-primary)" />
        </svg>
      </div>
    </div>
  </section>

  <section class="section section--audience bg-milky" id="audience-tabs">
    <div class="container">
      <div class="section-head reveal">
        <h2 class="section-title">Кому подойдёт наш подбор</h2>
      </div>
      <div class="audience-tabs">
        <div class="audience-tabs__nav" role="tablist">
          <button type="button" class="audience-tabs__tab is-active" data-audience="family">Семья с детьми</button>
          <button type="button" class="audience-tabs__tab" data-audience="couple">Пара</button>
          <button type="button" class="audience-tabs__tab" data-audience="cautious">Первая заграница</button>
          <button type="button" class="audience-tabs__tab" data-audience="experienced">Опытные путешественники</button>
        </div>
        <div class="audience-tabs__panel is-active" data-audience-panel="family">
          <ul class="audience-tabs__list">
            <li>Подберём отель с детской инфраструктурой и удобным перелётом из Уфы</li>
            <li>Объясним, что входит в пакет — без сюрпризов по питанию и трансферу</li>
            <li>Менеджер на связи до вылета и в поездке</li>
          </ul>
          <div class="audience-tabs__actions">
            <button type="button" class="btn btn--primary" data-quiz-open>Подобрать семейный тур</button>
            <a class="audience-tabs__link" href="tour.html">Подробнее</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--how-it-works">
    <div class="container">
      <div class="section-head reveal">
        <h2 class="section-title">Как мы подбираем тур: пошаговый процесс</h2>
        <p class="lead">От заявки и пожеланий до контроля вылета и сопровождения 24/7.</p>
      </div>
      <div class="steps-row">
        <div class="step-card reveal"><div class="step-number">01</div><h3>Заявка и пожелания</h3><p>Оставляете заявку или проходите квиз. Уточняем состав семьи, даты, бюджет и пожелания.</p></div>
        <div class="step-card reveal reveal-delay-1"><div class="step-number">02</div><h3>Экспертный подбор</h3><p>Сравниваем операторов и готовим 2–4 проверенных варианта с вылетом из Уфы.</p></div>
        <div class="step-card reveal reveal-delay-2"><div class="step-number">03</div><h3>Договор и оплата</h3><p>Официальный договор онлайн или в офисе на Гагарина, 74/1. Оплата по безналу или эквайрингу с чеком.</p></div>
        <div class="step-card reveal reveal-delay-3"><div class="step-number">04</div><h3>Контроль вылета 24/7</h3><p>Выдаём ваучеры, отслеживаем рейс из аэропорта «Уфа» и остаёмся на связи до возвращения.</p></div>
      </div>
      <p class="steps-cta reveal"><button type="button" class="btn btn--outline" data-quiz-open>Подобрать тур</button></p>
    </div>
  </section>

  <section class="section section--home-team bg-milky" id="home-team">
    <div class="container">
      <div class="section-head reveal">
        <h2 class="section-title">Кто подберёт ваш тур</h2>
        <p class="lead">Семейное агентство в Уфе с 2011 года — живые менеджеры, а не безликий поиск.</p>
      </div>
      <div class="home-team-grid">
        <article class="home-team-card reveal">
          <div class="home-team-card__photo"><img class="home-team-card__img" src="${img('office-ufa.jpg')}" alt="Рафаэль" loading="lazy" /></div>
          <div class="home-team-card__body"><h3 class="home-team-card__name">Рафаэль</h3><p class="home-team-card__role">Директор</p><p class="home-team-card__desc">Контроль качества сервиса, договоры и партнёрство с туроператорами.</p></div>
        </article>
        <article class="home-team-card reveal">
          <div class="home-team-card__photo"><img class="home-team-card__img" src="${img('team-travel.jpg')}" alt="Лиана" loading="lazy" /></div>
          <div class="home-team-card__body"><h3 class="home-team-card__name">Лиана</h3><p class="home-team-card__role">Менеджер по туризму</p><p class="home-team-card__desc">Турция и Египет, All Inclusive, семейный отдых с детьми.</p></div>
        </article>
        <article class="home-team-card reveal">
          <div class="home-team-card__photo"><img class="home-team-card__img" src="${img('travel-passport.jpg')}" alt="Алина" loading="lazy" /></div>
          <div class="home-team-card__body"><h3 class="home-team-card__name">Алина</h3><p class="home-team-card__role">Менеджер по туризму</p><p class="home-team-card__desc">Таиланд, Вьетнам, ОАЭ, стыковочные перелёты и визовые правила.</p></div>
        </article>
      </div>
      <p class="text-center reveal" style="margin-top:24px;"><a class="btn btn--outline" href="o-nas.html">Вся команда</a></p>
    </div>
  </section>

  <section class="section section--trust trust-social">
    <div class="container trust-social__grid">
      <div class="trust-social__content reveal">
        <h2 class="section-title">Гарантии и работа агентства «Жираф»</h2>
        <p class="lead" style="margin-top:12px;margin-bottom:16px;">Турагентство «Жираф» — семейная компания в Уфе с апреля 2011 года. Работаем открыто и строго по закону.</p>
        <ul class="trust-social__facts">
          <li><strong>Официальный статус:</strong> ООО «Жираф», реестровый номер РТА 0012822.</li>
          <li><strong>Защита туристов:</strong> работа строго по 132-ФЗ и 152-ФЗ.</li>
          <li><strong>Туроператор:</strong> цены и бронирование — «Библио-Глобус».</li>
          <li><strong>Оплата и чеки:</strong> официальный договор, безналичная и эквайринг-оплата с чеком.</li>
          <li><strong>Офис в Уфе:</strong> ул. Юрия Гагарина, 74/1, офис 3.</li>
        </ul>
        <div class="trust-social__links">
          <a href="tel:+79018105018">+7 901 810-50-18</a>
          <a href="https://vk.com/zhiraf_ufa" rel="noopener noreferrer" target="_blank">Мы ВКонтакте</a>
          <a href="kontakty.html">Контакты и карта</a>
        </div>
      </div>
      <div class="trust-social__visual reveal reveal-delay-1">
        <img class="trust-social__img" src="${img('office-ufa.jpg')}" alt="Офис турагентства Жираф в Уфе" loading="lazy" />
        <img class="trust-social__img" src="${img('team-travel.jpg')}" alt="Команда турагентства «Жираф»" loading="lazy" />
      </div>
    </div>
  </section>

  <section class="section section--reviews-placeholder bg-sand" id="reviews-placeholder">
    <div class="container reveal">
      <div class="reviews-placeholder-card" style="background:var(--color-white);border:1px solid var(--color-border);border-radius:var(--radius-lg);padding:40px;text-align:center;max-width:860px;margin:0 auto;">
        <h2 class="section-title" style="font-size:2rem;margin-bottom:12px;">Отзывы туристов из Уфы</h2>
        <p style="color:var(--color-muted);font-size:1.0625rem;line-height:1.6;margin-bottom:24px;">Мы не публикуем заказные отзывы на сайте. Реальные оценки туристов из Уфы и отзывы с рейтингом доступны на Яндекс.Картах, в 2ГИС и ВКонтакте.</p>
        <div style="display:flex;justify-content:center;gap:16px;flex-wrap:wrap;">
          <a href="https://yandex.ru/maps" rel="noopener noreferrer" target="_blank" class="btn btn--outline btn--sm">Рейтинг Яндекс.Карт</a>
          <a href="https://2gis.ru/ufa" rel="noopener noreferrer" target="_blank" class="btn btn--outline btn--sm">Рейтинг 2ГИС</a>
          <a href="https://vk.com/zhiraf_ufa" rel="noopener noreferrer" target="_blank" class="btn btn--outline btn--sm">ВКонтакте</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--destinations bg-milky">
    <div class="container destinations">
      <div class="section-head reveal">
        <h2 class="section-title">Направления и вылеты из Уфы</h2>
        <div class="section-tabs dest-tabs">
          <button type="button" class="tab-btn is-active" data-filter="all">Популярные</button>
          <button type="button" class="tab-btn" data-filter="oae">ОАЭ</button>
          <button type="button" class="tab-btn" data-filter="turciya">Турция</button>
          <button type="button" class="tab-btn" data-filter="egipet">Египет</button>
          <button type="button" class="tab-btn" data-filter="tailand">Таиланд</button>
          <button type="button" class="tab-btn" data-filter="vietnam">Вьетнам</button>
        </div>
      </div>
      <div class="dest-premium-row">
        <a href="tour-turciya.html" class="dest-premium-card dest-card" data-dest="turciya"><div class="dest-premium-card__bg"><img class="dest-premium-card__img" src="${img('dest-turkey.jpg')}" alt="Турция" loading="lazy" /></div><div class="dest-premium-card__content"><h3 class="dest-premium-card__title">Турция</h3><p class="dest-premium-card__subtitle">Туры из Уфы</p></div></a>
        <a href="tour-oae.html" class="dest-premium-card dest-card" data-dest="oae"><div class="dest-premium-card__bg"><img class="dest-premium-card__img" src="${img('dest-uae.jpg')}" alt="ОАЭ" loading="lazy" /></div><div class="dest-premium-card__content"><h3 class="dest-premium-card__title">ОАЭ</h3><p class="dest-premium-card__subtitle">Туры из Уфы</p></div></a>
        <a href="tour-egipet.html" class="dest-premium-card dest-card" data-dest="egipet"><div class="dest-premium-card__bg"><img class="dest-premium-card__img" src="${img('dest-egypt.jpg')}" alt="Египет" loading="lazy" /></div><div class="dest-premium-card__content"><h3 class="dest-premium-card__title">Египет</h3><p class="dest-premium-card__subtitle">Туры из Уфы</p></div></a>
        <a href="tour-tailand.html" class="dest-premium-card dest-card" data-dest="tailand"><div class="dest-premium-card__bg"><img class="dest-premium-card__img" src="${img('dest-thailand.jpg')}" alt="Таиланд" loading="lazy" /></div><div class="dest-premium-card__content"><h3 class="dest-premium-card__title">Таиланд</h3><p class="dest-premium-card__subtitle">Туры из Уфы</p></div></a>
        <a href="tour-vietnam.html" class="dest-premium-card dest-card" data-dest="vietnam"><div class="dest-premium-card__bg"><img class="dest-premium-card__img" src="${img('dest-vietnam.jpg')}" alt="Вьетнам" loading="lazy" /></div><div class="dest-premium-card__content"><h3 class="dest-premium-card__title">Вьетнам</h3><p class="dest-premium-card__subtitle">Туры из Уфы</p></div></a>
      </div>
      <div class="dest-flight-logistics reveal" style="margin-top:40px;background:var(--color-white);border-radius:var(--radius-lg);padding:32px;border:1px solid var(--color-border);">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;">
          <div><h3 style="font-size:1.25rem;margin-bottom:8px;">Перелёт из Уфы</h3><p style="color:var(--color-muted);margin:0;font-size:0.9375rem;">Турция, Египет, ОАЭ: в зависимости от даты — прямой рейс или стыковка в Москве. Тип перелёта указан на каждой карточке по данным «Библио-Глобус».</p></div>
          <div><h3 style="font-size:1.25rem;margin-bottom:8px;">Направления со стыковкой через Москву</h3><p style="color:var(--color-muted);margin:0;font-size:0.9375rem;">Таиланд и Вьетнам: сейчас туроператор «Библио-Глобус» предлагает маршруты из Уфы с пересадкой в Москве. Поможем подобрать удобные стыковки.</p></div>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--hot-tours bg-sand" id="hot-tours">
    <div class="container">
      <div class="section-head reveal">
        <div>
          <h2 class="section-title">Горящие туры из Уфы и Москвы</h2>
          <p class="lead" style="margin-top:8px;margin-bottom:0;color:var(--color-muted);">Минимальные цены туроператора «Библио-Глобус» за пакет на 2 взрослых (перелёт и отель). На карточке — состав тура по самому доступному предложению на сегодня. Обновление каждые 6 часов.</p>
        </div>
      </div>
      <p class="hot-bento-disclaimer reveal">Цены «от» — ориентир туроператора на ${today}. Итоговую стоимость с учётом дат, отеля и перелёта фиксируем в договоре перед оплатой.</p>
      ${ufaBento}
    </div>
  </section>

  <section class="section section--offers-wordstat bg-milky">
    <div class="container">
      <div class="section-head reveal">
        <h2 class="section-title">Популярные форматы отдыха из Уфы</h2>
        <p class="lead">Подбираем проверенные туры под ваш формат поездки: от срочных горящих предложений до спокойного семейного отдыха «всё включено».</p>
      </div>
      <div class="chip-filter chip-filter--offers">
        <a class="chip chip--link" href="goryashchie-tury.html">Горящие туры</a>
        <a class="chip chip--link" href="vse-vklyucheno.html">Всё включено</a>
        <a class="chip chip--link" href="tour.html">Каталог направлений</a>
      </div>
      <p class="text-center reveal"><button type="button" class="btn btn--primary" data-quiz-open>Подобрать тур</button></p>
    </div>
  </section>

  <section class="section section--faq faq-home bg-milky" id="faq-home">
    <div class="container">
      <div class="section-head reveal"><h2 class="section-title">Частые вопросы</h2></div>
      <div class="faq-home__list">
        <details class="faq-home__item reveal"><summary class="faq-home__question">Как заказать подбор тура в агентстве «Жираф» в Уфе?</summary><div class="faq-home__answer"><p>Пройдите короткий 4-шаговый квиз на сайте или оставьте телефон. Менеджер уточнит состав семьи, предпочтения по перелёту и курорту и подготовит 2–4 проверенных варианта с актуальными ценами.</p></div></details>
        <details class="faq-home__item reveal"><summary class="faq-home__question">Откуда на сайте цены «от»?</summary><div class="faq-home__answer"><p>Это минимальные цены туроператора «Библио-Глобус» за 2 взрослых на дату, указанную рядом с ценой. Тарифы и места меняются в реальном времени, поэтому окончательную стоимость фиксируем в официальном договоре.</p></div></details>
        <details class="faq-home__item reveal"><summary class="faq-home__question">Есть ли прямые рейсы из Уфы?</summary><div class="faq-home__answer"><p>По данным туроператора «Библио-Глобус», в Турцию, Египет и ОАЭ в зависимости от даты есть туры как с прямым рейсом, так и со стыковкой в Москве. Тип перелёта указан на каждой карточке тура.</p></div></details>
        <details class="faq-home__item reveal"><summary class="faq-home__question">Как организован перелёт в Таиланд и Вьетнам из Уфы?</summary><div class="faq-home__answer"><p>Сейчас туроператор «Библио-Глобус» формирует маршруты на Пхукет, в Паттайю, Нячанг и на Фукуок с комфортной пересадкой в Москве. Перелёт входит в пакетный тур — поможем подобрать рейсы с удобным временем стыковки.</p></div></details>
      </div>
    </div>
  </section>

  <section class="section section--form lead-action" id="lead-action">
    <div class="container reveal">
      <div class="form-premium">
        <div class="form-premium__text"><h2 class="section-title">Оставьте заявку на подбор</h2><p>Перезвоним в рабочее время и уточним детали поездки.</p></div>
        <form class="zhiraf-form"><div class="form-premium__fields"><input type="text" class="form-input" placeholder="Ваше имя" required /><input type="tel" class="form-input" placeholder="Телефон" required /><button type="submit" class="btn btn--primary">Отправить</button></div></form>
      </div>
    </div>
  </section>

  <section class="section section--editorial editorial-blog-teaser bg-milky">
    <div class="container">
      <div class="section-head reveal">
        <h2 class="section-title">Полезные статьи</h2>
        <a href="blog.html" class="btn btn--outline btn--sm">Все статьи</a>
      </div>
      <div class="editorial-globe reveal is-visible reveal--visible">
        <div class="editorial-globe__featured">
          <a href="blog.html" class="editorial-globe__featured-link editorial-main__link">
            <div class="editorial-main__img-wrap"><img class="editorial-main__img" src="${img('blog-cover-01.jpg')}" alt="Как купить тур в Уфе" loading="eager" /></div>
            <div class="editorial-main__content"><h3 class="editorial-main__title">Как купить тур в Уфе — пошаговая инструкция</h3></div>
          </a>
        </div>
        <div class="editorial-globe__stack editorial-globe-stack">
          <a href="blog.html" class="editorial-globe-stack__link"><div class="editorial-globe-stack__thumb"><img class="editorial-globe-stack__img" src="${img('blog-cover-02.jpg')}" alt="" loading="lazy" /></div><div class="editorial-globe-stack__body"><h3 class="editorial-globe-stack__title">Что входит в «всё включено» в туре</h3></div></a>
        </div>
      </div>
    </div>
  </section>

</main>

<footer class="site-footer"><div class="container"><p class="footer-rta">ООО «Жираф» · РТА 0012822 · Preview static</p></div></footer>
<script src="../wp-theme/zhiraf-ufa/assets/dist/main.js" defer></script>
</body>
</html>
`;

const out = path.join(__dir, 'home.html');
fs.writeFileSync(out, html, 'utf8');
console.log('wrote', out);
