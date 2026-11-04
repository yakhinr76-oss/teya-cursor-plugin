<?php
/**
 * Destination vibe selector grid.
 *
 * @package Zhiraf_Ufa
 *
 * @var array<string,mixed> $args {
 *   @type array<string,mixed> $section  Resolved vibe section.
 *   @type string              $quiz_prefill Quiz prefill string.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section      = ! empty( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$quiz_prefill = ! empty( $args['quiz_prefill'] ) ? (string) $args['quiz_prefill'] : '';
$cards        = ! empty( $section['cards'] ) && is_array( $section['cards'] ) ? $section['cards'] : array();
if ( empty( $cards ) ) {
	return;
}
?>
<section class="vibe-section section bg-white" id="vibes-section" aria-labelledby="vibes-section-title">
	<div class="container">
		<div class="vibe-section__head section-head reveal">
			<h2 class="section-title" id="vibes-section-title"><?php echo esc_html( $section['title'] ); ?></h2>
			<?php if ( ! empty( $section['subtitle'] ) ) : ?>
				<p class="lead vibe-section__subtitle"><?php echo esc_html( $section['subtitle'] ); ?></p>
			<?php endif; ?>
		</div>
		<div class="vibe-grid" role="list">
			<?php foreach ( $cards as $card ) : ?>
				<?php
				$color = ! empty( $card['vibe_color'] ) ? $card['vibe_color'] : 'teal';
				$slug  = ! empty( $card['vibe_slug'] ) ? $card['vibe_slug'] : '';
				$alt   = ! empty( $card['vibe_title'] ) ? $card['vibe_title'] : '';
				?>
				<article class="vibe-card vibe-card--<?php echo esc_attr( $color ); ?>" role="listitem">
					<div class="vibe-card__media">
						<?php
						if ( ! empty( $card['vibe_image_id'] ) ) {
							echo wp_get_attachment_image(
								(int) $card['vibe_image_id'],
								'medium_large',
								false,
								array(
									'class'   => 'vibe-card__img',
									'alt'     => esc_attr( $alt ),
									'loading' => 'lazy',
									'decoding' => 'async',
								)
							);
						} elseif ( ! empty( $card['vibe_image'] ) ) {
							zhiraf_ufa_img( $card['vibe_image'], $alt, 'vibe-card__img', true );
						} else {
							echo '<div class="vibe-card__img vibe-card__img--placeholder" aria-hidden="true"></div>';
						}
						?>
						<?php if ( ! empty( $card['vibe_tag'] ) ) : ?>
							<span class="vibe-card__tag"><?php echo esc_html( $card['vibe_tag'] ); ?></span>
						<?php endif; ?>
					</div>
					<div class="vibe-card__body">
						<h3 class="vibe-card__title"><?php echo esc_html( $card['vibe_title'] ); ?></h3>
						<?php if ( ! empty( $card['vibe_subtitle'] ) ) : ?>
							<p class="vibe-card__text"><?php echo esc_html( $card['vibe_subtitle'] ); ?></p>
						<?php endif; ?>
					</div>
					<div class="vibe-card__footer">
						<button
							type="button"
							class="vibe-card__cta btn-open-quiz"
							data-quiz-open
							data-vibe="<?php echo esc_attr( $slug ); ?>"
							data-vibe-title="<?php echo esc_attr( $card['vibe_title'] ); ?>"
							data-quiz-prefill="<?php echo esc_attr( $quiz_prefill ); ?>"
							data-cta-context="cta_vibe_quiz_open"
						>
							<span><?php esc_html_e( 'Выбрать этот вайб', 'zhiraf-ufa' ); ?></span>
							<span class="vibe-card__cta-arrow" aria-hidden="true">→</span>
						</button>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
