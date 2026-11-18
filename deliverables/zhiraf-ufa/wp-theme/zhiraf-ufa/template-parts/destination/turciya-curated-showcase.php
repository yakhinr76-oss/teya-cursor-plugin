<?php
/**
 * Turkey curated hub: resort pick cards + filterable hotel catalog.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest_slug   = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : 'turciya';
$hotels      = function_exists( 'zhiraf_ufa_curated_catalog_hotels' )
	? zhiraf_ufa_curated_catalog_hotels( $dest_slug )
	: ( function_exists( 'zhiraf_ufa_turciya_catalog_hotels' ) ? zhiraf_ufa_turciya_catalog_hotels() : array() );
$copy        = function_exists( 'zhiraf_ufa_curated_catalog_default_copy' )
	? zhiraf_ufa_curated_catalog_default_copy( $dest_slug )
	: ( function_exists( 'zhiraf_ufa_turciya_catalog_default_copy' ) ? zhiraf_ufa_turciya_catalog_default_copy() : array() );
$pool_total  = count( $hotels );
if ( 'oae' === $dest_slug ) {
	$fill_text   = __( 'Здравствуйте! На странице ОАЭ в выбранной категории 5 отелей — нужен ещё вариант под наши даты и состав (возраст детей: __). Вылет из Уфы. Пришлите 2–3 варианта из базы. ID подбора: UAE-FILL', 'zhiraf-ufa' );
	$fill_uon_id = 'UAE-FILL';
	$fill_tour   = __( 'ОАЭ, индивидуальный подбор', 'zhiraf-ufa' );
} elseif ( 'egipet' === $dest_slug ) {
	$fill_text   = __( 'Здравствуйте! На странице Египта в выбранной категории нужен ещё вариант под наши даты и состав (возраст детей: __). Вылет из Уфы. Пришлите 2–3 варианта из базы. ID подбора: EG-FILL', 'zhiraf-ufa' );
	$fill_uon_id = 'EG-FILL';
	$fill_tour   = __( 'Египет, индивидуальный подбор', 'zhiraf-ufa' );
} else {
	$fill_text   = __( 'Здравствуйте! На странице Турции в выбранной категории 5 отелей — нужен ещё вариант под наши даты и состав (возраст детей: __). Вылет из Уфы. Пришлите 2–3 варианта из базы. ID подбора: TR-FILL', 'zhiraf-ufa' );
	$fill_uon_id = 'TR-FILL';
	$fill_tour   = __( 'Турция, индивидуальный подбор', 'zhiraf-ufa' );
}

$fill_emirate_labels = array();
if ( 'oae' === $dest_slug && function_exists( 'zhiraf_ufa_oae_catalog_fill_emirate_labels' ) ) {
	$fill_emirate_labels = zhiraf_ufa_oae_catalog_fill_emirate_labels();
} elseif ( 'egipet' === $dest_slug && function_exists( 'zhiraf_ufa_egypt_catalog_fill_resort_labels' ) ) {
	$fill_emirate_labels = zhiraf_ufa_egypt_catalog_fill_resort_labels();
}
$fill_wa_base = function_exists( 'zhiraf_ufa_brand_whatsapp_url' )
	? zhiraf_ufa_brand_whatsapp_url()
	: 'https://wa.me/79018105018';

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
	data-catalog-pool-total="<?php echo esc_attr( (string) $pool_total ); ?>"
	data-dest-slug="<?php echo esc_attr( $dest_slug ); ?>"
	data-catalog-fill-wa-base="<?php echo esc_url( $fill_wa_base ); ?>"
	data-catalog-fill-emirates="<?php echo esc_attr( wp_json_encode( $fill_emirate_labels ) ); ?>"
>
	<div class="container">
		<?php if ( function_exists( 'zhiraf_ufa_curated_has_resort_pick_cards' ) && zhiraf_ufa_curated_has_resort_pick_cards( $dest_slug ) ) : ?>
			<?php
			get_template_part(
				'template-parts/destination/turciya-resort-pick-cards',
				null,
				array( 'dest_slug' => $dest_slug )
			);
			?>
		<?php endif; ?>

		<div class="turciya-curated-showcase__block">
			<?php
			get_template_part(
				'template-parts/destination/turciya-catalog-filters',
				null,
				array( 'dest_slug' => $dest_slug )
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
					<p class="turciya-catalog-fill__text" data-turciya-fill-text>
						<?php esc_html_e( 'В этой выборке 5 отелей. Напишите возраст детей — пришлём ещё 2–3 варианта из базы под ваши даты и вылет из Уфы.', 'zhiraf-ufa' ); ?>
					</p>
					<button
						type="button"
						class="btn btn--outline turciya-catalog-fill__cta"
						data-turciya-fill-cta-modal
						data-turciya-lead-open
						data-cta-context="turciya_catalog_fill_modal"
						data-uon-hotel-id="<?php echo esc_attr( $fill_uon_id ); ?>"
						data-hotel-name="<?php esc_attr_e( 'Индивидуальный подбор', 'zhiraf-ufa' ); ?>"
						data-lead-title="<?php esc_attr_e( 'Подберём отель под вашу семью', 'zhiraf-ufa' ); ?>"
						data-lead-subtitle="<?php esc_attr_e( 'Укажите телефон и мессенджер — уточним возраст детей и пришлём ещё 2–3 варианта с вылетом из Уфы.', 'zhiraf-ufa' ); ?>"
						data-lead-message="<?php echo esc_attr( $fill_text ); ?>"
						data-lead-tour="<?php echo esc_attr( $fill_tour ); ?>"
					>
						<?php esc_html_e( 'Подобрать в мессенджере →', 'zhiraf-ufa' ); ?>
					</button>
					<a
						class="btn btn--primary turciya-catalog-fill__cta turciya-catalog-fill__cta--wa"
						data-turciya-fill-cta-wa
						href="<?php echo esc_url( $fill_wa_base ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						data-track="messenger"
						data-channel="whatsapp"
						data-cta-context="oae_catalog_fill_whatsapp"
						hidden
					>
						<?php esc_html_e( 'Написать в WhatsApp →', 'zhiraf-ufa' ); ?>
					</a>
				</aside>
			</div>

			<?php
			get_template_part(
				'template-parts/destination/turciya-catalog-wa-strip',
				null,
				array( 'dest_slug' => $dest_slug )
			);
			?>

			<div class="turciya-catalog-more" data-turciya-catalog-more-wrap hidden>
				<button type="button" class="btn btn--outline turciya-catalog-more__btn" data-turciya-catalog-more>
					<?php esc_html_e( 'Показать ещё отели из базы', 'zhiraf-ufa' ); ?>
					<span data-turciya-catalog-more-count></span>
				</button>
				<p class="turciya-catalog-more__hint">
					<?php
					if ( 'oae' === $dest_slug ) {
						printf(
							/* translators: %d: hotels count beyond first six */
							esc_html__( 'В нашей базе ещё %d проверенных отелей ОАЭ под любые даты и состав семьи.', 'zhiraf-ufa' ),
							max( 0, $pool_total - 6 )
						);
					} else {
						printf(
							/* translators: %d: hotels count beyond first six */
							esc_html__( 'В нашей базе ещё %d проверенных отелей Турции под любые даты и состав семьи.', 'zhiraf-ufa' ),
							max( 0, $pool_total - 6 )
						);
					}
					?>
				</p>
			</div>
		</div>
	</div>
</section>
