<?php
/**
 * Universal excursion curated landing (programs JSON + layout pack).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * JSON path for excursion programs.
 *
 * @param string $slug Destination slug.
 * @return string
 */
function zhiraf_ufa_excursion_programs_json_path( $slug ) {
	$slug = sanitize_key( (string) $slug );
	return ZHIRAF_UFA_DIR . '/data/excursion_' . $slug . '.json';
}

/**
 * Load programs from JSON.
 *
 * @param string $slug Destination slug.
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_excursion_programs( $slug ) {
	$path = zhiraf_ufa_excursion_programs_json_path( $slug );
	if ( ! is_readable( $path ) ) {
		return array();
	}
	$raw = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! $raw ) {
		return array();
	}
	$data = json_decode( $raw, true );
	if ( ! is_array( $data ) || empty( $data['programs'] ) || ! is_array( $data['programs'] ) ) {
		return array();
	}
	$out = array();
	foreach ( $data['programs'] as $row ) {
		if ( empty( $row['id'] ) ) {
			continue;
		}
		$row['days']            = zhiraf_ufa_excursion_program_days( isset( $row['duration'] ) ? (string) $row['duration'] : '' );
		$row['transport_mode']  = zhiraf_ufa_excursion_program_transport_mode( $row );
		$out[]                  = $row;
	}
	return $out;
}

/**
 * Parse day count from duration label.
 *
 * @param string $duration e.g. "8 дней / 7 ночей".
 * @return int
 */
function zhiraf_ufa_excursion_program_days( $duration ) {
	if ( preg_match( '/(\d+)\s*дн/u', $duration, $m ) ) {
		return (int) $m[1];
	}
	return 0;
}

/**
 * Avia vs bus bucket for catalog filters.
 *
 * @param array<string,mixed> $row Program row.
 * @return string avia|bus
 */
function zhiraf_ufa_excursion_program_transport_mode( array $row ) {
	if ( ! empty( $row['format'] ) && 'bus' === sanitize_key( (string) $row['format'] ) ) {
		return 'bus';
	}
	$transport = isset( $row['transport'] ) ? mb_strtolower( (string) $row['transport'], 'UTF-8' ) : '';
	if ( false !== mb_strpos( $transport, 'автобус', 0, 'UTF-8' ) ) {
		return 'bus';
	}
	return 'avia';
}

/**
 * Slugs with active excursion pack + JSON.
 *
 * @return string[]
 */
function zhiraf_ufa_excursion_landing_slugs() {
	$candidates = array( 'uzbekistan', 'gruziya', 'armeniya' );
	$out        = array();
	foreach ( $candidates as $slug ) {
		if ( zhiraf_ufa_excursion_landing_pack( $slug ) && zhiraf_ufa_excursion_programs( $slug ) ) {
			$out[] = $slug;
		}
	}
	return $out;
}

/**
 * Virtual /tour/{slug}/ query var when no WP page exists.
 *
 * @return string
 */
function zhiraf_ufa_current_excursion_country_slug() {
	$slug = get_query_var( 'zhiraf_excursion_country' );
	return is_string( $slug ) ? sanitize_key( $slug ) : '';
}

/**
 * Format price for cards.
 *
 * @param int $amount RUB.
 * @return string
 */
function zhiraf_ufa_excursion_format_price( $amount ) {
	return sprintf(
		/* translators: %s: formatted amount */
		__( 'от %s ₽', 'zhiraf-ufa' ),
		number_format( max( 0, (int) $amount ), 0, '.', ' ' )
	);
}

/**
 * Resolve program card photo from JSON "photo" field (theme-relative path).
 *
 * @param string $photo Path relative to theme root or assets/images.
 * @return array{url:string,alt:string,width:int,height:int}|null
 */
function zhiraf_ufa_excursion_program_photo( $photo ) {
	$photo = ltrim( (string) $photo, '/' );
	if ( '' === $photo ) {
		return null;
	}

	$paths = array(
		ZHIRAF_UFA_DIR . '/' . $photo,
		ZHIRAF_UFA_DIR . '/assets/images/' . $photo,
		ZHIRAF_UFA_DIR . '/assets/img/excursions/' . $photo,
	);

	foreach ( $paths as $abs ) {
		if ( ! is_readable( $abs ) ) {
			continue;
		}
		$rel = str_replace( trailingslashit( ZHIRAF_UFA_DIR ), '', $abs );
		$url = trailingslashit( ZHIRAF_UFA_URI ) . ltrim( $rel, '/' );

		$width  = 1200;
		$height = 675;
		$size   = @getimagesize( $abs );
		if ( is_array( $size ) ) {
			$width  = (int) $size[0];
			$height = (int) $size[1];
		}

		return array(
			'url'    => $url,
			'alt'    => '',
			'width'  => $width,
			'height' => $height,
		);
	}

	return null;
}

/**
 * Transport + duration badge for program card photo overlay.
 *
 * @param array<string,mixed> $program Program row.
 * @return string
 */
function zhiraf_ufa_excursion_program_media_badge( array $program ) {
	$transport = isset( $program['transport'] ) ? trim( (string) $program['transport'] ) : '';
	$duration  = isset( $program['duration'] ) ? trim( (string) $program['duration'] ) : '';

	$days_label = $duration;
	if ( preg_match( '/^([^\/]+)/u', $duration, $m ) ) {
		$days_label = trim( $m[1] );
	}

	if ( $transport && $days_label ) {
		return $transport . ' • ' . $days_label;
	}

	return $transport ? $transport : $days_label;
}

/**
 * WA/messenger prefill for a program card.
 *
 * @param array<string,mixed> $program Program row.
 * @return string
 */
function zhiraf_ufa_excursion_program_lead_message( array $program ) {
	$title = ! empty( $program['title'] ) ? (string) $program['title'] : '';
	$id    = ! empty( $program['id'] ) ? (string) $program['id'] : '';
	/* translators: 1: program title, 2: program id */
	return sprintf(
		__( 'Здравствуйте! Пришлите программу по дням: «%1$s» (%2$s), вылет из Уфы. Даты и состав уточню в переписке.', 'zhiraf-ufa' ),
		$title,
		$id
	);
}

/**
 * Day-duration filter chips for catalog (days filter mode).
 *
 * @param array<string,mixed> $pack Landing pack.
 * @return array<int,array{key:string,label:string,min:int,max:int}>
 */
function zhiraf_ufa_excursion_catalog_day_filters( array $pack ) {
	if ( ! empty( $pack['catalog_day_filters'] ) && is_array( $pack['catalog_day_filters'] ) ) {
		return $pack['catalog_day_filters'];
	}
	return array(
		array(
			'key'   => '3-5',
			'label' => __( '3–5 дней', 'zhiraf-ufa' ),
			'min'   => 3,
			'max'   => 5,
		),
		array(
			'key'   => '6-9',
			'label' => __( '6–9 дней', 'zhiraf-ufa' ),
			'min'   => 6,
			'max'   => 9,
		),
	);
}

/**
 * JSON map key => [min,max] for JS filters.
 *
 * @param array<string,mixed> $pack Landing pack.
 * @return array<string,array{0:int,1:int}>
 */
function zhiraf_ufa_excursion_catalog_day_range_map( array $pack ) {
	$map = array();
	foreach ( zhiraf_ufa_excursion_catalog_day_filters( $pack ) as $row ) {
		if ( empty( $row['key'] ) ) {
			continue;
		}
		$map[ (string) $row['key'] ] = array(
			(int) ( $row['min'] ?? 0 ),
			(int) ( $row['max'] ?? 0 ),
		);
	}
	return $map;
}

/**
 * Static layout packs per destination (extend for future excursion landings).
 *
 * @param string $slug Destination slug.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_excursion_landing_pack( $slug ) {
	$slug = sanitize_key( (string) $slug );
	$packs = array(
		'uzbekistan' => array(
			'slug'                 => 'uzbekistan',
			'h1'                   => __( 'Экскурсионные туры в Узбекистан из Уфы', 'zhiraf-ufa' ),
			'title'                => __( 'Узбекистан из Уфы — подбор тура «Жираф»', 'zhiraf-ufa' ),
			'description'          => __( 'Пакетные туры в Узбекистан с вылетом из Уфы. Подбор без давления, актуальные цены Wordstat 2026-11-19.', 'zhiraf-ufa' ),
			'hero_lead'            => __( 'Прямые перелёты в Ташкент и Самарканд за 2,5–3 часа. Отели 3–4★ в центре, скоростной поезд «Афросиаб» и проверенные гиды — подберём маршрут под ваши даты', 'zhiraf-ufa' ),
			'hero_badges'          => array(
				array( 'text' => __( 'Прямой рейс из Уфы • ~3 часа', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'Без визы • нужен загранпаспорт', 'zhiraf-ufa' ) ),
			),
			'hero_trust'           => array(
				array(
					'icon' => 'plane',
					'text' => __( 'Прямые рейсы в Ташкент (Uzbekistan Airways, Utair) и Самарканд («Азимут»)', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'shield-check',
					'text' => __( 'Без визы и регистрации до 15 дней (только загранпаспорт, включая детей)', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'credit-card',
					'text' => __( 'Честная смета до оплаты: сразу расписываем билеты, питание и доплаты', 'zhiraf-ufa' ),
				),
			),
			'catalog_filter_mode'  => 'days',
			'convert_card'         => array(
				'title' => __( 'Нужен другой формат или автобусный тур?', 'zhiraf-ufa' ),
				'text'  => __( 'Соберём индивидуальный маршрут под ваши даты. Автобусные поездки из Уфы — под запрос после подтверждения перевозчика и дат', 'zhiraf-ufa' ),
				'cta'   => __( 'Описать задачу', 'zhiraf-ufa' ),
			),
			'format_cards'         => array(
				array(
					'key'   => 'classic',
					'title' => __( 'Классика Востока', 'zhiraf-ufa' ),
					'desc'  => __( 'Ташкент, Самарканд, Бухара, Хива', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'key'   => 'weekend',
					'title' => __( 'Уикенд 3–4 дня', 'zhiraf-ufa' ),
					'desc'  => __( 'прямой рейс в Самарканд без смены отелей', 'zhiraf-ufa' ),
					'icon'  => 'plane',
				),
				array(
					'key'   => 'gastro',
					'title' => __( 'Гастро и рынки', 'zhiraf-ufa' ),
					'desc'  => __( 'плов, тандырная самса, Чорсу', 'zhiraf-ufa' ),
					'icon'  => 'tag',
				),
				array(
					'key'   => 'nature',
					'title' => __( 'Горы и природа', 'zhiraf-ufa' ),
					'desc'  => __( 'Ташкент + Чимган и Чарвак', 'zhiraf-ufa' ),
					'icon'  => 'palmtree',
				),
			),
			'price_footnote'       => __( 'ориентир за 1 чел. при 2-местном размещении (авиа из Уфы + отели 3–4★ + завтраки)', 'zhiraf-ufa' ),
			'included'             => array(
				'title'    => __( 'Что входит в программу и что оплачивается отдельно', 'zhiraf-ufa' ),
				'in_price' => array(
					__( 'Авиабилеты из Уфы (или стыковка — согласуем заранее)', 'zhiraf-ufa' ),
					__( 'Отели 3–4★ с завтраками в центре городов', 'zhiraf-ufa' ),
					__( 'Трансферы аэропорт — отель и между объектами по программе', 'zhiraf-ufa' ),
					__( 'Русскоязычный гид на экскурсиях', 'zhiraf-ufa' ),
				),
				'extra'    => array(
					__( 'Багаж сверх нормы и местные авиаперелёты', 'zhiraf-ufa' ),
					__( 'Входные билеты в музеи и мавзолеи', 'zhiraf-ufa' ),
					__( 'Обеды и ужины (кроме указанных дегустаций)', 'zhiraf-ufa' ),
					__( 'Турсбор, страховка, чаевые', 'zhiraf-ufa' ),
					__( 'Раннее заселение / поздний выезд', 'zhiraf-ufa' ),
				),
			),
			'know_title'           => __( 'Что важно знать об Узбекистане', 'zhiraf-ufa' ),
			'know_cards'           => array(
				array(
					'title' => __( 'Сезонность', 'zhiraf-ufa' ),
					'text'  => __( 'Лучшее время — апрель–май и сентябрь–октябрь. С середины июня до августа жара +40 °C (делаем ранние старты), зимой бывают минусовые температуры', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'title' => __( 'Деньги и связь', 'zhiraf-ufa' ),
					'text'  => __( 'Карты «Мир» не работают — везите наличные доллары (обмен в банках на сумы). Интернет — местная SIM по загранпаспорту или eSIM', 'zhiraf-ufa' ),
					'icon'  => 'credit-card',
				),
				array(
					'title' => __( 'Темп и одежда', 'zhiraf-ufa' ),
					'text'  => __( 'Много пеших прогулок, брусчатки и ступеней. Для мечетей и святынь нужны закрытые плечи, колени и платок для женщин', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
			),
			'faq'                  => array(
				array(
					'q' => __( 'Можно ли ехать с детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Да, но ребёнку нужен собственный загранпаспорт. Для семей лучше 1–2 города без ежедневных межгородских переездов — подскажем маршрут под возраст.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Какие доплаты могут быть на месте?', 'zhiraf-ufa' ),
					'a' => __( 'Входные билеты в музеи, обеды и ужины, сувениры. Перед оплатой фиксируем закрытый список доплат по вашей программе.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Всё питание включено? Можно ли без жирного и мясного?', 'zhiraf-ufa' ),
					'a' => __( 'В базовой цене — завтраки в отелях. Обеды и ужины, а также щадящее или вегетарианское меню согласуем заранее с гидом и ресторанами.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Безопасно ли гулять самостоятельно?', 'zhiraf-ufa' ),
					'a' => __( 'Вечерние Ляби-Хауз и подсвеченный Регистан комфортны для прогулок. На базарах следите за вещами. Полис ОМС за рубежом не действует — оформляем туристическую страховку.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'За неделю успеем Ташкент, Самарканд, Бухару и Хиву?', 'zhiraf-ufa' ),
					'a' => __( 'За 7 дней реалистично 3 города с комфортным темпом. Маршрут на 9 дней — если нужна Хива и перелёты между регионами.', 'zhiraf-ufa' ),
				),
			),
			'seo_details'          => array(
				array(
					'title' => __( 'Куда сходить вечером (Ляби-Хауз, Регистан, Пои-Калон)', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'В Бухаре вечером удобнее всего Ляби-Хауз — чайханы, подсветка медресе и спокойный темп без жары. В Самарканде к закату приходят на площадь Регистан; в Ташкенте — прогулка по старому городу и вечерний базар.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Гастрономия: плов, самса, шивит оши', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Сравниваем самаркандский и ташкентский плов, закладываем дегустацию тандырной самсы и, по маршруту в Хорезм, — шивит оши. Жирные обеды чередуем с лёгкими ужинами, особенно летом.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Правила въезда и автобусные туры из Уфы', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Гражданам РФ нужен загранпаспорт, виза не требуется при сроке до 15 дней. Автобусные поездки из Уфы берём под запрос: согласуем перевозчика, даты и ночлеги до бронирования.', 'zhiraf-ufa' ) . '</p>',
				),
			),
			'excursion_chips'      => array(
				array( 'label' => __( 'Грузия', 'zhiraf-ufa' ), 'url' => '/tour/gruziya/' ),
				array( 'label' => __( 'Армения', 'zhiraf-ufa' ), 'url' => '/tour/armeniya/' ),
				array( 'label' => __( 'Санкт-Петербург', 'zhiraf-ufa' ), 'url' => '/tour/sankt-peterburg/' ),
				array( 'label' => __( 'Калининград', 'zhiraf-ufa' ), 'url' => '/tour/kaliningrad/' ),
			),
			'final_cta_title'      => __( 'Подберём маршрут по Узбекистану под ваши даты и темп за 15 минут', 'zhiraf-ufa' ),
			'quiz_prefill'         => __( 'Узбекистан, экскурсионный тур из Уфы', 'zhiraf-ufa' ),
			'modal_default_title'  => __( 'Программа по дням в мессенджер', 'zhiraf-ufa' ),
			'modal_default_lead'   => __( 'Укажите телефон и выберите MAX, WhatsApp или Telegram — пришлём PDF-программу с днями и ориентиром по цене.', 'zhiraf-ufa' ),
			'image'                => 'resort-samarkand.jpg',
			'image_alt'            => __( 'Узбекистан — площадь Регистан в Самарканде', 'zhiraf-ufa' ),
			'name'                 => __( 'Узбекистан', 'zhiraf-ufa' ),
		),
		'gruziya'    => array(
			'slug'                 => 'gruziya',
			'h1'                   => __( 'Экскурсионные туры в Грузию из Уфы', 'zhiraf-ufa' ),
			'title'                => __( 'Грузию из Уфы — подбор тура «Жираф»', 'zhiraf-ufa' ),
			'description'          => __( 'Пакетные туры в Грузию с вылетом из Уфы. Подбор без давления, актуальные цены Wordstat 2026-11-19.', 'zhiraf-ufa' ),
			'hero_lead'            => __( 'Прямой перелёт в Тбилиси за 3 часа или бюджетные автобусные туры. Старый город, винодельни Кахетии, горы Казбеги и честная смета без скрытых доплат', 'zhiraf-ufa' ),
			'hero_badges'          => array(
				array( 'text' => __( 'Прямой рейс в Тбилиси • ~3 ч', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'Без визы • нужна страховка', 'zhiraf-ufa' ) ),
			),
			'hero_trust'           => array(
				array(
					'icon' => 'plane',
					'text' => __( 'Прямой рейс Уфа — Тбилиси («Азимут») или автобусные туры через Северный Кавказ', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'shield-check',
					'text' => __( 'Без визы до 1 года (нужен загранпаспорт, включая детей)', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'credit-card',
					'text' => __( 'Оформляем обязательную страховку от 30 000 GEL по правилам въезда 2026 года', 'zhiraf-ufa' ),
				),
			),
			'catalog_filter_mode'  => 'transport',
			'convert_card'         => array(
				'title' => __( 'Нужен индивидуальный маршрут?', 'zhiraf-ufa' ),
				'text'  => __( 'Соберём программу под ваши даты, состав группы и желаемый темп (включая семейные варианты без длинных переездов)', 'zhiraf-ufa' ),
				'cta'   => __( 'Описать задачу', 'zhiraf-ufa' ),
			),
			'format_cards'         => array(
				array(
					'key'   => 'classic',
					'title' => __( 'Классика и горы', 'zhiraf-ufa' ),
					'desc'  => __( 'Тбилиси, Мцхета, Кахетия, Казбеги', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'key'   => 'weekend',
					'title' => __( 'Уикенд 3–4 дня', 'zhiraf-ufa' ),
					'desc'  => __( 'Тбилиси + Мцхета на прямом рейсе', 'zhiraf-ufa' ),
					'icon'  => 'plane',
				),
				array(
					'key'   => 'gastro',
					'title' => __( 'Вино и гастро', 'zhiraf-ufa' ),
					'desc'  => __( 'Кахетия, погреба квеври, хинкали', 'zhiraf-ufa' ),
					'icon'  => 'tag',
				),
				array(
					'key'   => 'bus',
					'title' => __( 'Автобус из Уфы', 'zhiraf-ufa' ),
					'desc'  => __( 'бюджетный маршрут от 45 000 ₽', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
			),
			'price_footnote'       => __( 'ориентир за 1 чел. при 2-местном размещении (перелёт или автобус из Уфы + отели 3–4★ + завтраки)', 'zhiraf-ufa' ),
			'included'             => array(
				'title'    => __( 'Что входит в программу и что оплачивается отдельно', 'zhiraf-ufa' ),
				'in_price' => array(
					__( 'Перелёт из Уфы или автобусный проезд по программе', 'zhiraf-ufa' ),
					__( 'Отели 3–4★ с завтраками', 'zhiraf-ufa' ),
					__( 'Трансферы и экскурсии по маршруту', 'zhiraf-ufa' ),
					__( 'Русскоязычный гид', 'zhiraf-ufa' ),
				),
				'extra'    => array(
					__( 'Входные билеты в музеи и объектов', 'zhiraf-ufa' ),
					__( 'Фуникулёр на Мтацминду', 'zhiraf-ufa' ),
					__( 'Серные бани', 'zhiraf-ufa' ),
					__( 'Подъезд к Гергети (при необходимости)', 'zhiraf-ufa' ),
					__( 'Дегустации, обеды и ужины', 'zhiraf-ufa' ),
				),
			),
			'know_title'           => __( 'Что важно знать о Грузии в 2026 году', 'zhiraf-ufa' ),
			'know_cards'           => array(
				array(
					'title' => __( 'Въезд и страховка', 'zhiraf-ufa' ),
					'text'  => __( 'Нужен загранпаспорт и обязательная медстраховка от 30 000 GEL (на английском/грузинском). Въезд через Абхазию и Южную Осетию запрещён', 'zhiraf-ufa' ),
					'icon'  => 'shield-check',
				),
				array(
					'title' => __( 'Деньги и связь', 'zhiraf-ufa' ),
					'text'  => __( 'Карты «Мир» и российских банков не работают — везите наличные (доллары, евро или рубли для обмена на лари). В горах связь нестабильна, скачайте офлайн-карты', 'zhiraf-ufa' ),
					'icon'  => 'credit-card',
				),
				array(
					'title' => __( 'Сезон и дороги', 'zhiraf-ufa' ),
					'text'  => __( 'Лучшее время для Тбилиси и Кахетии — май–июнь и сентябрь–октябрь. Зимой и в непогоду Военно-Грузинскую дорогу к Казбеги могут перекрывать', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
			),
			'faq'                  => array(
				array(
					'q' => __( 'Можно ли поехать с детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Да: нужен свой загранпаспорт и страховка. Для семей с детьми лучше авиатур и проживание в одном отеле в Тбилиси, без ночных автобусов.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Какие доплаты возможны на месте?', 'zhiraf-ufa' ),
					'a' => __( 'Входные билеты, фуникулёр, серные бани, подъезд к Гергети, обеды и ужины — фиксируем список до бронирования.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Питание включено? Есть ли варианты без мяса?', 'zhiraf-ufa' ),
					'a' => __( 'Базово — завтраки. В грузинской кухне есть хинкали с грибами, сыром и картофелем; согласуем меню заранее.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Безопасно ли ехать и нужна ли страховка?', 'zhiraf-ufa' ),
					'a' => __( 'С 2026 года страховка от 30 000 GEL обязательна по закону. В горах учитываем погодные условия и темп маршрута.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Гарантирована ли поездка в Казбеги и вид на Казбек?', 'zhiraf-ufa' ),
					'a' => __( 'Проезд зависит от погоды на перевале. При закрытии дороги заранее прописываем замену экскурсии или перерасчёт.', 'zhiraf-ufa' ),
				),
			),
			'seo_details'          => array(
				array(
					'title' => __( 'Куда сходить вечером в Тбилиси (Абанотубани, Мтацминда, Шардени)', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Вечером удобно гулять по Абанотубани и улице Шардени, подняться на Мтацминду ради панорамы. На ужин закладываем хинкали, хачапури и лёгкие блюда без жары днём.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Гастрономия: хинкали, аджарский хачапури, вино из квеври', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'В Кахетии — дегустации в квеври, в Тбилиси — мастер-классы по хинкали. Меню согласуем до оплаты, для непьющих подбираем альтернативы.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Автобусные туры из Уфы через Верхний Ларс: что учесть', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'В пути до двух суток в одну сторону, ночные переезды и прохождение границы Верхний Ларс. Перевозчика и даты подтверждаем до брони; маршрут не подходит при низкой переносимости долгой дороги.', 'zhiraf-ufa' ) . '</p>',
				),
			),
			'excursion_chips'      => array(
				array( 'label' => __( 'Узбекистан', 'zhiraf-ufa' ), 'url' => '/tour/uzbekistan/' ),
				array( 'label' => __( 'Армения', 'zhiraf-ufa' ), 'url' => '/tour/armeniya/' ),
				array( 'label' => __( 'Санкт-Петербург', 'zhiraf-ufa' ), 'url' => '/tour/sankt-peterburg/' ),
				array( 'label' => __( 'Калининград', 'zhiraf-ufa' ), 'url' => '/tour/kaliningrad/' ),
			),
			'final_cta_title'      => __( 'Подберём маршрут по Грузии под ваши даты и темп за 15 минут', 'zhiraf-ufa' ),
			'quiz_prefill'         => __( 'Грузия, экскурсионный тур из Уфы', 'zhiraf-ufa' ),
			'modal_default_title'  => __( 'Программа по дням в мессенджер', 'zhiraf-ufa' ),
			'modal_default_lead'   => __( 'Укажите телефон и выберите MAX, WhatsApp или Telegram — пришлём PDF-программу с днями и ориентиром по цене.', 'zhiraf-ufa' ),
			'image'                => 'hero-collage-active.jpg',
			'image_alt'            => __( 'Грузия — Старый Тбилиси и Кахетия', 'zhiraf-ufa' ),
			'name'                 => __( 'Грузия', 'zhiraf-ufa' ),
		),
		'armeniya'   => array(
			'slug'                 => 'armeniya',
			'h1'                   => __( 'Экскурсионные туры в Армению из Уфы', 'zhiraf-ufa' ),
			'title'                => __( 'Армению из Уфы — подбор тура «Жираф»', 'zhiraf-ufa' ),
			'description'          => __( 'Пакетные туры в Армению с вылетом из Уфы. Подбор без давления, актуальные цены Wordstat 2026-11-19.', 'zhiraf-ufa' ),
			'hero_lead'            => __( 'Прямой перелёт в Ереван за 3,5 часа по внутреннему паспорту РФ. Древние монастыри, озеро Севан, винодельни Арени и честная смета без скрытых доплат', 'zhiraf-ufa' ),
			'hero_badges'          => array(
				array( 'text' => __( 'Прямой рейс в Ереван • ~3,5 ч', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'Без визы • можно без загранпаспорта', 'zhiraf-ufa' ) ),
			),
			'hero_trust'           => array(
				array(
					'icon' => 'plane',
					'text' => __( 'Прямые рейсы Уфа — Ереван («Азимут», Shirak Avia) — стыкуем даты под расписание', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'shield-check',
					'text' => __( 'Въезд по внутреннему паспорту РФ при прямом перелёте (детям до 14 лет нужен загранпаспорт)', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'credit-card',
					'text' => __( 'Прозрачный расчёт до оплаты: сразу фиксируем багаж, входные билеты и дегустации', 'zhiraf-ufa' ),
				),
			),
			'catalog_filter_mode'  => 'days',
			'catalog_day_filters'  => array(
				array(
					'key'   => '4-5',
					'label' => __( '4–5 дней', 'zhiraf-ufa' ),
					'min'   => 4,
					'max'   => 5,
				),
				array(
					'key'   => '6-7',
					'label' => __( '6–7 дней', 'zhiraf-ufa' ),
					'min'   => 6,
					'max'   => 7,
				),
			),
			'convert_card'         => array(
				'title' => __( 'Нужен индивидуальный маршрут или проезд автобусом?', 'zhiraf-ufa' ),
				'text'  => __( 'Соберём программу под ваши даты и состав группы. Формат «рейсовый автобус до Еревана + экскурсии на месте» рассчитываем под запрос', 'zhiraf-ufa' ),
				'cta'   => __( 'Описать задачу', 'zhiraf-ufa' ),
			),
			'format_cards'         => array(
				array(
					'key'   => 'classic',
					'title' => __( 'Первое знакомство', 'zhiraf-ufa' ),
					'desc'  => __( 'Ереван, Гарни, Гегард, Севан, Дилижан', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'key'   => 'weekend',
					'title' => __( 'Уикенд 3–4 дня', 'zhiraf-ufa' ),
					'desc'  => __( 'Ереван и ближайшие выезды', 'zhiraf-ufa' ),
					'icon'  => 'plane',
				),
				array(
					'key'   => 'gastro',
					'title' => __( 'Вино и гастро', 'zhiraf-ufa' ),
					'desc'  => __( 'Арени, Воскеваз, лаваш, хоровац', 'zhiraf-ufa' ),
					'icon'  => 'tag',
				),
				array(
					'key'   => 'nature',
					'title' => __( 'Горы и природа', 'zhiraf-ufa' ),
					'desc'  => __( 'Татев, Дилижан, Севан, ущелья', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
			),
			'price_footnote'       => __( 'ориентир за 1 чел. при 2-местном размещении (авиа из Уфы + отели 3–4★ + завтраки)', 'zhiraf-ufa' ),
			'included'             => array(
				'title'    => __( 'Что входит в программу и что оплачивается отдельно', 'zhiraf-ufa' ),
				'in_price' => array(
					__( 'Авиаперелёт из Уфы', 'zhiraf-ufa' ),
					__( 'Отели 3–4★ с завтраками', 'zhiraf-ufa' ),
					__( 'Трансферы по программе', 'zhiraf-ufa' ),
					__( 'Русскоязычный гид', 'zhiraf-ufa' ),
				),
				'extra'    => array(
					__( 'Багаж сверх нормы', 'zhiraf-ufa' ),
					__( 'Билет на канатную дорогу «Крылья Татева»', 'zhiraf-ufa' ),
					__( 'Входные билеты в музеи и монастыри', 'zhiraf-ufa' ),
					__( 'Обеды, ужины и дополнительные дегустации', 'zhiraf-ufa' ),
				),
			),
			'know_title'           => __( 'Что важно знать об Армении в 2026 году', 'zhiraf-ufa' ),
			'know_cards'           => array(
				array(
					'title' => __( 'Сезонность', 'zhiraf-ufa' ),
					'text'  => __( 'Лучшее время — апрель–май и сентябрь–октябрь. В июле–августе в Ереване выше +30 °C (ставим прогулки на утро/вечер), а зимой и в горах нужны тёплые вещи', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'title' => __( 'Деньги и связь', 'zhiraf-ufa' ),
					'text'  => __( 'Возьмите наличные (рубли, доллары или евро для обмена на драмы). Карты «Мир» работают ограниченно — в основном для снятия наличных в ВТБ (Армения). Связь: местные SIM/eSIM (Viva, Ucom, Team)', 'zhiraf-ufa' ),
					'icon'  => 'credit-card',
				),
				array(
					'title' => __( 'Обувь и переезды', 'zhiraf-ufa' ),
					'text'  => __( 'Переезды между локациями на авто/микроавтобусе. У монастырей и на Каскаде много ступеней — берите нескользящую обувь и закрытую одежду для действующих храмов', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
			),
			'faq'                  => array(
				array(
					'q' => __( 'Можно ли поехать без загранпаспорта?', 'zhiraf-ufa' ),
					'a' => __( 'Да, взрослым при прямом перелёте в Ереван/Гюмри — по внутреннему паспорту РФ. Для автобуса или транзита, а также детям до 14 лет нужен загранпаспорт.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Подходит ли экскурсионный тур детям?', 'zhiraf-ufa' ),
					'a' => __( 'Для первой семейной поездки лучше Ереван, Гарни и Севан без дальних переездов и долгих винных дегустаций.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'За что придётся доплачивать на месте?', 'zhiraf-ufa' ),
					'a' => __( 'Обеды/ужины, канатная дорога «Крылья Татева», доп. дегустации — фиксируем закрытый список до бронирования.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Какое питание обычно включено?', 'zhiraf-ufa' ),
					'a' => __( 'Базово — завтраки в отеле. Мастер-класс по лавашу и дегустации не заменяют полноценный обед.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Безопасно ли ехать и можно ли менять маршрут?', 'zhiraf-ufa' ),
					'a' => __( 'Туристические маршруты безопасны, но самостоятельные отклонения к приграничным районам с Азербайджаном не рекомендуются — все дальние выезды согласуем с принимающей стороной.', 'zhiraf-ufa' ),
				),
			),
			'seo_details'          => array(
				array(
					'title' => __( 'Куда сходить вечером в Ереване (Каскад, Опера, Площадь Республики)', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Вечером удобны Каскад с видом на Арарат, оперный театр и Площадь Республики. Закладываем время на кафе и прогулку без жары днём.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Гастрономия: хоровац, лаваш из тонира, вина Арени', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Хоровац, лаваш из тонира и вина Арени/Воскеваза — в гастро-маршрутах. Дегустации и меню фиксируем до оплаты.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Правила въезда без загранпаспорта и рейсовый автобус из Уфы', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'При прямом авиарейсе взрослые въезжают по внутреннему паспорту РФ. Автобусные схемы и транзит — только с загранпаспортом; рейсовый автобус до Еревана считаем под запрос.', 'zhiraf-ufa' ) . '</p>',
				),
			),
			'excursion_chips'      => array(
				array( 'label' => __( 'Грузия', 'zhiraf-ufa' ), 'url' => '/tour/gruziya/' ),
				array( 'label' => __( 'Узбекистан', 'zhiraf-ufa' ), 'url' => '/tour/uzbekistan/' ),
				array( 'label' => __( 'Санкт-Петербург', 'zhiraf-ufa' ), 'url' => '/tour/sankt-peterburg/' ),
				array( 'label' => __( 'Калининград', 'zhiraf-ufa' ), 'url' => '/tour/kaliningrad/' ),
			),
			'final_cta_title'      => __( 'Подберём маршрут по Армении под ваши даты и темп за 15 минут', 'zhiraf-ufa' ),
			'quiz_prefill'         => __( 'Армения, экскурсионный тур из Уфы', 'zhiraf-ufa' ),
			'modal_default_title'  => __( 'Программа по дням в мессенджер', 'zhiraf-ufa' ),
			'modal_default_lead'   => __( 'Укажите телефон и выберите MAX, WhatsApp или Telegram — пришлём PDF-программу с днями и ориентиром по цене.', 'zhiraf-ufa' ),
			'image'                => 'alt-armeniya-yerevan.jpg',
			'image_alt'            => __( 'Армения — Каскад в Ереване', 'zhiraf-ufa' ),
			'name'                 => __( 'Армения', 'zhiraf-ufa' ),
		),
	);

	return isset( $packs[ $slug ] ) ? $packs[ $slug ] : null;
}

/**
 * Merge excursion SEO + disable hotel curated flags for Uzbekistan.
 *
 * @param array<string,mixed> $dest Landing row.
 * @param string              $slug Slug.
 * @return array<string,mixed>
 */
function zhiraf_ufa_excursion_destination_landing_filter( $dest, $slug ) {
	$slug = sanitize_key( (string) $slug );
	if ( ! in_array( $slug, zhiraf_ufa_excursion_landing_slugs(), true ) ) {
		return $dest;
	}
	$pack = zhiraf_ufa_excursion_landing_pack( $slug );
	if ( ! $pack || empty( zhiraf_ufa_excursion_programs( $slug ) ) ) {
		return $dest;
	}
	if ( ! is_array( $dest ) ) {
		$dest = array( 'slug' => $slug );
	}
	foreach ( array( 'h1', 'title', 'description', 'hero_lead', 'hero_badges', 'image', 'image_alt', 'name', 'quiz_prefill' ) as $key ) {
		if ( isset( $pack[ $key ] ) ) {
			$dest[ $key ] = $pack[ $key ];
		}
	}
	$dest['curated_landing_v1']     = false;
	$dest['curated_resort_chips']   = false;
	$dest['curated_dest_lead_form'] = false;
	$dest['excursion_landing_v1']   = true;
	$dest['departure']              = __( 'из Уфы', 'zhiraf-ufa' );
	$dest['hide_landing_prose']     = true;
	$dest['faq']                    = $pack['faq'];
	return $dest;
}
add_filter( 'zhiraf_ufa_destination_landing', 'zhiraf_ufa_excursion_destination_landing_filter', 30, 2 );

/**
 * Render excursion curated landing.
 *
 * @param string $slug Destination slug.
 */
function zhiraf_ufa_render_excursion_curated_landing( $slug ) {
	$slug     = sanitize_key( (string) $slug );
	$pack     = zhiraf_ufa_excursion_landing_pack( $slug );
	$programs = zhiraf_ufa_excursion_programs( $slug );
	$dest     = zhiraf_ufa_get_destination_landing( $slug );
	if ( ! $pack || ! $programs ) {
		if ( function_exists( 'zhiraf_ufa_render_destination_landing' ) ) {
			zhiraf_ufa_render_destination_landing( $slug );
		}
		return;
	}
	if ( ! is_array( $dest ) ) {
		$dest = zhiraf_ufa_excursion_destination_landing_filter( array( 'slug' => $slug ), $slug );
	}
	$excursion_pack     = $pack;
	$excursion_programs = $programs;
	$template           = ZHIRAF_UFA_DIR . '/templates/excursion-curated-landing.php';
	if ( is_readable( $template ) ) {
		include $template;
	}
}