<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$faq_items  = zhiraf_ufa_home_faq_items();
?>
<main id="primary" class="site-main site-main--home">

	<section class="hero-premium" data-motion="hero">
		<div class="container hero-premium__inner">
			<div class="hero-premium__content reveal">
				<h1 class="hero-premium__title">
					<?php esc_html_e( 'Туры с прямым вылетом из Уфы — подбираем как для своей семьи', 'zhiraf-ufa' ); ?>
				</h1>
				<p class="hero-premium__subtitle">
					<?php esc_html_e( 'Подберём 2–4 проверенных варианта с вылетом из Уфы — с фиксацией цены в договоре и сопровождением до возвращения. Офис на Гагарина, 74/1.', 'zhiraf-ufa' ); ?>
				</p>
				<ul class="hero-trust-strip" aria-label="<?php esc_attr_e( 'Гарантии агентства', 'zhiraf-ufa' ); ?>">
					<li><?php esc_html_e( 'РТА 0012822', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'С 2011 года', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Договор и чек', 'zhiraf-ufa' ); ?></li>
				</ul>
				<div class="hero-premium__actions">
					<button type="button" class="btn btn--primary btn--lg" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
					<?php zhiraf_ufa_render_social_links( array( 'variant' => 'pill', 'class' => 'hero-social' ) ); ?>
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

	<?php get_template_part( 'template-parts/home/home-quiz-inline' ); ?>

	<section class="section section--how-it-works">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Как мы подбираем тур: пошаговый процесс', 'zhiraf-ufa' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'От заявки и пожеланий до контроля вылета и сопровождения 24/7.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="steps-row">
				<div class="step-card reveal">
					<div class="step-number">01</div>
					<h3><?php esc_html_e( 'Заявка и пожелания', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Оставляете заявку или проходите квиз. Уточняем состав семьи, даты, бюджет и пожелания.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="step-card reveal reveal-delay-1">
					<div class="step-number">02</div>
					<h3><?php esc_html_e( 'Экспертный подбор', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Сравниваем операторов и готовим 2–4 проверенных варианта с вылетом из Уфы.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="step-card reveal reveal-delay-2">
					<div class="step-number">03</div>
					<h3><?php esc_html_e( 'Договор и оплата', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Официальный договор онлайн или в офисе на Гагарина, 74/1. Оплата по безналу или эквайрингу с чеком.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="step-card reveal reveal-delay-3">
					<div class="step-number">04</div>
					<h3><?php esc_html_e( 'Контроль вылета 24/7', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Выдаём ваучеры, отслеживаем рейс из аэропорта «Уфа» и остаёмся на связи до возвращения.', 'zhiraf-ufa' ); ?></p>
				</div>
			</div>
			<p class="steps-cta reveal">
				<button type="button" class="btn btn--outline" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
			</p>
		</div>
	</section>

	<?php
	if ( function_exists( 'zhiraf_ufa_show_home_team_teaser' ) && zhiraf_ufa_show_home_team_teaser() ) {
		get_template_part( 'template-parts/home/team-teaser' );
	}
	?>

	<section class="section section--trust trust-social">
		<div class="container trust-social__grid">
			<div class="trust-social__content reveal">
				<h2 class="section-title"><?php esc_html_e( 'Гарантии и работа агентства «Жираф»', 'zhiraf-ufa' ); ?></h2>
				<p class="lead" style="margin-top:12px;margin-bottom:16px;">
					<?php esc_html_e( 'Турагентство «Жираф» — семейная компания в Уфе с апреля 2011 года. Работаем открыто и строго по закону.', 'zhiraf-ufa' ); ?>
				</p>
				<ul class="trust-social__facts">
					<li><strong><?php esc_html_e( 'Официальный статус:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'ООО «Жираф», реестровый номер РТА 0012822.', 'zhiraf-ufa' ); ?></li>
					<li><strong><?php esc_html_e( 'Защита туристов:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'работа строго по 132-ФЗ и 152-ФЗ.', 'zhiraf-ufa' ); ?></li>
					<li><strong><?php esc_html_e( 'Туроператор:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'цены и бронирование — «Библио-Глобус».', 'zhiraf-ufa' ); ?></li>
					<li><strong><?php esc_html_e( 'Оплата и чеки:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'официальный договор, безналичная и эквайринг-оплата с чеком.', 'zhiraf-ufa' ); ?></li>
					<li><strong><?php esc_html_e( 'Офис в Уфе:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'ул. Юрия Гагарина, 74/1, офис 3.', 'zhiraf-ufa' ); ?></li>
				</ul>
				<div class="trust-social__links">
					<a href="tel:+79018105018" data-track="phone"><?php esc_html_e( '+7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты и карта', 'zhiraf-ufa' ); ?></a>
				</div>
				<?php zhiraf_ufa_render_social_links( array( 'variant' => 'icon', 'class' => 'trust-social__messengers' ) ); ?>
			</div>
			<div class="trust-social__visual reveal reveal-delay-1">
				<?php get_template_part( 'template-parts/home/trust-visual' ); ?>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home/trust-stats-strip' ); ?>

	<?php get_template_part( 'template-parts/home/unified-tour-catalog' ); ?>

	<section class="section section--offers-wordstat bg-milky">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Популярные форматы отдыха из Уфы', 'zhiraf-ufa' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Подбираем проверенные туры под ваш формат поездки: от срочных горящих предложений до спокойного семейного отдыха «всё включено».', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="chip-filter chip-filter--offers">
				<?php foreach ( zhiraf_ufa_offer_hub_links() as $offer ) : ?>
					<a class="chip chip--link" href="<?php echo esc_url( $offer['url'] ); ?>">
						<?php echo esc_html( $offer['label'] ); ?>
					</a>
				<?php endforeach; ?>
				<a class="chip chip--link" href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Каталог направлений', 'zhiraf-ufa' ); ?></a>
			</div>
			<p class="text-center reveal">
				<button type="button" class="btn btn--primary" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
			</p>
		</div>
	</section>

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
	<button type="button" class="btn btn--primary btn--sm" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
</div>
<?php
get_footer();
