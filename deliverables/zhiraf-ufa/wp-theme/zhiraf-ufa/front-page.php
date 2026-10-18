<?php
/**
 * Front page — Globe Trekker layout.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$blog_posts = zhiraf_ufa_home_blog_posts();
$dest_all   = zhiraf_ufa_destinations();
$dest_show  = array_slice( $dest_all, 0, 4 );
?>

<main id="primary" class="site-main site-main--home">

	<section class="hero-globe reveal" aria-labelledby="hero-heading">
		<div class="container hero-globe__grid">
			<div class="hero-globe__copy">
				<h1 id="hero-heading">
					<?php esc_html_e( 'Откройте', 'zhiraf-ufa' ); ?>
					<span class="headline-accent"><?php esc_html_e( 'лучшие', 'zhiraf-ufa' ); ?></span>
					<?php esc_html_e( 'туры из Уфы', 'zhiraf-ufa' ); ?>
				</h1>
				<p class="hero-lead"><?php esc_html_e( 'Подберём 2–3 варианта с вылетом из Уфы: Турция, ОАЭ, Египет, Таиланд и Вьетнам. Честно о плюсах и минусах отелей — консультация бесплатна.', 'zhiraf-ufa' ); ?></p>
				<div class="hero-actions">
					<button type="button" class="btn btn--primary btn--pill" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
				</div>
			</div>
			<div class="hero-globe__visual" data-gsap-collage>
				<div class="hero-map" aria-hidden="true">
					<svg class="hero-map__svg" viewBox="0 0 520 420" xmlns="http://www.w3.org/2000/svg">
						<ellipse cx="260" cy="210" rx="230" ry="170" fill="#f0ebe3" opacity="0.9"/>
						<path class="hero-map__land" d="M120 180c40-60 120-80 180-40 50 30 90 10 120 50 20 28 10 70-30 90-50 26-110 10-150-30-35-35-80-40-120-70z" fill="#e8e0d4"/>
						<path class="hero-map__route" d="M80 260 Q 180 120 320 200 T 440 150" fill="none" stroke="#c9bfb3" stroke-width="2" stroke-dasharray="6 8"/>
						<g transform="translate(400,130)">
							<path d="M0 8 L16 0 L12 8 L16 16 L0 8 Z" fill="#e87a5d"/>
						</g>
					</svg>
				</div>
				<div class="hero-collage">
					<?php
					zhiraf_ufa_img( 'hero-turkey.jpg', __( 'Турция', 'zhiraf-ufa' ), 'collage-tile collage-tile--1', false, true );
					zhiraf_ufa_img( 'hero-uae.jpg', __( 'ОАЭ', 'zhiraf-ufa' ), 'collage-tile collage-tile--2', true );
					zhiraf_ufa_img( 'hero-thailand.jpg', __( 'Таиланд', 'zhiraf-ufa' ), 'collage-tile collage-tile--3', true );
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="section destinations" id="napravleniya" aria-labelledby="dest-heading">
		<div class="container">
			<div class="section-head section-head--row">
				<h2 id="dest-heading"><?php esc_html_e( 'Популярные направления', 'zhiraf-ufa' ); ?></h2>
				<a class="btn btn--outline btn--sm" href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?></a>
			</div>
			<div class="dest-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Фильтр направлений', 'zhiraf-ufa' ); ?>">
				<button type="button" class="dest-tab is-active" data-filter="all"><?php esc_html_e( 'Популярные', 'zhiraf-ufa' ); ?></button>
				<?php foreach ( $dest_all as $dest ) : ?>
					<button type="button" class="dest-tab" data-filter="<?php echo esc_attr( $dest['slug'] ); ?>"><?php echo esc_html( $dest['name'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="dest-row">
				<?php foreach ( $dest_all as $dest ) : ?>
					<article class="dest-card" data-dest="<?php echo esc_attr( $dest['slug'] ); ?>">
						<a href="<?php echo esc_url( $dest['url'] ); ?>" class="dest-card__link">
							<?php zhiraf_ufa_img( $dest['image'], $dest['alt'], 'dest-card__img', true ); ?>
							<div class="dest-card__caption">
								<h3 class="dest-card__title"><?php echo esc_html( $dest['name'] ); ?></h3>
								<p class="dest-card__tag"><?php esc_html_e( 'Прямой рейс из Уфы', 'zhiraf-ufa' ); ?></p>
							</div>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section section--tight principles-light">
		<div class="container">
			<div class="principles-light__grid">
				<div class="principle-tile">
					<span class="principle-tile__icon" aria-hidden="true">✓</span>
					<h3><?php esc_html_e( 'Честная консультация', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Расскажем о минусах отеля, если они есть — без розовых очков.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="principle-tile">
					<span class="principle-tile__icon" aria-hidden="true">◎</span>
					<h3><?php esc_html_e( 'Офис в Уфе', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'ул. Гагарина, 74/1 — можно обсудить поездку лично.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="principle-tile">
					<span class="principle-tile__icon" aria-hidden="true">★</span>
					<h3><?php esc_html_e( 'Реестр ТА 0012822', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Работаем с 2011 года, договор и прозрачные условия.', 'zhiraf-ufa' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="section how-cards">
		<div class="container">
			<h2 class="section-title-center"><?php esc_html_e( 'Как это работает', 'zhiraf-ufa' ); ?></h2>
			<div class="how-cards__grid">
				<div class="how-card">
					<span class="how-card__icon" aria-hidden="true">1</span>
					<h3><?php esc_html_e( 'Заявка или квиз', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Оставьте контакты и пожелания по датам и составу.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="how-card">
					<span class="how-card__icon" aria-hidden="true">2</span>
					<h3><?php esc_html_e( 'Подбор вариантов', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Менеджер предложит 2–3 тура с вылетом из Уфы.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="how-card">
					<span class="how-card__icon" aria-hidden="true">3</span>
					<h3><?php esc_html_e( 'Договор и вылет', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Оформим документы и останемся на связи до возвращения.', 'zhiraf-ufa' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="section stories-split" aria-labelledby="stories-heading">
		<div class="container">
			<div class="section-head section-head--row">
				<h2 id="stories-heading"><?php esc_html_e( 'Полезные статьи', 'zhiraf-ufa' ); ?></h2>
				<a class="text-link" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Все статьи', 'zhiraf-ufa' ); ?></a>
			</div>
			<div class="stories-split__grid">
				<?php if ( ! empty( $blog_posts ) ) : ?>
					<?php
					$featured = $blog_posts[0];
					$thumb_id = get_post_thumbnail_id( $featured->ID );
					?>
					<article class="story-featured">
						<a class="story-featured__link" href="<?php echo esc_url( get_permalink( $featured ) ); ?>">
							<?php
							if ( $thumb_id ) {
								echo wp_get_attachment_image( $thumb_id, 'zhiraf-blog', false, array( 'class' => 'story-featured__img' ) );
							} else {
								zhiraf_ufa_img( 'blog-featured.jpg', get_the_title( $featured ), 'story-featured__img', true );
							}
							?>
							<div class="story-featured__body">
								<span class="story-kicker"><?php echo esc_html( zhiraf_ufa_story_category_label( $featured->ID ) ); ?></span>
								<h3><?php echo esc_html( get_the_title( $featured ) ); ?></h3>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $featured ), 22 ) ); ?></p>
								<time datetime="<?php echo esc_attr( get_the_date( 'c', $featured ) ); ?>"><?php echo esc_html( get_the_date( '', $featured ) ); ?></time>
							</div>
						</a>
					</article>
					<ul class="story-list">
						<?php
						$list_posts = array_slice( $blog_posts, 1, 3 );
						$fallback_imgs = array( 'blog-thumb-1.jpg', 'blog-thumb-2.jpg', 'blog-thumb-3.jpg' );
						$fi            = 0;
						foreach ( $list_posts as $post ) :
							setup_postdata( $post );
							$tid = get_post_thumbnail_id( $post->ID );
							?>
							<li class="story-list__item">
								<a class="story-list__link" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
									<?php
									if ( $tid ) {
										echo wp_get_attachment_image( $tid, 'thumbnail', false, array( 'class' => 'story-list__thumb' ) );
									} else {
										zhiraf_ufa_img( $fallback_imgs[ $fi % 3 ], get_the_title( $post ), 'story-list__thumb', true );
									}
									?>
									<div class="story-list__text">
										<span class="story-kicker"><?php echo esc_html( zhiraf_ufa_story_category_label( $post->ID ) ); ?></span>
										<h3><?php the_title(); ?></h3>
										<time datetime="<?php echo esc_attr( get_the_date( 'c', $post ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
									</div>
								</a>
							</li>
							<?php
							++$fi;
						endforeach;
						wp_reset_postdata();
						?>
					</ul>
				<?php else : ?>
					<?php $fallback = zhiraf_ufa_blog_fallback_stories(); ?>
					<article class="story-featured">
						<a class="story-featured__link" href="<?php echo esc_url( $fallback[0]['url'] ); ?>">
							<?php zhiraf_ufa_img( $fallback[0]['image'], $fallback[0]['title'], 'story-featured__img', true ); ?>
							<div class="story-featured__body">
								<span class="story-kicker"><?php echo esc_html( $fallback[0]['category'] ); ?></span>
								<h3><?php echo esc_html( $fallback[0]['title'] ); ?></h3>
							</div>
						</a>
					</article>
					<ul class="story-list">
						<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
							<li class="story-list__item">
								<a class="story-list__link" href="<?php echo esc_url( $fallback[ $i ]['url'] ); ?>">
									<?php zhiraf_ufa_img( $fallback[ $i ]['image'], $fallback[ $i ]['title'], 'story-list__thumb', true ); ?>
									<div class="story-list__text">
										<span class="story-kicker"><?php echo esc_html( $fallback[ $i ]['category'] ); ?></span>
										<h3><?php echo esc_html( $fallback[ $i ]['title'] ); ?></h3>
									</div>
								</a>
							</li>
						<?php endfor; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( zhiraf_ufa_show_reviews_block() ) : ?>
		<section class="section highlights" aria-labelledby="reviews-heading">
			<div class="container">
				<h2 id="reviews-heading"><?php esc_html_e( 'Отзывы путешественников', 'zhiraf-ufa' ); ?></h2>
			</div>
		</section>
	<?php else : ?>
		<section class="section reviews-cta">
			<div class="container reviews-cta__inner">
				<h2><?php esc_html_e( 'Отзывы о нас', 'zhiraf-ufa' ); ?></h2>
				<p><?php esc_html_e( 'Читайте мнения клиентов на площадках — мы не публикуем непроверенные цитаты на сайте.', 'zhiraf-ufa' ); ?></p>
				<div class="reviews-cta__links">
					<a class="btn btn--outline" href="https://vk.com/zhiraf_ufa" rel="noopener noreferrer" target="_blank"><?php esc_html_e( 'ВКонтакте', 'zhiraf-ufa' ); ?></a>
					<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Ссылка на карты в контактах', 'zhiraf-ufa' ); ?></a>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="subscribe-hero">
		<div class="subscribe-hero__bg">
			<?php zhiraf_ufa_img( 'subscribe-banner.jpg', '', 'subscribe-hero__bg-img', true ); ?>
		</div>
		<div class="container subscribe-hero__content">
			<h2><?php esc_html_e( 'Подборки туров и советы — на почту', 'zhiraf-ufa' ); ?></h2>
			<form class="zhiraf-form subscribe-hero__form" id="subscribe-form" data-action="zhiraf_subscribe">
				<div class="subscribe-hero__row">
					<label class="screen-reader-text" for="subscribe-email"><?php esc_html_e( 'Email', 'zhiraf-ufa' ); ?></label>
					<input id="subscribe-email" type="email" name="email" placeholder="<?php esc_attr_e( 'Ваш email', 'zhiraf-ufa' ); ?>" required />
					<button type="submit" class="btn btn--primary btn--pill"><?php esc_html_e( 'Подписаться', 'zhiraf-ufa' ); ?></button>
				</div>
				<?php zhiraf_ufa_consent_fields( 'sub' ); ?>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
		</div>
	</section>

	<section class="section lead-form section--tight" id="zayavka">
		<div class="container">
			<div class="lead-form__grid">
				<div>
					<h2><?php esc_html_e( 'Оставить заявку', 'zhiraf-ufa' ); ?></h2>
					<p><?php esc_html_e( 'Пн–Пт 10–19, Сб 11–15 · перезвоним в рабочее время.', 'zhiraf-ufa' ); ?></p>
				</div>
				<form class="zhiraf-form lead-form__form" id="lead-form" data-action="zhiraf_lead">
					<label class="form-field"><span><?php esc_html_e( 'Имя', 'zhiraf-ufa' ); ?></span><input name="name" type="text" required /></label>
					<label class="form-field"><span><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span><input name="phone" type="tel" required /></label>
					<label class="form-field"><span><?php esc_html_e( 'Комментарий', 'zhiraf-ufa' ); ?></span><textarea name="message" rows="3"></textarea></label>
					<?php zhiraf_ufa_consent_fields(); ?>
					<button type="submit" class="btn btn--primary btn--pill"><?php esc_html_e( 'Отправить', 'zhiraf-ufa' ); ?></button>
					<p class="form-status" role="status" aria-live="polite"></p>
				</form>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
