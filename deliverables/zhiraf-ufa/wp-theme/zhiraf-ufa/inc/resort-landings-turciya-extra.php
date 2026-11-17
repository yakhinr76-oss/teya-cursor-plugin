<?php
/**
 * Turkey resort landings (Kemer, Alanya, Side, Belek).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extra resort rows for /tour/turciya/{resort}/.
 *
 * @return array<string,array<string,mixed>>
 */
function zhiraf_ufa_turciya_extra_resort_landings() {
	$parent = home_url( '/tour/turciya/' );
	$common_faq_visa = array(
		'q' => __( 'Нужна ли виза в Турцию?', 'zhiraf-ufa' ),
		'a' => __( 'Гражданам РФ виза не нужна до 60 дней. Загранпаспорт — не менее 120 дней действия.', 'zhiraf-ufa' ),
	);

	return array(
		'turciya/kemer' => array(
			'tours_rx'     => '/Кемер|Текирова|Гёйнюк|Kemer/ui',
			'country'      => 'turciya',
			'country_name' => __( 'Турция', 'zhiraf-ufa' ),
			'resort'       => 'kemer',
			'h1'           => __( 'Туры в Кемер из Уфы', 'zhiraf-ufa' ),
			'title'        => __( 'Кемер из Уфы — туры у Таврских гор | Жираф', 'zhiraf-ufa' ),
			'description'  => __( 'Подбор туров в Кемер с вылетом из Уфы: сосновые горы, прозрачное море, галечные бухты, семейные Ultra All Inclusive.', 'zhiraf-ufa' ),
			'image'        => 'resort-turciya-kemer-1.jpg',
			'image_alt'    => __( 'Кемер — сосны, горы и Средиземное море', 'zhiraf-ufa' ),
			'hero_lead'    => __( 'Кемер для тех, кому важны чистый воздух, горный пейзаж и прозрачная вода. Подбираем отели в Текирова и Гёйнюк с честным описанием галечного берега.', 'zhiraf-ufa' ),
			'quiz_prefill' => __( 'Кемер, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'   => __( 'Прямой чартер из Уфы в Анталью — 4,5–5 часов, трансфер до Кемера около 1 часа по трассе. Тип рейса и багаж — в договоре по данным «Библио-Глобус».', 'zhiraf-ufa' ),
			'sections'     => array(
				array(
					'h2'         => __( 'Пляжи и вход в море', 'zhiraf-ufa' ),
					'paragraphs' => array(
						__( 'В Кемере преобладает галька и гравий: море прозрачное, но без аквашуз сложнее. Для малышей чаще смотрим Сиде или Белек — на странице Турции сравниваем курорты.', 'zhiraf-ufa' ),
					),
				),
			),
			'faq'          => array(
				$common_faq_visa,
				array(
					'q' => __( 'Чем Кемер отличается от Антальи?', 'zhiraf-ufa' ),
					'a' => sprintf(
						__( 'У Кемера — горы и сосны, у Антальи — короткий трансфер и город. Сводка по всем курортам — %s.', 'zhiraf-ufa' ),
						'<a href="' . esc_url( $parent ) . '">' . esc_html__( 'туры в Турцию из Уфы', 'zhiraf-ufa' ) . '</a>'
					),
				),
			),
		),
		'turciya/alanya' => array(
			'tours_rx'     => '/Аланья|Alanya/ui',
			'country'      => 'turciya',
			'country_name' => __( 'Турция', 'zhiraf-ufa' ),
			'resort'       => 'alanya',
			'h1'           => __( 'Туры в Аланью из Уфы', 'zhiraf-ufa' ),
			'title'        => __( 'Аланья из Уфы — тёплое море и песчаные пляжи | Жираф', 'zhiraf-ufa' ),
			'description'  => __( 'Туры в Аланью с вылетом из Уфы: пляж Клеопатры, пологий заход, семейные отели и доступный All Inclusive.', 'zhiraf-ufa' ),
			'image'        => 'resort-turciya-alanya-2.jpg',
			'image_alt'    => __( 'Аланья — песчаный пляж и крепость', 'zhiraf-ufa' ),
			'hero_lead'    => __( 'Аланья — южное побережье с длинным сезоном и широким выбором отелей. Честно предупреждаем про трансфер 2–2,5 часа от аэропорта Антальи.', 'zhiraf-ufa' ),
			'quiz_prefill' => __( 'Аланья, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'   => __( 'Чартер Уфа — Анталья 4,5–5 часов, далее трансфер до Аланьи 2–2,5 часа. На карточке тура и в договоре фиксируем рейс и багаж.', 'zhiraf-ufa' ),
			'sections'     => array(
				array(
					'h2'         => __( 'Пляжи Аланьи', 'zhiraf-ufa' ),
					'paragraphs' => array(
						__( 'Песчаный заход, тёплое море даже в межсезонье. Для первой поездки с детьми часто сочетаем Аланью с Сиде — сравнение на хабе Турции.', 'zhiraf-ufa' ),
					),
				),
			),
			'faq'          => array( $common_faq_visa ),
		),
		'turciya/side' => array(
			'tours_rx'     => '/Сиде|Side|Кумкой|Чолаклы/ui',
			'country'      => 'turciya',
			'country_name' => __( 'Турция', 'zhiraf-ufa' ),
			'resort'       => 'side',
			'h1'           => __( 'Туры в Сиде из Уфы', 'zhiraf-ufa' ),
			'title'        => __( 'Сиде из Уфы — песок и античные руины | Жираф', 'zhiraf-ufa' ),
			'description'  => __( 'Туры в Сиде с вылетом из Уфы: золотистый песок, мелководье для детей, отели All Inclusive у моря.', 'zhiraf-ufa' ),
			'image'        => 'resort-turciya-side-1.jpg',
			'image_alt'    => __( 'Сиде — песчаный пляж и античный амфитеатр', 'zhiraf-ufa' ),
			'hero_lead'    => __( 'Сиде — главный «детский» курорт побережья: широкий песок, пологий вход и спокойные бухты Кумкой и Чолаклы.', 'zhiraf-ufa' ),
			'quiz_prefill' => __( 'Сиде, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'   => __( 'Прямой чартер из Уфы в Анталью, трансфер до Сиде ~1 ч 10 мин. Подробный чек-лист для семей — в гиде про туры с детьми из Уфы.', 'zhiraf-ufa' ),
			'sections'     => array(
				array(
					'h2'         => __( 'Почему семьи выбирают Сиде', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'Песчаные отмели, детские клубы и спокойная анимация. Разбор критериев — %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/blog/tury-s-detmi-iz-ufy/' ) ) . '">' . esc_html__( 'туры с детьми из Уфы', 'zhiraf-ufa' ) . '</a>'
						),
					),
				),
			),
			'faq'          => array( $common_faq_visa ),
		),
		'turciya/belek' => array(
			'tours_rx'     => '/Белек|Belek/ui',
			'country'      => 'turciya',
			'country_name' => __( 'Турция', 'zhiraf-ufa' ),
			'resort'       => 'belek',
			'h1'           => __( 'Туры в Белек из Уфы', 'zhiraf-ufa' ),
			'title'        => __( 'Белек из Уфы — премиум 5★ и гольф | Жираф', 'zhiraf-ufa' ),
			'description'  => __( 'Туры в Белек с вылетом из Уфы: короткий трансфер, закрытые территории, Ultra All Inclusive и гольф-поля.', 'zhiraf-ufa' ),
			'image'        => 'resort-turciya-belek-1.jpg',
			'image_alt'    => __( 'Белек — сосновые отели у моря', 'zhiraf-ufa' ),
			'hero_lead'    => __( 'Белек — премиум-сегмент Антальи: песчаные пляжи, гольф, охраняемые резорты и один из самых коротких трансферов от аэропорта.', 'zhiraf-ufa' ),
			'quiz_prefill' => __( 'Белек, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'   => __( 'Чартер из Уфы в Анталью 4,5–5 часов, трансфер до Белека 30–40 минут. Цены и питание HCI/UAI — на карточках туров.', 'zhiraf-ufa' ),
			'sections'     => array(
				array(
					'h2'         => __( 'Формат отдыха в Белеке', 'zhiraf-ufa' ),
					'paragraphs' => array(
						__( 'Крупные территории 5★, песок, высокий сервис. Часто рекомендуем парам и семьям, которым важны безопасность и спокойствие на резорте.', 'zhiraf-ufa' ),
					),
				),
			),
			'faq'          => array( $common_faq_visa ),
		),
	);
}