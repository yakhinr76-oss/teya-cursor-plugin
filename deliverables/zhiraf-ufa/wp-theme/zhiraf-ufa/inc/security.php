<?php
/**
 * Security hardening.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove WP version from head.
 */
function zhiraf_ufa_remove_version() {
	return '';
}
add_filter( 'the_generator', 'zhiraf_ufa_remove_version' );

/**
 * Rate limit helper (simple transient per IP).
 *
 * @param string $action Action key.
 * @param int    $limit  Max attempts.
 * @param int    $window Seconds.
 * @return bool True if allowed.
 */
function zhiraf_ufa_rate_limit_allow( $action, $limit = 5, $window = 600 ) {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'zhiraf_rl_' . md5( $action . $ip );
	$count = (int) get_transient( $key );
	if ( $count >= $limit ) {
		return false;
	}
	set_transient( $key, $count + 1, $window );
	return true;
}
