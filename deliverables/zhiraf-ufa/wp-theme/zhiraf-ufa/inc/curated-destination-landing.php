<?php
/**
 * Curated destination landing v1 — country-agnostic switches.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether destination config enables curated landing v1 layout.
 *
 * @param array<string,mixed> $dest Landing row from zhiraf_ufa_get_destination_landing().
 * @return bool
 */
function zhiraf_ufa_destination_uses_curated_landing( $dest ) {
	return is_array( $dest ) && ! empty( $dest['curated_landing_v1'] );
}

/**
 * Tripadvisor property URL from curated hotel JSON row (Mode A — link-out only).
 *
 * @param array<string,mixed> $row JSON hotel row.
 * @return string
 */
function zhiraf_ufa_curated_tripadvisor_url_from_row( array $row ) {
	foreach ( array( 'tripadvisor_url', 'ta_property_url' ) as $key ) {
		if ( empty( $row[ $key ] ) || ! is_string( $row[ $key ] ) ) {
			continue;
		}
		$url = esc_url_raw( trim( $row[ $key ] ) );
		if ( $url ) {
			return $url;
		}
	}
	return '';
}

/**
 * Fallback TA property URL when only location id is known (Mode A JSON).
 *
 * @param int $location_id Tripadvisor location id.
 * @return string
 */
function zhiraf_ufa_curated_tripadvisor_url_from_location_id( $location_id ) {
	$location_id = (int) $location_id;
	if ( $location_id <= 0 ) {
		return '';
	}

	return 'https://www.tripadvisor.com/Hotel_Review-d' . $location_id . '-Reviews.html';
}

/**
 * Merge ta_location_id + tripadvisor_url from JSON row into curated card payload.
 *
 * @param array<string,mixed> $card Render card.
 * @param array<string,mixed> $row  JSON hotel row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_curated_card_apply_ta_from_json_row( array $card, array $row ) {
	$ta_id  = ! empty( $row['ta_location_id'] ) ? (int) $row['ta_location_id'] : 0;
	$ta_url = zhiraf_ufa_curated_tripadvisor_url_from_row( $row );
	if ( '' === $ta_url && $ta_id > 0 ) {
		$ta_url = zhiraf_ufa_curated_tripadvisor_url_from_location_id( $ta_id );
	}
	if ( $ta_id > 0 ) {
		$card['ta_location_id'] = $ta_id;
	}
	if ( '' !== $ta_url ) {
		$card['tripadvisor_url'] = esc_url( $ta_url );
	}

	return $card;
}

/**
 * H2 for expert nuances block (no Turkey fallback).
 *
 * @param string              $dest_slug Landing slug.
 * @param array<string,mixed> $dest      Optional landing row.
 * @return string
 */
function zhiraf_ufa_curated_expert_section_title( $dest_slug, $dest = array() ) {
	$dest_slug = sanitize_key( (string) $dest_slug );
	$labels    = array(
		'turciya'    => __( 'Турции', 'zhiraf-ufa' ),
		'oae'        => __( 'ОАЭ', 'zhiraf-ufa' ),
		'egipet'     => __( 'Египте', 'zhiraf-ufa' ),
		'tailand'    => __( 'Таиланде', 'zhiraf-ufa' ),
		'maldivy'    => __( 'Мальдивах', 'zhiraf-ufa' ),
		'vietnam'    => __( 'Вьетнаме', 'zhiraf-ufa' ),
		'shri-lanka' => __( 'Шри-Ланке', 'zhiraf-ufa' ),
		'indoneziya' => __( 'Бали', 'zhiraf-ufa' ),
		'indiya'     => __( 'Гоа', 'zhiraf-ufa' ),
		'sejshely'   => __( 'Сейшелах', 'zhiraf-ufa' ),
		'kitaj'      => __( 'Китае (Хайнань)', 'zhiraf-ufa' ),
		'uzbekistan' => __( 'Узбекистане', 'zhiraf-ufa' ),
		'armeniya'   => __( 'Армении', 'zhiraf-ufa' ),
		'abkhaziya'  => __( 'Абхазии', 'zhiraf-ufa' ),
		'rossiya'    => __( 'России', 'zhiraf-ufa' ),
	);
	$place     = $labels[ $dest_slug ] ?? '';
	if ( '' === $place && ! empty( $dest['name'] ) ) {
		$place = (string) $dest['name'];
	}
	if ( '' === $place ) {
		return __( 'Что важно знать перед поездкой в этом сезоне', 'zhiraf-ufa' );
	}

	/* translators: %s: destination name in prepositional case or short label */
	return sprintf( __( 'Что важно знать о %s в этом сезоне', 'zhiraf-ufa' ), $place );
}

/**
 * H2 for resort pick cards (no Turkey fallback).
 *
 * @param string              $dest_slug Landing slug.
 * @param array<string,mixed> $dest      Optional landing row.
 * @return string
 */
function zhiraf_ufa_curated_resort_pick_section_title( $dest_slug, $dest = array() ) {
	$dest_slug = sanitize_key( (string) $dest_slug );
	$titles    = array(
		'turciya'    => __( 'Какой курорт Турции выбрать именно вам?', 'zhiraf-ufa' ),
		'oae'        => __( 'Какой эмират ОАЭ выбрать именно вам?', 'zhiraf-ufa' ),
		'egipet'     => __( 'Какой курорт Египта выбрать именно вам?', 'zhiraf-ufa' ),
		'tailand'    => __( 'Какой курорт Таиланда выбрать именно вам?', 'zhiraf-ufa' ),
		'maldivy'    => __( 'Какой атолл Мальдив выбрать именно вам?', 'zhiraf-ufa' ),
		'vietnam'    => __( 'Какой курорт Вьетнама выбрать именно вам?', 'zhiraf-ufa' ),
		'shri-lanka' => __( 'Какое побережье Шри-Ланки выбрать именно вам?', 'zhiraf-ufa' ),
		'indoneziya' => __( 'Какой курорт Бали выбрать именно вам?', 'zhiraf-ufa' ),
		'indiya'     => __( 'Какую часть Гоа выбрать именно вам?', 'zhiraf-ufa' ),
		'sejshely'   => __( 'Какой остров Сейшел выбрать именно вам?', 'zhiraf-ufa' ),
		'kitaj'      => __( 'Какую бухту Саньи выбрать?', 'zhiraf-ufa' ),
		'uzbekistan' => __( 'Какой город маршрута выбрать?', 'zhiraf-ufa' ),
		'armeniya'   => __( 'Кому какой регион Армении подойдёт?', 'zhiraf-ufa' ),
		'abkhaziya'  => __( 'Гагра, Пицунда, Новый Афон или Сухум — что выбрать?', 'zhiraf-ufa' ),
		'rossiya'    => __( 'Куда поехать в России из Уфы?', 'zhiraf-ufa' ),
	);

	if ( ! empty( $titles[ $dest_slug ] ) ) {
		return $titles[ $dest_slug ];
	}
	if ( ! empty( $dest['name'] ) ) {
		/* translators: %s: country name */
		return sprintf( __( 'Куда поехать в %s — подсказка за 10 секунд', 'zhiraf-ufa' ), (string) $dest['name'] );
	}

	return __( 'Куда поехать — подсказка за 10 секунд', 'zhiraf-ufa' );
}

/**
 * Curated hotel pool for showcase grid.
 *
 * @param string $dest_slug Destination slug (e.g. turciya, oae).
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_curated_catalog_hotels( $dest_slug ) {
	$dest_slug = sanitize_key( (string) $dest_slug );
	if ( 'turciya' === $dest_slug && function_exists( 'zhiraf_ufa_turciya_catalog_hotels' ) ) {
		return zhiraf_ufa_turciya_catalog_hotels();
	}
	if ( 'oae' === $dest_slug && function_exists( 'zhiraf_ufa_oae_catalog_hotels' ) ) {
		return zhiraf_ufa_oae_catalog_hotels();
	}
	if ( 'egipet' === $dest_slug && function_exists( 'zhiraf_ufa_egypt_catalog_hotels' ) ) {
		return zhiraf_ufa_egypt_catalog_hotels();
	}
	if ( 'tailand' === $dest_slug && function_exists( 'zhiraf_ufa_thailand_catalog_hotels' ) ) {
		return zhiraf_ufa_thailand_catalog_hotels();
	}
	if ( 'maldivy' === $dest_slug && function_exists( 'zhiraf_ufa_maldives_catalog_hotels' ) ) {
		return zhiraf_ufa_maldives_catalog_hotels();
	}
	if ( 'vietnam' === $dest_slug && function_exists( 'zhiraf_ufa_vietnam_catalog_hotels' ) ) {
		return zhiraf_ufa_vietnam_catalog_hotels();
	}
	if ( 'shri-lanka' === $dest_slug && function_exists( 'zhiraf_ufa_sri_lanka_catalog_hotels' ) ) {
		return zhiraf_ufa_sri_lanka_catalog_hotels();
	}
	if ( 'indoneziya' === $dest_slug && function_exists( 'zhiraf_ufa_indonesia_catalog_hotels' ) ) {
		return zhiraf_ufa_indonesia_catalog_hotels();
	}
	if ( 'indiya' === $dest_slug && function_exists( 'zhiraf_ufa_india_catalog_hotels' ) ) {
		return zhiraf_ufa_india_catalog_hotels();
	}
	if ( 'sejshely' === $dest_slug && function_exists( 'zhiraf_ufa_seychelles_catalog_hotels' ) ) {
		return zhiraf_ufa_seychelles_catalog_hotels();
	}

	if ( 'kitaj' === $dest_slug && function_exists( 'zhiraf_ufa_kitaj_catalog_hotels' ) ) {
		return zhiraf_ufa_kitaj_catalog_hotels();
	}
	if ( 'uzbekistan' === $dest_slug && function_exists( 'zhiraf_ufa_uzbekistan_catalog_hotels' ) ) {
		return zhiraf_ufa_uzbekistan_catalog_hotels();
	}
	if ( 'armeniya' === $dest_slug && function_exists( 'zhiraf_ufa_armeniya_catalog_hotels' ) ) {
		return zhiraf_ufa_armeniya_catalog_hotels();
	}
	if ( 'abkhaziya' === $dest_slug && function_exists( 'zhiraf_ufa_abkhaziya_catalog_hotels' ) ) {
		return zhiraf_ufa_abkhaziya_catalog_hotels();
	}
	if ( 'rossiya' === $dest_slug && function_exists( 'zhiraf_ufa_rossiya_catalog_hotels' ) ) {
		return zhiraf_ufa_rossiya_catalog_hotels();
	}
	/**
	 * Filter curated hotels for a destination (non-Turkey rollout).
	 *
	 * @param array<int,array<string,mixed>> $hotels Empty by default.
	 * @param string                         $dest_slug Destination slug.
	 */
	return apply_filters( 'zhiraf_ufa_curated_catalog_hotels', array(), $dest_slug );
}

/**
 * Toolbar copy above curated grid.
 *
 * @param string $dest_slug Destination slug.
 * @return array{title?:string,desc?:string}
 */
function zhiraf_ufa_curated_catalog_default_copy( $dest_slug ) {
	$dest_slug = sanitize_key( (string) $dest_slug );
	if ( 'turciya' === $dest_slug && function_exists( 'zhiraf_ufa_turciya_catalog_default_copy' ) ) {
		return zhiraf_ufa_turciya_catalog_default_copy();
	}
	if ( 'oae' === $dest_slug && function_exists( 'zhiraf_ufa_oae_catalog_default_copy' ) ) {
		return zhiraf_ufa_oae_catalog_default_copy();
	}
	if ( 'egipet' === $dest_slug && function_exists( 'zhiraf_ufa_egypt_catalog_default_copy' ) ) {
		return zhiraf_ufa_egypt_catalog_default_copy();
	}
	if ( 'tailand' === $dest_slug && function_exists( 'zhiraf_ufa_thailand_catalog_default_copy' ) ) {
		return zhiraf_ufa_thailand_catalog_default_copy();
	}
	if ( 'maldivy' === $dest_slug && function_exists( 'zhiraf_ufa_maldives_catalog_default_copy' ) ) {
		return zhiraf_ufa_maldives_catalog_default_copy();
	}
	if ( 'vietnam' === $dest_slug && function_exists( 'zhiraf_ufa_vietnam_catalog_default_copy' ) ) {
		return zhiraf_ufa_vietnam_catalog_default_copy();
	}
	if ( 'shri-lanka' === $dest_slug && function_exists( 'zhiraf_ufa_sri_lanka_catalog_default_copy' ) ) {
		return zhiraf_ufa_sri_lanka_catalog_default_copy();
	}
	if ( 'indoneziya' === $dest_slug && function_exists( 'zhiraf_ufa_indonesia_catalog_default_copy' ) ) {
		return zhiraf_ufa_indonesia_catalog_default_copy();
	}
	if ( 'indiya' === $dest_slug && function_exists( 'zhiraf_ufa_india_catalog_default_copy' ) ) {
		return zhiraf_ufa_india_catalog_default_copy();
	}
	if ( 'sejshely' === $dest_slug && function_exists( 'zhiraf_ufa_seychelles_catalog_default_copy' ) ) {
		return zhiraf_ufa_seychelles_catalog_default_copy();
	}

	if ( 'kitaj' === $dest_slug && function_exists( 'zhiraf_ufa_kitaj_catalog_default_copy' ) ) {
		return zhiraf_ufa_kitaj_catalog_default_copy();
	}
	if ( 'uzbekistan' === $dest_slug && function_exists( 'zhiraf_ufa_uzbekistan_catalog_default_copy' ) ) {
		return zhiraf_ufa_uzbekistan_catalog_default_copy();
	}
	if ( 'armeniya' === $dest_slug && function_exists( 'zhiraf_ufa_armeniya_catalog_default_copy' ) ) {
		return zhiraf_ufa_armeniya_catalog_default_copy();
	}
	if ( 'abkhaziya' === $dest_slug && function_exists( 'zhiraf_ufa_abkhaziya_catalog_default_copy' ) ) {
		return zhiraf_ufa_abkhaziya_catalog_default_copy();
	}
	if ( 'rossiya' === $dest_slug && function_exists( 'zhiraf_ufa_rossiya_catalog_default_copy' ) ) {
		return zhiraf_ufa_rossiya_catalog_default_copy();
	}
	/**
	 * Filter default catalog title/desc for curated hub.
	 *
	 * @param array{title?:string,desc?:string} $copy
	 * @param string                          $dest_slug
	 */
	return apply_filters(
		'zhiraf_ufa_curated_catalog_default_copy',
		array(
			'title' => '',
			'desc'  => '',
		),
		$dest_slug
	);
}

/**
 * Whether curated hub renders resort/emirate pick cards (toolbar hides duplicate resort tabs).
 *
 * @param string $dest_slug Destination slug.
 * @return bool
 */
function zhiraf_ufa_curated_has_resort_pick_cards( $dest_slug ) {
	$dest_slug = sanitize_key( (string) $dest_slug );
	$cards     = zhiraf_ufa_curated_resort_pick_cards( $dest_slug );

	return ! empty( $cards );
}

/**
 * Resort / emirate pick cards for curated hub.
 *
 * @param string $dest_slug Destination slug.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_curated_resort_pick_cards( $dest_slug ) {
	$dest_slug = sanitize_key( (string) $dest_slug );
	if ( 'turciya' === $dest_slug && function_exists( 'zhiraf_ufa_turciya_resort_pick_cards' ) ) {
		return zhiraf_ufa_turciya_resort_pick_cards();
	}
	if ( 'oae' === $dest_slug && function_exists( 'zhiraf_ufa_oae_resort_pick_cards' ) ) {
		return zhiraf_ufa_oae_resort_pick_cards();
	}
	if ( 'egipet' === $dest_slug && function_exists( 'zhiraf_ufa_egypt_resort_pick_cards' ) ) {
		return zhiraf_ufa_egypt_resort_pick_cards();
	}
	if ( 'tailand' === $dest_slug && function_exists( 'zhiraf_ufa_thailand_resort_pick_cards' ) ) {
		return zhiraf_ufa_thailand_resort_pick_cards();
	}
	if ( 'maldivy' === $dest_slug && function_exists( 'zhiraf_ufa_maldives_resort_pick_cards' ) ) {
		return zhiraf_ufa_maldives_resort_pick_cards();
	}
	if ( 'vietnam' === $dest_slug && function_exists( 'zhiraf_ufa_vietnam_resort_pick_cards' ) ) {
		return zhiraf_ufa_vietnam_resort_pick_cards();
	}
	if ( 'shri-lanka' === $dest_slug && function_exists( 'zhiraf_ufa_sri_lanka_resort_pick_cards' ) ) {
		return zhiraf_ufa_sri_lanka_resort_pick_cards();
	}
	if ( 'indoneziya' === $dest_slug && function_exists( 'zhiraf_ufa_indonesia_resort_pick_cards' ) ) {
		return zhiraf_ufa_indonesia_resort_pick_cards();
	}
	if ( 'indiya' === $dest_slug && function_exists( 'zhiraf_ufa_india_resort_pick_cards' ) ) {
		return zhiraf_ufa_india_resort_pick_cards();
	}
	if ( 'sejshely' === $dest_slug && function_exists( 'zhiraf_ufa_seychelles_resort_pick_cards' ) ) {
		return zhiraf_ufa_seychelles_resort_pick_cards();
	}

	if ( 'kitaj' === $dest_slug && function_exists( 'zhiraf_ufa_kitaj_resort_pick_cards' ) ) {
		return zhiraf_ufa_kitaj_resort_pick_cards();
	}
	if ( 'uzbekistan' === $dest_slug && function_exists( 'zhiraf_ufa_uzbekistan_resort_pick_cards' ) ) {
		return zhiraf_ufa_uzbekistan_resort_pick_cards();
	}
	if ( 'armeniya' === $dest_slug && function_exists( 'zhiraf_ufa_armeniya_resort_pick_cards' ) ) {
		return zhiraf_ufa_armeniya_resort_pick_cards();
	}
	if ( 'abkhaziya' === $dest_slug && function_exists( 'zhiraf_ufa_abkhaziya_resort_pick_cards' ) ) {
		return zhiraf_ufa_abkhaziya_resort_pick_cards();
	}
	if ( 'rossiya' === $dest_slug && function_exists( 'zhiraf_ufa_rossiya_resort_pick_cards' ) ) {
		return zhiraf_ufa_rossiya_resort_pick_cards();
	}
	/**
	 * @param array<int,array<string,mixed>> $cards
	 * @param string                         $dest_slug
	 */
	return apply_filters( 'zhiraf_ufa_curated_resort_pick_cards', array(), $dest_slug );
}

/**
 * Quick filter chips in hero (sync with catalog JS).
 *
 * @param string $dest_slug Destination slug.
 * @return array<int,array{id:string,label:string}>
 */
function zhiraf_ufa_curated_resort_chips( $dest_slug ) {
	$dest_slug = sanitize_key( (string) $dest_slug );
	if ( 'turciya' === $dest_slug && function_exists( 'zhiraf_ufa_turciya_resort_chips' ) ) {
		return zhiraf_ufa_turciya_resort_chips();
	}
	if ( 'oae' === $dest_slug && function_exists( 'zhiraf_ufa_oae_resort_chips' ) ) {
		return zhiraf_ufa_oae_resort_chips();
	}
	if ( 'egipet' === $dest_slug && function_exists( 'zhiraf_ufa_egypt_resort_chips' ) ) {
		return zhiraf_ufa_egypt_resort_chips();
	}
	if ( 'tailand' === $dest_slug && function_exists( 'zhiraf_ufa_thailand_resort_chips' ) ) {
		return zhiraf_ufa_thailand_resort_chips();
	}
	if ( 'maldivy' === $dest_slug && function_exists( 'zhiraf_ufa_maldives_resort_chips' ) ) {
		return zhiraf_ufa_maldives_resort_chips();
	}
	if ( 'vietnam' === $dest_slug && function_exists( 'zhiraf_ufa_vietnam_resort_chips' ) ) {
		return zhiraf_ufa_vietnam_resort_chips();
	}
	if ( 'shri-lanka' === $dest_slug && function_exists( 'zhiraf_ufa_sri_lanka_resort_chips' ) ) {
		return zhiraf_ufa_sri_lanka_resort_chips();
	}
	if ( 'indoneziya' === $dest_slug && function_exists( 'zhiraf_ufa_indonesia_resort_chips' ) ) {
		return zhiraf_ufa_indonesia_resort_chips();
	}
	if ( 'indiya' === $dest_slug && function_exists( 'zhiraf_ufa_india_resort_chips' ) ) {
		return zhiraf_ufa_india_resort_chips();
	}
	if ( 'sejshely' === $dest_slug && function_exists( 'zhiraf_ufa_seychelles_resort_chips' ) ) {
		return zhiraf_ufa_seychelles_resort_chips();
	}

	if ( 'kitaj' === $dest_slug && function_exists( 'zhiraf_ufa_kitaj_resort_chips' ) ) {
		return zhiraf_ufa_kitaj_resort_chips();
	}
	if ( 'uzbekistan' === $dest_slug && function_exists( 'zhiraf_ufa_uzbekistan_resort_chips' ) ) {
		return zhiraf_ufa_uzbekistan_resort_chips();
	}
	if ( 'armeniya' === $dest_slug && function_exists( 'zhiraf_ufa_armeniya_resort_chips' ) ) {
		return zhiraf_ufa_armeniya_resort_chips();
	}
	if ( 'abkhaziya' === $dest_slug && function_exists( 'zhiraf_ufa_abkhaziya_resort_chips' ) ) {
		return zhiraf_ufa_abkhaziya_resort_chips();
	}
	if ( 'rossiya' === $dest_slug && function_exists( 'zhiraf_ufa_rossiya_resort_chips' ) ) {
		return zhiraf_ufa_rossiya_resort_chips();
	}
	/**
	 * @param array<int,array{id:string,label:string}> $chips
	 * @param string                                   $dest_slug
	 */
	return apply_filters( 'zhiraf_ufa_curated_resort_chips', array(), $dest_slug );
}

/**
 * Expert nuance cards (3 columns) for curated landing.
 *
 * @param string $dest_slug Destination slug.
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_curated_expert_nuances( $dest_slug ) {
	$dest_slug = sanitize_key( (string) $dest_slug );
	if ( 'turciya' === $dest_slug && function_exists( 'zhiraf_ufa_turciya_expert_nuances' ) ) {
		return zhiraf_ufa_turciya_expert_nuances();
	}
	if ( 'oae' === $dest_slug && function_exists( 'zhiraf_ufa_oae_expert_nuances' ) ) {
		return zhiraf_ufa_oae_expert_nuances();
	}
	if ( 'egipet' === $dest_slug && function_exists( 'zhiraf_ufa_egypt_expert_nuances' ) ) {
		return zhiraf_ufa_egypt_expert_nuances();
	}
	if ( 'tailand' === $dest_slug && function_exists( 'zhiraf_ufa_thailand_expert_nuances' ) ) {
		return zhiraf_ufa_thailand_expert_nuances();
	}
	if ( 'maldivy' === $dest_slug && function_exists( 'zhiraf_ufa_maldives_expert_nuances' ) ) {
		return zhiraf_ufa_maldives_expert_nuances();
	}
	if ( 'vietnam' === $dest_slug && function_exists( 'zhiraf_ufa_vietnam_expert_nuances' ) ) {
		return zhiraf_ufa_vietnam_expert_nuances();
	}
	if ( 'shri-lanka' === $dest_slug && function_exists( 'zhiraf_ufa_sri_lanka_expert_nuances' ) ) {
		return zhiraf_ufa_sri_lanka_expert_nuances();
	}
	if ( 'indoneziya' === $dest_slug && function_exists( 'zhiraf_ufa_indonesia_expert_nuances' ) ) {
		return zhiraf_ufa_indonesia_expert_nuances();
	}
	if ( 'indiya' === $dest_slug && function_exists( 'zhiraf_ufa_india_expert_nuances' ) ) {
		return zhiraf_ufa_india_expert_nuances();
	}
	if ( 'sejshely' === $dest_slug && function_exists( 'zhiraf_ufa_seychelles_expert_nuances' ) ) {
		return zhiraf_ufa_seychelles_expert_nuances();
	}

	if ( 'kitaj' === $dest_slug && function_exists( 'zhiraf_ufa_kitaj_expert_nuances' ) ) {
		return zhiraf_ufa_kitaj_expert_nuances();
	}
	if ( 'uzbekistan' === $dest_slug && function_exists( 'zhiraf_ufa_uzbekistan_expert_nuances' ) ) {
		return zhiraf_ufa_uzbekistan_expert_nuances();
	}
	if ( 'armeniya' === $dest_slug && function_exists( 'zhiraf_ufa_armeniya_expert_nuances' ) ) {
		return zhiraf_ufa_armeniya_expert_nuances();
	}
	if ( 'abkhaziya' === $dest_slug && function_exists( 'zhiraf_ufa_abkhaziya_expert_nuances' ) ) {
		return zhiraf_ufa_abkhaziya_expert_nuances();
	}
	if ( 'rossiya' === $dest_slug && function_exists( 'zhiraf_ufa_rossiya_expert_nuances' ) ) {
		return zhiraf_ufa_rossiya_expert_nuances();
	}
	/**
	 * @param array<int,array{icon:string,title:string,text:string}> $items
	 * @param string                                                 $dest_slug
	 */
	return apply_filters( 'zhiraf_ufa_curated_expert_nuances', array(), $dest_slug );
}

/**
 * Catalog filter tabs for curated hub.
 *
 * @param string $dest_slug Destination slug.
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_curated_catalog_filters( $dest_slug ) {
	$dest_slug = sanitize_key( (string) $dest_slug );
	if ( 'turciya' === $dest_slug && function_exists( 'zhiraf_ufa_turciya_catalog_filters' ) ) {
		return zhiraf_ufa_turciya_catalog_filters();
	}
	if ( 'oae' === $dest_slug && function_exists( 'zhiraf_ufa_oae_catalog_filters' ) ) {
		return zhiraf_ufa_oae_catalog_filters();
	}
	if ( 'egipet' === $dest_slug && function_exists( 'zhiraf_ufa_egypt_catalog_filters' ) ) {
		return zhiraf_ufa_egypt_catalog_filters();
	}
	if ( 'tailand' === $dest_slug && function_exists( 'zhiraf_ufa_thailand_catalog_filters' ) ) {
		return zhiraf_ufa_thailand_catalog_filters();
	}
	if ( 'maldivy' === $dest_slug && function_exists( 'zhiraf_ufa_maldives_catalog_filters' ) ) {
		return zhiraf_ufa_maldives_catalog_filters();
	}
	if ( 'vietnam' === $dest_slug && function_exists( 'zhiraf_ufa_vietnam_catalog_filters' ) ) {
		return zhiraf_ufa_vietnam_catalog_filters();
	}
	if ( 'shri-lanka' === $dest_slug && function_exists( 'zhiraf_ufa_sri_lanka_catalog_filters' ) ) {
		return zhiraf_ufa_sri_lanka_catalog_filters();
	}
	if ( 'indoneziya' === $dest_slug && function_exists( 'zhiraf_ufa_indonesia_catalog_filters' ) ) {
		return zhiraf_ufa_indonesia_catalog_filters();
	}
	if ( 'indiya' === $dest_slug && function_exists( 'zhiraf_ufa_india_catalog_filters' ) ) {
		return zhiraf_ufa_india_catalog_filters();
	}
	if ( 'sejshely' === $dest_slug && function_exists( 'zhiraf_ufa_seychelles_catalog_filters' ) ) {
		return zhiraf_ufa_seychelles_catalog_filters();
	}

	if ( 'kitaj' === $dest_slug && function_exists( 'zhiraf_ufa_kitaj_catalog_filters' ) ) {
		return zhiraf_ufa_kitaj_catalog_filters();
	}
	if ( 'uzbekistan' === $dest_slug && function_exists( 'zhiraf_ufa_uzbekistan_catalog_filters' ) ) {
		return zhiraf_ufa_uzbekistan_catalog_filters();
	}
	if ( 'armeniya' === $dest_slug && function_exists( 'zhiraf_ufa_armeniya_catalog_filters' ) ) {
		return zhiraf_ufa_armeniya_catalog_filters();
	}
	if ( 'abkhaziya' === $dest_slug && function_exists( 'zhiraf_ufa_abkhaziya_catalog_filters' ) ) {
		return zhiraf_ufa_abkhaziya_catalog_filters();
	}
	if ( 'rossiya' === $dest_slug && function_exists( 'zhiraf_ufa_rossiya_catalog_filters' ) ) {
		return zhiraf_ufa_rossiya_catalog_filters();
	}
	/**
	 * @param array<int,array<string,string>> $filters
	 * @param string                         $dest_slug
	 */
	return apply_filters( 'zhiraf_ufa_curated_catalog_filters', array(), $dest_slug );
}