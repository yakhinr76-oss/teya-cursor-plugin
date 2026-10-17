<?php
/**
 * Header template.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Перейти к содержимому', 'zhiraf-ufa' ); ?></a>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Жираф — на главную', 'zhiraf-ufa' ); ?>">
					<span class="site-logo__mark" aria-hidden="true">🦒</span>
					<span class="site-logo__text">Жираф</span>
				</a>
				<?php
			}
			?>
		</div>
		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Главная навигация', 'zhiraf-ufa' ); ?>">
			<button type="button" class="nav-toggle" aria-expanded="false" aria-controls="primary-menu-wrap">
				<span class="nav-toggle__bar"></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Меню', 'zhiraf-ufa' ); ?></span>
			</button>
			<div id="primary-menu-wrap" class="primary-menu-wrap">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_class'     => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => 'zhiraf_ufa_primary_menu_fallback',
					)
				);
				?>
			</div>
		</nav>
		<div class="site-header__actions">
			<a class="header-phone" href="tel:+79018105018" data-track="phone">+7 901 810-50-18</a>
			<button type="button" class="btn btn--primary btn--sm" data-quiz-open>
				<?php echo esc_html( get_theme_mod( 'zhiraf_cta_text', 'Подобрать тур' ) ); ?>
			</button>
		</div>
	</div>
</header>
