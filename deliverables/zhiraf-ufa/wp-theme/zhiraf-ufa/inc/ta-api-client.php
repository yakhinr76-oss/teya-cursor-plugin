<?php
/**
 * Tripadvisor Terra HTTP client (server-side only, import / admin).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return string
 */
function zhiraf_ufa_ta_api_key() {
	if ( defined( 'ZHIRAF_TA_API_KEY' ) && ZHIRAF_TA_API_KEY ) {
		return (string) ZHIRAF_TA_API_KEY;
	}
	$key = (string) get_option( 'zhiraf_ta_api_key', '' );
	return $key;
}

/**
 * @return bool
 */
function zhiraf_ufa_ta_is_configured() {
	return '' !== zhiraf_ufa_ta_api_key();
}

/**
 * @param string $path Path beginning with /api/...
 * @param array<string,mixed> $query Query args.
 * @return array{ok:bool,code:int,body:array<string,mixed>|null,raw:string}
 */
function zhiraf_ufa_ta_request( $path, $query = array() ) {
	$key = zhiraf_ufa_ta_api_key();
	if ( '' === $key ) {
		return array(
			'ok'   => false,
			'code' => 0,
			'body' => null,
			'raw'  => 'missing_api_key',
		);
	}
	$path = '/' . ltrim( $path, '/' );
	$url  = 'https://terra.tripadvisor.com' . $path;
	if ( $query ) {
		$url = add_query_arg( $query, $url );
	}
	$res = wp_remote_get(
		$url,
		array(
			'timeout' => 45,
			'headers' => array(
				'Accept'    => 'application/json',
				'X-API-Key' => $key,
			),
		)
	);
	if ( is_wp_error( $res ) ) {
		return array(
			'ok'   => false,
			'code' => 0,
			'body' => null,
			'raw'  => $res->get_error_message(),
		);
	}
	$code = (int) wp_remote_retrieve_response_code( $res );
	$raw  = (string) wp_remote_retrieve_body( $res );
	$body = json_decode( $raw, true );
	if ( ! is_array( $body ) ) {
		$body = null;
	}
	return array(
		'ok'   => $code >= 200 && $code < 300,
		'code' => $code,
		'body' => $body,
		'raw'  => $raw,
	);
}

/**
 * Resolve TA location id by hotel name + resort hint.
 *
 * @param string $hotel_name Display name.
 * @param string $resort     Resort / geo hint.
 * @return array{id:int,url:string,name:string}|null
 */
function zhiraf_ufa_ta_find_location( $hotel_name, $resort ) {
	$clean = preg_replace( '/\s*\d[+★].*$/u', '', $hotel_name );
	$clean = trim( (string) $clean );
	if ( '' === $clean ) {
		$clean = $hotel_name;
	}
	$geo = trim( preg_replace( '/,.*/u', '', $resort ) );
	$res = zhiraf_ufa_ta_request(
		'/api/catalog/locations/search',
		array(
			'version' => 1,
			'query'   => $clean,
			'geo_name' => $geo,
		)
	);
	if ( ! $res['ok'] || empty( $res['body']['data'] ) || ! is_array( $res['body']['data'] ) ) {
		return null;
	}
	$row = $res['body']['data'][0];
	$id  = isset( $row['location_id'] ) ? (int) $row['location_id'] : ( isset( $row['id'] ) ? (int) $row['id'] : 0 );
	if ( $id <= 0 ) {
		return null;
	}
	$url  = ! empty( $row['web_url'] ) ? (string) $row['web_url'] : 'https://www.tripadvisor.com/';
	$name = ! empty( $row['name'] ) ? (string) $row['name'] : $clean;
	return array(
		'id'   => $id,
		'url'  => $url,
		'name' => $name,
	);
}

/**
 * @param int $location_id TA location id.
 * @param int $limit       Max photos.
 * @return array<int,string> Image URLs largest-first.
 */
function zhiraf_ufa_ta_location_photo_urls( $location_id, $limit = 4 ) {
	$res = zhiraf_ufa_ta_request(
		'/api/locations/' . (int) $location_id . '/photos',
		array( 'version' => 1 )
	);
	if ( ! $res['ok'] || empty( $res['body']['data'] ) || ! is_array( $res['body']['data'] ) ) {
		return array();
	}
	$urls = array();
	foreach ( $res['body']['data'] as $photo ) {
		$url = '';
		if ( ! empty( $photo['images']['large']['url'] ) ) {
			$url = (string) $photo['images']['large']['url'];
		} elseif ( ! empty( $photo['images']['original']['url'] ) ) {
			$url = (string) $photo['images']['original']['url'];
		} elseif ( ! empty( $photo['url'] ) ) {
			$url = (string) $photo['url'];
		}
		if ( $url ) {
			$urls[] = $url;
		}
		if ( count( $urls ) >= $limit ) {
			break;
		}
	}
	return $urls;
}

/**
 * Download remote image into uploads with a fixed filename.
 *
 * @param string $url      Source URL.
 * @param string $filename Basename e.g. paloma_oceana_1.jpg.
 * @param int    $post_id  Parent post for attachment.
 * @return int Attachment ID or 0.
 */
function zhiraf_ufa_ta_find_attachment_by_basename( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	if ( '' === $filename ) {
		return 0;
	}
	$q = new WP_Query(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_wp_attached_file',
					'value'   => $filename,
					'compare' => 'LIKE',
				),
			),
		)
	);
	return ! empty( $q->posts[0] ) ? (int) $q->posts[0] : 0;
}

/**
 * Download remote image into uploads with a fixed filename.
 *
 * @param string $url      Source URL.
 * @param string $filename Basename e.g. paloma_oceana_1.jpg.
 * @param int    $post_id  Parent post for attachment.
 * @return int Attachment ID or 0.
 */
function zhiraf_ufa_ta_sideload_image( $url, $filename, $post_id = 0 ) {
	$existing = zhiraf_ufa_ta_find_attachment_by_basename( $filename );
	if ( $existing ) {
		return $existing;
	}
	if ( ! function_exists( 'media_handle_sideload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}
	$tmp = download_url( $url, 60 );
	if ( is_wp_error( $tmp ) ) {
		return 0;
	}
	$file_array = array(
		'name'     => sanitize_file_name( $filename ),
		'tmp_name' => $tmp,
	);
	$id = media_handle_sideload( $file_array, $post_id );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		return 0;
	}
	return (int) $id;
}
