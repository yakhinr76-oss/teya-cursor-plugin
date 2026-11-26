<?php
/**
 * Lucide-style inline SVG icons (brand orange stroke).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lucide icon markup.
 *
 * @param string $name  plane|compass|map|tag|hotel|shield|credit-card|users.
 * @param int    $size  Width/height in px.
 * @param string $class Extra CSS classes.
 * @return string
 */
function zhiraf_ufa_lucide_icon( $name, $size = 18, $class = '' ) {
	$name  = sanitize_key( (string) $name );
	$size  = max( 12, min( 48, (int) $size ) );
	$class = trim( 'lucide-icon lucide-icon--' . $name . ' ' . $class );

	$paths = array(
		'plane'        => '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 20 3s-3 .5-4.5 2L12 8.5 4.2 6.8c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
		'compass'      => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
		'map'          => '<polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21 3 6"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/>',
		'tag'          => '<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>',
		'hotel'        => '<path d="M10 22v-6.57"/><path d="M12 11h.01"/><path d="M12 7h.01"/><path d="M14 15.43V22"/><path d="M15 16a5 5 0 0 0-6 0"/><path d="M16 11h.01"/><path d="M16 7h.01"/><path d="M8 11h.01"/><path d="M8 7h.01"/><rect x="4" y="2" width="16" height="20" rx="2"/>',
		'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
		'credit-card'  => '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
		'receipt'      => '<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 17.5v-11"/>',
		'shopping-bag' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
		'sun'          => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>',
		'cloud-rain'   => '<path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M16 14v6"/><path d="M8 14v6"/><path d="M12 16v6"/>',
		'info'         => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
		'headphones'   => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/>',
		'heart'        => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
		'palmtree'        => '<path d="M13 8c0-2.76-2.46-5-5.5-5S2 5.24 2 8h2l1-1 1 1h4"/><path d="M13 7.14A5.82 5.82 0 0 1 16.5 6c3.04 0 5.5 2.24 5.5 5h-3l-1-1-1 1h-4"/><path d="M5.89 9.71c-2.15 2.15-2.3 5.47-.35 7.43l1 1h8l1-1c1.95-1.96 1.8-5.28-.35-7.43"/><path d="M12 22v-4"/>',
		'mountain'        => '<path d="m8 3 4 8 5-5 5 15H2L8 3z"/>',
		'check'           => '<path d="M20 6 9 17l-5-5"/>',
		'x'               => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'flame'           => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1.5-2.5-2.33-1.17-3-3.5-2.5-5 .5-2 2.5-3 4-2.5 2 .6 2.5 3 2 5-.5 2-2 4-3.5 5.5"/><path d="M12 22c-4.97 0-9-2.582-9-7 0-2.5 2-4 4-6 1 2 3 3.5 5 4.5 1.5-1 2.5-2.5 3.5-4 1 2 2.5 3.5 4 5 1.5 1.5 2.5 3.5 2.5 5.5 0 4.418-4.03 7-9 7z"/>',
		'users'           => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'user-round'      => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
		'baby'            => '<path d="M9 12h.01"/><path d="M15 12h.01"/><path d="M10 16c.5.5 1.5 1 2.5 1s2-.5 2.5-1"/><path d="M19 6.45c-1.5-.5-3-1-4.5-1-3 0-5.5 1.5-5.5 4 0 2 1.5 3.5 3.5 4"/><path d="M5 6.45c1.5-.5 3-1 4.5-1 3 0 5.5 1.5 5.5 4 0 2-1.5 3.5-3.5 4"/>',
		'calendar'        => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
		'message-circle'  => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
		'shield'          => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>',
		'smartphone'      => '<rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/>',
		'landmark'        => '<line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/>',
		'file-text'       => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
		'passport'        => '<path d="M13.5 8.5 16 6l1.5 1.5"/><path d="M16 6v6.5"/><path d="M4 10a4 4 0 0 1 4-4h8v12H8a4 4 0 0 1-4-4Z"/><path d="M8 14h.01"/>',
		'bus'             => '<path d="M8 6v6"/><path d="M15 6v6"/><path d="M2 12h19.6"/><path d="M18 18h3s.5-1.5.8-2.8A6 6 0 0 0 22 12"/><path d="M6 18H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-2"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/>',
		'anchor'          => '<path d="M12 22V8"/><path d="M5 12H2a10 10 0 0 0 20 0h-3"/><circle cx="12" cy="5" r="3"/>',
		'ship'            => '<path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.4.9 4.6 2.38 6.2"/><path d="M12 2v4"/>',
		'waves'           => '<path d="M2 6c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>',
		'trees'           => '<path d="M10 10v.2A3 3 0 0 1 8.9 16H5a3 3 0 0 1-1-5.8V10a3 3 0 0 1 6 0Z"/><path d="M7 16v6"/><path d="M13.5 7.5v.2A4 4 0 0 1 11.2 15H6.3a4 4 0 0 1-.8-7.9V7.5a4 4 0 0 1 8 0Z"/><path d="M10 15v7"/><path d="M18 5v.2A3 3 0 0 1 16.9 11H14a3 3 0 0 1-1-5.8V5a3 3 0 0 1 6 0Z"/><path d="M16 11v10"/>',
		'building-2'      => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
		'utensils'        => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>',
		'wine'            => '<path d="M8 22h8"/><path d="M7 10h10"/><path d="M12 15v7"/><path d="M12 15a5 5 0 0 0 5-5c0-2-.5-4-2-8H9c-1.5 4-2 6-2 8a5 5 0 0 0 5 5Z"/>',
		'map-pin'         => '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
		'luggage'         => '<path d="M6 20a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2"/><path d="M8 18V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v12"/><path d="M10 20h4"/><circle cx="16" cy="20" r="2"/><circle cx="8" cy="20" r="2"/>',
		'scale'           => '<path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/>',
		'gem'             => '<path d="M6 3h12l4 6-10 13L2 9Z"/><path d="M11 3 8 9l4 13 4-13-3-6"/><path d="M2 9h20"/>',
		'wind'            => '<path d="M17.7 7.7a2.5 2.5 0 1 1 1.8 4.3H2"/><path d="M9.6 4.6A2 2 0 1 1 11 8H2"/><path d="M12.6 19.4A2 2 0 1 0 14 16H2"/>',
		'fish'            => '<path d="M6.5 12c.94-3.46 4.94-6 8.5-6 3.56 0 6.06 2.54 7 6-.94 3.47-3.44 6-7 6-3.56 0-6.56-2.53-7.5-6Z"/><path d="M18 12v.5"/><path d="M16 17.93a9.77 9.77 0 0 1 0-11.86"/><path d="M7 10.67 7.01 10.67"/><path d="M10 8v8"/><path d="M2 8l1.88 1.88"/><path d="M2 16l1.88-1.88"/>',
		'glasses'         => '<circle cx="6" cy="15" r="4"/><circle cx="18" cy="15" r="4"/><path d="M14 15a2 2 0 0 0-2-2 2 2 0 0 0-2 2"/><path d="M2.5 13 5 7c.7-1.3 1.4-2 3-2"/><path d="M21.5 13 19 7c-.7-1.3-1.5-2-3-2"/>',
		'church'          => '<path d="M10 9h4"/><path d="M12 7v5"/><path d="M14 22v-4a2 2 0 0 0-4 0v4"/><path d="M18 22V5.618a1 1 0 0 0-.553-.894l-4.553-2.277a2 2 0 0 0-1.788 0L6.553 4.724A1 1 0 0 0 6 5.618V22"/><path d="m18 7 3.447 1.724a1 1 0 0 1 .553.894V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9.618a1 1 0 0 1 .553-.894L6 7"/>',
		'sparkles'        => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/>',
		'award'           => '<path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/><circle cx="12" cy="8" r="6"/>',
		'umbrella'        => '<path d="M22 12a10.06 10.06 1 0 0-20 0Z"/><path d="M12 12v8a2 2 0 0 0 4 0"/><path d="M12 2v1"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="%s" xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>',
		esc_attr( $class ),
		$size,
		$size,
		$paths[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed SVG paths
	);
}

/**
 * Legacy emoji / slug → Lucide name.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_lucide_emoji_map() {
	return array(
		'✈'             => 'plane',
		'✈️'            => 'plane',
		'🛂'            => 'passport',
		'🛡'            => 'shield-check',
		'🛡️'           => 'shield-check',
		'📅'            => 'calendar',
		'💬'            => 'message-circle',
		'🏨'            => 'hotel',
		'📱'            => 'smartphone',
		'🦒'            => 'award',
		'🔥'            => 'flame',
		'🍳'            => 'utensils',
		'🍸'            => 'wine',
		'🍽'            => 'utensils',
		'👶'            => 'baby',
		'🌊'            => 'waves',
		'🚌'            => 'bus',
		'🏝'            => 'palmtree',
		'🛥'            => 'ship',
		'💨'            => 'wind',
		'🩴'            => 'waves',
		'🤿'            => 'glasses',
		'🌧'            => 'cloud-rain',
		'🏖'            => 'palmtree',
		'🏖️'           => 'palmtree',
		'🌴'            => 'palmtree',
		'🧭'            => 'compass',
		'🏔'            => 'mountain',
		'🏔️'           => 'mountain',
		'✓'             => 'check',
		'✔'             => 'check',
		'✖'             => 'x',
		'✕'             => 'x',
	);
}

/**
 * Resolve Lucide icon name from config row or raw token.
 *
 * @param array<string,mixed>|string $icon_config Row with icon_svg/icon or slug/emoji string.
 * @return string Lucide slug or empty.
 */
function zhiraf_ufa_resolve_lucide_name( $icon_config ) {
	if ( is_array( $icon_config ) ) {
		if ( ! empty( $icon_config['icon_svg'] ) ) {
			return sanitize_key( (string) $icon_config['icon_svg'] );
		}
		if ( empty( $icon_config['icon'] ) ) {
			return '';
		}
		$icon_config = (string) $icon_config['icon'];
	}

	$raw = trim( (string) $icon_config );
	if ( '' === $raw ) {
		return '';
	}

	$slug = sanitize_key( $raw );
	if ( $slug === $raw || preg_match( '/^[a-z0-9_-]+$/', $raw ) ) {
		return $slug;
	}

	$map = zhiraf_ufa_lucide_emoji_map();
	return isset( $map[ $raw ] ) ? $map[ $raw ] : '';
}

/**
 * Render icon from config (icon_svg / icon / emoji legacy).
 *
 * @param array<string,mixed>|string $icon_config Config row or slug.
 * @param int                        $size        Px.
 * @param string                     $class       CSS class.
 * @return string HTML.
 */
function zhiraf_ufa_render_lucide_icon( $icon_config, $size = 18, $class = '' ) {
	$name = zhiraf_ufa_resolve_lucide_name( $icon_config );
	if ( '' === $name || ! function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
		return '';
	}
	return zhiraf_ufa_lucide_icon( $name, $size, $class );
}

/**
 * Strip pictographic emoji from copy (keeps ★ hotel stars).
 *
 * @param string $text Input.
 * @return string
 */
function zhiraf_ufa_strip_text_emojis( $text ) {
	$text = (string) $text;
	if ( '' === $text ) {
		return '';
	}
	$emoji_pattern = '/[\x{1F300}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{FE0F}\x{200D}\x{2B50}]+/u';
	$clean         = preg_replace( $emoji_pattern, '', $text );
	if ( ! is_string( $clean ) ) {
		return trim( $text );
	}
	$clean = preg_replace( '/\s{2,}/u', ' ', $clean );
	return trim( $clean );
}