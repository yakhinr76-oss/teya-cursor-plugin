<?php
/**
 * Contract checks for the Tourvisor price normalizer. No WordPress.
 *
 * php scripts/test_tourvisor_prices.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ );
}

if ( ! function_exists( 'add_action' ) ) {
	/**
	 * @param mixed ...$args Ignored.
	 */
	function add_action( ...$args ) {
		unset( $args );
	}
}

require dirname( __DIR__ ) . '/deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc/tourvisor-prices.php';

$failed = 0;

/**
 * @param bool   $ok Condition.
 * @param string $message Label.
 */
function zhiraf_tv_assert( $ok, $message ) {
	global $failed;
	if ( $ok ) {
		echo "ok  {$message}\n";
		return;
	}
	echo "FAIL {$message}\n";
	++$failed;
}

$map = zhiraf_ufa_tourvisor_normalize_payload(
	array(
		'token'        => 'secret-must-not-leak',
		'generated_at' => '2026-11-15T06:00:00Z',
		'prices'       => array(
			'turkey:1' => array(
				'country_slug'   => 'turkey',
				'zhiraf_id'      => 1,
				'hotel_name'     => 'Paloma Oceana 5★',
				'place'          => 'Сиде, Кумкёй',
				'price_rub'      => 145000,
				'nights'         => 7,
				'departure_date' => '2026-06-12',
			),
			'turkey:1-dearer' => array(
				'country_slug' => 'turkey',
				'zhiraf_id'    => 1,
				'hotel_name'   => 'Paloma Oceana 5★',
				'place'        => 'Сиде',
				'price_rub'    => 160000,
			),
		),
	)
);

zhiraf_tv_assert( empty( $map['error'] ), 'map payload accepted' );
zhiraf_tv_assert( isset( $map['prices']['turkey:1'] ), 'key country:id' );
zhiraf_tv_assert( 145000 === (int) $map['prices']['turkey:1']['price_rub'], 'cheaper duplicate kept' );
zhiraf_tv_assert( ! isset( $map['token'] ) && ! isset( $map['prices']['token'] ), 'token stripped' );
zhiraf_tv_assert( 'Сиде, Кумкёй' === $map['prices']['turkey:1']['place'], 'place kept' );

$list = zhiraf_ufa_tourvisor_normalize_payload(
	array(
		array(
			'country'    => 'turciya',
			'id'         => 2,
			'hotel_name' => 'Papillon Belvil 5*',
			'resort'     => 'Белек',
			'price_rur'  => '198 000',
			'nights'     => 9,
		),
	)
);
zhiraf_tv_assert( empty( $list['error'] ), 'list payload accepted' );
zhiraf_tv_assert( isset( $list['prices']['turkey:2'] ), 'turciya alias and id' );
zhiraf_tv_assert( 198000 === (int) $list['prices']['turkey:2']['price_rub'], 'spaced price_rur parsed' );
zhiraf_tv_assert( 'Белек' === $list['prices']['turkey:2']['place'], 'resort stored as place' );

$keyed = zhiraf_ufa_tourvisor_normalize_payload(
	array(
		'turkey:3' => array(
			'price'      => 175000,
			'hotel_name' => 'Voyage Belek Golf & Spa 5★',
			'place'      => 'Белек',
		),
	)
);
zhiraf_tv_assert( isset( $keyed['prices']['turkey:3'] ), 'bare key turkey:3 fills id' );

$bad = zhiraf_ufa_tourvisor_normalize_payload(
	array(
		'prices' => array(
			array(
				'country_slug' => 'turkey',
				'zhiraf_id'    => 4,
				'price_rub'    => 10,
			),
		),
	)
);
zhiraf_tv_assert( isset( $bad['error'] ) && 'empty' === $bad['error'], 'tiny price rejected' );

$index = zhiraf_ufa_tourvisor_build_index( $map );
$by_id = zhiraf_ufa_tourvisor_lookup_in_index( $index, 'turciya', 1, '' );
zhiraf_tv_assert( is_array( $by_id ) && 145000 === (int) $by_id['price_rub'], 'lookup by id via landing slug' );
$by_name = zhiraf_ufa_tourvisor_lookup_in_index( $index, 'turkey', 0, 'Paloma Oceana 5*' );
zhiraf_tv_assert( is_array( $by_name ) && 1 === (int) $by_name['zhiraf_id'], 'lookup by hotel name' );
$miss = zhiraf_ufa_tourvisor_lookup_in_index( $index, 'turkey', 99, 'Нет такого' );
zhiraf_tv_assert( null === $miss, 'unknown hotel misses' );

zhiraf_tv_assert( false === zhiraf_ufa_tourvisor_token_matches( 'short' ), 'short token rejected' );

$map_path = dirname( __DIR__ ) . '/deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/turkey-tourvisor-hotels.json';
$tv_map   = json_decode( (string) file_get_contents( $map_path ), true );
$tv_rows  = ( is_array( $tv_map ) && isset( $tv_map['hotels'] ) ) ? $tv_map['hotels'] : array();
$tv_ids   = array();
$zh_ids   = array();
foreach ( $tv_rows as $hotel_row ) {
	$tv_ids[] = (int) $hotel_row['tourvisor_id'];
	$zh_ids[] = (int) $hotel_row['zhiraf_id'];
}
zhiraf_tv_assert( 25 === count( $tv_rows ), 'turkey map has 25 hotels' );
zhiraf_tv_assert( 25 === count( array_unique( $tv_ids ) ), 'tourvisor ids are unique' );
zhiraf_tv_assert( range( 1, 25 ) === $zh_ids, 'zhiraf ids are 1..25 in order' );

$from_tv_name = zhiraf_ufa_tourvisor_normalize_payload(
	array(
		'prices' => array(
			array(
				'country_slug' => 'turkey',
				'hotel_name'   => 'Kleopatra Riviera Zen Adults Only 16+',
				'price_rub'    => 121000,
				'place'        => 'Алания-центр',
			),
			array(
				'country_slug' => 'turkey',
				'id'           => 1416,
				'hotel_name'   => 'Paloma Oceana Resort',
				'price_rub'    => 145000,
			),
			array(
				'country_slug' => 'turkey',
				'tourvisor_id' => 1421,
				'hotel_name'   => 'Papillon Belvil',
				'price_rub'    => 198000,
			),
		),
	)
);
zhiraf_tv_assert( isset( $from_tv_name['prices']['turkey:16'] ), 'Riviera Zen title resolves to catalog 16' );
zhiraf_tv_assert( isset( $from_tv_name['prices']['turkey:1'] ) && ! isset( $from_tv_name['prices']['turkey:1416'] ), 'Tourvisor id 1416 is catalog 1' );
zhiraf_tv_assert( isset( $from_tv_name['prices']['turkey:2'] ), 'tourvisor_id 1421 is catalog 2' );

$resolved = zhiraf_ufa_tourvisor_build_index( $from_tv_name );
$oceana   = zhiraf_ufa_tourvisor_lookup_in_index( $resolved, 'turkey', 0, 'Paloma Oceana 5★' );
zhiraf_tv_assert( is_array( $oceana ) && 1 === (int) $oceana['zhiraf_id'], 'catalog Oceana matches Tourvisor Resort title' );
$grida = zhiraf_ufa_tourvisor_lookup_in_index( $resolved, 'turkey', 0, 'Paloma Grida 5★' );
zhiraf_tv_assert( null === $grida, 'Paloma Grida does not take Oceana' );
$zen = zhiraf_ufa_tourvisor_lookup_in_index( $resolved, 'turkey', 0, 'Riviera Zen Hotel 4★' );
zhiraf_tv_assert( is_array( $zen ) && 16 === (int) $zen['zhiraf_id'], 'Riviera Zen matches Kleopatra card' );
$kept = zhiraf_ufa_tourvisor_turkey_resolve_id( 4, 1416, 'Paloma Oceana Resort' );
zhiraf_tv_assert( 4 === $kept, 'explicit catalog id wins over a foreign tourvisor id' );

$egypt_map_path = dirname( __DIR__ ) . '/deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/data/egypt-tourvisor-hotels.json';
$egypt_map      = is_readable( $egypt_map_path ) ? json_decode( (string) file_get_contents( $egypt_map_path ), true ) : array();
$egypt_hotels   = ( is_array( $egypt_map ) && isset( $egypt_map['hotels'] ) && is_array( $egypt_map['hotels'] ) ) ? $egypt_map['hotels'] : array();
zhiraf_tv_assert( 25 === count( $egypt_hotels ), 'egypt map has 25 hotels' );

$egypt_prices = zhiraf_ufa_tourvisor_normalize_payload(
	array(
		'prices' => array(
			'egypt:3' => array(
				'country_slug' => 'egypt',
				'zhiraf_id'    => 3,
				'hotel_name'   => 'Pickalbatros White Beach Resort 5★',
				'place'        => 'Хургада',
				'price_rub'    => 172000,
				'nights'       => 7,
			),
		),
	)
);
zhiraf_tv_assert( empty( $egypt_prices['error'] ), 'egypt price row accepted' );
$egypt_index = zhiraf_ufa_tourvisor_build_index( $egypt_prices );
$eg_white    = zhiraf_ufa_tourvisor_lookup_in_index( $egypt_index, 'egypt', 3, 'Pickalbatros White Beach Resort' );
zhiraf_tv_assert( is_array( $eg_white ) && 172000 === (int) $eg_white['price_rub'], 'egypt:3 lookup by id' );

$starter_card = array(
	'name'           => 'Pickalbatros White Beach Resort 5★',
	'pool_id'        => 'starter-family',
	'json_hotel_id'  => 3,
);
zhiraf_ufa_tourvisor_document_cache( $egypt_prices );
$overlay = zhiraf_ufa_tourvisor_overlay_egypt_card( $starter_card );
zhiraf_tv_assert( ! empty( $overlay['price_source'] ) && 'tourvisor' === $overlay['price_source'], 'egypt overlay uses json_hotel_id when pool_id is non-numeric' );
zhiraf_ufa_tourvisor_document_cache( false );

exit( $failed > 0 ? 1 : 0 );
