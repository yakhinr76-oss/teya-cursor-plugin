<?php
/**
 * Theme Customizer.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function zhiraf_ufa_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'zhiraf_ufa_brand',
		array(
			'title' => __( 'Жираф — бренд', 'zhiraf-ufa' ),
		)
	);

	$wp_customize->add_setting(
		'zhiraf_footer_copyright',
		array(
			'default'           => '© ' . gmdate( 'Y' ) . ' ООО «Жираф». Туры из Уфы.',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'zhiraf_footer_copyright',
		array(
			'label'   => __( 'Копирайт в подвале', 'zhiraf-ufa' ),
			'section' => 'zhiraf_ufa_brand',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'zhiraf_yandex_verification',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'zhiraf_yandex_verification',
		array(
			'label'   => __( 'Yandex verification', 'zhiraf-ufa' ),
			'section' => 'zhiraf_ufa_brand',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'zhiraf_metrika_id',
		array(
			'default'           => '45658173',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'zhiraf_metrika_id',
		array(
			'label'       => __( 'Yandex Metrika ID', 'zhiraf-ufa' ),
			'description' => __( 'Пустое значение отключает счётчик (staging). По умолчанию: 45658173.', 'zhiraf-ufa' ),
			'section'     => 'zhiraf_ufa_brand',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'zhiraf_cta_text',
		array(
			'default'           => 'Подобрать тур',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'zhiraf_cta_text',
		array(
			'label'   => __( 'Текст CTA', 'zhiraf-ufa' ),
			'section' => 'zhiraf_ufa_brand',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'zhiraf_ufa_customize_register' );
