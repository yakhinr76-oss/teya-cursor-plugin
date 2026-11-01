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
			'text'   => __( 'Египет, Таиланд — тёплое море и сезон без дождей', 'zhiraf-ufa' ),
			'filter' => 'egypt',
			'track'  => 'winter_sun',
		),
		array(
			'emoji'  => '🏔️',
			'title'  => __( 'Горы и спа-релакс', 'zhiraf-ufa' ),
			'text'   => __( 'Красная Поляна, Алтай, Абхазия — бассейны и горный воздух', 'zhiraf-ufa' ),
			'filter' => 'russia',
			'track'  => 'mountains',
		),
		array(
			'emoji'       => '🧭',
			'title'       => __( 'Экзотика и острова', 'zhiraf-ufa' ),
			'text'        => __( 'Мальдивы, Шри-Ланка, Вьетнам — лагуны и нестандартные маршруты', 'zhiraf-ufa' ),
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
			'title' => __( 'РТА 0012822 в реестре РФ', 'zhiraf-ufa' ),
			'text'  => __( 'Официальный номер в Едином федеральном реестре турагентств. Работаем строго по договору с фискальным чеком на каждую оплату.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '📍',
			'title' => __( 'Офис в Уфе с 2011 года', 'zhiraf-ufa' ),
			'text'  => __( 'ул. Гагарина, 74/1 (Сипайлово). Можно прийти лично: консультация, оформление документов и выдача билетов.', 'zhiraf-ufa' ),
		),
		array(
			'icon'  => '💳',
			'title' => __( 'Честная смета без скрытых доплат', 'zhiraf-ufa' ),
			'text'  => __( 'Окончательная сумма фиксируется в договоре в рублях до оплаты. Без сюрпризов в офисе и роста курса после брони.', 'zhiraf-ufa' ),
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
			'text'    => __( 'Песчаный заход, детское меню, коляски и тихие номера вдали от дискотек.', 'zhiraf-ufa' ),
			'prefill' => __( 'Здравствуйте! Ищу тур для первого выезда с малышом из Уфы. Подскажите проверенные отели с песчаным пляжем и детским столом.', 'zhiraf-ufa' ),
			'track'   => 'scenario_baby',
		),
		array(
			'emoji'   => '💍',
			'title'   => __( 'Медовый месяц и пары', 'zhiraf-ufa' ),
			'text'    => __( 'Adults Only 16+, виллы, спа и спокойная атмосфера без детских анимаций.', 'zhiraf-ufa' ),
			'prefill' => __( 'Здравствуйте! Нужен тур на двоих из Уфы в романтический отель (желательно Adults Only 16+).', 'zhiraf-ufa' ),
			'track'   => 'scenario_couple',
		),
		array(
			'emoji'   => '🌿',
			'title'   => __( 'Спокойный отдых родителям', 'zhiraf-ufa' ),
			'text'    => __( 'Ровные территории без крутых лестниц, санаторные зоны и русскоязычный персонал.', 'zhiraf-ufa' ),
			'prefill' => __( 'Здравствуйте! Подбираем комфортный и спокойный отдых для родителей с вылетом из Уфы. Важна территория без горок и забота.', 'zhiraf-ufa' ),
			'track'   => 'scenario_parents',
		),
		array(
			'emoji'   => '⚡',
			'title'   => __( 'Сорваться на выходные', 'zhiraf-ufa' ),
			'text'    => __( 'Экспресс-туры на 3–5 ночей: Сочи, ОАЭ, горящие направления с быстрым вылетом.', 'zhiraf-ufa' ),
			'prefill' => __( 'Здравствуйте! Хотим сорваться на 3–5 дней из Уфы в ближайшие даты. Что есть горящего и проверенного?', 'zhiraf-ufa' ),
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
