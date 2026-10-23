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
		'oae'        => 'battle',
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
		'rossiya'    => 'vibe',
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
			'image' => 'country-tailand.jpg',
			'alt'   => __( 'Таиланд — лазурное море и пляж', 'zhiraf-ufa' ),
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
			'title'   => __( 'Пхукет или Паттайя: что выбрать?', 'zhiraf-ufa' ),
			'lead'    => __( 'Лазурное Андаманское море и островной релакс или движ, экскурсии и Ко Лан — главный выбор первой поездки в Таиланд.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Пхукет', 'zhiraf-ufa' ),
					'tag'       => __( '🏝 Лазурное море и островной релакс', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Пляжи Карон, Ката, Банг Тао и островные экскурсии', 'zhiraf-ufa' ),
					'image'     => 'dest-thailand.jpg',
					'cta_label' => __( 'Смотреть отели на Пхукете', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Море и пляжи', 'zhiraf-ufa' ), 'value' => __( 'Чистое Андаманское море, белый песок — Карон, Ката, Банг Тао', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Резорты с садами, виллы и первая линия у моря', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Природа', 'zhiraf-ufa' ), 'value' => __( 'Пхи-Пхи, Симиланы, смотровые площадки и джунгли', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Паттайя', 'zhiraf-ufa' ),
					'tag'       => __( '🌆 Движ, экономия и экскурсии', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Бангкок рядом, Ко Лан за 15–20 минут на пароме', 'zhiraf-ufa' ),
					'image'     => 'dest-thailand-new.jpg',
					'cta_label' => __( 'Смотреть отели в Паттайе', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Бюджет', 'zhiraf-ufa' ), 'value' => __( 'Туры и кафе часто на 20–30% доступнее, чем на Пхукете', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Экскурсии', 'zhiraf-ufa' ), 'value' => __( 'Бангкок за 2 часа, Квай, Нонг Нуч, зоопарки', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Море', 'zhiraf-ufa' ), 'value' => __( 'За лазурной водой — паром на остров Ко Лан', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'oae'     => array(
			'title'   => __( 'Дубай или Рас-эль-Хайма: какой эмират выбрать?', 'zhiraf-ufa' ),
			'lead'    => __( 'Энергия небоскрёбов и шопинга или беззаботный курортный All Inclusive на первой линии.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Дубай', 'zhiraf-ufa' ),
					'tag'       => __( '🏙 Драйв и шопинг', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для активных туристов, пар и любителей впечатлений', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~20 мин', 'zhiraf-ufa' ),
					'image'     => 'dest-uae-new.jpg',
					'cta_label' => __( 'Искать туры в Дубай', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Формат отдыха', 'zhiraf-ufa' ), 'value' => __( 'Шопинг, рестораны, смотровые площадки, динамичная городская среда', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Питание', 'zhiraf-ufa' ), 'value' => __( 'Чаще завтраки (BB) или полупансион — обеды и ужины в городе', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Пляжи', 'zhiraf-ufa' ), 'value' => __( 'Городские пляжи JBR, La Mer или бич-клабы на Пальме', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Рас-эль-Хайма', 'zhiraf-ufa' ),
					'tag'       => __( '🏖 All Inclusive и релакс', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Идеально для семей с детьми и спокойного отдыха у моря', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~60 мин', 'zhiraf-ufa' ),
					'image'     => 'dest-uae.jpg',
					'cta_label' => __( 'Искать туры в Рас-эль-Хайму', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Формат отдыха', 'zhiraf-ufa' ), 'value' => __( 'Спокойный пляжный релакс, большие территории, отдых «в тапочках»', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Питание', 'zhiraf-ufa' ), 'value' => __( 'Классический All Inclusive / Ultra AI в отеле', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Пляжи', 'zhiraf-ufa' ), 'value' => __( 'Собственные песчаные пляжи отелей на первой линии', 'zhiraf-ufa' ) ),
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
			'title'   => __( 'Хургада или Шарм-эль-Шейх: что выбрать?', 'zhiraf-ufa' ),
			'lead'    => __( 'Песчаный заход для семьи или кораллы и безветренные бухты на зиму — главная дилемма Красного моря.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Хургада', 'zhiraf-ufa' ),
					'tag'       => __( '🏖 Песок и семейный отдых', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Пологий заход, аквапарки и экскурсии к Луксору', 'zhiraf-ufa' ),
					'image'     => 'resort-egipet-hurghada-2.jpg',
					'cta_label' => __( 'Искать отели в Хургаде', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Вход в море', 'zhiraf-ufa' ), 'value' => __( 'Пологий песок — комфортно детям и пожилым без аквашузов', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Крупные комплексы с аквапарками и широкими пляжами', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Экскурсии', 'zhiraf-ufa' ), 'value' => __( 'Удобнее добираться до Луксора и Каира, чем из Шарма', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Шарм-эль-Шейх', 'zhiraf-ufa' ),
					'tag'       => __( '🤿 Кораллы и безветренные бухты', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Рифы у понтона и защищённые бухты зимой', 'zhiraf-ufa' ),
					'image'     => 'resort-egipet-sharm-2.jpg',
					'cta_label' => __( 'Искать отели в Шарме', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Подводный мир', 'zhiraf-ufa' ), 'value' => __( 'Коралловые рифы у берега, снорклинг и дайвинг с понтона', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Зимний комфорт', 'zhiraf-ufa' ), 'value' => __( 'Бухты Наама-Бей, Шарм-эль-Майя, Рас-Умм-Сид, Хадаба', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Вечерний движ', 'zhiraf-ufa' ), 'value' => __( 'Старый город, променад Наама-Бей и кафе Farsha', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'abkhaziya' => array(
			'title'   => __( 'Гагра или Пицунда', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title' => __( 'Гагра', 'zhiraf-ufa' ),
					'tag'   => __( 'Вечерний движ', 'zhiraf-ufa' ),
					'image' => 'country-armeniya.jpg',
					'rows'  => array(
						array( 'label' => __( 'Атмосфера', 'zhiraf-ufa' ), 'value' => __( 'Набережная, рестораны, парки', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title' => __( 'Пицунда', 'zhiraf-ufa' ),
					'tag'   => __( 'Спокойное море', 'zhiraf-ufa' ),
					'image' => 'alt-armeniya-2.jpg',
					'rows'  => array(
						array( 'label' => __( 'Атмосфера', 'zhiraf-ufa' ), 'value' => __( 'Сосновые рощи, тихие пляжи без волн', 'zhiraf-ufa' ) ),
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
			if ( $battle_pack && ! empty( $battle_pack['columns'] ) ) {
				$has_rich_battle = false;
				if ( ! empty( $columns ) ) {
					foreach ( $columns as $col ) {
						if ( ! empty( $col['cta_label'] ) || ( ! empty( $col['rows'] ) && count( $col['rows'] ) >= 3 ) ) {
							$has_rich_battle = true;
							break;
						}
					}
				}
				if ( empty( $columns ) || ! $has_rich_battle ) {
					$columns = $battle_pack['columns'];
					if ( '' === $title && ! empty( $battle_pack['title'] ) ) {
						$title = $battle_pack['title'];
					}
				}
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
			if ( 'turciya' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать курорт в Турции', 'zhiraf-ufa' );
			} elseif ( 'oae' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать курорт в ОАЭ', 'zhiraf-ufa' );
			} elseif ( 'egipet' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Хургадой и Шармом', 'zhiraf-ufa' );
			} elseif ( 'tailand' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Пхукетом и Паттайей', 'zhiraf-ufa' );
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
