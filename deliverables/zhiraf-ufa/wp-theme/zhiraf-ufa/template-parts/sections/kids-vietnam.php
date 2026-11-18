<?php
/**
 * Секция: Вьетнам с детьми из Уфы (чек-лист + lead modal).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$kids_blog    = home_url( '/blog/tury-s-detmi-iz-ufy/' );
$vse_url      = home_url( '/vse-vklyucheno/' );
$lead_message = __( 'Здравствуйте! Подбираем семейный тур во Вьетнам из Уфы с ребёнком. Пришлите, пожалуйста, 3 варианта (Камрань, Нячанг или Фукуок) с аквапарком или All Inclusive — вылет из Уфы.', 'zhiraf-ufa' );
?>
<section id="kids-vietnam" class="section turciya-kids-turkey bg-sand">
	<div class="container">
		<div class="turciya-kids-turkey__card">
			<div class="turciya-kids-turkey__main">
				<p class="turciya-kids-turkey__eyebrow"><?php esc_html_e( 'Семейный чек-лист «Жирафа»', 'zhiraf-ufa' ); ?></p>
				<h2 class="section-title turciya-kids-turkey__title">
					<?php esc_html_e( 'Вьетнам с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ); ?>
				</h2>
				<p class="turciya-kids-turkey__lead">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: 1: link all-inclusive, 2: link kids blog */
							__( 'Сезон штормов разный у Нячанга и Фукуока — важно сверить месяц поездки и формат %1$s. Критерии для семей — в материале %2$s. Проверяем до брони:', 'zhiraf-ufa' ),
							'<a href="' . esc_url( $vse_url ) . '">' . esc_html__( 'питания', 'zhiraf-ufa' ) . '</a>',
							'<a href="' . esc_url( $kids_blog ) . '">' . esc_html__( 'туры с детьми из Уфы', 'zhiraf-ufa' ) . '</a>'
						)
					);
					?>
				</p>
				<ul class="turciya-kids-turkey__list">
					<li><?php esc_html_e( 'Камрань: гигантские резорты с аквапарками и AI — удобно с малышами.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Нячанг: городской пляж — смотрим, первая ли линия и сезон волн (осень–зима).', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Фукуок: островной релакс, Vinpearl Safari и спокойное море зимой.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Безвиз 45 дней — загранпаспорт от 6 месяцев, фиксируем в договоре.', 'zhiraf-ufa' ); ?></li>
				</ul>
			</div>
			<div class="turciya-kids-turkey__cta">
				<button
					type="button"
					class="btn btn--primary"
					data-turciya-lead-open
					data-curated-lead-open
					data-lead-title="<?php esc_attr_e( 'Подберём 3 семейных отеля во Вьетнаме', 'zhiraf-ufa' ); ?>"
					data-lead-subtitle="<?php esc_attr_e( 'Укажите телефон — уточним возраст детей и месяц поездки.', 'zhiraf-ufa' ); ?>"
					data-lead-message="<?php echo esc_attr( $lead_message ); ?>"
					data-lead-tour="<?php esc_attr_e( 'Вьетнам, семейный подбор', 'zhiraf-ufa' ); ?>"
				>
					<?php esc_html_e( 'Запросить 3 семейных отеля →', 'zhiraf-ufa' ); ?>
				</button>
			</div>
		</div>
	</div>
</section>