<?php
/**
 * Nested /tour/{country}/{resort}/ URLs (virtual pages, no WP post required).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register rewrite rules for resort landings.
 */
function zhiraf_ufa_register_tour_resort_rewrites() {
	add_rewrite_rule(
		'^tour/([^/]+)/([^/]+)/?$',
		'index.php?zhiraf_tour_country=$matches[1]&zhiraf_tour_resort=$matches[2]',
		'top'
	);
}
add_action( 'init', 'zhiraf_ufa_register_tour_resort_rewrites' );

/**
 * Query vars for tour routing.
 *
 * @param string[] $vars Vars.
 * @return string[]
 */
function zhiraf_ufa_tour_query_vars( $vars ) {
	$vars[] = 'zhiraf_tour_country';
	$vars[] = 'zhiraf_tour_resort';
	return $vars;
}
add_filter( 'query_vars', 'zhiraf_ufa_tour_query_vars' );

/**
 * Load resort template when rewrite matches.
 *
 * @param string $template Template path.
 * @return string
 */
function zhiraf_ufa_tour_resort_template( $template ) {
	$country = get_query_var( 'zhiraf_tour_country' );
	$resort  = get_query_var( 'zhiraf_tour_resort' );
	if ( ! $country || ! $resort ) {
		return $template;
	}
	if ( ! zhiraf_ufa_get_resort_landing( $country, $resort ) ) {
		return $template;
	}
	$custom = ZHIRAF_UFA_DIR . '/page-tour-resort.php';
	return is_readable( $custom ) ? $custom : $template;
}
add_filter( 'template_include', 'zhiraf_ufa_tour_resort_template', 99 );

/**
 * Permanent redirects for resort URLs that are consolidated on country landings.
 *
 * @return array<string,string> resort key country/resort => absolute URL.
 */
function zhiraf_ufa_resort_permanent_redirects() {
	return array(
		'vietnam/fukuok' => home_url( '/tour/vietnam/#dest-module' ),
	);
}

/**
 * 301 away from deprecated resort landings (e.g. Phu Quoc → Vietnam hub).
 */
function zhiraf_ufa_resort_permanent_redirect() {
	$country = sanitize_title( (string) get_query_var( 'zhiraf_tour_country' ) );
	$resort  = sanitize_title( (string) get_query_var( 'zhiraf_tour_resort' ) );
	if ( '' === $country || '' === $resort ) {
		return;
	}
	$key = $country . '/' . $resort;
	$map = zhiraf_ufa_resort_permanent_redirects();
	if ( empty( $map[ $key ] ) ) {
		return;
	}
	wp_safe_redirect( $map[ $key ], 301 );
	exit;
}
add_action( 'template_redirect', 'zhiraf_ufa_resort_permanent_redirect', 1 );

/**
 * Flush rewrite rules after theme activation.
 */
function zhiraf_ufa_flush_tour_rewrites() {
	zhiraf_ufa_register_tour_resort_rewrites();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'zhiraf_ufa_flush_tour_rewrites' );

/**
 * Current resort context from query vars.
 *
 * @return array{country:string,resort:string}|null
 */
function zhiraf_ufa_current_resort_context() {
	$country = sanitize_title( (string) get_query_var( 'zhiraf_tour_country' ) );
	$resort  = sanitize_title( (string) get_query_var( 'zhiraf_tour_resort' ) );
	if ( '' === $country || '' === $resort ) {
		return null;
	}
	return array(
		'country' => $country,
		'resort'  => $resort,
	);
}

/**
 * Public URL for a resort landing.
 *
 * @param string $country Country slug.
 * @param string $resort  Resort slug.
 * @return string
 */
function zhiraf_ufa_resort_url( $country, $resort ) {
	return home_url( '/tour/' . $country . '/' . $resort . '/' );
}
