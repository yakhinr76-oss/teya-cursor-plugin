<?php
/**
 * Homepage: unified «Популярные направления» + tour catalog.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tabs        = zhiraf_ufa_home_unified_catalog_tabs();
$tours       = zhiraf_ufa_home_unified_catalog_tours();
$display_max = 6;
$grid_tours  = array_slice( $tours, 0, $display_max );
?>
<section class="section section--unified-catalog" id="tour-catalog">
	<div class="container unified-catalog">
		<header class="unified-catalog__intro reveal">
			<p class="catalog-eyebrow">
				<?php esc_html_e( 'Прямые рейсы из аэропорта Уфа (UFA)', 'zhiraf-ufa' ); ?>
			</p>
			<h2 class="unified-catalog__title"><?php esc_html_e( 'Популярные направления с вылетом из Уфы', 'zhiraf-ufa' ); ?></h2>
			<p class="unified-catalog__lead">
				<?php esc_html_e( 'Стоимость указана на двоих взрослых с перелётом, трансфером, страховкой и проживанием. Данные синхронизируются напрямую с туроператорами.', 'zhiraf-ufa' ); ?>
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
					data-filter="<?php echo esc_attr( $tab['id'] ); ?>"
					aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
				><?php echo esc_html( $tab['label'] ); ?></button>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $grid_tours ) ) : ?>
			<div id="tours-grid" class="tour-price-grid tour-price-grid--catalog unified-catalog__grid">
				<?php
				foreach ( $grid_tours as $i => $tour_item ) {
					get_template_part(
						'template-parts/cards/card-tour-catalog',
						null,
						array(
							'tour'       => $tour_item,
							'card_index' => $i,
						)
					);
				}
				?>
			</div>
			<div class="unified-catalog__capture" style="margin-top: 3rem;">
				<?php get_template_part( 'template-parts/home/catalog-capture-card' ); ?>
			</div>
		<?php else : ?>
			<p class="unified-catalog__empty reveal"><?php esc_html_e( 'Сейчас обновляем цены — напишите нам, подберём актуальные варианты из базы операторов.', 'zhiraf-ufa' ); ?></p>
		<?php endif; ?>
	</div>
</section>
