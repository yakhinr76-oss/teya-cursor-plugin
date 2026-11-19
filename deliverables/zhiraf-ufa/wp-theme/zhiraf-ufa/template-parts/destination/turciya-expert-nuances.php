<?php
/**
 * Turkey expert nuances — 3 columns.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : 'turciya';
$dest      = array();
if ( function_exists( 'zhiraf_ufa_get_destination_landing' ) ) {
	$resolved = zhiraf_ufa_get_destination_landing( $dest_slug );
	if ( is_array( $resolved ) ) {
		$dest = $resolved;
	}
}
$items = function_exists( 'zhiraf_ufa_curated_expert_nuances' )
	? zhiraf_ufa_curated_expert_nuances( $dest_slug )
	: ( function_exists( 'zhiraf_ufa_turciya_expert_nuances' ) ? zhiraf_ufa_turciya_expert_nuances() : array() );
if ( empty( $items ) ) {
	return;
}
$expert_title = function_exists( 'zhiraf_ufa_curated_expert_section_title' )
	? zhiraf_ufa_curated_expert_section_title( $dest_slug, $dest )
	: __( 'Что важно знать перед поездкой в этом сезоне', 'zhiraf-ufa' );
?>
<section class="section turciya-expert-nuances bg-sand">
	<div class="container">
		<h2 class="section-title turciya-expert-nuances__title">
			<?php echo esc_html( $expert_title ); ?>
		</h2>
		<div class="turciya-expert-nuances__grid">
			<?php foreach ( $items as $item ) : ?>
				<article class="turciya-expert-card">
					<?php if ( ! empty( $item['icon'] ) ) : ?>
						<span class="turciya-expert-card__icon" aria-hidden="true"><?php echo esc_html( (string) $item['icon'] ); ?></span>
					<?php endif; ?>
					<h3 class="turciya-expert-card__title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
					<p class="turciya-expert-card__text"><?php echo esc_html( $item['text'] ?? '' ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
