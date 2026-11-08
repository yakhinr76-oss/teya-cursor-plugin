<?php
/**
 * Block 6: Офис в Уфе и юридические гарантии надежности.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section section--office-guarantees" id="office-guarantees">
	<div class="container">
		<div class="office-guarantees reveal">
			<div class="office-guarantees__content">
				<h2 class="office-guarantees__title"><?php esc_html_e( 'Работаем в Сипайлово с 2011 года. Ждём лично на чашку кофе', 'zhiraf-ufa' ); ?></h2>
				
				<div class="office-guarantees__address">
					<p><strong><?php esc_html_e( 'г. Уфа, ул. Юрия Гагарина, 74/1, офис 3', 'zhiraf-ufa' ); ?></strong></p>
					<p><?php esc_html_e( 'Пн-Пт: 11:00 – 19:00', 'zhiraf-ufa' ); ?><br>
					<?php esc_html_e( 'Сб: 12:00 – 16:00, Вс: выходной', 'zhiraf-ufa' ); ?></p>
					<p class="text-sm text-muted mt-2"><?php esc_html_e( 'Бесплатная парковка перед зданием.', 'zhiraf-ufa' ); ?></p>
				</div>

				<ul class="office-guarantees__shields">
					<li>
						<span class="shield-icon" aria-hidden="true">🏛️</span>
						<span><?php esc_html_e( 'Номер в Едином федеральном реестре турагентств (РТА 0012822).', 'zhiraf-ufa' ); ?></span>
					</li>
					<li>
						<span class="shield-icon" aria-hidden="true">📄</span>
						<span><?php esc_html_e( 'Работа по официальному договору с выдачей кассовых чеков.', 'zhiraf-ufa' ); ?></span>
					</li>
					<li>
						<span class="shield-icon" aria-hidden="true">💳</span>
						<span><?php esc_html_e( 'Оплата через эквайринг, расчетный счет или наличными в офисе.', 'zhiraf-ufa' ); ?></span>
					</li>
				</ul>
			</div>
			
			<div class="office-guarantees__map">
				<?php
				zhiraf_ufa_img(
					'about-office-gagarin.jpg',
					__( 'Офис турагентства «Жираф» в Уфе — ул. Юрия Гагарина, 74/1, офис 3', 'zhiraf-ufa' ),
					'office-guarantees__map-img',
					true
				);
				?>
			</div>
		</div>
	</div>
</section>
