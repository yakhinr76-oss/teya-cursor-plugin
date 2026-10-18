# SEO / GEO / WP Verification — zhiraf-ufa

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa |
| theme_slug | `zhiraf-ufa` |
| public_site_url | `https://zhiraf-ufa.ru` |
| mode | **local-only** (файлы темы + teya-memory; live crawl не выполнялся) |
| url_map | `teya-memory/semantic-core/zhiraf-ufa-2026/wordstat/url-map.md` (**19** URL) |
| checked_at | 2026-10-18 |
| agent | aurora-team-qa (bc-2df55d14-d895-5c00-bd17-2e3b56b367f2) |
| commit_base | `70f05ad` (branch `cursor/zhiraf-b2b-site-a2bf`) |
| **status** | **⚠️ FIXES NEEDED** (SEO-правки в теме применены; publish/deploy — нет) |
| ready_to_publish | **no** |

## Итог

- **Критичных SEO-блокеров в теме после правок нет:** title/description/H1 для 19 URL согласованы с `06-url-map.csv` и `page-content-pack.md`; canonical/host — `zhiraf-ufa.ru`; staging `b2b-tour.ru` — `noindex` + `Disallow: /` в `robots_txt`.
- **301** `/tour/tailand/phuket/` → `/tour/tailand/` — в `inc/redirects.php` (`zhiraf_ufa_legacy_redirect_map`), срабатывает на `template_redirect` (нормализация trailing slash).
- **Не проверялось live:** отдача `robots.txt`, `sitemap_index.xml`, Yandex-верификация (только theme mod), paint/release gate (по заданию не запускались).

## Инфраструктура (файлы темы)

| Элемент | Где | Вердикт |
|---------|-----|---------|
| Canonical + meta robots | `inc/seo.php` → `zhiraf_ufa_head_meta` | ✅ production host; staging noindex |
| `robots.txt` filter | `inc/seo.php` → `zhiraf_ufa_robots_txt` | ✅ `Host: zhiraf-ufa.ru`, `Sitemap: …/sitemap_index.xml`, AI-боты Allow |
| JSON-LD Organization/WebSite | `inc/seo.php` → `zhiraf_ufa_json_ld` | ✅ TravelAgency, NAP, без рейтингов |
| JSON-LD BreadcrumbList | `inc/seo.php` → `zhiraf_ufa_breadcrumb_json_ld` | ✅ только JSON-LD; цепочка hub→страна→курорт |
| Видимые крошки | `inc/breadcrumbs.php` | ✅ отсутствуют |
| Роутинг курортов | `inc/tour-routing.php` + `page-tour-resort.php` | ✅ 5 курортов в индексе |
| Sitemap генерация | — | ⚠️ ожидается Yoast/RankMath на WP (`sitemap_index.xml` в robots) |

## Редирект Пхукет

```text
/tour/tailand/phuket   → /tour/tailand/  (301)
/tour/tailand/phuket/  → /tour/tailand/  (301)
```

Источник: `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc/redirects.php`.

## Аудит 19 URL (после правок QA)

Легенда: **✅** — title, description, H1 и ключ Wordstat в контенте согласованы; **⚠️** — мелкое расхождение формулировок без потери интента; **—** — служебная/без ключа.

| # | URL | Ключ (Уфа) | Title (тема) | H1 (тема) | Canonical | Breadcrumb JSON-LD | Перелинковка | Вердикт |
|---|-----|------------|--------------|-----------|-----------|-------------------|--------------|---------|
| 1 | `/` | туры из уфы **33489**; турагентство уфа **2046** | Туры из Уфы — подбор пакетных туров \| «Жираф» | Туры с вылетом из Уфы — подбор без давления | `/` | — (главная) | hub, offers, destinations, blog | ✅ |
| 2 | `/tour/` | подбор тура уфа **72** | Направления отдыха с вылетом из Уфы \| «Жираф» | Направления отдыха с вылетом из Уфы | `/tour/` | Главная → каталог | 5 стран + offers + курорты | ✅ |
| 3 | `/goryashchie-tury/` | горящие туры из уфы **3480** | Горящие туры из Уфы — подбор пакетов \| «Жираф» | Горящие туры из Уфы | slug | Главная → H1 | `/tour/*`, `/vse-vklyucheno/` | ✅ (цифра **3480** в hero) |
| 4 | `/vse-vklyucheno/` | все включено из уфы **561** | Всё включено из Уфы — подбор тура \| «Жираф» | Туры «всё включено» из Уфы | slug | Главная → H1 | страны, BT-02 | ✅ (**561** в тексте) |
| 5 | `/tour/turciya/` | туры в турцию **2498**; турция из уфы **5875** | Турция из Уфы — туры «всё включено» \| Жираф | Туры в Турцию из Уфы | `/tour/turciya/` | Главная → каталог → H1 | Анталья, offers, blog | ✅ |
| 6 | `/tour/egipet/` | туры в египет **1983** | Египет из Уфы — туры на море \| Жираф | Туры в Египет из Уфы | `/tour/egipet/` | 3 уровня | Хургада, Шарм | ✅ |
| 7 | `/tour/vietnam/` | туры во вьетнам **614** | Вьетнам из Уфы — туры \| Жираф | Туры во Вьетнам из Уфы | `/tour/vietnam/` | 3 уровня | Нячанг, Фукуок | ✅ |
| 8 | `/tour/oae/` | туры в оаэ **274**; дубай **196** (секция) | ОАЭ из Уфы — туры и подбор \| Жираф | Туры в ОАЭ из Уфы | `/tour/oae/` | 3 уровня | vse-vklyucheno, blog | ✅ |
| 9 | `/tour/tailand/` | тур в таиланд **84**; паттайя **119**; пхукет **58+55** (секции) | Таиланд из Уфы — подбор тура \| Жираф | Туры в Таиланд из Уфы | `/tour/tailand/` | 3 уровня | секции Пхукет/Паттайя, redirect phuket | ✅ |
| 10 | `/tour/egipet/hurgada/` | хургада **853** | Хургада из Уфы — туры \| «Жираф» | Туры в Хургаду из Уфы | resort URL | страна → курорт | Шарм, Египет hub | ✅ |
| 11 | `/tour/egipet/sharm-el-sheyh/` | шарм **233** | Шарм-эль-Шейх из Уфы — туры \| «Жираф» | Туры в Шарм-эль-Шейх из Уфы | resort URL | 3 уровня | Хургада | ✅ |
| 12 | `/tour/vietnam/nyachang/` | нячанг **481** | Нячанг из Уфы — туры \| «Жираф» | Туры в Нячанг из Уфы | resort URL | 3 уровня | Фукуок, Вьетнам | ✅ |
| 13 | `/tour/vietnam/fukuok/` | фукуок **386** | Фукуок из Уфы — туры \| «Жираф» | Туры на Фукуок из Уфы | resort URL | 3 уровня | Нячанг | ✅ |
| 14 | `/tour/turciya/antalya/` | анталья **344** | Анталья из Уфы — туры на море \| «Жираф» | Туры в Анталью из Уфы | resort URL | 3 уровня | Турция hub | ✅ |
| 15 | `/o-nas/` | турагентство уфа (вторичн.) | О турагентстве «Жираф» в Уфе — с 2011 года | О турагентстве «Жираф» в Уфе | slug | Главная → title | kontakty, blog | ✅ |
| 16 | `/kontakty/` | local / buy | Контакты «Жираф» — офис в Уфе, Гагарина 74/1 | Контакты «Жираф» в Уфе | slug | Главная → title | o-nas, blog BT-01 | ✅ |
| 17 | `/blog/` | блог | Блог о путешествиях из Уфы \| «Жираф» | Блог о путешествиях из Уфы | `/blog/` | Главная → блог | карточки P0 | ✅ |
| 18 | `/politika-konfidenczialnosti/` | — | Политика конфиденциальности \| «Жираф» | Политика конфиденциальности | slug | Главная → title | footer | ✅ |
| 19 | `/politika-cookies/` | — | Политика cookies \| «Жираф» | Политика cookies | slug | Главная → title | footer | ✅ |

### Заметки по CSV vs pack

- `/tour/` **title** в `06-url-map.csv`: «Туры из Уфы — каталог направлений»; в теме и pack — «Направления отдыха с вылетом из Уфы» (**интент совпадает**, формулировка pack).
- Оффер «горящие»: в CSV «подбор \| Жираф», в теме «подбор пакетов» — **⚠️ косметика**, не блокер.

## Исправления в теме (этот прогон)

| Файл | Суть |
|------|------|
| `inc/seo.php` | Title/description home, tour, blog, legal; meta map; breadcrumb JSON-LD (hub/dest/offer/blog) |
| `inc/assets.php` | Wordstat chips **3480** / **561** |
| `inc/destination-landings.php` | Wordstat по url-map; ОАЭ/Египет/Турция; H1 hub-курортов |
| `inc/offer-landings.php` | **3480**, **561**, направления 2498/1983/614/274 |
| `inc/resort-landings.php` | **561**, Кемер **70**, Аланья **122** |
| `page-tour.php` | H1 каталога |
| `home.php` | H1 блога |
| `page-kontakty.php` | H1 контактов |

Зеркало: `teya-memory/wp/theme/zhiraf-ufa/` (те же пути).

## Critical issues

**none** для SEO-слоя темы после правок.

## Warnings

- Live: не подтверждены `robots.txt`, sitemap 200, canonical на staging vs production.
- FAQ **видимые** на лендингах есть; **FAQPage** JSON-LD не выводится (опционально P2).
- `php -l` на VM недоступен (нет PHP CLI); синтаксис проверен ревью диффа.
- Paint QA / design gate / `teya_release_gate.py` — **не запускались** (по заданию).
- Publish: нет production deploy.

## Ready to publish

**no** — нужен deploy + live crawl sitemap/robots + полный Aurora QA (paint, maps).
