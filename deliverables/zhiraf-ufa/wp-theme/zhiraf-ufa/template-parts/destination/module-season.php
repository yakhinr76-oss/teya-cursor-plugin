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
$seasons      = ! empty( $args['seasons'] ) && is_array( $args['seasons'] ) ? $args['seasons'] : array();
$title        = ! empty( $args['title'] ) ? (string) $args['title'] : __( 'Сезонность и климат', 'zhiraf-ufa' );
$quiz_prefill = ! empty( $args['quiz_prefill'] ) ? (string) $args['quiz_prefill'] : '';
if ( empty( $seasons ) ) {
	return;
}
$panel_id = 'dest-season-panel';
?>
<section class="section section--dest-module section--dest-season bg-milky" id="dest-module">
	<div class="container">
		<div class="section-head reveal">
			<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
			<p class="lead"><?php esc_html_e( 'Выберите период — подскажем, где море спокойнее и где выгоднее цены.', 'zhiraf-ufa' ); ?></p>
		</div>
		<div class="dest-season-module" data-dest-season-module>
			<div class="dest-season-tabs" role="tablist" aria-label="<?php echo esc_attr( $title ); ?>">
				<?php foreach ( $seasons as $i => $season_row ) : ?>
					<?php
					$tab_label = trim( (string) ( $season_row['label'] ?? '' ) . ' ' . (string) ( $season_row['months'] ?? '' ) );
					?>
					<button
						type="button"
						class="dest-season-tab<?php echo 0 === $i ? ' is-active' : ''; ?>"
						role="tab"
						id="dest-season-tab-<?php echo (int) $i; ?>"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						aria-controls="<?php echo esc_attr( $panel_id ); ?>"
						data-season-tab
						data-season-index="<?php echo (int) $i; ?>"
					>
						<?php echo esc_html( $tab_label ); ?>
					</button>
				<?php endforeach; ?>
			</div>
			<div class="dest-climate-grid dest-climate-grid--module" id="<?php echo esc_attr( $panel_id ); ?>" role="tabpanel">
				<?php foreach ( $seasons as $i => $season_row ) : ?>
					<article
						class="dest-climate-card reveal<?php echo 0 === $i ? ' is-active' : ''; ?>"
						data-season-card
						data-season-index="<?php echo (int) $i; ?>"
						<?php echo 0 === $i ? '' : ' hidden'; ?>
					>
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
			<p class="dest-season-module__cta-wrap">
				<button
					type="button"
					class="btn btn--primary btn-open-quiz"
					data-quiz-prefill="<?php echo esc_attr( $quiz_prefill ); ?>"
					data-cta-context="cta_season_module_quiz"
				>
					<?php esc_html_e( 'Подобрать тур на эти даты', 'zhiraf-ufa' ); ?>
				</button>
			</p>
		</div>
	</div>
</section>
