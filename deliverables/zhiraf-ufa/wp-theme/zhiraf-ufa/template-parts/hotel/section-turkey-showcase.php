<?php
/**
 * Turkey curated hotels (wrapper).
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
		'country' => 'turkey',
		'title'   => __( '25 эталонных отелей Турции', 'zhiraf-ufa' ),
	)
);
