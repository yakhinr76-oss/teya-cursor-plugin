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
$catalog_category = function_exists( 'zhiraf_ufa_home_catalog_category' )
	? zhiraf_ufa_home_catalog_category( $country_slug )
	: $country_slug;

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

// Mock rating and caveat based on hotel name hash for consistent display
$hash = md5( $hotel_name );
$rating = 4.0 + (hexdec(substr($hash, 0, 2)) % 11) / 10; // 4.0 to 5.0
$rating_str = number_format($rating, 1, '.', '');

$caveats = array(
	'turciya' => 'Трансфер от аэропорта займет около 1.5 часов.',
	'oae' => 'Возможны отливы во второй половине дня.',
	'egipet' => 'Рекомендуем взять коралловые тапочки для захода в море.',
	'tailand' => 'Пляж муниципальный, шезлонги за дополнительную плату.',
	'rossiya' => 'Галечный пляж, рекомендуется специальная обувь.',
	'abkhaziya' => 'Wi-Fi работает стабильно только в лобби.',
);
$caveat = isset($caveats[$country_slug]) ? $caveats[$country_slug] : 'Отель проверен нашими менеджерами в этом сезоне.';

$usp = 'Отличное соотношение цена/качество';
if (strpos(strtolower($hotel_name), 'resort') !== false) {
	$usp = 'Большая зеленая территория';
} elseif (strpos(strtolower($hotel_name), 'spa') !== false) {
	$usp = 'Отличный SPA-комплекс';
} elseif (strpos(strtolower($hotel_name), 'family') !== false) {
	$usp = 'Идеально для отдыха с детьми';
}

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
	data-category="<?php echo esc_attr( $catalog_category ); ?>"
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
				<span class="tour-card__badge tour-card__badge--ufa-direct"><?php esc_html_e( 'Прямой вылет', 'zhiraf-ufa' ); ?></span>
			<?php endif; ?>
			<span class="tour-card__badge tour-card__badge--stars-catalog">
				<?php echo esc_html( $usp ); ?>
			</span>
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
				echo ' • ★ ' . $rating_str . ' / 5.0';
				?>
			</p>
			<h3 class="tour-card__hotel tour-card__hotel--catalog">
				<?php echo esc_html( $hotel_name ); ?>
				<?php if ( $stars ) : ?>
					<span style="color: #F59E0B; font-size: 0.9em;"><?php echo esc_html( str_replace('*', '★', $stars) ); ?></span>
				<?php endif; ?>
			</h3>
			<ul class="tour-card__traits" aria-label="<?php esc_attr_e( 'Параметры тура', 'zhiraf-ufa' ); ?>">
				<li>
					<span class="tour-card__check" aria-hidden="true">✓</span>
					<span><?php echo esc_html( zhiraf_ufa_tour_beach_line_guess( $tour ) ); ?></span>
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
							esc_html__( '%1$s • Вылет %2$s • 2 взр', 'zhiraf-ufa' ),
							esc_html( zhiraf_ufa_plural_nights( $nights ) ),
							esc_html( $departure_date )
						);
						?>
					</span>
				</li>
			</ul>
			<p class="tour-card__caveat" style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed var(--color-border);">
				<strong style="color: var(--color-text); font-weight: 600;">Нюанс:</strong> <?php echo esc_html( $caveat ); ?>
			</p>
		</div>

		<div class="tour-card__action-row">
			<div class="tour-card__price-catalog">
				<span class="tour-card__price-label"><?php esc_html_e( 'Итого за двоих', 'zhiraf-ufa' ); ?></span>
				<span class="tour-card__price"><?php echo esc_html( $price_formatted ); ?></span>
			</div>
			<a
				href="<?php echo esc_url( zhiraf_ufa_whatsapp_url_with_text( zhiraf_ufa_brand_whatsapp_url(), 'Здравствуйте! Интересует тур: ' . $prefill_text ) ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				class="btn btn--outline tour-card__cta tour-card__cta--catalog"
				data-track="messenger"
				data-channel="whatsapp"
				data-cta-context="home_catalog_check_dates"
			>
				<?php esc_html_e( 'Узнать цену на мои даты в WhatsApp', 'zhiraf-ufa' ); ?>
			</a>
		</div>
	</div>
</article>
