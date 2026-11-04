<?php
/**
 * Life situation scenario cards → /tours/?vibe= filters.
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
			<h2 class="section-title"><?php esc_html_e( 'Готовые сценарии отдыха', 'zhiraf-ufa' ); ?></h2>
			<p class="lead">
				<?php esc_html_e( 'Фильтры по базе отелей — без «Подробнее», сразу к подборке.', 'zhiraf-ufa' ); ?>
			</p>
		</div>
		<div class="life-scenarios__grid">
			<?php foreach ( $scenarios as $scenario ) : ?>
				<a
					class="life-scenarios__card life-scenarios__card--link reveal"
					href="<?php echo esc_url( $scenario['url'] ); ?>"
					data-track="<?php echo esc_attr( $scenario['track'] ); ?>"
				>
					<span class="life-scenarios__badge"><?php echo esc_html( $scenario['count_badge'] ); ?></span>
					<span class="life-scenarios__emoji" aria-hidden="true"><?php echo esc_html( $scenario['emoji'] ); ?></span>
					<h3 class="life-scenarios__title"><?php echo esc_html( $scenario['title'] ); ?></h3>
					<p class="life-scenarios__text"><?php echo esc_html( $scenario['text'] ); ?></p>
					<span class="life-scenarios__cta"><?php echo esc_html( $scenario['cta'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
