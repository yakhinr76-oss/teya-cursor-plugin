<?php
/**
 * U-ON CRM API extension stub.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Minimal U-ON CRM client placeholder.
 */
class Zhiraf_UON_CRM {

	/**
	 * API base URL (configure in wp-config or options).
	 *
	 * @var string
	 */
	private $api_base = '';

	/**
	 * API key.
	 *
	 * @var string
	 */
	private $api_key = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->api_base = defined( 'ZHIRAF_UON_API_URL' ) ? ZHIRAF_UON_API_URL : '';
		$this->api_key  = defined( 'ZHIRAF_UON_API_KEY' ) ? ZHIRAF_UON_API_KEY : '';
	}

	/**
	 * Whether integration is configured.
	 *
	 * @return bool
	 */
	public function is_configured() {
		return ! empty( $this->api_base ) && ! empty( $this->api_key );
	}

	/**
	 * Send lead payload to U-ON (stub — logs when not configured).
	 *
	 * @param array<string,mixed> $payload Lead fields.
	 * @return array{ok:bool,message:string}
	 */
	public function send_lead( array $payload ) {
		if ( ! $this->is_configured() ) {
			return array(
				'ok'      => false,
				'message' => 'U-ON API not configured (define ZHIRAF_UON_API_URL and ZHIRAF_UON_API_KEY).',
			);
		}

		/**
		 * Future: wp_remote_post to U-ON endpoint with signed payload.
		 */
		return array(
			'ok'      => true,
			'message' => 'Stub: lead would be sent to U-ON.',
		);
	}
}
