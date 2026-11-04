<?php
/**
 * Agency trust guarantees (4 items).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args  = isset( $args ) && is_array( $args ) ? $args : array();
$items = ! empty( $args['items'] ) && is_array( $args['items'] ) ? $args['items'] : array();
if ( empty( $items ) ) {
	return;
}
?>
<section class="section section--agency-guarantees bg-sand" id="dest-guarantees">
	<div class="container">
		<div class="section-head section-head--center reveal">
			<h2 class="section-title"><?php esc_html_e( 'Гарантии турагентства «Жираф»', 'zhiraf-ufa' ); ?></h2>
		</div>
		<ul class="agency-guarantee-grid">
			<?php foreach ( $items as $item ) : ?>
				<li class="agency-guarantee-card reveal">
					<span class="agency-guarantee-card__icon" aria-hidden="true"><?php echo esc_html( $item['icon'] ?? '✓' ); ?></span>
					<h3 class="agency-guarantee-card__title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
					<p class="agency-guarantee-card__text"><?php echo esc_html( $item['text'] ?? '' ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
