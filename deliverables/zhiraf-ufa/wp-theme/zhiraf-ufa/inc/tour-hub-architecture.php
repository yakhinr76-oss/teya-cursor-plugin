<?php
/**
 * Navigation portal hub: CPT, taxonomies, cached stats, filter URLs.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register tour_hotel CPT and hub taxonomies (hotels CPT uses meta; tour_hotel for public archive).
 */
function zhiraf_ufa_register_tour_hub_architecture() {
	register_post_type(
		'tour_hotel',
		array(
			'labels'              => array(
				'name'          => __( 'Отели и туры', 'zhiraf-ufa' ),
				'singular_name' => __( 'Отель', 'zhiraf-ufa' ),
				'add_new'       => __( 'Добавить отель', 'zhiraf-ufa' ),
				'edit_item'     => __( 'Редактировать отель', 'zhiraf-ufa' ),
			),
			'public'              => true,
			'has_archive'         => 'tours',
			'rewrite'             => array(
				'slug'       => 'tour-item',
				'with_front' => false,
			),
			'supports'            => array( 'title', 'thumbnail', 'custom-fields', 'editor' ),
			'menu_icon'           => 'dashicons-palmtree',
			'show_in_rest'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
		)
	);

	$tax_post_types = array( 'tour_hotel', 'hotels' );

	register_taxonomy(
		'destination_country',
		$tax_post_types,
		array(
			'labels'            => array(
				'name' => __( 'Страны', 'zhiraf-ufa' ),
			),
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'country' ),
			'show_in_rest'      => true,
			'public'            => true,
		)
	);

	register_taxonomy(
		'departure_type',
		$tax_post_types,
		array(
			'labels'            => array(
				'name' => __( 'Тип вылета', 'zhiraf-ufa' ),
			),
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'departure' ),
			'show_in_rest'      => true,
			'public'            => true,
		)
	);

	register_taxonomy(
		'trip_vibe',
		$tax_post_types,
		array(
			'labels'            => array(
				'name' => __( 'Сценарий отдыха', 'zhiraf-ufa' ),
			),
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'vibe' ),
			'show_in_rest'      => true,
			'public'            => true,
		)
	);
}
add_action( 'init', 'zhiraf_ufa_register_tour_hub_architecture', 5 );

/**
 * Default trip_vibe terms (idempotent).
 */
function zhiraf_ufa_seed_hub_taxonomy_terms() {
	if ( ! taxonomy_exists( 'trip_vibe' ) ) {
		return;
	}
	$terms = array(
		'family'   => __( 'Семья с детьми', 'zhiraf-ufa' ),
		'couples'  => __( 'Пары 16+', 'zhiraf-ufa' ),
		'balance'  => __( 'Цена / качество', 'zhiraf-ufa' ),
		'city'     => __( 'Город и шопинг', 'zhiraf-ufa' ),
	);
	foreach ( $terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'trip_vibe' ) ) {
			wp_insert_term( $name, 'trip_vibe', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'init', 'zhiraf_ufa_seed_hub_taxonomy_terms', 20 );

/**
 * Query vars for /tours/ filter hub.
 *
 * @param string[] $vars Vars.
 * @return string[]
 */
function zhiraf_ufa_tour_hub_query_vars( $vars ) {
	$vars[] = 'country';
	$vars[] = 'vibe';
	return $vars;
}
add_filter( 'query_vars', 'zhiraf_ufa_tour_hub_query_vars' );

/**
 * Normalize country slug (TZ english → theme slugs).
 *
 * @param string $slug Raw slug.
 */
function zhiraf_ufa_hub_normalize_country_slug( $slug ) {
	$slug = sanitize_title( (string) $slug );
	$map  = array(
		'turkey'   => 'turciya',
		'uae'      => 'oae',
		'egypt'    => 'egipet',
		'thailand' => 'tailand',
		'maldives' => 'maldivy',
		'sochi'    => 'rossiya',
		'russia'   => 'rossiya',
		'china'    => 'kitaj',
		'india'    => 'indiya',
		'seychelles' => 'sejshely',
		'indonesia' => 'indoneziya',
	);
	return isset( $map[ $slug ] ) ? $map[ $slug ] : $slug;
}

/**
 * Country keys stored on hotels CPT (_hotel_country uses BG codes: turkey, egypt, …).
 *
 * @param string $country_slug Theme landing slug.
 * @return string[]
 */
function zhiraf_ufa_hub_hotel_country_meta_values( $country_slug ) {
	$slug = zhiraf_ufa_hub_normalize_country_slug( $country_slug );
	$map  = array(
		'turciya'    => array( 'turkey', 'turciya' ),
		'oae'        => array( 'uae', 'oae' ),
		'egipet'     => array( 'egypt', 'egipet' ),
		'tailand'    => array( 'thailand', 'tailand' ),
		'vietnam'    => array( 'vietnam' ),
		'maldivy'    => array( 'maldives', 'maldivy' ),
		'kitaj'      => array( 'china', 'kitaj' ),
		'indiya'     => array( 'india', 'indiya' ),
		'shri-lanka' => array( 'sri_lanka', 'shri-lanka' ),
		'indoneziya' => array( 'indonesia', 'indoneziya' ),
		'sejshely'   => array( 'seychelles', 'sejshely' ),
		'uzbekistan' => array( 'uzbekistan' ),
		'armeniya'   => array( 'armenia', 'armeniya' ),
		'rossiya'    => array( 'russia', 'rossiya' ),
		'abkhaziya'  => array( 'abkhazia', 'abkhaziya' ),
	);
	return isset( $map[ $slug ] ) ? $map[ $slug ] : array( $slug );
}

/**
 * Count hotels in curated CPT by country (and optional departure bucket).
 *
 * @param string $country_slug Theme country slug.
 * @param string $departure_slug direct_ufa|via_moscow|any.
 */
function zhiraf_ufa_hub_hotels_cpt_count( $country_slug, $departure_slug = 'any' ) {
	$country_slug = zhiraf_ufa_hub_normalize_country_slug( $country_slug );
	$cache_key    = 'hub_hotels_count_' . $country_slug . '_' . $departure_slug;
	$count        = get_transient( $cache_key );
	if ( false !== $count ) {
		return (int) $count;
	}

	$meta_values = zhiraf_ufa_hub_hotel_country_meta_values( $country_slug );
	$meta_query  = array(
		array(
			'key'     => '_hotel_country',
			'value'   => $meta_values,
			'compare' => 'IN',
		),
	);

	$q = new WP_Query(
		array(
			'post_type'              => 'hotels',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_query'             => $meta_query,
		)
	);
	$count = (int) $q->found_posts;
	set_transient( $cache_key, $count, 6 * HOUR_IN_SECONDS );
	return $count;
}

/**
 * Hub stats: hotel count + min tour price (BG engine).
 *
 * @param string $country_slug Country slug.
 * @param string $departure_slug direct_ufa|via_moscow.
 * @return array{count:int,min_price:string,min_price_int:int|null}
 */
function get_hub_destination_stats( $country_slug, $departure_slug = 'direct_ufa' ) {
	$country_slug   = zhiraf_ufa_hub_normalize_country_slug( $country_slug );
	$departure_slug = in_array( $departure_slug, array( 'direct_ufa', 'via_moscow' ), true ) ? $departure_slug : 'direct_ufa';
	$cache_key      = 'hub_stats_' . $country_slug . '_' . $departure_slug;
	$stats          = get_transient( $cache_key );
	if ( false !== $stats && is_array( $stats ) ) {
		return $stats;
	}

	$count = zhiraf_ufa_hub_hotels_cpt_count( $country_slug );

	// tour_hotel + taxonomies when populated.
	if ( taxonomy_exists( 'destination_country' ) ) {
		$tax_q = new WP_Query(
			array(
				'post_type'      => 'tour_hotel',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'tax_query'      => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'destination_country',
						'field'    => 'slug',
						'terms'    => $country_slug,
					),
					array(
						'taxonomy' => 'departure_type',
						'field'    => 'slug',
						'terms'    => $departure_slug,
					),
				),
			)
		);
		if ( $tax_q->found_posts > 0 ) {
			$count = (int) $tax_q->found_posts;
		}
	}

	$departure = 'via_moscow' === $departure_slug ? 'mow' : 'ufa';
	$min_int   = null;
	if ( function_exists( 'zhiraf_ufa_home_hub_min_tour' ) ) {
		$min = zhiraf_ufa_home_hub_min_tour( $country_slug, $departure );
		if ( $min && ! empty( $min['price_rur'] ) ) {
			$min_int = (int) $min['price_rur'];
		}
	}

	if ( null === $min_int && $count > 0 ) {
		$price_q = new WP_Query(
			array(
				'post_type'      => array( 'tour_hotel', 'hotels' ),
				'post_status'    => 'publish',
				'posts_per_page' => 200,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => '_hotel_country',
						'value'   => $country_slug,
						'compare' => '=',
					),
				),
			)
		);
		foreach ( $price_q->posts as $post_id ) {
			$price = (int) get_post_meta( $post_id, 'tour_min_price', true );
			if ( $price <= 0 ) {
				$price = (int) get_post_meta( $post_id, '_tour_min_price', true );
			}
			if ( $price > 0 && ( null === $min_int || $price < $min_int ) ) {
				$min_int = $price;
			}
		}
	}

	$stats = array(
		'count'         => $count,
		'min_price_int' => $min_int,
		'min_price'     => $min_int ? number_format( $min_int, 0, '', ' ' ) : '—',
	);
	set_transient( $cache_key, $stats, 6 * HOUR_IN_SECONDS );
	return $stats;
}

/**
 * Hotels matching trip_vibe (category_slug on hotels CPT).
 *
 * @param string $vibe_slug family|couples|balance|city.
 */
function zhiraf_ufa_hub_vibe_hotel_count( $vibe_slug ) {
	$vibe_slug = sanitize_key( (string) $vibe_slug );
	$cache_key = 'hub_vibe_count_' . $vibe_slug;
	$cached    = get_transient( $cache_key );
	if ( false !== $cached ) {
		return (int) $cached;
	}

	$count = 0;
	if ( taxonomy_exists( 'trip_vibe' ) ) {
		$term = get_term_by( 'slug', $vibe_slug, 'trip_vibe' );
		if ( $term && ! is_wp_error( $term ) ) {
			$count = (int) $term->count;
		}
	}

	if ( $count <= 0 ) {
		$q = new WP_Query(
			array(
				'post_type'              => 'hotels',
				'post_status'            => 'publish',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'no_found_rows'          => false,
				'update_post_meta_cache' => false,
				'meta_query'             => array(
					array(
						'key'   => '_hotel_category_slug',
						'value' => $vibe_slug,
					),
				),
			)
		);
		$count = (int) $q->found_posts;
	}

	set_transient( $cache_key, $count, 6 * HOUR_IN_SECONDS );
	return $count;
}

/**
 * Filter URL for tours archive hub.
 *
 * @param array<string,string> $args country, vibe slugs.
 */
function zhiraf_ufa_tours_filter_url( $args = array() ) {
	$base = get_post_type_archive_link( 'tour_hotel' );
	if ( ! $base ) {
		$base = home_url( '/tours/' );
	}
	$query = array();
	if ( ! empty( $args['country'] ) ) {
		$query['country'] = zhiraf_ufa_hub_normalize_country_slug( (string) $args['country'] );
	}
	if ( ! empty( $args['vibe'] ) ) {
		$query['vibe'] = sanitize_key( (string) $args['vibe'] );
	}
	return $query ? add_query_arg( $query, $base ) : $base;
}

/**
 * SEO footer interlink rows.
 *
 * @return array<int,array{label:string,url:string}>
 */
function zhiraf_ufa_hub_seo_footer_links() {
	return array(
		array(
			'label' => __( 'Туры в Турцию из Уфы 2026', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/turciya/' ),
		),
		array(
			'label' => __( 'Отели Сиде для отдыха с детьми', 'zhiraf-ufa' ),
			'url'   => zhiraf_ufa_tours_filter_url( array( 'country' => 'turciya', 'vibe' => 'family' ) ),
		),
		array(
			'label' => __( 'Отели 16+ в Кемере', 'zhiraf-ufa' ),
			'url'   => zhiraf_ufa_tours_filter_url( array( 'country' => 'turciya', 'vibe' => 'couples' ) ),
		),
		array(
			'label' => __( 'Прямой рейс Flydubai в ОАЭ', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/oae/' ),
		),
		array(
			'label' => __( 'Шарм-эль-Шейх всё включено', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/egipet/' ),
		),
		array(
			'label' => __( 'Чартеры на Пхукет из Уфы', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/tailand/' ),
		),
		array(
			'label' => __( 'Отели Имеретинки и Красной Поляны', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/rossiya/' ),
		),
		array(
			'label' => __( 'Мальдивы с вылетом через Москву', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/maldivy/' ),
		),
	);
}

/**
 * Hero zero-scroll chips (Ufa departures).
 *
 * @return array<int,array{label:string,url:string,emoji:string}>
 */
function zhiraf_ufa_home_hero_quick_chips() {
	$rows = array(
		array( 'emoji' => '🇹🇷', 'slug' => 'turciya', 'label' => __( 'Турция', 'zhiraf-ufa' ) ),
		array( 'emoji' => '🇦🇪', 'slug' => 'oae', 'label' => __( 'ОАЭ', 'zhiraf-ufa' ) ),
		array( 'emoji' => '🇪🇬', 'slug' => 'egipet', 'label' => __( 'Египет', 'zhiraf-ufa' ) ),
		array( 'emoji' => '🇹🇭', 'slug' => 'tailand', 'label' => __( 'Таиланд', 'zhiraf-ufa' ) ),
		array( 'emoji' => '🇲🇻', 'slug' => 'maldivy', 'label' => __( 'Мальдивы', 'zhiraf-ufa' ) ),
		array( 'emoji' => '🇷🇺', 'slug' => 'rossiya', 'label' => __( 'Сочи', 'zhiraf-ufa' ) ),
	);
	$out = array();
	foreach ( $rows as $row ) {
		$out[] = array(
			'emoji' => $row['emoji'],
			'label' => $row['label'],
			'url'   => zhiraf_ufa_home_hub_destination_url( $row['slug'] ),
		);
	}
	return $out;
}
