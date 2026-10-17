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
?>

<main id="primary" class="site-main site-main--home">

	<section class="hero-collage" aria-labelledby="hero-heading">
		<div class="container hero-collage__grid">
			<div class="hero-collage__copy">
				<p class="eyebrow"><?php esc_html_e( 'Турагентство в Уфе · вылет из Уфы', 'zhiraf-ufa' ); ?></p>
				<h1 id="hero-heading"><?php esc_html_e( 'Туры из Уфы — подбор путёвок с вылетом из Уфы', 'zhiraf-ufa' ); ?></h1>
				<p class="hero-lead"><?php esc_html_e( 'Турция, ОАЭ, Египет, Таиланд и Вьетнам — подберём 2–3 варианта под ваши даты и бюджет. Консультация бесплатна, в офисе на Гагарина.', 'zhiraf-ufa' ); ?></p>
				<div class="hero-actions">
					<button type="button" class="btn btn--primary" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
					<a class="btn btn--ghost" href="tel:+79018105018"><?php esc_html_e( 'Позвонить', 'zhiraf-ufa' ); ?></a>
				</div>
			</div>
			<div class="hero-collage__images" data-gsap-collage>
				<?php
				zhiraf_ufa_img( 'hero-turkey.jpg', __( 'Курорт Турции', 'zhiraf-ufa' ), 'collage-img collage-img--1', false, true );
				zhiraf_ufa_img( 'hero-uae.jpg', __( 'ОАЭ на закате', 'zhiraf-ufa' ), 'collage-img collage-img--2', true );
				zhiraf_ufa_img( 'hero-thailand.jpg', __( 'Пляж Таиланда', 'zhiraf-ufa' ), 'collage-img collage-img--3', true );
				zhiraf_ufa_img( 'hero-egypt.jpg', __( 'Египет', 'zhiraf-ufa' ), 'collage-img collage-img--4', true );
			</div>
		</div>
	</section>

	<section class="section section--sand pain-solution">
		<div class="container narrow">
			<h2><?php esc_html_e( 'Устали сравнивать сотни предложений?', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'На агрегаторах легко потеряться в отелях и тарифах. Мы уточняем детали по телефону или в офисе и предлагаем несколько осмысленных вариантов — без навязчивых продаж.', 'zhiraf-ufa' ); ?></p>
		</div>
	</section>

	<section class="section destinations" id="napravleniya" aria-labelledby="dest-heading">
		<div class="container">
			<div class="section-head">
				<h2 id="dest-heading"><?php esc_html_e( 'Популярные направления', 'zhiraf-ufa' ); ?></h2>
				<p><?php esc_html_e( 'Фильтр по стране — карточки обновятся ниже.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="chip-filter" role="tablist" aria-label="<?php esc_attr_e( 'Фильтр направлений', 'zhiraf-ufa' ); ?>">
				<button type="button" class="chip is-active" data-filter="all"><?php esc_html_e( 'Все', 'zhiraf-ufa' ); ?></button>
				<?php foreach ( zhiraf_ufa_destinations() as $dest ) : ?>
					<button type="button" class="chip" data-filter="<?php echo esc_attr( $dest['slug'] ); ?>"><?php echo esc_html( $dest['name'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="dest-grid">
				<?php foreach ( zhiraf_ufa_destinations() as $dest ) : ?>
					<article class="dest-card" data-dest="<?php echo esc_attr( $dest['slug'] ); ?>">
						<a href="<?php echo esc_url( $dest['url'] ); ?>" class="dest-card__link">
							<?php zhiraf_ufa_img( $dest['image'], $dest['alt'], 'dest-card__img', true ); ?>
							<h3 class="dest-card__title"><?php echo esc_html( $dest['name'] ); ?></h3>
							<span class="dest-card__cta"><?php esc_html_e( 'Смотреть подбор', 'zhiraf-ufa' ); ?></span>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
			<p class="section-cta"><a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Весь каталог направлений', 'zhiraf-ufa' ); ?></a></p>
		</div>
	</section>

	<section class="section section--dark principles">
		<div class="container">
			<h2><?php esc_html_e( 'Почему «Жираф»', 'zhiraf-ufa' ); ?></h2>
			<div class="principles-grid">
				<div class="principle-card">
					<h3><?php esc_html_e( 'Честная консультация', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Объясняем разницу отелей и питания простым языком. Решение о покупке — за вами.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="principle-card">
					<h3><?php esc_html_e( 'Офис в Уфе', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Можно приехать на Гагарина, 74/1 — обсудить поездку лично.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="principle-card">
					<h3><?php esc_html_e( 'Реестр турагентств', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Номер в реестре ТА 0012822. Реквизиты — на странице контактов.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="principle-card">
					<h3><?php esc_html_e( 'Сопровождение', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'От заявки до возвращения домой — на связи менеджер, с которым вы познакомились.', 'zhiraf-ufa' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="section how-it-works">
		<div class="container">
			<h2><?php esc_html_e( 'Как это работает', 'zhiraf-ufa' ); ?></h2>
			<ol class="steps">
				<li><strong><?php esc_html_e( 'Заявка', 'zhiraf-ufa' ); ?></strong> — <?php esc_html_e( 'оставьте контакты или пройдите квиз', 'zhiraf-ufa' ); ?></li>
				<li><strong><?php esc_html_e( 'Консультация', 'zhiraf-ufa' ); ?></strong> — <?php esc_html_e( 'уточним даты, состав и бюджет', 'zhiraf-ufa' ); ?></li>
				<li><strong><?php esc_html_e( 'Бронирование', 'zhiraf-ufa' ); ?></strong> — <?php esc_html_e( 'оформим договор и документы', 'zhiraf-ufa' ); ?></li>
			</ol>
		</div>
	</section>

	<section class="section blog-teaser" aria-labelledby="blog-teaser-heading">
		<div class="container">
			<h2 id="blog-teaser-heading"><?php esc_html_e( 'Материалы и темы блога', 'zhiraf-ufa' ); ?></h2>
			<p class="blog-teaser__note"><?php esc_html_e( 'Темы редакционного плана — статьи готовит редакция (Excalibur).', 'zhiraf-ufa' ); ?></p>
			<div class="story-grid">
				<?php
				$covers = array( 'blog-cover-01.jpg', 'travel-passport.jpg', 'team-travel.jpg' );
				$i      = 0;
				foreach ( zhiraf_ufa_blog_topics() as $topic ) :
					$img = $covers[ $i % count( $covers ) ];
					++$i;
					?>
					<article class="story-card">
						<?php zhiraf_ufa_img( $img, $topic['title'], 'story-card__img', true ); ?>
						<h3><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php echo esc_html( $topic['title'] ); ?></a></h3>
						<p class="story-card__meta"><span><?php echo esc_html( $topic['topic'] ); ?></span> · <?php echo esc_html( $topic['note'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Перейти в блог', 'zhiraf-ufa' ); ?></a>
		</div>
	</section>

	<section class="section reviews" aria-labelledby="reviews-heading">
		<div class="container">
			<h2 id="reviews-heading"><?php esc_html_e( 'Отзывы', 'zhiraf-ufa' ); ?></h2>
			<p class="reviews-disclaimer"><?php esc_html_e( 'Публикуем только верифицированные отзывы с согласия клиентов. Ниже — макетные карточки для вёрстки.', 'zhiraf-ufa' ); ?></p>
			<div class="review-grid">
				<article class="review-card review-card--placeholder">
					<p class="review-placeholder-label"><?php esc_html_e( 'PLACEHOLDER — отзыв 1', 'zhiraf-ufa' ); ?></p>
					<p><?php esc_html_e( 'Текст отзыва будет добавлен после верификации источника (Яндекс / 2ГИС / ВК).', 'zhiraf-ufa' ); ?></p>
				</article>
				<article class="review-card review-card--placeholder">
					<p class="review-placeholder-label"><?php esc_html_e( 'PLACEHOLDER — отзыв 2', 'zhiraf-ufa' ); ?></p>
					<p><?php esc_html_e( 'Имя и дата поездки — по согласованию с заказчиком.', 'zhiraf-ufa' ); ?></p>
				</article>
				<article class="review-card review-card--placeholder">
					<p class="review-placeholder-label"><?php esc_html_e( 'PLACEHOLDER — отзыв 3', 'zhiraf-ufa' ); ?></p>
					<p><?php esc_html_e( 'Без выдуманных рейтингов и звёзд в разметке schema.', 'zhiraf-ufa' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<section class="section faq" id="faq">
		<div class="container narrow">
			<h2><?php esc_html_e( 'Частые вопросы', 'zhiraf-ufa' ); ?></h2>
			<details class="faq-item">
				<summary><?php esc_html_e( 'Сколько стоит тур из Уфы?', 'zhiraf-ufa' ); ?></summary>
				<p><?php esc_html_e( 'Зависит от сезона, отеля и дат вылета. Подберём варианты под бюджет без обещания фиксированного минимума.', 'zhiraf-ufa' ); ?></p>
			</details>
			<details class="faq-item">
				<summary><?php esc_html_e( 'Как проверить, что вы в реестре?', 'zhiraf-ufa' ); ?></summary>
				<p><?php esc_html_e( 'Номер 0012822 на reestr-ta.ru; реквизиты ООО «Жираф» — на странице контактов.', 'zhiraf-ufa' ); ?></p>
			</details>
			<details class="faq-item">
				<summary><?php esc_html_e( 'Обязательно ли покупать после консультации?', 'zhiraf-ufa' ); ?></summary>
				<p><?php esc_html_e( 'Нет. Консультация бесплатна, решение за вами.', 'zhiraf-ufa' ); ?></p>
			</details>
		</div>
	</section>

	<section class="section lead-form" id="zayavka">
		<div class="container">
			<div class="lead-form__grid">
				<div>
					<h2><?php esc_html_e( 'Оставить заявку', 'zhiraf-ufa' ); ?></h2>
					<p><?php esc_html_e( 'Перезвоним в рабочее время: Пн–Пт 10–19, Сб 11–15.', 'zhiraf-ufa' ); ?></p>
				</div>
				<form class="zhiraf-form" id="lead-form" data-action="zhiraf_lead">
					<label class="form-field"><span><?php esc_html_e( 'Имя', 'zhiraf-ufa' ); ?></span><input name="name" type="text" required /></label>
					<label class="form-field"><span><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span><input name="phone" type="tel" required /></label>
					<label class="form-field"><span><?php esc_html_e( 'Email', 'zhiraf-ufa' ); ?></span><input name="email" type="email" /></label>
					<label class="form-field"><span><?php esc_html_e( 'Комментарий', 'zhiraf-ufa' ); ?></span><textarea name="message" rows="3"></textarea></label>
					<?php zhiraf_ufa_consent_fields(); ?>
					<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Отправить', 'zhiraf-ufa' ); ?></button>
					<p class="form-status" role="status" aria-live="polite"></p>
				</form>
			</div>
		</div>
	</section>

	<section class="section subscribe-panel">
		<div class="container subscribe-panel__inner">
			<h2><?php esc_html_e( 'Подписка на полезные материалы', 'zhiraf-ufa' ); ?></h2>
			<form class="zhiraf-form subscribe-form" id="subscribe-form" data-action="zhiraf_subscribe">
				<label class="form-field form-field--inline">
					<span class="screen-reader-text"><?php esc_html_e( 'Email', 'zhiraf-ufa' ); ?></span>
					<input type="email" name="email" placeholder="office@example.com" required />
				</label>
				<?php zhiraf_ufa_consent_fields( 'sub' ); ?>
				<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Подписаться', 'zhiraf-ufa' ); ?></button>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
		</div>
	</section>

	<section class="section contacts-teaser">
		<div class="container contacts-teaser__grid">
			<div>
				<h2><?php esc_html_e( 'Офис на Гагарина', 'zhiraf-ufa' ); ?></h2>
				<p><?php esc_html_e( 'ул. Юрия Гагарина, 74/1, офис 3', 'zhiraf-ufa' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты и карта', 'zhiraf-ufa' ); ?></a>
			</div>
			<?php zhiraf_ufa_img( 'office-ufa.jpg', __( 'Офисное пространство', 'zhiraf-ufa' ), 'contacts-teaser__img', true ); ?>
		</div>
	</section>

</main>

<?php
get_footer();
