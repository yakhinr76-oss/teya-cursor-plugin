<?php
/**
 * Turkey expert nuances — 3 columns.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = function_exists( 'zhiraf_ufa_turciya_expert_nuances' ) ? zhiraf_ufa_turciya_expert_nuances() : array();
if ( empty( $items ) ) {
	return;
}
?>
<section class="section turciya-expert-nuances bg-sand">
	<div class="container">
		<h2 class="section-title turciya-expert-nuances__title">
			<?php esc_html_e( 'Что важно знать о Турции в этом сезоне', 'zhiraf-ufa' ); ?>
		</h2>
		<div class="turciya-expert-nuances__grid">
			<?php foreach ( $items as $item ) : ?>
				<article class="turciya-expert-card">
					<?php if ( ! empty( $item['icon'] ) ) : ?>
						<span class="turciya-expert-card__icon" aria-hidden="true"><?php echo esc_html( (string) $item['icon'] ); ?></span>
					<?php endif; ?>
					<h3 class="turciya-expert-card__title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
					<p class="turciya-expert-card__text"><?php echo esc_html( $item['text'] ?? '' ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
