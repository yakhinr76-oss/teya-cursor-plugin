<?php
/**
 * Секция: ОАЭ с детьми из Уфы (чек-лист + lead modal).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$all_inc_url  = home_url( '/vse-vklyucheno/' );
$lead_message = __( 'Здравствуйте! Подбираем семейный тур в ОАЭ из Уфы с ребёнком. Пришлите, пожалуйста, 3 варианта отелей с пляжем или парками рядом — вылет из Уфы.', 'zhiraf-ufa' );
?>
<section id="kids-uae" class="section turciya-kids-turkey bg-sand">
	<div class="container">
		<div class="turciya-kids-turkey__card">
			<div class="turciya-kids-turkey__main">
				<p class="turciya-kids-turkey__eyebrow"><?php esc_html_e( 'Семейный чек-лист «Жирафа»', 'zhiraf-ufa' ); ?></p>
				<h2 class="section-title turciya-kids-turkey__title">
					<?php esc_html_e( 'ОАЭ с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ); ?>
				</h2>
				<p class="turciya-kids-turkey__lead">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: %s: link to all-inclusive landing */
							__( 'Перелёт из Уфы ~5 часов — важно сразу выбрать эмират и формат %s. Проверяем это в каждом отеле до брони:', 'zhiraf-ufa' ),
							'<a href="' . esc_url( $all_inc_url ) . '">' . esc_html__( 'питания', 'zhiraf-ufa' ) . '</a>'
						)
					);
					?>
				</p>
				<ul class="turciya-kids-turkey__list">
					<li><?php esc_html_e( 'Дубай: парки, аквапарки и короткий трансфер до пляжа JBR или крытые детские зоны у отеля.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Рас-эль-Хайма: широкий песок и спокойный All Inclusive — меньше переездов с коляской.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Детские клубы, подогреваемые бассейны зимой и кроватки — уточняем в ваучере.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Туристический налог и депозит — включаем в смету, чтобы не удивлять на ресепшене.', 'zhiraf-ufa' ); ?></li>
				</ul>
			</div>
			<aside class="turciya-kids-turkey__aside">
				<h3 class="turciya-kids-turkey__aside-title">
					<?php esc_html_e( 'Нужен быстрый семейный шорт-лист?', 'zhiraf-ufa' ); ?>
				</h3>
				<p class="turciya-kids-turkey__aside-text">
					<?php esc_html_e( 'Пришлём три проверенных отеля с ценой за двоих и вылетом из Уфы — в WhatsApp, Telegram или MAX.', 'zhiraf-ufa' ); ?></p>
				<button
					type="button"
					class="btn btn--primary turciya-kids-turkey__wa"
					data-turciya-lead-open
					data-cta-context="oae_kids_lead_modal"
					data-lead-title="<?php esc_attr_e( '3 семейных отеля в ОАЭ из Уфы', 'zhiraf-ufa' ); ?>"
					data-lead-subtitle="<?php esc_attr_e( 'Укажите телефон и мессенджер — пришлём три варианта с пляжем или парками рядом.', 'zhiraf-ufa' ); ?>"
					data-lead-message="<?php echo esc_attr( $lead_message ); ?>"
					data-lead-tour="<?php esc_attr_e( 'ОАЭ, 3 семейных отеля', 'zhiraf-ufa' ); ?>"
				>
					<?php esc_html_e( 'Запросить 3 семейных отеля →', 'zhiraf-ufa' ); ?>
				</button>
			</aside>
		</div>
	</div>
</section>