<?php
/**
 * 404 template.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main">
	<section class="section">
		<div class="container narrow">
			<h1><?php esc_html_e( 'Страница не найдена', 'zhiraf-ufa' ); ?></h1>
			<p><?php esc_html_e( 'Возможно, адрес изменился. Перейдите на главную или в каталог туров.', 'zhiraf-ufa' ); ?></p>
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'zhiraf-ufa' ); ?></a>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Туры', 'zhiraf-ufa' ); ?></a>
		</div>
	</section>
</main>
<?php
get_footer();
