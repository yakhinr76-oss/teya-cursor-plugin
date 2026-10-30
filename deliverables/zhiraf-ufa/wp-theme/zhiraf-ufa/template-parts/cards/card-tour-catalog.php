<?php
/**
 * Compact tour card for homepage unified catalog (Globe Trekker layout).
 *
 * @package Zhiraf_Ufa
 * @var array $args Tour data + optional card_index.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tour = isset( $args['tour'] ) ? $args['tour'] : ( is_array( $args ) ? $args : array() );
if ( empty( $tour ) || ! is_array( $tour ) ) {
	return;
}

$hotel_name      = ! empty( $tour['hotel_name'] ) ? (string) $tour['hotel_name'] : '';
$stars           = ! empty( $tour['stars'] ) ? (string) $tour['stars'] : '';
$resort          = ! empty( $tour['resort'] ) ? (string) $tour['resort'] : '';
$country_slug    = ! empty( $tour['country_slug'] ) ? (string) $tour['country_slug'] : 'turciya';
$departure_date  = ! empty( $tour['departure_date'] ) ? (string) $tour['departure_date'] : '';
$nights          = ! empty( $tour['nights'] ) ? (int) $tour['nights'] : 0;
$meal_name       = ! empty( $tour['meal_name'] ) ? (string) $tour['meal_name'] : '';
$meal_code       = ! empty( $tour['meal_code'] ) ? (string) $tour['meal_code'] : '';
$price_rur       = ! empty( $tour['price_rur'] ) ? (int) $tour['price_rur'] : 0;
$price_formatted = ! empty( $tour['price_formatted'] ) ? (string) $tour['price_formatted'] : zhiraf_ufa_format_tour_price( $price_rur );
$catalog_in_all  = ! empty( $tour['catalog_in_all'] );

if ( '' === $hotel_name || '' === $stars || '' === $resort || '' === $departure_date || $nights < 3 || $price_rur <= 0 ) {
	return;
}

if ( '' === $meal_name && '' !== $meal_code ) {
	$meal_map = array(
		'AI'  => __( 'All Inclusive', 'zhiraf-ufa' ),
		'UAI' => __( 'Ultra All Inclusive', 'zhiraf-ufa' ),
		'BB'  => __( 'Завтраки', 'zhiraf-ufa' ),
		'HB'  => __( 'Завтрак и ужин', 'zhiraf-ufa' ),
		'FB'  => __( 'Полный пансион', 'zhiraf-ufa' ),
	);
	$meal_name = isset( $meal_map[ strtoupper( $meal_code ) ] ) ? $meal_map[ strtoupper( $meal_code ) ] : $meal_code;
}

$card_index   = isset( $args['card_index'] ) ? (int) $args['card_index'] : null;
$image_info   = zhiraf_ufa_tour_image_info( $tour, $card_index );
$image_file   = $image_info['file'];
$country_name = zhiraf_ufa_tour_country_name( $country_slug );
$flight       = zhiraf_ufa_tour_flight_info( $tour );
$show_direct  = 'direct' === $flight['key'] || ( 'ufa' === $flight['key'] && 'Уфа' === ( $tour['departure_city'] ?? 'Уфа' ) );
$beach_line   = ! empty( $tour['beach_line'] ) ? (string) $tour['beach_line'] : __( '1-я линия • Песок', 'zhiraf-ufa' );
$spec_line    = function_exists( 'zhiraf_ufa_tour_card_spec_line' ) ? zhiraf_ufa_tour_card_spec_line( $tour ) : '';

$prefill_text = sprintf(
	'%s %s, %s, вылет %s, %s',
	$hotel_name,
	$stars,
	$resort,
	$departure_date,
	zhiraf_ufa_plural_nights( $nights )
);
?>
<article
	class="tour-card tour-card--catalog reveal"
	data-dest="<?php echo esc_attr( $country_slug ); ?>"
	data-catalog-all="<?php echo $catalog_in_all ? '1' : '0'; ?>"
>
	<div class="tour-card__media">
		<?php if ( $image_file ) : ?>
			<?php zhiraf_ufa_img( $image_file, $image_info['alt'] ? $image_info['alt'] : $resort, 'tour-card__img', true ); ?>
		<?php else : ?>
			<div class="tour-card__img tour-card__img--grad dest-grad dest-grad--<?php echo esc_attr( $country_slug ); ?>" role="img" aria-label="<?php echo esc_attr( $resort ); ?>"></div>
		<?php endif; ?>
		<div class="tour-card__badges tour-card__badges--catalog">
			<?php if ( $show_direct ) : ?>
				<span class="tour-card__badge tour-card__badge--ufa-direct"><?php esc_html_e( 'Прямой вылет из UFA', 'zhiraf-ufa' ); ?></span>
			<?php elseif ( '' !== $flight['label'] ) : ?>
				<span class="tour-card__badge tour-card__badge--ufa-direct tour-card__badge--muted"><?php echo esc_html( $flight['label'] ); ?></span>
			<?php endif; ?>
			<?php if ( $stars ) : ?>
				<span class="tour-card__badge tour-card__badge--stars-catalog" aria-label="<?php echo esc_attr( sprintf( __( 'Категория отеля: %s', 'zhiraf-ufa' ), $stars ) ); ?>">
					<?php echo esc_html( $stars ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>

	<div class="tour-card__body tour-card__body--catalog">
		<div class="tour-card__main">
			<p class="tour-card__crumb">
				<?php
				if ( $country_name ) {
					echo esc_html( $country_name );
					echo ', ';
				}
				echo esc_html( $resort );
				?>
			</p>
			<h3 class="tour-card__hotel tour-card__hotel--catalog"><?php echo esc_html( $hotel_name ); ?></h3>
			<ul class="tour-card__traits" aria-label="<?php esc_attr_e( 'Параметры тура', 'zhiraf-ufa' ); ?>">
				<li>
					<span class="tour-card__check" aria-hidden="true">✓</span>
					<span><?php echo esc_html( '' !== $spec_line ? $spec_line : $beach_line ); ?></span>
				</li>
				<?php if ( '' !== $meal_name ) : ?>
					<li>
						<span class="tour-card__check" aria-hidden="true">✓</span>
						<span>
							<?php
							printf(
								/* translators: %s: meal plan name */
								esc_html__( 'Питание: %s', 'zhiraf-ufa' ),
								esc_html( $meal_name )
							);
							?>
						</span>
					</li>
				<?php endif; ?>
				<li>
					<span class="tour-card__check" aria-hidden="true">✓</span>
					<span>
						<?php
						printf(
							/* translators: 1: nights phrase, 2: departure date */
							esc_html__( '%1$s • Вылет %2$s • 2 взрослых', 'zhiraf-ufa' ),
							esc_html( zhiraf_ufa_plural_nights( $nights ) ),
							esc_html( $departure_date )
						);
						?>
					</span>
				</li>
			</ul>
		</div>

		<div class="tour-card__action-row">
			<div class="tour-card__price-catalog">
				<span class="tour-card__price-label"><?php esc_html_e( 'Итого за двоих', 'zhiraf-ufa' ); ?></span>
				<span class="tour-card__price"><?php echo esc_html( $price_formatted ); ?></span>
			</div>
			<button
				type="button"
				class="btn tour-card__cta tour-card__cta--catalog"
				data-quiz-open
				data-quiz-prefill="<?php echo esc_attr( $prefill_text ); ?>"
				data-tour-hotel="<?php echo esc_attr( $hotel_name ); ?>"
				data-cta-context="home_catalog_quiz"
			>
				<?php esc_html_e( 'Проверить даты', 'zhiraf-ufa' ); ?>
			</button>
		</div>
	</div>
</article>
