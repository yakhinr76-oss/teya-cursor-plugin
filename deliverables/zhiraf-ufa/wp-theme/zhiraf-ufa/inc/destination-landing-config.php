<?php
/**
 * Destination landing presentation: hero standards, central module switcher, admin override.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Central interactive module types (one per landing page).
 *
 * @return array<string,string> slug => label
 */
function zhiraf_ufa_destination_module_types() {
	return array(
		'battle' => __( 'Батл курортов (VS)', 'zhiraf-ufa' ),
		'vibe'   => __( 'Выбор по вайбу', 'zhiraf-ufa' ),
		'season' => __( 'Сезонность и климат', 'zhiraf-ufa' ),
	);
}

/**
 * Default module per destination slug.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_destination_default_module_map() {
	return array(
		'turciya'    => 'battle',
		'oae'        => 'battle',
		'egipet'     => 'battle',
		'tailand'    => 'battle',
		'vietnam'    => 'battle',
		'kitaj'      => 'battle',
		'indiya'     => 'season',
		'shri-lanka' => 'battle',
		'indoneziya' => 'battle',
		'maldivy'    => 'battle',
		'sejshely'   => 'battle',
		'uzbekistan' => 'vibe',
		'armeniya'   => 'vibe',
		'rossiya'    => 'battle',
		'abkhaziya'  => 'battle',
	);
}

/**
 * Hero image + alt standards (resort emotion, not landmarks/construction).
 *
 * @return array<string,array{image:string,alt:string}>
 */
function zhiraf_ufa_destination_hero_image_standards() {
	return array(
		'turciya'    => array(
			'image' => 'resort-turciya-kemer-2.jpg',
			'alt'   => __( 'Турция — Средиземное море, курорт Кемер', 'zhiraf-ufa' ),
		),
		'egipet'     => array(
			'image' => 'hero-egypt.jpg',
			'alt'   => __( 'Египет — Красное море и пляжный отель', 'zhiraf-ufa' ),
		),
		'tailand'    => array(
			'image' => 'country-tailand.jpg',
			'alt'   => __( 'Таиланд — лазурное море и пляж', 'zhiraf-ufa' ),
		),
		'vietnam'    => array(
			'image' => 'resort-vietnam-phu-quoc-2.jpg',
			'alt'   => __( 'Вьетнам — пляж и море Фукуок', 'zhiraf-ufa' ),
		),
		'kitaj'      => array(
			'image' => 'resort-sanya-2.jpg',
			'alt'   => __( 'Китай — курорт Санья, Хайнань', 'zhiraf-ufa' ),
		),
		'indiya'     => array(
			'image' => 'resort-severnyj-goa.jpg',
			'alt'   => __( 'Индия — пляж Северного Гоа', 'zhiraf-ufa' ),
		),
		'indoneziya' => array(
			'image' => 'country-indoneziya.jpg',
			'alt'   => __( 'Бали — храм Улун Дану Братан на озере', 'zhiraf-ufa' ),
		),
		'rossiya'    => array(
			'image' => 'hero-collage-1.jpg',
			'alt'   => __( 'Сочи — Имеретинская набережная и море', 'zhiraf-ufa' ),
		),
		'abkhaziya'  => array(
			'image' => 'alt-armeniya-2.jpg',
			'alt'   => __( 'Абхазия — сосны и бирюзовое море у Пицунды', 'zhiraf-ufa' ),
		),
		'maldivy'    => array(
			'image' => 'resort-maldivy-atoll-1.jpg',
			'alt'   => __( 'Мальдивы — виллы над лагуной и бирюзовое море', 'zhiraf-ufa' ),
		),
		'sejshely'   => array(
			'image' => 'country-sejshely.jpg',
			'alt'   => __( 'Сейшелы — гранитные валуны и лазурная вода, Ла-Диг', 'zhiraf-ufa' ),
		),
		'shri-lanka' => array(
			'image' => 'country-shri-lanka.jpg',
			'alt'   => __( 'Шри-Ланка — рыбаки на шестах в океане', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Default logistics badges when not set in landing pack.
 *
 * @param string $slug Destination slug.
 * @return array<int,array{icon?:string,text:string}>
 */
function zhiraf_ufa_destination_default_hero_badges( $slug ) {
	$map = array(
		'turciya'   => array(
			array( 'icon' => '✈', 'text' => __( 'Прямой рейс или стыковка в Москве из Уфы', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без визы • до 90 дней', 'zhiraf-ufa' ) ),
		),
		'oae'       => array(
			array( 'icon' => '✈', 'text' => __( 'Прямой рейс из Уфы ~4,5–5 ч', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без визы • 90 дней', 'zhiraf-ufa' ) ),
		),
		'egipet'    => array(
			array( 'icon' => '✈', 'text' => __( 'Вылет из Уфы в Хургаду / Шарм', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Виза по прилёту / e-visa', 'zhiraf-ufa' ) ),
		),
		'tailand'   => array(
			array( 'icon' => '✈', 'text' => __( 'Стыковка в Москве из Уфы', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без визы • до 30/45/90 дней', 'zhiraf-ufa' ) ),
		),
		'vietnam'   => array(
			array( 'icon' => '✈', 'text' => __( 'Стыковка в Москве из Уфы', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без визы • до 45 дней', 'zhiraf-ufa' ) ),
		),
		'abkhaziya' => array(
			array( 'icon' => '🚐', 'text' => __( 'Трансфер из аэропорта Адлер (Сочи) до отеля', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Паспорт РФ • оплата в рублях', 'zhiraf-ufa' ) ),
		),
		'rossiya'   => array(
			array( 'icon' => '🏨', 'text' => __( 'Пакет с перелётом или только проживание', 'zhiraf-ufa' ) ),
			array( 'icon' => '🛂', 'text' => __( 'Без загранпаспорта', 'zhiraf-ufa' ) ),
		),
	);

	return isset( $map[ $slug ] ) ? $map[ $slug ] : array();
}

/**
 * Resolve active central module for a landing.
 *
 * @param string              $slug Destination slug.
 * @param array<string,mixed> $dest Landing row.
 * @param int                 $page_id Page ID.
 */
function zhiraf_ufa_destination_landing_module( $slug, $dest, $page_id = 0 ) {
	if ( ! empty( $dest['landing_module'] ) && array_key_exists( $dest['landing_module'], zhiraf_ufa_destination_module_types() ) ) {
		return (string) $dest['landing_module'];
	}
	if ( ! $page_id ) {
		$page_id = get_queried_object_id();
	}
	if ( $page_id ) {
		$meta = get_post_meta( $page_id, '_zhiraf_landing_module', true );
		if ( is_string( $meta ) && array_key_exists( $meta, zhiraf_ufa_destination_module_types() ) ) {
			return $meta;
		}
	}
	$defaults = zhiraf_ufa_destination_default_module_map();
	if ( isset( $defaults[ $slug ] ) ) {
		return $defaults[ $slug ];
	}
	return 'vibe';
}

/**
 * Apply hero standards and defaults to landing config.
 *
 * @param array<string,mixed> $dest Landing row.
 * @param string              $slug Slug.
 * @return array<string,mixed>
 */
function zhiraf_ufa_apply_destination_presentation( $dest, $slug ) {
	$standards = zhiraf_ufa_destination_hero_image_standards();
	if ( isset( $standards[ $slug ] ) && empty( $dest['hero_image_override'] ) ) {
		$dest['image']     = $standards[ $slug ]['image'];
		$dest['image_alt'] = $standards[ $slug ]['alt'];
	}
	if ( empty( $dest['hero_badges'] ) ) {
		$badges = zhiraf_ufa_destination_default_hero_badges( $slug );
		if ( $badges ) {
			$dest['hero_badges'] = $badges;
		}
	}
	if ( empty( $dest['cta_lead'] ) && ! empty( $dest['name'] ) ) {
		/* translators: %s: country name */
		$dest['cta_lead'] = sprintf(
			__( 'Не уверены, какой отель выбрать в %s? Напишите бюджет и состав семьи — эксперт пришлёт подборку из 3 отелей в WhatsApp за 15 минут.', 'zhiraf-ufa' ),
			$dest['name']
		);
	}
	if ( empty( $dest['cta_message_placeholder'] ) ) {
		$dest['cta_message_placeholder'] = __( 'Бюджет, даты, дети (возраст), пожелания по курорту', 'zhiraf-ufa' );
	}
	return $dest;
}

/**
 * Default resort battle blocks when not in landing pack.
 *
 * @param string $slug Destination slug.
 * @return array{title:string,columns:array}|null
 */
function zhiraf_ufa_default_resort_battle( $slug ) {
	$battles = array(
		'kitaj'   => array(
			'title'   => __( 'Дадунхай или Ялонг Бэй: где остановиться на Хайнане?', 'zhiraf-ufa' ),
			'lead'    => __( 'Две главные бухты Саньи — городской пляж с инфраструктурой или премиальный резорт с чистым морем. Подберём отель под ваш формат отдыха.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Бухта Дадунхай', 'zhiraf-ufa' ),
					'tag'       => __( '🍍 Движ, инфраструктура и экономия', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Русский квартал и вечерняя набережная', 'zhiraf-ufa' ),
					'image'     => 'resort-sanya-2.jpg',
					'cta_label' => __( 'Отели в Дадунхае', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-tours',
					'rows'      => array(
						array( 'label' => __( 'Инфраструктура', 'zhiraf-ufa' ), 'value' => __( 'ТЦ «Ананас», русские и китайские рестораны, массаж, рынки', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Бюджет', 'zhiraf-ufa' ), 'value' => __( 'Много городских отелей 4–5★ и доступный стритфуд', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Логистика', 'zhiraf-ufa' ), 'value' => __( '10 минут до центра Саньи на автобусе или такси', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Бухта Ялонг Бэй', 'zhiraf-ufa' ),
					'tag'       => __( '🌊 Элитный релакс и чистое море', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Резорты мировых сетей на первой линии', 'zhiraf-ufa' ),
					'image'     => 'resort-sanya-3.jpg',
					'cta_label' => __( 'Отели в Ялонг Бэй', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-tours',
					'rows'      => array(
						array( 'label' => __( 'Пляж', 'zhiraf-ufa' ), 'value' => __( 'Белый песок, широкая полоса, бирюзовая вода без катеров', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Премиум-отели', 'zhiraf-ufa' ), 'value' => __( 'Hilton, Marriott, Sheraton — парки и первая линия', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Тишина', 'zhiraf-ufa' ), 'value' => __( 'Мало трасс и толп — для пар и спокойного семейного отдыха', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'tailand' => array(
			'title'   => __( 'Пхукет или Паттайя: что выбрать?', 'zhiraf-ufa' ),
			'lead'    => __( 'Лазурное Андаманское море и островной релакс или движ, экскурсии и Ко Лан — главный выбор первой поездки в Таиланд.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Пхукет', 'zhiraf-ufa' ),
					'tag'       => __( '🏝 Лазурное море и островной релакс', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Пляжи Карон, Ката, Банг Тао и островные экскурсии', 'zhiraf-ufa' ),
					'image'     => 'dest-thailand.jpg',
					'cta_label' => __( 'Смотреть отели на Пхукете', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Море и пляжи', 'zhiraf-ufa' ), 'value' => __( 'Чистое Андаманское море, белый песок — Карон, Ката, Банг Тао', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Резорты с садами, виллы и первая линия у моря', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Природа', 'zhiraf-ufa' ), 'value' => __( 'Пхи-Пхи, Симиланы, смотровые площадки и джунгли', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Паттайя', 'zhiraf-ufa' ),
					'tag'       => __( '🌆 Движ, экономия и экскурсии', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Бангкок рядом, Ко Лан за 15–20 минут на пароме', 'zhiraf-ufa' ),
					'image'     => 'dest-thailand-new.jpg',
					'cta_label' => __( 'Смотреть отели в Паттайе', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Бюджет', 'zhiraf-ufa' ), 'value' => __( 'Туры и кафе часто на 20–30% доступнее, чем на Пхукете', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Экскурсии', 'zhiraf-ufa' ), 'value' => __( 'Бангкок за 2 часа, Квай, Нонг Нуч, зоопарки', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Море', 'zhiraf-ufa' ), 'value' => __( 'За лазурной водой — паром на остров Ко Лан', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'vietnam' => array(
			'title'   => __( 'Нячанг или Фукуок: быстрое сравнение', 'zhiraf-ufa' ),
			'lead'    => __( 'Сезоны здесь не совпадают: в один месяц на Фукуоке штиль, а в Нячанге — волны. Выберите курорт под даты вылета из Уфы.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Нячанг', 'zhiraf-ufa' ),
					'tag'       => __( '🏙 Городской пляж, движ и бюджет', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Лучшее море с марта по сентябрь', 'zhiraf-ufa' ),
					'image'     => 'resort-vietnam-nha-trang-2.jpg',
					'cta_label' => __( 'Выбрать туры в Нячанг', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Сезон без волн', 'zhiraf-ufa' ), 'value' => __( 'Идеальное купание март — сентябрь: сухой штиль и тёплая вода', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Инфраструктура', 'zhiraf-ufa' ), 'value' => __( 'Морепродукты, кофейни, ночные рынки и парки', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Развлечения', 'zhiraf-ufa' ), 'value' => __( 'VinWonders с канатной дорогой над морем', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Остров Фукуок', 'zhiraf-ufa' ),
					'tag'       => __( '🏝 Тропический релакс и прозрачное море', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Зимний высокий сезон — ноябрь — апрель', 'zhiraf-ufa' ),
					'image'     => 'resort-vietnam-phu-quoc-2.jpg',
					'cta_label' => __( 'Выбрать туры на Фукуок', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Зимний сезон', 'zhiraf-ufa' ), 'value' => __( 'Ноябрь — апрель: штиль, когда в России зима', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Пляжи', 'zhiraf-ufa' ), 'value' => __( 'Белый песок, бирюзовая вода — Бай Сао, Кхем', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Резорты на первой линии, бунгало в пальмах', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'oae'     => array(
			'title'   => __( 'Дубай или Рас-эль-Хайма: какой эмират выбрать?', 'zhiraf-ufa' ),
			'lead'    => __( 'Энергия небоскрёбов и шопинга или беззаботный курортный All Inclusive на первой линии.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Дубай', 'zhiraf-ufa' ),
					'tag'       => __( '🏙 Драйв и шопинг', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для активных туристов, пар и любителей впечатлений', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~20 мин', 'zhiraf-ufa' ),
					'image'     => 'dest-uae-new.jpg',
					'cta_label' => __( 'Искать туры в Дубай', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Формат отдыха', 'zhiraf-ufa' ), 'value' => __( 'Шопинг, рестораны, смотровые площадки, динамичная городская среда', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Питание', 'zhiraf-ufa' ), 'value' => __( 'Чаще завтраки (BB) или полупансион — обеды и ужины в городе', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Пляжи', 'zhiraf-ufa' ), 'value' => __( 'Городские пляжи JBR, La Mer или бич-клабы на Пальме', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Рас-эль-Хайма', 'zhiraf-ufa' ),
					'tag'       => __( '🏖 All Inclusive и релакс', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Идеально для семей с детьми и спокойного отдыха у моря', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~60 мин', 'zhiraf-ufa' ),
					'image'     => 'dest-uae.jpg',
					'cta_label' => __( 'Искать туры в Рас-эль-Хайму', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Формат отдыха', 'zhiraf-ufa' ), 'value' => __( 'Спокойный пляжный релакс, большие территории, отдых «в тапочках»', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Питание', 'zhiraf-ufa' ), 'value' => __( 'Классический All Inclusive / Ultra AI в отеле', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Пляжи', 'zhiraf-ufa' ), 'value' => __( 'Собственные песчаные пляжи отелей на первой линии', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'turciya' => array(
			'title'   => __( 'Кемер или Сиде и Аланья: где лучше море и отели', 'zhiraf-ufa' ),
			'lead'    => __( 'Главная дилемма турецкого отдыха: живописные горы с галькой или песчаный берег для детей.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Кемер и посёлки', 'zhiraf-ufa' ),
					'tag'       => __( '🌲 Горы и прохлада', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для ценителей природы и молодёжного отдыха', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~50 мин', 'zhiraf-ufa' ),
					'image'     => 'resort-turciya-kemer-2.jpg',
					'cta_label' => __( 'Искать отели в Кемере', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Пляж и море', 'zhiraf-ufa' ), 'value' => __( 'Галька — вода прозрачная даже в пик сезона', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Климат', 'zhiraf-ufa' ), 'value' => __( 'Сосны и горы Тавр, прохладнее, чем на равнине', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Клубные комплексы в Текирове, Бельдиби, Гёйнюке', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Сиде и Аланья', 'zhiraf-ufa' ),
					'tag'       => __( '🏖 Песок и аквапарки', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для семей с малышами и долгого купания', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: 1–2,5 ч', 'zhiraf-ufa' ),
					'image'     => 'resort-turciya-alanya-2.jpg',
					'cta_label' => __( 'Искать отели в Сиде / Аланье', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Пляж и море', 'zhiraf-ufa' ), 'value' => __( 'Песок и пологий заход — удобно с детьми', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Сезон', 'zhiraf-ufa' ), 'value' => __( 'Море дольше тёплое — часто до ноября', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Семейные резорты с аквапарками и детскими клубами', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'egipet'  => array(
			'title'   => __( 'Хургада или Шарм-эль-Шейх: что выбрать?', 'zhiraf-ufa' ),
			'lead'    => __( 'Песчаный заход для семьи или кораллы и безветренные бухты на зиму — главная дилемма Красного моря.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Хургада', 'zhiraf-ufa' ),
					'tag'       => __( '🏖 Песок и семейный отдых', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Пологий заход, аквапарки и экскурсии к Луксору', 'zhiraf-ufa' ),
					'image'     => 'resort-egipet-hurghada-2.jpg',
					'cta_label' => __( 'Искать отели в Хургаде', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Вход в море', 'zhiraf-ufa' ), 'value' => __( 'Пологий песок — комфортно детям и пожилым без аквашузов', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Крупные комплексы с аквапарками и широкими пляжами', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Экскурсии', 'zhiraf-ufa' ), 'value' => __( 'Удобнее добираться до Луксора и Каира, чем из Шарма', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Шарм-эль-Шейх', 'zhiraf-ufa' ),
					'tag'       => __( '🤿 Кораллы и безветренные бухты', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Рифы у понтона и защищённые бухты зимой', 'zhiraf-ufa' ),
					'image'     => 'resort-egipet-sharm-2.jpg',
					'cta_label' => __( 'Искать отели в Шарме', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Подводный мир', 'zhiraf-ufa' ), 'value' => __( 'Коралловые рифы у берега, снорклинг и дайвинг с понтона', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Зимний комфорт', 'zhiraf-ufa' ), 'value' => __( 'Бухты Наама-Бей, Шарм-эль-Майя, Рас-Умм-Сид, Хадаба', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Вечерний движ', 'zhiraf-ufa' ), 'value' => __( 'Старый город, променад Наама-Бей и кафе Farsha', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'sejshely'  => array(
			'title'   => __( 'Остров Маэ или Праслин и Ла-Диг?', 'zhiraf-ufa' ),
			'lead'    => __( 'Удобная логистика и пляж Бо-Валлон на главном острове — или открыточные валуны и велосипеды на соседних.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Остров Маэ', 'zhiraf-ufa' ),
					'tag'       => __( '🏝 Комфорт и Бо-Валлон', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Аэропорт, инфраструктура и аренда авто', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: 20–30 мин от аэропорта', 'zhiraf-ufa' ),
					'image'     => 'alt-sejshely-3.jpg',
					'cta_label' => __( 'Искать отели на Маэ', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Логистика', 'zhiraf-ufa' ), 'value' => __( 'Международный аэропорт — трансфер в отель на авто без паромов', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Пляж Бо-Валлон', 'zhiraf-ufa' ), 'value' => __( 'Широкая полоса, спокойное купание без сильных отливов', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Инфраструктура', 'zhiraf-ufa' ), 'value' => __( 'Виктория, креольская кухня, рынки и аренда авто', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Праслин и Ла-Диг', 'zhiraf-ufa' ),
					'tag'       => __( '🐢 Валуны и природа', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Анс Сурс д’Аржан и пальмы Коко-де-Мер', 'zhiraf-ufa' ),
					'meta'      => __( 'Добраться: паром или внутренний рейс', 'zhiraf-ufa' ),
					'image'     => 'country-sejshely.jpg',
					'cta_label' => __( 'Искать отели на Праслине и Ла-Диге', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Пляжи', 'zhiraf-ufa' ), 'value' => __( 'Анс Сурс д’Аржан и Анс Лацио — гранитные валуны и бирюза', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Ритм', 'zhiraf-ufa' ), 'value' => __( 'На Ла-Диге почти нет машин — велосипеды среди пальм', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Природа', 'zhiraf-ufa' ), 'value' => __( 'Валле-де-Мэ, Коко-де-Мер и гигантские черепахи', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'maldivy'   => array(
			'title'   => __( 'Остров-резорт или местный остров?', 'zhiraf-ufa' ),
			'lead'    => __( 'Премиальный «1 остров = 1 отель» с виллами над водой или бюджетный гестхаус на Маафуши / Тодду — два разных формата одной страны.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Остров-резорт', 'zhiraf-ufa' ),
					'tag'       => __( '🏝 1 остров = 1 отель', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Виллы над лагуной и All Inclusive', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: гидросамолёт / катер', 'zhiraf-ufa' ),
					'image'     => 'resort-maldivy-atoll-1.jpg',
					'cta_label' => __( 'Подобрать резорт-остров', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Формат', 'zhiraf-ufa' ), 'value' => __( 'Отдельный остров с пляжем, ресторанами и спа — без соседей с материка', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Проживание', 'zhiraf-ufa' ), 'value' => __( 'Водные и пляжные виллы, инфинити-бассейны, питание в отеле', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Для кого', 'zhiraf-ufa' ), 'value' => __( 'Пары, медовый месяц и семьи с бюджетом от 200 000 ₽ за двоих', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Маафуши / Тодду', 'zhiraf-ufa' ),
					'tag'       => __( '🏘 Местный остров', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Гестхаусы и экскурсии с местными', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: скоростной катер', 'zhiraf-ufa' ),
					'image'     => 'resort-maldivy-atoll-2.jpg',
					'cta_label' => __( 'Подобрать гестхаус', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Бюджет', 'zhiraf-ufa' ), 'value' => __( 'Дешевле резорта: гестхаусы, кафе, экскурсии на соседние пляжи', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Атмосфера', 'zhiraf-ufa' ), 'value' => __( 'Жизнь местных, магазины — не закрытый курорт', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Важно', 'zhiraf-ufa' ), 'value' => __( 'На общественных пляжах — закрытая одежда, алкоголь только в отеле', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'shri-lanka' => array(
			'title'   => __( 'Унаватуна или Хиккадува?', 'zhiraf-ufa' ),
			'lead'    => __( 'Спокойная бухта с рифом для семей или серф, черепахи и вечерняя жизнь на юго-западном побережье.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Унаватуна', 'zhiraf-ufa' ),
					'tag'       => __( '🐢 Без волн', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Спокойный океан и семьи с детьми', 'zhiraf-ufa' ),
					'meta'      => __( 'Юго-запад • лучший сезон: ноябрь–апрель', 'zhiraf-ufa' ),
					'image'     => 'alt-shri-lanka-beach-1.jpg',
					'cta_label' => __( 'Искать туры в Унаватуну', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Купание', 'zhiraf-ufa' ), 'value' => __( 'Коралловый риф закрывает бухту от больших волн — комфортно даже малышам', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Инфраструктура', 'zhiraf-ufa' ), 'value' => __( 'Кафе со свежими креветками и рыбой прямо на песке', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Рядом', 'zhiraf-ufa' ), 'value' => __( '15 минут на тук-туке до форта Галле (ЮНЕСКО)', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Хиккадува', 'zhiraf-ufa' ),
					'tag'       => __( '🏄 Серф и черепахи', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Спорт, природа и вечерняя атмосфера', 'zhiraf-ufa' ),
					'meta'      => __( 'Юго-запад • волны и тусовка', 'zhiraf-ufa' ),
					'image'     => 'alt-shri-lanka-beach-2.jpg',
					'cta_label' => __( 'Искать туры в Хиккадуву', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Черепахи', 'zhiraf-ufa' ), 'value' => __( 'У Hikka Tranz морские черепахи часто подплывают к берегу', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Серфинг', 'zhiraf-ufa' ), 'value' => __( 'Споты для начинающих и продвинутых, школы с русскоязычными инструкторами', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Вечер', 'zhiraf-ufa' ), 'value' => __( 'Бары с живой музыкой, вечеринки на берегу и фруктовый рынок', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'indoneziya' => array(
			'title'   => __( 'Нуса-Дуа или Чангу / Семиньяк?', 'zhiraf-ufa' ),
			'lead'    => __( 'Спокойное купание и отели 5★ на юго-востоке — или серф, бич-клабы и виллы на западном побережье.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Нуса-Дуа', 'zhiraf-ufa' ),
					'tag'       => __( '🏖 Купание и 5★', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Спокойный океан и курортная зона', 'zhiraf-ufa' ),
					'meta'      => __( 'Юго-восток • охраняемая территория', 'zhiraf-ufa' ),
					'image'     => 'alt-indoneziya-beach-1.jpg',
					'cta_label' => __( 'Искать отели в Нуса-Дуа', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Купание', 'zhiraf-ufa' ), 'value' => __( 'Риф гасит волны открытого океана — комфортный заход в воду даже детям', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Территория', 'zhiraf-ufa' ), 'value' => __( 'Закрытая зелёная зона с прогулочными дорожками и полями для гольфа', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Отели', 'zhiraf-ufa' ), 'value' => __( 'Мировые сети The Mulia, Grand Hyatt, Meliá на первой линии', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Чангу и Семиньяк', 'zhiraf-ufa' ),
					'tag'       => __( '🏄 Серф и бич-клабы', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Лайфстайл, закаты и волны', 'zhiraf-ufa' ),
					'meta'      => __( 'Западное побережье • тусовка', 'zhiraf-ufa' ),
					'image'     => 'alt-indoneziya-beach-2.jpg',
					'cta_label' => __( 'Искать туры в Чангу / Семиньяк', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Атмосфера', 'zhiraf-ufa' ), 'value' => __( 'Finns, Atlas Beach Fest, дизайнерские рестораны и закатные бары', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Серфинг', 'zhiraf-ufa' ), 'value' => __( 'Волны круглый год, серф-кемпы и русскоязычные инструкторы', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Проживание', 'zhiraf-ufa' ), 'value' => __( 'Стильные виллы с бассейнами среди рисовых полей', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'abkhaziya' => array(
			'title'   => __( 'Гагра или Пицунда: какой курорт выбрать?', 'zhiraf-ufa' ),
			'lead'    => __( 'Энергия курортной набережной с ресторанами или целебный сосновый микроклимат и спокойное море.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Гагра (Новая и Старая)', 'zhiraf-ufa' ),
					'tag'       => __( '🌴 Движ и набережная', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для молодёжи, пар и любителей вечерних прогулок', 'zhiraf-ufa' ),
					'meta'      => __( 'От границы: ~25 мин', 'zhiraf-ufa' ),
					'image'     => 'alt-armeniya-3.jpg',
					'cta_label' => __( 'Искать отели в Гагре', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Инфраструктура', 'zhiraf-ufa' ), 'value' => __( 'Приморский парк, ресторан «Гагрипш», кафе и аквапарк', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Ближе к границе', 'zhiraf-ufa' ), 'value' => __( 'Самый быстрый трансфер от аэропорта Адлер без длинного серпантина', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Горные панорамы', 'zhiraf-ufa' ), 'value' => __( 'Кавказские горы вплотную к морю — живописные виды', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Пицунда и Лдзаа', 'zhiraf-ufa' ),
					'tag'       => __( '🌲 Сосны и штиль', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Идеально для семей с детьми и оздоровления', 'zhiraf-ufa' ),
					'meta'      => __( 'От границы: ~50 мин', 'zhiraf-ufa' ),
					'image'     => 'alt-armeniya-2.jpg',
					'cta_label' => __( 'Искать отели в Пицунде', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Спокойное море', 'zhiraf-ufa' ), 'value' => __( 'Мыс закрывает бухту от ветров — волн почти не бывает, вода прозрачная', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Сосновая роща', 'zhiraf-ufa' ), 'value' => __( 'Целебный хвойный воздух прямо на берегу моря', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Песчаные участки', 'zhiraf-ufa' ), 'value' => __( 'У Рыбзавода (Лдзаа) — редкий для Абхазии песчаный вход в море', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
		'rossiya'   => array(
			'title'   => __( 'Имеретинка (Сириус) или Красная Поляна?', 'zhiraf-ufa' ),
			'lead'    => __( 'Широкая морская набережная без ж/д путей или чистый горный воздух и премиальные курорты.', 'zhiraf-ufa' ),
			'columns' => array(
				array(
					'title'     => __( 'Сириус (Имеретинская бухта)', 'zhiraf-ufa' ),
					'tag'       => __( '🌊 Море и самокаты', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для семей, прогулок у моря и активного отдыха', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~15 мин', 'zhiraf-ufa' ),
					'image'     => 'hero-collage-1.jpg',
					'cta_label' => __( 'Искать отели в Сириусе', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Без ж/д у пляжа', 'zhiraf-ufa' ), 'value' => __( 'Единственный район Сочи, где над пляжем не грохочут поезда', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Набережная 7 км', 'zhiraf-ufa' ), 'value' => __( 'Велодорожки, фонтаны, Сочи Парк, отели «Сириус», «Богатырь», Radisson', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Короткий путь', 'zhiraf-ufa' ), 'value' => __( '10–15 минут от трапа самолёта до дверей отеля', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title'     => __( 'Красная Поляна и Роза Хутор', 'zhiraf-ufa' ),
					'tag'       => __( '🏔 Горы и сервис 5★', 'zhiraf-ufa' ),
					'subtitle'  => __( 'Для ценителей высокого сервиса, SPA и природы', 'zhiraf-ufa' ),
					'meta'      => __( 'Трансфер: ~40 мин', 'zhiraf-ufa' ),
					'image'     => 'hero-collage-2.jpg',
					'cta_label' => __( 'Искать отели в Поляне', 'zhiraf-ufa' ),
					'cta_href'  => '#dest-lead',
					'rows'      => array(
						array( 'label' => __( 'Уровень сервиса', 'zhiraf-ufa' ), 'value' => __( 'Отели мировых брендов, подогреваемые бассейны с видом на горы', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Круглый год', 'zhiraf-ufa' ), 'value' => __( 'Зимой — лыжи, летом — треккинг и трансферы отелей на пляжи у моря', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Вечер', 'zhiraf-ufa' ), 'value' => __( 'Гастрономия региона и развлекательная программа', 'zhiraf-ufa' ) ),
					),
				),
			),
		),
	);
	if ( ! isset( $battles[ $slug ] ) ) {
		return null;
	}
	return $battles[ $slug ];
}

/**
 * Render the single central module for a destination landing.
 *
 * @param string              $slug Slug.
 * @param array<string,mixed> $dest Landing config (enriched).
 */
function zhiraf_ufa_render_destination_central_module( $slug, $dest ) {
	$module = zhiraf_ufa_destination_landing_module( $slug, $dest );
	switch ( $module ) {
		case 'battle':
			$title       = ! empty( $dest['resort_compare_title'] ) ? $dest['resort_compare_title'] : '';
			$columns     = ! empty( $dest['resort_compare'] ) ? $dest['resort_compare'] : array();
			$battle_pack = zhiraf_ufa_default_resort_battle( $slug );
			if ( $battle_pack && ! empty( $battle_pack['columns'] ) ) {
				$has_rich_battle = false;
				if ( ! empty( $columns ) ) {
					foreach ( $columns as $col ) {
						if ( ! empty( $col['cta_label'] ) && ! empty( $col['rows'] ) && count( $col['rows'] ) >= 2 ) {
							$has_rich_battle = true;
							break;
						}
					}
				}
				if ( empty( $columns ) || ! $has_rich_battle ) {
					$columns = $battle_pack['columns'];
					if ( '' === $title && ! empty( $battle_pack['title'] ) ) {
						$title = $battle_pack['title'];
					}
				}
			}
			if ( empty( $columns ) ) {
				break;
			}
			if ( '' === $title && $battle_pack && ! empty( $battle_pack['title'] ) ) {
				$title = $battle_pack['title'];
			}
			$battle_lead = ! empty( $dest['resort_compare_lead'] ) ? (string) $dest['resort_compare_lead'] : '';
			if ( '' === $battle_lead && $battle_pack && ! empty( $battle_pack['lead'] ) ) {
				$battle_lead = (string) $battle_pack['lead'];
			}
			$wa_text = __( 'Здравствуйте! Помогите выбрать курорт', 'zhiraf-ufa' );
			if ( 'turciya' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать курорт в Турции', 'zhiraf-ufa' );
			} elseif ( 'oae' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать курорт в ОАЭ', 'zhiraf-ufa' );
			} elseif ( 'egipet' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Хургадой и Шармом', 'zhiraf-ufa' );
			} elseif ( 'tailand' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Пхукетом и Паттайей', 'zhiraf-ufa' );
			} elseif ( 'vietnam' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Нячангом и Фукуоком', 'zhiraf-ufa' );
			} elseif ( 'abkhaziya' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Гагрой и Пицундой', 'zhiraf-ufa' );
			} elseif ( 'rossiya' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Сириусом и Красной Поляной', 'zhiraf-ufa' );
			} elseif ( 'kitaj' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Дадунхаем и Ялонг Бэй', 'zhiraf-ufa' );
			} elseif ( 'maldivy' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между резорт-островом и Маафуши', 'zhiraf-ufa' );
			} elseif ( 'sejshely' === $slug ) {
				$wa_text = __( 'Здравствуйте! Помогите выбрать между Маэ и Праслином / Ла-Дигом', 'zhiraf-ufa' );
			}
			get_template_part(
				'template-parts/destination/module-resort-battle',
				null,
				array(
					'title'   => $title,
					'lead'    => $battle_lead,
					'columns' => $columns,
					'wa_text' => $wa_text,
				)
			);
			if ( ! empty( $dest['vibes_after_central'] ) && function_exists( 'zhiraf_ufa_get_vibe_section' ) ) {
				$vibe_section = zhiraf_ufa_get_vibe_section( $slug, $dest );
				if ( $vibe_section ) {
					get_template_part(
						'template-parts/destination/vibe-cards',
						null,
						array(
							'section'      => $vibe_section,
							'quiz_prefill' => ! empty( $dest['quiz_prefill'] ) ? $dest['quiz_prefill'] : '',
						)
					);
				}
			}
			break;
		case 'season':
			$seasons = ! empty( $dest['climate_seasons'] ) ? $dest['climate_seasons'] : array();
			if ( empty( $seasons ) && ! empty( $dest['season'] ) ) {
				$seasons = array(
					array(
						'label'  => __( 'Обзор', 'zhiraf-ufa' ),
						'months' => '',
						'air'    => '',
						'water'  => '',
						'tip'    => (string) $dest['season'],
					),
				);
			}
			if ( empty( $seasons ) ) {
				break;
			}
			get_template_part(
				'template-parts/destination/module-season',
				null,
				array(
					'seasons'      => $seasons,
					'title'        => ! empty( $dest['climate_module_title'] ) ? $dest['climate_module_title'] : __( 'Сезонность и климат', 'zhiraf-ufa' ),
					'quiz_prefill' => ! empty( $dest['quiz_prefill'] ) ? $dest['quiz_prefill'] : '',
					'country_name' => ! empty( $dest['name'] ) ? $dest['name'] : '',
				)
			);
			break;
		case 'vibe':
		default:
			if ( ! function_exists( 'zhiraf_ufa_get_vibe_section' ) ) {
				break;
			}
			$vibe_section = zhiraf_ufa_get_vibe_section( $slug, $dest );
			if ( $vibe_section ) {
				get_template_part(
					'template-parts/destination/vibe-cards',
					null,
					array(
						'section'      => $vibe_section,
						'quiz_prefill' => ! empty( $dest['quiz_prefill'] ) ? $dest['quiz_prefill'] : '',
					)
				);
			}
			break;
	}
}

/**
 * Admin meta: pick central module on destination WP pages.
 */
function zhiraf_ufa_destination_module_meta_box() {
	$post = get_post();
	if ( ! $post || 'page' !== $post->post_type ) {
		return;
	}
	$slug = $post->post_name;
	if ( ! in_array( $slug, zhiraf_ufa_destination_landing_slugs(), true ) ) {
		return;
	}
	add_meta_box(
		'zhiraf-landing-module',
		__( 'Интерактивный блок посадочной', 'zhiraf-ufa' ),
		'zhiraf_ufa_destination_module_meta_box_render',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes_page', 'zhiraf_ufa_destination_module_meta_box' );

/**
 * @param WP_Post $post Post.
 */
function zhiraf_ufa_destination_module_meta_box_render( $post ) {
	wp_nonce_field( 'zhiraf_landing_module', 'zhiraf_landing_module_nonce' );
	$current = get_post_meta( $post->ID, '_zhiraf_landing_module', true );
	$types   = zhiraf_ufa_destination_module_types();
	$defaults = zhiraf_ufa_destination_default_module_map();
	$slug    = $post->post_name;
	echo '<p><label for="zhiraf_landing_module">' . esc_html__( 'Тип блока под каталогом туров', 'zhiraf-ufa' ) . '</label></p>';
	echo '<select name="zhiraf_landing_module" id="zhiraf_landing_module" class="widefat">';
	echo '<option value="">' . esc_html__( '— по умолчанию для направления —', 'zhiraf-ufa' ) . '</option>';
	foreach ( $types as $key => $label ) {
		printf(
			'<option value="%s"%s>%s</option>',
			esc_attr( $key ),
			selected( $current, $key, false ),
			esc_html( $label )
		);
	}
	echo '</select>';
	if ( isset( $defaults[ $slug ] ) ) {
		printf(
			'<p class="description">%s</p>',
			esc_html(
				sprintf(
					/* translators: %s: module label */
					__( 'По умолчанию для «%1$s»: %2$s', 'zhiraf-ufa' ),
					$slug,
					$types[ $defaults[ $slug ] ] ?? $defaults[ $slug ]
				)
			)
		);
	}
}

/**
 * Save module meta.
 *
 * @param int $post_id Post ID.
 */
function zhiraf_ufa_save_destination_module_meta( $post_id ) {
	if ( ! isset( $_POST['zhiraf_landing_module_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zhiraf_landing_module_nonce'] ) ), 'zhiraf_landing_module' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}
	$val = isset( $_POST['zhiraf_landing_module'] ) ? sanitize_key( wp_unslash( $_POST['zhiraf_landing_module'] ) ) : '';
	if ( '' === $val ) {
		delete_post_meta( $post_id, '_zhiraf_landing_module' );
		return;
	}
	if ( array_key_exists( $val, zhiraf_ufa_destination_module_types() ) ) {
		update_post_meta( $post_id, '_zhiraf_landing_module', $val );
	}
}
add_action( 'save_post_page', 'zhiraf_ufa_save_destination_module_meta' );
