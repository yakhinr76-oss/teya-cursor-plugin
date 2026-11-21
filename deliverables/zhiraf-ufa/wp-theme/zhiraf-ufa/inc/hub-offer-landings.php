<?php
/**
 * Cross-country offer hubs: /goryashchie-tury/, /vse-vklyucheno/.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Country filter tabs for hot hub (client-side filter keys).
 *
 * @return array<int,array{id:string,label:string}>
 */
function zhiraf_ufa_hot_hub_country_tabs() {
	return array(
		array(
			'id'    => 'all',
			'label' => __( 'Все страны', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'egipet',
			'label' => __( 'Египет', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'turciya',
			'label' => __( 'Турция', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'oae',
			'label' => __( 'ОАЭ', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'tailand',
			'label' => __( 'Таиланд', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'vietnam',
			'label' => __( 'Вьетнам', 'zhiraf-ufa' ),
		),
		array(
			'id'    => 'kitaj',
			'label' => __( 'Китай', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Hot showcase: nearest departures + min price, optional country slug.
 *
 * @param int    $limit         Max cards.
 * @param string $country_slug  Empty = all hub countries.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_get_hot_hub_catalog_tours( $limit = 24, $country_slug = '' ) {
	if ( ! class_exists( 'Zhiraf_BG_Price_Engine' ) ) {
		return array();
	}
	$engine     = Zhiraf_BG_Price_Engine::get_instance();
	$countries  = array( 'egipet', 'turciya', 'oae', 'tailand', 'vietnam', 'kitaj' );
	$country_slug = sanitize_key( (string) $country_slug );
	if ( '' !== $country_slug && 'all' !== $country_slug ) {
		$countries = array( $country_slug );
	}
	$today_ts = ( new DateTime( 'today', wp_timezone() ) )->getTimestamp();
	$window   = $today_ts + ( 14 * DAY_IN_SECONDS );
	$pool     = array();

	foreach ( $countries as $slug ) {
		foreach ( $engine->get_country_tours( $slug, 250 ) as $t ) {
			if ( ! function_exists( 'zhiraf_ufa_tour_valid_for_price_card' ) || ! zhiraf_ufa_tour_valid_for_price_card( $t ) ) {
				continue;
			}
			$ts = zhiraf_ufa_departure_to_timestamp( $t['departure_date'] ?? '' );
			if ( ! $ts || $ts < $today_ts ) {
				continue;
			}
			$t['country_slug'] = $slug;
			$pool[]            = array(
				'tour' => $t,
				'ts'   => $ts,
			);
		}
	}

	usort(
		$pool,
		static function ( $a, $b ) use ( $window ) {
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

	$out = array();
	foreach ( $pool as $row ) {
		$out[] = $row['tour'];
		if ( count( $out ) >= $limit ) {
			break;
		}
	}
	return $out;
}

/**
 * «Улететь завтра» — 6 безвизовых направлений.
 *
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_hot_hub_visa_free_cards() {
	return array(
		array(
			'name'    => __( 'Турция', 'zhiraf-ufa' ),
			'flight'  => __( '~4 ч из Уфы', 'zhiraf-ufa' ),
			'visa'    => __( 'Без визы', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/turciya/' ),
			'image'   => 'country-turciya.jpg',
		),
		array(
			'name'    => __( 'Египет', 'zhiraf-ufa' ),
			'flight'  => __( '~5,5 ч', 'zhiraf-ufa' ),
			'visa'    => __( 'Без визы', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/egipet/' ),
			'image'   => 'resort-egipet-hurghada-1.jpg',
		),
		array(
			'name'    => __( 'ОАЭ', 'zhiraf-ufa' ),
			'flight'  => __( '~5 ч', 'zhiraf-ufa' ),
			'visa'    => __( 'Без визы', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/oae/' ),
			'image'   => 'hero-uae.jpg',
		),
		array(
			'name'    => __( 'Таиланд', 'zhiraf-ufa' ),
			'flight'  => __( 'Через Москву', 'zhiraf-ufa' ),
			'visa'    => __( 'Без визы до 30 дней', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/tailand/' ),
			'image'   => 'resort-phuket.jpg',
		),
		array(
			'name'    => __( 'Вьетнам', 'zhiraf-ufa' ),
			'flight'  => __( 'Через Москву', 'zhiraf-ufa' ),
			'visa'    => __( 'Без визы до 45 дней', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/vietnam/' ),
			'image'   => 'resort-vietnam-nha-trang-1.jpg',
		),
		array(
			'name'    => __( 'Китай', 'zhiraf-ufa' ),
			'flight'  => __( 'Чартер / стыковка', 'zhiraf-ufa' ),
			'visa'    => __( 'Безвиз до 30 дней (групповой)', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/kitaj/' ),
			'image'   => 'resort-sanya-2.jpg',
		),
	);
}

/**
 * «Как работают горящие» — 3 карточки.
 *
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_hot_hub_how_cards() {
	return array(
		array(
			'icon'  => 'sun',
			'title' => __( 'Когда появляются', 'zhiraf-ufa' ),
			'text'  => __( 'За 2–7 дней до вылета, если на рейсе или в отеле остаются невыкупленные места. Цена падает, пока пакет не продадут.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => 'info',
			'title' => __( 'Честный нюанс', 'zhiraf-ufa' ),
			'text'  => __( 'Из Уфы вылетов меньше, чем из Москвы: топовые 5★ на пиковые даты разбирают заранее. Чаще «горят» 3–4★ и базовые 5★.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => 'shield-check',
			'title' => __( 'Что проверяем до оплаты', 'zhiraf-ufa' ),
			'text'  => __( 'Багаж и ручную кладь, топливный сбор, трансфер и реальное наличие мест на рейсе — фиксируем в договоре.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Hot hub FAQ (4 вопроса).
 *
 * @return array<int,array{q:string,a:string}>
 */
function zhiraf_ufa_hot_hub_faq() {
	return array(
		array(
			'q' => __( 'За сколько дней до вылета искать горящий тур из Уфы?', 'zhiraf-ufa' ),
			'a' => __( 'По безвизовым направлениям (Турция, Египет, ОАЭ) пик спеццен — за 2–5 дней до вылета. Для Таиланда и Вьетнама смотрим окно 4–7 дней и готовность к быстрому бронированию.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Реально ли поймать горящий тур на семью 2+2?', 'zhiraf-ufa' ),
			'a' => __( 'Да, если гибкие по датам на 2–3 дня и готовы к отелю 4★. На школьные каникулы и Новый год семейные номера в топ-отелях редко «горят» — там лучше раннее бронирование.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Нужна ли виза для горящего вылета из Уфы?', 'zhiraf-ufa' ),
			'a' => __( 'Для массовых морских направлений из нашего чек-листа — без визы или безвиз на короткий срок. Если смотрите Азию через стыковку, проверяем загранпаспорта всей семьи до брони.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Почему цена может меняться при бронировании?', 'zhiraf-ufa' ),
			'a' => __( 'Места продаются в реальном времени: пока вы согласовываете тур, кто-то может выкупить последний блок. Мы повторно проверяем цену у туроператора и называем итог до оплаты.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * AI hub — куда лететь (4 карточки).
 *
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_ai_hub_destination_cards() {
	return array(
		array(
			'title' => __( 'Турция', 'zhiraf-ufa' ),
			'text'  => __( 'Эталонный All Inclusive и Ultra All Inclusive: детские клубы и аквапарки, сезон май–октябрь.', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/turciya/' ),
			'image' => 'resort-turciya-belek-1.jpg',
		),
		array(
			'title' => __( 'Египет', 'zhiraf-ufa' ),
			'text'  => __( 'Круглогодичный AI у Красного моря: рифы у отеля или пологий песок — подбираем по вашему сценарию.', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/egipet/' ),
			'image' => 'resort-egipet-sharm-1.jpg',
		),
		array(
			'title' => __( 'ОАЭ', 'zhiraf-ufa' ),
			'text'  => __( 'Пляжные резорты Дубая и Шарджи. Честный нюанс: в Шардже концепция AI часто строго безалкогольная.', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/oae/' ),
			'image' => 'resort-abu-dabi.jpg',
		),
		array(
			'title' => __( 'Азия', 'zhiraf-ufa' ),
			'text'  => __( 'All Inclusive в крупных резортах: Vinpearl / международные сети в Нячанге, Хайтяньвэнь в Санье.', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/vietnam/' ),
			'url2'  => home_url( '/tour/kitaj/' ),
			'image' => 'resort-vietnam-nha-trang-1.jpg',
		),
	);
}

/**
 * AI hub catalog filters.
 *
 * @return array<int,array{id:string,label:string,filter:string}>
 */
function zhiraf_ufa_ai_hub_catalog_tabs() {
	return array(
		array(
			'id'     => 'all',
			'label'  => __( 'Все', 'zhiraf-ufa' ),
			'filter' => 'all',
		),
		array(
			'id'     => 'turciya',
			'label'  => __( 'Турция', 'zhiraf-ufa' ),
			'filter' => 'country:turciya',
		),
		array(
			'id'     => 'egipet',
			'label'  => __( 'Египет', 'zhiraf-ufa' ),
			'filter' => 'country:egipet',
		),
		array(
			'id'     => 'oae',
			'label'  => __( 'ОАЭ', 'zhiraf-ufa' ),
			'filter' => 'country:oae',
		),
		array(
			'id'     => 'kids',
			'label'  => __( 'Для отдыха с детьми', 'zhiraf-ufa' ),
			'filter' => 'kids',
		),
		array(
			'id'     => 'uai',
			'label'  => __( 'Ultra All Inclusive', 'zhiraf-ufa' ),
			'filter' => 'meal:uai',
		),
	);
}

/**
 * AI «что входит» — 3 колонки.
 *
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_ai_hub_included_columns() {
	return array(
		array(
			'icon'  => 'hotel',
			'title' => __( 'Питание и напитки', 'zhiraf-ufa' ),
			'text'  => __( 'Местный и импортный алкоголь зависит от концепции AI или Ultra AI. Свежевыжатые соки и кофе — по правилам отеля, не по рекламному буклету.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => 'receipt',
			'title' => __( 'Рестораны à la carte', 'zhiraf-ufa' ),
			'text'  => __( 'В Ultra AI часто входит 1–2 визита по записи. В классическом AI — символическая доплата или платное бронирование.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => 'palmtree',
			'title' => __( 'Пляж и сервис', 'zhiraf-ufa' ),
			'text'  => __( 'Лежаки у бассейна и на пляже обычно включены. Кабаны, спа, дайвинг и водные аттракционы — чаще за отдельную плату.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * AI hub FAQ (4 вопроса).
 *
 * @return array<int,array{q:string,a:string}>
 */
function zhiraf_ufa_ai_hub_faq() {
	return array(
		array(
			'q' => __( 'Чем отличается All Inclusive от Ultra All Inclusive?', 'zhiraf-ufa' ),
			'a' => __( 'Ultra All Inclusive расширяет список напитков (импорт), рестораны à la carte и иногда мини-бар. В договоре фиксируем код питания AI или UAI — без сюрпризов при заселении.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Куда лететь на AI зимой из Уфы?', 'zhiraf-ufa' ),
			'a' => __( 'Египет и ОАЭ — круглый год. Турция — с апреля по октябрь на пляж. Для тропиков зимой смотрим Таиланд и Вьетнам со стыковкой в Москве.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Стоит ли брать AI в ОАЭ и Азии?', 'zhiraf-ufa' ),
			'a' => __( 'В ОАЭ AI удобен на курортах Шарджи и Рас-эль-Хаймы. В Азии формат развит в крупных резортах (Нячанг, Санья); мелкие бутик-отели чаще работают на завтраках.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Какие бывают доплаты в отеле?', 'zhiraf-ufa' ),
			'a' => __( 'Мини-бар премиум, SPA, трансфер класса VIP, поздний выезд и экскурсии с гидом. Их не включают в пакет — проговариваем до оплаты тура.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Filter AI tours for hub tabs (client sets data-*; server marks rows).
 *
 * @param array<string,mixed> $tour Tour row.
 * @return string Space-separated filter tokens.
 */
function zhiraf_ufa_ai_hub_tour_filter_tokens( $tour ) {
	$tokens = array();
	$slug   = ! empty( $tour['country_slug'] ) ? sanitize_key( (string) $tour['country_slug'] ) : '';
	if ( $slug ) {
		$tokens[] = 'country:' . $slug;
	}
	$meal = strtoupper( (string) ( $tour['meal_code'] ?? '' ) );
	if ( 'UAI' === $meal ) {
		$tokens[] = 'meal:uai';
	}
	if ( in_array( $meal, array( 'AI', 'UAI' ), true ) ) {
		$tokens[] = 'meal:ai';
	}
	$stars = (float) preg_replace( '/[^\d.]/', '', (string) ( $tour['stars'] ?? '0' ) );
	if ( $stars >= 4.0 ) {
		$tokens[] = 'kids';
	}
	$name = mb_strtolower( (string) ( $tour['hotel_name'] ?? '' ), 'UTF-8' );
	if ( preg_match( '/family|kids|club|deluxe|resort/i', $name ) ) {
		$tokens[] = 'kids';
	}
	return implode( ' ', $tokens );
}

/**
 * Homepage quick scenario chips (offer hubs).
 *
 * @return array<int,array{label:string,url:string,icon:string}>
 */
function zhiraf_ufa_home_offer_scenario_chips() {
	return array(
		array(
			'label' => __( 'Горящие вылеты', 'zhiraf-ufa' ),
			'url'   => home_url( '/goryashchie-tury/' ),
			'icon'  => 'tag',
		),
		array(
			'label' => __( 'Всё включено', 'zhiraf-ufa' ),
			'url'   => home_url( '/vse-vklyucheno/' ),
			'icon'  => 'hotel',
		),
	);
}