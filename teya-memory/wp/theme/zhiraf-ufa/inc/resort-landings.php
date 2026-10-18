<?php
/**
 * Resort landings: /tour/{country}/{resort}/.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All resort keys country/resort.
 *
 * @return array<int,array{country:string,resort:string}>
 */
function zhiraf_ufa_resort_landing_index() {
	return array(
		array( 'country' => 'turciya', 'resort' => 'antalya' ),
		array( 'country' => 'egipet', 'resort' => 'hurgada' ),
		array( 'country' => 'egipet', 'resort' => 'sharm-el-sheyh' ),
		array( 'country' => 'vietnam', 'resort' => 'nyachang' ),
		array( 'country' => 'vietnam', 'resort' => 'fukuok' ),
	);
}

/**
 * Resort landing content.
 *
 * @return array<string,array<string,mixed>>
 */
function zhiraf_ufa_resort_landing_data() {
	return array(
		'turciya/antalya' => array(
			'country'       => 'turciya',
			'country_name'  => __( 'Турция', 'zhiraf-ufa' ),
			'resort'        => 'antalya',
			'h1'            => __( 'Туры в Анталью из Уфы', 'zhiraf-ufa' ),
			'title'         => __( 'Анталья из Уфы — туры на море | «Жираф»', 'zhiraf-ufa' ),
			'description'   => __( 'Туры в Анталью с вылетом из Уфы: отель, перелёт, питание AI/UAI. Подбор без цен «от».', 'zhiraf-ufa' ),
			'image'         => 'dest-turkey.jpg',
			'image_alt'     => __( 'Анталья, курорт Турции', 'zhiraf-ufa' ),
			'hero_lead'     => __( 'Анталья из Уфы — 344 показа в месяц по Wordstat: запрос стабильный у тех, кто уже определился с курортом Турции. Родительское направление — туры в Турцию из Уфы (2498).', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Анталья, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Почему выбирают Анталью из Уфы? Регион с развитой отельной базой и пляжами Средиземного моря; из Уфы в сезон доступны пакетные чартеры у туроператоров. Мы подбираем отель и питание под семью или пару — состав фиксируется в договоре.', 'zhiraf-ufa' ),
			'sections'      => array(
				array(
					'h2' => __( 'Что уточняем при подборе', 'zhiraf-ufa' ),
					'paragraphs' => array(
						__( 'Даты вылета из Уфы, звёздность отеля, линия пляжа, питание (AI/UAI), трансфер и страховка по пакету оператора.', 'zhiraf-ufa' ),
					),
				),
				array(
					'h2' => __( '«Всё включено» в Анталье', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'Многие отели работают в формате AI — детали по странице «всё включено» (735 показов) и %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ) ) . '">статье BT-02</a>'
						),
					),
				),
				array(
					'h2' => __( 'С детьми', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'Критерии отеля и перелёта — %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/blog/tury-s-detmi-iz-ufy/' ) ) . '">' . esc_html__( 'туры с детьми из Уфы', 'zhiraf-ufa' ) . '</a>'
						),
					),
				),
			),
			'faq'           => array(
				array(
					'q' => __( 'Чем Анталья отличается от Кемера?', 'zhiraf-ufa' ),
					'a' => __( 'Кемер — другой микроклимат и формат бухты; оба обсуждаем на странице Турции (секции Кемер 82, Аланья 58 показов).', 'zhiraf-ufa' ),
					'link' => home_url( '/tour/turciya/' ),
				),
				array(
					'q' => __( 'Есть ли горящие?', 'zhiraf-ufa' ),
					'a' => __( 'Актуальные пакеты — на странице горящих туров.', 'zhiraf-ufa' ),
					'link' => home_url( '/goryashchie-tury/' ),
				),
				array(
					'q' => __( 'Сколько вариантов пришлёте?', 'zhiraf-ufa' ),
					'a' => __( 'Обычно 2–4 релевантных пакета.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Офис в Уфе?', 'zhiraf-ufa' ),
					'a' => __( 'ул. Гагарина, 74/1 — контакты на сайте.', 'zhiraf-ufa' ),
					'link' => home_url( '/kontakty/' ),
				),
			),
		),
		'egipet/hurgada'  => array(
			'country'       => 'egipet',
			'country_name'  => __( 'Египет', 'zhiraf-ufa' ),
			'resort'        => 'hurgada',
			'h1'            => __( 'Туры в Хургаду из Уфы', 'zhiraf-ufa' ),
			'title'         => __( 'Хургада из Уфы — туры | «Жираф»', 'zhiraf-ufa' ),
			'description'   => __( 'Хургада с вылетом из Уфы: подбор пакета, перелёт и отель без прайса «от».', 'zhiraf-ufa' ),
			'image'         => 'dest-egypt.jpg',
			'image_alt'     => __( 'Хургада, Красное море', 'zhiraf-ufa' ),
			'hero_lead'     => __( 'Хургада из Уфы — 853 показа/мес. — сильнее, чем соседний Шарм по региональному спросу. Родитель: туры в Египет из Уфы (1983).', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Хургада, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Что такое Хургада для туриста из Уфы? Курорт на Красном море с линейкой отелей «всё включено»; пакет из Уфы включает перелёт и проживание по условиям оператора. Менеджер сравнивает 2–4 варианта на ваши даты.', 'zhiraf-ufa' ),
			'sections'      => array(
				array(
					'h2' => __( 'Подбор пакета', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'Питание AI/UAI, трансфер, страховка — по voucher. Всё включено из Уфы — 735 показов: %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/vse-vklyucheno/' ) ) . '">' . esc_html__( 'страница формата', 'zhiraf-ufa' ) . '</a>'
						),
					),
				),
			),
			'faq'           => array(
				array(
					'q' => __( 'Шарм или Хургада?', 'zhiraf-ufa' ),
					'a' => __( 'Шарм-эль-Шейх (233 показа) — другой формат бухт.', 'zhiraf-ufa' ),
					'link' => zhiraf_ufa_resort_url( 'egipet', 'sharm-el-sheyh' ),
				),
				array(
					'q' => __( 'С детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Подбираем отели с инфраструктурой под семью.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/tury-s-detmi-iz-ufy/' ),
				),
				array(
					'q' => __( 'Горящие?', 'zhiraf-ufa' ),
					'a' => __( 'Проверяем актуальность при подборе.', 'zhiraf-ufa' ),
					'link' => home_url( '/goryashchie-tury/' ),
				),
				array(
					'q' => __( 'Офис?', 'zhiraf-ufa' ),
					'a' => __( 'Контакты и карта — на сайте.', 'zhiraf-ufa' ),
					'link' => home_url( '/kontakty/' ),
				),
			),
		),
		'egipet/sharm-el-sheyh' => array(
			'country'       => 'egipet',
			'country_name'  => __( 'Египет', 'zhiraf-ufa' ),
			'resort'        => 'sharm-el-sheyh',
			'h1'            => __( 'Туры в Шарм-эль-Шейх из Уфы', 'zhiraf-ufa' ),
			'title'         => __( 'Шарм-эль-Шейх из Уфы — туры | «Жираф»', 'zhiraf-ufa' ),
			'description'   => __( 'Шарм-эль-Шейх с вылетом из Уфы: подбор пакета без цен «от» на сайте.', 'zhiraf-ufa' ),
			'image'         => 'dest-egypt.jpg',
			'image_alt'     => __( 'Шарм-эль-Шейх', 'zhiraf-ufa' ),
			'hero_lead'     => __( 'Шарм-эль-Шейх из Уфы — 233 показа/мес. Альтернатива — Хургада (853). Хаб: туры в Египет из Уфы (1983).', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Шарм-эль-Шейх, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Шарм из Уфы — кому подходит? Курорт с развитой отельной базой на Синае; часто выбирают за пляжи и дайвинг-инфраструктуру. Перелёт и отель — в пакете оператора из Уфы.', 'zhiraf-ufa' ),
			'sections'      => array(),
			'faq'           => array(
				array(
					'q' => __( 'Хургада или Шарм?', 'zhiraf-ufa' ),
					'a' => __( 'Обсудим в заявке; Хургада — 853 показа в Уфе.', 'zhiraf-ufa' ),
					'link' => zhiraf_ufa_resort_url( 'egipet', 'hurgada' ),
				),
				array(
					'q' => __( 'Всё включено?', 'zhiraf-ufa' ),
					'a' => __( 'Частый формат отелей — уточняем по пакету.', 'zhiraf-ufa' ),
					'link' => home_url( '/vse-vklyucheno/' ),
				),
				array(
					'q' => __( 'Горящие пакеты?', 'zhiraf-ufa' ),
					'a' => __( 'См. горящие туры из Уфы.', 'zhiraf-ufa' ),
					'link' => home_url( '/goryashchie-tury/' ),
				),
				array(
					'q' => __( 'Как купить в Уфе?', 'zhiraf-ufa' ),
					'a' => __( 'Квиз → 2–4 варианта → договор.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
				),
			),
		),
		'vietnam/nyachang' => array(
			'country'       => 'vietnam',
			'country_name'  => __( 'Вьетнам', 'zhiraf-ufa' ),
			'resort'        => 'nyachang',
			'h1'            => __( 'Туры в Нячанг из Уфы', 'zhiraf-ufa' ),
			'title'         => __( 'Нячанг из Уфы — туры | «Жираф»', 'zhiraf-ufa' ),
			'description'   => __( 'Нячанг с вылетом из Уфы: пакетный тур без цен «от» на сайте.', 'zhiraf-ufa' ),
			'image'         => 'dest-vietnam.jpg',
			'image_alt'     => __( 'Нячанг, Вьетнам', 'zhiraf-ufa' ),
			'hero_lead'     => __( 'Нячанг из Уфы — 481 показ/мес. Сравнение с Фукуоком (386) — в заявке. Хаб: туры во Вьетнам из Уфы (614).', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Нячанг, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Нячанг для туристов из Уфы: курорт на побережье Южного Вьетнама; пакет из Уфы — перелёт, отель, часто AI.', 'zhiraf-ufa' ),
			'sections'      => array(),
			'faq'           => array(
				array(
					'q' => __( 'Нячанг или Фукуок?', 'zhiraf-ufa' ),
					'a' => __( 'Фукуок — 386 показов; островной формат.', 'zhiraf-ufa' ),
					'link' => zhiraf_ufa_resort_url( 'vietnam', 'fukuok' ),
				),
				array(
					'q' => __( 'Всё включено?', 'zhiraf-ufa' ),
					'a' => __( 'Часто AI — уточняем по отелю.', 'zhiraf-ufa' ),
					'link' => home_url( '/vse-vklyucheno/' ),
				),
				array(
					'q' => __( 'Горящие?', 'zhiraf-ufa' ),
					'a' => __( 'См. горящие туры.', 'zhiraf-ufa' ),
					'link' => home_url( '/goryashchie-tury/' ),
				),
				array(
					'q' => __( 'Офис в Уфе?', 'zhiraf-ufa' ),
					'a' => __( 'ул. Гагарина, 74/1.', 'zhiraf-ufa' ),
					'link' => home_url( '/kontakty/' ),
				),
			),
		),
		'vietnam/fukuok'  => array(
			'country'       => 'vietnam',
			'country_name'  => __( 'Вьетнам', 'zhiraf-ufa' ),
			'resort'        => 'fukuok',
			'h1'            => __( 'Туры на Фукуок из Уфы', 'zhiraf-ufa' ),
			'title'         => __( 'Фукуок из Уфы — туры | «Жираф»', 'zhiraf-ufa' ),
			'description'   => __( 'Фукуок с вылетом из Уфы: подбор пакета в офисе на Гагарина.', 'zhiraf-ufa' ),
			'image'         => 'dest-vietnam.jpg',
			'image_alt'     => __( 'Фукуок, Вьетнам', 'zhiraf-ufa' ),
			'hero_lead'     => __( 'Фукуок из Уфы — 386 показов/мес. Островной пляжный формат; перелёт длиннее, чем в Турцию — обсуждаем до брони. Хаб: туры во Вьетнам из Уфы (614).', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Фукуок, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Фукуок из Уфы: островной отдых; пакет включает перелёт и проживание по условиям оператора. Сравним с Нячангом (193) в подборе.', 'zhiraf-ufa' ),
			'sections'      => array(),
			'faq'           => array(
				array(
					'q' => __( 'Фукуок или Нячанг?', 'zhiraf-ufa' ),
					'a' => __( 'Нячанг — 481 показ; побережье материка.', 'zhiraf-ufa' ),
					'link' => zhiraf_ufa_resort_url( 'vietnam', 'nyachang' ),
				),
				array(
					'q' => __( 'Сколько дней оптимально?', 'zhiraf-ufa' ),
					'a' => __( 'Зависит от перелёта; обычно от 10–12 ночей.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Всё включено?', 'zhiraf-ufa' ),
					'a' => __( 'По коду отеля в пакете.', 'zhiraf-ufa' ),
					'link' => home_url( '/vse-vklyucheno/' ),
				),
				array(
					'q' => __( 'Горящие?', 'zhiraf-ufa' ),
					'a' => __( 'Актуальность — при подборе.', 'zhiraf-ufa' ),
					'link' => home_url( '/goryashchie-tury/' ),
				),
			),
		),
	);
}

/**
 * @param string $country Country slug.
 * @param string $resort  Resort slug.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_get_resort_landing( $country, $resort ) {
	$country = sanitize_title( $country );
	$resort  = sanitize_title( $resort );
	$key     = $country . '/' . $resort;
	$data    = zhiraf_ufa_resort_landing_data();
	return isset( $data[ $key ] ) ? $data[ $key ] : null;
}

/**
 * Render resort landing.
 *
 * @param string $country Country.
 * @param string $resort  Resort.
 */
function zhiraf_ufa_render_resort_landing( $country, $resort ) {
	$resort_data = zhiraf_ufa_get_resort_landing( $country, $resort );
	if ( ! $resort_data ) {
		return;
	}
	$template = ZHIRAF_UFA_DIR . '/templates/resort-landing.php';
	if ( is_readable( $template ) ) {
		include $template;
	}
}

/**
 * Hub links for catalog / footer.
 *
 * @return array<int,array{label:string,url:string,wordstat?:int}>
 */
function zhiraf_ufa_resort_hub_links() {
	return array(
		array(
			'label'    => __( 'Анталья', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'turciya', 'antalya' ),
			'wordstat' => 344,
		),
		array(
			'label'    => __( 'Хургада', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'egipet', 'hurgada' ),
			'wordstat' => 853,
		),
		array(
			'label'    => __( 'Шарм-эль-Шейх', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'egipet', 'sharm-el-sheyh' ),
			'wordstat' => 233,
		),
		array(
			'label'    => __( 'Нячанг', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'vietnam', 'nyachang' ),
			'wordstat' => 481,
		),
		array(
			'label'    => __( 'Фукуок', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'vietnam', 'fukuok' ),
			'wordstat' => 386,
		),
	);
}
