<?php
/**
 * Three price-orientation cards with photos (not hotel catalog).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args  = isset( $args ) && is_array( $args ) ? $args : array();
$tiers = ! empty( $args['tiers'] ) && is_array( $args['tiers'] ) ? $args['tiers'] : array();
$ctx   = ! empty( $args['ctx'] ) && is_array( $args['ctx'] ) ? $args['ctx'] : array();
if ( count( $tiers ) < 3 ) {
	return;
}

$country_name = ! empty( $ctx['name'] ) ? (string) $ctx['name'] : '';
$is_turciya   = ( ! empty( $args['slug'] ) && str_starts_with( (string) $args['slug'], 'turciya' ) )
	|| ( ! empty( $ctx['quiz_prefill'] ) && str_contains( (string) $ctx['quiz_prefill'], 'Турци' ) );

$section_title = $is_turciya
	? __( 'Ориентиры по бюджету отдыха в Турции', 'zhiraf-ufa' )
	: __( 'Ценовые ориентиры «под ключ»', 'zhiraf-ufa' );

$section_lead = $is_turciya
	? __( 'Цены за полный тур на двоих с перелётом из Уфы, трансфером, питанием и страховкой', 'zhiraf-ufa' )
	: sprintf(
		/* translators: %s: country name */
		__( 'Ориентир на двоих с перелётом в %s — точную стоимость фиксируем в договоре после подбора.', 'zhiraf-ufa' ),
		$country_name ? $country_name : __( 'направление', 'zhiraf-ufa' )
	);
?>
<section class="section section--price-tiers bg-milky" id="dest-price-tiers">
	<div class="container">
		<div class="section-head section-head--center reveal">
			<h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
			<p class="lead"><?php echo esc_html( $section_lead ); ?></p>
		</div>
		<div class="price-tier-grid">
			<?php foreach ( $tiers as $tier ) : ?>
				<?php
				$featured = ! empty( $tier['featured'] );
				$amount   = ! empty( $tier['price_amount'] ) ? (int) $tier['price_amount'] : 0;
				$price_line = $amount > 0 && function_exists( 'zhiraf_ufa_price_tier_format_amount' )
					? zhiraf_ufa_price_tier_format_amount( $amount )
					: (string) ( $tier['price_hint'] ?? '' );
				$card_class = 'price-tier-card reveal' . ( $featured ? ' price-tier-card--featured' : '' );
				$btn_class  = 'btn btn--sm price-tier-card__cta' . ( $featured ? ' btn--primary' : ' btn--outline' );
				?>
				<article class="<?php echo esc_attr( $card_class ); ?>">
					<?php if ( $featured && ! empty( $tier['hit_label'] ) ) : ?>
						<p class="price-tier-card__hit"><?php echo esc_html( $tier['hit_label'] ); ?> 🔥</p>
					<?php endif; ?>
					<?php if ( ! empty( $tier['image'] ) ) : ?>
						<div class="price-tier-card__media">
							<?php
							zhiraf_ufa_img(
								(string) $tier['image'],
								! empty( $tier['image_alt'] ) ? (string) $tier['image_alt'] : (string) ( $tier['title'] ?? '' ),
								'price-tier-card__img',
								true
							);
							?>
							<?php if ( ! empty( $tier['badge'] ) ) : ?>
								<span class="price-tier-card__badge"><?php echo esc_html( $tier['badge'] ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="price-tier-card__body">
						<h3 class="price-tier-card__title"><?php echo esc_html( $tier['title'] ?? '' ); ?></h3>
						<?php if ( $price_line ) : ?>
							<p class="price-tier-card__price"><?php echo esc_html( $price_line ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $tier['price_suffix'] ) ) : ?>
							<p class="price-tier-card__price-note"><?php echo esc_html( $tier['price_suffix'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $tier['bullets'] ) && is_array( $tier['bullets'] ) ) : ?>
							<ul class="price-tier-card__bullets">
								<?php foreach ( $tier['bullets'] as $bullet ) : ?>
									<li><?php echo esc_html( $bullet ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
					<a
						class="<?php echo esc_attr( $btn_class ); ?>"
						href="#dest-lead"
						data-tier-prefill="<?php echo esc_attr( (string) ( $tier['prefill_tag'] ?? '' ) ); ?>"
					>
						<?php echo esc_html( ! empty( $tier['cta_label'] ) ? $tier['cta_label'] : __( 'Запросить подборку', 'zhiraf-ufa' ) ); ?>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
