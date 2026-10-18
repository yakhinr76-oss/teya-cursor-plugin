# AURA Page Plan

## Test Build Limit

Всего страниц: 5  
Обязательно: главная + 4 самые важные внутренние

## Pages

### 1. Главная

- slug: /
- build_in_test: yes
- design_priority: P0
- role: homepage / landing
- design_source: Globe Trekker editorial travel + `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/front-page.php`
- key_sections:
  - hero (collage + flight SVG)
  - destinations (tabs + vertical cards)
  - how-it-works (4 steps)
  - editorial blog (1+3)
  - trust-social (highlights + VK/phone CTA — **без фейковых отзывов**)
  - lead form
  - subscribe full-bleed
- components: `btn--primary` pill, `dest-premium-card`, `editorial-main`, `subscribe-premium`, planned `trust-highlight`
- responsive_notes: collage stack under copy на 375px; dest row → horizontal scroll 2-up; editorial single column
- motion_notes: `.reveal` + optional GSAP ScrollTrigger (theme `motion-home.js`)
- aura_requirements: #FAF8F5 / #E87A5D; Manrope 800 headlines; container 1240px; **no logo watermark**
- semantic_notes_for_aurora: сверить H1/hero copy с offers-map; не добавлять цены «от»

### 2. Каталог туров

- slug: /tour/
- build_in_test: yes
- design_priority: P0
- role: destination hub
- design_source: Globe Trekker dest row + `page-tour.php`
- key_sections: catalog hero + chip filter + large dest grid + CTA quiz
- aura_requirements: наследовать vertical card language; `catalog-hub.jpg` hero
- semantic_notes_for_aurora: URL-карта направлений — зона Ядрышка

### 3. О нас

- slug: /o-nas/
- build_in_test: yes
- design_priority: P1
- role: trust / E-E-A-T
- design_source: editorial inner + team grid
- key_sections: page-hero editorial, prose + 2 photos, team cards, cta-strip
- aura_requirements: `team-travel.jpg`, `office-ufa.jpg`; sand section `--sand`
- semantic_notes_for_aurora: факты команды только из fact-bank

### 4. Контакты

- slug: /kontakty/
- build_in_test: yes
- design_priority: P1
- role: conversion + NAP
- design_source: light footer NAP extended
- key_sections: hero, форма, карта, реквизиты
- aura_requirements: телефон кликабельный; без тёмных блоков
- semantic_notes_for_aurora: schema LocalBusiness уже в теме

### 5. Блог (архив)

- slug: /blog/
- build_in_test: yes
- design_priority: P1
- role: editorial / SEO support
- design_source: Globe Trekker article grid simplified
- key_sections: archive hero, card grid 16:9 covers
- aura_requirements: единая серия обложек `editorial_photo` (см. blog cover concept)
- semantic_notes_for_aurora: 6 Excalibur slug из fact-bank

## Backlog (не в test build)

| slug | role | design_priority |
|------|------|-----------------|
| /tour/turkey/ … | direction landing | P1 |
| single post | article template | P1 |
| politika-* | legal minimal | P2 |
