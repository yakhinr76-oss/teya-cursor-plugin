<?php
/**
 * Russia (/tour/rossiya/) curated landing — 6 starters + pool from russia_hotels_50.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_rossiya_hub_min_price_rub() {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'rossiya' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_rossiya_base_price_rub', 55000 );
}

/**
 * @return string
 */
function zhiraf_ufa_rossiya_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'rossiya' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_rossiya_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_rossiya_hotels_json_path();
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}

/**
 * Macro-region filter keys for a JSON row (10 regions → 6 catalog resort tabs).
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_rossiya_resort_keys_from_row( array $row ) {
	$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$map    = array(
		'sochi_coast'        => 'sochi',
		'krasnaya_polyana'   => 'polyana',
		'anapa'              => 'anapa',
		'kmv'                => 'kmv',
		'spb'                => 'city',
		'moscow'             => 'city',
		'kaliningrad_baltic' => 'city',
		'baikal'             => 'nature',
		'altai'              => 'nature',
		'karelia'            => 'nature',
	);
	if ( ! empty( $map[ $region ] ) ) {
		return array( $map[ $region ] );
	}

	return array();
}

/**
 * Hub anchor — отдельных курортных URL для России нет (якорь каталога).
 *
 * @param string $resort_key phuket|pattaya|khaolak|samui.
 * @return string
 */
function zhiraf_ufa_rossiya_resort_landing_url( $resort_key ) {
	unset( $resort_key );

	return home_url( '/tour/rossiya/#dest-curated-hotels' );
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_rossiya_catalog_fill_resort_labels() {
	return array(
		'resort-sochi'   => __( 'Сочи', 'zhiraf-ufa' ),
		'resort-polyana' => __( 'Красная Поляна', 'zhiraf-ufa' ),
		'resort-anapa'   => __( 'Анапа', 'zhiraf-ufa' ),
		'resort-kmv'     => __( 'Кавминводы', 'zhiraf-ufa' ),
		'resort-city'    => __( 'Город', 'zhiraf-ufa' ),
		'resort-nature'  => __( 'Природа', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_rossiya_need_tag_from_category( $category_slug ) {
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
function zhiraf_ufa_rossiya_pool_need_badge( $category_slug ) {
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
			'badge'     => __( 'Город', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
		);
	}
	if ( 'pattaya_beach' === $category_slug ) {
		return array(
			'badge'     => __( 'У моря', 'zhiraf-ufa' ),
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
function zhiraf_ufa_rossiya_pool_price_rub( $row ) {
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
function zhiraf_ufa_rossiya_pool_meal_concept( $name, $cat ) {
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
function zhiraf_ufa_rossiya_pool_card_usp( $row ) {
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
function zhiraf_ufa_rossiya_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_rossiya_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );
		return trim( $parts[0] );
	}
	$keys = zhiraf_ufa_rossiya_resort_keys_from_row( $row );
	$labels = array(
		'sochi'   => __( 'Сочи', 'zhiraf-ufa' ),
		'polyana' => __( 'Красная Поляна', 'zhiraf-ufa' ),
		'anapa'   => __( 'Анапа', 'zhiraf-ufa' ),
		'kmv'     => __( 'Кавминводы', 'zhiraf-ufa' ),
		'city'    => __( 'Город', 'zhiraf-ufa' ),
		'nature'  => __( 'Природа', 'zhiraf-ufa' ),
	);
	if ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {
		return $labels[ $keys[0] ];
	}
	return __( 'Россия', 'zhiraf-ufa' );
}

/**
 * Unique theme fallbacks so six starters do not share one stock photo.
 *
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_rossiya_image_from_json_row( array $row ) {
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
		foreach ( array(
			'hotels/russia/' . $first,
			'hotels/rossiya/' . $first,
			$first,
		) as $rel ) {
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
	$region    = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$fallbacks = array(
		'sochi_coast'        => 'hero-collage-1.jpg',
		'krasnaya_polyana'   => 'hero-collage-2.jpg',
		'anapa'              => 'hero-collage-3.jpg',
		'kmv'                => 'hero-collage-family.jpg',
		'spb'                => 'hero-collage-active.jpg',
		'moscow'             => 'hero-collage-uae.jpg',
		'kaliningrad_baltic' => 'hero-collage-2.jpg',
		'baikal'             => 'hero-collage-3.jpg',
		'altai'              => 'hero-collage-family.jpg',
		'karelia'            => 'hero-collage-active.jpg',
	);
	$fallback  = $fallbacks[ $region ] ?? 'hero-collage-1.jpg';
	if ( defined( 'ZHIRAF_UFA_DIR' ) && ! is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $fallback ) ) {
		$fallback = 'hero-collage-1.jpg';
	}
	if ( $pool_id > 0 ) {
		$rotate = array( 'hero-collage-1.jpg', 'hero-collage-2.jpg', 'hero-collage-3.jpg' );
		$fallback = $rotate[ ( $pool_id - 1 ) % count( $rotate ) ];
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
function zhiraf_ufa_rossiya_starter_definitions() {
	return array(
		array( 'json_id' => 1, 'slot' => 'sochi' ),
		array( 'json_id' => 7, 'slot' => 'polyana' ),
		array( 'json_id' => 11, 'slot' => 'anapa' ),
		array( 'json_id' => 16, 'slot' => 'kmv' ),
		array( 'json_id' => 26, 'slot' => 'city' ),
		array( 'json_id' => 36, 'slot' => 'nature' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_rossiya_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'sochi', 'anapa', 'polyana', 'kmv', 'family' ), true ) ) {
		$tags[] = 'need-family';
	} elseif ( in_array( $slot, array( 'city', 'urban' ), true ) ) {
		$tags[] = 'need-urban';
	} elseif ( 'nature' === $slot ) {
		$tags[] = 'need-nature';
	} elseif ( 'couples' === $slot ) {
		$tags[] = 'need-adults';
	} else {
		$tags[] = 'need-price';
	}

	return array_values( array_unique( $tags ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_rossiya_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_rossiya_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_rossiya_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_rossiya_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_rossiya_image_from_json_row( $row );

	return array(
		'destination_slug' => 'rossiya',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'rossiya',
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
			__( 'Отель %s, Россия', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_rossiya_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_rossiya_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_rossiya_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_rossiya_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_rossiya_pool_price_rub( $row ),
	);
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_rossiya_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_rossiya_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_rossiya_card( $card );
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
		$tour = zhiraf_ufa_get_country_min_tour( 'rossiya' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'rossiya', $name, $bg_hotel_id );
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
		$card['price_rub']             = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_rossiya_hub_min_price_rub() : 0 );
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
function zhiraf_ufa_rossiya_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'RU-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'RU-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'RU-STARTER-' . $slot;
	}

	return 'RU-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_rossiya_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_rossiya_curated_uon_hotel_id( $card );
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
function zhiraf_ufa_rossiya_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_rossiya_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_rossiya_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_rossiya_json_row_to_card( $row );
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
		$card['filter_tags'] = zhiraf_ufa_rossiya_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по России', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_rossiya_finalize_catalog_card( zhiraf_ufa_rossiya_apply_bg_pricing( $card ) );
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
		$card = zhiraf_ufa_rossiya_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_rossiya_finalize_catalog_card( zhiraf_ufa_rossiya_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_rossiya_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_rossiya_catalog_filters() {
	return array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Россия: топ-6 из базы 50 отелей', 'zhiraf-ufa' ),
			'desc'  => __( 'По одному якорю на регион: Сочи, Поляна, Анапа, Кавминводы, города и природа. Пакет с перелётом или только отель.', 'zhiraf-ufa' ),
		),
		array( 'id' => 'resort-sochi', 'group' => 'resort', 'label' => __( '🌊 Сочи', 'zhiraf-ufa' ), 'title' => __( 'Сочи и Сириус', 'zhiraf-ufa' ), 'desc' => __( 'Море, Олимпийский парк, Имеретинка.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-polyana', 'group' => 'resort', 'label' => __( '⛷ Поляна', 'zhiraf-ufa' ), 'title' => __( 'Красная Поляна', 'zhiraf-ufa' ), 'desc' => __( 'Горы, канатки, летом — треккинг.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-anapa', 'group' => 'resort', 'label' => __( '🏖 Анапа', 'zhiraf-ufa' ), 'title' => __( 'Анапа', 'zhiraf-ufa' ), 'desc' => __( 'Песок и семейные отели.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-kmv', 'group' => 'resort', 'label' => __( '💆 Кавминводы', 'zhiraf-ufa' ), 'title' => __( 'Санатории и СПА', 'zhiraf-ufa' ), 'desc' => __( 'Кисловодск, Пятигорск и др.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-city', 'group' => 'resort', 'label' => __( '🏙 Города', 'zhiraf-ufa' ), 'title' => __( 'СПб, Москва, Калининград', 'zhiraf-ufa' ), 'desc' => __( 'Городские отели и выходные.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-nature', 'group' => 'resort', 'label' => __( '🌲 Природа', 'zhiraf-ufa' ), 'title' => __( 'Байкал, Алтай, Карелия', 'zhiraf-ufa' ), 'desc' => __( 'Эко-отели и отдых у воды.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'Питание и бассейн.', 'zhiraf-ufa' ) ),
	);
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_rossiya_catalog_default_copy() {
	$filters = zhiraf_ufa_rossiya_catalog_filters();

	$pool_size = count( zhiraf_ufa_rossiya_json_rows() );

	return array(
		'eyebrow' => sprintf(
			/* translators: %d: hotels count in JSON pool */
			__( 'Выборка из базы %d отелей', 'zhiraf-ufa' ),
			$pool_size > 0 ? $pool_size : 50
		),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в России', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Топ-6 для быстрого старта; ниже — «Показать ещё» по всей базе. Пакет с перелётом из Уфы или только отель.', 'zhiraf-ufa' ),
	);
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_rossiya_resort_hotel_counts() {
	$counts = array();
	foreach ( zhiraf_ufa_rossiya_catalog_hotels() as $hotel ) {
		$tags = ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ? $hotel['filter_tags'] : array();
		foreach ( $tags as $tag ) {
			if ( 0 !== strpos( (string) $tag, 'resort-' ) ) {
				continue;
			}
			$key = substr( (string) $tag, 7 );
			if ( ! isset( $counts[ $key ] ) ) {
				$counts[ $key ] = 0;
			}
			++$counts[ $key ];
		}
	}
	return $counts;
}

/**
 * @return array<int,array{id:string,label:string}>
 */
function zhiraf_ufa_rossiya_resort_chips() {
	return array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-sochi', 'label' => __( 'Сочи', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-polyana', 'label' => __( 'Поляна', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-anapa', 'label' => __( 'Анапа', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-kmv', 'label' => __( 'КМВ', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-city', 'label' => __( 'Города', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-nature', 'label' => __( 'Природа', 'zhiraf-ufa' ) ),
	);
}

/**
 * Four resort pick cards in one row on desktop.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_rossiya_resort_pick_cards() {
	$counts = zhiraf_ufa_rossiya_resort_hotel_counts();
	$guide  = __( 'К отелям →', 'zhiraf-ufa' );

	return array(
		array(
			'id'          => 'sochi',
			'filter_id'   => 'resort-sochi',
			'guide_url'   => zhiraf_ufa_rossiya_resort_landing_url( 'sochi' ),
			'guide_label' => $guide,
			'name'        => __( 'Сочи и Сириус', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Чёрное море', 'zhiraf-ufa' ),
			'tag'         => __( 'Лето', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( 'Прямой рейс из Уфы', 'zhiraf-ufa' ),
			'featured'    => true,
			'hotel_count' => $counts['sochi'] ?? 0,
			'accent_mod'  => 'emerald',
			'bullets'     => array(
				__( 'Сириус и Имеретинка — пляж и парк', 'zhiraf-ufa' ),
				__( 'Пакет или только отель', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'          => 'polyana',
			'filter_id'   => 'resort-polyana',
			'guide_url'   => zhiraf_ufa_rossiya_resort_landing_url( 'polyana' ),
			'guide_label' => $guide,
			'name'        => __( 'Красная Поляна', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Горы', 'zhiraf-ufa' ),
			'tag'         => __( 'Зима / лето', 'zhiraf-ufa' ),
			'tag_mod'     => 'nature',
			'transfer'    => __( 'Сочи + трансфер', 'zhiraf-ufa' ),
			'hotel_count' => $counts['polyana'] ?? 0,
			'accent_mod'  => 'indigo',
			'bullets'     => array(
				__( 'Лыжи зимой, прогулки летом', 'zhiraf-ufa' ),
				__( 'Отели у канаток', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'          => 'anapa',
			'filter_id'   => 'resort-anapa',
			'guide_url'   => zhiraf_ufa_rossiya_resort_landing_url( 'anapa' ),
			'guide_label' => $guide,
			'name'        => __( 'Анапа', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Песчаные пляжи', 'zhiraf-ufa' ),
			'tag'         => __( 'Семьи', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( 'Рейс в Анапу / Краснодар', 'zhiraf-ufa' ),
			'hotel_count' => $counts['anapa'] ?? 0,
			'accent_mod'  => 'coral',
			'bullets'     => array(
				__( 'Пологий заход в море', 'zhiraf-ufa' ),
				__( 'Джемете и Витязево', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'          => 'kmv',
			'filter_id'   => 'resort-kmv',
			'guide_url'   => zhiraf_ufa_rossiya_resort_landing_url( 'kmv' ),
			'guide_label' => $guide,
			'name'        => __( 'Кавминводы', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Санатории', 'zhiraf-ufa' ),
			'tag'         => __( 'Круглый год', 'zhiraf-ufa' ),
			'tag_mod'     => 'health',
			'transfer'    => __( 'Аэропорт Минеральные Воды', 'zhiraf-ufa' ),
			'hotel_count' => $counts['kmv'] ?? 0,
			'accent_mod'  => 'gold',
			'bullets'     => array(
				__( 'Лечение и СПА-программы', 'zhiraf-ufa' ),
				__( 'Кисловодск, Ессентуки, Пятигорск', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'          => 'city',
			'filter_id'   => 'resort-city',
			'guide_url'   => zhiraf_ufa_rossiya_resort_landing_url( 'city' ),
			'guide_label' => $guide,
			'name'        => __( 'Города', 'zhiraf-ufa' ),
			'subtitle'    => __( 'СПб · Москва · Калининград', 'zhiraf-ufa' ),
			'tag'         => __( 'Выходные', 'zhiraf-ufa' ),
			'tag_mod'     => 'urban',
			'transfer'    => __( 'Перелёт из Уфы', 'zhiraf-ufa' ),
			'hotel_count' => $counts['city'] ?? 0,
			'accent_mod'  => 'slate',
			'bullets'     => array(
				__( 'Отели у центра и набережных', 'zhiraf-ufa' ),
				__( 'Можно без пакета — только номер', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'          => 'nature',
			'filter_id'   => 'resort-nature',
			'guide_url'   => zhiraf_ufa_rossiya_resort_landing_url( 'nature' ),
			'guide_label' => $guide,
			'name'        => __( 'Байкал · Алтай · Карелия', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Эко и турбазы', 'zhiraf-ufa' ),
			'tag'         => __( 'Природа', 'zhiraf-ufa' ),
			'tag_mod'     => 'nature',
			'transfer'    => __( 'Стыковочные рейсы', 'zhiraf-ufa' ),
			'hotel_count' => $counts['nature'] ?? 0,
			'accent_mod'  => 'pine',
			'bullets'     => array(
				__( 'Ольхон, Чемал, Петрозаводск', 'zhiraf-ufa' ),
				__( 'Сезон и логистику сверяем до оплаты', 'zhiraf-ufa' ),
			),
		),
	);
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_rossiya_expert_nuances() {
	return array(
		array( 'icon' => '✈', 'title' => __( 'Перелёты по РФ', 'zhiraf-ufa' ), 'text' => __( 'Сочи, Анапа, Минводы, Москва и регионы — или бронь только отеля.', 'zhiraf-ufa' ) ),
		array( 'icon' => '🏨', 'title' => __( 'Только проживание', 'zhiraf-ufa' ), 'text' => __( 'Блочные тарифы, если билеты и ж/д уже есть.', 'zhiraf-ufa' ) ),
		array( 'icon' => '🗺', 'title' => __( '50 отелей в базе', 'zhiraf-ufa' ), 'text' => __( 'Фильтры по Сочи, Поляне, КМВ, городам и природе — «Показать ещё» откроет весь пул.', 'zhiraf-ufa' ) ),
	);
}

