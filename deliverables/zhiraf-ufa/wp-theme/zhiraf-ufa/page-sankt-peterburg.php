<?php
/**
 * Destination landing: /tour/sankt-peterburg/.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
if ( function_exists( 'zhiraf_ufa_render_excursion_curated_landing' ) ) {
	zhiraf_ufa_render_excursion_curated_landing( 'sankt-peterburg' );
} elseif ( function_exists( 'zhiraf_ufa_render_destination_landing' ) ) {
	zhiraf_ufa_render_destination_landing( 'sankt-peterburg' );
}
get_footer();