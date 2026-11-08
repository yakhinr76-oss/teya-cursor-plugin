<?php
/**
 * Hero: horizontal destination chips (mobile only).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'zhiraf_ufa_hero_mobile_destination_chips' ) ) {
	return;
}

$chips = zhiraf_ufa_hero_mobile_destination_chips();
if ( empty( $chips ) ) {
	return;
}
?>
<nav class="hero-premium__quick-dest" aria-label="<?php esc_attr_e( 'Популярные направления', 'zhiraf-ufa' ); ?>">
	<span class="hero-premium__quick-dest-label"><?php esc_html_e( 'Куда летим:', 'zhiraf-ufa' ); ?></span>
	<?php foreach ( $chips as $chip ) : ?>
		<?php
		$slug = isset( $chip['slug'] ) ? (string) $chip['slug'] : '';
		if ( '' === $slug ) {
			continue;
		}
		$url   = home_url( '/tour/' . rawurlencode( $slug ) . '/' );
		$label = trim( (string) ( $chip['flag'] ?? '' ) . ' ' . (string) ( $chip['label'] ?? $slug ) );
		?>
		<a
			class="hero-premium__quick-dest-chip"
			href="<?php echo esc_url( $url ); ?>"
			data-track="hero_dest_chip_<?php echo esc_attr( $slug ); ?>"
		><?php echo esc_html( $label ); ?></a>
	<?php endforeach; ?>
</nav>
