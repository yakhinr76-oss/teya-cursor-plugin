# Site inventory — Phase 1 `zhiraf-ufa`

**Домен цель:** zhiraf-ufa.ru | **Staging:** b2b-tour.ru (noindex)

## Страницы в теме (есть шаблон / контент)

| URL | Шаблон / файл | Статус контента |
|-----|---------------|-----------------|
| `/` | `front-page.php` | Hero, табы направлений, квиз, блог-слот |
| `/o-nas/` | `page-o-nas.php` | История, команда |
| `/kontakty/` | `page-kontakty.php` | NAP, форма, карта |
| `/tour/` | `page-tour.php` | Хаб, чипы направлений |
| `/blog/` | `home.php` / archive | Архив постов |

## Планируемые лендинги (footer + `zhiraf_ufa_destinations()`)

| Slug | URL |
|------|-----|
| turciya | `/tour/turciya/` |
| oae | `/tour/oae/` |
| egipet | `/tour/egipet/` |
| tailand | `/tour/tailand/` |
| vietnam | `/tour/vietnam/` |

## Блог Excalibur (репозиторий, P0)

| Slug | topic_id |
|------|----------|
| `kak-kupit-tur-v-ufe` | BT-01 |
| `chto-vhodit-v-tur-vse-vklyucheno` | BT-02 |
| `dokumenty-dlya-zagranicy-iz-ufy` | BT-03 |
| `tury-s-detmi-iz-ufy` | BT-04 |
| `strahovka-i-rassrochka-na-tur` | BT-05 |
| `turciya-ili-egipet-iz-ufy` | BT-06 |

## Conversion assets

- Квиз: `quiz-modal.php`, 4 шага, лиды → office@zhiraf-ufa.ru
- Primary CTA: «Подобрать тур»
- Телефон: +7 901 810-50-18

## Schema

`TravelAgency` + `WebSite` — `inc/seo.php`

## Пробелы (не блокер ядра)

- Лендинги `/tour/{slug}/` — контент по `06-url-map.csv`
- FAQ на главной — рекомендовано в dossier §9
- Отзывы — намеренно отключены
