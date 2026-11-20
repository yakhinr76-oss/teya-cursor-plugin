<?php
/**
 * Destination landing: /tour/moskva/.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
if ( function_exists( 'zhiraf_ufa_render_excursion_curated_landing' ) ) {
	zhiraf_ufa_render_excursion_curated_landing( 'moskva' );
} elseif ( function_exists( 'zhiraf_ufa_render_destination_landing' ) ) {
	zhiraf_ufa_render_destination_landing( 'moskva' );
}
get_footer();