<?php
/**
 * GEO answer blocks (question-style H2 + body for AEO/FAQ parity).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Split a geo_answer string into a question title and answer body.
 *
 * @param string $geo_answer Full geo answer text.
 * @return array{title:string,body:string}
 */
function zhiraf_ufa_geo_answer_parts( $geo_answer ) {
	$geo_answer = trim( (string) $geo_answer );
	if ( '' === $geo_answer ) {
		return array(
			'title' => '',
			'body'  => '',
		);
	}

	$pos = mb_strpos( $geo_answer, '?' );
	if ( false !== $pos ) {
		return array(
			'title' => trim( mb_substr( $geo_answer, 0, $pos + 1 ) ),
			'body'  => trim( mb_substr( $geo_answer, $pos + 1 ) ),
		);
	}

	return array(
		'title' => __( 'Коротко о поездке из Уфы', 'zhiraf-ufa' ),
		'body'  => $geo_answer,
	);
}

/**
 * Serve llms.txt (and llms-full.txt) from the theme directory at site root paths.
 */
function zhiraf_ufa_serve_llms_txt() {
	if ( is_admin() ) {
		return;
	}

	$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$path = strtok( $path, '?' );
	$file = '';
	if ( '/llms.txt' === $path ) {
		$file = 'llms.txt';
	} elseif ( '/llms-full.txt' === $path ) {
		$file = 'llms-full.txt';
	}

	if ( '' === $file ) {
		return;
	}

	$disk = ZHIRAF_UFA_DIR . '/' . $file;
	if ( ! is_readable( $disk ) ) {
		return;
	}

	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plain text file.
	readfile( $disk );
	exit;
}
add_action( 'template_redirect', 'zhiraf_ufa_serve_llms_txt', 0 );