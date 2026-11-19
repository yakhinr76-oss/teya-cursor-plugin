<?php
/**
 * Unified intent checklist (Globe Trekker card + branded SVG checks).
 *
 * @package Zhiraf_Ufa
 *
 * @var array $args Template args: block (required), dest_slug (optional).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$block = isset( $args['block'] ) && is_array( $args['block'] ) ? $args['block'] : array();
if ( empty( $block['items'] ) ) {
	return;
}

$section_id = ! empty( $block['section_id'] ) ? $block['section_id'] : 'intent-checklist';
$eyebrow    = $block['eyebrow'] ?? '';
$title      = $block['title'] ?? '';
$subtitle   = $block['subtitle'] ?? '';
$items      = (array) $block['items'];
$cta        = $block['cta_text'] ?? __( 'Запросить подбор →', 'zhiraf-ufa' );
$aside      = ! empty( $block['aside'] ) && is_array( $block['aside'] ) ? $block['aside'] : null;
$modal      = ! empty( $block['lead_modal'] ) && is_array( $block['lead_modal'] ) ? $block['lead_modal'] : array();

$check_icon = '<svg class="intent-checklist__icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><rect width="20" height="20" rx="6" fill="#F5EFE6"/><path d="M6 10.2L8.6 12.8L14.2 7.2" stroke="#C85A32" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="section intent-checklist bg-sand">
	<div class="container">
		<div class="intent-checklist__card turciya-kids-turkey__card">
			<div class="intent-checklist__main turciya-kids-turkey__main">
				<?php if ( $eyebrow ) : ?>
					<p class="intent-checklist__eyebrow turciya-kids-turkey__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>
				<?php if ( $title ) : ?>
					<h2 class="section-title intent-checklist__title turciya-kids-turkey__title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>
				<?php if ( $subtitle ) : ?>
					<p class="intent-checklist__lead turciya-kids-turkey__lead"><?php echo wp_kses_post( $subtitle ); ?></p>
				<?php endif; ?>
				<ul class="intent-checklist__list turciya-kids-turkey__list">
					<?php foreach ( $items as $item ) : ?>
						<li class="intent-checklist__item">
							<?php echo $check_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG ?>
							<span class="intent-checklist__text"><?php echo esc_html( $item ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php if ( $aside ) : ?>
				<aside class="turciya-kids-turkey__aside intent-checklist__aside">
					<?php if ( ! empty( $aside['title'] ) ) : ?>
						<h3 class="turciya-kids-turkey__aside-title"><?php echo esc_html( $aside['title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $aside['text'] ) ) : ?>
						<p class="turciya-kids-turkey__aside-text"><?php echo esc_html( $aside['text'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $aside['url'] ) && ! empty( $aside['label'] ) ) : ?>
						<a class="btn btn--outline btn--lg turciya-kids-turkey__cta" href="<?php echo esc_url( $aside['url'] ); ?>">
							<?php echo esc_html( $aside['label'] ); ?>
						</a>
					<?php endif; ?>
					<button
						type="button"
						class="btn btn--primary intent-checklist__cta-btn turciya-kids-turkey__wa"
						data-turciya-lead-open
						data-curated-lead-open
						<?php if ( ! empty( $modal['title'] ) ) : ?>
							data-lead-title="<?php echo esc_attr( $modal['title'] ); ?>"
						<?php endif; ?>
						<?php if ( ! empty( $modal['subtitle'] ) ) : ?>
							data-lead-subtitle="<?php echo esc_attr( $modal['subtitle'] ); ?>"
						<?php endif; ?>
						<?php if ( ! empty( $modal['message'] ) ) : ?>
							data-lead-message="<?php echo esc_attr( $modal['message'] ); ?>"
						<?php endif; ?>
						<?php if ( ! empty( $modal['tour'] ) ) : ?>
							data-lead-tour="<?php echo esc_attr( $modal['tour'] ); ?>"
						<?php endif; ?>
					>
						<?php echo esc_html( $cta ); ?>
					</button>
				</aside>
			<?php else : ?>
				<div class="intent-checklist__cta-wrapper">
					<button
						type="button"
						class="btn btn--primary intent-checklist__cta-btn"
						data-turciya-lead-open
						data-curated-lead-open
						<?php if ( ! empty( $modal['title'] ) ) : ?>
							data-lead-title="<?php echo esc_attr( $modal['title'] ); ?>"
						<?php endif; ?>
						<?php if ( ! empty( $modal['subtitle'] ) ) : ?>
							data-lead-subtitle="<?php echo esc_attr( $modal['subtitle'] ); ?>"
						<?php endif; ?>
						<?php if ( ! empty( $modal['message'] ) ) : ?>
							data-lead-message="<?php echo esc_attr( $modal['message'] ); ?>"
						<?php endif; ?>
						<?php if ( ! empty( $modal['tour'] ) ) : ?>
							data-lead-tour="<?php echo esc_attr( $modal['tour'] ); ?>"
						<?php endif; ?>
					>
						<?php echo esc_html( $cta ); ?>
					</button>
					<p class="intent-checklist__micro"><?php esc_html_e( 'Бесплатный подбор · ответим за 15 минут', 'zhiraf-ufa' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>