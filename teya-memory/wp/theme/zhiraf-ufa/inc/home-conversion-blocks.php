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
 * Hard guarantees bar (3 columns).
 *
 * @return array<int,array{icon:string,title:string,text:string}>
 */
function zhiraf_ufa_home_guarantee_pillars() {
	return array(
		array(
			'icon'  => '🏛️',
			'title' => __( 'РТА 0012822', 'zhiraf-ufa' ),
			'text'  => __( 'Номер в Едином федеральном реестре турагентств РФ, официальный договор и чек.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '📍',
			'title' => __( 'Офис в Уфе с 2011 года', 'zhiraf-ufa' ),
			'text'  => __( 'ул. Гагарина, 74/1 (Сипайлово). Личный прием и консультации.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '💳',
			'title' => __( 'Честная смета', 'zhiraf-ufa' ),
			'text'  => __( 'Окончательная цена фиксируется в договоре в рублях до оплаты. Без скрытых наценок.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Life situation cards → WhatsApp with prefill.
 *
 * @return array<int,array{emoji:string,title:string,text:string,prefill:string,track:string}>
 */
function zhiraf_ufa_home_life_scenarios() {
	return array(
		array(
			'emoji'   => '👶',
			'title'   => __( 'Первый выезд с малышом', 'zhiraf-ufa' ),
			'text'    => __( 'Отели с плавным заходом в море, блендерами в ресторане, прокатом колясок и тихими номерами вдали от дискотек.', 'zhiraf-ufa' ),
			'prefill' => __( 'Здравствуйте! Ищу тур для первого выезда с малышом из Уфы. Подскажите проверенные отели с песчаным пляжем и детским столом.', 'zhiraf-ufa' ),
			'cta'     => __( 'Смотреть отели →', 'zhiraf-ufa' ),
			'track'   => 'scenario_baby',
		),
		array(
			'emoji'   => '💍',
			'title'   => __( 'Медовый месяц и пары', 'zhiraf-ufa' ),
			'text'    => __( 'Отели 16+ только для взрослых, уединенные виллы, панорамные спа-комплексы, винные бары и идеальные закаты.', 'zhiraf-ufa' ),
			'prefill' => __( 'Здравствуйте! Нужен тур на двоих из Уфы в романтический отель (желательно Adults Only 16+).', 'zhiraf-ufa' ),
			'cta'     => __( 'Смотреть отели →', 'zhiraf-ufa' ),
			'track'   => 'scenario_couple',
		),
		array(
			'emoji'   => '🌿',
			'title'   => __( 'Спокойный отдых родителям', 'zhiraf-ufa' ),
			'text'    => __( 'Зеленые ровные территории без крутых лестниц, санаторные зоны, минеральные воды и русскоязычный персонал.', 'zhiraf-ufa' ),
			'prefill' => __( 'Здравствуйте! Подбираем комфортный и спокойный отдых для родителей с вылетом из Уфы. Важна территория без горок и забота.', 'zhiraf-ufa' ),
			'cta'     => __( 'Смотреть отели →', 'zhiraf-ufa' ),
			'track'   => 'scenario_parents',
		),
		array(
			'emoji'   => '⚡',
			'title'   => __( 'Сорваться на выходные', 'zhiraf-ufa' ),
			'text'    => __( 'Короткие экспресс-туры на 3–5 ночей: Сочи, ОАЭ, Дагестан или экскурсионный Стамбул с быстрым прямым перелетом.', 'zhiraf-ufa' ),
			'prefill' => __( 'Здравствуйте! Хотим сорваться на 3–5 дней из Уфы в ближайшие даты. Что есть горящего и проверенного?', 'zhiraf-ufa' ),
			'cta'     => __( 'Смотреть варианты →', 'zhiraf-ufa' ),
			'track'   => 'scenario_weekend',
		),
	);
}

/**
 * WhatsApp URL for catalog capture card.
 */
function zhiraf_ufa_home_catalog_capture_wa_url() {
	$text = __( 'Здравствуйте! Не нашёл подходящий отель на сайте. Помогите подобрать тур из Уфы под мои даты и бюджет — нужны 3 персональных варианта.', 'zhiraf-ufa' );
	return zhiraf_ufa_whatsapp_url_with_text( zhiraf_ufa_brand_whatsapp_url(), $text );
}
