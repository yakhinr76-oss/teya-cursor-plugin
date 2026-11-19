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
$items     = function_exists( 'zhiraf_ufa_curated_expert_nuances' )
	? zhiraf_ufa_curated_expert_nuances( $dest_slug )
	: ( function_exists( 'zhiraf_ufa_turciya_expert_nuances' ) ? zhiraf_ufa_turciya_expert_nuances() : array() );
if ( empty( $items ) ) {
	return;
}
if ( 'oae' === $dest_slug ) {
	$expert_title = __( 'Что важно знать об ОАЭ в этом сезоне', 'zhiraf-ufa' );
} elseif ( 'egipet' === $dest_slug ) {
	$expert_title = __( 'Что важно знать о Египте в этом сезоне', 'zhiraf-ufa' );
} elseif ( 'tailand' === $dest_slug ) {
	$expert_title = __( 'Что важно знать о Таиланде в этом сезоне', 'zhiraf-ufa' );
} elseif ( 'maldivy' === $dest_slug ) {
	$expert_title = __( 'Что важно знать о Мальдивах в этом сезоне', 'zhiraf-ufa' );
} elseif ( 'vietnam' === $dest_slug ) {
	$expert_title = __( 'Что важно знать о Вьетнаме в этом сезоне', 'zhiraf-ufa' );
} elseif ( 'shri-lanka' === $dest_slug ) {
	$expert_title = __( 'Что важно знать о Шри-Ланке в этом сезоне', 'zhiraf-ufa' );
} elseif ( 'indoneziya' === $dest_slug ) {
	$expert_title = __( 'Что важно знать о Бали в этом сезоне', 'zhiraf-ufa' );
} elseif ( 'indiya' === $dest_slug ) {
	$expert_title = __( 'Что важно знать о Гоа в этом сезоне', 'zhiraf-ufa' );
} elseif ( 'sejshely' === $dest_slug ) {
	$expert_title = __( 'Что важно знать о Сейшелах в этом сезоне', 'zhiraf-ufa' );
} else {
	$expert_title = __( 'Что важно знать о Турции в этом сезоне', 'zhiraf-ufa' );
}
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
