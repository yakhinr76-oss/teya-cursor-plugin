<?php
/**
 * Catalog grid capture card (spans 2 columns on desktop).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wa_url = zhiraf_ufa_home_catalog_capture_wa_url();
?>
<aside class="catalog-capture-card" data-catalog-capture aria-label="<?php esc_attr_e( 'Индивидуальный подбор тура', 'zhiraf-ufa' ); ?>">
	<div class="catalog-capture-card__glow" aria-hidden="true"></div>
	<div class="catalog-capture-card__inner">
		<p class="catalog-capture-card__badge"><?php esc_html_e( 'Индивидуальный поиск', 'zhiraf-ufa' ); ?></p>
		<h3 class="catalog-capture-card__title">
			<?php esc_html_e( 'Не нашли свой отель среди этих вариантов?', 'zhiraf-ufa' ); ?>
		</h3>
		<p class="catalog-capture-card__text">
			<?php esc_html_e( 'В рабочей базе с вылетом из Уфы более 1 200 отелей. Напишите даты и бюджет — эксперт проверит номера и пришлёт 3 персональных варианта в WhatsApp за 15 минут.', 'zhiraf-ufa' ); ?>
		</p>
		<div class="catalog-capture-card__actions">
			<a
				class="btn btn--whatsapp catalog-capture-card__wa"
				href="<?php echo esc_url( $wa_url ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				data-track="messenger"
				data-channel="whatsapp"
				data-cta-context="home_catalog_capture_card"
			>
				<?php esc_html_e( 'Спросить в WhatsApp', 'zhiraf-ufa' ); ?>
			</a>
			<p class="catalog-capture-card__note">
				<?php esc_html_e( 'Отвечаем с 09:00 до 21:00 · без спама', 'zhiraf-ufa' ); ?>
			</p>
		</div>
	</div>
</aside>
