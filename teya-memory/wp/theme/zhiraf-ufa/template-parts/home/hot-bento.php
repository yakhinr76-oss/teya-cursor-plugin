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

$zhiraf_bento = array(
	array( 'slug' => 'turciya', 'title' => __( 'Турция', 'zhiraf-ufa' ), 'image' => 'dest-turkey.jpg', 'direct' => true, 'mod' => 'wide' ),
	array( 'slug' => 'egipet', 'title' => __( 'Египет', 'zhiraf-ufa' ), 'image' => 'dest-egypt.jpg', 'direct' => true, 'mod' => 'tall' ),
	array( 'slug' => 'oae', 'title' => __( 'ОАЭ', 'zhiraf-ufa' ), 'image' => 'dest-uae.jpg', 'direct' => true, 'mod' => 'small' ),
	array( 'slug' => 'tailand', 'title' => __( 'Таиланд', 'zhiraf-ufa' ), 'image' => 'dest-thailand.jpg', 'direct' => false, 'mod' => 'small' ),
	array( 'slug' => 'vietnam', 'title' => __( 'Вьетнам', 'zhiraf-ufa' ), 'image' => 'dest-vietnam.jpg', 'direct' => false, 'mod' => 'banner' ),
);
$zhiraf_today = function_exists( 'wp_date' ) ? wp_date( 'd.m.Y' ) : date( 'd.m.Y' );
?>
<div class="hot-bento">
	<?php
	foreach ( $zhiraf_bento as $tile ) :
		$tours = zhiraf_ufa_get_country_tours( $tile['slug'], 10 );
		$min   = null;
		foreach ( (array) $tours as $t ) {
			if ( ! empty( $t['price_rur'] ) && ( null === $min || (int) $t['price_rur'] < (int) $min['price_rur'] ) ) {
				$min = $t;
			}
		}
		$price_date = $min && ! empty( $min['price_date'] ) ? (string) $min['price_date'] : '';
		$is_stale   = $min && $price_date !== $zhiraf_today;
		$price_txt  = $min ? number_format( (int) $min['price_rur'], 0, '.', ' ' ) : '';
		?>
		<a class="hot-bento__tile hot-bento__tile--<?php echo esc_attr( $tile['mod'] ); ?>" href="<?php echo esc_url( home_url( '/tour/' . $tile['slug'] . '/' ) ); ?>">
			<?php zhiraf_ufa_img( $tile['image'], $tile['title'], 'hot-bento__img', true ); ?>
			<span class="hot-bento__shade" aria-hidden="true"></span>
			<span class="hot-bento__label">
				<span class="hot-bento__icon" aria-hidden="true"><?php echo $tile['direct'] ? '✈' : '⇄'; ?></span>
				<?php echo $tile['direct'] ? esc_html__( 'прямой вылет', 'zhiraf-ufa' ) : esc_html__( 'через Москву', 'zhiraf-ufa' ); ?>
			</span>
			<span class="hot-bento__body">
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
