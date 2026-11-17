<?php
/**
 * UAE (/tour/oae/) curated landing — catalog scaffold (hotels TBD).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Curated hotel cards for OAE hub (empty until editorial 6-pack is wired).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_oae_catalog_hotels() {
	/**
	 * @param array<int,array<string,mixed>> $hotels
	 */
	return apply_filters( 'zhiraf_ufa_oae_curated_catalog_hotels', array() );
}

/**
 * Copy above curated grid when catalog is non-empty.
 *
 * @return array{title:string,desc:string}
 */
function zhiraf_ufa_oae_catalog_default_copy() {
	return array(
		'title' => __( '6 проверенных отелей в ОАЭ', 'zhiraf-ufa' ),
		'desc'  => __( 'Подбираем под задачу: город у метро, пляж JBR или All Inclusive в Рас-эль-Хайме. Цены — за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
	);
}

/**
 * Default WhatsApp / messenger prefill for UAE resort compare.
 *
 * @return string
 */
function zhiraf_ufa_oae_resort_compare_wa_text() {
	return __( 'Здравствуйте! Сравните, пожалуйста, Дубай и Рас-эль-Хайму под наш бюджет — вылет из Уфы.', 'zhiraf-ufa' );
}