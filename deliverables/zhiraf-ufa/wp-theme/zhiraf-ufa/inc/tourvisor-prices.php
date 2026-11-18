<?php
/**
 * Tourvisor price inbox.
 *
 * Parser POSTs output/tourvisor-prices.json to
 * /wp-json/zhiraf/v1/update-prices with header X-Zhiraf-Token.
 * Theme stores the document and overlays Turkey, UAE curated + hotels REST.
 * Tourvisor wins over BG when a row matches.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema written to data/tourvisor-prices.json.
 */
function zhiraf_ufa_tourvisor_schema() {
	return 'zhiraf.tourvisor-prices.v1';
}

/**
 * Landing slug → catalog country_slug used as the price key.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_tourvisor_country_aliases() {
	return array(
		'turkey'     => 'turkey',
		'turciya'    => 'turkey',
		'egypt'      => 'egypt',
		'egipet'     => 'egypt',
		'uae'        => 'uae',
		'oae'        => 'uae',
		'thailand'   => 'thailand',
		'tailand'    => 'thailand',
		'vietnam'    => 'vietnam',
		'vetnam'     => 'vietnam',
		'maldives'   => 'maldives',
		'maldivy'    => 'maldives',
		'india'      => 'india',
		'indiya'     => 'india',
		'indonesia'  => 'indonesia',
		'indoneziya' => 'indonesia',
		'china'      => 'china',
		'kitaj'      => 'china',
		'kitay'      => 'china',
		'russia'     => 'russia',
		'rossiya'    => 'russia',
		'seychelles' => 'seychelles',
		'sejshely'   => 'seychelles',
		'seyshely'   => 'seychelles',
		'sri_lanka'  => 'sri_lanka',
		'shri-lanka' => 'sri_lanka',
		'shrilanka'  => 'sri_lanka',
		'uzbekistan' => 'uzbekistan',
		'uzbekiston' => 'uzbekistan',
		'armenia'    => 'armenia',
		'armeniya'   => 'armenia',
		'abkhazia'   => 'abkhazia',
		'abhaziya'   => 'abkhazia',
	);
}

/**
 * @param string $slug Raw country.
 * @return string
 */
function zhiraf_ufa_tourvisor_country_slug( $slug ) {
	$slug = strtolower( trim( (string) $slug ) );
	$slug = str_replace( array( ' ', '-' ), '_', $slug );
	$slug = preg_replace( '/[^a-z0-9_]/', '', $slug );
	$slug = is_string( $slug ) ? $slug : '';
	$aliases = zhiraf_ufa_tourvisor_country_aliases();
	if ( isset( $aliases[ $slug ] ) ) {
		return $aliases[ $slug ];
	}

	return $slug;
}

/**
 * @param mixed $value Price from JSON.
 * @return int
 */
function zhiraf_ufa_tourvisor_parse_rub( $value ) {
	if ( is_int( $value ) ) {
		return $value;
	}
	if ( is_float( $value ) ) {
		return (int) round( $value );
	}
	$digits = preg_replace( '/[^\d]/', '', (string) $value );

	return (int) $digits;
}

/**
 * @param string $name Hotel title.
 * @return string
 */
function zhiraf_ufa_tourvisor_name_key( $name ) {
	$name = trim( (string) $name );
	if ( function_exists( 'mb_strtolower' ) ) {
		$name = mb_strtolower( $name, 'UTF-8' );
	} else {
		$name = strtolower( $name );
	}
	$name = str_replace( array( '★', '*', '«', '»', '"', '\'', '’', '`' ), '', $name );
	$name = preg_replace( '/[^\p{L}\p{N}\s-]+/u', ' ', $name );
	$name = preg_replace( '/\s+/u', ' ', is_string( $name ) ? $name : '' );

	return trim( (string) $name );
}

/**
 * Tourvisor titles drop stars, "Hotel/Resort" and "(Ex. …)".
 * Same loose key means the same property.
 *
 * @param string $name Hotel title.
 * @return string
 */
function zhiraf_ufa_tourvisor_loose_key( $name ) {
	$name = preg_replace( '/\([^)]*\)/u', ' ', (string) $name );
	$name = strtr(
		is_string( $name ) ? $name : '',
		array(
			'ö' => 'o',
			'ü' => 'u',
			'ğ' => 'g',
			'ş' => 's',
			'ı' => 'i',
			'ç' => 'c',
			'é' => 'e',
			'Ö' => 'o',
			'Ü' => 'u',
			'Ğ' => 'g',
			'Ş' => 's',
			'İ' => 'i',
			'Ç' => 'c',
			'É' => 'e',
			'—' => ' ',
			'–' => ' ',
			'-' => ' ',
		)
	);
	if ( function_exists( 'mb_strtolower' ) ) {
		$name = mb_strtolower( $name, 'UTF-8' );
	} else {
		$name = strtolower( $name );
	}
	$name  = str_replace( array( '★', '*', '+', '&' ), ' ', $name );
	$name  = preg_replace( '/[^\p{L}\s]+/u', ' ', $name );
	$stop  = array(
		'hotel'      => true,
		'hotels'     => true,
		'resort'     => true,
		'spa'        => true,
		'golf'       => true,
		'beach'      => true,
		'club'       => true,
		'the'        => true,
		'by'         => true,
		'and'        => true,
		'adults'     => true,
		'adult'      => true,
		'only'       => true,
		'all'        => true,
		'inclusive'  => true,
		'ex'         => true,
		'collection' => true,
		'villas'     => true,
		'villa'      => true,
	);
	$parts = preg_split( '/\s+/u', trim( (string) $name ) );
	$keep  = array();
	if ( is_array( $parts ) ) {
		foreach ( $parts as $part ) {
			if ( '' === $part || isset( $stop[ $part ] ) ) {
				continue;
			}
			$len = function_exists( 'mb_strlen' ) ? mb_strlen( $part, 'UTF-8' ) : strlen( $part );
			if ( $len < 2 ) {
				continue;
			}
			$keep[] = $part;
		}
	}

	return implode( ' ', $keep );
}

/**
 * Basename of zhiraf_id ↔ tourvisor_id map for a catalog country.
 *
 * @param string $country Normalized country slug (turkey, uae, …).
 * @return string
 */
function zhiraf_ufa_tourvisor_hotels_map_basename( $country ) {
	$country = zhiraf_ufa_tourvisor_country_slug( $country );
	$known   = array(
		'turkey' => 'turkey-tourvisor-hotels.json',
		'uae'    => 'uae-tourvisor-hotels.json',
		'egypt'    => 'egypt-tourvisor-hotels.json',
		'thailand' => 'thailand-tourvisor-hotels.json',
		'vietnam'  => 'vietnam-tourvisor-hotels.json',
	);

	return ! empty( $known[ $country ] ) ? (string) $known[ $country ] : $country . '-tourvisor-hotels.json';
}

/**
 * Curated hub countries with zhiraf_id ↔ Tourvisor map files.
 *
 * @return string[]
 */
function zhiraf_ufa_tourvisor_curated_map_countries() {
	return array( 'turkey', 'uae', 'egypt', 'thailand', 'vietnam' );
}

/**
 * @param string $country Normalized country slug.
 * @return string
 */
function zhiraf_ufa_tourvisor_hotels_map_path( $country ) {
	$dir = defined( 'ZHIRAF_UFA_DIR' ) ? ZHIRAF_UFA_DIR : dirname( __DIR__ );

	return $dir . '/data/' . zhiraf_ufa_tourvisor_hotels_map_basename( $country );
}

/**
 * @return string
 */
function zhiraf_ufa_tourvisor_turkey_map_path() {
	return zhiraf_ufa_tourvisor_hotels_map_path( 'turkey' );
}

/**
 * @return string
 */
function zhiraf_ufa_tourvisor_uae_map_path() {
	return zhiraf_ufa_tourvisor_hotels_map_path( 'uae' );
}

/**
 * @return string
 */
function zhiraf_ufa_tourvisor_egypt_map_path() {
	return zhiraf_ufa_tourvisor_hotels_map_path( 'egypt' );
}

/**
 * Catalog id ↔ Tourvisor hotel id for curated cards.
 *
 * @param string $country Normalized country slug.
 * @return array{by_zhiraf:array<int,array<string,mixed>>,by_tv:array<int,int>,by_loose:array<string,int>}
 */
function zhiraf_ufa_tourvisor_country_map( $country ) {
	static $cache = array();
	$country      = zhiraf_ufa_tourvisor_country_slug( $country );
	if ( isset( $cache[ $country ] ) && is_array( $cache[ $country ] ) ) {
		return $cache[ $country ];
	}

	$map = array(
		'by_zhiraf' => array(),
		'by_tv'     => array(),
		'by_loose'  => array(),
	);
	$path = zhiraf_ufa_tourvisor_hotels_map_path( $country );
	if ( ! is_readable( $path ) ) {
		$cache[ $country ] = $map;

		return $map;
	}
	$data   = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$hotels = ( is_array( $data ) && isset( $data['hotels'] ) && is_array( $data['hotels'] ) ) ? $data['hotels'] : array();
	$owners = array();
	foreach ( $hotels as $hotel ) {
		if ( ! is_array( $hotel ) ) {
			continue;
		}
		$zid = isset( $hotel['zhiraf_id'] ) ? (int) $hotel['zhiraf_id'] : 0;
		$tid = isset( $hotel['tourvisor_id'] ) ? (int) $hotel['tourvisor_id'] : 0;
		if ( $zid <= 0 || $tid <= 0 ) {
			continue;
		}
		$map['by_zhiraf'][ $zid ] = $hotel;
		$map['by_tv'][ $tid ]     = $zid;
		$names                    = array();
		if ( isset( $hotel['match_names'] ) && is_array( $hotel['match_names'] ) ) {
			$names = $hotel['match_names'];
		}
		if ( isset( $hotel['hotel_name'] ) ) {
			$names[] = $hotel['hotel_name'];
		}
		if ( isset( $hotel['tourvisor_name'] ) ) {
			$names[] = $hotel['tourvisor_name'];
		}
		foreach ( $names as $alias ) {
			$loose = zhiraf_ufa_tourvisor_loose_key( $alias );
			if ( '' === $loose ) {
				continue;
			}
			$tokens = substr_count( $loose, ' ' ) + 1;
			if ( $tokens < 2 && strlen( $loose ) < 8 ) {
				continue;
			}
			if ( ! isset( $owners[ $loose ] ) ) {
				$owners[ $loose ] = $zid;
			} elseif ( (int) $owners[ $loose ] !== $zid ) {
				$owners[ $loose ] = 0;
			}
		}
	}
	foreach ( $owners as $loose => $zid ) {
		if ( $zid > 0 ) {
			$map['by_loose'][ $loose ] = (int) $zid;
		}
	}

	$cache[ $country ] = $map;

	return $map;
}

/**
 * Catalog id ↔ Tourvisor hotel id for the 25 Turkey cards.
 *
 * @return array{by_zhiraf:array<int,array<string,mixed>>,by_tv:array<int,int>,by_loose:array<string,int>}
 */
function zhiraf_ufa_tourvisor_turkey_map() {
	return zhiraf_ufa_tourvisor_country_map( 'turkey' );
}

/**
 * @return array{by_zhiraf:array<int,array<string,mixed>>,by_tv:array<int,int>,by_loose:array<string,int>}
 */
function zhiraf_ufa_tourvisor_uae_map() {
	return zhiraf_ufa_tourvisor_country_map( 'uae' );
}

/**
 * @return array{by_zhiraf:array<int,array<string,mixed>>,by_tv:array<int,int>,by_loose:array<string,int>}
 */
function zhiraf_ufa_tourvisor_egypt_map() {
	return zhiraf_ufa_tourvisor_country_map( 'egypt' );
}

/**
 * Prefer the catalog id. A bare Tourvisor hotel id or a Tourvisor title still resolves.
 *
 * @param string $country      Normalized country slug.
 * @param int    $zhiraf_id   Catalog id, or a Tourvisor id the parser sent as id.
 * @param int    $tourvisor_id Tourvisor hotel id when the parser sent it separately.
 * @param string $hotel_name  Title from either side.
 * @return int
 */
function zhiraf_ufa_tourvisor_resolve_catalog_id( $country, $zhiraf_id, $tourvisor_id, $hotel_name ) {
	$map = zhiraf_ufa_tourvisor_country_map( $country );
	$zid = (int) $zhiraf_id;
	$tid = (int) $tourvisor_id;
	if ( $zid > 0 && isset( $map['by_zhiraf'][ $zid ] ) ) {
		return $zid;
	}
	if ( $tid > 0 && isset( $map['by_tv'][ $tid ] ) ) {
		return (int) $map['by_tv'][ $tid ];
	}
	if ( $zid > 0 && isset( $map['by_tv'][ $zid ] ) ) {
		return (int) $map['by_tv'][ $zid ];
	}
	$loose = zhiraf_ufa_tourvisor_loose_key( $hotel_name );
	if ( '' !== $loose && isset( $map['by_loose'][ $loose ] ) ) {
		return (int) $map['by_loose'][ $loose ];
	}

	return $zid;
}

/**
 * @param int    $zhiraf_id Catalog id, or a Tourvisor id the parser sent as id.
 * @param int    $tourvisor_id Tourvisor hotel id when the parser sent it separately.
 * @param string $hotel_name Title from either side.
 * @return int
 */
function zhiraf_ufa_tourvisor_turkey_resolve_id( $zhiraf_id, $tourvisor_id, $hotel_name ) {
	return zhiraf_ufa_tourvisor_resolve_catalog_id( 'turkey', $zhiraf_id, $tourvisor_id, $hotel_name );
}

/**
 * @param int    $zhiraf_id Catalog id.
 * @param int    $tourvisor_id Tourvisor hotel id.
 * @param string $hotel_name Title.
 * @return int
 */
function zhiraf_ufa_tourvisor_uae_resolve_id( $zhiraf_id, $tourvisor_id, $hotel_name ) {
	return zhiraf_ufa_tourvisor_resolve_catalog_id( 'uae', $zhiraf_id, $tourvisor_id, $hotel_name );
}

/**
 * @param int    $zhiraf_id     Catalog id.
 * @param int    $tourvisor_id  Parser hotel id.
 * @param string $hotel_name    Title.
 * @return int
 */
function zhiraf_ufa_tourvisor_egypt_resolve_id( $zhiraf_id, $tourvisor_id, $hotel_name ) {
	return zhiraf_ufa_tourvisor_resolve_catalog_id( 'egypt', $zhiraf_id, $tourvisor_id, $hotel_name );
}

/**
 * @param array<mixed> $value Decoded JSON array.
 * @return bool
 */
function zhiraf_ufa_tourvisor_is_list( array $value ) {
	if ( function_exists( 'array_is_list' ) ) {
		return array_is_list( $value );
	}
	$i = 0;
	foreach ( $value as $key => $_unused ) {
		if ( $key !== $i ) {
			return false;
		}
		++$i;
	}

	return true;
}

/**
 * @param string $value Free text.
 * @param int    $max   Max chars.
 * @return string
 */
function zhiraf_ufa_tourvisor_plain( $value, $max = 180 ) {
	$text = preg_replace( '/<[^>]*>/', '', (string) $value );
	$text = trim( preg_replace( '/\s+/u', ' ', is_string( $text ) ? $text : '' ) );
	if ( function_exists( 'mb_substr' ) ) {
		return mb_substr( $text, 0, $max, 'UTF-8' );
	}

	return substr( $text, 0, $max );
}

/**
 * @param string $value Date.
 * @return string Y-m-d or empty.
 */
function zhiraf_ufa_tourvisor_date( $value ) {
	$value = trim( (string) $value );
	if ( preg_match( '/^(\d{4}-\d{2}-\d{2})/', $value, $m ) ) {
		return $m[1];
	}

	return '';
}

/**
 * @param mixed $row One price row or a keyed wrapper.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_tourvisor_normalize_row( $row ) {
	if ( ! is_array( $row ) ) {
		return null;
	}

	$country = '';
	if ( isset( $row['country_slug'] ) ) {
		$country = zhiraf_ufa_tourvisor_country_slug( $row['country_slug'] );
	} elseif ( isset( $row['country'] ) ) {
		$country = zhiraf_ufa_tourvisor_country_slug( $row['country'] );
	}

	$id = 0;
	if ( isset( $row['zhiraf_id'] ) ) {
		$id = (int) $row['zhiraf_id'];
	} elseif ( isset( $row['id'] ) ) {
		$id = (int) $row['id'];
	}

	$tourvisor_id = 0;
	foreach ( array( 'tourvisor_id', 'hotelcode', 'tv_id' ) as $tv_key ) {
		if ( isset( $row[ $tv_key ] ) && is_numeric( $row[ $tv_key ] ) ) {
			$tourvisor_id = (int) $row[ $tv_key ];
			break;
		}
	}
	$raw_name = isset( $row['hotel_name'] ) ? (string) $row['hotel_name'] : '';
	if ( in_array( $country, zhiraf_ufa_tourvisor_curated_map_countries(), true ) ) {
		$id = zhiraf_ufa_tourvisor_resolve_catalog_id( $country, $id, $tourvisor_id, $raw_name );
	}

	$raw_price = null;
	foreach ( array( 'price_rub', 'price_rur', 'price', 'cost' ) as $price_key ) {
		if ( isset( $row[ $price_key ] ) && ! is_array( $row[ $price_key ] ) ) {
			$raw_price = $row[ $price_key ];
			break;
		}
	}
	$price = zhiraf_ufa_tourvisor_parse_rub( $raw_price );
	if ( '' === $country || $id <= 0 || $price < 1000 || $price > 50000000 ) {
		return null;
	}

	$place = '';
	foreach ( array( 'place', 'resort', 'region' ) as $place_key ) {
		if ( ! empty( $row[ $place_key ] ) && ! is_array( $row[ $place_key ] ) ) {
			$place = zhiraf_ufa_tourvisor_plain( $row[ $place_key ], 120 );
			break;
		}
	}

	$nights = isset( $row['nights'] ) ? (int) $row['nights'] : 0;
	if ( $nights < 0 || $nights > 60 ) {
		$nights = 0;
	}
	$adults = isset( $row['adults'] ) ? (int) $row['adults'] : 2;
	if ( $adults < 1 || $adults > 8 ) {
		$adults = 2;
	}

	return array(
		'country_slug'    => $country,
		'zhiraf_id'       => $id,
		'hotel_name'      => isset( $row['hotel_name'] ) ? zhiraf_ufa_tourvisor_plain( $row['hotel_name'], 180 ) : '',
		'place'           => $place,
		'price_rub'       => $price,
		'nights'          => $nights,
		'adults'          => $adults,
		'departure_city'  => isset( $row['departure_city'] ) ? zhiraf_ufa_tourvisor_plain( $row['departure_city'], 80 ) : '',
		'departure_date'  => isset( $row['departure_date'] ) ? zhiraf_ufa_tourvisor_date( $row['departure_date'] ) : '',
		'meal'            => isset( $row['meal'] ) ? zhiraf_ufa_tourvisor_plain( $row['meal'], 40 ) : '',
		'operator'        => isset( $row['operator'] ) ? zhiraf_ufa_tourvisor_plain( $row['operator'], 80 ) : '',
	);
}

/**
 * Accept the parser document: map, list, or { prices: map|list }.
 *
 * @param mixed $payload Decoded JSON.
 * @return array<string,mixed>|array{error:string,message:string}
 */
function zhiraf_ufa_tourvisor_normalize_payload( $payload ) {
	if ( ! is_array( $payload ) ) {
		return array(
			'error'   => 'invalid_json',
			'message' => 'Тело запроса должно быть JSON-объектом или массивом цен.',
		);
	}

	unset( $payload['token'] );

	$rows = array();
	if ( isset( $payload['prices'] ) && is_array( $payload['prices'] ) ) {
		$rows = $payload['prices'];
	} elseif ( zhiraf_ufa_tourvisor_is_list( $payload ) ) {
		$rows = $payload;
	} else {
		$rows = $payload;
		unset( $rows['schema'], $rows['source'], $rows['currency'], $rows['generated_at'], $rows['received_at'] );
	}

	if ( ! is_array( $rows ) || count( $rows ) > 5000 ) {
		return array(
			'error'   => 'too_many',
			'message' => 'В документе больше 5000 цен.',
		);
	}

	$prices = array();
	foreach ( $rows as $key => $row ) {
		if ( is_array( $row ) && ! isset( $row['country_slug'], $row['zhiraf_id'] ) && is_string( $key ) && preg_match( '/^([a-z0-9_]+):(\d+)$/', $key, $m ) ) {
			$row['country_slug'] = $row['country_slug'] ?? $m[1];
			$row['zhiraf_id']    = $row['zhiraf_id'] ?? (int) $m[2];
		}
		$clean = zhiraf_ufa_tourvisor_normalize_row( $row );
		if ( ! $clean ) {
			continue;
		}
		$price_key = $clean['country_slug'] . ':' . $clean['zhiraf_id'];
		if ( isset( $prices[ $price_key ] ) && (int) $prices[ $price_key ]['price_rub'] <= (int) $clean['price_rub'] ) {
			continue;
		}
		$prices[ $price_key ] = $clean;
	}

	if ( empty( $prices ) ) {
		return array(
			'error'   => 'empty',
			'message' => 'Нет ни одной цены с country_slug, zhiraf_id и price_rub.',
		);
	}

	ksort( $prices );

	$generated = '';
	if ( isset( $payload['generated_at'] ) && is_string( $payload['generated_at'] ) && preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $payload['generated_at'] ) ) {
		$generated = substr( $payload['generated_at'], 0, 40 );
	}

	return array(
		'schema'       => zhiraf_ufa_tourvisor_schema(),
		'source'       => 'tourvisor',
		'currency'     => 'RUB',
		'generated_at' => $generated,
		'prices'       => $prices,
	);
}

/**
 * @param array<string,mixed> $document Normalized document.
 * @return array{by_key:array<string,array<string,mixed>>,by_name:array<string,array<string,string>>}
 */
function zhiraf_ufa_tourvisor_build_index( array $document ) {
	$by_key  = array();
	$by_name = array();
	$prices  = isset( $document['prices'] ) && is_array( $document['prices'] ) ? $document['prices'] : array();
	foreach ( $prices as $key => $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$key = (string) $key;
		$by_key[ $key ] = $row;
		$name_key       = zhiraf_ufa_tourvisor_name_key( isset( $row['hotel_name'] ) ? $row['hotel_name'] : '' );
		$country        = isset( $row['country_slug'] ) ? (string) $row['country_slug'] : '';
		if ( '' === $name_key || '' === $country ) {
			continue;
		}
		if ( ! isset( $by_name[ $country ] ) ) {
			$by_name[ $country ] = array();
		}
		if ( isset( $by_name[ $country ][ $name_key ] ) ) {
			$prev_key = $by_name[ $country ][ $name_key ];
			$prev     = isset( $by_key[ $prev_key ]['price_rub'] ) ? (int) $by_key[ $prev_key ]['price_rub'] : PHP_INT_MAX;
			if ( (int) $row['price_rub'] >= $prev ) {
				continue;
			}
		}
		$by_name[ $country ][ $name_key ] = $key;
		$loose                            = zhiraf_ufa_tourvisor_loose_key( isset( $row['hotel_name'] ) ? $row['hotel_name'] : '' );
		if ( '' === $loose || $loose === $name_key ) {
			continue;
		}
		if ( isset( $by_name[ $country ][ $loose ] ) && $by_name[ $country ][ $loose ] !== $key ) {
			$occupant      = $by_name[ $country ][ $loose ];
			$occupant_name = isset( $by_key[ $occupant ]['hotel_name'] ) ? (string) $by_key[ $occupant ]['hotel_name'] : '';
			if ( zhiraf_ufa_tourvisor_loose_key( $occupant_name ) !== $loose ) {
				continue;
			}
		}
		$row_id      = isset( $row['zhiraf_id'] ) ? (int) $row['zhiraf_id'] : 0;
		$expected    = 0;
		$country_map = in_array( $country, zhiraf_ufa_tourvisor_curated_map_countries(), true ) ? zhiraf_ufa_tourvisor_country_map( $country ) : array();
		if ( isset( $country_map['by_loose'][ $loose ] ) ) {
			$expected = (int) $country_map['by_loose'][ $loose ];
		}
		$same_hotel = ( 0 === $expected ) || $expected === $row_id;
		if ( ! $same_hotel && isset( $country_map['by_tv'][ $row_id ] ) ) {
			$same_hotel = (int) $country_map['by_tv'][ $row_id ] === $expected;
		}
		if ( $same_hotel ) {
			$by_name[ $country ][ $loose ] = $key;
		}
	}

	return array(
		'by_key'  => $by_key,
		'by_name' => $by_name,
	);
}

/**
 * @param array{by_key:array<string,array<string,mixed>>,by_name:array<string,array<string,string>>} $index Index.
 * @param string                                                                                     $country Country slug.
 * @param int                                                                                        $zhiraf_id Catalog id.
 * @param string                                                                                     $hotel_name Hotel title.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_tourvisor_lookup_in_index( array $index, $country, $zhiraf_id, $hotel_name = '' ) {
	$country = zhiraf_ufa_tourvisor_country_slug( $country );
	$id      = (int) $zhiraf_id;
	if ( '' !== $country && $id > 0 ) {
		$key = $country . ':' . $id;
		if ( isset( $index['by_key'][ $key ] ) ) {
			return $index['by_key'][ $key ];
		}
	}

	$name_key = zhiraf_ufa_tourvisor_name_key( $hotel_name );
	if ( '' !== $name_key && '' !== $country && ! empty( $index['by_name'][ $country ][ $name_key ] ) ) {
		$key = $index['by_name'][ $country ][ $name_key ];
		if ( isset( $index['by_key'][ $key ] ) ) {
			return $index['by_key'][ $key ];
		}
	}

	$loose = zhiraf_ufa_tourvisor_loose_key( $hotel_name );
	if ( '' !== $loose && '' !== $country && ! empty( $index['by_name'][ $country ][ $loose ] ) ) {
		$key = $index['by_name'][ $country ][ $loose ];
		if ( isset( $index['by_key'][ $key ] ) ) {
			return $index['by_key'][ $key ];
		}
	}

	if ( in_array( $country, zhiraf_ufa_tourvisor_curated_map_countries(), true ) && '' !== $loose ) {
		$map = zhiraf_ufa_tourvisor_country_map( $country );
		if ( isset( $map['by_loose'][ $loose ] ) ) {
			$key = $country . ':' . (int) $map['by_loose'][ $loose ];
			if ( isset( $index['by_key'][ $key ] ) ) {
				return $index['by_key'][ $key ];
			}
		}
	}

	return null;
}

/**
 * Theme file the parser contract names.
 *
 * @return string
 */
function zhiraf_ufa_tourvisor_prices_path() {
	$dir = defined( 'ZHIRAF_UFA_DIR' ) ? ZHIRAF_UFA_DIR : get_template_directory();

	return $dir . '/data/tourvisor-prices.json';
}

/**
 * @param array<string,mixed>|null $replace Test/runtime swap. Null reads cache.
 * @return array<string,mixed>
 */
function zhiraf_ufa_tourvisor_document_cache( $replace = null ) {
	static $doc = null;
	if ( is_array( $replace ) ) {
		$doc = $replace;
	} elseif ( false === $replace ) {
		$doc = null;
	}
	if ( is_array( $doc ) ) {
		return $doc;
	}

	$file_doc   = array();
	$option_doc = array();
	$path       = zhiraf_ufa_tourvisor_prices_path();
	if ( is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true );
		if ( is_array( $decoded ) ) {
			$file_doc = $decoded;
		}
	}
	if ( function_exists( 'get_option' ) ) {
		$stored = get_option( 'zhiraf_tourvisor_prices_document', array() );
		if ( is_array( $stored ) ) {
			$option_doc = $stored;
		}
	}

	$file_at   = isset( $file_doc['received_at'] ) ? (string) $file_doc['received_at'] : '';
	$option_at = isset( $option_doc['received_at'] ) ? (string) $option_doc['received_at'] : '';
	$doc       = ( '' !== $option_at && $option_at >= $file_at ) ? $option_doc : $file_doc;
	if ( ! isset( $doc['prices'] ) || ! is_array( $doc['prices'] ) ) {
		$doc = array(
			'schema' => zhiraf_ufa_tourvisor_schema(),
			'prices' => array(),
		);
	}

	return $doc;
}

/**
 * @param string $country Country slug.
 * @param int    $zhiraf_id Catalog id.
 * @param string $hotel_name Hotel title.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_tourvisor_find_price( $country, $zhiraf_id = 0, $hotel_name = '' ) {
	$index = zhiraf_ufa_tourvisor_build_index( zhiraf_ufa_tourvisor_document_cache() );
	$hit   = zhiraf_ufa_tourvisor_lookup_in_index( $index, $country, (int) $zhiraf_id, $hotel_name );
	if ( ! is_array( $hit ) ) {
		return null;
	}
	$doc = zhiraf_ufa_tourvisor_document_cache();
	if ( ! empty( $doc['generated_at'] ) ) {
		$hit['generated_at'] = (string) $doc['generated_at'];
	} elseif ( ! empty( $doc['received_at'] ) ) {
		$hit['generated_at'] = (string) $doc['received_at'];
	} else {
		$hit['generated_at'] = '';
	}

	return $hit;
}

/**
 * Tourvisor price on a curated card (Turkey, UAE, …). Unmatched card is returned as-is.
 *
 * @param array<string,mixed> $card    Card row.
 * @param string              $country Catalog country slug (turkey, uae).
 * @return array<string,mixed>
 */
function zhiraf_ufa_tourvisor_overlay_curated_card( array $card, $country ) {
	$country = zhiraf_ufa_tourvisor_country_slug( $country );
	$id      = 0;
	if ( isset( $card['json_hotel_id'] ) && is_numeric( $card['json_hotel_id'] ) ) {
		$id = (int) $card['json_hotel_id'];
	} elseif ( isset( $card['pool_id'] ) && is_numeric( $card['pool_id'] ) ) {
		$id = (int) $card['pool_id'];
	}
	$name = isset( $card['name'] ) ? (string) $card['name'] : '';
	$hit  = zhiraf_ufa_tourvisor_find_price( $country, $id, $name );
	if ( ! is_array( $hit ) ) {
		return $card;
	}

	$rub  = (int) $hit['price_rub'];
	$tour = array(
		'price_rur'      => $rub,
		'hotel_name'     => '' !== $hit['hotel_name'] ? $hit['hotel_name'] : $name,
		'departure_date' => (string) $hit['departure_date'],
		'nights'         => (int) $hit['nights'],
		'price_date'     => (string) $hit['generated_at'],
		'place'          => (string) $hit['place'],
		'source'         => 'tourvisor',
	);

	$card['price_rub']             = $rub;
	$card['price_from_api']        = true;
	$card['price_source']          = 'tourvisor';
	$card['price_scope']           = 'hotel';
	$card['price_api_matched']     = true;
	$card['price_api_unavailable'] = false;
	$card['price_place']           = (string) $hit['place'];
	$card['tour_offer']            = $tour;
	if ( function_exists( 'zhiraf_ufa_turciya_curated_messenger_prefill' ) ) {
		$label              = '' !== $name ? $name : (string) $hit['hotel_name'];
		$uon                = isset( $card['uon_hotel_id'] ) ? (string) $card['uon_hotel_id'] : '';
		$card['wa_prefill'] = zhiraf_ufa_turciya_curated_messenger_prefill( $label, $tour, $uon );
	}

	return $card;
}

/**
 * Tourvisor price on a Turkey card. Unmatched card is returned as-is.
 *
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_tourvisor_overlay_turkey_card( array $card ) {
	return zhiraf_ufa_tourvisor_overlay_curated_card( $card, 'turkey' );
}

/**
 * Tourvisor price on a UAE curated card. Unmatched card is returned as-is.
 *
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_tourvisor_overlay_uae_card( array $card ) {
	return zhiraf_ufa_tourvisor_overlay_curated_card( $card, 'uae' );
}

/**
 * Tourvisor price on an Egypt curated card. Unmatched card is returned as-is.
 *
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_tourvisor_overlay_egypt_card( array $card ) {
	return zhiraf_ufa_tourvisor_overlay_curated_card( $card, 'egypt' );
}

/**
 * Tourvisor price on a Thailand curated card. Unmatched card is returned as-is.
 *
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_tourvisor_overlay_thailand_card( array $card ) {
	return zhiraf_ufa_tourvisor_overlay_curated_card( $card, 'thailand' );
}

/**
 * Tourvisor price on a Vietnam curated card. Unmatched card is returned as-is.
 *
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_tourvisor_overlay_vietnam_card( array $card ) {
	return zhiraf_ufa_tourvisor_overlay_curated_card( $card, 'vietnam' );
}

/**
 * Attach Tourvisor fields onto a zhiraf/v1/hotels row.
 *
 * @param array<string,mixed> $row Public hotel.
 * @return array<string,mixed>
 */
function zhiraf_ufa_tourvisor_attach_hotel_price( array $row ) {
	$country = isset( $row['country'] ) ? (string) $row['country'] : '';
	$id      = isset( $row['source_id'] ) ? (int) $row['source_id'] : 0;
	$name    = isset( $row['hotel_name'] ) ? (string) $row['hotel_name'] : '';
	$hit     = zhiraf_ufa_tourvisor_find_price( $country, $id, $name );

	$row['price_rub']            = null;
	$row['price_currency']       = 'RUB';
	$row['price_source']         = null;
	$row['price_place']          = '';
	$row['price_nights']         = null;
	$row['price_departure_date'] = '';
	if ( ! is_array( $hit ) ) {
		return $row;
	}

	$row['price_rub']            = (int) $hit['price_rub'];
	$row['price_source']         = 'tourvisor';
	$row['price_place']          = (string) $hit['place'];
	$row['price_nights']         = (int) $hit['nights'] > 0 ? (int) $hit['nights'] : null;
	$row['price_departure_date'] = (string) $hit['departure_date'];

	return $row;
}

/**
 * @return string
 */
function zhiraf_ufa_tourvisor_expected_token() {
	if ( defined( 'ZHIRAF_TOURVISOR_WEBHOOK_TOKEN' ) && is_string( ZHIRAF_TOURVISOR_WEBHOOK_TOKEN ) && '' !== ZHIRAF_TOURVISOR_WEBHOOK_TOKEN ) {
		return ZHIRAF_TOURVISOR_WEBHOOK_TOKEN;
	}

	return (string) get_option( 'zhiraf_tourvisor_webhook_token', '' );
}

/**
 * @param string $given Header or body token.
 * @return bool
 */
function zhiraf_ufa_tourvisor_token_matches( $given ) {
	$given = (string) $given;
	if ( strlen( $given ) < 16 ) {
		return false;
	}
	$expected = zhiraf_ufa_tourvisor_expected_token();
	if ( strlen( $expected ) < 16 ) {
		return false;
	}

	return hash_equals( $expected, $given );
}

/**
 * @param WP_REST_Request $request Request.
 * @return true|WP_Error
 */
function zhiraf_ufa_tourvisor_rest_permission( WP_REST_Request $request ) {
	if ( strlen( zhiraf_ufa_tourvisor_expected_token() ) < 16 ) {
		return new WP_Error(
			'token_not_configured',
			__( 'Токен приёма цен не задан в Настройки → Цены Tourvisor.', 'zhiraf-ufa' ),
			array( 'status' => 503 )
		);
	}

	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$fail_key = 'zhiraf_tv_fail_' . md5( $ip );
	if ( (int) get_transient( $fail_key ) >= 20 ) {
		return new WP_Error(
			'rate_limited',
			__( 'Слишком много неверных токенов. Подождите и повторите.', 'zhiraf-ufa' ),
			array( 'status' => 429 )
		);
	}

	$header = (string) $request->get_header( 'x-zhiraf-token' );
	if ( '' === $header ) {
		$auth = (string) $request->get_header( 'authorization' );
		if ( preg_match( '/^Bearer\s+(\S+)/i', $auth, $m ) ) {
			$header = $m[1];
		}
	}
	$body_token = '';
	$json       = $request->get_json_params();
	if ( is_array( $json ) && isset( $json['token'] ) && is_string( $json['token'] ) ) {
		$body_token = $json['token'];
	}
	$given = '' !== $header ? $header : $body_token;
	if ( ! zhiraf_ufa_tourvisor_token_matches( $given ) ) {
		set_transient( $fail_key, (int) get_transient( $fail_key ) + 1, 10 * MINUTE_IN_SECONDS );
		return new WP_Error(
			'forbidden',
			__( 'Неверный токен.', 'zhiraf-ufa' ),
			array( 'status' => 401 )
		);
	}

	return true;
}

/**
 * Persist normalized document to the theme file and to an option.
 *
 * @param array<string,mixed> $document Document.
 * @return array<string,mixed>|WP_Error
 */
function zhiraf_ufa_tourvisor_store_document( array $document ) {
	$document['received_at'] = gmdate( 'c' );
	if ( '' === $document['generated_at'] ) {
		$document['generated_at'] = $document['received_at'];
	}

	$json = wp_json_encode( $document, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
	if ( ! is_string( $json ) ) {
		return new WP_Error( 'encode_failed', __( 'Не удалось сериализовать цены.', 'zhiraf-ufa' ), array( 'status' => 500 ) );
	}

	$path      = zhiraf_ufa_tourvisor_prices_path();
	$dir       = dirname( $path );
	$file_ok   = false;
	$file_error = '';
	if ( ! is_dir( $dir ) && ! wp_mkdir_p( $dir ) ) {
		$file_error = 'data directory missing';
	} else {
		$tmp = $dir . '/.tourvisor-prices.' . wp_generate_password( 8, false ) . '.tmp';
		$written = file_put_contents( $tmp, $json, LOCK_EX );
		if ( false === $written ) {
			$file_error = 'temp write failed';
		} elseif ( ! rename( $tmp, $path ) ) {
			wp_delete_file( $tmp );
			$file_error = 'rename failed';
		} else {
			$file_ok = true;
			if ( function_exists( 'chmod' ) ) {
				chmod( $path, 0644 );
			}
		}
	}

	update_option( 'zhiraf_tourvisor_prices_document', $document, false );
	update_option( 'zhiraf_tourvisor_last_received_at', $document['received_at'], false );
	update_option( 'zhiraf_tourvisor_last_count', count( $document['prices'] ), false );
	zhiraf_ufa_tourvisor_document_cache( false );
	zhiraf_ufa_tourvisor_document_cache( $document );

	return array(
		'ok'           => true,
		'count'        => count( $document['prices'] ),
		'file'         => 'data/tourvisor-prices.json',
		'file_written' => $file_ok,
		'file_error'   => $file_error,
		'received_at'  => $document['received_at'],
		'currency'     => 'RUB',
	);
}

/**
 * POST /zhiraf/v1/update-prices
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function zhiraf_ufa_tourvisor_rest_update( WP_REST_Request $request ) {
	$length = strlen( $request->get_body() );
	if ( $length > 2000000 ) {
		return new WP_Error( 'payload_too_large', __( 'JSON больше 2 МБ.', 'zhiraf-ufa' ), array( 'status' => 413 ) );
	}

	$payload = $request->get_json_params();
	if ( ! is_array( $payload ) ) {
		return new WP_Error( 'invalid_json', __( 'Ожидается JSON.', 'zhiraf-ufa' ), array( 'status' => 400 ) );
	}

	$document = zhiraf_ufa_tourvisor_normalize_payload( $payload );
	if ( isset( $document['error'] ) ) {
		$status = 'too_many' === $document['error'] ? 413 : 422;
		return new WP_Error( $document['error'], $document['message'], array( 'status' => $status ) );
	}

	$stored = zhiraf_ufa_tourvisor_store_document( $document );
	if ( is_wp_error( $stored ) ) {
		return $stored;
	}

	return rest_ensure_response( $stored );
}

/**
 * Register the webhook.
 */
function zhiraf_ufa_tourvisor_register_routes() {
	register_rest_route(
		'zhiraf/v1',
		'/update-prices',
		array(
			'methods'             => 'POST',
			'permission_callback' => 'zhiraf_ufa_tourvisor_rest_permission',
			'callback'            => 'zhiraf_ufa_tourvisor_rest_update',
		)
	);
}
add_action( 'rest_api_init', 'zhiraf_ufa_tourvisor_register_routes' );

/**
 * Settings screen.
 */
function zhiraf_ufa_tourvisor_admin_menu() {
	add_options_page(
		__( 'Цены Tourvisor', 'zhiraf-ufa' ),
		__( 'Цены Tourvisor', 'zhiraf-ufa' ),
		'manage_options',
		'zhiraf-tourvisor-prices',
		'zhiraf_ufa_tourvisor_admin_page'
	);
}
add_action( 'admin_menu', 'zhiraf_ufa_tourvisor_admin_menu' );

/**
 * Save or rotate the webhook token.
 */
function zhiraf_ufa_tourvisor_admin_actions() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( defined( 'ZHIRAF_TOURVISOR_WEBHOOK_TOKEN' ) && is_string( ZHIRAF_TOURVISOR_WEBHOOK_TOKEN ) && '' !== ZHIRAF_TOURVISOR_WEBHOOK_TOKEN ) {
		return;
	}
	if ( empty( $_POST['zhiraf_tourvisor_action'] ) ) {
		return;
	}

	check_admin_referer( 'zhiraf_tourvisor_save', 'zhiraf_tourvisor_nonce' );
	$action = sanitize_key( wp_unslash( $_POST['zhiraf_tourvisor_action'] ) );
	if ( 'generate' === $action ) {
		$token = bin2hex( random_bytes( 24 ) );
		update_option( 'zhiraf_tourvisor_webhook_token', $token, false );
		set_transient( 'zhiraf_tv_reveal_' . get_current_user_id(), $token, MINUTE_IN_SECONDS );
		$msg = 'generated';
	} elseif ( 'save' === $action ) {
		$typed = isset( $_POST['zhiraf_tourvisor_token'] ) ? trim( (string) wp_unslash( $_POST['zhiraf_tourvisor_token'] ) ) : '';
		if ( '' !== $typed ) {
			if ( strlen( $typed ) < 16 || strlen( $typed ) > 200 || ! preg_match( '/^[A-Za-z0-9._~+-]+$/', $typed ) ) {
				$msg = 'bad_token';
			} else {
				update_option( 'zhiraf_tourvisor_webhook_token', $typed, false );
				$msg = 'saved';
			}
		} else {
			$msg = 'unchanged';
		}
	} else {
		return;
	}

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'       => 'zhiraf-tourvisor-prices',
				'zhiraf_msg' => $msg,
			),
			admin_url( 'options-general.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'zhiraf_ufa_tourvisor_admin_actions' );

/**
 * Render settings.
 */
function zhiraf_ufa_tourvisor_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$token     = zhiraf_ufa_tourvisor_expected_token();
	$from_const = defined( 'ZHIRAF_TOURVISOR_WEBHOOK_TOKEN' ) && is_string( ZHIRAF_TOURVISOR_WEBHOOK_TOKEN ) && '' !== ZHIRAF_TOURVISOR_WEBHOOK_TOKEN;
	$reveal    = (string) get_transient( 'zhiraf_tv_reveal_' . get_current_user_id() );
	if ( '' !== $reveal ) {
		delete_transient( 'zhiraf_tv_reveal_' . get_current_user_id() );
	}
	$last_at    = (string) get_option( 'zhiraf_tourvisor_last_received_at', '' );
	$last_count = (int) get_option( 'zhiraf_tourvisor_last_count', 0 );
	$path       = zhiraf_ufa_tourvisor_prices_path();
	$writable   = is_writable( dirname( $path ) ) || ( file_exists( $path ) && is_writable( $path ) );
	$endpoint   = rest_url( 'zhiraf/v1/update-prices' );
	$msg        = isset( $_GET['zhiraf_msg'] ) ? sanitize_key( wp_unslash( $_GET['zhiraf_msg'] ) ) : '';
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Цены Tourvisor', 'zhiraf-ufa' ); ?></h1>
		<?php if ( 'generated' === $msg && '' !== $reveal ) : ?>
			<div class="notice notice-warning">
				<p><?php esc_html_e( 'Новый токен. Скопируйте его в парсер сейчас: повторно страница его не покажет.', 'zhiraf-ufa' ); ?></p>
				<p><code><?php echo esc_html( $reveal ); ?></code></p>
			</div>
		<?php elseif ( 'saved' === $msg ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Токен сохранён.', 'zhiraf-ufa' ); ?></p></div>
		<?php elseif ( 'bad_token' === $msg ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'Токен: 16–200 символов, латиница, цифры и . _ ~ + -', 'zhiraf-ufa' ); ?></p></div>
		<?php elseif ( 'unchanged' === $msg ) : ?>
			<div class="notice notice-info"><p><?php esc_html_e( 'Пустое поле не меняет текущий токен.', 'zhiraf-ufa' ); ?></p></div>
		<?php endif; ?>

		<p>
			<?php esc_html_e( 'Парсер шлёт свежий JSON сюда. Сайт пишет data/tourvisor-prices.json и подставляет цену на Турции и в /wp-json/zhiraf/v1/hotels. Если есть Tourvisor, цена BG не показывается.', 'zhiraf-ufa' ); ?>
		</p>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Endpoint', 'zhiraf-ufa' ); ?></th>
				<td><code><?php echo esc_html( $endpoint ); ?></code></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Заголовок', 'zhiraf-ufa' ); ?></th>
				<td><code>X-Zhiraf-Token</code></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Токен', 'zhiraf-ufa' ); ?></th>
				<td>
					<?php if ( $from_const ) : ?>
						<?php esc_html_e( 'Задан константой ZHIRAF_TOURVISOR_WEBHOOK_TOKEN в wp-config.php.', 'zhiraf-ufa' ); ?>
					<?php elseif ( strlen( $token ) >= 16 ) : ?>
						<?php
						echo esc_html(
							sprintf(
								/* translators: %s: last 4 token chars */
								__( 'Задан, окончание %s.', 'zhiraf-ufa' ),
								substr( $token, -4 )
							)
						);
						?>
					<?php else : ?>
						<?php esc_html_e( 'Не задан. Пока токена нет, endpoint отвечает 503 и цены не принимает.', 'zhiraf-ufa' ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Последний приём', 'zhiraf-ufa' ); ?></th>
				<td>
					<?php
					if ( '' === $last_at ) {
						esc_html_e( 'Ещё не было.', 'zhiraf-ufa' );
					} else {
						echo esc_html( $last_at . ' · ' . $last_count );
					}
					?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Файл темы', 'zhiraf-ufa' ); ?></th>
				<td>
					<code>data/tourvisor-prices.json</code>
					<?php echo $writable ? '' : ' — ' . esc_html__( 'каталог data сейчас не доступен на запись, копия останется в базе.', 'zhiraf-ufa' ); ?>
				</td>
			</tr>
		</table>

		<?php if ( ! $from_const ) : ?>
			<form method="post">
				<?php wp_nonce_field( 'zhiraf_tourvisor_save', 'zhiraf_tourvisor_nonce' ); ?>
				<input type="hidden" name="zhiraf_tourvisor_action" value="save" />
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="zhiraf_tourvisor_token"><?php esc_html_e( 'Свой токен', 'zhiraf-ufa' ); ?></label></th>
						<td>
							<input type="password" class="regular-text" name="zhiraf_tourvisor_token" id="zhiraf_tourvisor_token" value="" autocomplete="new-password" />
							<p class="description"><?php esc_html_e( 'Оставьте пустым, чтобы не менять.', 'zhiraf-ufa' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Сохранить токен', 'zhiraf-ufa' ) ); ?>
			</form>
			<form method="post">
				<?php wp_nonce_field( 'zhiraf_tourvisor_save', 'zhiraf_tourvisor_nonce' ); ?>
				<input type="hidden" name="zhiraf_tourvisor_action" value="generate" />
				<?php submit_button( __( 'Сгенерировать новый токен', 'zhiraf-ufa' ), 'secondary' ); ?>
			</form>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Тело POST', 'zhiraf-ufa' ); ?></h2>
		<pre><?php echo esc_html( '{"generated_at":"2026-11-15T06:00:00Z","prices":{"turkey:1":{"country_slug":"turkey","zhiraf_id":1,"hotel_name":"Paloma Oceana 5*","place":"Сиде","price_rub":145000,"nights":7,"adults":2,"departure_city":"Уфа","departure_date":"2026-06-12","meal":"AI"}}}' ); ?></pre>
		<p class="description"><?php esc_html_e( 'Ключ — country_slug:zhiraf_id. Допустимы и массив prices, и price_rur вместо price_rub. Токен из тела в файл не пишется.', 'zhiraf-ufa' ); ?></p>
		<p class="description"><?php esc_html_e( 'Маппинги curated: turkey-, uae-, egypt-, thailand-, vietnam-tourvisor-hotels.json. В webhook — ключ country_slug:zhiraf_id (например vietnam:1, egypt:3). tourvisor_id из парсера сводится к zhiraf_id через соответствующий файл.', 'zhiraf-ufa' ); ?></p>
	</div>
	<?php
}
