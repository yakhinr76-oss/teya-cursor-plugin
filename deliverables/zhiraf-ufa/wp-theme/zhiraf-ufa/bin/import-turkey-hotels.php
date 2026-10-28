#!/usr/bin/env php
<?php
/**
 * One-time import: turkey_hotels_25.json → CPT hotels + media library.
 *
 * Usage (on server with WordPress):
 *   ZHIRAF_TA_API_KEY=… php bin/import-turkey-hotels.php
 *   php bin/import-turkey-hotels.php --json=/path/to/turkey_hotels_25.json
 *   php bin/import-turkey-hotels.php --skip-photos   (meta only)
 *   php bin/import-turkey-hotels.php --dry-run
 *
 * @package Zhiraf_Ufa
 */

if ( 'cli' !== php_sapi_name() ) {
	fwrite( STDERR, "CLI only.\n" );
	exit( 1 );
}

$theme_dir = dirname( __DIR__ );
$wp_load   = getenv( 'WP_LOAD_PATH' ) ?: '';

$candidates = array_filter(
	array(
		$wp_load,
		$theme_dir . '/../../../../wp-load.php',
		$theme_dir . '/../../../wp-load.php',
		'/var/www/html/wp-load.php',
	)
);

$loaded = false;
foreach ( $candidates as $path ) {
	if ( $path && is_readable( $path ) ) {
		require_once $path;
		$loaded = true;
		break;
	}
}

if ( ! $loaded ) {
	fwrite( STDERR, "Set WP_LOAD_PATH to wp-load.php\n" );
	exit( 1 );
}

require_once $theme_dir . '/inc/ta-api-client.php';
require_once $theme_dir . '/inc/cpt-hotels.php';

$opts      = getopt( '', array( 'json:', 'skip-photos', 'dry-run', 'help' ) );
if ( isset( $opts['help'] ) ) {
	echo "Import turkey_hotels_25.json into CPT hotels.\n";
	exit( 0 );
}

$json_path = ! empty( $opts['json'] ) ? (string) $opts['json'] : zhiraf_ufa_turkey_hotels_json_path();
$skip_photos = isset( $opts['skip-photos'] );
$dry_run     = isset( $opts['dry-run'] );

if ( ! is_readable( $json_path ) ) {
	fwrite( STDERR, "JSON not found: {$json_path}\n" );
	exit( 1 );
}

$rows = json_decode( (string) file_get_contents( $json_path ), true );
if ( ! is_array( $rows ) ) {
	fwrite( STDERR, "Invalid JSON\n" );
	exit( 1 );
}

$ta_ok = zhiraf_ufa_ta_is_configured();
if ( ! $skip_photos && ! $ta_ok ) {
	fwrite( STDERR, "WARN: ZHIRAF_TA_API_KEY missing — use --skip-photos or set key in wp-config.php\n" );
}

echo 'Hotels to import: ' . count( $rows ) . "\n";

foreach ( $rows as $row ) {
	$name = (string) ( $row['hotel_name'] ?? '' );
	echo "\n— {$name}\n";

	if ( $dry_run ) {
		echo "  dry-run OK\n";
		continue;
	}

	$gallery_ids = array();
	$photo_names = ! empty( $row['photos'] ) && is_array( $row['photos'] ) ? $row['photos'] : array();
	$ta_url      = '';
	$ta_id       = 0;

	if ( ! $skip_photos && $ta_ok ) {
		$loc = zhiraf_ufa_ta_find_location( $name, (string) ( $row['resort'] ?? '' ) );
		if ( $loc ) {
			$ta_id  = (int) $loc['id'];
			$ta_url = (string) $loc['url'];
			echo "  TA location {$ta_id}\n";
			$urls = zhiraf_ufa_ta_location_photo_urls( $ta_id, max( 3, count( $photo_names ) ) );
			$urls = array_values( $urls );
			foreach ( $photo_names as $i => $filename ) {
				if ( empty( $urls[ $i ] ) ) {
					echo "  skip photo {$filename} (no URL)\n";
					continue;
				}
				$aid = zhiraf_ufa_ta_sideload_image( $urls[ $i ], $filename, 0 );
				if ( $aid ) {
					$gallery_ids[] = $aid;
					echo "  attached {$filename} (#{$aid})\n";
				} else {
					echo "  FAIL sideload {$filename}\n";
				}
				usleep( 250000 );
			}
		} else {
			echo "  TA location not found\n";
		}
	}

	$row['ta_location_id']  = $ta_id;
	$row['ta_property_url'] = $ta_url;

	$post_id = zhiraf_ufa_hotel_upsert_from_row( $row, $gallery_ids );
	echo $post_id ? "  post #{$post_id}\n" : "  FAIL post\n";
}

echo "\nDone.\n";
