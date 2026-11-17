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
$wa_text     = __( 'Здравствуйте! Нужна подборка из базы проверенных отелей Турции под наши даты и состав семьи — пришлите лучшие варианты с вылетом из Уфы.', 'zhiraf-ufa' );
$fill_text   = __( 'Здравствуйте! На странице Турции в выбранной категории 5 отелей — нужен ещё вариант под наши даты и состав (возраст детей: __). Вылет из Уфы. Пришлите 2–3 варианта из базы. ID подбора: TR-FILL', 'zhiraf-ufa' );
$wa_base     = function_exists( 'zhiraf_ufa_brand_whatsapp_url' ) ? zhiraf_ufa_brand_whatsapp_url() : 'https://wa.me/79018105018';
$wa_url      = function_exists( 'zhiraf_ufa_whatsapp_url_with_text' )
	? zhiraf_ufa_whatsapp_url_with_text( $wa_base, $wa_text )
	: $wa_base;
$fill_urls   = function_exists( 'zhiraf_ufa_turciya_messenger_channel_urls' )
	? zhiraf_ufa_turciya_messenger_channel_urls( $fill_text )
	: array(
		'whatsapp' => $wa_url,
		'max'      => '',
		'telegram' => '',
	);

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
						data-turciya-messenger-open
						data-messenger-url-whatsapp="<?php echo esc_url( (string) ( $fill_urls['whatsapp'] ?? $wa_url ) ); ?>"
						data-messenger-url-max="<?php echo esc_url( (string) ( $fill_urls['max'] ?? '' ) ); ?>"
						data-messenger-url-telegram="<?php echo esc_url( (string) ( $fill_urls['telegram'] ?? '' ) ); ?>"
						data-uon-hotel-id="TR-FILL"
						data-hotel-name="<?php echo esc_attr( __( 'Индивидуальный подбор', 'zhiraf-ufa' ) ); ?>"
						data-cta-context="turciya_catalog_fill"
					>
						<?php esc_html_e( 'Подобрать в мессенджере →', 'zhiraf-ufa' ); ?>
					</button>
				</aside>
			</div>

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
				<div class="turciya-catalog-more__actions">
					<a
						class="btn btn--whatsapp"
						href="<?php echo esc_url( $wa_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						data-track="messenger"
						data-channel="whatsapp"
						data-cta-context="turciya_catalog_pool_whatsapp"
					>
						<?php esc_html_e( 'Подборка в WhatsApp', 'zhiraf-ufa' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
