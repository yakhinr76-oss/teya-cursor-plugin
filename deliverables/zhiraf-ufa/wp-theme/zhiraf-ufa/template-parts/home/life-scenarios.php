<?php
/**
 * Life situation scenario cards → quiz with prefill.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$scenarios = zhiraf_ufa_home_life_scenarios();
?>
<section class="section section--life-scenarios bg-milky" id="life-scenarios">
	<div class="container">
		<div class="section-head section-head--center reveal">
			<p class="life-scenarios__eyebrow"><?php esc_html_e( 'Точный выбор', 'zhiraf-ufa' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Готовые подборки под вашу ситуацию', 'zhiraf-ufa' ); ?></h2>
			<p class="lead">
				<?php esc_html_e( 'Каждый формат проверен лично: знаем, где нет ступенек для колясок, а где берегут тишину.', 'zhiraf-ufa' ); ?>
			</p>
		</div>
		<div class="life-scenarios__grid">
			<?php foreach ( $scenarios as $scenario ) : ?>
				<?php
				$badge = ! empty( $scenario['badge'] ) && is_array( $scenario['badge'] ) ? $scenario['badge'] : array();
				$tags  = ! empty( $scenario['tags'] ) && is_array( $scenario['tags'] ) ? $scenario['tags'] : array();
				$vibe  = ! empty( $scenario['vibe'] ) ? (string) $scenario['vibe'] : '';
				?>
				<button
					type="button"
					class="life-scenarios__card reveal"
					data-quiz-open
					data-quiz-trigger="scenario"
					data-vibe="<?php echo esc_attr( $vibe ); ?>"
					data-quiz-step-1="<?php echo esc_attr( $scenario['quiz_step_1'] ); ?>"
					data-cta-context="<?php echo esc_attr( $scenario['track'] ); ?>"
				>
					<div class="life-scenarios__card-main">
						<div class="life-scenarios__media">
							<?php
							if ( ! empty( $scenario['image'] ) ) {
								zhiraf_ufa_img( $scenario['image'], $scenario['title'], 'life-scenarios__img', true );
							}
							?>
							<span class="life-scenarios__media-gradient" aria-hidden="true"></span>
							<?php if ( ! empty( $badge['label'] ) ) : ?>
								<span class="life-scenarios__badge life-scenarios__badge--<?php echo esc_attr( ! empty( $badge['type'] ) ? $badge['type'] : 'count' ); ?>">
									<?php echo esc_html( $badge['label'] ); ?>
								</span>
							<?php endif; ?>
							<?php if ( ! empty( $scenario['overlay'] ) ) : ?>
								<span class="life-scenarios__overlay"><?php echo esc_html( $scenario['overlay'] ); ?></span>
							<?php endif; ?>
						</div>
						<div class="life-scenarios__body">
							<h3 class="life-scenarios__title"><?php echo esc_html( $scenario['title'] ); ?></h3>
							<?php if ( $tags ) : ?>
								<ul class="life-scenarios__tags" aria-label="<?php esc_attr_e( 'Маркеры подборки', 'zhiraf-ufa' ); ?>">
									<?php foreach ( $tags as $tag ) : ?>
										<li class="life-scenarios__tag"><?php echo esc_html( $tag ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<p class="life-scenarios__text"><?php echo esc_html( $scenario['text'] ); ?></p>
						</div>
					</div>
					<span class="life-scenarios__action">
						<span class="life-scenarios__action-label"><?php echo esc_html( $scenario['cta'] ); ?></span>
						<span class="life-scenarios__action-arrow" aria-hidden="true">→</span>
					</span>
				</button>
			<?php endforeach; ?>
		</div>
	</div>
</section>
