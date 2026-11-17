<?php
/**
 * Turkey curated — quick lead in modal (messenger picker on send).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wa_text = function_exists( 'zhiraf_ufa_turciya_resort_compare_wa_text' )
	? zhiraf_ufa_turciya_resort_compare_wa_text()
	: __( 'Здравствуйте! Сравните, пожалуйста, Кемер и Сиде под наш бюджет — вылет из Уфы, семья с детьми.', 'zhiraf-ufa' );
?>
<div
	id="turciya-lead-modal"
	class="quiz-modal turciya-lead-modal"
	role="dialog"
	aria-modal="true"
	aria-labelledby="turciya-lead-modal-title"
	hidden
	inert
>
	<div class="quiz-modal__backdrop" data-turciya-lead-close tabindex="-1"></div>
	<div class="quiz-modal__panel turciya-lead-modal__panel">
		<button type="button" class="quiz-modal__close" data-turciya-lead-close aria-label="<?php esc_attr_e( 'Закрыть', 'zhiraf-ufa' ); ?>">×</button>
		<div class="quiz-modal__body">
			<p class="home-quiz__eyebrow home-quiz__eyebrow--modal"><?php esc_html_e( 'Подбор за 15 минут', 'zhiraf-ufa' ); ?></p>
			<h2 id="turciya-lead-modal-title" class="home-quiz__title home-quiz__title--modal">
				<?php esc_html_e( 'Сравним Кемер и Сиде под ваш бюджет', 'zhiraf-ufa' ); ?>
			</h2>
			<p class="home-quiz__lead home-quiz__lead--modal">
				<?php esc_html_e( 'Подберём 2–3 отеля с актуальными ценами на рейс из Уфы. Выберите мессенджер — пришлём расчёт туда.', 'zhiraf-ufa' ); ?>
			</p>
			<form
				id="turciya-lead-modal-form"
				class="zhiraf-form dest-landing-lead-form turciya-lead-modal__form"
				data-action="zhiraf_lead"
				data-form-context="dest-turciya-compare"
			>
				<?php
				if ( function_exists( 'zhiraf_ufa_render_messenger_picker' ) ) {
					zhiraf_ufa_render_messenger_picker(
						array(
							'name'    => 'contact_channel',
							'default' => 'WhatsApp',
							'label'   => __( 'Куда отправить подборку:', 'zhiraf-ufa' ),
						)
					);
				}
				?>
				<label class="form-label dest-landing-lead-form__field">
					<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span>
					<input type="tel" name="phone" placeholder="<?php esc_attr_e( '+7 …', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" inputmode="tel" />
				</label>
				<textarea
					name="message"
					class="form-input dest-landing-lead-form__message"
					rows="3"
					placeholder="<?php esc_attr_e( 'Кемер или Сиде, даты, возраст детей, бюджет', 'zhiraf-ufa' ); ?>"
				><?php echo esc_textarea( $wa_text ); ?></textarea>
				<div class="zhiraf-hp" aria-hidden="true">
					<input type="hidden" name="tour" value="<?php echo esc_attr( __( 'Турция, сравнение Кемер и Сиде', 'zhiraf-ufa' ) ); ?>" data-lead-tour />
					<input type="hidden" name="destination" value="Турция" />
					<input type="hidden" name="destination_slug" value="turciya" />
				</div>
				<?php
				if ( function_exists( 'zhiraf_ufa_consent_fields' ) ) {
					zhiraf_ufa_consent_fields( 'dest-turciya-compare-modal' );
				}
				?>
				<button type="submit" class="btn btn--primary btn--lg btn--block" data-messenger-submit-label>
					<?php esc_html_e( 'Отправить в WhatsApp →', 'zhiraf-ufa' ); ?>
				</button>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
		</div>
	</div>
</div>