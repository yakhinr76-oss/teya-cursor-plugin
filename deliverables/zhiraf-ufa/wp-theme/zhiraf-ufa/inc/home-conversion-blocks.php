<?php
/**
 * Homepage conversion blocks: express filter, guarantees, life scenarios.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Express vacation format chips (scroll to catalog + tab).
 *
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_home_express_vacation_chips() {
	return array(
		array(
			'emoji'  => '🏖️',
			'title'  => __( 'Семейный пляж', 'zhiraf-ufa' ),
			'text'   => __( 'Турция, ОАЭ — пологий песок, детские клубы и All Inclusive', 'zhiraf-ufa' ),
			'filter' => 'turkey',
			'track'  => 'family_beach',
		),
		array(
			'emoji'  => '🌴',
			'title'  => __( 'Зимнее лето', 'zhiraf-ufa' ),
			'text'   => __( 'Египет, Таиланд — теплое море и сезон без дождей прямо сейчас', 'zhiraf-ufa' ),
			'filter' => 'egypt',
			'track'  => 'winter_sun',
		),
		array(
			'emoji'  => '🏔️',
			'title'  => __( 'Горы и спа', 'zhiraf-ufa' ),
			'text'   => __( 'Красная Поляна, Абхазия — подогреваемые бассейны и горный воздух', 'zhiraf-ufa' ),
			'filter' => 'russia',
			'track'  => 'mountains',
		),
		array(
			'emoji'       => '🧭',
			'title'       => __( 'Экзотика и острова', 'zhiraf-ufa' ),
			'text'        => __( 'Мальдивы, Шри-Ланка — уединенные лагуны и дайвинг', 'zhiraf-ufa' ),
			'filter'      => '',
			'wa_prefill'  => __( 'Здравствуйте! Интересует экзотический тур с вылетом из Уфы (Мальдивы / Азия). Подскажите актуальные варианты и бюджет на двоих.', 'zhiraf-ufa' ),
			'track'       => 'exotic',
		),
	);
}

/**
 * Hard guarantees bar (4 columns).
 *
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_home_guarantee_pillars() {
	return array(
		array(
			'icon'  => '🏷️',
			'title' => __( 'Реальные цены туроператоров', 'zhiraf-ufa' ),
			'text'  => __( 'Без скрытых комиссий агентства и внезапных доплат.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🏨',
			'title' => __( 'Отели без прикрас', 'zhiraf-ufa' ),
			'text'  => __( 'Честно предупреждаем о нюансах захода в море, шумных трассах и ремонте.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '💳',
			'title' => __( 'Фиксация сметы в договоре', 'zhiraf-ufa' ),
			'text'  => __( 'Окончательная цена с топливными сборами и трансфером прописывается до оплаты.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '🤝',
			'title' => __( 'Поддержка 24/7', 'zhiraf-ufa' ),
			'text'  => __( 'Сопровождение туриста от регистрации в аэропорту Уфы до возвращения.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Life situation cards → Quiz with prefill.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_home_life_scenarios() {
	return array(
		array(
			'image'       => 'life-scenario-family.jpg',
			'title'       => __( 'Семья с детьми', 'zhiraf-ufa' ),
			'text'        => __( 'Пологий песчаный вход, тёплые детские бассейны, анимация и меню без специй.', 'zhiraf-ufa' ),
			'tags'        => array(
				__( 'Пологий песок', 'zhiraf-ufa' ),
				__( 'Детские бассейны', 'zhiraf-ufa' ),
			),
			'overlay'     => __( '👨‍👩‍👧 Пляжи без камней', 'zhiraf-ufa' ),
			'badge'       => array(
				'type'  => 'count',
				'label' => __( '38 отелей', 'zhiraf-ufa' ),
			),
			'cta'         => __( 'Подобрать для детей (38 отелей)', 'zhiraf-ufa' ),
			'vibe'        => 'family',
			'quiz_step_1' => 'Семья с детьми',
			'track'       => 'scenario_family',
		),
		array(
			'image'       => 'life-scenario-couple.jpg',
			'title'       => __( 'Для пар и релакса', 'zhiraf-ufa' ),
			'text'        => __( 'Отели 16+ / 18+, приватные бунгало, тишина у бассейна, спа-комплексы и романтика.', 'zhiraf-ufa' ),
			'tags'        => array(
				__( 'Без шума 16+', 'zhiraf-ufa' ),
				__( 'Спа и релакс', 'zhiraf-ufa' ),
			),
			'overlay'     => __( '🥂 Только для взрослых', 'zhiraf-ufa' ),
			'badge'       => array(
				'type'  => 'count',
				'label' => __( '24 отеля', 'zhiraf-ufa' ),
			),
			'cta'         => __( 'Смотреть отели 16+ (24 отеля)', 'zhiraf-ufa' ),
			'vibe'        => 'couple',
			'quiz_step_1' => 'Для пар / Релакс',
			'track'       => 'scenario_couple',
		),
		array(
			'image'       => 'life-scenario-value.jpg',
			'title'       => __( 'Хит «Цена / Качество»', 'zhiraf-ufa' ),
			'text'        => __( 'Проверенные 4* и 5* с отличным питанием и сервисом без переплаты за люксовый бренд.', 'zhiraf-ufa' ),
			'tags'        => array(
				__( 'Рейтинг 4.8+', 'zhiraf-ufa' ),
				__( 'Честный All Inclusive', 'zhiraf-ufa' ),
			),
			'overlay'     => __( '⚖️ Честный рейтинг 4.7+', 'zhiraf-ufa' ),
			'badge'       => array(
				'type'  => 'featured',
				'label' => __( '★ Топ выбор', 'zhiraf-ufa' ),
			),
			'cta'         => __( 'Найти отели с высоким рейтингом', 'zhiraf-ufa' ),
			'vibe'        => 'balance',
			'quiz_step_1' => 'Хит Цена/Качество',
			'track'       => 'scenario_value',
		),
		array(
			'image'       => 'life-scenario-city.jpg',
			'title'       => __( 'Город и шопинг', 'zhiraf-ufa' ),
			'text'        => __( 'В пешей доступности от набережных, торговых моллов, метро и ресторанных улиц.', 'zhiraf-ufa' ),
			'tags'        => array(
				__( 'Центр города', 'zhiraf-ufa' ),
				__( 'Шопинг рядом', 'zhiraf-ufa' ),
			),
			'overlay'     => __( '🛍️ В центре событий', 'zhiraf-ufa' ),
			'badge'       => array(
				'type'  => 'count',
				'label' => __( '19 отелей', 'zhiraf-ufa' ),
			),
			'cta'         => __( 'Выбрать отель в центре города', 'zhiraf-ufa' ),
			'vibe'        => 'city',
			'quiz_step_1' => 'Город и шопинг',
			'track'       => 'scenario_city',
		),
	);
}

/**
 * WhatsApp URL for catalog capture card.
 */
function zhiraf_ufa_home_catalog_capture_wa_url() {
	$text = __( 'Здравствуйте! Ищу тур из Уфы. Мой бюджет примерно _____ на _____ человек. Подскажите, что лучше выбрать?', 'zhiraf-ufa' );
	return zhiraf_ufa_whatsapp_url_with_text( zhiraf_ufa_brand_whatsapp_url(), $text );
}
