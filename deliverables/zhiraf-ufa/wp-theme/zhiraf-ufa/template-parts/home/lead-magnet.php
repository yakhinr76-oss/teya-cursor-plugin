<?php
/**
 * Lead magnet before blog (block 10).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$privacy = home_url( '/politika-konfidenczialnosti/' );
?>
<section class="section section--lead-magnet" id="lead-action">
	<div class="container lead-magnet-wrap">
		<div class="lead-magnet-banner reveal">
			<div class="lead-magnet-banner__glow" aria-hidden="true"></div>
			<div class="lead-magnet-banner__grid">
				<div class="lead-magnet__value">
					<p class="lead-magnet__badge">
						<span class="lead-magnet__badge-dot" aria-hidden="true"></span>
						<?php esc_html_e( 'Ответ эксперта из Уфы за 15 минут', 'zhiraf-ufa' ); ?>
					</p>
					<h2 class="lead-magnet__title"><?php esc_html_e( '3 лучших отеля под ваш бюджет + чек-лист по сборам в мессенджер', 'zhiraf-ufa' ); ?></h2>
					<p class="lead-magnet__text">
						<?php
						echo wp_kses_post(
							__( 'Напишите направление и сумму на двоих — тревел-эксперт из Уфы пришлёт <strong>три проверенных отеля</strong> с вылетом из UFA и <strong>готовый список документов и вещей</strong> в WhatsApp, Telegram или MAX. Без долгого перебора агрегаторов.', 'zhiraf-ufa' )
						);
						?>
					</p>
					<ul class="lead-magnet__bullets">
						<li>
							<span class="lead-magnet__check" aria-hidden="true">✓</span>
							<span>
								<strong><?php esc_html_e( 'Фиксированная цена:', 'zhiraf-ufa' ); ?></strong>
								<?php esc_html_e( ' считаем топливные сборы сразу, без сюрпризов в офисе.', 'zhiraf-ufa' ); ?>
							</span>
						</li>
						<li>
							<span class="lead-magnet__check" aria-hidden="true">✓</span>
							<span>
								<strong><?php esc_html_e( 'Без спама и навязчивых звонков:', 'zhiraf-ufa' ); ?></strong>
								<?php esc_html_e( ' расчёт приходит прямо в чат выбранного мессенджера.', 'zhiraf-ufa' ); ?>
							</span>
						</li>
						<li>
							<span class="lead-magnet__check" aria-hidden="true">✓</span>
							<span>
								<strong><?php esc_html_e( 'Личная ответственность:', 'zhiraf-ufa' ); ?></strong>
								<?php esc_html_e( ' агентство работает в Уфе с 2011 года (РТА 0012822).', 'zhiraf-ufa' ); ?>
							</span>
						</li>
					</ul>
				</div>

				<div class="lead-magnet__form-panel">
					<form
						id="lead-magnet-form"
						class="zhiraf-form lead-magnet__form"
						data-action="zhiraf_lead"
						data-form-context="home-lead-magnet"
					>
						<input type="hidden" name="phone_only" value="1" />
						<input type="hidden" name="name" value="<?php esc_attr_e( 'Гость сайта', 'zhiraf-ufa' ); ?>" />
						<input type="hidden" name="contact_channel" value="WhatsApp" />
						<input type="hidden" name="message" value="<?php esc_attr_e( '3 отеля + чек-лист сборов — блок перед блогом', 'zhiraf-ufa' ); ?>" />

						<div class="lead-magnet__form-head">
							<h3><?php esc_html_e( 'Куда прислать подборку?', 'zhiraf-ufa' ); ?></h3>
						</div>

						<?php
						zhiraf_ufa_render_messenger_picker(
							array(
								'name'    => 'lead_messenger',
								'default' => 'WhatsApp',
							)
						);
						?>

						<label class="form-field lead-magnet__field" for="lead-destination">
							<span><?php esc_html_e( 'Куда или на какой бюджет ориентируетесь?', 'zhiraf-ufa' ); ?></span>
							<input
								type="text"
								id="lead-destination"
								name="destination"
								placeholder="<?php esc_attr_e( 'Например: Турция с детьми до 200 000 ₽', 'zhiraf-ufa' ); ?>"
								class="lead-magnet__input"
								autocomplete="off"
							/>
						</label>

						<label class="form-field lead-magnet__field" for="lead-phone">
							<span><?php esc_html_e( 'Ваш номер телефона:', 'zhiraf-ufa' ); ?> <span class="lead-magnet__required">*</span></span>
							<input
								type="tel"
								id="lead-phone"
								name="phone"
								class="lead-magnet__phone lead-magnet__input"
								placeholder="+7 (___) ___-__-__"
								required
								autocomplete="tel"
								inputmode="tel"
								data-phone-mask
							/>
						</label>

						<button type="submit" class="btn btn--primary btn--lg btn--block lead-magnet__submit" data-messenger-submit-label>
							<?php esc_html_e( 'Получить подборку отелей в WhatsApp →', 'zhiraf-ufa' ); ?>
						</button>

						<p class="lead-magnet__legal">
							<?php
							printf(
								wp_kses_post( __( 'Нажимая кнопку, вы подтверждаете согласие на обработку персональных данных согласно <a href="%s">152-ФЗ</a>. Никакого спама и холодных звонков.', 'zhiraf-ufa' ) ),
								esc_url( $privacy )
							);
							?>
						</p>
						<?php zhiraf_ufa_consent_fields( 'home-lead-magnet' ); ?>
						<p class="form-status" role="status" aria-live="polite"></p>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
