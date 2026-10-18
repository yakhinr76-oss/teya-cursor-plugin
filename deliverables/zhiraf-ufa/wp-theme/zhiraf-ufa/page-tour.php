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
				<h1><?php esc_html_e( 'Направления отдыха с вылетом из Уфы', 'zhiraf-ufa' ); ?></h1>
				<p class="lead"><?php esc_html_e( 'Подбираем пакетные туры по ключевым зарубежным направлениям и России: Турция, ОАЭ, Египет, Таиланд, Вьетнам. Прямые рейсы из международного аэропорта Уфа, проверенные отели и сопровождение от заявки до возвращения домой.', 'zhiraf-ufa' ); ?></p>
			</div>
			<?php zhiraf_ufa_img( 'catalog-hub.jpg', __( 'Путешествия и отдых с вылетом из Уфы', 'zhiraf-ufa' ), 'catalog-hero__img', true ); ?>
		</div>
	</section>
	<section class="section destinations destinations--catalog">
		<div class="container">
			<div class="chip-filter" role="tablist">
				<button type="button" class="chip is-active" data-filter="all"><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?></button>
				<?php foreach ( zhiraf_ufa_destinations() as $dest ) : ?>
					<button type="button" class="chip" data-filter="<?php echo esc_attr( $dest['slug'] ); ?>"><?php echo esc_html( $dest['name'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="chip-filter chip-filter--offers" aria-label="<?php esc_attr_e( 'Популярные форматы', 'zhiraf-ufa' ); ?>">
				<?php foreach ( zhiraf_ufa_offer_hub_links() as $offer ) : ?>
					<a class="chip chip--link" href="<?php echo esc_url( $offer['url'] ); ?>">
						<?php echo esc_html( $offer['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
			<div class="resort-hub-links">
				<p class="resort-hub-links__title"><?php esc_html_e( 'Популярные курорты для отдыха из Уфы', 'zhiraf-ufa' ); ?></p>
				<ul class="resort-link-list resort-link-list--inline">
					<?php foreach ( zhiraf_ufa_resort_hub_links() as $link ) : ?>
						<li>
							<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="dest-grid dest-grid--large">
				<?php foreach ( zhiraf_ufa_destinations() as $dest ) : ?>
					<article class="dest-card dest-card--large" data-dest="<?php echo esc_attr( $dest['slug'] ); ?>">
						<a href="<?php echo esc_url( $dest['url'] ); ?>">
							<?php zhiraf_ufa_img( $dest['image'], $dest['alt'], 'dest-card__img', true ); ?>
							<h2><?php echo esc_html( $dest['name'] ); ?></h2>
							<p><?php esc_html_e( 'Прямые рейсы и удобные стыковки из Уфы, отели All Inclusive, семейный и пляжный отдых.', 'zhiraf-ufa' ); ?></p>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<section class="section section--sand">
		<div class="container narrow prose">
			<h2><?php esc_html_e( 'Как мы подбираем тур под ваш запрос', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'В турагентстве «Жираф» мы не предлагаем стандартные списки из десятков случайных отелей. Менеджер изучает ваши пожелания: состав семьи, возраст детей, предпочтения по пляжу (песок или галька), необходимость детского клуба или подогреваемого бассейна.', 'zhiraf-ufa' ); ?></p>
			<p><?php esc_html_e( 'Мы сравниваем предложения всех ведущих туроператоров, проверяем реальную доступность авиабилетов из Уфы и формируем персональную подборку из 2–4 оптимальных вариантов с фиксированной стоимостью и официальным договором.', 'zhiraf-ufa' ); ?></p>
		</div>
	</section>
	<section class="section bg-milky" id="faq-catalog">
		<div class="container container--narrow">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Частые вопросы о направлениях и турах', 'zhiraf-ufa' ); ?></h2>
			</div>
			<div class="faq-home__list">
				<?php foreach ( zhiraf_ufa_catalog_faq_items() as $item ) : ?>
					<details class="faq-home__item">
						<summary class="faq-home__question"><?php echo esc_html( $item['q'] ); ?></summary>
						<div class="faq-home__answer">
							<p><?php echo esc_html( $item['a'] ); ?></p>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<section class="section">
		<div class="container narrow">
			<h2><?php esc_html_e( 'Не нашли нужное направление?', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Подбираем индивидуальные и пакетные туры по всему миру, включая отдых на курортах России, круизы и экскурсионные поездки. Оставьте заявку — рассчитаем поездку на ваши даты.', 'zhiraf-ufa' ); ?></p>
			<p>
				<button type="button" class="btn btn--primary" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
			</p>
			<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'zhiraf-ufa' ); ?></a> · <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Блог с советами', 'zhiraf-ufa' ); ?></a> · <a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты в Уфе', 'zhiraf-ufa' ); ?></a></p>
		</div>
	</section>
</main>
<?php
get_footer();
