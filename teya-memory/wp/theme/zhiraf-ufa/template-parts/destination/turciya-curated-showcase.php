<?php
/**
 * Turkey curated hub: resort pick cards + filterable hotel catalog.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hotels      = function_exists( 'zhiraf_ufa_turciya_catalog_hotels' ) ? zhiraf_ufa_turciya_catalog_hotels() : array();
$copy        = function_exists( 'zhiraf_ufa_turciya_catalog_default_copy' ) ? zhiraf_ufa_turciya_catalog_default_copy() : array();
$pool_total  = count( $hotels );
$fill_text   = __( 'Здравствуйте! На странице Турции в выбранной категории 5 отелей — нужен ещё вариант под наши даты и состав (возраст детей: __). Вылет из Уфы. Пришлите 2–3 варианта из базы. ID подбора: TR-FILL', 'zhiraf-ufa' );

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
>
	<div class="container">
		<?php get_template_part( 'template-parts/destination/turciya-resort-pick-cards' ); ?>

		<div class="turciya-curated-showcase__block">
			<?php get_template_part( 'template-parts/destination/turciya-catalog-filters' ); ?>

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
						class="btn btn--primary turciya-catalog-fill__cta"
						data-turciya-lead-open
						data-cta-context="turciya_catalog_fill_modal"
						data-uon-hotel-id="TR-FILL"
						data-hotel-name="<?php esc_attr_e( 'Индивидуальный подбор', 'zhiraf-ufa' ); ?>"
						data-lead-title="<?php esc_attr_e( 'Подберём отель под вашу семью', 'zhiraf-ufa' ); ?>"
						data-lead-subtitle="<?php esc_attr_e( 'Укажите телефон и мессенджер — уточним возраст детей и пришлём ещё 2–3 варианта с вылетом из Уфы.', 'zhiraf-ufa' ); ?>"
						data-lead-message="<?php echo esc_attr( $fill_text ); ?>"
						data-lead-tour="<?php esc_attr_e( 'Турция, индивидуальный подбор', 'zhiraf-ufa' ); ?>"
					>
						<?php esc_html_e( 'Подобрать в мессенджере →', 'zhiraf-ufa' ); ?>
					</button>
				</aside>
			</div>

			<?php get_template_part( 'template-parts/destination/turciya-catalog-wa-strip' ); ?>

			<div class="turciya-catalog-more" data-turciya-catalog-more-wrap hidden>
				<button type="button" class="btn btn--outline turciya-catalog-more__btn" data-turciya-catalog-more>
					<?php esc_html_e( 'Показать ещё отели из базы', 'zhiraf-ufa' ); ?>
					<span data-turciya-catalog-more-count></span>
				</button>
				<p class="turciya-catalog-more__hint">
					<?php
					printf(
						/* translators: %d: hotels count beyond first six */
						esc_html__( 'В нашей базе ещё %d проверенных отелей Турции под любые даты и состав семьи.', 'zhiraf-ufa' ),
						max( 0, $pool_total - 6 )
					);
					?>
				</p>
			</div>
		</div>
	</div>
</section>
