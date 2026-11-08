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
			'badge'         => __( '🔥 Цена с главной', 'zhiraf-ufa' ),
			'badge_mod'     => 'home-price',
			'resort_pill'   => __( 'Аланья • 4★ All Inclusive', 'zhiraf-ufa' ),
			'subtitle'      => __( 'Базовый комфорт', 'zhiraf-ufa' ),
			'rating'        => '4.35',
			'name'          => __( 'Sun Fire Beach Hotel 4★', 'zhiraf-ufa' ),
			'description'   => __( 'Минимальный порог входа на море без рисков: свой оборудованный пляж через переход, чистое трёхразовое питание, прямой чартер из Уфы.', 'zhiraf-ufa' ),
			'meal'          => __( '4★ All Inclusive', 'zhiraf-ufa' ),
			'price_rub'     => $base_price,
			'price_emphasis'=> true,
			'wa_prefill'    => __( 'Здравствуйте! Интересует Sun Fire Beach Hotel 4★ в Аланье — базовый тур с вылетом из Уфы. Подскажите даты и актуальную цену на двоих.', 'zhiraf-ufa' ),
		),
		array(
			'slot'        => 'family',
			'resort'      => 'side',
			'image'       => 'resort-turciya-belek-1.jpg',
			'image_alt'   => __( 'Песчаный пляж и территория семейного отеля', 'zhiraf-ufa' ),
			'badge'       => __( '👨‍👩‍👧 Идеально детям', 'zhiraf-ufa' ),
			'badge_mod'   => 'family',
			'resort_pill' => __( 'Сиде • Песчаный вход', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ Ultra All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.85',
			'name'        => __( 'Marvida Family Eco 5★', 'zhiraf-ufa' ),
			'description' => __( 'Пологий песчаный берег без камней, детский луна-парк, отдельный диетический стол для малышей и водные горки.', 'zhiraf-ufa' ),
			'meal'        => __( '5★ UAI', 'zhiraf-ufa' ),
			'price_rub'   => 174000,
			'wa_prefill'  => __( 'Здравствуйте! Нужен подбор Marvida Family Eco 5★ на Сиде с вылетом из Уфы для семьи с детьми.', 'zhiraf-ufa' ),
		),
		array(
			'slot'        => 'value',
			'resort'      => 'alanya',
			'image'       => 'resort-turciya-alanya-2.jpg',
			'image_alt'   => __( 'Отель хит цена/качество, Аланья', 'zhiraf-ufa' ),
			'badge'       => __( '⚖️ Хит цена/качество', 'zhiraf-ufa' ),
			'badge_mod'   => 'value',
			'resort_pill' => __( 'Аланья • 1-я линия', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.78',
			'name'        => __( 'Quattro Beach Spa Resort 5★', 'zhiraf-ufa' ),
			'description' => __( 'Свежие дизайнерские номера, отличный выбор морепродуктов, стильная набережная и ненавязчивая живая музыка.', 'zhiraf-ufa' ),
			'meal'        => __( '5★ AI', 'zhiraf-ufa' ),
			'price_rub'   => 152000,
			'wa_prefill'  => __( 'Здравствуйте! Интересует Quattro Beach Spa 5★ в Аланье с вылетом из Уфы — уточните цену на 7–9 ночей для двоих.', 'zhiraf-ufa' ),
		),
		array(
			'slot'        => 'relax',
			'resort'      => 'side',
			'image'       => 'resort-turciya-kemer-2.jpg',
			'image_alt'   => __( 'Спокойный пляж и море — отдых 16+', 'zhiraf-ufa' ),
			'badge'       => __( '🥂 Только для взрослых', 'zhiraf-ufa' ),
			'badge_mod'   => 'adults',
			'resort_pill' => __( 'Сиде • 16+', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ Ultra All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.90',
			'name'        => __( 'Commodore Elite Suites 5★', 'zhiraf-ufa' ),
			'description' => __( 'Тишина у бассейна без детского шума, премиальный импортный бар, спа-центр и широкий приватный пляж.', 'zhiraf-ufa' ),
			'meal'        => __( '5★ UAI', 'zhiraf-ufa' ),
			'price_rub'   => 198000,
			'wa_prefill'  => __( 'Здравствуйте! Ищем тур в Commodore Elite Suites 5★ (16+) с вылетом из Уфы.', 'zhiraf-ufa' ),
		),
		array(
			'slot'        => 'kemer',
			'resort'      => 'kemer',
			'image'       => 'resort-turciya-kemer-1.jpg',
			'image_alt'   => __( 'Отель в соснах Кемера', 'zhiraf-ufa' ),
			'badge'       => __( '🌲 Сосны и горы', 'zhiraf-ufa' ),
			'badge_mod'   => 'nature',
			'resort_pill' => __( 'Кемер • 1-я линия', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ Ultra All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.82',
			'name'        => __( 'Akka Antedon Hotel 5★', 'zhiraf-ufa' ),
			'description' => __( 'Огромная реликтовая зелёная территория у подножия Таврских гор, кристально чистая вода и целебный хвойный воздух.', 'zhiraf-ufa' ),
			'meal'        => __( '5★ UAI', 'zhiraf-ufa' ),
			'price_rub'   => 186000,
			'wa_prefill'  => __( 'Здравствуйте! Подберите тур в Akka Antedon 5★ (Кемер) с вылетом из Уфы.', 'zhiraf-ufa' ),
		),
		array(
			'slot'        => 'premium',
			'resort'      => 'belek',
			'image'       => 'resort-turciya-belek-1.jpg',
			'image_alt'   => __( 'Премиум отель Белек', 'zhiraf-ufa' ),
			'badge'       => __( '👑 Премиум сервис', 'zhiraf-ufa' ),
			'badge_mod'   => 'premium',
			'resort_pill' => __( 'Белек • High Class', 'zhiraf-ufa' ),
			'subtitle'    => __( '5★ High Class All Inclusive', 'zhiraf-ufa' ),
			'rating'      => '4.95',
			'name'        => __( 'Voyage Belek Golf & Spa 5★', 'zhiraf-ufa' ),
			'description' => __( 'Безупречная гастрономия, ассистент-сервис 24/7, дизайнерские виллы среди сосен и приватная пляжная зона.', 'zhiraf-ufa' ),
			'meal'        => __( '5★ HCI', 'zhiraf-ufa' ),
			'price_rub'   => 245000,
			'wa_prefill'  => __( 'Здравствуйте! Интересует Voyage Belek Golf & Spa 5★ — премиум с вылетом из Уфы.', 'zhiraf-ufa' ),
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
	$map        = array(
		'antalya' => home_url( '/tour/turciya/antalya/' ),
	);
	return ! empty( $map[ $resort_key ] ) ? (string) $map[ $resort_key ] : '';
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
		'side'    => 'resort-turciya-belek-1.jpg',
		'kemer'   => 'resort-turciya-kemer-2.jpg',
		'alanya'  => 'resort-turciya-alanya-2.jpg',
		'belek'   => 'resort-turciya-belek-1.jpg',
		'antalya' => 'resort-turciya-antalya-2.jpg',
	);

	return ! empty( $map[ $region ] ) ? $map[ $region ] : 'resort-turciya-alanya-2.jpg';
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

	$badge_mod = 'default';
	$badge     = '';
	$cat       = ! empty( $row['category_slug'] ) ? (string) $row['category_slug'] : '';
	if ( 'family' === $cat ) {
		$badge     = __( '👨‍👩‍👧 Семейный выбор', 'zhiraf-ufa' );
		$badge_mod = 'family';
	} elseif ( 'couples' === $cat || 'premium' === $cat ) {
		$badge     = __( '🥂 Для пар', 'zhiraf-ufa' );
		$badge_mod = 'adults';
	} elseif ( 'balance' === $cat || 'urban' === $cat ) {
		$badge     = __( '⚖️ Цена / качество', 'zhiraf-ufa' );
		$badge_mod = 'value';
	}

	$resort_pill = ! empty( $row['resort'] ) ? (string) $row['resort'] : '';
	$pool_id     = ! empty( $row['id'] ) ? (int) $row['id'] : 0;
	$desc        = ! empty( $row['usp'] ) ? (string) $row['usp'] : '';

	return array(
		'pool_id'       => $pool_id,
		'pool_rank'     => 100 + $pool_id,
		'filter_tags'   => array_values( array_unique( $tags ) ),
		'resort'        => $region,
		'image'         => zhiraf_ufa_turciya_pool_image_for_region( $region ),
		'image_alt'     => sprintf(
			/* translators: %s: hotel name */
			__( 'Отель %s', 'zhiraf-ufa' ),
			$name
		),
		'badge'         => $badge,
		'badge_mod'     => $badge_mod,
		'resort_pill'   => $resort_pill,
		'subtitle'      => ! empty( $row['category_name'] ) ? (string) $row['category_name'] : __( 'Проверенный отель', 'zhiraf-ufa' ),
		'rating'        => ! empty( $row['rating'] ) ? number_format( (float) $row['rating'], 2, '.', '' ) : '4.50',
		'name'          => $name,
		'description'   => $desc,
		'price_rub'     => zhiraf_ufa_turciya_pool_price_rub( $row ),
		'wa_prefill'    => sprintf(
			/* translators: %s: hotel name */
			__( 'Здравствуйте! Интересует %1$s с вылетом из Уфы — подскажите цену на 7–9 ночей для двоих.', 'zhiraf-ufa' ),
			$name
		),
	);
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
	$rank         = 1;
	foreach ( zhiraf_ufa_turciya_curated_hotels() as $row ) {
		$starter_keys[] = zhiraf_ufa_turciya_hotel_name_key( ! empty( $row['name'] ) ? (string) $row['name'] : '' );
		$row['pool_id']     = 'starter-' . ( ! empty( $row['slot'] ) ? (string) $row['slot'] : $rank );
		$row['pool_rank']   = $rank;
		$row['filter_tags'] = zhiraf_ufa_turciya_starter_filter_tags( $row );
		$hotels[]           = $row;
		++$rank;
	}

	$json_path = function_exists( 'zhiraf_ufa_turkey_hotels_json_path' ) ? zhiraf_ufa_turkey_hotels_json_path() : '';
	if ( $json_path && is_readable( $json_path ) ) {
		$decoded = json_decode( (string) file_get_contents( $json_path ), true );
		if ( is_array( $decoded ) ) {
			foreach ( $decoded as $json_row ) {
				if ( ! is_array( $json_row ) ) {
					continue;
				}
				$key = zhiraf_ufa_turciya_hotel_name_key( ! empty( $json_row['hotel_name'] ) ? (string) $json_row['hotel_name'] : '' );
				if ( '' === $key || in_array( $key, $starter_keys, true ) ) {
					continue;
				}
				$card = zhiraf_ufa_turciya_json_row_to_card( $json_row );
				if ( $card ) {
					$hotels[] = $card;
				}
			}
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
	return array(
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
}

/**
 * Default catalog copy (starter mode).
 *
 * @return array{title:string,desc:string,eyebrow:string}
 */
function zhiraf_ufa_turciya_catalog_default_copy() {
	$filters = zhiraf_ufa_turciya_catalog_filters();

	return array(
		'eyebrow' => __( 'Выборка из базы 25 отелей', 'zhiraf-ufa' ),
		'title'   => ! empty( $filters[0]['title'] ) ? (string) $filters[0]['title'] : '',
		'desc'    => ! empty( $filters[0]['desc'] ) ? (string) $filters[0]['desc'] : '',
	);
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
 * Five resort pick cards — «В чём разница за 10 секунд».
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_turciya_resort_pick_cards() {
	$counts = zhiraf_ufa_turciya_resort_hotel_counts();

	return array(
		array(
			'id'           => 'side',
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
			'guide_label'  => __( 'Подробный гид по курорту →', 'zhiraf-ufa' ),
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
}

/**
 * Expert nuances (3 columns).
 *
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_turciya_expert_nuances() {
	return array(
		array(
			'icon'  => '🌊',
			'title' => __( 'Заход в море и пляжи', 'zhiraf-ufa' ),
			'text'  => __( 'На Сиде и Аланье — пологий песок для детей; у Кемера чаще галька, но вода прозрачнее. Уточняем тип берега и пирс до брони.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🚌',
			'title' => __( 'Трансферы по курортам', 'zhiraf-ufa' ),
			'text'  => __( 'До Антальи и Аланьи — короче путь от аэропорта; Кемер и Белек — дольше по трассе. Закладываем время в дороге в подбор.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '✈️',
			'title' => __( 'Рейсы из Уфы в сезоне', 'zhiraf-ufa' ),
			'text'  => __( 'На части дат — прямой чартер, на других — стыковка в Москве. Показываем тип перелёта и багаж по данным туроператора до оплаты.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * WhatsApp deep link for a curated hotel card.
 *
 * @param array<string,mixed> $hotel Hotel row.
 * @return string
 */
function zhiraf_ufa_turciya_curated_wa_url( $hotel ) {
	$text = ! empty( $hotel['wa_prefill'] ) ? (string) $hotel['wa_prefill'] : '';
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
