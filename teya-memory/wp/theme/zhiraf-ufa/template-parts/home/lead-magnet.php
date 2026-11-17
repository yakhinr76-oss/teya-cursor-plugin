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
						<?php esc_html_e( 'PDF-гид · бесплатно', 'zhiraf-ufa' ); ?>
					</p>
					<h2 class="lead-magnet__title"><?php esc_html_e( 'Скачайте гид туриста из Уфы 2026: сравнение 5 главных стран', 'zhiraf-ufa' ); ?></h2>
					<p class="lead-magnet__text">
						<?php
						echo wp_kses_post(
							__( 'Актуальные цены под ключ с прямым вылетом из Уфы, подборка отелей с <strong>пологим песчаным входом для детей</strong> и расписание чартеров без задержек. Пришлём файл в мессенджер <strong>за 1 минуту</strong> — без звонков и спама.', 'zhiraf-ufa' )
						);
						?>
					</p>
					<ul class="lead-magnet__bullets">
						<li>
							<span class="lead-magnet__check" aria-hidden="true">✓</span>
							<span>
								<strong><?php esc_html_e( '5 стран:', 'zhiraf-ufa' ); ?></strong>
								<?php esc_html_e( ' Турция, ОАЭ, Египет, Таиланд и Россия (Сочи) — в одном сравнении.', 'zhiraf-ufa' ); ?>
							</span>
						</li>
						<li>
							<span class="lead-magnet__check" aria-hidden="true">✓</span>
							<span>
								<strong><?php esc_html_e( 'Без навязчивых звонков:', 'zhiraf-ufa' ); ?></strong>
								<?php esc_html_e( ' гид приходит в чат, который выберете ниже.', 'zhiraf-ufa' ); ?>
							</span>
						</li>
						<li>
							<span class="lead-magnet__check" aria-hidden="true">✓</span>
							<span>
								<strong><?php esc_html_e( 'Агентство в Уфе с 2011 года', 'zhiraf-ufa' ); ?></strong>
								<?php esc_html_e( ' (РТА 0012822).', 'zhiraf-ufa' ); ?>
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
						<input type="hidden" name="message" value="<?php esc_attr_e( 'PDF-гид туриста из Уфы 2026', 'zhiraf-ufa' ); ?>" />

						<div class="lead-magnet__form-head">
							<h3><?php esc_html_e( 'Куда прислать PDF-гид?', 'zhiraf-ufa' ); ?></h3>
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
							<span><?php esc_html_e( 'На какую страну смотрите в первую очередь? (необязательно)', 'zhiraf-ufa' ); ?></span>
							<input
								type="text"
								id="lead-destination"
								name="destination"
								placeholder="<?php esc_attr_e( 'Например: Турция с детьми', 'zhiraf-ufa' ); ?>"
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
							<?php esc_html_e( 'Получить PDF-гид в WhatsApp →', 'zhiraf-ufa' ); ?>
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
