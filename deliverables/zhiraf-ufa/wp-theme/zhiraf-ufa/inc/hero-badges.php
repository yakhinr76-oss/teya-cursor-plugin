<?php
/**
 * Hero badge icon markup (destination landings).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render icon for a hero badge row.
 *
 * @param array<string,mixed> $badge Badge config (icon, icon_svg).
 * @return string Safe HTML.
 */
function zhiraf_ufa_render_hero_badge_icon( array $badge ) {
	$svg_key = ! empty( $badge['icon_svg'] ) ? sanitize_key( (string) $badge['icon_svg'] ) : '';

	if ( 'passport' === $svg_key ) {
		return '<svg class="dest-landing-hero__badge-svg" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">'
			. '<path fill="currentColor" d="M6 4h12a2 2 0 0 1 2 2v14l-4-2.5L12 20l-4-1.5L4 20V6a2 2 0 0 1 2-2zm0 2v11.2l2-.75 4 1.5 4-1.5 2 .75V6H6zm4 1.5h4v1.5h-4V7.5zm0 3h4V12h-4v-1.5z"/>'
			. '</svg>';
	}

	if ( 'plane' === $svg_key && function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
		return zhiraf_ufa_lucide_icon( 'plane', 18, 'dest-landing-hero__badge-svg' );
	}

	if ( ! empty( $badge['icon'] ) ) {
		return '<span class="dest-landing-hero__badge-icon" aria-hidden="true">' . esc_html( (string) $badge['icon'] ) . '</span>';
	}

	return '';
}