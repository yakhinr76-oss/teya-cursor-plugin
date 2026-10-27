<?php
/**
 * Three price-orientation cards (not hotel catalog).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args  = isset( $args ) && is_array( $args ) ? $args : array();
$tiers = ! empty( $args['tiers'] ) && is_array( $args['tiers'] ) ? $args['tiers'] : array();
if ( count( $tiers ) < 3 ) {
	return;
}
?>
<section class="section section--price-tiers bg-milky" id="dest-price-tiers">
	<div class="container">
		<div class="section-head section-head--center reveal">
			<h2 class="section-title"><?php esc_html_e( 'Ценовые ориентиры «под ключ»', 'zhiraf-ufa' ); ?></h2>
			<p class="lead"><?php esc_html_e( 'Ориентир на двоих с перелётом и отелем — точную стоимость фиксируем в договоре после подбора.', 'zhiraf-ufa' ); ?></p>
		</div>
		<div class="price-tier-grid">
			<?php foreach ( $tiers as $tier ) : ?>
				<article class="price-tier-card reveal">
					<h3 class="price-tier-card__title"><?php echo esc_html( $tier['title'] ?? '' ); ?></h3>
					<?php if ( ! empty( $tier['subtitle'] ) ) : ?>
						<p class="price-tier-card__subtitle"><?php echo esc_html( $tier['subtitle'] ); ?></p>
					<?php endif; ?>
					<p class="price-tier-card__price"><?php echo esc_html( $tier['price_hint'] ?? '' ); ?></p>
					<a
						class="btn btn--primary btn--sm price-tier-card__cta"
						href="<?php echo esc_url( 'https://wa.me/79018105018?text=' . rawurlencode( (string) ( $tier['wa_message'] ?? '' ) ) ); ?>"
						data-track="messenger"
						data-tier-id="<?php echo esc_attr( $tier['id'] ?? '' ); ?>"
					>
						<?php esc_html_e( 'Запросить подборку этого уровня в WhatsApp', 'zhiraf-ufa' ); ?>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
