<?php
/**
 * Homepage destinations hub (Ufa / Moscow tabs).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Raw hub card definitions per departure city.
 *
 * @return array<string,array<int,array<string,mixed>>>
 */
function zhiraf_ufa_home_destinations_hub_config() {
	return array(
		'ufa' => array(
			array(
				'slug'        => 'turciya',
				'flag'        => '🇹🇷',
				'title'       => __( 'Турция', 'zhiraf-ufa' ),
				'teaser'      => __( 'Анталья, Кемер, Сиде • All Inclusive', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Прямой чартер', 'zhiraf-ufa' ),
				'image'       => 'dest-turkey-new.jpg',
			),
			array(
				'slug'        => 'oae',
				'flag'        => '🇦🇪',
				'title'       => __( 'ОАЭ', 'zhiraf-ufa' ),
				'teaser'      => __( 'Дубай, Шарджа, Рас-эль-Хайма', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Flydubai', 'zhiraf-ufa' ),
				'image'       => 'dest-uae-new.jpg',
			),
			array(
				'slug'        => 'egipet',
				'flag'        => '🇪🇬',
				'title'       => __( 'Египет', 'zhiraf-ufa' ),
				'teaser'      => __( 'Шарм-эль-Шейх, Хургада • All Inclusive', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Прямой чартер', 'zhiraf-ufa' ),
				'image'       => 'dest-egypt-new.jpg',
			),
			array(
				'slug'        => 'tailand',
				'flag'        => '🇹🇭',
				'title'       => __( 'Таиланд', 'zhiraf-ufa' ),
				'teaser'      => __( 'Пхукет, Паттайя • Завтраки', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Прямой рейс', 'zhiraf-ufa' ),
				'image'       => 'dest-thailand-new.jpg',
			),
			array(
				'slug'        => 'rossiya',
				'flag'        => '🇷🇺',
				'title'       => __( 'Россия (Сочи)', 'zhiraf-ufa' ),
				'teaser'      => __( 'Имеретинский, Красная Поляна', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Без загранпаспорта', 'zhiraf-ufa' ),
				'image'       => 'hero-collage-1.jpg',
			),
			array(
				'slug'        => 'abkhaziya',
				'flag'        => '🇬🇪',
				'title'       => __( 'Абхазия', 'zhiraf-ufa' ),
				'teaser'      => __( 'Гагра, Пицунда • Море и горы', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Трансфер из Сочи', 'zhiraf-ufa' ),
				'image'       => 'alt-armeniya-3.jpg',
			),
		),
		'mow' => array(
			array(
				'slug'        => 'maldivy',
				'flag'        => '🇲🇻',
				'title'       => __( 'Мальдивы', 'zhiraf-ufa' ),
				'teaser'      => __( 'Атоллы, виллы на воде • All Inclusive', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Регулярный рейс', 'zhiraf-ufa' ),
				'image'       => 'resort-maldivy-atoll-1.jpg',
			),
			array(
				'slug'        => 'shri-lanka',
				'flag'        => '🇱🇰',
				'title'       => __( 'Шри-Ланка', 'zhiraf-ufa' ),
				'teaser'      => __( 'Бентота, Хиккадува • Завтраки', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Регулярный рейс', 'zhiraf-ufa' ),
				'image'       => 'country-shri-lanka.jpg',
			),
			array(
				'slug'        => 'vietnam',
				'flag'        => '🇻🇳',
				'title'       => __( 'Вьетнам', 'zhiraf-ufa' ),
				'teaser'      => __( 'Нячанг, Фукуок • Пляж и SPA', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Регулярный рейс', 'zhiraf-ufa' ),
				'image'       => 'dest-vietnam-new.jpg',
			),
			array(
				'slug'        => 'kitaj',
				'flag'        => '🇨🇳',
				'title'       => __( 'Китай (Хайнань)', 'zhiraf-ufa' ),
				'teaser'      => __( 'Санья • Пляж и тропики', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Регулярный рейс', 'zhiraf-ufa' ),
				'image'       => 'resort-sanya-2.jpg',
			),
			array(
				'slug'        => 'indiya',
				'flag'        => '🇮🇳',
				'title'       => __( 'Индия (Гоа)', 'zhiraf-ufa' ),
				'teaser'      => __( 'Северный и Южный Гоа • Завтраки', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Регулярный рейс', 'zhiraf-ufa' ),
				'image'       => 'country-indiya.jpg',
			),
			array(
				'slug'        => 'indoneziya',
				'flag'        => '🇮🇩',
				'title'       => __( 'Индонезия (Бали)', 'zhiraf-ufa' ),
				'teaser'      => __( 'Бали • Пляж и завтраки', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Регулярный рейс', 'zhiraf-ufa' ),
				'image'       => 'alt-indoneziya-beach-1.jpg',
			),
			array(
				'slug'        => 'sejshely',
				'flag'        => '🇸🇨',
				'title'       => __( 'Сейшелы', 'zhiraf-ufa' ),
				'teaser'      => __( 'Маэ, Праслин • Романтика и пляж', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Регулярный рейс', 'zhiraf-ufa' ),
				'image'       => 'country-sejshely.jpg',
			),
			array(
				'slug'        => 'uzbekistan',
				'flag'        => '🇺🇿',
				'title'       => __( 'Узбекистан', 'zhiraf-ufa' ),
				'teaser'      => __( 'Самарканд, Бухара • экскурсии', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Прямой рейс', 'zhiraf-ufa' ),
				'image'       => 'resort-samarkand.jpg',
			),
			array(
				'slug'        => 'armeniya',
				'flag'        => '🇦🇲',
				'title'       => __( 'Армения', 'zhiraf-ufa' ),
				'teaser'      => __( 'Ереван, озеро Севан • гастрономия', 'zhiraf-ufa' ),
				'flight_badge'=> __( 'Прямой рейс', 'zhiraf-ufa' ),
				'image'       => 'alt-armeniya-yerevan.jpg',
			),
		),
	);
}

/**
 * Min tour for hub card: departure city + ~7 nights preference.
 *
 * @param string $country_slug Country slug.
 * @param string $departure    ufa|mow.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_home_hub_min_tour( $country_slug, $departure = 'ufa' ) {
	if ( ! function_exists( 'zhiraf_ufa_get_country_tours' ) ) {
		return null;
	}
	$departure = 'mow' === $departure ? 'mow' : 'ufa';
	$best      = null;
	$best_score = PHP_INT_MAX;

	foreach ( zhiraf_ufa_get_country_tours( $country_slug, 250 ) as $t ) {
		if ( empty( $t['price_rur'] ) ) {
			continue;
		}
		$dep_city = (string) ( $t['departure_city'] ?? '' );
		if ( 'mow' === $departure ) {
			if ( false === mb_stripos( $dep_city, 'моск', 0, 'UTF-8' ) ) {
				continue;
			}
		} elseif ( false === mb_stripos( $dep_city, 'уф', 0, 'UTF-8' ) ) {
			continue;
		}

		$nights = (int) ( $t['nights'] ?? 0 );
		if ( $nights <= 0 ) {
			$nights = 7;
		}
		$night_penalty = abs( $nights - 7 );
		$score         = ( $night_penalty * 1_000_000 ) + (int) $t['price_rur'];
		if ( $score < $best_score ) {
			$best_score = $score;
			$best       = $t;
		}
	}

	if ( $best ) {
		return $best;
	}

	return function_exists( 'zhiraf_ufa_get_country_min_tour' )
		? zhiraf_ufa_get_country_min_tour( $country_slug )
		: null;
}

/**
 * Resolve landing URL for a hub slug.
 *
 * @param string $slug Country slug.
 */
function zhiraf_ufa_home_hub_destination_url( $slug ) {
	return home_url( '/tour/' . rawurlencode( (string) $slug ) . '/' );
}

/**
 * Build render-ready cards for a departure tab.
 *
 * @param string $departure ufa|mow.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_home_destinations_hub_cards( $departure = 'ufa' ) {
	$config = zhiraf_ufa_home_destinations_hub_config();
	$key    = 'mow' === $departure ? 'mow' : 'ufa';
	$rows   = isset( $config[ $key ] ) ? $config[ $key ] : array();
	$out    = array();

	foreach ( $rows as $row ) {
		$slug = (string) $row['slug'];
		$min  = zhiraf_ufa_home_hub_min_tour( $slug, $key );
		$price_label = '';
		if ( $min && ! empty( $min['price_rur'] ) ) {
			$price_label = sprintf(
				/* translators: %s: formatted price */
				__( 'от %s ₽', 'zhiraf-ufa' ),
				number_format( (int) $min['price_rur'], 0, '.', ' ' )
			);
		}
		$img = zhiraf_ufa_theme_image( (string) $row['image'], (string) $row['title'] );
		$out[] = array_merge(
			$row,
			array(
				'url'         => zhiraf_ufa_home_hub_destination_url( $slug ),
				'price_label' => $price_label,
				'photo_url'   => ! empty( $img['url'] ) ? (string) $img['url'] : '',
				'photo_alt'   => ! empty( $img['alt'] ) ? (string) $img['alt'] : (string) $row['title'],
			)
		);
	}

	return $out;
}
