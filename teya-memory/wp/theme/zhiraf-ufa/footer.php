<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<?php zhiraf_ufa_render_site_logo( 'footer' ); ?>
			<p class="footer-desc"><?php esc_html_e( 'Подбор туров с вылетом из Уфы — честно и по-человечески.', 'zhiraf-ufa' ); ?></p>
			<p class="footer-nap">
				<?php esc_html_e( 'ул. Юрия Гагарина, 74/1, офис 3, Уфа', 'zhiraf-ufa' ); ?><br>
				<a href="tel:+79018105018" data-track="phone">+7 901 810-50-18</a><br>
				<a href="mailto:office@zhiraf-ufa.ru" data-track="email">office@zhiraf-ufa.ru</a>
			</p>
			<p class="footer-rta"><?php esc_html_e( 'ООО «Жираф» · РТА 0012822', 'zhiraf-ufa' ); ?></p>
		</div>
		<div class="footer-col">
			<p class="footer-col__title"><?php esc_html_e( 'Навигация', 'zhiraf-ufa' ); ?></p>
			<ul class="footer-menu">
				<li><a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Туры', 'zhiraf-ufa' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/o-nas/' ) ); ?>"><?php esc_html_e( 'О нас', 'zhiraf-ufa' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Блог', 'zhiraf-ufa' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты', 'zhiraf-ufa' ); ?></a></li>
			</ul>
		</div>
		<div class="footer-col">
			<p class="footer-col__title"><?php esc_html_e( 'Направления', 'zhiraf-ufa' ); ?></p>
			<ul class="footer-menu">
				<li><a href="<?php echo esc_url( home_url( '/tour/turciya/' ) ); ?>"><?php esc_html_e( 'Турция', 'zhiraf-ufa' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/tour/oae/' ) ); ?>"><?php esc_html_e( 'ОАЭ', 'zhiraf-ufa' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/tour/egipet/' ) ); ?>"><?php esc_html_e( 'Египет', 'zhiraf-ufa' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/tour/tailand/' ) ); ?>"><?php esc_html_e( 'Таиланд', 'zhiraf-ufa' ); ?></a></li>
			</ul>
		</div>
		<div class="footer-col">
			<p class="footer-col__title"><?php esc_html_e( 'Правовая информация', 'zhiraf-ufa' ); ?></p>
			<ul class="footer-menu">
				<li><a href="<?php echo esc_url( home_url( '/politika-konfidenczialnosti/' ) ); ?>"><?php esc_html_e( 'Политика конфиденциальности', 'zhiraf-ufa' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/politika-cookies/' ) ); ?>"><?php esc_html_e( 'Политика cookies', 'zhiraf-ufa' ); ?></a></li>
			</ul>
			<button type="button" class="btn btn--primary btn--sm" style="margin-top:24px" data-quiz-open><?php echo esc_html( get_theme_mod( 'zhiraf_cta_text', 'Подобрать тур' ) ); ?></button>
		</div>
	</div>
	<div class="site-footer__legal">
		<div class="container">
			<p><?php echo esc_html( get_theme_mod( 'zhiraf_footer_copyright', '© ' . gmdate( 'Y' ) . ' ООО «Жираф». Туры из Уфы.' ) ); ?></p>
		</div>
	</div>
</footer>

<div id="cookie-banner" class="cookie-banner" role="dialog" aria-live="polite" aria-label="<?php esc_attr_e( 'Уведомление о cookies', 'zhiraf-ufa' ); ?>" hidden>
	<div class="cookie-banner__inner">
		<p><?php
		printf(
			wp_kses_post( __( 'Cookies для работы сайта. <a href="%1$s">Политика конфиденциальности</a> и <a href="%2$s">политика cookies</a>.', 'zhiraf-ufa' ) ),
			esc_url( home_url( '/politika-konfidenczialnosti/' ) ),
			esc_url( home_url( '/politika-cookies/' ) )
		);
		?></p>
		<button type="button" class="btn btn--primary btn--sm" id="cookie-accept"><?php esc_html_e( 'Принять', 'zhiraf-ufa' ); ?></button>
	</div>
</div>

<?php get_template_part( 'template-parts/quiz', 'modal' ); ?>

<?php
$metrika_id = get_theme_mod( 'zhiraf_metrika_id', '' );
?>
<script>
(function(){
	'use strict';
	var METRIKA_ID = <?php echo $metrika_id ? wp_json_encode( $metrika_id ) : 'null'; ?>;
	window.zhirafMetrika = { id: METRIKA_ID, ready: false };
	function loadMetrika(){
		if(!METRIKA_ID || window.zhirafMetrika.ready){return;}
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
