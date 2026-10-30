<?php
/**
 * About page («О нас») trust blocks and team portrait helpers.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filenames that must not be used as team portraits (stock / misleading).
 *
 * @return string[]
 */
function zhiraf_ufa_team_stock_photo_denylist() {
	return apply_filters(
		'zhiraf_ufa_team_stock_photo_denylist',
		array(
			'office-ufa.jpg',
			'team-travel.jpg',
			'travel-passport.jpg',
		)
	);
}

/**
 * Whether a theme image file exists on disk.
 *
 * @param string $filename Basename in assets/images.
 */
function zhiraf_ufa_theme_image_exists( $filename ) {
	$filename = ltrim( (string) $filename, '/' );
	if ( '' === $filename ) {
		return false;
	}
	$path = ZHIRAF_UFA_DIR . '/assets/images/' . $filename;

	return is_readable( $path );
}

/**
 * Resolve portrait filename for a roster member (empty = show initials placeholder).
 *
 * @param array<string,mixed> $member Team member row.
 */
/**
 * Show «Кто подберёт ваш тур» block on the homepage.
 * Off by default until all team portraits are ready; full roster stays on /o-nas/.
 *
 * @return bool
 */
function zhiraf_ufa_show_home_team_teaser() {
	return (bool) apply_filters( 'zhiraf_ufa_show_home_team_teaser', false );
}

function zhiraf_ufa_team_member_portrait( $member ) {
	$file = ! empty( $member['image'] ) ? (string) $member['image'] : '';
	if ( '' === $file ) {
		return '';
	}
	if ( in_array( $file, zhiraf_ufa_team_stock_photo_denylist(), true ) ) {
		return '';
	}
	if ( ! zhiraf_ufa_theme_image_exists( $file ) ) {
		return '';
	}

	return $file;
}

/**
 * Initials for avatar placeholder (Cyrillic-friendly first grapheme).
 *
 * @param string $name Display name.
 */
function zhiraf_ufa_team_member_initials( $name ) {
	$name = trim( (string) $name );
	if ( '' === $name ) {
		return '?';
	}
	if ( function_exists( 'mb_substr' ) ) {
		return mb_strtoupper( mb_substr( $name, 0, 1, 'UTF-8' ), 'UTF-8' );
	}

	return strtoupper( substr( $name, 0, 1 ) );
}

/**
 * Trust metrics for the about page hero strip.
 *
 * @return array<int,array{value:string,label:string,detail?:string}>
 */
function zhiraf_ufa_about_trust_metrics() {
	return apply_filters(
		'zhiraf_ufa_about_trust_metrics',
		array(
			array(
				'value'  => '2011',
				'label'  => __( 'год основания', 'zhiraf-ufa' ),
				'detail' => __( 'Работаем в Уфе без смены юрлица', 'zhiraf-ufa' ),
			),
			array(
				'value'  => 'РТА 0012822',
				'label'  => __( 'реестр турагентств', 'zhiraf-ufa' ),
				'detail' => __( 'ООО «Жираф», официальный статус', 'zhiraf-ufa' ),
			),
			array(
				'value'  => '24/7',
				'label'  => __( 'на связи в поездке', 'zhiraf-ufa' ),
				'detail' => __( 'Менеджер до возвращения домой', 'zhiraf-ufa' ),
			),
			array(
				'value'  => '100%',
				'label'  => __( 'белые договоры', 'zhiraf-ufa' ),
				'detail' => __( '132-ФЗ, договор и фискальный чек', 'zhiraf-ufa' ),
			),
		)
	);
}

/**
 * Office / «живые» фото для блока «О нас» (положите файлы в assets/images).
 *
 * @return array<int,array{file:string,caption:string,slot:string}>
 */
function zhiraf_ufa_about_office_gallery() {
	return apply_filters(
		'zhiraf_ufa_about_office_gallery',
		array(
			array(
				'file'              => 'about-office-gagarin.jpg',
				'placeholder_title' => __( 'Офис на ул. Гагарина, 74/1', 'zhiraf-ufa' ),
				'caption'           => __( 'Офис 3 — можно приехать лично: покажем договор, реестр РТА и подберём тур за чашкой кофе.', 'zhiraf-ufa' ),
				'slot'              => 'primary',
			),
			array(
				'file'              => 'about-office-desk.jpg',
				'placeholder_title' => __( 'Живая команда в Уфе', 'zhiraf-ufa' ),
				'caption'           => __( 'Менеджеры на связи по телефону и в мессенджере — без анонимного колл-центра.', 'zhiraf-ufa' ),
				'slot'              => 'secondary',
			),
		)
	);
}

/**
 * Brand WhatsApp URL (Customizer or default office line).
 */
function zhiraf_ufa_brand_whatsapp_url() {
	$url = trim( (string) get_theme_mod( 'zhiraf_whatsapp_url', '' ) );
	if ( '' !== $url ) {
		return $url;
	}

	return apply_filters( 'zhiraf_ufa_default_whatsapp_url', 'https://wa.me/79018105018' );
}

/**
 * Append URL-encoded prefill text to a WhatsApp deep link.
 *
 * @param string $base_url Theme mod or wa.me URL.
 * @param string $text     Prefill message.
 */
function zhiraf_ufa_whatsapp_url_with_text( $base_url, $text ) {
	$base_url = trim( (string) $base_url );
	$text     = trim( (string) $text );
	if ( '' === $base_url ) {
		return '';
	}
	if ( '' === $text ) {
		return $base_url;
	}

	$sep = ( false !== strpos( $base_url, '?' ) ) ? '&' : '?';

	return $base_url . $sep . 'text=' . rawurlencode( $text );
}

/**
 * Messenger URL for a team member (WhatsApp prefill or explicit URL).
 *
 * @param array<string,mixed> $member Roster row.
 */
function zhiraf_ufa_team_member_whatsapp_url( $member ) {
	if ( ! empty( $member['whatsapp_url'] ) ) {
		return (string) $member['whatsapp_url'];
	}
	$base = zhiraf_ufa_brand_whatsapp_url();
	if ( '' === $base ) {
		return '';
	}
	$prefill = ! empty( $member['whatsapp_prefill'] ) ? (string) $member['whatsapp_prefill'] : '';

	return zhiraf_ufa_whatsapp_url_with_text( $base, $prefill );
}

/**
 * Yandex Maps link for the Ufa office.
 */
function zhiraf_ufa_office_map_url() {
	return 'https://yandex.ru/maps/?text=' . rawurlencode( 'Уфа, улица Юрия Гагарина, 74/1' );
}
