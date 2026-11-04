# Indexing & Crawl Map — «Жираф», Уфа (`zhiraf-ufa`)

## Meta

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru |
| **canonical host** | **`https://zhiraf-ufa.ru`** (`teya-memory/site.inv` → `project.public_site_url`) |
| staging | `https://b2b-tour.ru` — **только preview, полный noindex** |
| theme_slug | `zhiraf-ufa` |
| semantic_run | `teya-memory/semantic-core/zhiraf-ufa-2026/` |
| blueprint_ref | `teya-memory/wp/aurora-team-blueprint.md` |
| url_map_ref | `06-url-map.csv` |
| theme_seo | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc/seo.php` |
| redirects | `inc/redirects.php` + `teya-memory/seo/old-url-map.csv` (если есть) |
| author | aurora-team-indexing |
| date | 2026-10-18 |
| status | ✅ READY (контракт; live verify после cutover на zhiraf-ufa.ru) |

---

## 1. Public domain rules (блокеры)

| Правило | Требование | Сейчас |
|---------|------------|--------|
| Canonical host | Только `https://zhiraf-ufa.ru` в canonical, `Sitemap:`, `Host:`, JSON-LD `@id`/`url` | ✅ в теме: `ZHIRAF_UFA_PUBLIC_SITE_URL` + rewrite с staging |
| Staging | `b2b-tour.ru` не индексируется | ✅ `noindex,nofollow` meta, `X-Robots-Tag`, `robots.txt Disallow: /`, WP `blog_public=0` (bootstrap) |
| robots.txt | Не ссылаться на staging в `Sitemap:` / `Host:` | ✅ production-шаблон только на public host |
| Sitemap HTTP | `/sitemap_index.xml` не 500 на production | ⏳ verify после deploy + Yoast/RankMath или core sitemap |
| Sitemap content | Без `/wp-admin/`, `/wp-json/`, search, thank-you, noindex URL | ⏳ plugin config при deploy |
| CSS/JS/images | Не блокировать в robots (кроме staging blanket) | ✅ `Allow` для uploads + theme assets |

**Blockers до production cutover:**

1. Live-проверка `https://zhiraf-ufa.ru/robots.txt` и sitemap (не выполнялась в этом run).
2. Убедиться, что на **b2b-tour.ru** в WP «Видимость для поисковых систем» = снято с индексации (`blog_public=0`).

---

## 2. Staging policy — `b2b-tour.ru`

| Слой | Поведение |
|------|-----------|
| WordPress | `blog_public = false` (`scripts/bootstrap_zhiraf_b2b_wp.py`) |
| `<meta name="robots">` | `noindex, nofollow` на всех HTML-страницах |
| HTTP header | `X-Robots-Tag: noindex, nofollow` |
| `robots.txt` | `User-agent: *` / `Disallow: /` |
| Canonical / schema | URL переписываются на `https://zhiraf-ufa.ru/...` (не дублировать staging в SERP) |
| Sitemap на staging | Не сабмитить в GSC/Yandex; при запросе — плагин может отдавать URL; crawlers заблокированы robots |

**После переноса на zhiraf-ufa.ru:** снять staging-хост с DNS или оставить 401/basic-auth; не отключать noindex на staging, пока домен публично резолвится.

---

## 3. `robots.txt` (production — `zhiraf-ufa.ru`)

Реализация: фильтр `robots_txt` в `inc/seo.php` (не статический файл в репо).

```text
User-agent: *
Allow: /wp-content/uploads/
Allow: /wp-content/themes/zhiraf-ufa/assets/
Disallow: /wp-admin/
Allow: /wp-admin/admin-ajax.php
Disallow: /wp-json/
Disallow: /?s=
Disallow: /search/

User-agent: Googlebot
Allow: /

User-agent: Yandex
Allow: /

User-agent: Bingbot
Allow: /

# AI crawlers (indexing + llms.txt policy)
User-agent: GPTBot
Allow: /

User-agent: OAI-SearchBot
Allow: /

User-agent: ChatGPT-User
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: Google-Extended
Allow: /

User-agent: CCBot
Allow: /

Sitemap: https://zhiraf-ufa.ru/sitemap_index.xml
Host: zhiraf-ufa.ru
```

**Не делать:** `Disallow: /wp-content/` целиком (ломает рендер сниппетов); блокировать `.css`/`.js` темы.

---

## 4. AI crawler policy

| Bot | Production | Staging |
|-----|------------|---------|
| GPTBot | Allow | Disallow (via blanket) |
| OAI-SearchBot | Allow | Disallow |
| ChatGPT-User | Allow | Disallow |
| ClaudeBot | Allow | Disallow |
| PerplexityBot | Allow | Disallow |
| Google-Extended | Allow | Disallow |
| CCBot | Allow | Disallow |

**Контент:** публичные маркетинговые страницы и блог — можно цитировать с атрибуцией «Турагентство «Жираф», zhiraf-ufa.ru»; не использовать для выдуманных цен/гарантий. Юрстраницы и персональные данные из форм — не для обучения.

---

## 5. `llms.txt` (план, production root)

Файл: `https://zhiraf-ufa.ru/llms.txt` (статический в theme root или mu-plugin; **не** на staging в индексе).

```markdown
# Турагентство «Жираф» — Уфа

> Семейное турагентство с 2011 года. Подбор пакетных туров с вылетом из Уфы (Турция, ОАЭ, Египет, Таиланд, Вьетнам). Офис: ул. Гагарина 74/1. РТА 0012822.

## Ключевые страницы
- https://zhiraf-ufa.ru/ — подбор тура, квиз
- https://zhiraf-ufa.ru/tour/ — каталог направлений
- https://zhiraf-ufa.ru/kontakty/ — телефон, адрес, заявка
- https://zhiraf-ufa.ru/o-nas/ — о компании
- https://zhiraf-ufa.ru/blog/ — статьи перед поездкой

## Политика
- Цены в контенте не фиксированы без даты вылета; не выдумывать тарифы.
- Контакт: office@zhiraf-ufa.ru, +7 901 810-50-18
- Полное использование: ссылка на canonical URL страницы.
```

---

## 6. Sitemap guidance

**Источник URL:** `teya-memory/semantic-core/zhiraf-ufa-2026/06-url-map.csv` (только `url_status=ok|create` с финальным контентом).

### 6.1 Включить (indexable, self-referencing canonical)

| URL | Тип | priority | changefreq |
|-----|-----|----------|------------|
| `/` | home | 1.0 | weekly |
| `/tour/` | hub | 0.9 | weekly |
| `/tour/turciya/` | destination | 0.8 | monthly |
| `/tour/oae/` | destination | 0.8 | monthly |
| `/tour/egipet/` | destination | 0.8 | monthly |
| `/tour/tailand/` | destination | 0.7 | monthly |
| `/tour/vietnam/` | destination | 0.7 | monthly |
| `/o-nas/` | trust | 0.6 | yearly |
| `/kontakty/` | local | 0.7 | monthly |
| `/blog/` | archive | 0.6 | weekly |
| `/blog/kak-kupit-tur-v-ufe/` | article | 0.5 | yearly |
| `/blog/chto-vhodit-v-tur-vse-vklyucheno/` | article | 0.5 | yearly |
| `/blog/dokumenty-dlya-zagranicy-iz-ufy/` | article | 0.5 | yearly |
| `/blog/tury-s-detmi-iz-ufy/` | article | 0.5 | yearly |
| `/blog/strahovka-i-rassrochka-na-tur/` | article | 0.5 | yearly |
| `/blog/turciya-ili-egipet-iz-ufy/` | article | 0.5 | yearly |
| `/politika-konfidenczialnosti/` | legal | 0.3 | yearly |
| `/politika-cookies/` | legal | 0.3 | yearly |

**`lastmod`:** `post_modified_gmt` для страниц/постов; не ставить фиктивную дату.

### 6.2 Исключить из sitemap

| Паттерн | Причина |
|---------|---------|
| `/?s=`, `/search/` | noindex |
| `/wp-admin/`, `/wp-json/` | utility |
| `/wp-login.php` | auth |
| Thank-you / quiz success (если появятся) | noindex |
| Attachment-only URLs без контента | thin |
| Staging host | не canonical |
| Tag/author/date archives (если не используются) | thin / duplicate |

**Плагин:** Yoast SEO или Rank Math — отключить sitemap для taxonomies, users, media; один index `sitemap_index.xml` на public host.

---

## 7. Canonical & robots meta (indexable pages)

| Контекст | robots | canonical |
|----------|--------|-----------|
| Обычные страницы/посты (production) | `index, follow` + max-snippet/image | self `https://zhiraf-ufa.ru{path}` |
| Поиск `?s=` / `search.php` | `noindex, follow` | не обязателен |
| 404 | `noindex, follow` | — |
| Staging любая | `noindex, nofollow` | public URL (для согласованности, не для индекса staging) |
| Пагинация блога `/blog/page/2/` | `index, follow` | **self** (не склеивать на page/1) |
| `rel=prev/next` | опционально; не конфликтовать с canonical |

Реализация: `zhiraf_ufa_head_meta()` в `inc/seo.php`.

---

## 8. Redirect map

| From (legacy zhiraf-ufa.ru) | To | Code |
|-----------------------------|-----|------|
| `/kontacty`, `/kontacty/` | `/kontakty/` | 301 |
| `/rasskazy`, `/rasskazy/` | `/blog/` | 301 |

**При cutover домена:** если старый сайт отдавал другие пути — дополнить `zhiraf_ufa_legacy_redirect_map()` и CSV.

**www:** выбрать один вариант (`zhiraf-ufa.ru` без www — как в brief); 301 с `www` → apex на уровне хостинга.

**http → https:** 301 на edge.

---

## 9. Crawl depth & internal linking

Цель: любая P0/P1 страница ≤ **3 клика** с главной.

```text
/ → /tour/ → /tour/turciya/     (2)
/ → /blog/ → /blog/{slug}/       (2)
/ → /kontakty/                   (1)
/ → /o-nas/                      (1)
```

Hub `/tour/` и главная должны ссылаться на все 5 destination landings; блог — на `/tour/` и `/kontakty/` (см. url-map `internal_links_*`).

---

## 10. Blog pagination & crawl

| Правило | Деталь |
|---------|--------|
| Archive `/blog/` | indexable; в sitemap |
| `/blog/page/N/` | indexable; canonical на себя |
| RSS | `/feed/` — allow в robots; не в sitemap как отдельные URL |
| Single posts | в sitemap; `Article` schema |
| Категории/теги | по умолчанию **noindex** (если не ведём hub-категории) |

---

## 11. 404 / soft-404

| Ситуация | Поведение |
|----------|-----------|
| Несуществующий путь | HTTP 404, `noindex, follow`, дружелюбный шаблон + ссылки на `/tour/`, `/kontakty/` |
| Пустой destination без контента | **Не публиковать** до контента (избегать soft-404) |
| Редирект на главную для unknown URL | **Запрещено** (soft-404) |

---

## 12. Pre-launch checklist (indexing)

- [ ] DNS `zhiraf-ufa.ru` → production WP
- [ ] `blog_public=1` только на production
- [ ] `robots.txt` → `Sitemap:` и `Host:` на zhiraf-ufa.ru
- [ ] Submit sitemap: Google Search Console, Яндекс.Вебмастер
- [ ] Staging b2b-tour.ru: noindex layers + не в GSC
- [ ] Проверить sample: canonical, robots, JSON-LD host
- [ ] `llms.txt` на production
- [ ] Удалить/заменить hardcoded `b2b-tour.ru` в опубликованном schema вне темы (Excalibur JSON при импорте — перегенерировать на public URL)

---

## 13. Связь с другими Aurora maps

| Map | Связь |
|-----|--------|
| `navigation-linking-map` | depth + hub links |
| `schema-technical-seo-map` | JSON-LD согласован с canonical host |
| `security-release-map` | `blog_public`, staging access |
| `seo-geo-verification.md` | live gate после deploy |
