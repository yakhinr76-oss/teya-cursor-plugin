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

$catalog_dests = zhiraf_ufa_catalog_hub_destinations();

get_header();
?>
<main id="primary" class="site-main site-main--catalog tour-hub">
	<section class="page-hero page-hero--catalog">
		<div class="container catalog-hero">
			<div class="catalog-hero__copy">
				<h1><?php esc_html_e( 'Направления отдыха с вылетом из Уфы', 'zhiraf-ufa' ); ?></h1>
				<p class="lead catalog-hero__lead"><?php esc_html_e( 'Зарубежные направления с вылетом из Уфы плюс Россия и Абхазия без загранпаспорта — подберём пакет или только отель.', 'zhiraf-ufa' ); ?></p>
				<ul class="catalog-hero__highlights">
					<li><?php esc_html_e( 'Цены «от» по данным «Библио-Глобус» за 2 взрослых', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Прямые рейсы и стыковки — видно на карточке', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( '2–4 варианта и договор до оплаты', 'zhiraf-ufa' ); ?></li>
				</ul>
			</div>
			<div class="catalog-hero__visual" aria-hidden="false">
				<div class="catalog-hero__collage">
					<?php foreach ( zhiraf_ufa_catalog_hero_collage_items() as $tile ) : ?>
						<div class="catalog-hero__collage-item catalog-hero__collage-item<?php echo esc_attr( $tile['modifier'] ); ?>">
							<?php zhiraf_ufa_img( $tile['image'], $tile['alt'], '', true ); ?>
							<div class="catalog-hero__badges" aria-hidden="false">
								<?php if ( ! empty( $tile['price_badge'] ) ) : ?>
									<span class="catalog-hero__badge catalog-hero__badge--price"><?php echo esc_html( $tile['price_badge'] ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $tile['flight_badge'] ) ) : ?>
									<span class="catalog-hero__badge catalog-hero__badge--flight"><?php echo esc_html( $tile['flight_badge'] ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="section destinations destinations--catalog">
		<div class="container">
			<div class="catalog-filter-block">
				<p class="catalog-filter-block__label"><?php esc_html_e( 'Страны и направления', 'zhiraf-ufa' ); ?></p>
				<div class="chip-filter" role="tablist" aria-label="<?php esc_attr_e( 'Фильтр по стране', 'zhiraf-ufa' ); ?>">
					<button type="button" class="chip is-active" data-filter="all"><?php esc_html_e( 'Все', 'zhiraf-ufa' ); ?></button>
					<?php foreach ( $catalog_dests as $dest ) : ?>
						<button type="button" class="chip" data-filter="<?php echo esc_attr( $dest['slug'] ); ?>"><?php echo esc_html( $dest['name'] ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="catalog-filter-block">
				<p class="catalog-filter-block__label"><?php esc_html_e( 'Быстрые форматы', 'zhiraf-ufa' ); ?></p>
				<div class="chip-filter chip-filter--offers" aria-label="<?php esc_attr_e( 'Спецпредложения', 'zhiraf-ufa' ); ?>">
					<?php foreach ( zhiraf_ufa_offer_hub_links() as $offer ) : ?>
						<a class="chip chip--link" href="<?php echo esc_url( $offer['url'] ); ?>">
							<?php echo esc_html( $offer['label'] ); ?>
						</a>
					<?php endforeach; ?>
					<a class="chip chip--link" href="<?php echo esc_url( home_url( '/#hot-tours' ) ); ?>"><?php esc_html_e( 'Минимальные цены', 'zhiraf-ufa' ); ?></a>
				</div>
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

			<div class="dest-grid dest-grid--catalog">
				<?php foreach ( $catalog_dests as $dest ) : ?>
					<?php
					get_template_part(
						'template-parts/catalog/hub-dest-card',
						null,
						array( 'dest' => $dest )
					);
					?>
				<?php endforeach; ?>
			</div>

			<p class="catalog-price-note catalog-price-note--after-grid">
				<?php
				printf(
					esc_html__( 'Цены «от» — минимум туроператора на %1$s за пакет на 2 взрослых (перелёт + отель). Итог фиксируем в договоре.', 'zhiraf-ufa' ),
					esc_html( function_exists( 'wp_date' ) ? wp_date( 'd.m.Y' ) : gmdate( 'd.m.Y' ) )
				);
				?>
			</p>
		</div>
	</section>

	<section class="section section--how-it-works">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Как мы подбираем тур под ваш запрос', 'zhiraf-ufa' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Без «случайных» отелей из прайса — только варианты под ваш состав, даты и бюджет.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="steps-row">
				<div class="step-card reveal">
					<div class="step-number">01</div>
					<h3><?php esc_html_e( 'Заявка и пожелания', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Оставляете контакты или проходите квиз: состав, месяц, бюджет, формат отдыха.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="step-card reveal reveal-delay-1">
					<div class="step-number">02</div>
					<h3><?php esc_html_e( 'Подбор 2–4 вариантов', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Сравниваем перелёт из Уфы, отель, питание и реальную доступность мест у оператора.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="step-card reveal reveal-delay-2">
					<div class="step-number">03</div>
					<h3><?php esc_html_e( 'Договор и оплата', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Фиксируем стоимость в договоре в офисе на Гагарина, 74/1 или дистанционно.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="step-card reveal reveal-delay-3">
					<div class="step-number">04</div>
					<h3><?php esc_html_e( 'Сопровождение до вылета', 'zhiraf-ufa' ); ?></h3>
					<p><?php esc_html_e( 'Ваучеры, напоминания по рейсу и связь с менеджером до возвращения домой.', 'zhiraf-ufa' ); ?></p>
				</div>
			</div>
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

	<section class="section catalog-hub-cta" id="catalog-lead">
		<div class="container container--narrow catalog-hub-cta__inner">
			<h2><?php esc_html_e( 'Не нашли нужное направление?', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Подберём индивидуальные и пакетные туры — оставьте телефон, отправим подборку в удобный мессенджер или перезвоним.', 'zhiraf-ufa' ); ?></p>
			<form class="zhiraf-form catalog-hub-lead-form dest-landing-lead-form" data-action="zhiraf_lead" data-form-context="catalog-hub">
				<div class="dest-landing-lead-form__fields">
					<input type="text" name="name" placeholder="<?php esc_attr_e( 'Ваше имя', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="name" />
					<input type="tel" name="phone" placeholder="<?php esc_attr_e( 'Телефон', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" />
					<label class="form-label dest-landing-lead-form__channel-label">
						<span class="dest-landing-lead-form__channel-text"><?php esc_html_e( 'Куда отправить подборку', 'zhiraf-ufa' ); ?></span>
						<select name="contact_channel" class="form-input">
							<option value="<?php esc_attr_e( 'WhatsApp', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Telegram', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Telegram', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Звонок', 'zhiraf-ufa' ); ?>" selected><?php esc_html_e( 'Звонок', 'zhiraf-ufa' ); ?></option>
						</select>
					</label>
					<input type="hidden" name="tour" value="<?php esc_attr_e( 'Каталог направлений из Уфы', 'zhiraf-ufa' ); ?>" />
					<?php zhiraf_ufa_consent_fields( 'catalog-hub' ); ?>
					<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Получить подборку', 'zhiraf-ufa' ); ?></button>
				</div>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
			<p class="catalog-hub-cta__links">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'zhiraf-ufa' ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Блог с советами', 'zhiraf-ufa' ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты в Уфе', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</section>
</main>
<?php
get_footer();
