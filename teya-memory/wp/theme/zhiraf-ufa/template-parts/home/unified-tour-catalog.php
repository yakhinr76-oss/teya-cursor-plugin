<?php
/**
 * Homepage: «Популярные направления» — 3 featured hotels per country tab.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tabs            = zhiraf_ufa_home_unified_catalog_tabs();
$featured_hotels = zhiraf_ufa_home_featured_hotels_data();
$default_tab     = ! empty( $tabs[0]['id'] ) ? (string) $tabs[0]['id'] : 'turkey';
$initial_hotels  = ! empty( $featured_hotels[ $default_tab ] ) ? $featured_hotels[ $default_tab ] : array();
?>
<section class="section section--unified-catalog" id="catalog-tours">
	<div class="container unified-catalog">
		<header class="unified-catalog__intro reveal">
			<p class="catalog-eyebrow">
				<?php esc_html_e( 'Прямые рейсы из аэропорта Уфа (UFA)', 'zhiraf-ufa' ); ?>
			</p>
			<h2 class="unified-catalog__title"><?php esc_html_e( 'Популярные направления с вылетом из Уфы', 'zhiraf-ufa' ); ?></h2>
			<p class="unified-catalog__lead">
				<?php esc_html_e( 'Три проверенных отеля на каждое направление — без лишних карточек и пустых ячеек. Цену и даты уточним в WhatsApp за несколько минут.', 'zhiraf-ufa' ); ?>
			</p>
		</header>

		<div
			id="tour-tabs"
			class="catalog-pills unified-catalog__tabs reveal"
			role="tablist"
			aria-label="<?php esc_attr_e( 'Фильтр направлений', 'zhiraf-ufa' ); ?>"
		>
			<?php foreach ( $tabs as $i => $tab ) : ?>
				<button
					type="button"
					class="catalog-pills__btn tab-btn<?php echo 0 === $i ? ' is-active' : ''; ?>"
					role="tab"
					id="catalog-tab-<?php echo esc_attr( $tab['id'] ); ?>"
					data-filter="<?php echo esc_attr( $tab['id'] ); ?>"
					aria-controls="featured-hotels-grid"
					aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
				><?php echo esc_html( $tab['label'] ); ?></button>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $featured_hotels ) ) : ?>
			<div
				id="featured-hotels-grid"
				class="featured-hotels-grid unified-catalog__featured-grid is-ready"
				role="tabpanel"
				aria-labelledby="catalog-tab-<?php echo esc_attr( $default_tab ); ?>"
				data-default-tab="<?php echo esc_attr( $default_tab ); ?>"
			>
				<?php
				foreach ( $initial_hotels as $hotel_row ) {
					get_template_part(
						'template-parts/cards/card-featured-hotel',
						null,
						array(
							'hotel' => $hotel_row,
						)
					);
				}
				?>
			</div>

			<div class="unified-catalog__capture reveal">
				<?php get_template_part( 'template-parts/home/catalog-capture-card' ); ?>
			</div>
		<?php else : ?>
			<p class="unified-catalog__empty reveal"><?php esc_html_e( 'Сейчас обновляем подборку — напишите нам, подберём актуальные варианты из базы операторов.', 'zhiraf-ufa' ); ?></p>
		<?php endif; ?>
	</div>
</section>
