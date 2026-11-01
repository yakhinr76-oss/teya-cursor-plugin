<?php
/**
 * Life situation scenario cards → WhatsApp.
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
		<div class="section-head reveal">
			<p class="life-scenarios__eyebrow"><?php esc_html_e( 'Точный выбор', 'zhiraf-ufa' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Готовые подборки под вашу ситуацию', 'zhiraf-ufa' ); ?></h2>
			<p class="lead">
				<?php esc_html_e( 'Каждый формат проверен лично: знаем, где нет ступенек для колясок, а где берегут тишину.', 'zhiraf-ufa' ); ?>
			</p>
		</div>
		<div class="life-scenarios__grid">
			<?php foreach ( $scenarios as $scenario ) : ?>
				<?php
				$wa = zhiraf_ufa_whatsapp_url_with_text(
					zhiraf_ufa_brand_whatsapp_url(),
					$scenario['prefill']
				);
				?>
				<a
					class="life-scenarios__card reveal"
					href="<?php echo esc_url( $wa ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					data-track="messenger"
					data-channel="whatsapp"
					data-cta-context="<?php echo esc_attr( $scenario['track'] ); ?>"
				>
					<span class="life-scenarios__emoji" aria-hidden="true"><?php echo esc_html( $scenario['emoji'] ); ?></span>
					<h3 class="life-scenarios__title"><?php echo esc_html( $scenario['title'] ); ?></h3>
					<p class="life-scenarios__text"><?php echo esc_html( $scenario['text'] ); ?></p>
					<span class="life-scenarios__cta"><?php esc_html_e( 'Написать в WhatsApp →', 'zhiraf-ufa' ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
