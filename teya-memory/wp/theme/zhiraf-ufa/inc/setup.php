<?php
/**
 * Theme setup.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sets up theme defaults and registers support for WordPress features.
 */
function zhiraf_ufa_setup() {
	load_theme_textdomain( 'zhiraf-ufa', ZHIRAF_UFA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Главное меню', 'zhiraf-ufa' ),
			'footer'  => __( 'Меню в подвале', 'zhiraf-ufa' ),
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Подвал — колонка 1', 'zhiraf-ufa' ),
			'id'            => 'footer-1',
			'description'   => __( 'Дополнительный блок в подвале.', 'zhiraf-ufa' ),
			'before_widget' => '<div class="footer-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer-widget__title">',
			'after_title'   => '</h3>',
		)
	);

	add_image_size( 'zhiraf-card', 600, 750, true );
	add_image_size( 'zhiraf-hero', 1200, 900, true );
	add_image_size( 'zhiraf-blog', 800, 450, true );
}
add_action( 'after_setup_theme', 'zhiraf_ufa_setup' );

/**
 * Default menu fallback.
 */
function zhiraf_ufa_primary_menu_fallback() {
	$items = array(
		home_url( '/tour/' )           => __( 'Туры', 'zhiraf-ufa' ),
		home_url( '/o-nas/' )          => __( 'О нас', 'zhiraf-ufa' ),
		home_url( '/blog/' )           => __( 'Блог', 'zhiraf-ufa' ),
		home_url( '/kontakty/' )       => __( 'Контакты', 'zhiraf-ufa' ),
	);
	echo '<ul class="primary-menu">';
	foreach ( $items as $url => $label ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}
