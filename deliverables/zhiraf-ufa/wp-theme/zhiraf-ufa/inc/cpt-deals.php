<?php
/**
 * CPT deal — temporary hot departures for messengers (noindex).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register deal post type.
 */
function zhiraf_ufa_register_deal_cpt() {
	register_post_type(
		'deal',
		array(
			'labels'              => array(
				'name'          => __( 'Акции / вылеты', 'zhiraf-ufa' ),
				'singular_name' => __( 'Акция', 'zhiraf-ufa' ),
				'add_new_item'  => __( 'Добавить акцию', 'zhiraf-ufa' ),
				'edit_item'     => __( 'Редактировать акцию', 'zhiraf-ufa' ),
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'rest_base'           => 'deals',
			'menu_position'       => 23,
			'menu_icon'           => 'dashicons-megaphone',
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'     => 'post',
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'       => 'deals',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'zhiraf_ufa_register_deal_cpt' );

/**
 * Meta keys exposed in REST.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_deal_meta_keys() {
	return array(
		'country_slug'   => 'string',
		'departure_date' => 'string',
		'nights'         => 'integer',
		'price_from'     => 'integer',
		'hotel_name'     => 'string',
		'meal_type'      => 'string',
		'expires_at'     => 'string',
	);
}

/**
 * Register meta for REST.
 */
function zhiraf_ufa_register_deal_meta() {
	foreach ( zhiraf_ufa_deal_meta_keys() as $key => $type ) {
		register_post_meta(
			'deal',
			$key,
			array(
				'show_in_rest'   => true,
				'single'         => true,
				'type'           => 'integer' === $type ? 'integer' : 'string',
				'auth_callback'  => static function () {
					return current_user_can( 'edit_posts' );
				},
				'default'        => 'integer' === $type ? 0 : '',
			)
		);
	}
}
add_action( 'init', 'zhiraf_ufa_register_deal_meta', 20 );

/**
 * @param int $post_id Post ID.
 * @return array<string,mixed>
 */
function zhiraf_ufa_deal_get_meta( $post_id ) {
	$out = array();
	foreach ( zhiraf_ufa_deal_meta_keys() as $key => $type ) {
		$val = get_post_meta( $post_id, $key, true );
		if ( 'integer' === $type ) {
			$val = (int) $val;
		} else {
			$val = (string) $val;
		}
		$out[ $key ] = $val;
	}
	return $out;
}

/**
 * Single deal template.
 *
 * @param string $template Template.
 * @return string
 */
function zhiraf_ufa_deal_single_template( $template ) {
	if ( is_singular( 'deal' ) ) {
		$custom = ZHIRAF_UFA_DIR . '/single-deal.php';
		if ( is_readable( $custom ) ) {
			return $custom;
		}
	}
	return $template;
}
add_filter( 'template_include', 'zhiraf_ufa_deal_single_template', 100 );

/**
 * Exclude deal from core sitemaps.
 *
 * @param array<string,bool> $post_types Post types.
 * @return array<string,bool>
 */
function zhiraf_ufa_deal_exclude_sitemap( $post_types ) {
	unset( $post_types['deal'] );
	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'zhiraf_ufa_deal_exclude_sitemap' );
