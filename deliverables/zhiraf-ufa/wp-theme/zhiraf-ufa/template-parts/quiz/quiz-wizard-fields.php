<?php
/**
 * Shared 4-step quiz wizard (inline + modal).
 *
 * @package Zhiraf_Ufa
 * @var string $quiz_form_id Form element id attribute.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$quiz_form_id   = ! empty( $quiz_form_id ) ? (string) $quiz_form_id : 'quiz-form';
$destinations   = zhiraf_ufa_home_quiz_destinations();
$is_modal       = ( 'quiz-form' === $quiz_form_id );
$progress_id    = $is_modal ? 'quiz-modal-progress-bar' : 'quiz-progress-bar';
$title_id       = $is_modal ? 'quiz-modal-step-title' : 'quiz-step-title';
$percent_id     = $is_modal ? 'quiz-modal-step-percent' : 'quiz-step-percent';
?>
<form
	id="<?php echo esc_attr( $quiz_form_id ); ?>"
	class="quiz-form quiz-wizard js-quiz-wizard zhiraf-form"
	data-action="zhiraf_quiz"
	novalidate
>
	<input type="hidden" name="vibe" value="" />
	<input type="hidden" name="quiz_wishes" value="" />
	<input type="hidden" name="quiz_month" value="" />
	<input type="hidden" name="quiz_budget" value="" />
	<input type="hidden" name="name" value="<?php esc_attr_e( 'Гость сайта', 'zhiraf-ufa' ); ?>" />

	<div class="quiz-wizard__progress-head">
		<span class="quiz-wizard__progress-title" id="<?php echo esc_attr( $title_id ); ?>" data-quiz-progress-label>
			<?php esc_html_e( 'Шаг 1 из 4: Куда планируете полететь?', 'zhiraf-ufa' ); ?>
		</span>
		<span class="quiz-wizard__progress-percent" id="<?php echo esc_attr( $percent_id ); ?>" data-quiz-progress-percent>25%</span>
	</div>
	<div class="quiz-wizard__progress-track" aria-hidden="true">
		<div class="quiz-wizard__progress-bar" id="<?php echo esc_attr( $progress_id ); ?>" style="width:25%"></div>
	</div>

	<div class="quiz-step is-active" data-step="1" data-step-title="<?php esc_attr_e( 'Куда планируете полететь?', 'zhiraf-ufa' ); ?>">
		<h3 class="quiz-step__title"><?php esc_html_e( 'Выберите приоритетное направление:', 'zhiraf-ufa' ); ?></h3>
		<div class="quiz-tiles quiz-tiles--dest">
			<?php foreach ( $destinations as $i => $dest ) : ?>
				<label class="quiz-tile quiz-tile--dest">
					<input type="radio" name="quiz_destination" value="<?php echo esc_attr( $dest['value'] ); ?>" <?php checked( 0 === $i ); ?> required />
					<span class="quiz-tile__inner">
						<span class="quiz-tile__emoji" aria-hidden="true"><?php echo esc_html( $dest['emoji'] ); ?></span>
						<span class="quiz-tile__label"><?php echo esc_html( $dest['label'] ); ?></span>
						<span class="quiz-tile__subtitle"><?php echo esc_html( $dest['subtitle'] ); ?></span>
					</span>
				</label>
			<?php endforeach; ?>
		</div>
		<div class="quiz-step__nav quiz-step__nav--end">
			<button type="button" class="btn btn--primary quiz-step__next" data-quiz-go="2"><?php esc_html_e( 'Далее →', 'zhiraf-ufa' ); ?></button>
		</div>
	</div>

	<div class="quiz-step" data-step="2" data-step-title="<?php esc_attr_e( 'В каком составе летите?', 'zhiraf-ufa' ); ?>" hidden>
		<h3 class="quiz-step__title"><?php esc_html_e( 'В каком составе отправляетесь в поездку?', 'zhiraf-ufa' ); ?></h3>
		<div class="quiz-tiles quiz-tiles--travelers">
			<label class="quiz-tile quiz-tile--row">
				<input type="radio" name="quiz_composition" value="<?php esc_attr_e( 'Семья с детьми', 'zhiraf-ufa' ); ?>" required />
				<span class="quiz-tile__inner">
					<span class="quiz-tile__emoji">👨‍👩‍👧</span>
					<span class="quiz-tile__text">
						<span class="quiz-tile__label"><?php esc_html_e( 'Семья с детьми', 'zhiraf-ufa' ); ?></span>
						<span class="quiz-tile__subtitle"><?php esc_html_e( 'Важен пологий пляж и детское меню', 'zhiraf-ufa' ); ?></span>
					</span>
				</span>
			</label>
			<label class="quiz-tile quiz-tile--row">
				<input type="radio" name="quiz_composition" value="<?php esc_attr_e( 'Пара', 'zhiraf-ufa' ); ?>" />
				<span class="quiz-tile__inner">
					<span class="quiz-tile__emoji">👩‍❤️‍👨</span>
					<span class="quiz-tile__text">
						<span class="quiz-tile__label"><?php esc_html_e( 'Вдвоём / Романтический', 'zhiraf-ufa' ); ?></span>
						<span class="quiz-tile__subtitle"><?php esc_html_e( 'Спокойная атмосфера, без лишнего шума', 'zhiraf-ufa' ); ?></span>
					</span>
				</span>
			</label>
			<label class="quiz-tile quiz-tile--row">
				<input type="radio" name="quiz_composition" value="<?php esc_attr_e( 'Компания друзей', 'zhiraf-ufa' ); ?>" />
				<span class="quiz-tile__inner">
					<span class="quiz-tile__emoji">👯</span>
					<span class="quiz-tile__text">
						<span class="quiz-tile__label"><?php esc_html_e( 'Компания друзей', 'zhiraf-ufa' ); ?></span>
						<span class="quiz-tile__subtitle"><?php esc_html_e( 'Активный отдых, экскурсии', 'zhiraf-ufa' ); ?></span>
					</span>
				</span>
			</label>
			<label class="quiz-tile quiz-tile--row">
				<input type="radio" name="quiz_composition" value="<?php esc_attr_e( 'Один', 'zhiraf-ufa' ); ?>" />
				<span class="quiz-tile__inner">
					<span class="quiz-tile__emoji">🧳</span>
					<span class="quiz-tile__text">
						<span class="quiz-tile__label"><?php esc_html_e( 'Один / Соло-тур', 'zhiraf-ufa' ); ?></span>
						<span class="quiz-tile__subtitle"><?php esc_html_e( 'Перезагрузка, комфортный отель', 'zhiraf-ufa' ); ?></span>
					</span>
				</span>
			</label>
		</div>
		<div class="quiz-step__nav">
			<button type="button" class="btn btn--ghost quiz-step__back" data-quiz-go="1"><?php esc_html_e( '← Назад', 'zhiraf-ufa' ); ?></button>
			<button type="button" class="btn btn--primary quiz-step__next" data-quiz-go="3"><?php esc_html_e( 'Далее →', 'zhiraf-ufa' ); ?></button>
		</div>
	</div>

	<div class="quiz-step" data-step="3" data-step-title="<?php esc_attr_e( 'Даты вылета и длительность', 'zhiraf-ufa' ); ?>" hidden>
		<h3 class="quiz-step__title"><?php esc_html_e( 'Когда планируете вылет и на какой срок?', 'zhiraf-ufa' ); ?></h3>
		<p class="quiz-step__hint"><?php esc_html_e( 'Примерные даты вылета:', 'zhiraf-ufa' ); ?></p>
		<div class="quiz-chips">
			<label class="quiz-chip"><input type="radio" name="quiz_timing" value="<?php esc_attr_e( 'Ближайшие 7–14 дней', 'zhiraf-ufa' ); ?>" required /> <?php esc_html_e( 'Ближайшие 7–14 дней', 'zhiraf-ufa' ); ?></label>
			<label class="quiz-chip"><input type="radio" name="quiz_timing" value="<?php esc_attr_e( 'В следующем месяце', 'zhiraf-ufa' ); ?>" /> <?php esc_html_e( 'В следующем месяце', 'zhiraf-ufa' ); ?></label>
			<label class="quiz-chip"><input type="radio" name="quiz_timing" value="<?php esc_attr_e( 'Через 2–4 месяца', 'zhiraf-ufa' ); ?>" /> <?php esc_html_e( 'Через 2–4 месяца', 'zhiraf-ufa' ); ?></label>
		</div>
		<p class="quiz-step__hint"><?php esc_html_e( 'Длительность отдыха:', 'zhiraf-ufa' ); ?></p>
		<div class="quiz-chips quiz-chips--thirds">
			<label class="quiz-chip"><input type="radio" name="quiz_duration" value="<?php esc_attr_e( '7–9 ночей', 'zhiraf-ufa' ); ?>" required /> <?php esc_html_e( '7–9 ночей', 'zhiraf-ufa' ); ?></label>
			<label class="quiz-chip"><input type="radio" name="quiz_duration" value="<?php esc_attr_e( '10–12 ночей', 'zhiraf-ufa' ); ?>" /> <?php esc_html_e( '10–12 ночей', 'zhiraf-ufa' ); ?></label>
			<label class="quiz-chip"><input type="radio" name="quiz_duration" value="<?php esc_attr_e( '14+ ночей', 'zhiraf-ufa' ); ?>" /> <?php esc_html_e( '14+ ночей', 'zhiraf-ufa' ); ?></label>
		</div>
		<div class="quiz-step__nav">
			<button type="button" class="btn btn--ghost quiz-step__back" data-quiz-go="2"><?php esc_html_e( '← Назад', 'zhiraf-ufa' ); ?></button>
			<button type="button" class="btn btn--primary quiz-step__next" data-quiz-go="4"><?php esc_html_e( 'Далее →', 'zhiraf-ufa' ); ?></button>
		</div>
	</div>

	<div class="quiz-step quiz-step--final" data-step="4" data-step-title="<?php esc_attr_e( 'Получение подборки', 'zhiraf-ufa' ); ?>" hidden>
		<div class="quiz-final-head">
			<span class="quiz-final-head__icon" aria-hidden="true">✓</span>
			<h3 class="quiz-step__title"><?php esc_html_e( 'Параметры приняты! Куда прислать расчёт?', 'zhiraf-ufa' ); ?></h3>
			<p class="quiz-final-head__lead"><?php esc_html_e( 'Подготовим 2–4 проверенных отеля с прямым рейсом из UFA, честными ценами и фото пляжей.', 'zhiraf-ufa' ); ?></p>
		</div>
		<p class="quiz-step__hint"><?php esc_html_e( 'Где вам удобнее получить подборку:', 'zhiraf-ufa' ); ?></p>
		<div class="quiz-channels">
			<label class="quiz-channel quiz-channel--wa"><input type="radio" name="quiz_channel" value="WhatsApp" checked required /> WhatsApp</label>
			<label class="quiz-channel quiz-channel--tg"><input type="radio" name="quiz_channel" value="Telegram" /> Telegram</label>
		</div>
		<label class="form-field quiz-phone-field" for="<?php echo esc_attr( $quiz_form_id ); ?>-phone">
			<span><?php esc_html_e( 'Номер телефона:', 'zhiraf-ufa' ); ?></span>
			<input type="tel" id="<?php echo esc_attr( $quiz_form_id ); ?>-phone" name="phone" class="quiz-phone-input" placeholder="+7 (___) ___-__-__" required autocomplete="tel" inputmode="tel" />
		</label>
		<button type="submit" class="btn btn--primary btn--lg btn--block quiz-submit-btn" data-quiz-submit-label>
			<?php esc_html_e( 'Получить расчёт туров в мессенджер →', 'zhiraf-ufa' ); ?>
		</button>
		<p class="quiz-trust"><?php esc_html_e( 'Нажимая кнопку, вы соглашаетесь с обработкой персональных данных в соответствии с 152-ФЗ. Без спама и навязчивых звонков.', 'zhiraf-ufa' ); ?></p>
		<?php zhiraf_ufa_consent_fields( 'quiz-' . $quiz_form_id ); ?>
		<p class="form-status" role="status" aria-live="polite"></p>
		<button type="button" class="btn btn--ghost quiz-step__back quiz-step__back--solo" data-quiz-go="3"><?php esc_html_e( '← Изменить параметры', 'zhiraf-ufa' ); ?></button>
	</div>
</form>
