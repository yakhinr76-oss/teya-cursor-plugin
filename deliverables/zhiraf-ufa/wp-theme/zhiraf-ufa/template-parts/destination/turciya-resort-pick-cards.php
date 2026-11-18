<?php
/**
 * Curated hub — resort / emirate pick cards (SEO H2 + «Разница за 10 секунд» eyebrow).
 *
 * @package Zhiraf_Ufa
 * @var array $args { dest_slug?: string }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : 'turciya';

$cards = function_exists( 'zhiraf_ufa_curated_resort_pick_cards' )
	? zhiraf_ufa_curated_resort_pick_cards( $dest_slug )
	: ( function_exists( 'zhiraf_ufa_turciya_resort_pick_cards' ) ? zhiraf_ufa_turciya_resort_pick_cards() : array() );

if ( empty( $cards ) ) {
	return;
}

$filter_meta = array();
if ( function_exists( 'zhiraf_ufa_curated_catalog_filters' ) ) {
	foreach ( zhiraf_ufa_curated_catalog_filters( $dest_slug ) as $filter ) {
		$fid = ! empty( $filter['id'] ) ? (string) $filter['id'] : '';
		if ( '' !== $fid ) {
			$filter_meta[ $fid ] = $filter;
		}
	}
}

if ( 'oae' === $dest_slug ) {
	$pick_title = __( 'Какой эмират ОАЭ выбрать именно вам?', 'zhiraf-ufa' );
} elseif ( 'egipet' === $dest_slug ) {
	$pick_title = __( 'Какой курорт Египта выбрать именно вам?', 'zhiraf-ufa' );
} elseif ( 'tailand' === $dest_slug ) {
	$pick_title = __( 'Какой курорт Таиланда выбрать именно вам?', 'zhiraf-ufa' );
} elseif ( 'maldivy' === $dest_slug ) {
	$pick_title = __( 'Какой атолл Мальдив выбрать именно вам?', 'zhiraf-ufa' );
} else {
	$pick_title = __( 'Какой курорт Турции выбрать именно вам?', 'zhiraf-ufa' );
}
$pick_lead = ( 'oae' === $dest_slug || 'egipet' === $dest_slug || 'tailand' === $dest_slug || 'maldivy' === $dest_slug )
	? __( 'Нажмите на курорт — сразу покажем проверенные отели и честные цены с вылетом из Уфы.', 'zhiraf-ufa' )
	: __( 'Нажмите на курорт — сразу покажем проверенные отели и честные цены с вылетом из Уфы.', 'zhiraf-ufa' );
?>
<div class="turciya-resort-pick" id="dest-resort-compare" aria-labelledby="turciya-resort-pick-title">
	<div class="turciya-resort-pick__head">
		<p class="turciya-resort-pick__eyebrow">
			<?php esc_html_e( 'Шпаргалка туриста • Разница за 10 секунд', 'zhiraf-ufa' ); ?>
		</p>
		<h2 class="section-title turciya-resort-pick__title" id="turciya-resort-pick-title">
			<?php echo esc_html( $pick_title ); ?>
		</h2>
		<p class="turciya-resort-pick__lead">
			<?php echo esc_html( $pick_lead ); ?>
		</p>
	</div>

	<?php
	$card_count = count( $cards );
	if ( $card_count >= 6 ) {
		$pick_track_class = 'turciya-resort-pick__track turciya-resort-pick__track--six';
	} else {
		$cols             = max( 1, min( 5, $card_count ) );
		$pick_track_class = 'turciya-resort-pick__track turciya-resort-pick__track--five turciya-resort-pick__track--cols-' . $cols;
	}
	?>
	<div class="<?php echo esc_attr( $pick_track_class ); ?>" role="list">
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
			$catalog_filter = ! empty( $card['filter_id'] ) ? (string) $card['filter_id'] : 'resort-' . $id;
			$meta           = isset( $filter_meta[ $catalog_filter ] ) ? $filter_meta[ $catalog_filter ] : array();
			$catalog_title  = ! empty( $meta['title'] ) ? (string) $meta['title'] : '';
			$catalog_desc   = ! empty( $meta['desc'] ) ? (string) $meta['desc'] : '';
			$transfer_mod   = ! empty( $card['transfer_mod'] ) ? sanitize_html_class( (string) $card['transfer_mod'] ) : '';
			$guide_url      = ! empty( $card['guide_url'] ) ? (string) $card['guide_url'] : '';
			$guide_label    = ! empty( $card['guide_label'] ) ? (string) $card['guide_label'] : __( 'На страницу →', 'zhiraf-ufa' );
			$show_filter_cta = $count > 0 && empty( $card['hide_catalog_filter'] );
			/* translators: %d: hotel count */
			$filter_cta     = sprintf(
				( 'oae' === $dest_slug || 'egipet' === $dest_slug || 'tailand' === $dest_slug || 'maldivy' === $dest_slug )
					? __( 'Показать здесь (%d) ↓', 'zhiraf-ufa' )
					: __( 'Показать отели (%d) ↓', 'zhiraf-ufa' ),
				$count
			);
			?>
			<div class="<?php echo esc_attr( $card_class ); ?>" role="listitem">
				<button
					type="button"
					class="turciya-resort-pick__card-hit"
					data-turciya-pick-card
					data-turciya-catalog-filter="<?php echo esc_attr( $catalog_filter ); ?>"
					data-catalog-title="<?php echo esc_attr( $catalog_title ); ?>"
					data-catalog-desc="<?php echo esc_attr( $catalog_desc ); ?>"
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
					<?php if ( $show_filter_cta ) : ?>
						<button
							type="button"
							class="turciya-resort-pick__filter-cta"
							data-turciya-catalog-filter="<?php echo esc_attr( $catalog_filter ); ?>"
							data-catalog-title="<?php echo esc_attr( $catalog_title ); ?>"
							data-catalog-desc="<?php echo esc_attr( $catalog_desc ); ?>"
						>
							<?php echo esc_html( $filter_cta ); ?>
						</button>
					<?php endif; ?>
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
