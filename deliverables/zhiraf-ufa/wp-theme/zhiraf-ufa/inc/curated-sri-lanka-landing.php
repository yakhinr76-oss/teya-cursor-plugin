<?php
/**
 * Sri Lanka (/tour/shri-lanka/) curated landing v1 — 6 starters + pool from sri_lanka_hotels_25.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_sri_lanka_hub_min_price_rub() {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'shri-lanka' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_sri_lanka_base_price_rub', 198000 );
}

/**
 * @return string
 */
function zhiraf_ufa_sri_lanka_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'sri_lanka' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_sri_lanka_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_sri_lanka_hotels_json_path();
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}


/**
 * Resort filter keys for a JSON row (south_west, west_coast, east_coast).
 *
 * @param array<string,mixed> $row JSON row.
 * @return string[]
 */
function zhiraf_ufa_sri_lanka_resort_keys_from_row( array $row ) {
	$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$keys   = array();
	foreach ( array( 'south_west', 'west_coast', 'east_coast' ) as $allowed ) {
		if ( $region === $allowed ) {
			$keys[] = $allowed;
		}
	}

	return array_values( array_unique( $keys ) );
}

/**
 * Hub anchor — чипы ведут на якорь каталога /tour/shri-lanka/#dest-curated-hotels.
 *
 * @param string $resort_key south_west|west_coast|east_coast.
 * @return string
 */
function zhiraf_ufa_sri_lanka_resort_landing_url( $resort_key ) {
	unset( $resort_key );

	return home_url( '/tour/shri-lanka/#dest-curated-hotels' );
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_sri_lanka_catalog_fill_resort_labels() {
	return array(
		'resort-south_west' => __( 'юго-западе', 'zhiraf-ufa' ),
		'resort-west_coast' => __( 'Бентоте', 'zhiraf-ufa' ),
		'resort-east_coast' => __( 'Пасикуде', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_sri_lanka_need_tag_from_category( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	switch ( $category_slug ) {
		case 'family':
			return 'need-family';
		case 'couples':
			return 'need-adults';
		case 'bentota':
			return 'need-bentota';
		case 'active_lifestyle':
			return 'need-active';
		case 'balance':
		default:
			return 'need-price';
	}
}

/**
 * @param string $category_slug Category slug.
 * @return array{badge:string,badge_mod:string}
 */
function zhiraf_ufa_sri_lanka_pool_need_badge( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	if ( 'family' === $category_slug ) {
		return array(
			'badge'     => __( 'Семья и лагуна', 'zhiraf-ufa' ),
			'badge_mod' => 'family',
		);
	}
	if ( 'couples' === $category_slug ) {
		return array(
			'badge'     => __( 'Для пар', 'zhiraf-ufa' ),
			'badge_mod' => 'adults',
		);
	}
	if ( 'bentota' === $category_slug ) {
		return array(
			'badge'     => __( 'Бентота / Берувела', 'zhiraf-ufa' ),
			'badge_mod' => 'nature',
		);
	}
	if ( 'active_lifestyle' === $category_slug ) {
		return array(
			'badge'     => __( 'Актив и черепахи', 'zhiraf-ufa' ),
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
function zhiraf_ufa_sri_lanka_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 208000;
		case 'couples':
			return 228000;
		case 'bentota':
			return 218000;
		case 'active_lifestyle':
			return 198000;
		case 'balance':
		default:
			return 208000;
	}
}

/**
 * @param string $name Hotel title.
 * @param string $cat  Category slug.
 * @return string
 */
function zhiraf_ufa_sri_lanka_pool_meal_concept( $name, $cat ) {
	$name  = (string) $name;
	$cat   = sanitize_key( (string) $cat );
	$stars = '';
	if ( preg_match( '/\b(\d)\s*★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}
	if ( 'bentota' === $cat || 'balance' === $cat ) {
		return trim( $stars . ' ' . __( 'HB / «Всё включено»', 'zhiraf-ufa' ) );
	}
	if ( 'active_lifestyle' === $cat ) {
		return trim( $stars . ' ' . __( 'Завтраки + кафе на пляже', 'zhiraf-ufa' ) );
	}
	if ( 'family' === $cat ) {
		return trim( $stars . ' ' . __( 'Завтраки / HB для семьи', 'zhiraf-ufa' ) );
	}

	return trim( $stars . ' ' . __( 'HB для пар', 'zhiraf-ufa' ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_sri_lanka_pool_card_usp( $row ) {
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
function zhiraf_ufa_sri_lanka_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_sri_lanka_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );

		return trim( $parts[0] );
	}
	$keys   = zhiraf_ufa_sri_lanka_resort_keys_from_row( $row );
	$labels = array(
		'south_west'  => __( 'Юго-запад', 'zhiraf-ufa' ),
		'west_coast'  => __( 'Запад', 'zhiraf-ufa' ),
		'east_coast'  => __( 'Восток', 'zhiraf-ufa' ),
	);
	if ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {
		return $labels[ $keys[0] ];
	}

	return __( 'Шри-Ланка', 'zhiraf-ufa' );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_sri_lanka_image_from_json_row( array $row ) {
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
		foreach ( array( 'hotels/sri_lanka/' . $first, $first ) as $rel ) {
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
		1  => 'alt-shri-lanka-beach-1.jpg',
		3  => 'country-shri-lanka.jpg',
		6  => 'alt-shri-lanka-beach-2.jpg',
		11 => 'alt-shri-lanka-beach-1.jpg',
		16 => 'alt-shri-lanka-beach-2.jpg',
		21 => 'country-shri-lanka.jpg',
	);
	$region_fb    = array(
		'south_west' => 'alt-shri-lanka-beach-1.jpg',
		'west_coast' => 'alt-shri-lanka-beach-2.jpg',
		'east_coast' => 'country-shri-lanka.jpg',
	);
	$fallback     = ! empty( $id_fallbacks[ $pool_id ] ) ? $id_fallbacks[ $pool_id ] : 'country-shri-lanka.jpg';
	$region       = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	if ( isset( $region_fb[ $region ] ) && 'country-shri-lanka.jpg' === $fallback ) {
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
function zhiraf_ufa_sri_lanka_starter_definitions() {
	return array(
		array( 'json_id' => 1, 'slot' => 'base' ),
		array( 'json_id' => 3, 'slot' => 'family-east' ),
		array( 'json_id' => 6, 'slot' => 'couples' ),
		array( 'json_id' => 11, 'slot' => 'bentota' ),
		array( 'json_id' => 16, 'slot' => 'active' ),
		array( 'json_id' => 21, 'slot' => 'balance' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_sri_lanka_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'family', 'family-east', 'family-southwest' ), true ) ) {
		$tags[] = 'need-family';
	} elseif ( 'couples' === $slot ) {
		$tags[] = 'need-adults';
	} elseif ( 'bentota' === $slot ) {
		$tags[] = 'need-bentota';
	} elseif ( 'active' === $slot ) {
		$tags[] = 'need-active';
	} else {
		$tags[] = 'need-price';
	}

	return array_values( array_unique( $tags ) );
}

function zhiraf_ufa_sri_lanka_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_sri_lanka_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_sri_lanka_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_sri_lanka_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_sri_lanka_image_from_json_row( $row );

	$card = array(
		'destination_slug' => 'shri-lanka',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'south_west',
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
			__( 'Отель %s, Шри-Ланка', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_sri_lanka_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_sri_lanka_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_sri_lanka_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_sri_lanka_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_sri_lanka_pool_price_rub( $row ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_sri_lanka_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_sri_lanka_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_sri_lanka_card( $card );
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
		$tour = zhiraf_ufa_get_country_min_tour( 'shri-lanka' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'shri-lanka', $name, $bg_hotel_id );
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
		$card['price_rub']             = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_sri_lanka_hub_min_price_rub() : 0 );
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
function zhiraf_ufa_sri_lanka_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'SL-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'SL-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'SL-STARTER-' . $slot;
	}

	return 'SL-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_sri_lanka_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_sri_lanka_curated_uon_hotel_id( $card );
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
function zhiraf_ufa_sri_lanka_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_sri_lanka_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_sri_lanka_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_sri_lanka_json_row_to_card( $row );
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
		$card['filter_tags'] = zhiraf_ufa_sri_lanka_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по Шри-Ланке', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_sri_lanka_finalize_catalog_card( zhiraf_ufa_sri_lanka_apply_bg_pricing( $card ) );
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
		$card = zhiraf_ufa_sri_lanka_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_sri_lanka_finalize_catalog_card( zhiraf_ufa_sri_lanka_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_sri_lanka_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_sri_lanka_catalog_filters() {
	return array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Туры на Шри-Ланку: 6 проверенных отелей', 'zhiraf-ufa' ),
			'desc'  => __( 'Семьи на юго-западе и в Пасикуде, пары в Хиккадуве, Бентота, актив с черепахами и баланс цены — три побережья острова. Ориентир за двоих с перелётом (стыковка в Москве).', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-south_west',
			'group' => 'resort',
			'label' => __( '🌴 Юго-запад', 'zhiraf-ufa' ),
			'title' => __( 'Отели юго-запада', 'zhiraf-ufa' ),
			'desc'  => __( 'Унаватуна, Хиккадува, Мирисса — лагуны, серф и черепахи. Лучший сезон: декабрь — апрель.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-west_coast',
			'group' => 'resort',
			'label' => __( '🏝 Бентота', 'zhiraf-ufa' ),
			'title' => __( 'Западное побережье', 'zhiraf-ufa' ),
			'desc'  => __( 'Бентота и Берувела — широкий песок, река и резорты 4–5★ ближе к аэропорту.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-east_coast',
			'group' => 'resort',
			'label' => __( '🐚 Пасикуда', 'zhiraf-ufa' ),
			'title' => __( 'Восточное побережье', 'zhiraf-ufa' ),
			'desc'  => __( 'Мелководная бухта Пасикуда — идеальна для детей летом, когда на юго-западе муссон.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-family',
			'group' => 'need',
			'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ),
			'title' => __( 'Семейные отели', 'zhiraf-ufa' ),
			'desc'  => __( 'Закрытые бухты, пологий заход и детские бассейны.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-adults',
			'group' => 'need',
			'label' => __( '🥂 Для пар', 'zhiraf-ufa' ),
			'title' => __( 'Отели для пар', 'zhiraf-ufa' ),
			'desc'  => __( 'Бутики, закаты и тихие пляжи юго-запада.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-bentota',
			'group' => 'need',
			'label' => __( '🛶 Бентота', 'zhiraf-ufa' ),
			'title' => __( 'Бентота и Берувела', 'zhiraf-ufa' ),
			'desc'  => __( 'Коса между рекой и океаном, паром до отеля и резорты с HB.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-active',
			'group' => 'need',
			'label' => __( '🏄 Актив', 'zhiraf-ufa' ),
			'title' => __( 'Серф и черепахи', 'zhiraf-ufa' ),
			'desc'  => __( 'Хиккадува — серф-школы и утренние черепахи у берега.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-price',
			'group' => 'need',
			'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ),
			'title' => __( 'Хиты «цена / качество»', 'zhiraf-ufa' ),
			'desc'  => __( 'Честные 4–5★ у моря без переплаты за бренд.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_sri_lanka_catalog_default_copy() {
	$filters = zhiraf_ufa_sri_lanka_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей на Шри-Ланке', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Под задачу: юго-запад, Бентота или Пасикуда — с учётом сезона муссонов. Цены за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_sri_lanka_resort_hotel_counts() {
	$counts = array(
		'south_west' => 0,
		'west_coast' => 0,
		'east_coast' => 0,
	);
	foreach ( zhiraf_ufa_sri_lanka_catalog_hotels() as $hotel ) {
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
function zhiraf_ufa_sri_lanka_resort_chips() {
	return array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-south_west', 'label' => __( 'Юго-запад', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-west_coast', 'label' => __( 'Бентота', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-east_coast', 'label' => __( 'Пасикуда', 'zhiraf-ufa' ) ),
	);
}

/**
 * Three coast pick cards.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_sri_lanka_resort_pick_cards() {
	$counts     = zhiraf_ufa_sri_lanka_resort_hotel_counts();
	$guide_here = __( 'К отелям →', 'zhiraf-ufa' );

	return array(
		array(
			'id'          => 'south_west',
			'filter_id'   => 'resort-south_west',
			'guide_url'   => zhiraf_ufa_sri_lanka_resort_landing_url( 'south_west' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Юго-запад', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Унаватуна и Хиккадува', 'zhiraf-ufa' ),
			'tag'         => __( 'Сезон: дек–апр', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( '2–3 ч от Коломбо', 'zhiraf-ufa' ),
			'featured'    => true,
			'hotel_count' => $counts['south_west'],
			'accent_mod'  => 'emerald',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Лагуны и рифы — спокойное купание для детей', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Май–ноябрь — муссон; смотрим восток', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'west_coast',
			'filter_id'   => 'resort-west_coast',
			'guide_url'   => zhiraf_ufa_sri_lanka_resort_landing_url( 'west_coast' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Бентота', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Западное побережье', 'zhiraf-ufa' ),
			'tag'         => __( 'Ближе к аэропорту', 'zhiraf-ufa' ),
			'tag_mod'     => 'nature',
			'transfer'    => __( '1,5–2 ч от Коломбо', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['west_coast'],
			'accent_mod'  => 'teal',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Широкий песок, река Бентота и резорты 4–5★', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'В муссон — волнение моря', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'east_coast',
			'filter_id'   => 'resort-east_coast',
			'guide_url'   => zhiraf_ufa_sri_lanka_resort_landing_url( 'east_coast' ),
			'guide_label' => $guide_here,
			'name'        => __( 'Пасикуда', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Восточное побережье', 'zhiraf-ufa' ),
			'tag'         => __( 'Летний сезон', 'zhiraf-ufa' ),
			'tag_mod'     => 'adults',
			'transfer'    => __( '5–6 ч от аэропорта', 'zhiraf-ufa' ),
			'featured'    => false,
			'hotel_count' => $counts['east_coast'],
			'accent_mod'  => 'amber',
			'bullets'     => array(
				array( 'type' => 'pro', 'text' => __( 'Мелкая лагуна — безопасно для малышей', 'zhiraf-ufa' ) ),
				array( 'type' => 'info', 'text' => __( 'Лучше в мае–сентябре, когда юг штормит', 'zhiraf-ufa' ) ),
			),
		),
	);
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_sri_lanka_expert_nuances() {
	return array(
		array(
			'icon'  => '🛂',
			'title' => __( 'ETA и загранпаспорт', 'zhiraf-ufa' ),
			'text'  => __( 'Нужно электронное разрешение ETA до вылета и загранпаспорт с запасом срока. Условия для граждан РФ менялись — проверяем правила на дату поездки.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🌧',
			'title' => __( 'Два муссона — два побережья', 'zhiraf-ufa' ),
			'text'  => __( 'Юго-запад и юг лучше с декабря по апрель. Восток (Пасикуда) — примерно с мая по сентябрь. Если месяц не совпал — меняем побережье, а не отказываемся от острова.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🌊',
			'title' => __( 'Волны и купание', 'zhiraf-ufa' ),
			'text'  => __( 'На открытых пляжах бывают течения — купаемся в лагунах Унаватуны, Бентоты или Пасикуды. Фиксируем локацию отеля и сезон до оплаты.', 'zhiraf-ufa' ),
		),
	);
}