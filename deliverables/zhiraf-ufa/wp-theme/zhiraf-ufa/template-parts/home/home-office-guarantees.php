<?php
/**
 * Block 6: Офис в Уфе и юридические гарантии надежности.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$office_map_url = function_exists( 'zhiraf_ufa_office_map_url' ) ? zhiraf_ufa_office_map_url() : '';
?>
<section class="section section--office-guarantees bg-milky" id="office-guarantees">
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

				<?php if ( $office_map_url ) : ?>
					<div class="office-guarantees__actions">
						<a class="btn btn--outline btn--sm" href="<?php echo esc_url( $office_map_url ); ?>" target="_blank" rel="noopener noreferrer" data-track="office_map">
							<?php esc_html_e( 'Маршрут в Яндекс.Картах', 'zhiraf-ufa' ); ?>
						</a>
						<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>" data-track="office_contacts">
							<?php esc_html_e( 'Контакты и реквизиты', 'zhiraf-ufa' ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<figure class="office-guarantees__media">
				<div class="office-guarantees__media-frame">
					<?php
					zhiraf_ufa_img(
						'about-office-gagarin-home.jpg',
						__( 'Офис турагентства «Жираф» в Уфе — ул. Юрия Гагарина, 74/1, офис 3', 'zhiraf-ufa' ),
						'office-guarantees__media-img',
						true
					);
					?>
				</div>
				<figcaption class="office-guarantees__caption">
					<?php esc_html_e( 'Живой офис в Сипайлово — приезжайте: покажем договор, реестр РТА и подберём тур.', 'zhiraf-ufa' ); ?>
				</figcaption>
			</figure>
		</div>
	</div>
</section>
