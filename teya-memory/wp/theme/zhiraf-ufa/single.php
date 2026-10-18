<?php
/**
 * Single post.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main site-main--single">
	<div class="container entry-layout">
		<?php
		while ( have_posts() ) {
			the_post();
			$card = zhiraf_ufa_blog_card_from_post( get_post() );
			$card['date'] = get_the_date();
			?>
			<article <?php post_class( 'entry entry--single entry--globe' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php
					get_template_part(
						'template-parts/blog/blog-meta-row',
						null,
						array(
							'card' => $card,
							'opts' => array( 'show_date' => true ),
						)
					);
					?>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry-figure entry-figure--globe"><?php the_post_thumbnail( 'large', array( 'class' => 'entry-hero-img' ) ); ?></figure>
				<?php else : ?>
					<figure class="entry-figure entry-figure--globe">
						<?php zhiraf_ufa_blog_card_image( $card, 'entry-hero-img', true, true ); ?>
					</figure>
				<?php endif; ?>
				<div class="entry-content prose">
					<?php the_content(); ?>
				</div>
				<div class="single-post-cta">
					<p><?php esc_html_e( 'Нужен подбор тура с вылетом из Уфы?', 'zhiraf-ufa' ); ?></p>
					<button type="button" class="btn btn--primary" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
				</div>
				<footer class="entry-footer">
					<p>
						<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Каталог туров', 'zhiraf-ufa' ); ?></a>
						· <a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты', 'zhiraf-ufa' ); ?></a>
					</p>
				</footer>
			</article>
			<?php
			comments_template();
		}
		?>
	</div>
</main>
<?php
get_footer();
