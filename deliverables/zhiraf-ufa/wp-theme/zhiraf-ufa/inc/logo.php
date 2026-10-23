<?php
/**
 * Site logo (WP media ID 2671 + local fallback).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Default attachment ID on b2b-tour.ru (logo-1-e1551387858344.png). */
if ( ! defined( 'ZHIRAF_UFA_DEFAULT_LOGO_ID' ) ) {
	define( 'ZHIRAF_UFA_DEFAULT_LOGO_ID', 2671 );
}

if ( ! defined( 'ZHIRAF_UFA_DEFAULT_LOGO_URL' ) ) {
	define(
		'ZHIRAF_UFA_DEFAULT_LOGO_URL',
		'https://b2b-tour.ru/wp-content/uploads/2025/09/logo-1-e1551387858344.png'
	);
}

/**
 * Resolve logo attachment ID: custom_logo → site_logo → default ID if attachment exists.
 *
 * @return int
 */
function zhiraf_ufa_get_logo_attachment_id() {
	$custom = (int) get_theme_mod( 'custom_logo' );
	if ( $custom && wp_attachment_is_image( $custom ) ) {
		return $custom;
	}

	$site = (int) get_option( 'site_logo' );
	if ( $site && wp_attachment_is_image( $site ) ) {
		return $site;
	}

	if ( ZHIRAF_UFA_DEFAULT_LOGO_ID && wp_attachment_is_image( ZHIRAF_UFA_DEFAULT_LOGO_ID ) ) {
		return (int) ZHIRAF_UFA_DEFAULT_LOGO_ID;
	}

	return 0;
}

/**
 * Logo image URL when no attachment is available.
 *
 * @return string
 */
function zhiraf_ufa_get_logo_fallback_src() {
	$local = ZHIRAF_UFA_DIR . '/assets/images/logo-zhiraf-client.png';
	if ( file_exists( $local ) ) {
		return ZHIRAF_UFA_URI . '/assets/images/logo-zhiraf-client.png';
	}
	return ZHIRAF_UFA_DEFAULT_LOGO_URL;
}

/**
 * Output linked logo for header or footer.
 *
 * @param string $context header|footer.
 */
function zhiraf_ufa_render_site_logo( $context = 'header' ) {
	$link_class = ( 'footer' === $context ) ? 'site-footer-logo-link' : 'site-logo-link';
	$alt        = __( 'Туристическое агентство «Жираф»', 'zhiraf-ufa' );
	$img_attrs  = array(
		'class'    => 'site-logo-img',
		'alt'      => $alt,
		'width'    => '167',
		'height'   => '56',
		'decoding' => 'async',
		'loading'  => ( 'footer' === $context ) ? 'lazy' : 'eager',
	);

	printf(
		'<a class="%1$s" href="%2$s" rel="home">',
		esc_attr( $link_class ),
		esc_url( home_url( '/' ) )
	);

	$attachment_id = zhiraf_ufa_get_logo_attachment_id();
	if ( $attachment_id ) {
		echo wp_get_attachment_image( $attachment_id, 'full', false, $img_attrs );
	} else {
		printf(
			'<img src="%1$s" alt="%2$s" width="167" height="56" class="site-logo-img" decoding="async" loading="%3$s" />',
			esc_url( zhiraf_ufa_get_logo_fallback_src() ),
			esc_attr( $alt ),
			esc_attr( $img_attrs['loading'] )
		);
	}

	echo '</a>';
}

/**
 * Keep custom-logo markup within size limits when core outputs it elsewhere.
 *
 * @param string $html Logo HTML.
 * @return string
 */
function zhiraf_ufa_filter_custom_logo( $html ) {
	if ( ! $html ) {
		return $html;
	}
	if ( false === strpos( $html, 'site-logo-img' ) ) {
		$html = str_replace( 'class="custom-logo"', 'class="custom-logo site-logo-img"', $html );
	}
	return $html;
}
add_filter( 'get_custom_logo', 'zhiraf_ufa_filter_custom_logo' );
