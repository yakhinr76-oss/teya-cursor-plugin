<?php
/**
 * Homepage trust block visuals: office + RTA plaque (no generic stock team photo).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="trust-social__visual-stack">
	<?php zhiraf_ufa_img( 'office-ufa.jpg', __( 'Офис турагентства «Жираф» в Уфе, ул. Гагарина, 74/1', 'zhiraf-ufa' ), 'trust-social__img trust-social__img--office', true ); ?>
	<aside class="trust-rta-plaque" aria-label="<?php esc_attr_e( 'Реестр турагентств РТА', 'zhiraf-ufa' ); ?>">
		<p class="trust-rta-plaque__eyebrow"><?php esc_html_e( 'Официальный статус', 'zhiraf-ufa' ); ?></p>
		<p class="trust-rta-plaque__title"><?php esc_html_e( 'Реестр турагентств РТА', 'zhiraf-ufa' ); ?></p>
		<p class="trust-rta-plaque__number">0012822</p>
		<p class="trust-rta-plaque__legal"><?php esc_html_e( 'ООО «Жираф» · Уфа', 'zhiraf-ufa' ); ?></p>
		<ul class="trust-rta-plaque__list">
			<li><?php esc_html_e( 'Договор и чек по 132-ФЗ', 'zhiraf-ufa' ); ?></li>
			<li><?php esc_html_e( 'Офис: Гагарина, 74/1', 'zhiraf-ufa' ); ?></li>
		</ul>
	</aside>
</div>
