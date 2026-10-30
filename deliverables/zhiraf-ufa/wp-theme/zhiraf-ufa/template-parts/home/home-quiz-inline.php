<?php
/**
 * Homepage inline quiz (block 2).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$quiz_form_id = 'home-quiz-form';
?>
<section class="section section--home-quiz" id="quiz">
	<div class="container home-quiz">
		<header class="home-quiz__intro reveal">
			<p class="home-quiz__eyebrow"><?php esc_html_e( 'Персональный экспресс-подбор за 1 минуту', 'zhiraf-ufa' ); ?></p>
			<h2 class="home-quiz__title"><?php esc_html_e( 'Подберите проверенный тур с вылетом из Уфы', 'zhiraf-ufa' ); ?></h2>
			<p class="home-quiz__lead">
				<?php esc_html_e( 'Ответьте на 4 вопроса — тревел-эксперт отберёт 2–4 отеля под ваш бюджет и пришлёт расчёт в WhatsApp или Telegram.', 'zhiraf-ufa' ); ?>
			</p>
		</header>
		<div class="home-quiz-card reveal">
			<?php require get_template_directory() . '/template-parts/quiz/quiz-wizard-fields.php'; ?>
		</div>
	</div>
</section>
