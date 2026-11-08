<?php
/**
 * Turkey — resort pick cards («В чём разница за 10 секунд»).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cards = function_exists( 'zhiraf_ufa_turciya_resort_pick_cards' )
	? zhiraf_ufa_turciya_resort_pick_cards()
	: array();

if ( empty( $cards ) ) {
	return;
}
?>
<div class="turciya-resort-pick" id="dest-resort-compare" aria-labelledby="turciya-resort-pick-title">
	<div class="turciya-resort-pick__head">
		<p class="turciya-resort-pick__eyebrow"><?php esc_html_e( 'Шпаргалка туриста', 'zhiraf-ufa' ); ?></p>
		<h2 class="section-title turciya-resort-pick__title" id="turciya-resort-pick-title">
			<?php esc_html_e( 'В чём разница за 10 секунд', 'zhiraf-ufa' ); ?>
		</h2>
		<p class="turciya-resort-pick__lead">
			<?php esc_html_e( 'Нажмите на курорт — сразу отфильтруем проверенные отели ниже.', 'zhiraf-ufa' ); ?>
		</p>
	</div>

	<div class="turciya-resort-pick__track" role="list">
		<?php foreach ( $cards as $card ) : ?>
			<?php
			$id          = ! empty( $card['id'] ) ? sanitize_html_class( (string) $card['id'] ) : '';
			$featured    = ! empty( $card['featured'] );
			$tag_mod     = ! empty( $card['tag_mod'] ) ? sanitize_html_class( (string) $card['tag_mod'] ) : '';
			$accent_mod  = ! empty( $card['accent_mod'] ) ? sanitize_html_class( (string) $card['accent_mod'] ) : '';
			$count       = isset( $card['hotel_count'] ) ? (int) $card['hotel_count'] : 0;
			$card_class  = 'turciya-resort-pick__card turciya-resort-pick__card--' . $accent_mod;
			if ( $featured ) {
				$card_class .= ' turciya-resort-pick__card--featured';
			}
			/* translators: 1: resort name, 2: number of hotels */
			$footer_label = sprintf(
				__( 'Отели в %1$s (%2$d)', 'zhiraf-ufa' ),
				(string) ( $card['name'] ?? '' ),
				$count
			);
			$transfer_mod = ! empty( $card['transfer_mod'] ) ? sanitize_html_class( (string) $card['transfer_mod'] ) : '';
			?>
			<?php
			$catalog_filter = 'resort-' . $id;
			?>
			<button
				type="button"
				class="<?php echo esc_attr( $card_class ); ?>"
				data-turciya-pick-card
				data-turciya-catalog-filter="<?php echo esc_attr( $catalog_filter ); ?>"
				aria-pressed="false"
				role="listitem"
			>
				<span class="turciya-resort-pick__card-top">
					<span class="turciya-resort-pick__card-meta">
						<span class="turciya-resort-pick__tag turciya-resort-pick__tag--<?php echo esc_attr( $tag_mod ); ?>">
							<?php echo esc_html( (string) ( $card['tag'] ?? '' ) ); ?>
						</span>
						<?php if ( ! empty( $card['transfer'] ) ) : ?>
							<span class="turciya-resort-pick__transfer<?php echo $transfer_mod ? ' turciya-resort-pick__transfer--' . esc_attr( $transfer_mod ) : ''; ?>">
								<?php echo esc_html( (string) $card['transfer'] ); ?>
							</span>
						<?php endif; ?>
					</span>
					<span class="turciya-resort-pick__name"><?php echo esc_html( (string) ( $card['name'] ?? '' ) ); ?></span>
					<?php if ( ! empty( $card['subtitle'] ) ) : ?>
						<span class="turciya-resort-pick__subtitle"><?php echo esc_html( (string) $card['subtitle'] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $card['bullets'] ) && is_array( $card['bullets'] ) ) : ?>
						<ul class="turciya-resort-pick__bullets">
							<?php foreach ( $card['bullets'] as $bullet ) : ?>
								<?php
								$b_type = ! empty( $bullet['type'] ) ? (string) $bullet['type'] : 'pro';
								$b_text = ! empty( $bullet['text'] ) ? (string) $bullet['text'] : '';
								if ( '' === $b_text ) {
									continue;
								}
								?>
								<li class="turciya-resort-pick__bullet turciya-resort-pick__bullet--<?php echo esc_attr( sanitize_html_class( $b_type ) ); ?>">
									<span class="turciya-resort-pick__bullet-icon" aria-hidden="true">
										<?php echo 'info' === $b_type ? 'i' : '✓'; ?>
									</span>
									<span><?php echo esc_html( $b_text ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</span>
				<span class="turciya-resort-pick__card-foot">
					<span><?php echo esc_html( $footer_label ); ?></span>
					<span class="turciya-resort-pick__foot-arrow" aria-hidden="true">↓</span>
				</span>
			</button>
		<?php endforeach; ?>
	</div>
</div>
