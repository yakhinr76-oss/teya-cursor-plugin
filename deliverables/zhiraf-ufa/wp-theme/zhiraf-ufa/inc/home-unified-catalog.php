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
define( 'ZHIRAF_UFA_HOME_CATALOG_MORE_STEP', 6 );

/** Max cards kept in DOM per country category. */
define( 'ZHIRAF_UFA_HOME_CATALOG_POOL_PER_TAB', 12 );

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
		'all'      => __( 'Открыть полный каталог направлений', 'zhiraf-ufa' ),
		'turkey'   => __( 'Все проверенные отели в Турции с вылетом из Уфы', 'zhiraf-ufa' ),
		'uae'      => __( 'Все туры в ОАЭ с вылетом из Уфы', 'zhiraf-ufa' ),
		'egypt'    => __( 'Все туры в Египет с прямым вылетом из UFA', 'zhiraf-ufa' ),
		'thailand' => __( 'Все туры в Таиланд из аэропорта Уфа', 'zhiraf-ufa' ),
		'russia'   => __( 'Сочи, Сириус, Абхазия и КМВ — полная подборка', 'zhiraf-ufa' ),
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
 * Homepage catalog only shows UFA-direct packages (keeps Hero promise honest).
 *
 * @param array<string,mixed> $tour Tour row.
 */
function zhiraf_ufa_home_catalog_tour_is_direct_ufa( $tour ) {
	if ( ! is_array( $tour ) || empty( $tour ) ) {
		return false;
	}
	if ( ! function_exists( 'zhiraf_ufa_tour_flight_info' ) ) {
		return true;
	}
	$flight = zhiraf_ufa_tour_flight_info( $tour );
	return in_array( $flight['key'], array( 'direct', 'ufa' ), true );
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
		$limit    = in_array( $slug, array( 'rossiya', 'abkhaziya' ), true )
			? min( 6, ZHIRAF_UFA_HOME_CATALOG_POOL_PER_TAB )
			: ZHIRAF_UFA_HOME_CATALOG_POOL_PER_TAB;

		if ( ! isset( $by_category[ $category ] ) ) {
			$by_category[ $category ] = array();
		}

		foreach ( zhiraf_ufa_get_country_top_tours( $slug, $limit ) as $tour ) {
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

	$all_pick_keys = array();
	foreach ( $by_category as $category => $rows ) {
		if ( empty( $rows ) ) {
			continue;
		}
		$all_pick_keys[ $category ] = zhiraf_ufa_home_unified_tour_key( $rows[0] );
	}

	$out = array();
	foreach ( $by_category as $category => $rows ) {
		foreach ( $rows as $tour ) {
			$key = zhiraf_ufa_home_unified_tour_key( $tour );
			$tour['catalog_in_all'] = isset( $all_pick_keys[ $category ] ) && $all_pick_keys[ $category ] === $key;
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

	return $out;
}
