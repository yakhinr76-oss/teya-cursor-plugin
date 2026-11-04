<?php
/**
 * Blog archive featured card (page 1).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card = isset( $args['card'] ) ? $args['card'] : null;
if ( empty( $card ) || empty( $card['title'] ) ) {
	return;
}
?>
<article class="blog-featured reveal">
	<a href="<?php echo esc_url( $card['url'] ); ?>" class="blog-featured__link">
		<div class="blog-featured__img-wrap">
			<?php zhiraf_ufa_blog_card_image( $card, 'blog-featured__img', true, true ); ?>
		</div>
		<?php get_template_part( 'template-parts/blog/blog-meta-row', null, array( 'card' => $card ) ); ?>
		<h2 class="blog-featured__title"><?php echo esc_html( $card['title'] ); ?></h2>
		<?php if ( ! empty( $card['excerpt'] ) ) : ?>
			<p class="blog-featured__excerpt"><?php echo esc_html( $card['excerpt'] ); ?></p>
		<?php endif; ?>
	</a>
</article>
