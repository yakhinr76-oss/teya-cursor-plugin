# Blog topics — 6 × P0 Excalibur + Wordstat Уфа (18.10.2026)

Согласовано с `deliverables/zhiraf-ufa/excalibur-articles/articles/{slug}/` и `06-url-map.csv`.

## Wordstat → контент (не новые slug в Phase 1)

| Запрос (Уфа) | Показы/мес. | Куда вести трафик |
|--------------|-------------|-------------------|
| горящие туры уфа | **3024** | `/goryashchie-tury/` + упоминание в BT-01 FAQ «срочный вылет» |
| все включено из уфы | **735** | `/vse-vklyucheno/` + **BT-02** (informational) |
| туры в турцию из уфы | **1117** | `/tour/turciya/` + BT-06 |
| туры в египет из уфы | **858** | `/tour/egipet/` + BT-06 |
| хургада из уфы | **327** | `/tour/egipet/hurgada/` |
| анталья из уфы | **122** | `/tour/turciya/antalya/` |
| пхукет из уфы | **125** | `/tour/tailand/phuket/` |
| нячанг / фукуок | **193** / **183** | дочерние VN |
| туры с детьми из уфы | **20** | **BT-04** + секции на странах (не URL) |
| кемер / аланья / паттайя | **82** / **58** / **49** | секции на `/tour/turciya/`, `/tour/tailand/` |

---

## BT-01 — `kak-kupit-tur-v-ufe` (P0, slug без изменений)

| Поле | Значение |
|------|----------|
| **topic_id** | BT-01 |
| **slug** | `kak-kupit-tur-v-ufe` |
| **URL** | `/blog/kak-kupit-tur-v-ufe/` |
| **cluster_id** | CL-BLOG-BUY |
| **priority** | **P0** |

**H1:** Как купить тур в Уфе: от заявки до вылета  
**Primary query:** как купить тур в уфе  
**Wordstat angle:** доп. абзац в CTA — если нужен срочный вылет, см. [горящие туры из Уфы](/goryashchie-tury/) (**3024** показов в регионе; без обещания «самой низкой цены»).

**Internal links:** → `/`, `/kontakty/`, `/tour/`, `/goryashchie-tury/`, BT-03  
**Excalibur:** publish as-is; добавить 1 anchor на `/goryashchie-tury/` при интеграции (Excalibur integrator).

---

## BT-02 — `chto-vhodit-v-tur-vse-vklyucheno` (P0)

| **cluster_id** | CL-AI |
| **priority** | **P0** |

**H1:** Что входит в тур «всё включено»: простыми словами  
**Primary query:** что входит в тур все включено  
**Wordstat angle:** в лиде — запрос **«всё включено из Уфы»** (**735** показов) закрывает коммерческая [/vse-vklyucheno/](/vse-vklyucheno/); статья объясняет состав пакета.

**Secondary topics (не отдельные статьи Phase 1):** ai и uai разница; что входит в путевку турция (**2589** broad — anchor `/tour/turciya/`).

**Internal links:** → `/vse-vklyucheno/`, `/tour/turciya/`, `/tour/egipet/`, BT-04

---

## BT-03 — `dokumenty-dlya-zagranicy-iz-ufy` (P0)

**Primary query:** документы для заграницы  
**Wordstat angle:** без частот в тексте; GEO чек-лист.  
**Internal links:** → BT-01, `/kontakty/`, `/tour/oae/` (виза — «уточнять при подборе»)

---

## BT-04 — `tury-s-detmi-iz-ufy` (P0)

**Primary query:** туры с детьми из уфы (**20** показов/мес. — низкий, но P0 по аудитории)  
**Wordstat angle:** явно указать: отдельный коммерческий URL не делаем (ниже порога 100); критерии — в статье + секции «с детьми» на `/tour/turciya/`, `/tour/egipet/`, `/tour/tailand/`, `/tour/vietnam/`.

**Internal links:** → `/tour/turciya/`, `/tour/egipet/`, `/vse-vklyucheno/`, BT-02  
**Resort anchors:** Анталья, Хургада — как примеры семейных курортов (без «лучший»).

---

## BT-05 — `strahovka-i-rassrochka-na-tur` (P0)

**Primary query:** страховка для путешествия  
**Secondary angle:** рассрочка — вопросы менеджеру; optional mention горящих (**3024**) — «срочная поездка не отменяет проверку полиса».

**Internal links:** → BT-01, `/tour/`, `/goryashchie-tury/`

---

## BT-06 — `turciya-ili-egipet-iz-ufy` (P0)

**Primary query:** турция или египет из уфы  
**Wordstat angle:** в таблице сравнения — показы **1117** (TR из Уфы) vs **858** (EG из Уфы); broad **2589** / **2071** — в сноске «общий спрос по стране».

**Internal links:** → `/tour/turciya/`, `/tour/egipet/`, `/tour/egipet/hurgada/`, `/tour/turciya/antalya/`, `/vse-vklyucheno/`, `/`

**Secondary topics (Phase 2, не P0 slug):** «турция или египет всё включено» → anchors на `/vse-vklyucheno/` + BT-02.

---

## Вторичные темы (блог Phase 2 — только анкоры сейчас)

| Тема | Wordstat | Anchor target |
|------|----------|---------------|
| Горящие vs раннее бронирование | 3024 | `/goryashchie-tury/` |
| Курорт: Хургада vs Шарм | 327 / 242 | дочерние EG |
| Вьетнам: Нячанг vs Фукуок | 193 / 183 | дочерние VN |
| Дубай в составе ОАЭ | 87 | секция `/tour/oae/` |
| Паттайя vs Пхукет | 49 / 125 | секция TH + `/tour/tailand/phuket/` |

---

## Сводка приоритетов публикации

1. BT-01 (как купить) + anchor горящие  
2. BT-02 (всё включено) + `/vse-vklyucheno/`  
3. BT-06 (сравнение → лендинги + курорты)  
4. BT-03, BT-04, BT-05

**Excalibur P0 slugs (не менять):** `kak-kupit-tur-v-ufe`, `chto-vhodit-v-tur-vse-vklyucheno`, `dokumenty-dlya-zagranicy-iz-ufy`, `tury-s-detmi-iz-ufy`, `strahovka-i-rassrochka-na-tur`, `turciya-ili-egipet-iz-ufy`.
