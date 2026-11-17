<?php
/**
 * Curated hotel card — photo badges, title/rating, суть, цена, CTA.
 *
 * @package Zhiraf_Ufa
 * @var array<string,mixed> $args { hotel: array }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hotel = ! empty( $args['hotel'] ) && is_array( $args['hotel'] ) ? $args['hotel'] : array();
if ( empty( $hotel['name'] ) ) {
	return;
}

$is_base     = ! empty( $hotel['slot'] ) && 'base' === $hotel['slot'];
$resort      = ! empty( $hotel['resort'] ) ? sanitize_html_class( (string) $hotel['resort'] ) : '';
$filter_tags = ! empty( $hotel['filter_tags'] ) && is_array( $hotel['filter_tags'] ) ? $hotel['filter_tags'] : array();
if ( empty( $filter_tags ) && '' !== $resort ) {
	$filter_tags = array( 'resort-' . $resort );
}
$tags_attr   = esc_attr( implode( ' ', array_map( 'sanitize_html_class', $filter_tags ) ) );
$pool_rank   = ! empty( $hotel['pool_rank'] ) ? (int) $hotel['pool_rank'] : 999;
$card_class  = 'turciya-curated-card';
if ( $is_base ) {
	$card_class .= ' turciya-curated-card--base';
}
$uon_id      = ! empty( $hotel['uon_hotel_id'] ) ? (string) $hotel['uon_hotel_id'] : '';
$lead_message = ! empty( $hotel['wa_prefill'] )
	? (string) $hotel['wa_prefill']
	: sprintf(
		/* translators: %s: hotel name */
		__( 'Здравствуйте! Интересует отель %s — даты, цена за двоих с перелётом из Уфы.', 'zhiraf-ufa' ),
		(string) $hotel['name']
	);
$lead_tour    = (string) $hotel['name'];
$image_remote = ! empty( $hotel['image_remote'] );
$is_base_slot = ! empty( $hotel['slot'] ) && 'base' === $hotel['slot'];
$price_amount = (int) ( $hotel['price_rub'] ?? 0 );
if ( $price_amount <= 0 && $is_base_slot ) {
	if ( function_exists( 'zhiraf_ufa_get_country_min_tour' ) ) {
		$min_tour = zhiraf_ufa_get_country_min_tour( 'turciya' );
		if ( is_array( $min_tour ) && ! empty( $min_tour['price_rur'] ) ) {
			$price_amount = (int) $min_tour['price_rur'];
		}
	}
	if ( $price_amount <= 0 && function_exists( 'zhiraf_ufa_turciya_hub_min_price_rub' ) ) {
		$price_amount = zhiraf_ufa_turciya_hub_min_price_rub();
	}
}
$price_label = function_exists( 'zhiraf_ufa_turciya_curated_format_price' ) && $price_amount > 0
	? zhiraf_ufa_turciya_curated_format_price( $price_amount )
	: ( function_exists( 'zhiraf_ufa_format_tour_price' ) ? zhiraf_ufa_format_tour_price( 0 ) : '' );
$concept     = ! empty( $hotel['subtitle'] ) ? (string) $hotel['subtitle'] : '';
$price_note  = function_exists( 'zhiraf_ufa_turciya_curated_price_note' )
	? zhiraf_ufa_turciya_curated_price_note( $hotel )
	: __( '7–9 ночей, ориентир (включая прямой перелёт из Уфы, трансфер, страховку и питание).', 'zhiraf-ufa' );
$badge_mod   = ! empty( $hotel['badge_mod'] ) ? sanitize_html_class( (string) $hotel['badge_mod'] ) : 'default';
$is_starter  = in_array( 'starter', $filter_tags, true );
$hide_init   = ! empty( $hotel['initial_hidden'] ) || ! $is_starter;
if ( $hide_init ) {
	$card_class .= ' is-hidden';
}
$pool_id     = ! empty( $hotel['pool_id'] ) ? sanitize_html_class( (string) $hotel['pool_id'] ) : '';
$honest_raw  = '';
if ( ! empty( $hotel['caveat'] ) ) {
	$honest_raw = (string) $hotel['caveat'];
} elseif ( ! empty( $hotel['hotel_honest_con'] ) ) {
	$honest_raw = (string) $hotel['hotel_honest_con'];
} elseif ( ! empty( $hotel['nuance'] ) ) {
	$honest_raw = (string) $hotel['nuance'];
}
$honest_raw = trim( $honest_raw );
?>
<article
	class="<?php echo esc_attr( $card_class ); ?>"
	data-curated-card
	data-resort="<?php echo esc_attr( $resort ); ?>"
	data-filter-tags="<?php echo $tags_attr; ?>"
	data-pool-rank="<?php echo esc_attr( (string) $pool_rank ); ?>"
	<?php if ( '' !== $pool_id ) : ?>
		data-pool-id="<?php echo esc_attr( $pool_id ); ?>"
	<?php endif; ?>
	<?php echo $hide_init ? ' hidden' : ''; ?>
>
	<div class="turciya-curated-card__top">
		<div class="turciya-curated-card__media">
			<?php if ( ! empty( $hotel['image'] ) ) : ?>
				<?php
				$image_alt = ! empty( $hotel['image_alt'] ) ? (string) $hotel['image_alt'] : (string) $hotel['name'];
				if ( $image_remote ) :
					$image_w = ! empty( $hotel['image_width'] ) ? (int) $hotel['image_width'] : 1200;
					$image_h = ! empty( $hotel['image_height'] ) ? (int) $hotel['image_height'] : 800;
					?>
					<img
						class="turciya-curated-card__img"
						src="<?php echo esc_url( (string) $hotel['image'] ); ?>"
						alt="<?php echo esc_attr( $image_alt ); ?>"
						width="<?php echo esc_attr( (string) $image_w ); ?>"
						height="<?php echo esc_attr( (string) $image_h ); ?>"
						loading="lazy"
						decoding="async"
					/>
				<?php else : ?>
					<?php
					zhiraf_ufa_img(
						(string) $hotel['image'],
						$image_alt,
						'turciya-curated-card__img',
						true
					);
					?>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( ! empty( $hotel['badge'] ) ) : ?>
				<span class="turciya-curated-card__badge turciya-curated-card__badge--<?php echo esc_attr( $badge_mod ); ?>">
					<?php echo esc_html( (string) $hotel['badge'] ); ?>
				</span>
			<?php endif; ?>
			<?php if ( ! empty( $hotel['resort_pill'] ) ) : ?>
				<span class="turciya-curated-card__pill"><?php echo esc_html( (string) $hotel['resort_pill'] ); ?></span>
			<?php endif; ?>
		</div>
		<div class="turciya-curated-card__body">
			<div class="turciya-curated-card__headline">
				<h3 class="turciya-curated-card__title"><?php echo esc_html( (string) $hotel['name'] ); ?></h3>
				<?php if ( ! empty( $hotel['rating'] ) ) : ?>
					<p class="turciya-curated-card__rating" aria-label="<?php esc_attr_e( 'Рейтинг по отзывам', 'zhiraf-ufa' ); ?>">
						<span aria-hidden="true">★</span>
						<?php echo esc_html( (string) $hotel['rating'] ); ?>
					</p>
				<?php endif; ?>
			</div>
			<?php if ( '' !== $concept ) : ?>
				<p class="turciya-curated-card__concept"><?php echo esc_html( $concept ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $hotel['description'] ) ) : ?>
				<div class="turciya-curated-card__copy">
					<p class="turciya-curated-card__usp"><?php echo esc_html( (string) $hotel['description'] ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( '' !== $honest_raw ) : ?>
		<div class="turciya-curated-card__honest" role="note">
			<span class="turciya-curated-card__honest-label"><?php esc_html_e( 'Честный нюанс:', 'zhiraf-ufa' ); ?></span>
			<span class="turciya-curated-card__honest-text"><?php echo esc_html( $honest_raw ); ?></span>
		</div>
	<?php endif; ?>
	<div class="turciya-curated-card__footer">
		<div class="turciya-curated-card__price">
			<span class="turciya-curated-card__price-label"><?php esc_html_e( 'за двоих с перелётом:', 'zhiraf-ufa' ); ?></span>
			<span class="turciya-curated-card__price-value<?php echo ! empty( $hotel['price_emphasis'] ) ? ' turciya-curated-card__price-value--accent' : ''; ?>">
				<?php echo esc_html( $price_label ); ?>
			</span>
			<span class="turciya-curated-card__price-note">
				<?php echo esc_html( $price_note ); ?>
			</span>
		</div>
		<button
			type="button"
			class="btn btn--primary turciya-curated-card__cta"
			data-turciya-lead-open
			data-cta-context="turciya_curated_hotel_modal"
			data-hotel-name="<?php echo esc_attr( (string) $hotel['name'] ); ?>"
			data-uon-hotel-id="<?php echo esc_attr( $uon_id ); ?>"
			data-lead-message="<?php echo esc_attr( $lead_message ); ?>"
			data-lead-tour="<?php echo esc_attr( $lead_tour ); ?>"
			data-lead-title="<?php echo esc_attr( (string) $hotel['name'] ); ?>"
			data-lead-subtitle="<?php echo esc_attr__( 'Укажите телефон и выберите мессенджер — пришлём даты и цену с вылетом из Уфы.', 'zhiraf-ufa' ); ?>"
		>
			<?php esc_html_e( 'Узнать даты / цену →', 'zhiraf-ufa' ); ?>
		</button>
	</div>
</article>
