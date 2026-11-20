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
} elseif ( 'vietnam' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли подходящий отель в подборке Вьетнама — нужны ещё варианты с вылетом из Уфы под наши даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель в шестёрке? Напишите — подберём из базы или сравним Нячанг и Фукуок под бюджет.', 'zhiraf-ufa' );
	$cta_context = 'vietnam_catalog_under_grid_whatsapp';
} elseif ( 'tailand' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли подходящий отель в подборке Таиланда — нужны ещё варианты с вылетом из Уфы под наши даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель в шестёрке? Напишите — подберём из базы или сравним Пхукет и Паттайю под бюджет.', 'zhiraf-ufa' );
	$cta_context = 'thailand_catalog_under_grid_whatsapp';
} elseif ( 'shri-lanka' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли подходящий отель в подборке Шри-Ланки — нужны ещё варианты с вылетом из Уфы.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель в шестёрке? Напишите — подберём из базы или сравним Унаватуну и Хиккадуву.', 'zhiraf-ufa' );
	$cta_context = 'sri_lanka_catalog_under_grid_whatsapp';
} elseif ( 'indoneziya' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли подходящий отель на Бали — нужны варианты с вылетом из Уфы.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель? Сравним Нуса-Дуа и Семиньяк под ваш бюджет.', 'zhiraf-ufa' );
	$cta_context = 'indonesia_catalog_under_grid_whatsapp';
} elseif ( 'indiya' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли отель в Гоа — подберём с вылетом из Уфы.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель? Сравним Южный и Северный Гоа.', 'zhiraf-ufa' );
	$cta_context = 'india_catalog_under_grid_whatsapp';
} elseif ( 'sejshely' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли отель на Сейшелах — подберём с вылетом из Уфы.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель? Сравним Маэ и Праслин.', 'zhiraf-ufa' );
	$cta_context = 'seychelles_catalog_under_grid_whatsapp';
} elseif ( 'maldivy' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли подходящий отель в подборке Мальдив — нужны ещё варианты с вылетом из Уфы под наши даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель в шестёрке? Напишите — подберём из базы или сравним катер и сиампан под бюджет.', 'zhiraf-ufa' );
	$cta_context = 'maldives_catalog_under_grid_whatsapp';
} elseif ( 'kitaj' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли отель в подборке Саньи — нужны варианты под наши даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель в шестёрке? Напишите — подберём из базы или сравним Дадунхай и Ялунвань.', 'zhiraf-ufa' );
	$cta_context = 'kitaj_catalog_under_grid_whatsapp';
} elseif ( 'uzbekistan' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли отель по маршруту Узбекистана — подберём под даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель? Сравним Самарканд и Бухару под ваш бюджет.', 'zhiraf-ufa' );
	$cta_context = 'uzbekistan_catalog_under_grid_whatsapp';
} elseif ( 'armeniya' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли отель в Армении — подберём под даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель? Сравним Ереван и Севан.', 'zhiraf-ufa' );
	$cta_context = 'armeniya_catalog_under_grid_whatsapp';
} elseif ( 'abkhaziya' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли отель в Абхазии — подберём под даты и трансфер из Адлера.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель? Подберём по Гагре, Пицунде, Новому Афону или Сухуму — или из полной базы.', 'zhiraf-ufa' );
	$cta_context = 'abkhaziya_catalog_under_grid_whatsapp';
} elseif ( 'rossiya' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли отель в России — подберём под даты и формат (пакет или только отель).', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель? Подскажем Сочи, Кавминводы или Калининград.', 'zhiraf-ufa' );
	$cta_context = 'rossiya_catalog_under_grid_whatsapp';
} elseif ( 'turciya' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Не нашли подходящий отель в подборке на странице Турции — нужны ещё варианты с вылетом из Уфы под наши даты и состав.', 'zhiraf-ufa' );
	$default_strip = __( 'Не нашли свой отель в шестёрке? Напишите — подберём из всей базы или сравним Кемер и Сиде под бюджет.', 'zhiraf-ufa' );
	$cta_context = 'turciya_catalog_under_grid_whatsapp';
} else {
	$dest_name = $dest_slug;
	if ( function_exists( 'zhiraf_ufa_get_destination_landing' ) ) {
		$dest_row = zhiraf_ufa_get_destination_landing( $dest_slug );
		if ( is_array( $dest_row ) && ! empty( $dest_row['name'] ) ) {
			$dest_name = (string) $dest_row['name'];
		}
	}
	/* translators: %s: destination name */
	$wa_text = sprintf( __( 'Здравствуйте! Не нашли отель в подборке «%s» — нужны варианты под наши даты и состав.', 'zhiraf-ufa' ), $dest_name );
	/* translators: %s: destination name */
	$default_strip = sprintf( __( 'Не нашли свой отель в шестёрке? Напишите — подберём из базы по направлению «%s».', 'zhiraf-ufa' ), $dest_name );
	$cta_context   = $dest_slug . '_catalog_under_grid_whatsapp';
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
		class="btn btn--outline btn--sm turciya-catalog-wa-strip__link"
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