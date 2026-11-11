<?php
/**
 * Curated hotel card — photo badges, title/rating, суть, цена, CTA.
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
$concept     = ! empty( $hotel['subtitle'] ) ? (string) $hotel['subtitle'] : '';
$price_note  = function_exists( 'zhiraf_ufa_turciya_curated_price_note' )
	? zhiraf_ufa_turciya_curated_price_note( $hotel )
	: __( '7–9 ночей, ориентир (включая прямой перелёт из Уфы, трансфер, страховку и питание).', 'zhiraf-ufa' );
$badge_mod   = ! empty( $hotel['badge_mod'] ) ? sanitize_html_class( (string) $hotel['badge_mod'] ) : 'default';
$is_starter  = in_array( 'starter', $filter_tags, true );
$hide_init   = ! empty( $hotel['initial_hidden'] ) || ! $is_starter;
if ( $hide_init ) {
	$card_class .= ' is-hidden';
}
$pool_id     = ! empty( $hotel['pool_id'] ) ? sanitize_html_class( (string) $hotel['pool_id'] ) : '';
?>
<article
	class="<?php echo esc_attr( $card_class ); ?>"
	data-curated-card
	data-resort="<?php echo esc_attr( $resort ); ?>"
	data-filter-tags="<?php echo $tags_attr; ?>"
	data-pool-rank="<?php echo esc_attr( (string) $pool_rank ); ?>"
	<?php if ( '' !== $pool_id ) : ?>
		data-pool-id="<?php echo esc_attr( $pool_id ); ?>"
	<?php endif; ?>
	<?php echo $hide_init ? ' hidden' : ''; ?>
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
			<div class="turciya-curated-card__headline">
				<h3 class="turciya-curated-card__title"><?php echo esc_html( (string) $hotel['name'] ); ?></h3>
				<?php if ( ! empty( $hotel['rating'] ) ) : ?>
					<p class="turciya-curated-card__rating" aria-label="<?php esc_attr_e( 'Рейтинг по отзывам', 'zhiraf-ufa' ); ?>">
						<span aria-hidden="true">★</span>
						<?php echo esc_html( (string) $hotel['rating'] ); ?>
					</p>
				<?php endif; ?>
			</div>
			<?php if ( '' !== $concept ) : ?>
				<p class="turciya-curated-card__concept"><?php echo esc_html( $concept ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $hotel['description'] ) || ! empty( $hotel['caveat'] ) ) : ?>
				<div class="turciya-curated-card__copy">
					<?php if ( ! empty( $hotel['description'] ) ) : ?>
						<p class="turciya-curated-card__usp"><?php echo esc_html( (string) $hotel['description'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $hotel['caveat'] ) ) : ?>
						<p class="turciya-curated-card__nuance"><?php echo esc_html( (string) $hotel['caveat'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="turciya-curated-card__footer">
		<div class="turciya-curated-card__price">
			<span class="turciya-curated-card__price-label"><?php esc_html_e( 'за двоих с перелётом:', 'zhiraf-ufa' ); ?></span>
			<span class="turciya-curated-card__price-value<?php echo ! empty( $hotel['price_emphasis'] ) ? ' turciya-curated-card__price-value--accent' : ''; ?>">
				<?php echo esc_html( $price_label ); ?>
			</span>
			<span class="turciya-curated-card__price-note">
				<?php echo esc_html( $price_note ); ?>
			</span>
		</div>
		<a
			class="btn btn--primary turciya-curated-card__cta"
			href="<?php echo esc_url( $wa_url ); ?>"
			target="_blank"
			rel="noopener noreferrer"
			data-track="messenger"
			data-channel="whatsapp"
			data-cta-context="turciya_curated_hotel"
		>
			<?php esc_html_e( 'Узнать даты в WhatsApp →', 'zhiraf-ufa' ); ?>
		</a>
	</div>
</article>
