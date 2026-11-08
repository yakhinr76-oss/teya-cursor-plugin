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
$only    = ! empty( $args['only'] ) && is_array( $args['only'] ) ? array_map( 'sanitize_key', $args['only'] ) : array();
$order   = ! empty( $args['order'] ) && is_array( $args['order'] ) ? array_map( 'sanitize_key', $args['order'] ) : array();

if ( 'pill' === $variant ) {
	$labels = true;
}

$channels = zhiraf_ufa_brand_social_channels();
if ( empty( $channels ) ) {
	return;
}

if ( ! empty( $only ) ) {
	$allowed = array_flip( $only );
	$channels = array_values(
		array_filter(
			$channels,
			static function ( $channel ) use ( $allowed ) {
				$id = ! empty( $channel['id'] ) ? sanitize_key( (string) $channel['id'] ) : '';

				return isset( $allowed[ $id ] );
			}
		)
	);
}

$sort_ids = ! empty( $order ) ? $order : $only;
if ( ! empty( $sort_ids ) ) {
	$by_id = array();
	foreach ( $channels as $channel ) {
		$id = ! empty( $channel['id'] ) ? sanitize_key( (string) $channel['id'] ) : '';
		if ( '' !== $id ) {
			$by_id[ $id ] = $channel;
		}
	}
	$sorted = array();
	foreach ( $sort_ids as $id ) {
		if ( isset( $by_id[ $id ] ) ) {
			$sorted[] = $by_id[ $id ];
		}
	}
	if ( ! empty( $sorted ) ) {
		$channels = $sorted;
	}
}

if ( empty( $channels ) ) {
	return;
}

$wrapper_class = 'zh-social-links zh-social-links--' . sanitize_html_class( $variant );
if ( 'header' === $context ) {
	$wrapper_class .= ' zh-social-links--header';
} elseif ( 'footer' === $context ) {
	$wrapper_class .= ' zh-social-links--footer';
}
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
	'span' => array(
		'class'       => true,
		'aria-hidden' => true,
	),
	'img'  => array(
		'class'       => true,
		'src'         => true,
		'width'       => true,
		'height'      => true,
		'alt'         => true,
		'decoding'    => true,
		'aria-hidden' => true,
	),
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
		if ( 'pill' === $variant ) {
			$icon_size = 20;
		} elseif ( 'header' === $context ) {
			$icon_size = 20;
		} elseif ( 'footer' === $context ) {
			$icon_size = 36;
		} else {
			$icon_size = 22;
		}
		$icon      = zhiraf_ufa_social_icon_svg( $id, $icon_size );
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
