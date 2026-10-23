<?php
/**
 * Clickable AI destination cards with price from.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cards = function_exists( 'zhiraf_ufa_all_inclusive_destination_cards' )
	? zhiraf_ufa_all_inclusive_destination_cards()
	: array();
if ( empty( $cards ) ) {
	return;
}
?>
<section class="section ai-dest-cards">
	<div class="container">
		<h2 class="section-title"><?php esc_html_e( 'Куда летим на «всё включено» из Уфы', 'zhiraf-ufa' ); ?></h2>
		<div class="ai-dest-cards__grid">
			<?php foreach ( $cards as $card ) : ?>
				<a class="ai-dest-card" href="<?php echo esc_url( $card['url'] ); ?>">
					<?php zhiraf_ufa_img( $card['image'], $card['name'], 'ai-dest-card__img', true ); ?>
					<div class="ai-dest-card__body">
						<p class="ai-dest-card__tag"><?php echo esc_html( $card['tag'] ); ?></p>
						<h3><?php echo esc_html( $card['name'] ); ?></h3>
						<?php if ( ! empty( $card['price_from'] ) ) : ?>
							<p class="ai-dest-card__price">
								<?php
								printf(
									esc_html__( 'AI/UAI от %s ₽ на двоих', 'zhiraf-ufa' ),
									esc_html( number_format( (int) $card['price_from'], 0, '.', ' ' ) )
								);
								?>
							</p>
						<?php endif; ?>
						<span class="ai-dest-card__cta"><?php esc_html_e( 'Смотреть туры', 'zhiraf-ufa' ); ?> →</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
