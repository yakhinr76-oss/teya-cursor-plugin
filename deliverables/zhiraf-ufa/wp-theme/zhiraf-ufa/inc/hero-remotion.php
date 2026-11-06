<?php
/**
 * Hero Remotion Player (orbit photos).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve three hero collage image URLs for Remotion / fallback stack.
 *
 * @return array<int,string>
 */
function zhiraf_ufa_hero_orbit_photo_urls() {
	$sets = array(
		array( 'hero-collage-uae.jpg', 'hero-collage-family.jpg', 'hero-collage-active.jpg' ),
		array( 'hero-uae.jpg', 'hero-turkey.jpg', 'hero-egypt.jpg' ),
		array( 'hero-collage-1.jpg', 'hero-collage-2.jpg', 'hero-collage-3.jpg' ),
	);

	foreach ( $sets as $files ) {
		$urls = array();
		foreach ( $files as $file ) {
			$path = ZHIRAF_UFA_DIR . '/assets/images/' . $file;
			if ( ! is_readable( $path ) ) {
				$urls = array();
				break;
			}
			$urls[] = ZHIRAF_UFA_URI . '/assets/images/' . $file;
		}
		if ( count( $urls ) === 3 ) {
			return $urls;
		}
	}

	return array();
}

/**
 * Whether the built Remotion player bundle is available.
 *
 * @return bool
 */
function zhiraf_ufa_hero_remotion_player_available() {
	$js = ZHIRAF_UFA_DIR . '/assets/dist/hero-remotion-player.js';
	return is_readable( $js ) && count( zhiraf_ufa_hero_orbit_photo_urls() ) === 3;
}

/**
 * Enqueue Remotion player on the homepage.
 */
function zhiraf_ufa_enqueue_hero_remotion_player() {
	if ( ! is_front_page() || ! zhiraf_ufa_hero_remotion_player_available() ) {
		return;
	}

	$js_path = ZHIRAF_UFA_DIR . '/assets/dist/hero-remotion-player.js';
	wp_enqueue_script(
		'zhiraf-hero-remotion',
		ZHIRAF_UFA_URI . '/assets/dist/hero-remotion-player.js',
		array(),
		(string) filemtime( $js_path ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_localize_script(
		'zhiraf-hero-remotion',
		'zhirafHeroRemotion',
		array(
			'photos'             => zhiraf_ufa_hero_orbit_photo_urls(),
			'width'              => 520,
			'height'             => 500,
			'fps'                => 30,
			'durationInFrames'   => 360,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'zhiraf_ufa_enqueue_hero_remotion_player', 25 );
