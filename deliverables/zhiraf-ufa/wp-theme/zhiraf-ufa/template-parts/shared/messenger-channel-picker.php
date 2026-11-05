<?php
/**
 * Shared MAX / WhatsApp / Telegram picker (3 columns).
 *
 * @package Zhiraf_Ufa
 * @var array $args { name, default, label }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$name    = ! empty( $args['name'] ) ? (string) $args['name'] : 'lead_messenger';
$default = ! empty( $args['default'] ) ? (string) $args['default'] : 'WhatsApp';
$label   = ! empty( $args['label'] ) ? (string) $args['label'] : __( 'Где вам удобнее получить подборку:', 'zhiraf-ufa' );

$options = array(
	array(
		'value'   => 'MAX',
		'channel' => 'max',
		'label'   => 'MAX',
		'mod'     => 'max',
	),
	array(
		'value'   => 'WhatsApp',
		'channel' => 'whatsapp',
		'label'   => 'WhatsApp',
		'mod'     => 'wa',
	),
	array(
		'value'   => 'Telegram',
		'channel' => 'telegram',
		'label'   => 'Telegram',
		'mod'     => 'tg',
	),
);
?>
<div class="messenger-picker" data-messenger-picker>
	<p class="messenger-picker__label"><?php echo esc_html( $label ); ?></p>
	<div class="messenger-picker__grid" role="radiogroup" aria-label="<?php esc_attr_e( 'Мессенджер', 'zhiraf-ufa' ); ?>">
		<?php foreach ( $options as $opt ) : ?>
			<label class="messenger-picker__option messenger-picker__option--<?php echo esc_attr( $opt['mod'] ); ?>">
				<input
					type="radio"
					name="<?php echo esc_attr( $name ); ?>"
					value="<?php echo esc_attr( $opt['value'] ); ?>"
					<?php checked( $default, $opt['value'] ); ?>
					<?php
					if ( 'quiz_channel' === $name && 'WhatsApp' === $opt['value'] ) {
						echo ' required';
					}
					?>
				/>
				<span class="messenger-picker__inner">
					<?php echo zhiraf_ufa_messenger_picker_icon( $opt['channel'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="messenger-picker__text"><?php echo esc_html( $opt['label'] ); ?></span>
				</span>
			</label>
		<?php endforeach; ?>
	</div>
</div>
