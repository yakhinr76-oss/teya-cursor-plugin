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
<style id="quiz-modal-selection-styles">
#quiz-modal {
	--quiz-terracotta: #c85a32;
	--quiz-ivory: #faf7f2;
	--quiz-border: #eae4dc;
	--quiz-muted: #524c48;
	color: #221f1e;
}
#quiz-modal .quiz-tile input:checked + .quiz-tile__inner,
#quiz-modal .quiz-tile.is-quiz-selected .quiz-tile__inner,
#quiz-modal .quiz-tile:has(input:checked) .quiz-tile__inner {
	border-color: var(--quiz-terracotta);
	background: var(--quiz-ivory);
	box-shadow: 0 1px 3px rgba(200, 90, 50, 0.12);
}
#quiz-modal .quiz-chip.is-quiz-selected,
#quiz-modal .quiz-chip:has(input:checked) {
	border-color: var(--quiz-terracotta);
	background: var(--quiz-ivory);
	color: var(--quiz-terracotta);
}
#quiz-modal .quiz-channel.is-quiz-selected,
#quiz-modal .quiz-channel:has(input:checked) {
	border-color: var(--quiz-terracotta);
	background: var(--quiz-ivory);
	color: var(--quiz-terracotta);
}
#quiz-modal .quiz-channel--wa.is-quiz-selected,
#quiz-modal .quiz-channel--wa:has(input:checked) {
	border-color: #25d366;
	background: rgba(37, 211, 102, 0.08);
	color: #128c7e;
}
#quiz-modal .quiz-channel--tg.is-quiz-selected,
#quiz-modal .quiz-channel--tg:has(input:checked) {
	border-color: #229ed9;
	background: rgba(34, 158, 217, 0.08);
	color: #229ed9;
}
</style>
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
