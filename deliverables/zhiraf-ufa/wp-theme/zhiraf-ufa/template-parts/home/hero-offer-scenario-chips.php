<?php
/**
 * Hero: quick links to offer hubs.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'zhiraf_ufa_home_offer_scenario_chips' ) ) {
	return;
}

$chips = zhiraf_ufa_home_offer_scenario_chips();
if ( empty( $chips ) ) {
	return;
}
?>
<nav class="hero-premium__offer-scenarios" aria-label="<?php esc_attr_e( 'Быстрые сценарии', 'zhiraf-ufa' ); ?>">
	<?php foreach ( $chips as $chip ) : ?>
		<a class="hero-premium__offer-chip" href="<?php echo esc_url( $chip['url'] ); ?>" data-track="hero_offer_<?php echo esc_attr( sanitize_title( $chip['label'] ) ); ?>">
			<?php
			if ( ! empty( $chip['icon'] ) && function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
				echo zhiraf_ufa_lucide_icon( (string) $chip['icon'], 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<span><?php echo esc_html( $chip['label'] ); ?></span>
		</a>
	<?php endforeach; ?>
</nav>