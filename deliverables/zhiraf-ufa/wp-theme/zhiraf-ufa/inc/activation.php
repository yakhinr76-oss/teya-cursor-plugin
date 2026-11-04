<?php
/**
 * Theme activation: create required pages (idempotent) and flush rewrite rules.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ensure a page exists at the given slug/parent; never duplicates or overwrites.
 *
 * @param string $slug   Page slug.
 * @param string $title  Page title.
 * @param int    $parent Parent page ID.
 * @return int Page ID (0 on failure).
 */
function zhiraf_ufa_ensure_page( $slug, $title, $parent = 0 ) {
	$path     = $parent ? get_page_uri( $parent ) . '/' . $slug : $slug;
	$existing = get_page_by_path( $path, OBJECT, 'page' );
	if ( $existing ) {
		return (int) $existing->ID;
	}
	$id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_parent' => (int) $parent,
		),
		true
	);
	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * Create catalog, all country pages and offer landings; then flush rewrites.
 */
function zhiraf_ufa_activation_pages() {
	$tour_id = zhiraf_ufa_ensure_page( 'tour', __( 'Направления', 'zhiraf-ufa' ) );
	if ( $tour_id && function_exists( 'zhiraf_ufa_destination_landing_data' ) ) {
		foreach ( zhiraf_ufa_destination_landing_data() as $slug => $dest ) {
			zhiraf_ufa_ensure_page( $slug, ! empty( $dest['name'] ) ? $dest['name'] : $slug, $tour_id );
		}
	}
	zhiraf_ufa_ensure_page( 'goryashchie-tury', __( 'Горящие туры', 'zhiraf-ufa' ) );
	zhiraf_ufa_ensure_page( 'vse-vklyucheno', __( 'Всё включено', 'zhiraf-ufa' ) );

	if ( function_exists( 'zhiraf_ufa_register_tour_resort_rewrites' ) ) {
		zhiraf_ufa_register_tour_resort_rewrites();
	}
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'zhiraf_ufa_activation_pages' );
