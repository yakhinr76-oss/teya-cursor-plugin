<?php
/**
 * Curated destination — bottom lead form (phone + messenger).
 *
 * @package Zhiraf_Ufa
 * @var array $args { dest: array, dest_slug: string }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest      = isset( $args['dest'] ) && is_array( $args['dest'] ) ? $args['dest'] : array();
$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : '';
if ( '' === $dest_slug || empty( $dest ) ) {
	return;
}
?>
<section class="section dest-landing-cta" id="dest-lead">
	<div class="container container--narrow dest-landing-cta__inner">
		<h2><?php esc_html_e( 'Готовы обсудить поездку?', 'zhiraf-ufa' ); ?></h2>
		<p>
			<?php
			$cta_lead = ! empty( $dest['cta_lead'] ) ? $dest['cta_lead'] : __( 'Оставьте контакты — перезвоним в рабочее время и предложим 2–4 варианта под ваш бюджет.', 'zhiraf-ufa' );
			echo esc_html( $cta_lead );
			?>
		</p>
		<form class="zhiraf-form dest-landing-lead-form" data-action="zhiraf_lead" data-form-context="dest-<?php echo esc_attr( $dest_slug ); ?>">
			<div class="dest-landing-lead-form__fields">
				<label class="form-label dest-landing-lead-form__field">
					<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Ваше имя', 'zhiraf-ufa' ); ?></span>
					<input type="text" name="name" placeholder="<?php esc_attr_e( 'Как к вам обращаться', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="name" />
				</label>
				<label class="form-label dest-landing-lead-form__field">
					<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span>
					<input type="tel" name="phone" placeholder="<?php esc_attr_e( '+7 …', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" inputmode="tel" />
				</label>
				<?php if ( ! empty( $dest['cta_message_placeholder'] ) ) : ?>
					<textarea name="message" class="form-input dest-landing-lead-form__message" rows="3" placeholder="<?php echo esc_attr( $dest['cta_message_placeholder'] ); ?>"><?php echo esc_textarea( $dest['cta_message_placeholder'] ); ?></textarea>
				<?php endif; ?>
				<label class="form-label dest-landing-lead-form__channel-label">
					<span class="dest-landing-lead-form__channel-text"><?php esc_html_e( 'Куда отправить подборку', 'zhiraf-ufa' ); ?></span>
					<select name="contact_channel" class="form-input">
						<option value="<?php esc_attr_e( 'WhatsApp', 'zhiraf-ufa' ); ?>" selected><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></option>
						<option value="<?php esc_attr_e( 'Telegram', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Telegram', 'zhiraf-ufa' ); ?></option>
						<option value="<?php esc_attr_e( 'MAX', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'MAX', 'zhiraf-ufa' ); ?></option>
						<option value="<?php esc_attr_e( 'Звонок', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Звонок', 'zhiraf-ufa' ); ?></option>
					</select>
				</label>
				<div class="zhiraf-hp" aria-hidden="true">
					<input type="hidden" name="tour" value="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>" data-lead-tour />
					<input type="hidden" name="destination" value="<?php echo esc_attr( ! empty( $dest['name'] ) ? $dest['name'] : $dest_slug ); ?>" />
					<input type="hidden" name="destination_slug" value="<?php echo esc_attr( $dest_slug ); ?>" />
					<input type="hidden" name="express_pick" value="" data-lead-express />
				</div>
				<?php zhiraf_ufa_consent_fields( 'dest-lead-' . $dest_slug ); ?>
				<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Отправить заявку', 'zhiraf-ufa' ); ?></button>
			</div>
			<p class="form-status" role="status" aria-live="polite"></p>
		</form>
		<p class="dest-landing-cta__alt">
			<button type="button" class="btn btn--outline btn--sm" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>">
				<?php esc_html_e( 'Или пройти квиз', 'zhiraf-ufa' ); ?></button>
		</p>
	</div>
</section>