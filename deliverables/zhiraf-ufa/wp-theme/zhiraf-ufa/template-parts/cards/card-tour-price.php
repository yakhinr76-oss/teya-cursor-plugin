<?php
/**
 * Tour price card component (Globe Trekker aesthetic).
 *
 * @package Zhiraf_Ufa
 * @var array $args Tour data array passed via get_template_part().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tour = isset( $args['tour'] ) ? $args['tour'] : ( is_array( $args ) ? $args : array() );
if ( empty( $tour ) || ! is_array( $tour ) ) {
	return;
}

$hotel_name       = ! empty( $tour['hotel_name'] ) ? (string) $tour['hotel_name'] : '';
$stars            = ! empty( $tour['stars'] ) ? (string) $tour['stars'] : '';
$resort           = ! empty( $tour['resort'] ) ? (string) $tour['resort'] : '';
$country_slug     = ! empty( $tour['country_slug'] ) ? (string) $tour['country_slug'] : 'turciya';
$departure_date   = ! empty( $tour['departure_date'] ) ? (string) $tour['departure_date'] : '';
$nights           = ! empty( $tour['nights'] ) ? (int) $tour['nights'] : 0;
$meal_code        = ! empty( $tour['meal_code'] ) ? (string) $tour['meal_code'] : '';
$meal_name        = ! empty( $tour['meal_name'] ) ? (string) $tour['meal_name'] : $meal_code;
$tourists         = ! empty( $tour['tourists'] ) ? (string) $tour['tourists'] : __( 'за 2 взрослых', 'zhiraf-ufa' );
$price_rur        = ! empty( $tour['price_rur'] ) ? (int) $tour['price_rur'] : 0;
$price_formatted  = ! empty( $tour['price_formatted'] ) ? (string) $tour['price_formatted'] : zhiraf_ufa_format_tour_price( $price_rur );
$price_date       = ! empty( $tour['price_date'] ) ? (string) $tour['price_date'] : '18.10.2026';
$disclaimer       = ! empty( $tour['disclaimer'] ) ? (string) $tour['disclaimer'] : sprintf( __( 'рассчитана по курсу туроператора на %s, итоговая стоимость фиксируется при бронировании', 'zhiraf-ufa' ), $price_date );

// Cards missing any required field are not shown.
if ( '' === $hotel_name || '' === $stars || '' === $resort || '' === $departure_date || $nights < 3 || '' === $meal_code || $price_rur <= 0 ) {
	return;
}
// Stale = price not refreshed today: show "цена от … на [дата], уточните…" instead of the fresh-price disclaimer.
$is_stale = ( $price_date !== wp_date( 'd.m.Y' ) );

$image_file       = zhiraf_ufa_tour_image( $tour );
$nights_formatted = zhiraf_ufa_plural_nights( $nights );
$country_name     = zhiraf_ufa_tour_country_name( $country_slug );

$prefill_text     = sprintf(
	'%s %s, %s, вылет %s, %s',
	$hotel_name,
	$stars,
	$resort,
	$departure_date,
	$nights_formatted
);
?>
<article class="tour-card reveal" data-dest="<?php echo esc_attr( $country_slug ); ?>">
	<div class="tour-card__media">
		<?php if ( $image_file ) : ?>
		<?php zhiraf_ufa_img( $image_file, $resort . ' — ' . $hotel_name, 'tour-card__img', true ); ?>
		<?php else : ?>
			<div class="tour-card__img tour-card__img--grad dest-grad dest-grad--<?php echo esc_attr( $country_slug ); ?>" role="img" aria-label="<?php echo esc_attr( $resort ); ?>"></div>
		<?php endif; ?>
		<div class="tour-card__badges">
			<?php if ( $country_name ) : ?>
				<span class="tour-card__badge tour-card__badge--country"><?php echo esc_html( $country_name ); ?></span>
			<?php endif; ?>
			<?php if ( $stars ) : ?>
				<span class="tour-card__badge tour-card__badge--stars" aria-label="<?php echo esc_attr( sprintf( __( 'Категория отеля: %s', 'zhiraf-ufa' ), $stars ) ); ?>">
					<span class="star-icon" aria-hidden="true">★</span><?php echo esc_html( $stars ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>

	<div class="tour-card__body">
		<div class="tour-card__header">
			<h3 class="tour-card__hotel">
				<?php echo esc_html( $hotel_name ); ?>
				<?php if ( $stars ) : ?>
					<span class="tour-card__hotel-stars"><?php echo esc_html( $stars ); ?></span>
				<?php endif; ?>
			</h3>
			<p class="tour-card__resort">
				<svg class="tour-card__icon-pin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
				<span><?php echo esc_html( $resort ); ?></span>
			</p>
		</div>

		<div class="tour-card__meta">
			<div class="tour-card__meta-item">
				<span class="tour-card__meta-label"><?php echo ( 'Москва' === ( $tour['departure_city'] ?? '' ) ) ? esc_html__( 'Вылет из Москвы:', 'zhiraf-ufa' ) : esc_html__( 'Вылет из Уфы:', 'zhiraf-ufa' ); ?></span>
				<strong class="tour-card__meta-value"><?php echo esc_html( $departure_date ); ?></strong>
			</div>
			<?php $zhiraf_flight = zhiraf_ufa_tour_flight_info( $tour ); ?>
			<?php if ( '' !== $zhiraf_flight['line'] ) : ?>
			<div class="tour-card__meta-item">
				<span class="tour-card__meta-label"><?php esc_html_e( 'Перелёт:', 'zhiraf-ufa' ); ?></span>
				<strong class="tour-card__meta-value"><?php echo esc_html( $zhiraf_flight['line'] ); ?></strong>
			</div>
			<?php endif; ?>
			<div class="tour-card__meta-item">
				<span class="tour-card__meta-label"><?php esc_html_e( 'Длительность:', 'zhiraf-ufa' ); ?></span>
				<strong class="tour-card__meta-value"><?php echo esc_html( $nights_formatted ); ?></strong>
			</div>
			<div class="tour-card__meta-item tour-card__meta-item--meal">
				<span class="tour-card__meta-label"><?php esc_html_e( 'Питание:', 'zhiraf-ufa' ); ?></span>
				<span class="tour-card__meal-badge" title="<?php echo esc_attr( $meal_name ); ?>">
					<span class="tour-card__meal-code"><?php echo esc_html( $meal_code ); ?></span>
					<span class="tour-card__meal-desc"><?php echo esc_html( $meal_name ); ?></span>
				</span>
			</div>
		</div>

		<div class="tour-card__footer">
			<div class="tour-card__price-wrap">
				<div class="tour-card__price-row">
					<span class="tour-card__price"><?php echo esc_html( $price_formatted ); ?></span>
					<span class="tour-card__tourists"><?php echo esc_html( $tourists ); ?></span>
				</div>
				<?php if ( $is_stale ) : ?>
				<p class="tour-card__fallback-note">
					<span class="tour-card__status-dot" aria-hidden="true"></span>
					<?php
					printf(
						/* translators: 1: price, 2: date */
						esc_html__( 'цена %1$s на %2$s, уточните актуальную стоимость у менеджера', 'zhiraf-ufa' ),
						esc_html( $price_formatted ),
						esc_html( $price_date )
					);
					?>
				</p>
				<?php else : ?>
				<p class="tour-card__disclaimer"><?php echo esc_html( $disclaimer ); ?></p>
				<?php endif; ?>
			</div>

			<button
				type="button"
				class="btn btn--primary tour-card__cta"
				data-modal-open="quiz"
				data-quiz-open
				data-quiz-prefill="<?php echo esc_attr( $prefill_text ); ?>"
				data-tour-hotel="<?php echo esc_attr( $hotel_name ); ?>"
				data-cta-context="tour_card_quiz_open"
			>
				<?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?>
			</button>
		</div>
	</div>
</article>
