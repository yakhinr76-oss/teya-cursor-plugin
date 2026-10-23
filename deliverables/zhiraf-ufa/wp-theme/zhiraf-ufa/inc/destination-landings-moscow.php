<?php
/**
 * Destination landings priced from MOSCOW (owner-approved 19.10.2026).
 *
 * ТЕКСТ НА ПРОВЕРКУ: intros are deliberately short and factual (only what follows
 * from Biblio-Globus data: departure city, resort, package = flight + hotel for 2 adults).
 * No climate/visa/flight-time claims — add after editorial review.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds landing rows for Moscow-departure countries.
 *
 * @return array<string,array<string,mixed>>
 */
function zhiraf_ufa_moscow_landing_data() {
	$rows = array(
		'kitaj'      => array( __( 'Китай', 'zhiraf-ufa' ), __( 'Китай (Санья, о. Хайнань)', 'zhiraf-ufa' ), __( 'в Китай (Санья)', 'zhiraf-ufa' ) ),
		'indiya'     => array( __( 'Индия', 'zhiraf-ufa' ), __( 'Индия (Гоа)', 'zhiraf-ufa' ), __( 'в Индию (Гоа)', 'zhiraf-ufa' ) ),
		'shri-lanka' => array( __( 'Шри-Ланка', 'zhiraf-ufa' ), __( 'Шри-Ланка', 'zhiraf-ufa' ), __( 'на Шри-Ланку', 'zhiraf-ufa' ) ),
		'indoneziya' => array( __( 'Индонезия', 'zhiraf-ufa' ), __( 'Индонезия (Бали)', 'zhiraf-ufa' ), __( 'на Бали', 'zhiraf-ufa' ) ),
		'maldivy'    => array( __( 'Мальдивы', 'zhiraf-ufa' ), __( 'Мальдивы', 'zhiraf-ufa' ), __( 'на Мальдивы', 'zhiraf-ufa' ) ),
		'sejshely'   => array( __( 'Сейшелы', 'zhiraf-ufa' ), __( 'Сейшелы', 'zhiraf-ufa' ), __( 'на Сейшелы', 'zhiraf-ufa' ) ),
		'uzbekistan' => array( __( 'Узбекистан', 'zhiraf-ufa' ), __( 'Узбекистан', 'zhiraf-ufa' ), __( 'в Узбекистан', 'zhiraf-ufa' ) ),
		'armeniya'   => array( __( 'Армения', 'zhiraf-ufa' ), __( 'Армения', 'zhiraf-ufa' ), __( 'в Армению', 'zhiraf-ufa' ) ),
	);
	$alts = array(
		'kitaj' => __( 'Китай — статуя Гуаньинь Наньшань в Санье, о. Хайнань', 'zhiraf-ufa' ),
		'indiya' => __( 'Индия — собор Се в Старом Гоа', 'zhiraf-ufa' ),
		'shri-lanka' => __( 'Шри-Ланка — рыбаки на шестах в океане', 'zhiraf-ufa' ),
		'indoneziya' => __( 'Бали — храм Улун Дану Братан на озере Братан', 'zhiraf-ufa' ),
		'maldivy' => __( 'Мальдивы — Исламский центр в Мале', 'zhiraf-ufa' ),
		'sejshely' => __( 'Сейшелы — гранитные валуны на пляже Ла-Диг', 'zhiraf-ufa' ),
		'uzbekistan' => __( 'Узбекистан — Регистан в Самарканде', 'zhiraf-ufa' ),
		'armeniya' => __( 'Армения — античный храм Гарни', 'zhiraf-ufa' ),
	);
	$content = function_exists( 'zhiraf_ufa_moscow_content' ) ? zhiraf_ufa_moscow_content() : array();
	$out     = array();
	foreach ( $rows as $slug => $r ) {
		list( $name, $full, $to ) = $r;
		$out[ $slug ] = array(
			'name'         => $name,
			'needs_review' => true,
			'departure'    => __( 'из Москвы', 'zhiraf-ufa' ),
			'h1'           => sprintf( __( 'Туры %s с вылетом из Москвы', 'zhiraf-ufa' ), $to ),
			'title'        => sprintf( __( 'Туры %s из Москвы — цены туроператора | Жираф', 'zhiraf-ufa' ), $to ),
			'description'  => sprintf( __( '%s: пакетные туры с вылетом из Москвы (перелёт + отель) по ценам туроператора Библио-Глобус. Перелёт Уфа — Москва подберём отдельно.', 'zhiraf-ufa' ), $full ),
			'image'        => 'country-' . $slug . '.jpg',
			'image_alt'    => $alts[ $slug ] ?? $full,
			'hero_lead'    => sprintf( __( 'Направление %s: пакетные туры с перелётом из Москвы и проживанием в отеле по данным туроператора Библио-Глобус. Перелёт из Уфы в Москву в стоимость не входит — менеджер подберёт удобную стыковку.', 'zhiraf-ufa' ), $full ),
			'quiz_prefill' => sprintf( __( '%s, вылет из Москвы', 'zhiraf-ufa' ), $full ),
			'faq'          => array(
				array(
					'q' => __( 'Входит ли в цену перелёт из Уфы?', 'zhiraf-ufa' ),
					'a' => __( 'Нет. Цены на странице рассчитаны туроператором для вылета из Москвы. Перелёт Уфа — Москва — Уфа подбирается и оплачивается отдельно, менеджер поможет состыковать рейсы.', 'zhiraf-ufa' ),
				),
				array(
					'q' => __( 'Что входит в указанную цену?', 'zhiraf-ufa' ),
					'a' => __( 'Перелёт из Москвы и проживание в отеле с указанным типом питания для 2 взрослых на указанное число ночей. Трансфер, страховку и другие услуги менеджер уточнит по конкретному предложению; итоговая стоимость фиксируется при бронировании.', 'zhiraf-ufa' ),
				),
			),
		);
		if ( ! empty( $content[ $slug ] ) ) {
			// ТЕКСТ НА ПРОВЕРКУ: long-form sections + FAQ (inc/destination-landings-moscow-content.php).
			$c      = $content[ $slug ];
			$common = zhiraf_ufa_moscow_common_sections( $to, $name );
			if ( 'shri-lanka' === $slug ) {
				$out[ $slug ]['landing_module'] = 'battle';
				$out[ $slug ]['vibe_section']   = false;
				$out[ $slug ]['faq_title']      = __( 'Частые вопросы о Шри-Ланке', 'zhiraf-ufa' );
				$out[ $slug ]['hero_lead']      = __( 'Пляжи юго-запада и чайные холмы с вылетом из Москвы. Поможем состыковать перелёт из Уфы и выбрать между спокойной Унаватуной и динамичной Хиккадувой.', 'zhiraf-ufa' );
				$out[ $slug ]['hero_badges']    = array(
					array( 'icon' => '🛂', 'text' => __( 'ETA онлайн до вылета', 'zhiraf-ufa' ) ),
					array( 'icon' => '✈', 'text' => __( 'Вылет из Москвы • стыковка из Уфы', 'zhiraf-ufa' ) ),
					array( 'icon' => '🌊', 'text' => __( 'Сезон побережья под даты', 'zhiraf-ufa' ) ),
				);
				$out[ $slug ]['faq']            = array(
					array(
						'q' => __( 'Входит ли в цену перелёт из Уфы?', 'zhiraf-ufa' ),
						'a' => __( 'Нет. Цены на карточках — для вылета из Москвы. Перелёт Уфа — Москва подбираем отдельно, помогаем состыковать рейсы.', 'zhiraf-ufa' ),
					),
					array(
						'q' => __( 'Сколько длится перелёт из Москвы на Шри-Ланку?', 'zhiraf-ufa' ),
						'a' => __( 'Обычно около 8–9 часов в одну сторону (прямой или стыковочный рейс) — точное время смотрите на карточке тура.', 'zhiraf-ufa' ),
					),
					array(
						'q' => __( 'Нужна ли виза и как оформить ETA?', 'zhiraf-ufa' ),
						'a' => $c['entry'][0],
					),
					array(
						'q' => __( 'Унаватуна или Хиккадува — что выбрать?', 'zhiraf-ufa' ),
						'a' => __( 'Унаватуна — спокойная бухта с рифом для семей. Хиккадува — волны, серф, черепахи и вечерняя жизнь. Сравнение — в блоке выше; зимой чаще едут на юго-запад, летом смотрят восточное побережье (Пасикуда).', 'zhiraf-ufa' ),
					),
					array(
						'q' => __( 'Когда лучше ехать и куда смотреть по сезону?', 'zhiraf-ufa' ),
						'a' => $c['season'][0],
					),
					array(
						'q' => __( 'Какая валюта и как платить на месте?', 'zhiraf-ufa' ),
						'a' => __( 'Местная валюта — шри-ланкийская рупия. Российские карты Visa и Mastercard за рубежом не работают — заранее продумайте наличные или альтернативные способы оплаты.', 'zhiraf-ufa' ),
					),
				);
			} else {
			$out[ $slug ]['extra_sections'] = array(
				array( 'h2' => __( 'Когда ехать', 'zhiraf-ufa' ), 'paragraphs' => $c['season'] ),
				array( 'h2' => __( 'Курорты в турах', 'zhiraf-ufa' ), 'paragraphs' => $c['resorts'] ),
				array( 'h2' => __( 'Что входит в цену на сайте', 'zhiraf-ufa' ), 'paragraphs' => $common['price'] ),
				array( 'h2' => __( 'Въезд для граждан России', 'zhiraf-ufa' ), 'paragraphs' => array_merge( $c['entry'], array( $content['_entry_note'] ) ) ),
				array( 'h2' => __( 'Перелёт: из Москвы, без участка Уфа — Москва', 'zhiraf-ufa' ), 'paragraphs' => $common['flight'] ),
				array( 'h2' => __( 'Полезно знать', 'zhiraf-ufa' ), 'paragraphs' => $c['tips'] ),
				array( 'h2' => __( 'Как забронировать', 'zhiraf-ufa' ), 'paragraphs' => $common['how'] ),
			);
			$out[ $slug ]['faq'] = array_merge( $out[ $slug ]['faq'], $c['faq'] );
			}
		}
	}
	return $out;
}
