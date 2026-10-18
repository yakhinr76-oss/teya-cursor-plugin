<?php
/**
 * Biblio-Globus API (BG API) Live Price Engine & Integration.
 *
 * Implements authentication (A1, Z1, L session cookies with Z1 rotation),
 * detailed price fetching (partners?action=price, strictly from RUR field),
 * field validation, transients caching with 6-hour TTL, WP-Cron synchronization,
 * WP Admin settings page, and automatic fallback to bg-tours-fixtures.json.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cache TTL in seconds (6 hours).
 */
if ( ! defined( 'ZHIRAF_BG_CACHE_TTL' ) ) {
	define( 'ZHIRAF_BG_CACHE_TTL', 6 * HOUR_IN_SECONDS );
}

/**
 * Biblio-Globus API HTTP Client.
 */
class Zhiraf_BG_API_Client {

	/**
	 * Primary auth endpoint.
	 *
	 * @var string
	 */
	const AUTH_ENDPOINT = 'https://login.bgoperator.ru/auth';

	/**
	 * Fallback auth endpoint.
	 *
	 * @var string
	 */
	const AUTH_ENDPOINT_FALLBACK = 'https://www.bgoperator.ru/auth';

	/**
	 * Export API base URL.
	 *
	 * @var string
	 */
	const EXPORT_BASE_URL = 'http://export.bgoperator.ru';

	/**
	 * Option name for stored cookies.
	 *
	 * @var string
	 */
	const COOKIES_OPTION = 'zhiraf_bg_session_cookies';

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Active session cookies (A1, Z1, L).
	 *
	 * @var array<string,string>
	 */
	private $cookies = array();

	/**
	 * Get singleton instance.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$stored = get_option( self::COOKIES_OPTION, array() );
		if ( is_array( $stored ) && ! empty( $stored['cookies'] ) && is_array( $stored['cookies'] ) ) {
			$this->cookies = $stored['cookies'];
		}
	}

	/**
	 * Get configured BG login.
	 *
	 * @return string
	 */
	public function get_login() {
		if ( defined( 'ZHIRAF_BG_LOGIN' ) && ! empty( ZHIRAF_BG_LOGIN ) ) {
			return (string) ZHIRAF_BG_LOGIN;
		}
		return (string) get_option( 'zhiraf_bg_login', '' );
	}

	/**
	 * Get configured BG password.
	 *
	 * @return string
	 */
	public function get_password() {
		if ( defined( 'ZHIRAF_BG_PWD' ) && ! empty( ZHIRAF_BG_PWD ) ) {
			return (string) ZHIRAF_BG_PWD;
		}
		return (string) get_option( 'zhiraf_bg_pwd', '' );
	}

	/**
	 * Whether credentials are configured via wp-config.php constants.
	 *
	 * @return bool
	 */
	public function is_constant_configured() {
		return defined( 'ZHIRAF_BG_LOGIN' ) && defined( 'ZHIRAF_BG_PWD' )
			&& ! empty( ZHIRAF_BG_LOGIN ) && ! empty( ZHIRAF_BG_PWD );
	}

	/**
	 * Whether client credentials are ready.
	 *
	 * @return bool
	 */
	public function is_configured() {
		$login = $this->get_login();
		$pwd   = $this->get_password();
		return ! empty( $login ) && ! empty( $pwd );
	}

	/**
	 * Get current cookies.
	 *
	 * @return array<string,string>
	 */
	public function get_cookies() {
		return $this->cookies;
	}

	/**
	 * Authenticate against BG Auth service and store A1, Z1, L cookies.
	 *
	 * @return array{ok:bool,message:string,cookies:array<string,string>}
	 */
	public function authenticate() {
		if ( ! $this->is_configured() ) {
			return array(
				'ok'      => false,
				'message' => 'Логин или пароль API Библио-Глобус не настроены.',
				'cookies' => array(),
			);
		}

		$login = $this->get_login();
		$pwd   = $this->get_password();

		$body = http_build_query(
			array(
				'login' => $login,
				'pwd'   => $pwd,
			),
			'',
			'&'
		);

		$endpoints = array( self::AUTH_ENDPOINT, self::AUTH_ENDPOINT_FALLBACK );
		$last_error = '';

		foreach ( $endpoints as $endpoint ) {
			$response = wp_remote_post(
				$endpoint,
				array(
					'timeout'     => 20,
					'redirection' => 5,
					'decompress'  => true,
					'headers'     => array(
						'Accept-Encoding' => 'gzip',
						'Content-Type'    => 'application/x-www-form-urlencoded',
						'User-Agent'      => 'Zhiraf-Ufa-BG-Engine/1.0 (+https://zhiraf-ufa.ru)',
					),
					'body'        => $body,
				)
			);

			if ( is_wp_error( $response ) ) {
				$last_error = $response->get_error_message();
				continue;
			}

			$code = wp_remote_retrieve_response_code( $response );
			if ( 200 !== $code && 302 !== $code ) {
				$last_error = sprintf( 'HTTP %d от %s', $code, $endpoint );
				continue;
			}

			$cookies = $this->extract_cookies_from_response( $response );
			if ( ! empty( $cookies['A1'] ) || ! empty( $cookies['Z1'] ) || ! empty( $cookies['L'] ) ) {
				$this->cookies = array_merge( $this->cookies, $cookies );
				update_option(
					self::COOKIES_OPTION,
					array(
						'cookies'    => $this->cookies,
						'updated_at' => time(),
					)
				);

				return array(
					'ok'      => true,
					'message' => 'Авторизация успешно пройдена.',
					'cookies' => $this->cookies,
				);
			}

			$last_error = 'Сервер не вернул сессионные куки A1/Z1/L.';
		}

		return array(
			'ok'      => false,
			'message' => 'Ошибка аутентификации: ' . ( $last_error ? $last_error : 'Неизвестная ошибка' ),
			'cookies' => array(),
		);
	}

	/**
	 * Perform GET request with session cookies, gzip decompress, Z1 rotation and 401 retry.
	 *
	 * @param string $url URL to request.
	 * @param array  $args Optional wp_remote_get arguments.
	 * @param bool   $retry_on_401 Whether to re-auth and retry on 401.
	 * @return array{ok:bool,code:int,body:string,data:mixed,error:string}
	 */
	public function get( $url, array $args = array(), $retry_on_401 = true ) {
		if ( empty( $this->cookies['A1'] ) && empty( $this->cookies['Z1'] ) ) {
			$auth = $this->authenticate();
			if ( ! $auth['ok'] ) {
				return array(
					'ok'    => false,
					'code'  => 401,
					'body'  => '',
					'data'  => null,
					'error' => $auth['message'],
				);
			}
		}

		$cookie_header = $this->build_cookie_header();

		$default_headers = array(
			'Accept-Encoding' => 'gzip',
			'User-Agent'      => 'Zhiraf-Ufa-BG-Engine/1.0 (+https://zhiraf-ufa.ru)',
			'Accept'          => 'application/json, text/javascript, */*',
		);
		if ( ! empty( $cookie_header ) ) {
			$default_headers['Cookie'] = $cookie_header;
		}

		$request_args = wp_parse_args(
			$args,
			array(
				'timeout'    => 25,
				'decompress' => true,
				'headers'    => $default_headers,
			)
		);

		$response = wp_remote_get( $url, $request_args );

		if ( is_wp_error( $response ) ) {
			return array(
				'ok'    => false,
				'code'  => 0,
				'body'  => '',
				'data'  => null,
				'error' => $response->get_error_message(),
			);
		}

		$code = (int) wp_remote_retrieve_response_code( $response );

		// Capture rotated Z1 or other cookies.
		$new_cookies = $this->extract_cookies_from_response( $response );
		if ( ! empty( $new_cookies ) ) {
			$this->cookies = array_merge( $this->cookies, $new_cookies );
			update_option(
				self::COOKIES_OPTION,
				array(
					'cookies'    => $this->cookies,
					'updated_at' => time(),
				)
			);
		}

		// Handle 401 Unauthorized with token refresh.
		if ( 401 === $code && $retry_on_401 ) {
			$this->cookies = array();
			delete_option( self::COOKIES_OPTION );
			$auth = $this->authenticate();
			if ( $auth['ok'] ) {
				return $this->get( $url, $args, false );
			}
			return array(
				'ok'    => false,
				'code'  => 401,
				'body'  => '',
				'data'  => null,
				'error' => '401 Unauthorized: ' . $auth['message'],
			);
		}

		$body = wp_remote_retrieve_body( $response );

		if ( $code >= 400 ) {
			return array(
				'ok'    => false,
				'code'  => $code,
				'body'  => $body,
				'data'  => null,
				'error' => sprintf( 'HTTP %d response from BG API', $code ),
			);
		}

		$data = json_decode( $body, true );

		return array(
			'ok'    => true,
			'code'  => $code,
			'body'  => $body,
			'data'  => $data,
			'error' => '',
		);
	}

	/**
	 * Build Cookie header string.
	 *
	 * @return string
	 */
	private function build_cookie_header() {
		if ( empty( $this->cookies ) ) {
			return '';
		}
		$parts = array();
		foreach ( $this->cookies as $name => $val ) {
			$parts[] = $name . '=' . $val;
		}
		return implode( '; ', $parts );
	}

	/**
	 * Extract Set-Cookie headers from response.
	 *
	 * @param array|object $response WordPress HTTP response.
	 * @return array<string,string>
	 */
	private function extract_cookies_from_response( $response ) {
		$cookies = array();

		// Check WP_HTTP_Requests_Response cookie jar if available.
		if ( is_array( $response ) && isset( $response['cookies'] ) && is_array( $response['cookies'] ) ) {
			foreach ( $response['cookies'] as $cookie_obj ) {
				if ( is_object( $cookie_obj ) && isset( $cookie_obj->name, $cookie_obj->value ) ) {
					$cookies[ $cookie_obj->name ] = $cookie_obj->value;
				} elseif ( is_array( $cookie_obj ) && isset( $cookie_obj['name'], $cookie_obj['value'] ) ) {
					$cookies[ $cookie_obj['name'] ] = $cookie_obj['value'];
				}
			}
		}

		$headers = wp_remote_retrieve_headers( $response );
		if ( empty( $headers ) ) {
			return $cookies;
		}

		$raw_cookies = array();
		if ( isset( $headers['set-cookie'] ) ) {
			$set_cookie = $headers['set-cookie'];
			if ( is_array( $set_cookie ) ) {
				$raw_cookies = $set_cookie;
			} else {
				$raw_cookies = array( (string) $set_cookie );
			}
		}

		foreach ( $raw_cookies as $cookie_str ) {
			$parts = explode( ';', $cookie_str );
			if ( ! empty( $parts[0] ) ) {
				$pair = explode( '=', trim( $parts[0] ), 2 );
				if ( 2 === count( $pair ) ) {
					$c_name  = trim( $pair[0] );
					$c_value = trim( $pair[1] );
					if ( in_array( $c_name, array( 'A1', 'Z1', 'L', 'PHPSESSID' ), true ) ) {
						$cookies[ $c_name ] = $c_value;
					}
				}
			}
		}

		return $cookies;
	}
}

/**
 * Biblio-Globus Price Engine & Sync Coordinator.
 */
class Zhiraf_BG_Price_Engine {

	/**
	 * City of departure (Ufa BG ID).
	 *
	 * @var string
	 */
	const UFA_DEPARTURE_ID = '100510324872';

	/**
	 * Transient key for full parsed tours bundle.
	 *
	 * @var string
	 */
	const TRANSIENT_KEY = 'zhiraf_bg_tours_data';

	/**
	 * Option key for persistent backup of parsed tours.
	 *
	 * @var string
	 */
	const BACKUP_OPTION_KEY = 'zhiraf_bg_cached_data';

	/**
	 * Target destination countries with BG IDs.
	 *
	 * @var array<string,array{id:string,name_ru:string,code:string,currency:string}>
	 */
	public static $countries = array(
		'turciya' => array(
			'id'       => '100411293179',
			'name_ru'  => 'Турция',
			'code'     => 'TR',
			'currency' => 'EUR',
		),
		'oae'     => array(
			'id'       => '100410510097',
			'name_ru'  => 'ОАЭ',
			'code'     => 'AE',
			'currency' => 'USD',
		),
		'egipet'  => array(
			'id'       => '100410000047',
			'name_ru'  => 'Египет',
			'code'     => 'EG',
			'currency' => 'USD',
		),
		'tailand' => array(
			'id'       => '100410000051',
			'name_ru'  => 'Таиланд',
			'code'     => 'TH',
			'currency' => 'USD',
		),
		'vietnam' => array(
			'id'       => '100410419078',
			'name_ru'  => 'Вьетнам',
			'code'     => 'VN',
			'currency' => 'USD',
		),
	);

	/**
	 * Country slug aliases map.
	 *
	 * @var array<string,string>
	 */
	private static $slug_aliases = array(
		'turkey'       => 'turciya',
		'turciya'      => 'turciya',
		'tr'           => 'turciya',
		'100411293179' => 'turciya',

		'uae'          => 'oae',
		'oae'          => 'oae',
		'ae'           => 'oae',
		'100410510097' => 'oae',

		'egypt'        => 'egipet',
		'egipet'       => 'egipet',
		'eg'           => 'egipet',
		'100410000047' => 'egipet',

		'thailand'     => 'tailand',
		'tailand'      => 'tailand',
		'th'           => 'tailand',
		'100410000051' => 'tailand',

		'vietnam'      => 'vietnam',
		'vn'           => 'vietnam',
		'100410419078' => 'vietnam',
	);

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Cached fixtures in memory.
	 *
	 * @var array|null
	 */
	private $fixtures_data = null;

	/**
	 * Get singleton instance.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {}

	/**
	 * Normalize country slug or ID.
	 *
	 * @param string $slug_or_id Country slug or BG ID.
	 * @return string Canonical theme slug ('turciya', 'oae', 'egipet', 'tailand', 'vietnam') or empty string.
	 */
	public static function normalize_country_slug( $slug_or_id ) {
		$raw = strtolower( trim( (string) $slug_or_id ) );
		return isset( self::$slug_aliases[ $raw ] ) ? self::$slug_aliases[ $raw ] : $raw;
	}

	/**
	 * Load static fixtures from data/bg-tours-fixtures.json.
	 *
	 * @return array
	 */
	public function get_fixtures_data() {
		if ( null !== $this->fixtures_data ) {
			return $this->fixtures_data;
		}

		$fixture_file = ZHIRAF_UFA_DIR . '/data/bg-tours-fixtures.json';
		if ( file_exists( $fixture_file ) && is_readable( $fixture_file ) ) {
			$contents = file_get_contents( $fixture_file );
			$decoded  = json_decode( (string) $contents, true );
			if ( is_array( $decoded ) && ! empty( $decoded['destinations'] ) ) {
				$this->fixtures_data = $decoded;
				return $this->fixtures_data;
			}
		}

		// Hardcoded absolute minimal fallback if file is missing.
		$this->fixtures_data = array(
			'meta'          => array(
				'source'         => 'Fallback fixtures',
				'city_departure' => 'Уфа',
				'price_date'     => date( 'd.m.Y' ),
			),
			'home_featured' => array(),
			'destinations'  => array(),
		);

		return $this->fixtures_data;
	}

	/**
	 * Get full tours bundle (from transient, backup option, or fixtures).
	 *
	 * @return array
	 */
	public function get_bundle_data() {
		// 1. Check Transient (TTL 6 hours).
		$cached = get_transient( self::TRANSIENT_KEY );
		if ( is_array( $cached ) && ! empty( $cached['destinations'] ) ) {
			return $cached;
		}

		// 2. Check Option Backup.
		$backup = get_option( self::BACKUP_OPTION_KEY, array() );
		if ( is_array( $backup ) && ! empty( $backup['destinations'] ) ) {
			set_transient( self::TRANSIENT_KEY, $backup, ZHIRAF_BG_CACHE_TTL );
			return $backup;
		}

		// 3. Fallback to Fixtures.
		$fixtures = $this->get_fixtures_data();
		set_transient( self::TRANSIENT_KEY, $fixtures, ZHIRAF_BG_CACHE_TTL );
		return $fixtures;
	}

	/**
	 * Fetch and parse hot tours for the front page.
	 *
	 * @param int $limit Maximum tours to return.
	 * @return array<int,array<string,mixed>>
	 */
	public function get_hot_tours( $limit = 6 ) {
		$bundle = $this->get_bundle_data();

		$featured = array();
		if ( ! empty( $bundle['home_featured'] ) && is_array( $bundle['home_featured'] ) ) {
			$featured = $bundle['home_featured'];
		} else {
			// Extract best tour per destination.
			$destinations = ! empty( $bundle['destinations'] ) ? $bundle['destinations'] : array();
			foreach ( $destinations as $tours ) {
				if ( ! empty( $tours ) && is_array( $tours ) ) {
					$featured[] = $tours[0];
				}
			}
		}

		if ( empty( $featured ) ) {
			$fixtures = $this->get_fixtures_data();
			$featured = ! empty( $fixtures['home_featured'] ) ? $fixtures['home_featured'] : array();
		}

		$limit = max( 1, (int) $limit );
		return array_slice( $featured, 0, $limit );
	}

	/**
	 * Get tours for a specific country destination.
	 *
	 * @param string $country_slug_or_id Destination slug or BG country ID.
	 * @param int    $limit Maximum tours to return.
	 * @return array<int,array<string,mixed>>
	 */
	public function get_country_tours( $country_slug_or_id, $limit = 5 ) {
		$slug   = self::normalize_country_slug( $country_slug_or_id );
		$bundle = $this->get_bundle_data();

		$tours = array();
		if ( ! empty( $bundle['destinations'][ $slug ] ) && is_array( $bundle['destinations'][ $slug ] ) ) {
			$tours = $bundle['destinations'][ $slug ];
		}

		// Fallback to fixtures if destination missing.
		if ( empty( $tours ) ) {
			$fixtures = $this->get_fixtures_data();
			if ( ! empty( $fixtures['destinations'][ $slug ] ) && is_array( $fixtures['destinations'][ $slug ] ) ) {
				$tours = $fixtures['destinations'][ $slug ];
			}
		}

		$limit = max( 1, (int) $limit );
		return array_slice( $tours, 0, $limit );
	}

	/**
	 * Perform live synchronization from BG API for all target destinations.
	 *
	 * @return array{ok:bool,message:string,counts:array<string,int>,source:string}
	 */
	public function sync_all_prices() {
		$client = Zhiraf_BG_API_Client::get_instance();

		if ( ! $client->is_configured() ) {
			$fixtures = $this->get_fixtures_data();
			set_transient( self::TRANSIENT_KEY, $fixtures, ZHIRAF_BG_CACHE_TTL );
			update_option( self::BACKUP_OPTION_KEY, $fixtures );
			update_option( 'zhiraf_bg_last_sync_time', time() );
			update_option( 'zhiraf_bg_last_sync_status', 'fixtures_fallback' );
			update_option( 'zhiraf_bg_last_sync_message', 'Логин/пароль BG API не заданы. Применены фикстуры.' );

			return array(
				'ok'      => true,
				'message' => 'Логин/пароль BG API не заданы. Применены фикстуры.',
				'counts'  => $this->count_bundle_tours( $fixtures ),
				'source'  => 'fixtures',
			);
		}

		$price_date = date( 'd.m.Y' );
		$disclaimer = sprintf( 'рассчитана по курсу туроператора на %s, итоговая стоимость фиксируется при бронировании', $price_date );

		$bundle = array(
			'meta'              => array(
				'source'             => 'Biblio-Globus Live API partners?action=price',
				'city_departure'     => 'Уфа',
				'city_departure_id'  => self::UFA_DEPARTURE_ID,
				'updated_at'         => date( 'c' ),
				'price_date'         => $price_date,
				'currency'           => 'RUR',
				'disclaimer_default' => $disclaimer,
			),
			'countries_mapping' => self::$countries,
			'home_featured'     => array(),
			'destinations'      => array(),
		);

		$total_fetched = 0;
		$counts        = array();
		$errors        = array();

		foreach ( self::$countries as $slug => $c_info ) {
			$country_id = $c_info['id'];
			$url        = sprintf(
				'http://export.bgoperator.ru/partners?action=price&tid=211&flt=%s&flt2=%s&xml=11',
				rawurlencode( $country_id ),
				rawurlencode( self::UFA_DEPARTURE_ID )
			);

			$res = $client->get( $url );

			if ( ! $res['ok'] || empty( $res['data'] ) ) {
				$errors[] = sprintf( '%s: %s', $c_info['name_ru'], ( $res['error'] ? $res['error'] : 'Пустой ответ' ) );
				// Fallback this country from fixtures.
				$fixtures = $this->get_fixtures_data();
				$bundle['destinations'][ $slug ] = ! empty( $fixtures['destinations'][ $slug ] ) ? $fixtures['destinations'][ $slug ] : array();
				$counts[ $slug ] = count( $bundle['destinations'][ $slug ] );
				continue;
			}

			$parsed_tours = $this->parse_price_entries( $res['data'], $slug, $price_date, $disclaimer );

			if ( ! empty( $parsed_tours ) ) {
				$bundle['destinations'][ $slug ] = $parsed_tours;
				$counts[ $slug ] = count( $parsed_tours );
				$total_fetched += count( $parsed_tours );
			} else {
				// Fallback to fixtures if entries were invalid.
				$fixtures = $this->get_fixtures_data();
				$bundle['destinations'][ $slug ] = ! empty( $fixtures['destinations'][ $slug ] ) ? $fixtures['destinations'][ $slug ] : array();
				$counts[ $slug ] = count( $bundle['destinations'][ $slug ] );
			}
		}

		// Build home featured selection.
		$featured = array();
		foreach ( $bundle['destinations'] as $slug => $tours ) {
			if ( ! empty( $tours[0] ) ) {
				$featured[] = $tours[0];
			}
			if ( ! empty( $tours[1] ) && count( $featured ) < 7 ) {
				$featured[] = $tours[1];
			}
		}
		if ( empty( $featured ) ) {
			$fixtures = $this->get_fixtures_data();
			$featured = ! empty( $fixtures['home_featured'] ) ? $fixtures['home_featured'] : array();
		}
		$bundle['home_featured'] = array_slice( $featured, 0, 7 );

		// Save into Transient and Persistent Option.
		set_transient( self::TRANSIENT_KEY, $bundle, ZHIRAF_BG_CACHE_TTL );
		update_option( self::BACKUP_OPTION_KEY, $bundle );

		$sync_status  = empty( $errors ) ? 'live_success' : 'partial_success';
		$sync_message = sprintf(
			'Синхронизировано %d туров из BG API на %s. %s',
			$total_fetched,
			$price_date,
			( ! empty( $errors ) ? 'Ошибки: ' . implode( '; ', $errors ) : 'Все направления обновлены успешно.' )
		);

		update_option( 'zhiraf_bg_last_sync_time', time() );
		update_option( 'zhiraf_bg_last_sync_status', $sync_status );
		update_option( 'zhiraf_bg_last_sync_message', $sync_message );
		update_option( 'zhiraf_bg_last_sync_count', $total_fetched );

		return array(
			'ok'      => true,
			'message' => $sync_message,
			'counts'  => $counts,
			'source'  => $total_fetched > 0 ? 'live_api' : 'fixtures_fallback',
		);
	}

	/**
	 * Parse detailed price entries from BG action=price response.
	 *
	 * Rubles are taken STRICTLY from entries[].prices[].RUR.
	 * Amounts from minprice are strictly ignored.
	 *
	 * @param array  $data Raw JSON response array.
	 * @param string $country_slug Canonical destination slug.
	 * @param string $price_date Date of price fetch.
	 * @param string $disclaimer Legal disclaimer.
	 * @return array<int,array<string,mixed>>
	 */
	private function parse_price_entries( array $data, $country_slug, $price_date, $disclaimer ) {
		$entries = array();
		if ( isset( $data['entries'] ) && is_array( $data['entries'] ) ) {
			$entries = $data['entries'];
		} elseif ( is_array( $data ) && isset( $data[0] ) ) {
			$entries = $data;
		}

		if ( empty( $entries ) ) {
			return array();
		}

		$valid_cards = array();

		foreach ( $entries as $entry ) {
			if ( ! is_array( $entry ) ) {
				continue;
			}

			// Validate stop-sales.
			if ( ! empty( $entry['isstop'] ) ) {
				$stop = (string) $entry['isstop'];
				if ( false !== stripos( $stop, 'avia' ) || false !== stripos( $stop, 'hotel' ) ) {
					continue;
				}
			}

			// Extract nights.
			$nights = 0;
			if ( isset( $entry['o_duration'] ) && (int) $entry['o_duration'] > 0 ) {
				$nights = (int) $entry['o_duration'];
			} elseif ( isset( $entry['duration'] ) && (int) $entry['duration'] > 0 ) {
				$nights = (int) $entry['duration'];
			}

			// P0 Beach filter: discard anomaly trips (< 3 nights).
			if ( $nights < 3 ) {
				continue;
			}

			// Extract and validate Ruble price strictly from prices[].RUR.
			$price_rur = 0;
			if ( ! empty( $entry['prices'] ) && is_array( $entry['prices'] ) ) {
				foreach ( $entry['prices'] as $p_item ) {
					if ( ! is_array( $p_item ) ) {
						continue;
					}
					// Strictly check RUR field.
					if ( isset( $p_item['RUR'] ) ) {
						$candidate = (int) preg_replace( '/[^\d]/', '', (string) $p_item['RUR'] );
						if ( $candidate > 0 ) {
							$price_rur = $candidate;
							break;
						}
					}
				}
			}

			// Validation: price must be > 0.
			if ( $price_rur <= 0 ) {
				continue;
			}

			// Extract date.
			$departure_date = ! empty( $entry['tour_date'] ) ? trim( (string) $entry['tour_date'] ) : '';
			if ( empty( $departure_date ) && ! empty( $entry['date'] ) ) {
				$departure_date = trim( (string) $entry['date'] );
			}
			if ( empty( $departure_date ) ) {
				continue;
			}

			// Extract hotel name.
			$hotel_name = '';
			if ( ! empty( $entry['hotel'] ) ) {
				$hotel_name = trim( (string) $entry['hotel'] );
			} elseif ( ! empty( $entry['hotel_name'] ) ) {
				$hotel_name = trim( (string) $entry['hotel_name'] );
			} elseif ( ! empty( $entry['id_hotel'] ) ) {
				$hotel_name = 'Отель #' . trim( (string) $entry['id_hotel'] );
			}
			if ( empty( $hotel_name ) ) {
				continue;
			}

			// Extract stars.
			$stars = $this->parse_hotel_stars( $entry, $hotel_name );

			// Extract resort/town.
			$resort = '';
			if ( ! empty( $entry['town'] ) ) {
				$resort_raw = trim( (string) $entry['town'] );
				$resort_parts = explode( '/', $resort_raw );
				$resort = trim( $resort_parts[0] );
			} elseif ( ! empty( $entry['resort'] ) ) {
				$resort = trim( (string) $entry['resort'] );
			}
			if ( empty( $resort ) ) {
				$resort = self::$countries[ $country_slug ]['name_ru'] ?? 'Курорт';
			}

			// Extract meal.
			$meal_info = $this->parse_meal_code_and_name( $entry );

			$card = array(
				'hotel_name'      => $hotel_name,
				'stars'           => $stars,
				'resort'          => $resort,
				'country_slug'    => $country_slug,
				'departure_date'  => $departure_date,
				'nights'          => $nights,
				'meal_code'       => $meal_info['code'],
				'meal_name'       => $meal_info['name'],
				'tourists'        => '2 взрослых',
				'price_rur'       => $price_rur,
				'price_formatted' => zhiraf_ufa_format_tour_price( $price_rur ),
				'price_date'      => $price_date,
				'disclaimer'      => $disclaimer,
			);

			$valid_cards[] = $card;
		}

		// Sort by price ascending.
		usort(
			$valid_cards,
			function ( $a, $b ) {
				return $a['price_rur'] <=> $b['price_rur'];
			}
		);

		return array_slice( $valid_cards, 0, 10 );
	}

	/**
	 * Parse hotel star category.
	 *
	 * @param array  $entry BG price entry.
	 * @param string $hotel_name Hotel name.
	 * @return string
	 */
	private function parse_hotel_stars( array $entry, $hotel_name ) {
		if ( ! empty( $entry['stars'] ) ) {
			$raw = trim( (string) $entry['stars'] );
			if ( preg_match( '/\b([1-5])\s*\*/', $raw, $m ) ) {
				return $m[1] . '*';
			}
			if ( is_numeric( $raw ) && (int) $raw >= 1 && (int) $raw <= 5 ) {
				return $raw . '*';
			}
			return $raw;
		}

		if ( preg_match( '/\b([1-5])\s*\*/', $hotel_name, $m ) ) {
			return $m[1] . '*';
		}

		return '4*';
	}

	/**
	 * Parse meal code and human-readable Russian label.
	 *
	 * @param array $entry BG price entry.
	 * @return array{code:string,name:string}
	 */
	private function parse_meal_code_and_name( array $entry ) {
		$source = '';
		if ( ! empty( $entry['meal'] ) ) {
			$source .= ' ' . $entry['meal'];
		}
		if ( ! empty( $entry['room'] ) ) {
			$source .= ' ' . $entry['room'];
		}
		if ( ! empty( $entry['pansion'] ) ) {
			$source .= ' ' . $entry['pansion'];
		}

		$upper = strtoupper( $source );

		if ( preg_match( '/\b(UAI|ULTRA ALL INCLUSIVE)\b/', $upper ) ) {
			return array(
				'code' => 'UAI',
				'name' => 'UAI (Ультра все включено)',
			);
		}
		if ( preg_match( '/\b(AI|ALL INCLUSIVE|ВСЕ ВКЛЮЧЕНО)\b/', $upper ) ) {
			return array(
				'code' => 'AI',
				'name' => 'AI (Все включено)',
			);
		}
		if ( preg_match( '/\b(FB|FULL BOARD|ПОЛНЫЙ ПАНСИОН)\b/', $upper ) ) {
			return array(
				'code' => 'FB',
				'name' => 'FB (Полный пансион)',
			);
		}
		if ( preg_match( '/\b(HB|HALF BOARD|ПОЛУПАНСИОН)\b/', $upper ) ) {
			return array(
				'code' => 'HB',
				'name' => 'HB (Завтрак и ужин (полупансион))',
			);
		}
		if ( preg_match( '/\b(BB|BED & BREAKFAST|ЗАВТРАК)\b/', $upper ) ) {
			return array(
				'code' => 'BB',
				'name' => 'BB (Завтрак включен)',
			);
		}
		if ( preg_match( '/\b(RO|AO|ROOM ONLY|БЕЗ ПИТАНИЯ)\b/', $upper ) ) {
			return array(
				'code' => 'AO',
				'name' => 'AO (Без питания)',
			);
		}

		return array(
			'code' => 'BB',
			'name' => 'BB (Завтрак включен)',
		);
	}

	/**
	 * Count tours in bundle.
	 *
	 * @param array $bundle Bundle array.
	 * @return array<string,int>
	 */
	private function count_bundle_tours( array $bundle ) {
		$counts = array();
		if ( ! empty( $bundle['destinations'] ) && is_array( $bundle['destinations'] ) ) {
			foreach ( $bundle['destinations'] as $slug => $items ) {
				$counts[ $slug ] = is_array( $items ) ? count( $items ) : 0;
			}
		}
		return $counts;
	}
}

/**
 * =========================================================================
 * WP-Cron Integration
 * =========================================================================
 */

/**
 * Register custom 6-hour cron schedule.
 *
 * @param array<string,array{interval:int,display:string}> $schedules Existing cron schedules.
 * @return array<string,array{interval:int,display:string}>
 */
function zhiraf_bg_add_cron_interval( $schedules ) {
	$schedules['zhiraf_bg_six_hours'] = array(
		'interval' => 6 * HOUR_IN_SECONDS,
		'display'  => __( 'Каждые 6 часов (Библио-Глобус API)', 'zhiraf-ufa' ),
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'zhiraf_bg_add_cron_interval' );

/**
 * Schedule BG sync cron event on theme initialization.
 */
function zhiraf_bg_schedule_sync() {
	if ( ! wp_next_scheduled( 'zhiraf_bg_sync_prices' ) ) {
		wp_schedule_event( time() + 300, 'zhiraf_bg_six_hours', 'zhiraf_bg_sync_prices' );
	}
}
add_action( 'init', 'zhiraf_bg_schedule_sync' );

/**
 * WP-Cron action handler for price sync.
 */
function zhiraf_bg_cron_sync_handler() {
	Zhiraf_BG_Price_Engine::get_instance()->sync_all_prices();
}
add_action( 'zhiraf_bg_sync_prices', 'zhiraf_bg_cron_sync_handler' );

/**
 * Clear scheduled cron event when theme is switched.
 */
function zhiraf_bg_clear_cron_schedule() {
	$timestamp = wp_next_scheduled( 'zhiraf_bg_sync_prices' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'zhiraf_bg_sync_prices' );
	}
}
add_action( 'switch_theme', 'zhiraf_bg_clear_cron_schedule' );

/**
 * =========================================================================
 * WP Admin Settings Page (Настройки -> Библио-Глобус API)
 * =========================================================================
 */

/**
 * Register admin menu entry.
 */
function zhiraf_bg_admin_menu() {
	add_options_page(
		__( 'Библио-Глобус API', 'zhiraf-ufa' ),
		__( 'Библио-Глобус API', 'zhiraf-ufa' ),
		'manage_options',
		'zhiraf-bg-api',
		'zhiraf_bg_admin_page_render'
	);
}
add_action( 'admin_menu', 'zhiraf_bg_admin_menu' );

/**
 * Handle admin POST actions (save settings, manual sync, cache flush).
 */
function zhiraf_bg_admin_actions_handler() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// 1. Save Settings.
	if ( isset( $_POST['zhiraf_bg_action'] ) && 'save_settings' === $_POST['zhiraf_bg_action'] ) {
		check_admin_referer( 'zhiraf_bg_save_settings_nonce', 'zhiraf_bg_nonce' );

		$login = isset( $_POST['zhiraf_bg_login'] ) ? sanitize_text_field( wp_unslash( $_POST['zhiraf_bg_login'] ) ) : '';
		update_option( 'zhiraf_bg_login', $login );

		if ( isset( $_POST['zhiraf_bg_pwd'] ) && '' !== trim( (string) $_POST['zhiraf_bg_pwd'] ) ) {
			$pwd = sanitize_text_field( wp_unslash( $_POST['zhiraf_bg_pwd'] ) );
			update_option( 'zhiraf_bg_pwd', $pwd );
		}

		wp_safe_redirect( add_query_arg( array( 'page' => 'zhiraf-bg-api', 'zhiraf_msg' => 'saved' ), admin_url( 'options-general.php' ) ) );
		exit;
	}

	// 2. Manual Sync.
	if ( isset( $_POST['zhiraf_bg_action'] ) && 'manual_sync' === $_POST['zhiraf_bg_action'] ) {
		check_admin_referer( 'zhiraf_bg_sync_nonce', 'zhiraf_bg_nonce' );

		$result = Zhiraf_BG_Price_Engine::get_instance()->sync_all_prices();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'       => 'zhiraf-bg-api',
					'zhiraf_msg' => $result['ok'] ? 'synced' : 'sync_err',
				),
				admin_url( 'options-general.php' )
			)
		);
		exit;
	}

	// 3. Flush Cache.
	if ( isset( $_POST['zhiraf_bg_action'] ) && 'flush_cache' === $_POST['zhiraf_bg_action'] ) {
		check_admin_referer( 'zhiraf_bg_flush_nonce', 'zhiraf_bg_nonce' );

		delete_transient( Zhiraf_BG_Price_Engine::TRANSIENT_KEY );
		delete_option( Zhiraf_BG_Price_Engine::BACKUP_OPTION_KEY );
		delete_option( Zhiraf_BG_API_Client::COOKIES_OPTION );

		wp_safe_redirect( add_query_arg( array( 'page' => 'zhiraf-bg-api', 'zhiraf_msg' => 'flushed' ), admin_url( 'options-general.php' ) ) );
		exit;
	}
}
add_action( 'admin_init', 'zhiraf_bg_admin_actions_handler' );

/**
 * Render admin settings page.
 */
function zhiraf_bg_admin_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$client = Zhiraf_BG_API_Client::get_instance();
	$engine = Zhiraf_BG_Price_Engine::get_instance();

	$is_constant_cfg = $client->is_constant_configured();
	$login_val       = $client->get_login();
	$has_pwd         = ! empty( $client->get_password() );
	$cookies         = $client->get_cookies();

	$last_sync_time   = (int) get_option( 'zhiraf_bg_last_sync_time', 0 );
	$last_sync_status = get_option( 'zhiraf_bg_last_sync_status', 'none' );
	$last_sync_msg    = get_option( 'zhiraf_bg_last_sync_message', '' );
	$next_cron        = wp_next_scheduled( 'zhiraf_bg_sync_prices' );

	$bundle = $engine->get_bundle_data();
	$source = ! empty( $bundle['meta']['source'] ) ? $bundle['meta']['source'] : 'Fixtures Fallback';

	$msg = isset( $_GET['zhiraf_msg'] ) ? sanitize_text_field( wp_unslash( $_GET['zhiraf_msg'] ) ) : '';
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Настройки Библио-Глобус API (BG API)', 'zhiraf-ufa' ); ?></h1>

		<?php if ( 'saved' === $msg ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Настройки успешно сохранены.', 'zhiraf-ufa' ); ?></p></div>
		<?php elseif ( 'synced' === $msg ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Синхронизация цен завершена: ' . esc_html( $last_sync_msg ), 'zhiraf-ufa' ); ?></p></div>
		<?php elseif ( 'sync_err' === $msg ) : ?>
			<div class="notice notice-error is-dismissible"><p><?php esc_html_e( 'Ошибка синхронизации: ' . esc_html( $last_sync_msg ), 'zhiraf-ufa' ); ?></p></div>
		<?php elseif ( 'flushed' === $msg ) : ?>
			<div class="notice notice-info is-dismissible"><p><?php esc_html_e( 'Кэш цен и сессионные куки очищены.', 'zhiraf-ufa' ); ?></p></div>
		<?php endif; ?>

		<div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:24px;margin-top:20px;">

			<!-- Left Column: Credentials & Actions -->
			<div>
				<div class="card" style="padding:20px;max-width:100%;">
					<h2><?php esc_html_e( 'Параметры авторизации API', 'zhiraf-ufa' ); ?></h2>
					<p class="description">
						<?php esc_html_e( 'Учётные данные турагентства в системе «Библио-Глобус» для получения актуальных цен из Уфы.', 'zhiraf-ufa' ); ?>
					</p>

					<?php if ( $is_constant_cfg ) : ?>
						<div class="notice notice-info inline" style="margin:12px 0;">
							<p><strong><?php esc_html_e( 'Защищённый режим:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'Логин и пароль заданы через константы ZHIRAF_BG_LOGIN и ZHIRAF_BG_PWD в wp-config.php.', 'zhiraf-ufa' ); ?></p>
						</div>
					<?php endif; ?>

					<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page=zhiraf-bg-api' ) ); ?>">
						<?php wp_nonce_field( 'zhiraf_bg_save_settings_nonce', 'zhiraf_bg_nonce' ); ?>
						<input type="hidden" name="zhiraf_bg_action" value="save_settings" />

						<table class="form-table" role="presentation">
							<tr>
								<th scope="row"><label for="zhiraf_bg_login"><?php esc_html_e( 'Логин агентства', 'zhiraf-ufa' ); ?></label></th>
								<td>
									<input
										name="zhiraf_bg_login"
										type="text"
										id="zhiraf_bg_login"
										value="<?php echo esc_attr( $login_val ); ?>"
										class="regular-text"
										<?php echo $is_constant_cfg ? 'readonly' : ''; ?>
										autocomplete="off"
									/>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="zhiraf_bg_pwd"><?php esc_html_e( 'Пароль агентства', 'zhiraf-ufa' ); ?></label></th>
								<td>
									<input
										name="zhiraf_bg_pwd"
										type="password"
										id="zhiraf_bg_pwd"
										value=""
										placeholder="<?php echo $has_pwd ? '••••••••••••' : ''; ?>"
										class="regular-text"
										<?php echo $is_constant_cfg ? 'readonly' : ''; ?>
										autocomplete="new-password"
									/>
									<p class="description">
										<?php echo $has_pwd ? esc_html__( 'Пароль сохранён. Оставьте пустым, чтобы не изменять.', 'zhiraf-ufa' ) : esc_html__( 'Введите пароль агентства.', 'zhiraf-ufa' ); ?>
									</p>
								</td>
							</tr>
						</table>

						<?php if ( ! $is_constant_cfg ) : ?>
							<p class="submit">
								<button type="submit" class="button button-primary"><?php esc_html_e( 'Сохранить настройки', 'zhiraf-ufa' ); ?></button>
							</p>
						<?php endif; ?>
					</form>
				</div>

				<div class="card" style="padding:20px;margin-top:20px;max-width:100%;">
					<h2><?php esc_html_e( 'Управление синхронизацией цен', 'zhiraf-ufa' ); ?></h2>
					<p class="description">
						<?php esc_html_e( 'Цены синхронизируются автоматически каждые 6 часов через WP-Cron (хук zhiraf_bg_sync_prices).', 'zhiraf-ufa' ); ?>
					</p>

					<div style="display:flex;gap:12px;margin-top:16px;">
						<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page=zhiraf-bg-api' ) ); ?>">
							<?php wp_nonce_field( 'zhiraf_bg_sync_nonce', 'zhiraf_bg_nonce' ); ?>
							<input type="hidden" name="zhiraf_bg_action" value="manual_sync" />
							<button type="submit" class="button button-secondary button-hero">
								🔄 <?php esc_html_e( 'Синхронизировать цены сейчас', 'zhiraf-ufa' ); ?>
							</button>
						</form>

						<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page=zhiraf-bg-api' ) ); ?>">
							<?php wp_nonce_field( 'zhiraf_bg_flush_nonce', 'zhiraf_bg_nonce' ); ?>
							<input type="hidden" name="zhiraf_bg_action" value="flush_cache" />
							<button type="submit" class="button button-link-delete" style="align-self:center;">
								<?php esc_html_e( 'Очистить кэш (Transients)', 'zhiraf-ufa' ); ?>
							</button>
						</form>
					</div>
				</div>
			</div>

			<!-- Right Column: Status & Stats -->
			<div>
				<div class="card" style="padding:20px;max-width:100%;">
					<h2><?php esc_html_e( 'Диагностика и статус движка', 'zhiraf-ufa' ); ?></h2>

					<table class="widefat striped" style="margin-top:12px;">
						<tbody>
							<tr>
								<td><strong><?php esc_html_e( 'Источник данных:', 'zhiraf-ufa' ); ?></strong></td>
								<td>
									<?php if ( 'live_success' === $last_sync_status ) : ?>
										<span style="color:#00a32a;font-weight:600;">🟢 Live BG API</span>
									<?php else : ?>
										<span style="color:#2271b1;font-weight:600;">📦 Fixtures Fallback (bg-tours-fixtures.json)</span>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e( 'Город вылета:', 'zhiraf-ufa' ); ?></strong></td>
								<td>Уфа (ID: <code>100510324872</code>)</td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e( 'Авторизация BG:', 'zhiraf-ufa' ); ?></strong></td>
								<td>
									<?php if ( $client->is_configured() ) : ?>
										<span style="color:#00a32a;">✔ Настроена (Логин: <?php echo esc_html( substr( $login_val, 0, 3 ) . '***' ); ?>)</span>
									<?php else : ?>
										<span style="color:#d63638;">✖ Не настроена</span>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e( 'Сессионные куки (A1/Z1/L):', 'zhiraf-ufa' ); ?></strong></td>
								<td>
									<?php if ( ! empty( $cookies['A1'] ) || ! empty( $cookies['Z1'] ) ) : ?>
										<span style="color:#00a32a;">✔ Активны (Z1 обновляется)</span>
									<?php else : ?>
										<span style="color:#646970;">— Будут получены при синхронизации</span>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e( 'Последняя синхронизация:', 'zhiraf-ufa' ); ?></strong></td>
								<td>
									<?php
									echo $last_sync_time > 0
										? esc_html( date_i18n( 'd.m.Y H:i:s', $last_sync_time ) )
										: esc_html__( 'Ещё не запускалась', 'zhiraf-ufa' );
									?>
								</td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e( 'Следующий запуск WP-Cron:', 'zhiraf-ufa' ); ?></strong></td>
								<td>
									<?php
									echo $next_cron
										? esc_html( date_i18n( 'd.m.Y H:i:s', $next_cron ) )
										: esc_html__( 'Не запланирован', 'zhiraf-ufa' );
									?>
								</td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e( 'Горящие туры (главная):', 'zhiraf-ufa' ); ?></strong></td>
								<td><strong><?php echo esc_html( count( $bundle['home_featured'] ?? array() ) ); ?></strong> туров</td>
							</tr>
							<?php foreach ( Zhiraf_BG_Price_Engine::$countries as $c_slug => $c_data ) : ?>
								<tr>
									<td><?php echo esc_html( $c_data['name_ru'] ); ?> (<code><?php echo esc_html( $c_slug ); ?></code>):</td>
									<td><strong><?php echo esc_html( count( $bundle['destinations'][ $c_slug ] ?? array() ) ); ?></strong> туров</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>

		</div>
	</div>
	<?php
}

/**
 * =========================================================================
 * Public Helper Functions
 * =========================================================================
 */

/**
 * Get hot tours for homepage showcase.
 *
 * @param int $limit Maximum tours to return (default 6).
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_get_hot_tours( $limit = 6 ) {
	return Zhiraf_BG_Price_Engine::get_instance()->get_hot_tours( $limit );
}

/**
 * Get tours for a specific country destination.
 *
 * Supports slugs 'turkey'/'turciya', 'uae'/'oae', 'egypt'/'egipet', 'thailand'/'tailand', 'vietnam'.
 *
 * @param string $country_slug_or_id Destination slug or BG country ID.
 * @param int    $limit Maximum tours to return (default 5).
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_get_country_tours( $country_slug_or_id, $limit = 5 ) {
	return Zhiraf_BG_Price_Engine::get_instance()->get_country_tours( $country_slug_or_id, $limit );
}

/**
 * Format tour price in rubles: "от XX XXX ₽".
 *
 * @param int|float|string $price Price in rubles.
 * @return string Formatted string like "от 95 018 ₽".
 */
function zhiraf_ufa_format_tour_price( $price ) {
	$int_val = (int) preg_replace( '/[^\d]/', '', (string) $price );
	if ( $int_val <= 0 ) {
		return __( 'цена по запросу', 'zhiraf-ufa' );
	}
	return sprintf( 'от %s ₽', number_format( $int_val, 0, '.', ' ' ) );
}
