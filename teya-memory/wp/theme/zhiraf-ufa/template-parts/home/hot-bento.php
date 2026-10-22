<?php
/**
 * Homepage «Горящие туры из Уфы»: bento grid, one tile per country.
 * Price = minimum live RUR price (2 adults) from synced Biblio-Globus data.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$zhiraf_groups = array(
	array(
		'heading' => __( 'Из Уфы', 'zhiraf-ufa' ),
		'mod'     => 'ufa',
		'tiles'   => array(
			array( 'slug' => 'turciya', 'title' => __( 'Турция', 'zhiraf-ufa' ), 'image' => 'country-turciya.jpg', 'mod' => 'wide' ),
			array( 'slug' => 'egipet', 'title' => __( 'Египет', 'zhiraf-ufa' ), 'image' => 'country-egipet.jpg', 'mod' => 'tall' ),
			array( 'slug' => 'oae', 'title' => __( 'ОАЭ', 'zhiraf-ufa' ), 'image' => 'country-oae.jpg', 'mod' => 'small' ),
			array( 'slug' => 'tailand', 'title' => __( 'Таиланд', 'zhiraf-ufa' ), 'image' => 'country-tailand.jpg', 'mod' => 'small' ),
			array( 'slug' => 'vietnam', 'title' => __( 'Вьетнам', 'zhiraf-ufa' ), 'image' => 'country-vietnam.jpg', 'mod' => 'banner' ),
		),
	),
	array(
		'heading' => __( 'Из Москвы', 'zhiraf-ufa' ),
		'mod'     => 'mow',
		'tiles'   => array(
			array( 'slug' => 'maldivy', 'title' => __( 'Мальдивы', 'zhiraf-ufa' ), 'image' => 'country-maldivy.jpg', 'mod' => 'wide' ),
			array( 'slug' => 'sejshely', 'title' => __( 'Сейшелы', 'zhiraf-ufa' ), 'image' => 'country-sejshely.jpg', 'mod' => 'small' ),
			array( 'slug' => 'shri-lanka', 'title' => __( 'Шри-Ланка', 'zhiraf-ufa' ), 'image' => 'country-shri-lanka.jpg', 'mod' => 'small' ),
			array( 'slug' => 'indoneziya', 'title' => __( 'Бали', 'zhiraf-ufa' ), 'kicker' => __( 'Индонезия', 'zhiraf-ufa' ), 'image' => 'country-indoneziya.jpg', 'mod' => 'small' ),
			array( 'slug' => 'indiya', 'title' => __( 'Гоа', 'zhiraf-ufa' ), 'kicker' => __( 'Индия', 'zhiraf-ufa' ), 'image' => 'country-indiya.jpg', 'mod' => 'wide' ),
			array( 'slug' => 'kitaj', 'title' => __( 'Санья', 'zhiraf-ufa' ), 'kicker' => __( 'Китай, о. Хайнань', 'zhiraf-ufa' ), 'image' => 'country-kitaj.jpg', 'mod' => 'small' ),
			array( 'slug' => 'uzbekistan', 'title' => __( 'Узбекистан', 'zhiraf-ufa' ), 'image' => 'country-uzbekistan.jpg', 'mod' => 'wide' ),
			array( 'slug' => 'armeniya', 'title' => __( 'Армения', 'zhiraf-ufa' ), 'image' => 'country-armeniya.jpg', 'mod' => 'wide' ),
		),
	),
);
$zhiraf_today = function_exists( 'wp_date' ) ? wp_date( 'd.m.Y' ) : date( 'd.m.Y' );
?>
<?php foreach ( $zhiraf_groups as $group ) : ?>
<h3 class="hot-bento__group"><?php echo esc_html( $group['heading'] ); ?></h3>
<div class="hot-bento hot-bento--<?php echo esc_attr( $group['mod'] ); ?>">
	<?php
	foreach ( $group['tiles'] as $tile ) :
		$min   = zhiraf_ufa_get_country_min_tour( $tile['slug'] );
		// Label comes from the shown min-price tour itself (airline/route), never hardcoded.
		$flight     = zhiraf_ufa_tour_flight_info( $min, 'mow' === $group['mod'] ? 'Москва' : 'Уфа' );
		$price_date = $min && ! empty( $min['price_date'] ) ? (string) $min['price_date'] : '';
		$is_stale   = $min && $price_date !== $zhiraf_today;
		$price_txt  = $min ? number_format( (int) $min['price_rur'], 0, '.', ' ' ) : '';
		?>
		<a class="hot-bento__tile hot-bento__tile--<?php echo esc_attr( $tile['mod'] ); ?><?php echo $tile['image'] ? '' : ' hot-bento__tile--grad dest-grad dest-grad--' . esc_attr( $tile['slug'] ); ?>" href="<?php echo esc_url( home_url( '/tour/' . $tile['slug'] . '/' ) ); ?>">
			<?php if ( $tile['image'] ) : ?>
				<?php zhiraf_ufa_img( $tile['image'], $tile['title'], 'hot-bento__img', true ); ?>
			<?php else : ?>
				<span class="hot-bento__mark" aria-hidden="true"><?php echo esc_html( mb_substr( $tile['title'], 0, 1 ) ); ?></span>
			<?php endif; ?>
			<span class="hot-bento__shade" aria-hidden="true"></span>
			<span class="hot-bento__label">
				<span class="hot-bento__icon" aria-hidden="true"><?php echo esc_html( $flight['icon'] ); ?></span>
				<?php echo esc_html( $flight['label'] ); ?>
			</span>
			<span class="hot-bento__body">
				<?php if ( ! empty( $tile['kicker'] ) ) : ?>
					<span class="hot-bento__kicker"><?php echo esc_html( $tile['kicker'] ); ?></span>
				<?php endif; ?>
				<span class="hot-bento__title"><?php echo esc_html( $tile['title'] ); ?></span>
				<?php if ( $min ) : ?>
					<span class="hot-bento__sub">
						<?php
						/* translators: %s: price */
						printf( esc_html__( 'от %s ₽ за 2 взрослых', 'zhiraf-ufa' ), esc_html( $price_txt ) );
						?>
					</span>
					<?php if ( $is_stale ) : ?>
						<span class="hot-bento__note">
							<?php
							/* translators: 1: price, 2: date */
							printf( esc_html__( 'цена от %1$s ₽ на %2$s, уточните актуальную стоимость у менеджера', 'zhiraf-ufa' ), esc_html( $price_txt ), esc_html( $price_date ) );
							?>
						</span>
					<?php else : ?>
						<span class="hot-bento__note">
							<?php
							/* translators: %s: date */
							printf( esc_html__( 'цена на %1$s; рассчитана по курсу туроператора на %1$s, итоговая стоимость фиксируется при бронировании', 'zhiraf-ufa' ), esc_html( $price_date ) );
							?>
						</span>
					<?php endif; ?>
				<?php else : ?>
					<span class="hot-bento__sub"><?php esc_html_e( 'уточните актуальную стоимость у менеджера', 'zhiraf-ufa' ); ?></span>
				<?php endif; ?>
			</span>
		</a>
	<?php endforeach; ?>
</div>
<?php endforeach; ?>
