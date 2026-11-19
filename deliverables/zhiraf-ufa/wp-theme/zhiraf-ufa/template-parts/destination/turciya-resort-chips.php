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

$context   = ! empty( $args['context'] ) ? (string) $args['context'] : 'showcase';
$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : 'turciya';
$chips     = ! empty( $args['chips'] ) && is_array( $args['chips'] )
	? $args['chips']
	: ( function_exists( 'zhiraf_ufa_curated_resort_chips' )
		? zhiraf_ufa_curated_resort_chips( $dest_slug )
		: ( function_exists( 'zhiraf_ufa_turciya_resort_chips' ) ? zhiraf_ufa_turciya_resort_chips() : array() ) );

if ( empty( $chips ) ) {
	return;
}

$wrap_class = 'turciya-resort-chips turciya-resort-chips--' . sanitize_html_class( $context );
?>
<div
	class="<?php echo esc_attr( $wrap_class ); ?>"
	data-turciya-resort-chips="<?php echo esc_attr( $context ); ?>"
	role="tablist"
	aria-label="<?php
	$chip_aria_labels = array(
		'oae'        => __( 'Фильтр по эмиратам ОАЭ', 'zhiraf-ufa' ),
		'egipet'     => __( 'Фильтр по курортам Египта', 'zhiraf-ufa' ),
		'tailand'    => __( 'Фильтр по курортам Таиланда', 'zhiraf-ufa' ),
		'maldivy'    => __( 'Фильтр по атоллам Мальдив', 'zhiraf-ufa' ),
		'vietnam'    => __( 'Фильтр по курортам Вьетнама', 'zhiraf-ufa' ),
		'shri-lanka' => __( 'Фильтр по побережьям Шри-Ланки', 'zhiraf-ufa' ),
		'indoneziya' => __( 'Фильтр по курортам Бали', 'zhiraf-ufa' ),
		'indiya'     => __( 'Фильтр по берегам Гоа', 'zhiraf-ufa' ),
		'sejshely'   => __( 'Фильтр по островам Сейшел', 'zhiraf-ufa' ),
		'turciya'    => __( 'Фильтр по курортам Турции', 'zhiraf-ufa' ),
	);
	echo esc_attr( $chip_aria_labels[ $dest_slug ] ?? $chip_aria_labels['turciya'] );
	?>"
>
	<?php foreach ( $chips as $i => $chip ) : ?>
		<?php
		$id    = ! empty( $chip['id'] ) ? sanitize_html_class( (string) $chip['id'] ) : '';
		$label = ! empty( $chip['label'] ) ? (string) $chip['label'] : '';
		if ( '' === $id || '' === $label ) {
			continue;
		}
		$active = ( 0 === (int) $i && 'starter' === $id );
		?>
		<button
			type="button"
			class="turciya-resort-chips__btn<?php echo $active ? ' is-active' : ''; ?>"
			data-turciya-catalog-filter="<?php echo esc_attr( $id ); ?>"
			role="tab"
			aria-selected="<?php echo $active ? 'true' : 'false'; ?>"
		>
			<?php echo esc_html( $label ); ?>
			<?php if ( 'starter' === $id ) : ?>
				<span class="turciya-resort-chips__count">(6)</span>
			<?php endif; ?>
		</button>
	<?php endforeach; ?>
</div>
