<?php
/**
 * Homepage featured hotel cards (3 per country tab).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default WhatsApp deep link for the agency.
 */
function zhiraf_ufa_brand_whatsapp_url() {
	$url = get_theme_mod( 'zhiraf_whatsapp_url', '' );
	if ( is_string( $url ) && '' !== trim( $url ) ) {
		return trim( $url );
	}

	return apply_filters( 'zhiraf_ufa_default_whatsapp_url', 'https://wa.me/79018105018' );
}

/**
 * Append URL-encoded prefill text to a WhatsApp deep link.
 *
 * @param string $base_url Theme mod or wa.me URL.
 * @param string $text     Prefill message.
 */
function zhiraf_ufa_whatsapp_url_with_text( $base_url, $text ) {
	$base_url = trim( (string) $base_url );
	$text     = trim( (string) $text );
	if ( '' === $base_url ) {
		return '';
	}
	if ( '' === $text ) {
		return $base_url;
	}

	$sep = ( false !== strpos( $base_url, '?' ) ) ? '&' : '?';

	return $base_url . $sep . 'text=' . rawurlencode( $text );
}

/**
 * WhatsApp URL for catalog capture card.
 */
function zhiraf_ufa_home_catalog_capture_wa_url() {
	$text = __( 'Здравствуйте! Не нашёл подходящий отель на сайте. Помогите подобрать тур из Уфы под мои даты и бюджет — нужны 3 персональных варианта.', 'zhiraf-ufa' );

	return zhiraf_ufa_whatsapp_url_with_text( zhiraf_ufa_brand_whatsapp_url(), $text );
}

/**
 * WhatsApp URL for a featured hotel card CTA.
 *
 * @param string $wa_query Hotel name fragment for the message.
 */
function zhiraf_ufa_home_featured_hotel_wa_url( $wa_query ) {
	$hotel = trim( (string) $wa_query );
	$text  = sprintf(
		/* translators: %s: hotel name */
		__( 'Здравствуйте! Интересует тур в отель %s с вылетом из Уфы. Рассчитайте, пожалуйста, на мои даты.', 'zhiraf-ufa' ),
		$hotel
	);

	return zhiraf_ufa_whatsapp_url_with_text( zhiraf_ufa_brand_whatsapp_url(), $text );
}

/**
 * Resolve theme image URL for a featured hotel photo filename.
 *
 * @param string $filename Basename under assets/images/.
 */
function zhiraf_ufa_home_featured_hotel_photo_url( $filename ) {
	$filename = ltrim( (string) $filename, '/' );
	if ( '' === $filename ) {
		return '';
	}
	$img = zhiraf_ufa_theme_image( $filename, '' );

	return ! empty( $img['url'] ) ? (string) $img['url'] : '';
}

/**
 * Load featured hotels JSON (country slug => exactly 3 hotels).
 *
 * @return array<string,array<int,array<string,mixed>>>
 */
function zhiraf_ufa_home_featured_hotels_data() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$path = ZHIRAF_UFA_DIR . '/data/home-featured-hotels.json';
	$cache = array();
	if ( ! is_readable( $path ) ) {
		return $cache;
	}

	$raw = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! is_string( $raw ) || '' === $raw ) {
		return $cache;
	}

	$decoded = json_decode( $raw, true );
	if ( ! is_array( $decoded ) ) {
		return $cache;
	}

	foreach ( $decoded as $country => $rows ) {
		if ( ! is_array( $rows ) ) {
			continue;
		}
		$normalized = array();
		foreach ( array_slice( $rows, 0, 3 ) as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$photo_file = ! empty( $row['photo'] ) ? (string) $row['photo'] : '';
			$row['photoUrl'] = zhiraf_ufa_home_featured_hotel_photo_url( $photo_file );
			$row['waUrl']    = zhiraf_ufa_home_featured_hotel_wa_url( $row['waQuery'] ?? $row['name'] ?? '' );
			$normalized[]    = $row;
		}
		if ( 3 === count( $normalized ) ) {
			$cache[ (string) $country ] = $normalized;
		}
	}

	return $cache;
}

/**
 * Payload for front-end script (photo URLs + wa links resolved server-side).
 *
 * @return array<string,mixed>
 */
function zhiraf_ufa_home_featured_hotels_script_data() {
	$tabs = zhiraf_ufa_home_unified_catalog_tabs();
	$default_tab = ! empty( $tabs[0]['id'] ) ? (string) $tabs[0]['id'] : 'turkey';

	return array(
		'hotels'      => zhiraf_ufa_home_featured_hotels_data(),
		'defaultTab'  => $default_tab,
		'ctaLabel'    => __( 'Узнать цену на мои даты в WhatsApp', 'zhiraf-ufa' ),
		'caveatLead'  => __( 'Важно:', 'zhiraf-ufa' ),
	);
}
