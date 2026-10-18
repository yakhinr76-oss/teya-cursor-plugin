<?php
/**
 * Template Name: Каталог туров
 * Template Post Type: page
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main site-main--catalog">
	<section class="page-hero page-hero--catalog">
		<div class="container catalog-hero">
			<div>
				<h1><?php esc_html_e( 'Туры с вылетом из Уфы', 'zhiraf-ufa' ); ?></h1>
				<p class="lead"><?php esc_html_e( 'Турция, ОАЭ, Египет, Таиланд, Вьетнам и отдых по России — подбор под даты и бюджет без фиктивных цен «от».', 'zhiraf-ufa' ); ?></p>
			</div>
			<?php zhiraf_ufa_img( 'catalog-hub.jpg', __( 'Путешествие', 'zhiraf-ufa' ), 'catalog-hero__img', true ); ?>
		</div>
	</section>
	<section class="section destinations destinations--catalog">
		<div class="container">
			<div class="chip-filter" role="tablist">
				<button type="button" class="chip is-active" data-filter="all"><?php esc_html_e( 'Все', 'zhiraf-ufa' ); ?></button>
				<?php foreach ( zhiraf_ufa_destinations() as $dest ) : ?>
					<button type="button" class="chip" data-filter="<?php echo esc_attr( $dest['slug'] ); ?>"><?php echo esc_html( $dest['name'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="dest-grid dest-grid--large">
				<?php foreach ( zhiraf_ufa_destinations() as $dest ) : ?>
					<article class="dest-card dest-card--large" data-dest="<?php echo esc_attr( $dest['slug'] ); ?>">
						<a href="<?php echo esc_url( $dest['url'] ); ?>">
							<?php zhiraf_ufa_img( $dest['image'], $dest['alt'], 'dest-card__img', true ); ?>
							<h2><?php echo esc_html( $dest['name'] ); ?></h2>
							<p><?php esc_html_e( 'Подбор отеля и перелёта из Уфы — консультация бесплатно.', 'zhiraf-ufa' ); ?></p>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<section class="section">
		<div class="container narrow">
			<h2><?php esc_html_e( 'Не нашли направление?', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Оставьте заявку — подберём индивидуально или предложим ближайшие альтернативы.', 'zhiraf-ufa' ); ?></p>
			<button type="button" class="btn btn--primary" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
			<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'zhiraf-ufa' ); ?></a> · <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Блог', 'zhiraf-ufa' ); ?></a></p>
		</div>
	</section>
</main>
<?php
get_footer();
