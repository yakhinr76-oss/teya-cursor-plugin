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
			'id'    => 'all',
			'label' => __( 'Все', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'side',
			'label' => __( 'Сиде — песок', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'kemer',
			'label' => __( 'Кемер — горы', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'alanya',
			'label' => __( 'Аланья — бюджет', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'belek',
			'label' => __( 'Белек — премиум', 'zhiraf-ufa' ),
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
			'image'       => 'resort-turciya-side-1.jpg',
			'image_alt'   => __( 'Семейный отель на Сиде', 'zhiraf-ufa' ),
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
			'image'       => 'resort-turciya-belek-2.jpg',
			'image_alt'   => __( 'Отель для взрослых, Сиде', 'zhiraf-ufa' ),
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
	return __( 'Здравствуйте! Хочу сравнить Кемер и Сиде под наш бюджет с вылетом из Уфы. Подскажите 2–3 отеля на 7–9 ночей для двоих.', 'zhiraf-ufa' );
}
