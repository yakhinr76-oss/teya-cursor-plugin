<?php
/**
 * Hub offer final CTA — lead form with messenger picker.
 *
 * @package Zhiraf_Ufa
 * @var array $args {
 *   @type string $context         data-form-context.
 *   @type string $quiz_prefill    Hidden tour prefill.
 *   @type string $consent_id      Consent fields id suffix.
 *   @type string $submit_base     Button label base (without messenger).
 *   @type string $messenger_label Picker legend.
 *   @type string $form_class      Extra class on form element.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$context         = ! empty( $args['context'] ) ? sanitize_key( (string) $args['context'] ) : 'hub-offer';
$quiz_prefill    = ! empty( $args['quiz_prefill'] ) ? (string) $args['quiz_prefill'] : '';
$consent_id      = ! empty( $args['consent_id'] ) ? sanitize_key( (string) $args['consent_id'] ) : $context;
$submit_base     = ! empty( $args['submit_base'] ) ? (string) $args['submit_base'] : __( 'Отправить запрос', 'zhiraf-ufa' );
$messenger_label = ! empty( $args['messenger_label'] ) ? (string) $args['messenger_label'] : __( 'Где удобнее связаться:', 'zhiraf-ufa' );
$form_class      = ! empty( $args['form_class'] ) ? (string) $args['form_class'] : '';
$form_classes    = trim( 'zhiraf-form dest-landing-lead-form hub-offer-lead-form ' . $form_class );
?>
<form
	class="<?php echo esc_attr( $form_classes ); ?>"
	data-action="zhiraf_lead"
	data-form-context="<?php echo esc_attr( $context ); ?>"
	data-hub-submit-base="<?php echo esc_attr( $submit_base ); ?>"
>
	<div class="hub-offer-lead-form__grid">
		<label class="form-label hub-offer-lead-form__field">
			<span class="hub-offer-lead-form__label"><?php esc_html_e( 'Даты вылета и ночей', 'zhiraf-ufa' ); ?></span>
			<input
				type="text"
				name="hub_dates_nights"
				class="form-input"
				required
				autocomplete="off"
				placeholder="<?php esc_attr_e( 'Например: ближайшие даты, 7 ночей', 'zhiraf-ufa' ); ?>"
			/>
		</label>
		<label class="form-label hub-offer-lead-form__field">
			<span class="hub-offer-lead-form__label"><?php esc_html_e( 'Бюджет на двоих', 'zhiraf-ufa' ); ?></span>
			<input
				type="text"
				name="hub_budget"
				class="form-input"
				required
				autocomplete="off"
				inputmode="numeric"
				placeholder="<?php esc_attr_e( 'Ориентир, ₽ — например 120 000', 'zhiraf-ufa' ); ?>"
			/>
		</label>
		<label class="form-label hub-offer-lead-form__field">
			<span class="hub-offer-lead-form__label"><?php esc_html_e( 'Ваше имя', 'zhiraf-ufa' ); ?></span>
			<input type="text" name="name" class="form-input" required autocomplete="name" placeholder="<?php esc_attr_e( 'Имя', 'zhiraf-ufa' ); ?>" />
		</label>
		<label class="form-label hub-offer-lead-form__field">
			<span class="hub-offer-lead-form__label"><?php esc_html_e( 'Телефон или контакт', 'zhiraf-ufa' ); ?></span>
			<input
				type="tel"
				name="phone"
				class="form-input"
				required
				autocomplete="tel"
				inputmode="tel"
				data-phone-mask
				placeholder="<?php esc_attr_e( '+7 (___) ___-__-__', 'zhiraf-ufa' ); ?>"
			/>
		</label>
	</div>

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
	<input type="hidden" name="tour" value="<?php echo esc_attr( $quiz_prefill ); ?>" />
	<input type="hidden" name="message" value="" />

	<?php
	if ( function_exists( 'zhiraf_ufa_consent_fields' ) ) {
		zhiraf_ufa_consent_fields( $consent_id );
	}
	?>

	<button type="submit" class="btn btn--primary btn--lg btn--block rounded-xl hub-offer-lead-form__submit" data-messenger-submit-label>
		<?php echo esc_html( $submit_base ); ?>
	</button>

	<p class="form-status hub-offer-lead-form__status" role="status" aria-live="polite"></p>
</form>