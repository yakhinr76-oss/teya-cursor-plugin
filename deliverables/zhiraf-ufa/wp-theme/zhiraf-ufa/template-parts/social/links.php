<?php
/**
 * Social / messenger link row.
 *
 * @package Zhiraf_Ufa
 *
 * @var array<string,mixed> $args Passed from get_template_part().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$variant = ! empty( $args['variant'] ) ? (string) $args['variant'] : 'icon';
$labels  = ! empty( $args['labels'] );
$class   = ! empty( $args['class'] ) ? (string) $args['class'] : '';
$context = ! empty( $args['context'] ) ? (string) $args['context'] : '';

if ( 'pill' === $variant ) {
	$labels = true;
}

$channels = zhiraf_ufa_brand_social_channels();
if ( empty( $channels ) ) {
	return;
}

$wrapper_class = 'zh-social-links zh-social-links--' . sanitize_html_class( $variant );
if ( '' !== $class ) {
	$wrapper_class .= ' ' . esc_attr( $class );
}

$allowed_svg = array(
	'svg'  => array(
		'class'           => true,
		'width'           => true,
		'height'          => true,
		'viewbox'         => true,
		'aria-hidden'     => true,
		'focusable'       => true,
	),
	'path' => array( 'fill' => true, 'd' => true ),
);
?>
<nav class="<?php echo esc_attr( $wrapper_class ); ?>" aria-label="<?php esc_attr_e( 'Соцсети и мессенджеры', 'zhiraf-ufa' ); ?>">
	<?php foreach ( $channels as $channel ) : ?>
		<?php
		$id    = ! empty( $channel['id'] ) ? sanitize_html_class( (string) $channel['id'] ) : '';
		$url   = ! empty( $channel['url'] ) ? (string) $channel['url'] : '';
		$aria_label = ! empty( $channel['label'] ) ? (string) $channel['label'] : '';
		$label      = $aria_label;
		if ( $labels && ! empty( $channel['label_short'] ) ) {
			$label = (string) $channel['label_short'];
		}
		if ( '' === $id || '' === $url || '' === $aria_label ) {
			continue;
		}
		$icon = zhiraf_ufa_social_icon_svg( $id, 'pill' === $variant ? 20 : 22 );
		if ( '' === $icon ) {
			continue;
		}
		$link_class = 'zh-social-link zh-social-link--' . $id;
		if ( 'header' === $context ) {
			$link_class .= ' header-messenger header-messenger--' . $id;
		}
		?>
		<a class="<?php echo esc_attr( $link_class ); ?>" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" data-track="messenger" data-channel="<?php echo esc_attr( $id ); ?>">
			<span class="zh-social-link__icon"><?php echo wp_kses( $icon, $allowed_svg ); ?></span>
			<?php if ( $labels ) : ?>
				<span class="zh-social-link__label"><?php echo esc_html( $label ); ?></span>
			<?php else : ?>
				<span class="screen-reader-text"><?php echo esc_html( $aria_label ); ?></span>
			<?php endif; ?>
		</a>
	<?php endforeach; ?>
</nav>
