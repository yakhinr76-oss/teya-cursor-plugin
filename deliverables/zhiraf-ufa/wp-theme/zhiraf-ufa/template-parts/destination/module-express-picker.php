<?php
/**
 * 10-second express trip matcher (chips).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args         = isset( $args ) && is_array( $args ) ? $args : array();
$matrix       = ! empty( $args['matrix'] ) && is_array( $args['matrix'] ) ? $args['matrix'] : array();
$quiz_prefill = ! empty( $args['quiz_prefill'] ) ? (string) $args['quiz_prefill'] : '';
if ( empty( $matrix ) ) {
	return;
}
$who_options = array(
	'kids'    => __( 'С детьми', 'zhiraf-ufa' ),
	'couple'  => __( 'Пара', 'zhiraf-ufa' ),
	'friends' => __( 'Компания', 'zhiraf-ufa' ),
	'solo'    => __( 'Соло', 'zhiraf-ufa' ),
);
$prio_options = array(
	'beach' => __( 'Пляж без волн', 'zhiraf-ufa' ),
	'price' => __( 'Цена', 'zhiraf-ufa' ),
	'party' => __( 'Тусовка / экскурсии', 'zhiraf-ufa' ),
	'hotel' => __( 'Отель важнее', 'zhiraf-ufa' ),
);
?>
<section class="section section--express-pick bg-sand" id="dest-express-pick" data-express-picker data-matrix="<?php echo esc_attr( wp_json_encode( $matrix ) ); ?>">
	<div class="container container--narrow">
		<div class="section-head section-head--center reveal">
			<h2 class="section-title"><?php esc_html_e( 'Экспресс-подбор за 10 секунд', 'zhiraf-ufa' ); ?></h2>
			<p class="lead"><?php esc_html_e( 'Отметьте состав и приоритет — покажем район и концепцию отеля, параметры можно сразу отправить менеджеру.', 'zhiraf-ufa' ); ?></p>
		</div>
		<div class="express-pick">
			<div class="express-pick__group">
				<p class="express-pick__label"><?php esc_html_e( 'С кем едете', 'zhiraf-ufa' ); ?></p>
				<div class="express-pick__chips" role="group" aria-label="<?php esc_attr_e( 'С кем едете', 'zhiraf-ufa' ); ?>">
					<?php foreach ( $who_options as $key => $label ) : ?>
						<button type="button" class="express-pick__chip" data-express-who="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="express-pick__group">
				<p class="express-pick__label"><?php esc_html_e( 'Приоритет', 'zhiraf-ufa' ); ?></p>
				<div class="express-pick__chips" role="group" aria-label="<?php esc_attr_e( 'Приоритет', 'zhiraf-ufa' ); ?>">
					<?php foreach ( $prio_options as $key => $label ) : ?>
						<button type="button" class="express-pick__chip" data-express-prio="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="express-pick__result" data-express-result hidden>
				<p class="express-pick__result-kicker"><?php esc_html_e( 'Рекомендация «Жираф»', 'zhiraf-ufa' ); ?></p>
				<p class="express-pick__result-area"><strong><?php esc_html_e( 'Район:', 'zhiraf-ufa' ); ?></strong> <span data-express-area></span></p>
				<p class="express-pick__result-concept"><strong><?php esc_html_e( 'Концепция:', 'zhiraf-ufa' ); ?></strong> <span data-express-concept></span></p>
				<div class="express-pick__result-actions">
					<button type="button" class="btn btn--primary btn--sm" data-express-to-form><?php esc_html_e( 'Передать в заявку', 'zhiraf-ufa' ); ?></button>
					<a class="btn btn--outline btn--sm" data-express-wa href="#" data-track="messenger"><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></a>
				</div>
			</div>
		</div>
		<input type="hidden" id="dest-express-prefill-base" value="<?php echo esc_attr( $quiz_prefill ); ?>" />
	</div>
</section>
