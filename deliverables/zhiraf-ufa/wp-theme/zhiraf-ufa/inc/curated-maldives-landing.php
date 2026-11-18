<?php
/**
 * Maldives (/tour/maldivy/) curated landing — 6 starters + pool from maldives_hotels_25.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_maldives_hub_min_price_rub() {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'maldives' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_maldives_base_price_rub', 265000 );
}

/**
 * @return string
 */
function zhiraf_ufa_maldives_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'maldives' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_maldives_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_maldives_hotels_json_path();
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}

/**
 * Atoll filter keys for a JSON row.
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_maldives_resort_keys_from_row( array $row ) {
	$atoll = ! empty( $row['atoll'] ) ? sanitize_key( (string) $row['atoll'] ) : '';
	$keys  = array();
	$allowed = array( 'north_male', 'south_male', 'north_ari', 'south_ari', 'raa', 'lhaviyani', 'baa' );
	if ( in_array( $atoll, $allowed, true ) ) {
		$keys[] = $atoll;
	}

	return array_values( array_unique( $keys ) );
}

/**
 * @param string $atoll_key Atoll slug.
 * @return string
 */
function zhiraf_ufa_maldives_resort_landing_url( $atoll_key ) {
	unset( $atoll_key );

	return home_url( '/tour/maldivy/#dest-curated-hotels' );
}

/**
 * @return array<string,string>
 */
function zhiraf_ufa_maldives_atoll_labels() {
	return array(
		'north_male' => __( 'Северный Мале', 'zhiraf-ufa' ),
		'south_male' => __( 'Южный Мале', 'zhiraf-ufa' ),
		'north_ari'  => __( 'Северный Ари', 'zhiraf-ufa' ),
		'south_ari'  => __( 'Южный Ари', 'zhiraf-ufa' ),
		'raa'        => __( 'Раа', 'zhiraf-ufa' ),
		'lhaviyani'  => __( 'Лавиани', 'zhiraf-ufa' ),
		'baa'        => __( 'Баа', 'zhiraf-ufa' ),
	);
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_maldives_catalog_fill_resort_labels() {
	return array(
		'resort-north_male' => __( 'Северном Мале', 'zhiraf-ufa' ),
		'resort-south_ari'  => __( 'Южном Ари', 'zhiraf-ufa' ),
		'resort-raa'        => __( 'атолле Раа', 'zhiraf-ufa' ),
		'resort-lhaviyani'  => __( 'Лавиани', 'zhiraf-ufa' ),
		'resort-north_ari'  => __( 'Северном Ари', 'zhiraf-ufa' ),
		'resort-south_male' => __( 'Южном Мале', 'zhiraf-ufa' ),
		'resort-baa'        => __( 'атолле Баа', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_maldives_need_tag_from_category( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	switch ( $category_slug ) {
		case 'family':
			return 'need-family';
		case 'couples':
			return 'need-adults';
		case 'snorkeling':
			return 'need-snorkel';
		case 'speedboat':
			return 'need-speedboat';
		case 'balance':
		default:
			return 'need-price';
	}
}

/**
 * @param string $category_slug Category slug.
 * @return array{badge:string,badge_mod:string}
 */
function zhiraf_ufa_maldives_pool_need_badge( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	if ( 'family' === $category_slug ) {
		return array(
			'badge'     => __( 'Семья на острове', 'zhiraf-ufa' ),
			'badge_mod' => 'family',
		);
	}
	if ( 'couples' === $category_slug ) {
		return array(
			'badge'     => __( 'Для пар', 'zhiraf-ufa' ),
			'badge_mod' => 'adults',
		);
	}
	if ( 'snorkeling' === $category_slug ) {
		return array(
			'badge'     => __( 'Домашний риф', 'zhiraf-ufa' ),
			'badge_mod' => 'nature',
		);
	}
	if ( 'speedboat' === $category_slug ) {
		return array(
			'badge'     => __( 'Катер с Мале', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
		);
	}

	return array(
		'badge'     => __( 'All Inclusive', 'zhiraf-ufa' ),
		'badge_mod' => 'value',
	);
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return int
 */
function zhiraf_ufa_maldives_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 288000;
		case 'couples':
			return 318000;
		case 'snorkeling':
			return 298000;
		case 'speedboat':
			return 278000;
		case 'balance':
		default:
			return 268000;
	}
}

/**
 * @param string $name Hotel title.
 * @param string $cat  Category slug.
 * @return string
 */
function zhiraf_ufa_maldives_pool_meal_concept( $name, $cat ) {
	$name  = (string) $name;
	$cat   = sanitize_key( (string) $cat );
	$stars = '';
	if ( preg_match( '/\b(\d)\s*★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}
	if ( 'balance' === $cat || 'family' === $cat ) {
		return trim( $stars . ' ' . __( 'All Inclusive / Premium AI', 'zhiraf-ufa' ) );
	}
	if ( 'speedboat' === $cat ) {
		return trim( $stars . ' ' . __( 'AI + быстрый катер', 'zhiraf-ufa' ) );
	}
	if ( 'snorkeling' === $cat ) {
		return trim( $stars . ' ' . __( 'AI и сноркл с берега', 'zhiraf-ufa' ) );
	}

	return trim( $stars . ' ' . __( 'HB / AI для пар', 'zhiraf-ufa' ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_maldives_pool_card_usp( $row ) {
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
function zhiraf_ufa_maldives_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_maldives_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );

		return trim( $parts[0] );
	}
	$labels = zhiraf_ufa_maldives_atoll_labels();
	$keys   = zhiraf_ufa_maldives_resort_keys_from_row( $row );
	if ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {
		return $labels[ $keys[0] ];
	}

	return __( 'Мальдивы', 'zhiraf-ufa' );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_maldives_image_from_json_row( array $row ) {
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
		foreach ( array( 'hotels/maldives/' . $first, $first ) as $rel ) {
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
		1  => 'resort-maldivy-atoll-1.jpg',
		2  => 'resort-maldivy-atoll-2.jpg',
		6  => 'country-maldivy.jpg',
		12 => 'resort-maldivy-atoll-2.jpg',
		18 => 'resort-maldivy-atoll-1.jpg',
		21 => 'country-maldivy.jpg',
	);
	$atoll_fb = array(
		'north_male' => 'resort-maldivy-atoll-2.jpg',
		'south_ari'  => 'resort-maldivy-atoll-1.jpg',
		'raa'        => 'country-maldivy.jpg',
		'lhaviyani'  => 'resort-maldivy-atoll-2.jpg',
	);
	$fallback = ! empty( $id_fallbacks[ $pool_id ] ) ? $id_fallbacks[ $pool_id ] : 'resort-maldivy-atoll-1.jpg';
	$atoll    = ! empty( $row['atoll'] ) ? sanitize_key( (string) $row['atoll'] ) : '';
	if ( isset( $atoll_fb[ $atoll ] ) && 'resort-maldivy-atoll-1.jpg' === $fallback ) {
		$fallback = $atoll_fb[ $atoll ];
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
function zhiraf_ufa_maldives_starter_definitions() {
	return array(
		array( 'json_id' => 1, 'slot' => 'base' ),
		array( 'json_id' => 2, 'slot' => 'family-north_male' ),
		array( 'json_id' => 6, 'slot' => 'snorkeling' ),
		array( 'json_id' => 12, 'slot' => 'couples' ),
		array( 'json_id' => 18, 'slot' => 'speedboat' ),
		array( 'json_id' => 21, 'slot' => 'balance' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_maldives_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'family', 'family-north_male', 'base' ), true ) ) {
		$tags[] = 'need-family';
	} elseif ( 'snorkeling' === $slot ) {
		$tags[] = 'need-snorkel';
	} elseif ( 'couples' === $slot ) {
		$tags[] = 'need-adults';
	} elseif ( 'speedboat' === $slot ) {
		$tags[] = 'need-speedboat';
	} else {
		$tags[] = 'need-price';
	}

	return array_values( array_unique( $tags ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_maldives_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_maldives_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_maldives_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_maldives_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_maldives_image_from_json_row( $row );

	return array(
		'destination_slug' => 'maldivy',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'north_male',
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
			__( 'Отель %s, Мальдивы', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_maldives_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_maldives_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_maldives_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_maldives_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_maldives_pool_price_rub( $row ),
	);
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_maldives_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_maldives_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_maldives_card( $card );
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
		$tour = zhiraf_ufa_get_country_min_tour( 'maldives' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'maldives', $name, $bg_hotel_id );
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
		$card['price_rub']             = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_maldives_hub_min_price_rub() : 0 );
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
function zhiraf_ufa_maldives_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'MV-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'MV-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'MV-STARTER-' . $slot;
	}

	return 'MV-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_maldives_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_maldives_curated_uon_hotel_id( $card );
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
function zhiraf_ufa_maldives_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_maldives_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_maldives_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_maldives_json_row_to_card( $row );
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
		$card['filter_tags'] = zhiraf_ufa_maldives_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по Мальдивам', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_maldives_finalize_catalog_card( zhiraf_ufa_maldives_apply_bg_pricing( $card ) );
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
		$card = zhiraf_ufa_maldives_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_maldives_finalize_catalog_card( zhiraf_ufa_maldives_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_maldives_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_maldives_catalog_filters() {
	return array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Туры на Мальдивы: 6 проверенных резортов', 'zhiraf-ufa' ),
			'desc'  => __( 'Семьи, риф, пары, катер с Мале и честный All Inclusive. Цены — ориентир за двоих с перелётом (стыковка в Москве).', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-north_male',
			'group' => 'resort',
			'label' => __( '⚓ Северный Мале', 'zhiraf-ufa' ),
			'title' => __( 'Отели Северного Мале', 'zhiraf-ufa' ),
			'desc'  => __( 'Катер 15–45 мин — быстрый заезд после перелёта.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-south_ari',
			'group' => 'resort',
			'label' => __( '🏝 Южный Ари', 'zhiraf-ufa' ),
			'title' => __( 'Отели Южного Ари', 'zhiraf-ufa' ),
			'desc'  => __( 'Классика Мальдив: гидросамолёт и белые виллы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-raa',
			'group' => 'resort',
			'label' => __( '🌿 Раа', 'zhiraf-ufa' ),
			'title' => __( 'Отели атолла Раа', 'zhiraf-ufa' ),
			'desc'  => __( 'Севернее Мале — тише, зеленее, семейные острова.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-lhaviyani',
			'group' => 'resort',
			'label' => __( '💠 Лавиани', 'zhiraf-ufa' ),
			'title' => __( 'Отели Лавиани', 'zhiraf-ufa' ),
			'desc'  => __( 'Бирюзовые лагуны и дизайнерские резорты.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-family',
			'group' => 'need',
			'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ),
			'title' => __( 'Семейные острова', 'zhiraf-ufa' ),
			'desc'  => __( 'Детские клубы, пологие лагуны и врач на острове.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-adults',
			'group' => 'need',
			'label' => __( '🥂 Для пар', 'zhiraf-ufa' ),
			'title' => __( 'Adults Only и романтика', 'zhiraf-ufa' ),
			'desc'  => __( 'Водные виллы, приватность и 18+.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-snorkel',
			'group' => 'need',
			'label' => __( '🐠 Снорклинг', 'zhiraf-ufa' ),
			'title' => __( 'Домашний риф', 'zhiraf-ufa' ),
			'desc'  => __( 'Черепахи и риф прямо у виллы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-speedboat',
			'group' => 'need',
			'label' => __( '🛥 Катер', 'zhiraf-ufa' ),
			'title' => __( 'Быстрый трансфер', 'zhiraf-ufa' ),
			'desc'  => __( 'Заселение в день прилёта без ожидания сиампана.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-price',
			'group' => 'need',
			'label' => __( '⚖️ AI / цена', 'zhiraf-ufa' ),
			'title' => __( 'Честный All Inclusive', 'zhiraf-ufa' ),
			'desc'  => __( 'Богатый AI без переплаты за гидросамолёт.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_maldives_catalog_default_copy() {
	$filters = zhiraf_ufa_maldives_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных резортов на Мальдивах', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Под задачу: семейный Южный Ари, быстрый катер с Мале, риф у виллы или adults only. Цены — за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_maldives_resort_hotel_counts() {
	$counts = array(
		'north_male' => 0,
		'south_ari'  => 0,
		'raa'        => 0,
		'lhaviyani'  => 0,
		'north_ari'  => 0,
		'south_male' => 0,
		'baa'        => 0,
	);
	foreach ( zhiraf_ufa_maldives_catalog_hotels() as $hotel ) {
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
function zhiraf_ufa_maldives_resort_chips() {
	return array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-north_male', 'label' => __( 'Сев. Мале', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-south_ari', 'label' => __( 'Юж. Ари', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-raa', 'label' => __( 'Раа', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-lhaviyani', 'label' => __( 'Лавиани', 'zhiraf-ufa' ) ),
	);
}

/**
 * Four atoll pick cards in one row on desktop.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_maldives_resort_pick_cards() {
	$counts     = zhiraf_ufa_maldives_resort_hotel_counts();
	$guide_here = __( 'К отелям →', 'zhiraf-ufa' );

	return array(
		array(
			'id'          => 'north_male',
			'filter_id'   => 'resort-north_male',
			'guide_url'   => zhiraf_ufa_maldives_resort_landing_url( 'north_male' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Северный Мале', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Катер с аэропорта', 'zhiraf-ufa' ),
			'tag'         => __( 'Быстрый заезд', 'zhiraf-ufa' ),
			'tag_mod'     => 'urban',
			'transfer'    => __( '15–45 мин на катере', 'zhiraf-ufa' ),
			'featured'    => true,
			'hotel_count' => $counts['north_male'],
			'accent_mod'  => 'teal',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Заселение в день прилёта без гидросамолёта', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Близость к Мале — без полной изоляции', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'south_ari',
			'filter_id'   => 'resort-south_ari',
			'guide_url'   => zhiraf_ufa_maldives_resort_landing_url( 'south_ari' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Южный Ари', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Классика вилл', 'zhiraf-ufa' ),
			'tag'         => __( 'Семейный хит', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( 'Гидросамолёт ~25 мин', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['south_ari'],
			'accent_mod'  => 'emerald',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Песчаные лагуны и большие семейные острова', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Трансфер на сиампане — закладываем в пакет', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'raa',
			'filter_id'   => 'resort-raa',
			'guide_url'   => zhiraf_ufa_maldives_resort_landing_url( 'raa' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Раа', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Северная тишина', 'zhiraf-ufa' ),
			'tag'         => __( 'Premium AI', 'zhiraf-ufa' ),
			'tag_mod'     => 'nature',
			'transfer'    => __( 'Сиампан ~45 мин', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['raa'],
			'accent_mod'  => 'amber',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Зелёные острова и просторные семейные виллы', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Меньше соседних отелей — спокойнее риф', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'lhaviyani',
			'filter_id'   => 'resort-lhaviyani',
			'guide_url'   => zhiraf_ufa_maldives_resort_landing_url( 'lhaviyani' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Лавиани', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Дизайн и лагуны', 'zhiraf-ufa' ),
			'tag'         => __( 'Для пар', 'zhiraf-ufa' ),
			'tag_mod'     => 'adults',
			'transfer'    => __( 'Сиампан 30–40 мин', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['lhaviyani'],
			'accent_mod'  => 'lux',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Бирюзовые лагуны и adults only', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Итальянский дизайн и качественный AI', 'zhiraf-ufa' ) ),
			),
		),
	);
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_maldives_expert_nuances() {
	return array(
		array(
			'icon'  => '✈',
			'title' => __( 'Перелёт из Уфы', 'zhiraf-ufa' ),
			'text'  => __( 'Пакеты на Мальдивы идут со стыковкой в Москве. Считаем суммарное время, багаж и запас на трансфер на остров до брони.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🛥',
			'title' => __( 'Катер или сиампан', 'zhiraf-ufa' ),
			'text'  => __( 'Трансфер — отдельная статья бюджета. Для семей с малышами часто выгоднее Северный Мале на катере; для «открыточных» вилл — Южный Ари на гидросамолёте.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🍽',
			'title' => __( 'All Inclusive', 'zhiraf-ufa' ),
			'text'  => __( 'На резорт-острове кафе снаружи нет — сравниваем базовый AI, Premium и Dine Around. Условия фиксируем в договоре, включая налоги TGST.', 'zhiraf-ufa' ),
		),
	);
}