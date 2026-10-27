<?php
/**
 * Unified conversion stack for country & resort landings (TZ v2).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_agency_guarantee_items() {
	return array(
		array(
			'icon'  => '🦒',
			'title' => __( 'С 2011 года', 'zhiraf-ufa' ),
			'text'  => __( 'Работаем в Уфе очно и онлайн — без «серых» схем и скрытых доплат.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🛡',
			'title' => __( 'Финансовые гарантии', 'zhiraf-ufa' ),
			'text'  => __( 'Договор и оплата через туроператоров с действующими гарантиями Ростourism.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🏨',
			'title' => __( 'Проверяем отели', 'zhiraf-ufa' ),
			'text'  => __( 'Смотрим отзывы, питание и пляж — не продаём «кота в мешке».', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '📱',
			'title' => __( '24/7 в поездке', 'zhiraf-ufa' ),
			'text'  => __( 'Менеджер на связи в WhatsApp, если что-то пошло не так на курорте.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * @param string              $slug Context slug (country or country/resort).
 * @param array<string,mixed> $ctx  Landing row.
 * @return int Baseline price for two adults (RUB).
 */
function zhiraf_ufa_landing_baseline_price_rur( $slug, $ctx ) {
	$country = $slug;
	if ( str_contains( $slug, '/' ) ) {
		$parts   = explode( '/', $slug, 2 );
		$country = $parts[0];
	}
	$cards = zhiraf_ufa_get_country_top_tours( $country, 1 );
	if ( ! empty( $cards[0]['price_rur'] ) ) {
		return (int) $cards[0]['price_rur'];
	}
	$fallback = array(
		'turciya'    => 120000,
		'egipet'     => 130000,
		'tailand'    => 150000,
		'vietnam'    => 140000,
		'oae'        => 180000,
		'maldivy'    => 250000,
		'uzbekistan' => 65000,
		'armeniya'   => 55000,
		'kitaj'      => 110000,
		'indiya'     => 100000,
	);
	return isset( $fallback[ $country ] ) ? (int) $fallback[ $country ] : 120000;
}

/**
 * @param int $amount RUB.
 */
function zhiraf_ufa_price_tier_format_amount( $amount ) {
	return sprintf(
		/* translators: %s: formatted price */
		__( 'от %s ₽', 'zhiraf-ufa' ),
		number_format( max( (int) $amount, 0 ), 0, '.', ' ' )
	);
}

/**
 * Country slug from landing context slug.
 *
 * @param string $slug Context slug.
 */
function zhiraf_ufa_landing_country_slug( $slug ) {
	if ( str_contains( $slug, '/' ) ) {
		$parts = explode( '/', $slug, 2 );
		return $parts[0];
	}
	return $slug;
}

/**
 * Default tier photos per country.
 *
 * @param string $country Country slug.
 * @return array{comfort:string,family:string,premium:string}
 */
function zhiraf_ufa_landing_price_tier_images( $country ) {
	$map = array(
		'turciya' => array( 'resort-turciya-side-1.jpg', 'resort-turciya-belek-1.jpg', 'resort-turciya-belek-2.jpg' ),
		'egipet'  => array( 'hero-egypt.jpg', 'dest-egypt.jpg', 'dest-egypt-new.jpg' ),
		'tailand' => array( 'country-tailand.jpg', 'resort-phuket.jpg', 'country-tailand.jpg' ),
		'vietnam' => array( 'resort-vietnam-phu-quoc-2.jpg', 'resort-vietnam-nha-trang-1.jpg', 'resort-vietnam-phu-quoc-1.jpg' ),
		'oae'     => array( 'hero-uae.jpg', 'dest-uae.jpg', 'dest-uae-new.jpg' ),
	);
	$fallback = array( 'hero-collage-1.jpg', 'hero-collage-2.jpg', 'hero-collage-3.jpg' );
	$pick     = isset( $map[ $country ] ) ? $map[ $country ] : $fallback;
	return array(
		'comfort' => $pick[0],
		'family'  => $pick[1],
		'premium' => $pick[2],
	);
}

/**
 * Fixed Turkey tier pack (marketing anchors).
 *
 * @param string $prefill Quiz prefill base.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_turciya_price_tiers( $prefill ) {
	$images = zhiraf_ufa_landing_price_tier_images( 'turciya' );
	return array(
		array(
			'id'           => 'comfort',
			'badge'        => __( 'Базовый комфорт', 'zhiraf-ufa' ),
			'title'        => __( 'Отели 3–4★', 'zhiraf-ufa' ),
			'price_amount' => 85000,
			'price_suffix' => __( 'на двоих под ключ', 'zhiraf-ufa' ),
			'image'        => $images['comfort'],
			'image_alt'    => __( 'Отели 3–4 звезды в Турции', 'zhiraf-ufa' ),
			'bullets'      => array(
				__( '2-я линия или городской отель у набережной', 'zhiraf-ufa' ),
				__( 'Питание: завтраки или базовый All Inclusive', 'zhiraf-ufa' ),
				__( 'Для моря, прогулок и экскурсий без переплаты', 'zhiraf-ufa' ),
			),
			'cta_label'    => __( 'Смотреть отели 3–4★', 'zhiraf-ufa' ),
			'prefill_tag'  => $prefill . ' · 3–4★',
			'featured'     => false,
		),
		array(
			'id'           => 'family',
			'badge'        => __( 'Всё включено', 'zhiraf-ufa' ),
			'title'        => __( 'Первая линия 4–5★', 'zhiraf-ufa' ),
			'price_amount' => 140000,
			'price_suffix' => __( 'на двоих под ключ', 'zhiraf-ufa' ),
			'image'        => $images['family'],
			'image_alt'    => __( 'Семейные отели все включено в Турции', 'zhiraf-ufa' ),
			'bullets'      => array(
				__( 'Собственный пляж прямо у отеля (песок / мелкая галька)', 'zhiraf-ufa' ),
				__( 'Водные горки, детские клубы и анимация', 'zhiraf-ufa' ),
				__( 'Полноценный All Inclusive без скрытых доплат', 'zhiraf-ufa' ),
			),
			'cta_label'    => __( 'Смотреть отели 1 линии', 'zhiraf-ufa' ),
			'prefill_tag'  => $prefill . ' · 1 линия AI',
			'featured'     => true,
			'hit_label'    => __( 'Хит сезона', 'zhiraf-ufa' ),
		),
		array(
			'id'           => 'premium',
			'badge'        => __( 'Ultra VIP', 'zhiraf-ufa' ),
			'title'        => __( 'Премиум 5★ / Ultra All', 'zhiraf-ufa' ),
			'price_amount' => 210000,
			'price_suffix' => __( 'на двоих под ключ', 'zhiraf-ufa' ),
			'image'        => $images['premium'],
			'image_alt'    => __( 'Премиум отели 5 звезд Ultra All Inclusive в Турции', 'zhiraf-ufa' ),
			'bullets'      => array(
				__( 'Огромные зелёные территории, виллы и спа-комплексы', 'zhiraf-ufa' ),
				__( 'Импортный алкоголь, рестораны a la carte', 'zhiraf-ufa' ),
				__( 'Безупречный сервис и приватные зоны отдыха', 'zhiraf-ufa' ),
			),
			'cta_label'    => __( 'Смотреть премиум 5★', 'zhiraf-ufa' ),
			'prefill_tag'  => $prefill . ' · premium 5★',
			'featured'     => false,
		),
	);
}

/**
 * Three price-orientation cards (not hotel catalog).
 *
 * @param string              $slug Context slug.
 * @param array<string,mixed> $ctx  Landing config.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_landing_price_tiers( $slug, $ctx ) {
	$country = zhiraf_ufa_landing_country_slug( $slug );
	$name    = ! empty( $ctx['name'] ) ? (string) $ctx['name'] : __( 'направление', 'zhiraf-ufa' );
	$prefill = ! empty( $ctx['quiz_prefill'] ) ? (string) $ctx['quiz_prefill'] : $name;

	if ( 'turciya' === $country ) {
		return zhiraf_ufa_turciya_price_tiers( $prefill );
	}

	$base    = zhiraf_ufa_landing_baseline_price_rur( $slug, $ctx );
	$comfort = (int) round( $base * 0.92 / 5000 ) * 5000;
	$family  = (int) round( $base * 1.28 / 5000 ) * 5000;
	$premium = (int) round( $base * 1.75 / 5000 ) * 5000;
	$imgs    = zhiraf_ufa_landing_price_tier_images( $country );

	return array(
		array(
			'id'           => 'comfort',
			'badge'        => __( 'Комфорт', 'zhiraf-ufa' ),
			'title'        => __( 'Уютный комфорт 3–4★', 'zhiraf-ufa' ),
			'price_amount' => max( $comfort, 50000 ),
			'price_suffix' => __( 'на двоих под ключ', 'zhiraf-ufa' ),
			'image'        => $imgs['comfort'],
			'image_alt'    => sprintf(
				/* translators: %s: destination name */
				__( 'Отели 3–4★ — %s', 'zhiraf-ufa' ),
				$name
			),
			'bullets'      => array(
				__( 'Оптимальный баланс цены и сервиса', 'zhiraf-ufa' ),
				__( 'Завтраки или базовое питание', 'zhiraf-ufa' ),
				__( 'Подходит для первой поездки', 'zhiraf-ufa' ),
			),
			'cta_label'    => __( 'Запросить подборку', 'zhiraf-ufa' ),
			'prefill_tag'  => $prefill . ' · comfort',
			'featured'     => false,
		),
		array(
			'id'           => 'family',
			'badge'        => __( 'Семейный', 'zhiraf-ufa' ),
			'title'        => __( 'All Inclusive / 1-я линия', 'zhiraf-ufa' ),
			'price_amount' => $family,
			'price_suffix' => __( 'на двоих под ключ', 'zhiraf-ufa' ),
			'image'        => $imgs['family'],
			'image_alt'    => sprintf( __( 'Семейные отели — %s', 'zhiraf-ufa' ), $name ),
			'bullets'      => array(
				__( 'Пляж, детские зоны и питание «всё включено»', 'zhiraf-ufa' ),
				__( 'Проверенные отели для семьи', 'zhiraf-ufa' ),
				__( 'Популярный выбор из Уфы', 'zhiraf-ufa' ),
			),
			'cta_label'    => __( 'Запросить подборку', 'zhiraf-ufa' ),
			'prefill_tag'  => $prefill . ' · family-ai',
			'featured'     => true,
			'hit_label'    => __( 'Хит выбора', 'zhiraf-ufa' ),
		),
		array(
			'id'           => 'premium',
			'badge'        => __( 'Премиум', 'zhiraf-ufa' ),
			'title'        => __( 'Премиум 5★ / виллы', 'zhiraf-ufa' ),
			'price_amount' => $premium,
			'price_suffix' => __( 'на двоих под ключ', 'zhiraf-ufa' ),
			'image'        => $imgs['premium'],
			'image_alt'    => sprintf( __( 'Премиум отели — %s', 'zhiraf-ufa' ), $name ),
			'bullets'      => array(
				__( 'Максимум сервиса и приватности', 'zhiraf-ufa' ),
				__( 'Ultra / VIP концепции', 'zhiraf-ufa' ),
				__( 'SPA, a la carte, импортные напитки', 'zhiraf-ufa' ),
			),
			'cta_label'    => __( 'Запросить подборку', 'zhiraf-ufa' ),
			'prefill_tag'  => $prefill . ' · premium',
			'featured'     => false,
		),
	);
}

/**
 * Express picker recommendation matrix (who × priority).
 *
 * @param string              $slug Country or resort context slug.
 * @param array<string,mixed> $ctx  Config.
 * @return array<string,array<string,array{area:string,concept:string,prefill:string}>>
 */
function zhiraf_ufa_express_picker_matrix( $slug, $ctx ) {
	$defaults = array(
		'kids'    => array(
			'beach'  => array(
				'area'     => __( 'Песчаный заход и спокойное море', 'zhiraf-ufa' ),
				'concept'  => __( 'Семейный резорт 4–5★ с анимацией и детским меню', 'zhiraf-ufa' ),
				'prefill'  => __( 'семья с детьми · спокойное море', 'zhiraf-ufa' ),
			),
			'price'  => array(
				'area'     => __( 'Проверенные отели с AI в среднем сегменте', 'zhiraf-ufa' ),
				'concept'  => __( 'Комфорт 3–4★ без переплаты за «звёздность ради звёзд»', 'zhiraf-ufa' ),
				'prefill'  => __( 'семья с детьми · бюджет', 'zhiraf-ufa' ),
			),
			'party'  => array(
				'area'     => __( 'Курорт с инфраструктурой и коротким трансфером', 'zhiraf-ufa' ),
				'concept'  => __( 'Отель с аквапарком / детским клубом и вечерними активностями', 'zhiraf-ufa' ),
				'prefill'  => __( 'семья с детьми · активности', 'zhiraf-ufa' ),
			),
			'hotel'  => array(
				'area'     => __( 'Закрытая территория и первая линия', 'zhiraf-ufa' ),
				'concept'  => __( 'Семейный All Inclusive с большим бассейном и пляжем', 'zhiraf-ufa' ),
				'prefill'  => __( 'семья с детьми · отель важнее курорта', 'zhiraf-ufa' ),
			),
		),
		'couple'  => array(
			'beach'  => array(
				'area'     => __( 'Бухта без волн и лишнего шума', 'zhiraf-ufa' ),
				'concept'  => __( 'Adults friendly / бутик 4–5★ для пары', 'zhiraf-ufa' ),
				'prefill'  => __( 'пара · спокойное море', 'zhiraf-ufa' ),
			),
			'price'  => array(
				'area'     => __( 'Оптимальный курорт по соотношению цена/море', 'zhiraf-ufa' ),
				'concept'  => __( 'Комфорт 3–4★ с завтраками или полупансионом', 'zhiraf-ufa' ),
				'prefill'  => __( 'пара · бюджет', 'zhiraf-ufa' ),
			),
			'party'  => array(
				'area'     => __( 'Курорт с вечерней жизнью и экскурсиями', 'zhiraf-ufa' ),
				'concept'  => __( 'Городской или смешанный формат 4★', 'zhiraf-ufa' ),
				'prefill'  => __( 'пара · тусовка', 'zhiraf-ufa' ),
			),
			'hotel'  => array(
				'area'     => __( 'Премиальная линия или закрытый резорт', 'zhiraf-ufa' ),
				'concept'  => __( '5★ / вилла с SPA и приватным пляжем', 'zhiraf-ufa' ),
				'prefill'  => __( 'пара · премиум-отель', 'zhiraf-ufa' ),
			),
		),
		'friends' => array(
			'beach'  => array(
				'area'     => __( 'Широкий пляж и простая логистика', 'zhiraf-ufa' ),
				'concept'  => __( 'Отели 3–4★ рядом с кафе и экскурсиями', 'zhiraf-ufa' ),
				'prefill'  => __( 'компания · пляж', 'zhiraf-ufa' ),
			),
			'price'  => array(
				'area'     => __( 'Популярный курорт с доступными турами', 'zhiraf-ufa' ),
				'concept'  => __( 'Комфорт без переплаты, можно BB/HB', 'zhiraf-ufa' ),
				'prefill'  => __( 'компания · цена', 'zhiraf-ufa' ),
			),
			'party'  => array(
				'area'     => __( 'Центр курорта / набережная', 'zhiraf-ufa' ),
				'concept'  => __( 'Отель рядом с барами, рынками и экскурсиями', 'zhiraf-ufa' ),
				'prefill'  => __( 'компания · тусовка', 'zhiraf-ufa' ),
			),
			'hotel'  => array(
				'area'     => __( 'Большой резорт с инфраструктурой', 'zhiraf-ufa' ),
				'concept'  => __( 'All Inclusive с бассейнами и вечерней программой', 'zhiraf-ufa' ),
				'prefill'  => __( 'компания · отель', 'zhiraf-ufa' ),
			),
		),
		'solo'    => array(
			'beach'  => array(
				'area'     => __( 'Спокойная бухта для купания', 'zhiraf-ufa' ),
				'concept'  => __( 'Компактный отель 3–4★ у моря', 'zhiraf-ufa' ),
				'prefill'  => __( 'соло · спокойное море', 'zhiraf-ufa' ),
			),
			'price'  => array(
				'area'     => __( 'Курорт с недорогими отелями', 'zhiraf-ufa' ),
				'concept'  => __( 'Гостиница 3★ с завтраками', 'zhiraf-ufa' ),
				'prefill'  => __( 'соло · бюджет', 'zhiraf-ufa' ),
			),
			'party'  => array(
				'area'     => __( 'Городской курорт с событиями', 'zhiraf-ufa' ),
				'concept'  => __( 'Отель в центре — экскурсии и кафе', 'zhiraf-ufa' ),
				'prefill'  => __( 'соло · активности', 'zhiraf-ufa' ),
			),
			'hotel'  => array(
				'area'     => __( 'Отель с хорошими отзывами и SPA', 'zhiraf-ufa' ),
				'concept'  => __( '4–5★ с завтраками и трансфером', 'zhiraf-ufa' ),
				'prefill'  => __( 'соло · комфортный отель', 'zhiraf-ufa' ),
			),
		),
	);

	$overrides = array(
		'egipet' => array(
			'kids' => array(
				'beach' => array(
					'area'    => __( 'Хургада — песчаный пологий заход', 'zhiraf-ufa' ),
					'concept' => __( 'Семейный AI с аквапарком на первой линии', 'zhiraf-ufa' ),
					'prefill' => __( 'Египет · семья · Хургада', 'zhiraf-ufa' ),
				),
			),
		),
		'turciya' => array(
			'kids' => array(
				'beach' => array(
					'area'    => __( 'Аланья / Сиде — песок и мелкий заход', 'zhiraf-ufa' ),
					'concept' => __( 'Семейный резорт с аквапарком', 'zhiraf-ufa' ),
					'prefill' => __( 'Турция · семья · песчаный курорт', 'zhiraf-ufa' ),
				),
			),
		),
	);

	$country = str_contains( $slug, '/' ) ? explode( '/', $slug, 2 )[0] : $slug;
	if ( isset( $overrides[ $country ] ) ) {
		foreach ( $overrides[ $country ] as $who => $prio_map ) {
			foreach ( $prio_map as $prio => $row ) {
				$defaults[ $who ][ $prio ] = array_merge( $defaults[ $who ][ $prio ], $row );
			}
		}
	}

	$dest_name = ! empty( $ctx['name'] ) ? (string) $ctx['name'] : '';
	if ( $dest_name ) {
		foreach ( $defaults as $who => $prio_map ) {
			foreach ( $prio_map as $prio => $row ) {
				if ( ! empty( $row['prefill'] ) && ! str_contains( $row['prefill'], $dest_name ) ) {
					$defaults[ $who ][ $prio ]['prefill'] = $dest_name . ' · ' . $row['prefill'];
				}
			}
		}
	}

	return $defaults;
}

/**
 * Render battle + express picker + price tiers + guarantees.
 *
 * @param string              $slug Country slug or country/resort.
 * @param array<string,mixed> $ctx  Landing config.
 * @param string              $kind destination|resort.
 */
function zhiraf_ufa_render_landing_conversion_stack( $slug, $ctx, $kind = 'destination' ) {
	if ( 'destination' === $kind && function_exists( 'zhiraf_ufa_render_destination_battle_module' ) ) {
		zhiraf_ufa_render_destination_battle_module( $slug, $ctx );
	} elseif ( 'resort' === $kind && ! empty( $ctx['resort_compare'] ) && count( $ctx['resort_compare'] ) >= 2 ) {
		$resort_cols = $ctx['resort_compare'];
		foreach ( $resort_cols as $i => $col ) {
			if ( empty( $resort_cols[ $i ]['cta_href'] ) || '#dest-tours' === $resort_cols[ $i ]['cta_href'] ) {
				$resort_cols[ $i ]['cta_href'] = '#dest-express-pick';
			}
		}
		get_template_part(
			'template-parts/destination/module-resort-battle',
			null,
			array(
				'title'   => ! empty( $ctx['resort_compare_title'] ) ? (string) $ctx['resort_compare_title'] : '',
				'lead'    => ! empty( $ctx['resort_compare_lead'] ) ? (string) $ctx['resort_compare_lead'] : '',
				'columns' => $resort_cols,
				'wa_text' => ! empty( $ctx['quiz_prefill'] ) ? sprintf( __( 'Здравствуйте! Помогите выбрать район в %s', 'zhiraf-ufa' ), $ctx['quiz_prefill'] ) : __( 'Здравствуйте! Помогите выбрать курорт', 'zhiraf-ufa' ),
			)
		);
	}

	get_template_part(
		'template-parts/destination/module-express-picker',
		null,
		array(
			'slug'         => $slug,
			'ctx'          => $ctx,
			'matrix'       => zhiraf_ufa_express_picker_matrix( $slug, $ctx ),
			'quiz_prefill' => ! empty( $ctx['quiz_prefill'] ) ? (string) $ctx['quiz_prefill'] : '',
		)
	);

	get_template_part(
		'template-parts/destination/module-price-tiers',
		null,
		array(
			'slug'  => $slug,
			'ctx'   => $ctx,
			'tiers' => zhiraf_ufa_landing_price_tiers( $slug, $ctx ),
		)
	);

	get_template_part(
		'template-parts/destination/module-agency-guarantees',
		null,
		array(
			'items' => zhiraf_ufa_agency_guarantee_items(),
		)
	);
}

/**
 * Battle-only helper (always use defaults when pack is richer).
 *
 * @param string              $slug Country slug.
 * @param array<string,mixed> $dest Landing row.
 */
function zhiraf_ufa_render_destination_battle_module( $slug, $dest ) {
	$title       = ! empty( $dest['resort_compare_title'] ) ? $dest['resort_compare_title'] : '';
	$columns     = ! empty( $dest['resort_compare'] ) ? $dest['resort_compare'] : array();
	$battle_pack = zhiraf_ufa_default_resort_battle( $slug );
	if ( $battle_pack && ! empty( $battle_pack['columns'] ) ) {
		$has_rich_battle = false;
		if ( ! empty( $columns ) ) {
			foreach ( $columns as $col ) {
				if ( ! empty( $col['cta_label'] ) && ! empty( $col['rows'] ) && count( $col['rows'] ) >= 2 ) {
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
		return;
	}
	if ( '' === $title && $battle_pack && ! empty( $battle_pack['title'] ) ) {
		$title = $battle_pack['title'];
	}
	$battle_lead = ! empty( $dest['resort_compare_lead'] ) ? (string) $dest['resort_compare_lead'] : '';
	if ( '' === $battle_lead && $battle_pack && ! empty( $battle_pack['lead'] ) ) {
		$battle_lead = (string) $battle_pack['lead'];
	}
	foreach ( $columns as $i => $col ) {
		if ( empty( $columns[ $i ]['cta_href'] ) || '#dest-tours' === $columns[ $i ]['cta_href'] ) {
			$columns[ $i ]['cta_href'] = '#dest-express-pick';
		}
		if ( empty( $columns[ $i ]['cta_label'] ) ) {
			$columns[ $i ]['cta_label'] = __( 'Перейти к подбору', 'zhiraf-ufa' );
		}
	}
	get_template_part(
		'template-parts/destination/module-resort-battle',
		null,
		array(
			'title'   => $title,
			'lead'    => $battle_lead,
			'columns' => $columns,
			'wa_text' => ! empty( $dest['quiz_prefill'] ) ? sprintf( __( 'Здравствуйте! Помогите выбрать курорт в %s', 'zhiraf-ufa' ), $dest['quiz_prefill'] ) : __( 'Здравствуйте! Помогите выбрать курорт', 'zhiraf-ufa' ),
		)
	);
}

/**
 * Merge FAQ lists and cap at 6 entries.
 *
 * @param array<int,array{q:string,a:string}> ...$lists FAQ arrays.
 * @return array<int,array{q:string,a:string}>
 */
function zhiraf_ufa_merge_faq_lists( ...$lists ) {
	$out  = array();
	$seen = array();
	foreach ( $lists as $list ) {
		if ( empty( $list ) || ! is_array( $list ) ) {
			continue;
		}
		foreach ( $list as $item ) {
			if ( empty( $item['q'] ) ) {
				continue;
			}
			$key = mb_strtolower( (string) $item['q'], 'UTF-8' );
			if ( isset( $seen[ $key ] ) ) {
				continue;
			}
			$seen[ $key ] = true;
			$out[]        = $item;
			if ( count( $out ) >= 6 ) {
				return $out;
			}
		}
	}
	return $out;
}
