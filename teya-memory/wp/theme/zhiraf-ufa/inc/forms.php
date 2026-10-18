<?php
/**
 * Form handlers (lead, subscribe, quiz).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared honeypot check.
 *
 * @return bool True if spam.
 */
function zhiraf_ufa_is_honeypot_spam() {
	$hp = isset( $_POST['zhiraf_hp'] ) ? sanitize_text_field( wp_unslash( $_POST['zhiraf_hp'] ) ) : '';
	return '' !== $hp;
}

/**
 * Consent checkboxes (152-FZ required + optional marketing).
 *
 * @return true|WP_Error
 */
function zhiraf_ufa_validate_consents() {
	if ( empty( $_POST['consent_pd'] ) ) {
		return new WP_Error( 'consent', __( 'Необходимо согласие на обработку персональных данных.', 'zhiraf-ufa' ) );
	}
	return true;
}

/**
 * Build email body from POST.
 *
 * @param array<string,string> $fields Allowed fields.
 * @return string
 */
function zhiraf_ufa_format_mail_body( $fields ) {
	$lines = array( 'Заявка с сайта «Жираф»', '---' );
	foreach ( $fields as $key => $label ) {
		if ( isset( $_POST[ $key ] ) ) {
			$val = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
			$lines[] = $label . ': ' . $val;
		}
	}
	if ( ! empty( $_POST['consent_marketing'] ) ) {
		$lines[] = 'Маркетинговые рассылки: согласие дано';
	}
	$lines[] = 'Время: ' . gmdate( 'Y-m-d H:i:s' ) . ' UTC';
	return implode( "\n", $lines );
}

/**
 * Send mail to office.
 *
 * @param string $subject Subject.
 * @param string $body    Body.
 * @return bool
 */
function zhiraf_ufa_send_office_mail( $subject, $body ) {
	$to      = 'office@zhiraf-ufa.ru';
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	return wp_mail( $to, $subject, $body, $headers );
}

/**
 * AJAX: lead form.
 */
function zhiraf_ufa_ajax_lead() {
	check_ajax_referer( 'zhiraf_ufa_forms', 'nonce' );

	if ( zhiraf_ufa_is_honeypot_spam() || ! zhiraf_ufa_rate_limit_allow( 'lead', 8, 900 ) ) {
		wp_send_json_error( array( 'message' => __( 'Не удалось отправить. Попробуйте позже.', 'zhiraf-ufa' ) ), 400 );
	}

	$consent = zhiraf_ufa_validate_consents();
	if ( is_wp_error( $consent ) ) {
		wp_send_json_error( array( 'message' => $consent->get_error_message() ), 400 );
	}

	$body = zhiraf_ufa_format_mail_body(
		array(
			'name'    => 'Имя',
			'phone'   => 'Телефон',
			'email'   => 'Email',
			'message' => 'Сообщение',
		)
	);

	$sent = zhiraf_ufa_send_office_mail( 'Заявка на подбор тура', $body );
	if ( $sent ) {
		$crm = new Zhiraf_UON_CRM();
		$crm->send_lead( $_POST );
		wp_send_json_success( array( 'message' => __( 'Спасибо! Менеджер свяжется с вами.', 'zhiraf-ufa' ) ) );
	}
	wp_send_json_error( array( 'message' => __( 'Ошибка отправки. Позвоните нам: +7 901 810-50-18', 'zhiraf-ufa' ) ), 500 );
}
add_action( 'wp_ajax_zhiraf_lead', 'zhiraf_ufa_ajax_lead' );
add_action( 'wp_ajax_nopriv_zhiraf_lead', 'zhiraf_ufa_ajax_lead' );

/**
 * AJAX: subscribe.
 */
function zhiraf_ufa_ajax_subscribe() {
	check_ajax_referer( 'zhiraf_ufa_forms', 'nonce' );

	if ( zhiraf_ufa_is_honeypot_spam() || ! zhiraf_ufa_rate_limit_allow( 'subscribe', 5, 900 ) ) {
		wp_send_json_error( array( 'message' => __( 'Не удалось подписаться.', 'zhiraf-ufa' ) ), 400 );
	}

	$consent = zhiraf_ufa_validate_consents();
	if ( is_wp_error( $consent ) ) {
		wp_send_json_error( array( 'message' => $consent->get_error_message() ), 400 );
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Укажите корректный email.', 'zhiraf-ufa' ) ), 400 );
	}

	$body = zhiraf_ufa_format_mail_body( array( 'email' => 'Email подписки' ) );
	$sent = zhiraf_ufa_send_office_mail( 'Подписка на рассылку', $body );
	if ( $sent ) {
		wp_send_json_success( array( 'message' => __( 'Вы подписаны на новости.', 'zhiraf-ufa' ) ) );
	}
	wp_send_json_error( array( 'message' => __( 'Ошибка отправки.', 'zhiraf-ufa' ) ), 500 );
}
add_action( 'wp_ajax_zhiraf_subscribe', 'zhiraf_ufa_ajax_subscribe' );
add_action( 'wp_ajax_nopriv_zhiraf_subscribe', 'zhiraf_ufa_ajax_subscribe' );

/**
 * AJAX: quiz modal.
 */
function zhiraf_ufa_ajax_quiz() {
	check_ajax_referer( 'zhiraf_ufa_forms', 'nonce' );

	if ( zhiraf_ufa_is_honeypot_spam() || ! zhiraf_ufa_rate_limit_allow( 'quiz', 5, 900 ) ) {
		wp_send_json_error( array( 'message' => __( 'Не удалось отправить.', 'zhiraf-ufa' ) ), 400 );
	}

	$consent = zhiraf_ufa_validate_consents();
	if ( is_wp_error( $consent ) ) {
		wp_send_json_error( array( 'message' => $consent->get_error_message() ), 400 );
	}

	$body = zhiraf_ufa_format_mail_body(
		array(
			'name'           => 'Имя',
			'phone'          => 'Телефон',
			'quiz_composition' => 'Состав',
			'quiz_month'     => 'Месяц',
			'quiz_budget'    => 'Бюджет',
			'quiz_wishes'    => 'Пожелания',
		)
	);

	$sent = zhiraf_ufa_send_office_mail( 'Квиз: подбор тура', $body );
	if ( $sent ) {
		wp_send_json_success( array( 'message' => __( 'Заявка отправлена! Мы подберём варианты.', 'zhiraf-ufa' ) ) );
	}
	wp_send_json_error( array( 'message' => __( 'Ошибка отправки.', 'zhiraf-ufa' ) ), 500 );
}
add_action( 'wp_ajax_zhiraf_quiz', 'zhiraf_ufa_ajax_quiz' );
add_action( 'wp_ajax_nopriv_zhiraf_quiz', 'zhiraf_ufa_ajax_quiz' );

/**
 * Render consent fields (two unchecked checkboxes).
 *
 * @param string $form_id Form id prefix.
 */
function zhiraf_ufa_consent_fields( $form_id = 'zhiraf' ) {
	$privacy = home_url( '/politika-konfidenczialnosti/' );
	?>
	<div class="form-consents">
		<label class="form-check">
			<input type="checkbox" name="consent_pd" value="1" required />
			<span><?php
			printf(
				/* translators: %s: privacy policy URL */
				wp_kses_post( __( 'Согласен(на) на <a href="%s">обработку персональных данных</a> (152-ФЗ)', 'zhiraf-ufa' ) ),
				esc_url( $privacy )
			);
			?></span>
		</label>
		<label class="form-check">
			<input type="checkbox" name="consent_marketing" value="1" />
			<span><?php esc_html_e( 'Согласен(на) на получение рекламно-информационных рассылок (38-ФЗ, необязательно)', 'zhiraf-ufa' ); ?></span>
		</label>
		<label class="zhiraf-hp" aria-hidden="true">
			<span><?php esc_html_e( 'Не заполняйте', 'zhiraf-ufa' ); ?></span>
			<input type="text" name="zhiraf_hp" value="" tabindex="-1" autocomplete="off" />
		</label>
	</div>
	<?php
}
