<?php
/**
 * Curated hotels showcase (local REST only on frontend).
 *
 * Args: country (turkey|egypt), title, lead.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$country = ! empty( $args['country'] ) ? sanitize_key( (string) $args['country'] ) : 'turkey';
$title   = ! empty( $args['title'] ) ? (string) $args['title'] : __( '25 эталонных отелей', 'zhiraf-ufa' );
$lead    = ! empty( $args['lead'] )
	? (string) $args['lead']
	: __( 'Подборка «Жираф» по формату отдыха: фото и выводы по отзывам — на нашем сервере. Цены на туры — из Библио‑Глобус при заявке.', 'zhiraf-ufa' );
?>
<section class="section section--hotels-showcase bg-sand" id="dest-hotels-showcase-<?php echo esc_attr( $country ); ?>" data-hotels-showcase data-hotels-country="<?php echo esc_attr( $country ); ?>">
	<div class="container">
		<div class="section-head section-head--center reveal">
			<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
			<p class="lead"><?php echo esc_html( $lead ); ?></p>
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
<?php
zhiraf_ufa_render_hotel_modal_shell();
