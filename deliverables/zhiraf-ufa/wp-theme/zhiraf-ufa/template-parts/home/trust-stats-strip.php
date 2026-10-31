<?php
/**
 * Compact trust metrics between office block and tour catalog.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section section--trust-stats" aria-label="<?php esc_attr_e( 'Показатели агентства', 'zhiraf-ufa' ); ?>">
	<div class="container">
		<ul class="trust-stats-strip reveal">
			<li class="trust-stats-strip__item">
				<span class="trust-stats-strip__value"><?php esc_html_e( '15 лет опыта', 'zhiraf-ufa' ); ?></span>
				<span class="trust-stats-strip__hint"><?php esc_html_e( 'с апреля 2011', 'zhiraf-ufa' ); ?></span>
			</li>
			<li class="trust-stats-strip__sep" aria-hidden="true">•</li>
			<li class="trust-stats-strip__item">
				<span class="trust-stats-strip__value"><?php esc_html_e( '12 000+ туристов', 'zhiraf-ufa' ); ?></span>
				<span class="trust-stats-strip__hint"><?php esc_html_e( 'из Башкирии', 'zhiraf-ufa' ); ?></span>
			</li>
			<li class="trust-stats-strip__sep" aria-hidden="true">•</li>
			<li class="trust-stats-strip__item">
				<span class="trust-stats-strip__value"><?php esc_html_e( '0 скрытых комиссий', 'zhiraf-ufa' ); ?></span>
				<span class="trust-stats-strip__hint"><?php esc_html_e( 'цены операторов + договор', 'zhiraf-ufa' ); ?></span>
			</li>
		</ul>
		<p class="trust-stats-strip__reviews reveal">
			<?php esc_html_e( 'Отзывы не публикуем на сайте — смотрите рейтинг на', 'zhiraf-ufa' ); ?>
			<a href="https://yandex.ru/maps" rel="noopener noreferrer" target="_blank"><?php esc_html_e( 'Яндекс.Картах', 'zhiraf-ufa' ); ?></a>,
			<a href="https://2gis.ru/ufa" rel="noopener noreferrer" target="_blank">2ГИС</a>
			<?php esc_html_e( 'и', 'zhiraf-ufa' ); ?>
			<a href="https://vk.com/zhiraf_ufa" rel="noopener noreferrer" target="_blank"><?php esc_html_e( 'ВКонтакте', 'zhiraf-ufa' ); ?></a>.
		</p>
	</div>
</section>
