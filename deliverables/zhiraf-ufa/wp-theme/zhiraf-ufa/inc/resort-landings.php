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
		array( 'country' => 'tailand', 'resort' => 'phuket' ),
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
			'hero_lead'     => __( 'Анталья из Уфы — 122 показа в месяц по Wordstat: запрос стабильный у тех, кто уже определился с курортом Турции. Родительское направление — туры в Турцию (1117 показов).', 'zhiraf-ufa' ),
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
			'hero_lead'     => __( 'Хургада из Уфы — 327 показов/мес. — сильнее, чем соседний Шарм по региональному спросу. Родитель: Египет (858 показов).', 'zhiraf-ufa' ),
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
					'a' => __( 'Шарм-эль-Шейх (242 показа) — другой формат бухт.', 'zhiraf-ufa' ),
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
			'hero_lead'     => __( 'Шарм-эль-Шейх из Уфы — 242 показа/мес. Альтернатива — Хургада (327). Хаб: Египет.', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Шарм-эль-Шейх, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Шарм из Уфы — кому подходит? Курорт с развитой отельной базой на Синае; часто выбирают за пляжи и дайвинг-инфраструктуру. Перелёт и отель — в пакете оператора из Уфы.', 'zhiraf-ufa' ),
			'sections'      => array(),
			'faq'           => array(
				array(
					'q' => __( 'Хургада или Шарм?', 'zhiraf-ufa' ),
					'a' => __( 'Обсудим в заявке; Хургада — 327 показов в Уфе.', 'zhiraf-ufa' ),
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
		'tailand/phuket'  => array(
			'country'       => 'tailand',
			'country_name'  => __( 'Таиланд', 'zhiraf-ufa' ),
			'resort'        => 'phuket',
			'h1'            => __( 'Туры на Пхукет из Уфы', 'zhiraf-ufa' ),
			'title'         => __( 'Пхукет из Уфы — туры | «Жираф»', 'zhiraf-ufa' ),
			'description'   => __( 'Пхукет с вылетом из Уфы: перелёт, отель и питание — без прайса на сайте.', 'zhiraf-ufa' ),
			'image'         => 'dest-thailand.jpg',
			'image_alt'     => __( 'Пхукет, Таиланд', 'zhiraf-ufa' ),
			'hero_lead'     => __( 'Пхукет из Уфы — 125 показов — единственный курорт Таиланда с отдельным URL в Phase 1. Таиланд — хаб; Паттайя (49) — секция на странице страны.', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Пхукет, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Пхукет из Уфы — что в пакете? Перелёт до Таиланда, проживание на острове, трансфер и питание по коду оператора. Длительность перелёта обсуждаем заранее.', 'zhiraf-ufa' ),
			'sections'      => array(
				array(
					'h2' => __( 'Перелёт из Уфы', 'zhiraf-ufa' ),
					'paragraphs' => array(
						__( 'Обычно с пересадкой; точное время зависит от рейса — покажем варианты в подборе. Для семей это критично — учтём в квизе.', 'zhiraf-ufa' ),
					),
				),
			),
			'faq'           => array(
				array(
					'q' => __( 'Пхукет или Паттайя?', 'zhiraf-ufa' ),
					'a' => __( 'Сравним в подборе; Паттайя — 49 показов, без отдельного URL.', 'zhiraf-ufa' ),
					'link' => home_url( '/tour/tailand/' ),
				),
				array(
					'q' => __( 'Всё включено?', 'zhiraf-ufa' ),
					'a' => __( 'Зависит от отеля.', 'zhiraf-ufa' ),
					'link' => home_url( '/vse-vklyucheno/' ),
				),
				array(
					'q' => __( 'Горящие?', 'zhiraf-ufa' ),
					'a' => __( 'Горящие туры из Уфы — 3024 показов по смежному запросу.', 'zhiraf-ufa' ),
					'link' => home_url( '/goryashchie-tury/' ),
				),
				array(
					'q' => __( 'С детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Чек-лист в блоге.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/tury-s-detmi-iz-ufy/' ),
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
			'hero_lead'     => __( 'Нячанг из Уфы — 193 показа/мес. Сравнение с Фукуоком (183) — в заявке. Хаб: Вьетнам (262).', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Нячанг, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Нячанг для туристов из Уфы: курорт на побережье Южного Вьетнама; пакет из Уфы — перелёт, отель, часто AI.', 'zhiraf-ufa' ),
			'sections'      => array(),
			'faq'           => array(
				array(
					'q' => __( 'Нячанг или Фукуок?', 'zhiraf-ufa' ),
					'a' => __( 'Фукуок — 183 показа; островной формат.', 'zhiraf-ufa' ),
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
			'hero_lead'     => __( 'Фукуок из Уфы — 183 показа/мес. Островной пляжный формат; перелёт длиннее, чем в Турцию — обсуждаем до брони. Хаб: Вьетнам (262).', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Фукуок, вылет из Уфы', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Фукуок из Уфы: островной отдых; пакет включает перелёт и проживание по условиям оператора. Сравним с Нячангом (193) в подборе.', 'zhiraf-ufa' ),
			'sections'      => array(),
			'faq'           => array(
				array(
					'q' => __( 'Фукуок или Нячанг?', 'zhiraf-ufa' ),
					'a' => __( 'Нячанг — 193 показа; побережье материка.', 'zhiraf-ufa' ),
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
			'wordstat' => 122,
		),
		array(
			'label'    => __( 'Хургада', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'egipet', 'hurgada' ),
			'wordstat' => 327,
		),
		array(
			'label'    => __( 'Шарм-эль-Шейх', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'egipet', 'sharm-el-sheyh' ),
			'wordstat' => 242,
		),
		array(
			'label'    => __( 'Пхукет', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'tailand', 'phuket' ),
			'wordstat' => 125,
		),
		array(
			'label'    => __( 'Нячанг', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'vietnam', 'nyachang' ),
			'wordstat' => 193,
		),
		array(
			'label'    => __( 'Фукуок', 'zhiraf-ufa' ),
			'url'      => zhiraf_ufa_resort_url( 'vietnam', 'fukuok' ),
			'wordstat' => 183,
		),
	);
}
