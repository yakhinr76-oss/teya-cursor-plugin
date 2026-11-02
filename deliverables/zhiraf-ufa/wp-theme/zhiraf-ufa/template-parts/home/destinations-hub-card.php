<?php
/**
 * Single destination hub card.
 *
 * @package Zhiraf_Ufa
 * @var array $args { card: array }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card = ! empty( $args['card'] ) && is_array( $args['card'] ) ? $args['card'] : array();
if ( empty( $card['url'] ) ) {
	return;
}

$country_name = (string) ( $card['title'] ?? '' );
$flag_emoji   = (string) ( $card['flag'] ?? '' );
$title        = trim( $flag_emoji . ' ' . $country_name );
$flag_url     = ! empty( $card['flag_url'] ) ? (string) $card['flag_url'] : '';
?>
<a
	class="dest-hub-card reveal"
	href="<?php echo esc_url( $card['url'] ); ?>"
	data-dest-hub-card="<?php echo esc_attr( $card['slug'] ?? '' ); ?>"
>
	<?php if ( ! empty( $card['photo_url'] ) ) : ?>
		<img
			class="dest-hub-card__photo"
			src="<?php echo esc_url( $card['photo_url'] ); ?>"
			alt="<?php echo esc_attr( $card['photo_alt'] ?? $title ); ?>"
			loading="lazy"
			decoding="async"
			width="640"
			height="360"
		/>
	<?php endif; ?>
	<span class="dest-hub-card__shade" aria-hidden="true"></span>
	<span class="dest-hub-card__top">
		<span class="dest-hub-card__badge"><?php echo esc_html( $card['flight_badge'] ?? '' ); ?></span>
		<span class="dest-hub-card__nights"><?php esc_html_e( '7 ночей / 2 взр.', 'zhiraf-ufa' ); ?></span>
	</span>
	<span class="dest-hub-card__bottom">
		<span class="dest-hub-card__copy">
			<span class="dest-hub-card__country">
				<?php if ( $flag_url ) : ?>
					<img
						class="dest-hub-card__flag"
						src="<?php echo esc_url( $flag_url ); ?>"
						alt=""
						width="28"
						height="19"
						decoding="async"
						aria-hidden="true"
					/>
				<?php endif; ?>
				<span class="dest-hub-card__country-text"><?php echo esc_html( $title ); ?></span>
			</span>
			<?php if ( ! empty( $card['teaser'] ) ) : ?>
				<span class="dest-hub-card__teaser"><?php echo esc_html( $card['teaser'] ); ?></span>
			<?php endif; ?>
		</span>
		<?php if ( ! empty( $card['price_label'] ) ) : ?>
			<span class="dest-hub-card__price"><?php echo esc_html( $card['price_label'] ); ?></span>
		<?php else : ?>
			<span class="dest-hub-card__price dest-hub-card__price--muted"><?php esc_html_e( 'Цена по запросу', 'zhiraf-ufa' ); ?></span>
		<?php endif; ?>
	</span>
</a>
