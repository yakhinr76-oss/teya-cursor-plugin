# Local Entity Map — «Жираф», Уфа (`zhiraf-ufa`)

## Meta

| Поле | Значение |
|------|----------|
| project | zhiraf-ufa.ru (staging: b2b-tour.ru, noindex) |
| theme_slug | `zhiraf-ufa` |
| mode | CONVERSION-FIRST |
| blueprint_ref | `teya-memory/wp/aurora-team-blueprint.md` §22 |
| research_ref | `teya-memory/research/fact-bank.md`, `site-research-dossier.md` |
| site_inv | `teya-memory/site.inv` |
| theme_path | `deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/` |
| playbook | `shared/wp-theme-builder-playbook.md` |
| author | aurora-team-local-entity |
| date | 2026-10-18 |
| status | ✅ READY (контракт NAP / каталоги / schema; внешние профили — чеклист без URL) |

**Источник фактов:** только `confirmed` из `fact-bank.md` и код темы. Не использовать устаревшие контакты с legacy zhiraf-ufa.ru (`info@zhirafufa.com`, `+7 123 456 7890`).

---

## 1. Canonical NAP

Единый набор для сайта, JSON-LD, footer, `/kontakty/`, Яндекс Бизнес, Google Business Profile, 2ГИС.

| Поле | Canonical value | Формат на сайте | Schema.org / tel |
|------|-----------------|-----------------|------------------|
| **Company name (brand)** | Турагентство «Жираф» | H1, og:site_name, schema `name` | `TravelAgency.name` |
| **Legal name** | ООО «Жираф» | Реквизиты, footer line | `legalName` |
| **Registry** | РТА **0012822** | Footer, `/o-nas/`, trust blocks | Текст на сайте; **не** выдумывать поле schema без согласования со `schema-technical-seo-map.md` |
| **ИНН / ОГРН** | 0278177191 / 1110280012457 | `/kontakty/` реквизиты | Не в LocalBusiness JSON-LD по умолчанию |
| **Street address (офис, посещение)** | ул. Юрия Гагарина, 74/1, офис 3 | Footer, контакты | `streetAddress` |
| **Locality** | г. Уфа | | `addressLocality`: Уфа |
| **Region** | Республика Башкортостан | Текст при необходимости | `addressRegion`: Республика Башкортостан (добавить в schema при синхронизации с schema-map) |
| **Postal code** | 450068 | Контакты | `postalCode` |
| **Country** | Россия | | `addressCountry`: RU |
| **Phone** | +7 901 810-50-18 | Кликабельный `tel:+79018105018` | `telephone`: `+7-901-810-50-18` (как в `inc/seo.php`) |
| **Email** | office@zhiraf-ufa.ru | `mailto:` | `email` |
| **Website** | https://zhiraf-ufa.ru/ | Canonical host после cutover | `url` |
| **Hours** | Пн–Пт 10:00–19:00, Сб 11:00–15:00 | Footer, контакты | `openingHoursSpecification` (см. §8) |
| **Legal address (не офис)** | 450068, РБ, г. Уфа, б-р Плеханова, д. 5, кв. 123 | Только блок реквизитов | **Не** подменять офисный NAP в каталогах |

**Юр. vs физический офис:** в Яндекс/Google/2ГИС и на карте — **только адрес посещения (Гагарина)**. Плеханова — только в реквизитах и договорной документации.

---

## 2. NAP consistency checklist

| Канал | Обязательное совпадение | Текущее состояние (репозиторий) | Действие |
|-------|-------------------------|----------------------------------|----------|
| Сайт `/kontakty/` | Полный NAP + часы | ✅ `page-kontakty.php` | Держать 1:1 с таблицей §1 |
| Footer sitewide | Адрес, телефон, email, РТА | ✅ `footer.php` | Единый текст; телефон с `tel:` |
| Header / sticky CTA | Телефон | ✅ theme | Тот же номер |
| JSON-LD | name, legalName, address, tel, email, hours | ✅ `TravelAgency` в `inc/seo.php` | Сверить с schema-map; добавить `geo` при верификации координат |
| Политика конфиденциальности | Юрлицо + контакты | ✅ `page-politika-konfidenczialnosti.php` | |
| Excalibur статьи | РТА 0012822, офис, телефон | ✅ fact-check reports | Не дублировать другой email/телефон |
| Staging `b2b-tour.ru` | Тот же NAP, noindex | ✅ `inc/seo.php` | NAP не менять на staging |
| Яндекс Бизнес | = §1 | ⚠️ URL профиля не в репозитории | Клиент: создать/заявить организацию |
| Google Business Profile | = §1 | ⚠️ не подтверждён | Клиент: верификация |
| 2ГИС | = §1 | ⚠️ не подтверждён | Клиент: карточка компании |
| VK | https://vk.com/zhiraf_ufa | ✅ schema `sameAs` | Единственный подтверждённый sameAs |

**Запрещено:** второй телефон/email на индексируемых страницах; сокращение «Гагарина 74» без «офис 3» в официальном NAP; «14 лет» без согласования с «с 2011».

---

## 3. Yandex Business checklist (Яндекс Бизнес / Справочник)

| # | Пункт | Требование |
|---|--------|------------|
| 1 | Организация | ООО «Жираф» / бренд «Жираф» — как в карточке, NAP = §1 |
| 2 | Регион | Уфа, Республика Башкортостан |
| 3 | Категории | Турагентство; при необходимости «Туристическое агентство», «Организация отдыха» (без лишних категорий) |
| 4 | Услуги | Подбор пакетного тура; консультация; бронирование (без цен «от» без даты) |
| 5 | Сайт | https://zhiraf-ufa.ru/ |
| 6 | Телефон / email | +7 901 810-50-18, office@zhiraf-ufa.ru |
| 7 | Часы | Пн–Пт 10:00–19:00, Сб 11:00–15:00; воскресенье — закрыто (если не скажет клиент иначе) |
| 8 | Фото | Офис, команда (с согласия), логотип; без стоковых «пляжей» как «наш офис» |
| 9 | Описание | Семейное агентство с 2011, вылеты из Уфы, честный подбор — без неподтверждённых цифр |
| 10 | РТА | Указать **0012822** в описании или поле «лицензии», если доступно |
| 11 | Отзывы | Просить реальных клиентов; отвечать от имени бренда; **не** импортировать фейковые цитаты с legacy-сайта |
| 12 | Верификация | Связать с Яндекс.Вебмастер / Метрикой после получения ID (`needs_user_fact` в fact-bank) |

**Ссылка на карточку:** зафиксировать в `site.inv` или handoff после создания клиентом (в репозитории отсутствует).

---

## 4. Google Business Profile checklist

| # | Пункт | Требование |
|---|--------|------------|
| 1 | Тип | **Travel agency** (ближайший GBP; в schema — `TravelAgency`) |
| 2 | Name | Турагентство «Жираф» (без keyword stuffing) |
| 3 | Address | Гагарина 74/1, офис 3 — **storefront** если клиент принимает в офисе |
| 4 | Service area | Уфа + при необходимости РБ (не заменяет адрес офиса) |
| 5 | Phone / website | Как §1 |
| 6 | Hours | Как §1 |
| 7 | Categories | Primary: Travel agency; secondary только по факту услуг |
| 8 | Attributes | Wheelchair / parking — только если подтверждено |
| 9 | Posts / photos | Редкие обновления направлений сезона без цен-гарантий |
| 10 | Messaging | Если включено — тот же SLA, что телефон в часы работы |
| 11 | Reviews | Политика §10; **без** AggregateRating на сайте до реальных данных |

GBP менее критичен для локального SEO Уфы, чем Яндекс и 2ГИС, но NAP должен совпадать побитово.

---

## 5. 2GIS checklist (РФ)

| # | Пункт | Требование |
|---|--------|------------|
| 1 | Название и адрес | = §1 |
| 2 | Рубрики | Турагентства, Туристические агентства |
| 3 | Контакты | Телефон, сайт, email |
| 4 | Время работы | = §1 |
| 5 | Фото офиса / входа | Для навигации «как пройти» |
| 6 | Отзывы | Синхронная стратегия с Яндексом |
| 7 | Привязка к зданию | ул. Юрия Гагарина, 74/1 — уточнить корпус/офис на карте |

После публикации карточки: добавить URL в `sameAs` (только с подтверждением клиента).

---

## 6. Maps embed policy

| Правило | Значение |
|---------|----------|
| Где embed | Только `/kontakty/` (основной); дублировать iframe на главной **не нужно** |
| Провайдер P0 | **Яндекс.Карты** (рынок Уфа) |
| Реализация в теме | `page-kontakty.php`: widget `yandex.ru/map-widget/v1/` с `ll=55.979,54.738`, `pt=55.979,54.738` |
| Google Maps | Опционально P2; один провайдер на страницу достаточно |
| lazy loading | `loading="lazy"` на iframe ✅ |
| title | Осмысленный `title` для a11y (есть) |
| privacy | Упомянуть карты в политике cookies при необходимости (conversion-map) |
| Координаты schema | `GeoCoordinates`: latitude **54.738**, longitude **55.979** — **сверить с пином Яндекс Бизнес** после верификации; при расхождении править widget + JSON-LD вместе |
| Service area | Не рисовать полигон «весь мир»; текстово: клиенты из Уфы и РБ, вылет из аэропорта Уфы |

**Нет адреса → нет embed.** Адрес есть — embed обязателен на контактах (уже в теме).

---

## 7. LocalBusiness subtype

| Решение | `TravelAgency` (schema.org) |
|---------|------------------------------|
| Почему не generic `LocalBusiness` | Услуга — подбор/продажа туров, не гостиница и не авиакасса |
| Почему не `TouristInformationCenter` | Есть коммерческое бронирование и договор |
| Связь с GBP | Категория Travel agency |
| `@id` | `{canonical_home}/#organization` (как в теме) |
| Доп. типы | `Organization` через `@graph`; не дублировать противоречивый второй LocalBusiness |

---

## 8. Structured data requirements

Использовать только подтверждённые поля; детальная разбивка по страницам — `schema-technical-seo-map.md`.

### 8.1 `areaServed`

```json
"areaServed": [
  { "@type": "City", "name": "Уфа" },
  { "@type": "AdministrativeArea", "name": "Республика Башкортостан" },
  { "@type": "Country", "name": "Россия" }
]
```

Смысл: обслуживаем клиентов региона; **не** doorway-страницы под другие города.

### 8.2 `geo`

```json
"geo": {
  "@type": "GeoCoordinates",
  "latitude": 54.738,
  "longitude": 55.979
}
```

Статус: **theme-aligned, verify with Yandex pin** (см. §6).

### 8.3 `openingHoursSpecification`

Как в `inc/seo.php`:

| dayOfWeek | opens | closes |
|-----------|-------|--------|
| Monday–Friday | 10:00 | 19:00 |
| Saturday | 11:00 | 15:00 |
| Sunday | — | **закрыто** (не добавлять в schema, если нет обслуживания) |

Часовой пояс: `Europe/Moscow` (Уфа, MSK+2).

### 8.4 `sameAs`

| URL | Статус |
|-----|--------|
| https://vk.com/zhiraf_ufa | ✅ confirmed |
| Яндекс Бизнес | ⏳ добавить после URL от клиента |
| 2ГИС | ⏳ |
| Google Business | ⏳ |
| Instagram / Telegram | ❌ не в fact-bank — не выдумывать |

### 8.5 Запрещено в schema

- `AggregateRating`, `Review`, `ratingValue`, `reviewCount`
- Выдуманные `priceRange`
- Несколько конкурирующих адресов в одном узле

---

## 9. Reviews strategy

| Принцип | Действие |
|---------|----------|
| On-site | Блок отзывов **выключен** (`zhiraf_ufa_show_reviews_block` default false) до реальных источников |
| Источники | Яндекс, 2ГИС, VK — после согласия клиента (fact-bank §needs_user_fact) |
| CTA | На `/o-nas/` или `/kontakty/` — мягкий текст «Оставить отзыв» со ссылкой на **подтверждённый** профиль, не на якорь с выдуманными именами |
| Ответы | В течение 2–3 рабочих дней; тон «семейный сервис», без споров в публичном поле |
| Импорт | Не копировать legacy-отзывы с zhiraf-ufa.ru без верификации |
| Schema | Без rating до стабильного потока и политики |

---

## 10. Location pages policy

| Тип страницы | Политика |
|--------------|----------|
| `/kontakty/` | Единственная **location**-страница с NAP + карта + реквизиты |
| `/tour/{country}/` | **Destination**, не geo-doorway: контент про направление и вылет из Уфы, не «Туры в Москве» |
| Города РФ кроме Уфы | **Не создавать** `/tury-iz-kazani/` и т.п. без фактов офиса/вылета |
| Блог GEO | Упоминание «из Уфы» в контексте аэропорта/офиса — OK (Excalibur) |
| Дубли NAP | Footer на всех страницах — достаточно для local signals |
| Thin local | Запрещены шаблоны «Турагентство в {город}» с подменой одного слова |

---

## 11. Aurora / wp-theme-builder implementation

| ID | Требование | Файл / зона |
|----|------------|-------------|
| LE-01 | NAP в footer на всех шаблонах | `footer.php` |
| LE-02 | Полный NAP + форма + карта | `page-kontakty.php` |
| LE-03 | TravelAgency JSON-LD | `inc/seo.php` |
| LE-04 | РТА 0012822 в trust/footer | `footer.php`, `front-page.php`, `/o-nas/` |
| LE-05 | tel/mailto единые | header, sticky, kontakty |
| LE-06 | Не показывать fake reviews | theme mod / default false |
| LE-07 | После cutover canonical URL в schema = zhiraf-ufa.ru | indexing + seo maps |
| LE-08 | При появлении URL каталогов — расширить `sameAs` | `inc/seo.php` filter/theme mod |

---

## 12. Blockers и needs_user_fact

| ID | Блокер | Владелец |
|----|--------|----------|
| LE-B01 | Нет URL Яндекс Бизнес / 2ГИС / GBP в репозитории | Клиент |
| LE-B02 | Яндекс verification / Metrika ID | Клиент |
| LE-B03 | Подтверждённые ссылки для отзывов | Клиент |
| LE-B04 | Точные координаты пина vs widget (если расхождение) | Контент/клиент |

**Не блокирует READY контракта:** сайт и тема уже несут согласованный NAP; внешние каталоги — операционный чеклист.

---

## 13. Cross-refs

- `teya-memory/wp/schema-technical-seo-map.md` — детализация JSON-LD по URL
- `teya-memory/wp/indexing-crawl-map.md` — canonical host
- `teya-memory/wp/conversion-funnel-map.md` — телефон как North Star
- `teya-memory/wp/page-content-pack.md` — copy контактов и answer-блок
