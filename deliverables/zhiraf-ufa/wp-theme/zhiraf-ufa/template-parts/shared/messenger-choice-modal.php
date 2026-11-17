<?php
/**
 * Lightweight modal: pick MAX / WhatsApp / Telegram before opening chat (Turkey cards).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div
	id="turciya-messenger-modal"
	class="quiz-modal turciya-messenger-modal"
	role="dialog"
	aria-modal="true"
	aria-labelledby="turciya-messenger-modal-title"
	hidden
	inert
>
	<div class="quiz-modal__backdrop" data-turciya-messenger-close tabindex="-1" aria-hidden="true"></div>
	<div class="quiz-modal__panel turciya-messenger-modal__panel">
		<button type="button" class="quiz-modal__close" data-turciya-messenger-close aria-label="<?php esc_attr_e( 'Закрыть', 'zhiraf-ufa' ); ?>">×</button>
		<p class="turciya-messenger-modal__eyebrow"><?php esc_html_e( 'Узнать даты и цену', 'zhiraf-ufa' ); ?></p>
		<h2 id="turciya-messenger-modal-title" class="turciya-messenger-modal__title" data-turciya-messenger-title></h2>
		<p class="messenger-picker__label turciya-messenger-modal__label"><?php esc_html_e( 'Куда написать?', 'zhiraf-ufa' ); ?></p>
		<div class="messenger-picker__grid turciya-messenger-modal__grid" role="list">
			<a
				class="messenger-choice-modal__channel messenger-picker__option messenger-picker__option--max"
				href="#"
				target="_blank"
				rel="noopener noreferrer"
				data-turciya-messenger-channel="max"
				data-track="messenger"
				data-channel="max"
				data-cta-context="turciya_curated_hotel"
				hidden
			>
				<span class="messenger-picker__inner">
					<?php echo zhiraf_ufa_messenger_picker_icon( 'max' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="messenger-picker__text">MAX</span>
				</span>
			</a>
			<a
				class="messenger-choice-modal__channel messenger-picker__option messenger-picker__option--wa"
				href="#"
				target="_blank"
				rel="noopener noreferrer"
				data-turciya-messenger-channel="whatsapp"
				data-track="messenger"
				data-channel="whatsapp"
				data-cta-context="turciya_curated_hotel"
				hidden
			>
				<span class="messenger-picker__inner">
					<?php echo zhiraf_ufa_messenger_picker_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="messenger-picker__text">WhatsApp</span>
				</span>
			</a>
			<a
				class="messenger-choice-modal__channel messenger-picker__option messenger-picker__option--tg"
				href="#"
				target="_blank"
				rel="noopener noreferrer"
				data-turciya-messenger-channel="telegram"
				data-track="messenger"
				data-channel="telegram"
				data-cta-context="turciya_curated_hotel"
				hidden
			>
				<span class="messenger-picker__inner">
					<?php echo zhiraf_ufa_messenger_picker_icon( 'telegram' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="messenger-picker__text">Telegram</span>
				</span>
			</a>
		</div>
		<p class="turciya-messenger-modal__hint"><?php esc_html_e( 'Откроется чат с готовым текстом про отель и вылет из Уфы.', 'zhiraf-ufa' ); ?></p>
	</div>
</div>