<?php
/**
 * UAE (/tour/oae/) curated landing — 6 starters + pool from uae_hotels_25.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_oae_hub_min_price_rub() {
	$min = function_exists( 'zhiraf_ufa_home_hub_min_tour' )
		? zhiraf_ufa_home_hub_min_tour( 'oae', 'ufa' )
		: null;
	if ( $min && ! empty( $min['price_rur'] ) ) {
		return (int) $min['price_rur'];
	}
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$row = zhiraf_ufa_get_country_min_tour( 'oae' );
		if ( is_array( $row ) && ! empty( $row['price_rur'] ) ) {
			return (int) $row['price_rur'];
		}
	}

	return (int) apply_filters( 'zhiraf_ufa_oae_base_price_rub', 185000 );
}

/**
 * @return string
 */
function zhiraf_ufa_uae_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'uae' )
		: '';
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
 * @param string $emirate JSON emirate key.
 * @return string Filter id suffix dubai|rak|abu-dhabi|sharjah|ajman.
 */
function zhiraf_ufa_oae_emirate_key( $emirate ) {
	$e = sanitize_key( (string) $emirate );
	$map = array(
		'ras_al_khaimah' => 'rak',
		'abu_dhabi'      => 'abu-dhabi',
		'sharjah'        => 'sharjah',
		'dubai'          => 'dubai',
		'ajman'          => 'ajman',
	);

	return ! empty( $map[ $e ] ) ? (string) $map[ $e ] : str_replace( '_', '-', $e );
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_oae_need_tag_from_category( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	switch ( $category_slug ) {
		case 'family':
			return 'need-family';
		case 'couples':
			return 'need-adults';
		case 'urban':
			return 'need-urban';
		case 'apartments':
			return 'need-apartments';
		case 'balance':
		default:
			return 'need-price';
	}
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return int
 */
function zhiraf_ufa_oae_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 198000;
		case 'couples':
			return 228000;
		case 'urban':
			return 168000;
		case 'apartments':
			return 178000;
		case 'balance':
		default:
			return 188000;
	}
}

/**
 * @param string $name   Hotel title.
 * @param string $cat    Category slug.
 * @return string
 */
function zhiraf_ufa_oae_pool_meal_concept( $name, $cat ) {
	$name = (string) $name;
	$cat  = sanitize_key( (string) $cat );
	$stars = '';
	if ( preg_match( '/\b(\d)\s*★/u', $name, $m ) ) {
		$stars = $m[1] . '★';
	}
	if ( 'family' === $cat && false !== mb_stripos( $name, 'all inclusive', 0, 'UTF-8' ) ) {
		return trim( $stars . ' All Inclusive' );
	}
	if ( 'urban' === $cat || 'apartments' === $cat ) {
		return trim( $stars . ' ' . __( 'Город, завтраки', 'zhiraf-ufa' ) );
	}
	if ( preg_match( '/all inclusive|AI/i', $name ) ) {
		return trim( $stars . ' AI' );
	}

	return $stars ? $stars . ' BB' : __( 'Завтраки', 'zhiraf-ufa' );
}

/**
 * @param string $category_slug JSON category.
 * @return array{badge:string,badge_mod:string}
 */
function zhiraf_ufa_oae_pool_need_badge( $category_slug ) {
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
	if ( 'urban' === $category_slug ) {
		return array(
			'badge'     => __( 'Город и метро', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
		);
	}
	if ( 'apartments' === $category_slug ) {
		return array(
			'badge'     => __( 'Апартаменты', 'zhiraf-ufa' ),
			'badge_mod' => 'value',
		);
	}

	return array(
		'badge'     => __( 'Хит цена/качество', 'zhiraf-ufa' ),
		'badge_mod' => 'value',
	);
}

/**
 * @param string              $emirate_key Region key.
 * @param string              $resort_raw  JSON resort line.
 * @param array<string,mixed> $row         JSON row.
 * @return string
 */
function zhiraf_ufa_oae_pool_resort_pill( $emirate_key, $resort_raw, $row ) {
	$labels = array(
		'dubai'     => __( 'Дубай', 'zhiraf-ufa' ),
		'rak'       => __( 'Рас-эль-Хайма', 'zhiraf-ufa' ),
		'abu-dhabi' => __( 'Абу-Даби', 'zhiraf-ufa' ),
		'sharjah'   => __( 'Шарджа', 'zhiraf-ufa' ),
		'ajman'     => __( 'Аджман', 'zhiraf-ufa' ),
	);
	$resort_label = ! empty( $labels[ $emirate_key ] ) ? $labels[ $emirate_key ] : wp_trim_words( (string) $resort_raw, 4, '…' );
	$cat          = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : '';
	if ( 'family' === $cat ) {
		$suffix = __( 'Пляж / AI', 'zhiraf-ufa' );
	} elseif ( 'urban' === $cat ) {
		$suffix = __( 'Центр', 'zhiraf-ufa' );
	} else {
		$suffix = wp_trim_words( (string) ( $row['category_name'] ?? '' ), 3, '…' );
	}

	return $resort_label . ' • ' . $suffix;
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_oae_pool_card_usp( $row ) {
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
function zhiraf_ufa_oae_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 18, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_oae_image_from_json_row( array $row ) {
	$emirate = zhiraf_ufa_oae_emirate_key( ! empty( $row['emirate'] ) ? (string) $row['emirate'] : '' );
	$photos  = ! empty( $row['photos'] ) && is_array( $row['photos'] ) ? $row['photos'] : array();
	$first   = isset( $photos[0] ) ? trim( (string) $photos[0] ) : '';
	$width   = ! empty( $row['lead_photo_width'] ) ? (int) $row['lead_photo_width'] : 0;
	$height  = ! empty( $row['lead_photo_height'] ) ? (int) $row['lead_photo_height'] : 0;

	if ( preg_match( '#^https://#i', $first ) ) {
		if ( $width > 1200 && $height > 0 ) {
			$height = (int) max( 1, round( $height * 1200 / $width ) );
			$width  = 1200;
		}
		$url = function_exists( 'zhiraf_ufa_turciya_display_photo_url' )
			? zhiraf_ufa_turciya_display_photo_url( $first )
			: $first;

		return array(
			'image'        => $url,
			'image_remote' => true,
			'image_width'  => $width,
			'image_height' => $height,
		);
	}

	if ( '' !== $first ) {
		$base = basename( $first );
		foreach ( array( 'hotels/uae/' . $base, $base, 'country-oae.jpg', 'dest-uae.jpg' ) as $rel ) {
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

	$fallback = 'dubai' === $emirate ? 'dest-uae.jpg' : 'country-oae.jpg';

	return array(
		'image'        => $fallback,
		'image_remote' => false,
		'image_width'  => 0,
		'image_height' => 0,
	);
}

/**
 * Starter showcase rows: JSON id + slot (6 scenarios).
 *
 * @return array<int,array{json_id:int,slot:string}>
 */
function zhiraf_ufa_oae_starter_definitions() {
	return array(
		array(
			'json_id' => 1,
			'slot'    => 'base',
		),
		array(
			'json_id' => 2,
			'slot'    => 'family',
		),
		array(
			'json_id' => 6,
			'slot'    => 'balance',
		),
		array(
			'json_id' => 11,
			'slot'    => 'urban',
		),
		array(
			'json_id' => 16,
			'slot'    => 'couples',
		),
		array(
			'json_id' => 21,
			'slot'    => 'apartments',
		),
	);
}

/**
 * @param string $name Hotel title.
 * @return string
 */
function zhiraf_ufa_oae_hotel_name_key( $name ) {
	if ( function_exists( 'zhiraf_ufa_turciya_hotel_name_key' ) ) {
		return zhiraf_ufa_turciya_hotel_name_key( $name );
	}
	$name = remove_accents( wp_strip_all_tags( (string) $name ) );
	$name = mb_strtolower( $name, 'UTF-8' );
	$name = preg_replace( '/[^\p{L}\p{N}]+/u', '', $name );

	return $name;
}

/**
 * @param array<string,mixed> $hotel Card with slot / emirate.
 * @return array<int,string>
 */
function zhiraf_ufa_oae_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	$emirate = ! empty( $hotel['emirate'] ) ? zhiraf_ufa_oae_emirate_key( (string) $hotel['emirate'] ) : '';
	if ( '' !== $emirate ) {
		$tags[] = 'emirate-' . $emirate;
	}
	$cat = ! empty( $hotel['category_slug'] ) ? (string) $hotel['category_slug'] : '';
	if ( '' !== $cat ) {
		$tags[] = zhiraf_ufa_oae_need_tag_from_category( $cat );
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( 'family' === $slot ) {
		$tags[] = 'need-family';
	} elseif ( 'couples' === $slot ) {
		$tags[] = 'need-adults';
	} elseif ( 'urban' === $slot ) {
		$tags[] = 'need-urban';
	} elseif ( in_array( $slot, array( 'base', 'balance', 'apartments' ), true ) ) {
		$tags[] = 'need-price';
	}

	return array_values( array_unique( $tags ) );
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_oae_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name         = (string) $row['hotel_name'];
	$emirate_key  = zhiraf_ufa_oae_emirate_key( ! empty( $row['emirate'] ) ? (string) $row['emirate'] : '' );
	$cat          = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$tags         = array( 'emirate-' . $emirate_key, zhiraf_ufa_oae_need_tag_from_category( $cat ) );
	$need_badge   = zhiraf_ufa_oae_pool_need_badge( $cat );
	$resort_raw   = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	$pool_id      = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image        = zhiraf_ufa_oae_image_from_json_row( $row );

	return array(
		'destination_slug' => 'oae',
		'emirate'          => ! empty( $row['emirate'] ) ? (string) $row['emirate'] : '',
		'resort'           => $emirate_key,
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
			__( 'Отель %s, ОАЭ', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_oae_pool_resort_pill( $emirate_key, $resort_raw, $row ),
		'subtitle'         => zhiraf_ufa_oae_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_oae_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_oae_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_oae_pool_price_rub( $row ),
	);
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

	$editorial   = (int) ( $card['price_rub'] ?? 0 );
	$is_base     = ! empty( $card['slot'] ) && 'base' === $card['slot'];
	$bg_hotel_id = ! empty( $card['bg_hotel_id'] ) ? trim( (string) $card['bg_hotel_id'] ) : '';

	$tour        = null;
	$price_scope = '';

	if ( $is_base && function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$tour = zhiraf_ufa_get_country_min_tour( 'oae' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'oae', $name, $bg_hotel_id );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'hotel';
		} else {
			$tour = null;
		}
	}

	if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
		$card['price_rub']             = (int) $tour['price_rur'];
		$card['price_from_api']        = true;
		$card['price_scope']           = $price_scope;
		$card['tour_offer']            = $tour;
		$card['price_api_matched']     = ( 'hotel' === $price_scope );
		if ( ! empty( $tour['hotel_name'] ) ) {
			$card['bg_hotel_name'] = (string) $tour['hotel_name'];
		}
		if ( ! empty( $tour['hotel_id'] ) ) {
			$card['bg_hotel_id'] = (string) $tour['hotel_id'];
		}
		if ( function_exists( 'zhiraf_ufa_turciya_curated_messenger_prefill' ) ) {
			$card['wa_prefill'] = zhiraf_ufa_turciya_curated_messenger_prefill( $name, 'hotel' === $price_scope ? $tour : null );
		}
	} else {
		$card['price_rub']               = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_oae_hub_min_price_rub() : 0 );
		$card['price_from_api']          = false;
		$card['price_api_matched']       = false;
		$card['price_api_unavailable']   = ! $is_base;
		unset( $card['tour_offer'], $card['bg_hotel_name'], $card['price_scope'] );
	}

	return $card;
}

/**
 * @param array<string,mixed> $card Card row.
 * @return string
 */
function zhiraf_ufa_oae_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'UAE-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'UAE-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'UAE-STARTER-' . $slot;
	}

	return 'UAE-HOTEL';
}

/**
 * @param string $text Message.
 * @return array{whatsapp:string,max:string,telegram:string}
 */
function zhiraf_ufa_oae_messenger_channel_urls( $text ) {
	$text    = trim( (string) $text );
	$wa_base = function_exists( 'zhiraf_ufa_brand_whatsapp_url' ) ? zhiraf_ufa_brand_whatsapp_url() : 'https://wa.me/79018105018';
	$wa      = function_exists( 'zhiraf_ufa_whatsapp_url_with_text' )
		? zhiraf_ufa_whatsapp_url_with_text( $wa_base, $text )
		: $wa_base;
	$page    = function_exists( 'home_url' ) ? home_url( '/tour/oae/' ) : 'https://zhiraf-ufa.ru/tour/oae/';

	return array(
		'whatsapp' => $wa,
		'max'      => 'https://max.ru/:share?text=' . rawurlencode( $text ),
		'telegram' => 'https://t.me/share/url?url=' . rawurlencode( $page ) . '&text=' . rawurlencode( $text ),
	);
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_oae_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_oae_curated_uon_hotel_id( $card );
	$tour                 = null;
	if ( ! empty( $card['price_api_matched'] ) && ! empty( $card['tour_offer'] ) && is_array( $card['tour_offer'] ) ) {
		$tour = $card['tour_offer'];
	}
	$name = ! empty( $card['name'] ) ? (string) $card['name'] : '';
	if ( function_exists( 'zhiraf_ufa_turciya_curated_messenger_prefill' ) ) {
		$card['wa_prefill'] = zhiraf_ufa_turciya_curated_messenger_prefill( $name, $tour, $card['uon_hotel_id'] );
	}
	$card['messenger'] = zhiraf_ufa_oae_messenger_channel_urls( $card['wa_prefill'] ?? '' );

	return $card;
}

/**
 * Curated hotel cards for OAE hub (6 starters + JSON pool).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_oae_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_oae_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_oae_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_oae_json_row_to_card( $row );
		if ( ! $card ) {
			continue;
		}
		$slot = ! empty( $def['slot'] ) ? sanitize_key( (string) $def['slot'] ) : 'starter';
		$name_key = zhiraf_ufa_oae_hotel_name_key( (string) $card['name'] );
		$starter_keys[] = $name_key;

		$card['slot']       = $slot;
		$card['pool_id']    = 'starter-' . $slot;
		$card['pool_rank']  = $rank;
		$card['filter_tags'] = zhiraf_ufa_oae_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']       = __( 'Старт по ОАЭ', 'zhiraf-ufa' );
			$card['badge_mod']   = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_oae_finalize_catalog_card( zhiraf_ufa_oae_apply_bg_pricing( $card ) );
		++$rank;
	}

	$json_by_name = array();
	foreach ( $by_id as $json_row ) {
		$key = zhiraf_ufa_oae_hotel_name_key( ! empty( $json_row['hotel_name'] ) ? (string) $json_row['hotel_name'] : '' );
		if ( '' !== $key ) {
			$json_by_name[ $key ] = $json_row;
		}
	}

	foreach ( $json_by_name as $key => $json_row ) {
		if ( '' === $key || in_array( $key, $starter_keys, true ) ) {
			continue;
		}
		$card = zhiraf_ufa_oae_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_oae_finalize_catalog_card( zhiraf_ufa_oae_apply_bg_pricing( $card ) );
		}
	}

	/**
	 * @param array<int,array<string,mixed>> $hotels
	 */
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
			'desc'  => __( 'Семья, город, пары, апартаменты и баланс цены — с вылетом из Уфы. Цены ориентир за двоих с перелётом.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'emirate-dubai',
			'group' => 'resort',
			'label' => __( '🏙️ Дубай', 'zhiraf-ufa' ),
			'title' => __( 'Отели Дубая', 'zhiraf-ufa' ),
			'desc'  => __( 'Марина, JBR, Dubai Islands и город у метро — до 6 лучших из базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'emirate-rak',
			'group' => 'resort',
			'label' => __( '🏖️ Рас-эль-Хайма', 'zhiraf-ufa' ),
			'title' => __( 'Отели Рас-эль-Хаймы', 'zhiraf-ufa' ),
			'desc'  => __( 'Пляжи залива, All Inclusive и спокойный курорт — отбор из 25 отелей.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'emirate-sharjah',
			'group' => 'resort',
			'label' => __( '🌊 Шарджа', 'zhiraf-ufa' ),
			'title' => __( 'Отели Шарджи', 'zhiraf-ufa' ),
			'desc'  => __( 'Семейные пляжи рядом с Дубаем — проверенные варианты из базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'emirate-abu-dhabi',
			'group' => 'resort',
			'label' => __( '🕌 Абу-Даби', 'zhiraf-ufa' ),
			'title' => __( 'Отели Абу-Даби', 'zhiraf-ufa' ),
			'desc'  => __( 'Яс, культура и пляж — отели из редакционной базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'emirate-ajman',
			'group' => 'resort',
			'label' => __( '🏝 Аджман', 'zhiraf-ufa' ),
			'title' => __( 'Отели Аджмана', 'zhiraf-ufa' ),
			'desc'  => __( 'Спокойный пляж и доступные цены — из нашей базы 25 отелей.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-family',
			'group' => 'need',
			'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ),
			'title' => __( 'Семейные отели ОАЭ', 'zhiraf-ufa' ),
			'desc'  => __( 'Пляж, аквазоны, детские клубы и понятный трансфер из аэропорта.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-adults',
			'group' => 'need',
			'label' => __( '🥂 Для пар', 'zhiraf-ufa' ),
			'title' => __( 'Отели для пар', 'zhiraf-ufa' ),
			'desc'  => __( 'Тишина, вид на залив, романтика и взрослая атмосфера.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-urban',
			'group' => 'need',
			'label' => __( '🚇 Город', 'zhiraf-ufa' ),
			'title' => __( 'Городские отели', 'zhiraf-ufa' ),
			'desc'  => __( 'Метро, шопинг, рестораны — без обязательного пляжа у отеля.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-apartments',
			'group' => 'need',
			'label' => __( '🏠 Апартаменты', 'zhiraf-ufa' ),
			'title' => __( 'Апартаменты и резиденции', 'zhiraf-ufa' ),
			'desc'  => __( 'Кухня, простор и длительное проживание — для семей и удалёнщиков.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-price',
			'group' => 'need',
			'label' => __( '⚖️ Цена/качество', 'zhiraf-ufa' ),
			'title' => __( 'Хиты «цена / качество»', 'zhiraf-ufa' ),
			'desc'  => __( 'Сервис выше ожиданий относительно бюджета.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_oae_catalog_default_copy() {
	$filters = zhiraf_ufa_oae_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в ОАЭ', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Подбираем под задачу: город у метро, пляж JBR или All Inclusive в Рас-эль-Хайме. Цены — за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * @return string
 */
function zhiraf_ufa_oae_resort_compare_wa_text() {
	return __( 'Здравствуйте! Сравните, пожалуйста, Дубай и Рас-эль-Хайму под наш бюджет — вылет из Уфы.', 'zhiraf-ufa' );
}