<?php
/**
 * Legacy URL redirects from zhiraf-ufa.ru structure.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 301 map for old paths (see teya-memory/seo/old-url-map.csv).
 *
 * @return array<string,string> Request path => target path (relative).
 */
function zhiraf_ufa_legacy_redirect_map() {
	return array(
		'/kontacty'                    => '/kontakty/',
		'/kontacty/'                   => '/kontakty/',
		'/rasskazy'                    => '/blog/',
		'/rasskazy/'                   => '/blog/',
		'/politika-konfidenczialnosti' => '/politika-konfidenczialnosti/',
		'/tour/tailand/phuket'         => '/tour/tailand/',
		'/tour/tailand/phuket/'        => '/tour/tailand/',
	);
}

/**
 * Run redirects on template_redirect.
 */
function zhiraf_ufa_maybe_legacy_redirect() {
	if ( is_admin() ) {
		return;
	}
	$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	if ( ! is_string( $request ) ) {
		return;
	}
	$request = untrailingslashit( $request );
	$map     = zhiraf_ufa_legacy_redirect_map();
	foreach ( $map as $from => $to ) {
		$from_norm = untrailingslashit( $from );
		if ( untrailingslashit( $to ) === $from_norm ) {
			continue; // Canonical slash is handled by WordPress; self-redirect caused an infinite 301 loop.
		}
		if ( $request === $from_norm || $request . '/' === $from ) {
			wp_safe_redirect( home_url( $to ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'zhiraf_ufa_maybe_legacy_redirect', 0 );
