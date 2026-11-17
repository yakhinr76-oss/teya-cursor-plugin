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
$wa_base = function_exists( 'zhiraf_ufa_brand_whatsapp_url' ) ? zhiraf_ufa_brand_whatsapp_url() : 'https://wa.me/79018105018';
$wa_url  = function_exists( 'zhiraf_ufa_whatsapp_url_with_text' )
	? zhiraf_ufa_whatsapp_url_with_text( $wa_base, $wa_text )
	: $wa_base;
$max_url = function_exists( 'zhiraf_ufa_brand_max_url' ) ? zhiraf_ufa_brand_max_url() : '';
?>
<section class="section turciya-compare-lead" id="dest-inquiry">
	<div class="container container--narrow turciya-compare-lead__inner">
		<h2 class="turciya-compare-lead__title">
			<?php esc_html_e( 'Сравним Кемер и Сиде под ваш бюджет за 15 минут', 'zhiraf-ufa' ); ?>
		</h2>
		<p class="turciya-compare-lead__text">
			<?php esc_html_e( 'Напишите возраст детей и даты — пришлём 2–3 варианта. Удобнее мессенджер — жмите кнопку ниже; без WhatsApp на десктопе — оставьте телефон и канал.', 'zhiraf-ufa' ); ?>
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
		<div class="turciya-compare-lead__actions">
			<a
				class="btn btn--whatsapp btn--lg"
				href="<?php echo esc_url( $wa_url ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				data-track="messenger"
				data-channel="whatsapp"
				data-cta-context="turciya_compare_kemer_side"
			>
				<?php esc_html_e( 'Сразу в WhatsApp', 'zhiraf-ufa' ); ?>
			</a>
			<?php if ( '' !== $max_url ) : ?>
				<a
					class="btn btn--outline btn--lg"
					href="<?php echo esc_url( $max_url ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					data-track="messenger"
					data-channel="max"
					data-cta-context="turciya_compare_kemer_side_max"
				>
					<?php esc_html_e( 'Написать в MAX', 'zhiraf-ufa' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>