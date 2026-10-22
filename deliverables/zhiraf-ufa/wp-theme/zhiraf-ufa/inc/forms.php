<?php
/**
 * Form handlers (lead, subscribe, quiz).
 *
 * Every submission is saved as a private «zhiraf_lead» post first (wp-admin → «Заявки»),
 * then e-mailed to the site admin e-mail (Settings → General) and the office address.
 * A lead is never lost if mail delivery fails.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the private lead post type.
 */
function zhiraf_ufa_register_lead_cpt() {
	register_post_type(
		'zhiraf_lead',
		array(
			'labels'              => array(
				'name'          => __( 'Заявки', 'zhiraf-ufa' ),
				'singular_name' => __( 'Заявка', 'zhiraf-ufa' ),
				'menu_name'     => __( 'Заявки', 'zhiraf-ufa' ),
				'all_items'     => __( 'Все заявки', 'zhiraf-ufa' ),
				'edit_item'     => __( 'Заявка', 'zhiraf-ufa' ),
				'not_found'     => __( 'Заявок пока нет', 'zhiraf-ufa' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'menu_position'       => 3,
			'menu_icon'           => 'dashicons-email-alt',
			'supports'            => array( 'title', 'editor' ),
			'capability_type'     => 'post',
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'        => true,
			'rewrite'             => false,
			'query_var'           => false,
		)
	);
}
add_action( 'init', 'zhiraf_ufa_register_lead_cpt' );

/**
 * Admin list columns for leads.
 *
 * @param array<string,string> $cols Columns.
 * @return array<string,string>
 */
function zhiraf_ufa_lead_columns( $cols ) {
	return array(
		'cb'           => isset( $cols['cb'] ) ? $cols['cb'] : '',
		'title'        => __( 'Заявка', 'zhiraf-ufa' ),
		'zl_phone'     => __( 'Телефон', 'zhiraf-ufa' ),
		'zl_form'      => __( 'Форма', 'zhiraf-ufa' ),
		'zl_mail'      => __( 'Письмо', 'zhiraf-ufa' ),
		'date'         => __( 'Дата', 'zhiraf-ufa' ),
	);
}
add_filter( 'manage_zhiraf_lead_posts_columns', 'zhiraf_ufa_lead_columns' );

/**
 * Render lead columns.
 *
 * @param string $col     Column.
 * @param int    $post_id Post id.
 */
function zhiraf_ufa_lead_column_value( $col, $post_id ) {
	if ( 'zl_phone' === $col ) {
		echo esc_html( (string) get_post_meta( $post_id, '_zl_phone', true ) );
	} elseif ( 'zl_form' === $col ) {
		echo esc_html( (string) get_post_meta( $post_id, '_zl_form', true ) );
	} elseif ( 'zl_mail' === $col ) {
		echo get_post_meta( $post_id, '_zl_mail_sent', true ) ? esc_html__( 'отправлено', 'zhiraf-ufa' ) : '<strong style="color:#b32d2e">' . esc_html__( 'не отправлено', 'zhiraf-ufa' ) . '</strong>';
	}
}
add_action( 'manage_zhiraf_lead_posts_custom_column', 'zhiraf_ufa_lead_column_value', 10, 2 );

/**
 * Shared honeypot check.
 *
 * @return bool True if spam.
 */
function zhiraf_ufa_is_honeypot_spam() {
	$hp = isset( $_POST['zhiraf_hp'] ) ? sanitize_text_field( wp_unslash( $_POST['zhiraf_hp'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	return '' !== $hp;
}

/**
 * Verify the form nonce without dying (JS refreshes the nonce on cached pages and retries).
 */
function zhiraf_ufa_verify_form_nonce() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'zhiraf_ufa_forms' ) ) {
		wp_send_json_error(
			array(
				'code'    => 'nonce',
				'message' => __( 'Страница устарела. Обновите её и отправьте форму ещё раз.', 'zhiraf-ufa' ),
			),
			403
		);
	}
}

/**
 * AJAX: fresh nonce for pages served from cache.
 */
function zhiraf_ufa_ajax_form_nonce() {
	nocache_headers();
	wp_send_json_success( array( 'nonce' => wp_create_nonce( 'zhiraf_ufa_forms' ) ) );
}
add_action( 'wp_ajax_zhiraf_form_nonce', 'zhiraf_ufa_ajax_form_nonce' );
add_action( 'wp_ajax_nopriv_zhiraf_form_nonce', 'zhiraf_ufa_ajax_form_nonce' );

/**
 * Consent checkboxes (152-FZ required + optional marketing).
 *
 * @return true|WP_Error
 */
function zhiraf_ufa_validate_consents() {
	if ( empty( $_POST['consent_pd'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return new WP_Error( 'consent', __( 'Необходимо согласие на обработку персональных данных.', 'zhiraf-ufa' ) );
	}
	return true;
}

/**
 * Validate name + phone (lead and quiz).
 *
 * @return true|WP_Error
 */
function zhiraf_ufa_validate_contact() {
	$name  = isset( $_POST['name'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['name'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( '' === $name ) {
		return new WP_Error( 'name', __( 'Укажите имя.', 'zhiraf-ufa' ) );
	}
	if ( strlen( preg_replace( '/\D+/', '', $phone ) ) < 10 ) {
		return new WP_Error( 'phone', __( 'Укажите телефон полностью, например +7 900 000-00-00.', 'zhiraf-ufa' ) );
	}
	return true;
}

/**
 * Collect sanitized values for the given field map.
 *
 * @param array<string,string> $fields key => label.
 * @return array<string,string> label => value (non-empty only).
 */
function zhiraf_ufa_collect_fields( $fields ) {
	$out = array();
	foreach ( $fields as $key => $label ) {
		if ( isset( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$val = 'quiz_wishes' === $key
				? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) // phpcs:ignore WordPress.Security.NonceVerification
				: sanitize_text_field( wp_unslash( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification
			if ( '' !== $val ) {
				$out[ $label ] = mb_substr( $val, 0, 2000 );
			}
		}
	}
	return $out;
}

/**
 * Page the form was sent from (same-site referer only).
 *
 * @return string
 */
function zhiraf_ufa_lead_source_url() {
	$ref = wp_get_referer();
	if ( $ref && 0 === strpos( $ref, home_url() ) ) {
		return esc_url_raw( $ref );
	}
	return '';
}

/**
 * Build email / lead body.
 *
 * @param string               $form_label Human form name.
 * @param array<string,string> $values     label => value.
 * @return string
 */
function zhiraf_ufa_format_mail_body( $form_label, $values ) {
	$lines = array( 'Заявка с сайта «Жираф»: ' . $form_label, '---' );
	foreach ( $values as $label => $val ) {
		$lines[] = $label . ': ' . $val;
	}
	$lines[] = '---';
	$lines[] = 'Согласие на обработку ПДн (152-ФЗ): дано';
	$lines[] = 'Согласие на рассылки (38-ФЗ): ' . ( ! empty( $_POST['consent_marketing'] ) ? 'дано' : 'нет' ); // phpcs:ignore WordPress.Security.NonceVerification
	$src     = zhiraf_ufa_lead_source_url();
	if ( $src ) {
		$lines[] = 'Страница: ' . $src;
	}
	$lines[] = 'Время: ' . wp_date( 'd.m.Y H:i' ) . ' (' . wp_timezone_string() . ')';
	return implode( "\n", $lines );
}

/**
 * Lead recipients: site admin e-mail + office (filterable).
 *
 * @return string[]
 */
function zhiraf_ufa_lead_recipients() {
	$list = array( get_option( 'admin_email' ), 'office@zhiraf-ufa.ru' );
	$list = apply_filters( 'zhiraf_ufa_lead_recipients', $list );
	return array_values( array_unique( array_filter( array_map( 'sanitize_email', (array) $list ), 'is_email' ) ) );
}

/**
 * Save lead as private post, then mail it. Returns post id (0 on DB failure).
 *
 * @param string               $form_key   lead|quiz|subscribe.
 * @param string               $form_label Human form name.
 * @param array<string,string> $values     label => value.
 * @param string               $phone      Phone (meta).
 * @return array{id:int,mail:bool}
 */
function zhiraf_ufa_store_and_mail_lead( $form_key, $form_label, $values, $phone = '' ) {
	$body  = zhiraf_ufa_format_mail_body( $form_label, $values );
	$who   = isset( $values['Имя'] ) ? $values['Имя'] : ( isset( $values['Email'] ) ? $values['Email'] : '' );
	$title = $form_label . ( $who ? ' — ' . $who : '' ) . ( $phone ? ', ' . $phone : '' );

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'zhiraf_lead',
			'post_status'  => 'private',
			'post_title'   => mb_substr( $title, 0, 190 ),
			'post_content' => $body,
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		$post_id = 0;
	}
	if ( $post_id ) {
		update_post_meta( $post_id, '_zl_form', $form_key );
		update_post_meta( $post_id, '_zl_phone', $phone );
		update_post_meta( $post_id, '_zl_consent_pd', 1 );
		update_post_meta( $post_id, '_zl_consent_marketing', ! empty( $_POST['consent_marketing'] ) ? 1 : 0 ); // phpcs:ignore WordPress.Security.NonceVerification
		update_post_meta( $post_id, '_zl_source', zhiraf_ufa_lead_source_url() );
	}

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	$sent    = (bool) wp_mail( zhiraf_ufa_lead_recipients(), 'Сайт «Жираф»: ' . $form_label . ( $phone ? ' ' . $phone : '' ), $body, $headers );
	if ( $post_id ) {
		update_post_meta( $post_id, '_zl_mail_sent', $sent ? 1 : 0 );
	}
	do_action( 'zhiraf_ufa_lead_saved', $post_id, $form_key, $values );
	return array(
		'id'   => (int) $post_id,
		'mail' => $sent,
	);
}

/**
 * Common guard: nonce, honeypot, rate limit, consent.
 *
 * @param string $action Rate limit key.
 * @param int    $limit  Max per window.
 */
function zhiraf_ufa_form_guard( $action, $limit ) {
	zhiraf_ufa_verify_form_nonce();
	if ( zhiraf_ufa_is_honeypot_spam() ) {
		wp_send_json_error( array( 'message' => __( 'Не удалось отправить. Позвоните нам: +7 901 810-50-18', 'zhiraf-ufa' ) ), 400 );
	}
	if ( ! zhiraf_ufa_rate_limit_allow( $action, $limit, 900 ) ) {
		wp_send_json_error( array( 'message' => __( 'Слишком много заявок подряд. Попробуйте через 15 минут или позвоните: +7 901 810-50-18', 'zhiraf-ufa' ) ), 429 );
	}
	$consent = zhiraf_ufa_validate_consents();
	if ( is_wp_error( $consent ) ) {
		wp_send_json_error( array( 'message' => $consent->get_error_message() ), 400 );
	}
}

/**
 * Finish response after store+mail.
 *
 * @param array{id:int,mail:bool} $res     Result.
 * @param string                  $success Success message.
 */
function zhiraf_ufa_form_respond( $res, $success ) {
	if ( $res['id'] || $res['mail'] ) {
		wp_send_json_success( array( 'message' => $success ) );
	}
	wp_send_json_error( array( 'message' => __( 'Ошибка отправки. Позвоните нам: +7 901 810-50-18', 'zhiraf-ufa' ) ), 500 );
}

/**
 * AJAX: lead form.
 */
function zhiraf_ufa_ajax_lead() {
	zhiraf_ufa_form_guard( 'lead', 8 );
	$contact = zhiraf_ufa_validate_contact();
	if ( is_wp_error( $contact ) ) {
		wp_send_json_error( array( 'message' => $contact->get_error_message() ), 400 );
	}
	$values = zhiraf_ufa_collect_fields(
		array(
			'name'             => 'Имя',
			'phone'            => 'Телефон',
			'email'            => 'Email',
			'message'          => 'Сообщение',
			'tour'             => 'Тур',
			'contact_channel'  => 'Куда отправить подборку',
		)
	);
	$res = zhiraf_ufa_store_and_mail_lead( 'lead', 'Заявка на подбор тура', $values, isset( $values['Телефон'] ) ? $values['Телефон'] : '' );
	if ( $res['id'] && class_exists( 'Zhiraf_UON_CRM' ) ) {
		$crm = new Zhiraf_UON_CRM();
		$crm->send_lead( $values );
	}
	zhiraf_ufa_form_respond( $res, __( 'Спасибо! Заявка принята, менеджер перезвонит в рабочее время.', 'zhiraf-ufa' ) );
}
add_action( 'wp_ajax_zhiraf_lead', 'zhiraf_ufa_ajax_lead' );
add_action( 'wp_ajax_nopriv_zhiraf_lead', 'zhiraf_ufa_ajax_lead' );

/**
 * AJAX: subscribe.
 */
function zhiraf_ufa_ajax_subscribe() {
	zhiraf_ufa_form_guard( 'subscribe', 5 );
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Укажите корректный email.', 'zhiraf-ufa' ) ), 400 );
	}
	$res = zhiraf_ufa_store_and_mail_lead( 'subscribe', 'Подписка на рассылку', array( 'Email' => $email ) );
	zhiraf_ufa_form_respond( $res, __( 'Вы подписаны на новости.', 'zhiraf-ufa' ) );
}
add_action( 'wp_ajax_zhiraf_subscribe', 'zhiraf_ufa_ajax_subscribe' );
add_action( 'wp_ajax_nopriv_zhiraf_subscribe', 'zhiraf_ufa_ajax_subscribe' );

/**
 * AJAX: quiz modal.
 */
function zhiraf_ufa_ajax_quiz() {
	zhiraf_ufa_form_guard( 'quiz', 5 );
	$contact = zhiraf_ufa_validate_contact();
	if ( is_wp_error( $contact ) ) {
		wp_send_json_error( array( 'message' => $contact->get_error_message() ), 400 );
	}
	$values = zhiraf_ufa_collect_fields(
		array(
			'name'             => 'Имя',
			'phone'            => 'Телефон',
			'quiz_composition' => 'Состав',
			'quiz_month'       => 'Месяц',
			'quiz_budget'      => 'Бюджет на человека',
			'quiz_wishes'      => 'Пожелания',
		)
	);
	$res = zhiraf_ufa_store_and_mail_lead( 'quiz', 'Квиз: подбор тура', $values, isset( $values['Телефон'] ) ? $values['Телефон'] : '' );
	zhiraf_ufa_form_respond( $res, __( 'Заявка отправлена! Мы подберём варианты и перезвоним.', 'zhiraf-ufa' ) );
}
add_action( 'wp_ajax_zhiraf_quiz', 'zhiraf_ufa_ajax_quiz' );
add_action( 'wp_ajax_nopriv_zhiraf_quiz', 'zhiraf_ufa_ajax_quiz' );

/**
 * Render consent fields (two unchecked checkboxes) + hidden honeypot.
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
				wp_kses_post( __( 'Я даю согласие на обработку моих персональных данных в соответствии с <a href="%s" target="_blank" rel="noopener">Политикой конфиденциальности</a> (152-ФЗ)', 'zhiraf-ufa' ) ),
				esc_url( $privacy )
			);
			?></span>
		</label>
		<label class="form-check">
			<input type="checkbox" name="consent_marketing" value="1" />
			<span><?php esc_html_e( 'Согласен(на) на получение рекламно-информационных рассылок (38-ФЗ, необязательно)', 'zhiraf-ufa' ); ?></span>
		</label>
		<div class="zhiraf-hp" aria-hidden="true">
			<label><?php esc_html_e( 'Не заполняйте', 'zhiraf-ufa' ); ?>
			<input type="text" name="zhiraf_hp" value="" tabindex="-1" autocomplete="off" /></label>
		</div>
	</div>
	<?php
}
