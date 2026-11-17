<?php
/**
 * Kemer vs Side — hybrid lead form + messengers.
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
<section class="section turciya-compare-lead" id="dest-inquiry">
	<div class="container container--narrow turciya-compare-lead__inner">
		<h2 class="turciya-compare-lead__title">
			<?php esc_html_e( 'Сравним Кемер и Сиде под ваш бюджет за 15 минут', 'zhiraf-ufa' ); ?>
		</h2>
		<p class="turciya-compare-lead__text">
			<?php esc_html_e( 'Подберём 2–3 отеля с актуальными ценами на рейс из Уфы и пришлём в удобный мессенджер.', 'zhiraf-ufa' ); ?>
		</p>
		<form class="zhiraf-form dest-landing-lead-form turciya-compare-lead__form" data-action="zhiraf_lead" data-form-context="dest-turciya-compare">
			<div class="dest-landing-lead-form__fields">
				<label class="form-label dest-landing-lead-form__field">
					<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span>
					<input type="tel" name="phone" placeholder="<?php esc_attr_e( '+7 …', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" inputmode="tel" />
				</label>
				<textarea
					name="message"
					class="form-input dest-landing-lead-form__message"
					rows="2"
					placeholder="<?php esc_attr_e( 'Кемер или Сиде, даты, возраст детей, бюджет', 'zhiraf-ufa' ); ?>"
				><?php echo esc_textarea( $wa_text ); ?></textarea>
				<?php
				if ( function_exists( 'zhiraf_ufa_render_messenger_picker' ) ) {
					zhiraf_ufa_render_messenger_picker(
						array(
							'name'    => 'contact_channel',
							'default' => 'WhatsApp',
							'label'   => __( 'Куда отправить сравнение:', 'zhiraf-ufa' ),
						)
					);
				}
				?>
				<div class="zhiraf-hp" aria-hidden="true">
					<input type="hidden" name="tour" value="<?php echo esc_attr( __( 'Турция, сравнение Кемер и Сиде', 'zhiraf-ufa' ) ); ?>" data-lead-tour />
					<input type="hidden" name="destination" value="Турция" />
					<input type="hidden" name="destination_slug" value="turciya" />
				</div>
				<?php
				if ( function_exists( 'zhiraf_ufa_consent_fields' ) ) {
					zhiraf_ufa_consent_fields( 'dest-turciya-compare' );
				}
				?>
				<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Получить сравнение', 'zhiraf-ufa' ); ?></button>
			</div>
			<p class="form-status" role="status" aria-live="polite"></p>
		</form>
	</div>
</section>