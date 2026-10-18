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
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<article <?php post_class( 'entry entry--single' ); ?>>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<p class="entry-meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<?php
					$modified = get_the_modified_date( 'c' );
					if ( $modified !== get_the_date( 'c' ) ) {
						echo ' · <span>' . esc_html__( 'Обновлено:', 'zhiraf-ufa' ) . ' ' . esc_html( get_the_modified_date() ) . '</span>';
					}
					?>
					· <?php echo esc_html( zhiraf_ufa_reading_time() ); ?>
				</p>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="entry-figure"><?php the_post_thumbnail( 'large', array( 'class' => 'entry-hero-img' ) ); ?></figure>
			<?php endif; ?>
			<div class="entry-content prose">
				<?php the_content(); ?>
			</div>
			<footer class="entry-footer">
				<p><?php esc_html_e( 'Планируете поездку?', 'zhiraf-ufa' ); ?>
					<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Каталог туров', 'zhiraf-ufa' ); ?></a>
					· <a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты', 'zhiraf-ufa' ); ?></a>
				</p>
			</footer>
		</article>
		<?php
		comments_template();
	}
	?>
</main>
<?php
get_footer();
