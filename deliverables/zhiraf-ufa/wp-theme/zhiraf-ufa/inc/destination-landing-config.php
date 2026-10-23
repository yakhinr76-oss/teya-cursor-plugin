<?php
/**
 * Destination landing presentation: hero standards, central module switcher, admin override.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Central interactive module types (one per landing page).
 *
 * @return array<string,string> slug => label
 */
function zhiraf_ufa_destination_module_types() {
	return array(
		'battle' => __( 'Батл курортов (VS)', 'zhiraf-ufa' ),
		'vibe'   => __( 'Выбор по вайбу', 'zhiraf-ufa' ),
		'season' => __( 'Сезонность и климат', 'zhiraf-ufa' ),
	);
}

/**
 * Default module per destination slug.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_destination_default_module_map() {
	return array(
		'turciya'    => 'battle',
		'oae'        => 'vibe',
		'egipet'     => 'battle',
		'tailand'    => 'battle',
		'vietnam'    => 'battle',
		'kitaj'      => 'season',
		'indiya'     => 'season',
		'shri-lanka' => 'season',
		'indoneziya' => 'season',
		'maldivy'    => 'vibe',
		'sejshely'   => 'vibe',
		'uzbekistan' => 'vibe',
		'armeniya'   => 'vibe',
		'rossiya'    => 'battle',
		'abkhaziya'  => 'battle',
	);
}

/**
 * Hero image + alt standards (resort emotion, not landmarks/construction).
 *
 * @return array<string,array{image:string,alt:string}>
 */
function zhiraf_ufa_destination_hero_image_standards() {
	return array(
		'turciya'    => array(
			'image' => 'resort-turciya-kemer-2.jpg',
			'alt'   => __( 'Турция — Средиземное море, курорт Кемер', 'zhiraf-ufa' ),
		),
		'egipet'     => array(
			'image' => 'hero-egypt.jpg',
			'alt'   => __( 'Египет — Красное море и пляжный отель', 'zhiraf-ufa' ),
		),
		'tailand'    => array(
			'image' => 'resort-phuket.jpg',
			'alt'   => __( 'Таиланд — пляж с пальмами, Пхукет', 'zhiraf-ufa' ),
		),
		'vietnam'    => array(
			'image' => 'resort-vietnam-phu-quoc-1.jpg',
			'alt'   => __( 'Вьетнам — пляж Фукуок', 'zhiraf-ufa' ),
		),
		'kitaj'      => array(
			'image' => 'resort-sanya-2.jpg',
			'alt'   => __( 'Китай — курорт Санья, Хайнань', 'zhiraf-ufa' ),
		),
		'indiya'     => array(
			'image' => 'resort-severnyj-goa.jpg',
			'alt'   => __( 'Индия — пляж Северного Гоа', 'zhiraf-ufa' ),
		),
		'rossiya'    => array(
			'image' => 'hero-collage-1.jpg',
			'alt'   => __( 'Сочи — Имеретинская набережная и море', 'zhiraf-ufa' ),
		),
		'abkhaziya'  => array(
			'image' => 'alt-armeniya-2.jpg',
			'alt'   => __( 'Абхазия — сосны и бирюзовое море у Пицунды', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Default logistics badges when not set in landing pack.
 *
 * @param string $slug Destination slug.
 * @return array<int,array{icon?:string,text:string}>
 */
function zhiraf_ufa_destination_default_hero_badges( $slug ) {
	$map = array(
		'turciya'   => array(
			array( 'icon' => '✈', 'text' => __( 'Прямой рейс или стыковка в Москве из Уфы', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без визы • до 90 дней', 'zhiraf-ufa' ) ),
		),
		'oae'       => array(
			array( 'icon' => '✈', 'text' => __( 'Прямой рейс из Уфы ~4,5–5 ч', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без визы • 90 дней', 'zhiraf-ufa' ) ),
		),
		'egipet'    => array(
			array( 'icon' => '✈', 'text' => __( 'Вылет из Уфы в Хургаду / Шарм', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Виза по прилёту / e-visa', 'zhiraf-ufa' ) ),
		),
		'tailand'   => array(
			array( 'icon' => '✈', 'text' => __( 'Стыковка в Москве из Уфы', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без визы • до 30/45/90 дней', 'zhiraf-ufa' ) ),
		),
		'vietnam'   => array(
			array( 'icon' => '✈', 'text' => __( 'Стыковка в Москве из Уфы', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без визы • до 45 дней', 'zhiraf-ufa' ) ),
		),
		'abkhaziya' => array(
			array( 'icon' => '🚐', 'text' => __( 'Трансфер из аэропорта Адлер (Сочи) до отеля', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Паспорт РФ • оплата в рублях', 'zhiraf-ufa' ) ),
		),
		'rossiya'   => array(
			array( 'icon' => '🏨', 'text' => __( 'Пакет с перелётом или только проживание', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без загранпаспорта', 'zhiraf-ufa' ) ),
		),
	);

	return isset( $map[ $slug ] ) ? $map[ $slug ] : array();
}

/**
 * Resolve active central module for a landing.
 *
 * @param string              $slug Destination slug.
 * @param array<string,mixed> $dest Landing row.
 * @param int                 $page_id Page ID.
 */
function zhiraf_ufa_destination_landing_module( $slug, $dest, $page_id = 0 ) {
	if ( ! empty( $dest['landing_module'] ) && array_key_exists( $dest['landing_module'], zhiraf_ufa_destination_module_types() ) ) {
		return (string) $dest['landing_module'];
	}
	if ( ! $page_id ) {
		$page_id = get_queried_object_id();
	}
	if ( $page_id ) {
		$meta = get_post_meta( $page_id, '_zhiraf_landing_module', true );
		if ( is_string( $meta ) && array_key_exists( $meta, zhiraf_ufa_destination_module_types() ) ) {
			return $meta;
		}
	}
	$defaults = zhiraf_ufa_destination_default_module_map();
	if ( isset( $defaults[ $slug ] ) ) {
		return $defaults[ $slug ];
	}
	return 'vibe';
}

/**
 * Apply hero standards and defaults to landing config.
 *
 * @param array<string,mixed> $dest Landing row.
 * @param string              $slug Slug.
 * @return array<string,mixed>
 */
function zhiraf_ufa_apply_destination_presentation( $dest, $slug ) {
	$standards = zhiraf_ufa_destination_hero_image_standards();
	if ( isset( $standards[ $slug ] ) && empty( $dest['hero_image_override'] ) ) {
		$dest['image']     = $standards[ $slug ]['image'];
		$dest['image_alt'] = $standards[ $slug ]['alt'];
	}
	if ( empty( $dest['hero_badges'] ) ) {
		$badges = zhiraf_ufa_destination_default_hero_badges( $slug );
		if ( $badges ) {
			$dest['hero_badges'] = $badges;
		}
	}
	if ( empty( $dest['cta_lead'] ) && ! empty( $dest['name'] ) ) {
		/* translators: %s: country name */
		$dest['cta_lead'] = sprintf(
			__( 'Не уверены, какой отель выбрать в %s? Напишите бюджет и состав семьи — эксперт пришлёт подборку из 3 отелей в WhatsApp за 15 минут.', 'zhiraf-ufa' ),
			$dest['name']
		);
	}
	if ( empty( $dest['cta_message_placeholder'] ) ) {
		$dest['cta_message_placeholder'] = __( 'Бюджет, даты, дети (возраст), пожелания по курорту', 'zhiraf-ufa' );
	}
	return $dest;
}

/**
 * Default resort battle blocks when not in landing pack.
 *
 * @param string $slug Destination slug.
 * @return array{title:string,columns:array}|null
 */
function zhiraf_ufa_default_resort_battle( $slug ) {
	$battles = array(
		'tailand' => array(
			'title'   => __( 'Пхукет или Паттайя: что выбрать', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title' => __( 'Пхукет', 'zhiraf-ufa' ),
					'tag'   => __( 'Остров и пляжи', 'zhiraf-ufa' ),
					'image' => 'resort-phuket.jpg',
					'rows'  => array(
						array( 'label' => __( 'Формат', 'zhiraf-ufa' ), 'value' => __( 'Карон, Ката, закаты, островные экскурсии', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Сезон', 'zhiraf-ufa' ), 'value' => __( 'ноябрь — апрель — штиль', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title' => __( 'Паттайя', 'zhiraf-ufa' ),
					'tag'   => __( 'Город и движ', 'zhiraf-ufa' ),
					'image' => 'dest-thailand-new.jpg',
					'rows'  => array(
						array( 'label' => __( 'Формат', 'zhiraf-ufa' ), 'value' => __( 'Шопинг, вечерняя жизнь, Ко Лан за 20 мин', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Сезон', 'zhiraf-ufa' ), 'value' => __( 'море спокойнее летом, чем на западе Пхукета', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'turciya' => array(
			'title'   => __( 'Кемер или Сиде и Аланья: где лучше море и отели', 'zhiraf-ufa' ),
			'lead'    => __( 'Главная дилемма турецкого отдыха: живописные горы с галькой или песчаный берег для детей.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Кемер и посёлки', 'zhiraf-ufa' ),
					'tag'       => __( '🌲 Горы и прохлада', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для ценителей природы и молодёжного отдыха', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~50 мин', 'zhiraf-ufa' ),
					'image'     => 'resort-turciya-kemer-2.jpg',
					'cta_label' => __( 'Искать отели в Кемере', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Пляж и море', 'zhiraf-ufa' ), 'value' => __( 'Галька — вода прозрачная даже в пик сезона', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Климат', 'zhiraf-ufa' ), 'value' => __( 'Сосны и горы Тавр, прохладнее, чем на равнине', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Клубные комплексы в Текирове, Бельдиби, Гёйнюке', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Сиде и Аланья', 'zhiraf-ufa' ),
					'tag'       => __( '🏖 Песок и аквапарки', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для семей с малышами и долгого купания', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: 1–2,5 ч', 'zhiraf-ufa' ),
					'image'     => 'resort-turciya-alanya-2.jpg',
					'cta_label' => __( 'Искать отели в Сиде / Аланье', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Пляж и море', 'zhiraf-ufa' ), 'value' => __( 'Песок и пологий заход — удобно с детьми', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Сезон', 'zhiraf-ufa' ), 'value' => __( 'Море дольше тёплое — часто до ноября', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Семейные резорты с аквапарками и детскими клубами', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'egipet'  => array(
			'title'   => __( 'Хургада или Шарм-эль-Шейх', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title' => __( 'Хургада', 'zhiraf-ufa' ),
					'tag'   => __( 'Семьи', 'zhiraf-ufa' ),
					'image' => 'resort-egipet-hurghada-1.jpg',
					'rows'  => array(
						array( 'label' => __( 'Пляж', 'zhiraf-ufa' ), 'value' => __( 'Песок, пологий заход', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title' => __( 'Шарм-эль-Шейх', 'zhiraf-ufa' ),
					'tag'   => __( 'Рифы', 'zhiraf-ufa' ),
					'image' => 'resort-egipet-sharm-1.jpg',
					'rows'  => array(
						array( 'label' => __( 'Пляж', 'zhiraf-ufa' ), 'value' => __( 'Понтоны, снорклинг у рифа', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'abkhaziya' => array(
			'title'   => __( 'Гагра или Пицунда: какой курорт выбрать?', 'zhiraf-ufa' ),
			'lead'    => __( 'Энергия курортной набережной с ресторанами или целебный сосновый микроклимат и спокойное море.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Гагра (Новая и Старая)', 'zhiraf-ufa' ),
					'tag'       => __( '🌴 Движ и набережная', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для молодёжи, пар и любителей вечерних прогулок', 'zhiraf-ufa' ),
					'meta'      => __( 'От границы: ~25 мин', 'zhiraf-ufa' ),
					'image'     => 'alt-armeniya-3.jpg',
					'cta_label' => __( 'Искать отели в Гагре', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Инфраструктура', 'zhiraf-ufa' ), 'value' => __( 'Приморский парк, ресторан «Гагрипш», кафе и аквапарк', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Ближе к границе', 'zhiraf-ufa' ), 'value' => __( 'Самый быстрый трансфер от аэропорта Адлер без длинного серпантина', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Горные панорамы', 'zhiraf-ufa' ), 'value' => __( 'Кавказские горы вплотную к морю — живописные виды', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Пицунда и Лдзаа', 'zhiraf-ufa' ),
					'tag'       => __( '🌲 Сосны и штиль', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Идеально для семей с детьми и оздоровления', 'zhiraf-ufa' ),
					'meta'      => __( 'От границы: ~50 мин', 'zhiraf-ufa' ),
					'image'     => 'alt-armeniya-2.jpg',
					'cta_label' => __( 'Искать отели в Пицунде', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Спокойное море', 'zhiraf-ufa' ), 'value' => __( 'Мыс закрывает бухту от ветров — волн почти не бывает, вода прозрачная', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Сосновая роща', 'zhiraf-ufa' ), 'value' => __( 'Целебный хвойный воздух прямо на берегу моря', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Песчаные участки', 'zhiraf-ufa' ), 'value' => __( 'У Рыбзавода (Лдзаа) — редкий для Абхазии песчаный вход в море', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'rossiya'   => array(
			'title'   => __( 'Имеретинка (Сириус) или Красная Поляна?', 'zhiraf-ufa' ),
			'lead'    => __( 'Широкая морская набережная без ж/д путей или чистый горный воздух и премиальные курорты.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Сириус (Имеретинская бухта)', 'zhiraf-ufa' ),
					'tag'       => __( '🌊 Море и самокаты', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для семей, прогулок у моря и активного отдыха', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~15 мин', 'zhiraf-ufa' ),
					'image'     => 'hero-collage-1.jpg',
					'cta_label' => __( 'Искать отели в Сириусе', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Без ж/д у пляжа', 'zhiraf-ufa' ), 'value' => __( 'Единственный район Сочи, где над пляжем не грохочут поезда', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Набережная 7 км', 'zhiraf-ufa' ), 'value' => __( 'Велодорожки, фонтаны, Сочи Парк, отели «Сириус», «Богатырь», Radisson', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Короткий путь', 'zhiraf-ufa' ), 'value' => __( '10–15 минут от трапа самолёта до дверей отеля', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Красная Поляна и Роза Хутор', 'zhiraf-ufa' ),
					'tag'       => __( '🏔 Горы и сервис 5★', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для ценителей высокого сервиса, SPA и природы', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~40 мин', 'zhiraf-ufa' ),
					'image'     => 'hero-collage-2.jpg',
					'cta_label' => __( 'Искать отели в Поляне', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Уровень сервиса', 'zhiraf-ufa' ), 'value' => __( 'Отели мировых брендов, подогреваемые бассейны с видом на горы', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Круглый год', 'zhiraf-ufa' ), 'value' => __( 'Зимой — лыжи, летом — треккинг и трансферы отелей на пляжи у моря', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Вечер', 'zhiraf-ufa' ), 'value' => __( 'Гастрономия региона и развлекательная программа', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
	);
	if ( ! isset( $battles[ $slug ] ) ) {
		return null;
	}
	return $battles[ $slug ];
}

/**
 * Render the single central module for a destination landing.
 *
 * @param string              $slug Slug.
 * @param array<string,mixed> $dest Landing config (enriched).
 */
function zhiraf_ufa_render_destination_central_module( $slug, $dest ) {
	$module = zhiraf_ufa_destination_landing_module( $slug, $dest );
	switch ( $module ) {
		case 'battle':
			$title       = ! empty( $dest['resort_compare_title'] ) ? $dest['resort_compare_title'] : '';
			$columns     = ! empty( $dest['resort_compare'] ) ? $dest['resort_compare'] : array();
			$battle_pack = zhiraf_ufa_default_resort_battle( $slug );
			if ( empty( $columns ) && $battle_pack ) {
				$title   = $battle_pack['title'];
				$columns = $battle_pack['columns'];
			}
			if ( empty( $columns ) ) {
				break;
			}
			if ( '' === $title && $battle_pack && ! empty( $battle_pack['title'] ) ) {
				$title = $battle_pack['title'];
			}
			$battle_lead = ! empty( $dest['resort_compare_lead'] ) ? (string) $dest['resort_compare_lead'] : '';
			if ( '' === $battle_lead && $battle_pack && ! empty( $battle_pack['lead'] ) ) {
				$battle_lead = (string) $battle_pack['lead'];
			}
			$wa_text = __( 'Здравствуйте! Помогите выбрать курорт', 'zhiraf-ufa' );
			if ( 'abkhaziya' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Гагрой и Пицундой', 'zhiraf-ufa' );
			} elseif ( 'rossiya' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Сириусом и Красной Поляной', 'zhiraf-ufa' );
			}
			get_template_part(
				'template-parts/destination/module-resort-battle',
				null,
				array(
					'title'   => $title,
					'lead'    => $battle_lead,
					'columns' => $columns,
					'wa_text' => $wa_text,
				)
			);
			if ( ! empty( $dest['vibes_after_central'] ) && function_exists( 'zhiraf_ufa_get_vibe_section' ) ) {
				$vibe_section = zhiraf_ufa_get_vibe_section( $slug, $dest );
				if ( $vibe_section ) {
					get_template_part(
						'template-parts/destination/vibe-cards',
						null,
						array(
							'section'      => $vibe_section,
							'quiz_prefill' => ! empty( $dest['quiz_prefill'] ) ? $dest['quiz_prefill'] : '',
						)
					);
				}
			}
			break;
		case 'season':
			$seasons = ! empty( $dest['climate_seasons'] ) ? $dest['climate_seasons'] : array();
			if ( empty( $seasons ) && ! empty( $dest['season'] ) ) {
				$seasons = array(
					array(
						'label'  => __( 'Обзор', 'zhiraf-ufa' ),
						'months' => '',
						'air'    => '',
						'water'  => '',
						'tip'    => (string) $dest['season'],
					),
				);
			}
			if ( empty( $seasons ) ) {
				break;
			}
			get_template_part(
				'template-parts/destination/module-season',
				null,
				array(
					'seasons'      => $seasons,
					'title'        => ! empty( $dest['climate_module_title'] ) ? $dest['climate_module_title'] : __( 'Сезонность и климат', 'zhiraf-ufa' ),
					'quiz_prefill' => ! empty( $dest['quiz_prefill'] ) ? $dest['quiz_prefill'] : '',
					'country_name' => ! empty( $dest['name'] ) ? $dest['name'] : '',
				)
			);
			break;
		case 'vibe':
		default:
			if ( ! function_exists( 'zhiraf_ufa_get_vibe_section' ) ) {
				break;
			}
			$vibe_section = zhiraf_ufa_get_vibe_section( $slug, $dest );
			if ( $vibe_section ) {
				get_template_part(
					'template-parts/destination/vibe-cards',
					null,
					array(
						'section'      => $vibe_section,
						'quiz_prefill' => ! empty( $dest['quiz_prefill'] ) ? $dest['quiz_prefill'] : '',
					)
				);
			}
			break;
	}
}

/**
 * Admin meta: pick central module on destination WP pages.
 */
function zhiraf_ufa_destination_module_meta_box() {
	$post = get_post();
	if ( ! $post || 'page' !== $post->post_type ) {
		return;
	}
	$slug = $post->post_name;
	if ( ! in_array( $slug, zhiraf_ufa_destination_landing_slugs(), true ) ) {
		return;
	}
	add_meta_box(
		'zhiraf-landing-module',
		__( 'Интерактивный блок посадочной', 'zhiraf-ufa' ),
		'zhiraf_ufa_destination_module_meta_box_render',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes_page', 'zhiraf_ufa_destination_module_meta_box' );

/**
 * @param WP_Post $post Post.
 */
function zhiraf_ufa_destination_module_meta_box_render( $post ) {
	wp_nonce_field( 'zhiraf_landing_module', 'zhiraf_landing_module_nonce' );
	$current = get_post_meta( $post->ID, '_zhiraf_landing_module', true );
	$types   = zhiraf_ufa_destination_module_types();
	$defaults = zhiraf_ufa_destination_default_module_map();
	$slug    = $post->post_name;
	echo '<p><label for="zhiraf_landing_module">' . esc_html__( 'Тип блока под каталогом туров', 'zhiraf-ufa' ) . '</label></p>';
	echo '<select name="zhiraf_landing_module" id="zhiraf_landing_module" class="widefat">';
	echo '<option value="">' . esc_html__( '— по умолчанию для направления —', 'zhiraf-ufa' ) . '</option>';
	foreach ( $types as $key => $label ) {
		printf(
			'<option value="%s"%s>%s</option>',
			esc_attr( $key ),
			selected( $current, $key, false ),
			esc_html( $label )
		);
	}
	echo '</select>';
	if ( isset( $defaults[ $slug ] ) ) {
		printf(
			'<p class="description">%s</p>',
			esc_html(
				sprintf(
					/* translators: %s: module label */
					__( 'По умолчанию для «%1$s»: %2$s', 'zhiraf-ufa' ),
					$slug,
					$types[ $defaults[ $slug ] ] ?? $defaults[ $slug ]
				)
			)
		);
	}
}

/**
 * Save module meta.
 *
 * @param int $post_id Post ID.
 */
function zhiraf_ufa_save_destination_module_meta( $post_id ) {
	if ( ! isset( $_POST['zhiraf_landing_module_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zhiraf_landing_module_nonce'] ) ), 'zhiraf_landing_module' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}
	$val = isset( $_POST['zhiraf_landing_module'] ) ? sanitize_key( wp_unslash( $_POST['zhiraf_landing_module'] ) ) : '';
	if ( '' === $val ) {
		delete_post_meta( $post_id, '_zhiraf_landing_module' );
		return;
	}
	if ( array_key_exists( $val, zhiraf_ufa_destination_module_types() ) ) {
		update_post_meta( $post_id, '_zhiraf_landing_module', $val );
	}
}
add_action( 'save_post_page', 'zhiraf_ufa_save_destination_module_meta' );
