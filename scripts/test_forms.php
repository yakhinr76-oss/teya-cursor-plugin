<?php
/**
 * Offline test for zhiraf-ufa form handlers (inc/forms.php) with minimal WP stubs.
 *
 * Checks: bad nonce -> JSON 403 code=nonce; honeypot -> 400; missing consent -> 400;
 * bad phone -> 400; valid lead/quiz -> lead post saved + wp_mail to admin_email & office;
 * mail failure still saves the lead and returns success; consent markup links privacy policy.
 *
 * Run: php scripts/test_forms.php
 */

define( 'ABSPATH', __DIR__ . '/' );
$theme = dirname( __DIR__ ) . '/deliverables/zhiraf-ufa/wp-theme/zhiraf-ufa';

class WP_Error {
	public $c; public $m;
	public function __construct( $c = '', $m = '' ) { $this->c = $c; $this->m = $m; }
	public function get_error_message() { return $this->m; }
}
class ZTJsonExit extends Exception {
	public $payload; public $status;
	public function __construct( $p, $s ) { $this->payload = $p; $this->status = $s; parent::__construct( 'json' ); }
}
$GLOBALS['zt'] = array( 'posts' => array(), 'meta' => array(), 'mail' => array(), 'mail_ok' => true, 'actions' => array() );
function add_action( $h, $cb ) { $GLOBALS['zt']['actions'][ $h ][] = $cb; }
function add_filter( $h, $cb ) {}
function apply_filters( $h, $v ) { return $v; }
function do_action() {}
function __( $s ) { return $s; }
function esc_html__( $s ) { return htmlspecialchars( $s ); }
function esc_html_e( $s ) { echo htmlspecialchars( $s ); }
function esc_html( $s ) { return htmlspecialchars( $s ); }
function esc_url( $s ) { return $s; }
function esc_url_raw( $s ) { return $s; }
function wp_kses_post( $s ) { return $s; }
function home_url( $p = '' ) { return 'https://b2b-tour.ru' . $p; }
function sanitize_text_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_textarea_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_email( $s ) { return filter_var( trim( (string) $s ), FILTER_SANITIZE_EMAIL ); }
function is_email( $s ) { return (bool) filter_var( $s, FILTER_VALIDATE_EMAIL ); }
function wp_unslash( $s ) { return $s; }
function is_wp_error( $x ) { return $x instanceof WP_Error; }
function wp_verify_nonce( $n, $a ) { return 'good' === $n && 'zhiraf_ufa_forms' === $a; }
function wp_create_nonce( $a ) { return 'good'; }
function nocache_headers() {}
function wp_send_json_success( $d = null, $s = 200 ) { throw new ZTJsonExit( array( 'success' => true, 'data' => $d ), $s ); }
function wp_send_json_error( $d = null, $s = 200 ) { throw new ZTJsonExit( array( 'success' => false, 'data' => $d ), $s ); }
function get_option( $k ) { return 'admin_email' === $k ? 'admin@b2b-tour.ru' : false; }
function wp_get_referer() { return 'https://b2b-tour.ru/kontakty/'; }
function wp_date( $f ) { return date( $f ); }
function wp_timezone_string() { return 'Asia/Yekaterinburg'; }
function wp_insert_post( $a ) { $GLOBALS['zt']['posts'][] = $a; return count( $GLOBALS['zt']['posts'] ); }
function update_post_meta( $id, $k, $v ) { $GLOBALS['zt']['meta'][ $id ][ $k ] = $v; }
function get_post_meta( $id, $k ) { return $GLOBALS['zt']['meta'][ $id ][ $k ] ?? ''; }
function register_post_type() {}
function wp_mail( $to, $sub, $body ) { $GLOBALS['zt']['mail'][] = compact( 'to', 'sub', 'body' ); return $GLOBALS['zt']['mail_ok']; }
function zhiraf_ufa_rate_limit_allow() { return true; }
class Zhiraf_UON_CRM { public function send_lead( $p ) { return array( 'ok' => false ); } }

require $theme . '/inc/forms.php';

$fail = 0;
function zt_call( $fn, $post ) {
	$_POST = $post;
	try { $fn(); } catch ( ZTJsonExit $e ) { return array( $e->status, $e->payload ); }
	return array( 0, null );
}
function zt_assert( $cond, $label ) { global $fail; echo ( $cond ? 'PASS ' : 'FAIL ' ) . $label . "\n"; if ( ! $cond ) { $fail++; } }

$ok = array( 'nonce' => 'good', 'name' => 'ТЕСТ', 'phone' => '+7 900 000-00-00', 'consent_pd' => '1', 'zhiraf_hp' => '' );

list( $s, $p ) = zt_call( 'zhiraf_ufa_ajax_lead', array( 'nonce' => 'bad' ) + $ok );
zt_assert( 403 === $s && 'nonce' === $p['data']['code'], 'bad nonce -> JSON 403 code=nonce' );
list( $s, $p ) = zt_call( 'zhiraf_ufa_ajax_form_nonce', array() );
zt_assert( $p['success'] && 'good' === $p['data']['nonce'], 'nonce refresh endpoint' );
list( $s ) = zt_call( 'zhiraf_ufa_ajax_lead', array( 'zhiraf_hp' => 'bot' ) + $ok );
zt_assert( 400 === $s, 'honeypot -> 400' );
$no = $ok; unset( $no['consent_pd'] );
list( $s, $p ) = zt_call( 'zhiraf_ufa_ajax_lead', $no );
zt_assert( 400 === $s && false !== strpos( $p['data']['message'], 'согласие' ), 'no 152-FZ consent -> 400' );
list( $s ) = zt_call( 'zhiraf_ufa_ajax_lead', array( 'phone' => '123' ) + $ok );
zt_assert( 400 === $s, 'short phone -> 400' );
zt_assert( 0 === count( $GLOBALS['zt']['posts'] ), 'rejected submissions are not stored' );

list( $s, $p ) = zt_call( 'zhiraf_ufa_ajax_lead', $ok );
zt_assert( 200 === $s && $p['success'], 'valid lead -> success' );
zt_assert( 1 === count( $GLOBALS['zt']['posts'] ) && 'zhiraf_lead' === $GLOBALS['zt']['posts'][0]['post_type'] && 'private' === $GLOBALS['zt']['posts'][0]['post_status'], 'lead saved as private zhiraf_lead' );
$m = end( $GLOBALS['zt']['mail'] );
zt_assert( in_array( 'admin@b2b-tour.ru', $m['to'], true ) && in_array( 'office@zhiraf-ufa.ru', $m['to'], true ), 'mail to admin_email + office' );
zt_assert( false !== strpos( $m['body'], '152-ФЗ' ) && false !== strpos( $m['body'], '+7 900 000-00-00' ), 'mail body has phone + consent' );
zt_assert( 1 === $GLOBALS['zt']['meta'][1]['_zl_mail_sent'], 'mail status meta = 1' );

$GLOBALS['zt']['mail_ok'] = false;
list( $s, $p ) = zt_call( 'zhiraf_ufa_ajax_quiz', $ok + array( 'quiz_composition' => 'Пара', 'quiz_month' => 'Ноябрь', 'quiz_budget' => 'до 80 000 ₽', 'quiz_wishes' => 'Турция' ) );
zt_assert( 200 === $s && $p['success'], 'quiz with failed mail still succeeds (lead stored)' );
zt_assert( 2 === count( $GLOBALS['zt']['posts'] ) && 0 === $GLOBALS['zt']['meta'][2]['_zl_mail_sent'], 'quiz stored, mail status meta = 0' );
zt_assert( false !== strpos( $GLOBALS['zt']['posts'][1]['post_content'], 'Ноябрь' ), 'quiz answers in lead body' );

list( $s ) = zt_call( 'zhiraf_ufa_ajax_subscribe', array( 'nonce' => 'good', 'consent_pd' => '1', 'email' => 'nope' ) );
zt_assert( 400 === $s, 'subscribe bad email -> 400' );

ob_start(); zhiraf_ufa_consent_fields(); $html = ob_get_clean();
zt_assert( false !== strpos( $html, 'name="consent_pd" value="1" required' ) && false !== strpos( $html, 'https://b2b-tour.ru/politika-konfidenczialnosti/' ) && false !== strpos( $html, 'target="_blank"' ), 'consent checkbox required + privacy link (new tab)' );
zt_assert( false === strpos( $html, 'checked' ), 'consent checkboxes unchecked by default' );
zt_assert( false !== strpos( $html, 'class="zhiraf-hp"' ), 'honeypot present' );

$css = file_get_contents( $theme . '/assets/dist/style.css' );
foreach ( array( '.quiz-modal', '.quiz-modal[hidden]', '.zhiraf-hp', '.form-check', '.form-status', '.quiz-step[hidden]' ) as $sel ) {
	zt_assert( false !== strpos( $css, $sel . ' ' ) || false !== strpos( $css, $sel . '{' ), "dist CSS has $sel" );
}
$front = file_get_contents( $theme . '/front-page.php' );
zt_assert( (bool) preg_match( '#<form class="zhiraf-form"[^>]*>(?:(?!</form>).)*form-status#s', $front ), 'home lead form status is inside <form>' );

echo $fail ? "\n$fail FAILED\n" : "\nALL PASS\n";
exit( $fail ? 1 : 0 );
