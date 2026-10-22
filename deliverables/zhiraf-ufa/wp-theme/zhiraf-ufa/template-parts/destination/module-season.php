<?php
/**
 * Season / climate central module.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $args ) || ! is_array( $args ) ) {
	$args = array();
}
$seasons = ! empty( $args['seasons'] ) && is_array( $args['seasons'] ) ? $args['seasons'] : array();
$title   = ! empty( $args['title'] ) ? (string) $args['title'] : __( 'Сезонность и климат', 'zhiraf-ufa' );
if ( empty( $seasons ) ) {
	return;
}
?>
<section class="section section--dest-module section--dest-season bg-milky" id="dest-module">
	<div class="container">
		<div class="section-head reveal">
			<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
			<p class="lead"><?php esc_html_e( 'Выберите месяц — подскажем, где море спокойнее и где выгоднее цены.', 'zhiraf-ufa' ); ?></p>
		</div>
		<div class="dest-climate-grid dest-climate-grid--module">
			<?php foreach ( $seasons as $i => $season_row ) : ?>
				<article class="dest-climate-card reveal<?php echo 0 === $i ? ' is-active' : ''; ?>" data-season-card>
					<p class="dest-climate-card__label">
						<?php echo esc_html( $season_row['label'] ?? '' ); ?>
						<?php if ( ! empty( $season_row['months'] ) ) : ?>
							<span class="dest-climate-card__months"><?php echo esc_html( $season_row['months'] ); ?></span>
						<?php endif; ?>
					</p>
					<?php if ( ! empty( $season_row['air'] ) || ! empty( $season_row['water'] ) ) : ?>
						<dl class="dest-climate-card__stats">
							<?php if ( ! empty( $season_row['air'] ) ) : ?>
								<div><dt><?php esc_html_e( 'Воздух', 'zhiraf-ufa' ); ?></dt><dd><?php echo esc_html( $season_row['air'] ); ?></dd></div>
							<?php endif; ?>
							<?php if ( ! empty( $season_row['water'] ) ) : ?>
								<div><dt><?php esc_html_e( 'Вода', 'zhiraf-ufa' ); ?></dt><dd><?php echo esc_html( $season_row['water'] ); ?></dd></div>
							<?php endif; ?>
						</dl>
					<?php endif; ?>
					<?php if ( ! empty( $season_row['tip'] ) ) : ?>
						<p class="dest-climate-card__tip"><?php echo esc_html( $season_row['tip'] ); ?></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
