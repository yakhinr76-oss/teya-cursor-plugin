<?php
/**
 * Секция: Бали с детьми из Уфы (чек-лист + lead modal).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$kids_blog    = home_url( '/blog/tury-s-detmi-iz-ufy/' );
$vse_url      = home_url( '/vse-vklyucheno/' );
$lead_message = __( 'Здравствуйте! Подбираем семейный тур на Бали из Уфы с ребёнком. Пришлите, пожалуйста, 3 варианта (Нуса-Дуа или Санур) с детским клубом и спокойным морем — вылет из Уфы.', 'zhiraf-ufa' );
?>
<section id="kids-indonesia" class="section turciya-kids-turkey bg-sand">
	<div class="container">
		<div class="turciya-kids-turkey__card">
			<div class="turciya-kids-turkey__main">
				<p class="turciya-kids-turkey__eyebrow"><?php esc_html_e( 'Семейный чек-лист «Жирафа»', 'zhiraf-ufa' ); ?></p>
				<h2 class="section-title turciya-kids-turkey__title">
					<?php esc_html_e( 'Бали с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ); ?>
				</h2>
				<p class="turciya-kids-turkey__lead">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: 1: link all-inclusive, 2: link kids blog */
							__( 'На западном побережье волны — для купания с малышами чаще Нуса-Дуа и Санур. Критерии для семей — в материале %2$s. Проверяем до брони:', 'zhiraf-ufa' ),
							'<a href="' . esc_url( $vse_url ) . '">' . esc_html__( 'питания', 'zhiraf-ufa' ) . '</a>',
							'<a href="' . esc_url( $kids_blog ) . '">' . esc_html__( 'туры с детьми из Уфы', 'zhiraf-ufa' ) . '</a>'
						)
					);
					?>
				</p>
				<ul class="turciya-kids-turkey__list">
					<li><?php esc_html_e( 'Нуса-Дуа: риф гасит волны — удобно купаться с детьми.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Санур: спокойный восточный пляж, променад и рассветы.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Семиньяк и Чангу: серф и закаты — купание по флажкам спасателей.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'VoA / e-VoA и туристический сбор Бали — напомним в договоре.', 'zhiraf-ufa' ); ?></li>
				</ul>
			</div>
			<div class="turciya-kids-turkey__cta">
				<button
					type="button"
					class="btn btn--primary"
					data-turciya-lead-open
					data-curated-lead-open
					data-lead-title="<?php esc_attr_e( 'Подберём 3 семейных отеля на Бали', 'zhiraf-ufa' ); ?>"
					data-lead-subtitle="<?php esc_attr_e( 'Укажите телефон — уточним возраст детей и месяц поездки.', 'zhiraf-ufa' ); ?>"
					data-lead-message="<?php echo esc_attr( $lead_message ); ?>"
					data-lead-tour="<?php esc_attr_e( 'Бали, семейный подбор', 'zhiraf-ufa' ); ?>"
				>
					<?php esc_html_e( 'Запросить 3 семейных отеля →', 'zhiraf-ufa' ); ?>
				</button>
			</div>
		</div>
	</div>
</section>