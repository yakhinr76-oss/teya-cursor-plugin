<?php
/**
 * Destination landing (Ufa departure): /tour/armeniya/.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
if ( function_exists( 'zhiraf_ufa_render_excursion_curated_landing' ) ) {
	zhiraf_ufa_render_excursion_curated_landing( 'armeniya' );
} else {
	zhiraf_ufa_render_destination_landing( 'armeniya' );
}
get_footer();
