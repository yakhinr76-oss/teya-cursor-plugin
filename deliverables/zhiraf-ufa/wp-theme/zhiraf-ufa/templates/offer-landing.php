<?php
/**
 * Offer landing layout.
 *
 * @package Zhiraf_Ufa
 * @var array $offer Offer data from zhiraf_ufa_get_offer_landing().
 */

if ( ! defined( 'ABSPATH' ) || empty( $offer ) ) {
	return;
}
$cta = ! empty( $offer['cta_primary'] ) ? $offer['cta_primary'] : __( 'Подобрать тур', 'zhiraf-ufa' );
?>
<main id="primary" class="site-main site-main--offer">
	<section class="dest-landing-hero offer-landing-hero">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<?php if ( ! empty( $offer['eyebrow'] ) ) : ?>
					<p class="dest-landing-hero__eyebrow"><?php echo esc_html( $offer['eyebrow'] ); ?></p>
				<?php endif; ?>
				<h1><?php echo esc_html( $offer['h1'] ); ?></h1>
				<p class="lead"><?php echo esc_html( $offer['hero_lead'] ); ?></p>
				<button type="button" class="btn btn--primary btn--lg" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $offer['quiz_prefill'] ); ?>">
					<?php echo esc_html( $cta ); ?>
				</button>
				<p class="dest-landing-cta__meta">
					<a href="tel:+79018105018" data-track="phone"><?php esc_html_e( '+7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
				</p>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $offer['image'], $offer['image_alt'], 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<?php
	$zhiraf_cards = ( isset( $slug ) && 'goryashchie-tury' === $slug && class_exists( 'Zhiraf_BG_Price_Engine' ) ) ? array_values( array_filter( array_map( 'zhiraf_ufa_get_country_min_tour', array_keys( Zhiraf_BG_Price_Engine::$countries ) ) ) ) : array();
	?>
	<?php if ( ! empty( $zhiraf_cards ) ) : ?>
		<section class="section section--dest-tours section--dest-tours-top bg-sand" id="dest-tours">
			<div class="container">
				<div class="section-head reveal">
					<div>
						<h2 class="section-title"><?php echo esc_html( __( 'Самые выгодные туры по направлениям', 'zhiraf-ufa' ) ); ?></h2>
						<p class="lead" style="margin-top:8px;margin-bottom:0;color:var(--color-muted);"><?php echo esc_html( __( 'Минимальная цена туроператора Библио-Глобус по каждой стране для 2 взрослых (перелёт и отель): из Уфы — 5 направлений, остальные — с вылетом из Москвы (перелёт Уфа — Москва не входит). Обновление каждые 6 часов.', 'zhiraf-ufa' ) ); ?></p>
					</div>
				</div>
				<div class="tour-price-grid tour-price-grid--dest tour-price-grid--top">
					<?php foreach ( $zhiraf_cards as $tour_item ) : ?>
						<?php get_template_part( 'template-parts/cards/card-tour-price', null, array( 'tour' => $tour_item ) ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $offer['geo_answer'] ) ) : ?>
		<section class="section geo-answer-block bg-milky">
			<div class="container container--narrow">
				<p class="geo-answer-block__text"><?php echo esc_html( $offer['geo_answer'] ); ?></p>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $offer['sections'] ) ) : ?>
		<?php foreach ( $offer['sections'] as $section ) : ?>
			<section class="section dest-landing-prose">
				<div class="container container--narrow">
					<?php if ( ! empty( $section['h2'] ) ) : ?>
						<h2><?php echo esc_html( $section['h2'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['lead'] ) ) : ?>
						<p class="lead"><?php echo esc_html( $section['lead'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $section['paragraphs'] ) ) : ?>
						<?php foreach ( $section['paragraphs'] as $para ) : ?>
							<p><?php echo wp_kses_post( $para ); ?></p>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</section>
		<?php endforeach; ?>
	<?php endif; ?>

	<section class="section dest-landing-faq">
		<div class="container container--narrow">
			<h2 class="section-title"><?php esc_html_e( 'Частые вопросы', 'zhiraf-ufa' ); ?></h2>
			<div class="faq-home__list">
				<?php foreach ( $offer['faq'] as $item ) : ?>
					<details class="faq-home__item">
						<summary class="faq-home__question"><?php echo esc_html( $item['q'] ); ?></summary>
						<div class="faq-home__answer">
							<p><?php echo esc_html( $item['a'] ); ?></p>
							<?php if ( ! empty( $item['link'] ) ) : ?>
								<p><a href="<?php echo esc_url( $item['link'] ); ?>"><?php esc_html_e( 'Подробнее', 'zhiraf-ufa' ); ?></a></p>
							<?php endif; ?>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section dest-landing-cta">
		<div class="container container--narrow dest-landing-cta__inner">
			<h2><?php esc_html_e( 'Узнать актуальные варианты', 'zhiraf-ufa' ); ?></h2>
			<button type="button" class="btn btn--primary" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $offer['quiz_prefill'] ); ?>">
				<?php echo esc_html( $cta ); ?>
			</button>
			<p class="dest-landing-cta__meta">
				<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Каталог направлений', 'zhiraf-ufa' ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</section>
</main>
