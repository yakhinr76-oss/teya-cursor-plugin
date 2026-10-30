<?php
/**
 * Blog posts index (/blog/).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$is_first_page = ! is_paged();
$demo_posts    = zhiraf_ufa_excalibur_demo_posts();
?>
<main id="primary" class="site-main site-main--blog">
	<section class="page-hero page-hero--blog">
		<div class="container">
			<h1><?php esc_html_e( 'Блог о путешествиях из Уфы', 'zhiraf-ufa' ); ?></h1>
			<p><?php esc_html_e( 'Практические материалы о подготовке к поездке и направлениях с вылетом из Уфы.', 'zhiraf-ufa' ); ?></p>
		</div>
	</section>
	<section class="section blog-archive-section">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<?php
				$archive_posts = array();
				while ( have_posts() ) {
					the_post();
					$archive_posts[] = get_post();
				}

				$grid_posts = $archive_posts;
				if ( $is_first_page && ! empty( $archive_posts[0] ) ) {
					get_template_part(
						'template-parts/blog/featured-archive',
						null,
						array( 'card' => zhiraf_ufa_blog_card_from_post( $archive_posts[0] ) )
					);
					$grid_posts = array_slice( $archive_posts, 1 );
				}
				?>
				<div class="post-grid post-grid--globe">
					<?php
					foreach ( $grid_posts as $post ) {
						setup_postdata( $post );
						get_template_part( 'template-parts/content/content', 'card' );
					}
					wp_reset_postdata();
					?>
				</div>
				<div class="blog-archive-pagination">
					<?php the_posts_pagination(); ?>
				</div>
			<?php else : ?>
				<?php
				$featured = array_shift( $demo_posts );
				get_template_part( 'template-parts/blog/featured-archive', null, array( 'card' => $featured ) );
				?>
				<div class="post-grid post-grid--globe">
					<?php
					foreach ( $demo_posts as $card ) {
						get_template_part( 'template-parts/content/content', 'card', array( 'card' => $card ) );
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
