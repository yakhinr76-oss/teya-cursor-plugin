<?php
/**
 * Homepage Globe Trekker blog block (1 featured + 5 cards).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bundle   = zhiraf_ufa_home_globe_blog_bundle();
$featured = $bundle['featured'];
$stack    = $bundle['stack'];
$row2     = $bundle['row2'];
?>
<div class="editorial-globe reveal">
	<div class="editorial-globe__featured">
		<a href="<?php echo esc_url( $featured['url'] ); ?>" class="editorial-globe__featured-link editorial-main__link" data-track="blog_teaser_featured">
			<div class="editorial-main__img-wrap">
				<?php zhiraf_ufa_blog_card_image( $featured, 'editorial-main__img', true, true ); ?>
			</div>
			<div class="editorial-main__content">
				<?php get_template_part( 'template-parts/blog/blog-meta-row', null, array( 'card' => $featured ) ); ?>
				<h3 class="editorial-main__title"><?php echo esc_html( $featured['title'] ); ?></h3>
				<?php if ( ! empty( $featured['excerpt'] ) ) : ?>
					<p class="editorial-main__excerpt"><?php echo esc_html( $featured['excerpt'] ); ?></p>
				<?php endif; ?>
			</div>
		</a>
	</div>

	<div class="editorial-globe__stack editorial-globe-stack">
		<?php foreach ( $stack as $card ) : ?>
			<?php if ( empty( $card['title'] ) ) { continue; } ?>
			<a href="<?php echo esc_url( $card['url'] ); ?>" class="editorial-globe-stack__link" data-track="blog_teaser_stack">
				<div class="editorial-globe-stack__thumb">
					<?php zhiraf_ufa_blog_card_image( $card, 'editorial-globe-stack__img' ); ?>
				</div>
				<div class="editorial-globe-stack__body">
					<?php
					get_template_part(
						'template-parts/blog/blog-meta-row',
						null,
						array(
							'card' => $card,
							'opts' => array( 'compact' => true ),
						)
					);
					?>
					<h3 class="editorial-globe-stack__title"><?php echo esc_html( $card['title'] ); ?></h3>
				</div>
			</a>
		<?php endforeach; ?>
	</div>

	<?php if ( ! empty( $row2 ) ) : ?>
		<div class="editorial-globe__row2">
			<?php foreach ( $row2 as $card ) : ?>
				<?php if ( empty( $card['title'] ) ) { continue; } ?>
				<article class="editorial-globe-card">
					<a href="<?php echo esc_url( $card['url'] ); ?>" class="editorial-globe-card__link" data-track="blog_teaser_card">
						<div class="editorial-globe-card__img-wrap">
							<?php zhiraf_ufa_blog_card_image( $card, 'editorial-globe-card__img' ); ?>
						</div>
						<?php get_template_part( 'template-parts/blog/blog-meta-row', null, array( 'card' => $card ) ); ?>
						<h3 class="editorial-globe-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
