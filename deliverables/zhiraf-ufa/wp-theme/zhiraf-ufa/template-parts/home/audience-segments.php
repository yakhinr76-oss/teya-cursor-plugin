<?php
/**
 * Homepage audience segments (block 2 after hero).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$segments = zhiraf_ufa_audience_segments();
if ( empty( $segments ) ) {
	return;
}
?>
<section class="section section--audience bg-milky" id="audience-segments">
	<div class="container audience-tabs reveal">
		<header class="section-head">
			<h2 class="section-title"><?php esc_html_e( 'Как мы подбираем: семьям, парам и тем, кто летит впервые', 'zhiraf-ufa' ); ?></h2>
			<p class="lead">
				<?php esc_html_e( 'Не один шаблон на всех — учитываем состав, опыт и бюджет. Эксперт сравнит операторов и пришлёт 2–4 варианта с вылетом из Уфы.', 'zhiraf-ufa' ); ?>
			</p>
		</header>

		<div class="audience-tabs__nav" role="tablist" aria-label="<?php esc_attr_e( 'Сегменты туристов', 'zhiraf-ufa' ); ?>">
			<?php foreach ( $segments as $i => $segment ) : ?>
				<button
					type="button"
					class="audience-tabs__tab<?php echo 0 === $i ? ' is-active' : ''; ?>"
					role="tab"
					id="audience-tab-<?php echo esc_attr( $segment['id'] ); ?>"
					data-audience="<?php echo esc_attr( $segment['id'] ); ?>"
					aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
					aria-controls="audience-panel-<?php echo esc_attr( $segment['id'] ); ?>"
				><?php echo esc_html( $segment['label'] ); ?></button>
			<?php endforeach; ?>
		</div>

		<?php foreach ( $segments as $i => $segment ) : ?>
			<div
				class="audience-tabs__panel<?php echo 0 === $i ? ' is-active' : ''; ?>"
				id="audience-panel-<?php echo esc_attr( $segment['id'] ); ?>"
				role="tabpanel"
				data-audience-panel="<?php echo esc_attr( $segment['id'] ); ?>"
				aria-labelledby="audience-tab-<?php echo esc_attr( $segment['id'] ); ?>"
				<?php echo 0 === $i ? '' : ' hidden'; ?>
			>
				<ul class="audience-tabs__list">
					<?php foreach ( $segment['items'] as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
				<div class="audience-tabs__actions">
					<a class="btn btn--outline btn--sm" href="<?php echo esc_url( $segment['link'] ); ?>">
						<?php echo esc_html( $segment['cta'] ); ?>
					</a>
					<?php if ( in_array( $segment['id'], array( 'family', 'couple' ), true ) ) : ?>
						<button
							type="button"
							class="btn btn--ghost btn--sm"
							data-quiz-open
							data-vibe="<?php echo esc_attr( $segment['id'] ); ?>"
							data-cta-context="cta_audience_segment_quiz"
						>
							<?php esc_html_e( 'Быстрый расчёт в квизе', 'zhiraf-ufa' ); ?>
						</button>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
