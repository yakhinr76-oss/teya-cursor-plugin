<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$dest_all   = zhiraf_ufa_destinations();
$audience   = zhiraf_ufa_audience_segments();
$faq_items  = zhiraf_ufa_home_faq_items();
$teaser     = zhiraf_ufa_blog_teaser_bt01();
?>
<main id="primary" class="site-main site-main--home">

	<section class="hero-premium" data-motion="hero">
		<div class="container hero-premium__inner">
			<div class="hero-premium__content reveal">
				<h1 class="hero-premium__title">
					<?php esc_html_e( 'Туры с вылетом из Уфы — подбор без давления', 'zhiraf-ufa' ); ?>
				</h1>
				<p class="hero-premium__subtitle">
					<?php esc_html_e( 'Устали от безликого поиска и скрытых доплат? Подберём 2–4 варианта с вылетом из Уфы — честно, без впаривания. Офис на Гагарина, 74/1.', 'zhiraf-ufa' ); ?>
				</p>
				<div class="hero-premium__actions">
					<button type="button" class="btn btn--primary btn--lg" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
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

	<section class="section section--audience bg-milky" id="audience-tabs">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Кому подойдёт наш подбор', 'zhiraf-ufa' ); ?></h2>
			</div>
			<div class="audience-tabs">
				<div class="audience-tabs__nav" role="tablist" aria-label="<?php esc_attr_e( 'Сегменты аудитории', 'zhiraf-ufa' ); ?>">
					<?php foreach ( $audience as $i => $seg ) : ?>
						<button
							type="button"
							class="audience-tabs__tab<?php echo 0 === $i ? ' is-active' : ''; ?>"
							role="tab"
							id="audience-tab-<?php echo esc_attr( $seg['id'] ); ?>"
							aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
							aria-controls="audience-panel-<?php echo esc_attr( $seg['id'] ); ?>"
							data-audience="<?php echo esc_attr( $seg['id'] ); ?>"
						><?php echo esc_html( $seg['label'] ); ?></button>
					<?php endforeach; ?>
				</div>
				<?php foreach ( $audience as $i => $seg ) : ?>
					<div
						class="audience-tabs__panel reveal<?php echo 0 === $i ? ' is-active' : ''; ?>"
						role="tabpanel"
						id="audience-panel-<?php echo esc_attr( $seg['id'] ); ?>"
						aria-labelledby="audience-tab-<?php echo esc_attr( $seg['id'] ); ?>"
						data-audience-panel="<?php echo esc_attr( $seg['id'] ); ?>"
						<?php echo $i > 0 ? 'hidden' : ''; ?>
					>
						<ul class="audience-tabs__list">
							<?php foreach ( $seg['items'] as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
						<div class="audience-tabs__actions">
							<button type="button" class="btn btn--primary" data-quiz-open><?php echo esc_html( $seg['cta'] ); ?></button>
							<a class="audience-tabs__link" href="<?php echo esc_url( $seg['link'] ); ?>"><?php esc_html_e( 'Подробнее', 'zhiraf-ufa' ); ?></a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section section--destinations bg-milky">
		<div class="container destinations">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Направления', 'zhiraf-ufa' ); ?></h2>
				<div class="section-tabs dest-tabs" role="tablist">
					<button type="button" class="tab-btn is-active" data-filter="all"><?php esc_html_e( 'Популярные', 'zhiraf-ufa' ); ?></button>
					<button type="button" class="tab-btn" data-filter="oae"><?php esc_html_e( 'ОАЭ', 'zhiraf-ufa' ); ?></button>
					<button type="button" class="tab-btn" data-filter="turciya"><?php esc_html_e( 'Турция', 'zhiraf-ufa' ); ?></button>
					<button type="button" class="tab-btn" data-filter="egipet"><?php esc_html_e( 'Египет', 'zhiraf-ufa' ); ?></button>
					<button type="button" class="tab-btn" data-filter="tailand"><?php esc_html_e( 'Таиланд', 'zhiraf-ufa' ); ?></button>
					<button type="button" class="tab-btn" data-filter="vietnam"><?php esc_html_e( 'Вьетнам', 'zhiraf-ufa' ); ?></button>
				</div>
			</div>
			<div class="dest-premium-row">
				<?php foreach ( $dest_all as $dest ) : ?>
					<a href="<?php echo esc_url( $dest['url'] ); ?>" class="dest-premium-card dest-card" data-dest="<?php echo esc_attr( $dest['slug'] ); ?>">
						<div class="dest-premium-card__bg">
							<?php zhiraf_ufa_img( $dest['image'], $dest['alt'], 'dest-premium-card__img', true ); ?>
						</div>
						<div class="dest-premium-card__content">
							<h3 class="dest-premium-card__title"><?php echo esc_html( $dest['name'] ); ?></h3>
							<p class="dest-premium-card__subtitle"><?php esc_html_e( 'Туры из Уфы', 'zhiraf-ufa' ); ?></p>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section section--how-it-works">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Как это работает', 'zhiraf-ufa' ); ?></h2>
			</div>
			<div class="steps-row">
				<div class="step-card reveal">
					<div class="step-number">01</div>
					<h3><?php esc_html_e( 'Заявка', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Оставляете заявку или проходите короткий квиз на сайте.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="step-card reveal reveal-delay-1">
					<div class="step-number">02</div>
					<h3><?php esc_html_e( 'Подбор', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Менеджер предлагает 2–4 варианта под даты, бюджет и состав.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="step-card reveal reveal-delay-2">
					<div class="step-number">03</div>
					<h3><?php esc_html_e( 'Договор', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Оформляем договор онлайн или в офисе на Гагарина.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="step-card reveal reveal-delay-3">
					<div class="step-number">04</div>
					<h3><?php esc_html_e( 'Отдых', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Вы летите с вылетом из Уфы — мы на связи до возвращения.', 'zhiraf-ufa' ); ?></p>
				</div>
			</div>
			<p class="steps-cta reveal">
				<button type="button" class="btn btn--outline" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
			</p>
		</div>
	</section>

	<section class="section section--trust trust-social">
		<div class="container trust-social__grid">
			<div class="trust-social__content reveal">
				<h2 class="section-title"><?php esc_html_e( 'Почему нам доверяют в Уфе', 'zhiraf-ufa' ); ?></h2>
				<ul class="trust-social__facts">
					<li><strong><?php esc_html_e( 'ООО «Жираф»', 'zhiraf-ufa' ); ?></strong> — <?php esc_html_e( 'РТА 0012822', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Семейное агентство с апреля 2011 года', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Офис: ул. Юрия Гагарина, 74/1, офис 3', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Пн–Пт 10:00–19:00, Сб 11:00–15:00', 'zhiraf-ufa' ); ?></li>
				</ul>
				<div class="trust-social__links">
					<a href="tel:+79018105018" data-track="phone"><?php esc_html_e( '+7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
					<a href="https://vk.com/zhiraf_ufa" rel="noopener noreferrer" target="_blank"><?php esc_html_e( 'Мы ВКонтакте', 'zhiraf-ufa' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты и карта', 'zhiraf-ufa' ); ?></a>
				</div>
			</div>
			<div class="trust-social__visual reveal reveal-delay-1">
				<?php zhiraf_ufa_img( 'office-ufa.jpg', __( 'Офис турагентства Жираф в Уфе', 'zhiraf-ufa' ), 'trust-social__img', true ); ?>
			</div>
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

	<section class="section section--form lead-action" id="lead-action">
		<div class="container reveal">
			<div class="form-premium">
				<div class="form-premium__text">
					<h2 class="section-title"><?php esc_html_e( 'Оставьте заявку на подбор', 'zhiraf-ufa' ); ?></h2>
					<p><?php esc_html_e( 'Перезвоним в рабочее время и уточним детали поездки.', 'zhiraf-ufa' ); ?></p>
				</div>
				<form class="zhiraf-form" data-action="zhiraf_lead">
					<div class="form-premium__fields">
						<input type="text" name="name" placeholder="<?php esc_attr_e( 'Ваше имя', 'zhiraf-ufa' ); ?>" required class="form-input" />
						<input type="tel" name="phone" placeholder="<?php esc_attr_e( 'Телефон', 'zhiraf-ufa' ); ?>" required class="form-input" />
						<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Отправить', 'zhiraf-ufa' ); ?></button>
					</div>
					<?php zhiraf_ufa_consent_fields( 'home-lead' ); ?>
				</form>
				<p class="form-status" role="status"></p>
				<p class="form-premium__alt">
					<button type="button" class="btn btn--outline btn--sm" data-quiz-open><?php esc_html_e( 'Или пройти квиз', 'zhiraf-ufa' ); ?></button>
				</p>
			</div>
		</div>
	</section>

	<section class="section section--editorial editorial-blog-teaser bg-milky">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Полезные статьи', 'zhiraf-ufa' ); ?></h2>
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="btn btn--outline btn--sm" data-track="blog_teaser"><?php esc_html_e( 'Все статьи', 'zhiraf-ufa' ); ?></a>
			</div>
			<article class="editorial-teaser reveal">
				<a href="<?php echo esc_url( $teaser['url'] ); ?>" class="editorial-main__link">
					<div class="editorial-main__img-wrap">
						<?php
						if ( ! empty( $teaser['post_id'] ) ) {
							$thumb_id = get_post_thumbnail_id( $teaser['post_id'] );
							if ( $thumb_id ) {
								echo wp_get_attachment_image( $thumb_id, 'large', false, array( 'class' => 'editorial-main__img' ) );
							} else {
								zhiraf_ufa_img( 'blog-cover-01.jpg', $teaser['title'], 'editorial-main__img', true );
							}
						} else {
							zhiraf_ufa_img( $teaser['image'], $teaser['title'], 'editorial-main__img', true );
						}
						?>
					</div>
					<div class="editorial-main__content">
						<span class="editorial-cat"><?php echo esc_html( $teaser['category'] ); ?></span>
						<h3 class="editorial-main__title"><?php echo esc_html( $teaser['title'] ); ?></h3>
						<p class="editorial-main__excerpt"><?php echo esc_html( $teaser['excerpt'] ); ?></p>
					</div>
				</a>
			</article>
		</div>
	</section>

	<section class="section section--subscribe section--subscribe-hold" aria-label="<?php esc_attr_e( 'Подписка на рассылку', 'zhiraf-ufa' ); ?>">
		<div class="subscribe-premium__bg">
			<?php zhiraf_ufa_img( 'subscribe-bg.jpg', __( 'Подписка на подборки туров', 'zhiraf-ufa' ), 'subscribe-premium__img', false, true ); ?>
			<div class="subscribe-premium__overlay"></div>
		</div>
		<div class="container reveal">
			<div class="subscribe-premium__content">
				<h2><?php esc_html_e( 'Подборки туров и советы — на почту', 'zhiraf-ufa' ); ?></h2>
				<p class="subscribe-premium__hold"><?php esc_html_e( 'Рассылка готовится — оставьте заявку через квиз или позвоните, пока подключаем email-канал.', 'zhiraf-ufa' ); ?></p>
				<p><button type="button" class="btn btn--primary" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button></p>
			</div>
		</div>
	</section>

</main>

<div class="sticky-mobile-cta" id="sticky-mobile-cta" hidden>
	<a href="tel:+79018105018" class="btn btn--outline btn--sm" data-track="phone"><?php esc_html_e( 'Позвонить', 'zhiraf-ufa' ); ?></a>
	<button type="button" class="btn btn--primary btn--sm" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
</div>
<?php
get_footer();
