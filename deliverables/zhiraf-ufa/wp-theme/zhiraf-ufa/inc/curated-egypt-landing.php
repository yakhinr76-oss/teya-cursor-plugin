<?php
/**
 * Egypt (/tour/egipet/) curated landing — 6 starters + pool from egypt_hotels_25.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_egypt_hub_min_price_rub() {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'egypt' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_egypt_base_price_rub', 155000 );
}

/**
 * @return string
 */
function zhiraf_ufa_egypt_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'egypt' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_egypt_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_egypt_hotels_json_path();
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}

/**
 * Resort filter keys for a JSON row (sharm, hurghada, makadi, sahl).
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_egypt_resort_keys_from_row( array $row ) {
	$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$resort = ! empty( $row['resort'] ) ? mb_strtolower( (string) $row['resort'], 'UTF-8' ) : '';
	$keys   = array();

	if ( 'sharm' === $region ) {
		$keys[] = 'sharm';
	}
	if ( 'hurghada' === $region ) {
		$keys[] = 'hurghada';
		if ( false !== mb_strpos( $resort, 'макади', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'makadi', 0, 'UTF-8' ) ) {
			$keys[] = 'makadi';
		}
		if ( false !== mb_strpos( $resort, 'сахл', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'sahl', 0, 'UTF-8' ) ) {
			$keys[] = 'sahl';
		}
	}

	return array_values( array_unique( $keys ) );
}

/**
 * @param string $resort_key sharm|hurghada|makadi|sahl.
 * @return string
 */
function zhiraf_ufa_egypt_resort_landing_url( $resort_key ) {
	$resort_key = sanitize_key( (string) $resort_key );
	$map        = array(
		'hurghada' => 'hurgada',
		'sharm'    => 'sharm-el-sheyh',
	);
	if ( empty( $map[ $resort_key ] ) ) {
		return '';
	}

	return home_url( '/tour/egipet/' . $map[ $resort_key ] . '/' );
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_egypt_catalog_fill_resort_labels() {
	return array(
		'resort-hurghada' => __( 'Хургаде', 'zhiraf-ufa' ),
		'resort-sharm'    => __( 'Шарм-эль-Шейхе', 'zhiraf-ufa' ),
		'resort-makadi'   => __( 'Макади-Бее', 'zhiraf-ufa' ),
		'resort-sahl'     => __( 'Сахл-Хашише', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_egypt_need_tag_from_category( $category_slug ) {
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
		case 'balance':
		default:
			return 'need-price';
	}
}

/**
 * @param string $category_slug Category slug.
 * @return array{badge:string,badge_mod:string}
 */
function zhiraf_ufa_egypt_pool_need_badge( $category_slug ) {
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
			'badge'     => __( 'Город Хургада', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
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
function zhiraf_ufa_egypt_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 168000;
		case 'couples':
			return 198000;
		case 'premium':
			return 248000;
		case 'urban':
			return 152000;
		case 'balance':
		default:
			return 162000;
	}
}

/**
 * @param string $name Hotel title.
 * @param string $cat  Category slug.
 * @return string
 */
function zhiraf_ufa_egypt_pool_meal_concept( $name, $cat ) {
	$name  = (string) $name;
	$cat   = sanitize_key( (string) $cat );
	$stars = '';
	if ( preg_match( '/\b(\d)\s*★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}
	if ( 'premium' === $cat ) {
		return trim( $stars . ' ' . __( 'AI / HB по отелю', 'zhiraf-ufa' ) );
	}
	if ( 'urban' === $cat ) {
		return trim( $stars . ' ' . __( 'All Inclusive у моря', 'zhiraf-ufa' ) );
	}

	return trim( $stars . ' All Inclusive' );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_egypt_pool_card_usp( $row ) {
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
function zhiraf_ufa_egypt_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_egypt_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );

		return trim( $parts[0] );
	}
	$keys = zhiraf_ufa_egypt_resort_keys_from_row( $row );
	if ( in_array( 'sharm', $keys, true ) ) {
		return __( 'Шарм-эль-Шейх', 'zhiraf-ufa' );
	}

	return __( 'Хургада', 'zhiraf-ufa' );
}

/**
 * Unique theme fallbacks so six starters do not share one stock photo.
 *
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_egypt_image_from_json_row( array $row ) {
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
		foreach ( array( 'hotels/egypt/' . $first, $first ) as $rel ) {
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
		1  => 'resort-egipet-sharm-2.jpg',
		3  => 'resort-egipet-hurghada-1.jpg',
		4  => 'resort-egipet-hurghada-2.jpg',
		6  => 'resort-egipet-sharm-1.jpg',
		13 => 'resort-egipet-el-gouna-2.jpg',
		21 => 'resort-egipet-marsa-alam-2.jpg',
	);
	$region_fb    = array(
		'sharm' => 'resort-egipet-sharm-2.jpg',
		'hurghada' => 'resort-egipet-hurghada-1.jpg',
	);
	$fallback     = ! empty( $id_fallbacks[ $pool_id ] ) ? $id_fallbacks[ $pool_id ] : 'hero-egypt.jpg';
	$region       = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	if ( isset( $region_fb[ $region ] ) && 'hero-egypt.jpg' === $fallback ) {
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
function zhiraf_ufa_egypt_starter_definitions() {
	return array(
		array( 'json_id' => 1, 'slot' => 'base' ),
		array( 'json_id' => 3, 'slot' => 'family' ),
		array( 'json_id' => 4, 'slot' => 'family-makadi' ),
		array( 'json_id' => 6, 'slot' => 'couples' ),
		array( 'json_id' => 13, 'slot' => 'premium' ),
		array( 'json_id' => 21, 'slot' => 'balance' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_egypt_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'family', 'family-makadi' ), true ) ) {
		$tags[] = 'need-family';
	} elseif ( 'couples' === $slot ) {
		$tags[] = 'need-adults';
	} elseif ( 'premium' === $slot ) {
		$tags[] = 'need-premium';
	} else {
		$tags[] = 'need-price';
	}

	return array_values( array_unique( $tags ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_egypt_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_egypt_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_egypt_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_egypt_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_egypt_image_from_json_row( $row );

	$card = array(
		'destination_slug' => 'egipet',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'hurghada',
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
			__( 'Отель %s, Египет', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_egypt_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_egypt_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_egypt_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_egypt_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_egypt_pool_price_rub( $row ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_egypt_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_egypt_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_egypt_card( $card );
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
		$tour = zhiraf_ufa_get_country_min_tour( 'egypt' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'egypt', $name, $bg_hotel_id );
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
		$card['price_rub']             = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_egypt_hub_min_price_rub() : 0 );
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
function zhiraf_ufa_egypt_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'EG-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'EG-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'EG-STARTER-' . $slot;
	}

	return 'EG-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_egypt_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_egypt_curated_uon_hotel_id( $card );
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
function zhiraf_ufa_egypt_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_egypt_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_egypt_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_egypt_json_row_to_card( $row );
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
		$card['filter_tags'] = zhiraf_ufa_egypt_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по Египту', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_egypt_finalize_catalog_card( zhiraf_ufa_egypt_apply_bg_pricing( $card ) );
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
		$card = zhiraf_ufa_egypt_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_egypt_finalize_catalog_card( zhiraf_ufa_egypt_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_egypt_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_egypt_catalog_filters() {
	return array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Туры в Египет: 6 проверенных отелей', 'zhiraf-ufa' ),
			'desc'  => __( 'Семьи, пары, премиум и разумный All Inclusive — Хургада и Шарм с вылетом из Уфы. Цены — ориентир за двоих с перелётом.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-hurghada',
			'group' => 'resort',
			'label' => __( '🏖 Хургада', 'zhiraf-ufa' ),
			'title' => __( 'Отели Хургады', 'zhiraf-ufa' ),
			'desc'  => __( 'Песчаный заход, аквапарки и спокойное море — до 6 лучших из базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-sharm',
			'group' => 'resort',
			'label' => __( '🤿 Шарм', 'zhiraf-ufa' ),
			'title' => __( 'Отели Шарм-эль-Шейха', 'zhiraf-ufa' ),
			'desc'  => __( 'Кораллы, бухты и зимний штиль — проверенные отели из базы 25.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-makadi',
			'group' => 'resort',
			'label' => __( '👶 Макади', 'zhiraf-ufa' ),
			'title' => __( 'Отели Макади-Бей', 'zhiraf-ufa' ),
			'desc'  => __( 'Песчаный залив и аквапарки для семей — отбор из нашей базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-sahl',
			'group' => 'resort',
			'label' => __( '✨ Сахл', 'zhiraf-ufa' ),
			'title' => __( 'Отели Сахл-Хашиш', 'zhiraf-ufa' ),
			'desc'  => __( 'Уединённый люкс у рифа — лучшие варианты из базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-family',
			'group' => 'need',
			'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ),
			'title' => __( 'Семейные отели', 'zhiraf-ufa' ),
			'desc'  => __( 'Пологий песок, аквапарки и детские клубы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-adults',
			'group' => 'need',
			'label' => __( '🥂 Для пар', 'zhiraf-ufa' ),
			'title' => __( 'Отели для пар', 'zhiraf-ufa' ),
			'desc'  => __( 'Рифы, 16+ и романтические бухты.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-premium',
			'group' => 'need',
			'label' => __( '💎 Премиум', 'zhiraf-ufa' ),
			'title' => __( 'Премиум-отели', 'zhiraf-ufa' ),
			'desc'  => __( 'Люкс, сьюты и высокий сервис у моря.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-price',
			'group' => 'need',
			'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ),
			'title' => __( 'Хиты «цена / качество»', 'zhiraf-ufa' ),
			'desc'  => __( 'Честный All Inclusive без переплаты за бренд.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_egypt_catalog_default_copy() {
	$filters = zhiraf_ufa_egypt_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в Египте', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Подбираем под задачу: песок Хургады, рифы Шарма или люкс Сахл-Хашиш. Цены — за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_egypt_resort_hotel_counts() {
	$counts = array(
		'hurghada' => 0,
		'sharm'    => 0,
		'makadi'   => 0,
		'sahl'     => 0,
	);
	foreach ( zhiraf_ufa_egypt_catalog_hotels() as $hotel ) {
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
function zhiraf_ufa_egypt_resort_chips() {
	return array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-hurghada', 'label' => __( 'Хургада — песок', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-sharm', 'label' => __( 'Шарм — рифы', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-makadi', 'label' => __( 'Макади — семьи', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-sahl', 'label' => __( 'Сахл-Хашиш — люкс', 'zhiraf-ufa' ) ),
	);
}

/**
 * Four resort pick cards (2×2 grid).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_egypt_resort_pick_cards() {
	$counts     = zhiraf_ufa_egypt_resort_hotel_counts();
	$guide_here = __( 'На страницу →', 'zhiraf-ufa' );

	return array(
		array(
			'id'          => 'hurghada',
			'filter_id'   => 'resort-hurghada',
			'guide_url'   => zhiraf_ufa_egypt_resort_landing_url( 'hurghada' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Хургада', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Песок и мелководье', 'zhiraf-ufa' ),
			'tag'         => __( 'Семейный хит', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( '~5–5,5 ч из Уфы', 'zhiraf-ufa' ),
			'featured'    => true,
			'hotel_count' => $counts['hurghada'],
			'accent_mod'  => 'emerald',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Пологий песчаный заход без коралловой обуви', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Экскурсии в Луксор и на Нил', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'sharm',
			'filter_id'   => 'resort-sharm',
			'guide_url'   => zhiraf_ufa_egypt_resort_landing_url( 'sharm' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Шарм-эль-Шейх', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Рифы и снорклинг', 'zhiraf-ufa' ),
			'tag'         => __( 'Зимний штиль', 'zhiraf-ufa' ),
			'tag_mod'     => 'nature',
			'transfer'    => __( 'Sinai Only до 15 дней', 'zhiraf-ufa' ),
			'transfer_mod' => 'fast',
			'featured'    => false,
			'hotel_count' => $counts['sharm'],
			'accent_mod'  => 'teal',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Живые кораллы у понтонов', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Безветренные бухты зимой', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'makadi',
			'filter_id'   => 'resort-makadi',
			'guide_url'   => zhiraf_ufa_egypt_resort_landing_url( 'makadi' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Макади-Бей', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Аквапарк и песок', 'zhiraf-ufa' ),
			'tag'         => __( 'С детьми', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( '35 км от Хургады', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['makadi'],
			'accent_mod'  => 'amber',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Закрытый залив без сильных волн', 'zhiraf-ufa' ) ),
				array( 'type' => 'pro', 'text' => __( 'Крупные семейные резорты AI', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'sahl',
			'filter_id'   => 'resort-sahl',
			'guide_url'   => zhiraf_ufa_egypt_resort_landing_url( 'sahl' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Сахл-Хашиш', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Люкс у рифа', 'zhiraf-ufa' ),
			'tag'         => __( 'Премиум', 'zhiraf-ufa' ),
			'tag_mod'     => 'premium',
			'transfer'    => __( '20 мин от HRG', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['sahl'],
			'accent_mod'  => 'lux',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Тихие сьюты и приватные пляжи', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Меньше суеты, чем в центре Хургады', 'zhiraf-ufa' ) ),
			),
		),
	);
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_egypt_expert_nuances() {
	return array(
		array(
			'icon'  => '🩴',
			'title' => __( 'Песок или понтон', 'zhiraf-ufa' ),
			'text'  => __( 'В Хургаде и Макади чаще натуральный песок; в Шарме вход с понтона на риф. Заранее говорим, нужны ли аквашузы детям и как далеко от номера до пляжа.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🛂',
			'title' => __( 'Виза и Sinai Only', 'zhiraf-ufa' ),
			'text'  => __( 'В HRG — марка $25 на 30 дней. В Шарме до 15 дней на Синае можно Sinai Only без визы, если не едете в Каир. Учитываем маршрут до брони.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '💨',
			'title' => __( 'Зимние ветра', 'zhiraf-ufa' ),
			'text'  => __( 'С декабря по февраль на открытых пляжах Хургады бывает ветер — подбираем лагуны, подогреваемые бассейны и бухты Шарма (Наама, Рас-Ум-Сид).', 'zhiraf-ufa' ),
		),
	);
}