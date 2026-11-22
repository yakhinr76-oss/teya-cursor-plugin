<?php
/**
 * Indexable tour URLs for sitemap (virtual resorts + CSV-aligned paths).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Destination slugs without a live theme route (excluded from sitemap + url-map status backlog).
 *
 * @return string[]
 */
function zhiraf_ufa_sitemap_backlog_destination_slugs() {
	return array( 'kuba', 'dominikana', 'tunis', 'mauritius' );
}

/**
 * All tour landing paths with trailing slash (routing truth, not marketing CSV).
 *
 * @return string[]
 */
function zhiraf_ufa_sitemap_indexable_tour_paths() {
	static $paths = null;
	if ( null !== $paths ) {
		return $paths;
	}

	$paths = array( '/goryashchie-tury/', '/vse-vklyucheno/' );
	$block = array_flip( zhiraf_ufa_sitemap_backlog_destination_slugs() );

	if ( function_exists( 'zhiraf_ufa_destination_landing_slugs' ) ) {
		foreach ( zhiraf_ufa_destination_landing_slugs() as $slug ) {
			$slug = sanitize_title( (string) $slug );
			if ( '' === $slug || isset( $block[ $slug ] ) ) {
				continue;
			}
			$paths[] = '/tour/' . $slug . '/';
		}
	}

	if ( function_exists( 'zhiraf_ufa_resort_landing_index' ) ) {
		foreach ( zhiraf_ufa_resort_landing_index() as $row ) {
			if ( empty( $row['country'] ) || empty( $row['resort'] ) ) {
				continue;
			}
			$paths[] = '/tour/' . sanitize_title( (string) $row['country'] ) . '/' . sanitize_title( (string) $row['resort'] ) . '/';
		}
	}

	$retired = array();
	if ( function_exists( 'zhiraf_ufa_retired_resort_landing_redirects' ) ) {
		foreach ( array_keys( zhiraf_ufa_retired_resort_landing_redirects() ) as $from ) {
			$norm = trailingslashit( (string) $from );
			if ( ! str_starts_with( $norm, '/' ) ) {
				$norm = '/' . $norm;
			}
			$retired[ $norm ] = true;
		}
	}

	$clean = array();
	foreach ( $paths as $path ) {
		$path = trailingslashit( (string) $path );
		if ( ! str_starts_with( $path, '/' ) ) {
			$path = '/' . $path;
		}
		if ( isset( $retired[ $path ] ) ) {
			continue;
		}
		$clean[] = $path;
	}
	$paths = array_values( array_unique( $clean ) );
	sort( $paths );

	return $paths;
}

/**
 * Absolute canonical URLs for tour sitemap entries.
 *
 * @return string[]
 */
function zhiraf_ufa_sitemap_indexable_tour_urls() {
	$urls = array();
	foreach ( zhiraf_ufa_sitemap_indexable_tour_paths() as $path ) {
		$urls[] = function_exists( 'zhiraf_ufa_canonical_public_url' )
			? zhiraf_ufa_canonical_public_url( home_url( $path ) )
			: home_url( $path );
	}

	return $urls;
}

/**
 * Exclude non-indexable CPTs from core sitemaps.
 *
 * @param array<string,bool> $post_types Post types.
 * @return array<string,bool>
 */
function zhiraf_ufa_sitemap_post_types( $post_types ) {
	unset( $post_types['deal'], $post_types['hotels'], $post_types['zhiraf_lead'] );

	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'zhiraf_ufa_sitemap_post_types', 20 );

/**
 * Drop author archives from core sitemaps.
 *
 * @param array<int,WP_User> $users Users.
 * @return array<int,WP_User>
 */
function zhiraf_ufa_sitemap_users_empty( $users ) {
	return array();
}
add_filter( 'wp_sitemaps_users_pre_list', 'zhiraf_ufa_sitemap_users_empty' );

/**
 * Drop taxonomy sitemaps (categories/tags are noindex by policy).
 *
 * @param array<string,WP_Taxonomy> $taxonomies Taxonomies.
 * @return array<string,WP_Taxonomy>
 */
function zhiraf_ufa_sitemap_taxonomies_empty( $taxonomies ) {
	return array();
}
add_filter( 'wp_sitemaps_taxonomies', 'zhiraf_ufa_sitemap_taxonomies_empty' );

/**
 * Core sitemaps provider for virtual /tour/* landings.
 */
class Zhiraf_Ufa_Tour_Landings_Sitemaps_Provider extends WP_Sitemaps_Provider {

	/**
	 * @return string
	 */
	public function get_name() {
		return 'tour-landings';
	}

	/**
	 * @return array|null
	 */
	public function get_object_subtypes() {
		return null;
	}

	/**
	 * @param int    $page_num       Page number.
	 * @param string $object_subtype Unused.
	 * @return array<int,array{loc:string,lastmod?:string}>
	 */
	public function get_url_list( $page_num, $object_subtype = '' ) {
		unset( $object_subtype );
		if ( 1 !== (int) $page_num || ( function_exists( 'zhiraf_ufa_is_staging_host' ) && zhiraf_ufa_is_staging_host() ) ) {
			return array();
		}
		$urls  = zhiraf_ufa_sitemap_indexable_tour_urls();
		$out   = array();
		$stamp = gmdate( 'Y-m-d' );
		foreach ( $urls as $loc ) {
			$out[] = array(
				'loc'     => $loc,
				'lastmod' => $stamp,
			);
		}

		return $out;
	}

	/**
	 * @param string $object_subtype Unused.
	 * @return int
	 */
	public function get_max_num_pages( $object_subtype = '' ) {
		unset( $object_subtype );
		if ( function_exists( 'zhiraf_ufa_is_staging_host' ) && zhiraf_ufa_is_staging_host() ) {
			return 0;
		}
		return empty( zhiraf_ufa_sitemap_indexable_tour_paths() ) ? 0 : 1;
	}
}

/**
 * Register core sitemap provider.
 */
function zhiraf_ufa_register_tour_sitemaps_provider() {
	if ( ! class_exists( 'WP_Sitemaps_Provider' ) || ! function_exists( 'wp_register_sitemap_provider' ) ) {
		return;
	}
	wp_register_sitemap_provider( 'tour-landings', new Zhiraf_Ufa_Tour_Landings_Sitemaps_Provider() );
}
add_action( 'init', 'zhiraf_ufa_register_tour_sitemaps_provider', 25 );

/**
 * Yoast SEO: extra sitemap for virtual tour routes (sitemap_index.xml).
 */
function zhiraf_ufa_register_yoast_tour_sitemap() {
	if ( ! class_exists( 'WPSEO_Sitemaps' ) ) {
		return;
	}
	global $wpseo_sitemaps;
	if ( ! $wpseo_sitemaps || ! method_exists( $wpseo_sitemaps, 'register_sitemap' ) ) {
		return;
	}
	$wpseo_sitemaps->register_sitemap( 'tour-landings', 'zhiraf_ufa_yoast_render_tour_sitemap' );
}
add_action( 'init', 'zhiraf_ufa_register_yoast_tour_sitemap', 99 );

/**
 * Output Yoast tour-landings sitemap body.
 */
function zhiraf_ufa_yoast_render_tour_sitemap() {
	if ( function_exists( 'zhiraf_ufa_is_staging_host' ) && zhiraf_ufa_is_staging_host() ) {
		return;
	}
	global $wpseo_sitemaps;
	if ( ! $wpseo_sitemaps || ! isset( $wpseo_sitemaps->renderer ) ) {
		return;
	}
	$body = '';
	foreach ( zhiraf_ufa_sitemap_indexable_tour_urls() as $loc ) {
		$body .= $wpseo_sitemaps->renderer->sitemap_url(
			array(
				'loc' => $loc,
				'mod' => gmdate( 'c' ),
			)
		);
	}
	$wpseo_sitemaps->set_sitemap( $body );
}