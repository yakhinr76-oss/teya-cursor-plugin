<?php
/**
 * Turkey curated hub: resort pick cards + filterable hotel catalog.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hotels = function_exists( 'zhiraf_ufa_turciya_catalog_hotels' ) ? zhiraf_ufa_turciya_catalog_hotels() : array();
$copy   = function_exists( 'zhiraf_ufa_turciya_catalog_default_copy' ) ? zhiraf_ufa_turciya_catalog_default_copy() : array();

if ( empty( $hotels ) ) {
	return;
}
?>
<section
	class="section turciya-curated-hub"
	id="dest-curated-hotels"
	data-turciya-curated-root
	data-catalog-default-title="<?php echo esc_attr( $copy['title'] ?? '' ); ?>"
	data-catalog-default-desc="<?php echo esc_attr( $copy['desc'] ?? '' ); ?>"
>
	<div class="container">
		<?php get_template_part( 'template-parts/destination/turciya-resort-pick-cards' ); ?>

		<div class="turciya-curated-showcase__block">
			<?php get_template_part( 'template-parts/destination/turciya-catalog-filters' ); ?>

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
