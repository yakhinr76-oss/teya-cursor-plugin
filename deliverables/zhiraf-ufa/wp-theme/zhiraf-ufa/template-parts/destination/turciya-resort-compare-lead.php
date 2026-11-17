<?php
/**
 * Turkey — CTA to open lead modal (messenger picker inside popup).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section turciya-compare-lead" id="dest-inquiry">
	<div class="container container--narrow turciya-compare-lead__inner">
		<h2 class="turciya-compare-lead__title">
			<?php esc_html_e( 'Сравним Кемер и Сиде под ваш бюджет за 15 минут', 'zhiraf-ufa' ); ?>
		</h2>
		<p class="turciya-compare-lead__text">
			<?php esc_html_e( 'Подберём 2–3 отеля с актуальными ценами на рейс из Уфы. В попапе выберете MAX, WhatsApp или Telegram — туда уйдёт расчёт.', 'zhiraf-ufa' ); ?>
		</p>
		<button type="button" class="btn btn--primary btn--lg" data-turciya-lead-open>
			<?php esc_html_e( 'Оставить заявку и выбрать мессенджер', 'zhiraf-ufa' ); ?>
		</button>
	</div>
</section>
<?php get_template_part( 'template-parts/destination/turciya-lead-modal' ); ?>