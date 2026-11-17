<?php
/**
 * Sticky mobile WhatsApp + lead bar on destination/resort pages.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args     = isset( $args ) && is_array( $args ) ? $args : array();
$wa_text  = ! empty( $args['wa_text'] ) ? (string) $args['wa_text'] : __( 'Здравствуйте! Хочу подбор тура из Уфы', 'zhiraf-ufa' );
$prefill  = ! empty( $args['quiz_prefill'] ) ? (string) $args['quiz_prefill'] : '';
$use_modal = ! empty( $args['use_curated_modal'] ) || ! empty( $args['use_turciya_modal'] );
?>
<div class="sticky-mobile-cta sticky-mobile-cta--dest" id="sticky-mobile-dest" hidden>
	<a
		class="btn btn--primary btn--sm"
		href="<?php echo esc_url( 'https://wa.me/79018105018?text=' . rawurlencode( $wa_text ) ); ?>"
		data-track="messenger"
	><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></a>
	<?php if ( $use_modal ) : ?>
		<button type="button" class="btn btn--outline btn--sm" data-turciya-lead-open data-curated-lead-open><?php esc_html_e( 'Заявка', 'zhiraf-ufa' ); ?></button>
	<?php else : ?>
		<a class="btn btn--outline btn--sm" href="#dest-lead"><?php esc_html_e( 'Заявка', 'zhiraf-ufa' ); ?></a>
	<?php endif; ?>
</div>
<?php if ( $prefill ) : ?>
	<script>document.documentElement.dataset.destPrefill = <?php echo wp_json_encode( $prefill ); ?>;</script>
<?php endif; ?>
