<?php
/**
 * Scripts and styles.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front assets.
 */
function zhiraf_ufa_enqueue_assets() {
	$css_path = ZHIRAF_UFA_DIR . '/assets/dist/style.css';
	$js_path  = ZHIRAF_UFA_DIR . '/assets/dist/main.js';
	$css_ver  = file_exists( $css_path ) ? (string) filemtime( $css_path ) : ZHIRAF_UFA_VERSION;
	$js_ver   = file_exists( $js_path ) ? (string) filemtime( $js_path ) : ZHIRAF_UFA_VERSION;

	wp_enqueue_style(
		'zhiraf-ufa-style',
		ZHIRAF_UFA_URI . '/assets/dist/style.css',
		array(),
		$css_ver
	);

	wp_enqueue_script(
		'zhiraf-ufa-main',
		ZHIRAF_UFA_URI . '/assets/dist/main.js',
		array(),
		$js_ver,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_localize_script(
		'zhiraf-ufa-main',
		'zhirafUfa',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'zhiraf_ufa_forms' ),
			'privacyUrl' => home_url( '/politika-konfidenczialnosti/' ),
			'cookiesUrl' => home_url( '/politika-cookies/' ),
			'hotelsRest' => rest_url( 'zhiraf/v1/hotels' ),
		)
	);

	if ( is_front_page() ) {
		$motion = ZHIRAF_UFA_DIR . '/assets/js/motion-home.js';
		wp_enqueue_script(
			'zhiraf-gsap',
			ZHIRAF_UFA_URI . '/assets/vendor/gsap/gsap.min.js',
			array(),
			'3.12.5',
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
		wp_enqueue_script(
			'zhiraf-gsap-scroll',
			ZHIRAF_UFA_URI . '/assets/vendor/gsap/ScrollTrigger.min.js',
			array( 'zhiraf-gsap' ),
			'3.12.5',
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
		wp_enqueue_script(
			'zhiraf-motion-home',
			ZHIRAF_UFA_URI . '/assets/js/motion-home.js',
			array( 'zhiraf-gsap', 'zhiraf-gsap-scroll' ),
			file_exists( $motion ) ? (string) filemtime( $motion ) : ZHIRAF_UFA_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		$three_path = ZHIRAF_UFA_DIR . '/assets/vendor/three/three.min.js';
		if ( file_exists( $three_path ) ) {
			wp_enqueue_script(
				'zhiraf-three',
				ZHIRAF_UFA_URI . '/assets/vendor/three/three.min.js',
				array(),
				'0.170.0',
				array(
					'strategy'  => 'defer',
					'in_footer' => true,
				)
			);
			$ambient = ZHIRAF_UFA_DIR . '/assets/js/motion-hero-ambient.js';
			wp_enqueue_script(
				'zhiraf-motion-ambient',
				ZHIRAF_UFA_URI . '/assets/js/motion-hero-ambient.js',
				array( 'zhiraf-three' ),
				file_exists( $ambient ) ? (string) filemtime( $ambient ) : ZHIRAF_UFA_VERSION,
				array(
					'strategy'  => 'defer',
					'in_footer' => true,
				)
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'zhiraf_ufa_enqueue_assets' );

/**
 * Inline font faces — Manrope + Cormorant Garamond (local, no Google CDN).
 */
function zhiraf_ufa_font_faces() {
	$uri = ZHIRAF_UFA_URI;
	$css = "
@font-face{font-family:'Manrope';src:url('{$uri}/assets/fonts/manrope/Manrope-Cyrillic-Variable.woff2') format('woff2');font-weight:200 800;font-style:normal;font-display:swap;unicode-range:U+0301,U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;}
@font-face{font-family:'Manrope';src:url('{$uri}/assets/fonts/manrope/Manrope-Variable.woff2') format('woff2');font-weight:200 800;font-style:normal;font-display:swap;unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD;}
@font-face{font-family:'Cormorant Garamond';src:url('{$uri}/assets/fonts/cormorant-garamond/CormorantGaramond-SemiBold.woff2') format('woff2');font-weight:600;font-style:normal;font-display:swap;unicode-range:U+0301,U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;}
@font-face{font-family:'Cormorant Garamond';src:url('{$uri}/assets/fonts/cormorant-garamond/CormorantGaramond-Bold.woff2') format('woff2');font-weight:700;font-style:normal;font-display:swap;unicode-range:U+0301,U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116;}
";
	wp_add_inline_style( 'zhiraf-ufa-style', $css );
}
add_action( 'wp_enqueue_scripts', 'zhiraf_ufa_font_faces', 20 );
