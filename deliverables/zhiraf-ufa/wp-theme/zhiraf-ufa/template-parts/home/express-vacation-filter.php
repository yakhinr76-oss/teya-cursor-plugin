<?php
/**
 * Homepage express vacation filter (block 2 after hero).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$chips = zhiraf_ufa_home_express_vacation_chips();
?>
<section class="section section--express-filter" id="express-vacation-filter" aria-labelledby="express-filter-title">
	<div class="container express-filter reveal">
		<div class="express-filter__panel">
			<p class="express-filter__eyebrow"><?php esc_html_e( 'Быстрый подбор', 'zhiraf-ufa' ); ?></p>
			<h2 class="express-filter__title" id="express-filter-title">
				<?php esc_html_e( 'Куда летим из Уфы? Выберите формат отдыха:', 'zhiraf-ufa' ); ?>
			</h2>
			<div class="express-filter__grid">
				<?php foreach ( $chips as $chip ) : ?>
					<?php if ( ! empty( $chip['wa_prefill'] ) ) : ?>
						<?php
						$wa = zhiraf_ufa_whatsapp_url_with_text(
							zhiraf_ufa_brand_whatsapp_url(),
							$chip['wa_prefill']
						);
						?>
						<a
							class="express-filter__chip express-filter__chip--link"
							href="<?php echo esc_url( $wa ); ?>"
							target="_blank"
							rel="noopener noreferrer"
							data-track="messenger"
							data-channel="whatsapp"
							data-cta-context="express_<?php echo esc_attr( $chip['track'] ); ?>"
						>
							<span class="express-filter__emoji" aria-hidden="true"><?php echo esc_html( $chip['emoji'] ); ?></span>
							<span class="express-filter__chip-title"><?php echo esc_html( $chip['title'] ); ?></span>
							<span class="express-filter__chip-text"><?php echo esc_html( $chip['text'] ); ?></span>
						</a>
					<?php else : ?>
						<button
							type="button"
							class="express-filter__chip"
							data-express-vacation="<?php echo esc_attr( $chip['filter'] ); ?>"
							data-cta-context="express_<?php echo esc_attr( $chip['track'] ); ?>"
						>
							<span class="express-filter__emoji" aria-hidden="true"><?php echo esc_html( $chip['emoji'] ); ?></span>
							<span class="express-filter__chip-title"><?php echo esc_html( $chip['title'] ); ?></span>
							<span class="express-filter__chip-text"><?php echo esc_html( $chip['text'] ); ?></span>
						</button>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
