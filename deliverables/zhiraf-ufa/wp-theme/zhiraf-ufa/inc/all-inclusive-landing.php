<?php
/**
 * All Inclusive offer landing helpers (/vse-vklyucheno/).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether tour meal is AI or UAI.
 *
 * @param array<string,mixed> $tour Tour row.
 * @return bool
 */
function zhiraf_ufa_tour_is_all_inclusive( $tour ) {
	if ( ! is_array( $tour ) ) {
		return false;
	}
	$code = ! empty( $tour['meal_code'] ) ? strtoupper( trim( (string) $tour['meal_code'] ) ) : '';
	return in_array( $code, array( 'AI', 'UAI' ), true );
}

/**
 * Showcase AI/UAI tours for the offer landing.
 *
 * @param int    $limit         Max cards.
 * @param string $country_slug  Optional filter slug.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_get_all_inclusive_showcase_tours( $limit = 9, $country_slug = '' ) {
	if ( ! class_exists( 'Zhiraf_BG_Price_Engine' ) ) {
		return array();
	}
	$engine = Zhiraf_BG_Price_Engine::get_instance();
	$slugs  = array( 'turciya', 'oae', 'egipet', 'tailand', 'vietnam' );
	if ( '' !== $country_slug ) {
		$slugs = array( sanitize_key( $country_slug ) );
	}
	$limit   = max( 1, (int) $limit );
	$per     = '' !== $country_slug ? $limit : max( 2, (int) ceil( $limit / count( $slugs ) ) );
	$cards   = array();
	$seen_h  = array();

	foreach ( $slugs as $slug ) {
		$tours = $engine->get_country_tours( $slug, 200 );
		$ai    = array_values(
			array_filter(
				$tours,
				function ( $t ) use ( &$seen_h, $slug ) {
					if ( ! zhiraf_ufa_tour_is_all_inclusive( $t ) ) {
						return false;
					}
					$key = $slug . '|' . mb_strtolower( (string) ( $t['hotel_name'] ?? '' ), 'UTF-8' );
					if ( isset( $seen_h[ $key ] ) ) {
						return false;
					}
					return true;
				}
			)
		);
		usort(
			$ai,
			function ( $a, $b ) {
				$ua = ( 'UAI' === strtoupper( (string) ( $a['meal_code'] ?? '' ) ) ) ? 0 : 1;
				$ub = ( 'UAI' === strtoupper( (string) ( $b['meal_code'] ?? '' ) ) ) ? 0 : 1;
				if ( $ua !== $ub ) {
					return $ua <=> $ub;
				}
				return (int) ( $a['price_rur'] ?? 0 ) <=> (int) ( $b['price_rur'] ?? 0 );
			}
		);
		$slice = array_slice( $ai, 0, $per );
		foreach ( $slice as $t ) {
			$key = $slug . '|' . mb_strtolower( (string) ( $t['hotel_name'] ?? '' ), 'UTF-8' );
			$seen_h[ $key ] = true;
			$cards[]        = $t;
		}
	}
	usort(
		$cards,
		function ( $a, $b ) {
			return (int) ( $a['price_rur'] ?? 0 ) <=> (int) ( $b['price_rur'] ?? 0 );
		}
	);
	return array_slice( $cards, 0, $limit );
}

/**
 * Minimum AI/UAI price for hero badge (2 adults).
 *
 * @return array{price:int,formatted:string}|null
 */
function zhiraf_ufa_all_inclusive_min_price() {
	$tours = zhiraf_ufa_get_all_inclusive_showcase_tours( 40 );
	if ( empty( $tours ) ) {
		return null;
	}
	$min = (int) $tours[0]['price_rur'];
	foreach ( $tours as $t ) {
		$p = (int) ( $t['price_rur'] ?? 0 );
		if ( $p > 0 && $p < $min ) {
			$min = $p;
		}
	}
	if ( $min <= 0 ) {
		return null;
	}
	return array(
		'price'     => $min,
		'formatted' => number_format( $min, 0, '.', ' ' ),
	);
}

/**
 * Destination tabs for AI landing.
 *
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_all_inclusive_destination_tabs() {
	return array(
		array(
			'id'    => 'all',
			'label' => __( 'Все', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'turciya',
			'label' => __( 'Турция · прямой рейс', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'egipet',
			'label' => __( 'Египет · прямой рейс', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'oae',
			'label' => __( 'ОАЭ', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'islands',
			'label' => __( 'Острова (Таиланд / Вьетнам)', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Map tab id to country slugs for filtering.
 *
 * @param string $tab_id Tab id.
 * @return string[] Empty = show all.
 */
function zhiraf_ufa_all_inclusive_tab_countries( $tab_id ) {
	if ( 'turciya' === $tab_id ) {
		return array( 'turciya' );
	}
	if ( 'egipet' === $tab_id ) {
		return array( 'egipet' );
	}
	if ( 'oae' === $tab_id ) {
		return array( 'oae' );
	}
	if ( 'islands' === $tab_id ) {
		return array( 'tailand', 'vietnam' );
	}
	return array();
}

/**
 * Destination mini-cards with AI min price.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_all_inclusive_destination_cards() {
	$rows = array(
		array(
			'slug'  => 'turciya',
			'name'  => __( 'Турция', 'zhiraf-ufa' ),
			'image' => 'resort-turciya-belek-1.jpg',
			'tag'   => __( 'Прямой рейс из Уфы', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/turciya/' ),
		),
		array(
			'slug'  => 'egipet',
			'name'  => __( 'Египет', 'zhiraf-ufa' ),
			'image' => 'resort-egipet-hurghada-1.jpg',
			'tag'   => __( 'Прямой рейс из Уфы', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/egipet/' ),
		),
		array(
			'slug'  => 'oae',
			'name'  => __( 'ОАЭ', 'zhiraf-ufa' ),
			'image' => 'hero-uae.jpg',
			'tag'   => __( 'Пляж + AI в отеле', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/oae/' ),
		),
		array(
			'slug'  => 'tailand',
			'name'  => __( 'Таиланд', 'zhiraf-ufa' ),
			'image' => 'resort-phuket.jpg',
			'tag'   => __( 'Через Москву', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/tailand/' ),
		),
	);
	foreach ( $rows as &$row ) {
		$min = null;
		foreach ( zhiraf_ufa_get_all_inclusive_showcase_tours( 20, $row['slug'] ) as $t ) {
			if ( null === $min || (int) $t['price_rur'] < (int) $min['price_rur'] ) {
				$min = $t;
			}
		}
		$row['price_from'] = $min ? (int) $min['price_rur'] : 0;
	}
	unset( $row );
	return $rows;
}
