<?php
/**
 * Homepage hard guarantees bar (3 pillars).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pillars = zhiraf_ufa_home_guarantee_pillars();
?>
<section class="section section--guarantees-bar" id="guarantees-bar" aria-labelledby="guarantees-bar-title">
	<div class="container">
		<h2 class="screen-reader-text" id="guarantees-bar-title">
			<?php esc_html_e( 'Гарантии турагентства перед бронированием', 'zhiraf-ufa' ); ?>
		</h2>
		<div class="guarantees-bar reveal">
			<?php foreach ( $pillars as $pillar ) : ?>
				<div class="guarantees-bar__item">
					<div class="guarantees-bar__icon" aria-hidden="true"><?php echo esc_html( $pillar['icon'] ); ?></div>
					<div class="guarantees-bar__body">
						<h3 class="guarantees-bar__title"><?php echo esc_html( $pillar['title'] ); ?></h3>
						<p class="guarantees-bar__text"><?php echo esc_html( $pillar['text'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
