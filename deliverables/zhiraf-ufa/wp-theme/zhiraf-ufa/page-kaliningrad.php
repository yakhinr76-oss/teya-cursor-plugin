<?php
/**
 * Destination landing: /tour/kaliningrad/.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
if ( function_exists( 'zhiraf_ufa_render_excursion_curated_landing' ) ) {
	zhiraf_ufa_render_excursion_curated_landing( 'kaliningrad' );
} elseif ( function_exists( 'zhiraf_ufa_render_destination_landing' ) ) {
	zhiraf_ufa_render_destination_landing( 'kaliningrad' );
}
get_footer();