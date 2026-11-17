#!/usr/bin/env php
<?php
/**
 * One-time import: hotels JSON → CPT hotels + media library.
 *
 * Usage (on server with WordPress):
 *   php bin/import-hotels.php --country=turkey
 *   php bin/import-hotels.php --country=egypt
 *   php bin/import-hotels.php --country=uae
 *   php bin/import-hotels.php --country=thailand
 *   php bin/import-hotels.php --country=vietnam
 *   php bin/import-hotels.php --country=china
 *   php bin/import-hotels.php --country=india
 *   php bin/import-hotels.php --country=maldives
 *   php bin/import-hotels.php --country=seychelles
 *   php bin/import-hotels.php --country=sri_lanka
 *   php bin/import-hotels.php --country=indonesia
 *   php bin/import-hotels.php --country=uzbekistan
 *   php bin/import-hotels.php --country=armenia
 *   php bin/import-hotels.php --country=russia
 *   php bin/import-hotels.php --country=abkhazia
 *   php bin/import-hotels.php --json=/path/to/hotels.json
 *   php bin/import-hotels.php --country=egypt --skip-photos
 *   php bin/import-hotels.php --dry-run --country=turkey
 *
 * Optional JSON photo URLs (Content API enrichment):
 *   python3 bin/fetch-hotel-photos-content-api.py --dry-run
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

$opts = getopt( '', array( 'json:', 'country:', 'skip-photos', 'dry-run', 'help' ) );
if ( isset( $opts['help'] ) ) {
	echo "Import hotels JSON into CPT hotels.\n";
	echo "  --country=turkey|egypt|uae|thailand|vietnam|china|india|maldives|seychelles|sri_lanka|indonesia|uzbekistan|armenia|russia|abkhazia  default JSON in theme/data/\n";
	echo "  --json=path             explicit JSON file\n";
	exit( 0 );
}

$country     = ! empty( $opts['country'] ) ? sanitize_key( (string) $opts['country'] ) : 'turkey';
$json_path   = ! empty( $opts['json'] ) ? (string) $opts['json'] : zhiraf_ufa_hotels_json_path( $country );
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

echo 'Import: ' . basename( $json_path ) . ' (' . count( $rows ) . " hotels)\n";

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
	$ta_id       = (int) ( $row['ta_location_id'] ?? 0 );
	$has_remote  = false;
	foreach ( $photo_names as $entry ) {
		if ( is_string( $entry ) && preg_match( '#^https?://#i', $entry ) ) {
			$has_remote = true;
			break;
		}
	}

	if ( ! $skip_photos && ( $ta_ok || $has_remote ) ) {
		$resort = (string) ( $row['resort'] ?? '' );
		if ( $ta_ok && $ta_id <= 0 ) {
			$loc = zhiraf_ufa_ta_find_location( $name, $resort );
			if ( $loc ) {
				$ta_id  = (int) $loc['id'];
				$ta_url = (string) $loc['url'];
				echo "  TA location {$ta_id}\n";
			}
		} elseif ( $ta_id > 0 ) {
			echo "  TA location {$ta_id} (from JSON)\n";
		} else {
			echo "  sideload from JSON photo URLs\n";
		}

		$urls = array();
		if ( $ta_ok && $ta_id > 0 ) {
			$urls = array_values( zhiraf_ufa_ta_location_photo_urls( $ta_id, max( 3, count( $photo_names ) ) ) );
		}
		foreach ( $photo_names as $i => $filename ) {
			$download_url = '';
			$basename     = (string) $filename;
			if ( preg_match( '#^https?://#i', $basename ) ) {
				$download_url = $basename;
				$path         = wp_parse_url( $download_url, PHP_URL_PATH );
				$parsed       = is_string( $path ) ? basename( $path ) : '';
				$basename     = $parsed ? sanitize_file_name( $parsed ) : sanitize_file_name( 'hotel_' . (int) ( $row['id'] ?? 0 ) . '_' . ( $i + 1 ) . '.jpg' );
			} elseif ( ! empty( $urls[ $i ] ) ) {
				$download_url = (string) $urls[ $i ];
			}
			if ( '' === $download_url ) {
				echo "  skip photo {$filename} (no URL)\n";
				continue;
			}
			$aid = zhiraf_ufa_ta_sideload_image( $download_url, $basename, 0 );
			if ( $aid ) {
				$gallery_ids[] = $aid;
				echo "  attached {$basename} (#{$aid})\n";
			} else {
				echo "  FAIL sideload {$basename}\n";
			}
			usleep( 250000 );
		}
	} elseif ( ! $skip_photos && ! $ta_ok && ! $has_remote ) {
		echo "  WARN: no TA key and no http photos — use --skip-photos or enrich JSON\n";
	}

	$row['ta_location_id']  = $ta_id;
	$row['ta_property_url'] = $ta_url;

	$post_id = zhiraf_ufa_hotel_upsert_from_row( $row, $gallery_ids );
	echo $post_id ? "  post #{$post_id}\n" : "  FAIL post\n";
}

echo "\nDone.\n";
