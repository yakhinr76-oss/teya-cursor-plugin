<?php
/**
 * Messenger channel picker (MAX / WhatsApp / Telegram).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline icon markup for messenger picker options.
 *
 * @param string $channel max|whatsapp|telegram.
 * @return string
 */
function zhiraf_ufa_messenger_picker_icon( $channel ) {
	switch ( sanitize_key( $channel ) ) {
		case 'max':
			return '<span class="messenger-picker__icon messenger-picker__icon--max" aria-hidden="true">' . zhiraf_ufa_social_icon_svg( 'max', 20 ) . '</span>';
		case 'whatsapp':
			return '<span class="messenger-picker__icon messenger-picker__icon--wa" aria-hidden="true">' . zhiraf_ufa_social_icon_svg( 'whatsapp', 20 ) . '</span>';
		case 'telegram':
			return '<span class="messenger-picker__icon messenger-picker__icon--tg" aria-hidden="true">' . zhiraf_ufa_social_icon_svg( 'telegram', 20 ) . '</span>';
		default:
			return '';
	}
}

/**
 * Render 3-column messenger picker.
 *
 * @param array<string,mixed> $args {
 *   @type string $name     Radio input name (lead_messenger|quiz_channel).
 *   @type string $default  Checked value: MAX|WhatsApp|Telegram.
 *   @type string $label    Field legend (optional).
 * }
 */
function zhiraf_ufa_render_messenger_picker( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'name'    => 'lead_messenger',
			'default' => 'WhatsApp',
			'label'   => __( 'Где вам удобнее получить подборку:', 'zhiraf-ufa' ),
		)
	);

	get_template_part(
		'template-parts/shared/messenger-channel-picker',
		null,
		array(
			'name'    => (string) $args['name'],
			'default' => (string) $args['default'],
			'label'   => (string) $args['label'],
		)
	);
}

/**
 * Messenger deep links with a prefilled client message.
 *
 * @param string $text Prefill body.
 * @return array{whatsapp:string,max:string,telegram:string}
 */
function zhiraf_ufa_messenger_channel_urls( $text ) {
	$text = trim( (string) $text );
	$wa_base = function_exists( 'zhiraf_ufa_brand_whatsapp_url' ) ? zhiraf_ufa_brand_whatsapp_url() : 'https://wa.me/79018105018';
	$wa      = function_exists( 'zhiraf_ufa_whatsapp_url_with_text' )
		? zhiraf_ufa_whatsapp_url_with_text( $wa_base, $text )
		: $wa_base;
	$page    = function_exists( 'zhiraf_ufa_lead_source_url' ) ? zhiraf_ufa_lead_source_url() : home_url( '/' );
	if ( ! $page ) {
		$page = home_url( '/' );
	}

	return array(
		'whatsapp' => $wa,
		'max'      => 'https://max.ru/:share?text=' . rawurlencode( $text ),
		'telegram' => 'https://t.me/share/url?url=' . rawurlencode( $page ) . '&text=' . rawurlencode( $text ),
	);
}

/**
 * Full hub final CTA form (fields + messenger + AJAX).
 *
 * @param array<string,mixed> $args See template-parts/hub/hub-offer-lead-form.php.
 */
function zhiraf_ufa_render_hub_offer_lead_form( $args = array() ) {
	get_template_part( 'template-parts/hub/hub-offer-lead-form', null, is_array( $args ) ? $args : array() );
}
