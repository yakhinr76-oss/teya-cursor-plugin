<?php
/**
 * Blog card partial.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$demo_card = isset( $args['card'] ) ? $args['card'] : null;

if ( is_array( $demo_card ) && ! empty( $demo_card['title'] ) ) {
	$card = $demo_card;
	?>
	<article class="post-card post-card--globe">
		<a href="<?php echo esc_url( $card['url'] ); ?>" class="post-card__link">
			<div class="post-card__img-wrap">
				<?php zhiraf_ufa_blog_card_image( $card, 'post-card__img' ); ?>
			</div>
			<?php get_template_part( 'template-parts/blog/blog-meta-row', null, array( 'card' => $card ) ); ?>
			<h2 class="post-card__title"><?php echo esc_html( $card['title'] ); ?></h2>
			<?php if ( ! empty( $card['excerpt'] ) ) : ?>
				<p class="post-card__excerpt"><?php echo esc_html( $card['excerpt'] ); ?></p>
			<?php endif; ?>
		</a>
	</article>
	<?php
	return;
}

$card  = zhiraf_ufa_blog_card_from_post( get_post() );
$card['date'] = get_the_date();
?>
<article <?php post_class( 'post-card post-card--globe' ); ?>>
	<a href="<?php the_permalink(); ?>" class="post-card__link">
		<div class="post-card__img-wrap">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'zhiraf-blog', array( 'class' => 'post-card__img', 'loading' => 'lazy' ) );
			} else {
				zhiraf_ufa_blog_card_image( $card, 'post-card__img' );
			}
			?>
		</div>
		<?php
		get_template_part(
			'template-parts/blog/blog-meta-row',
			null,
			array(
				'card' => $card,
				'opts' => array( 'show_date' => false ),
			)
		);
		?>
		<h2 class="post-card__title"><?php the_title(); ?></h2>
		<p class="post-card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	</a>
</article>
