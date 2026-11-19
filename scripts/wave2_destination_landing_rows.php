<?php
// Paste into zhiraf_ufa_destination_landing_data() before 'rossiya'.
return <<<'PHP'
		'shri-lanka' => array(
			'curated_landing_v1'           => true,
			'hide_landing_prose'           => false,
			'curated_resort_chips'         => true,
			'curated_dest_lead_form'       => true,
			'curated_hero_show_compare'    => false,
			'curated_hero_show_trust'      => false,
			'curated_kids_section'         => true,
			'curated_expert_section'       => true,
			'seo_prose_after_faq'          => true,
			'seo_prose_accordions'         => true,
			'prose_accordion_title'        => __( 'Подробнее о побережьях, сезоне и пакете', 'zhiraf-ufa' ),
			'curated_compare_btn_default'  => __( 'Сравнить Унаватуну и Хиккадуву', 'zhiraf-ufa' ),
			'curated_compare_lead_default' => __( 'Сравним Унаватуну и Хиккадуву под ваш месяц и бюджет', 'zhiraf-ufa' ),
			'name'           => __( 'Шри-Ланка', 'zhiraf-ufa' ),
			'h1'             => __( 'Туры на Шри-Ланку из Уфы', 'zhiraf-ufa' ),
			'title'          => __( 'Шри-Ланка из Уфы — туры на побережье | Жираф', 'zhiraf-ufa' ),
			'description'    => __( 'Подбор туров на Шри-Ланку с вылетом из Уфы: Унаватуна, Бентота, Пасикуда. ETA онлайн, консультации «Жираф».', 'zhiraf-ufa' ),
			'image'          => 'country-shri-lanka.jpg',
			'image_alt'      => __( 'Шри-Ланка — пляж и океан', 'zhiraf-ufa' ),
			'hero_badges'    => array(
				array( 'icon' => '✈', 'text' => __( 'Вылет из Уфы • стыковка в Москве', 'zhiraf-ufa' ) ),
				array( 'icon_svg' => 'passport', 'text' => __( 'ETA онлайн до вылета', 'zhiraf-ufa' ) ),
			),
			'hero_lead'      => __( 'Туры на Шри-Ланку с вылетом из Уфы: 6 проверенных отелей на юго-западе, западе (Бентота) и востоке (Пасикуда). Сезон побережья под даты, честные цены за двоих с перелётом.', 'zhiraf-ufa' ),
			'cta_message_placeholder' => __( 'Шри-Ланка из Уфы: даты, состав и бюджет на двоих.', 'zhiraf-ufa' ),
			'resort_compare_title' => __( 'Унаватуна или Хиккадува: сравнение', 'zhiraf-ufa' ),
			'resort_compare' => array(
				array(
					'title' => __( 'Унаватуна', 'zhiraf-ufa' ),
					'tag'   => __( 'Семейная бухта', 'zhiraf-ufa' ),
					'image' => 'alt-shri-lanka-beach-1.jpg',
					'rows'  => array(
						array( 'label' => __( 'Атмосфера', 'zhiraf-ufa' ), 'value' => __( 'Риф, спокойная вода, семейный ритм', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Сезон', 'zhiraf-ufa' ), 'value' => __( 'декабрь — апрель на юго-западе', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title' => __( 'Хиккадува', 'zhiraf-ufa' ),
					'tag'   => __( 'Серф и вечер', 'zhiraf-ufa' ),
					'image' => 'alt-shri-lanka-beach-2.jpg',
					'rows'  => array(
						array( 'label' => __( 'Атмосфера', 'zhiraf-ufa' ), 'value' => __( 'Волны, черепахи, кафе и серф', 'zhiraf-ufa' ) ),
						array( 'label' => __( 'Осторожно', 'zhiraf-ufa' ), 'value' => __( 'летом — муссон на западе', 'zhiraf-ufa' ) ),
					),
				),
			),
			'quiz_prefill'   => __( 'Шри-Ланка, вылет из Уфы', 'zhiraf-ufa' ),
			'blog_links'     => array(
				array( 'url' => home_url( '/blog/tury-s-detmi-iz-ufy/' ), 'label' => __( 'Туры с детьми из Уфы', 'zhiraf-ufa' ), 'tag' => __( 'СЕМЬЯ', 'zhiraf-ufa' ) ),
				array( 'url' => home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ), 'label' => __( 'Что входит в тур', 'zhiraf-ufa' ), 'tag' => __( 'ПАКЕТ', 'zhiraf-ufa' ) ),
			),
			'faq'            => array(
				array( 'q' => __( 'Как лететь из Уфы на Шри-Ланку?', 'zhiraf-ufa' ), 'a' => __( 'Пакеты идут со стыковкой в Москве; маршрут и багаж фиксируем на карточке тура.', 'zhiraf-ufa' ) ),
				array( 'q' => __( 'Нужна ли ETA?', 'zhiraf-ufa' ), 'a' => __( 'Да, электронное разрешение оформляется онлайн до вылета.', 'zhiraf-ufa' ) ),
				array( 'q' => __( 'Унаватуна или Хиккадува?', 'zhiraf-ufa' ), 'a' => __( 'Унаватуна — спокойная бухта для семей. Хиккадува — серф и вечерняя жизнь. Сезон юго-запада — зима; летом смотрим Пасикуду.', 'zhiraf-ufa' ) ),
			),
		),
		'indoneziya' => array(
			'curated_landing_v1'           => true,
			'curated_resort_chips'         => true,
			'curated_dest_lead_form'       => true,
			'curated_kids_section'         => true,
			'curated_expert_section'       => true,
			'seo_prose_after_faq'          => true,
			'curated_compare_btn_default'  => __( 'Сравнить Нуса-Дуа и Семиньяк', 'zhiraf-ufa' ),
			'curated_compare_lead_default' => __( 'Сравним Нуса-Дуа и Семиньяк под ваш бюджет', 'zhiraf-ufa' ),
			'name'           => __( 'Индонезия', 'zhiraf-ufa' ),
			'h1'             => __( 'Туры на Бали из Уфы', 'zhiraf-ufa' ),
			'title'          => __( 'Бали из Уфы — туры на Нуса-Дуа и в Семиньяк | Жираф', 'zhiraf-ufa' ),
			'description'    => __( 'Туры на Бали с вылетом из Уфы: Нуса-Дуа, Семиньяк, Санур, Убуд. VoA, подбор отелей «Жираф».', 'zhiraf-ufa' ),
			'image'          => 'country-indoneziya.jpg',
			'hero_badges'    => array(
				array( 'icon' => '✈', 'text' => __( 'Вылет из Уфы • стыковка', 'zhiraf-ufa' ) ),
				array( 'icon_svg' => 'passport', 'text' => __( 'VoA / e-VoA', 'zhiraf-ufa' ) ),
			),
			'hero_lead'      => __( '6 проверенных отелей на Бали: спокойная Нуса-Дуа, динамичный Семиньяк, семейный Санур и джунгли Убуда. Цены за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
			'resort_compare_title' => __( 'Нуса-Дуа или Семиньяк', 'zhiraf-ufa' ),
			'resort_compare' => array(
				array(
					'title' => __( 'Нуса-Дуа', 'zhiraf-ufa' ),
					'tag'   => __( 'Семья', 'zhiraf-ufa' ),
					'image' => 'country-indoneziya.jpg',
					'rows'  => array(
						array( 'label' => __( 'Море', 'zhiraf-ufa' ), 'value' => __( 'Пологий заход, резорты-клубы', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title' => __( 'Семиньяк', 'zhiraf-ufa' ),
					'tag'   => __( 'Закаты', 'zhiraf-ufa' ),
					'image' => 'country-indoneziya.jpg',
					'rows'  => array(
						array( 'label' => __( 'Море', 'zhiraf-ufa' ), 'value' => __( 'Волны, серф, кафе', 'zhiraf-ufa' ) ),
					),
				),
			),
			'quiz_prefill'   => __( 'Бали, вылет из Уфы', 'zhiraf-ufa' ),
			'blog_links'     => array(
				array( 'url' => home_url( '/blog/tury-s-detmi-iz-ufy/' ), 'label' => __( 'Отдых с детьми', 'zhiraf-ufa' ), 'tag' => __( 'СЕМЬЯ', 'zhiraf-ufa' ) ),
				array( 'url' => home_url( '/blog/kak-kupit-tur-v-ufe/' ), 'label' => __( 'Как купить тур в Уфе', 'zhiraf-ufa' ), 'tag' => __( 'ГАЙД', 'zhiraf-ufa' ) ),
			),
			'faq'            => array(
				array( 'q' => __( 'Как лететь из Уфы на Бали?', 'zhiraf-ufa' ), 'a' => __( 'Долгий перелёт со стыковкой — считаем время в пути до брони.', 'zhiraf-ufa' ) ),
				array( 'q' => __( 'Нуса-Дуа или Семиньяк?', 'zhiraf-ufa' ), 'a' => __( 'Нуса-Дуа — спокойное море. Семиньяк — кафе и волны; с детьми чаще Нуса-Дуа.', 'zhiraf-ufa' ) ),
			),
		),
		'indiya' => array(
			'curated_landing_v1'           => true,
			'curated_resort_chips'         => true,
			'curated_dest_lead_form'       => true,
			'curated_kids_section'         => true,
			'curated_expert_section'       => true,
			'curated_compare_btn_default'  => __( 'Сравнить Юг и Север Гоа', 'zhiraf-ufa' ),
			'curated_compare_lead_default' => __( 'Сравним Южный и Северный Гоа', 'zhiraf-ufa' ),
			'name'           => __( 'Индия', 'zhiraf-ufa' ),
			'h1'             => __( 'Туры в Гоа из Уфы', 'zhiraf-ufa' ),
			'title'          => __( 'Гоа из Уфы — туры на пляжи | Жираф', 'zhiraf-ufa' ),
			'description'    => __( 'Туры в Гоа с вылетом из Уфы: Южный и Северный Гоа, e-Visa, подбор отелей.', 'zhiraf-ufa' ),
			'image'          => 'resort-severnyj-goa.jpg',
			'hero_badges'    => array(
				array( 'icon' => '✈', 'text' => __( 'Вылет из Уфы', 'zhiraf-ufa' ) ),
				array( 'icon_svg' => 'passport', 'text' => __( 'e-Visa онлайн', 'zhiraf-ufa' ) ),
			),
			'hero_lead'      => __( '6 отелей на пляжах Гоа: тихий Юг и динамичный Север. Сезон ноябрь — апрель, цены за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
			'resort_compare_title' => __( 'Южный или Северный Гоа', 'zhiraf-ufa' ),
			'resort_compare' => array(
				array(
					'title' => __( 'Южный Гоа', 'zhiraf-ufa' ),
					'tag'   => __( 'Тишина', 'zhiraf-ufa' ),
					'image' => 'resort-severnyj-goa.jpg',
					'rows'  => array(
						array( 'label' => __( 'Атмосфера', 'zhiraf-ufa' ), 'value' => __( 'Длинные пляжи, семьи', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title' => __( 'Северный Гоа', 'zhiraf-ufa' ),
					'tag'   => __( 'Тусовка', 'zhiraf-ufa' ),
					'image' => 'resort-severnyj-goa.jpg',
					'rows'  => array(
						array( 'label' => __( 'Атмосфера', 'zhiraf-ufa' ), 'value' => __( 'Кафе, рынки, вечер', 'zhiraf-ufa' ) ),
					),
				),
			),
			'quiz_prefill'   => __( 'Гоа, вылет из Уфы', 'zhiraf-ufa' ),
			'blog_links'     => array(
				array( 'url' => home_url( '/blog/tury-s-detmi-iz-ufy/' ), 'label' => __( 'Семейный отдых', 'zhiraf-ufa' ), 'tag' => __( 'СЕМЬЯ', 'zhiraf-ufa' ) ),
			),
			'faq'            => array(
				array( 'q' => __( 'Юг или Север Гоа?', 'zhiraf-ufa' ), 'a' => __( 'Юг — тишина и длинный песок. Север — кафе и вечерняя жизнь.', 'zhiraf-ufa' ) ),
				array( 'q' => __( 'Когда сезон?', 'zhiraf-ufa' ), 'a' => __( 'Лучше ноябрь — апрель: спокойнее море и меньше дождей.', 'zhiraf-ufa' ) ),
			),
		),
		'sejshely' => array(
			'curated_landing_v1'           => true,
			'curated_resort_chips'         => true,
			'curated_dest_lead_form'       => true,
			'curated_kids_section'         => true,
			'curated_expert_section'       => true,
			'curated_compare_btn_default'  => __( 'Сравнить Маэ и Праслин', 'zhiraf-ufa' ),
			'curated_compare_lead_default' => __( 'Сравним Маэ и Праслин под ваш бюджет', 'zhiraf-ufa' ),
			'name'           => __( 'Сейшелы', 'zhiraf-ufa' ),
			'h1'             => __( 'Туры на Сейшелы из Уфы', 'zhiraf-ufa' ),
			'title'          => __( 'Сейшелы из Уфы — туры на Маэ и Праслин | Жираф', 'zhiraf-ufa' ),
			'description'    => __( 'Туры на Сейшелы с вылетом из Уфы: Маэ, Праслин, Ла-Диг. ETA, подбор отелей «Жираф».', 'zhiraf-ufa' ),
			'image'          => 'country-sejshely.jpg',
			'hero_badges'    => array(
				array( 'icon' => '✈', 'text' => __( 'Вылет из Уфы', 'zhiraf-ufa' ) ),
				array( 'icon_svg' => 'passport', 'text' => __( 'ETA • без визы', 'zhiraf-ufa' ) ),
			),
			'hero_lead'      => __( '6 отелей на Маэ, Праслине и Ла-Диг: гранитные бухты и белый песок. Цены за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
			'resort_compare_title' => __( 'Маэ или Праслин', 'zhiraf-ufa' ),
			'resort_compare' => array(
				array(
					'title' => __( 'Маэ', 'zhiraf-ufa' ),
					'tag'   => __( 'Старт', 'zhiraf-ufa' ),
					'image' => 'country-sejshely.jpg',
					'rows'  => array(
						array( 'label' => __( 'Плюсы', 'zhiraf-ufa' ), 'value' => __( 'Аэропорт, Бо-Валлон, инфраструктура', 'zhiraf-ufa' ) ),
					),
				),
				array(
					'title' => __( 'Праслин', 'zhiraf-ufa' ),
					'tag'   => __( 'Природа', 'zhiraf-ufa' ),
					'image' => 'country-sejshely.jpg',
					'rows'  => array(
						array( 'label' => __( 'Плюсы', 'zhiraf-ufa' ), 'value' => __( 'Валлон де Мэ, паром, тише', 'zhiraf-ufa' ) ),
					),
				),
			),
			'quiz_prefill'   => __( 'Сейшелы, вылет из Уфы', 'zhiraf-ufa' ),
			'blog_links'     => array(
				array( 'url' => home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ), 'label' => __( 'Состав пакета', 'zhiraf-ufa' ), 'tag' => __( 'ПАКЕТ', 'zhiraf-ufa' ) ),
				array( 'url' => home_url( '/blog/tury-s-detmi-iz-ufy/' ), 'label' => __( 'С детьми из Уфы', 'zhiraf-ufa' ), 'tag' => __( 'СЕМЬЯ', 'zhiraf-ufa' ) ),
			),
			'faq'            => array(
				array( 'q' => __( 'Нужна ли ETA?', 'zhiraf-ufa' ), 'a' => __( 'Да, электронное разрешение до вылета; въезд безвизовый.', 'zhiraf-ufa' ) ),
				array( 'q' => __( 'Маэ или Праслин?', 'zhiraf-ufa' ), 'a' => __( 'Маэ — без парома после прилёта. Праслин и Ла-Диг — природа, паромы.', 'zhiraf-ufa' ) ),
			),
		),

PHP;