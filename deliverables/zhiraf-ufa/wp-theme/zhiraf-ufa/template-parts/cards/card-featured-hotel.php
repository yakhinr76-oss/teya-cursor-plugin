<?php
/**
 * Featured hotel card (homepage catalog — 3 per country tab).
 *
 * @package Zhiraf_Ufa
 * @var array $args Hotel row from home-featured-hotels.json.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hotel = isset( $args['hotel'] ) && is_array( $args['hotel'] ) ? $args['hotel'] : array();
if ( empty( $hotel['name'] ) ) {
	return;
}

$photo_url = ! empty( $hotel['photoUrl'] ) ? (string) $hotel['photoUrl'] : '';
if ( '' === $photo_url && ! empty( $hotel['photo'] ) ) {
	$photo_url = zhiraf_ufa_home_featured_hotel_photo_url( (string) $hotel['photo'] );
}

$wa_url = ! empty( $hotel['waUrl'] ) ? (string) $hotel['waUrl'] : zhiraf_ufa_home_featured_hotel_wa_url( $hotel['waQuery'] ?? $hotel['name'] );
$rating = ! empty( $hotel['rating'] ) ? (string) $hotel['rating'] : '';
$pros   = ! empty( $hotel['pros'] ) && is_array( $hotel['pros'] ) ? $hotel['pros'] : array();
?>
<article class="featured-hotel-card">
	<div class="featured-hotel-card__media aspect-video">
		<?php if ( $photo_url ) : ?>
			<img
				class="featured-hotel-card__photo"
				src="<?php echo esc_url( $photo_url ); ?>"
				alt="<?php echo esc_attr( $hotel['name'] ); ?>"
				loading="lazy"
				decoding="async"
				width="640"
				height="360"
			/>
		<?php endif; ?>
		<?php if ( ! empty( $hotel['badge'] ) ) : ?>
			<span class="featured-hotel-card__badge"><?php echo esc_html( $hotel['badge'] ); ?></span>
		<?php endif; ?>
	</div>
	<div class="featured-hotel-card__body">
		<div class="featured-hotel-card__meta">
			<span class="featured-hotel-card__resort"><?php echo esc_html( $hotel['resort'] ?? '' ); ?></span>
			<?php if ( '' !== $rating ) : ?>
				<span class="featured-hotel-card__rating" aria-label="<?php echo esc_attr( sprintf( __( 'Рейтинг %s из 5', 'zhiraf-ufa' ), $rating ) ); ?>">
					<span aria-hidden="true">★</span> <?php echo esc_html( $rating ); ?> / 5.0
				</span>
			<?php endif; ?>
		</div>
		<h3 class="featured-hotel-card__title"><?php echo esc_html( $hotel['name'] ); ?></h3>
		<?php if ( ! empty( $hotel['usp'] ) ) : ?>
			<p class="featured-hotel-card__usp"><?php echo esc_html( $hotel['usp'] ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $pros ) ) : ?>
			<ul class="featured-hotel-card__pros">
				<?php foreach ( array_slice( $pros, 0, 3 ) as $pro ) : ?>
					<li><span class="featured-hotel-card__check" aria-hidden="true">✓</span><?php echo esc_html( $pro ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( ! empty( $hotel['caveat'] ) ) : ?>
			<p class="featured-hotel-card__caveat">
				<strong><?php esc_html_e( 'Важно:', 'zhiraf-ufa' ); ?></strong>
				<?php echo esc_html( $hotel['caveat'] ); ?>
			</p>
		<?php endif; ?>
		<a
			class="btn btn--whatsapp featured-hotel-card__cta"
			href="<?php echo esc_url( $wa_url ); ?>"
			target="_blank"
			rel="noopener noreferrer"
			data-track="messenger"
			data-channel="whatsapp"
			data-cta-context="home_featured_hotel_wa"
		>
			<?php esc_html_e( 'Узнать цену на мои даты в WhatsApp', 'zhiraf-ufa' ); ?>
		</a>
	</div>
</article>
