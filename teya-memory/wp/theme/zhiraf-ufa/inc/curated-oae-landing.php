<?php
/**
 * UAE (/tour/oae/) curated 6-hotel showcase.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Path to uae_hotels_25.json.
 *
 * @return string
 */
function zhiraf_ufa_uae_hotels_json_path() {
	return defined( 'ZHIRAF_UFA_DIR' ) ? ZHIRAF_UFA_DIR . '/data/uae_hotels_25.json' : '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_oae_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_uae_hotels_json_path();
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_oae_json_by_id() {
	$map = array();
	foreach ( zhiraf_ufa_oae_json_rows() as $row ) {
		if ( ! empty( $row['id'] ) ) {
			$map[ (int) $row['id'] ] = $row;
		}
	}

	return $map;
}

/**
 * @return int RUB.
 */
function zhiraf_ufa_oae_hub_min_price_rub() {
	$min = function_exists( 'zhiraf_ufa_home_hub_min_tour' )
		? zhiraf_ufa_home_hub_min_tour( 'oae', 'ufa' )
		: null;
	if ( $min && ! empty( $min['price_rur'] ) ) {
		return (int) $min['price_rur'];
	}
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$tour = zhiraf_ufa_get_country_min_tour( 'oae' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			return (int) $tour['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_oae_base_price_rub', 185000 );
}

/**
 * @param int $amount RUB.
 * @return string
 */
function zhiraf_ufa_oae_curated_format_price( $amount ) {
	return sprintf(
		/* translators: %s: formatted amount */
		__( 'от %s ₽', 'zhiraf-ufa' ),
		number_format( max( 0, (int) $amount ), 0, '.', ' ' )
	);
}

/**
 * Editorial price hint when API has no tour.
 *
 * @param array<string,mixed> $row JSON row.
 * @return int
 */
function zhiraf_ufa_oae_editorial_price_rub( array $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'urban':
			return 168000;
		case 'family':
			return 198000;
		case 'balance':
			return 158000;
		default:
			return 172000;
	}
}

/**
 * @param string $emirate JSON emirate key.
 * @return string
 */
function zhiraf_ufa_oae_emirate_filter_slug( $emirate ) {
	$map = array(
		'dubai'        => 'dubai',
		'ras_al_khaimah' => 'rak',
		'sharjah'      => 'sharjah',
		'abu_dhabi'    => 'abudhabi',
		'ajman'        => 'ajman',
	);

	return $map[ sanitize_key( (string) $emirate ) ] ?? 'uae';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_oae_image_from_json_row( array $row ) {
	$photos = ! empty( $row['photos'] ) && is_array( $row['photos'] ) ? $row['photos'] : array();
	$first  = isset( $photos[0] ) ? trim( (string) $photos[0] ) : '';

	if ( preg_match( '#^https://#i', $first ) ) {
		return array(
			'image'        => $first,
			'image_remote' => true,
			'image_width'  => 0,
			'image_height' => 0,
		);
	}

	if ( '' !== $first ) {
		$base = basename( $first );
		foreach ( array( 'hotels/uae/' . $base, $base ) as $rel ) {
			if ( defined( 'ZHIRAF_UFA_DIR' ) && is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $rel ) ) {
				return array(
					'image'        => $rel,
					'image_remote' => false,
					'image_width'  => 0,
					'image_height' => 0,
				);
			}
		}
	}

	$emirate = ! empty( $row['emirate'] ) ? sanitize_key( (string) $row['emirate'] ) : '';
	$fallback = 'dest-uae-new.jpg';
	switch ( $emirate ) {
		case 'abu_dhabi':
			$fallback = 'resort-abu-dabi.jpg';
			break;
		case 'sharjah':
		case 'ajman':
			$fallback = 'dest-uae.jpg';
			break;
		case 'ras_al_khaimah':
			$fallback = 'dest-uae-new.jpg';
			break;
		default:
			$fallback = 'hero-uae.jpg';
	}

	return array(
		'image'        => $fallback,
		'image_remote' => false,
		'image_width'  => 0,
		'image_height' => 0,
	);
}

/**
 * @param string $hotel_name Hotel title.
 * @return string
 */
function zhiraf_ufa_oae_curated_messenger_prefill( $hotel_name ) {
	return sprintf(
		/* translators: %s: hotel name */
		__( 'Здравствуйте! Интересует %s — даты, цена за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
		(string) $hotel_name
	);
}

/**
 * Six editorial starters (json id + slot).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_oae_curated_starters() {
	$base_price = zhiraf_ufa_oae_hub_min_price_rub();

	return array(
		array(
			'slot'       => 'base',
			'json_id'    => 3,
			'badge'      => __( 'Старт по ОАЭ', 'zhiraf-ufa' ),
			'badge_mod'  => 'home-price',
			'price_rub'  => $base_price,
			'price_emphasis' => true,
		),
		array(
			'slot'      => 'family',
			'json_id'   => 1,
			'badge'     => __( 'Семьи и аквазона', 'zhiraf-ufa' ),
			'badge_mod' => 'family',
		),
		array(
			'slot'      => 'urban',
			'json_id'   => 11,
			'badge'     => __( 'Город + метро', 'zhiraf-ufa' ),
			'badge_mod' => 'value',
		),
		array(
			'slot'      => 'balance',
			'json_id'   => 6,
			'badge'     => __( 'Пляж у Дубая', 'zhiraf-ufa' ),
			'badge_mod' => 'default',
		),
		array(
			'slot'      => 'parks',
			'json_id'   => 5,
			'badge'     => __( 'Парки Яса', 'zhiraf-ufa' ),
			'badge_mod' => 'nature',
		),
		array(
			'slot'      => 'premium',
			'json_id'   => 2,
			'badge'     => __( 'All Inclusive 5★', 'zhiraf-ufa' ),
			'badge_mod' => 'premium',
		),
	);
}

/**
 * @param array<string,mixed>      $json   JSON hotel row.
 * @param array<string,mixed>      $starter Slot meta.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_oae_starter_to_card( array $json, array $starter ) {
	if ( empty( $json['hotel_name'] ) ) {
		return null;
	}

	$name        = (string) $json['hotel_name'];
	$resort_lbl  = ! empty( $json['resort'] ) ? (string) $json['resort'] : '';
	$emirate     = zhiraf_ufa_oae_emirate_filter_slug( ! empty( $json['emirate'] ) ? (string) $json['emirate'] : '' );
	$description = ! empty( $json['usp'] ) ? (string) $json['usp'] : '';
	$caveat      = ! empty( $json['caveat'] ) ? (string) $json['caveat'] : '';
	$rating      = ! empty( $json['rating'] ) ? (string) $json['rating'] : '';
	$image       = zhiraf_ufa_oae_image_from_json_row( $json );
	$stars       = '';
	if ( preg_match( '/(\d)★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}

	$card = array(
		'slot'            => ! empty( $starter['slot'] ) ? (string) $starter['slot'] : 'pool',
		'country_slug'    => 'oae',
		'resort'          => $emirate,
		'name'            => $name,
		'description'     => $description,
		'caveat'          => $caveat,
		'rating'          => $rating,
		'badge'           => ! empty( $starter['badge'] ) ? (string) $starter['badge'] : '',
		'badge_mod'       => ! empty( $starter['badge_mod'] ) ? (string) $starter['badge_mod'] : 'default',
		'resort_pill'     => $resort_lbl,
		'subtitle'        => $stars ? $stars : __( 'Отель у моря', 'zhiraf-ufa' ),
		'price_rub'       => isset( $starter['price_rub'] ) ? (int) $starter['price_rub'] : zhiraf_ufa_oae_editorial_price_rub( $json ),
		'price_emphasis'  => ! empty( $starter['price_emphasis'] ),
		'wa_prefill'      => zhiraf_ufa_oae_curated_messenger_prefill( $name ),
		'json_hotel_id'   => ! empty( $json['id'] ) ? (int) $json['id'] : 0,
		'image'           => $image['image'],
		'image_remote'    => ! empty( $image['image_remote'] ),
		'image_width'     => (int) ( $image['image_width'] ?? 0 ),
		'image_height'    => (int) ( $image['image_height'] ?? 0 ),
		'image_alt'       => $name,
	);

	return $card;
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_oae_apply_bg_pricing( array $card ) {
	$name = ! empty( $card['name'] ) ? trim( (string) $card['name'] ) : '';
	if ( '' === $name || ! function_exists( 'zhiraf_ufa_get_hotel_min_tour' ) ) {
		return $card;
	}

	$is_base = ! empty( $card['slot'] ) && 'base' === $card['slot'];
	$tour    = null;

	if ( $is_base && function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$tour = zhiraf_ufa_get_country_min_tour( 'oae' );
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'oae', $name );
	}

	if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
		$card['price_rub']    = (int) $tour['price_rur'];
		$card['price_source'] = 'tourvisor';
	}

	return $card;
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_oae_finalize_catalog_card( array $card ) {
	$rank = ! empty( $card['pool_rank'] ) ? (int) $card['pool_rank'] : 1;
	$card['pool_rank']   = $rank;
	$card['pool_id']     = ! empty( $card['pool_id'] ) ? (string) $card['pool_id'] : 'starter-' . ( ! empty( $card['slot'] ) ? $card['slot'] : $rank );
	$card['filter_tags'] = array( 'starter', 'emirate-' . ( ! empty( $card['resort'] ) ? sanitize_html_class( (string) $card['resort'] ) : 'uae' ) );

	return $card;
}

/**
 * Curated hotel cards for OAE hub.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_oae_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id  = zhiraf_ufa_oae_json_by_id();
	$hotels = array();
	$rank   = 1;

	foreach ( zhiraf_ufa_oae_curated_starters() as $starter ) {
		$json_id = ! empty( $starter['json_id'] ) ? (int) $starter['json_id'] : 0;
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$card = zhiraf_ufa_oae_starter_to_card( $by_id[ $json_id ], $starter );
		if ( ! $card ) {
			continue;
		}
		$card['pool_rank'] = $rank;
		$hotels[]          = zhiraf_ufa_oae_finalize_catalog_card( zhiraf_ufa_oae_apply_bg_pricing( $card ) );
		++$rank;
	}

	$cache = apply_filters( 'zhiraf_ufa_oae_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_oae_catalog_filters() {
	return array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Туры в ОАЭ: 6 проверенных вариантов', 'zhiraf-ufa' ),
			'desc'  => __( 'Дубай у моря, пляжи Шарджи, All Inclusive в Рас-эль-Хайме и база у парков Яса — с вылетом из Уфы.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Copy above curated grid.
 *
 * @return array{eyebrow?:string,title:string,desc:string}
 */
function zhiraf_ufa_oae_catalog_default_copy() {
	$filters = zhiraf_ufa_oae_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей ОАЭ', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в ОАЭ', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : '',
	);
}

/**
 * Default WhatsApp / messenger prefill for UAE resort compare.
 *
 * @return string
 */
function zhiraf_ufa_oae_resort_compare_wa_text() {
	return __( 'Здравствуйте! Сравните, пожалуйста, Дубай и Рас-эль-Хайму под наш бюджет — вылет из Уфы.', 'zhiraf-ufa' );
}

/**
 * Price note under curated card.
 *
 * @param array<string,mixed> $hotel Card.
 * @return string
 */
function zhiraf_ufa_oae_curated_price_note( array $hotel ) {
	unset( $hotel );

	return __( '7–9 ночей, ориентир (перелёт из Уфы, трансфер, страховка; питание по выбранному пакету).', 'zhiraf-ufa' );
}