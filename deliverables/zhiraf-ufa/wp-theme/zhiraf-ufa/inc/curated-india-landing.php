<?php
/**
 * India (/tour/indiya/) curated landing — 6 starters + pool from india_hotels_25.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_india_hub_min_price_rub() {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'india' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_india_base_price_rub', 142300 );
}

/**
 * @return string
 */
function zhiraf_ufa_india_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'india' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_india_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_india_hotels_json_path();
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}

/**
 * Goa regions (south_goa, north_goa).
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_india_resort_keys_from_row( array $row ) {
	$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$keys   = array();
	foreach ( array( 'south_goa', 'north_goa' ) as $allowed ) {
		if ( $region === $allowed ) {
			$keys[] = $allowed;
		}
	}
	return array_values( array_unique( $keys ) );
}

/**
 * Hub anchor — отдельных курортных URL для Индии нет (якорь каталога).
 *
 * @param string $resort_key south_goa|north_goa.
 * @return string
 */
function zhiraf_ufa_india_resort_landing_url( $resort_key ) {
	unset( $resort_key );

	return home_url( '/tour/indiya/#dest-curated-hotels' );
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_india_catalog_fill_resort_labels() {
	return array(
		'resort-south_goa' => __( 'Южном Гоа', 'zhiraf-ufa' ),
		'resort-north_goa' => __( 'Северном Гоа', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_india_need_tag_from_category( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	switch ( $category_slug ) {
		case 'family':
			return 'need-family';
		case 'couples':
			return 'need-adults';
		case 'active_north':
			return 'need-active';
		case 'south_classic':
			return 'need-premium';
		case 'balance':
		default:
			return 'need-price';
	}
}

/**
 * @param string $category_slug Category slug.
 * @return array{badge:string,badge_mod:string}
 */
function zhiraf_ufa_india_pool_need_badge( $category_slug ) {
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
	if ( 'active_north' === $category_slug ) {
		return array(
			'badge'     => __( 'Север: кафе и закаты', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
		);
	}
	if ( 'south_classic' === $category_slug ) {
		return array(
			'badge'     => __( 'Классика юга', 'zhiraf-ufa' ),
			'badge_mod' => 'premium',
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
function zhiraf_ufa_india_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 198000;
		case 'couples':
			return 175000;
		case 'active_north':
			return 168000;
		case 'south_classic':
			return 215000;
		case 'balance':
		default:
			return 142300;
	}
}

/**
 * @param string $name Hotel title.
 * @param string $cat  Category slug.
 * @return string
 */
function zhiraf_ufa_india_pool_meal_concept( $name, $cat ) {
	$name  = (string) $name;
	$cat   = sanitize_key( (string) $cat );
	$stars = '';
	if ( preg_match( '/\b(\d)\s*★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}
	if ( 'family' === $cat || 'south_classic' === $cat ) {
		return trim( $stars . ' ' . __( 'на завтраках или HB', 'zhiraf-ufa' ) );
	}
	if ( 'active_north' === $cat ) {
		return trim( $stars . ' ' . __( 'BB + шеки у пляжа', 'zhiraf-ufa' ) );
	}

	return trim( $stars . ' ' . __( 'Завтраки (BB)', 'zhiraf-ufa' ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_india_pool_card_usp( $row ) {
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
function zhiraf_ufa_india_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_india_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );
		return trim( $parts[0] );
	}
	$keys = zhiraf_ufa_india_resort_keys_from_row( $row );
	$labels = array(
		'south_goa' => __( 'Юг Гоа', 'zhiraf-ufa' ),
		'north_goa' => __( 'Север Гоа', 'zhiraf-ufa' ),
	);
	if ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {
		return $labels[ $keys[0] ];
	}
	return __( 'Гоа', 'zhiraf-ufa' );
}

/**
 * Unique theme fallbacks so six starters do not share one stock photo.
 *
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_india_image_from_json_row( array $row ) {
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
		foreach ( array( 'hotels/india/' . $first, $first ) as $rel ) {
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
	$fallback = 'resort-severnyj-goa.jpg';
	if ( ! is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $fallback ) ) {
		$fallback = 'resort-severnyj-goa.jpg';
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
function zhiraf_ufa_india_starter_definitions() {
	return array(
		array( 'json_id' => 21, 'slot' => 'base' ),
		array( 'json_id' => 3, 'slot' => 'family-south' ),
		array( 'json_id' => 6, 'slot' => 'north' ),
		array( 'json_id' => 11, 'slot' => 'budget-north' ),
		array( 'json_id' => 16, 'slot' => 'premium-south' ),
		array( 'json_id' => 21, 'slot' => 'balance' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_india_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'family', 'family-south', 'premium-south' ), true ) ) {
		$tags[] = 'need-family';
	} elseif ( 'couples' === $slot || 'north' === $slot ) {
		$tags[] = 'need-adults';
	} elseif ( 'active-north' === $slot || 'budget-north' === $slot ) {
		$tags[] = 'need-active';
	} else {
		$tags[] = 'need-price';
	}

	return array_values( array_unique( $tags ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_india_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_india_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_india_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_india_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_india_image_from_json_row( $row );

	return array(
		'destination_slug' => 'indiya',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'south_goa',
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
			__( 'Отель %s, Индия', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_india_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_india_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_india_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_india_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_india_pool_price_rub( $row ),
	);
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_india_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_india_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_india_card( $card );
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
		$tour = zhiraf_ufa_get_country_min_tour( 'india' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'india', $name, $bg_hotel_id );
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
		$card['price_rub']             = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_india_hub_min_price_rub() : 0 );
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
function zhiraf_ufa_india_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'IN-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'IN-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'IN-STARTER-' . $slot;
	}

	return 'IN-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_india_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_india_curated_uon_hotel_id( $card );
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
function zhiraf_ufa_india_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_india_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_india_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_india_json_row_to_card( $row );
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
		$card['filter_tags'] = zhiraf_ufa_india_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по Гоа', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_india_finalize_catalog_card( zhiraf_ufa_india_apply_bg_pricing( $card ) );
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
		$card = zhiraf_ufa_india_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_india_finalize_catalog_card( zhiraf_ufa_india_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_india_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_india_catalog_filters() {
	return array(
		array( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Туры в Гоа: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Южный и Северный Гоа — пляж и BB. Цены за двоих с перелётом из Уфы.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-south_goa', 'group' => 'resort', 'label' => __( '🏖 Юг Гоа', 'zhiraf-ufa' ), 'title' => __( 'Южный Гоа', 'zhiraf-ufa' ), 'desc' => __( 'Тишина и длинные пляжи.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-north_goa', 'group' => 'resort', 'label' => __( '🌴 Север Гоа', 'zhiraf-ufa' ), 'title' => __( 'Северный Гоа', 'zhiraf-ufa' ), 'desc' => __( 'Кафе и вечерняя жизнь.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'Кавелоссим, Мобор и Варка — пологий песок и клубные отели.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-adults', 'group' => 'need', 'label' => __( '🥂 Для пар', 'zhiraf-ufa' ), 'title' => __( 'Отели для пар', 'zhiraf-ufa' ), 'desc' => __( 'Север — закаты и шеки; юг — тихие бухты.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-active', 'group' => 'need', 'label' => __( '🌴 Север', 'zhiraf-ufa' ), 'title' => __( 'Активный Северный Гоа', 'zhiraf-ufa' ), 'desc' => __( 'Арамболь, Калангут и Мандрем — рынки и вечер.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-premium', 'group' => 'need', 'label' => __( '✨ 5★ на юге', 'zhiraf-ufa' ), 'title' => __( 'Классика Южного Гоа', 'zhiraf-ufa' ), 'desc' => __( 'Taj, Leela и сетевые резорты на длинных пляжах.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-price', 'group' => 'need', 'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ), 'title' => __( 'Цена / качество', 'zhiraf-ufa' ), 'desc' => __( '3–4★ у моря без переплаты за бренд.', 'zhiraf-ufa' ) ),
	);
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_india_catalog_default_copy() {
	$filters = zhiraf_ufa_india_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в Индии', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Под задачу — семья, пары и честный BB по нашей базе 25 отелей. Цены — за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_india_resort_hotel_counts() {
	$counts = array();
	foreach ( zhiraf_ufa_india_catalog_hotels() as $hotel ) {
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
function zhiraf_ufa_india_resort_chips() {
	return array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-south_goa', 'label' => __( 'Юг Гоа', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-north_goa', 'label' => __( 'Север Гоа', 'zhiraf-ufa' ) ),
	);
}

/**
 * Four resort pick cards in one row on desktop.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_india_resort_pick_cards() {
	$counts = zhiraf_ufa_india_resort_hotel_counts();
	$guide = __( 'К отелям →', 'zhiraf-ufa' );
	return array(
		array( 'id' => 'south_goa', 'filter_id' => 'resort-south_goa', 'guide_url' => zhiraf_ufa_india_resort_landing_url( 'south_goa' ), 'guide_label' => $guide, 'name' => __( 'Южный Гоа', 'zhiraf-ufa' ), 'subtitle' => __( 'Тихие пляжи', 'zhiraf-ufa' ), 'tag' => __( 'Семья', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['south_goa'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array( array( 'type' => 'pro', 'text' => __( 'Бенаулим и Кавелоссим', 'zhiraf-ufa' ) ) ) ),
		array( 'id' => 'north_goa', 'filter_id' => 'resort-north_goa', 'guide_url' => zhiraf_ufa_india_resort_landing_url( 'north_goa' ), 'guide_label' => $guide, 'name' => __( 'Северный Гоа', 'zhiraf-ufa' ), 'subtitle' => __( 'Рынки и кафе', 'zhiraf-ufa' ), 'tag' => __( 'Активно', 'zhiraf-ufa' ), 'tag_mod' => 'adults', 'transfer' => __( 'Ближе к аэропорту GOI', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['north_goa'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array( array( 'type' => 'info', 'text' => __( 'Сезон: ноябрь — апрель', 'zhiraf-ufa' ) ) ) ),
	);
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_india_expert_nuances() {
	return array(
		array( 'icon' => '✈', 'title' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'text' => __( 'Стыковка в Москве или Дубае — считаем время и багаж до брони.', 'zhiraf-ufa' ) ),
		array( 'icon' => '🛂', 'title' => __( 'e-Visa', 'zhiraf-ufa' ), 'text' => __( 'Туристическая e-Visa оформляется онлайн до вылета — проверим данные паспорта.', 'zhiraf-ufa' ) ),
		array( 'icon' => '🏖', 'title' => __( 'Юг или Север', 'zhiraf-ufa' ), 'text' => __( 'Юг — длинный песок и тишина. Север — шаки, рынки и вечер. Подберём под состав и месяц.', 'zhiraf-ufa' ) ),
	);
}

