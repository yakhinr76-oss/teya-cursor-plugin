<?php
/**
 * Tour price cards helpers and integration.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve resort/destination image for tour card.
 *
 * @param array<string,mixed> $tour Tour data.
 * @return string Image filename in assets/images/.
 */
function zhiraf_ufa_tour_image( $tour ) {
	if ( ! empty( $tour['image'] ) ) {
		return (string) $tour['image'];
	}

	$resort = ! empty( $tour['resort'] ) ? mb_strtolower( (string) $tour['resort'], 'UTF-8' ) : '';
	$slug   = ! empty( $tour['country_slug'] ) ? (string) $tour['country_slug'] : 'turciya';

	$slug_map = array(
		'turkey'   => 'turciya',
		'uae'      => 'oae',
		'egypt'    => 'egipet',
		'thailand' => 'tailand',
	);
	if ( isset( $slug_map[ $slug ] ) ) {
		$slug = $slug_map[ $slug ];
	}

	if ( '' !== $resort ) {
		if ( false !== mb_strpos( $resort, 'стамбул', 0, 'UTF-8' ) ) {
			return 'hero-turkey.jpg';
		}
		if ( false !== mb_strpos( $resort, 'анталь', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'кемер', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'алань', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'сиде', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'белек', 0, 'UTF-8' ) ) {
			return 'dest-turkey.jpg';
		}
		if ( false !== mb_strpos( $resort, 'шарм', 0, 'UTF-8' ) ) {
			return 'hero-egypt.jpg';
		}
		if ( false !== mb_strpos( $resort, 'хургад', 0, 'UTF-8' ) ) {
			return 'hero-egypt.jpg';
		}
		if ( false !== mb_strpos( $resort, 'фукуок', 0, 'UTF-8' ) ) {
			return 'hero-vietnam.jpg';
		}
		if ( false !== mb_strpos( $resort, 'нячанг', 0, 'UTF-8' ) ) {
			return 'dest-vietnam.jpg';
		}
		if ( false !== mb_strpos( $resort, 'пхукет', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'паттай', 0, 'UTF-8' ) ) {
			return 'country-tailand.jpg';
		}
		if ( false !== mb_strpos( $resort, 'дубай', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'шардж', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'аджман', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'умм', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'рас', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'фуджейр', 0, 'UTF-8' ) ) {
			return 'country-oae.jpg';
		}
	}

	$map = array(
		'turciya' => 'country-turciya.jpg',
		'egipet' => 'country-egipet.jpg',
		'oae' => 'country-oae.jpg',
		'tailand' => 'country-tailand.jpg',
		'vietnam' => 'country-vietnam.jpg',
		'kitaj' => 'country-kitaj.jpg',
		'indiya' => 'country-indiya.jpg',
		'shri-lanka' => 'country-shri-lanka.jpg',
		'indoneziya' => 'country-indoneziya.jpg',
		'maldivy' => 'country-maldivy.jpg',
		'sejshely' => 'country-sejshely.jpg',
		'uzbekistan' => 'country-uzbekistan.jpg',
		'armeniya' => 'country-armeniya.jpg',
	);

	// Countries without a matching library photo get '' => card renders a gradient (never a wrong country's photo).
	return isset( $map[ $slug ] ) ? $map[ $slug ] : '';
}

/**
 * Format Russian plural for nights.
 *
 * @param int $nights Number of nights.
 * @return string
 */
function zhiraf_ufa_plural_nights( $nights ) {
	$n      = (int) $nights;
	$mod10  = $n % 10;
	$mod100 = $n % 100;

	if ( $mod100 >= 11 && $mod100 <= 19 ) {
		return sprintf( /* translators: %d: nights */ _n( '%d ночей', '%d ночей', $n, 'zhiraf-ufa' ), $n );
	}
	if ( 1 === $mod10 ) {
		return sprintf( /* translators: %d: night */ _n( '%d ночь', '%d ночь', $n, 'zhiraf-ufa' ), $n );
	}
	if ( $mod10 >= 2 && $mod10 <= 4 ) {
		return sprintf( /* translators: %d: nights */ _n( '%d ночи', '%d ночи', $n, 'zhiraf-ufa' ), $n );
	}

	return sprintf( /* translators: %d: nights */ _n( '%d ночей', '%d ночей', $n, 'zhiraf-ufa' ), $n );
}

/**
 * Get country label in prepositional/accusative for heading.
 *
 * @param string $country_slug Country slug.
 * @return string
 */
function zhiraf_ufa_country_prepositional( $country_slug ) {
	$map = array(
		'turciya'  => __( 'в Турцию', 'zhiraf-ufa' ),
		'turkey'   => __( 'в Турцию', 'zhiraf-ufa' ),
		'oae'      => __( 'в ОАЭ', 'zhiraf-ufa' ),
		'uae'      => __( 'в ОАЭ', 'zhiraf-ufa' ),
		'egipet'   => __( 'в Египет', 'zhiraf-ufa' ),
		'egypt'    => __( 'в Египет', 'zhiraf-ufa' ),
		'tailand'  => __( 'в Таиланд', 'zhiraf-ufa' ),
		'thailand' => __( 'в Таиланд', 'zhiraf-ufa' ),
		'vietnam'  => __( 'во Вьетнам', 'zhiraf-ufa' ),
	);

	return isset( $map[ $country_slug ] ) ? $map[ $country_slug ] : __( 'по направлению', 'zhiraf-ufa' );
}

/**
 * Get normalized country name.
 *
 * @param string $country_slug Country slug.
 * @return string
 */
function zhiraf_ufa_tour_country_name( $country_slug ) {
	$map = array(
		'turciya'  => __( 'Турция', 'zhiraf-ufa' ),
		'turkey'   => __( 'Турция', 'zhiraf-ufa' ),
		'oae'      => __( 'ОАЭ', 'zhiraf-ufa' ),
		'uae'      => __( 'ОАЭ', 'zhiraf-ufa' ),
		'egipet'   => __( 'Египет', 'zhiraf-ufa' ),
		'egypt'    => __( 'Египет', 'zhiraf-ufa' ),
		'tailand'  => __( 'Таиланд', 'zhiraf-ufa' ),
		'thailand' => __( 'Таиланд', 'zhiraf-ufa' ),
		'vietnam'  => __( 'Вьетнам', 'zhiraf-ufa' ),
		'kitaj'      => __( 'Китай', 'zhiraf-ufa' ),
		'indiya'     => __( 'Индия', 'zhiraf-ufa' ),
		'shri-lanka' => __( 'Шри-Ланка', 'zhiraf-ufa' ),
		'indoneziya' => __( 'Индонезия', 'zhiraf-ufa' ),
		'maldivy'    => __( 'Мальдивы', 'zhiraf-ufa' ),
		'sejshely'   => __( 'Сейшелы', 'zhiraf-ufa' ),
		'uzbekistan' => __( 'Узбекистан', 'zhiraf-ufa' ),
		'armeniya'   => __( 'Армения', 'zhiraf-ufa' ),
	);

	return isset( $map[ $country_slug ] ) ? $map[ $country_slug ] : '';
}
