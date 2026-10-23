<?php
/**
 * Template Name: Контакты
 * Template Post Type: page
 *
 * Only data already used elsewhere in the theme (header/footer/schema): phone, email,
 * office address, hours, VK, requisites, РТА. Map: link to Yandex Maps by address
 * (no third-party iframe before cookie consent, no unverified coordinates).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$zh_address  = __( 'ул. Юрия Гагарина, 74/1, офис 3, г. Уфа, 450068', 'zhiraf-ufa' );
$zh_map_url  = 'https://yandex.ru/maps/?text=' . rawurlencode( 'Уфа, улица Юрия Гагарина, 74/1' );

get_header();
?>
<main id="primary" class="site-main site-main--contacts">
	<section class="contacts-hero">
		<div class="container">
			<p class="contacts-hero__kicker"><?php esc_html_e( 'Контакты', 'zhiraf-ufa' ); ?></p>
			<h1 class="contacts-hero__title"><?php esc_html_e( 'Контакты «Жираф» в Уфе', 'zhiraf-ufa' ); ?></h1>
			<p class="contacts-hero__lead"><?php esc_html_e( 'Позвоните, напишите или приходите в офис на Гагарина, 74/1. Можно оставить заявку — менеджер свяжется с вами.', 'zhiraf-ufa' ); ?></p>
			<div class="contacts-hero__actions">
				<a class="btn btn--primary btn--lg" href="tel:+79018105018" data-track="phone"><?php esc_html_e( 'Позвонить: +7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
				<a class="btn btn--outline btn--lg" href="#contacts-form"><?php esc_html_e( 'Оставить заявку', 'zhiraf-ufa' ); ?></a>
			</div>
		</div>
	</section>

	<section class="section contacts-main">
		<div class="container contacts-layout">
			<div class="contacts-cards">
				<article class="contacts-card contacts-card--accent">
					<span class="contacts-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg></span>
					<h2 class="contacts-card__label"><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></h2>
					<a class="contacts-card__value contacts-card__value--big" href="tel:+79018105018" data-track="phone">+7 901 810-50-18</a>
					<p class="contacts-card__note"><?php esc_html_e( 'Звонки в часы работы офиса', 'zhiraf-ufa' ); ?></p>
				</article>
				<article class="contacts-card">
					<span class="contacts-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg></span>
					<h2 class="contacts-card__label"><?php esc_html_e( 'Email', 'zhiraf-ufa' ); ?></h2>
					<a class="contacts-card__value" href="mailto:office@zhiraf-ufa.ru" data-track="email">office@zhiraf-ufa.ru</a>
				</article>
				<article class="contacts-card">
					<span class="contacts-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
					<h2 class="contacts-card__label"><?php esc_html_e( 'Адрес офиса', 'zhiraf-ufa' ); ?></h2>
					<p class="contacts-card__value"><?php echo esc_html( $zh_address ); ?></p>
					<a class="contacts-card__link" href="<?php echo esc_url( $zh_map_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Открыть в Яндекс Картах →', 'zhiraf-ufa' ); ?></a>
				</article>
				<article class="contacts-card">
					<span class="contacts-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>
					<h2 class="contacts-card__label"><?php esc_html_e( 'Часы работы', 'zhiraf-ufa' ); ?></h2>
					<dl class="contacts-hours">
						<div><dt><?php esc_html_e( 'Пн–Пт', 'zhiraf-ufa' ); ?></dt><dd>10:00–19:00</dd></div>
						<div><dt><?php esc_html_e( 'Сб', 'zhiraf-ufa' ); ?></dt><dd>11:00–15:00</dd></div>
					</dl>
				</article>
				<article class="contacts-card contacts-card--wide">
					<span class="contacts-card__icon" aria-hidden="true">VK</span>
					<h2 class="contacts-card__label"><?php esc_html_e( 'ВКонтакте', 'zhiraf-ufa' ); ?></h2>
					<a class="contacts-card__link" href="https://vk.com/zhiraf_ufa" rel="noopener noreferrer" target="_blank" data-track="messenger" data-channel="vk"><?php esc_html_e( 'Сообщество «Жираф» ВКонтакте →', 'zhiraf-ufa' ); ?></a>
				</article>
			</div>

			<form id="contacts-form" class="zhiraf-form contacts-form" data-action="zhiraf_lead" data-form-context="contacts">
				<h2 class="contacts-form__title"><?php esc_html_e( 'Быстрая заявка', 'zhiraf-ufa' ); ?></h2>
				<p class="contacts-form__lead"><?php esc_html_e( 'Оставьте имя и телефон — менеджер свяжется с вами и уточнит пожелания по туру.', 'zhiraf-ufa' ); ?></p>
				<label class="form-field"><span><?php esc_html_e( 'Имя', 'zhiraf-ufa' ); ?></span><input name="name" autocomplete="name" required /></label>
				<label class="form-field"><span><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span><input name="phone" type="tel" autocomplete="tel" placeholder="+7" required /></label>
				<?php zhiraf_ufa_consent_fields(); ?>
				<button type="submit" class="btn btn--primary btn--lg contacts-form__submit"><?php esc_html_e( 'Отправить заявку', 'zhiraf-ufa' ); ?></button>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
		</div>
	</section>

	<section class="section contacts-map-section">
		<div class="container">
			<div class="contacts-map">
				<div class="contacts-map__art" aria-hidden="true">
					<span class="contacts-map__pin"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
				</div>
				<div class="contacts-map__body">
					<h2 class="contacts-map__title"><?php esc_html_e( 'Как нас найти', 'zhiraf-ufa' ); ?></h2>
					<p class="contacts-map__addr"><?php echo esc_html( $zh_address ); ?></p>
					<p class="contacts-map__note"><?php esc_html_e( 'Перед визитом лучше позвонить — менеджер подготовит варианты туров к вашему приходу.', 'zhiraf-ufa' ); ?></p>
					<a class="btn btn--primary" href="<?php echo esc_url( $zh_map_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Маршрут в Яндекс Картах', 'zhiraf-ufa' ); ?></a>
				</div>
			</div>
		</div>
	</section>

	<section class="section contacts-req-section bg-sand">
		<div class="container">
			<div class="contacts-req">
				<h2 class="contacts-req__title"><?php esc_html_e( 'Реквизиты', 'zhiraf-ufa' ); ?></h2>
				<dl class="contacts-req__grid">
					<div><dt><?php esc_html_e( 'Организация', 'zhiraf-ufa' ); ?></dt><dd><?php esc_html_e( 'ООО «Жираф»', 'zhiraf-ufa' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Реестровый номер турагента', 'zhiraf-ufa' ); ?></dt><dd><?php esc_html_e( 'РТА 0012822', 'zhiraf-ufa' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'ИНН', 'zhiraf-ufa' ); ?></dt><dd>0278177191</dd></div>
					<div><dt><?php esc_html_e( 'ОГРН', 'zhiraf-ufa' ); ?></dt><dd>1110280012457</dd></div>
					<div class="contacts-req__wide"><dt><?php esc_html_e( 'Юридический адрес', 'zhiraf-ufa' ); ?></dt><dd><?php esc_html_e( '450068, РБ, г. Уфа, б-р Плеханова, д. 5, кв. 123', 'zhiraf-ufa' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Банк', 'zhiraf-ufa' ); ?></dt><dd><?php esc_html_e( 'ПАО «ПРОМСВЯЗЬБАНК»', 'zhiraf-ufa' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'БИК', 'zhiraf-ufa' ); ?></dt><dd>042202803</dd></div>
				</dl>
				<p class="contacts-req__more"><a href="<?php echo esc_url( home_url( '/o-nas/' ) ); ?>"><?php esc_html_e( 'О компании →', 'zhiraf-ufa' ); ?></a></p>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
