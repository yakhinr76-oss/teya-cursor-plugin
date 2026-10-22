<?php
/**
 * Resort VS battle block (central module).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $args ) || ! is_array( $args ) ) {
	$args = array();
}
$title   = ! empty( $args['title'] ) ? (string) $args['title'] : __( 'Сравнение курортов', 'zhiraf-ufa' );
$columns = ! empty( $args['columns'] ) && is_array( $args['columns'] ) ? $args['columns'] : array();
if ( count( $columns ) < 2 ) {
	return;
}
?>
<section class="section section--dest-module section--dest-battle bg-milky" id="dest-module">
	<div class="container">
		<div class="section-head reveal">
			<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
			<p class="lead"><?php esc_html_e( 'Два главных формата отдыха — выберите свой, мы подберём отели под критерии.', 'zhiraf-ufa' ); ?></p>
		</div>
		<div class="dest-resort-compare dest-resort-compare--module">
			<?php foreach ( $columns as $col ) : ?>
				<article class="dest-resort-compare__col reveal">
					<?php if ( ! empty( $col['image'] ) ) : ?>
						<?php zhiraf_ufa_img( $col['image'], $col['title'] ?? '', 'dest-resort-compare__img', true ); ?>
					<?php endif; ?>
					<div class="dest-resort-compare__body">
						<?php if ( ! empty( $col['tag'] ) ) : ?>
							<p class="dest-resort-compare__tag"><?php echo esc_html( $col['tag'] ); ?></p>
						<?php endif; ?>
						<h3 class="dest-resort-compare__title"><?php echo esc_html( $col['title'] ?? '' ); ?></h3>
						<?php if ( ! empty( $col['rows'] ) && is_array( $col['rows'] ) ) : ?>
							<dl class="dest-resort-compare__rows">
								<?php foreach ( $col['rows'] as $row ) : ?>
									<div>
										<dt><?php echo esc_html( $row['label'] ?? '' ); ?></dt>
										<dd><?php echo esc_html( $row['value'] ?? '' ); ?></dd>
									</div>
								<?php endforeach; ?>
							</dl>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
