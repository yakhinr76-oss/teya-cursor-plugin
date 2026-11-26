<?php
/**
 * Homepage quiz destination tiles (step 1).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array<int,array{value:string,label:string,subtitle:string,icon:string}>
 */
function zhiraf_ufa_home_quiz_destinations() {
	return array(
		array(
			'value'    => __( 'Турция', 'zhiraf-ufa' ),
			'label'    => __( 'Турция', 'zhiraf-ufa' ),
			'subtitle' => __( 'Анталья, Кемер, Сиде', 'zhiraf-ufa' ),
			'icon'     => 'sun',
		),
		array(
			'value'    => __( 'ОАЭ', 'zhiraf-ufa' ),
			'label'    => __( 'ОАЭ', 'zhiraf-ufa' ),
			'subtitle' => __( 'Дубай, Рас-эль-Хайма', 'zhiraf-ufa' ),
			'icon'     => 'building-2',
		),
		array(
			'value'    => __( 'Египет', 'zhiraf-ufa' ),
			'label'    => __( 'Египет', 'zhiraf-ufa' ),
			'subtitle' => __( 'Шарм, Хургада', 'zhiraf-ufa' ),
			'icon'     => 'sun',
		),
		array(
			'value'    => __( 'Таиланд', 'zhiraf-ufa' ),
			'label'    => __( 'Таиланд', 'zhiraf-ufa' ),
			'subtitle' => __( 'Пхукет, Паттайя', 'zhiraf-ufa' ),
			'icon'     => 'palmtree',
		),
		array(
			'value'    => __( 'Россия/Абхазия', 'zhiraf-ufa' ),
			'label'    => __( 'Россия / Абхазия', 'zhiraf-ufa' ),
			'subtitle' => __( 'Сочи, Гагра, КМВ', 'zhiraf-ufa' ),
			'icon'     => 'mountain',
		),
		array(
			'value'    => __( 'Пока не решили', 'zhiraf-ufa' ),
			'label'    => __( 'Нужен совет', 'zhiraf-ufa' ),
			'subtitle' => __( 'Подберите лучший вариант', 'zhiraf-ufa' ),
			'icon' => 'compass',
		),
	);
}
