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

	/**
	 * @param array<int,array<string,string>> $filters
	 * @param string                         $dest_slug
	 */
	return apply_filters( 'zhiraf_ufa_curated_catalog_filters', array(), $dest_slug );
}