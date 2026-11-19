<?php
/**
 * Intent checklist blocks (Wordstat-driven types + copy without tour-operator slang).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Intent cluster per hub slug (sync: teya-memory/.../10-intent-wave2-report.json).
 *
 * @return array<string,string>
 */
function zhiraf_ufa_intent_block_type_map() {
	return array(
		'turciya'    => 'kids',
		'oae'        => 'kids',
		'egipet'     => 'kids',
		'tailand'    => 'kids',
		'vietnam'    => 'kids',
		'kitaj'      => 'all_inclusive',
		'indiya'     => 'kids',
		'shri-lanka' => 'kids',
		'indoneziya' => 'couples',
		'maldivy'    => 'couples',
		'sejshely'   => 'couples',
		'uzbekistan' => 'excursion',
		'armeniya'   => 'excursion',
		'rossiya'    => 'excursion',
		'abkhaziya'  => 'kids',
	);
}

/**
 * Standard CTA label per intent cluster.
 *
 * @param string $type kids|couples|all_inclusive|excursion.
 * @return string
 */
function zhiraf_ufa_intent_block_cta_label( $type ) {
	$labels = array(
		'kids'           => __( 'Запросить 3 семейных отеля →', 'zhiraf-ufa' ),
		'couples'        => __( 'Запросить 3 отеля для двоих →', 'zhiraf-ufa' ),
		'all_inclusive'  => __( 'Подобрать отель «Всё включено» →', 'zhiraf-ufa' ),
		'excursion'      => __( 'Получить программу и расчёт →', 'zhiraf-ufa' ),
	);
	return $labels[ $type ] ?? $labels['kids'];
}

/**
 * Eyebrow line per intent cluster.
 *
 * @param string $type Intent type.
 * @return string
 */
function zhiraf_ufa_intent_block_eyebrow( $type ) {
	$map = array(
		'kids'          => __( 'Семейный чек-лист «Жирафа»', 'zhiraf-ufa' ),
		'couples'       => __( 'Чек-лист для двоих «Жирафа»', 'zhiraf-ufa' ),
		'all_inclusive' => __( 'Чек-лист «Всё включено»', 'zhiraf-ufa' ),
		'excursion'     => __( 'Экскурсионный чек-лист «Жирафа»', 'zhiraf-ufa' ),
	);
	return $map[ $type ] ?? $map['kids'];
}

/**
 * Preset checklist copy per destination slug.
 *
 * @return array<string,array<string,mixed>>
 */
function zhiraf_ufa_intent_block_presets() {
	$vse   = home_url( '/vse-vklyucheno/' );
	$kids_blog = home_url( '/blog/tury-s-detmi-iz-ufy/' );
	$kids_guide = home_url( '/blog/tury-s-detmi-iz-ufy/' );

	return array(
		'turciya'    => array(
			'title'      => __( 'Турция с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html'  => sprintf(
				/* translators: %s: link */
				__( 'При прямом перелёте из Уфы (4,5–5 часов) важно попасть на нужный пляж и в понятную концепцию %s. Проверяем это в каждом отеле до брони:', 'zhiraf-ufa' ),
				'<a href="' . esc_url( $vse ) . '">' . esc_html__( '«Всё включено»', 'zhiraf-ufa' ) . '</a>'
			),
			'items'      => array(
				__( 'Пологий песчаный вход (Сиде, Белек, Инжекум) — без резкой глубины и крупной гальки.', 'zhiraf-ufa' ),
				__( 'Детское меню и блендеры в ресторане, отдельные столы для малышей.', 'zhiraf-ufa' ),
				__( 'Подогреваемые бассейны в мае и октябре, тень над детскими зонами.', 'zhiraf-ufa' ),
				__( 'Короткий трансфер от аэропорта Антальи — до 60 минут в пути с детьми.', 'zhiraf-ufa' ),
			),
			'aside'      => array(
				'title' => __( 'Подробный гид: отдых с детьми из Уфы', 'zhiraf-ufa' ),
				'text'  => __( 'Аптечка, бассейны в межсезонье и на чём можно сэкономить без потери комфорта.', 'zhiraf-ufa' ),
				'url'   => $kids_guide,
				'label' => __( 'Читать гид →', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 семейных отеля в Турции', 'zhiraf-ufa' ),
				'subtitle' => __( 'Укажите телефон — уточним возраст детей и пришлём варианты с вылетом из Уфы.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем семейный тур в Турцию из Уфы с ребёнком. Пришлите, пожалуйста, 3 варианта отелей с песчаным входом и детским меню.', 'zhiraf-ufa' ),
				'tour'     => __( 'Турция, семейный подбор', 'zhiraf-ufa' ),
			),
		),
		'oae'        => array(
			'title'     => __( 'ОАЭ с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html' => sprintf(
				__( 'Стыковка в Москве и разные эмираты — заранее фиксируем пляж, детские зоны и формат %s. Проверяем до брони:', 'zhiraf-ufa' ),
				'<a href="' . esc_url( $vse ) . '">' . esc_html__( 'питания', 'zhiraf-ufa' ) . '</a>'
			),
			'items'     => array(
				__( 'Фуджейра и Рас-эль-Хайма: спокойные пляжи и отели с аквазонами.', 'zhiraf-ufa' ),
				__( 'Дубай — городская программа; для пляжа с детьми сравниваем с пляжными эмиратами.', 'zhiraf-ufa' ),
				__( 'Детские клубы, кроватки и завтраки — прописываем в договоре.', 'zhiraf-ufa' ),
				__( 'Трансферы и время в пути после стыковки — считаем по маршруту из Уфы.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 семейных отеля в ОАЭ', 'zhiraf-ufa' ),
				'subtitle' => __( 'Уточним возраст детей и пожелания по эмирату.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем семейный тур в ОАЭ из Уфы. Нужны 3 отеля с детским клубом и пологим пляжем.', 'zhiraf-ufa' ),
				'tour'     => __( 'ОАЭ, семейный подбор', 'zhiraf-ufa' ),
			),
		),
		'egipet'     => array(
			'title'     => __( 'Египет с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html' => sprintf(
				__( 'Прямой перелёт в Хургаду или Шарм — проверяем пляж, риф и формат %s до оплаты:', 'zhiraf-ufa' ),
				'<a href="' . esc_url( $vse ) . '">' . esc_html__( '«Всё включено»', 'zhiraf-ufa' ) . '</a>'
			),
			'items'     => array(
				__( 'Песчаные бухты Хургады и семейные отели у Марса-Алам.', 'zhiraf-ufa' ),
				__( 'Пологий вход в море и детские бассейны — важнее «картинки» номера.', 'zhiraf-ufa' ),
				__( 'Питание по программе тура — согласуем до подписания договора.', 'zhiraf-ufa' ),
				__( 'Виза и правила въезда — напоминаем до вылета из Уфы.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 семейных отеля в Египте', 'zhiraf-ufa' ),
				'subtitle' => __( 'Хургада или Шарм — уточним по телефону.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем семейный тур в Египет из Уфы с ребёнком. Нужны 3 отеля с песчаным пляжем.', 'zhiraf-ufa' ),
				'tour'     => __( 'Египет, семейный подбор', 'zhiraf-ufa' ),
			),
		),
		'tailand'    => array(
			'title'     => __( 'Таиланд с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html' => sprintf(
				__( 'Долгий перелёт — выбираем курорт и %s под возраст детей. Проверяем:', 'zhiraf-ufa' ),
				'<a href="' . esc_url( $vse ) . '">' . esc_html__( 'питание', 'zhiraf-ufa' ) . '</a>'
			),
			'items'     => array(
				__( 'Пхукет и Краби: пологий заход и семейные отели у пляжа.', 'zhiraf-ufa' ),
				__( 'Паттайя — оживлённее; для малышей сравниваем с тихими бухтами.', 'zhiraf-ufa' ),
				__( 'Детские клубы и завтраки в отеле — фиксируем в ваучере.', 'zhiraf-ufa' ),
				__( 'Трансфер и время после стыковки в Москве.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 семейных отеля в Таиланде', 'zhiraf-ufa' ),
				'subtitle' => __( 'Укажите телефон и возраст детей.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем семейный тур в Таиланд из Уфы. Нужны 3 варианта с пологим пляжем.', 'zhiraf-ufa' ),
				'tour'     => __( 'Таиланд, семейный подбор', 'zhiraf-ufa' ),
			),
		),
		'vietnam'    => array(
			'title'     => __( 'Вьетнам с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html' => sprintf(
				__( 'Нячанг или Фукуок — заранее согласуем пляж и %s. Проверяем до брони:', 'zhiraf-ufa' ),
				'<a href="' . esc_url( $vse ) . '">' . esc_html__( 'питание', 'zhiraf-ufa' ) . '</a>'
			),
			'items'     => array(
				__( 'Пологий песок на центральном пляже Нячанга для купания с детьми.', 'zhiraf-ufa' ),
				__( 'Фукуок — отели с парками и семейными зонами.', 'zhiraf-ufa' ),
				__( 'Детские кроватки и питание по программе тура.', 'zhiraf-ufa' ),
				__( 'Стыковка в Москве и длительность перелёта из Уфы.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 семейных отеля во Вьетнаме', 'zhiraf-ufa' ),
				'subtitle' => __( 'Нячанг или Фукуок — подскажем по телефону.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем семейный тур во Вьетнам из Уфы. Нужны 3 отеля с детским клубом.', 'zhiraf-ufa' ),
				'tour'     => __( 'Вьетнам, семейный подбор', 'zhiraf-ufa' ),
			),
		),
		'kitaj'      => array(
			'title'     => __( 'Хайнань из Уфы: отель «всё включено»', 'zhiraf-ufa' ),
			'lead_html' => sprintf(
				__( 'Санья — главный пляжный курорт Китая. Сравниваем пакеты %s и пляжи до брони:', 'zhiraf-ufa' ),
				'<a href="' . esc_url( $vse ) . '">' . esc_html__( '«Всё включено»', 'zhiraf-ufa' ) . '</a>'
			),
			'items'     => array(
				__( 'Отели 4–5★ с полным пансионом у залива Ялунвань и Дадунхай.', 'zhiraf-ufa' ),
				__( 'Что входит в «всё включено»: питание, напитки, спорт — сверяем по договору.', 'zhiraf-ufa' ),
				__( 'Стыковка в Москве и трансфер до отеля.', 'zhiraf-ufa' ),
				__( 'Сезон: лучшее море ноябрь–апрель для пляжного отдыха.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём отель «Всё включено» на Хайнане', 'zhiraf-ufa' ),
				'subtitle' => __( 'Уточним даты и состав пакета.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем тур на Хайнань (Санья) из Уфы с полным пансионом. Нужны 3 варианта отелей.', 'zhiraf-ufa' ),
				'tour'     => __( 'Хайнань, всё включено', 'zhiraf-ufa' ),
			),
		),
		'indiya'     => array(
			'title'     => __( 'Гоа с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html' => sprintf(
				__( 'Южное или Северное Гоа — выбираем пляж и %s под семью. Проверяем:', 'zhiraf-ufa' ),
				'<a href="' . esc_url( $kids_blog ) . '">' . esc_html__( 'критерии для детей', 'zhiraf-ufa' ) . '</a>'
			),
			'items'     => array(
				__( 'Южное Гоа: пологий песок в Кавелоссиме и Варке.', 'zhiraf-ufa' ),
				__( 'Север — волнее; для купания с малышами берём южные пляжи.', 'zhiraf-ufa' ),
				__( 'Завтраки в отеле и детские услуги — в договоре.', 'zhiraf-ufa' ),
				__( 'e-Visa и стыковка в Москве — напоминаем до оплаты.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 семейных отеля в Гоа', 'zhiraf-ufa' ),
				'subtitle' => __( 'Север или Юг — уточним по телефону.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем семейный тур в Гоа из Уфы. Нужны 3 отеля с пологим пляжем.', 'zhiraf-ufa' ),
				'tour'     => __( 'Гоа, семейный подбор', 'zhiraf-ufa' ),
			),
		),
		'shri-lanka' => array(
			'title'     => __( 'Шри-Ланка с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html' => sprintf(
				__( 'Бентота или Хиккадува — согласуем пляж и %s. Проверяем:', 'zhiraf-ufa' ),
				'<a href="' . esc_url( $vse ) . '">' . esc_html__( 'питание', 'zhiraf-ufa' ) . '</a>'
			),
			'items'     => array(
				__( 'Песчаные пляжи западного побережья для купания с детьми.', 'zhiraf-ufa' ),
				__( 'Муссон на разных берегах — подбираем сезон под даты.', 'zhiraf-ufa' ),
				__( 'Питание по программе тура и трансферы.', 'zhiraf-ufa' ),
				__( 'ETA и стыковка в Москве.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 семейных отеля на Шри-Ланке', 'zhiraf-ufa' ),
				'subtitle' => __( 'Укажите телефон и даты.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем семейный тур на Шри-Ланку из Уфы. Нужны 3 отеля у спокойного пляжа.', 'zhiraf-ufa' ),
				'tour'     => __( 'Шри-Ланка, семейный подбор', 'zhiraf-ufa' ),
			),
		),
		'indoneziya' => array(
			'title'     => __( 'Бали для двоих из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html' => __( 'Долгий перелёт и стыковка — выбираем спокойный курорт и отель для пары. Проверяем до брони:', 'zhiraf-ufa' ),
			'items'     => array(
				__( 'Нуса-Дуа и Санур: спокойное море и тихие вечера.', 'zhiraf-ufa' ),
				__( 'Семиньяк и Кута — активнее; для романтики сравниваем с восточным побережьем.', 'zhiraf-ufa' ),
				__( 'Виллы и номера с видом — согласуем пожелания в договоре.', 'zhiraf-ufa' ),
				__( 'Трансфер после стыковки в Москве.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 отеля для двоих на Бали', 'zhiraf-ufa' ),
				'subtitle' => __( 'Уточним даты и формат отдыха.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем тур на Бали для двоих из Уфы. Нужны 3 отеля с тихим пляжем.', 'zhiraf-ufa' ),
				'tour'     => __( 'Бали, для двоих', 'zhiraf-ufa' ),
			),
		),
		'maldivy'    => array(
			'title'     => __( 'Мальдивы для двоих из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html' => sprintf(
				__( 'Атолл и трансфер на катере — заранее согласуем формат %s. Проверяем:', 'zhiraf-ufa' ),
				'<a href="' . esc_url( $vse ) . '">' . esc_html__( 'питания', 'zhiraf-ufa' ) . '</a>'
			),
			'items'     => array(
				__( 'Пологая лагуна важнее «картинки» водной виллы для спокойного отдыха.', 'zhiraf-ufa' ),
				__( 'Близкий трансфер с Мале — меньше усталости после стыковки.', 'zhiraf-ufa' ),
				__( 'Питание по программе тура и налоги на месте — проговариваем до оплаты.', 'zhiraf-ufa' ),
				__( '16+ и взрослые зоны — если нужен тихий формат для пары.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 отеля для двоих на Мальдивах', 'zhiraf-ufa' ),
				'subtitle' => __( 'Уточним атолл и бюджет.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем тур на Мальдивы для двоих из Уфы. Нужны 3 острова с лагуной и полным пансионом.', 'zhiraf-ufa' ),
				'tour'     => __( 'Мальдивы, для двоих', 'zhiraf-ufa' ),
			),
		),
		'sejshely'   => array(
			'title'     => __( 'Сейшелы для двоих из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ),
			'lead_html' => __( 'Маэ, Праслин или Ла-Диг — островная логистика для пары. Проверяем:', 'zhiraf-ufa' ),
			'items'     => array(
				__( 'Гранитные пляжи и тихие бухты — подбираем под романтический формат.', 'zhiraf-ufa' ),
				__( 'Паромы между островами — закладываем в маршрут.', 'zhiraf-ufa' ),
				__( 'Питание в отеле и трансферы — в договоре.', 'zhiraf-ufa' ),
				__( 'Стыковка в Москве и длительность перелёта.', 'zhiraf-ufa' ),
			),
			'lead_modal' => array(
				'title'    => __( 'Подберём 3 отеля для двоих на Сейшелах', 'zhiraf-ufa' ),
				'subtitle' => __( 'Уточним остров и даты.', 'zhiraf-ufa' ),
				'message'  => __( 'Здравствуйте! Подбираем тур на Сейшелы для двоих из Уфы. Нужны 3 отеля с уютным пляжем.', 'zhiraf-ufa' ),
				'tour'     => __( 'Сейшелы, для двоих', 'zhiraf-ufa' ),
			),
		),
	);
}

/**
 * Build intent checklist block for a destination.
 *
 * @param string               $slug Hub slug.
 * @param array<string,mixed>  $dest Destination config row.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_resolve_intent_block( $slug, $dest = array() ) {
	$types    = zhiraf_ufa_intent_block_type_map();
	$presets  = zhiraf_ufa_intent_block_presets();
	if ( ! isset( $presets[ $slug ] ) ) {
		return null;
	}
	$type = $types[ $slug ] ?? 'kids';
	if ( ! empty( $dest['intent_block'] ) && is_array( $dest['intent_block'] ) && ! empty( $dest['intent_block']['type'] ) ) {
		$type = $dest['intent_block']['type'];
	}
	$block = array_merge(
		array(
			'type'    => $type,
			'eyebrow' => zhiraf_ufa_intent_block_eyebrow( $type ),
			'cta'     => zhiraf_ufa_intent_block_cta_label( $type ),
			'section_id' => 'intent-' . sanitize_html_class( $slug ),
		),
		$presets[ $slug ]
	);
	if ( ! empty( $dest['intent_block'] ) && is_array( $dest['intent_block'] ) ) {
		$block = array_merge( $block, $dest['intent_block'] );
	}
	return $block;
}