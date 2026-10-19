<?php
/**
 * Destination landing layout (included via page-{slug}.php).
 *
 * @package Zhiraf_Ufa
 * @var array $dest Landing data from zhiraf_ufa_get_destination_landing().
 */

if ( ! defined( 'ABSPATH' ) || empty( $dest ) ) {
	return;
}
?>
<main id="primary" class="site-main site-main--destination">
	<section class="dest-landing-hero">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<p class="dest-landing-hero__eyebrow">
					<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?></a>
				</p>
				<h1><?php echo esc_html( $dest['h1'] ); ?></h1>
				<p class="lead"><?php echo esc_html( ! empty( $dest['hero_lead_extended'] ) ? $dest['hero_lead_extended'] : $dest['hero_lead'] ); ?></p>
				<button type="button" class="btn btn--primary btn--lg" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>">
					<?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?>
				</button>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $dest['image'], $dest['image_alt'], 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<?php
	$dest_slug  = ! empty( $dest['slug'] ) ? $dest['slug'] : ( isset( $slug ) ? $slug : '' );
	if ( empty( $dest_slug ) ) {
		$dest_slug = get_post_field( 'post_name', get_post() );
	}
	$dest_tours = ! empty( $dest_slug ) ? zhiraf_ufa_get_country_tours( $dest_slug, 3 ) : array();
	?>
	<?php if ( ! empty( $dest_tours ) ) : ?>
		<section class="section section--dest-tours section--dest-tours-top bg-sand" id="dest-tours">
			<div class="container">
				<div class="section-head reveal">
					<div>
						<h2 class="section-title">
							<?php
							printf(
								/* translators: %s: country name in prepositional */
								esc_html__( 'Актуальные туры %s из Уфы', 'zhiraf-ufa' ),
								esc_html( zhiraf_ufa_country_prepositional( $dest_slug ) )
							);
							?>
						</h2>
						<p class="lead" style="margin-top:8px;margin-bottom:0;color:var(--color-muted);">
							<?php esc_html_e( 'Три самых выгодных отеля по ценам туроператора Библио-Глобус для 2 взрослых с вылетом из Уфы. Обновление каждые 6 часов.', 'zhiraf-ufa' ); ?>
						</p>
					</div>
				</div>

				<div class="tour-price-grid tour-price-grid--dest tour-price-grid--top">
					<?php foreach ( $dest_tours as $tour_item ) : ?>
						<?php get_template_part( 'template-parts/cards/card-tour-price', null, array( 'tour' => $tour_item ) ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $dest['geo_answer'] ) ) : ?>
		<section class="section geo-answer-block bg-milky">
			<div class="container container--narrow">
				<p class="geo-answer-block__text"><?php echo esc_html( $dest['geo_answer'] ); ?></p>
			</div>
		</section>
	<?php endif; ?>


	<section class="section dest-landing-prose bg-milky">
		<div class="container container--narrow">
			<h2><?php esc_html_e( 'Для кого подойдёт', 'zhiraf-ufa' ); ?></h2>
			<p><?php echo esc_html( $dest['for_who'] ); ?></p>
			<h2><?php esc_html_e( 'Что уточняем при подборе', 'zhiraf-ufa' ); ?></h2>
			<p><?php echo esc_html( $dest['we_clarify'] ); ?></p>
			<h2><?php esc_html_e( 'Сезон и климат', 'zhiraf-ufa' ); ?></h2>
			<p><?php echo esc_html( $dest['season'] ); ?></p>
			<?php if ( ! empty( $dest['extra_sections'] ) ) : ?>
				<?php foreach ( $dest['extra_sections'] as $section ) : ?>
					<?php if ( ! empty( $section['h2'] ) ) : ?>
						<h2><?php echo esc_html( $section['h2'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['lead'] ) ) : ?>
						<p><?php echo esc_html( $section['lead'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $section['paragraphs'] ) ) : ?>
						<?php foreach ( $section['paragraphs'] as $para ) : ?>
							<p><?php echo wp_kses_post( $para ); ?></p>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ( ! empty( $dest['resort_links'] ) ) : ?>
				<h2><?php esc_html_e( 'Курорты направления', 'zhiraf-ufa' ); ?></h2>
				<ul class="resort-link-list">
					<?php foreach ( $dest['resort_links'] as $link ) : ?>
						<li>
							<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
							<?php if ( ! empty( $link['desc'] ) ) : ?>
								<span class="resort-link-list__desc"><?php echo esc_html( $link['desc'] ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( ! empty( $dest['blog_links'] ) ) : ?>
				<p class="dest-landing-links">
					<?php
					foreach ( $dest['blog_links'] as $link ) {
						printf(
							'<a href="%s">%s</a>',
							esc_url( $link['url'] ),
							esc_html( $link['label'] )
						);
					}
					?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<section class="section dest-landing-faq">
		<div class="container container--narrow">
			<h2 class="section-title"><?php esc_html_e( 'Вопросы по направлению', 'zhiraf-ufa' ); ?></h2>
			<div class="faq-home__list">
				<?php foreach ( $dest['faq'] as $item ) : ?>
					<details class="faq-home__item">
						<summary class="faq-home__question"><?php echo esc_html( $item['q'] ); ?></summary>
						<div class="faq-home__answer">
							<p><?php echo esc_html( $item['a'] ); ?></p>
							<?php if ( ! empty( $item['link'] ) ) : ?>
								<p><a href="<?php echo esc_url( $item['link'] ); ?>"><?php esc_html_e( 'Подробнее в блоге', 'zhiraf-ufa' ); ?></a></p>
							<?php endif; ?>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section dest-landing-cta">
		<div class="container container--narrow dest-landing-cta__inner">
			<h2><?php esc_html_e( 'Готовы обсудить поездку?', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Оставьте заявку в квизе — менеджер предложит 2–4 проверенных варианта с вылетом из Уфы.', 'zhiraf-ufa' ); ?></p>
			<button type="button" class="btn btn--primary" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>">
				<?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?>
			</button>
			<p class="dest-landing-cta__meta">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'zhiraf-ufa' ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты', 'zhiraf-ufa' ); ?></a>
				·
				<a href="tel:+79018105018" data-track="phone"><?php esc_html_e( '+7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</section>
</main>
