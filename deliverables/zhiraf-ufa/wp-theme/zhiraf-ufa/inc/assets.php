<?php
/**
 * Theme asset helpers and media map.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns theme image URL from assets/images or media map.
 *
 * @param string $filename Basename in assets/images.
 * @param string $alt      Alt text default.
 * @return array{url:string,alt:string,width:int,height:int}
 */
function zhiraf_ufa_theme_image( $filename, $alt = '' ) {
	$path = ZHIRAF_UFA_DIR . '/assets/images/' . ltrim( $filename, '/' );
	$url  = ZHIRAF_UFA_URI . '/assets/images/' . ltrim( $filename, '/' );

	$width  = 800;
	$height = 600;
	if ( is_readable( $path ) ) {
		$size = @getimagesize( $path );
		if ( is_array( $size ) ) {
			$width  = (int) $size[0];
			$height = (int) $size[1];
		}
	}

	return array(
		'url'    => $url,
		'alt'    => $alt,
		'width'  => $width,
		'height' => $height,
	);
}

/**
 * Echo responsive img tag for theme asset.
 *
 * @param string $filename Filename.
 * @param string $alt      Alt.
 * @param string $class    CSS class.
 * @param bool   $lazy     Lazy load.
 * @param bool   $priority High priority LCP.
 */
function zhiraf_ufa_img( $filename, $alt, $class = '', $lazy = true, $priority = false ) {
	$img = zhiraf_ufa_theme_image( $filename, $alt );
	$attrs = array(
		'src'    => $img['url'],
		'alt'    => $img['alt'],
		'width'  => $img['width'],
		'height' => $img['height'],
		'class'  => $class,
	);
	if ( $lazy && ! $priority ) {
		$attrs['loading'] = 'lazy';
	}
	if ( $priority ) {
		$attrs['loading']       = 'eager';
		$attrs['fetchpriority'] = 'high';
	}
	$out = '<img';
	foreach ( $attrs as $k => $v ) {
		if ( '' === $v && 'class' === $k ) {
			continue;
		}
		$out .= sprintf( ' %s="%s"', esc_attr( $k ), esc_attr( $v ) );
	}
	$out .= ' />';
	echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
}

/**
 * Destination cards data.
 *
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_destinations() {
	return array(
		array(
			'slug'  => 'turciya',
			'name'  => __( 'Турция', 'zhiraf-ufa' ),
			'image' => 'dest-turkey.jpg',
			'alt'   => __( 'Турция — отдых на море', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/turciya/' ),
		),
		array(
			'slug'  => 'oae',
			'name'  => __( 'ОАЭ', 'zhiraf-ufa' ),
			'image' => 'dest-uae.jpg',
			'alt'   => __( 'ОАЭ — город и пляж', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/oae/' ),
		),
		array(
			'slug'  => 'egipet',
			'name'  => __( 'Египет', 'zhiraf-ufa' ),
			'image' => 'dest-egypt.jpg',
			'alt'   => __( 'Египет — курорты Красного моря', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/egipet/' ),
		),
		array(
			'slug'  => 'tailand',
			'name'  => __( 'Таиланд', 'zhiraf-ufa' ),
			'image' => 'dest-thailand.jpg',
			'alt'   => __( 'Таиланд — тропический отдых', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/tailand/' ),
		),
		array(
			'slug'  => 'vietnam',
			'name'  => __( 'Вьетнам', 'zhiraf-ufa' ),
			'image' => 'dest-vietnam.jpg',
			'alt'   => __( 'Вьетнам — пляжи и природа', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/vietnam/' ),
		),
	);
}

/**
 * Reading time for posts.
 *
 * @param int|null $post_id Post ID.
 * @return string
 */
function zhiraf_ufa_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
	$min     = max( 1, (int) ceil( $words / 200 ) );
	return sprintf(
		/* translators: %d: minutes */
		_n( '%d мин чтения', '%d мин чтения', $min, 'zhiraf-ufa' ),
		$min
	);
}

/**
 * Editorial blog topics (no fake articles).
 *
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_blog_topics() {
	return array(
		array(
			'title' => __( 'Как купить тур в Уфе: от заявки до вылета', 'zhiraf-ufa' ),
			'topic' => 'BT-01',
			'note'  => __( 'Тема редакционного плана', 'zhiraf-ufa' ),
		),
		array(
			'title' => __( 'Что входит в тур «всё включено»', 'zhiraf-ufa' ),
			'topic' => 'BT-02',
			'note'  => __( 'Тема редакционного плана', 'zhiraf-ufa' ),
		),
		array(
			'title' => __( 'Документы для первой заграницы из Уфы', 'zhiraf-ufa' ),
			'topic' => 'BT-03',
			'note'  => __( 'Тема редакционного плана', 'zhiraf-ufa' ),
		),
		array(
			'title' => __( 'Когда выгоднее бронировать тур из Уфы', 'zhiraf-ufa' ),
			'topic' => 'BT-04',
			'note'  => __( 'Тема редакционного плана', 'zhiraf-ufa' ),
		),
	);
}
