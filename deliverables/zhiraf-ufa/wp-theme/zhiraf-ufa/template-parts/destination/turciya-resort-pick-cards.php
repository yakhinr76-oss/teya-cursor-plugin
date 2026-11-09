<?php
/**
 * Turkey — resort pick cards (SEO H2 + «Разница за 10 секунд» eyebrow).
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
		<p class="turciya-resort-pick__eyebrow">
			<?php esc_html_e( 'Шпаргалка туриста • Разница за 10 секунд', 'zhiraf-ufa' ); ?>
		</p>
		<h2 class="section-title turciya-resort-pick__title" id="turciya-resort-pick-title">
			<?php esc_html_e( 'Какой курорт Турции выбрать именно вам?', 'zhiraf-ufa' ); ?>
		</h2>
		<p class="turciya-resort-pick__lead">
			<?php esc_html_e( 'Нажмите на курорт — сразу покажем проверенные отели и честные цены с вылетом из Уфы.', 'zhiraf-ufa' ); ?>
		</p>
	</div>

	<div class="turciya-resort-pick__track turciya-resort-pick__track--five" role="list">
		<?php foreach ( $cards as $card ) : ?>
			<?php
			$id             = ! empty( $card['id'] ) ? sanitize_html_class( (string) $card['id'] ) : '';
			$featured       = ! empty( $card['featured'] );
			$tag_mod        = ! empty( $card['tag_mod'] ) ? sanitize_html_class( (string) $card['tag_mod'] ) : '';
			$accent_mod     = ! empty( $card['accent_mod'] ) ? sanitize_html_class( (string) $card['accent_mod'] ) : '';
			$count          = isset( $card['hotel_count'] ) ? (int) $card['hotel_count'] : 0;
			$card_class     = 'turciya-resort-pick__card turciya-resort-pick__card--' . $accent_mod;
			if ( $featured ) {
				$card_class .= ' turciya-resort-pick__card--featured';
			}
			$catalog_filter = 'resort-' . $id;
			$transfer_mod   = ! empty( $card['transfer_mod'] ) ? sanitize_html_class( (string) $card['transfer_mod'] ) : '';
			$guide_url      = ! empty( $card['guide_url'] ) ? (string) $card['guide_url'] : '';
			$guide_label    = ! empty( $card['guide_label'] ) ? (string) $card['guide_label'] : __( 'Подробный гид по курорту →', 'zhiraf-ufa' );
			/* translators: %d: hotel count */
			$filter_cta     = sprintf( __( 'Показать отели (%d) ↓', 'zhiraf-ufa' ), $count );
			?>
			<div class="<?php echo esc_attr( $card_class ); ?>" role="listitem">
				<button
					type="button"
					class="turciya-resort-pick__card-hit"
					data-turciya-pick-card
					data-turciya-catalog-filter="<?php echo esc_attr( $catalog_filter ); ?>"
					aria-pressed="false"
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
				</button>
				<div class="turciya-resort-pick__card-foot">
					<button
						type="button"
						class="turciya-resort-pick__filter-cta"
						data-turciya-catalog-filter="<?php echo esc_attr( $catalog_filter ); ?>"
					>
						<?php echo esc_html( $filter_cta ); ?>
					</button>
					<?php if ( '' !== $guide_url ) : ?>
						<a
							class="turciya-resort-pick__guide"
							href="<?php echo esc_url( $guide_url ); ?>"
							data-turciya-pick-guide
						>
							<?php echo esc_html( $guide_label ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
