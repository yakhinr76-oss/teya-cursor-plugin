<?php
/**
 * Секция: Египет с детьми из Уфы (чек-лист + lead modal).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$all_inc_url  = home_url( '/vse-vklyucheno/' );
$kids_blog    = home_url( '/blog/tury-s-detmi-iz-ufy/' );
$lead_message = __( 'Здравствуйте! Подбираем семейный тур в Египет из Уфы с ребёнком. Пришлите, пожалуйста, 3 варианта отелей с песчаным пляжем или аквапарком — вылет из Уфы.', 'zhiraf-ufa' );
?>
<section id="kids-egypt" class="section turciya-kids-turkey bg-sand">
	<div class="container">
		<div class="turciya-kids-turkey__card">
			<div class="turciya-kids-turkey__main">
				<p class="turciya-kids-turkey__eyebrow"><?php esc_html_e( 'Семейный чек-лист «Жирафа»', 'zhiraf-ufa' ); ?></p>
				<h2 class="section-title turciya-kids-turkey__title">
					<?php esc_html_e( 'Египет с детьми из Уфы: на что смотреть при выборе', 'zhiraf-ufa' ); ?>
				</h2>
				<p class="turciya-kids-turkey__lead">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: 1: link all-inclusive, 2: link kids blog */
							__( 'Красное море круглый год — важно сразу выбрать курорт и формат %1$s. Критерии для семей — в материале %2$s. Проверяем до брони:', 'zhiraf-ufa' ),
							'<a href="' . esc_url( $all_inc_url ) . '">' . esc_html__( 'питания', 'zhiraf-ufa' ) . '</a>',
							'<a href="' . esc_url( $kids_blog ) . '">' . esc_html__( 'туры с детьми из Уфы', 'zhiraf-ufa' ) . '</a>'
						)
					);
					?>
				</p>
				<ul class="turciya-kids-turkey__list">
					<li><?php esc_html_e( 'Хургада и Макади: песчаный пологий заход — без коралловых тапочек на пляже.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Шарм: понтон и риф — уточняем детские бассейны с подогревом зимой.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Детские клубы, кроватки и питание AI — фиксируем в ваучере и договоре.', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Виза HRG ($25) или Sinai Only в Шарме — подсказываем до оплаты тура.', 'zhiraf-ufa' ); ?></li>
				</ul>
			</div>
			<div class="turciya-kids-turkey__cta">
				<button
					type="button"
					class="btn btn--primary"
					data-turciya-lead-open
					data-curated-lead-open
					data-lead-title="<?php esc_attr_e( 'Подберём 3 семейных отеля в Египте', 'zhiraf-ufa' ); ?>"
					data-lead-subtitle="<?php esc_attr_e( 'Укажите телефон — уточним возраст детей и пришлём варианты с вылетом из Уфы.', 'zhiraf-ufa' ); ?>"
					data-lead-message="<?php echo esc_attr( $lead_message ); ?>"
					data-lead-tour="<?php esc_attr_e( 'Египет, семейный подбор', 'zhiraf-ufa' ); ?>"
				>
					<?php esc_html_e( 'Запросить 3 семейных отеля →', 'zhiraf-ufa' ); ?>
				</button>
			</div>
		</div>
	</div>
</section>