<?php
/**
 * Turkey curated hotels (local REST only on frontend).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section section--hotels-showcase bg-sand" id="dest-hotels-showcase" data-hotels-showcase data-hotels-country="turkey">
	<div class="container">
		<div class="section-head section-head--center reveal">
			<h2 class="section-title"><?php esc_html_e( '25 эталонных отелей Турции', 'zhiraf-ufa' ); ?></h2>
			<p class="lead"><?php esc_html_e( 'Подборка «Жираф» по формату отдыха: фото и выводы по отзывам — на нашем сервере. Цены на туры — из Библио‑Глобус при заявке.', 'zhiraf-ufa' ); ?></p>
		</div>
		<div class="hotels-showcase__filters" data-hotels-filters role="tablist" aria-label="<?php esc_attr_e( 'Категория отеля', 'zhiraf-ufa' ); ?>">
			<button type="button" class="hotels-showcase__filter is-active" data-hotels-filter="" role="tab" aria-selected="true"><?php esc_html_e( 'Все', 'zhiraf-ufa' ); ?></button>
		</div>
		<div class="hotels-showcase__grid" data-hotels-grid>
			<p class="hotels-showcase__loading"><?php esc_html_e( 'Загрузка подборки…', 'zhiraf-ufa' ); ?></p>
		</div>
		<p class="hotels-showcase__attribution">
			<?php esc_html_e( 'Фото отелей получены при разовой выгрузке и хранятся на сайте. Если указана ссылка Tripadvisor — полные отзывы и альбом на стороне Tripadvisor.', 'zhiraf-ufa' ); ?>
		</p>
	</div>
</section>

<div class="hotel-modal" id="hotel-modal" data-hotel-modal hidden>
	<div class="hotel-modal__backdrop" data-hotel-modal-close tabindex="-1"></div>
	<div class="hotel-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="hotel-modal-title">
		<button type="button" class="hotel-modal__close" data-hotel-modal-close aria-label="<?php esc_attr_e( 'Закрыть', 'zhiraf-ufa' ); ?>">×</button>
		<div class="hotel-modal__body" data-hotel-modal-body></div>
	</div>
</div>
