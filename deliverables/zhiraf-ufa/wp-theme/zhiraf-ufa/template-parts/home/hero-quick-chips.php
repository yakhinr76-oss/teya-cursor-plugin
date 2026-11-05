<?php
/**
 * Hero quick-link chips (zero-scroll to country landing).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$chips = function_exists( 'zhiraf_ufa_home_hero_quick_chips' ) ? zhiraf_ufa_home_hero_quick_chips() : array();
if ( ! $chips ) {
	return;
}
?>
<div class="hero-quick-chips reveal reveal-delay-1">
	<span class="hero-quick-chips__label"><?php esc_html_e( 'Быстрый переход:', 'zhiraf-ufa' ); ?></span>
	<div class="hero-quick-chips__track" tabindex="0" role="navigation" aria-label="<?php esc_attr_e( 'Быстрый переход к направлениям', 'zhiraf-ufa' ); ?>">
		<?php foreach ( $chips as $chip ) : ?>
			<a class="hero-quick-chips__chip" href="<?php echo esc_url( $chip['url'] ); ?>">
				<span aria-hidden="true"><?php echo esc_html( $chip['emoji'] ); ?></span>
				<?php echo esc_html( $chip['label'] ); ?>
			</a>
		<?php endforeach; ?>
	</div>
</div>
