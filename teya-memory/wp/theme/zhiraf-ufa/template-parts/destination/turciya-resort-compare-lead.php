<?php
/**
 * Turkey — CTA to open lead modal (messenger picker inside popup).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest = isset( $args['dest'] ) && is_array( $args['dest'] ) ? $args['dest'] : array();
$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : 'turciya';
?>
<section class="section turciya-compare-lead" id="dest-inquiry">
	<div class="container container--narrow turciya-compare-lead__inner">
		<h2 class="turciya-compare-lead__title">
			<?php
			$compare_title = function_exists( 'get_field' ) ? get_field( 'resort_compare_lead_title', get_queried_object() ) : '';
			if ( ! empty( $compare_title ) ) {
				echo esc_html( $compare_title );
			} elseif ( ! empty( $dest['curated_compare_lead_default'] ) ) {
				echo esc_html( $dest['curated_compare_lead_default'] );
			} else {
				esc_html_e( 'Сравним Кемер и Сиде под ваш бюджет за 15 минут', 'zhiraf-ufa' );
			}
			?>
		</h2>
		<p class="turciya-compare-lead__text">
			<?php
			$compare_text = __( 'Подберём 2–3 отеля с актуальными ценами на рейс из Уфы. В попапе выберете MAX, WhatsApp или Telegram — туда уйдёт расчёт.', 'zhiraf-ufa' );
			if ( strpos( $compare_text, 'Быстрее ответить в WhatsApp' ) !== false ) {
				$compare_text = __( 'Нужен ответ прямо сейчас? Нажмите кнопку WhatsApp сразу под каталогом отелей выше.', 'zhiraf-ufa' );
			}
			echo esc_html( $compare_text );
			?>
		</p>
		<button type="button" class="btn btn--primary btn--lg" data-turciya-lead-open>
			<?php esc_html_e( 'Оставить заявку и выбрать мессенджер', 'zhiraf-ufa' ); ?>
		</button>
	</div>
</section>
<?php
get_template_part(
	'template-parts/destination/turciya-lead-modal',
	null,
	array(
		'dest'      => $dest,
		'dest_slug' => $dest_slug,
	)
);
?>