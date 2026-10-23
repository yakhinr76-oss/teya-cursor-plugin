<?php
/**
 * Tour price cards helpers and integration.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve resort/destination image for tour card.
 *
 * @param array<string,mixed> $tour Tour data.
 * @return string Image filename in assets/images/.
 */
function zhiraf_ufa_tour_image( $tour ) {
	$info = zhiraf_ufa_tour_image_info( $tour );
	return $info['file'];
}

/**
 * Photo for a tour card: the resort's own verified photo when the library has one,
 * otherwise a photo of the country. Within one page request the least-used candidate
 * is picked, so cards of one country look varied where several verified photos exist.
 * Sources/credits: assets/images/CREDITS.md (Pexels, no hotel signage, checked visually).
 *
 * @param array<string,mixed> $tour Tour data.
 * @return array{file:string,is_resort:bool,alt:string}
 */
function zhiraf_ufa_tour_image_info( $tour, $card_index = null ) {
	static $used = array();

	if ( ! empty( $tour['image'] ) ) {
		return array( 'file' => (string) $tour['image'], 'is_resort' => true, 'alt' => '' );
	}
	if ( ! empty( $tour['image_url'] ) ) {
		$ext = zhiraf_ufa_tour_operator_image_file( (string) $tour['image_url'] );
		if ( '' !== $ext ) {
			return array( 'file' => $ext, 'is_resort' => true, 'alt' => '' );
		}
	}
	$resort = ! empty( $tour['resort'] ) ? mb_strtolower( (string) $tour['resort'], 'UTF-8' ) : '';
	$slug   = ! empty( $tour['country_slug'] ) ? (string) $tour['country_slug'] : '';
	$legacy = array( 'turkey' => 'turciya', 'uae' => 'oae', 'egypt' => 'egipet', 'thailand' => 'tailand' );
	if ( isset( $legacy[ $slug ] ) ) {
		$slug = $legacy[ $slug ];
	}

	// Resort needle (lowercase substring of the BG resort name) => verified photos of that very place.
	$resort_photos = array(
		'turciya'    => array(
			'алань'    => array( 'resort-turciya-alanya-1.jpg', 'resort-turciya-alanya-2.jpg' ),
			'бельдиби' => array( 'resort-turciya-kemer-1.jpg', 'resort-turciya-kemer-2.jpg' ),
			'гёйнюк'   => array( 'resort-turciya-kemer-1.jpg', 'resort-turciya-kemer-2.jpg' ),
			'гейнюк'   => array( 'resort-turciya-kemer-1.jpg', 'resort-turciya-kemer-2.jpg' ),
			'кемер'    => array( 'resort-turciya-kemer-1.jpg', 'resort-turciya-kemer-2.jpg' ),
			'сиде'     => array( 'resort-turciya-alanya-2.jpg', 'resort-turciya-belek-1.jpg' ),
			'белек'    => array( 'resort-turciya-belek-1.jpg' ),
			'анталь'   => array( 'resort-turciya-antalya-1.jpg', 'resort-turciya-antalya-2.jpg' ),
			'антали'   => array( 'resort-turciya-antalya-1.jpg', 'resort-turciya-antalya-2.jpg' ),
		),
		'egipet'     => array(
			'хургада'  => array( 'resort-egipet-hurghada-1.jpg', 'resort-egipet-hurghada-2.jpg' ),
			'шарм'     => array( 'resort-egipet-sharm-1.jpg', 'resort-egipet-sharm-2.jpg' ),
			'марса'    => array( 'resort-egipet-marsa-alam-1.jpg', 'resort-egipet-marsa-alam-2.jpg' ),
			'гуна'     => array( 'resort-egipet-el-gouna-1.jpg', 'resort-egipet-el-gouna-2.jpg' ),
		),
		'vietnam'    => array(
			'нячанг'   => array( 'resort-vietnam-nha-trang-1.jpg', 'resort-vietnam-nha-trang-2.jpg' ),
			'фукуок'   => array( 'resort-vietnam-phu-quoc-1.jpg', 'resort-vietnam-phu-quoc-2.jpg' ),
			'камрань'  => array( 'resort-vietnam-cam-ranh-1.jpg', 'resort-vietnam-cam-ranh-2.jpg' ),
			'фантьет'  => array( 'resort-vietnam-phan-thiet-1.jpg', 'resort-vietnam-phan-thiet-2.jpg' ),
			'муйне'    => array( 'resort-vietnam-phan-thiet-1.jpg', 'resort-vietnam-phan-thiet-2.jpg' ),
		),
		'oae'        => array(
			'дубай'    => array( 'hero-uae.jpg', 'dest-uae-new.jpg' ),
			'шарджа'   => array( 'dest-uae.jpg', 'dest-uae-new.jpg' ),
			'рас-эль'  => array( 'dest-uae-new.jpg', 'dest-uae.jpg' ),
			'абу-даби' => array( 'resort-abu-dabi.jpg', 'dest-uae-new.jpg' ),
			'фуджей'   => array( 'dest-uae-new.jpg' ),
		),
		'tailand'    => array( 'пхукет' => array( 'resort-phuket.jpg' ), 'краби' => array( 'country-tailand.jpg' ) ),
		'kitaj'      => array( 'санья' => array( 'country-kitaj.jpg', 'resort-sanya-2.jpg', 'resort-sanya-3.jpg' ) ),
		'indiya'     => array( 'северный гоа' => array( 'resort-severnyj-goa.jpg' ) ),
		'maldivy'    => array( 'мале' => array( 'country-maldivy.jpg' ), 'архипелаг' => array( 'resort-maldivy-atoll-1.jpg', 'resort-maldivy-atoll-2.jpg' ) ),
		'uzbekistan' => array( 'самарканд' => array( 'resort-samarkand.jpg', 'country-uzbekistan.jpg' ), 'хива' => array( 'alt-uzbekistan-2.jpg' ) ),
		'armeniya'   => array(
			'ереван'   => array( 'alt-armeniya-yerevan.jpg', 'alt-armeniya-2.jpg' ),
			'севан'    => array( 'alt-armeniya-sevan.jpg', 'alt-armeniya-3.jpg' ),
			'дилижан'  => array( 'alt-armeniya-sevan.jpg', 'alt-armeniya-2.jpg' ),
		),
	);
	// Country-level photos (fallback; never another resort's own photo). Several => varied cards on one page.
	$country_photos = array(
		'turciya'    => array(
			'resort-turciya-belek-1.jpg',
			'resort-turciya-belek-2.jpg',
			'resort-turciya-kemer-2.jpg',
			'resort-turciya-alanya-2.jpg',
		),
		'egipet'     => array(
			'resort-egipet-hurghada-1.jpg',
			'resort-egipet-sharm-1.jpg',
			'resort-egipet-el-gouna-1.jpg',
		),
		'oae'        => array( 'hero-uae.jpg', 'dest-uae-new.jpg', 'dest-uae.jpg', 'resort-abu-dabi.jpg' ),
		'tailand'    => array( 'resort-phuket.jpg', 'hero-thailand.jpg', 'dest-thailand-new.jpg' ),
		'vietnam'    => array(
			'resort-vietnam-phu-quoc-1.jpg',
			'resort-vietnam-phu-quoc-2.jpg',
			'resort-vietnam-nha-trang-1.jpg',
			'resort-vietnam-nha-trang-2.jpg',
			'resort-vietnam-phan-thiet-1.jpg',
			'dest-vietnam-new.jpg',
		),
		'kitaj'      => array( 'country-kitaj.jpg', 'resort-sanya-2.jpg', 'resort-sanya-3.jpg' ),
		'indiya'     => array( 'country-indiya.jpg', 'alt-indiya-2.jpg' ),
		'shri-lanka' => array( 'country-shri-lanka.jpg', 'alt-shri-lanka-2.jpg', 'alt-shri-lanka-3.jpg' ),
		'indoneziya' => array( 'country-indoneziya.jpg', 'alt-indoneziya-2.jpg', 'alt-indoneziya-3.jpg' ),
		'maldivy'    => array( 'country-maldivy.jpg', 'resort-maldivy-atoll-1.jpg', 'resort-maldivy-atoll-2.jpg' ),
		'sejshely'   => array( 'country-sejshely.jpg', 'alt-sejshely-2.jpg', 'alt-sejshely-3.jpg' ),
		'uzbekistan' => array( 'country-uzbekistan.jpg', 'alt-uzbekistan-2.jpg' ),
		'armeniya'   => array( 'alt-armeniya-yerevan.jpg', 'alt-armeniya-sevan.jpg', 'alt-armeniya-2.jpg', 'alt-armeniya-3.jpg' ),
	);

	$candidates = array();
	$is_resort  = false;
	if ( '' !== $resort && isset( $resort_photos[ $slug ] ) ) {
		foreach ( $resort_photos[ $slug ] as $needle => $files ) {
			if ( false !== mb_strpos( $resort, $needle, 0, 'UTF-8' ) ) {
				$candidates = $files;
				$is_resort  = true;
				break;
			}
		}
	}
	if ( ! $candidates ) {
		$candidates = isset( $country_photos[ $slug ] ) ? $country_photos[ $slug ] : array();
	}
	if ( 'armeniya' === $slug && false !== mb_strpos( $resort, 'ереван', 0, 'UTF-8' ) ) {
		$candidates = array( 'alt-armeniya-yerevan.jpg', 'alt-armeniya-2.jpg', 'alt-armeniya-3.jpg' );
	}
	if ( ! $candidates ) {
		// No library photo => gradient (never a wrong country's photo).
		return array( 'file' => '', 'is_resort' => false, 'alt' => '' );
	}
	// Never repeat the same fallback on adjacent cards: prefer unused, then rotate by slot index.
	if ( null !== $card_index && count( $candidates ) > 1 ) {
		$pick = $candidates[ ( (int) $card_index ) % count( $candidates ) ];
		foreach ( $candidates as $f ) {
			if ( 0 === ( $used[ $f ] ?? 0 ) ) {
				$pick = $f;
				break;
			}
		}
	} else {
		$pick = $candidates[0];
		foreach ( $candidates as $f ) {
			if ( ( $used[ $f ] ?? 0 ) < ( $used[ $pick ] ?? 0 ) ) {
				$pick = $f;
			}
		}
	}
	$used[ $pick ] = ( $used[ $pick ] ?? 0 ) + 1;

	$country = function_exists( 'zhiraf_ufa_tour_country_name' ) ? zhiraf_ufa_tour_country_name( $slug ) : '';
	$alt     = $is_resort ? (string) $tour['resort'] : sprintf( __( 'Фото направления: %s', 'zhiraf-ufa' ), $country );
	// Verified photo library: some files show a nearby landmark, not the resort beach itself.
	$alt_by_file = array(
		'resort-turciya-belek-2.jpg' => __( 'Античный театр Аспендос (близ Белека)', 'zhiraf-ufa' ),
	);
	if ( isset( $alt_by_file[ $pick ] ) ) {
		$alt = $alt_by_file[ $pick ];
	}
	return array( 'file' => $pick, 'is_resort' => $is_resort, 'alt' => $alt );
}

/**
 * Resort region slug for destination tour filters.
 *
 * @param string $country_slug Country slug.
 * @param string $resort       Resort label.
 * @return string Filter id or empty.
 */
function zhiraf_ufa_tour_landing_region( $country_slug, $resort ) {
	if ( 'oae' === $country_slug ) {
		return zhiraf_ufa_tour_emirate_region( $resort );
	}
	if ( 'vietnam' === $country_slug ) {
		return zhiraf_ufa_tour_vietnam_region( $resort );
	}
	return '';
}

/**
 * Vietnam resort slug for filters (Nha Trang / Phu Quoc / Mui Ne).
 *
 * @param string $resort Resort label.
 * @return string nhatrang|phuquoc|muine|other
 */
function zhiraf_ufa_tour_vietnam_region( $resort ) {
	$r = mb_strtolower( (string) $resort, 'UTF-8' );
	if ( '' === $r ) {
		return 'other';
	}
	if ( false !== mb_strpos( $r, 'фукуок', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'phu quoc', 0, 'UTF-8' ) ) {
		return 'phuquoc';
	}
	if ( false !== mb_strpos( $r, 'нячанг', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'nha trang', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'камran', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'камран', 0, 'UTF-8' ) ) {
		return 'nhatrang';
	}
	if ( false !== mb_strpos( $r, 'муйне', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'фантьет', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'phan thiet', 0, 'UTF-8' ) ) {
		return 'muine';
	}
	return 'other';
}

function zhiraf_ufa_tour_emirate_region( $resort ) {
	$r = mb_strtolower( (string) $resort, 'UTF-8' );
	if ( '' === $r ) {
		return 'other';
	}
	if ( false !== mb_strpos( $r, 'шарджа', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'sharjah', 0, 'UTF-8' ) ) {
		return 'sharjah';
	}
	if ( false !== mb_strpos( $r, 'рас-эль', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'rak', 0, 'UTF-8' ) ) {
		return 'rak';
	}
	if ( false !== mb_strpos( $r, 'абу-даби', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'abu dhabi', 0, 'UTF-8' ) ) {
		return 'abudhabi';
	}
	if ( false !== mb_strpos( $r, 'фуджей', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'fujairah', 0, 'UTF-8' ) ) {
		return 'fujairah';
	}
	if ( false !== mb_strpos( $r, 'дубай', 0, 'UTF-8' ) || false !== mb_strpos( $r, 'dubai', 0, 'UTF-8' ) ) {
		return 'dubai';
	}
	return 'other';
}

/**
 * Format Russian plural for nights.
 *
 * @param int $nights Number of nights.
 * @return string
 */
function zhiraf_ufa_plural_nights( $nights ) {
	$n      = (int) $nights;
	$mod10  = $n % 10;
	$mod100 = $n % 100;

	if ( $mod100 >= 11 && $mod100 <= 19 ) {
		return sprintf( /* translators: %d: nights */ _n( '%d ночей', '%d ночей', $n, 'zhiraf-ufa' ), $n );
	}
	if ( 1 === $mod10 ) {
		return sprintf( /* translators: %d: night */ _n( '%d ночь', '%d ночь', $n, 'zhiraf-ufa' ), $n );
	}
	if ( $mod10 >= 2 && $mod10 <= 4 ) {
		return sprintf( /* translators: %d: nights */ _n( '%d ночи', '%d ночи', $n, 'zhiraf-ufa' ), $n );
	}

	return sprintf( /* translators: %d: nights */ _n( '%d ночей', '%d ночей', $n, 'zhiraf-ufa' ), $n );
}

/**
 * Get country label in prepositional/accusative for heading.
 *
 * @param string $country_slug Country slug.
 * @return string
 */
function zhiraf_ufa_country_prepositional( $country_slug ) {
	$map = array(
		'turciya'  => __( 'в Турцию', 'zhiraf-ufa' ),
		'turkey'   => __( 'в Турцию', 'zhiraf-ufa' ),
		'oae'      => __( 'в ОАЭ', 'zhiraf-ufa' ),
		'uae'      => __( 'в ОАЭ', 'zhiraf-ufa' ),
		'egipet'   => __( 'в Египет', 'zhiraf-ufa' ),
		'egypt'    => __( 'в Египет', 'zhiraf-ufa' ),
		'tailand'  => __( 'в Таиланд', 'zhiraf-ufa' ),
		'thailand' => __( 'в Таиланд', 'zhiraf-ufa' ),
		'vietnam'  => __( 'во Вьетнам', 'zhiraf-ufa' ),
	);

	return isset( $map[ $country_slug ] ) ? $map[ $country_slug ] : __( 'по направлению', 'zhiraf-ufa' );
}

/**
 * Get normalized country name.
 *
 * @param string $country_slug Country slug.
 * @return string
 */
function zhiraf_ufa_tour_country_name( $country_slug ) {
	$map = array(
		'turciya'  => __( 'Турция', 'zhiraf-ufa' ),
		'turkey'   => __( 'Турция', 'zhiraf-ufa' ),
		'oae'      => __( 'ОАЭ', 'zhiraf-ufa' ),
		'uae'      => __( 'ОАЭ', 'zhiraf-ufa' ),
		'egipet'   => __( 'Египет', 'zhiraf-ufa' ),
		'egypt'    => __( 'Египет', 'zhiraf-ufa' ),
		'tailand'  => __( 'Таиланд', 'zhiraf-ufa' ),
		'thailand' => __( 'Таиланд', 'zhiraf-ufa' ),
		'vietnam'  => __( 'Вьетнам', 'zhiraf-ufa' ),
		'kitaj'      => __( 'Китай', 'zhiraf-ufa' ),
		'indiya'     => __( 'Индия', 'zhiraf-ufa' ),
		'shri-lanka' => __( 'Шри-Ланка', 'zhiraf-ufa' ),
		'indoneziya' => __( 'Индонезия', 'zhiraf-ufa' ),
		'maldivy'    => __( 'Мальдивы', 'zhiraf-ufa' ),
		'sejshely'   => __( 'Сейшелы', 'zhiraf-ufa' ),
		'uzbekistan' => __( 'Узбекистан', 'zhiraf-ufa' ),
		'armeniya'   => __( 'Армения', 'zhiraf-ufa' ),
	);

	return isset( $map[ $country_slug ] ) ? $map[ $country_slug ] : '';
}

/**
 * Flight info derived ONLY from the tour's own BG data (departure city + airline/route string).
 * Ufa departure: "(стыковка в Москве)" in the operator's airline field => via Moscow, otherwise direct.
 *
 * @param array|null $tour Tour card.
 * @param string     $fallback_city 'Уфа' or 'Москва' when no tour data.
 * @return array{key:string,icon:string,label:string,line:string}
 */
function zhiraf_ufa_tour_flight_info( $tour, $fallback_city = 'Уфа' ) {
	$city    = ! empty( $tour['departure_city'] ) ? (string) $tour['departure_city'] : $fallback_city;
	$airline = ! empty( $tour['airline'] ) ? trim( (string) $tour['airline'] ) : '';
	$carrier = trim( preg_replace( '/\s*\((без багажа|с багажом|стыковка в Москве|группа Аэрофлот)\)/u', '', $airline ) );
	if ( '' !== $carrier && false !== mb_stripos( $airline, 'без багажа', 0, 'UTF-8' ) ) {
		$carrier .= ', ' . __( 'без багажа', 'zhiraf-ufa' );
	}
	if ( 'Москва' === $city ) {
		return array( 'key' => 'mow', 'icon' => '✈', 'label' => __( 'вылет из Москвы', 'zhiraf-ufa' ), 'line' => $carrier ? sprintf( __( '%s, из Москвы', 'zhiraf-ufa' ), $carrier ) : __( 'из Москвы', 'zhiraf-ufa' ) );
	}
	if ( '' === $airline ) {
		return array( 'key' => 'ufa', 'icon' => '✈', 'label' => __( 'вылет из Уфы', 'zhiraf-ufa' ), 'line' => '' );
	}
	if ( false !== mb_stripos( $airline, 'стыковка', 0, 'UTF-8' ) ) {
		return array( 'key' => 'via', 'icon' => '⇄', 'label' => __( 'через Москву', 'zhiraf-ufa' ), 'line' => sprintf( __( '%s, стыковка в Москве', 'zhiraf-ufa' ), $carrier ) );
	}
	return array( 'key' => 'direct', 'icon' => '✈', 'label' => __( 'прямой рейс', 'zhiraf-ufa' ), 'line' => sprintf( __( '%s, прямой рейс из Уфы', 'zhiraf-ufa' ), $carrier ) );
}

/**
 * Short package line for homepage bento (nights · stars · meal).
 *
 * @param array<string,mixed>|null $tour Min-price tour row.
 * @return string Empty if not enough data.
 */
function zhiraf_ufa_bento_package_summary( $tour ) {
	if ( ! is_array( $tour ) || empty( $tour ) ) {
		return '';
	}
	$parts = array();
	$nights = ! empty( $tour['nights'] ) ? (int) $tour['nights'] : 0;
	if ( $nights >= 3 ) {
		$parts[] = zhiraf_ufa_plural_nights( $nights );
	}
	$stars = ! empty( $tour['stars'] ) ? trim( (string) $tour['stars'] ) : '';
	if ( '' !== $stars ) {
		$parts[] = sprintf(
			/* translators: %s: hotel star category e.g. 4* */
			__( 'отель %s', 'zhiraf-ufa' ),
			$stars
		);
	}
	$meal_name = ! empty( $tour['meal_name'] ) ? trim( (string) $tour['meal_name'] ) : '';
	$meal_code = ! empty( $tour['meal_code'] ) ? strtoupper( trim( (string) $tour['meal_code'] ) ) : '';
	if ( '' === $meal_name && '' !== $meal_code ) {
		$meal_map = array(
			'AI'  => __( 'Всё включено', 'zhiraf-ufa' ),
			'UAI' => __( 'Ultra All Inclusive', 'zhiraf-ufa' ),
			'BB'  => __( 'Завтраки', 'zhiraf-ufa' ),
			'HB'  => __( 'Завтрак и ужин', 'zhiraf-ufa' ),
			'FB'  => __( 'Полный пансион', 'zhiraf-ufa' ),
		);
		$meal_name = isset( $meal_map[ $meal_code ] ) ? $meal_map[ $meal_code ] : $meal_code;
	}
	if ( '' !== $meal_name ) {
		$parts[] = $meal_name;
	}
	return implode( ' · ', $parts );
}

/**
 * Map operator hotel image URL to a cached local file (future: BG hotelsJson photo field).
 *
 * @param string $url Remote image URL.
 */
function zhiraf_ufa_tour_operator_image_file( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	/**
	 * Filter: return theme image basename when operator URL was synced to assets/images.
	 *
	 * @param string $file Empty until wired.
	 * @param string $url  Operator URL.
	 */
	return (string) apply_filters( 'zhiraf_ufa_tour_operator_image_file', '', $url );
}

/**
 * One-line hotel spec for cards: stars · nights · meal · beach line.
 *
 * @param array<string,mixed> $tour Tour row.
 */
function zhiraf_ufa_tour_card_spec_line( $tour ) {
	$parts = array();
	$stars = ! empty( $tour['stars'] ) ? trim( (string) $tour['stars'] ) : '';
	if ( '' !== $stars ) {
		$parts[] = '★ ' . $stars;
	}
	$nights = ! empty( $tour['nights'] ) ? (int) $tour['nights'] : 0;
	if ( $nights >= 3 ) {
		$parts[] = zhiraf_ufa_plural_nights( $nights );
	}
	$meal_code = ! empty( $tour['meal_code'] ) ? strtoupper( trim( (string) $tour['meal_code'] ) ) : '';
	if ( '' !== $meal_code ) {
		$parts[] = $meal_code;
	}
	$beach = ! empty( $tour['beach_line'] ) ? (string) $tour['beach_line'] : zhiraf_ufa_tour_beach_line_guess( $tour );
	if ( '' !== $beach ) {
		$parts[] = $beach;
	}
	return implode( ' • ', $parts );
}

/**
 * @param array<string,mixed> $tour Tour row.
 */
function zhiraf_ufa_tour_beach_line_guess( $tour ) {
	if ( ! empty( $tour['beach_line'] ) ) {
		return (string) $tour['beach_line'];
	}
	$resort = mb_strtolower( (string) ( $tour['resort'] ?? '' ), 'UTF-8' );
	if ( false !== mb_strpos( $resort, '1-я', 0, 'UTF-8' ) || false !== mb_strpos( $resort, 'первая', 0, 'UTF-8' ) ) {
		return __( '1-я линия', 'zhiraf-ufa' );
	}
	return __( 'линия моря — уточняем', 'zhiraf-ufa' );
}
