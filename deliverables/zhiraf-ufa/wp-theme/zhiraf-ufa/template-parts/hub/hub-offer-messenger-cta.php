<?php
/**
 * Hub final CTA — messenger picker + deep link (no form fields).
 *
 * @package Zhiraf_Ufa
 * @var array $args {
 *   @type string               $lead             One-line explainer.
 *   @type string               $messenger_label  Picker legend.
 *   @type array<string,string> $messenger_urls   whatsapp|telegram|max URLs.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$lead             = ! empty( $args['lead'] ) ? (string) $args['lead'] : '';
$messenger_label  = ! empty( $args['messenger_label'] ) ? (string) $args['messenger_label'] : __( 'Куда написать:', 'zhiraf-ufa' );
$messenger_urls   = ! empty( $args['messenger_urls'] ) && is_array( $args['messenger_urls'] ) ? $args['messenger_urls'] : array();
$default_href     = ! empty( $messenger_urls['whatsapp'] ) ? (string) $messenger_urls['whatsapp'] : '';
?>
<div
	class="hub-offer-messenger-cta"
	data-hub-messenger-cta
	data-messenger-urls="<?php echo esc_attr( wp_json_encode( $messenger_urls, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ); ?>"
>
	<?php if ( '' !== $lead ) : ?>
		<p class="hub-offer-messenger-cta__lead"><?php echo esc_html( $lead ); ?></p>
	<?php endif; ?>

	<?php
	if ( function_exists( 'zhiraf_ufa_render_messenger_picker' ) ) {
		zhiraf_ufa_render_messenger_picker(
			array(
				'name'    => 'hub_messenger',
				'default' => 'WhatsApp',
				'label'   => $messenger_label,
			)
		);
	}
	?>

	<a
		class="btn btn--primary btn--lg btn--block rounded-xl hub-offer-messenger-cta__go"
		data-hub-messenger-go
		href="<?php echo esc_url( $default_href ); ?>"
		target="_blank"
		rel="noopener noreferrer"
	>
		<?php esc_html_e( 'Написать в WhatsApp →', 'zhiraf-ufa' ); ?>
	</a>
</div>