<?php
/**
 * Blog meta row partial.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card = isset( $args['card'] ) ? $args['card'] : array();
$opts = isset( $args['opts'] ) ? $args['opts'] : array();

zhiraf_ufa_blog_meta_row( $card, $opts );
