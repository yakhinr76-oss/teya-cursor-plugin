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
 * Prefill text for hub messenger deep links.
 *
 * @param string $hub hot|ai.
 */
function zhiraf_ufa_hub_messenger_prefill_text( $hub = 'hot' ) {
	$hub = sanitize_key( (string) $hub );
	if ( 'ai' === $hub ) {
		return __( 'Здравствуйте! Нужен подбор отеля All Inclusive с вылетом из Уфы. Подскажите, пожалуйста, варианты по датам и бюджету.', 'zhiraf-ufa' );
	}

	return __( 'Здравствуйте! Интересуют горящие или отказные туры с вылетом из Уфы. Подскажите, пожалуйста, ближайшие варианты по датам и бюджету.', 'zhiraf-ufa' );
}

/**
 * Hub final CTA: unified lead form (picker, phone, message, AJAX).
 *
 * @param array<string,mixed> $args {
 *   @type string $hub              hot|ai — контекст префилла.
 *   @type string $lead             Одна поясняющая фраза.
 *   @type string $prefill          Override prefill message.
 *   @type string $messenger_label  Legend picker.
 * }
 */
function zhiraf_ufa_render_hub_messenger_cta( $args = array() ) {
	$args = wp_parse_args(
		is_array( $args ) ? $args : array(),
		array(
			'hub'             => 'hot',
			'lead'            => '',
			'prefill'         => '',
			'messenger_label' => __( 'Куда написать:', 'zhiraf-ufa' ),
		)
	);
	$hub     = sanitize_key( (string) $args['hub'] );
	$prefill = '' !== (string) $args['prefill']
		? (string) $args['prefill']
		: zhiraf_ufa_hub_messenger_prefill_text( $hub );
	$context = 'ai' === $hub ? 'ai-hub-final' : 'hot-hub-final';

	if ( '' !== (string) $args['lead'] ) {
		echo '<p class="hub-offer-unified-lead__lead">' . esc_html( (string) $args['lead'] ) . '</p>';
	}

	if ( function_exists( 'zhiraf_ufa_render_unified_lead_form' ) ) {
		zhiraf_ufa_render_unified_lead_form(
			array(
				'form_context'      => $context,
				'consent_id'        => $context,
				'messenger_variant' => 'quiz',
				'messenger_label'   => (string) $args['messenger_label'],
				'message'           => $prefill,
				'tour'              => $prefill,
				'form_class'        => 'hub-offer-unified-lead',
			)
		);
	}
}

/**
 * @deprecated Use zhiraf_ufa_render_hub_messenger_cta().
 */
function zhiraf_ufa_render_hub_offer_lead_form( $args = array() ) {
	$hub = 'ai';
	if ( ! empty( $args['context'] ) && false !== mb_stripos( (string) $args['context'], 'hot', 0, 'UTF-8' ) ) {
		$hub = 'hot';
	}
	zhiraf_ufa_render_hub_messenger_cta(
		array(
			'hub'             => $hub,
			'messenger_label' => ! empty( $args['messenger_label'] ) ? (string) $args['messenger_label'] : __( 'Куда написать:', 'zhiraf-ufa' ),
			'prefill'         => ! empty( $args['quiz_prefill'] ) ? (string) $args['quiz_prefill'] : '',
		)
	);
}
