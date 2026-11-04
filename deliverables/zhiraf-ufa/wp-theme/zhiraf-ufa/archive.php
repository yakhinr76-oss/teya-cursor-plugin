<?php
/**
 * Archive template.
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
			<h1><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<div class="archive-desc">', '</div>' ); ?>
		</div>
	</header>
	<section class="section">
		<div class="container post-grid">
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
	</section>
</main>
<?php
get_footer();
