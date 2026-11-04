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
						<?php esc_html_e( 'Экспресс-подбор тура на сезон 2026', 'zhiraf-ufa' ); ?>
					</p>
					<h2 class="lead-magnet__title"><?php esc_html_e( 'Не хотите тратить часы на ручной перебор сотен отелей?', 'zhiraf-ufa' ); ?></h2>
					<p class="lead-magnet__text">
						<?php
						echo wp_kses_post(
							__( 'Назовите направление или бюджет — тревел-эксперт из Уфы отберёт <strong>2–3 проверенных отеля</strong> с прямым вылетом из UFA, честным входом в море и пришлёт раскладку в мессенджер через 20 минут.', 'zhiraf-ufa' )
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
						<input type="hidden" name="message" value="<?php esc_attr_e( 'Экспресс-подбор отелей — блок перед блогом', 'zhiraf-ufa' ); ?>" />

						<div class="lead-magnet__form-head">
							<h3><?php esc_html_e( 'Куда прислать подборку?', 'zhiraf-ufa' ); ?></h3>
							<p><?php esc_html_e( 'Выберите удобный канал связи:', 'zhiraf-ufa' ); ?></p>
						</div>

						<div class="lead-magnet__messengers" role="radiogroup" aria-label="<?php esc_attr_e( 'Мессенджер', 'zhiraf-ufa' ); ?>">
							<label class="lead-messenger lead-messenger--wa">
								<input type="radio" name="lead_messenger" value="WhatsApp" checked />
								<span class="lead-messenger__inner"><?php esc_html_e( 'В WhatsApp', 'zhiraf-ufa' ); ?></span>
							</label>
							<label class="lead-messenger lead-messenger--tg">
								<input type="radio" name="lead_messenger" value="Telegram" />
								<span class="lead-messenger__inner"><?php esc_html_e( 'В Telegram', 'zhiraf-ufa' ); ?></span>
							</label>
						</div>

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

						<button type="submit" class="btn btn--primary btn--lg btn--block lead-magnet__submit">
							<?php esc_html_e( 'Получить подборку отелей →', 'zhiraf-ufa' ); ?>
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
