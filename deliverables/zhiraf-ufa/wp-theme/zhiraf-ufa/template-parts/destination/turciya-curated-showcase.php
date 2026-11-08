<?php
/**
 * Turkey curated hub: resort pick cards + 2×3 hotel grid.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hotels = function_exists( 'zhiraf_ufa_turciya_curated_hotels' ) ? zhiraf_ufa_turciya_curated_hotels() : array();
$chips  = function_exists( 'zhiraf_ufa_turciya_resort_chips' ) ? zhiraf_ufa_turciya_resort_chips() : array();

if ( empty( $hotels ) ) {
	return;
}
?>
<section class="section turciya-curated-hub" id="dest-curated-hotels" data-turciya-curated-root>
	<div class="container">
		<?php get_template_part( 'template-parts/destination/turciya-resort-pick-cards' ); ?>

		<div class="turciya-curated-showcase__block">
			<div class="turciya-curated-showcase__head">
				<div class="turciya-curated-showcase__intro">
					<p class="turciya-curated-showcase__eyebrow"><?php esc_html_e( 'Честные цены туроператоров', 'zhiraf-ufa' ); ?></p>
					<h2 class="section-title turciya-curated-showcase__title">
						<?php esc_html_e( 'Туры в Турцию с вылетом из Уфы: 6 проверенных вариантов', 'zhiraf-ufa' ); ?>
					</h2>
					<p class="turciya-curated-showcase__lead">
						<?php esc_html_e( 'Цены за двоих на 7–9 ночей с перелётом из Уфы, трансфером и страховкой. Первая карточка — тот же минимум, что на главной.', 'zhiraf-ufa' ); ?>
					</p>
				</div>
				<?php
				get_template_part(
					'template-parts/destination/turciya-resort-chips',
					null,
					array(
						'context' => 'showcase',
						'chips'   => $chips,
					)
				);
				?>
			</div>

			<div class="turciya-curated-grid" data-curated-grid>
				<?php foreach ( $hotels as $hotel ) : ?>
					<?php
					get_template_part(
						'template-parts/destination/card-curated-hotel',
						null,
						array( 'hotel' => $hotel )
					);
					?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
