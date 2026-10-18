<?php
/**
 * Resort landing layout (/tour/{country}/{resort}/).
 *
 * @package Zhiraf_Ufa
 * @var array $resort_data From zhiraf_ufa_get_resort_landing().
 */

if ( ! defined( 'ABSPATH' ) || empty( $resort_data ) ) {
	return;
}
$country_url = home_url( '/tour/' . $resort_data['country'] . '/' );
?>
<main id="primary" class="site-main site-main--resort">
	<section class="dest-landing-hero">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<p class="dest-landing-hero__eyebrow">
					<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?></a>
					·
					<a href="<?php echo esc_url( $country_url ); ?>"><?php echo esc_html( $resort_data['country_name'] ); ?></a>
				</p>
				<h1><?php echo esc_html( $resort_data['h1'] ); ?></h1>
				<p class="lead"><?php echo esc_html( $resort_data['hero_lead'] ); ?></p>
				<button type="button" class="btn btn--primary btn--lg" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $resort_data['quiz_prefill'] ); ?>">
					<?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?>
				</button>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $resort_data['image'], $resort_data['image_alt'], 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $resort_data['geo_answer'] ) ) : ?>
		<section class="section geo-answer-block bg-milky">
			<div class="container container--narrow">
				<p class="geo-answer-block__text"><?php echo esc_html( $resort_data['geo_answer'] ); ?></p>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $resort_data['sections'] ) ) : ?>
		<section class="section dest-landing-prose">
			<div class="container container--narrow">
				<?php foreach ( $resort_data['sections'] as $section ) : ?>
					<?php if ( ! empty( $section['h2'] ) ) : ?>
						<h2><?php echo esc_html( $section['h2'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['paragraphs'] ) ) : ?>
						<?php foreach ( $section['paragraphs'] as $para ) : ?>
							<p><?php echo wp_kses_post( $para ); ?></p>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="section dest-landing-faq">
		<div class="container container--narrow">
			<h2 class="section-title"><?php esc_html_e( 'Вопросы по курорту', 'zhiraf-ufa' ); ?></h2>
			<div class="faq-home__list">
				<?php foreach ( $resort_data['faq'] as $item ) : ?>
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
			<h2><?php esc_html_e( 'Готовы обсудить поездку?', 'zhiraf-ufa' ); ?></h2>
			<button type="button" class="btn btn--primary" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $resort_data['quiz_prefill'] ); ?>">
				<?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?>
			</button>
			<p class="dest-landing-cta__meta">
				<a href="<?php echo esc_url( $country_url ); ?>"><?php echo esc_html( $resort_data['country_name'] ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</section>
</main>
