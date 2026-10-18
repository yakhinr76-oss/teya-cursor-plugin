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
?>
<main id="primary" class="site-main site-main--blog">
	<section class="page-hero">
		<div class="container">
			<h1><?php esc_html_e( 'Блог о путешествиях из Уфы', 'zhiraf-ufa' ); ?></h1>
			<p><?php esc_html_e( 'Практические материалы о подготовке к поездке и направлениях с вылетом из Уфы.', 'zhiraf-ufa' ); ?></p>
		</div>
	</section>
	<section class="section">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="post-grid">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/content/content', 'card' );
					}
					?>
				</div>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<h2><?php esc_html_e( 'Темы редакционного плана', 'zhiraf-ufa' ); ?></h2>
				<p><?php esc_html_e( 'Опубликованных статей пока нет. Ниже — запланированные темы из семантического ядра.', 'zhiraf-ufa' ); ?></p>
				<div class="story-grid">
					<?php foreach ( zhiraf_ufa_blog_topics() as $topic ) : ?>
						<article class="story-card story-card--topic">
							<h3><?php echo esc_html( $topic['title'] ); ?></h3>
							<p><?php echo esc_html( $topic['note'] ); ?> (<?php echo esc_html( $topic['topic'] ); ?>)</p>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
