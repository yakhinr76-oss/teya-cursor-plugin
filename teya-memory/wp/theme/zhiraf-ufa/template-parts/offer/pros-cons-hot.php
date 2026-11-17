<?php
/**
 * Pros vs cons block for hot tours landing.
 *
 * @package Zhiraf_Ufa
 * @var array $args { block: array }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$block = ! empty( $args['block'] ) && is_array( $args['block'] ) ? $args['block'] : array();
if ( empty( $block['pros'] ) && empty( $block['cons'] ) ) {
	return;
}
?>
<section class="section hot-pros-cons">
	<div class="container">
		<?php if ( ! empty( $block['title'] ) ) : ?>
			<h2 class="section-title hot-pros-cons__title"><?php echo esc_html( $block['title'] ); ?></h2>
		<?php endif; ?>
		<div class="hot-pros-cons__grid">
			<div class="hot-pros-cons__col hot-pros-cons__col--pro">
				<h3><?php echo esc_html( ! empty( $block['pros_title'] ) ? $block['pros_title'] : __( 'Плюс', 'zhiraf-ufa' ) ); ?></h3>
				<ul>
					<?php foreach ( (array) $block['pros'] as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="hot-pros-cons__col hot-pros-cons__col--con">
				<h3><?php echo esc_html( ! empty( $block['cons_title'] ) ? $block['cons_title'] : __( 'Риск', 'zhiraf-ufa' ) ); ?></h3>
				<ul>
					<?php foreach ( (array) $block['cons'] as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>
