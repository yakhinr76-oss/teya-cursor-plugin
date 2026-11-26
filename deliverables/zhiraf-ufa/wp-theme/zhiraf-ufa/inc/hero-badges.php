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
	if ( function_exists( 'zhiraf_ufa_render_lucide_icon' ) ) {
		$markup = zhiraf_ufa_render_lucide_icon( $badge, 18, 'dest-landing-hero__badge-svg' );
		if ( '' !== $markup ) {
			return $markup;
		}
	}

	return '';
}

/**
 * Render trust-row / list icon (same rules as badges).
 *
 * @param array<string,mixed> $row  Row with icon / icon_svg.
 * @param int                 $size Icon size.
 * @param string              $class CSS class.
 * @return string
 */
function zhiraf_ufa_render_trust_row_icon( array $row, $size = 18, $class = '' ) {
	$class = '' !== $class ? $class : 'dest-landing-hero__trust-icon';
	if ( function_exists( 'zhiraf_ufa_render_lucide_icon' ) ) {
		return zhiraf_ufa_render_lucide_icon( $row, $size, $class );
	}
	return '';
}