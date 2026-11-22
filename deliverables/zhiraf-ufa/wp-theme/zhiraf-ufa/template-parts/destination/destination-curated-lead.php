<?php
/**
 * Curated destination — bottom lead form (phone + messenger).
 *
 * @package Zhiraf_Ufa
 * @var array $args { dest: array, dest_slug: string }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest      = isset( $args['dest'] ) && is_array( $args['dest'] ) ? $args['dest'] : array();
$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : '';
if ( '' === $dest_slug || empty( $dest ) ) {
	return;
}
?>
<section class="section dest-landing-cta" id="dest-lead">
	<div class="container container--narrow dest-landing-cta__inner">
		<h2><?php esc_html_e( 'Готовы обсудить поездку?', 'zhiraf-ufa' ); ?></h2>
		<p>
			<?php
			$cta_lead = ! empty( $dest['cta_lead'] ) ? $dest['cta_lead'] : __( 'Оставьте контакты — перезвоним в рабочее время и предложим 2–4 варианта под ваш бюджет.', 'zhiraf-ufa' );
			echo esc_html( $cta_lead );
			?>
		</p>
		<?php
		$dest_name_cta = ! empty( $dest['name'] ) ? (string) $dest['name'] : $dest_slug;
		$cta_message   = sprintf(
			/* translators: %s: destination name */
			__( 'Здравствуйте! Помогите выбрать отель по направлению «%s» — вылет из Уфы, даты и состав в сообщении.', 'zhiraf-ufa' ),
			$dest_name_cta
		);
		?>
		<div class="unified-lead-form-panel">
			<?php
			if ( function_exists( 'zhiraf_ufa_render_unified_lead_form' ) ) {
				zhiraf_ufa_render_unified_lead_form(
					array(
						'form_context'        => 'dest-' . $dest_slug,
						'consent_id'          => 'dest-lead-' . $dest_slug,
						'messenger_variant'   => 'quiz',
						'message'             => $cta_message,
						'message_placeholder' => ! empty( $dest['cta_message_placeholder'] ) ? (string) $dest['cta_message_placeholder'] : __( 'Даты, состав, бюджет', 'zhiraf-ufa' ),
						'tour'                => ! empty( $dest['quiz_prefill'] ) ? (string) $dest['quiz_prefill'] : '',
						'destination'         => $dest_name_cta,
						'destination_slug'    => $dest_slug,
						'lead_tour_attr'      => true,
						'express_pick'        => '',
					)
				);
			}
			?>
		</div>
		<p class="dest-landing-cta__alt">
			<button type="button" class="btn btn--outline btn--sm" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>">
				<?php esc_html_e( 'Или пройти квиз', 'zhiraf-ufa' ); ?></button>
		</p>
	</div>
</section>