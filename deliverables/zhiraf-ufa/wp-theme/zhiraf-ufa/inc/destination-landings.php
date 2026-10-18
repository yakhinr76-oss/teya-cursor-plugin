<?php
/**
 * Destination landing pages (/tour/{slug}/).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Landing slugs served by page-{slug}.php templates.
 *
 * @return string[]
 */
function zhiraf_ufa_destination_landing_slugs() {
	return array( 'turciya', 'oae', 'egipet', 'tailand', 'vietnam' );
}

/**
 * Content pack for destination landings (url-map + 07-content-briefs).
 *
 * @return array<string,array<string,mixed>>
 */
function zhiraf_ufa_destination_landing_data() {
	return array(
		'turciya'  => array(
			'name'           => __( 'Турция', 'zhiraf-ufa' ),
			'h1'             => __( 'Туры в Турцию из Уфы', 'zhiraf-ufa' ),
			'title'          => __( 'Турция из Уфы — туры «всё включено» | Жираф', 'zhiraf-ufa' ),
			'description'    => __( 'Подбор тура в Турцию с вылетом из Уфы: курорты, питание AI/UAI, перелёт. Заявка без обязательств.', 'zhiraf-ufa' ),
			'image'          => 'dest-turkey.jpg',
			'image_alt'      => __( 'Турция — курортный отдых', 'zhiraf-ufa' ),
			'hero_lead'      => __( 'Пляжный отдых «всё включено» с вылетом из Уфы — подберём курорт и отель под состав семьи и даты, без фиктивных цен «от».', 'zhiraf-ufa' ),
			'for_who'        => __( 'Семьи с детьми, пары и те, кто ищет понятный пляжный формат с коротким перелётом из Уфы.', 'zhiraf-ufa' ),
			'we_clarify'     => __( 'Сезон, курорт (Анталья, Алания и др.), питание AI/UAI, перелёт и трансфер, номер и доплаты — всё фиксируем в договоре.', 'zhiraf-ufa' ),
			'season'         => __( 'Весна и осень — комфортное море; летом высокий спрос; зимой — горнолыжные и экскурсионные форматы по запросу. Погоду не гарантируем — ориентируем по статистике сезона.', 'zhiraf-ufa' ),
			'quiz_prefill'   => __( 'Турция, вылет из Уфы', 'zhiraf-ufa' ),
			'blog_links'     => array(
				array(
					'url'   => home_url( '/blog/turciya-ili-egipet-iz-ufy/' ),
					'label' => __( 'Турция или Египет из Уфы', 'zhiraf-ufa' ),
				),
				array(
					'url'   => home_url( '/blog/tury-s-detmi-iz-ufy/' ),
					'label' => __( 'Туры с детьми из Уфы', 'zhiraf-ufa' ),
				),
			),
			'faq'            => array(
				array(
					'q' => __( 'Есть ли прямые рейсы из Уфы в Турцию?', 'zhiraf-ufa' ),
					'a' => __( 'Расписание меняется по сезону — проверяем актуальные варианты при подборе.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Что значит «всё включено» в Турции?', 'zhiraf-ufa' ),
					'a' => __( 'Состав пакета зависит от отеля и оператора — разбираем в статье и в договоре.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ),
				),
				array(
					'q' => __( 'Подойдёт ли Турция с детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Частый выбор семей из Уфы — учитываем перелёт, питание и инфраструктуру отеля.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Сколько стоит тур в Турцию из Уфы?', 'zhiraf-ufa' ),
					'a' => __( 'Зависит от дат, отеля и оператора — считаем персонально, без прайса «от» на сайте.', 'zhiraf-ufa' ),
				),
			),
		),
		'oae'      => array(
			'name'           => __( 'ОАЭ', 'zhiraf-ufa' ),
			'h1'             => __( 'Туры в ОАЭ из Уфы', 'zhiraf-ufa' ),
			'title'          => __( 'ОАЭ из Уфы — туры и подбор | Жираф', 'zhiraf-ufa' ),
			'description'    => __( 'Отдых в ОАЭ с вылетом из Уфы: подбор отеля и перелёта под ваши даты.', 'zhiraf-ufa' ),
			'image'          => 'dest-uae.jpg',
			'image_alt'      => __( 'ОАЭ — город и пляж', 'zhiraf-ufa' ),
			'hero_lead'      => __( 'Дубай и другие эмираты — сочетание пляжа и города. Подберём пакет с вылетом из Уфы под ваш бюджет и даты.', 'zhiraf-ufa' ),
			'for_who'        => __( 'Пары, семьи и те, кто хочет зимний или межсезонный отдых с перелётом из Уфы.', 'zhiraf-ufa' ),
			'we_clarify'     => __( 'Эмират, отель, питание, перелёт, трансфер, страховка и доплаты — без скрытых обещаний.', 'zhiraf-ufa' ),
			'season'         => __( 'С октября по апрель — традиционный высокий спрос на ОАЭ; летом жарче — обсуждаем комфортные варианты.', 'zhiraf-ufa' ),
			'quiz_prefill'   => __( 'ОАЭ, вылет из Уфы', 'zhiraf-ufa' ),
			'blog_links'     => array(
				array(
					'url'   => home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ),
					'label' => __( 'Что входит в «всё включено»', 'zhiraf-ufa' ),
				),
			),
			'faq'            => array(
				array(
					'q' => __( 'Нужна ли виза в ОАЭ?', 'zhiraf-ufa' ),
					'a' => __( 'Правила зависят от гражданства и срока — уточняем при подборе; это не юридическая консультация.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/dokumenty-dlya-zagranicy-iz-ufy/' ),
				),
				array(
					'q' => __( 'Можно ли совместить пляж и экскурсии?', 'zhiraf-ufa' ),
					'a' => __( 'Да, обсуждаем формат отдыха в пожеланиях к квизу.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Как купить тур в ОАЭ через «Жираф»?', 'zhiraf-ufa' ),
					'a' => __( 'Заявка или квиз → 2–4 варианта → договор. Подробно — в блоге.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
				),
			),
		),
		'egipet'   => array(
			'name'           => __( 'Египет', 'zhiraf-ufa' ),
			'h1'             => __( 'Туры в Египет из Уфы', 'zhiraf-ufa' ),
			'title'          => __( 'Египет из Уфы — туры на море | Жираф', 'zhiraf-ufa' ),
			'description'    => __( 'Египет с вылетом из Уфы: Хургада, Шарм — подбор пакета под семью или пару.', 'zhiraf-ufa' ),
			'image'          => 'dest-egypt.jpg',
			'image_alt'      => __( 'Египет — курорты Красного моря', 'zhiraf-ufa' ),
			'hero_lead'      => __( 'Красное море, отели с питанием и перелёт из Уфы — поможем выбрать курорт без давления и лишних вкладок.', 'zhiraf-ufa' ),
			'for_who'        => __( 'Семьи, пары и первая заграница — часто сравнивают с Турцией по формату отдыха.', 'zhiraf-ufa' ),
			'we_clarify'     => __( 'Курорт (Хургада, Шарм-эль-Шейх и др.), питание, перелёт, трансфер, экскурсии — только по договору.', 'zhiraf-ufa' ),
			'season'         => __( 'Круглый год море; зимой комфортнее для семей из Уфы. Погоду и температуру не гарантируем.', 'zhiraf-ufa' ),
			'quiz_prefill'   => __( 'Египет, вылет из Уфы', 'zhiraf-ufa' ),
			'blog_links'     => array(
				array(
					'url'   => home_url( '/blog/turciya-ili-egipet-iz-ufy/' ),
					'label' => __( 'Турция или Египет — сравнение', 'zhiraf-ufa' ),
				),
			),
			'faq'            => array(
				array(
					'q' => __( 'Чем Египет отличается от Турции для отдыха из Уфы?', 'zhiraf-ufa' ),
					'a' => __( 'Формат, перелёт и отели — сравниваем в статье без цен «от».', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/turciya-ili-egipet-iz-ufy/' ),
				),
				array(
					'q' => __( 'Подходит ли Египет с детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Да, подбираем отели с инфраструктурой под состав семьи.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Какие документы нужны?', 'zhiraf-ufa' ),
					'a' => __( 'Зависит от правил въезда — см. чек-лист в блоге.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/dokumenty-dlya-zagranicy-iz-ufy/' ),
				),
			),
		),
		'tailand'  => array(
			'name'           => __( 'Таиланд', 'zhiraf-ufa' ),
			'h1'             => __( 'Туры в Таиланд из Уфы', 'zhiraf-ufa' ),
			'title'          => __( 'Таиланд из Уфы — подбор тура | Жираф', 'zhiraf-ufa' ),
			'description'    => __( 'Таиланд с вылетом из Уфы: длительный перелёт — поможем с маршрутом и отелем.', 'zhiraf-ufa' ),
			'image'          => 'dest-thailand.jpg',
			'image_alt'      => __( 'Таиланд — тропический отдых', 'zhiraf-ufa' ),
			'hero_lead'      => __( 'Пхукет и другие курорты — честно предупредим о длительном перелёте из Уфы и подберём комфортный маршрут.', 'zhiraf-ufa' ),
			'for_who'        => __( 'Пары и опытные путешественники, готовые к длинному перелёту ради тропиков.', 'zhiraf-ufa' ),
			'we_clarify'     => __( 'Даты, курорт, питание, стыковки, багаж, страховка — обсуждаем до бронирования.', 'zhiraf-ufa' ),
			'season'         => __( 'Высокий сезон — ноябрь–март; в остальное время возможны осадки — без гарантий погоды.', 'zhiraf-ufa' ),
			'quiz_prefill'   => __( 'Таиланд, вылет из Уфы', 'zhiraf-ufa' ),
			'blog_links'     => array(
				array(
					'url'   => home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ),
					'label' => __( 'Состав пакетного тура', 'zhiraf-ufa' ),
				),
			),
			'faq'            => array(
				array(
					'q' => __( 'Сколько лететь из Уфы в Таиланд?', 'zhiraf-ufa' ),
					'a' => __( 'Обычно с пересадкой; точное время зависит от рейса — покажем варианты в подборе.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Нужна ли виза?', 'zhiraf-ufa' ),
					'a' => __( 'Правила меняются — уточняем на дату поездки; см. блог по документам.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/dokumenty-dlya-zagranicy-iz-ufy/' ),
				),
				array(
					'q' => __( 'Можно ли подобрать тур в офисе?', 'zhiraf-ufa' ),
					'a' => __( 'Да, ул. Гагарина, 74/1 — или начните с квиза на сайте.', 'zhiraf-ufa' ),
					'link' => home_url( '/kontakty/' ),
				),
			),
		),
		'vietnam'  => array(
			'name'           => __( 'Вьетнам', 'zhiraf-ufa' ),
			'h1'             => __( 'Туры во Вьетнам из Уфы', 'zhiraf-ufa' ),
			'title'          => __( 'Вьетнам из Уфы — туры | Жираф', 'zhiraf-ufa' ),
			'description'    => __( 'Вьетнам с вылетом из Уфы: Нячанг и другие курорты — консультация в офисе.', 'zhiraf-ufa' ),
			'image'          => 'dest-vietnam.jpg',
			'image_alt'      => __( 'Вьетнам — пляжи и природа', 'zhiraf-ufa' ),
			'hero_lead'      => __( 'Нячанг и другие курорты — подбор с учётом перелёта из Уфы и длительности поездки.', 'zhiraf-ufa' ),
			'for_who'        => __( 'Пары и путешественники, которым важны природа и пляж, готовые к перелёту с пересадкой.', 'zhiraf-ufa' ),
			'we_clarify'     => __( 'Курорт, отель, питание, перелёт, трансфер, виза — актуализируем при заявке.', 'zhiraf-ufa' ),
			'season'         => __( 'Сухой сезон на побережье — примерно декабрь–апрель; без гарантий климата.', 'zhiraf-ufa' ),
			'quiz_prefill'   => __( 'Вьетнам, вылет из Уфы', 'zhiraf-ufa' ),
			'blog_links'     => array(
				array(
					'url'   => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
					'label' => __( 'Как купить тур в Уфе', 'zhiraf-ufa' ),
				),
			),
			'faq'            => array(
				array(
					'q' => __( 'Есть ли чартеры из Уфы во Вьетнам?', 'zhiraf-ufa' ),
					'a' => __( 'Чаще пакеты со стыковочными рейсами — проверяем доступность на ваши даты.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Сколько дней оптимально?', 'zhiraf-ufa' ),
					'a' => __( 'Зависит от перелёта и бюджета — обычно от 10–12 ночей; обсудим в квизе.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Чем агентство отличается от сайта оператора?', 'zhiraf-ufa' ),
					'a' => __( 'Подбор нескольких вариантов и сопровождение в Уфе до договора.', 'zhiraf-ufa' ),
				),
			),
		),
	);
}

/**
 * Get landing config by slug.
 *
 * @param string $slug Destination slug.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_get_destination_landing( $slug ) {
	$data = zhiraf_ufa_destination_landing_data();
	return isset( $data[ $slug ] ) ? $data[ $slug ] : null;
}

/**
 * Render destination landing main content.
 *
 * @param string $slug Destination slug.
 */
function zhiraf_ufa_render_destination_landing( $slug ) {
	$dest = zhiraf_ufa_get_destination_landing( $slug );
	if ( ! $dest ) {
		return;
	}
	$template = ZHIRAF_UFA_DIR . '/templates/destination-landing.php';
	if ( is_readable( $template ) ) {
		include $template;
	}
}
