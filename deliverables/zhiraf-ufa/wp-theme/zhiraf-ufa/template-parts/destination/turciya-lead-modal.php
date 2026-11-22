<?php
/**
 * Turkey curated — quick lead in modal (messenger picker on send).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest      = isset( $args['dest'] ) && is_array( $args['dest'] ) ? $args['dest'] : array();
$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : 'turciya';
$is_home_modal = 'home' === $dest_slug;
if ( ! $is_home_modal && empty( $dest ) && function_exists( 'zhiraf_ufa_get_destination_landing' ) ) {
	$dest = zhiraf_ufa_get_destination_landing( $dest_slug );
	if ( is_array( $dest ) ) {
		$dest_slug = ! empty( $dest['slug'] ) ? $dest['slug'] : $dest_slug;
	} else {
		$dest = array();
	}
} elseif ( $is_home_modal ) {
	$dest = array(
		'name'         => __( 'Туры из Уфы', 'zhiraf-ufa' ),
		'quiz_prefill' => __( 'Подбор тура из Уфы', 'zhiraf-ufa' ),
	);
}

if ( ! empty( $args['modal_message'] ) ) {
	$wa_text = (string) $args['modal_message'];
} elseif ( $is_home_modal ) {
	$wa_text = __( 'Здравствуйте! Хочу подобрать тур из Уфы: направление, даты, состав и ориентир по бюджету.', 'zhiraf-ufa' );
} elseif ( 'uzbekistan' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Нужна программа экскурсионного тура в Узбекистан из Уфы — укажу даты, города и состав в переписке.', 'zhiraf-ufa' );
} elseif ( 'gruziya' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Нужна программа экскурсионного тура в Грузию из Уфы — укажу даты, города и состав в переписке.', 'zhiraf-ufa' );
} elseif ( 'armeniya' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Нужна программа экскурсионного тура в Армению из Уфы — укажу даты, маршрут и состав в переписке.', 'zhiraf-ufa' );
} elseif ( 'moskva' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Нужна программа экскурсионного тура в Москву из Уфы — укажу даты, маршрут и состав в переписке.', 'zhiraf-ufa' );
} elseif ( 'sankt-peterburg' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Нужна программа экскурсионного тура в Санкт-Петербург из Уфы — укажу даты, маршрут и состав в переписке.', 'zhiraf-ufa' );
} elseif ( 'kaliningrad' === $dest_slug ) {
	$wa_text = __( 'Здравствуйте! Нужна программа экскурсионного тура в Калининград из Уфы — укажу даты, маршрут и состав в переписке.', 'zhiraf-ufa' );
} elseif ( 'oae' === $dest_slug && function_exists( 'zhiraf_ufa_oae_resort_compare_wa_text' ) ) {
	$wa_text = zhiraf_ufa_oae_resort_compare_wa_text();
} elseif ( 'turciya' === $dest_slug && function_exists( 'zhiraf_ufa_turciya_resort_compare_wa_text' ) ) {
	$wa_text = zhiraf_ufa_turciya_resort_compare_wa_text();
} else {
	$dest_name = ! empty( $dest['name'] ) ? (string) $dest['name'] : $dest_slug;
	/* translators: %s: destination name */
	$wa_text = sprintf( __( 'Здравствуйте! Помогите выбрать отель по направлению «%s» — вылет из Уфы, даты и состав в сообщении.', 'zhiraf-ufa' ), $dest_name );
}

$compare_title = function_exists( 'get_field' ) ? get_field( 'resort_compare_lead_title', get_queried_object() ) : '';
if ( ! empty( $args['modal_title'] ) ) {
	$modal_title = (string) $args['modal_title'];
} elseif ( ! empty( $compare_title ) ) {
	$modal_title = $compare_title;
} elseif ( ! empty( $dest['curated_compare_lead_default'] ) ) {
	$modal_title = (string) $dest['curated_compare_lead_default'];
} elseif ( $is_home_modal ) {
	$modal_title = __( 'Подберём тур из Уфы за 15 минут', 'zhiraf-ufa' );
} elseif ( 'turciya' === $dest_slug ) {
	$modal_title = __( 'Сравним Кемер и Сиде под ваш бюджет', 'zhiraf-ufa' );
} elseif ( function_exists( 'zhiraf_ufa_excursion_landing_slugs' ) && in_array( $dest_slug, zhiraf_ufa_excursion_landing_slugs(), true ) ) {
	$modal_title = __( 'Программа по дням в мессенджер', 'zhiraf-ufa' );
} else {
	$dest_name   = ! empty( $dest['name'] ) ? (string) $dest['name'] : $dest_slug;
	/* translators: %s: destination name */
	$modal_title = sprintf( __( 'Подберём отель: %s', 'zhiraf-ufa' ), $dest_name );
}

$dest_name    = ! empty( $dest['name'] ) ? (string) $dest['name'] : ( 'turciya' === $dest_slug ? __( 'Турция', 'zhiraf-ufa' ) : $dest_slug );
$default_tour = ! empty( $dest['quiz_prefill'] )
	? (string) $dest['quiz_prefill']
	: ( 'turciya' === $dest_slug
		? __( 'Турция, сравнение Кемер и Сиде', 'zhiraf-ufa' )
		: sprintf(
			/* translators: %s: destination name */
			__( '%s, подбор отеля', 'zhiraf-ufa' ),
			$dest_name
		) );
$form_context = $is_home_modal ? 'home-lead-modal' : 'dest-' . $dest_slug . '-compare';
$consent_id   = $is_home_modal ? 'home-lead-modal' : 'dest-' . $dest_slug . '-compare-modal';
$modal_lead   = ! empty( $args['modal_lead'] )
	? (string) $args['modal_lead']
	: ( function_exists( 'zhiraf_ufa_excursion_landing_slugs' ) && in_array( $dest_slug, zhiraf_ufa_excursion_landing_slugs(), true )
		? __( 'Укажите телефон и выберите MAX, WhatsApp или Telegram — пришлём PDF-программу с днями и ориентиром по цене.', 'zhiraf-ufa' )
		: __( 'Укажите телефон и выберите мессенджер — пришлём даты и цену с вылетом из Уфы.', 'zhiraf-ufa' ) );
?>
<div
	id="turciya-lead-modal"
	class="quiz-modal turciya-lead-modal"
	role="dialog"
	aria-modal="true"
	aria-labelledby="turciya-lead-modal-title"
	data-default-title="<?php echo esc_attr( $modal_title ); ?>"
	data-default-lead="<?php echo esc_attr( $modal_lead ); ?>"
	data-default-tour="<?php echo esc_attr( $default_tour ); ?>"
	data-default-message="<?php echo esc_attr( $wa_text ); ?>"
	hidden
	inert
>
	<div class="quiz-modal__backdrop" data-turciya-lead-close tabindex="-1"></div>
	<div class="quiz-modal__panel turciya-lead-modal__panel">
		<button type="button" class="quiz-modal__close" data-turciya-lead-close aria-label="<?php esc_attr_e( 'Закрыть', 'zhiraf-ufa' ); ?>">×</button>
		<div class="quiz-modal__body">
			<p class="home-quiz__eyebrow home-quiz__eyebrow--modal"><?php esc_html_e( 'Подбор за 15 минут', 'zhiraf-ufa' ); ?></p>
			<h2 id="turciya-lead-modal-title" class="home-quiz__title home-quiz__title--modal">
				<?php echo esc_html( $modal_title ); ?>
			</h2>
			<p id="turciya-lead-modal-lead" class="home-quiz__lead home-quiz__lead--modal">
				<?php echo esc_html( $modal_lead ); ?>
			</p>
			<?php
			if ( function_exists( 'zhiraf_ufa_render_unified_lead_form' ) ) {
				zhiraf_ufa_render_unified_lead_form(
					array(
						'form_id'              => 'turciya-lead-modal-form',
						'form_class'           => 'turciya-lead-modal__form',
						'form_context'         => $form_context,
						'consent_id'           => $consent_id,
						'messenger_variant'    => 'quiz',
						'message'              => $wa_text,
						'message_placeholder'  => __( 'Даты, состав, бюджет', 'zhiraf-ufa' ),
						'tour'                 => $default_tour,
						'destination'          => $dest_name,
						'destination_slug'     => $dest_slug,
						'lead_tour_attr'       => true,
						'tour_field_id'        => 'turciya-lead-modal-tour',
						'uon_field_id'         => 'turciya-lead-modal-uon',
					)
				);
			}
			?>
		</div>
	</div>
</div>