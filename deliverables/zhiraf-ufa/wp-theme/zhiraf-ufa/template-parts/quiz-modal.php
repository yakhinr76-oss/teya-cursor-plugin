<?php
/**
 * Multi-step quiz modal.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="quiz-modal" class="quiz-modal" role="dialog" aria-modal="true" aria-labelledby="quiz-title" hidden>
	<div class="quiz-modal__backdrop" data-quiz-close tabindex="-1"></div>
	<div class="quiz-modal__panel">
		<button type="button" class="quiz-modal__close" data-quiz-close aria-label="<?php esc_attr_e( 'Закрыть', 'zhiraf-ufa' ); ?>">×</button>
		<h2 id="quiz-title" class="quiz-modal__title"><?php esc_html_e( 'Подбор тура за 2 минуты', 'zhiraf-ufa' ); ?></h2>
		<form id="quiz-form" class="quiz-form" novalidate>
			<input type="hidden" name="vibe" id="quiz-vibe-field" value="" />
			<div class="quiz-step is-active" data-step="1">
				<p class="quiz-step__label"><?php esc_html_e( 'Шаг 1 из 4', 'zhiraf-ufa' ); ?></p>
				<h3><?php esc_html_e( 'Состав путешественников', 'zhiraf-ufa' ); ?></h3>
				<div class="quiz-options">
					<label><input type="radio" name="quiz_composition" value="Пара" required /> <?php esc_html_e( 'Пара', 'zhiraf-ufa' ); ?></label>
					<label><input type="radio" name="quiz_composition" value="Семья с детьми" /> <?php esc_html_e( 'Семья с детьми', 'zhiraf-ufa' ); ?></label>
					<label><input type="radio" name="quiz_composition" value="Один" /> <?php esc_html_e( 'Один', 'zhiraf-ufa' ); ?></label>
					<label><input type="radio" name="quiz_composition" value="Компания друзей" /> <?php esc_html_e( 'Компания друзей', 'zhiraf-ufa' ); ?></label>
				</div>
				<button type="button" class="btn btn--primary quiz-next"><?php esc_html_e( 'Далее', 'zhiraf-ufa' ); ?></button>
			</div>
			<div class="quiz-step" data-step="2" hidden>
				<p class="quiz-step__label"><?php esc_html_e( 'Шаг 2 из 4', 'zhiraf-ufa' ); ?></p>
				<h3><?php esc_html_e( 'Желаемый месяц поездки', 'zhiraf-ufa' ); ?></h3>
				<select name="quiz_month" required>
					<option value=""><?php esc_html_e( 'Выберите месяц', 'zhiraf-ufa' ); ?></option>
					<?php
					$months = array( 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' );
					foreach ( $months as $m ) {
						printf( '<option value="%s">%s</option>', esc_attr( $m ), esc_html( $m ) );
					}
					?>
				</select>
				<div class="quiz-nav">
					<button type="button" class="btn btn--ghost quiz-prev"><?php esc_html_e( 'Назад', 'zhiraf-ufa' ); ?></button>
					<button type="button" class="btn btn--primary quiz-next"><?php esc_html_e( 'Далее', 'zhiraf-ufa' ); ?></button>
				</div>
			</div>
			<div class="quiz-step" data-step="3" hidden>
				<p class="quiz-step__label"><?php esc_html_e( 'Шаг 3 из 4', 'zhiraf-ufa' ); ?></p>
				<h3><?php esc_html_e( 'Ориентировочный бюджет на человека', 'zhiraf-ufa' ); ?></h3>
				<div class="quiz-options">
					<label><input type="radio" name="quiz_budget" value="до 80 000 ₽" required /> <?php esc_html_e( 'до 80 000 ₽', 'zhiraf-ufa' ); ?></label>
					<label><input type="radio" name="quiz_budget" value="80 000 – 120 000 ₽" /> <?php esc_html_e( '80 000 – 120 000 ₽', 'zhiraf-ufa' ); ?></label>
					<label><input type="radio" name="quiz_budget" value="120 000 – 180 000 ₽" /> <?php esc_html_e( '120 000 – 180 000 ₽', 'zhiraf-ufa' ); ?></label>
					<label><input type="radio" name="quiz_budget" value="выше 180 000 ₽" /> <?php esc_html_e( 'выше 180 000 ₽', 'zhiraf-ufa' ); ?></label>
				</div>
				<div class="quiz-nav">
					<button type="button" class="btn btn--ghost quiz-prev"><?php esc_html_e( 'Назад', 'zhiraf-ufa' ); ?></button>
					<button type="button" class="btn btn--primary quiz-next"><?php esc_html_e( 'Далее', 'zhiraf-ufa' ); ?></button>
				</div>
			</div>
			<div class="quiz-step" data-step="4" hidden>
				<p class="quiz-step__label"><?php esc_html_e( 'Шаг 4 из 4', 'zhiraf-ufa' ); ?></p>
				<h3><?php esc_html_e( 'Пожелания и контакты', 'zhiraf-ufa' ); ?></h3>
				<label class="form-field">
					<span><?php esc_html_e( 'Направление или пожелания', 'zhiraf-ufa' ); ?></span>
					<textarea name="quiz_wishes" rows="3" placeholder="<?php esc_attr_e( 'Турция, всё включено, спокойный отель…', 'zhiraf-ufa' ); ?>"></textarea>
				</label>
				<label class="form-field">
					<span><?php esc_html_e( 'Имя', 'zhiraf-ufa' ); ?></span>
					<input type="text" name="name" required autocomplete="name" />
				</label>
				<label class="form-field">
					<span><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span>
					<input type="tel" name="phone" required autocomplete="tel" />
				</label>
				<?php zhiraf_ufa_consent_fields( 'quiz' ); ?>
				<div class="quiz-nav">
					<button type="button" class="btn btn--ghost quiz-prev"><?php esc_html_e( 'Назад', 'zhiraf-ufa' ); ?></button>
					<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Отправить', 'zhiraf-ufa' ); ?></button>
				</div>
				<p class="form-status" role="status" aria-live="polite"></p>
			</div>
		</form>
	</div>
</div>
