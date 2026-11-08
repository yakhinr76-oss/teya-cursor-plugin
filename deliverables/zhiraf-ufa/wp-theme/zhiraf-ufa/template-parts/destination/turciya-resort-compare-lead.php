<?php
/**
 * Contextual lead magnet — Kemer vs Side in messengers.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wa_text = function_exists( 'zhiraf_ufa_turciya_resort_compare_wa_text' )
	? zhiraf_ufa_turciya_resort_compare_wa_text()
	: '';
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
			<?php esc_html_e( 'Напишите в мессенджер — пришлём 2–3 отеля с пляжами, питанием и актуальной ценой на ваши даты. Без «оставьте номер и ждите».', 'zhiraf-ufa' ); ?>
		</p>
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
				<?php esc_html_e( 'Сравнить в WhatsApp', 'zhiraf-ufa' ); ?>
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
