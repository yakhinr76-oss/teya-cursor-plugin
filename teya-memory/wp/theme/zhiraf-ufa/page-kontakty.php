<?php
/**
 * Template Name: Контакты
 * Template Post Type: page
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main site-main--contacts">
	<section class="page-hero">
		<div class="container">
			<h1><?php esc_html_e( 'Контакты «Жираф» в Уфе', 'zhiraf-ufa' ); ?></h1>
		</div>
	</section>
	<section class="section">
		<div class="container contact-grid">
			<div class="contact-cards">
				<div class="contact-card">
					<h2><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></h2>
					<a href="tel:+79018105018">+7 901 810-50-18</a>
				</div>
				<div class="contact-card">
					<h2><?php esc_html_e( 'Email', 'zhiraf-ufa' ); ?></h2>
					<a href="mailto:office@zhiraf-ufa.ru">office@zhiraf-ufa.ru</a>
				</div>
				<div class="contact-card">
					<h2><?php esc_html_e( 'Адрес', 'zhiraf-ufa' ); ?></h2>
					<p><?php esc_html_e( 'ул. Юрия Гагарина, 74/1, офис 3, г. Уфа, 450068', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="contact-card">
					<h2><?php esc_html_e( 'Часы работы', 'zhiraf-ufa' ); ?></h2>
					<p><?php esc_html_e( 'Пн–Пт 10:00–19:00, Сб 11:00–15:00', 'zhiraf-ufa' ); ?></p>
				</div>
			</div>
			<form class="zhiraf-form mini-form" data-action="zhiraf_lead">
				<h2><?php esc_html_e( 'Быстрая заявка', 'zhiraf-ufa' ); ?></h2>
				<label class="form-field"><span><?php esc_html_e( 'Имя', 'zhiraf-ufa' ); ?></span><input name="name" required /></label>
				<label class="form-field"><span><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span><input name="phone" type="tel" required /></label>
				<?php zhiraf_ufa_consent_fields(); ?>
				<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Отправить', 'zhiraf-ufa' ); ?></button>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
		</div>
	</section>
	<section class="section map-section">
		<div class="container">
			<h2><?php esc_html_e( 'Как нас найти', 'zhiraf-ufa' ); ?></h2>
			<div class="map-frame">
				<iframe title="<?php esc_attr_e( 'Карта — офис Жираф', 'zhiraf-ufa' ); ?>" src="https://yandex.ru/map-widget/v1/?ll=55.979%2C54.738&amp;z=16&amp;pt=55.979,54.738,pm2rdm" width="100%" height="400" style="border:0;" loading="lazy" allowfullscreen></iframe>
			</div>
		</div>
	</section>
	<section class="section section--sand">
		<div class="container prose requisites">
			<h2><?php esc_html_e( 'Реквизиты', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'ООО «Жираф», ИНН 0278177191, ОГРН 1110280012457', 'zhiraf-ufa' ); ?></p>
			<p><?php esc_html_e( 'Юридический адрес: 450068, РБ, г. Уфа, б-р Плеханова, д. 5, кв. 123', 'zhiraf-ufa' ); ?></p>
			<p><?php esc_html_e( 'Банк: ПАО «ПРОМСВЯЗЬБАНК», БИК 042202803', 'zhiraf-ufa' ); ?></p>
			<p><a href="<?php echo esc_url( home_url( '/o-nas/' ) ); ?>"><?php esc_html_e( 'О компании', 'zhiraf-ufa' ); ?></a></p>
		</div>
	</section>
</main>
<?php
get_footer();
