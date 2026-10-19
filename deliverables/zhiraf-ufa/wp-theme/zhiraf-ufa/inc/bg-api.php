<?php
/**
 * Biblio-Globus API (BG API) Live Price Engine & Integration.
 *
 * Implements authentication (A1, Z1, L session cookies with Z1 rotation),
 * detailed price fetching (partners?action=price, strictly from RUR field),
 * field validation, transients caching with 6-hour TTL, WP-Cron synchronization,
 * detailed step-by-step sync logging, diagnostic WP Admin panel,
 * and automatic fallback to bg-tours-fixtures.json.
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
	 * Primary auth endpoint (HTTPS only).
	 *
	 * @var string
	 */
	const AUTH_ENDPOINT = 'https://login.bgoperator.ru/auth';

	/**
	 * Fallback auth endpoint (HTTPS only).
	 *
	 * @var string
	 */
	const AUTH_ENDPOINT_FALLBACK = 'https://www.bgoperator.ru/auth';

	/**
	 * Export API base URL (HTTPS only).
	 *
	 * @var string
	 */
	const EXPORT_BASE_URL = 'https://export.bgoperator.ru';

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

		$endpoints  = array( self::AUTH_ENDPOINT, self::AUTH_ENDPOINT_FALLBACK );
		$last_error = '';

		foreach ( $endpoints as $endpoint ) {
			$response = wp_remote_post(
				$endpoint,
				array(
					'timeout'     => 60,
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

			$code = (int) wp_remote_retrieve_response_code( $response );
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
	 * @param string $url URL to request (HTTPS only).
	 * @param array  $args Optional wp_remote_get arguments.
	 * @param bool   $retry_on_401 Whether to re-auth and retry on 401.
	 * @return array{ok:bool,code:int,body:string,data:mixed,bytes:int,error:string}
	 */
	public function get( $url, array $args = array(), $retry_on_401 = true ) {
		// Enforce HTTPS.
		if ( 0 === strpos( $url, 'http://' ) ) {
			$url = 'https://' . substr( $url, 7 );
		}

		if ( empty( $this->cookies['A1'] ) && empty( $this->cookies['Z1'] ) ) {
			$auth = $this->authenticate();
			if ( ! $auth['ok'] ) {
				return array(
					'ok'    => false,
					'code'  => 401,
					'body'  => '',
					'data'  => null,
					'bytes' => 0,
					'error' => $auth['message'],
				);
			}
		}

		$cookie_header = $this->build_cookie_header();

		$default_headers = array(
			'Accept-Encoding' => 'gzip',
			'User-Agent'      => 'Zhiraf-Ufa-BG-Engine/1.0 (+https://zhiraf-ufa.ru)',
			'Accept'          => 'application/json, text/javascript, application/xml, text/xml, */*',
		);
		if ( ! empty( $cookie_header ) ) {
			$default_headers['Cookie'] = $cookie_header;
		}

		$request_args = wp_parse_args(
			$args,
			array(
				'timeout'    => 60,
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
				'bytes' => 0,
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
				'bytes' => 0,
				'error' => '401 Unauthorized: ' . $auth['message'],
			);
		}

		$body = wp_remote_retrieve_body( $response );

		// Decompress gzip payload if magic bytes \x1f\x8b are present (cURL didn't auto-decode or double gzip).
		if ( function_exists( 'gzdecode' ) && 0 === strncmp( $body, "\x1f\x8b", 2 ) ) {
			$decompressed = @gzdecode( $body );
			if ( false !== $decompressed ) {
				$body = $decompressed;
			}
		}

		// Strip UTF-8 BOM if present.
		$body       = preg_replace( '/^\xEF\xBB\xBF/', '', trim( (string) $body ) );
		$body_bytes = strlen( $body );

		if ( $code >= 400 ) {
			return array(
				'ok'    => false,
				'code'  => $code,
				'body'  => $body,
				'data'  => null,
				'bytes' => $body_bytes,
				'error' => sprintf( 'HTTP %d response from BG API', $code ),
			);
		}

		// Decode JSON or XML payload.
		$data = json_decode( $body, true );
		if ( null === $data && 0 === strpos( $body, '<' ) ) {
			$xml = @simplexml_load_string( $body );
			if ( false !== $xml ) {
				$data = json_decode( (string) wp_json_encode( $xml ), true );
			}
		}

		return array(
			'ok'    => true,
			'code'  => $code,
			'body'  => $body,
			'data'  => $data,
			'bytes' => $body_bytes,
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
	 * Option key for detailed sync log history.
	 *
	 * @var string
	 */
	const LOG_OPTION_KEY = 'zhiraf_bg_sync_log';

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
	 * Known hotel catalog mapping for Biblio-Globus hotel IDs.
	 *
	 * @var array<string,array{name:string,stars:string,resort:string}>
	 */
	public static $hotel_directory = array(
		// Turkey
		'102610117721' => array(
			'name'   => 'Grand Ant Hotel Istanbul',
			'stars'  => '3*',
			'resort' => 'Стамбул (Старый город-Султанахмет)',
		),
		'102610117714' => array(
			'name'   => 'Kaya Hotel Istanbul',
			'stars'  => '3*',
			'resort' => 'Стамбул (Старый город-Султанахмет)',
		),
		'102610117758' => array(
			'name'   => 'Grand Washington Hotel Istanbul',
			'stars'  => '4*',
			'resort' => 'Стамбул (Старый город-Султанахмет)',
		),
		'102610135621' => array(
			'name'   => 'Klas Hotel Istanbul',
			'stars'  => '4*',
			'resort' => 'Стамбул (Лалели)',
		),
		'102610122676' => array(
			'name'   => 'Vicenza Hotel Istanbul',
			'stars'  => '4*',
			'resort' => 'Стамбул (Фатих)',
		),
		'102616154038' => array(
			'name'   => 'Antis Hotel Istanbul Sultanahmet',
			'stars'  => '4*',
			'resort' => 'Стамбул (Старый город-Султанахмет)',
		),
		'102610114708' => array(
			'name'   => 'Darkhill Hotel Istanbul Laleli',
			'stars'  => '4*',
			'resort' => 'Стамбул (Фатих)',
		),

		// UAE
		'102616534618' => array(
			'name'   => 'Al Seef Hotel Sharjah',
			'stars'  => '3*',
			'resort' => 'Шарджа',
		),
		'102610305324' => array(
			'name'   => 'Ramada Hotel & Suites by Wyndham Ajman',
			'stars'  => '4*',
			'resort' => 'Аджман',
		),
		'102630893783' => array(
			'name'   => 'Flamingo Beach Resort',
			'stars'  => '3*',
			'resort' => 'Умм Аль Кувейн',
		),
		'102610129435' => array(
			'name'   => 'Sharjah Premiere Hotel & Resort',
			'stars'  => '4*',
			'resort' => 'Шарджа',
		),
		'102610123624' => array(
			'name'   => 'Golden Tulip Sharjah',
			'stars'  => '4*',
			'resort' => 'Шарджа',
		),
		'102610214846' => array(
			'name'   => 'Al Seef Hotel Sharjah',
			'stars'  => '3*',
			'resort' => 'Шарджа',
		),
		'102610237731' => array(
			'name'   => 'Ramada Hotel & Suites by Wyndham Ajman',
			'stars'  => '4*',
			'resort' => 'Аджман',
		),

		// Egypt
		'102632927562' => array(
			'name'   => 'Minamark Beach Resort Hurghada',
			'stars'  => '4*',
			'resort' => 'Хургада',
		),
		'102675395936' => array(
			'name'   => 'Bella Vista Resort Hurghada',
			'stars'  => '4*',
			'resort' => 'Хургада',
		),
		'102610201634' => array(
			'name'   => 'Regency Plaza Aqua Park & Spa',
			'stars'  => '5*',
			'resort' => 'Шарм-эль-Шейх',
		),
		'102661696060' => array(
			'name'   => 'Pickalbatros Aqua Park Resort Hurghada',
			'stars'  => '4*',
			'resort' => 'Хургада',
		),
		'102632810444' => array(
			'name'   => 'Seagull Beach Resort Hurghada',
			'stars'  => '4*',
			'resort' => 'Хургада',
		),
		'102610123547' => array(
			'name'   => 'Bella Vista Resort Hurghada',
			'stars'  => '4*',
			'resort' => 'Хургада',
		),
		'102610123533' => array(
			'name'   => 'Regency Plaza Aqua Park & Spa',
			'stars'  => '5*',
			'resort' => 'Шарм-эль-Шейх',
		),
		'102610123522' => array(
			'name'   => 'Seagull Beach Resort Hurghada',
			'stars'  => '4*',
			'resort' => 'Хургада',
		),
		'102610228383' => array(
			'name'   => 'Pickalbatros Aqua Park Resort Hurghada',
			'stars'  => '4*',
			'resort' => 'Хургада',
		),

		// Thailand
		'102610268222' => array(
			'name'   => 'Kamala Beach Resort A Sunprime Resort',
			'stars'  => '4*',
			'resort' => 'Пхукет, Пляж Камала',
		),
		'102610205893' => array(
			'name'   => 'Sugar Palm Grand Hillside Kata',
			'stars'  => '4*',
			'resort' => 'Пхукет, Пляж Ката',
		),
		'102610157476' => array(
			'name'   => 'Splash Beach Resort Maikhao Phuket',
			'stars'  => '5*',
			'resort' => 'Пхукет, Пляж Май Хао',
		),
		'102670735843' => array(
			'name'   => 'Sugar Marina Resort ART Karon Beach',
			'stars'  => '4*',
			'resort' => 'Пхукет, Пляж Карон',
		),
		'102623578045' => array(
			'name'   => 'The Charm Resort Phuket',
			'stars'  => '4*',
			'resort' => 'Пхукет, Пляж Патонг',
		),
		'102610230536' => array(
			'name'   => 'Splash Beach Resort Maikhao Phuket',
			'stars'  => '5*',
			'resort' => 'Пхукет, Пляж Май Хао',
		),
		'102610245233' => array(
			'name'   => 'Sugar Marina Resort ART Karon Beach',
			'stars'  => '4*',
			'resort' => 'Пхукет, Пляж Карон',
		),
		'102610238127' => array(
			'name'   => 'The Charm Resort Phuket',
			'stars'  => '4*',
			'resort' => 'Пхукет, Пляж Патонг',
		),

		// Vietnam
		'102610289828' => array(
			'name'   => 'V Hotel Nha Trang',
			'stars'  => '4*',
			'resort' => 'Нячанг',
		),
		'102610262501' => array(
			'name'   => 'Golden Sea Hotel Nha Trang',
			'stars'  => '3*',
			'resort' => 'Нячанг',
		),
		'102610262485' => array(
			'name'   => 'Regalia Hotel Nha Trang',
			'stars'  => '3*',
			'resort' => 'Нячанг',
		),
		'102610279315' => array(
			'name'   => 'Erica Hotel Nha Trang',
			'stars'  => '4*',
			'resort' => 'Нячанг',
		),
		'102610262575' => array(
			'name'   => 'Virgo Hotel Nha Trang',
			'stars'  => '5*',
			'resort' => 'Нячанг',
		),
		'102610280459' => array(
			'name'   => 'Erica Hotel Nha Trang',
			'stars'  => '4*',
			'resort' => 'Нячанг',
		),
		'102610262483' => array(
			'name'   => 'V Hotel Nha Trang',
			'stars'  => '4*',
			'resort' => 'Нячанг',
		),
		'102610280461' => array(
			'name'   => 'Virgo Hotel Nha Trang',
			'stars'  => '5*',
			'resort' => 'Нячанг',
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

		$fixture_dir = defined( 'ZHIRAF_UFA_DIR' ) ? ZHIRAF_UFA_DIR : dirname( __DIR__ );
		$fixture_file = $fixture_dir . '/data/bg-tours-fixtures.json';
		if ( file_exists( $fixture_file ) && is_readable( $fixture_file ) ) {
			$contents = file_get_contents( $fixture_file );
			$decoded  = json_decode( (string) $contents, true );
			if ( is_array( $decoded ) && ! empty( $decoded['destinations'] ) ) {
				$this->fixtures_data = $decoded;
				return $this->fixtures_data;
			}
		}

		// Fallback minimal structure if file is missing.
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
	 * Strictly logs every step, handles decompression and HTTP codes, and accurately
	 * updates status/message (warning/failed if 0 tours are synced).
	 *
	 * @return array{ok:bool,message:string,counts:array<string,int>,source:string,status:string,log:array}
	 */
	public function sync_all_prices() {
		$client = Zhiraf_BG_API_Client::get_instance();

		$sync_log = array(
			'started_at'   => time(),
			'finished_at'  => null,
			'status'       => 'pending',
			'message'      => '',
			'total_parsed' => 0,
			'steps'        => array(),
		);

		if ( ! $client->is_configured() ) {
			$fixtures = $this->get_fixtures_data();
			set_transient( self::TRANSIENT_KEY, $fixtures, ZHIRAF_BG_CACHE_TTL );
			update_option( self::BACKUP_OPTION_KEY, $fixtures );
			update_option( 'zhiraf_bg_last_sync_time', time() );
			update_option( 'zhiraf_bg_last_sync_status', 'fixtures_fallback' );
			update_option( 'zhiraf_bg_last_sync_message', 'Логин/пароль BG API не заданы. Применены резервные фикстуры.' );
			update_option( 'zhiraf_bg_last_sync_count', 0 );

			$sync_log['finished_at'] = time();
			$sync_log['status']      = 'fixtures_fallback';
			$sync_log['message']     = 'Логин/пароль BG API не заданы. Применены резервные фикстуры.';
			update_option( self::LOG_OPTION_KEY, $sync_log );

			return array(
				'ok'      => true,
				'message' => 'Логин/пароль BG API не заданы. Применены резервные фикстуры.',
				'counts'  => $this->count_bundle_tours( $fixtures ),
				'source'  => 'fixtures',
				'status'  => 'fixtures_fallback',
				'log'     => $sync_log,
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
				'https://export.bgoperator.ru/partners?action=price&tid=211&flt=%s&flt2=%s&xml=11',
				rawurlencode( $country_id ),
				rawurlencode( self::UFA_DEPARTURE_ID )
			);

			$res = $client->get( $url );

			$step_log = array(
				'country'           => $c_info['name_ru'],
				'country_slug'      => $slug,
				'country_id'        => $country_id,
				'url'               => $url,
				'http_code'         => $res['code'],
				'response_bytes'    => $res['bytes'] ?? strlen( (string) ( $res['body'] ?? '' ) ),
				'raw_entries_count' => 0,
				'parsed_count'      => 0,
				'filtered_count'    => 0,
				'filter_reasons'    => array(
					'stop_sales'              => 0,
					'duration_under_3_nights' => 0,
					'no_rur_price'            => 0,
					'invalid_date'            => 0,
					'empty_hotel'             => 0,
				),
				'status'            => 'ok',
				'error'             => $res['error'],
			);

			if ( ! $res['ok'] || empty( $res['data'] ) ) {
				$err_desc = $res['error'] ? $res['error'] : 'Пустой или некорректный ответ сервера';
				$errors[] = sprintf( '%s: %s (HTTP %d)', $c_info['name_ru'], $err_desc, $res['code'] );

				$step_log['status'] = 'error';
				$step_log['error']  = $err_desc;

				// Fallback this country from fixtures.
				$fixtures = $this->get_fixtures_data();
				$bundle['destinations'][ $slug ] = ! empty( $fixtures['destinations'][ $slug ] ) ? $fixtures['destinations'][ $slug ] : array();
				$counts[ $slug ] = count( $bundle['destinations'][ $slug ] );

				$sync_log['steps'][] = $step_log;
				continue;
			}

			$parse_stats   = array();
			$parsed_tours  = $this->parse_price_entries_with_stats( $res['data'], $slug, $price_date, $disclaimer, $parse_stats );

			$step_log['raw_entries_count'] = $parse_stats['raw_entries'] ?? 0;
			$step_log['parsed_count']      = count( $parsed_tours );
			$step_log['filtered_count']    = $parse_stats['filtered_total'] ?? 0;
			$step_log['filter_reasons']    = $parse_stats['filter_reasons'] ?? $step_log['filter_reasons'];

			if ( ! empty( $parsed_tours ) ) {
				$bundle['destinations'][ $slug ] = $parsed_tours;
				$counts[ $slug ]                 = count( $parsed_tours );
				$total_fetched                  += count( $parsed_tours );
				$step_log['status']              = 'ok';
			} else {
				$step_log['status'] = 'warning';
				$step_log['error']  = 'Все полученные строки отфильтрованы правилами валидации.';
				$errors[]           = sprintf( '%s: 0 валидных туров (все строки отсеяны)', $c_info['name_ru'] );

				// Fallback to fixtures if entries were invalid.
				$fixtures = $this->get_fixtures_data();
				$bundle['destinations'][ $slug ] = ! empty( $fixtures['destinations'][ $slug ] ) ? $fixtures['destinations'][ $slug ] : array();
				$counts[ $slug ]                 = count( $bundle['destinations'][ $slug ] );
			}

			$sync_log['steps'][] = $step_log;
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

		// Determine strict final sync status.
		if ( $total_fetched > 0 && empty( $errors ) ) {
			$sync_status  = 'live_success';
			$sync_message = sprintf(
				'Успешно синхронизировано %d туров из Live BG API на %s. Все 5 направлений обновлены.',
				$total_fetched,
				$price_date
			);
			$is_ok        = true;
		} elseif ( $total_fetched > 0 && ! empty( $errors ) ) {
			$sync_status  = 'partial_success';
			$sync_message = sprintf(
				'Частичная синхронизация: %d туров получено из Live BG API на %s. Ошибки: %s',
				$total_fetched,
				$price_date,
				implode( '; ', $errors )
			);
			$is_ok        = true;
		} else {
			$sync_status  = 'sync_warning';
			$sync_message = sprintf(
				'Предупреждение: из Live BG API получено 0 туров на %s (%s). Сохранены резервные фикстуры. Проверьте диагностический лог и доступ к API.',
				$price_date,
				( ! empty( $errors ) ? implode( '; ', $errors ) : 'все строки отфильтрованы или пустые ответы' )
			);
			$is_ok        = false;
		}

		$sync_log['finished_at']  = time();
		$sync_log['status']       = $sync_status;
		$sync_log['message']      = $sync_message;
		$sync_log['total_parsed'] = $total_fetched;

		update_option( 'zhiraf_bg_last_sync_time', time() );
		update_option( 'zhiraf_bg_last_sync_status', $sync_status );
		update_option( 'zhiraf_bg_last_sync_message', $sync_message );
		update_option( 'zhiraf_bg_last_sync_count', $total_fetched );
		update_option( self::LOG_OPTION_KEY, $sync_log );

		return array(
			'ok'      => $is_ok,
			'message' => $sync_message,
			'counts'  => $counts,
			'source'  => $total_fetched > 0 ? 'live_api' : 'fixtures_fallback',
			'status'  => $sync_status,
			'log'     => $sync_log,
		);
	}

	/**
	 * Parse detailed price entries from BG action=price response.
	 *
	 * Public wrapper for backwards compatibility and offline tests.
	 * Rubles are taken STRICTLY from entries[].prices[].RUR.
	 *
	 * @param array|string $data Raw JSON response array or string.
	 * @param string       $country_slug Canonical destination slug.
	 * @param string       $price_date Date of price fetch.
	 * @param string       $disclaimer Legal disclaimer.
	 * @return array<int,array<string,mixed>>
	 */
	public function parse_price_entries( $data, $country_slug, $price_date = '', $disclaimer = '' ) {
		$dummy_stats = array();
		return $this->parse_price_entries_with_stats( $data, $country_slug, $price_date, $disclaimer, $dummy_stats );
	}

	/**
	 * Parse detailed price entries with detailed filtering statistics.
	 *
	 * @param array|string $data Raw JSON response array, XML object or string.
	 * @param string       $country_slug Canonical destination slug.
	 * @param string       $price_date Date of price fetch.
	 * @param string       $disclaimer Legal disclaimer.
	 * @param array        $stats Output statistics array.
	 * @return array<int,array<string,mixed>>
	 */
	public function parse_price_entries_with_stats( $data, $country_slug, $price_date = '', $disclaimer = '', array &$stats = array() ) {
		if ( empty( $price_date ) ) {
			$price_date = date( 'd.m.Y' );
		}
		if ( empty( $disclaimer ) ) {
			$disclaimer = sprintf( 'рассчитана по курсу туроператора на %s, итоговая стоимость фиксируется при бронировании', $price_date );
		}

		if ( is_string( $data ) ) {
			$decoded = json_decode( $data, true );
			if ( is_array( $decoded ) ) {
				$data = $decoded;
			} elseif ( 0 === strpos( trim( $data ), '<' ) ) {
				$xml = @simplexml_load_string( $data );
				if ( false !== $xml ) {
					$data = json_decode( (string) wp_json_encode( $xml ), true );
				}
			}
		}

		$entries = array();
		if ( is_array( $data ) ) {
			if ( isset( $data['entries'] ) && is_array( $data['entries'] ) ) {
				$entries = $data['entries'];
			} elseif ( isset( $data['entry'] ) && is_array( $data['entry'] ) ) {
				$entries = $data['entry'];
			} elseif ( isset( $data[0] ) ) {
				$entries = $data;
			}
		}

		$stats = array(
			'raw_entries'    => count( $entries ),
			'filtered_total' => 0,
			'filter_reasons' => array(
				'stop_sales'              => 0,
				'duration_under_3_nights' => 0,
				'no_rur_price'            => 0,
				'invalid_date'            => 0,
				'empty_hotel'             => 0,
			),
		);

		if ( empty( $entries ) ) {
			return array();
		}

		$valid_cards  = array();
		$seen_hotels  = array();

		foreach ( $entries as $entry ) {
			if ( ! is_array( $entry ) ) {
				$stats['filtered_total']++;
				continue;
			}

			// 1. Validate stop-sales.
			if ( ! empty( $entry['isstop'] ) ) {
				$stop = is_array( $entry['isstop'] ) ? implode( ' ', $entry['isstop'] ) : (string) $entry['isstop'];
				if ( false !== stripos( $stop, 'avia' ) || false !== stripos( $stop, 'hotel' ) ) {
					$stats['filter_reasons']['stop_sales']++;
					$stats['filtered_total']++;
					continue;
				}
			}

			// 2. Extract and validate nights (P0 Beach filter: >= 3 nights).
			$nights = 0;
			if ( isset( $entry['o_duration'] ) && (int) $entry['o_duration'] > 0 ) {
				$nights = (int) $entry['o_duration'];
			} elseif ( isset( $entry['duration'] ) && (int) $entry['duration'] > 0 ) {
				$nights = (int) $entry['duration'];
			}

			if ( $nights < 3 ) {
				$stats['filter_reasons']['duration_under_3_nights']++;
				$stats['filtered_total']++;
				continue;
			}

			// 3. Extract and validate Ruble price strictly from prices[].RUR or cost.
			$price_rur = 0;
			if ( ! empty( $entry['prices'] ) && is_array( $entry['prices'] ) ) {
				foreach ( $entry['prices'] as $p_item ) {
					if ( ! is_array( $p_item ) ) {
						continue;
					}
					if ( isset( $p_item['RUR'] ) ) {
						$candidate = (int) preg_replace( '/[^\d]/', '', (string) $p_item['RUR'] );
						if ( $candidate > 0 ) {
							$price_rur = $candidate;
							break;
						}
					}
				}
			}
			if ( $price_rur <= 0 && isset( $entry['price_rub'] ) ) {
				$price_rur = (int) $entry['price_rub'];
			}
			if ( $price_rur <= 0 && isset( $entry['cost'] ) && is_array( $entry['cost'] ) ) {
				if ( ( $entry['cost']['valute'] ?? '' ) === 'RUR' ) {
					$price_rur = (int) ( $entry['cost']['amount'] ?? 0 );
				}
			}

			if ( $price_rur <= 0 ) {
				$stats['filter_reasons']['no_rur_price']++;
				$stats['filtered_total']++;
				continue;
			}

			// 4. Extract and validate date.
			$departure_date = '';
			if ( ! empty( $entry['tour_date'] ) ) {
				$departure_date = trim( (string) $entry['tour_date'] );
			} elseif ( ! empty( $entry['date'] ) ) {
				$departure_date = trim( (string) $entry['date'] );
			} elseif ( ! empty( $entry['dt'] ) ) {
				$departure_date = trim( (string) $entry['dt'] );
			}

			if ( empty( $departure_date ) ) {
				$stats['filter_reasons']['invalid_date']++;
				$stats['filtered_total']++;
				continue;
			}

			// 5. Extract resort/town.
			$resort = '';
			if ( ! empty( $entry['town'] ) ) {
				$resort_raw   = trim( (string) $entry['town'] );
				$resort_parts = explode( '/', $resort_raw );
				$resort       = trim( $resort_parts[0] );
			} elseif ( ! empty( $entry['resort'] ) ) {
				$resort = trim( (string) $entry['resort'] );
			}
			if ( empty( $resort ) ) {
				$resort = self::$countries[ $country_slug ]['name_ru'] ?? 'Курорт';
			}

			// 6. Extract hotel name & category (catalog lookup with graceful fallback).
			$hotel_id   = ! empty( $entry['id_hotel'] ) ? trim( (string) $entry['id_hotel'] ) : '';
			$hotel_name = '';
			$stars      = '4*';

			if ( ! empty( $hotel_id ) && isset( self::$hotel_directory[ $hotel_id ] ) ) {
				$hotel_name = self::$hotel_directory[ $hotel_id ]['name'];
				$stars      = self::$hotel_directory[ $hotel_id ]['stars'];
				if ( ! empty( self::$hotel_directory[ $hotel_id ]['resort'] ) ) {
					$resort = self::$hotel_directory[ $hotel_id ]['resort'];
				}
			} elseif ( ! empty( $entry['hotel'] ) ) {
				$hotel_name = trim( (string) $entry['hotel'] );
				$stars      = $this->parse_hotel_stars( $entry, $hotel_name );
			} elseif ( ! empty( $entry['hotel_name'] ) ) {
				$hotel_name = trim( (string) $entry['hotel_name'] );
				$stars      = $this->parse_hotel_stars( $entry, $hotel_name );
			} elseif ( ! empty( $entry['tourname'] ) ) {
				$hotel_name = trim( (string) $entry['tourname'] );
				$stars      = $this->parse_hotel_stars( $entry, $hotel_name );
			} elseif ( ! empty( $hotel_id ) ) {
				$hotel_name = 'Отель ' . $resort . ' #' . $hotel_id;
				$stars      = $this->parse_hotel_stars( $entry, $hotel_name );
			}

			if ( empty( $hotel_name ) ) {
				$stats['filter_reasons']['empty_hotel']++;
				$stats['filtered_total']++;
				continue;
			}

			// 7. Extract meal.
			$meal_info = $this->parse_meal_code_and_name( $entry );

			// Deduplicate by hotel name to ensure rich selection.
			if ( isset( $seen_hotels[ $hotel_name ] ) ) {
				continue;
			}
			$seen_hotels[ $hotel_name ] = true;

			$card = array(
				'hotel_name'      => $hotel_name,
				'stars'           => $stars,
				'resort'          => $resort,
				'country_slug'    => $country_slug,
				'departure_date'  => $departure_date,
				'date'            => $departure_date,
				'nights'          => $nights,
				'duration'        => $nights,
				'meal'            => $meal_info['name'],
				'meal_code'       => $meal_info['code'],
				'meal_name'       => $meal_info['name'],
				'tourists'        => '2 взрослых',
				'price_rur'       => $price_rur,
				'price_rub'       => $price_rur,
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
		'zhiraf-bg-settings',
		'zhiraf_bg_admin_page_render'
	);
}
add_action( 'admin_menu', 'zhiraf_bg_admin_menu' );

/**
 * Handle admin POST actions (save settings, manual sync, cache flush, clear log).
 */
function zhiraf_bg_admin_actions_handler() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$target_page = isset( $_GET['page'] ) && 'zhiraf-bg-api' === $_GET['page'] ? 'zhiraf-bg-api' : 'zhiraf-bg-settings';

	// 1. Save Settings.
	if ( isset( $_POST['zhiraf_bg_action'] ) && 'save_settings' === $_POST['zhiraf_bg_action'] ) {
		check_admin_referer( 'zhiraf_bg_save_settings_nonce', 'zhiraf_bg_nonce' );

		$login = isset( $_POST['zhiraf_bg_login'] ) ? sanitize_text_field( wp_unslash( $_POST['zhiraf_bg_login'] ) ) : '';
		update_option( 'zhiraf_bg_login', $login );

		if ( isset( $_POST['zhiraf_bg_pwd'] ) && '' !== trim( (string) $_POST['zhiraf_bg_pwd'] ) ) {
			$pwd = sanitize_text_field( wp_unslash( $_POST['zhiraf_bg_pwd'] ) );
			update_option( 'zhiraf_bg_pwd', $pwd );
		}

		wp_safe_redirect( add_query_arg( array( 'page' => $target_page, 'zhiraf_msg' => 'saved' ), admin_url( 'options-general.php' ) ) );
		exit;
	}

	// 2. Manual Sync.
	if ( isset( $_POST['zhiraf_bg_action'] ) && 'manual_sync' === $_POST['zhiraf_bg_action'] ) {
		check_admin_referer( 'zhiraf_bg_sync_nonce', 'zhiraf_bg_nonce' );

		$result = Zhiraf_BG_Price_Engine::get_instance()->sync_all_prices();

		$msg_code = 'synced';
		if ( ! $result['ok'] ) {
			$msg_code = 'sync_warning';
		} elseif ( 'partial_success' === ( $result['status'] ?? '' ) ) {
			$msg_code = 'sync_partial';
		}

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'       => $target_page,
					'zhiraf_msg' => $msg_code,
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

		wp_safe_redirect( add_query_arg( array( 'page' => $target_page, 'zhiraf_msg' => 'flushed' ), admin_url( 'options-general.php' ) ) );
		exit;
	}

	// 4. Clear Diagnostic Log.
	if ( isset( $_POST['zhiraf_bg_action'] ) && 'clear_log' === $_POST['zhiraf_bg_action'] ) {
		check_admin_referer( 'zhiraf_bg_clear_log_nonce', 'zhiraf_bg_nonce' );

		delete_option( Zhiraf_BG_Price_Engine::LOG_OPTION_KEY );

		wp_safe_redirect( add_query_arg( array( 'page' => $target_page, 'zhiraf_msg' => 'log_cleared' ), admin_url( 'options-general.php' ) ) );
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
	$last_sync_count  = (int) get_option( 'zhiraf_bg_last_sync_count', 0 );
	$next_cron        = wp_next_scheduled( 'zhiraf_bg_sync_prices' );

	$sync_log = get_option( Zhiraf_BG_Price_Engine::LOG_OPTION_KEY, array() );
	$bundle   = $engine->get_bundle_data();

	$msg = isset( $_GET['zhiraf_msg'] ) ? sanitize_text_field( wp_unslash( $_GET['zhiraf_msg'] ) ) : '';
	$action_url = admin_url( 'options-general.php?page=zhiraf-bg-settings' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Настройки Библио-Глобус API (BG API)', 'zhiraf-ufa' ); ?></h1>

		<?php if ( 'saved' === $msg ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Настройки успешно сохранены.', 'zhiraf-ufa' ); ?></p></div>
		<?php elseif ( 'synced' === $msg ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $last_sync_msg ); ?></p></div>
		<?php elseif ( 'sync_partial' === $msg ) : ?>
			<div class="notice notice-warning is-dismissible"><p><?php echo esc_html( $last_sync_msg ); ?></p></div>
		<?php elseif ( 'sync_warning' === $msg || 'sync_err' === $msg ) : ?>
			<div class="notice notice-error is-dismissible"><p><strong><?php esc_html_e( 'Предупреждение синхронизации:', 'zhiraf-ufa' ); ?></strong> <?php echo esc_html( $last_sync_msg ); ?></p></div>
		<?php elseif ( 'flushed' === $msg ) : ?>
			<div class="notice notice-info is-dismissible"><p><?php esc_html_e( 'Кэш цен и сессионные куки очищены.', 'zhiraf-ufa' ); ?></p></div>
		<?php elseif ( 'log_cleared' === $msg ) : ?>
			<div class="notice notice-info is-dismissible"><p><?php esc_html_e( 'Диагностический лог очищен.', 'zhiraf-ufa' ); ?></p></div>
		<?php endif; ?>

		<div style="display:grid;grid-template-columns:1.1fr 0.9fr;gap:24px;margin-top:20px;">

			<!-- Left Column: Credentials & Actions -->
			<div>
				<div class="card" style="padding:20px;max-width:100%;">
					<h2><?php esc_html_e( 'Параметры авторизации API', 'zhiraf-ufa' ); ?></h2>
					<p class="description">
						<?php esc_html_e( 'Учётные данные турагентства в системе «Библио-Глобус» для получения актуальных цен из Уфы через защищённый протокол HTTPS.', 'zhiraf-ufa' ); ?>
					</p>

					<?php if ( $is_constant_cfg ) : ?>
						<div class="notice notice-info inline" style="margin:12px 0;">
							<p><strong><?php esc_html_e( 'Защищённый режим:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'Логин и пароль заданы через константы ZHIRAF_BG_LOGIN и ZHIRAF_BG_PWD в wp-config.php.', 'zhiraf-ufa' ); ?></p>
						</div>
					<?php endif; ?>

					<form method="post" action="<?php echo esc_url( $action_url ); ?>">
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
							<p class="submit" style="margin-bottom:0;padding-bottom:0;">
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

					<div style="display:flex;gap:12px;margin-top:16px;flex-wrap:wrap;align-items:center;">
						<form method="post" action="<?php echo esc_url( $action_url ); ?>">
							<?php wp_nonce_field( 'zhiraf_bg_sync_nonce', 'zhiraf_bg_nonce' ); ?>
							<input type="hidden" name="zhiraf_bg_action" value="manual_sync" />
							<button type="submit" class="button button-secondary button-hero">
								🔄 <?php esc_html_e( 'Синхронизировать цены сейчас', 'zhiraf-ufa' ); ?>
							</button>
						</form>

						<form method="post" action="<?php echo esc_url( $action_url ); ?>">
							<?php wp_nonce_field( 'zhiraf_bg_flush_nonce', 'zhiraf_bg_nonce' ); ?>
							<input type="hidden" name="zhiraf_bg_action" value="flush_cache" />
							<button type="submit" class="button button-link-delete">
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
										<span style="color:#00a32a;font-weight:600;">🟢 Live BG API (Синхронизировано <?php echo esc_html( $last_sync_count ); ?> туров)</span>
									<?php elseif ( 'partial_success' === $last_sync_status ) : ?>
										<span style="color:#dba617;font-weight:600;">🟡 Live BG API (Частично: <?php echo esc_html( $last_sync_count ); ?> туров)</span>
									<?php elseif ( 'sync_warning' === $last_sync_status || 'sync_failed' === $last_sync_status ) : ?>
										<span style="color:#d63638;font-weight:600;">🔴 Ошибка Live API (0 туров, активен fallback)</span>
									<?php else : ?>
										<span style="color:#2271b1;font-weight:600;">📦 Fixtures Fallback (bg-tours-fixtures.json)</span>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td><strong><?php esc_html_e( 'Протокол / Эндпоинты:', 'zhiraf-ufa' ); ?></strong></td>
								<td><code>HTTPS (login.bgoperator.ru / export.bgoperator.ru)</code></td>
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

		<!-- Detailed Synchronization Log Section -->
		<div class="card" style="padding:20px;margin-top:24px;max-width:100%;">
			<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
				<h2 style="margin:0;"><?php esc_html_e( 'Детальный диагностический лог синхронизации', 'zhiraf-ufa' ); ?></h2>
				<?php if ( ! empty( $sync_log['steps'] ) ) : ?>
					<form method="post" action="<?php echo esc_url( $action_url ); ?>" style="margin:0;">
						<?php wp_nonce_field( 'zhiraf_bg_clear_log_nonce', 'zhiraf_bg_nonce' ); ?>
						<input type="hidden" name="zhiraf_bg_action" value="clear_log" />
						<button type="submit" class="button button-small button-link-delete">
							<?php esc_html_e( 'Очистить лог', 'zhiraf-ufa' ); ?>
						</button>
					</form>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $sync_log['steps'] ) && is_array( $sync_log['steps'] ) ) : ?>
				<p class="description" style="margin-bottom:14px;">
					<?php
					printf(
						/* translators: 1: timestamp, 2: message */
						esc_html__( 'Лог последнего прогона (%1$s): %2$s', 'zhiraf-ufa' ),
						esc_html( date_i18n( 'd.m.Y H:i:s', $sync_log['started_at'] ?? time() ) ),
						esc_html( $sync_log['message'] ?? '' )
					);
					?>
				</p>

				<table class="widefat striped" style="font-size:12px;">
					<thead>
						<tr>
							<th style="width:120px;"><?php esc_html_e( 'Направление', 'zhiraf-ufa' ); ?></th>
							<th><?php esc_html_e( 'URL запроса (HTTPS)', 'zhiraf-ufa' ); ?></th>
							<th style="width:70px;text-align:center;"><?php esc_html_e( 'HTTP', 'zhiraf-ufa' ); ?></th>
							<th style="width:90px;text-align:right;"><?php esc_html_e( 'Размер', 'zhiraf-ufa' ); ?></th>
							<th style="width:80px;text-align:right;"><?php esc_html_e( 'Получено', 'zhiraf-ufa' ); ?></th>
							<th style="width:80px;text-align:right;"><?php esc_html_e( 'Прошло', 'zhiraf-ufa' ); ?></th>
							<th style="width:240px;"><?php esc_html_e( 'Причины отсева строк', 'zhiraf-ufa' ); ?></th>
							<th style="width:90px;text-align:center;"><?php esc_html_e( 'Статус', 'zhiraf-ufa' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $sync_log['steps'] as $step ) : ?>
							<tr>
								<td><strong><?php echo esc_html( $step['country'] ?? '' ); ?></strong></td>
								<td><code style="word-break:break-all;font-size:11px;"><?php echo esc_html( $step['url'] ?? '' ); ?></code></td>
								<td style="text-align:center;">
									<?php $code = (int) ( $step['http_code'] ?? 0 ); ?>
									<span style="font-weight:600;color:<?php echo 200 === $code ? '#00a32a' : '#d63638'; ?>">
										<?php echo esc_html( $code > 0 ? (string) $code : 'ERR' ); ?>
									</span>
								</td>
								<td style="text-align:right;">
									<?php
									$bytes = (int) ( $step['response_bytes'] ?? 0 );
									echo esc_html( $bytes >= 1024 ? number_format( $bytes / 1024, 1 ) . ' KB' : $bytes . ' B' );
									?>
								</td>
								<td style="text-align:right;"><?php echo esc_html( (string) ( $step['raw_entries_count'] ?? 0 ) ); ?></td>
								<td style="text-align:right;"><strong><?php echo esc_html( (string) ( $step['parsed_count'] ?? 0 ) ); ?></strong></td>
								<td>
									<?php
									$reasons = $step['filter_reasons'] ?? array();
									$parts   = array();
									if ( ! empty( $reasons['duration_under_3_nights'] ) ) {
										$parts[] = sprintf( '< 3 ночей: %d', $reasons['duration_under_3_nights'] );
									}
									if ( ! empty( $reasons['no_rur_price'] ) ) {
										$parts[] = sprintf( 'нет цены в рублях: %d', $reasons['no_rur_price'] );
									}
									if ( ! empty( $reasons['stop_sales'] ) ) {
										$parts[] = sprintf( 'стоп-сейл: %d', $reasons['stop_sales'] );
									}
									if ( ! empty( $reasons['invalid_date'] ) ) {
										$parts[] = sprintf( 'некорректная дата: %d', $reasons['invalid_date'] );
									}
									if ( ! empty( $reasons['empty_hotel'] ) ) {
										$parts[] = sprintf( 'нет отеля: %d', $reasons['empty_hotel'] );
									}
									echo ! empty( $parts ) ? esc_html( implode( ', ', $parts ) ) : '<span style="color:#8c8f94;">—</span>';
									if ( ! empty( $step['error'] ) ) {
										echo '<br><span style="color:#d63638;">' . esc_html( $step['error'] ) . '</span>';
									}
									?>
								</td>
								<td style="text-align:center;">
									<?php if ( 'ok' === ( $step['status'] ?? '' ) ) : ?>
										<span style="color:#00a32a;font-weight:600;">✔ OK</span>
									<?php elseif ( 'warning' === ( $step['status'] ?? '' ) ) : ?>
										<span style="color:#dba617;font-weight:600;">⚠ Внимание</span>
									<?php else : ?>
										<span style="color:#d63638;font-weight:600;">✖ Ошибка</span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<p style="color:#646970;margin:0;">
					<?php esc_html_e( 'Лог синхронизации пуст. Запустите ручную синхронизацию для сбора подробной телеметрии запросов.', 'zhiraf-ufa' ); ?>
				</p>
			<?php endif; ?>
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
