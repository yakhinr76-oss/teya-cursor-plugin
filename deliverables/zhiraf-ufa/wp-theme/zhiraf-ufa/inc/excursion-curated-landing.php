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
		$row['days'] = zhiraf_ufa_excursion_program_days( isset( $row['duration'] ) ? (string) $row['duration'] : '' );
		$out[]       = $row;
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
	if ( 'uzbekistan' !== sanitize_key( (string) $slug ) ) {
		return $dest;
	}
	$pack = zhiraf_ufa_excursion_landing_pack( 'uzbekistan' );
	if ( ! $pack || empty( zhiraf_ufa_excursion_programs( 'uzbekistan' ) ) ) {
		return $dest;
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
	if ( ! $pack || ! $programs || ! $dest ) {
		if ( function_exists( 'zhiraf_ufa_render_destination_landing' ) ) {
			zhiraf_ufa_render_destination_landing( $slug );
		}
		return;
	}
	$excursion_pack     = $pack;
	$excursion_programs = $programs;
	$template           = ZHIRAF_UFA_DIR . '/templates/excursion-curated-landing.php';
	if ( is_readable( $template ) ) {
		include $template;
	}
}