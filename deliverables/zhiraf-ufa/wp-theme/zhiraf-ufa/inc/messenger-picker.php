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
