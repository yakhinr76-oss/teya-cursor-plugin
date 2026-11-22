<?php
/**
 * Unified lead form: messenger picker, pill phone, message bubble, consents.
 *
 * @package Zhiraf_Ufa
 * @var array $args See zhiraf_ufa_render_unified_lead_form().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$form_id             = ! empty( $args['form_id'] ) ? (string) $args['form_id'] : '';
$form_context        = ! empty( $args['form_context'] ) ? sanitize_key( (string) $args['form_context'] ) : 'lead';
$extra_class         = ! empty( $args['form_class'] ) ? (string) $args['form_class'] : '';
$consent_id          = ! empty( $args['consent_id'] ) ? sanitize_key( (string) $args['consent_id'] ) : $form_context;
$messenger_variant   = ! empty( $args['messenger_variant'] ) ? sanitize_key( (string) $args['messenger_variant'] ) : 'quiz';
$messenger_label     = ! empty( $args['messenger_label'] ) ? (string) $args['messenger_label'] : __( 'Куда отправить подборку:', 'zhiraf-ufa' );
$message             = isset( $args['message'] ) ? (string) $args['message'] : '';
$message_placeholder = ! empty( $args['message_placeholder'] ) ? (string) $args['message_placeholder'] : __( 'Даты, состав, бюджет', 'zhiraf-ufa' );
$tour                = isset( $args['tour'] ) ? (string) $args['tour'] : '';
$destination         = isset( $args['destination'] ) ? (string) $args['destination'] : '';
$destination_slug    = isset( $args['destination_slug'] ) ? (string) $args['destination_slug'] : '';
$uon_hotel_id        = isset( $args['uon_hotel_id'] ) ? (string) $args['uon_hotel_id'] : '';
$express_pick        = isset( $args['express_pick'] ) ? (string) $args['express_pick'] : '';
$lead_tour_attr      = ! empty( $args['lead_tour_attr'] );
$tour_field_id       = ! empty( $args['tour_field_id'] ) ? (string) $args['tour_field_id'] : '';
$uon_field_id        = ! empty( $args['uon_field_id'] ) ? (string) $args['uon_field_id'] : '';
$submit_label        = ! empty( $args['submit_label'] ) ? (string) $args['submit_label'] : __( 'Получить расчёт туров в WhatsApp →', 'zhiraf-ufa' );
$form_classes        = trim( 'zhiraf-form unified-lead-form dest-landing-lead-form ' . $extra_class );
?>
<form
	<?php echo '' !== $form_id ? 'id="' . esc_attr( $form_id ) . '"' : ''; ?>
	class="<?php echo esc_attr( $form_classes ); ?>"
	data-action="zhiraf_lead"
	data-form-context="<?php echo esc_attr( $form_context ); ?>"
	data-messenger-variant="<?php echo esc_attr( $messenger_variant ); ?>"
>
	<?php
	if ( function_exists( 'zhiraf_ufa_render_messenger_picker' ) ) {
		zhiraf_ufa_render_messenger_picker(
			array(
				'name'    => 'lead_messenger',
				'default' => 'WhatsApp',
				'label'   => $messenger_label,
			)
		);
	}
	?>
	<input type="hidden" name="contact_channel" value="WhatsApp" />
	<input type="hidden" name="name" value="<?php echo esc_attr( __( 'Гость сайта', 'zhiraf-ufa' ) ); ?>" />

	<label class="form-label unified-lead-form__phone-field">
		<span class="unified-lead-form__label"><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span>
		<input
			type="tel"
			name="phone"
			placeholder="<?php esc_attr_e( '+7 …', 'zhiraf-ufa' ); ?>"
			required
			class="form-input unified-lead-form__phone"
			autocomplete="tel"
			inputmode="tel"
			data-phone-mask
		/>
	</label>

	<div class="unified-lead-form__message-wrap">
		<textarea
			name="message"
			class="form-input unified-lead-form__message"
			rows="4"
			placeholder="<?php echo esc_attr( $message_placeholder ); ?>"
		><?php echo esc_textarea( $message ); ?></textarea>
	</div>

	<div class="zhiraf-hp" aria-hidden="true">
		<input type="hidden" name="tour" value="<?php echo esc_attr( $tour ); ?>"<?php echo $lead_tour_attr ? ' data-lead-tour' : ''; ?><?php echo '' !== $tour_field_id ? ' id="' . esc_attr( $tour_field_id ) . '"' : ''; ?> />
		<?php if ( '' !== $destination ) : ?>
			<input type="hidden" name="destination" value="<?php echo esc_attr( $destination ); ?>" />
		<?php endif; ?>
		<?php if ( '' !== $destination_slug ) : ?>
			<input type="hidden" name="destination_slug" value="<?php echo esc_attr( $destination_slug ); ?>" />
		<?php endif; ?>
		<input type="hidden" name="uon_hotel_id" value="<?php echo esc_attr( $uon_hotel_id ); ?>"<?php echo '' !== $uon_field_id ? ' id="' . esc_attr( $uon_field_id ) . '"' : ''; ?> />
		<input type="hidden" name="express_pick" value="<?php echo esc_attr( $express_pick ); ?>" data-lead-express />
	</div>

	<?php zhiraf_ufa_consent_fields( $consent_id ); ?>

	<button type="submit" class="btn btn--primary btn--lg btn--block rounded-xl unified-lead-form__submit" data-messenger-submit-label>
		<?php echo esc_html( $submit_label ); ?>
	</button>
	<p class="form-status unified-lead-form__status" role="status" aria-live="polite"></p>
</form>