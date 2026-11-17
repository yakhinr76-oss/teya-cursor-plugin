<?php
/**
 * Egypt curated hotels (wrapper).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part(
	'template-parts/hotel/section-hotels-showcase',
	null,
	array(
		'country' => 'egypt',
		'title'   => __( '25 эталонных отелей Египта', 'zhiraf-ufa' ),
	)
);
