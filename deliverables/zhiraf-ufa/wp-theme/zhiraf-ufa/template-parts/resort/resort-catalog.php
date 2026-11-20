<?php
/**
 * Resort v2 — Dubai-only (or locked) curated catalog.
 *
 * @package Zhiraf_Ufa
 * @var array $args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$resort_data = ! empty( $args['resort_data'] ) && is_array( $args['resort_data'] ) ? $args['resort_data'] : array();
$catalog     = ! empty( $resort_data['catalog'] ) && is_array( $resort_data['catalog'] ) ? $resort_data['catalog'] : array();
$dest_slug   = ! empty( $resort_data['catalog_country'] ) ? sanitize_key( (string) $resort_data['catalog_country'] ) : sanitize_key( (string) ( $resort_data['country'] ?? 'oae' ) );

$hotels = function_exists( 'zhiraf_ufa_resort_json_catalog_hotels' )
	? zhiraf_ufa_resort_json_catalog_hotels( $resort_data )
	: array();

if ( empty( $hotels ) ) {
	return;
}

$copy = array(
	'eyebrow' => $catalog['eyebrow'] ?? '',
	'title'   => $catalog['title'] ?? '',
	'desc'    => $catalog['desc'] ?? '',
);

$filters       = ! empty( $catalog['filters'] ) && is_array( $catalog['filters'] ) ? $catalog['filters'] : array();
$default_filter = ! empty( $resort_data['catalog_default_filter'] ) ? (string) $resort_data['catalog_default_filter'] : 'emirate-dubai';
$fill_text     = $catalog['fill_text'] ?? '';
$fill_uon_id   = $catalog['fill_uon_id'] ?? 'RESORT-FILL';
$fill_tour     = $catalog['fill_tour'] ?? ( $resort_data['quiz_prefill'] ?? '' );
$pool_total    = count( $hotels );
$fill_wa_base  = function_exists( 'zhiraf_ufa_brand_whatsapp_url' ) ? zhiraf_ufa_brand_whatsapp_url() : 'https://wa.me/79018105018';

$fill_emirate_labels = array();
if ( function_exists( 'zhiraf_ufa_oae_catalog_fill_emirate_labels' ) ) {
	$fill_emirate_labels = zhiraf_ufa_oae_catalog_fill_emirate_labels();
}
?>
<section
	class="section turciya-curated-hub"
	id="dest-curated-hotels"
	data-turciya-curated-root
	data-catalog-default-title="<?php echo esc_attr( $copy['title'] ?? '' ); ?>"
	data-catalog-default-desc="<?php echo esc_attr( $copy['desc'] ?? '' ); ?>"
	data-catalog-default-filter="<?php echo esc_attr( $default_filter ); ?>"
	data-catalog-pool-total="<?php echo esc_attr( (string) $pool_total ); ?>"
	data-dest-slug="<?php echo esc_attr( $dest_slug ); ?>"
	data-catalog-fill-wa-base="<?php echo esc_url( $fill_wa_base ); ?>"
	data-catalog-fill-emirates="<?php echo esc_attr( wp_json_encode( $fill_emirate_labels ) ); ?>"
>
	<div class="container">
		<div class="turciya-curated-showcase__block">
			<?php
			get_template_part(
				'template-parts/destination/turciya-catalog-filters',
				null,
				array(
					'dest_slug'          => $dest_slug,
					'filters'            => $filters,
					'copy'               => $copy,
					'hide_resort_tabs'   => true,
				)
			);
			?>

			<div class="turciya-curated-grid" data-curated-grid data-catalog-pending>
				<?php foreach ( $hotels as $hotel ) : ?>
					<?php
					get_template_part(
						'template-parts/destination/card-curated-hotel',
						null,
						array( 'hotel' => $hotel )
					);
					?>
				<?php endforeach; ?>
				<aside class="turciya-catalog-fill" data-turciya-catalog-fill hidden>
					<p class="turciya-catalog-fill__badge"><?php esc_html_e( 'Индивидуальный подбор', 'zhiraf-ufa' ); ?></p>
					<h3 class="turciya-catalog-fill__title" data-turciya-fill-title>
						<?php esc_html_e( 'Подберём отель под вашу семью', 'zhiraf-ufa' ); ?>
					</h3>
					<p class="turciya-catalog-fill__text" data-turciya-fill-text></p>
					<button
						type="button"
						class="btn btn--outline turciya-catalog-fill__cta"
						data-turciya-fill-cta-modal
						data-turciya-lead-open
						data-uon-hotel-id="<?php echo esc_attr( $fill_uon_id ); ?>"
						data-lead-tour="<?php echo esc_attr( $fill_tour ); ?>"
						data-lead-message="<?php echo esc_attr( $fill_text ); ?>"
					>
						<?php esc_html_e( 'Подобрать в мессенджере →', 'zhiraf-ufa' ); ?>
					</button>
				</aside>
			</div>

			<div class="turciya-catalog-more" data-turciya-catalog-more-wrap hidden>
				<button type="button" class="btn btn--outline turciya-catalog-more__btn" data-turciya-catalog-more>
					<?php esc_html_e( 'Показать ещё отели', 'zhiraf-ufa' ); ?>
					<span data-turciya-catalog-more-count></span>
				</button>
			</div>
		</div>
	</div>
</section>