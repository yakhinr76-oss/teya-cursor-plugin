<?php
/**
 * Search results.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main">
	<header class="page-hero">
		<div class="container">
			<h1><?php printf( esc_html__( 'Поиск: %s', 'zhiraf-ufa' ), esc_html( get_search_query() ) ); ?></h1>
		</div>
	</header>
	<section class="section">
		<div class="container">
			<?php get_search_form(); ?>
			<div class="post-grid">
				<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/content/content', 'card' );
					}
					the_posts_pagination();
				} else {
					get_template_part( 'template-parts/content/content', 'none' );
				}
				?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
