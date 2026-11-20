<?php
/**
 * Resort landing v2 — JSON configs (data/resorts/{resort}.json).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resort keys with JSON-driven v2 layout (country/resort).
 *
 * @return string[]
 */
function zhiraf_ufa_resort_json_registry() {
	return array(
		'oae/dubai',
		'egipet/hurgada',
		'egipet/sharm-el-sheyh',
		'vietnam/fukuok',
	);
}

/**
 * Absolute path to JSON file.
 *
 * @param string $country Country slug.
 * @param string $resort  Resort slug.
 * @return string
 */
function zhiraf_ufa_resort_json_path( $country, $resort ) {
	$resort = sanitize_title( (string) $resort );
	$path   = ZHIRAF_UFA_DIR . '/data/resorts/' . $resort . '.json';
	if ( is_readable( $path ) ) {
		return $path;
	}

	return '';
}

/**
 * @param string $country Country slug.
 * @param string $resort  Resort slug.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_get_resort_landing_from_json( $country, $resort ) {
	$country = sanitize_title( (string) $country );
	$resort  = sanitize_title( (string) $resort );
	$key     = $country . '/' . $resort;
	if ( ! in_array( $key, zhiraf_ufa_resort_json_registry(), true ) ) {
		return null;
	}
	$file = zhiraf_ufa_resort_json_path( $country, $resort );
	if ( '' === $file ) {
		return null;
	}
	$decoded = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! is_array( $decoded ) ) {
		return null;
	}
	if ( empty( $decoded['country'] ) || empty( $decoded['resort'] ) ) {
		return null;
	}
	if ( sanitize_title( (string) $decoded['country'] ) !== $country || sanitize_title( (string) $decoded['resort'] ) !== $resort ) {
		return null;
	}
	$decoded['schema_version'] = ! empty( $decoded['schema_version'] ) ? (int) $decoded['schema_version'] : 2;
	$decoded['country']        = $country;
	$decoded['resort']         = $resort;

	return $decoded;
}

/**
 * Hotels for resort catalog (subset of country pool).
 *
 * @param array<string,mixed> $resort_data JSON resort config.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_resort_json_catalog_hotels( array $resort_data ) {
	$country_slug = ! empty( $resort_data['catalog_country'] ) ? sanitize_key( (string) $resort_data['catalog_country'] ) : sanitize_key( (string) ( $resort_data['country'] ?? '' ) );
	$lock         = ! empty( $resort_data['catalog_lock_filter'] ) ? sanitize_key( (string) $resort_data['catalog_lock_filter'] ) : '';
	$lock_any     = ! empty( $resort_data['catalog_lock_any'] ) && is_array( $resort_data['catalog_lock_any'] )
		? array_values( array_filter( array_map( 'sanitize_key', $resort_data['catalog_lock_any'] ) ) )
		: array();
	$pool_tag     = ! empty( $resort_data['catalog_pool_tag'] ) ? sanitize_key( (string) $resort_data['catalog_pool_tag'] ) : '';
	if ( '' === $country_slug || ! function_exists( 'zhiraf_ufa_curated_catalog_hotels' ) ) {
		return array();
	}
	$hotels = zhiraf_ufa_curated_catalog_hotels( $country_slug );
	if ( $lock_any ) {
		$hotels = array_values(
			array_filter(
				$hotels,
				function ( $row ) use ( $lock_any ) {
					if ( ! is_array( $row ) || empty( $row['filter_tags'] ) || ! is_array( $row['filter_tags'] ) ) {
						return false;
					}
					foreach ( $lock_any as $tag ) {
						if ( in_array( $tag, $row['filter_tags'], true ) ) {
							return true;
						}
					}
					return false;
				}
			)
		);
	} elseif ( '' !== $lock ) {
		$hotels = array_values(
			array_filter(
				$hotels,
				function ( $row ) use ( $lock ) {
					if ( ! is_array( $row ) || empty( $row['filter_tags'] ) || ! is_array( $row['filter_tags'] ) ) {
						return false;
					}
					return in_array( $lock, $row['filter_tags'], true );
				}
			)
		);
	}
	if ( '' !== $pool_tag ) {
		foreach ( $hotels as $i => $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$tags = ! empty( $row['filter_tags'] ) && is_array( $row['filter_tags'] ) ? $row['filter_tags'] : array();
			if ( ! in_array( $pool_tag, $tags, true ) ) {
				$tags[] = $pool_tag;
			}
			$row['filter_tags'] = array_values( array_unique( $tags ) );
			$hotels[ $i ]       = $row;
		}
	}
	if ( ! empty( $resort_data['catalog_derived_tags'] ) && is_array( $resort_data['catalog_derived_tags'] ) ) {
		foreach ( $hotels as $i => $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$tags = ! empty( $row['filter_tags'] ) && is_array( $row['filter_tags'] ) ? $row['filter_tags'] : array();
			foreach ( $resort_data['catalog_derived_tags'] as $rule ) {
				if ( ! is_array( $rule ) || empty( $rule['tag'] ) || empty( $rule['from'] ) ) {
					continue;
				}
				$from = sanitize_key( (string) $rule['from'] );
				$tag  = sanitize_key( (string) $rule['tag'] );
				if ( in_array( $from, $tags, true ) && ! in_array( $tag, $tags, true ) ) {
					$tags[] = $tag;
				}
			}
			$row['filter_tags'] = array_values( array_unique( $tags ) );
			$hotels[ $i ]       = $row;
		}
	}

	return $hotels;
}