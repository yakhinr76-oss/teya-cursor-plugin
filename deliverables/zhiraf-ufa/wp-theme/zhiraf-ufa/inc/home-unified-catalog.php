<?php
/**
 * Homepage unified tour catalog (destinations + price cards).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pill tabs for the unified homepage catalog.
 *
 * @return array<int,array{id:string,label:string}>
 */
function zhiraf_ufa_home_unified_catalog_tabs() {
	return array(
		array(
			'id'    => 'all',
			'label' => __( 'Все направления', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'turciya',
			'label' => __( 'Турция', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'oae',
			'label' => __( 'ОАЭ', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'egipet',
			'label' => __( 'Египет', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'tailand',
			'label' => __( 'Таиланд', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'rossiya-domestic',
			'label' => __( 'Россия и Абхазия', 'zhiraf-ufa' ),
		),
	);
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
 * Tour cards for the unified grid (deduped union for tab filtering).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_home_unified_catalog_tours() {
	$per_country = 6;
	$slugs       = array( 'turciya', 'oae', 'egipet', 'tailand', 'rossiya', 'abkhaziya' );
	$by_key      = array();
	$in_all      = array();

	foreach ( zhiraf_ufa_get_hot_tours( $per_country ) as $tour ) {
		if ( ! is_array( $tour ) || empty( $tour ) ) {
			continue;
		}
		$key = zhiraf_ufa_home_unified_tour_key( $tour );
		$in_all[ $key ] = true;
		$tour['catalog_in_all'] = true;
		$by_key[ $key ]         = $tour;
	}

	foreach ( $slugs as $slug ) {
		$limit = in_array( $slug, array( 'rossiya', 'abkhaziya' ), true ) ? 3 : $per_country;
		foreach ( zhiraf_ufa_get_country_top_tours( $slug, $limit ) as $tour ) {
			if ( ! is_array( $tour ) || empty( $tour ) ) {
				continue;
			}
			$key = zhiraf_ufa_home_unified_tour_key( $tour );
			if ( isset( $by_key[ $key ] ) ) {
				continue;
			}
			$tour['catalog_in_all'] = ! empty( $in_all[ $key ] );
			$by_key[ $key ]         = $tour;
		}
	}

	usort(
		$by_key,
		function ( $a, $b ) {
			return (int) ( $a['price_rur'] ?? 0 ) <=> (int) ( $b['price_rur'] ?? 0 );
		}
	);

	if ( empty( $in_all ) && ! empty( $by_key ) ) {
		$slice = array_slice( array_values( $by_key ), 0, 6 );
		foreach ( $slice as $t ) {
			$key = zhiraf_ufa_home_unified_tour_key( $t );
			if ( isset( $by_key[ $key ] ) ) {
				$by_key[ $key ]['catalog_in_all'] = true;
			}
		}
	}

	return array_values( $by_key );
}
