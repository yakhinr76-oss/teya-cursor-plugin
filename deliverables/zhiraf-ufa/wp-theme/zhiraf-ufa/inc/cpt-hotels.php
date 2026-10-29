<?php
/**
 * CPT hotels — curated Turkey showcase (local content only on frontend).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register hotels post type.
 */
function zhiraf_ufa_register_hotels_cpt() {
	register_post_type(
		'hotels',
		array(
			'labels'              => array(
				'name'          => __( 'Отели (витрина)', 'zhiraf-ufa' ),
				'singular_name' => __( 'Отель', 'zhiraf-ufa' ),
				'add_new_item'  => __( 'Добавить отель', 'zhiraf-ufa' ),
				'edit_item'     => __( 'Редактировать отель', 'zhiraf-ufa' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'menu_position'       => 22,
			'menu_icon'           => 'dashicons-building',
			'supports'            => array( 'title', 'thumbnail' ),
			'capability_type'     => 'post',
			'has_archive'         => false,
			'rewrite'             => false,
		)
	);
}
add_action( 'init', 'zhiraf_ufa_register_hotels_cpt' );

/**
 * Meta keys used by hotels CPT.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_hotel_meta_keys() {
	return array(
		'hotel_country'         => 'string',
		'hotel_region'          => 'string',
		'hotel_atoll'           => 'string',
		'hotel_island'          => 'string',
		'hotel_emirate'         => 'string',
		'hotel_resort'          => 'string',
		'hotel_rating'          => 'float',
		'hotel_usp'             => 'string',
		'hotel_pros'            => 'json',
		'hotel_caveat'          => 'string',
		'hotel_reviews_summary' => 'string',
		'hotel_category_slug'   => 'string',
		'hotel_category_name'   => 'string',
		'hotel_source_id'       => 'int',
		'hotel_ta_location_id'  => 'int',
		'hotel_ta_property_url' => 'string',
		'hotel_gallery_ids'     => 'json',
	);
}

/**
 * @param int $post_id Post ID.
 * @return array<string,mixed>
 */
function zhiraf_ufa_hotel_get_meta( $post_id ) {
	$out = array();
	foreach ( zhiraf_ufa_hotel_meta_keys() as $key => $type ) {
		$val = get_post_meta( $post_id, '_' . $key, true );
		if ( 'json' === $type && is_string( $val ) ) {
			$decoded = json_decode( $val, true );
			$val     = is_array( $decoded ) ? $decoded : array();
		} elseif ( 'float' === $type ) {
			$val = (float) $val;
		} elseif ( 'int' === $type ) {
			$val = (int) $val;
		} else {
			$val = (string) $val;
		}
		$out[ $key ] = $val;
	}
	return $out;
}

/**
 * Serialize hotel for REST / frontend.
 *
 * @param WP_Post $post Post.
 * @return array<string,mixed>
 */
function zhiraf_ufa_hotel_to_public_row( $post ) {
	$meta = zhiraf_ufa_hotel_get_meta( $post->ID );
	$gallery_ids = ! empty( $meta['hotel_gallery_ids'] ) && is_array( $meta['hotel_gallery_ids'] )
		? array_map( 'intval', $meta['hotel_gallery_ids'] )
		: array();
	$photos = array();
	foreach ( $gallery_ids as $aid ) {
		$src = wp_get_attachment_image_url( $aid, 'large' );
		if ( $src ) {
			$photos[] = array(
				'id'  => $aid,
				'url' => $src,
				'alt' => (string) get_post_meta( $aid, '_wp_attachment_image_alt', true ),
			);
		}
	}
	if ( empty( $photos ) && has_post_thumbnail( $post->ID ) ) {
		$photos[] = array(
			'id'  => (int) get_post_thumbnail_id( $post->ID ),
			'url' => (string) get_the_post_thumbnail_url( $post->ID, 'large' ),
			'alt' => $post->post_title,
		);
	}
	return array(
		'id'              => (int) $post->ID,
		'source_id'       => (int) ( $meta['hotel_source_id'] ?? 0 ),
		'hotel_name'      => $post->post_title,
		'country'         => (string) ( $meta['hotel_country'] ?? '' ),
		'region'          => (string) ( $meta['hotel_region'] ?? '' ),
		'atoll'           => (string) ( $meta['hotel_atoll'] ?? '' ),
		'island'          => (string) ( $meta['hotel_island'] ?? '' ),
		'emirate'         => (string) ( $meta['hotel_emirate'] ?? '' ),
		'resort'          => (string) ( $meta['hotel_resort'] ?? '' ),
		'rating'          => (float) ( $meta['hotel_rating'] ?? 0 ),
		'usp'             => (string) ( $meta['hotel_usp'] ?? '' ),
		'pros'            => ! empty( $meta['hotel_pros'] ) && is_array( $meta['hotel_pros'] ) ? $meta['hotel_pros'] : array(),
		'caveat'          => (string) ( $meta['hotel_caveat'] ?? '' ),
		'reviews_summary' => (string) ( $meta['hotel_reviews_summary'] ?? '' ),
		'category_slug'   => (string) ( $meta['hotel_category_slug'] ?? '' ),
		'category_name'   => (string) ( $meta['hotel_category_name'] ?? '' ),
		'ta_property_url' => (string) ( $meta['hotel_ta_property_url'] ?? '' ),
		'photos'          => $photos,
	);
}

/**
 * REST: local hotel catalog (no Tripadvisor calls).
 */
function zhiraf_ufa_register_hotels_rest_routes() {
	register_rest_route(
		'zhiraf/v1',
		'/hotels',
		array(
			'methods'             => 'GET',
			'permission_callback' => '__return_true',
			'callback'            => function ( WP_REST_Request $req ) {
				$country  = sanitize_key( (string) $req->get_param( 'country' ) );
				$region   = sanitize_key( (string) $req->get_param( 'region' ) );
				$atoll    = sanitize_key( (string) $req->get_param( 'atoll' ) );
				$island   = sanitize_key( (string) $req->get_param( 'island' ) );
				$emirate  = sanitize_key( (string) $req->get_param( 'emirate' ) );
				$category = sanitize_key( (string) $req->get_param( 'category' ) );
				$args     = array(
					'post_type'      => 'hotels',
					'post_status'    => 'publish',
					'posts_per_page' => 100,
					'orderby'        => 'meta_value_num',
					'meta_key'       => '_hotel_source_id',
					'order'          => 'ASC',
				);
				$meta_query = array( 'relation' => 'AND' );
				if ( $country ) {
					$meta_query[] = array(
						'key'   => '_hotel_country',
						'value' => $country,
					);
				}
				if ( $region ) {
					$meta_query[] = array(
						'key'   => '_hotel_region',
						'value' => $region,
					);
				}
				if ( $atoll ) {
					$meta_query[] = array(
						'key'   => '_hotel_atoll',
						'value' => $atoll,
					);
				}
				if ( $island ) {
					$meta_query[] = array(
						'key'   => '_hotel_island',
						'value' => $island,
					);
				}
				if ( $emirate ) {
					$meta_query[] = array(
						'key'   => '_hotel_emirate',
						'value' => $emirate,
					);
				}
				if ( $category ) {
					$meta_query[] = array(
						'key'   => '_hotel_category_slug',
						'value' => $category,
					);
				}
				if ( count( $meta_query ) > 1 ) {
					$args['meta_query'] = $meta_query;
				}
				$q     = new WP_Query( $args );
				$items = array();
				foreach ( $q->posts as $post ) {
					$items[] = zhiraf_ufa_hotel_to_public_row( $post );
				}
				$categories = array();
				foreach ( $items as $row ) {
					$slug = $row['category_slug'];
					if ( $slug && ! isset( $categories[ $slug ] ) ) {
						$categories[ $slug ] = $row['category_name'];
					}
				}
				return rest_ensure_response(
					array(
						'hotels'     => $items,
						'categories' => $categories,
					)
				);
			},
		)
	);

	register_rest_route(
		'zhiraf/v1',
		'/hotels/(?P<id>\d+)',
		array(
			'methods'             => 'GET',
			'permission_callback' => '__return_true',
			'callback'            => function ( WP_REST_Request $req ) {
				$post = get_post( (int) $req['id'] );
				if ( ! $post || 'hotels' !== $post->post_type || 'publish' !== $post->post_status ) {
					return new WP_Error( 'not_found', __( 'Отель не найден', 'zhiraf-ufa' ), array( 'status' => 404 ) );
				}
				return rest_ensure_response( zhiraf_ufa_hotel_to_public_row( $post ) );
			},
		)
	);
}
add_action( 'rest_api_init', 'zhiraf_ufa_register_hotels_rest_routes' );

/**
 * Admin columns.
 *
 * @param array<string,string> $cols Columns.
 * @return array<string,string>
 */
function zhiraf_ufa_hotels_columns( $cols ) {
	return array(
		'cb'       => $cols['cb'] ?? '',
		'title'    => __( 'Отель', 'zhiraf-ufa' ),
		'category' => __( 'Категория', 'zhiraf-ufa' ),
		'region'   => __( 'Регион', 'zhiraf-ufa' ),
		'resort'   => __( 'Курорт', 'zhiraf-ufa' ),
		'rating'   => __( 'Рейтинг', 'zhiraf-ufa' ),
		'date'     => $cols['date'] ?? __( 'Дата', 'zhiraf-ufa' ),
	);
}
add_filter( 'manage_hotels_posts_columns', 'zhiraf_ufa_hotels_columns' );

/**
 * @param string $col Column.
 * @param int    $post_id ID.
 */
function zhiraf_ufa_hotels_column_render( $col, $post_id ) {
	if ( 'category' === $col ) {
		echo esc_html( (string) get_post_meta( $post_id, '_hotel_category_name', true ) );
	} elseif ( 'region' === $col ) {
		$em = (string) get_post_meta( $post_id, '_hotel_emirate', true );
		$rg = (string) get_post_meta( $post_id, '_hotel_region', true );
		echo esc_html( $em ? $em : $rg );
	} elseif ( 'resort' === $col ) {
		echo esc_html( (string) get_post_meta( $post_id, '_hotel_resort', true ) );
	} elseif ( 'rating' === $col ) {
		echo esc_html( (string) get_post_meta( $post_id, '_hotel_rating', true ) );
	}
}
add_action( 'manage_hotels_posts_custom_column', 'zhiraf_ufa_hotels_column_render', 10, 2 );

/**
 * Find existing hotel by source id + country.
 *
 * @param int    $source_id JSON id.
 * @param string $country   Country slug.
 * @return int Post ID or 0.
 */
function zhiraf_ufa_hotel_find_by_source( $source_id, $country ) {
	$q = new WP_Query(
		array(
			'post_type'      => 'hotels',
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'   => '_hotel_source_id',
					'value' => (int) $source_id,
				),
				array(
					'key'   => '_hotel_country',
					'value' => $country,
				),
			),
			'fields'         => 'ids',
		)
	);
	return ! empty( $q->posts[0] ) ? (int) $q->posts[0] : 0;
}

/**
 * Upsert hotel from JSON row + attachment ids.
 *
 * @param array<string,mixed> $row JSON hotel.
 * @param array<int,int>      $gallery_ids Attachment IDs in order.
 * @return int Post ID.
 */
function zhiraf_ufa_hotel_upsert_from_row( array $row, array $gallery_ids = array() ) {
	$source_id = (int) ( $row['id'] ?? 0 );
	$country   = sanitize_key( (string) ( $row['country'] ?? 'turkey' ) );
	$post_id   = zhiraf_ufa_hotel_find_by_source( $source_id, $country );
	$title     = sanitize_text_field( (string) ( $row['hotel_name'] ?? '' ) );

	$postarr = array(
		'post_type'   => 'hotels',
		'post_title'  => $title,
		'post_status' => 'publish',
	);
	if ( $post_id ) {
		$postarr['ID'] = $post_id;
		wp_update_post( $postarr );
	} else {
		$post_id = (int) wp_insert_post( $postarr, true );
	}
	if ( is_wp_error( $post_id ) || $post_id <= 0 ) {
		return 0;
	}

	$pros = ! empty( $row['pros'] ) && is_array( $row['pros'] ) ? $row['pros'] : array();

	update_post_meta( $post_id, '_hotel_country', $country );
	if ( ! empty( $row['region'] ) ) {
		update_post_meta( $post_id, '_hotel_region', sanitize_key( (string) $row['region'] ) );
	}
	if ( ! empty( $row['atoll'] ) ) {
		$atoll_key = sanitize_key( (string) $row['atoll'] );
		update_post_meta( $post_id, '_hotel_atoll', $atoll_key );
		update_post_meta( $post_id, '_hotel_region', $atoll_key );
	}
	if ( ! empty( $row['island'] ) ) {
		$island_key = sanitize_key( (string) $row['island'] );
		update_post_meta( $post_id, '_hotel_island', $island_key );
		update_post_meta( $post_id, '_hotel_region', $island_key );
	}
	if ( ! empty( $row['emirate'] ) ) {
		update_post_meta( $post_id, '_hotel_emirate', sanitize_key( (string) $row['emirate'] ) );
	}
	update_post_meta( $post_id, '_hotel_resort', sanitize_text_field( (string) ( $row['resort'] ?? '' ) ) );
	update_post_meta( $post_id, '_hotel_rating', (float) ( $row['rating'] ?? 0 ) );
	update_post_meta( $post_id, '_hotel_usp', sanitize_textarea_field( (string) ( $row['usp'] ?? '' ) ) );
	update_post_meta( $post_id, '_hotel_pros', wp_json_encode( array_values( array_map( 'sanitize_text_field', $pros ) ), JSON_UNESCAPED_UNICODE ) );
	update_post_meta( $post_id, '_hotel_caveat', sanitize_textarea_field( (string) ( $row['caveat'] ?? '' ) ) );
	update_post_meta( $post_id, '_hotel_reviews_summary', sanitize_textarea_field( (string) ( $row['reviews_summary'] ?? '' ) ) );
	update_post_meta( $post_id, '_hotel_category_slug', sanitize_key( (string) ( $row['category_slug'] ?? '' ) ) );
	update_post_meta( $post_id, '_hotel_category_name', sanitize_text_field( (string) ( $row['category_name'] ?? '' ) ) );
	update_post_meta( $post_id, '_hotel_source_id', $source_id );
	update_post_meta( $post_id, '_hotel_gallery_ids', wp_json_encode( array_values( array_map( 'intval', $gallery_ids ) ) ) );

	if ( ! empty( $row['ta_location_id'] ) ) {
		update_post_meta( $post_id, '_hotel_ta_location_id', (int) $row['ta_location_id'] );
	}
	if ( ! empty( $row['ta_property_url'] ) ) {
		update_post_meta( $post_id, '_hotel_ta_property_url', esc_url_raw( (string) $row['ta_property_url'] ) );
	}

	if ( ! empty( $gallery_ids[0] ) ) {
		set_post_thumbnail( $post_id, (int) $gallery_ids[0] );
	}

	return $post_id;
}

/**
 * Path to turkey hotels JSON in theme.
 *
 * @return string
 */
function zhiraf_ufa_turkey_hotels_json_path() {
	return zhiraf_ufa_hotels_json_path( 'turkey' );
}

/**
 * Path to curated hotels JSON by country code (turkey, egypt, …).
 *
 * @param string $country Country slug from JSON.
 * @return string
 */
function zhiraf_ufa_hotels_json_path( $country ) {
	$country = sanitize_key( (string) $country );
	$map     = array(
		'turkey' => 'turkey_hotels_25.json',
		'egypt'  => 'egypt_hotels_25.json',
		'uae'      => 'uae_hotels_25.json',
		'thailand' => 'thailand_hotels_25.json',
		'vietnam'  => 'vietnam_hotels_25.json',
		'china'    => 'china_hotels_25.json',
		'india'    => 'india_hotels_25.json',
		'maldives'   => 'maldives_hotels_25.json',
		'seychelles' => 'seychelles_hotels_25.json',
		'sri_lanka'  => 'sri_lanka_hotels_25.json',
		'indonesia'  => 'indonesia_hotels_25.json',
	);
	$file    = ! empty( $map[ $country ] ) ? $map[ $country ] : $country . '_hotels_25.json';
	return ZHIRAF_UFA_DIR . '/data/' . $file;
}

/**
 * Landing slug → hotels REST country filter + copy for showcase block.
 *
 * @return array<string,array{country:string,title:string,region?:string,lead?:string}>
 */
function zhiraf_ufa_hotels_showcase_for_destinations() {
	return array(
		'turciya' => array(
			'country' => 'turkey',
			'title'   => __( '25 эталонных отелей Турции', 'zhiraf-ufa' ),
		),
		'egipet'  => array(
			'country' => 'egypt',
			'title'   => __( '25 эталонных отелей Египта', 'zhiraf-ufa' ),
		),
		'oae'     => array(
			'country' => 'uae',
			'title'   => __( '25 эталонных отелей ОАЭ', 'zhiraf-ufa' ),
			'lead'    => __( 'Дубай, Рас-эль-Хайма, Абу-Даби, Шарджа и Аджман: семейный пляж, город, пары и апартаменты — тексты и фото на нашем сервере.', 'zhiraf-ufa' ),
		),
		'tailand' => array(
			'country' => 'thailand',
			'title'   => __( '25 эталонных отелей Таиланда', 'zhiraf-ufa' ),
			'lead'    => __( 'Пхукет, Паттайя, Као-Лак и Самуи: семьи, пары, первая линия Паттайи и разумный баланс — тексты и фото на нашем сервере.', 'zhiraf-ufa' ),
		),
		'vietnam' => array(
			'country' => 'vietnam',
			'title'   => __( '25 эталонных отелей Вьетнама', 'zhiraf-ufa' ),
			'lead'    => __( 'Камрань, Фукуок, Муйне, Дананг и Хойан, Нячанг: семьи, пары, загородные резорты, город у моря и баланс — тексты и фото на нашем сервере.', 'zhiraf-ufa' ),
		),
		'kitaj'   => array(
			'country' => 'china',
			'title'   => __( '25 эталонных отелей Китая', 'zhiraf-ufa' ),
			'lead'    => __( 'Хайнань, Пекин, Шанхай, Гуанчжоу и Сиань: семейный пляж, пары, город у метро и разумный баланс — тексты и фото на нашем сервере.', 'zhiraf-ufa' ),
		),
		'indiya'  => array(
			'country' => 'india',
			'title'   => __( '25 эталонных отелей Индии', 'zhiraf-ufa' ),
			'lead'    => __( 'Южный и Северный Гоа: семьи, пары, активный север, южная классика и разумный баланс — тексты и фото на нашем сервере.', 'zhiraf-ufa' ),
		),
		'maldivy' => array(
			'country' => 'maldives',
			'title'   => __( '25 эталонных отелей Мальдив', 'zhiraf-ufa' ),
			'lead'    => __( 'Семьи, снорклинг и домашний риф, пары Adults Only, катер от Мале и разумный All Inclusive — тексты и фото на нашем сервере.', 'zhiraf-ufa' ),
		),
		'sejshely' => array(
			'country' => 'seychelles',
			'title'   => __( '25 эталонных отелей Сейшел', 'zhiraf-ufa' ),
			'lead'    => __( 'Маэ, Праслин и Ла-Диг: семьи, пары, вело-остров, Self-Catering и баланс 4–5★ у моря — тексты и фото на нашем сервере.', 'zhiraf-ufa' ),
		),
		'shri-lanka' => array(
			'country' => 'sri_lanka',
			'title'   => __( '25 эталонных отелей Шри-Ланки', 'zhiraf-ufa' ),
			'lead'    => __( 'Юго-Запад, восток и запад: семьи, пары и серф, Бентота и Берувела, черепахи и разумный баланс — тексты и фото на нашем сервере.', 'zhiraf-ufa' ),
		),
		'indoneziya' => array(
			'country' => 'indonesia',
			'title'   => __( '25 эталонных отелей Индонезии (Бали)', 'zhiraf-ufa' ),
			'lead'    => __( 'Нуса-Дуа, Убуд, Семиньяк и Чангу, Санур и Джимбаран: семьи, джунгли, бич-клабы и баланс — тексты и фото на нашем сервере.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Resort landing slug → hotels showcase (country + optional region filter).
 *
 * @return array<string,array{country:string,title:string,region?:string,lead?:string}>
 */
function zhiraf_ufa_hotels_showcase_for_resorts() {
	return array(
		'egipet/hurgada'        => array(
			'country' => 'egypt',
			'region'  => 'hurghada',
			'title'   => __( 'Эталонные отели Хургады и Макади', 'zhiraf-ufa' ),
			'lead'    => __( 'Подборка по формату отдыха на побережье Хургады: тексты и фото — на нашем сервере.', 'zhiraf-ufa' ),
		),
		'egipet/sharm-el-sheyh' => array(
			'country' => 'egypt',
			'region'  => 'sharm',
			'title'   => __( 'Эталонные отели Шарм-эль-Шейха', 'zhiraf-ufa' ),
			'lead'    => __( 'Рифы, пляжи и формат отдыха: редакционные выводы и локальные фото без внешних API.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Modal shell for hotel cards (once per page).
 */
function zhiraf_ufa_render_hotel_modal_shell() {
	static $rendered = false;
	if ( $rendered ) {
		return;
	}
	$rendered = true;
	?>
	<div class="hotel-modal" id="hotel-modal" data-hotel-modal hidden>
		<div class="hotel-modal__backdrop" data-hotel-modal-close tabindex="-1"></div>
		<div class="hotel-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="hotel-modal-title">
			<button type="button" class="hotel-modal__close" data-hotel-modal-close aria-label="<?php esc_attr_e( 'Закрыть', 'zhiraf-ufa' ); ?>">×</button>
			<div class="hotel-modal__body" data-hotel-modal-body></div>
		</div>
	</div>
	<?php
}
