<?php
/**
 * Homepage unified tour catalog (destinations + price cards).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Visible tour cards on first paint (per active tab). */
define( 'ZHIRAF_UFA_HOME_CATALOG_VISIBLE', 6 );

/** Extra cards revealed per «Показать ещё» click. */
define( 'ZHIRAF_UFA_HOME_CATALOG_MORE_STEP', 3 );

/** Max cards kept in DOM per filter category (6 visible + до 3 по «Показать ещё»). */
define( 'ZHIRAF_UFA_HOME_CATALOG_POOL_PER_TAB', 9 );

/**
 * Season mode: auto | summer | winter (theme mod for manual override).
 */
function zhiraf_ufa_home_catalog_season() {
	$mode = get_theme_mod( 'zhiraf_catalog_season', 'auto' );
	if ( 'summer' === $mode ) {
		return 'summer';
	}
	if ( 'winter' === $mode ) {
		return 'winter';
	}
	$month = (int) gmdate( 'n' );
	return ( $month >= 5 && $month <= 10 ) ? 'summer' : 'winter';
}

/**
 * Sort weight per filter category for the active season (lower = higher on «Все»).
 *
 * @return array<string,int>
 */
function zhiraf_ufa_home_catalog_category_priorities( $season = null ) {
	if ( null === $season ) {
		$season = zhiraf_ufa_home_catalog_season();
	}
	if ( 'summer' === $season ) {
		return array(
			'turkey'   => 1,
			'russia'   => 2,
			'egypt'    => 3,
			'uae'      => 4,
			'thailand' => 5,
		);
	}
	return array(
		'uae'      => 1,
		'thailand' => 2,
		'egypt'    => 3,
		'russia'   => 4,
		'turkey'   => 5,
	);
}

/**
 * Pill tabs for the unified homepage catalog (seasonal order, UFA-direct focus).
 *
 * @return array<int,array{id:string,label:string}>
 */
function zhiraf_ufa_home_unified_catalog_tabs() {
	$season = zhiraf_ufa_home_catalog_season();
	if ( 'summer' === $season ) {
		return array(
			array(
				'id'    => 'all',
				'label' => __( 'Все направления', 'zhiraf-ufa' ),
			),
			array(
				'id'    => 'turkey',
				'label' => __( 'Турция', 'zhiraf-ufa' ),
			),
			array(
				'id'    => 'russia',
				'label' => __( 'Сочи и Абхазия', 'zhiraf-ufa' ),
			),
			array(
				'id'    => 'egypt',
				'label' => __( 'Египет', 'zhiraf-ufa' ),
			),
			array(
				'id'    => 'uae',
				'label' => __( 'ОАЭ', 'zhiraf-ufa' ),
			),
			array(
				'id'    => 'thailand',
				'label' => __( 'Таиланд', 'zhiraf-ufa' ),
			),
		);
	}

	return array(
		array(
			'id'    => 'all',
			'label' => __( 'Все направления', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'uae',
			'label' => __( 'ОАЭ', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'thailand',
			'label' => __( 'Таиланд', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'egypt',
			'label' => __( 'Египет', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'russia',
			'label' => __( 'Красная Поляна и Сочи', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'turkey',
			'label' => __( 'Турция', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Landing URL for «все отели» link under the active catalog tab.
 *
 * @param string $tab_id Tab filter id.
 */
function zhiraf_ufa_home_catalog_tab_landing_url( $tab_id ) {
	$map = array(
		'turkey'   => 'turciya',
		'uae'      => 'oae',
		'egypt'    => 'egipet',
		'thailand' => 'tailand',
		'russia'   => 'rossiya',
	);
	$tab_id = (string) $tab_id;
	if ( 'all' === $tab_id ) {
		return home_url( '/tour/' );
	}
	if ( ! isset( $map[ $tab_id ] ) ) {
		return home_url( '/tour/' );
	}
	return home_url( '/tour/' . $map[ $tab_id ] . '/' );
}

/**
 * CTA label for the country landing link.
 *
 * @param string $tab_id Tab filter id.
 */
function zhiraf_ufa_home_catalog_tab_landing_label( $tab_id ) {
	$labels = array(
		'all'      => __( 'Посмотреть все направления с вылетом из Уфы', 'zhiraf-ufa' ),
		'turkey'   => __( 'Посмотреть все отели по направлению', 'zhiraf-ufa' ),
		'uae'      => __( 'Посмотреть все отели по направлению', 'zhiraf-ufa' ),
		'egypt'    => __( 'Посмотреть все отели по направлению', 'zhiraf-ufa' ),
		'thailand' => __( 'Посмотреть все отели по направлению', 'zhiraf-ufa' ),
		'russia'   => __( 'Посмотреть все отели по направлению', 'zhiraf-ufa' ),
	);
	return isset( $labels[ $tab_id ] ) ? $labels[ $tab_id ] : $labels['all'];
}

/**
 * data-category slug for unified catalog filters (maps BG country slugs).
 *
 * @param string $country_slug Theme country slug.
 */
function zhiraf_ufa_home_catalog_category( $country_slug ) {
	$map = array(
		'turciya'   => 'turkey',
		'oae'       => 'uae',
		'egipet'    => 'egypt',
		'tailand'   => 'thailand',
		'rossiya'   => 'russia',
		'abkhaziya' => 'russia',
	);
	$slug = (string) $country_slug;
	return isset( $map[ $slug ] ) ? $map[ $slug ] : $slug;
}

/**
 * Homepage catalog: packages sold from Ufa for core charter directions.
 * BG API often labels these «стыковка в Москве» in airline text while the
 * storefront offer is still «из Уфы» — exclude only real Moscow departures.
 *
 * @param array<string,mixed> $tour Tour row.
 */
function zhiraf_ufa_home_catalog_tour_is_direct_ufa( $tour ) {
	if ( ! is_array( $tour ) || empty( $tour ) ) {
		return false;
	}

	$city = (string) ( $tour['departure_city'] ?? 'Уфа' );
	if ( 'Москва' === $city ) {
		return false;
	}

	if ( ! function_exists( 'zhiraf_ufa_tour_flight_info' ) ) {
		return 'Уфа' === $city;
	}

	$flight = zhiraf_ufa_tour_flight_info( $tour );
	if ( in_array( $flight['key'], array( 'direct', 'ufa' ), true ) ) {
		return true;
	}

	$slug            = (string) ( $tour['country_slug'] ?? '' );
	$ufa_charter_slugs = array( 'turciya', 'oae', 'egipet', 'tailand', 'rossiya', 'abkhaziya' );

	return 'Уфа' === $city && in_array( $slug, $ufa_charter_slugs, true );
}

/**
 * Stable dedupe key for a tour row.
 *
 * @param array<string,mixed> $tour Tour row.
 */
function zhiraf_ufa_home_unified_tour_key( $tour ) {
	$parts = array(
		(string) ( $tour['hotel_name'] ?? '' ),
		(string) ( $tour['resort'] ?? '' ),
		(string) ( $tour['departure_date'] ?? '' ),
		(string) ( $tour['country_slug'] ?? '' ),
	);
	return md5( implode( '|', $parts ) );
}

/**
 * Tour cards for the unified grid (deduped, capped per tab, seasonal priority).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_home_unified_catalog_tours() {
	$slugs       = array( 'turciya', 'oae', 'egipet', 'tailand', 'rossiya', 'abkhaziya' );
	$priorities  = zhiraf_ufa_home_catalog_category_priorities();
	$by_category = array();
	$seen_keys   = array();

	foreach ( $slugs as $slug ) {
		$category = zhiraf_ufa_home_catalog_category( $slug );
		$limit    = ZHIRAF_UFA_HOME_CATALOG_POOL_PER_TAB;

		if ( ! isset( $by_category[ $category ] ) ) {
			$by_category[ $category ] = array();
		}

		if ( count( $by_category[ $category ] ) >= ZHIRAF_UFA_HOME_CATALOG_POOL_PER_TAB ) {
			continue;
		}

		foreach ( zhiraf_ufa_get_country_top_tours( $slug, $limit ) as $tour ) {
			if ( count( $by_category[ $category ] ) >= ZHIRAF_UFA_HOME_CATALOG_POOL_PER_TAB ) {
				break;
			}
			if ( ! is_array( $tour ) || empty( $tour ) ) {
				continue;
			}
			if ( ! zhiraf_ufa_home_catalog_tour_is_direct_ufa( $tour ) ) {
				continue;
			}
			$key = zhiraf_ufa_home_unified_tour_key( $tour );
			if ( isset( $seen_keys[ $key ] ) ) {
				continue;
			}
			$seen_keys[ $key ] = true;
			$tour['catalog_priority'] = $priorities[ $category ] ?? 50;
			$by_category[ $category ][] = $tour;
		}
	}

	foreach ( $by_category as $category => $rows ) {
		usort(
			$rows,
			function ( $a, $b ) {
				return (int) ( $a['price_rur'] ?? 0 ) <=> (int) ( $b['price_rur'] ?? 0 );
			}
		);
		$by_category[ $category ] = $rows;
	}

	$out = array();
	foreach ( $by_category as $category => $rows ) {
		foreach ( $rows as $tour ) {
			$tour['catalog_in_all'] = false;
			$out[]                  = $tour;
		}
	}

	usort(
		$out,
		function ( $a, $b ) {
			$pa = (int) ( $a['catalog_priority'] ?? 99 );
			$pb = (int) ( $b['catalog_priority'] ?? 99 );
			if ( $pa !== $pb ) {
				return $pa <=> $pb;
			}
			return (int) ( $a['price_rur'] ?? 0 ) <=> (int) ( $b['price_rur'] ?? 0 );
		}
	);

	zhiraf_ufa_home_mark_catalog_all_teasers( $out );

	return $out;
}

/**
 * Mark up to 6 tours for the «Все направления» tab (round-robin by category).
 *
 * @param array<int,array<string,mixed>> $tours Tour list (by reference).
 */
function zhiraf_ufa_home_mark_catalog_all_teasers( array &$tours ) {
	$max = (int) ZHIRAF_UFA_HOME_CATALOG_VISIBLE;
	if ( $max < 1 || empty( $tours ) ) {
		return;
	}

	foreach ( $tours as $i => $tour ) {
		$tours[ $i ]['catalog_in_all'] = false;
	}

	$indices_by_category = array();
	foreach ( $tours as $i => $tour ) {
		$cat = zhiraf_ufa_home_catalog_category( (string) ( $tour['country_slug'] ?? '' ) );
		if ( ! isset( $indices_by_category[ $cat ] ) ) {
			$indices_by_category[ $cat ] = array();
		}
		$indices_by_category[ $cat ][] = $i;
	}

	$category_order = array_keys( zhiraf_ufa_home_catalog_category_priorities() );
	$marked         = 0;
	$round          = 0;

	while ( $marked < $max ) {
		$progress = false;
		foreach ( $category_order as $cat ) {
			if ( $marked >= $max ) {
				break;
			}
			if ( empty( $indices_by_category[ $cat ][ $round ] ) ) {
				continue;
			}
			$idx = $indices_by_category[ $cat ][ $round ];
			if ( ! empty( $tours[ $idx ]['catalog_in_all'] ) ) {
				continue;
			}
			$tours[ $idx ]['catalog_in_all'] = true;
			++$marked;
			$progress = true;
		}
		if ( ! $progress ) {
			break;
		}
		++$round;
	}
}
