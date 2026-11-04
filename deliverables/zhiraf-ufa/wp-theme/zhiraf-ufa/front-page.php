<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$faq_items = zhiraf_ufa_home_faq_items();
$quiz_cta  = zhiraf_ufa_quiz_cta_label();
?>
<main id="primary" class="site-main site-main--home">

	<section class="hero-premium" data-motion="hero">
		<div class="container hero-premium__inner">
			<div class="hero-premium__content reveal">
				<h1 class="hero-premium__title">
					<?php esc_html_e( 'Туры с прямым вылетом из Уфы — честный подбор по ценам туроператоров', 'zhiraf-ufa' ); ?>
				</h1>
				<p class="hero-premium__subtitle">
					<?php esc_html_e( 'Показываем реальную стоимость туроператоров без скрытых доплат. Проверенная база 400 отелей с честным разбором плюсов и нюансов.', 'zhiraf-ufa' ); ?>
				</p>
				<?php get_template_part( 'template-parts/home/hero', 'quick-chips' ); ?>
				<div class="hero-premium__actions">
					<button type="button" class="btn btn--primary btn--lg" onclick="document.getElementById('destinations-hub') && document.getElementById('destinations-hub').scrollIntoView({behavior: 'smooth'})">
						<?php esc_html_e( 'Выбрать направление отдыха', 'zhiraf-ufa' ); ?>
					</button>
					<button type="button" class="btn btn--outline btn--lg" data-quiz-open data-quiz-trigger="hero">
						<?php esc_html_e( 'Подобрать тур через квиз (1 мин)', 'zhiraf-ufa' ); ?>
					</button>
				</div>
			</div>

			<div class="hero-premium__collage reveal reveal-delay-1" data-three-canvas="hero-ambient">
				<div class="hero-ambient-canvas" aria-hidden="true"></div>
				<div class="hero-collage__img hero-collage__img--1 collage-tile">
					<?php zhiraf_ufa_img( 'hero-uae.jpg', 'ОАЭ', '', false, true ); ?>
				</div>
				<div class="hero-collage__img hero-collage__img--2 collage-tile">
					<?php zhiraf_ufa_img( 'hero-turkey.jpg', 'Турция', '', false, true ); ?>
				</div>
				<div class="hero-collage__img hero-collage__img--3 collage-tile">
					<?php zhiraf_ufa_img( 'hero-egypt.jpg', 'Египет', '', false, true ); ?>
				</div>
				<svg class="hero-collage__flight-path" data-motion="flight-path" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M10,180 Q100,50 380,80" stroke="var(--color-primary)" stroke-width="2" stroke-dasharray="6,6" fill="none"/>
					<path d="M370,75 L385,81 L370,87 Z" fill="var(--color-primary)" />
				</svg>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home/home-guarantees-bar' ); ?>

	<?php get_template_part( 'template-parts/home/destinations-hub' ); ?>

	<?php get_template_part( 'template-parts/home/life-scenarios' ); ?>

	<?php get_template_part( 'template-parts/home/home-office-guarantees' ); ?>

	<section class="section section--faq faq-home bg-milky" id="faq-home">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Частые вопросы', 'zhiraf-ufa' ); ?></h2>
			</div>
			<div class="faq-home__list">
				<?php foreach ( $faq_items as $faq ) : ?>
					<details class="faq-home__item reveal">
						<summary class="faq-home__question"><?php echo esc_html( $faq['q'] ); ?></summary>
						<div class="faq-home__answer">
							<p><?php echo esc_html( $faq['a'] ); ?></p>
							<?php if ( ! empty( $faq['link'] ) ) : ?>
								<p><a href="<?php echo esc_url( $faq['link'] ); ?>"><?php esc_html_e( 'Читать в блоге', 'zhiraf-ufa' ); ?></a></p>
							<?php endif; ?>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home/lead-magnet' ); ?>

	<section class="section section--editorial editorial-blog-teaser bg-milky">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Полезные статьи', 'zhiraf-ufa' ); ?></h2>
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="btn btn--outline btn--sm" data-track="blog_teaser"><?php esc_html_e( 'Все статьи', 'zhiraf-ufa' ); ?></a>
			</div>
			<?php get_template_part( 'template-parts/blog/editorial-globe' ); ?>
		</div>
	</section>

</main>

<div class="sticky-mobile-cta" id="sticky-mobile-cta" hidden>
	<a href="tel:+79018105018" class="btn btn--outline btn--sm" data-track="phone"><?php esc_html_e( 'Позвонить', 'zhiraf-ufa' ); ?></a>
	<button type="button" class="btn btn--primary btn--sm" data-quiz-open data-cta-context="cta_sticky_quiz_open"><?php echo esc_html( $quiz_cta ); ?></button>
</div>
<?php
get_footer();
