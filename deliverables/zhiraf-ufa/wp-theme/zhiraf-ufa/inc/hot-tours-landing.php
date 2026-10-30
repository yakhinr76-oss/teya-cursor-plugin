<?php
/**
 * Hot tours landing helpers (/goryashchie-tury/).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Parse BG departure date (d.m.Y) to timestamp at midnight site TZ.
 *
 * @param string $date_str Date string.
 * @return int Unix timestamp or 0.
 */
function zhiraf_ufa_departure_to_timestamp( $date_str ) {
	$date_str = trim( (string) $date_str );
	if ( '' === $date_str ) {
		return 0;
	}
	$tz = wp_timezone();
	$dt = DateTime::createFromFormat( 'd.m.Y', $date_str, $tz );
	if ( ! $dt ) {
		return 0;
	}
	$dt->setTime( 0, 0, 0 );
	return (int) $dt->getTimestamp();
}

/**
 * Russian plural phrase for days until departure.
 *
 * @param int $days Days count.
 * @return string
 */
function zhiraf_ufa_plural_days_until( $days ) {
	$n      = (int) $days;
	$mod10  = $n % 10;
	$mod100 = $n % 100;
	if ( $mod100 >= 11 && $mod100 <= 19 ) {
		return sprintf( __( '%d дней', 'zhiraf-ufa' ), $n );
	}
	if ( 1 === $mod10 ) {
		return sprintf( __( '%d день', 'zhiraf-ufa' ), $n );
	}
	if ( $mod10 >= 2 && $mod10 <= 4 ) {
		return sprintf( __( '%d дня', 'zhiraf-ufa' ), $n );
	}
	return sprintf( __( '%d дней', 'zhiraf-ufa' ), $n );
}

/**
 * Short human date for hot cards (e.g. «25 октября»).
 *
 * @param string $date_str d.m.Y.
 * @return string
 */
function zhiraf_ufa_departure_human_date( $date_str ) {
	$ts = zhiraf_ufa_departure_to_timestamp( $date_str );
	if ( ! $ts ) {
		return trim( (string) $date_str );
	}
	return function_exists( 'wp_date' ) ? wp_date( 'j F', $ts ) : gmdate( 'j F', $ts );
}

/**
 * Human label for hot-tour urgency on cards.
 *
 * @param string $date_str Departure d.m.Y.
 * @return string
 */
function zhiraf_ufa_departure_urgency_text( $date_str ) {
	$ts    = zhiraf_ufa_departure_to_timestamp( $date_str );
	$label = zhiraf_ufa_departure_human_date( $date_str );
	if ( ! $ts ) {
		return sprintf(
			/* translators: %s: date */
			__( 'Вылет: %s', 'zhiraf-ufa' ),
			$label
		);
	}
	$today = ( new DateTime( 'today', wp_timezone() ) )->getTimestamp();
	$days  = (int) floor( ( $ts - $today ) / DAY_IN_SECONDS );
	if ( $days <= 0 ) {
		return sprintf(
			/* translators: %s: date */
			__( 'Вылет сегодня (%s)', 'zhiraf-ufa' ),
			$label
		);
	}
	if ( 1 === $days ) {
		return sprintf(
			/* translators: %s: date */
			__( 'Вылет завтра (%s)', 'zhiraf-ufa' ),
			$label
		);
	}
	if ( $days <= 14 ) {
		return sprintf(
			/* translators: 1: days phrase, 2: date */
			__( 'Вылет через %1$s (%2$s)', 'zhiraf-ufa' ),
			zhiraf_ufa_plural_days_until( $days ),
			$label
		);
	}
	return sprintf(
		/* translators: %s: date */
		__( 'Ближайший вылет: %s', 'zhiraf-ufa' ),
		$label
	);
}

/**
 * Price feed freshness for FOMO hero (from BG bundle meta).
 *
 * @return array{label:string,minutes:int|null}
 */
function zhiraf_ufa_price_feed_freshness() {
	$minutes = null;
	$label   = __( 'База обновляется каждые 6 часов', 'zhiraf-ufa' );
	if ( ! class_exists( 'Zhiraf_BG_Price_Engine' ) ) {
		return array(
			'label'   => $label,
			'minutes' => $minutes,
		);
	}
	$bundle = Zhiraf_BG_Price_Engine::get_instance()->get_bundle_data();
	$raw    = ! empty( $bundle['meta']['updated_at'] ) ? (string) $bundle['meta']['updated_at'] : '';
	if ( '' !== $raw ) {
		$ts = strtotime( $raw );
		if ( $ts ) {
			$minutes = max( 0, (int) floor( ( time() - $ts ) / 60 ) );
			if ( $minutes < 60 ) {
				$label = sprintf(
					/* translators: %d: minutes */
					_n( 'База обновлена %d минуту назад', 'База обновлена %d минут назад', $minutes, 'zhiraf-ufa' ),
					$minutes
				);
			} else {
				$hours = max( 1, (int) round( $minutes / 60 ) );
				$label = sprintf(
					/* translators: %d: hours */
					_n( 'База обновлена %d час назад', 'База обновлена %d часа назад', $hours, 'zhiraf-ufa' ),
					$hours
				);
			}
		}
	}
	return array(
		'label'   => $label,
		'minutes' => $minutes,
	);
}

/**
 * Country slugs for hot landing grid (12 cards, no orphan row).
 *
 * @return string[]
 */
function zhiraf_ufa_hot_landing_country_order() {
	return array(
		'turciya',
		'oae',
		'egipet',
		'tailand',
		'vietnam',
		'kitaj',
		'maldivy',
		'sejshely',
		'indiya',
		'shri-lanka',
		'indoneziya',
		'uzbekistan',
	);
}

/**
 * Nearest-departure tour per country for hot landing (max 12).
 *
 * @param int $limit Cards.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_get_hot_landing_tours( $limit = 12 ) {
	if ( ! class_exists( 'Zhiraf_BG_Price_Engine' ) ) {
		return array();
	}
	$engine   = Zhiraf_BG_Price_Engine::get_instance();
	$order    = zhiraf_ufa_hot_landing_country_order();
	$today_ts = ( new DateTime( 'today', wp_timezone() ) )->getTimestamp();
	$window   = $today_ts + ( 21 * DAY_IN_SECONDS );
	$cards    = array();

	foreach ( $order as $slug ) {
		$tours = $engine->get_country_tours( $slug, 200 );
		if ( empty( $tours ) ) {
			continue;
		}
		$candidates = array();
		foreach ( $tours as $t ) {
			$ts = zhiraf_ufa_departure_to_timestamp( $t['departure_date'] ?? '' );
			if ( ! $ts || $ts < $today_ts ) {
				continue;
			}
			$candidates[] = array(
				'tour' => $t,
				'ts'   => $ts,
			);
		}
		if ( empty( $candidates ) ) {
			foreach ( $tours as $t ) {
				$ts = zhiraf_ufa_departure_to_timestamp( $t['departure_date'] ?? '' );
				if ( $ts && $ts >= $today_ts ) {
					$candidates[] = array(
						'tour' => $t,
						'ts'   => $ts,
					);
				}
			}
		}
		if ( empty( $candidates ) ) {
			continue;
		}
		usort(
			$candidates,
			function ( $a, $b ) use ( $window ) {
				$aw = $a['ts'] <= $window ? 0 : 1;
				$bw = $b['ts'] <= $window ? 0 : 1;
				if ( $aw !== $bw ) {
					return $aw <=> $bw;
				}
				if ( $a['ts'] !== $b['ts'] ) {
					return $a['ts'] <=> $b['ts'];
				}
				return (int) ( $a['tour']['price_rur'] ?? 0 ) <=> (int) ( $b['tour']['price_rur'] ?? 0 );
			}
		);
		$cards[] = $candidates[0]['tour'];
		if ( count( $cards ) >= $limit ) {
			break;
		}
	}

	return $cards;
}
