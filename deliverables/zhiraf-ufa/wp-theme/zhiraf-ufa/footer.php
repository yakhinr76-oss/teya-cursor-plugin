<?php
/**
 * Footer template.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<p class="footer-title"><?php esc_html_e( 'Турагентство «Жираф»', 'zhiraf-ufa' ); ?></p>
			<p class="footer-desc"><?php esc_html_e( 'Подбор туров с вылетом из Уфы — честно и по-человечески.', 'zhiraf-ufa' ); ?></p>
			<p class="footer-nap">
				<?php esc_html_e( 'ул. Юрия Гагарина, 74/1, офис 3, Уфа', 'zhiraf-ufa' ); ?><br>
				<a href="tel:+79018105018" data-track="phone">+7 901 810-50-18</a><br>
				<a href="mailto:office@zhiraf-ufa.ru" data-track="email">office@zhiraf-ufa.ru</a>
			</p>
			<p class="footer-rta"><?php esc_html_e( 'Реестр ТА: 0012822', 'zhiraf-ufa' ); ?></p>
		</div>
		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Подвал', 'zhiraf-ufa' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-menu',
					'container'      => false,
					'fallback_cb'    => function () {
						$links = array(
							home_url( '/tour/' ) => __( 'Туры', 'zhiraf-ufa' ),
							home_url( '/o-nas/' ) => __( 'О нас', 'zhiraf-ufa' ),
							home_url( '/blog/' ) => __( 'Блог', 'zhiraf-ufa' ),
							home_url( '/kontakty/' ) => __( 'Контакты', 'zhiraf-ufa' ),
							home_url( '/politika-konfidenczialnosti/' ) => __( 'Политика конфиденциальности', 'zhiraf-ufa' ),
							home_url( '/politika-cookies/' ) => __( 'Политика cookies', 'zhiraf-ufa' ),
						);
						echo '<ul class="footer-menu">';
						foreach ( $links as $url => $label ) {
							printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
						}
						echo '</ul>';
					},
				)
			);
			?>
		</nav>
		<div class="site-footer__cta">
			<button type="button" class="btn btn--primary" data-quiz-open><?php echo esc_html( get_theme_mod( 'zhiraf_cta_text', 'Подобрать тур' ) ); ?></button>
			<a class="footer-vk" href="https://vk.com/zhiraf_ufa" rel="noopener noreferrer" target="_blank">ВКонтакте</a>
		</div>
	</div>
	<div class="site-footer__legal">
		<div class="container">
			<p><?php echo esc_html( get_theme_mod( 'zhiraf_footer_copyright', '© ' . gmdate( 'Y' ) . ' ООО «Жираф». Туры из Уфы.' ) ); ?></p>
			<p class="footer-legal-links">
				<a href="<?php echo esc_url( home_url( '/politika-konfidenczialnosti/' ) ); ?>"><?php esc_html_e( 'Политика конфиденциальности', 'zhiraf-ufa' ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/politika-cookies/' ) ); ?>"><?php esc_html_e( 'Политика cookies', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</div>
</footer>

<div id="cookie-banner" class="cookie-banner" role="dialog" aria-live="polite" aria-label="<?php esc_attr_e( 'Уведомление о cookies', 'zhiraf-ufa' ); ?>" hidden>
	<div class="cookie-banner__inner container">
		<p><?php
		printf(
			wp_kses_post( __( 'Мы используем cookies для работы сайта и аналитики. Подробнее в <a href="%1$s">политике cookies</a> и <a href="%2$s">политике конфиденциальности</a>.', 'zhiraf-ufa' ) ),
			esc_url( home_url( '/politika-cookies/' ) ),
			esc_url( home_url( '/politika-konfidenczialnosti/' ) )
		);
		?></p>
		<button type="button" class="btn btn--primary" id="cookie-accept"><?php esc_html_e( 'Принять cookies', 'zhiraf-ufa' ); ?></button>
	</div>
</div>

<?php get_template_part( 'template-parts/quiz', 'modal' ); ?>

<?php
$metrika_id = get_theme_mod( 'zhiraf_metrika_id', '' );
?>
<!-- Yandex.Metrika stub: set ID in Customizer to activate -->
<script>
(function(){
	'use strict';
	var METRIKA_ID = <?php echo $metrika_id ? wp_json_encode( $metrika_id ) : 'null'; ?>;
	window.zhirafMetrika = { id: METRIKA_ID, ready: false };
	function loadMetrika(){
		if(!METRIKA_ID || window.zhirafMetrika.ready){return;}
		// Defer load until cookie consent when ID is set.
		if(!window.localStorage.getItem('zhiraf_cookie_consent')){return;}
		var s=document.createElement('script');
		s.src='https://mc.yandex.ru/metrika/tag.js';
		s.defer=true;
		document.body.appendChild(s);
		window.zhirafMetrika.ready=true;
	}
	document.addEventListener('zhiraf:cookie-accepted', loadMetrika);
	if(METRIKA_ID && window.localStorage.getItem('zhiraf_cookie_consent')){loadMetrika();}
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
