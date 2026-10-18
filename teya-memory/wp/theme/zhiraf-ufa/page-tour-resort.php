<?php
/**
 * Virtual resort landing via rewrite (/tour/{country}/{resort}/).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ctx = zhiraf_ufa_current_resort_context();
if ( ! $ctx ) {
	status_header( 404 );
	nocache_headers();
	get_header();
	echo '<main id="primary" class="site-main"><div class="container"><h1>' . esc_html__( 'Страница не найдена', 'zhiraf-ufa' ) . '</h1></div></main>';
	get_footer();
	exit;
}

get_header();
zhiraf_ufa_render_resort_landing( $ctx['country'], $ctx['resort'] );
get_footer();
