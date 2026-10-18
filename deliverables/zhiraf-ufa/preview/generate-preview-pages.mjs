import { writeFileSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dir = dirname(fileURLToPath(import.meta.url));
const img = (f, alt, attrs = '') =>
  `<img src="../wp-theme/zhiraf-ufa/assets/images/${f}" alt="${alt}" loading="lazy" ${attrs} />`;

const head = (title) => `<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>${title}</title>
  <link rel="stylesheet" href="../wp-theme/zhiraf-ufa/assets/dist/style.css" />
  <style>
    @font-face{font-family:'Manrope';src:url('../wp-theme/zhiraf-ufa/assets/fonts/manrope/Manrope-Cyrillic-Variable.woff2') format('woff2');font-weight:200 800;font-display:swap;}
    @font-face{font-family:'Manrope';src:url('../wp-theme/zhiraf-ufa/assets/fonts/manrope/Manrope-Variable.woff2') format('woff2');font-weight:200 800;font-display:swap;}
  </style>
</head>
<body>`;

const header = `<header class="site-header"><div class="container site-header__inner">
  <div class="site-branding"><a href="home.html"><img src="../wp-theme/zhiraf-ufa/assets/images/logo-zhiraf-client.png" alt="Жираф" width="167" height="56" style="height:40px;width:auto"></a></div>
  <nav class="site-nav" aria-label="Главная навигация"><ul class="primary-menu" style="display:flex;gap:32px"><li><a href="tour.html">Туры</a></li><li><a href="o-nas.html">О нас</a></li><li><a href="blog.html">Блог</a></li><li><a href="kontakty.html">Контакты</a></li></ul></nav>
  <div class="site-header__actions"><a class="header-phone" href="tel:+79018105018">+7 901 810-50-18</a><button type="button" class="btn btn--primary btn--sm">Подобрать тур</button></div>
</div></header>`;

const footer = `<footer class="site-footer"><div class="container"><p>ООО «Жираф» · РТА 0012822 · <a href="politika-konfidenczialnosti.html">Политика</a> · <a href="politika-cookies.html">Cookies</a></p></div></footer>
<script src="../wp-theme/zhiraf-ufa/assets/dist/main.js" defer></script></body></html>`;

const destCards = [
  ['dest-turkey.jpg', 'Турция', 'Турция'],
  ['dest-uae.jpg', 'ОАЭ', 'ОАЭ'],
  ['dest-egypt.jpg', 'Египет', 'Египет'],
  ['dest-thailand.jpg', 'Таиланд', 'Таиланд'],
  ['dest-vietnam.jpg', 'Вьетнам', 'Вьетнам'],
]
  .map(
    ([f, alt, name]) =>
      `<article class="dest-card dest-card--large"><a href="#"><div>${img(f, alt, 'class="dest-card__img"')}</div><h2>${name}</h2><p>Подбор из Уфы</p></a></article>`
  )
  .join('');

const landings = {
  turciya: { h1: 'Туры в Турцию из Уфы', img: 'dest-turkey.jpg', alt: 'Турция' },
  oae: { h1: 'Туры в ОАЭ из Уфы', img: 'dest-uae.jpg', alt: 'ОАЭ' },
  egipet: { h1: 'Туры в Египет из Уфы', img: 'dest-egypt.jpg', alt: 'Египет' },
  tailand: { h1: 'Туры в Таиланд из Уфы', img: 'dest-thailand.jpg', alt: 'Таиланд' },
  vietnam: { h1: 'Туры во Вьетнам из Уфы', img: 'dest-vietnam.jpg', alt: 'Вьетнам' },
};

function wrap(title, main) {
  return head(title) + header + main + footer;
}

const pages = {
  'tour.html': wrap(
    'Туры из Уфы — preview',
    `<main id="primary" class="site-main site-main--catalog">
<section class="page-hero page-hero--catalog"><div class="container catalog-hero"><div><h1>Туры с вылетом из Уфы</h1><p class="lead">Турция, ОАЭ, Египет, Таиланд, Вьетнам.</p></div>${img('catalog-hub.jpg', 'Каталог', 'class="catalog-hero__img"')}</div></section>
<section class="section destinations destinations--catalog"><div class="container"><div class="dest-grid dest-grid--large">${destCards}</div></div></section></main>`
  ),
  'o-nas.html': wrap(
    'О нас — preview',
    `<main id="primary" class="site-main site-main--about">
<section class="page-hero page-hero--editorial"><div class="container"><h1>О турагентстве «Жираф» в Уфе</h1><p class="lead">С 2011 года.</p></div></section>
<section class="section"><div class="container about-grid"><div class="prose"><h2>История</h2><p>Семейное агентство в Уфе.</p></div><div class="about-visual">${img('team-travel.jpg', 'Команда', 'class="about-img"')}${img('office-ufa.jpg', 'Офис', 'class="about-img"')}</div></div></section></main>`
  ),
  'kontakty.html': wrap(
    'Контакты — preview',
    `<main id="primary" class="site-main site-main--contacts">
<section class="page-hero"><div class="container"><h1>Контакты «Жираф» в Уфе</h1></div></section>
<section class="section"><div class="container contact-grid"><div class="contact-cards"><div class="contact-card"><h2>Телефон</h2><a href="tel:+79018105018">+7 901 810-50-18</a></div><div class="contact-card"><h2>Адрес</h2><p>ул. Гагарина, 74/1, Уфа</p></div></div></div></section></main>`
  ),
  'blog.html': wrap(
    'Блог — preview',
    `<main id="primary" class="site-main site-main--blog">
<section class="page-hero"><div class="container"><h1>Блог и советы</h1></div></section>
<section class="section"><div class="container"><article class="story-card">${img('blog-cover-01.jpg', 'Статья', 'class="story-card__img"')}<h3>Как купить тур в Уфе</h3></article></div></section></main>`
  ),
  'politika-konfidenczialnosti.html': wrap(
    'Политика конфиденциальности',
    `<main id="primary" class="site-main site-main--legal"><section class="section"><div class="container narrow prose"><h1>Политика конфиденциальности</h1><p>ООО «Жираф», Уфа.</p><h2>Оператор</h2><p>ИНН 0278177191.</p></div></section></main>`
  ),
  'politika-cookies.html': wrap(
    'Политика cookies',
    `<main id="primary" class="site-main site-main--legal"><section class="section"><div class="container narrow prose"><h1>Политика cookies</h1><p>Используем cookies для работы сайта.</p></div></section></main>`
  ),
};

function destinationMain(data) {
  return `<main id="primary" class="site-main site-main--destination">
<section class="dest-landing-hero"><div class="container dest-landing-hero__grid">
<div class="dest-landing-hero__copy"><h1>${data.h1}</h1><p class="lead">${data.lead || 'Вылет из Уфы — подбор без давления.'}</p><button type="button" class="btn btn--primary btn--lg">Подобрать тур</button></div>
<div class="dest-landing-hero__visual">${img(data.img, data.alt, 'class="dest-landing-hero__img"')}</div>
</div></section>
<section class="section dest-landing-prose bg-milky"><div class="container container--narrow"><h2>Для кого</h2><p>${data.geo || 'Семьи и пары из Уфы.'}</p></div></section>
<section class="section dest-landing-faq"><div class="container container--narrow"><details class="faq-home__item" open><summary>Частый вопрос</summary><p>Ответ менеджера «Жираф».</p></details></div></section></main>`;
}

function offerMain(data) {
  return `<main id="primary" class="site-main site-main--offer">
<section class="page-hero page-hero--offer"><div class="container page-hero__grid">
<div><p class="eyebrow">${data.eyebrow}</p><h1>${data.h1}</h1><p class="lead">${data.lead}</p><button type="button" class="btn btn--primary btn--lg">${data.cta}</button></div>
${img(data.img, data.alt, 'class="page-hero__img"')}
</div></section>
<section class="section bg-milky"><div class="container container--narrow prose"><h2>Коротко</h2><p>${data.geo}</p></div></section></main>`;
}

for (const [slug, data] of Object.entries(landings)) {
  pages[`tour-${slug}.html`] = wrap(`${data.h1} — preview`, destinationMain(data));
}

const offers = {
  'goryashchie-tury': {
    h1: 'Горящие туры из Уфы',
    eyebrow: 'Вылет из Уфы · без прайс-борда',
    lead: 'По данным поиска в Уфе тысячи раз в месяц ищут горящие туры из Уфы (3024 показа/мес.) — понимаем, что важны даты и скорость ответа.',
    geo: 'Горящий тур из Уфы — пакет с близкими датами вылета, когда у оператора остаются места. Состав услуг фиксируем в договоре.',
    img: 'hero-turkey.jpg',
    alt: 'Пляжный отдых, вылет из Уфы',
    cta: 'Подобрать горящий тур',
  },
  'vse-vklyucheno': {
    h1: 'Туры «всё включено» из Уфы',
    eyebrow: 'AI / UAI · пакет из Уфы',
    lead: 'Запрос «всё включено из Уфы» — 735 показов в месяц по Wordstat.',
    geo: 'В пакет обычно входят перелёт из Уфы, проживание, трансфер и питание по коду оператора (AI/UAI).',
    img: 'dest-turkey.jpg',
    alt: 'Турция, отель с питанием всё включено',
    cta: 'Подобрать тур «всё включено»',
  },
};

for (const [slug, data] of Object.entries(offers)) {
  pages[`${slug}.html`] = wrap(`${data.h1} — preview`, offerMain(data));
}

const resorts = [
  { file: 'tour-turciya-antalya', h1: 'Туры в Анталью из Уфы', img: 'dest-turkey.jpg', alt: 'Анталья', lead: 'Анталья из Уфы — 122 показа в месяц по Wordstat.', geo: 'Курорт на Средиземном море; пакет из Уфы — перелёт и отель по условиям оператора.' },
  { file: 'tour-egipet-hurgada', h1: 'Туры в Хургаду из Уфы', img: 'dest-egypt.jpg', alt: 'Хургада', lead: 'Хургада из Уфы — 327 показов/мес.', geo: 'Курорт на Красном море с линейкой отелей «всё включено».' },
  { file: 'tour-egipet-sharm-el-sheyh', h1: 'Туры в Шарм-эль-Шейх из Уфы', img: 'dest-egypt.jpg', alt: 'Шарм-эль-Шейх', lead: 'Шарм-эль-Шейх из Уфы — 242 показа/мес.', geo: 'Курорт на Синае; перелёт и отель — в пакете оператора из Уфы.' },
  { file: 'tour-tailand-phuket', h1: 'Туры на Пхукет из Уфы', img: 'dest-thailand.jpg', alt: 'Пхукет', lead: 'Пхукет из Уфы — 125 показов.', geo: 'Перелёт до Таиланда, проживание на острове, трансфер и питание по коду оператора.' },
  { file: 'tour-vietnam-nyachang', h1: 'Туры в Нячанг из Уфы', img: 'dest-vietnam.jpg', alt: 'Нячанг', lead: 'Нячанг из Уфы — 193 показа/мес.', geo: 'Курорт на побережье Южного Вьетнама; пакет из Уфы — перелёт, отель, часто AI.' },
  { file: 'tour-vietnam-fukuok', h1: 'Туры на Фукуок из Уфы', img: 'dest-vietnam.jpg', alt: 'Фукуок', lead: 'Фукуок из Уфы — 183 показа/мес.', geo: 'Островной отдых; пакет включает перелёт и проживание по условиям оператора.' },
];

for (const r of resorts) {
  pages[`${r.file}.html`] = wrap(`${r.h1} — preview`, destinationMain(r));
}

for (const [file, html] of Object.entries(pages)) {
  writeFileSync(join(__dir, file), html, 'utf8');
}
console.log('generated', Object.keys(pages).length, 'preview pages (home.html unchanged)');
