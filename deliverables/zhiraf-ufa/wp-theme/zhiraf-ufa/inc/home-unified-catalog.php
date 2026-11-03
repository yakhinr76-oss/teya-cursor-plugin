<?php
/**
 * Homepage unified catalog tabs (featured hotels block).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Country tabs for #catalog-tours (exactly 3 cards per tab via featured hotels JSON).
 *
 * @return array<int,array{id:string,label:string}>
 */
function zhiraf_ufa_home_unified_catalog_tabs() {
	return array(
		array(
			'id'    => 'turkey',
			'label' => __( 'Турция', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'uae',
			'label' => __( 'ОАЭ', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'egypt',
			'label' => __( 'Египет', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'thailand',
			'label' => __( 'Таиланд', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'russia',
			'label' => __( 'Россия и Абхазия', 'zhiraf-ufa' ),
		),
	);
}
