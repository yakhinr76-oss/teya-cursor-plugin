<?php
/**
 * Offer landing: all inclusive (/vse-vklyucheno/).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
zhiraf_ufa_render_offer_landing( 'vse-vklyucheno' );
get_footer();
