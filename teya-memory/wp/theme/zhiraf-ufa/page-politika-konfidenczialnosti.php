<?php
/**
 * Template Name: Политика конфиденциальности
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
			<h1><?php esc_html_e( 'Политика конфиденциальности', 'zhiraf-ufa' ); ?></h1>
			<p><?php esc_html_e( 'Настоящая политика определяет порядок обработки персональных данных пользователей сайта турагентства «Жираф» (ООО «Жираф»).', 'zhiraf-ufa' ); ?></p>
			<h2><?php esc_html_e( '1. Оператор', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'ООО «Жираф», ИНН 0278177191, ОГРН 1110280012457. Адрес: ул. Юрия Гагарина, 74/1, офис 3, г. Уфа. Email: office@zhiraf-ufa.ru, тел. +7 901 810-50-18.', 'zhiraf-ufa' ); ?></p>
			<h2><?php esc_html_e( '2. Какие данные обрабатываем', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Имя, телефон, email, сведения о поездке, указанные в формах заявки, квиза и подписки.', 'zhiraf-ufa' ); ?></p>
			<h2><?php esc_html_e( '3. Цели обработки', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Консультация, подбор тура, исполнение договора, обратная связь. Маркетинговые рассылки — только при отдельном согласии.', 'zhiraf-ufa' ); ?></p>
			<h2><?php esc_html_e( '4. Права субъекта', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Вы можете запросить уточнение, блокировку или удаление данных, направив обращение на office@zhiraf-ufa.ru.', 'zhiraf-ufa' ); ?></p>
			<h2><?php esc_html_e( '5. Cookies', 'zhiraf-ufa' ); ?></h2>
			<p><a href="<?php echo esc_url( home_url( '/politika-cookies/' ) ); ?>"><?php esc_html_e( 'Политика cookies', 'zhiraf-ufa' ); ?></a></p>
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
