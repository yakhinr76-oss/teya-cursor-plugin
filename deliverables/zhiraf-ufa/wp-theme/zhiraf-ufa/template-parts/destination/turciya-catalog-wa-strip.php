<?php
/**
 * Компактный перехват под витриной 6 отелей.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : 'turciya';

if ( 'oae' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли подходящий отель в подборке ОАЭ — нужны ещё варианты с вылетом из Уфы под наши даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель в шестёрке? Напишите — подберём из всей базы или сравним Дубай и РАК под бюджет.', 'zhiraf-ufa' );
	$cta_context = 'oae_catalog_under_grid_whatsapp';
} elseif ( 'egipet' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли подходящий отель в подборке Египта — нужны ещё варианты с вылетом из Уфы под наши даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель в шестёрке? Напишите — подберём из базы или сравним Хургаду и Шарм под бюджет.', 'zhiraf-ufa' );
	$cta_context = 'egypt_catalog_under_grid_whatsapp';
} else {
	$wa_text = __( 'Здравствуйте! Не нашли подходящий отель в подборке на странице Турции — нужны ещё варианты с вылетом из Уфы под наши даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель в шестёрке? Напишите — подберём из всей базы или сравним Кемер и Сиде под бюджет.', 'zhiraf-ufa' );
	$cta_context = 'turciya_catalog_under_grid_whatsapp';
}

$wa_base = function_exists( 'zhiraf_ufa_brand_whatsapp_url' ) ? zhiraf_ufa_brand_whatsapp_url() : 'https://wa.me/79018105018';
$wa_url  = function_exists( 'zhiraf_ufa_whatsapp_url_with_text' )
	? zhiraf_ufa_whatsapp_url_with_text( $wa_base, $wa_text )
	: $wa_base;
?>
<div class="turciya-catalog-wa-strip">
	<p class="turciya-catalog-wa-strip__text">
		<?php
		$strip_text = function_exists( 'get_field' ) ? get_field( 'resort_compare_strip_text', get_queried_object() ) : '';
		if ( ! empty( $strip_text ) ) {
			echo esc_html( $strip_text );
		} else {
			echo esc_html( $default_strip );
		}
		?>
		<button type="button" class="turciya-catalog-wa-strip__form-link" data-turciya-lead-open>
			<?php esc_html_e( 'Оставить заявку в попапе', 'zhiraf-ufa' ); ?>
		</button>
	</p>
	<a
		class="turciya-catalog-wa-strip__link"
		href="<?php echo esc_url( $wa_url ); ?>"
		target="_blank"
		rel="noopener noreferrer"
		data-track="messenger"
		data-channel="whatsapp"
		data-cta-context="<?php echo esc_attr( $cta_context ); ?>"
	>
		<?php esc_html_e( 'Написать в WhatsApp', 'zhiraf-ufa' ); ?>
	</a>
</div>