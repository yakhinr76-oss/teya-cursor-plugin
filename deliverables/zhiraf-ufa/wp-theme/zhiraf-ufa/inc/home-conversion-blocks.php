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
 * Life situation cards → taxonomy filter landing (/tours/?vibe=).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_home_life_scenarios() {
	$defs = array(
		array(
			'emoji'  => '👶',
			'title'  => __( 'Первый раз с малышом', 'zhiraf-ufa' ),
			'text'   => __( 'Песчаный вход, горки, детское меню — только проверенные семейные отели.', 'zhiraf-ufa' ),
			'vibe'   => 'family',
			'track'  => 'scenario_family',
		),
		array(
			'emoji'  => '🥂',
			'title'  => __( 'Медовый месяц и пары', 'zhiraf-ufa' ),
			'text'   => __( 'Adults Only 16+, спа и тишина без детского шума.', 'zhiraf-ufa' ),
			'vibe'   => 'couples',
			'track'  => 'scenario_couple',
		),
		array(
			'emoji'  => '⭐',
			'title'  => __( 'Хит цена / качество', 'zhiraf-ufa' ),
			'text'   => __( 'Рейтинг 4.7+ и честная смета без переплат за «раскрученное» имя.', 'zhiraf-ufa' ),
			'vibe'   => 'balance',
			'track'  => 'scenario_value',
		),
		array(
			'emoji'  => '🏙️',
			'title'  => __( 'Городской отдых и шопинг', 'zhiraf-ufa' ),
			'text'   => __( 'Отели у набережных, моллов и транспортных узлов.', 'zhiraf-ufa' ),
			'vibe'   => 'city',
			'track'  => 'scenario_city',
		),
	);
	$out = array();
	foreach ( $defs as $def ) {
		$count = function_exists( 'zhiraf_ufa_hub_vibe_hotel_count' )
			? zhiraf_ufa_hub_vibe_hotel_count( $def['vibe'] )
			: 0;
		$out[] = array_merge(
			$def,
			array(
				'url'          => function_exists( 'zhiraf_ufa_tours_filter_url' )
					? zhiraf_ufa_tours_filter_url( array( 'vibe' => $def['vibe'] ) )
					: home_url( '/tours/?vibe=' . rawurlencode( $def['vibe'] ) ),
				'count'        => $count,
				'count_badge'  => $count > 0
					? sprintf(
						/* translators: %d: hotel count */
						_n( '%d отель', '%d отелей', $count, 'zhiraf-ufa' ),
						$count
					)
					: __( 'Подборка', 'zhiraf-ufa' ),
				'cta'          => __( 'Смотреть отели →', 'zhiraf-ufa' ),
			)
		);
	}
	return $out;
}

/**
 * WhatsApp URL for catalog capture card.
 */
function zhiraf_ufa_home_catalog_capture_wa_url() {
	$text = __( 'Здравствуйте! Не нашёл подходящий отель на сайте. Помогите подобрать тур из Уфы под мои даты и бюджет — нужны 3 персональных варианта.', 'zhiraf-ufa' );
	return zhiraf_ufa_whatsapp_url_with_text( zhiraf_ufa_brand_whatsapp_url(), $text );
}
