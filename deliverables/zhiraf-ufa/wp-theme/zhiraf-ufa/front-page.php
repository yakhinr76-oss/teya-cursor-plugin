<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$dest_all   = zhiraf_ufa_destinations();
$audience   = zhiraf_ufa_audience_segments();
$faq_items  = zhiraf_ufa_home_faq_items();
?>
<main id="primary" class="site-main site-main--home">

	<section class="hero-premium" data-motion="hero">
		<div class="container hero-premium__inner">
			<div class="hero-premium__content reveal">
				<h1 class="hero-premium__title">
					<?php esc_html_e( 'Туры с вылетом из Уфы — подбор без давления', 'zhiraf-ufa' ); ?>
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
					<?php
					$zhiraf_wa = trim( (string) get_theme_mod( 'zhiraf_whatsapp_url', 'https://wa.me/79018105018' ) );
					$zhiraf_tg = trim( (string) get_theme_mod( 'zhiraf_telegram_url', '' ) );
					if ( '' !== $zhiraf_wa ) :
						?>
						<a class="btn btn--outline btn--lg" href="<?php echo esc_url( $zhiraf_wa ); ?>" target="_blank" rel="noopener noreferrer" data-track="messenger" data-channel="whatsapp"><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></a>
					<?php endif; ?>
					<?php if ( '' !== $zhiraf_tg ) : ?>
						<a class="btn btn--outline btn--lg" href="<?php echo esc_url( $zhiraf_tg ); ?>" target="_blank" rel="noopener noreferrer" data-track="messenger" data-channel="telegram"><?php esc_html_e( 'Telegram', 'zhiraf-ufa' ); ?></a>
					<?php endif; ?>
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

	<?php get_template_part( 'template-parts/home/team-teaser' ); ?>

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
					<a href="https://vk.com/zhiraf_ufa" rel="noopener noreferrer" target="_blank" data-track="messenger" data-channel="vk"><?php esc_html_e( 'Мы ВКонтакте', 'zhiraf-ufa' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты и карта', 'zhiraf-ufa' ); ?></a>
				</div>
			</div>
			<div class="trust-social__visual reveal reveal-delay-1">
				<?php zhiraf_ufa_img( 'office-ufa.jpg', __( 'Офис турагентства Жираф в Уфе', 'zhiraf-ufa' ), 'trust-social__img', true ); ?>
				<?php zhiraf_ufa_img( 'team-travel.jpg', __( 'Команда турагентства «Жираф»', 'zhiraf-ufa' ), 'trust-social__img', true ); ?>
			</div>
		</div>
	</section>

	<section class="section section--reviews-placeholder bg-sand" id="reviews-placeholder">
		<div class="container reveal">
			<div class="reviews-placeholder-card" style="background:var(--color-white);border:1px solid var(--color-border);border-radius:var(--radius-lg);padding:40px;text-align:center;max-width:860px;margin:0 auto;">
				<h2 class="section-title" style="font-size:2rem;margin-bottom:12px;"><?php esc_html_e( 'Отзывы туристов из Уфы', 'zhiraf-ufa' ); ?></h2>
				<p style="color:var(--color-muted);font-size:1.0625rem;line-height:1.6;margin-bottom:24px;">
					<?php esc_html_e( 'Мы не публикуем заказные отзывы на сайте. Реальные оценки туристов из Уфы и отзывы с рейтингом доступны на Яндекс.Картах, в 2ГИС и ВКонтакте.', 'zhiraf-ufa' ); ?>
				</p>
				<div style="display:flex;justify-content:center;gap:16px;flex-wrap:wrap;">
					<a href="https://yandex.ru/maps" rel="noopener noreferrer" target="_blank" class="btn btn--outline btn--sm"><?php esc_html_e( 'Рейтинг Яндекс.Карт', 'zhiraf-ufa' ); ?></a>
					<a href="https://2gis.ru/ufa" rel="noopener noreferrer" target="_blank" class="btn btn--outline btn--sm"><?php esc_html_e( 'Рейтинг 2ГИС', 'zhiraf-ufa' ); ?></a>
					<a href="https://vk.com/zhiraf_ufa" rel="noopener noreferrer" target="_blank" class="btn btn--outline btn--sm"><?php esc_html_e( 'ВКонтакте', 'zhiraf-ufa' ); ?></a>
				</div>
			</div>
		</div>
	</section>

	<section class="section section--destinations bg-milky">
		<div class="container destinations">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Направления и вылеты из Уфы', 'zhiraf-ufa' ); ?></h2>
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

			<div class="dest-flight-logistics reveal" style="margin-top:40px;background:var(--color-white);border-radius:var(--radius-lg);padding:32px;border:1px solid var(--color-border);">
				<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;">
					<div>
						<h3 style="font-size:1.25rem;margin-bottom:8px;"><?php esc_html_e( 'Перелёт из Уфы', 'zhiraf-ufa' ); ?></h3>
						<p style="color:var(--color-muted);margin:0;font-size:0.9375rem;">
							<?php esc_html_e( 'Турция, Египет, ОАЭ: в зависимости от даты — прямой рейс или стыковка в Москве. Тип перелёта указан на каждой карточке по данным «Библио-Глобус».', 'zhiraf-ufa' ); ?>
						</p>
					</div>
					<div>
						<h3 style="font-size:1.25rem;margin-bottom:8px;"><?php esc_html_e( 'Направления со стыковкой через Москву', 'zhiraf-ufa' ); ?></h3>
						<p style="color:var(--color-muted);margin:0;font-size:0.9375rem;">
							<?php esc_html_e( 'Таиланд и Вьетнам: сейчас туроператор «Библио-Глобус» предлагает маршруты из Уфы с пересадкой в Москве. Поможем подобрать удобные стыковки.', 'zhiraf-ufa' ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section section--hot-tours bg-sand" id="hot-tours">
		<div class="container">
			<div class="section-head reveal">
				<div>
					<h2 class="section-title"><?php esc_html_e( 'Горящие туры из Уфы и Москвы', 'zhiraf-ufa' ); ?></h2>
					<p class="lead" style="margin-top:8px;margin-bottom:0;color:var(--color-muted);">
						<?php esc_html_e( 'Минимальные цены туроператора «Библио-Глобус» за пакет на 2 взрослых (перелёт и отель). На карточке — состав тура по самому доступному предложению на сегодня. Обновление каждые 6 часов.', 'zhiraf-ufa' ); ?>
					</p>
				</div>
			</div>

			<p class="hot-bento-disclaimer reveal">
				<?php
				printf(
					esc_html__( 'Цены «от» — ориентир туроператора на %1$s. Итоговую стоимость с учётом дат, отеля и перелёта фиксируем в договоре перед оплатой.', 'zhiraf-ufa' ),
					esc_html( function_exists( 'wp_date' ) ? wp_date( 'd.m.Y' ) : gmdate( 'd.m.Y' ) )
				);
				?>
			</p>

			<?php get_template_part( 'template-parts/home/hot-bento' ); ?>
		</div>
	</section>

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
					<p class="form-status" role="status" aria-live="polite"></p>
				</form>
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
