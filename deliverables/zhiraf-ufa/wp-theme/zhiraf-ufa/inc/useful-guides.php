<?php
/**
 * «Полезные гиды» на посадочных направлений — нормализация blog_links.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slug → осмысленный ярлык рубрики (без «Без рубрики»).
 *
 * @param string $slug Post slug.
 * @return string
 */
function zhiraf_ufa_useful_guide_tag_from_slug( $slug ) {
	$slug = sanitize_title( (string) $slug );
	$map  = array(
		'turciya-ili-egipet-iz-ufy'        => __( 'СРАВНЕНИЕ', 'zhiraf-ufa' ),
		'tury-s-detmi-iz-ufy'              => __( 'СЕМЕЙНЫЙ ОТДЫХ', 'zhiraf-ufa' ),
		'kak-kupit-tur-v-ufe'              => __( 'ИНСТРУКЦИЯ', 'zhiraf-ufa' ),
		'chto-vhodit-v-tur-vse-vklyucheno' => __( 'ГИД ПО НАПРАВЛЕНИЮ', 'zhiraf-ufa' ),
		'dokumenty-dlya-zagranicy-iz-ufy'  => __( 'СОВЕТЫ ЭКСПЕРТОВ', 'zhiraf-ufa' ),
		'strahovka-i-rassrochka-na-tur'    => __( 'СОВЕТЫ ЭКСПЕРТОВ', 'zhiraf-ufa' ),
	);

	return ! empty( $map[ $slug ] ) ? (string) $map[ $slug ] : __( 'ГИД ЭКСПЕРТОВ', 'zhiraf-ufa' );
}

/**
 * @param int $post_id Post ID.
 * @return string
 */
function zhiraf_ufa_useful_guide_tag_for_post( $post_id ) {
	$post_id = (int) $post_id;
	if ( $post_id <= 0 ) {
		return __( 'ГИД ЭКСПЕРТОВ', 'zhiraf-ufa' );
	}

	$slug = get_post_field( 'post_name', $post_id );
	if ( is_string( $slug ) && '' !== $slug ) {
		return zhiraf_ufa_useful_guide_tag_from_slug( $slug );
	}

	$cats = get_the_category( $post_id );
	if ( ! empty( $cats ) ) {
		foreach ( $cats as $cat ) {
			if ( ! $cat instanceof WP_Term ) {
				continue;
			}
			$name = trim( (string) $cat->name );
			$slug_cat = sanitize_title( (string) $cat->slug );
			if ( in_array( $slug_cat, array( 'uncategorized', 'bez-rubriki', 'bez-rubrici' ), true ) ) {
				continue;
			}
			if ( '' === $name || preg_match( '/^(без\s*рубрики|uncategorized)$/iu', $name ) ) {
				continue;
			}
			return mb_strtoupper( $name, 'UTF-8' );
		}
	}

	return __( 'ГИД ЭКСПЕРТОВ', 'zhiraf-ufa' );
}

/**
 * @param string $url URL.
 * @return string Post slug or empty.
 */
function zhiraf_ufa_useful_guide_slug_from_url( $url ) {
	$path = wp_parse_url( (string) $url, PHP_URL_PATH );
	if ( ! is_string( $path ) || '' === $path ) {
		return '';
	}
	$path = trim( $path, '/' );
	if ( preg_match( '#/blog/([^/]+)/?$#', $path, $m ) ) {
		return sanitize_title( $m[1] );
	}

	return '';
}

/**
 * @param string $slug Article slug.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_useful_guide_static_row( $slug ) {
	if ( ! function_exists( 'zhiraf_ufa_excalibur_article_static_catalog' ) ) {
		return null;
	}
	$catalog = zhiraf_ufa_excalibur_article_static_catalog();
	$slug    = sanitize_title( (string) $slug );

	return ! empty( $catalog[ $slug ] ) && is_array( $catalog[ $slug ] ) ? $catalog[ $slug ] : null;
}

/**
 * @param array<string,mixed> $item blog_links row.
 * @return array{title:string,url:string,tag:string,read_time:string,image:string,post_id:int}
 */
function zhiraf_ufa_useful_guide_normalize_item( array $item ) {
	$url = ! empty( $item['url'] ) ? (string) $item['url'] : '';

	$post_id = ! empty( $item['post_id'] ) ? (int) $item['post_id'] : 0;
	if ( $post_id <= 0 && '' !== $url && function_exists( 'url_to_postid' ) ) {
		$post_id = (int) url_to_postid( $url );
	}

	$slug   = zhiraf_ufa_useful_guide_slug_from_url( $url );
	$static = $slug ? zhiraf_ufa_useful_guide_static_row( $slug ) : null;

	$title = '';
	if ( ! empty( $item['title'] ) ) {
		$title = (string) $item['title'];
	} elseif ( ! empty( $item['label'] ) ) {
		$title = (string) $item['label'];
	} elseif ( $post_id > 0 ) {
		$title = get_the_title( $post_id );
	} elseif ( $static && ! empty( $static['title'] ) ) {
		$title = (string) $static['title'];
	}

	$tag = '';
	if ( ! empty( $item['tag'] ) ) {
		$tag = mb_strtoupper( trim( (string) $item['tag'] ), 'UTF-8' );
	} elseif ( $post_id > 0 ) {
		$tag = zhiraf_ufa_useful_guide_tag_for_post( $post_id );
	} elseif ( '' !== $slug ) {
		$tag = zhiraf_ufa_useful_guide_tag_from_slug( $slug );
	} else {
		$tag = __( 'ГИД ЭКСПЕРТОВ', 'zhiraf-ufa' );
	}

	$read_time = '';
	if ( ! empty( $item['read_time'] ) ) {
		$read_time = (string) $item['read_time'];
	} elseif ( $post_id > 0 && function_exists( 'zhiraf_ufa_blog_card_from_post' ) ) {
		$post = get_post( $post_id );
		if ( $post instanceof WP_Post ) {
			$card      = zhiraf_ufa_blog_card_from_post( $post );
			$read_time = ! empty( $card['read_minutes'] ) && function_exists( 'zhiraf_ufa_read_minutes_label' )
				? zhiraf_ufa_read_minutes_label( (int) $card['read_minutes'] )
				: '';
		}
	} elseif ( $static && ! empty( $static['char_count'] ) && function_exists( 'zhiraf_ufa_read_minutes_label' ) && function_exists( 'zhiraf_ufa_read_minutes_from_chars' ) ) {
		$read_time = zhiraf_ufa_read_minutes_label( zhiraf_ufa_read_minutes_from_chars( (int) $static['char_count'] ) );
	}
	if ( '' === $read_time ) {
		$read_time = __( '3 мин чтения', 'zhiraf-ufa' );
	}

	$image_file = '';
	if ( ! empty( $item['image'] ) ) {
		$image_file = (string) $item['image'];
	} elseif ( $post_id > 0 ) {
		$thumb_url = get_the_post_thumbnail_url( $post_id, 'medium_large' );
		if ( is_string( $thumb_url ) && '' !== $thumb_url ) {
			return array(
				'title'     => $title,
				'url'       => '' !== $url ? $url : get_permalink( $post_id ),
				'tag'       => $tag,
				'read_time' => $read_time,
				'image'     => $thumb_url,
				'post_id'   => $post_id,
				'image_is_url' => true,
			);
		}
		if ( function_exists( 'zhiraf_ufa_blog_card_from_post' ) ) {
			$post = get_post( $post_id );
			if ( $post instanceof WP_Post ) {
				$card       = zhiraf_ufa_blog_card_from_post( $post );
				$image_file = ! empty( $card['image'] ) ? (string) $card['image'] : '';
			}
		}
	}
	if ( '' === $image_file && $static && ! empty( $static['image'] ) ) {
		$image_file = (string) $static['image'];
	}
	if ( '' === $image_file ) {
		$image_file = 'blog-cover-01.jpg';
	}

	$image_out = $image_file;
	if ( ! empty( $item['image_is_url'] ) || 0 === strpos( $image_file, 'http://' ) || 0 === strpos( $image_file, 'https://' ) ) {
		$image_out = $image_file;
	} elseif ( function_exists( 'zhiraf_ufa_theme_image' ) ) {
		$img       = zhiraf_ufa_theme_image( $image_file, $title );
		$image_out = ! empty( $img['url'] ) ? (string) $img['url'] : ( ZHIRAF_UFA_URI . '/assets/images/' . ltrim( $image_file, '/' ) );
	} else {
		$image_out = ZHIRAF_UFA_URI . '/assets/images/' . ltrim( $image_file, '/' );
	}

	return array(
		'title'          => $title,
		'url'            => '' !== $url ? $url : ( $post_id > 0 ? get_permalink( $post_id ) : '#' ),
		'tag'            => $tag,
		'read_time'      => $read_time,
		'image'          => $image_out,
		'post_id'        => $post_id,
		'image_is_url'   => true,
	);
}

/**
 * @param array<int,array<string,mixed>> $blog_links From destination config.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_prepare_useful_guides( array $blog_links ) {
	$guides = array();
	foreach ( $blog_links as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}
		if ( empty( $item['url'] ) && empty( $item['label'] ) && empty( $item['title'] ) ) {
			continue;
		}
		$guides[] = zhiraf_ufa_useful_guide_normalize_item( $item );
	}

	return $guides;
}