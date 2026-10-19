<?php
/**
 * Destination landings priced from MOSCOW (owner-approved 19.10.2026).
 *
 * ТЕКСТ НА ПРОВЕРКУ: intros are deliberately short and factual (only what follows
 * from Biblio-Globus data: departure city, resort, package = flight + hotel for 2 adults).
 * No climate/visa/flight-time claims — add after editorial review.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds landing rows for Moscow-departure countries.
 *
 * @return array<string,array<string,mixed>>
 */
function zhiraf_ufa_moscow_landing_data() {
	$rows = array(
		'kitaj'      => array( __( 'Китай', 'zhiraf-ufa' ), __( 'Китай (Санья, о. Хайнань)', 'zhiraf-ufa' ), __( 'в Китай (Санья)', 'zhiraf-ufa' ) ),
		'indiya'     => array( __( 'Индия', 'zhiraf-ufa' ), __( 'Индия (Гоа)', 'zhiraf-ufa' ), __( 'в Индию (Гоа)', 'zhiraf-ufa' ) ),
		'shri-lanka' => array( __( 'Шри-Ланка', 'zhiraf-ufa' ), __( 'Шри-Ланка', 'zhiraf-ufa' ), __( 'на Шри-Ланку', 'zhiraf-ufa' ) ),
		'indoneziya' => array( __( 'Индонезия', 'zhiraf-ufa' ), __( 'Индонезия (Бали)', 'zhiraf-ufa' ), __( 'на Бали', 'zhiraf-ufa' ) ),
		'maldivy'    => array( __( 'Мальдивы', 'zhiraf-ufa' ), __( 'Мальдивы', 'zhiraf-ufa' ), __( 'на Мальдивы', 'zhiraf-ufa' ) ),
		'sejshely'   => array( __( 'Сейшелы', 'zhiraf-ufa' ), __( 'Сейшелы', 'zhiraf-ufa' ), __( 'на Сейшелы', 'zhiraf-ufa' ) ),
		'uzbekistan' => array( __( 'Узбекистан', 'zhiraf-ufa' ), __( 'Узбекистан', 'zhiraf-ufa' ), __( 'в Узбекистан', 'zhiraf-ufa' ) ),
		'armeniya'   => array( __( 'Армения', 'zhiraf-ufa' ), __( 'Армения', 'zhiraf-ufa' ), __( 'в Армению', 'zhiraf-ufa' ) ),
	);
	$out = array();
	foreach ( $rows as $slug => $r ) {
		list( $name, $full, $to ) = $r;
		$out[ $slug ] = array(
			'name'         => $name,
			'needs_review' => true,
			'departure'    => __( 'из Москвы', 'zhiraf-ufa' ),
			'h1'           => sprintf( __( 'Туры %s с вылетом из Москвы', 'zhiraf-ufa' ), $to ),
			'title'        => sprintf( __( 'Туры %s из Москвы — цены туроператора | Жираф', 'zhiraf-ufa' ), $to ),
			'description'  => sprintf( __( '%s: пакетные туры с вылетом из Москвы (перелёт + отель) по ценам туроператора Библио-Глобус. Перелёт Уфа — Москва подберём отдельно.', 'zhiraf-ufa' ), $full ),
			'image'        => '',
			'image_alt'    => $full,
			'hero_lead'    => sprintf( __( 'Направление %s: пакетные туры с перелётом из Москвы и проживанием в отеле по данным туроператора Библио-Глобус. Перелёт из Уфы в Москву в стоимость не входит — менеджер подберёт удобную стыковку.', 'zhiraf-ufa' ), $full ),
			'quiz_prefill' => sprintf( __( '%s, вылет из Москвы', 'zhiraf-ufa' ), $full ),
			'faq'          => array(
				array(
					'q' => __( 'Входит ли в цену перелёт из Уфы?', 'zhiraf-ufa' ),
					'a' => __( 'Нет. Цены на странице рассчитаны туроператором для вылета из Москвы. Перелёт Уфа — Москва — Уфа подбирается и оплачивается отдельно, менеджер поможет состыковать рейсы.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Что входит в указанную цену?', 'zhiraf-ufa' ),
					'a' => __( 'Перелёт из Москвы и проживание в отеле с указанным типом питания для 2 взрослых на указанное число ночей. Трансфер, страховку и другие услуги менеджер уточнит по конкретному предложению; итоговая стоимость фиксируется при бронировании.', 'zhiraf-ufa' ),
				),
			),
		);
	}
	return $out;
}
