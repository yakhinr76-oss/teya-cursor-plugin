<?php
/**
 * Curated hotel card (Turkey landing 2×3 grid).
 *
 * @package Zhiraf_Ufa
 * @var array<string,mixed> $args { hotel: array }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hotel = ! empty( $args['hotel'] ) && is_array( $args['hotel'] ) ? $args['hotel'] : array();
if ( empty( $hotel['name'] ) ) {
	return;
}

$is_base     = ! empty( $hotel['slot'] ) && 'base' === $hotel['slot'];
$resort      = ! empty( $hotel['resort'] ) ? sanitize_html_class( (string) $hotel['resort'] ) : '';
$filter_tags = ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ? $hotel['filter_tags'] : array();
if ( empty( $filter_tags ) && '' !== $resort ) {
	$filter_tags = array( 'resort-' . $resort );
}
$tags_attr   = esc_attr( implode( ' ', array_map( 'sanitize_html_class', $filter_tags ) ) );
$pool_rank   = ! empty( $hotel['pool_rank'] ) ? (int) $hotel['pool_rank'] : 999;
$card_class  = 'turciya-curated-card';
if ( $is_base ) {
	$card_class .= ' turciya-curated-card--base';
}
$wa_url      = function_exists( 'zhiraf_ufa_turciya_curated_wa_url' )
	? zhiraf_ufa_turciya_curated_wa_url( $hotel )
	: '#';
$price_label = function_exists( 'zhiraf_ufa_turciya_curated_format_price' ) && ! empty( $hotel['price_rub'] )
	? zhiraf_ufa_turciya_curated_format_price( (int) $hotel['price_rub'] )
	: '';
$badge_mod   = ! empty( $hotel['badge_mod'] ) ? sanitize_html_class( (string) $hotel['badge_mod'] ) : 'default';
?>
<article
	class="<?php echo esc_attr( $card_class ); ?>"
	data-curated-card
	data-resort="<?php echo esc_attr( $resort ); ?>"
	data-filter-tags="<?php echo $tags_attr; ?>"
	data-pool-rank="<?php echo esc_attr( (string) $pool_rank ); ?>"
>
	<div class="turciya-curated-card__top">
		<div class="turciya-curated-card__media">
			<?php if ( ! empty( $hotel['image'] ) ) : ?>
				<?php
				zhiraf_ufa_img(
					(string) $hotel['image'],
					! empty( $hotel['image_alt'] ) ? (string) $hotel['image_alt'] : (string) $hotel['name'],
					'turciya-curated-card__img',
					true
				);
				?>
			<?php endif; ?>
			<?php if ( ! empty( $hotel['badge'] ) ) : ?>
				<span class="turciya-curated-card__badge turciya-curated-card__badge--<?php echo esc_attr( $badge_mod ); ?>">
					<?php echo esc_html( (string) $hotel['badge'] ); ?>
				</span>
			<?php endif; ?>
			<?php if ( ! empty( $hotel['resort_pill'] ) ) : ?>
				<span class="turciya-curated-card__pill"><?php echo esc_html( (string) $hotel['resort_pill'] ); ?></span>
			<?php endif; ?>
		</div>
		<div class="turciya-curated-card__body">
			<div class="turciya-curated-card__meta">
				<span class="turciya-curated-card__meal"><?php echo esc_html( $hotel['subtitle'] ?? '' ); ?></span>
				<?php if ( ! empty( $hotel['rating'] ) ) : ?>
					<span class="turciya-curated-card__rating" aria-hidden="true">★ <?php echo esc_html( (string) $hotel['rating'] ); ?></span>
				<?php endif; ?>
			</div>
			<h3 class="turciya-curated-card__title"><?php echo esc_html( (string) $hotel['name'] ); ?></h3>
			<?php if ( ! empty( $hotel['description'] ) ) : ?>
				<p class="turciya-curated-card__text"><?php echo esc_html( (string) $hotel['description'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<div class="turciya-curated-card__footer">
		<div class="turciya-curated-card__price">
			<span class="turciya-curated-card__price-label"><?php esc_html_e( 'за двоих с перелётом:', 'zhiraf-ufa' ); ?></span>
			<span class="turciya-curated-card__price-value<?php echo ! empty( $hotel['price_emphasis'] ) ? ' turciya-curated-card__price-value--accent' : ''; ?>">
				<?php echo esc_html( $price_label ); ?>
			</span>
			<span class="turciya-curated-card__price-note"><?php esc_html_e( '7–9 ночей, ориентир', 'zhiraf-ufa' ); ?></span>
		</div>
		<a
			class="btn <?php echo $is_base ? 'btn--primary' : 'btn--outline'; ?> btn--sm turciya-curated-card__cta"
			href="<?php echo esc_url( $wa_url ); ?>"
			target="_blank"
			rel="noopener noreferrer"
			data-track="messenger"
			data-channel="whatsapp"
			data-cta-context="turciya_curated_hotel"
		>
			<?php esc_html_e( 'Узнать даты →', 'zhiraf-ufa' ); ?>
		</a>
	</div>
</article>
