<?php
/**
 * Multi-step quiz modal.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$quiz_form_id = 'quiz-form';
?>
<div id="quiz-modal" class="quiz-modal" role="dialog" aria-modal="true" aria-labelledby="quiz-title" hidden inert>
	<div class="quiz-modal__backdrop" data-quiz-close tabindex="-1"></div>
	<div class="quiz-modal__panel home-quiz-card home-quiz-card--modal">
		<button type="button" class="quiz-modal__close" data-quiz-close aria-label="<?php esc_attr_e( 'Закрыть', 'zhiraf-ufa' ); ?>">×</button>
		<p class="home-quiz__eyebrow home-quiz__eyebrow--modal"><?php esc_html_e( 'Экспресс-подбор за 1 минуту', 'zhiraf-ufa' ); ?></p>
		<h2 id="quiz-title" class="home-quiz__title home-quiz__title--modal"><?php esc_html_e( 'Подберите проверенный тур с вылетом из Уфы', 'zhiraf-ufa' ); ?></h2>
		<p class="home-quiz__lead home-quiz__lead--modal">
			<?php esc_html_e( '4 коротких шага — пришлём 2–4 отеля с прямым рейсом из UFA в WhatsApp или Telegram.', 'zhiraf-ufa' ); ?>
		</p>
		<?php require get_template_directory() . '/template-parts/quiz/quiz-wizard-fields.php'; ?>
	</div>
</div>
