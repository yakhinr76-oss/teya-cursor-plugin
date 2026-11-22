<?php
/**
 * Turkey (/tour/turciya/) curated 6-hotel showcase — data and helpers.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Minimal tour price from home hub (align card #1 with hub «от … ₽»).
 *
 * @return int RUB.
 */
function zhiraf_ufa_turciya_hub_min_price_rub() {
	$min = function_exists( 'zhiraf_ufa_home_hub_min_tour' )
		? zhiraf_ufa_home_hub_min_tour( 'turciya', 'ufa' )
		: null;
	if ( $min && ! empty( $min['price_rur'] ) ) {
		return (int) $min['price_rur'];
	}

	return (int) apply_filters( 'zhiraf_ufa_turciya_base_price_rub', 118400 );
}

/**
 * Format price for curated cards.
 *
 * @param int $amount RUB.
 * @return string
 */
function zhiraf_ufa_turciya_curated_format_price( $amount ) {
	return sprintf(
		/* translators: %s: formatted amount */
		__( 'от %s ₽', 'zhiraf-ufa' ),
		number_format( max( 0, (int) $amount ), 0, '.', ' ' )
	);
}

/**
 * Apply BG API pricing to one curated card (cheapest tour for hotel name).
 *
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_turciya_apply_bg_pricing( array $card ) {
	if ( function_exists( 'zhiraf_ufa_tourvisor_overlay_turkey_card' ) ) {
		$with_tourvisor = zhiraf_ufa_tourvisor_overlay_turkey_card( $card );
		if ( ! empty( $with_tourvisor['price_source'] ) && 'tourvisor' === $with_tourvisor['price_source'] ) {
			return $with_tourvisor;
		}
	}

	$name = ! empty( $card['name'] ) ? trim( (string) $card['name'] ) : '';
	if ( '' === $name || ! function_exists( 'zhiraf_ufa_get_hotel_min_tour' ) ) {
		return $card;
	}

	$editorial   = (int) ( $card['price_rub'] ?? 0 );
	$is_base     = ! empty( $card['slot'] ) && 'base' === $card['slot'];
	$bg_hotel_id = ! empty( $card['bg_hotel_id'] ) ? trim( (string) $card['bg_hotel_id'] ) : '';

	$tour        = null;
	$price_scope = '';

	if ( $is_base && function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$tour = zhiraf_ufa_get_country_min_tour( 'turciya' );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'country';
		} else {
			$tour = null;
		}
	} else {
		$tour = zhiraf_ufa_get_hotel_min_tour( 'turciya', $name, $bg_hotel_id );
		if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
			$price_scope = 'hotel';
		} else {
			$tour = null;
		}
	}

	if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
		$card['price_rub']        = (int) $tour['price_rur'];
		$card['price_from_api']   = true;
		$card['price_scope']      = $price_scope;
		$card['tour_offer']       = $tour;
		$card['price_api_matched'] = ( 'hotel' === $price_scope );
		if ( ! empty( $tour['hotel_name'] ) ) {
			$card['bg_hotel_name'] = (string) $tour['hotel_name'];
		}
		if ( ! empty( $tour['hotel_id'] ) ) {
			$card['bg_hotel_id'] = (string) $tour['hotel_id'];
		}
		$card['wa_prefill'] = zhiraf_ufa_turciya_curated_messenger_prefill( $name, 'hotel' === $price_scope ? $tour : null );
	} else {
		$card['price_rub']          = $editorial > 0 ? $editorial : ( $is_base ? zhiraf_ufa_turciya_hub_min_price_rub() : 0 );
		$card['price_from_api']     = false;
		$card['price_api_matched']  = false;
		$card['price_api_unavailable'] = ! $is_base;
		unset( $card['tour_offer'], $card['bg_hotel_name'], $card['price_scope'] );
	}

	return $card;
}

/**
 * Footnote under curated card price (API vs editorial fallback).
 *
 * @param array<string,mixed> $hotel Card row.
 * @return string
 */
function zhiraf_ufa_turciya_curated_price_note( array $hotel ) {
	if ( ! empty( $hotel['price_from_api'] ) && ! empty( $hotel['tour_offer'] ) && is_array( $hotel['tour_offer'] ) ) {
		$tour        = $hotel['tour_offer'];
		$source      = ! empty( $hotel['price_source'] ) ? (string) $hotel['price_source'] : 'bg';
		if ( 'tourvisor' === $source ) {
			$place = ! empty( $tour['place'] ) ? (string) $tour['place'] : '';
			$date  = ! empty( $tour['departure_date'] ) ? (string) $tour['departure_date'] : '';
			$nights = ! empty( $tour['nights'] ) ? (int) $tour['nights'] : 0;
			if ( '' !== $place && '' !== $date && $nights > 0 ) {
				return sprintf(
					/* translators: 1: place, 2: departure date, 3: nights count */
					__( 'Цена Tourvisor, %1$s: вылет %2$s, %3$d ночей, за 2 взрослых с перелётом из Уфы.', 'zhiraf-ufa' ),
					$place,
					$date,
					$nights
				);
			}
			if ( '' !== $place ) {
				return sprintf(
					/* translators: %s: resort or city from the parser */
					__( 'Цена Tourvisor по этому отелю, %s. За 2 взрослых с перелётом из Уфы.', 'zhiraf-ufa' ),
					$place
				);
			}

			return __( 'Цена Tourvisor по этому отелю. За 2 взрослых с перелётом из Уфы.', 'zhiraf-ufa' );
		}
		$scope       = ! empty( $hotel['price_scope'] ) ? (string) $hotel['price_scope'] : 'hotel';
		$date        = ! empty( $tour['departure_date'] ) ? (string) $tour['departure_date'] : '';
		$nights      = ! empty( $tour['nights'] ) ? (int) $tour['nights'] : 0;
		$nights_text = $nights > 0 && function_exists( 'zhiraf_ufa_plural_nights' )
			? zhiraf_ufa_plural_nights( $nights )
			: ( $nights > 0 ? sprintf( _n( '%d ночь', '%d ночи', $nights, 'zhiraf-ufa' ), $nights ) : '' );
		$price_date  = ! empty( $tour['price_date'] ) ? (string) $tour['price_date'] : '';
		$bg_hotel    = ! empty( $tour['hotel_name'] ) ? (string) $tour['hotel_name'] : '';

		if ( 'hotel' === $scope && '' !== $date && '' !== $nights_text ) {
			if ( '' !== $price_date ) {
				return sprintf(
					/* translators: 1: departure date, 2: nights phrase, 3: price calculation date */
					__( 'Минимальная цена по этому отелю: вылет %1$s, %2$s, за 2 взрослых с перелётом из Уфы, трансфером, страховкой и питанием. Курс туроператора на %3$s.', 'zhiraf-ufa' ),
					$date,
					$nights_text,
					$price_date
				);
			}

			return sprintf(
				/* translators: 1: departure date, 2: nights phrase */
				__( 'Минимальная цена по этому отелю: вылет %1$s, %2$s, за 2 взрослых — по данным туроператора.', 'zhiraf-ufa' ),
				$date,
				$nights_text
			);
		}

		if ( 'country' === $scope && '' !== $date && '' !== $nights_text ) {
			return sprintf(
				/* translators: 1: departure date, 2: nights phrase, 3: BG hotel name */
				__( 'Минимальная цена по Турции с главной: вылет %1$s, %2$s, в смете %3$s. За 2 взрослых с перелётом из Уфы.', 'zhiraf-ufa' ),
				$date,
				$nights_text,
				$bg_hotel ? $bg_hotel : '—'
			);
		}
	}

	if ( ! empty( $hotel['price_api_unavailable'] ) ) {
		return __( 'По этому отелю нет позиции в онлайн-прайсе туроператора из Уфы — запросим актуальную смету. Сумма «от» на карточке — редакционный ориентир.', 'zhiraf-ufa' );
	}

	return __( '7–9 ночей, ориентир (включая прямой перелёт из Уфы, трансфер, страховку и питание).', 'zhiraf-ufa' );
}

/**
 * Resort filter chips (hero + showcase).
 *
 * @return array<int,array{id:string,label:string}>
 */
function zhiraf_ufa_turciya_resort_chips() {
	return array(
		array(
			'id'    => 'starter',
			'label' => __( 'Топ-6', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-side',
			'label' => __( 'Сиде — песок', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-kemer',
			'label' => __( 'Кемер — горы', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-alanya',
			'label' => __( 'Аланья — бюджет', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-belek',
			'label' => __( 'Белек — премиум', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-antalya',
			'label' => __( 'Анталья — город', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Six curated hotels (slot 1 = hub min price).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_turciya_curated_hotels() {
	$base_price = zhiraf_ufa_turciya_hub_min_price_rub();

	return array(
		array(
			'slot'          => 'base',
			'resort'        => 'alanya',
			'image'         => 'resort-turciya-alanya-1.jpg',
			'image_alt'     => __( 'Базовый доступный тур в Турцию, Аланья', 'zhiraf-ufa' ),
			'badge'         => __( 'Старт по Турции', 'zhiraf-ufa' ),
			'badge_mod'     => 'home-price',
			'resort_pill'   => __( 'Аланья • 4★ All Inclusive', 'zhiraf-ufa' ),
			'subtitle'      => __( 'Базовый комфорт', 'zhiraf-ufa' ),
			'rating'        => '4.35',
			'name'          => __( 'Sun Fire Beach Hotel 4★', 'zhiraf-ufa' ),
			'description'   => __( 'Честный вход на море с прямым чартером из Уфы и трёхразовым питанием.', 'zhiraf-ufa' ),
			'caveat'        => __( 'Пляж через переход, номера 4★ попроще лидеров курорта.', 'zhiraf-ufa' ),
			'meal'          => __( '4★ All Inclusive', 'zhiraf-ufa' ),
			'price_rub'     => $base_price,
			'price_emphasis'=> true,
			'wa_prefill'    => zhiraf_ufa_turciya_curated_messenger_prefill( 'Sun Fire Beach Hotel 4★' ),
		),
		array(
			'slot'        => 'family',
			'resort'      => 'side',
			'image'       => 'resort-turciya-side-1.jpg',
			'image_alt'   => __( 'Сиде — песчаный пляж и семейный отель', 'zhiraf-ufa' ),
			'badge'       => __( 'Идеально детям', 'zhiraf-ufa' ),
			'badge_mod'   => 'family',
			'resort_pill' => __( 'Сиде • Песчаный вход', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ Ultra All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.85',
			'name'        => __( 'Marvida Family Eco 5★', 'zhiraf-ufa' ),
			'description' => __( 'Пологий песок, луна-парк и отдельный стол для малышей.', 'zhiraf-ufa' ),
			'caveat'      => __( '2-я линия: до пляжа ~450 м, каждые 5 мин шаттл через озеро.', 'zhiraf-ufa' ),
			'meal'        => __( '5★ UAI', 'zhiraf-ufa' ),
			'price_rub'   => 174000,
			'wa_prefill'  => zhiraf_ufa_turciya_curated_messenger_prefill( 'Marvida Family Eco 5★' ),
		),
		array(
			'slot'        => 'value',
			'resort'      => 'alanya',
			'image'       => 'resort-turciya-alanya-2.jpg',
			'image_alt'   => __( 'Отель хит цена/качество, Аланья', 'zhiraf-ufa' ),
			'badge'       => __( 'Хит цена/качество', 'zhiraf-ufa' ),
			'badge_mod'   => 'value',
			'resort_pill' => __( 'Аланья • 1-я линия', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.78',
			'name'        => __( 'Quattro Beach Spa Resort 5★', 'zhiraf-ufa' ),
			'description' => __( 'Первая линия, сильная линия морепродуктов и свежие номера.', 'zhiraf-ufa' ),
			'caveat'      => __( 'Вечером у пляжа оживлённо — ранний сон лучше в корпусе подальше от сцены.', 'zhiraf-ufa' ),
			'meal'        => __( '5★ AI', 'zhiraf-ufa' ),
			'price_rub'   => 152000,
			'wa_prefill'  => zhiraf_ufa_turciya_curated_messenger_prefill( 'Quattro Beach Spa Resort 5★' ),
		),
		array(
			'slot'        => 'relax',
			'resort'      => 'side',
			'image'       => 'resort-turciya-side-2.jpg',
			'image_alt'   => __( 'Сиде — спокойный пляж, отдых 16+', 'zhiraf-ufa' ),
			'badge'       => __( 'Только для взрослых 16+', 'zhiraf-ufa' ),
			'badge_mod'   => 'adults',
			'resort_pill' => __( 'Сиде • 16+', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ Ultra All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.90',
			'name'        => __( 'Commodore Elite Suites 5★', 'zhiraf-ufa' ),
			'description' => __( 'Формат 16+, тишина у бассейна и широкий приватный пляж.', 'zhiraf-ufa' ),
			'caveat'      => __( 'Часть импортного алкоголя в баре — за доплату (типично для UAI).', 'zhiraf-ufa' ),
			'meal'        => __( '5★ UAI', 'zhiraf-ufa' ),
			'price_rub'   => 198000,
			'wa_prefill'  => zhiraf_ufa_turciya_curated_messenger_prefill( 'Commodore Elite Suites 5★' ),
		),
		array(
			'slot'        => 'kemer',
			'resort'      => 'kemer',
			'image'       => 'resort-turciya-kemer-1.jpg',
			'image_alt'   => __( 'Отель в соснах Кемера', 'zhiraf-ufa' ),
			'badge'       => __( 'Сосны и горы', 'zhiraf-ufa' ),
			'badge_mod'   => 'nature',
			'resort_pill' => __( 'Кемер • 1-я линия', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ Ultra All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.82',
			'name'        => __( 'Akka Antedon Hotel 5★', 'zhiraf-ufa' ),
			'description' => __( 'Сосны, чистая вода и целебный хвойный воздух у подножия Тавра.', 'zhiraf-ufa' ),
			'caveat'      => __( 'Галечный вход в море — без аквашуз не обойтись.', 'zhiraf-ufa' ),
			'meal'        => __( '5★ UAI', 'zhiraf-ufa' ),
			'price_rub'   => 186000,
			'wa_prefill'  => zhiraf_ufa_turciya_curated_messenger_prefill( 'Akka Antedon Hotel 5★' ),
		),
		array(
			'slot'        => 'premium',
			'resort'      => 'belek',
			'image'       => 'resort-turciya-belek-1.jpg',
			'image_alt'   => __( 'Премиум отель Белек', 'zhiraf-ufa' ),
			'badge'       => __( 'Премиум-сервис', 'zhiraf-ufa' ),
			'badge_mod'   => 'premium',
			'resort_pill' => __( 'Белек • High Class', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ High Class All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.95',
			'name'        => __( 'Voyage Belek Golf & Spa 5★', 'zhiraf-ufa' ),
			'description' => __( 'High Class сервис, гастрономия и виллы среди сосен у своего пляжа.', 'zhiraf-ufa' ),
			'caveat'      => __( 'A la carte и коллекционный алкоголь — по записи и с доплатой.', 'zhiraf-ufa' ),
			'meal'        => __( '5★ HCI', 'zhiraf-ufa' ),
			'price_rub'   => 245000,
			'wa_prefill'  => zhiraf_ufa_turciya_curated_messenger_prefill( 'Voyage Belek Golf & Spa 5★' ),
		),
	);
}

/**
 * Normalize hotel name for dedupe against starter showcase.
 *
 * @param string $name Hotel title.
 * @return string
 */
function zhiraf_ufa_turciya_hotel_name_key( $name ) {
	$name = remove_accents( wp_strip_all_tags( (string) $name ) );
	$name = mb_strtolower( $name, 'UTF-8' );
	$name = preg_replace( '/[^\p{L}\p{N}]+/u', '', $name );

	return $name;
}

/**
 * Resort region key from JSON resort label.
 *
 * @param string $resort_label Resort line from JSON.
 * @return string side|kemer|alanya|belek|antalya or empty.
 */
function zhiraf_ufa_turciya_resort_region_key( $resort_label ) {
	$r = mb_strtolower( (string) $resort_label, 'UTF-8' );
	if ( preg_match( '/сиде|side|кумк|кызылагач|чолак|эврен/u', $r ) ) {
		return 'side';
	}
	if ( preg_match( '/белек|kadri|cullinan|regnum/u', $r ) ) {
		return 'belek';
	}
	if ( preg_match( '/алань|алан|инжек|клеопат|сарай/u', $r ) ) {
		return 'alanya';
	}
	if ( preg_match( '/анталь|муратпаш|калеичи|kaleici|terra\s*city/u', $r ) || preg_match( '/\bлара\b/u', $r ) ) {
		return 'antalya';
	}
	if ( preg_match( '/кемер|кириш|текир|гёню|гёйн|бельд/u', $r ) ) {
		return 'kemer';
	}

	return '';
}

/**
 * Resort landing URL for Turkey pick cards (optional deep guide).
 *
 * @param string $resort_key side|kemer|alanya|belek|antalya.
 * @return string
 */
function zhiraf_ufa_turciya_resort_guide_url( $resort_key ) {
	$resort_key = sanitize_key( (string) $resort_key );
	if ( 'antalya' === $resort_key ) {
		return home_url( '/tour/turciya/antalya/' );
	}
	return '';
}

/**
 * Need tag from JSON category slug.
 *
 * @param string $category_slug JSON category.
 * @return string need-family|need-adults|need-price
 */
function zhiraf_ufa_turciya_need_tag_from_category( $category_slug ) {
	$category_slug = sanitize_key( (string) $category_slug );
	switch ( $category_slug ) {
		case 'family':
			return 'need-family';
		case 'couples':
			return 'need-adults';
		case 'balance':
		case 'urban':
			return 'need-price';
		case 'premium':
			return 'need-adults';
		default:
			return 'need-price';
	}
}

/**
 * Filter tags for the six starter showcase hotels.
 *
 * @param array<string,mixed> $hotel Curated row.
 * @return array<int,string>
 */
function zhiraf_ufa_turciya_starter_filter_tags( $hotel ) {
	$tags = array( 'starter' );
	$resort = ! empty( $hotel['resort'] ) ? sanitize_key( (string) $hotel['resort'] ) : '';
	if ( '' !== $resort ) {
		$tags[] = 'resort-' . $resort;
	}
	$slot = ! empty( $hotel['slot'] ) ? (string) $hotel['slot'] : '';
	switch ( $slot ) {
		case 'family':
			$tags[] = 'need-family';
			break;
		case 'relax':
			$tags[] = 'need-adults';
			break;
		case 'value':
		case 'base':
			$tags[] = 'need-price';
			break;
		default:
			break;
	}

	return array_values( array_unique( $tags ) );
}

/**
 * Hero image by resort region.
 *
 * @param string $region Resort key.
 * @return string Filename.
 */
function zhiraf_ufa_turciya_pool_image_for_region( $region ) {
	$map = array(
		'side'    => 'resort-turciya-side-1.jpg',
		'kemer'   => 'resort-turciya-kemer-2.jpg',
		'alanya'  => 'resort-turciya-alanya-2.jpg',
		'belek'   => 'resort-turciya-belek-1.jpg',
		'antalya' => 'resort-turciya-antalya-2.jpg',
	);

	return ! empty( $map[ $region ] ) ? $map[ $region ] : 'resort-turciya-alanya-2.jpg';
}

/**
 * Need/status badge for pool card (top-left on photo).
 *
 * @param string $category_slug JSON category_slug.
 * @param string $region        Resort region key.
 * @return array{badge:string,badge_mod:string}
 */
function zhiraf_ufa_turciya_pool_need_badge( $category_slug, $region ) {
	$category_slug = sanitize_key( (string) $category_slug );
	if ( 'family' === $category_slug ) {
		return array(
			'badge'     => __( 'Идеально детям', 'zhiraf-ufa' ),
			'badge_mod' => 'family',
		);
	}
	if ( 'couples' === $category_slug ) {
		return array(
			'badge'     => __( 'Только для взрослых 16+', 'zhiraf-ufa' ),
			'badge_mod' => 'adults',
		);
	}
	if ( 'premium' === $category_slug ) {
		return array(
			'badge'     => __( 'Премиум-сервис', 'zhiraf-ufa' ),
			'badge_mod' => 'premium',
		);
	}
	if ( 'kemer' === $region && 'balance' !== $category_slug ) {
		return array(
			'badge'     => __( 'Сосны и горы', 'zhiraf-ufa' ),
			'badge_mod' => 'nature',
		);
	}
	return array(
		'badge'     => __( 'Хит цена/качество', 'zhiraf-ufa' ),
		'badge_mod' => 'value',
	);
}

/**
 * Logistics pill (bottom-right on photo): курорт • линия / вход.
 *
 * @param string              $region        Region key.
 * @param string              $category_slug Category.
 * @param string              $resort_raw    JSON resort line.
 * @param array<string,mixed> $row           Full JSON row.
 * @return string
 */
function zhiraf_ufa_turciya_pool_resort_pill( $region, $category_slug, $resort_raw, $row ) {
	$resort_raw    = (string) $resort_raw;
	$category_slug = sanitize_key( (string) $category_slug );
	$labels        = array(
		'side'    => __( 'Сиде', 'zhiraf-ufa' ),
		'kemer'   => __( 'Кемер', 'zhiraf-ufa' ),
		'alanya'  => __( 'Аланья', 'zhiraf-ufa' ),
		'belek'   => __( 'Белек', 'zhiraf-ufa' ),
		'antalya' => __( 'Анталья', 'zhiraf-ufa' ),
	);
	$resort_label  = ! empty( $labels[ $region ] ) ? $labels[ $region ] : wp_trim_words( $resort_raw, 3, '…' );

	$haystack = mb_strtolower( $resort_raw . ' ' . wp_json_encode( $row, JSON_UNESCAPED_UNICODE ), 'UTF-8' );
	if ( 'family' === $category_slug && preg_match( '/песок|песчан|мелк/u', $haystack ) ) {
		$suffix = __( 'Песчаный вход', 'zhiraf-ufa' );
	} elseif ( 'premium' === $category_slug || 'belek' === $region ) {
		$suffix = __( 'High Class', 'zhiraf-ufa' );
	} elseif ( 'couples' === $category_slug || preg_match( '/16\+|взросл/u', $haystack ) ) {
		$suffix = __( '16+', 'zhiraf-ufa' );
	} elseif ( 'urban' === $category_slug || 'antalya' === $region ) {
		$suffix = __( 'город • пляж', 'zhiraf-ufa' );
	} else {
		$suffix = __( '1-я линия', 'zhiraf-ufa' );
	}

	return $resort_label . ' • ' . $suffix;
}

/**
 * Meal / concept line under hotel title.
 *
 * @param string $hotel_name    Official name with stars.
 * @param string $category_slug JSON category.
 * @return string
 */
function zhiraf_ufa_turciya_pool_meal_concept( $hotel_name, $category_slug ) {
	$hotel_name    = (string) $hotel_name;
	$category_slug = sanitize_key( (string) $category_slug );
	if ( preg_match( '/4\s*★/u', $hotel_name ) ) {
		return __( '4★ All Inclusive', 'zhiraf-ufa' );
	}
	if ( 'premium' === $category_slug ) {
		return __( '5★ High Class All Inclusive', 'zhiraf-ufa' );
	}
	if ( in_array( $category_slug, array( 'family', 'couples' ), true ) ) {
		return __( '5★ Ultra All Inclusive', 'zhiraf-ufa' );
	}

	return __( '5★ All Inclusive', 'zhiraf-ufa' );
}

/**
 * Card USP — одно ёмкое предложение.
 *
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_turciya_pool_card_usp( $row ) {
	if ( ! empty( $row['usp'] ) ) {
		return wp_trim_words( trim( (string) $row['usp'] ), 16, '…' );
	}
	if ( ! empty( $row['pros'] ) && is_array( $row['pros'] ) ) {
		foreach ( $row['pros'] as $pro ) {
			$pro = trim( (string) $pro );
			if ( '' !== $pro ) {
				return wp_trim_words( $pro, 16, '…' );
			}
		}
	}

	return '';
}

/**
 * Card caveat — одно честное предупреждение.
 *
 * @param array<string,mixed> $row JSON row.
 * @return string
 */
function zhiraf_ufa_turciya_pool_card_caveat( $row ) {
	if ( empty( $row['caveat'] ) ) {
		return '';
	}

	return wp_trim_words( trim( (string) $row['caveat'] ), 16, '…' );
}

/**
 * Messenger prefill for curated hotel CTA (WhatsApp / MAX share).
 *
 * @param string               $hotel_name Hotel title.
 * @param array<string,mixed>|null $tour   Optional BG tour row.
 * @return string
 */
function zhiraf_ufa_turciya_curated_messenger_prefill( $hotel_name, $tour = null, $uon_id = '' ) {
	$hotel_name = trim( (string) $hotel_name );
	$uon_id     = trim( (string) $uon_id );
	$id_bit     = '';
	if ( '' !== $uon_id ) {
		$id_bit = ' ' . sprintf(
			/* translators: %s: hotel id passed to U-ON */
			__( 'ID отеля: %s.', 'zhiraf-ufa' ),
			$uon_id
		);
	}
	if ( '' === $hotel_name ) {
		return __( 'Здравствуйте! Интересует тур в Турцию из Уфы. Подскажите свободные даты и актуальную стоимость.', 'zhiraf-ufa' ) . $id_bit;
	}

	if ( is_array( $tour ) && ! empty( $tour['price_rur'] ) ) {
		$price = number_format( (int) $tour['price_rur'], 0, '.', ' ' );
		$date  = ! empty( $tour['departure_date'] ) ? (string) $tour['departure_date'] : '';
		$nights = ! empty( $tour['nights'] ) ? (int) $tour['nights'] : 0;
		if ( '' !== $date && $nights > 0 ) {
			return sprintf(
				/* translators: 1: hotel, 2: departure date, 3: nights count, 4: formatted RUB price, 5: U-ON id sentence */
				__( 'Здравствуйте! Интересует %1$s из Уфы: вылет %2$s, %3$d ночей, ориентир от %4$s ₽. Подскажите, есть ли места на эти даты?%5$s', 'zhiraf-ufa' ),
				$hotel_name,
				$date,
				$nights,
				$price,
				$id_bit
			);
		}
	}

	return sprintf(
		/* translators: 1: hotel name, 2: U-ON id sentence */
		__( 'Здравствуйте! Интересует тур в %1$s из Уфы. Подскажите свободные даты и актуальную стоимость.%2$s', 'zhiraf-ufa' ),
		$hotel_name,
		$id_bit
	);
}

/**
 * Stable hotel id for the messenger template and U-ON payload.
 *
 * @param array<string,mixed> $hotel Card row.
 * @return string
 */
function zhiraf_ufa_turciya_curated_uon_hotel_id( array $hotel ) {
	if ( ! empty( $hotel['json_hotel_id'] ) ) {
		return 'TR-' . (int) $hotel['json_hotel_id'];
	}
	if ( ! empty( $hotel['pool_id'] ) && is_numeric( $hotel['pool_id'] ) ) {
		return 'TR-' . (int) $hotel['pool_id'];
	}
	$slot = ! empty( $hotel['slot'] ) ? strtoupper( sanitize_key( (string) $hotel['slot'] ) ) : '';
	if ( '' !== $slot ) {
		return 'TR-STARTER-' . $slot;
	}

	return 'TR-HOTEL';
}

/**
 * WhatsApp / MAX / Telegram links with the same prefilled message.
 *
 * @param string $text Message template.
 * @return array{whatsapp:string,max:string,telegram:string}
 */
function zhiraf_ufa_turciya_messenger_channel_urls( $text ) {
	$text    = trim( (string) $text );
	$wa_base = function_exists( 'zhiraf_ufa_brand_whatsapp_url' ) ? zhiraf_ufa_brand_whatsapp_url() : 'https://wa.me/79018105018';
	$wa      = function_exists( 'zhiraf_ufa_whatsapp_url_with_text' )
		? zhiraf_ufa_whatsapp_url_with_text( $wa_base, $text )
		: $wa_base;
	$page    = function_exists( 'home_url' ) ? home_url( '/tour/turciya/' ) : 'https://zhiraf-ufa.ru/tour/turciya/';

	return array(
		'whatsapp' => $wa,
		'max'      => 'https://max.ru/:share?text=' . rawurlencode( $text ),
		'telegram' => 'https://t.me/share/url?url=' . rawurlencode( $page ) . '&text=' . rawurlencode( $text ),
	);
}

/**
 * Attach U-ON id and messenger links after price overlay.
 *
 * @param array<string,mixed> $card Card row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_turciya_finalize_catalog_card( array $card ) {
	$card['uon_hotel_id'] = zhiraf_ufa_turciya_curated_uon_hotel_id( $card );
	$tour                 = null;
	if ( ! empty( $card['price_api_matched'] ) && ! empty( $card['tour_offer'] ) && is_array( $card['tour_offer'] ) ) {
		$tour = $card['tour_offer'];
	}
	$name               = ! empty( $card['name'] ) ? (string) $card['name'] : '';
	$card['wa_prefill'] = zhiraf_ufa_turciya_curated_messenger_prefill( $name, $tour, $card['uon_hotel_id'] );
	$card['messenger']  = zhiraf_ufa_turciya_messenger_channel_urls( $card['wa_prefill'] );

	return $card;
}

/**
 * Display-size CDN url. Originals from Terra are several megabytes.
 *
 * @param string $url Photo URL.
 * @return string
 */
function zhiraf_ufa_turciya_display_photo_url( $url ) {
	$url = trim( (string) $url );
	if ( ! preg_match( '#^https://dynamic-media\.tacdn\.com/#i', $url ) ) {
		return $url;
	}
	if ( false !== strpos( $url, 'w=' ) ) {
		return $url;
	}
	$sep = ( false !== strpos( $url, '?' ) ) ? '&' : '?';

	return $url . $sep . 'w=1200&h=-1&s=1';
}

/**
 * Card image from JSON photos[0]: remote URL, local file, then region fallback.
 *
 * @param array<string,mixed> $row JSON hotel.
 * @return array{image:string,image_remote:bool,image_width:int,image_height:int}
 */
function zhiraf_ufa_turciya_image_from_json_row( array $row ) {
	$region = zhiraf_ufa_turciya_resort_region_key( ! empty( $row['resort'] ) ? (string) $row['resort'] : '' );
	$photos = ! empty( $row['photos'] ) && is_array( $row['photos'] ) ? $row['photos'] : array();
	$first  = isset( $photos[0] ) ? trim( (string) $photos[0] ) : '';
	$width  = ! empty( $row['lead_photo_width'] ) ? (int) $row['lead_photo_width'] : 0;
	$height = ! empty( $row['lead_photo_height'] ) ? (int) $row['lead_photo_height'] : 0;

	if ( preg_match( '#^https://#i', $first ) ) {
		if ( $width > 1200 && $height > 0 ) {
			$height = (int) max( 1, round( $height * 1200 / $width ) );
			$width  = 1200;
		}

		return array(
			'image'        => zhiraf_ufa_turciya_display_photo_url( $first ),
			'image_remote' => true,
			'image_width'  => $width,
			'image_height' => $height,
		);
	}

	if ( '' !== $first ) {
		$base = basename( $first );
		foreach ( array( 'hotels/turkey/' . $base, $base ) as $rel ) {
			if ( defined( 'ZHIRAF_UFA_DIR' ) && is_readable( ZHIRAF_UFA_DIR . '/assets/images/' . $rel ) ) {
				return array(
					'image'        => $rel,
					'image_remote' => false,
					'image_width'  => 0,
					'image_height' => 0,
				);
			}
		}
	}

	return array(
		'image'        => zhiraf_ufa_turciya_pool_image_for_region( $region ),
		'image_remote' => false,
		'image_width'  => 0,
		'image_height' => 0,
	);
}

/**
 * Decoded turkey_hotels_25.json rows.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_turciya_json_rows() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$path  = function_exists( 'zhiraf_ufa_turkey_hotels_json_path' ) ? zhiraf_ufa_turkey_hotels_json_path() : '';
	if ( $path && is_readable( $path ) ) {
		$decoded = json_decode( (string) file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( is_array( $decoded ) ) {
			$cache = $decoded;
		}
	}

	return $cache;
}

/**
 * Editorial price hint for JSON pool hotel.
 *
 * @param array<string,mixed> $row JSON row.
 * @return int
 */
function zhiraf_ufa_turciya_pool_price_rub( $row ) {
	$cat = ! empty( $row['category_slug'] ) ? sanitize_key( (string) $row['category_slug'] ) : 'balance';
	switch ( $cat ) {
		case 'family':
			return 172000;
		case 'couples':
			return 198000;
		case 'premium':
			return 248000;
		case 'urban':
			return 158000;
		case 'balance':
		default:
			return 162000;
	}
}

/**
 * Map one JSON hotel row to curated card shape.
 *
 * @param array<string,mixed> $row JSON.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_turciya_json_row_to_card( $row ) {
	if ( empty( $row['hotel_name'] ) ) {
		return null;
	}
	$name = (string) $row['hotel_name'];
	$region = zhiraf_ufa_turciya_resort_region_key( ! empty( $row['resort'] ) ? (string) $row['resort'] : '' );
	$tags   = array();
	if ( '' !== $region ) {
		$tags[] = 'resort-' . $region;
	}
	$tags[] = zhiraf_ufa_turciya_need_tag_from_category( ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '' );

	$cat         = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	$need_badge  = zhiraf_ufa_turciya_pool_need_badge( $cat, $region );
	$badge       = $need_badge['badge'];
	$badge_mod   = $need_badge['badge_mod'];
	$resort_raw  = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	$resort_pill = zhiraf_ufa_turciya_pool_resort_pill( $region, $cat, $resort_raw, $row );
	$pool_id     = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$desc        = zhiraf_ufa_turciya_pool_card_usp( $row );
	$caveat      = zhiraf_ufa_turciya_pool_card_caveat( $row );
	$concept     = zhiraf_ufa_turciya_pool_meal_concept( $name, $cat );
	$image       = zhiraf_ufa_turciya_image_from_json_row( $row );

	$bg_hotel_id = ! empty( $row['bg_hotel_id'] ) ? trim( (string) $row['bg_hotel_id'] ) : '';

	$card = array(
		'bg_hotel_id'   => $bg_hotel_id,
		'pool_id'       => $pool_id,
		'json_hotel_id' => $pool_id,
		'pool_rank'     => 100 + $pool_id,
		'filter_tags'   => array_values( array_unique( $tags ) ),
		'resort'        => $region,
		'image'         => $image['image'],
		'image_remote'  => $image['image_remote'],
		'image_width'   => $image['image_width'],
		'image_height'  => $image['image_height'],
		'image_alt'     => sprintf(
			/* translators: %s: hotel name */
			__( 'Отель %s', 'zhiraf-ufa' ),
			$name
		),
		'badge'         => $badge,
		'badge_mod'     => $badge_mod,
		'resort_pill'   => $resort_pill,
		'subtitle'      => $concept,
		'rating'        => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'          => $name,
		'description'   => $desc,
		'caveat'        => $caveat,
		'price_rub'     => zhiraf_ufa_turciya_pool_price_rub( $row ),
		'wa_prefill'    => zhiraf_ufa_turciya_curated_messenger_prefill( $name ),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * Full on-page pool (25 editorial + starter slots), tagged for catalog filters.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_turciya_catalog_hotels() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$hotels       = array();
	$starter_keys = array();
	$json_by_name = array();
	foreach ( zhiraf_ufa_turciya_json_rows() as $json_row ) {
		if ( ! is_array( $json_row ) ) {
			continue;
		}
		$key = zhiraf_ufa_turciya_hotel_name_key( ! empty( $json_row['hotel_name'] ) ? (string) $json_row['hotel_name'] : '' );
		if ( '' !== $key ) {
			$json_by_name[ $key ] = $json_row;
		}
	}
	$rank = 1;
	foreach ( zhiraf_ufa_turciya_curated_hotels() as $row ) {
		$name_key       = zhiraf_ufa_turciya_hotel_name_key( ! empty( $row['name'] ) ? (string) $row['name'] : '' );
		$starter_keys[] = $name_key;
		$row['pool_id']     = 'starter-' . ( ! empty( $row['slot'] ) ? (string) $row['slot'] : $rank );
		$row['pool_rank']   = $rank;
		$row['filter_tags'] = zhiraf_ufa_turciya_starter_filter_tags( $row );
		if ( '' !== $name_key && isset( $json_by_name[ $name_key ] ) ) {
			$json_row = $json_by_name[ $name_key ];
			$image    = zhiraf_ufa_turciya_image_from_json_row( $json_row );
			$row['image']         = $image['image'];
			$row['image_remote']  = $image['image_remote'];
			$row['image_width']   = $image['image_width'];
			$row['image_height']  = $image['image_height'];
			if ( ! empty( $json_row['id'] ) ) {
				$row['json_hotel_id'] = (int) $json_row['id'];
			}
			if ( function_exists( 'zhiraf_ufa_curated_card_apply_ta_from_json_row' ) ) {
				$row = zhiraf_ufa_curated_card_apply_ta_from_json_row( $row, $json_row );
			}
		}
		$hotels[] = zhiraf_ufa_turciya_finalize_catalog_card( zhiraf_ufa_turciya_apply_bg_pricing( $row ) );
		++$rank;
	}

	foreach ( $json_by_name as $key => $json_row ) {
		if ( '' === $key || in_array( $key, $starter_keys, true ) ) {
			continue;
		}
		$card = zhiraf_ufa_turciya_json_row_to_card( $json_row );
		if ( $card ) {
			$hotels[] = zhiraf_ufa_turciya_finalize_catalog_card( zhiraf_ufa_turciya_apply_bg_pricing( $card ) );
		}
	}

	$cache = $hotels;

	return $cache;
}

/**
 * Unified catalog filter definitions (one active filter at a time).
 *
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_turciya_catalog_filters() {
	$card = array(
		array(
			'id'    => 'starter',
			'group' => 'default',
			'label' => __( '⭐ Стартовый топ (6)', 'zhiraf-ufa' ),
			'title' => __( 'Туры в Турцию: 6 проверенных вариантов', 'zhiraf-ufa' ),
			'desc'  => __( 'Базовый тур с главной страницы + 5 отелей под разные сценарии отдыха с прямым рейсом из Уфы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-side',
			'group' => 'resort',
			'label' => __( '🏖️ Сиде', 'zhiraf-ufa' ),
			'title' => __( 'Отели Сиде (песчаные пляжи)', 'zhiraf-ufa' ),
			'desc'  => __( 'Отобранные отели Сиде с пологим берегом без плит — до 6 лучших из нашей базы.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-kemer',
			'group' => 'resort',
			'label' => __( '🌲 Кемер', 'zhiraf-ufa' ),
			'title' => __( 'Отели Кемера (горы и сосны)', 'zhiraf-ufa' ),
			'desc'  => __( 'Проверенные отели среди реликтовых сосен с кристально чистой водой.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-alanya',
			'group' => 'resort',
			'label' => __( '☀️ Аланья', 'zhiraf-ufa' ),
			'title' => __( 'Отели Аланьи (максимальная выгода)', 'zhiraf-ufa' ),
			'desc'  => __( 'Лучшие предложения по соотношению цены и тёплого моря.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'resort-belek',
			'group' => 'resort',
			'label' => __( '👑 Белек', 'zhiraf-ufa' ),
			'title' => __( 'Отели Белека (High-End 5★)', 'zhiraf-ufa' ),
			'desc'  => __( 'Флагманские отели с закрытой территорией и сервисом премиум-уровня.', 'zhiraf-ufa' ),
		),
		array(
			'id'        => 'resort-antalya',
			'group'     => 'resort',
			'label'     => __( '🏙️ Анталья', 'zhiraf-ufa' ),
			'title'     => __( 'Отели Антальи (город и Лара)', 'zhiraf-ufa' ),
			'desc'      => __( 'Короткий трансфер, пляжи Лара / Коньяалты, Калеичи и шопинг — до 6 отелей из базы.', 'zhiraf-ufa' ),
			'guide_url' => zhiraf_ufa_turciya_resort_guide_url( 'antalya' ),
		),
		array(
			'id'    => 'need-family',
			'group' => 'need',
			'label' => __( '👨‍👩‍👧 С детьми', 'zhiraf-ufa' ),
			'title' => __( 'Отели для отдыха с детьми', 'zhiraf-ufa' ),
			'desc'  => __( 'Пологий песчаный вход, горки, детское меню и спокойный трансфер.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-adults',
			'group' => 'need',
			'label' => __( '🥂 Без детей 16+', 'zhiraf-ufa' ),
			'title' => __( 'Отели только для взрослых (16+ / 18+)', 'zhiraf-ufa' ),
			'desc'  => __( 'Никакого детского шума, тишина, спа и романтическая атмосфера.', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'need-price',
			'group' => 'need',
			'label' => __( '⚖️ Хит цена/качество', 'zhiraf-ufa' ),
			'title' => __( 'Хиты «Цена / Качество»', 'zhiraf-ufa' ),
			'desc'  => __( 'Отели, где сервис и питание существенно выше уплаченных денег.', 'zhiraf-ufa' ),
		),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * Default catalog copy (starter mode).
 *
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_turciya_catalog_default_copy() {
	$filters = zhiraf_ufa_turciya_catalog_filters();

	$card = array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : '',
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : '',
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * Count curated hotels per resort (for pick-card footers).
 *
 * @return array<string,int>
 */
function zhiraf_ufa_turciya_resort_hotel_counts() {
	$counts = array(
		'side'    => 0,
		'kemer'   => 0,
		'alanya'  => 0,
		'belek'   => 0,
		'antalya' => 0,
	);
	foreach ( zhiraf_ufa_turciya_catalog_hotels() as $hotel ) {
		$tags = ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ? $hotel['filter_tags'] : array();
		foreach ( $tags as $tag ) {
			if ( 0 !== strpos( (string) $tag, 'resort-' ) ) {
				continue;
			}
			$key = substr( (string) $tag, 7 );
			if ( isset( $counts[ $key ] ) ) {
				++$counts[ $key ];
			}
		}
	}

	return $counts;
}

/**
 * Five resort pick cards — H2 «Какой курорт Турции выбрать именно вам?».
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_turciya_resort_pick_cards() {
	$counts = zhiraf_ufa_turciya_resort_hotel_counts();

	$card = array(
		array(
			'id'           => 'side',
			'guide_url'    => zhiraf_ufa_turciya_resort_guide_url( 'side' ),
			'guide_label'  => __( 'На страницу →', 'zhiraf-ufa' ),
			'name'         => __( 'Сиде', 'zhiraf-ufa' ),
			'subtitle'     => __( 'Песок и пологий вход', 'zhiraf-ufa' ),
			'tag'          => __( 'Детский хит', 'zhiraf-ufa' ),
			'tag_mod'      => 'family',
			'transfer'     => __( '~1 ч 10 мин', 'zhiraf-ufa' ),
			'featured'     => true,
			'hotel_count'  => $counts['side'],
			'accent_mod'   => 'emerald',
			'bullets'      => array(
				array(
					'type' => 'pro',
					'text' => __( 'Идеально с колясками и малышами', 'zhiraf-ufa' ),
				),
				array(
					'type' => 'pro',
					'text' => __( 'Широкие песчаные отмели', 'zhiraf-ufa' ),
				),
			),
		),
		array(
			'id'           => 'kemer',
			'guide_url'    => zhiraf_ufa_turciya_resort_guide_url( 'kemer' ),
			'guide_label'  => __( 'На страницу →', 'zhiraf-ufa' ),
			'name'         => __( 'Кемер', 'zhiraf-ufa' ),
			'subtitle'     => __( 'Сосны, скалы и чистейшая вода', 'zhiraf-ufa' ),
			'tag'          => __( 'Природа и горы', 'zhiraf-ufa' ),
			'tag_mod'      => 'nature',
			'transfer'     => __( '~1 ч 00 мин', 'zhiraf-ufa' ),
			'featured'     => false,
			'hotel_count'  => $counts['kemer'],
			'accent_mod'   => 'teal',
			'bullets'      => array(
				array(
					'type' => 'pro',
					'text' => __( 'Целебный хвойный воздух', 'zhiraf-ufa' ),
				),
				array(
					'type' => 'info',
					'text' => __( 'Галечный вход (нужны аквашузы)', 'zhiraf-ufa' ),
				),
			),
		),
		array(
			'id'           => 'alanya',
			'guide_url'    => zhiraf_ufa_turciya_resort_guide_url( 'alanya' ),
			'guide_label'  => __( 'На страницу →', 'zhiraf-ufa' ),
			'name'         => __( 'Аланья', 'zhiraf-ufa' ),
			'subtitle'     => __( 'Тепло и бюджетно', 'zhiraf-ufa' ),
			'tag'          => __( 'Эконом-хит', 'zhiraf-ufa' ),
			'tag_mod'      => 'value',
			'transfer'     => __( '~2 – 2.5 ч', 'zhiraf-ufa' ),
			'transfer_mod' => 'warn',
			'featured'     => false,
			'hotel_count'  => $counts['alanya'],
			'accent_mod'   => 'amber',
			'bullets'      => array(
				array(
					'type' => 'pro',
					'text' => __( 'Лучшие цены на длительный отдых', 'zhiraf-ufa' ),
				),
				array(
					'type' => 'info',
					'text' => __( 'Самое тёплое море на побережье', 'zhiraf-ufa' ),
				),
			),
		),
		array(
			'id'           => 'belek',
			'guide_url'    => zhiraf_ufa_turciya_resort_guide_url( 'belek' ),
			'guide_label'  => __( 'На страницу →', 'zhiraf-ufa' ),
			'name'         => __( 'Белек', 'zhiraf-ufa' ),
			'subtitle'     => __( 'Сервис 5★ Lux', 'zhiraf-ufa' ),
			'tag'          => __( 'Премиум', 'zhiraf-ufa' ),
			'tag_mod'      => 'premium',
			'transfer'     => __( '~30–40 мин', 'zhiraf-ufa' ),
			'transfer_mod' => 'fast',
			'featured'     => false,
			'hotel_count'  => $counts['belek'],
			'accent_mod'   => 'lux',
			'bullets'      => array(
				array(
					'type' => 'pro',
					'text' => __( 'Ближе всех к аэропорту', 'zhiraf-ufa' ),
				),
				array(
					'type' => 'pro',
					'text' => __( 'Закрытые территории и гольф', 'zhiraf-ufa' ),
				),
			),
		),
		array(
			'id'           => 'antalya',
			'name'         => __( 'Анталья', 'zhiraf-ufa' ),
			'subtitle'     => __( 'Пляжи Лара, Коньяалты и центр', 'zhiraf-ufa' ),
			'tag'          => __( 'Город и шопинг', 'zhiraf-ufa' ),
			'tag_mod'      => 'urban',
			'transfer'     => __( '~15–20 мин', 'zhiraf-ufa' ),
			'transfer_mod' => 'fast',
			'featured'     => false,
			'hotel_count'  => $counts['antalya'],
			'accent_mod'   => 'sky',
			'guide_url'    => zhiraf_ufa_turciya_resort_guide_url( 'antalya' ),
			'guide_label'  => __( 'На страницу →', 'zhiraf-ufa' ),
			'bullets'      => array(
				array(
					'type' => 'pro',
					'text' => __( 'Самый короткий трансфер от рейса Уфы', 'zhiraf-ufa' ),
				),
				array(
					'type' => 'pro',
					'text' => __( 'ТРЦ, Калеичи, рестораны у моря', 'zhiraf-ufa' ),
				),
			),
		),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * Expert nuances (3 columns).
 *
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_turciya_expert_nuances() {
	$card = array(
		array(
			'icon'  => '🌊',
			'title' => __( 'Заход в море и пляжи', 'zhiraf-ufa' ),
			'text'  => __( 'На Сиде и Аланье — пологий песок для детей; у Кемера чаще галька, но вода прозрачнее. Уточняем тип берега и пирс до брони.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🚌',
			'title' => __( 'Трансферы по курортам', 'zhiraf-ufa' ),
			'text'  => __( 'До Антальи и Белека — 20–40 минут от аэропорта. До Сиде и Кемера — около часа, до Аланьи — от 2 часов по побережью. Учитываем это при подборе с детьми.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '✈',
			'title' => __( 'Прямой чартер из Уфы', 'zhiraf-ufa' ),
			'text'  => __( 'Вылет из Уфы в Анталью — в среднем 4,5–5 часов. Багаж, время вылета и трансфер фиксируем в договоре до оплаты.', 'zhiraf-ufa' ),
		),
	);
	return zhiraf_ufa_curated_card_apply_ta_from_json_row( $card, $row );
}

/**
 * WhatsApp deep link for a curated hotel card.
 *
 * @param array<string,mixed> $hotel Hotel row.
 * @return string
 */
function zhiraf_ufa_turciya_curated_wa_url( $hotel ) {
	$text = ! empty( $hotel['wa_prefill'] )
		? (string) $hotel['wa_prefill']
		: zhiraf_ufa_turciya_curated_messenger_prefill( ! empty( $hotel['name'] ) ? (string) $hotel['name'] : '' );
	$base = function_exists( 'zhiraf_ufa_brand_whatsapp_url' ) ? zhiraf_ufa_brand_whatsapp_url() : 'https://wa.me/79018105018';

	return function_exists( 'zhiraf_ufa_whatsapp_url_with_text' )
		? zhiraf_ufa_whatsapp_url_with_text( $base, $text )
		: $base;
}

/**
 * Lead magnet prefill (Kemer vs Side).
 *
 * @return string
 */
function zhiraf_ufa_turciya_resort_compare_wa_text() {
	return __( 'Здравствуйте! Не могу выбрать между Кемером и Сиде. Возраст детей: __. Подскажите 2–3 отеля с вылетом из Уфы на 7–9 ночей под наш бюджет.', 'zhiraf-ufa' );
}

/**
 * Click on a hotel messenger link: pass the hotel id to U-ON.
 */
function zhiraf_ufa_ajax_hotel_intent() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'zhiraf_ufa_forms' ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Страница устарела. Обновите её и откройте мессенджер ещё раз.', 'zhiraf-ufa' ),
			),
			403
		);
	}

	$hotel_id = isset( $_POST['uon_hotel_id'] ) ? sanitize_text_field( wp_unslash( $_POST['uon_hotel_id'] ) ) : '';
	$name     = isset( $_POST['hotel_name'] ) ? sanitize_text_field( wp_unslash( $_POST['hotel_name'] ) ) : '';
	$channel  = isset( $_POST['channel'] ) ? sanitize_key( wp_unslash( $_POST['channel'] ) ) : '';
	if ( ! preg_match( '/^TR-[A-Z0-9-]{1,40}$/', $hotel_id ) ) {
		wp_send_json_error( array( 'message' => 'hotel id' ), 400 );
	}

	$payload = array(
		'source'       => 'turciya_hotel_card',
		'uon_hotel_id' => $hotel_id,
		'hotel_name'   => $name,
		'channel'      => $channel,
		'page'         => home_url( '/tour/turciya/' ),
	);
	$uon_ok  = false;
	if ( class_exists( 'Zhiraf_UON_CRM' ) ) {
		$crm    = new Zhiraf_UON_CRM();
		$result = $crm->send_lead( $payload );
		$uon_ok = ! empty( $result['ok'] );
	}

	wp_send_json_success(
		array(
			'uon_hotel_id' => $hotel_id,
			'uon'          => $uon_ok,
		)
	);
}
add_action( 'wp_ajax_zhiraf_hotel_intent', 'zhiraf_ufa_ajax_hotel_intent' );
add_action( 'wp_ajax_nopriv_zhiraf_hotel_intent', 'zhiraf_ufa_ajax_hotel_intent' );
