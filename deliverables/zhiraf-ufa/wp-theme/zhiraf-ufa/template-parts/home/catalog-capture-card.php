<?php
/**
 * Terracotta capture banner under featured hotel grid.
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
		<p class="catalog-capture-card__badge"><?php esc_html_e( 'Персональный подбор', 'zhiraf-ufa' ); ?></p>
		<h3 class="catalog-capture-card__title">
			<?php esc_html_e( 'Не определились со страной?', 'zhiraf-ufa' ); ?>
		</h3>
		<p class="catalog-capture-card__text">
			<?php esc_html_e( 'Сравним 2–3 направления по погоде, пляжам и рейсам из Уфы под ваш бюджет. Ответ за 15 минут.', 'zhiraf-ufa' ); ?>
		</p>
		<div class="catalog-capture-card__actions">
			<a
				class="catalog-capture-card__wa"
				href="<?php echo esc_url( $wa_url ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				data-track="messenger"
				data-channel="whatsapp"
				data-cta-context="home_catalog_capture_card"
			>
				<?php if ( function_exists( 'zhiraf_ufa_social_icon_svg' ) ) : ?>
					<span class="messenger-picker__icon messenger-picker__icon--wa" aria-hidden="true"><?php echo zhiraf_ufa_social_icon_svg( 'whatsapp', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
				<span><?php esc_html_e( 'Спросить эксперта в WhatsApp →', 'zhiraf-ufa' ); ?></span>
			</a>
			<p class="catalog-capture-card__note">
				<?php esc_html_e( 'Отвечаем с 09:00 до 21:00 · без спама', 'zhiraf-ufa' ); ?>
			</p>
		</div>
	</div>
</aside>
