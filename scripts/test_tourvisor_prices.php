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

exit( $failed > 0 ? 1 : 0 );
