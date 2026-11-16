<?php
/**
 * One-screen hotel inquiry: timing chips + messenger picker + phone.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div
	id="hotel-inquiry-modal"
	class="quiz-modal hotel-inquiry-modal"
	role="dialog"
	aria-modal="true"
	aria-labelledby="hotel-inquiry-title"
	hidden
	inert
>
	<div class="quiz-modal__backdrop" data-hotel-inquiry-close tabindex="-1"></div>
	<div class="quiz-modal__panel hotel-inquiry-modal__panel">
		<button type="button" class="quiz-modal__close" data-hotel-inquiry-close aria-label="<?php esc_attr_e( 'Закрыть', 'zhiraf-ufa' ); ?>">×</button>

		<div id="hotel-inquiry-body" class="hotel-inquiry-modal__body">
			<h2 id="hotel-inquiry-title" class="quiz-modal__title"><?php esc_html_e( 'Узнать даты и точную цену', 'zhiraf-ufa' ); ?></h2>
			<p class="hotel-inquiry-modal__subtitle" data-hotel-inquiry-subtitle>
				<?php esc_html_e( 'Отель:', 'zhiraf-ufa' ); ?>
				<span data-hotel-inquiry-name></span>
				<?php esc_html_e( '(прямой рейс из Уфы)', 'zhiraf-ufa' ); ?>
			</p>

			<form
				id="hotel-inquiry-form"
				class="zhiraf-form hotel-inquiry-modal__form"
				data-action="zhiraf_lead"
				data-form-context="hotel-inquiry"
			>
				<input type="hidden" name="hotel_inquiry" value="1" />
				<input type="hidden" name="phone_only" value="1" />
				<input type="hidden" name="name" value="<?php esc_attr_e( 'Гость сайта', 'zhiraf-ufa' ); ?>" />
				<input type="hidden" name="contact_channel" value="WhatsApp" />
				<input type="hidden" name="hotel_name" value="" data-hotel-inquiry-field="name" />
				<input type="hidden" name="uon_hotel_id" value="" data-hotel-inquiry-field="id" />

				<fieldset class="hotel-inquiry-modal__timing">
					<legend class="hotel-inquiry-modal__timing-label"><?php esc_html_e( 'Когда планируете вылет?', 'zhiraf-ufa' ); ?></legend>
					<div class="quiz-chips hotel-inquiry-modal__chips">
						<label class="quiz-chip">
							<input type="radio" name="hotel_timing" value="<?php esc_attr_e( 'В этом месяце', 'zhiraf-ufa' ); ?>" />
							<?php esc_html_e( 'В этом месяце', 'zhiraf-ufa' ); ?>
						</label>
						<label class="quiz-chip">
							<input type="radio" name="hotel_timing" value="<?php esc_attr_e( 'Следующий месяц', 'zhiraf-ufa' ); ?>" />
							<?php esc_html_e( 'Следующий месяц', 'zhiraf-ufa' ); ?>
						</label>
						<label class="quiz-chip">
							<input type="radio" name="hotel_timing" value="<?php esc_attr_e( 'Лето / Бархатный сезон', 'zhiraf-ufa' ); ?>" />
							<?php esc_html_e( 'Лето / Бархатный сезон', 'zhiraf-ufa' ); ?>
						</label>
						<label class="quiz-chip">
							<input type="radio" name="hotel_timing" value="<?php esc_attr_e( 'Пока прицениваюсь', 'zhiraf-ufa' ); ?>" />
							<?php esc_html_e( 'Пока прицениваюсь', 'zhiraf-ufa' ); ?>
						</label>
					</div>
				</fieldset>

				<?php
				zhiraf_ufa_render_messenger_picker(
					array(
						'name'    => 'hotel_messenger',
						'default' => 'WhatsApp',
						'label'   => __( 'Куда прислать расчёт:', 'zhiraf-ufa' ),
					)
				);
				?>

				<label class="form-field hotel-inquiry-modal__phone" for="hotel-inquiry-phone">
					<span><?php esc_html_e( 'Ваш номер телефона:', 'zhiraf-ufa' ); ?> <span class="lead-magnet__required">*</span></span>
					<input
						type="tel"
						id="hotel-inquiry-phone"
						name="phone"
						class="lead-magnet__phone lead-magnet__input"
						placeholder="+7 (___) ___-__-__"
						required
						autocomplete="tel"
						inputmode="tel"
						data-phone-mask
					/>
				</label>

				<button type="submit" class="btn btn--primary btn--lg btn--block hotel-inquiry-modal__submit" data-messenger-submit-label>
					<?php esc_html_e( 'Получить расчёт в WhatsApp →', 'zhiraf-ufa' ); ?>
				</button>

				<?php zhiraf_ufa_consent_fields( 'hotel-inquiry' ); ?>

				<p class="form-status hotel-inquiry-modal__status" role="status" aria-live="polite"></p>
			</form>
		</div>

		<div id="hotel-inquiry-success" class="quiz-modal__success hotel-inquiry-modal__success" hidden>
			<span class="quiz-final-head__icon" aria-hidden="true">✓</span>
			<h3 class="quiz-step__title"><?php esc_html_e( 'Заявка принята!', 'zhiraf-ufa' ); ?></h3>
			<p class="hotel-inquiry-modal__success-lead" data-hotel-inquiry-success-text></p>
			<a
				href="#"
				class="btn btn--primary btn--lg btn--block"
				data-hotel-inquiry-open-chat
				target="_blank"
				rel="noopener noreferrer"
			>
				<?php esc_html_e( 'Открыть чат с расчётом →', 'zhiraf-ufa' ); ?>
			</a>
			<button type="button" class="btn btn--ghost hotel-inquiry-modal__done" data-hotel-inquiry-close>
				<?php esc_html_e( 'Закрыть', 'zhiraf-ufa' ); ?>
			</button>
		</div>
	</div>
</div>