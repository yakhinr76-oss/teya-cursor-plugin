<?php
/**
 * Hero: quick destination chips (3×2 grid).
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
<div class="hero-premium__quick-dest-wrap">
	<p class="hero-premium__quick-dest-label"><?php esc_html_e( 'Куда летим из Уфы:', 'zhiraf-ufa' ); ?></p>
	<nav class="hero-premium__quick-dest" aria-label="<?php esc_attr_e( 'Популярные направления', 'zhiraf-ufa' ); ?>">
		<?php foreach ( $chips as $chip ) : ?>
			<?php
			$slug = isset( $chip['slug'] ) ? (string) $chip['slug'] : '';
			if ( '' === $slug ) {
				continue;
			}
			$url   = home_url( '/tour/' . rawurlencode( $slug ) . '/' );
			$label = (string) ( $chip['label'] ?? $slug );
			?>
			<a
				class="hero-premium__quick-dest-chip"
				href="<?php echo esc_url( $url ); ?>"
				data-track="hero_dest_chip_<?php echo esc_attr( $slug ); ?>"
			>
				<?php if ( function_exists( 'zhiraf_ufa_lucide_icon' ) ) : ?>
					<span class="hero-premium__quick-dest-flag" aria-hidden="true"><?php echo zhiraf_ufa_lucide_icon( 'map', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
				<span class="hero-premium__quick-dest-name"><?php echo esc_html( $label ); ?></span>
			</a>
		<?php endforeach; ?>
	</nav>
</div>
