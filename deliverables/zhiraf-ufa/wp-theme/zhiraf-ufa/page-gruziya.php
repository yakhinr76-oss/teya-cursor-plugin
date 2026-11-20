<?php
/**
 * Destination landing: /tour/gruziya/.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
if ( function_exists( 'zhiraf_ufa_render_excursion_curated_landing' ) ) {
	zhiraf_ufa_render_excursion_curated_landing( 'gruziya' );
} elseif ( function_exists( 'zhiraf_ufa_render_destination_landing' ) ) {
	zhiraf_ufa_render_destination_landing( 'gruziya' );
}
get_footer();