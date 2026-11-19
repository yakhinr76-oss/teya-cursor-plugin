# Wave 2 SEO backlog — Шри-Ланка, Бали, Гоа, Сейшелы

**Статус:** curated v1 **UX готов**, SEO-контент **слабее эталона** (Вьетнам / Мальдивы / Таиланд).  
**Для агентов:** не считать wave-2 «готовыми к индексации на проде» без прохода по чеклисту ниже.

## Эталон (что ожидает план SEO)

Источники: `CURATED-LANDING-V1-ETALON.md`, `teya-memory/wp/schema-technical-seo-map.md`, блоки в `inc/destination-landings.php` у `vietnam` / `maldivy` / `tailand`.

| Элемент | Эталон | Wave 2 (текущее) |
|---------|--------|------------------|
| **title / description** | 50–60 / 150–160 символов, Уфа + запрос | Есть, но короче и менее «long-tail» |
| **hero_lead** | 2–3 предложения + Уфа + 6 отелей + сегменты | Короче, мало LSI (виза, сезон, состав) |
| **hero_lead_extended** | Абзац GEO + доверие | ❌ SL / ID / IN; ✅ SC частично |
| **geo_answer** | Блок «как лететь из Уфы» (под FAQ/SEO) | ❌ SL, ID, IN; ✅ SC |
| **for_who / we_clarify / season** | Три аккордеона SEO-текста | ❌ SL, ID, IN; ✅ SC |
| **extra_sections** | 2+ H2 с абзацами (виза, сезон, деньги…) | ❌ у wave 2 |
| **FAQ** | 6–10 вопросов, Уфа, виза, пакет, «Жираф» | SL: 3; ID: 2; IN: 2; SC: 7 ✅ |
| **resort_links** | Сетка `.dest-resort-link-card` с desc | ❌ SL, ID, IN; ✅ SC |
| **blog_links** | 2–3 гида с **осмысленными tag** | SL/ID/IN: 2; IN: **1** (эталон — 2 минимум) |
| **seo_prose_accordions** | `prose_accordion_title` + длинные ответы | ❌ ID, IN; SL/SC: флаги есть |
| **resort_compare** | Уникальные **image** + 3+ строки в таблице | SL/ID: урезано; ID: дубль `country-indoneziya.jpg` |
| **Pick / Expert H2** | Страна, не Турция | ✅ после `1.0.56-wave2-pick-copy-fix` |
| **Tourvisor / цены** | Живые ориентиры | scaffold 0/25 — слабый сигнал «актуальности» |
| **Дочерние курорты** | `/tour/{resort}/` при необходимости | Нет отдельных URL (ок для v1, минус long-tail) |

## Приоритеты (очередь для агента)

### P0 — видимый контент на стейдже/проде

1. **Добить `destination-landings.php`** для `shri-lanka`, `indoneziya`, `indiya` по образцу `maldivy`:
   - `hero_lead_extended`, `geo_answer`, `for_who`, `we_clarify`, `season`
   - `extra_sections` (2 блока: виза/въезд + сезон/деньги)
   - `resort_links` (4 якоря на `#dest-curated-hotels` с уникальным `desc`)
   - FAQ → **минимум 6** вопросов (вылет Уфа, виза, что в пакете, сезон, дети, контакт «Жираф»)
2. **indiya:** второй `blog_links`; **indoneziya / indiya:** включить `seo_prose_accordions` + `prose_accordion_title`.
3. **Картинки compare:** отдельные `image` / `image_alt` (не дублировать `country-*.jpg` на обе колонки).

### P1 — meta и семантика

4. Переписать **title/description** с опорой на `schema-technical-seo-map.md` (Уфа, без цен «от»).
5. Расширить **hero_lead** до плотности эталона (курорты по имени, виза VoA/e-Visa/ETA, «за двоих»).
6. Expert-нюансы: проверить уникальность и сезонность (не generic-копипаст).

### P2 — тех SEO и перелинковка

7. Сверить с `06-url-map.csv` (если есть черновики title) — завести строки для 4 slug.
8. **JSON-LD** / FAQ schema — проверить `inc/seo.php` на вывод FAQPage для destination.
9. После Tourvisor `--match` — обновить микрокопию «актуальная цена» (не блокер текста, но E-E-A-T).

## Файлы для правок

| Slug | Конфиг SEO | Модуль каталога |
|------|------------|-----------------|
| `shri-lanka` | `destination-landings.php` ~1080 | `curated-sri-lanka-landing.php` |
| `indoneziya` | ~1138 | `curated-indonesia-landing.php` |
| `indiya` | ~1186 | `curated-india-landing.php` |
| `sejshely` | ~774 | `curated-seychelles-landing.php` (доработать P1) |

Шаблоны: `templates/destination-landing.php` (FAQ, prose, resort hub, useful-guides).

## Приёмка SEO (добавить к релизу)

- [ ] ≥6 FAQ, в каждом хотя бы один якорь «Уфа» / вылет / офис
- [ ] SEO-аккордеоны раскрываются и содержат `geo_answer` + сезон
- [ ] Сетка **курортов** 4 карточки с уникальным `desc`
- [ ] **2** полезных гида с тегами ≠ «Без рубрики»
- [ ] title/description в лимите карты SEO
- [ ] View-source: один H1 = `h1` из конфига
- [ ] Compare: две разные обложки, alt на русском

## Связанные задачи (не SEO, но влияют на качество)

- Deploy `1.0.56-wave2-pick-copy-fix` на b2b (если ещё не залит) — исправление «Турции» в pick-cards.
- Контент-проход по **ключам** из семантического ядра (отдельная задача контент-агента).

---

*Создано после ревью wave-2 post-deploy. Обновлять по мере закрытия P0/P1.*