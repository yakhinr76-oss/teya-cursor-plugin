<?php
/**
 * Offline BG API Parser Test Suite for Zhiraf Ufa.
 *
 * Loads sample files from teya-memory/bg-api-samples/ and validates parser behavior:
 * - Turkey (price_TR.xml)
 * - Egypt (price_EG.xml)
 * - UAE (price_AE.xml)
 * - Thailand (price_TH.xml)
 * - Vietnam (price_VN.xml)
 *
 * Verifies >= 5 parsed tours per country (whitelisted resorts, beach >= 5 nights), hotel_name, date, duration, meal, price_rub > 0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

if ( ! defined( 'HOUR_IN_SECONDS' ) ) {
	define( 'HOUR_IN_SECONDS', 3600 );
}

if ( ! defined( 'MINUTE_IN_SECONDS' ) ) {
	define( 'MINUTE_IN_SECONDS', 60 );
}

// Mock basic WordPress functions required for standalone CLI execution.
if ( ! function_exists( 'get_option' ) ) {
	$GLOBALS['wp_mock_options'] = array();
	function get_option( $name, $default = false ) {
		return $GLOBALS['wp_mock_options'][ $name ] ?? $default;
	}
}

if ( ! function_exists( 'update_option' ) ) {
	function update_option( $name, $value ) {
		$GLOBALS['wp_mock_options'][ $name ] = $value;
		return true;
	}
}

if ( ! function_exists( 'delete_option' ) ) {
	function delete_option( $name ) {
		unset( $GLOBALS['wp_mock_options'][ $name ] );
		return true;
	}
}

if ( ! function_exists( 'get_transient' ) ) {
	function get_transient( $name ) {
		return false;
	}
}

if ( ! function_exists( 'set_transient' ) ) {
	function set_transient( $name, $value, $expiration = 0 ) {
		return true;
	}
}

if ( ! function_exists( 'delete_transient' ) ) {
	function delete_transient( $name ) {
		return true;
	}
}

if ( ! function_exists( 'wp_parse_args' ) ) {
	function wp_parse_args( $args, $defaults = array() ) {
		if ( is_object( $args ) ) {
			$r = get_object_vars( $args );
		} elseif ( is_array( $args ) ) {
			$r =& $args;
		} else {
			wp_parse_str( $args, $r );
		}
		if ( is_array( $defaults ) ) {
			return array_merge( $defaults, $r );
		}
		return $r;
	}
}

if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( $data, $options = 0, $depth = 512 ) {
		return json_encode( $data, $options, $depth );
	}
}

if ( ! function_exists( 'add_action' ) ) {
	function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {
		return true;
	}
}

if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {
		return true;
	}
}

if ( ! function_exists( 'add_submenu_page' ) ) {
	function add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback = '', $position = null ) {
		return 'settings_page_' . $menu_slug;
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		return trim( strip_tags( (string) $str ) );
	}
}

if ( ! function_exists( 'wp_unslash' ) ) {
	function wp_unslash( $value ) {
		return is_string( $value ) ? stripslashes( $value ) : $value;
	}
}

if ( ! function_exists( 'admin_url' ) ) {
	function admin_url( $path = '', $scheme = 'admin' ) {
		return 'https://example.com/wp-admin/' . ltrim( $path, '/' );
	}
}

if ( ! function_exists( 'wp_nonce_field' ) ) {
	function wp_nonce_field( $action = -1, $name = '_wpnonce', $referer = true, $echo = true ) {
		$html = '<input type="hidden" name="' . esc_attr( $name ) . '" value="test_nonce" />';
		if ( $echo ) {
			echo $html;
		}
		return $html;
	}
}

if ( ! function_exists( 'wp_next_scheduled' ) ) {
	function wp_next_scheduled( $hook ) {
		return false;
	}
}

if ( ! function_exists( 'wp_schedule_event' ) ) {
	function wp_schedule_event( $timestamp, $recurrence, $hook ) {
		return true;
	}
}

if ( ! function_exists( 'wp_unschedule_event' ) ) {
	function wp_unschedule_event( $timestamp, $hook ) {
		return true;
	}
}

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_html_e' ) ) {
	function esc_html_e( $text, $domain = 'default' ) {
		echo esc_html( $text );
	}
}

if ( ! function_exists( 'esc_html__' ) ) {
	function esc_html__( $text, $domain = 'default' ) {
		return (string) $text;
	}
}

if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $url ) {
		return (string) $url;
	}
}

if ( ! function_exists( 'date_i18n' ) ) {
	function date_i18n( $format, $timestamp = false ) {
		return date( $format, $timestamp ? $timestamp : time() );
	}
}

if ( ! function_exists( 'is_admin' ) ) {
	function is_admin() {
		return false;
	}
}

if ( ! function_exists( 'current_user_can' ) ) {
	function current_user_can( $cap ) {
		return true;
	}
}

// Load the BG API engine.
$bg_api_file = dirname( __DIR__ ) . '/deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa/inc/bg-api.php';
if ( ! file_exists( $bg_api_file ) ) {
	fwrite( STDERR, "ERROR: bg-api.php not found at: {$bg_api_file}\n" );
	exit( 1 );
}
require_once $bg_api_file;

$samples_dir = dirname( __DIR__ ) . '/teya-memory/bg-api-samples';
// Hotel catalog subset taken from live yandex?action=hotelsJson (names/stars come only from BG data).
Zhiraf_BG_Price_Engine::$hotel_catalog = json_decode( (string) file_get_contents( $samples_dir . '/hotels_catalog.json' ), true );
if ( ! is_dir( $samples_dir ) ) {
	fwrite( STDERR, "ERROR: Samples dir not found at: {$samples_dir}\n" );
	exit( 1 );
}

$engine = Zhiraf_BG_Price_Engine::get_instance();

$test_cases = array(
	'turciya'  => array(
		'country_name' => 'Турция',
		'sample_file'  => $samples_dir . '/price_TR.xml',
	),
	'egipet'   => array(
		'country_name' => 'Египет',
		'sample_file'  => $samples_dir . '/price_EG.xml',
	),
	'oae'      => array(
		'country_name' => 'ОАЭ',
		'sample_file'  => $samples_dir . '/price_AE.xml',
	),
	'tailand'  => array(
		'country_name' => 'Таиланд',
		'sample_file'  => $samples_dir . '/price_TH.xml',
	),
	'vietnam'  => array(
		'country_name' => 'Вьетнам',
		'sample_file'  => $samples_dir . '/price_VN.xml',
	),
);

echo "========================================================================================\n";
echo "           ZHIRAF UFA: OFFLINE BG API PARSER VERIFICATION TEST SUITE\n";
echo "========================================================================================\n\n";

$all_passed = true;
$summary    = array();

foreach ( $test_cases as $slug => $meta ) {
	$file = $meta['sample_file'];
	$name = $meta['country_name'];

	echo "----------------------------------------------------------------------------------------\n";
	echo " Направление: {$name} ({$slug})\n";
	echo " Файл образца: " . basename( $file ) . "\n";
	echo "----------------------------------------------------------------------------------------\n";

	if ( ! file_exists( $file ) ) {
		echo " [FAIL] Файл не найден: {$file}\n\n";
		$all_passed = false;
		continue;
	}

	$raw_content = file_get_contents( $file );
	$file_size   = strlen( $raw_content );
	echo " Размер файла: " . number_format( $file_size / 1024, 1 ) . " KB\n";

	// Test gzip detection if present in raw content
	if ( substr( $raw_content, 0, 2 ) === "\x1f\x8b" ) {
		$decompressed = @gzdecode( $raw_content );
		if ( false !== $decompressed ) {
			$raw_content = $decompressed;
			echo " Распакован GZIP: " . number_format( strlen( $raw_content ) / 1024, 1 ) . " KB\n";
		}
	}

	$stats = array();
	$tours = $engine->parse_price_entries_with_stats( $raw_content, $slug, '19.10.2026', '', $stats );

	$raw_count      = $stats['raw_entries'] ?? 0;
	$filtered_count = $stats['filtered_total'] ?? 0;
	$parsed_count   = count( $tours );

	echo " Строк в выгрузке: {$raw_count}\n";
	echo " Отфильтровано:    {$filtered_count}\n";
	if ( ! empty( $stats['filter_reasons'] ) ) {
		$reasons_str = array();
		foreach ( $stats['filter_reasons'] as $r_k => $r_v ) {
			if ( $r_v > 0 ) {
				$reasons_str[] = "{$r_k}={$r_v}";
			}
		}
		if ( ! empty( $reasons_str ) ) {
			echo " Причины отсева:   " . implode( ', ', $reasons_str ) . "\n";
		}
	}
	echo " Успешно отобрано: {$parsed_count} туров\n\n";

	// Validation checks
	$errors = array();
	// Resort whitelist + nights rules (owner 19.10.2026): no city breaks, beach countries >= 5 nights.
	$cfg = Zhiraf_BG_Price_Engine::$countries[ $slug ] ?? array();
	foreach ( $tours as $t_idx => $t_row ) {
		if ( ! empty( $cfg['resorts'] ) && ! preg_match( $cfg['resorts'], (string) $t_row['resort'] ) ) {
			$errors[] = "Тур #{$t_idx}: курорт вне белого списка ({$t_row['resort']})";
		}
		if ( ! empty( $cfg['beach'] ) && (int) $t_row['nights'] < 5 ) {
			$errors[] = "Тур #{$t_idx}: пляжное направление, но {$t_row['nights']} ночей";
		}
		if ( preg_match( '/Стамбул/u', (string) $t_row['resort'] ) ) {
			$errors[] = "Тур #{$t_idx}: Стамбул запрещён в карточках";
		}
	}
	if ( $parsed_count < 5 ) {
		$errors[] = "Получено менее 5 туров ({$parsed_count} < 5)";
	}

	printf( " %-4s | %-32s | %-12s | %-6s | %-16s | %-10s\n", '#', 'Отель', 'Дата', 'Ночи', 'Питание', 'Цена (руб)' );
	echo " " . str_repeat( '-', 88 ) . "\n";

	$idx = 1;
	foreach ( $tours as $tour ) {
		$hotel    = $tour['hotel_name'] ?? '';
		$date     = $tour['date'] ?? ( $tour['departure_date'] ?? '' );
		$duration = $tour['duration'] ?? ( $tour['nights'] ?? 0 );
		$meal     = $tour['meal'] ?? ( $tour['meal_code'] ?? '' );
		$price    = $tour['price_rub'] ?? ( $tour['price_rur'] ?? 0 );

		if ( empty( $hotel ) ) {
			$errors[] = "Тур #{$idx}: пустое поле hotel_name";
		}
		if ( empty( $date ) ) {
			$errors[] = "Тур #{$idx}: пустое поле date";
		}
		if ( (int) $duration < 3 ) {
			$errors[] = "Тур #{$idx}: duration < 3 ({$duration})";
		}
		if ( empty( $meal ) ) {
			$errors[] = "Тур #{$idx}: пустое поле meal";
		}
		if ( (int) $price <= 0 ) {
			$errors[] = "Тур #{$idx}: price_rub <= 0 ({$price})";
		}

		$hotel_display = function_exists( 'mb_strimwidth' ) ? mb_strimwidth( $hotel, 0, 32, '...' ) : ( strlen( $hotel ) > 32 ? substr( $hotel, 0, 29 ) . '...' : $hotel );
		$meal_display  = function_exists( 'mb_strimwidth' ) ? mb_strimwidth( $meal, 0, 16, '...' ) : ( strlen( $meal ) > 16 ? substr( $meal, 0, 13 ) . '...' : $meal );

		printf(
			" %-4d | %-32s | %-12s | %-6d | %-16s | %-10s\n",
			$idx,
			$hotel_display,
			$date,
			(int) $duration,
			$meal_display,
			number_format( (int) $price, 0, '', ' ' ) . ' ₽'
		);
		$idx++;
	}

	echo "\n";
	if ( empty( $errors ) ) {
		echo " [PASS] Направление {$name} прошло валидацию (отобрано {$parsed_count} туров, все обязательные поля заполнены).\n\n";
		$summary[ $name ] = "PASS ({$parsed_count} туров)";
	} else {
		echo " [FAIL] Ошибки валидации для {$name}:\n";
		foreach ( $errors as $err ) {
			echo "   - {$err}\n";
		}
		echo "\n";
		$summary[ $name ] = "FAIL (" . implode( '; ', $errors ) . ")";
		$all_passed       = false;
	}
}

echo "========================================================================================\n";
echo "                               ИТОГОВЫЙ ОТЧЕТ ТЕСТИРОВАНИЯ\n";
echo "========================================================================================\n";
foreach ( $summary as $c_name => $res ) {
	printf( " %-20s : %s\n", $c_name, $res );
}
echo "========================================================================================\n";

if ( $all_passed ) {
	echo "\n ВСЕ ТЕСТЫ УСПЕШНО ПРОЙДЕНЫ (EXIT CODE 0)\n\n";
	exit( 0 );
} else {
	echo "\n ТЕСТЫ ЗАВЕРШИЛИСЬ С ОШИБКАМИ (EXIT CODE 1)\n\n";
	exit( 1 );
}
