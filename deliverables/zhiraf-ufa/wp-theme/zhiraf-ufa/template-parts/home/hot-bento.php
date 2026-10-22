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
<?php if ( 'mow' === $group['mod'] ) : ?>
	<p class="hot-bento__group-note"><?php esc_html_e( 'Цены указаны за прямой вылет из Москвы. Поможем состыковать с удобным рейсом из аэропорта «Уфа» и посчитаем полную смету до договора.', 'zhiraf-ufa' ); ?></p>
<?php endif; ?>
<div class="hot-bento hot-bento--<?php echo esc_attr( $group['mod'] ); ?>">
	<?php
	foreach ( $group['tiles'] as $tile ) :
		$min          = zhiraf_ufa_get_country_min_tour( $tile['slug'] );
		$flight       = zhiraf_ufa_tour_flight_info( $min, 'mow' === $group['mod'] ? 'Москва' : 'Уфа' );
		$price_txt    = $min ? number_format( (int) $min['price_rur'], 0, '.', ' ' ) : '';
		$package_line = zhiraf_ufa_bento_package_summary( $min );
		$tour_url     = home_url( '/tour/' . $tile['slug'] . '/' );
		?>
		<article class="hot-bento__tile hot-bento__tile--<?php echo esc_attr( $tile['mod'] ); ?><?php echo $tile['image'] ? '' : ' hot-bento__tile--grad dest-grad dest-grad--' . esc_attr( $tile['slug'] ); ?>">
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
			<div class="hot-bento__body">
				<?php if ( ! empty( $tile['kicker'] ) ) : ?>
					<span class="hot-bento__kicker"><?php echo esc_html( $tile['kicker'] ); ?></span>
				<?php endif; ?>
				<h3 class="hot-bento__title"><?php echo esc_html( $tile['title'] ); ?></h3>
				<?php if ( '' !== $package_line ) : ?>
					<p class="hot-bento__meta"><?php echo esc_html( $package_line ); ?></p>
				<?php endif; ?>
				<?php if ( $min ) : ?>
					<p class="hot-bento__sub">
						<?php
						printf(
							esc_html__( 'от %s ₽ за 2 взрослых', 'zhiraf-ufa' ),
							esc_html( $price_txt )
						);
						?>
					</p>
				<?php else : ?>
					<p class="hot-bento__sub"><?php esc_html_e( 'уточните актуальную стоимость у менеджера', 'zhiraf-ufa' ); ?></p>
				<?php endif; ?>
				<a class="btn btn--primary btn--sm hot-bento__cta" href="<?php echo esc_url( $tour_url ); ?>">
					<?php esc_html_e( 'Смотреть туры', 'zhiraf-ufa' ); ?>
					<span aria-hidden="true"> →</span>
				</a>
			</div>
		</article>
	<?php endforeach; ?>
</div>
<?php endforeach; ?>
