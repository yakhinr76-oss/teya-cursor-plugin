<?php
/**
 * Catalog hub destination card with price and flight badge.
 *
 * @package Zhiraf_Ufa
 *
 * @var array<string,mixed> $args { dest: destination row from zhiraf_ufa_destinations(). }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest = ! empty( $args['dest'] ) && is_array( $args['dest'] ) ? $args['dest'] : array();
if ( empty( $dest['slug'] ) ) {
	return;
}

$min     = function_exists( 'zhiraf_ufa_get_country_min_tour' ) ? zhiraf_ufa_get_country_min_tour( $dest['slug'] ) : null;
$flight  = function_exists( 'zhiraf_ufa_tour_flight_info' ) ? zhiraf_ufa_tour_flight_info( $min, 'Уфа' ) : array( 'key' => 'ufa', 'icon' => '✈', 'label' => __( 'вылет из Уфы', 'zhiraf-ufa' ), 'line' => '' );
$package = function_exists( 'zhiraf_ufa_bento_package_summary' ) ? zhiraf_ufa_bento_package_summary( $min ) : '';
$teaser  = ! empty( $dest['teaser'] ) ? $dest['teaser'] : __( 'Пакетные туры с перелётом и отелем — подберём под ваши даты.', 'zhiraf-ufa' );
?>
<article class="catalog-dest-card dest-card" data-dest="<?php echo esc_attr( $dest['slug'] ); ?>">
	<a class="catalog-dest-card__media" href="<?php echo esc_url( $dest['url'] ); ?>">
		<?php zhiraf_ufa_img( $dest['image'], $dest['alt'], 'catalog-dest-card__img dest-card__img', true ); ?>
		<span class="catalog-dest-card__flight catalog-dest-card__flight--<?php echo esc_attr( $flight['key'] ); ?>">
			<span aria-hidden="true"><?php echo esc_html( $flight['icon'] ); ?></span>
			<?php echo esc_html( $flight['label'] ); ?>
		</span>
		<?php if ( $min && ! empty( $min['price_rur'] ) ) : ?>
			<span class="catalog-dest-card__price">
				<?php
				printf(
					/* translators: %s: formatted price */
					esc_html__( 'от %s ₽', 'zhiraf-ufa' ),
					esc_html( number_format( (int) $min['price_rur'], 0, '.', ' ' ) )
				);
				?>
				<small><?php esc_html_e( 'на двоих', 'zhiraf-ufa' ); ?></small>
			</span>
		<?php endif; ?>
	</a>
	<div class="catalog-dest-card__body">
		<h2 class="catalog-dest-card__title">
			<a href="<?php echo esc_url( $dest['url'] ); ?>"><?php echo esc_html( $dest['name'] ); ?></a>
		</h2>
		<p class="catalog-dest-card__teaser"><?php echo esc_html( $teaser ); ?></p>
		<?php if ( '' !== $package ) : ?>
			<p class="catalog-dest-card__meta"><?php echo esc_html( $package ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $flight['line'] ) ) : ?>
			<p class="catalog-dest-card__flight-line"><?php echo esc_html( $flight['line'] ); ?></p>
		<?php endif; ?>
		<a class="btn btn--primary btn--sm catalog-dest-card__cta" href="<?php echo esc_url( $dest['url'] ); ?>">
			<?php esc_html_e( 'Выбрать туры', 'zhiraf-ufa' ); ?>
			<span aria-hidden="true"> →</span>
		</a>
	</div>
</article>
