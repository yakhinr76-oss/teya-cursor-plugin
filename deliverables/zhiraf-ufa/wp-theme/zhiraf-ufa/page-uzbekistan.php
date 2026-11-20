<?php
/**
 * Destination landing (Moscow departure): /tour/uzbekistan/.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
if ( function_exists( 'zhiraf_ufa_render_excursion_curated_landing' ) ) {
	zhiraf_ufa_render_excursion_curated_landing( 'uzbekistan' );
} else {
	zhiraf_ufa_render_destination_landing( 'uzbekistan' );
}
get_footer();
