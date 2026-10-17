<?php
/**
 * Blog card partial.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article <?php post_class( 'post-card' ); ?>>
	<a href="<?php the_permalink(); ?>" class="post-card__link">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'zhiraf-blog', array( 'class' => 'post-card__img', 'loading' => 'lazy' ) );
		} else {
			zhiraf_ufa_img( 'blog-cover-01.jpg', get_the_title(), 'post-card__img', true );
		}
		?>
		<h2 class="post-card__title"><?php the_title(); ?></h2>
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		<p class="post-card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
	</a>
</article>
