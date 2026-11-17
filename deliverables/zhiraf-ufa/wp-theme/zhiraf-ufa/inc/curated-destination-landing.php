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