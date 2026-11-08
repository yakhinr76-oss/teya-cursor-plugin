<?php
/**
 * Turkey resort filter chips (hero or showcase).
 *
 * @package Zhiraf_Ufa
 * @var array $args { context: hero|showcase, chips?: array }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$context = ! empty( $args['context'] ) ? (string) $args['context'] : 'showcase';
$chips   = ! empty( $args['chips'] ) && is_array( $args['chips'] )
	? $args['chips']
	: ( function_exists( 'zhiraf_ufa_turciya_resort_chips' ) ? zhiraf_ufa_turciya_resort_chips() : array() );

if ( empty( $chips ) ) {
	return;
}

$wrap_class = 'turciya-resort-chips turciya-resort-chips--' . sanitize_html_class( $context );
?>
<div
	class="<?php echo esc_attr( $wrap_class ); ?>"
	data-turciya-resort-chips="<?php echo esc_attr( $context ); ?>"
	role="tablist"
	aria-label="<?php esc_attr_e( 'Фильтр по курортам Турции', 'zhiraf-ufa' ); ?>"
>
	<?php foreach ( $chips as $i => $chip ) : ?>
		<?php
		$id    = ! empty( $chip['id'] ) ? sanitize_html_class( (string) $chip['id'] ) : '';
		$label = ! empty( $chip['label'] ) ? (string) $chip['label'] : '';
		if ( '' === $id || '' === $label ) {
			continue;
		}
		$active = ( 0 === (int) $i && 'all' === $id );
		?>
		<button
			type="button"
			class="turciya-resort-chips__btn<?php echo $active ? ' is-active' : ''; ?>"
			data-resort-filter="<?php echo esc_attr( $id ); ?>"
			role="tab"
			aria-selected="<?php echo $active ? 'true' : 'false'; ?>"
		>
			<?php echo esc_html( $label ); ?>
			<?php if ( 'all' === $id ) : ?>
				<span class="turciya-resort-chips__count">(6)</span>
			<?php endif; ?>
		</button>
	<?php endforeach; ?>
</div>
