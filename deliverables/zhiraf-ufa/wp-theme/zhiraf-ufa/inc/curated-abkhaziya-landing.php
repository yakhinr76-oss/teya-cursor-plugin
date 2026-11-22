<?php
/**
 * Abkhazia (/tour/abkhaziya/) curated landing — 6 starters + pool from abkhazia_hotels_25.json (4 курорта).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return int RUB
 */
function zhiraf_ufa_abkhaziya_hub_min_price_rub() {
	static $cached = null;
	if ( null !== $cached ) {
		return $cached;
	}
	$min = 0;
	foreach ( zhiraf_ufa_abkhaziya_json_rows() as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$p = zhiraf_ufa_abkhaziya_pool_price_rub( $row );
		if ( $p > 0 && ( $min <= 0 || $p < $min ) ) {
			$min = $p;
		}
	}
	if ( $min <= 0 ) {
		$min = (int) apply_filters( 'zhiraf_ufa_abkhaziya_base_price_rub', 65000 );
	}
	$cached = $min;

	return $cached;
}

/**
 * @return string
 */
function zhiraf_ufa_abkhaziya_hotels_json_path() {
	return function_exists( 'zhiraf_ufa_hotels_json_path' )
		? zhiraf_ufa_hotels_json_path( 'abkhaziya' )
		: '';
}

/**
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_abkhaziya_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = zhiraf_ufa_abkhaziya_hotels_json_path();
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
function zhiraf_ufa_abkhaziya_resort_keys_from_row( array $row ) {
	$region = ! empty( $row['region'] ) ? sanitize_key( (string) $row['region'] ) : '';
	$keys   = array();
	foreach ( array( 'gagra', 'pitsunda', 'new_afon', 'sukhum' ) as $allowed ) {
		if ( $region === $allowed ) {
			$keys[] = $allowed;
		}
	}
	return $keys;
}

/**
 * Hub anchor — отдельных курортных URL для Абхазии нет (якорь каталога).
 *
 * @param string $resort_key phuket|pattaya|khaolak|samui.
 * @return string
 */
function zhiraf_ufa_abkhaziya_resort_landing_url( $resort_key ) {
	unset( $resort_key );

	return home_url( '/tour/abkhaziya/#dest-curated-hotels' );
}

/**
 * Prepositional labels for catalog fill copy.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_abkhaziya_catalog_fill_resort_labels() {
	return array(
		'resort-gagra'      => __( 'Гагре', 'zhiraf-ufa' ),
		'resort-pitsunda'   => __( 'Пицунде', 'zhiraf-ufa' ),
		'resort-new_afon'   => __( 'Новом Афоне', 'zhiraf-ufa' ),
		'resort-sukhum'     => __( 'Сухуме', 'zhiraf-ufa' ),
	);
}

/**
 * @param string $category_slug JSON category.
 * @return string need-* filter tag.
 */
function zhiraf_ufa_abkhaziya_need_tag_from_category( $category_slug ) {
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
function zhiraf_ufa_abkhaziya_pool_need_badge( $category_slug ) {
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
			'badge'     => __( 'Город / набережная', 'zhiraf-ufa' ),
			'badge_mod' => 'urban',
		);
	}
	if ( 'pattaya_beach' === $category_slug ) {
		return array(
			'badge'     => __( 'Первая линия', 'zhiraf-ufa' ),
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
function zhiraf_ufa_abkhaziya_pool_price_rub( $row ) {
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
function zhiraf_ufa_abkhaziya_pool_meal_concept( $name, $cat ) {
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
function zhiraf_ufa_abkhaziya_pool_card_usp( $row ) {
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
function zhiraf_ufa_abkhaziya_pool_card_caveat( $row ) {
	if ( ! empty( $row['caveat'] ) ) {
		return wp_trim_words( trim( (string) $row['caveat'] ), 22, '…' );
	}

	return '';
}

/**
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_abkhaziya_pool_resort_pill( array $row ) {
	$resort = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	if ( '' !== $resort ) {
		$parts = explode( ',', $resort );
		return trim( $parts[0] );
	}
	$keys = zhiraf_ufa_abkhaziya_resort_keys_from_row( $row );
	$labels = array(
		'gagra'      => __( 'Гагра', 'zhiraf-ufa' ),
		'pitsunda'   => __( 'Пицунда', 'zhiraf-ufa' ),
		'new_afon'   => __( 'Новый Афон', 'zhiraf-ufa' ),
		'sukhum'     => __( 'Сухум', 'zhiraf-ufa' ),
	);
	if ( ! empty( $keys[0] ) && isset( $labels[ $keys[0] ] ) ) {
		return $labels[ $keys[0] ];
	}
	return __( 'Абхазия', 'zhiraf-ufa' );
}

/**
 * Unique theme fallbacks so six starters do not share one stock photo.
 *
 * @param array<string,mixed> $row JSON row.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_abkhaziya_image_from_json_row( array $row ) {
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
			'hotels/abkhazia/' . $first,
			'hotels/abkhaziya/' . $first,
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
		'gagra'      => 'dest-abkhazia.jpg',
		'pitsunda'   => 'hero-collage-2.jpg',
		'new_afon'   => 'hero-collage-3.jpg',
		'sukhum'     => 'hero-collage-family.jpg',
	);
	$fallback  = $fallbacks[ $region ] ?? 'dest-abkhazia.jpg';
	if ( defined( 'ZHIRAF_UFA_DIR' ) && ! is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $fallback ) ) {
		$fallback = 'hero-collage-1.jpg';
	}
	if ( $pool_id > 0 ) {
		$rotate   = array( 'dest-abkhazia.jpg', 'hero-collage-2.jpg', 'hero-collage-3.jpg' );
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
function zhiraf_ufa_abkhaziya_starter_definitions() {
	return array(
		array( 'json_id' => 1, 'slot' => 'gagra' ),
		array( 'json_id' => 2, 'slot' => 'pitsunda' ),
		array( 'json_id' => 11, 'slot' => 'new_afon' ),
		array( 'json_id' => 21, 'slot' => 'sukhum' ),
		array( 'json_id' => 17, 'slot' => 'gagra' ),
		array( 'json_id' => 8, 'slot' => 'pitsunda' ),
	);
}

/**
 * @param array<string,mixed> $hotel Card with slot / filter tags.
 * @return array<int,string>
 */
function zhiraf_ufa_abkhaziya_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	if ( ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ) {
		foreach ( $hotel['filter_tags'] as $tag ) {
			if ( 'starter' !== $tag ) {
				$tags[] = (string) $tag;
			}
		}
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	if ( in_array( $slot, array( 'gagra', 'pitsunda', 'new_afon', 'family' ), true ) ) {
		$tags[] = 'need-family';
	} elseif ( in_array( $slot, array( 'sukhum', 'urban' ), true ) ) {
		$tags[] = 'need-urban';
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
function zhiraf_ufa_abkhaziya_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name   = (string) $row['hotel_name'];
	$cat    = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$resort = zhiraf_ufa_abkhaziya_resort_keys_from_row( $row );
	$tags   = array( zhiraf_ufa_abkhaziya_need_tag_from_category( $cat ) );
	foreach ( $resort as $key ) {
		$tags[] = 'resort-' . $key;
	}
	$need_badge = zhiraf_ufa_abkhaziya_pool_need_badge( $cat );
	$pool_id    = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$image      = zhiraf_ufa_abkhaziya_image_from_json_row( $row );

	$card = array(
		'destination_slug' => 'abkhaziya',
		'resort'           => ! empty( $resort[0] ) ? (string) $resort[0] : 'gagra',
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
			__( 'Отель %s, Абхазия', 'zhiraf-ufa' ),
			$name
		),
		'badge'            => $need_badge['badge'],
		'badge_mod'        => $need_badge['badge_mod'],
		'resort_pill'      => zhiraf_ufa_abkhaziya_pool_resort_pill( $row ),
		'subtitle'         => zhiraf_ufa_abkhaziya_pool_meal_concept( $name, $cat ),
		'rating'           => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'             => $name,
		'description'      => zhiraf_ufa_abkhaziya_pool_card_usp( $row ),
		'caveat'           => zhiraf_ufa_abkhaziya_pool_card_caveat( $row ),
		'price_rub'        => zhiraf_ufa_abkhaziya_pool_price_rub( $row ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_abkhaziya_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_curated_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_curated_card( $card, 'abkhazia' );
		if ( ! empty( $with_tourvisor['price_from_api'] ) && 'tourvisor' === ( $with_tourvisor['price_source'] ?? '' ) ) {
			return $with_tourvisor;
		}
		$card = $with_tourvisor;
	}

	$editorial = (int) ( $card['price_rub'] ?? 0 );
	if ( $editorial <= 0 ) {
		$card['price_rub'] = zhiraf_ufa_abkhaziya_hub_min_price_rub();
	}
	$card['price_from_api']        = false;
	$card['price_api_matched']     = false;
	$card['price_api_unavailable'] = false;
	unset( $card['tour_offer'], $card['bg_hotel_name'], $card['price_scope'] );

	return $card;
}

/**
 * @param array<string,mixed> $card Card.
 * @return string
 */
function zhiraf_ufa_abkhaziya_curated_uon_hotel_id( array $card ) {
	if ( ! empty( $card['json_hotel_id'] ) ) {
		return 'AB-' . (int) $card['json_hotel_id'];
	}
	if ( ! empty( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		return 'AB-' . (int) $card['pool_id'];
	}
	$slot = ! empty( $card['slot'] ) ? strtoupper( sanitize_key( (string) $card['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'AB-STARTER-' . $slot;
	}

	return 'AB-HOTEL';
}

/**
 * @param array<string,mixed> $card Card.
 * @return array<string,mixed>
 */
function zhiraf_ufa_abkhaziya_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_abkhaziya_curated_uon_hotel_id( $card );
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
function zhiraf_ufa_abkhaziya_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$by_id = array();
	foreach ( zhiraf_ufa_abkhaziya_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) || empty( $json_row['id'] ) ) {
			continue;
		}
		$by_id[ (int) $json_row['id'] ] = $json_row;
	}

	$hotels       = array();
	$starter_keys = array();
	$rank         = 1;

	foreach ( zhiraf_ufa_abkhaziya_starter_definitions() as $def ) {
		$json_id = (int) ( $def['json_id'] ?? 0 );
		if ( $json_id <= 0 || empty( $by_id[ $json_id ] ) ) {
			continue;
		}
		$row  = $by_id[ $json_id ];
		$card = zhiraf_ufa_abkhaziya_json_row_to_card( $row );
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
		$card['filter_tags'] = zhiraf_ufa_abkhaziya_starter_filter_tags( array_merge( $card, array( 'slot' => $slot ) ) );
		if ( 'base' === $slot ) {
			$card['badge']          = __( 'Старт по Абхазии', 'zhiraf-ufa' );
			$card['badge_mod']      = 'home-price';
			$card['price_emphasis'] = true;
		}

		$hotels[] = zhiraf_ufa_abkhaziya_finalize_catalog_card( zhiraf_ufa_abkhaziya_apply_bg_pricing( $card ) );
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
		$card = zhiraf_ufa_abkhaziya_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_abkhaziya_finalize_catalog_card( zhiraf_ufa_abkhaziya_apply_bg_pricing( $card ) );
		}
	}

	$cache = apply_filters( 'zhiraf_ufa_abkhaziya_curated_catalog_hotels', $hotels );

	return $cache;
}

/**
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_abkhaziya_catalog_filters() {
	$card = array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Абхазия: топ-6 из базы 25 отелей', 'zhiraf-ufa' ),
			'desc'  => __( 'По одному якорю на курорт: Гагра, Пицунда, Новый Афон, Сухум. Трансфер из Адлера согласуем до оплаты.', 'zhiraf-ufa' ),
		),
		array( 'id' => 'resort-gagra', 'group' => 'resort', 'label' => __( '🌊 Гагра', 'zhiraf-ufa' ), 'title' => __( 'Гагра', 'zhiraf-ufa' ), 'desc' => __( 'Ближе к границе и набережная.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-pitsunda', 'group' => 'resort', 'label' => __( '🌲 Пицунда', 'zhiraf-ufa' ), 'title' => __( 'Пицунда', 'zhiraf-ufa' ), 'desc' => __( 'Сосны и пологий пляж.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-new_afon', 'group' => 'resort', 'label' => __( '⛪ Новый Афон', 'zhiraf-ufa' ), 'title' => __( 'Новый Афон', 'zhiraf-ufa' ), 'desc' => __( 'Пещера и монастырь рядом.', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-sukhum', 'group' => 'resort', 'label' => __( '🏙 Сухум', 'zhiraf-ufa' ), 'title' => __( 'Сухум', 'zhiraf-ufa' ), 'desc' => __( 'Столица и набережные.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-family', 'group' => 'need', 'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ), 'title' => __( 'Семейные', 'zhiraf-ufa' ), 'desc' => __( 'Пологий заход и питание.', 'zhiraf-ufa' ) ),
		array( 'id' => 'need-adults', 'group' => 'need', 'label' => __( '💑 Парам', 'zhiraf-ufa' ), 'title' => __( 'Спокойные пары', 'zhiraf-ufa' ), 'desc' => __( 'Тишина и вид на море.', 'zhiraf-ufa' ) ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_abkhaziya_catalog_default_copy() {
	$filters = zhiraf_ufa_abkhaziya_catalog_filters();

	$pool_size = count( zhiraf_ufa_abkhaziya_json_rows() );

	$card = array(
		'eyebrow' => sprintf(
			/* translators: %d: hotels count in JSON pool */
			__( 'Выборка из базы %d отелей', 'zhiraf-ufa' ),
			$pool_size > 0 ? $pool_size : 25
		),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : __( '6 проверенных отелей в Абхазии', 'zhiraf-ufa' ),
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : __( 'Топ-6 для старта; «Показать ещё» откроет все 25. Нужен отель вне витрины — подберём из полной базы Tourvisor.', 'zhiraf-ufa' ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * @return array<string,int>
 */
function zhiraf_ufa_abkhaziya_resort_hotel_counts() {
	$counts = array();
	foreach ( zhiraf_ufa_abkhaziya_catalog_hotels() as $hotel ) {
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
function zhiraf_ufa_abkhaziya_resort_chips() {
	$card = array(
		array( 'id' => 'starter', 'label' => __( 'Топ-6', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-gagra', 'label' => __( 'Гагра', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-pitsunda', 'label' => __( 'Пицунда', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-new_afon', 'label' => __( 'Н. Афон', 'zhiraf-ufa' ) ),
		array( 'id' => 'resort-sukhum', 'label' => __( 'Сухум', 'zhiraf-ufa' ) ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * Four resort pick cards in one row on desktop.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_abkhaziya_resort_pick_cards() {
	$counts = zhiraf_ufa_abkhaziya_resort_hotel_counts();
	$guide  = __( 'К отелям →', 'zhiraf-ufa' );

	$card = array(
		array(
			'id'          => 'gagra',
			'filter_id'   => 'resort-gagra',
			'guide_url'   => zhiraf_ufa_abkhaziya_resort_landing_url( 'gagra' ),
			'guide_label' => $guide,
			'name'        => __( 'Гагра', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Набережная и кафе', 'zhiraf-ufa' ),
			'tag'         => __( 'Ближе к границе', 'zhiraf-ufa' ),
			'tag_mod'     => 'family',
			'transfer'    => __( '~1 ч от Адлера', 'zhiraf-ufa' ),
			'featured'    => true,
			'hotel_count' => $counts['gagra'] ?? 0,
			'accent_mod'  => 'emerald',
			'bullets'     => array(
				array( 'text' => __( '7 отелей в нашей эталонной базе', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'Старая и Новая Гагра', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'pitsunda',
			'filter_id'   => 'resort-pitsunda',
			'guide_url'   => zhiraf_ufa_abkhaziya_resort_landing_url( 'pitsunda' ),
			'guide_label' => $guide,
			'name'        => __( 'Пицунда', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Сосновые пляжи', 'zhiraf-ufa' ),
			'tag'         => __( 'Семьям', 'zhiraf-ufa' ),
			'tag_mod'     => 'nature',
			'transfer'    => __( '1–1,5 ч от Адлера', 'zhiraf-ufa' ),
			'hotel_count' => $counts['pitsunda'] ?? 0,
			'accent_mod'  => 'teal',
			'bullets'     => array(
				array( 'text' => __( '8 отелей в эталонной базе', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'Лдзаа и мы·Пицунда', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'new_afon',
			'filter_id'   => 'resort-new_afon',
			'guide_url'   => zhiraf_ufa_abkhaziya_resort_landing_url( 'new_afon' ),
			'guide_label' => $guide,
			'name'        => __( 'Новый Афон', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Пещера и монастырь', 'zhiraf-ufa' ),
			'tag'         => __( 'Экскурсии', 'zhiraf-ufa' ),
			'tag_mod'     => 'heritage',
			'transfer'    => __( 'Между Гагрой и Сухумом', 'zhiraf-ufa' ),
			'hotel_count' => $counts['new_afon'] ?? 0,
			'accent_mod'  => 'indigo',
			'bullets'     => array(
				array( 'text' => __( '5 отелей в эталонной базе', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'Удобно совместить с морем', 'zhiraf-ufa' ) ),
			),
		),
		array(
			'id'          => 'sukhum',
			'filter_id'   => 'resort-sukhum',
			'guide_url'   => zhiraf_ufa_abkhaziya_resort_landing_url( 'sukhum' ),
			'guide_label' => $guide,
			'name'        => __( 'Сухум', 'zhiraf-ufa' ),
			'subtitle'    => __( 'Столица', 'zhiraf-ufa' ),
			'tag'         => __( 'Город', 'zhiraf-ufa' ),
			'tag_mod'     => 'urban',
			'transfer'    => __( 'Дольше от Адлера', 'zhiraf-ufa' ),
			'hotel_count' => $counts['sukhum'] ?? 0,
			'accent_mod'  => 'slate',
			'bullets'     => array(
				array( 'text' => __( '5 отелей в эталонной базе', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'Набережные Махаджиров и Диоскуров', 'zhiraf-ufa' ) ),
			),
		),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_abkhaziya_expert_nuances() {
	$card = array(
		array( 'icon' => '🛂', 'title' => __( 'Паспорт РФ', 'zhiraf-ufa' ), 'text' => __( 'Внутренний паспорт и рубли — без заграна.', 'zhiraf-ufa' ) ),
		array( 'icon' => '🚐', 'title' => __( 'Трансфер', 'zhiraf-ufa' ), 'text' => __( 'Встреча в Адлере и довоз до отеля в Гагре, Пицунде, Новом Афоне или Сухуме.', 'zhiraf-ufa' ) ),
		array( 'icon' => '🏨', 'title' => __( '25 + полная база', 'zhiraf-ufa' ), 'text' => __( 'На странице — эталонные 25 с текстами; по кнопке в WhatsApp найдём вариант в полном каталоге Tourvisor.', 'zhiraf-ufa' ) ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

