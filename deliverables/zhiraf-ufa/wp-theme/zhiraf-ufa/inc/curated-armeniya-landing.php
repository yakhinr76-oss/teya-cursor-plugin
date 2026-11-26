<?php
/**
 * Armenia (/tour/armeniya/) curated landing — 6 starters + pool from armeniya_hotels_25.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_armeniya_hub_min_price_rub() {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'armeniya' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_armeniya_base_price_rub', 95000 );
}

/**
 * @return string
 */
function zhiraf_ufa_armeniya_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'armeniya' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_armeniya_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_armeniya_hotels_json_path();
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
function zhiraf_ufa_armeniya_resort_keys_from_row( array $row ) {
	$allowed = array( 'yerevan', 'dilijan', 'tsaghkadzor', 'jermuk', 'sevan' );
	$city    = ! empty( $row['city'] ) ? sanitize_key( (string) $row['city'] ) : '';
	if ( in_array( $city, $allowed, true ) ) {
		return array( $city );
	}
	$resort = mb_strtolower( (string) ( $row['resort'] ?? '' ) );
	$map    = array(
		'ереван'    => 'yerevan',
		'дилижан'   => 'dilijan',
		'цахкадзор' => 'tsaghkadzor',
		'джермук'   => 'jermuk',
		'севан'     => 'sevan',
	);
	foreach ( $map as $needle => $key ) {
		if ( str_contains( $resort, $needle ) ) {
			return array( $key );
		}
	}
	return array();
}

/**
 * @return array<string,string> resort key => nominative label.
 */
function zhiraf_ufa_armeniya_resort_labels() {
	return array(
		'yerevan'      => __( 'Ереван', 'zhiraf-ufa' ),
		'dilijan'      => __( 'Дилижан', 'zhiraf-ufa' ),
		'sevan'        => __( 'Севан', 'zhiraf-ufa' ),
		'tsaghkadzor'  => __( 'Цахкадзор', 'zhiraf-ufa' ),
		'jermuk'       => __( 'Джермук', 'zhiraf-ufa' ),
	);
}

/**
 * Hub anchor — отдельных курортных URL для Армении нет (якорь каталога).
 *
 * @param string $resort_key phuket|pattaya|khaolak|samui.
 * @return string
 */
function zhiraf_ufa_armeniya_resort_landing_url( $resort_key ) {
	unset( $resort_key );

	return home_url( '/tour/armeniya/#dest-curated-hotels' );
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_armeniya_catalog_fill_resort_labels() {
	return array(
		'resort-yerevan'     => __( 'Ереване', 'zhiraf-ufa' ),
		'resort-dilijan'     => __( 'Дилижане', 'zhiraf-ufa' ),
		'resort-sevan'       => __( 'на Севане', 'zhiraf-ufa' ),
		'resort-tsaghkadzor' => __( 'Цахкадзоре', 'zhiraf-ufa' ),
		'resort-jermuk'      => __( 'Джермуке', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_armeniya_need_tag_from_category( $category_slug ) {
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
function zhiraf_ufa_armeniya_pool_need_badge( $category_slug ) {
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
function zhiraf_ufa_armeniya_pool_price_rub( $row ) {
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
function zhiraf_ufa_armeniya_pool_meal_concept( $name, $cat ) {
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
function zhiraf_ufa_armeniya_pool_card_usp( $row ) {
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
function zhiraf_ufa_armeniya_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_armeniya_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );
		return trim( $parts[0] );
	}
	$keys   = zhiraf_ufa_armeniya_resort_keys_from_row( $row );
	$labels = zhiraf_ufa_armeniya_resort_labels();
	if ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {
		return $labels[ $keys[0] ];
	}
	return __( 'Армения', 'zhiraf-ufa' );
}

/**
 * Unique theme fallbacks so six starters do not share one stock photo.
 *
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_armeniya_image_from_json_row( array $row ) {
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
		foreach ( array( 'hotels/armeniya/' . $first, $first ) as $rel ) {
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
	$fallback = 'country-armeniya.jpg';
	if ( ! is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $fallback ) ) {
		$fallback = 'country-armeniya.jpg';
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
function zhiraf_ufa_armeniya_starter_definitions() {
	return array(
		array( 'json_id' => 22, 'slot' => 'yerevan' ),
		array( 'json_id' => 4, 'slot' => 'yerevan' ),
		array( 'json_id' => 6, 'slot' => 'dilijan' ),
		array( 'json_id' => 16, 'slot' => 'sevan' ),
		array( 'json_id' => 13, 'slot' => 'tsaghkadzor' ),
		array( 'json_id' => 19, 'slot' => 'jermuk' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_armeniya_starter_filter_tags( $hotel ) {
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
function zhiraf_ufa_armeniya_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_armeniya_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_armeniya_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_armeniya_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_armeniya_image_from_json_row( $row );

	$card = array(
		'destination_slug' => 'armeniya',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'yerevan',
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
			__( 'Отель %s, Армения', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_armeniya_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_armeniya_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_armeniya_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_armeniya_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_armeniya_pool_price_rub( $row ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_armeniya_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_armeniya_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_armeniya_card( $card );
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
		$tour = zhiraf_ufa_get_country_min_tour( 'armeniya' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'armeniya', $name, $bg_hotel_id );
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
		$card['price_rub']             = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_armeniya_hub_min_price_rub() : 0 );
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
function zhiraf_ufa_armeniya_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'AM-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'AM-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'AM-STARTER-' . $slot;
	}

	return 'AM-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_armeniya_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_armeniya_curated_uon_hotel_id( $card );
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
function zhiraf_ufa_armeniya_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_armeniya_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_armeniya_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_armeniya_json_row_to_card( $row );
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
		$card['filter_tags'] = zhiraf_ufa_armeniya_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по Армении', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_armeniya_finalize_catalog_card( zhiraf_ufa_armeniya_apply_bg_pricing( $card ) );
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
		$card = zhiraf_ufa_armeniya_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_armeniya_finalize_catalog_card( zhiraf_ufa_armeniya_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_armeniya_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_armeniya_catalog_filters() {
	return array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( 'Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Армения: топ-6 из базы 25 отелей', 'zhiraf-ufa' ),
			'desc'  => __( 'По одному якорю на направление: Ереван, Дилижан, Севан, Цахкадзор, Джермук. Вылет из Уфы.', 'zhiraf-ufa' ),
		),
		array( 'id' => 'resort-yerevan', 'group' => 'resort', 'label' => __( 'Ереван', 'zhiraf-ufa' ), 'title' => __( 'Ереван', 'zhiraf-ufa' ), 'desc' => __( 'Парам и «первый раз в Армении»: город, кафе, выездные экскурсии.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-dilijan', 'group' => 'resort', 'label' => __( 'Дилижан', 'zhiraf-ufa' ), 'title' => __( 'Дилижан', 'zhiraf-ufa' ), 'desc' => __( 'Семьям и тем, кому нужна тишина: лес, трекинг, без городского шума.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-sevan', 'group' => 'resort', 'label' => __( 'Севан', 'zhiraf-ufa' ), 'title' => __( 'Озеро Севан', 'zhiraf-ufa' ), 'desc' => __( 'Короткий «перезагруз» на 1–2 ночи: озеро, монастыри, рыба.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-tsaghkadzor', 'group' => 'resort', 'label' => __( 'Цахкадзор', 'zhiraf-ufa' ), 'title' => __( 'Цахкадзор', 'zhiraf-ufa' ), 'desc' => __( 'Зимой — лыжи и активный отдых; межсезонье — SPA и горный воздух.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-jermuk', 'group' => 'resort', 'label' => __( 'Джермук', 'zhiraf-ufa' ), 'title' => __( 'Джермук', 'zhiraf-ufa' ), 'desc' => __( 'Оздоровительный формат: минеральная вода, неспешный ритм, старшему поколению.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-price', 'group' => 'need', 'label' => __( 'Цена/качество', 'zhiraf-ufa' ), 'title' => __( '3–4', 'zhiraf-ufa' ), 'desc' => __( 'Бутики и сетевые отели.', 'zhiraf-ufa' ) ),
	);
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_armeniya_catalog_default_copy() {
	$filters = zhiraf_ufa_armeniya_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в Армении', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Под задачу — семья, пары и честный BB по нашей базе 25 отелей. Цены — за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_armeniya_resort_hotel_counts() {
	$counts = array();
	foreach ( zhiraf_ufa_armeniya_catalog_hotels() as $hotel ) {
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
function zhiraf_ufa_armeniya_resort_chips() {
	return array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-yerevan', 'label' => __( 'Ереван', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-dilijan', 'label' => __( 'Дилижан', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-sevan', 'label' => __( 'Севан', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-tsaghkadzor', 'label' => __( 'Цахкадзор', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-jermuk', 'label' => __( 'Джермук', 'zhiraf-ufa' ) ),
	);
}

/**
 * Resort pick cards (desktop row).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_armeniya_resort_pick_cards() {
	$counts = zhiraf_ufa_armeniya_resort_hotel_counts();
	$guide  = __( 'Подобрать тур →', 'zhiraf-ufa' );

	return array(
		array(
			'id'          => 'yerevan',
			'filter_id'   => 'resort-yerevan',
			'guide_url'   => zhiraf_ufa_armeniya_resort_landing_url( 'yerevan' ),
			'guide_label' => $guide,
			'name'        => __( 'Ереван', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Город + выезды на день', 'zhiraf-ufa' ),
			'tag'         => __( 'Парам и первый визит', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( 'Логистический хаб', 'zhiraf-ufa' ),
			'featured'    => true,
			'hotel_count' => $counts['yerevan'] ?? 0,
			'accent_mod'  => 'emerald',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Сарьяна, Каскад, гастрономия и джаз — без долгих переездов', 'zhiraf-ufa' ) ),
				array( 'type' => 'pro', 'text' => __( 'База для Гарни, Гегарда и винных маршрутов', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Летом в городе жарко — лучше весна и осень', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'dilijan',
			'filter_id'   => 'resort-dilijan',
			'guide_url'   => zhiraf_ufa_armeniya_resort_landing_url( 'dilijan' ),
			'guide_label' => $guide,
			'name'        => __( 'Дилижан', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Тишина в лесу', 'zhiraf-ufa' ),
			'tag'         => __( 'Семьям и природе', 'zhiraf-ufa' ),
			'tag_mod'     => 'nature',
			'transfer'    => __( '1–2 ночи в программе', 'zhiraf-ufa' ),
			'hotel_count' => $counts['dilijan'] ?? 0,
			'accent_mod'  => 'teal',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Нацпарк, трекинг и прохладнее, чем в Ереване', 'zhiraf-ufa' ) ),
				array( 'type' => 'pro', 'text' => __( 'Хорошо совмещать с Севаном в одном маршруте', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Не подойдёт, если нужен ночной город', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'sevan',
			'filter_id'   => 'resort-sevan',
			'guide_url'   => zhiraf_ufa_armeniya_resort_landing_url( 'sevan' ),
			'guide_label' => $guide,
			'name'        => __( 'Севан', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Озеро и монастыри', 'zhiraf-ufa' ),
			'tag'         => __( 'Короткая перезагрузка', 'zhiraf-ufa' ),
			'tag_mod'     => 'heritage',
			'transfer'    => __( 'Часто как выезд из Еревана', 'zhiraf-ufa' ),
			'hotel_count' => $counts['sevan'] ?? 0,
			'accent_mod'  => 'indigo',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Севанаванк, виды на воду, севанский ишхан', 'zhiraf-ufa' ) ),
				array( 'type' => 'pro', 'text' => __( 'Идеально добавить 1–2 ночи к городскому туру', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'На озере ветрено — закладываем тёплую одежду', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'tsaghkadzor',
			'filter_id'   => 'resort-tsaghkadzor',
			'guide_url'   => zhiraf_ufa_armeniya_resort_landing_url( 'tsaghkadzor' ),
			'guide_label' => $guide,
			'name'        => __( 'Цахкадзор', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Горнолыжный курорт', 'zhiraf-ufa' ),
			'tag'         => __( 'Активный зимний отдых', 'zhiraf-ufa' ),
			'tag_mod'     => 'active',
			'transfer'    => __( 'Декабрь — март', 'zhiraf-ufa' ),
			'hotel_count' => $counts['tsaghkadzor'] ?? 0,
			'accent_mod'  => 'slate',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Лыжи, канатки и SPA после трасс', 'zhiraf-ufa' ) ),
				array( 'type' => 'pro', 'text' => __( 'Подходит парам и компаниям друзей', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Летом — горный воздух, но без катания', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'jermuk',
			'filter_id'   => 'resort-jermuk',
			'guide_url'   => zhiraf_ufa_armeniya_resort_landing_url( 'jermuk' ),
			'guide_label' => $guide,
			'name'        => __( 'Джермук', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Курортная вода и SPA', 'zhiraf-ufa' ),
			'tag'         => __( 'Оздоровление', 'zhiraf-ufa' ),
			'tag_mod'     => 'urban',
			'transfer'    => __( 'Неспешный ритм', 'zhiraf-ufa' ),
			'hotel_count' => $counts['jermuk'] ?? 0,
			'accent_mod'  => 'amber',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Питьевая галерея, процедуры, минимум суеты', 'zhiraf-ufa' ) ),
				array( 'type' => 'pro', 'text' => __( 'Чаще выбирают 35+ и родителей', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Дальше от аэропорта — закладываем длинный трансфер', 'zhiraf-ufa' ) ),
			),
		),
	);
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_armeniya_expert_nuances() {
	return array(
		array( 'icon' => 'hotel', 'title' => __( '25 отелей в базе', 'zhiraf-ufa' ), 'text' => __( 'Топ-6 на старте, фильтры и «Показать ещё» — весь эталонный пул.', 'zhiraf-ufa' ) ),
		array( 'icon' => 'passport', 'title' => __( 'Безвиз', 'zhiraf-ufa' ), 'text' => __( 'Паспорт РФ — въезд без визы.', 'zhiraf-ufa' ) ),
		array( 'icon' => 'wine', 'title' => __( 'Гастрономия', 'zhiraf-ufa' ), 'text' => __( 'Бронируем столики и маршруты с дегустациями.', 'zhiraf-ufa' ) ),
		array( 'icon' => 'mountain', 'title' => __( 'Севан', 'zhiraf-ufa' ), 'text' => __( '1–2 ночи у озера — закладываем в пакет.', 'zhiraf-ufa' ) ),
	);
}
