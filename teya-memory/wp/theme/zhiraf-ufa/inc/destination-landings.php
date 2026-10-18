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
			'hero_lead_extended' => __( 'Турция — главный пляжный вектор из Уфы: «туры в Турцию из Уфы» — 1117 показов/мес., «туры Турция» — 2589. Подберём пакет с перелётом, отелем и питанием без прайса на сайте.', 'zhiraf-ufa' ),
			'geo_answer'     => __( 'Сколько лететь из Уфы в Турцию? Время в пути зависит от аэропорта прилёта и типа рейса; обычно перелёт короче, чем на дальние направления. Точное расписание — у туроператора на ваши даты; мы проверяем при подборе пакета из Уфы.', 'zhiraf-ufa' ),
			'for_who'        => __( 'Семьи с детьми — короткий перелёт и отели с AI; пары — отдых «всё включено»; те, кто сравнивает с Египтом — статья в блоге.', 'zhiraf-ufa' ),
			'resort_links'   => array(
				array(
					'label'    => __( 'Анталья', 'zhiraf-ufa' ),
					'url'      => home_url( '/tour/turciya/antalya/' ),
					'wordstat' => 122,
				),
			),
			'extra_sections' => array(
				array(
					'h2' => __( 'Курорты: что обсудим в заявке', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'анталья из уфы — 122 показа/мес. Подробный подбор — %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/tour/turciya/antalya/' ) ) . '">' . esc_html__( 'туры в Анталью из Уфы', 'zhiraf-ufa' ) . '</a>'
						),
						__( 'кемер из уфы — 82 показа/мес. Бухта, горы и пляжи; часто выбирают за спокойную атмосферу и прогулочную набережную. Обсудим отель и трансфер в пакете из Уфы.', 'zhiraf-ufa' ),
						__( 'аланья из уфы — 58 показов/мес. Длинные пляжи, подходит семьям; уточним питание AI/UAI и перелёт на ваши даты.', 'zhiraf-ufa' ),
						sprintf(
							__( 'Хабы: %1$s · %2$s', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/tour/' ) ) . '">' . esc_html__( 'Все направления', 'zhiraf-ufa' ) . '</a>',
							'<a href="' . esc_url( home_url( '/vse-vklyucheno/' ) ) . '">' . esc_html__( 'Всё включено', 'zhiraf-ufa' ) . '</a>'
						),
					),
				),
				array(
					'h2' => __( 'Турция с детьми из Уфы', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'Запрос «туры с детьми из Уфы» — 20 показов/мес., но для семей это ключевой критерий: ночной перелёт, детское меню, бассейн и клуб. Разбор — %s; в квизе выберите «Семья с детьми».', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/blog/tury-s-detmi-iz-ufy/' ) ) . '">BT-04</a>'
						),
					),
				),
			),
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
			'hero_lead_extended' => __( 'Запрос «туры в ОАЭ из Уфы» — 166 показов/мес. Формат: пляжные отели эмиратов и городской отдых. Подберём пакет под ваш бюджет в квизе.', 'zhiraf-ufa' ),
			'geo_answer'     => __( 'Когда едут в ОАЭ из Уфы? Часто зимой и в межсезонье — тёплая погода и короткий «перезимовочный» отпуск. Точные даты и рейсы сверяем с туроператором при подборе.', 'zhiraf-ufa' ),
			'for_who'        => __( 'Пары и семьи, кому важен сервис и инфраструктура; зимний отдых из Уфы.', 'zhiraf-ufa' ),
			'extra_sections' => array(
				array(
					'h2' => __( 'Дубай из Уфы (87 показов/мес.)', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'Отдельного URL нет — ниже порога 100: обсуждаем Дубай в заявке как часть ОАЭ. Сравнение питания — %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/vse-vklyucheno/' ) ) . '">' . esc_html__( 'всё включено из Уфы', 'zhiraf-ufa' ) . '</a>'
						),
					),
				),
			),
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
			'hero_lead_extended' => __( 'Туры в Египет из Уфы — 858 показов, «туры Египет» — 2071. Красное море круглый год; сравнение с Турцией — в блоге.', 'zhiraf-ufa' ),
			'geo_answer'     => __( 'Куда на море из Уфы в Египет? Чаще выбирают Хургаду и Шарм-эль-Шейх — у каждого курорта свой характер отелей и бухт. Мы подбираем пакет с перелётом из Уфы и фиксируем услуги в договоре.', 'zhiraf-ufa' ),
			'for_who'        => __( 'Семьи, пары и первая заграница — часто сравнивают с Турцией по формату отдыха.', 'zhiraf-ufa' ),
			'resort_links'   => array(
				array(
					'label'    => __( 'Хургада', 'zhiraf-ufa' ),
					'url'      => home_url( '/tour/egipet/hurgada/' ),
					'wordstat' => 327,
				),
				array(
					'label'    => __( 'Шарм-эль-Шейх', 'zhiraf-ufa' ),
					'url'      => home_url( '/tour/egipet/sharm-el-sheyh/' ),
					'wordstat' => 242,
				),
			),
			'extra_sections' => array(
				array(
					'h2' => __( 'С детьми (20 показов — секция)', 'zhiraf-ufa' ),
					'paragraphs' => array(
						sprintf(
							__( 'Семейный отдых: перелёт, питание AI, детская инфраструктура — %s.', 'zhiraf-ufa' ),
							'<a href="' . esc_url( home_url( '/blog/tury-s-detmi-iz-ufy/' ) ) . '">BT-04</a>'
						),
					),
				),
			),
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
			'hero_lead'      => __( 'Пхукет и Паттайя — секции на этой странице; честно предупредим о длительном перелёте из Уфы и подберём маршрут.', 'zhiraf-ufa' ),
			'hero_lead_extended' => __( '«Тур в Таиланд из Уфы» — 84 показа/мес.; Паттайя (119) и Пхукет (58 + 55 по двум фразам) — секции на этой странице, без отдельного URL. Перелёт дальний — обсуждаем до брони.', 'zhiraf-ufa' ),
			'geo_answer'     => __( 'Долго ли лететь из Уфы в Таиланд? Перелёт дальний; закладываем время в пути и стыковки при планировании. Мы честно обсуждаем это до бронирования — особенно с детьми.', 'zhiraf-ufa' ),
			'for_who'        => __( 'Пары и опытные путешественники, готовые к длинному перелёту ради тропиков.', 'zhiraf-ufa' ),
			'extra_sections' => array(
				array(
					'h2' => __( 'Пхукет из Уфы (58 + 55 показов — секция, без URL)', 'zhiraf-ufa' ),
					'paragraphs' => array(
						__( 'Отдельный slug /tour/tailand/phuket/ не в карте URL: коммерческие фразы ниже порога 100. Подбор островного отдыха — в заявке и квизе; сравним с Паттайей по инфраструктуре и перелёту.', 'zhiraf-ufa' ),
					),
				),
				array(
					'h2' => __( 'Паттайя из Уфы (119 показов/мес. — секция, без URL)', 'zhiraf-ufa' ),
					'paragraphs' => array(
						__( 'Ниже порога 100: Паттайю обсуждаем в заявке как альтернативу Пхукету — городская инфраструктура и пляж. Не обещаем «лучший курорт» — подбираем под запрос.', 'zhiraf-ufa' ),
					),
				),
			),
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
					'q' => __( 'Пхукет или Паттайя?', 'zhiraf-ufa' ),
					'a' => __( 'Сравним в подборе на этой странице (секции выше); отдельного URL для Пхукета нет.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'С детьми?', 'zhiraf-ufa' ),
					'a' => __( 'Запрос «туры с детьми из Уфы» — 32 показа; разбор — в блоге BT-04.', 'zhiraf-ufa' ),
					'link' => home_url( '/blog/tury-s-detmi-iz-ufy/' ),
				),
				array(
					'q' => __( 'Всё включено?', 'zhiraf-ufa' ),
					'a' => __( 'Подберём пакет AI/UAI под отель — см. страницу «всё включено».', 'zhiraf-ufa' ),
					'link' => home_url( '/vse-vklyucheno/' ),
				),
				array(
					'q' => __( 'Горящие?', 'zhiraf-ufa' ),
					'a' => __( '«Горящие туры из Уфы» — 3480 показов/мес.; актуальность — при подборе.', 'zhiraf-ufa' ),
					'link' => home_url( '/goryashchie-tury/' ),
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
			'hero_lead_extended' => __( 'Туры во Вьетнам из Уфы — 614 показов/мес. Пляжный отдых и тёплое море; перелёт из Уфы — в пакете оператора.', 'zhiraf-ufa' ),
			'geo_answer'     => __( 'Какие курорты Вьетнама из Уфы? Чаще запрашивают Нячанг (481) и Фукуок (386) — у нас отдельные страницы при спросе ≥100.', 'zhiraf-ufa' ),
			'for_who'        => __( 'Пары и путешественники, которым важны природа и пляж, готовые к перелёту с пересадкой.', 'zhiraf-ufa' ),
			'resort_links'   => array(
				array(
					'label'    => __( 'Нячанг', 'zhiraf-ufa' ),
					'url'      => home_url( '/tour/vietnam/nyachang/' ),
					'wordstat' => 481,
				),
				array(
					'label'    => __( 'Фукуок', 'zhiraf-ufa' ),
					'url'      => home_url( '/tour/vietnam/fukuok/' ),
					'wordstat' => 386,
				),
			),
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
