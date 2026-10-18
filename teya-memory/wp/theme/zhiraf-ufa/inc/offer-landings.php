<?php
/**
 * Offer landings: /goryashchie-tury/, /vse-vklyucheno/.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Offer page slugs.
 *
 * @return string[]
 */
function zhiraf_ufa_offer_landing_slugs() {
	return array( 'goryashchie-tury', 'vse-vklyucheno' );
}

/**
 * Offer landing content (page-content-pack).
 *
 * @return array<string,array<string,mixed>>
 */
function zhiraf_ufa_offer_landing_data() {
	return array(
		'goryashchie-tury' => array(
			'slug'          => 'goryashchie-tury',
			'h1'            => __( 'Горящие туры из Уфы', 'zhiraf-ufa' ),
			'title'         => __( 'Горящие туры из Уфы — подбор пакетов | «Жираф»', 'zhiraf-ufa' ),
			'description'   => __( 'Запрос «горящие туры из Уфы» — поможем найти актуальные пакеты с вылетом из города. Без цен «от» на сайте: считаем под ваши даты. Квиз и офис на Гагарина.', 'zhiraf-ufa' ),
			'eyebrow'       => __( 'Вылет из Уфы · без прайс-борда', 'zhiraf-ufa' ),
			'hero_lead'     => __( 'По данным поиска в Уфе тысячи раз в месяц ищут горящие туры из Уфы (3024 показа/мес.) — понимаем, что важны даты и скорость ответа. Оставьте заявку или позвоните: подберём пакет у туроператоров, объясним состав и не будем обещать «самую низкую цену на рынке».', 'zhiraf-ufa' ),
			'image'         => 'hero-turkey.jpg',
			'image_alt'     => __( 'Пляжный отдых, вылет из Уфы', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Горящий тур, вылет из Уфы', 'zhiraf-ufa' ),
			'cta_primary'   => __( 'Подобрать горящий тур', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Что такое горящий тур из Уфы? Это пакетный тур с близкими датами вылета из Уфы, когда у туроператора остаются места на рейсе или в отеле. Состав услуг — как у обычной путёвки: перелёт, проживание, трансфер и питание по коду в договоре. Мы не публикуем «завлекающие» суммы на сайте — менеджер проверяет актуальность и предлагает 2–4 варианта под ваш запрос.', 'zhiraf-ufa' ),
			'sections'      => array(
				array(
					'h2' => __( 'Как мы ищем горящие пакеты', 'zhiraf-ufa' ),
					'paragraphs' => array(
						__( 'Уточняем окно вылета (часто — ближайшие 3–14 дней), состав путешественников и ориентир по бюджету на человека в квизе. Сверяем рейсы из Уфы и доступность отелей у операторов — без выдуманных «осталось 2 места».', 'zhiraf-ufa' ),
						__( 'Присылаем 2–4 релевантных варианта с понятным списком услуг. Перед оплатой — договор и voucher; можно встретиться в офисе: ул. Юрия Гагарина, 74/1, офис 3.', 'zhiraf-ufa' ),
						sprintf(
							/* translators: %1$s %2$s %3$s %4$s: link markup */
							__( 'Направления с вылетом из Уфы — %1$s, %2$s, %3$s и другие из %4$s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/tour/turciya/' ) ) . '">' . esc_html__( 'Турция', 'zhiraf-ufa' ) . '</a>',
							'<a href="' . esc_url( home_url( '/tour/egipet/' ) ) . '">' . esc_html__( 'Египет', 'zhiraf-ufa' ) . '</a>',
							'<a href="' . esc_url( home_url( '/tour/oae/' ) ) . '">' . esc_html__( 'ОАЭ', 'zhiraf-ufa' ) . '</a>',
							'<a href="' . esc_url( home_url( '/tour/' ) ) . '">' . esc_html__( 'каталога', 'zhiraf-ufa' ) . '</a>'
						),
					),
				),
				array(
					'h2' => __( 'Куда чаще уезжают «в последний момент» из Уфы', 'zhiraf-ufa' ),
					'lead' => __( 'Выбор зависит от сезона и расписания чартеров — не гарантируем наличие конкретной страны на каждую дату.', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'Турция — короткий перелёт, формат «всё включено». %s', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/tour/turciya/' ) ) . '">' . esc_html__( 'Туры в Турцию', 'zhiraf-ufa' ) . '</a>'
						),
						sprintf(
							__( 'Египет — Красное море круглый год; Хургада и Шарм — отдельные подборы. %s', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/tour/egipet/' ) ) . '">' . esc_html__( 'Туры в Египет', 'zhiraf-ufa' ) . '</a>'
						),
						sprintf(
							__( 'ОАЭ — зимний спрос; город и пляж в одной поездке. %s', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/tour/oae/' ) ) . '">' . esc_html__( 'Туры в ОАЭ', 'zhiraf-ufa' ) . '</a>'
						),
						sprintf(
							__( 'Нужен спокойный пляж без срочности? Смотрите туры «всё включено» из Уфы — 735 показов в месяц по этому запросу в Уфе: %s', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/vse-vklyucheno/' ) ) . '">' . esc_html__( 'всё включено из Уфы', 'zhiraf-ufa' ) . '</a>'
						),
					),
				),
			),
			'faq'           => array(
				array(
					'q' => __( 'Почему на сайте нет цен на горящие туры?', 'zhiraf-ufa' ),
					'a' => __( 'Стоимость меняется по часам: рейс, отель, питание и оператор. Мы считаем пакет под ваш запрос, чтобы цифра была из договора, а не маркетинговой.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Как быстро вы отвечаете?', 'zhiraf-ufa' ),
					'a' => __( 'В рабочее время агентства: Пн–Пт 10:00–19:00, Сб 11:00–15:00. Вне часов — оставьте заявку, свяжемся в ближайший рабочий день.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Горящий тур — это всегда дешевле?', 'zhiraf-ufa' ),
					'a' => __( 'Не обязательно: иногда выгоднее планировать заранее. Мы сравниваем пакет целиком и честно говорим, если срочный вылет не лучший вариант.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Нужна ли предоплата сразу?', 'zhiraf-ufa' ),
					'a' => __( 'Условия — по правилам выбранного туроператора; обсуждаем до подписания договора.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
				),
				array(
					'q' => __( 'Можно ли с детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Да. В квизе укажите состав семьи — учтём перелёт и отель.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/tury-s-detmi-iz-ufy/' ),
				),
			),
		),
		'vse-vklyucheno'   => array(
			'slug'          => 'vse-vklyucheno',
			'h1'            => __( 'Туры «всё включено» из Уфы', 'zhiraf-ufa' ),
			'title'         => __( 'Всё включено из Уфы — подбор тура | «Жираф»', 'zhiraf-ufa' ),
			'description'   => __( 'Пакеты AI и UAI с вылетом из Уфы: перелёт, отель, трансфер и питание — что входит, фиксируем в договоре. Без цен «от» на сайте.', 'zhiraf-ufa' ),
			'eyebrow'       => __( 'AI / UAI · пакет из Уфы', 'zhiraf-ufa' ),
			'hero_lead'     => __( 'Запрос «всё включено из Уфы» — 735 показов в месяц по Wordstat: семьи и пары хотят понятный бюджет на отдых. Мы подбираем пакетные туры с вылетом из Уфы и заранее проговариваем, что входит в цену, а что нет.', 'zhiraf-ufa' ),
			'image'         => 'dest-turkey.jpg',
			'image_alt'     => __( 'Турция, отель с питанием всё включено', 'zhiraf-ufa' ),
			'quiz_prefill'  => __( 'Всё включено, вылет из Уфы', 'zhiraf-ufa' ),
			'cta_primary'   => __( 'Подобрать тур «всё включено»', 'zhiraf-ufa' ),
			'geo_answer'    => __( 'Что значит «всё включено» в туре из Уфы? Обычно в пакет входят перелёт из Уфы, проживание, групповой трансфер и питание по коду оператора (чаще AI или UAI). Точный перечень — в договоре и voucher; алкоголь, экскурсии и отдельные услуги могут оплачиваться дополнительно.', 'zhiraf-ufa' ),
			'sections'      => array(
				array(
					'h2' => __( 'AI, UAI и не только — простыми словами', 'zhiraf-ufa' ),
					'paragraphs' => array(
						__( 'AI (All Inclusive) — как правило, завтрак, обед, ужин и локальные напитки по правилам отеля. UAI (Ultra All Inclusive) — расширенный набор услуг и напитков; состав зависит от сети отеля. BB/HB — только завтрак или завтрак+ужин; такой пакет тоже можно подобрать из Уфы, если не нужен полный AI.', 'zhiraf-ufa' ),
						sprintf(
							__( 'Подробный разбор — в статье %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ) ) . '">«Что входит в тур всё включено»</a>'
						),
						sprintf(
							__( 'Популярные направления: %1$s (туры в Турцию из Уфы — 1117 показов, туры Турция — 2589), %2$s (858 / 2071), %3$s (262 / 860).', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/tour/turciya/' ) ) . '">' . esc_html__( 'Турция', 'zhiraf-ufa' ) . '</a>',
							'<a href="' . esc_url( home_url( '/tour/egipet/' ) ) . '">' . esc_html__( 'Египет', 'zhiraf-ufa' ) . '</a>',
							'<a href="' . esc_url( home_url( '/tour/vietnam/' ) ) . '">' . esc_html__( 'Вьетнам', 'zhiraf-ufa' ) . '</a>'
						),
					),
				),
				array(
					'h2' => __( 'Кому подходит формат «всё включено»', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'Семья с детьми — предсказуемое питание; см. %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/blog/tury-s-detmi-iz-ufy/' ) ) . '">' . esc_html__( 'туры с детьми', 'zhiraf-ufa' ) . '</a>'
						),
						__( 'Пара — отдых без постоянного выбора ресторанов.', 'zhiraf-ufa' ),
						sprintf(
							__( 'Первая заграница — проще планировать бюджет; офис в Уфе — %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/kontakty/' ) ) . '">' . esc_html__( 'контакты', 'zhiraf-ufa' ) . '</a>'
						),
					),
				),
			),
			'faq'           => array(
				array(
					'q' => __( 'Входит ли перелёт из Уфы в «всё включено»?', 'zhiraf-ufa' ),
					'a' => __( 'В пакетном туре — да, если так указано в предложении оператора; проверяем до оплаты.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Всегда ли алкоголь включён?', 'zhiraf-ufa' ),
					'a' => __( 'Зависит от отеля и кода питания; уточняем по конкретному пакету.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Чем ваш подбор отличается от агрегатора?', 'zhiraf-ufa' ),
					'a' => __( 'Сверяем состав услуг и сопровождаем договор в Уфе; не публикуем фиктивные «от».', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Есть ли горящие варианты «всё включено»?', 'zhiraf-ufa' ),
					'a' => __( 'Иногда — см. горящие туры из Уфы (3024 показов по смежному запросу).', 'zhiraf-ufa' ),
					'link' => home_url( '/goryashchie-tury/' ),
				),
			),
		),
	);
}

/**
 * @param string $slug Offer slug.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_get_offer_landing( $slug ) {
	$data = zhiraf_ufa_offer_landing_data();
	return isset( $data[ $slug ] ) ? $data[ $slug ] : null;
}

/**
 * Render offer landing.
 *
 * @param string $slug Slug.
 */
function zhiraf_ufa_render_offer_landing( $slug ) {
	$offer = zhiraf_ufa_get_offer_landing( $slug );
	if ( ! $offer ) {
		return;
	}
	$template = ZHIRAF_UFA_DIR . '/templates/offer-landing.php';
	if ( is_readable( $template ) ) {
		include $template;
	}
}
