<?php
/**
 * CLI: sync BG prices (optional) and report curated Turkey hotels with API min price.
 *
 * Usage:
 *   WP_LOAD_PATH=/path/to/wp-load.php php bin/report-turkey-bg-prices.php
 *   WP_LOAD_PATH=... php bin/report-turkey-bg-prices.php --sync
 *
 * @package Zhiraf_Ufa
 */

$wp_load = getenv( 'WP_LOAD_PATH' );
if ( ! $wp_load || ! is_readable( $wp_load ) ) {
	fwrite( STDERR, "Set WP_LOAD_PATH to wp-load.php\n" );
	exit( 2 );
}
require_once $wp_load;

$do_sync = in_array( '--sync', $argv ?? array(), true );

if ( $do_sync && class_exists( 'Zhiraf_BG_Price_Engine' ) ) {
	$res = Zhiraf_BG_Price_Engine::get_instance()->sync_all_prices();
	echo 'sync_status=' . ( $res['status'] ?? '' ) . "\n";
	echo 'sync_msg=' . ( $res['message'] ?? '' ) . "\n";
	if ( ! empty( $res['counts']['turciya'] ) ) {
		echo 'turciya_tours=' . (int) $res['counts']['turciya'] . "\n";
	}
}

$json = dirname( __DIR__ ) . '/data/turkey_hotels_25.json';
if ( ! is_readable( $json ) ) {
	fwrite( STDERR, "Missing $json\n" );
	exit( 2 );
}
$rows = json_decode( (string) file_get_contents( $json ), true );
if ( ! is_array( $rows ) ) {
	fwrite( STDERR, "Invalid JSON\n" );
	exit( 2 );
}

$window = function_exists( 'zhiraf_ufa_turciya_curated_departure_window' )
	? zhiraf_ufa_turciya_curated_departure_window()
	: array();
if ( ! empty( $window['label'] ) ) {
	echo 'departure_window=' . (string) $window['label'] . "\n";
}

$with_id = 0;
$priced  = 0;
foreach ( $rows as $row ) {
	if ( empty( $row['bg_hotel_id'] ) ) {
		continue;
	}
	++$with_id;
	$name = (string) ( $row['hotel_name'] ?? '' );
	$hid  = (string) $row['bg_hotel_id'];
	$tour = function_exists( 'zhiraf_ufa_get_hotel_min_tour' )
		? zhiraf_ufa_get_hotel_min_tour( 'turciya', $name, $hid, $window )
		: null;
	if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
		++$priced;
		echo 'PRICE ' . (int) ( $row['id'] ?? 0 ) . ' ' . $name . '=' . (int) $tour['price_rur'] . "\n";
	}
}
echo "matched_with_id=$with_id priced=$priced\n";
