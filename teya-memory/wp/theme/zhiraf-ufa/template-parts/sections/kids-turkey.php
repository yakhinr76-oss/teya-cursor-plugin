<?php
/**
 * Секция: Турция с детьми из Уфы (чек-лист + перелинковка на гайд).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$kids_guide_url = home_url( '/blog/tury-s-detmi-iz-ufy/' );
$all_inc_url    = home_url( '/vse-vklyucheno/' );
$wa_text        = __( 'Здравствуйте! Подбираем семейный тур в Турцию из Уфы с ребёнком. Нужны отели с песчаным входом и детским меню.', 'zhiraf-ufa' );
$wa_base        = function_exists( 'zhiraf_ufa_brand_whatsapp_url' ) ? zhiraf_ufa_brand_whatsapp_url() : 'https://wa.me/79018105018';
$wa_link        = function_exists( 'zhiraf_ufa_whatsapp_url_with_text' )
	? zhiraf_ufa_whatsapp_url_with_text( $wa_base, $wa_text )
	: $wa_base;
?>
<section id="kids-turkey" class="section turciya-kids-turkey bg-sand">
	<div class="container">
		<div class="turciya-kids-turkey__card">
			<div class="turciya-kids-turkey__main">
				<p class="turciya-kids-turkey__eyebrow"><?php esc_html_e( 'Семейный чек-лист «Жирафа»', 'zhiraf-ufa' ); ?></p>
				<h2 class="section-title turciya-kids-turkey__title">
					<?php esc_html_e( 'Турция с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ); ?>
				</h2>
				<p class="turciya-kids-turkey__lead">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: %s: link to all-inclusive landing */
							__( 'При прямом перелёте из Уфы (4,5–5 часов) важно попасть на нужный пляж и в понятную концепцию %s. Проверяем это в каждом отеле до брони:', 'zhiraf-ufa' ),
							'<a href="' . esc_url( $all_inc_url ) . '">' . esc_html__( '«Всё включено»', 'zhiraf-ufa' ) . '</a>'
						)
					);
					?>
				</p>
				<ul class="turciya-kids-turkey__list">
					<li><?php esc_html_e( 'Пологий песчаный вход (Сиде, Белек, Инжекум) — без резкой глубины и крупной гальки.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Детское меню и блендеры в ресторане, отдельные столы для малышей.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Подогреваемые бассейны в мае и октябре, тень над детскими зонами.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Короткий трансфер от аэропорта Антальи — до 60 минут в пути с детьми.', 'zhiraf-ufa' ); ?></li>
				</ul>
			</div>
			<aside class="turciya-kids-turkey__aside">
				<h3 class="turciya-kids-turkey__aside-title">
					<?php esc_html_e( 'Подробный гид: отдых с детьми из Уфы', 'zhiraf-ufa' ); ?>
				</h3>
				<p class="turciya-kids-turkey__aside-text">
					<?php esc_html_e( 'Аптечка, бассейны в межсезонье и на чём можно сэкономить без потери комфорта.', 'zhiraf-ufa' ); ?></p>
				<a class="btn btn--primary btn--lg turciya-kids-turkey__cta" href="<?php echo esc_url( $kids_guide_url ); ?>">
					<?php esc_html_e( 'Читать гид →', 'zhiraf-ufa' ); ?>
				</a>
				<a
					class="btn btn--outline turciya-kids-turkey__wa"
					href="<?php echo esc_url( $wa_link ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					data-track="messenger"
					data-channel="whatsapp"
					data-cta-context="turciya_kids_whatsapp"
				>
					<?php esc_html_e( 'Запросить 3 семейных отеля в WhatsApp', 'zhiraf-ufa' ); ?>
				</a>
			</aside>
		</div>
	</div>
</section>