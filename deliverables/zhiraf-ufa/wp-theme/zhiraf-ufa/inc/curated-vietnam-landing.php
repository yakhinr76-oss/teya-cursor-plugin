<?php
/**
 * Vietnam (/tour/vietnam/) curated landing — 6 starters + pool from vietnam_hotels_25.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_vietnam_hub_min_price_rub() {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'vietnam' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_vietnam_base_price_rub', 178000 );
}

/**
 * @return string
 */
function zhiraf_ufa_vietnam_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'vietnam' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_vietnam_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_vietnam_hotels_json_path();
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}

/**
 * Resort filter keys for a JSON row (cam_ranh, phu_quoc, nha_trang, muine, danang_hoian).
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_vietnam_resort_keys_from_row( array $row ) {
	$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$keys   = array();
	foreach ( array( 'cam_ranh', 'phu_quoc', 'nha_trang', 'muine', 'danang_hoian' ) as $allowed ) {
		if ( $region === $allowed ) {
			$keys[] = $allowed;
		}
	}

	return array_values( array_unique( $keys ) );
}

/**
 * Hub anchor — отдельных курортных URL нет; чипы ведут на якорь каталога /tour/vietnam/#dest-curated-hotels.
 *
 * @param string $resort_key cam_ranh|phu_quoc|nha_trang|muine|danang_hoian.
 * @return string
 */
function zhiraf_ufa_vietnam_resort_landing_url( $resort_key ) {
	$map = array(
		'phu_quoc'  => 'fukuok',
		'nha_trang' => 'nyachang',
	);
	$slug = ! empty( $map[ sanitize_key( (string) $resort_key ) ] )
		? (string) $map[ sanitize_key( (string) $resort_key ) ]
		: sanitize_title( (string) $resort_key );
	if ( '' === $slug ) {
		return home_url( '/tour/vietnam/#dest-curated-hotels' );
	}

	return home_url( '/tour/vietnam/' . $slug . '/' );
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_vietnam_catalog_fill_resort_labels() {
	return array(
		'resort-cam_ranh'  => __( 'Камрани', 'zhiraf-ufa' ),
		'resort-phu_quoc'  => __( 'Фукуоке', 'zhiraf-ufa' ),
		'resort-nha_trang' => __( 'Нячанге', 'zhiraf-ufa' ),
		'resort-muine'     => __( 'Муйне', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_vietnam_need_tag_from_category( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	switch ( $category_slug ) {
		case 'family':
			return 'need-family';
		case 'couples':
			return 'need-adults';
		case 'premium':
			return 'need-premium';
		case 'urban':
			return 'need-urban';
		case 'cam_ranh_resorts':
			return 'need-ai';
		case 'balance':
		default:
			return 'need-price';
	}
}

/**
 * @param string $category_slug Category slug.
 * @return array{badge:string,badge_mod:string}
 */
function zhiraf_ufa_vietnam_pool_need_badge( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	if ( 'family' === $category_slug ) {
		return array(
			'badge'     => __( 'Семья и пляж', 'zhiraf-ufa' ),
			'badge_mod' => 'family',
		);
	}
	if ( 'couples' === $category_slug ) {
		return array(
			'badge'     => __( 'Для пар', 'zhiraf-ufa' ),
			'badge_mod' => 'adults',
		);
	}
	if ( 'premium' === $category_slug ) {
		return array(
			'badge'     => __( 'Премиум', 'zhiraf-ufa' ),
			'badge_mod' => 'premium',
		);
	}
	if ( 'urban' === $category_slug ) {
		return array(
			'badge'     => __( 'Нячанг / город', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
		);
	}
	if ( 'cam_ranh_resorts' === $category_slug ) {
		return array(
			'badge'     => __( 'All Inclusive Камрань', 'zhiraf-ufa' ),
			'badge_mod' => 'nature',
		);
	}

	return array(
		'badge'     => __( 'Цена / качество', 'zhiraf-ufa' ),
		'badge_mod' => 'value',
	);
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return int
 */
function zhiraf_ufa_vietnam_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 192000;
		case 'couples':
			return 218000;
		case 'premium':
			return 268000;
		case 'urban':
			return 178000;
		case 'cam_ranh_resorts':
			return 215000;
		case 'balance':
		default:
			return 188000;
	}
}

/**
 * @param string $name Hotel title.
 * @param string $cat  Category slug.
 * @return string
 */
function zhiraf_ufa_vietnam_pool_meal_concept( $name, $cat ) {
	$name  = (string) $name;
	$cat   = sanitize_key( (string) $cat );
	$stars = '';
	if ( preg_match( '/\b(\d)\s*★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}
	if ( 'cam_ranh_resorts' === $cat || 'family' === $cat ) {
		return trim( $stars . ' ' . __( '«Всё включено»', 'zhiraf-ufa' ) );
	}
	if ( 'urban' === $cat ) {
		return trim( $stars . ' ' . __( 'Завтраки + кафе рядом', 'zhiraf-ufa' ) );
	}

	return trim( $stars . ' ' . __( 'на завтраках', 'zhiraf-ufa' ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_vietnam_pool_card_usp( $row ) {
	if ( ! empty( $row['usp'] ) ) {
		return wp_trim_words( trim( (string) $row['usp'] ), 22, '…' );
	}
	if ( ! empty( $row['pros'] ) && is_array( $row['pros'] ) && ! empty( $row['pros'][0] ) ) {
		return wp_trim_words( trim( (string) $row['pros'][0] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_vietnam_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_vietnam_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );

		return trim( $parts[0] );
	}
	$keys = zhiraf_ufa_vietnam_resort_keys_from_row( $row );
	$labels = array(
		'cam_ranh'       => __( 'Камрань', 'zhiraf-ufa' ),
		'phu_quoc'       => __( 'Фукуок', 'zhiraf-ufa' ),
		'nha_trang'      => __( 'Нячанг', 'zhiraf-ufa' ),
		'muine'          => __( 'Муйне', 'zhiraf-ufa' ),
		'danang_hoian'   => __( 'Дананг / Хойан', 'zhiraf-ufa' ),
	);
	if ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {
		return $labels[ $keys[0] ];
	}

	return __( 'Вьетнам', 'zhiraf-ufa' );
}

/**
 * Unique theme fallbacks so six starters do not share one stock photo.
 *
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_vietnam_image_from_json_row( array $row ) {
	$pool_id = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$photos  = ! empty( $row['photos'] ) && is_array( $row['photos'] ) ? $row['photos'] : array();
	$first   = isset( $photos[0] ) ? trim( (string) $photos[0] ) : '';

	if ( preg_match( '#^https://#i', $first ) ) {
		return array(
			'image'        => $first,
			'image_remote' => true,
			'image_width'  => 0,
			'image_height' => 0,
		);
	}

	if ( '' !== $first && defined( 'ZHIRAF_UFA_DIR' ) ) {
		foreach ( array( 'hotels/vietnam/' . $first, $first ) as $rel ) {
			if ( is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $rel ) ) {
				return array(
					'image'        => $rel,
					'image_remote' => false,
					'image_width'  => 0,
					'image_height' => 0,
				);
			}
		}
	}

	$id_fallbacks = array(
		1  => 'resort-vietnam-cam-ranh-1.jpg',
		3  => 'resort-vietnam-cam-ranh-1.jpg',
		4  => 'resort-vietnam-phu-quoc-1.jpg',
		6  => 'resort-vietnam-phu-quoc-1.jpg',
		16 => 'resort-vietnam-nha-trang-1.jpg',
		23 => 'resort-vietnam-phu-quoc-1.jpg',
	);
	$region_fb    = array(
		'cam_ranh'  => 'resort-vietnam-cam-ranh-1.jpg',
		'phu_quoc'  => 'resort-vietnam-phu-quoc-1.jpg',
		'nha_trang' => 'resort-vietnam-nha-trang-1.jpg',
		'muine'     => 'resort-vietnam-phan-thiet-1.jpg',
	);
	$fallback     = ! empty( $id_fallbacks[ $pool_id ] ) ? $id_fallbacks[ $pool_id ] : 'hero-vietnam.jpg';
	$region       = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	if ( isset( $region_fb[ $region ] ) && 'hero-vietnam.jpg' === $fallback ) {
		$fallback = $region_fb[ $region ];
	}

	return array(
		'image'        => $fallback,
		'image_remote' => false,
		'image_width'  => 0,
		'image_height' => 0,
	);
}

/**
 * @return array<int,array{json_id:int,slot:string}>
 */
function zhiraf_ufa_vietnam_starter_definitions() {
	return array(
		array( 'json_id' => 1, 'slot' => 'base' ),
		array( 'json_id' => 3, 'slot' => 'family-camranh' ),
		array( 'json_id' => 4, 'slot' => 'family-phuquoc' ),
		array( 'json_id' => 6, 'slot' => 'couples' ),
		array( 'json_id' => 16, 'slot' => 'urban' ),
		array( 'json_id' => 23, 'slot' => 'balance' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_vietnam_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'family', 'family-camranh', 'family-phuquoc' ), true ) ) {
		$tags[] = 'need-family';
	} elseif ( 'couples' === $slot ) {
		$tags[] = 'need-adults';
	} elseif ( 'urban' === $slot ) {
		$tags[] = 'need-urban';
	} else {
		$tags[] = 'need-price';
	}

	return array_values( array_unique( $tags ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_vietnam_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_vietnam_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_vietnam_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_vietnam_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_vietnam_image_from_json_row( $row );

	$card = array(
		'destination_slug' => 'vietnam',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'cam_ranh',
		'pool_id'          => $pool_id,
		'json_hotel_id'    => $pool_id,
		'pool_rank'        => 100 + $pool_id,
		'category_slug'    => $cat,
		'filter_tags'      => array_values( array_unique( $tags ) ),
		'image'            => $image['image'],
		'image_remote'     => $image['image_remote'],
		'image_width'      => $image['image_width'],
		'image_height'     => $image['image_height'],
		'image_alt'        => sprintf(
			/* translators: %s: hotel name */
			__( 'Отель %s, Вьетнам', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_vietnam_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_vietnam_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_vietnam_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_vietnam_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_vietnam_pool_price_rub( $row ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_vietnam_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_vietnam_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_vietnam_card( $card );
		if ( ! empty( $with_tourvisor['price_from_api'] ) && 'tourvisor' === ( $with_tourvisor['price_source'] ?? '' ) ) {
			return $with_tourvisor;
		}
		$card = $with_tourvisor;
	}

	$name = ! empty( $card['name'] ) ? trim( (string) $card['name'] ) : '';
	if ( '' === $name || ! function_exists( 'zhiraf_ufa_get_hotel_min_tour' ) ) {
		return $card;
	}

	$editorial   = (int) ( $card['price_rub'] ?? 0 );
	$is_base     = ! empty( $card['slot'] ) && 'base' === $card['slot'];
	$bg_hotel_id = ! empty( $card['bg_hotel_id'] ) ? trim( (string) $card['bg_hotel_id'] ) : '';

	$tour        = null;
	$price_scope = '';

	if ( $is_base && function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$tour = zhiraf_ufa_get_country_min_tour( 'vietnam' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'vietnam', $name, $bg_hotel_id );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'hotel';
		} else {
			$tour = null;
		}
	}

	if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
		$card['price_rub']           = (int) $tour['price_rur'];
		$card['price_from_api']      = true;
		$card['price_scope']         = $price_scope;
		$card['tour_offer']          = $tour;
		$card['price_api_matched']   = ( 'hotel' === $price_scope );
		if ( ! empty( $tour['hotel_name'] ) ) {
			$card['bg_hotel_name'] = (string) $tour['hotel_name'];
		}
		if ( ! empty( $tour['hotel_id'] ) ) {
			$card['bg_hotel_id'] = (string) $tour['hotel_id'];
		}
	} else {
		$card['price_rub']             = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_vietnam_hub_min_price_rub() : 0 );
		$card['price_from_api']        = false;
		$card['price_api_matched']     = false;
		$card['price_api_unavailable'] = ! $is_base;
		unset( $card['tour_offer'], $card['bg_hotel_name'], $card['price_scope'] );
	}

	return $card;
}

/**
 * @param array<string,mixed> $card Card.
 * @return string
 */
function zhiraf_ufa_vietnam_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'VN-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'VN-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'VN-STARTER-' . $slot;
	}

	return 'VN-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_vietnam_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_vietnam_curated_uon_hotel_id( $card );
	$tour                 = null;
	if ( ! empty( $card['price_api_matched'] ) && ! empty( $card['tour_offer'] ) && is_array( $card['tour_offer'] ) ) {
		$tour = $card['tour_offer'];
	}
	$name = ! empty( $card['name'] ) ? (string) $card['name'] : '';
	if ( function_exists( 'zhiraf_ufa_turciya_curated_messenger_prefill' ) ) {
		$card['wa_prefill'] = zhiraf_ufa_turciya_curated_messenger_prefill( $name, $tour, $card['uon_hotel_id'] );
	}

	return $card;
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_vietnam_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_vietnam_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_vietnam_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_vietnam_json_row_to_card( $row );
		if ( ! $card ) {
			continue;
		}
		$slot     = ! empty( $def['slot'] ) ? sanitize_key( (string) $def['slot'] ) : 'starter';
		$key      = function_exists( 'zhiraf_ufa_oae_hotel_name_key' )
			? zhiraf_ufa_oae_hotel_name_key( (string) $card['name'] )
			: sanitize_title( (string) $card['name'] );
		$starter_keys[] = $key;

		$card['slot']        = $slot;
		$card['pool_id']     = 'starter-' . $slot;
		$card['pool_rank']   = $rank;
		$card['filter_tags'] = zhiraf_ufa_vietnam_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по Вьетнаму', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_vietnam_finalize_catalog_card( zhiraf_ufa_vietnam_apply_bg_pricing( $card ) );
		++$rank;
	}

	$json_by_name = array();
	foreach ( $by_id as $json_row ) {
		$key = function_exists( 'zhiraf_ufa_oae_hotel_name_key' )
			? zhiraf_ufa_oae_hotel_name_key( ! empty( $json_row['hotel_name'] ) ? (string) $json_row['hotel_name'] : '' )
			: sanitize_title( ! empty( $json_row['hotel_name'] ) ? (string) $json_row['hotel_name'] : '' );
		if ( '' !== $key ) {
			$json_by_name[ $key ] = $json_row;
		}
	}

	foreach ( $json_by_name as $key => $json_row ) {
		if ( '' === $key || in_array( $key, $starter_keys, true ) ) {
			continue;
		}
		$card = zhiraf_ufa_vietnam_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_vietnam_finalize_catalog_card( zhiraf_ufa_vietnam_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_vietnam_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_vietnam_catalog_filters() {
	return array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Туры во Вьетнам: 6 проверенных отелей', 'zhiraf-ufa' ),
			'desc'  => __( 'Семьи в Камрани и на Фукуоке, пары, городской Нячанг и баланс цены — Камрань, Фукуок, Нячанг, Муйне. Ориентир за двоих с перелётом (стыковка в Москве).', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-cam_ranh',
			'group' => 'resort',
			'label' => __( '🏝 Камрань', 'zhiraf-ufa' ),
			'title' => __( 'Отели Камрани', 'zhiraf-ufa' ),
			'desc'  => __( 'Бухта Бай Дай, крупные резорты All Inclusive и аквапарки — лучшие из базы 25.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-phu_quoc',
			'group' => 'resort',
			'label' => __( '🌴 Фукуок', 'zhiraf-ufa' ),
			'title' => __( 'Отели Фукуока', 'zhiraf-ufa' ),
			'desc'  => __( 'Островной релакс, белый песок и Vinpearl — отбор из базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-nha_trang',
			'group' => 'resort',
			'label' => __( '🌆 Нячанг', 'zhiraf-ufa' ),
			'title' => __( 'Отели Нячанга', 'zhiraf-ufa' ),
			'desc'  => __( 'Набережная Чан Фу, грязелечение и городские отели — до 6 лучших из базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-muine',
			'group' => 'resort',
			'label' => __( '🏜 Муйне', 'zhiraf-ufa' ),
			'title' => __( 'Отели Муйне / Фантьет', 'zhiraf-ufa' ),
			'desc'  => __( 'Широкие пляжи, дюны и виндсёрф — спокойная альтернатива Нячангу.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-family',
			'group' => 'need',
			'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ),
			'title' => __( 'Семейные отели', 'zhiraf-ufa' ),
			'desc'  => __( 'Аквапарки в Камрани, детские клубы и пологий песок.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-adults',
			'group' => 'need',
			'label' => __( '🥂 Для пар', 'zhiraf-ufa' ),
			'title' => __( 'Отели для пар', 'zhiraf-ufa' ),
			'desc'  => __( 'Виллы на Фукуоке, закаты и тихие бухты.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-urban',
			'group' => 'need',
			'label' => __( '🌆 Нячанг', 'zhiraf-ufa' ),
			'title' => __( 'Город и кафе', 'zhiraf-ufa' ),
			'desc'  => __( 'Променад, морепродукты и VinWonders — пешком до пляжа.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-ai',
			'group' => 'need',
			'label' => __( '🍽 All Inclusive', 'zhiraf-ufa' ),
			'title' => __( 'All Inclusive в Камрани', 'zhiraf-ufa' ),
			'desc'  => __( 'Клубные резорты с питанием «всё включено» на Бай Дай — без поиска кафе.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-price',
			'group' => 'need',
			'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ),
			'title' => __( 'Хиты «цена / качество»', 'zhiraf-ufa' ),
			'desc'  => __( 'Честные 3–4★ у моря без переплаты за бренд.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_vietnam_catalog_default_copy() {
	$filters = zhiraf_ufa_vietnam_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в Вьетнаме', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Под задачу: All Inclusive в Камрани, остров Фукуок, городской Нячанг или дюны Муйне. Цены — за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_vietnam_resort_hotel_counts() {
	$counts = array(
		'cam_ranh'       => 0,
		'phu_quoc'       => 0,
		'nha_trang'      => 0,
		'muine'          => 0,
		'danang_hoian'   => 0,
	);
	foreach ( zhiraf_ufa_vietnam_catalog_hotels() as $hotel ) {
		$tags = ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ? $hotel['filter_tags'] : array();
		foreach ( $tags as $tag ) {
			if ( 0 !== strpos( (string) $tag, 'resort-' ) ) {
				continue;
			}
			$key = substr( (string) $tag, 7 );
			if ( isset( $counts[ $key ] ) ) {
				++$counts[ $key ];
			}
		}
	}

	return $counts;
}

/**
 * @return array<int,array{id:string,label:string}>
 */
function zhiraf_ufa_vietnam_resort_chips() {
	return array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-cam_ranh', 'label' => __( 'Камрань', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-phu_quoc', 'label' => __( 'Фукуок', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-nha_trang', 'label' => __( 'Нячанг', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-muine', 'label' => __( 'Муйне', 'zhiraf-ufa' ) ),
	);
}

/**
 * Four resort pick cards in one row on desktop.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_vietnam_resort_pick_cards() {
	$counts     = zhiraf_ufa_vietnam_resort_hotel_counts();
	$guide_here = __( 'К отелям →', 'zhiraf-ufa' );

	return array(
		array(
			'id'          => 'cam_ranh',
			'filter_id'   => 'resort-cam_ranh',
			'guide_url'   => zhiraf_ufa_vietnam_resort_landing_url( 'cam_ranh' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Камрань', 'zhiraf-ufa' ),
			'subtitle'    => __( 'All Inclusive у моря', 'zhiraf-ufa' ),
			'tag'         => __( 'Семейный хит', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( 'Аэропорт + 15–40 мин', 'zhiraf-ufa' ),
			'featured'    => true,
			'hotel_count' => $counts['cam_ranh'],
			'accent_mod'  => 'emerald',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Бухта Бай Дай — широкий песок и клубные резорты AI', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Осень: волны — смотрим сезон Нячанга', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'phu_quoc',
			'filter_id'   => 'resort-phu_quoc',
			'guide_url'   => zhiraf_ufa_vietnam_resort_landing_url( 'phu_quoc' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Фукуок', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Остров и закаты', 'zhiraf-ufa' ),
			'tag'         => __( 'Релакс', 'zhiraf-ufa' ),
			'tag_mod'     => 'nature',
			'transfer'    => __( 'Стыковка в Москве', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['phu_quoc'],
			'accent_mod'  => 'teal',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Ноябрь–апрель — идеальный штиль и лазурь', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Летом дожди — планируйте запас по погоде', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'nha_trang',
			'filter_id'   => 'resort-nha_trang',
			'guide_url'   => zhiraf_ufa_vietnam_resort_landing_url( 'nha_trang' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Нячанг', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Город у моря', 'zhiraf-ufa' ),
			'tag'         => __( 'Город и пляж', 'zhiraf-ufa' ),
			'tag_mod'     => 'urban',
			'transfer'    => __( '~40 мин от Камрани', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['nha_trang'],
			'accent_mod'  => 'amber',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Февраль–август: спокойное море на Чан Фу', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Осень — муссоны и волны', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'muine',
			'filter_id'   => 'resort-muine',
			'guide_url'   => zhiraf_ufa_vietnam_resort_landing_url( 'muine' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Муйне', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Дюны и виндсёрф', 'zhiraf-ufa' ),
			'tag'         => __( 'Альтернатива', 'zhiraf-ufa' ),
			'tag_mod'     => 'adults',
			'transfer'    => __( '4–5 ч от Нячанга', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['muine'],
			'accent_mod'  => 'lux',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Широкие пляжи Фантьета и красные дюны', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Хорош, если даты не совпали с сезоном Нячанга', 'zhiraf-ufa' ) ),
			),
		),
	);
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_vietnam_expert_nuances() {
	return array(
		array(
			'icon'  => '🛂',
			'title' => __( 'Без визы до 45 дней', 'zhiraf-ufa' ),
			'text'  => __( 'Гражданам РФ не нужна виза для туристической поездки во Вьетнам до 45 дней — въездной штамп бесплатно. Загранпаспорт должен действовать не менее 6 месяцев.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🌧',
			'title' => __( 'Сезон: Нячанг и Фукуок', 'zhiraf-ufa' ),
			'text'  => __( 'Нячанг комфортен с февраля по август; осенью — волны и дожди. Фукуок лучше с ноября по апрель. Если месяцы не совпадают с курортом — смотрим Муйне или переносим даты.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🍽',
			'title' => __( 'All Inclusive в Камрани', 'zhiraf-ufa' ),
			'text'  => __( 'В городском Нячанге чаще BB и кафе с фо-бо. На Бай Дай в Камрани — крупные резорты с All Inclusive и аквапарками: фиксируем формат питания в договоре до оплаты.', 'zhiraf-ufa' ),
		),
	);
}