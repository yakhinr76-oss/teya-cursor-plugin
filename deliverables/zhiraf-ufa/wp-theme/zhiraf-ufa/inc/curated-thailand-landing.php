<?php
/**
 * Thailand (/tour/tailand/) curated landing — 6 starters + pool from thailand_hotels_25.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_thailand_hub_min_price_rub() {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'thailand' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_thailand_base_price_rub', 185000 );
}

/**
 * @return string
 */
function zhiraf_ufa_thailand_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'thailand' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_thailand_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_thailand_hotels_json_path();
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}

/**
 * Resort filter keys for a JSON row (phuket, pattaya, khaolak, samui).
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_thailand_resort_keys_from_row( array $row ) {
	$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$keys   = array();
	foreach ( array( 'phuket', 'pattaya', 'khaolak', 'samui' ) as $allowed ) {
		if ( $region === $allowed ) {
			$keys[] = $allowed;
		}
	}

	return array_values( array_unique( $keys ) );
}

/**
 * Hub anchor — отдельных курортных URL для Таиланда нет (редирект с /phuket/).
 *
 * @param string $resort_key phuket|pattaya|khaolak|samui.
 * @return string
 */
function zhiraf_ufa_thailand_resort_landing_url( $resort_key ) {
	unset( $resort_key );

	return home_url( '/tour/tailand/#dest-curated-hotels' );
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_thailand_catalog_fill_resort_labels() {
	return array(
		'resort-phuket'  => __( 'Пхукете', 'zhiraf-ufa' ),
		'resort-pattaya' => __( 'Паттайе', 'zhiraf-ufa' ),
		'resort-khaolak' => __( 'Као-Лаке', 'zhiraf-ufa' ),
		'resort-samui'   => __( 'Самуи', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_thailand_need_tag_from_category( $category_slug ) {
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
		case 'pattaya_beach':
			return 'need-beach';
		case 'balance':
		default:
			return 'need-price';
	}
}

/**
 * @param string $category_slug Category slug.
 * @return array{badge:string,badge_mod:string}
 */
function zhiraf_ufa_thailand_pool_need_badge( $category_slug ) {
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
			'badge'     => __( 'Патонг / кафе', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
		);
	}
	if ( 'pattaya_beach' === $category_slug ) {
		return array(
			'badge'     => __( 'Паттайя у моря', 'zhiraf-ufa' ),
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
function zhiraf_ufa_thailand_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 198000;
		case 'couples':
			return 218000;
		case 'premium':
			return 268000;
		case 'urban':
			return 178000;
		case 'pattaya_beach':
			return 192000;
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
function zhiraf_ufa_thailand_pool_meal_concept( $name, $cat ) {
	$name  = (string) $name;
	$cat   = sanitize_key( (string) $cat );
	$stars = '';
	if ( preg_match( '/\b(\d)\s*★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}
	if ( 'premium' === $cat || 'family' === $cat ) {
		return trim( $stars . ' ' . __( 'Завтраки BB / HB', 'zhiraf-ufa' ) );
	}
	if ( 'urban' === $cat || 'pattaya_beach' === $cat ) {
		return trim( $stars . ' ' . __( 'Завтраки + кафе рядом', 'zhiraf-ufa' ) );
	}

	return trim( $stars . ' ' . __( 'Завтраки (BB)', 'zhiraf-ufa' ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_thailand_pool_card_usp( $row ) {
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
function zhiraf_ufa_thailand_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_thailand_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );

		return trim( $parts[0] );
	}
	$keys = zhiraf_ufa_thailand_resort_keys_from_row( $row );
	$labels = array(
		'phuket'  => __( 'Пхукет', 'zhiraf-ufa' ),
		'pattaya' => __( 'Паттайя', 'zhiraf-ufa' ),
		'khaolak' => __( 'Као-Лак', 'zhiraf-ufa' ),
		'samui'   => __( 'Самуи', 'zhiraf-ufa' ),
	);
	if ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {
		return $labels[ $keys[0] ];
	}

	return __( 'Таиланд', 'zhiraf-ufa' );
}

/**
 * Unique theme fallbacks so six starters do not share one stock photo.
 *
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_thailand_image_from_json_row( array $row ) {
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
		foreach ( array( 'hotels/thailand/' . $first, $first ) as $rel ) {
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
		1  => 'dest-thailand.jpg',
		3  => 'dest-thailand-new.jpg',
		5  => 'country-tailand.jpg',
		6  => 'hero-thailand.jpg',
		16 => 'dest-thailand.jpg',
		23 => 'dest-thailand-new.jpg',
	);
	$region_fb    = array(
		'phuket'  => 'dest-thailand.jpg',
		'pattaya' => 'dest-thailand-new.jpg',
		'khaolak' => 'country-tailand.jpg',
		'samui'   => 'hero-thailand.jpg',
	);
	$fallback     = ! empty( $id_fallbacks[ $pool_id ] ) ? $id_fallbacks[ $pool_id ] : 'hero-thailand.jpg';
	$region       = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	if ( isset( $region_fb[ $region ] ) && 'hero-thailand.jpg' === $fallback ) {
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
function zhiraf_ufa_thailand_starter_definitions() {
	return array(
		array( 'json_id' => 1, 'slot' => 'base' ),
		array( 'json_id' => 3, 'slot' => 'family-pattaya' ),
		array( 'json_id' => 5, 'slot' => 'family-khaolak' ),
		array( 'json_id' => 6, 'slot' => 'couples' ),
		array( 'json_id' => 16, 'slot' => 'urban' ),
		array( 'json_id' => 23, 'slot' => 'balance' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_thailand_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'family', 'family-pattaya', 'family-khaolak' ), true ) ) {
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
function zhiraf_ufa_thailand_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_thailand_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_thailand_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_thailand_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_thailand_image_from_json_row( $row );

	return array(
		'destination_slug' => 'tailand',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'phuket',
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
			__( 'Отель %s, Таиланд', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_thailand_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_thailand_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_thailand_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_thailand_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_thailand_pool_price_rub( $row ),
	);
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_thailand_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_thailand_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_thailand_card( $card );
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
		$tour = zhiraf_ufa_get_country_min_tour( 'thailand' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'thailand', $name, $bg_hotel_id );
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
		$card['price_rub']             = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_thailand_hub_min_price_rub() : 0 );
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
function zhiraf_ufa_thailand_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'TH-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'TH-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'TH-STARTER-' . $slot;
	}

	return 'TH-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_thailand_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_thailand_curated_uon_hotel_id( $card );
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
function zhiraf_ufa_thailand_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_thailand_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_thailand_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_thailand_json_row_to_card( $row );
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
		$card['filter_tags'] = zhiraf_ufa_thailand_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по Таиланду', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_thailand_finalize_catalog_card( zhiraf_ufa_thailand_apply_bg_pricing( $card ) );
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
		$card = zhiraf_ufa_thailand_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_thailand_finalize_catalog_card( zhiraf_ufa_thailand_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_thailand_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_thailand_catalog_filters() {
	return array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Туры в Таиланд: 6 проверенных отелей', 'zhiraf-ufa' ),
			'desc'  => __( 'Семьи, пары, городской Патонг и честный BB — Пхукет, Паттайя, Као-Лак. Цены ориентир за двоих с перелётом (стыковка в Москве).', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-phuket',
			'group' => 'resort',
			'label' => __( '🏝 Пхукет', 'zhiraf-ufa' ),
			'title' => __( 'Отели Пхукета', 'zhiraf-ufa' ),
			'desc'  => __( 'Карон, Ката, Бангтао и Патонг — до 6 лучших из базы 25.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-pattaya',
			'group' => 'resort',
			'label' => __( '🌴 Паттайя', 'zhiraf-ufa' ),
			'title' => __( 'Отели Паттайи', 'zhiraf-ufa' ),
			'desc'  => __( 'Сиамский залив, аквапарки и первая линия — отбор из базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-khaolak',
			'group' => 'resort',
			'label' => __( '🌿 Као-Лак', 'zhiraf-ufa' ),
			'title' => __( 'Отели Као-Лак', 'zhiraf-ufa' ),
			'desc'  => __( 'Широкий песок и спокойный ритм — семейные резорты без суеты.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-samui',
			'group' => 'resort',
			'label' => __( '🥥 Самуи', 'zhiraf-ufa' ),
			'title' => __( 'Отели Самуи', 'zhiraf-ufa' ),
			'desc'  => __( 'Островной релакс и бухты — варианты из нашей базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-family',
			'group' => 'need',
			'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ),
			'title' => __( 'Семейные отели', 'zhiraf-ufa' ),
			'desc'  => __( 'Аквапарки, детские клубы и пологий песок.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-adults',
			'group' => 'need',
			'label' => __( '🥂 Для пар', 'zhiraf-ufa' ),
			'title' => __( 'Отели для пар', 'zhiraf-ufa' ),
			'desc'  => __( '16+, инфинити-бассейны и тихие бухты.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-urban',
			'group' => 'need',
			'label' => __( '🌆 Патонг', 'zhiraf-ufa' ),
			'title' => __( 'Город и кафе', 'zhiraf-ufa' ),
			'desc'  => __( 'Пешком до пляжа, шопинга и ночных рынков.', 'zhiraf-ufa' ),
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
function zhiraf_ufa_thailand_catalog_default_copy() {
	$filters = zhiraf_ufa_thailand_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в Таиланде', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Под задачу: семейный Пхукет, аквапарк Паттайи, тихий Као-Лак или городской Патонг. Цены — за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_thailand_resort_hotel_counts() {
	$counts = array(
		'phuket'  => 0,
		'pattaya' => 0,
		'khaolak' => 0,
		'samui'   => 0,
	);
	foreach ( zhiraf_ufa_thailand_catalog_hotels() as $hotel ) {
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
function zhiraf_ufa_thailand_resort_chips() {
	return array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-phuket', 'label' => __( 'Пхукет', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-pattaya', 'label' => __( 'Паттайя', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-khaolak', 'label' => __( 'Као-Лак', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-samui', 'label' => __( 'Самуи', 'zhiraf-ufa' ) ),
	);
}

/**
 * Four resort pick cards in one row on desktop.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_thailand_resort_pick_cards() {
	$counts     = zhiraf_ufa_thailand_resort_hotel_counts();
	$guide_here = __( 'К отелям →', 'zhiraf-ufa' );

	return array(
		array(
			'id'          => 'phuket',
			'filter_id'   => 'resort-phuket',
			'guide_url'   => zhiraf_ufa_thailand_resort_landing_url( 'phuket' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Пхукет', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Остров и бухты', 'zhiraf-ufa' ),
			'tag'         => __( 'Семейный хит', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( 'Стыковка в Москве', 'zhiraf-ufa' ),
			'featured'    => true,
			'hotel_count' => $counts['phuket'],
			'accent_mod'  => 'emerald',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Карон, Ката и Бангтао — песок и снорклинг', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Май–окт: волны на западном побережье', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'pattaya',
			'filter_id'   => 'resort-pattaya',
			'guide_url'   => zhiraf_ufa_thailand_resort_landing_url( 'pattaya' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Паттайя', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Залив и аквапарки', 'zhiraf-ufa' ),
			'tag'         => __( 'С детьми', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( '2 ч от Бангкока', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['pattaya'],
			'accent_mod'  => 'teal',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Спокойное море летом в Сиамском заливе', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Экскурсии и остров Ко Лан', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'khaolak',
			'filter_id'   => 'resort-khaolak',
			'guide_url'   => zhiraf_ufa_thailand_resort_landing_url( 'khaolak' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Као-Лак', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Природа и тишина', 'zhiraf-ufa' ),
			'tag'         => __( 'Релакс', 'zhiraf-ufa' ),
			'tag_mod'     => 'nature',
			'transfer'    => __( '~1,5 ч от HKT', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['khaolak'],
			'accent_mod'  => 'amber',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Широкий песок и пологий заход', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Без ночной суеты курортов', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'samui',
			'filter_id'   => 'resort-samui',
			'guide_url'   => zhiraf_ufa_thailand_resort_landing_url( 'samui' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Самуи', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Островной уют', 'zhiraf-ufa' ),
			'tag'         => __( 'Для пар', 'zhiraf-ufa' ),
			'tag_mod'     => 'adults',
			'transfer'    => __( 'Чартер + паром', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['samui'],
			'accent_mod'  => 'lux',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Лагуны и виллы у воды', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Меньше отелей в базе — уточняем даты', 'zhiraf-ufa' ) ),
			),
		),
	);
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_thailand_expert_nuances() {
	return array(
		array(
			'icon'  => '✈',
			'title' => __( 'Перелёт из Уфы', 'zhiraf-ufa' ),
			'text'  => __( 'Сейчас пакеты идут со стыковкой в Москве. Считаем общее время в пути, стыковку и норму багажа до брони — без сюрпризов на стойке регистрации.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🌧',
			'title' => __( 'Сезон и волны', 'zhiraf-ufa' ),
			'text'  => __( 'С ноября по апрель — сухой сезон на Андамане. Летом на западном Пхукете бывают муссонные волны: для детей подбираем Паттайю, бухты Ката-Ной или Као-Лак.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🍜',
			'title' => __( 'Питание BB', 'zhiraf-ufa' ),
			'text'  => __( 'В Таиланде чаще выгодно брать завтраки и ужинать в кафе. Если нужен AI — подбираем клубные резорты заранее и фиксируем в договоре.', 'zhiraf-ufa' ),
		),
	);
}