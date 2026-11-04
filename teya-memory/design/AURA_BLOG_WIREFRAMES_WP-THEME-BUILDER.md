# Wireframes — блог Globe Trekker (wp-theme-builder)

**Blueprint:** `AURA_BLOG_SECTION_BLUEPRINT.json`  
**Тема:** `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/`  
**Цвета:** фон `#FAF8F5`, акцент `#E87A5D`, текст `#2B2B2B`. **Watermark логотипа запрещён.**

## Типографика (scope)

| Зона | Шрифт | Файлы в теме |
|------|--------|----------------|
| Весь сайт (кроме blog H1–H3) | Manrope | `assets/fonts/manrope/Manrope-Cyrillic-Variable.woff2` |
| Блог: H1–H3 (главный тизер, архив, статья) | Cormorant Garamond 600–700 | `assets/fonts/cormorant-garamond/*.woff2` — **только local @font-face, без Google CDN** |

CSS: `--font-blog-display` на перечисленных селекторах из blueprint; `section-title` на главной остаётся Manrope 800.

---

## 1. Главная — блок «Полезные статьи» (1 featured + 5 cards)

**Классы:** `.section--editorial.editorial-blog-teaser` → обёртка `.editorial-globe`

```
┌──────────────────────────────────────── container 1240 ────────────────────────────────────────┐
│  [H2 Manrope] Полезные статьи                              [outline btn] Все статьи → /blog/ │
├──────────────────────────────────────┬───────────────────────────────────────────────────────┤
│  ┌────────────────────────────────┐  │  ┌────┐  РУБРИКА · 8 мин                                  │
│  │                                │  │  │ 1:1│  H3 serif — короткий заголовок карточки 1        │
│  │         FEATURED 16:9          │  │  └────┘  (link entire row)                               │
│  │                                │  ├──────────────────────────────────────────────────────────┤
│  └────────────────────────────────┘  │  ┌────┐  карточка 2                                        │
│  РУБРИКА · Турция · 12 мин         │  └────┘                                                    │
│  H3 serif — заголовок featured     │  ┌────┐  карточка 3                                        │
│  excerpt Manrope 2 lines           │  └────┘                                                    │
│                                    │                                                           │
├────────────────────────────────────┴───────────────────────────────────────────────────────────┤
│  ┌──────────── 16:9 ────────────┐    ┌──────────── 16:9 ────────────┐                          │
│  │  card 4                       │    │  card 5                       │                          │
│  └───────────────────────────────┘    └───────────────────────────────┘                          │
│  meta + H3 serif each                                                                            │
└──────────────────────────────────────────────────────────────────────────────────────────────────┘
```

**Desktop (≥1024):** CSS grid 2 колонки (1.35fr / 1fr). Featured + excerpt занимают левую колонку на всю высоту верхней зоны; справа 3 stack-cards. Ряд `.editorial-globe__row2` — две 16:9 карточки под featured (ширина = левая колонка) **или** на всю ширину контейнера 2×50% — предпочтение: **под левой колонкой** для асимметрии Globe Trekker.

**Tablet:** featured full width; stack 3 → горизонтальный scroll или 1 колонка; row2 → 2 col.

**Mobile (≤767):** всё в одну колонку: featured → 5 карточек vertical 16:9 card pattern; stack thumbs 96px.

**PHP:** расширить `zhiraf_ufa_blog_teaser_bt01()` / `zhiraf_ufa_home_blog_posts()` — массив из 6 слотов (1 featured + 5). Разметка: `template-parts/blog/editorial-globe.php` (новый partial).

**Meta row markup (пример):**

```html
<p class="blog-meta-row">
  <span class="blog-meta__rubrika">Гид</span>
  <span class="blog-meta__country">Турция</span>
  <span class="blog-meta__read-time">8 мин чтения</span>
</p>
```

---

## 2. Архив `/blog/`

```
┌──────────────── container ────────────────┐
│  H1 serif — Блог о путешествиях из Уфы     │
│  p Manrope — подзаголовок                  │
├────────────────────────────────────────────┤
│  [optional page 1] FEATURED 16:9 full width │
│  meta + H2 serif + excerpt                 │
├────────────────────────────────────────────┤
│  ┌─────────┐ ┌─────────┐ ┌─────────┐       │
│  │ 16:9    │ │ 16:9    │ │ 16:9    │       │
│  │ meta    │ │ meta    │ │ meta    │       │
│  │ H2 ser. │ │ H2 ser. │ │ H2 ser. │       │
│  └─────────┘ └─────────┘ └─────────┘       │
│         post-grid 3 / 2 / 1                │
│  [ pagination ]                            │
└────────────────────────────────────────────┘
```

**home.php:** обернуть hero в `.page-hero--blog`; перед grid на первой странице — `get_template_part( 'template-parts/blog/featured-archive' )` для `posts[0]` если не дублировать в grid (skip first in loop или `offset`).

**Карточка:** обновить `content-card.php` — добавить `.blog-meta-row`, класс `post-card--globe`, заголовок `h2` с serif.

---

## 3. Single post

```
┌──────────────── container max 720 prose ────┐
│  H1 serif — title                            │
│  meta row (rubrika · read time · date)       │
│  ┌──────────────── 16:9 hero ──────────────┐ │
│  └──────────────────────────────────────────┘ │
│  .entry-content.prose — Manrope body         │
│  H2/H3 inside content → serif                │
│  footer CTA Manrope                          │
└──────────────────────────────────────────────┘
```

**single.php:** класс `entry--globe` на article; обернуть контент в `.entry-layout`; дата в meta — Manrope muted 14px.

**Не трогать:** комментарии, schema (seo.php) — только классы и типографика.

---

## SCSS checklist

1. Импорт `_blog-globe.scss` из `main.scss`.
2. `@font-face` для Cormorant в `_fonts.scss` или top of `_blog-globe.scss`.
3. `.blog-meta-row` + модификатор `--compact` для stack cards.
4. Убрать `.editorial-teaser { max-width: 720px }` при переходе на full editorial-globe grid.
5. Сохранить существующие `.editorial-main__img-wrap` aspect 16:9 — переиспользовать BEM с префиксом `editorial-globe`.

---

## QA

- [ ] На главной ровно 6 ссылок на статьи (1+5), все с read time.
- [ ] H1 hero сайта и section titles — Manrope; H3 в блочке статей — serif.
- [ ] Нет watermark; логотип только в header/footer.
- [ ] 1440 и 390: сетка не ломается, meta не переполняет карточку.
