<?php
/**
 * Indonesia (/tour/indoneziya/) curated landing — 6 starters + pool from indonesia_hotels_25.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_indonesia_hub_min_price_rub() {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'indonesia' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_indonesia_base_price_rub', 248000 );
}

/**
 * @return string
 */
function zhiraf_ufa_indonesia_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'indonesia' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_indonesia_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_indonesia_hotels_json_path();
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}

/**
 * Bali regions; legian/canggu → seminyak, benoa → nusa_dua, jimbaran → sanur.
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_indonesia_resort_keys_from_row( array $row ) {
	$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$alias  = array(
		'canggu'   => 'seminyak',
		'legian'   => 'seminyak',
		'benoa'    => 'nusa_dua',
		'jimbaran' => 'sanur',
	);
	if ( isset( $alias[ $region ] ) ) {
		$region = $alias[ $region ];
	}
	$keys = array();
	foreach ( array( 'nusa_dua', 'seminyak', 'sanur', 'ubud' ) as $allowed ) {
		if ( $region === $allowed ) {
			$keys[] = $allowed;
		}
	}
	return array_values( array_unique( $keys ) );
}

/**
 * Hub anchor — отдельных курортных URL для Индонезии нет (якорь каталога).
 *
 * @param string $resort_key nusa_dua|seminyak|sanur|ubud.
 * @return string
 */
function zhiraf_ufa_indonesia_resort_landing_url( $resort_key ) {
	unset( $resort_key );

	return home_url( '/tour/indoneziya/#dest-curated-hotels' );
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_indonesia_catalog_fill_resort_labels() {
	return array(
		'resort-nusa_dua' => __( 'Нуса-Дуа', 'zhiraf-ufa' ),
		'resort-seminyak' => __( 'Семиньяке', 'zhiraf-ufa' ),
		'resort-sanur'    => __( 'Сануре', 'zhiraf-ufa' ),
		'resort-ubud'     => __( 'Убуде', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_indonesia_need_tag_from_category( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	switch ( $category_slug ) {
		case 'family':
			return 'need-family';
		case 'ubud':
			return 'need-nature';
		case 'seminyak_canggu':
			return 'need-urban';
		case 'sanur_jimbaran':
			return 'need-adults';
		case 'balance':
		default:
			return 'need-price';
	}
}

/**
 * @param string $category_slug Category slug.
 * @return array{badge:string,badge_mod:string}
 */
function zhiraf_ufa_indonesia_pool_need_badge( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	if ( 'family' === $category_slug ) {
		return array(
			'badge'     => __( 'Семья и океан', 'zhiraf-ufa' ),
			'badge_mod' => 'family',
		);
	}
	if ( 'ubud' === $category_slug ) {
		return array(
			'badge'     => __( 'Убуд / джунгли', 'zhiraf-ufa' ),
			'badge_mod' => 'nature',
		);
	}
	if ( 'seminyak_canggu' === $category_slug ) {
		return array(
			'badge'     => __( 'Семиньяк / Чангу', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
		);
	}
	if ( 'sanur_jimbaran' === $category_slug ) {
		return array(
			'badge'     => __( 'Санур / Джимбаран', 'zhiraf-ufa' ),
			'badge_mod' => 'adults',
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
function zhiraf_ufa_indonesia_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 192000;
		case 'ubud':
			return 205000;
		case 'seminyak_canggu':
			return 188000;
		case 'sanur_jimbaran':
			return 198000;
		case 'balance':
		default:
			return 176800;
	}
}

/**
 * @param string $name Hotel title.
 * @param string $cat  Category slug.
 * @return string
 */
function zhiraf_ufa_indonesia_pool_meal_concept( $name, $cat ) {
	$name  = (string) $name;
	$cat   = sanitize_key( (string) $cat );
	$stars = '';
	if ( preg_match( '/\b(\d)\s*★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}
	if ( 'family' === $cat ) {
		return trim( $stars . ' ' . __( 'завтраки + бассейны', 'zhiraf-ufa' ) );
	}
	if ( 'ubud' === $cat ) {
		return trim( $stars . ' ' . __( 'вилла в джунглях', 'zhiraf-ufa' ) );
	}
	if ( 'seminyak_canggu' === $cat ) {
		return trim( $stars . ' ' . __( 'бич-клабы рядом', 'zhiraf-ufa' ) );
	}

	return trim( $stars . ' ' . __( 'на завтраках', 'zhiraf-ufa' ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_indonesia_pool_card_usp( $row ) {
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
function zhiraf_ufa_indonesia_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_indonesia_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );
		return trim( $parts[0] );
	}
	$keys = zhiraf_ufa_indonesia_resort_keys_from_row( $row );
	$labels = array(
		'nusa_dua' => __( 'Нуса-Дуа', 'zhiraf-ufa' ),
		'seminyak' => __( 'Семиньяк', 'zhiraf-ufa' ),
		'sanur' => __( 'Санур', 'zhiraf-ufa' ),
		'ubud' => __( 'Убуд', 'zhiraf-ufa' ),
	);
	if ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {
		return $labels[ $keys[0] ];
	}
	return __( 'Бали', 'zhiraf-ufa' );
}

/**
 * Unique theme fallbacks so six starters do not share one stock photo.
 *
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_indonesia_image_from_json_row( array $row ) {
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
		foreach ( array( 'hotels/indonesia/' . $first, $first ) as $rel ) {
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
	$fallback = 'country-indoneziya.jpg';
	if ( ! is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $fallback ) ) {
		$fallback = 'country-indoneziya.jpg';
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
function zhiraf_ufa_indonesia_starter_definitions() {
	return array(
		array( 'json_id' => 1, 'slot' => 'base' ),
		array( 'json_id' => 3, 'slot' => 'family' ),
		array( 'json_id' => 6, 'slot' => 'ubud' ),
		array( 'json_id' => 11, 'slot' => 'urban' ),
		array( 'json_id' => 16, 'slot' => 'sanur' ),
		array( 'json_id' => 21, 'slot' => 'balance' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_indonesia_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'family', 'family-nusadua', 'family-sanur' ), true ) ) {
		$tags[] = 'need-family';
	} elseif ( 'ubud' === $slot ) {
		$tags[] = 'need-nature';
	} elseif ( 'urban' === $slot ) {
		$tags[] = 'need-urban';
	} elseif ( 'sanur' === $slot ) {
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
function zhiraf_ufa_indonesia_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_indonesia_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_indonesia_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_indonesia_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_indonesia_image_from_json_row( $row );

	return array(
		'destination_slug' => 'indoneziya',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'nusa_dua',
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
			__( 'Отель %s, Бали', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_indonesia_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_indonesia_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_indonesia_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_indonesia_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_indonesia_pool_price_rub( $row ),
	);
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_indonesia_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_indonesia_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_indonesia_card( $card );
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
		$tour = zhiraf_ufa_get_country_min_tour( 'indonesia' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'indonesia', $name, $bg_hotel_id );
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
		$card['price_rub']             = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_indonesia_hub_min_price_rub() : 0 );
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
function zhiraf_ufa_indonesia_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'ID-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'ID-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'ID-STARTER-' . $slot;
	}

	return 'ID-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_indonesia_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_indonesia_curated_uon_hotel_id( $card );
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
function zhiraf_ufa_indonesia_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_indonesia_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_indonesia_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_indonesia_json_row_to_card( $row );
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
		$card['filter_tags'] = zhiraf_ufa_indonesia_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по Бали', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_indonesia_finalize_catalog_card( zhiraf_ufa_indonesia_apply_bg_pricing( $card ) );
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
		$card = zhiraf_ufa_indonesia_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_indonesia_finalize_catalog_card( zhiraf_ufa_indonesia_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_indonesia_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_indonesia_catalog_filters() {
	return array(
		array( 'id' => 'starter', 'group' => 'default', 'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ), 'title' => __( 'Туры на Бали: 6 проверенных отелей', 'zhiraf-ufa' ), 'desc' => __( 'Нуса-Дуа, Семиньяк, Санур и Убуд. Цены за двоих с перелётом из Уфы.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-nusa_dua', 'group' => 'resort', 'label' => __( '🏝 Нуса-Дуа', 'zhiraf-ufa' ), 'title' => __( 'Нуса-Дуа', 'zhiraf-ufa' ), 'desc' => __( 'Спокойное море и резорты 5★.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-seminyak', 'group' => 'resort', 'label' => __( '🌅 Семиньяк', 'zhiraf-ufa' ), 'title' => __( 'Семиньяк / Чангу', 'zhiraf-ufa' ), 'desc' => __( 'Закаты и кафе.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-sanur', 'group' => 'resort', 'label' => __( '🌊 Санур', 'zhiraf-ufa' ), 'title' => __( 'Санур', 'zhiraf-ufa' ), 'desc' => __( 'Семейный восточный пляж.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-ubud', 'group' => 'resort', 'label' => __( '🌿 Убуд', 'zhiraf-ufa' ), 'title' => __( 'Убуд', 'zhiraf-ufa' ), 'desc' => __( 'Джунгли без моря.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'Нуса-Дуа и Санур.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-price', 'group' => 'need', 'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ), 'title' => __( 'Цена / качество', 'zhiraf-ufa' ), 'desc' => __( 'Честные 4★.', 'zhiraf-ufa' ) ),
	);
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_indonesia_catalog_default_copy() {
	$filters = zhiraf_ufa_indonesia_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в Индонезии', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Под задачу — семья, пары и честный BB по нашей базе 25 отелей. Цены — за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_indonesia_resort_hotel_counts() {
	$counts = array();
	foreach ( zhiraf_ufa_indonesia_catalog_hotels() as $hotel ) {
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
function zhiraf_ufa_indonesia_resort_chips() {
	return array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-nusa_dua', 'label' => __( 'Нуса-Дуа', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-seminyak', 'label' => __( 'Семиньяк', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-sanur', 'label' => __( 'Санур', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-ubud', 'label' => __( 'Убуд', 'zhiraf-ufa' ) ),
	);
}

/**
 * Four resort pick cards in one row on desktop.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_indonesia_resort_pick_cards() {
	$counts = zhiraf_ufa_indonesia_resort_hotel_counts();
	$guide = __( 'К отелям →', 'zhiraf-ufa' );
	return array(
		array( 'id' => 'nusa_dua', 'filter_id' => 'resort-nusa_dua', 'guide_url' => zhiraf_ufa_indonesia_resort_landing_url( 'nusa_dua' ), 'guide_label' => $guide, 'name' => __( 'Нуса-Дуа', 'zhiraf-ufa' ), 'subtitle' => __( 'Спокойное море', 'zhiraf-ufa' ), 'tag' => __( 'С детьми', 'zhiraf-ufa' ), 'tag_mod' => 'family', 'transfer' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'featured' => true, 'hotel_count' => $counts['nusa_dua'] ?? 0, 'accent_mod' => 'emerald', 'bullets' => array( array( 'type' => 'pro', 'text' => __( 'Пологий заход и отели-клубы', 'zhiraf-ufa' ) ) ) ),
		array( 'id' => 'seminyak', 'filter_id' => 'resort-seminyak', 'guide_url' => zhiraf_ufa_indonesia_resort_landing_url( 'seminyak' ), 'guide_label' => $guide, 'name' => __( 'Семиньяк', 'zhiraf-ufa' ), 'subtitle' => __( 'Закаты и кафе', 'zhiraf-ufa' ), 'tag' => __( 'Для пар', 'zhiraf-ufa' ), 'tag_mod' => 'adults', 'transfer' => __( 'Серф и вечер', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['seminyak'] ?? 0, 'accent_mod' => 'teal', 'bullets' => array( array( 'type' => 'info', 'text' => __( 'Волны — купание по флажкам', 'zhiraf-ufa' ) ) ) ),
		array( 'id' => 'sanur', 'filter_id' => 'resort-sanur', 'guide_url' => zhiraf_ufa_indonesia_resort_landing_url( 'sanur' ), 'guide_label' => $guide, 'name' => __( 'Санур', 'zhiraf-ufa' ), 'subtitle' => __( 'Семейный пляж', 'zhiraf-ufa' ), 'tag' => __( 'Релакс', 'zhiraf-ufa' ), 'tag_mod' => 'nature', 'transfer' => __( 'Близко к аэропорту', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['sanur'] ?? 0, 'accent_mod' => 'amber', 'bullets' => array() ),
		array( 'id' => 'ubud', 'filter_id' => 'resort-ubud', 'guide_url' => zhiraf_ufa_indonesia_resort_landing_url( 'ubud' ), 'guide_label' => $guide, 'name' => __( 'Убуд', 'zhiraf-ufa' ), 'subtitle' => __( 'Джунгли', 'zhiraf-ufa' ), 'tag' => __( 'Без моря', 'zhiraf-ufa' ), 'tag_mod' => 'value', 'transfer' => __( 'Комбо с пляжем', 'zhiraf-ufa' ), 'featured' => false, 'hotel_count' => $counts['ubud'] ?? 0, 'accent_mod' => 'lux', 'bullets' => array() ),
	);
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_indonesia_expert_nuances() {
	return array(
		array( 'icon' => '✈', 'title' => __( 'Вылет из Уфы', 'zhiraf-ufa' ), 'text' => __( 'Долгий перелёт со стыковкой — закладываем день на дорогу и отдых в первый день.', 'zhiraf-ufa' ) ),
		array( 'icon' => '🛂', 'title' => __( 'VoA и сбор Бали', 'zhiraf-ufa' ), 'text' => __( 'Виза по прилёту или e-VoA, плюс туристический сбор острова — напомним до оплаты тура.', 'zhiraf-ufa' ) ),
		array( 'icon' => '🌊', 'title' => __( 'Где купаться', 'zhiraf-ufa' ), 'text' => __( 'Спокойная вода — Нуса-Дуа и Санур. Семиньяк и Чангу — серф; для детей чаще Нуса-Дуа.', 'zhiraf-ufa' ) ),
	);
}

