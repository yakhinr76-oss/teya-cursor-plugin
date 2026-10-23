<?php
/**
 * Template Name: Политика cookies
 * Template Post Type: page
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main site-main--legal">
	<section class="section">
		<div class="container narrow prose">
			<h1><?php esc_html_e( 'Политика cookies', 'zhiraf-ufa' ); ?></h1>
			<p><?php esc_html_e( 'Сайт ООО «Жираф» использует файлы cookies и localStorage для работы форм, запоминания согласия и (при вашем согласии) аналитики.', 'zhiraf-ufa' ); ?></p>
			<h2><?php esc_html_e( 'Типы cookies', 'zhiraf-ufa' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Технические — для безопасности форм и сессии WordPress.', 'zhiraf-ufa' ); ?></li>
				<li><?php esc_html_e( 'zhiraf_cookie_consent — запоминание принятия баннера (localStorage).', 'zhiraf-ufa' ); ?></li>
				<li><?php esc_html_e( 'Аналитические — Яндекс.Метрика только после принятия cookies и при указанном ID в настройках сайта.', 'zhiraf-ufa' ); ?></li>
			</ul>
			<p><?php esc_html_e( 'Google Analytics на сайте не используется.', 'zhiraf-ufa' ); ?></p>
			<p><a href="<?php echo esc_url( home_url( '/politika-konfidenczialnosti/' ) ); ?>"><?php esc_html_e( 'Политика конфиденциальности', 'zhiraf-ufa' ); ?></a></p>
			<?php
			while ( have_posts() ) {
				the_post();
				the_content();
			}
			?>
		</div>
	</section>
</main>
<?php
get_footer();
