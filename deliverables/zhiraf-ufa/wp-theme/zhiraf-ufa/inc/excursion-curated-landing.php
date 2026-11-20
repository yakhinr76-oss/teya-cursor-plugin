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
	$candidates = array( 'uzbekistan', 'gruziya', 'armeniya', 'moskva', 'sankt-peterburg', 'kaliningrad' );
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
 * Excursion landing slug for current request (rewrite or WP page).
 *
 * @return string
 */
function zhiraf_ufa_active_excursion_landing_slug() {
	$slug = zhiraf_ufa_current_excursion_country_slug();
	if ( '' !== $slug ) {
		return $slug;
	}
	if ( is_page() && function_exists( 'zhiraf_ufa_excursion_landing_slugs' ) ) {
		$name = get_post_field( 'post_name', get_queried_object_id() );
		$name = sanitize_key( (string) $name );
		if ( in_array( $name, zhiraf_ufa_excursion_landing_slugs(), true ) ) {
			return $name;
		}
	}
	return '';
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
 * Echo img for theme-relative excursion asset (hero / program card).
 *
 * @param string $photo    Path relative to theme root or assets/img/excursions/.
 * @param string $alt      Alt text.
 * @param string $class    CSS class.
 * @param bool   $lazy     Lazy load.
 * @param bool   $priority LCP candidate.
 */
function zhiraf_ufa_excursion_photo_img_echo( $photo, $alt, $class = '', $lazy = true, $priority = false ) {
	$resolved = zhiraf_ufa_excursion_program_photo( $photo );
	if ( ! $resolved ) {
		return;
	}
	$attrs = array(
		'src'    => $resolved['url'],
		'alt'    => $alt,
		'width'  => (string) $resolved['width'],
		'height' => (string) $resolved['height'],
		'class'  => $class,
	);
	if ( $lazy && ! $priority ) {
		$attrs['loading'] = 'lazy';
	}
	if ( $priority ) {
		$attrs['loading']       = 'eager';
		$attrs['fetchpriority'] = 'high';
	}
	$out = '<img';
	foreach ( $attrs as $k => $v ) {
		if ( '' === $v && 'class' === $k ) {
			continue;
		}
		$out .= sprintf( ' %s="%s"', esc_attr( $k ), esc_attr( $v ) );
	}
	$out .= ' />';
	echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
			'hero_photo'           => 'assets/img/excursions/uzbekistan/hero.jpg',
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
			'hero_photo'           => 'assets/img/excursions/gruziya/hero.jpg',
			'image_alt'            => __( 'Грузия — закат над Старым Тбилиси', 'zhiraf-ufa' ),
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
			'hero_photo'           => 'assets/img/excursions/armeniya/hero.jpg',
			'image_alt'            => __( 'Армения — Ереван и Арарат', 'zhiraf-ufa' ),
			'name'                 => __( 'Армения', 'zhiraf-ufa' ),
		),
		'moskva'     => array(
			'slug'                 => 'moskva',
			'h1'                   => __( 'Экскурсионные туры в Москву из Уфы', 'zhiraf-ufa' ),
			'title'                => __( 'Москву из Уфы — подбор тура «Жираф»', 'zhiraf-ufa' ),
			'description'          => __( 'Экскурсионные туры в Москву с вылетом из Уфы. Подбор без давления, актуальные цены Wordstat 2026-11-19.', 'zhiraf-ufa' ),
			'hero_lead'            => __( 'Прямой перелёт за 2 часа 15 минут и отели в 5–10 минутах от метро. Кремль, ВДНХ, семейные маршруты и гастрономическая столица с подтверждёнными билетами', 'zhiraf-ufa' ),
			'hero_badges'          => array(
				array( 'text' => __( 'Прямой рейс из Уфы • ~2 ч 20 мин', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'По внутреннему паспорту РФ', 'zhiraf-ufa' ) ),
			),
			'hero_trust'           => array(
				array(
					'icon' => 'plane',
					'text' => __( 'Прямые рейсы в Шереметьево, Внуково и Домодедово («Аэрофлот», S7, Utair, «Победа»)', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'shield-check',
					'text' => __( 'Подбираем отели у метро и рейсы под время старта экскурсий', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'map',
					'text' => __( 'Возможен расчёт поездки на поезде (~23–31 ч) или автобусе под запрос', 'zhiraf-ufa' ),
				),
			),
			'catalog_filter_mode'  => 'days',
			'catalog_day_filters'  => array(
				array(
					'key'   => '3-4',
					'label' => __( '3–4 дня', 'zhiraf-ufa' ),
					'min'   => 3,
					'max'   => 4,
				),
				array(
					'key'   => '5',
					'label' => __( '5 дней', 'zhiraf-ufa' ),
					'min'   => 5,
					'max'   => 5,
				),
			),
			'convert_card'         => array(
				'title' => __( 'Нужен тур на поезде или индивидуальный маршрут?', 'zhiraf-ufa' ),
				'text'  => __( 'Соберём программу под ваши даты, добавим театр, Оружейную палату или проезд поездом из Уфы', 'zhiraf-ufa' ),
				'cta'   => __( 'Подобрать маршрут', 'zhiraf-ufa' ),
			),
			'format_cards'         => array(
				array(
					'key'   => 'classic',
					'title' => __( 'Москва впервые', 'zhiraf-ufa' ),
					'desc'  => __( 'Кремль, Зарядье, ВДНХ, Сергиев Посад', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'key'   => 'weekend',
					'title' => __( 'Уикенд 3–4 дня', 'zhiraf-ufa' ),
					'desc'  => __( 'центр, Арбат, Москва-Сити', 'zhiraf-ufa' ),
					'icon'  => 'plane',
				),
				array(
					'key'   => 'family',
					'title' => __( 'С детьми', 'zhiraf-ufa' ),
					'desc'  => __( 'Москвариум, Музей космонавтики, ВДНХ', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
				array(
					'key'   => 'gastro',
					'title' => __( 'Гастро и переулки', 'zhiraf-ufa' ),
					'desc'  => __( 'Булгаков, Замоскворечье, «Депо»', 'zhiraf-ufa' ),
					'icon'  => 'tag',
				),
			),
			'price_footnote'       => __( 'ориентир за 1 чел. при 2-местном размещении (авиа из Уфы + отель 3–4★ у метро + завтраки)', 'zhiraf-ufa' ),
			'included'             => array(
				'title'    => __( 'Что входит в программу и что оплачивается отдельно', 'zhiraf-ufa' ),
				'in_price' => array(
					__( 'Прямой перелёт из Уфы', 'zhiraf-ufa' ),
					__( 'Отель 3–4★ с завтраками', 'zhiraf-ufa' ),
					__( 'Групповые экскурсии по маршруту', 'zhiraf-ufa' ),
					__( 'Базовые входные билеты (уточняем заранее)', 'zhiraf-ufa' ),
				),
				'extra'    => array(
					__( 'Багаж сверх нормы', 'zhiraf-ufa' ),
					__( 'Трансфер из аэропорта', 'zhiraf-ufa' ),
					__( 'Оружейная палата', 'zhiraf-ufa' ),
					__( 'Шоу в Москвариуме', 'zhiraf-ufa' ),
					__( 'Смотровая Москва-Сити', 'zhiraf-ufa' ),
					__( 'Обеды и ужины', 'zhiraf-ufa' ),
				),
			),
			'know_title'           => __( 'Что важно знать о поездке в Москву', 'zhiraf-ufa' ),
			'know_cards'           => array(
				array(
					'title' => __( 'Сезон и цены', 'zhiraf-ufa' ),
					'text'  => __( 'Для прогулок комфортнее май–июнь и сентябрь; для спокойного бюджета — непраздничные даты февраля–марта и ноября. В даты выставок отели дорожают даже в будни', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'title' => __( 'Билеты в музеи', 'zhiraf-ufa' ),
					'text'  => __( 'Онлайн-продажа в Кремль открывается за 17 дней, четверг — выходной. Пакет гарантирует подтверждённый сеанс, но контроль на входе проходят все группы', 'zhiraf-ufa' ),
					'icon'  => 'shield-check',
				),
				array(
					'title' => __( 'Отель и транспорт', 'zhiraf-ufa' ),
					'text'  => __( 'В сборных турах туристы часто добираются до места встречи с гидом на метро, поэтому мы подбираем отели в 5–10 минутах пешком от станции', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
			),
			'faq'                  => array(
				array(
					'q' => __( 'Нужен ли загранпаспорт для поездки в Москву?', 'zhiraf-ufa' ),
					'a' => __( 'Нет, это внутренний рейс — достаточно паспорта РФ, для детей проверяем документы по правилам авиакомпании', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Можно ли ехать с ребёнком и будет ли дешевле?', 'zhiraf-ufa' ),
					'a' => __( 'Да, лучше формат с 1 крупным объектом в день; детскую стоимость считаем точно по возрасту, перелёту и билетам', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Что обычно не входит в стоимость?', 'zhiraf-ufa' ),
					'a' => __( 'Обеды/ужины, трансферы из аэропорта, необязательные музеи и вечерние шоу — фиксируем список до оплаты', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Что будет с программой, если пойдёт дождь или снег?', 'zhiraf-ufa' ),
					'a' => __( 'Прогулки не отменяются — берите удобную обувь и зонт; замену на музей делаем при наличии билетов', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Тур гарантирует Кремль и вход без очереди?', 'zhiraf-ufa' ),
					'a' => __( 'Только если посещение Кремля или Оружейной палаты прямо указано в программе; досмотр на входе обязателен для всех', 'zhiraf-ufa' ),
				),
			),
			'seo_details'          => array(
				array(
					'title' => __( 'Куда сходить вечером в Москве (Зарядье, Арбат, «Депо»)', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Вечером удобны парк «Зарядье», Старый Арбат и фудмолл «Депо» на Лесной — закладываем время после дневных экскурсий.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Гастрономия столицы: исторические рестораны и фудмоллы', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'В гастро-маршрутах — «Кафе Пушкинъ», Даниловский рынок и фуд-корты у метро; бронь и меню согласуем до оплаты.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Поездом или автобусом из Уфы в Москву: что учесть', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Поезд Уфа — Москва ~23–31 час в пути; автобусный вариант считаем под запрос. Стыкуем прибытие с заселением и стартом экскурсий.', 'zhiraf-ufa' ) . '</p>',
				),
			),
			'excursion_chips'      => array(
				array( 'label' => __( 'Санкт-Петербург', 'zhiraf-ufa' ), 'url' => '/tour/sankt-peterburg/' ),
				array( 'label' => __( 'Калининград', 'zhiraf-ufa' ), 'url' => '/tour/kaliningrad/' ),
				array( 'label' => __( 'Грузия', 'zhiraf-ufa' ), 'url' => '/tour/gruziya/' ),
				array( 'label' => __( 'Узбекистан', 'zhiraf-ufa' ), 'url' => '/tour/uzbekistan/' ),
				array( 'label' => __( 'Армения', 'zhiraf-ufa' ), 'url' => '/tour/armeniya/' ),
			),
			'final_cta_title'      => __( 'Подберём маршрут по Москве под ваши даты и темп за 15 минут', 'zhiraf-ufa' ),
			'quiz_prefill'         => __( 'Москва, экскурсионный тур из Уфы', 'zhiraf-ufa' ),
			'modal_default_title'  => __( 'Программа по дням в мессенджер', 'zhiraf-ufa' ),
			'modal_default_lead'   => __( 'Укажите телефон и выберите MAX, WhatsApp или Telegram — пришлём PDF-программу с днями и ориентиром по цене.', 'zhiraf-ufa' ),
			'hero_photo'           => 'assets/img/excursions/moskva/hero.jpg',
			'image_alt'            => __( 'Москва — Красная площадь и Кремль', 'zhiraf-ufa' ),
			'name'                 => __( 'Москва', 'zhiraf-ufa' ),
		),
		'sankt-peterburg' => array(
			'slug'                 => 'sankt-peterburg',
			'h1'                   => __( 'Экскурсионные туры в Санкт-Петербург из Уфы', 'zhiraf-ufa' ),
			'title'                => __( 'Санкт-Петербург из Уфы — подбор тура «Жираф»', 'zhiraf-ufa' ),
			'description'          => __( 'Экскурсионные туры в Санкт-Петербург с вылетом из Уфы. Подбор без давления, актуальные цены Wordstat 2026-11-19.', 'zhiraf-ufa' ),
			'hero_lead'            => __( 'Прямой перелёт в Пулково за 3 часа. Эрмитаж, Петергоф, Царское Село и современные пространства с заранее подтверждёнными сеансами в музеи', 'zhiraf-ufa' ),
			'hero_badges'          => array(
				array( 'text' => __( 'Прямой рейс из Уфы • ~3 часа', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'По внутреннему паспорту РФ', 'zhiraf-ufa' ) ),
			),
			'hero_trust'           => array(
				array(
					'icon' => 'plane',
					'text' => __( 'Прямые рейсы Уфа — Пулково («Россия»/«Аэрофлот», «Победа», Smartavia, Nordwind)', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'shield-check',
					'text' => __( 'Заранее связываем авиарейс, заселение, пригородные выезды и сеансы в музеи', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'map',
					'text' => __( 'Возможен расчёт поездки на прямом поезде № 347Й (~2 суток в пути) под запрос', 'zhiraf-ufa' ),
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
					'key'   => '6',
					'label' => __( '6 дней', 'zhiraf-ufa' ),
					'min'   => 6,
					'max'   => 6,
				),
			),
			'convert_card'         => array(
				'title' => __( 'Нужен тур на белые ночи или на поезде?', 'zhiraf-ufa' ),
				'text'  => __( 'Рассчитаем программу на пиковые даты июня, добавим театры, водные прогулки или проезд прямым поездом из Уфы', 'zhiraf-ufa' ),
				'cta'   => __( 'Подобрать маршрут', 'zhiraf-ufa' ),
			),
			'format_cards'         => array(
				array(
					'key'   => 'classic',
					'title' => __( 'Классика и пригороды', 'zhiraf-ufa' ),
					'desc'  => __( 'Эрмитаж, Петергоф, Царское Село, Павловск', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'key'   => 'weekend',
					'title' => __( 'Уикенд 4 дня', 'zhiraf-ufa' ),
					'desc'  => __( 'центр, Эрмитаж, Новая Голландия', 'zhiraf-ufa' ),
					'icon'  => 'plane',
				),
				array(
					'key'   => 'family',
					'title' => __( 'С детьми', 'zhiraf-ufa' ),
					'desc'  => __( '«Гранд Макет», «Планетарий 1», прогулки', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
				array(
					'key'   => 'gastro',
					'title' => __( 'Другой Петербург', 'zhiraf-ufa' ),
					'desc'  => __( 'Эрарта, Севкабель, «Вокзал 1853»', 'zhiraf-ufa' ),
					'icon'  => 'tag',
				),
			),
			'price_footnote'       => __( 'ориентир за 1 чел. при 2-местном размещении (авиа из Уфы + отель 3–4★ + завтраки; на белые ночи отдельный расчёт)', 'zhiraf-ufa' ),
			'included'             => array(
				'title'    => __( 'Что входит в программу и что оплачивается отдельно', 'zhiraf-ufa' ),
				'in_price' => array(
					__( 'Прямой перелёт из Уфы', 'zhiraf-ufa' ),
					__( 'Отель 3–4★ с завтраками', 'zhiraf-ufa' ),
					__( 'Групповые экскурсии по маршруту', 'zhiraf-ufa' ),
					__( 'Перечисленные в программе музеи (уточняем заранее)', 'zhiraf-ufa' ),
				),
				'extra'    => array(
					__( 'Багаж сверх нормы', 'zhiraf-ufa' ),
					__( 'Трансфер из аэропорта', 'zhiraf-ufa' ),
					__( 'Большой дворец Петергофа', 'zhiraf-ufa' ),
					__( 'Обеды и ужины', 'zhiraf-ufa' ),
					__( 'Водные прогулки', 'zhiraf-ufa' ),
					__( 'Раннее заселение', 'zhiraf-ufa' ),
				),
			),
			'know_title'           => __( 'Что важно знать о Санкт-Петербурге', 'zhiraf-ufa' ),
			'know_cards'           => array(
				array(
					'title' => __( 'Сезон и погода', 'zhiraf-ufa' ),
					'text'  => __( 'Белые ночи (конец мая — середина июля) — пик цен и толп, бронируем заранее. Для комфортных прогулок лучше конец апреля–май и сентябрь; ветровка и закрытая обувь нужны даже летом', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'title' => __( 'Билеты и сеансы', 'zhiraf-ufa' ),
					'text'  => __( 'В Екатерининский дворец (Янтарная комната) онлайн-билеты открываются за 7 дней и быстро заканчиваются. Пакет фиксирует нужный сеанс и экономит время в коротком туре', 'zhiraf-ufa' ),
					'icon'  => 'shield-check',
				),
				array(
					'title' => __( 'Темп и метро', 'zhiraf-ufa' ),
					'text'  => __( 'Планируем не более 1 крупного музея или 1 пригорода в день. Метро работает примерно с 05:30 до полуночи — после ночных прогулок и развода мостов закладывайте такси', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
			),
			'faq'                  => array(
				array(
					'q' => __( 'Нужен ли загранпаспорт?', 'zhiraf-ufa' ),
					'a' => __( 'Нет, это внутренний рейс в Пулково — достаточно действующего паспорта РФ', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Какой тур выбрать для поездки с детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Оптимально 5 дней / 4 ночи: «Гранд Макет Россия», «Планетарий 1» и короткие прогулки без утомительных переездов', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Что обычно не входит в цену?', 'zhiraf-ufa' ),
					'a' => __( 'Обеды/ужины, багаж, аэропортовые трансферы и дополнительные музеи — фиксируем точный список до оплаты', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Что будет, если пойдёт дождь?', 'zhiraf-ufa' ),
					'a' => __( 'Экскурсии не отменяются, чередуем улицу и музеи; берите непромокаемую обувь и ветровку даже в июле', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Можно ли купить Эрмитаж и Янтарную комнату на месте?', 'zhiraf-ufa' ),
					'a' => __( 'Иногда да, но в коротком туре велик риск не попасть на удобный сеанс — надёжнее фиксировать их в пакете заранее', 'zhiraf-ufa' ),
				),
			),
			'seo_details'          => array(
				array(
					'title' => __( 'Куда сходить вечером (Новая Голландия, Севкабель Порт, «Вокзал 1853»)', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Вечером удобны Новая Голландия, Севкабель Порт и фудмолл «Вокзал 1853» — закладываем время после дневных экскурсий.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Гастрономия: пышки на Конюшенной, корюшка и ленинградский рассольник', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Пышечная на Большой Конюшенной, сезонная корюшка и ленинградский рассольник — в гастро-маршрутах. Меню и бронь согласуем до оплаты.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Поездом № 347Й из Уфы в Санкт-Петербург: кому подходит', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Прямой поезд № 347Й — около двух суток в пути; подходит тем, кто хочет сэкономить на перелёте и готов к ночному в пути. Стыкуем прибытие с заселением.', 'zhiraf-ufa' ) . '</p>',
				),
			),
			'excursion_chips'      => array(
				array( 'label' => __( 'Москва', 'zhiraf-ufa' ), 'url' => '/tour/moskva/' ),
				array( 'label' => __( 'Калининград', 'zhiraf-ufa' ), 'url' => '/tour/kaliningrad/' ),
				array( 'label' => __( 'Грузия', 'zhiraf-ufa' ), 'url' => '/tour/gruziya/' ),
				array( 'label' => __( 'Узбекистан', 'zhiraf-ufa' ), 'url' => '/tour/uzbekistan/' ),
				array( 'label' => __( 'Армения', 'zhiraf-ufa' ), 'url' => '/tour/armeniya/' ),
			),
			'final_cta_title'      => __( 'Подберём маршрут по Санкт-Петербургу под ваши даты и темп за 15 минут', 'zhiraf-ufa' ),
			'quiz_prefill'         => __( 'Санкт-Петербург, экскурсионный тур из Уфы', 'zhiraf-ufa' ),
			'modal_default_title'  => __( 'Программа по дням в мессенджер', 'zhiraf-ufa' ),
			'modal_default_lead'   => __( 'Укажите телефон и выберите MAX, WhatsApp или Telegram — пришлём PDF-программу с днями и ориентиром по цене.', 'zhiraf-ufa' ),
			'hero_photo'           => 'assets/img/excursions/sankt-peterburg/hero.jpg',
			'image_alt'            => __( 'Санкт-Петербург — Дворцовая набережная и Эрмитаж', 'zhiraf-ufa' ),
			'name'                 => __( 'Санкт-Петербург', 'zhiraf-ufa' ),
		),
		'kaliningrad'  => array(
			'slug'                 => 'kaliningrad',
			'h1'                   => __( 'Экскурсионные туры в Калининград из Уфы', 'zhiraf-ufa' ),
			'title'                => __( 'Калининград из Уфы — подбор тура «Жираф»', 'zhiraf-ufa' ),
			'description'          => __( 'Экскурсионные туры в Калининград с вылетом из Уфы. Подбор без давления, актуальные цены Wordstat 2026-11-19.', 'zhiraf-ufa' ),
			'hero_lead'            => __( 'Прямой перелёт в Храброво за 4 ч 20 мин по внутреннему паспорту РФ. Остров Канта, дюны Куршской косы, Светлогорск, Зеленоградск и честная смета без скрытых доплат', 'zhiraf-ufa' ),
			'hero_badges'          => array(
				array( 'text' => __( 'Прямой рейс из Уфы • ~4 ч 20 мин', 'zhiraf-ufa' ) ),
				array( 'text' => __( 'По внутреннему паспорту РФ', 'zhiraf-ufa' ) ),
			),
			'hero_trust'           => array(
				array(
					'icon' => 'plane',
					'text' => __( 'Прямые рейсы Уфа — Храброво (Nordwind Airlines) — стыкуем программу под дни вылетов', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'shield-check',
					'text' => __( 'При перелёте загранпаспорт не нужен (он требуется только для поезда через Литву)', 'zhiraf-ufa' ),
				),
				array(
					'icon' => 'tag',
					'text' => __( 'Заранее фиксируем билеты на органный концерт, «Планету Океан» и дегустации', 'zhiraf-ufa' ),
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
				'title' => __( 'Нужен летний расчёт или индивидуальный маршрут?', 'zhiraf-ufa' ),
				'text'  => __( 'Подберём даты под прямые рейсы из Уфы, добавим органный концерт, отдых на побережье или рассчитаем поезд под запрос', 'zhiraf-ufa' ),
				'cta'   => __( 'Подобрать маршрут', 'zhiraf-ufa' ),
			),
			'format_cards'         => array(
				array(
					'key'   => 'classic',
					'title' => __( 'Город и коса', 'zhiraf-ufa' ),
					'desc'  => __( 'Калининград, Куршская коса, Светлогорск, Янтарный', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'key'   => 'weekend',
					'title' => __( 'Уикенд 4 дня', 'zhiraf-ufa' ),
					'desc'  => __( 'Калининград + Зеленоградск', 'zhiraf-ufa' ),
					'icon'  => 'plane',
				),
				array(
					'key'   => 'family',
					'title' => __( 'С детьми', 'zhiraf-ufa' ),
					'desc'  => __( 'Музей Мирового океана, город и море', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
				array(
					'key'   => 'gastro',
					'title' => __( 'Вкус Балтики', 'zhiraf-ufa' ),
					'desc'  => __( 'замок Шаакен, сыроварня, строганина', 'zhiraf-ufa' ),
					'icon'  => 'tag',
				),
			),
			'price_footnote'       => __( 'ориентир за 1 чел. при 2-местном размещении (авиа из Уфы + отель 3–4★ + завтраки; лето и праздники — отдельный расчёт)', 'zhiraf-ufa' ),
			'included'             => array(
				'title'    => __( 'Что входит в программу и что оплачивается отдельно', 'zhiraf-ufa' ),
				'in_price' => array(
					__( 'Прямой перелёт из Уфы', 'zhiraf-ufa' ),
					__( 'Отель 3–4★ с завтраками', 'zhiraf-ufa' ),
					__( 'Трансфер аэропорт — отель', 'zhiraf-ufa' ),
					__( 'Базовые экскурсии по маршруту (уточняем заранее)', 'zhiraf-ufa' ),
				),
				'extra'    => array(
					__( 'Багаж сверх нормы', 'zhiraf-ufa' ),
					__( 'Обеды и ужины', 'zhiraf-ufa' ),
					__( 'Органный концерт', 'zhiraf-ufa' ),
					__( 'Отдельные экспозиции Музея Мирового океана', 'zhiraf-ufa' ),
					__( 'Дегустации', 'zhiraf-ufa' ),
				),
			),
			'know_title'           => __( 'Что важно знать о Калининграде', 'zhiraf-ufa' ),
			'know_cards'           => array(
				array(
					'title' => __( 'Сезон и погода', 'zhiraf-ufa' ),
					'text'  => __( 'Июль–август — пик цен и пляжного спроса, но купание не гарантировано. Для экскурсий идеален сентябрь; ветровка и дождевик нужны даже летом (ночью на косе +12…+15 °C)', 'zhiraf-ufa' ),
					'icon'  => 'compass',
				),
				array(
					'title' => __( 'Билеты и сеансы', 'zhiraf-ufa' ),
					'text'  => __( 'Заранее бронируем органный концерт в Кафедральном соборе, конкретные экспозиции («Планета Океан») и дегустацию на сыроварне — чтобы не терять время на месте', 'zhiraf-ufa' ),
					'icon'  => 'shield-check',
				),
				array(
					'title' => __( 'Время и транспорт', 'zhiraf-ufa' ),
					'text'  => __( 'Калининград на 3 часа позади Уфы (время всех встреч — местное). В Зеленоградск и Светлогорск в свободный день легко съездить на электричке с Северного или Южного вокзала', 'zhiraf-ufa' ),
					'icon'  => 'map',
				),
			),
			'faq'                  => array(
				array(
					'q' => __( 'Нужен ли загранпаспорт?', 'zhiraf-ufa' ),
					'a' => __( 'Для прямого перелёта из Уфы — нет, достаточно паспорта РФ; для поезда через Литву нужен загранпаспорт и транзитный УПД-ЖД', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Можно ли ехать с детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Да, оптимально 5–6 дней с чередованием Музея Мирового океана, города и побережья, без привязки к купанию', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Что обычно не входит в цену?', 'zhiraf-ufa' ),
					'a' => __( 'Багаж, обеды/ужины, органный концерт, дополнительные экскурсии и дегустации — фиксируем список до оплаты', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Дожди испортят экскурсионный тур?', 'zhiraf-ufa' ),
					'a' => __( 'Нет: в городе выручают музеи и органный концерт, а для Куршской косы берите дождевик и ветровку', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Можно ли съездить на 3–4 дня прямым рейсом из Уфы?', 'zhiraf-ufa' ),
					'a' => __( 'Да, если совпадут дни прямых рейсов Nordwind; за это время спокойно успеете Калининград + 1 выезд на побережье или косу', 'zhiraf-ufa' ),
				),
			),
			'seo_details'          => array(
				array(
					'title' => __( 'Куда сходить вечером (Остров Канта, Рыбная деревня, органный концерт, Светлогорск)', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Вечером — Остров Канта, Рыбная деревня, органный концерт в соборе или прогулка в Светлогорске; время в программе закладываем заранее.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Гастрономия: строганина из пеламиды, кёнигсбергские клопсы, марципан', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Строганина из пеламиды, кёнигсбергские клопсы и марципан — в гастро-маршрутах. Ужины и дегустации фиксируем до оплаты.', 'zhiraf-ufa' ) . '</p>',
				),
				array(
					'title' => __( 'Поездом из Уфы в Калининград через Литву: правила УПД-ЖД', 'zhiraf-ufa' ),
					'html'  => '<p>' . esc_html__( 'Поезд через Литву требует загранпаспорт и транзитный УПД-ЖД; на перелёте из Уфы достаточно паспорта РФ. Стыковки считаем под запрос.', 'zhiraf-ufa' ) . '</p>',
				),
			),
			'excursion_chips'      => array(
				array( 'label' => __( 'Москва', 'zhiraf-ufa' ), 'url' => '/tour/moskva/' ),
				array( 'label' => __( 'Санкт-Петербург', 'zhiraf-ufa' ), 'url' => '/tour/sankt-peterburg/' ),
				array( 'label' => __( 'Грузия', 'zhiraf-ufa' ), 'url' => '/tour/gruziya/' ),
				array( 'label' => __( 'Узбекистан', 'zhiraf-ufa' ), 'url' => '/tour/uzbekistan/' ),
				array( 'label' => __( 'Армения', 'zhiraf-ufa' ), 'url' => '/tour/armeniya/' ),
			),
			'final_cta_title'      => __( 'Подберём маршрут по Калининграду под ваши даты и темп за 15 минут', 'zhiraf-ufa' ),
			'quiz_prefill'         => __( 'Калининград, экскурсионный тур из Уфы', 'zhiraf-ufa' ),
			'modal_default_title'  => __( 'Программа по дням в мессенджер', 'zhiraf-ufa' ),
			'modal_default_lead'   => __( 'Укажите телефон и выберите MAX, WhatsApp или Telegram — пришлём PDF-программу с днями и ориентиром по цене.', 'zhiraf-ufa' ),
			'hero_photo'           => 'assets/img/excursions/kaliningrad/hero.jpg',
			'image_alt'            => __( 'Калининград — остров Канта и Кафедральный собор', 'zhiraf-ufa' ),
			'name'                 => __( 'Калининград', 'zhiraf-ufa' ),
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
	foreach ( array( 'h1', 'title', 'description', 'hero_lead', 'hero_badges', 'hero_photo', 'image', 'image_alt', 'name', 'quiz_prefill' ) as $key ) {
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