<?php
/**
 * Single deal landing (noindex).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$meta          = zhiraf_ufa_deal_get_meta( get_the_ID() );
	$price         = (int) ( $meta['price_from'] ?? 0 );
	$price_text    = $price > 0 ? number_format( $price, 0, '.', ' ' ) . ' ₽' : '';
	$departure     = (string) ( $meta['departure_date'] ?? '' );
	$nights        = (int) ( $meta['nights'] ?? 0 );
	$hotel         = (string) ( $meta['hotel_name'] ?? '' );
	$meal          = (string) ( $meta['meal_type'] ?? '' );
	$country_slug  = sanitize_key( (string) ( $meta['country_slug'] ?? '' ) );
	$country_name  = $country_slug && function_exists( 'zhiraf_ufa_tour_country_name' ) ? zhiraf_ufa_tour_country_name( $country_slug ) : $country_slug;
	$country_url   = $country_slug ? home_url( '/tour/' . $country_slug . '/' ) : home_url( '/tour/' );
	$prefill       = trim( get_the_title() . ' — вылет ' . $departure );
	?>
	<main id="primary" class="site-main site-main--deal">
		<section class="section hub-offer-final bg-milky">
			<div class="container container--narrow">
				<article class="hub-offer-card hub-offer-card--deal rounded-3xl">
					<p class="hub-offer-card__eyebrow"><?php esc_html_e( 'Спецвылет', 'zhiraf-ufa' ); ?></p>
					<h1 class="hub-offer-card__title"><?php the_title(); ?></h1>
					<?php if ( $departure || $nights ) : ?>
						<ul class="hub-deal-meta">
							<?php if ( $departure ) : ?>
								<li><strong><?php esc_html_e( 'Вылет:', 'zhiraf-ufa' ); ?></strong> <?php echo esc_html( $departure ); ?></li>
							<?php endif; ?>
							<?php if ( $nights > 0 ) : ?>
								<li><strong><?php esc_html_e( 'Ночей:', 'zhiraf-ufa' ); ?></strong> <?php echo esc_html( (string) $nights ); ?></li>
							<?php endif; ?>
							<?php if ( $hotel ) : ?>
								<li><strong><?php esc_html_e( 'Отель:', 'zhiraf-ufa' ); ?></strong> <?php echo esc_html( $hotel ); ?></li>
							<?php endif; ?>
							<?php if ( $meal ) : ?>
								<li><strong><?php esc_html_e( 'Питание:', 'zhiraf-ufa' ); ?></strong> <?php echo esc_html( $meal ); ?></li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
					<?php if ( $price_text ) : ?>
						<p class="hub-deal-price"><?php echo esc_html( sprintf( __( 'от %s за двоих', 'zhiraf-ufa' ), $price_text ) ); ?></p>
					<?php endif; ?>
					<div class="entry-content hub-deal-content">
						<?php the_content(); ?>
					</div>
					<div class="hub-offer-card__actions">
						<button
							type="button"
							class="btn btn--primary btn--lg rounded-xl"
							data-turciya-lead-open
							data-lead-tour="<?php echo esc_attr( $prefill ); ?>"
							data-lead-message="<?php echo esc_attr( $prefill ); ?>"
						>
							<?php esc_html_e( 'Забронировать в мессенджер', 'zhiraf-ufa' ); ?>
						</button>
					</div>
					<?php
					if ( function_exists( 'zhiraf_ufa_render_messenger_picker' ) ) {
						zhiraf_ufa_render_messenger_picker(
							array(
								'context' => 'deal-single',
								'compact' => true,
							)
						);
					}
					?>
					<p class="hub-deal-links">
						<a href="<?php echo esc_url( home_url( '/goryashchie-tury/' ) ); ?>"><?php esc_html_e( 'Смотреть все горящие туры', 'zhiraf-ufa' ); ?></a>
						<?php if ( $country_slug ) : ?>
							· <a href="<?php echo esc_url( $country_url ); ?>"><?php echo esc_html( $country_name ); ?></a>
						<?php endif; ?>
					</p>
				</article>
			</div>
		</section>
	</main>
	<?php
endwhile;

get_footer();