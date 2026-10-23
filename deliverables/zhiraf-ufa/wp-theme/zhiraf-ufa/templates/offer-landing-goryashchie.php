<?php
/**
 * Hot tours offer landing (/goryashchie-tury/).
 *
 * @package Zhiraf_Ufa
 * @var array $offer Offer data.
 */

if ( ! defined( 'ABSPATH' ) || empty( $offer ) ) {
	return;
}

$slug           = ! empty( $offer['slug'] ) ? $offer['slug'] : 'goryashchie-tury';
$cta            = ! empty( $offer['cta_primary'] ) ? $offer['cta_primary'] : __( 'Подобрать тур', 'zhiraf-ufa' );
$freshness      = function_exists( 'zhiraf_ufa_price_feed_freshness' ) ? zhiraf_ufa_price_feed_freshness() : array( 'label' => '' );
$hot_cards      = function_exists( 'zhiraf_ufa_get_hot_landing_tours' ) ? zhiraf_ufa_get_hot_landing_tours( 12 ) : array();
$tg_url         = trim( (string) get_theme_mod( 'zhiraf_telegram_url', '' ) );
$dest_options   = function_exists( 'zhiraf_ufa_catalog_hub_destinations' ) ? zhiraf_ufa_catalog_hub_destinations() : zhiraf_ufa_destinations();
?>
<main id="primary" class="site-main site-main--offer site-main--hot-tours">
	<section class="dest-landing-hero offer-landing-hero offer-landing-hero--hot">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<?php if ( ! empty( $offer['eyebrow'] ) ) : ?>
					<p class="dest-landing-hero__eyebrow"><?php echo esc_html( $offer['eyebrow'] ); ?></p>
				<?php endif; ?>
				<h1><?php echo esc_html( $offer['h1'] ); ?></h1>
				<p class="lead dest-landing-hero__lead"><?php echo esc_html( $offer['hero_lead'] ); ?></p>
				<?php if ( ! empty( $freshness['label'] ) ) : ?>
					<p class="hot-hero-freshness">
						<span class="hot-hero-freshness__dot" aria-hidden="true"></span>
						<?php echo esc_html( $freshness['label'] ); ?>
					</p>
				<?php endif; ?>
				<?php if ( ! empty( $offer['hero_badges'] ) && is_array( $offer['hero_badges'] ) ) : ?>
					<ul class="dest-landing-hero__badges hot-hero-badges">
						<?php foreach ( $offer['hero_badges'] as $badge ) : ?>
							<li class="dest-landing-hero__badge">
								<?php if ( ! empty( $badge['icon'] ) ) : ?>
									<span class="dest-landing-hero__badge-icon" aria-hidden="true"><?php echo esc_html( $badge['icon'] ); ?></span>
								<?php endif; ?>
								<span><?php echo esc_html( $badge['text'] ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<div class="dest-landing-hero__actions">
					<a class="btn btn--primary btn--lg" href="#hot-tours-grid"><?php esc_html_e( 'Смотреть горящие', 'zhiraf-ufa' ); ?></a>
					<a class="btn btn--outline btn--lg" href="#hot-lead"><?php esc_html_e( 'Поймать тур', 'zhiraf-ufa' ); ?></a>
				</div>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $offer['image'], $offer['image_alt'], 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $hot_cards ) ) : ?>
		<section class="section section--dest-tours section--hot-tours-page bg-sand" id="hot-tours-grid">
			<div class="container">
				<div class="section-head reveal">
					<div>
						<h2 class="section-title"><?php esc_html_e( 'Актуальные предложения с ближайшими вылетами', 'zhiraf-ufa' ); ?></h2>
						<p class="lead hot-tours-intro">
							<?php esc_html_e( '12 лучших карточек: у каждой — отель, дата вылета и цена за двоих. Сортировка по ближайшей дате вылета по направлению.', 'zhiraf-ufa' ); ?>
						</p>
					</div>
				</div>
				<div class="tour-price-grid tour-price-grid--dest tour-price-grid--hot">
					<?php
					foreach ( $hot_cards as $i => $tour_item ) :
						get_template_part(
							'template-parts/cards/card-tour-price',
							null,
							array(
								'tour'                   => $tour_item,
								'emphasize_hot_departure' => true,
								'emphasize_flight'       => true,
							)
						);
						if ( 2 === $i && ! empty( $offer['telegram_banner'] ) ) {
							get_template_part(
								'template-parts/offer/telegram-hot-banner',
								null,
								array(
									'banner' => $offer['telegram_banner'],
									'url'    => $tg_url,
								)
							);
						}
					endforeach;
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $offer['geo_answer'] ) ) : ?>
		<section class="section geo-answer-block bg-milky">
			<div class="container container--narrow">
				<p class="geo-answer-block__text"><?php echo esc_html( $offer['geo_answer'] ); ?></p>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $offer['pros_cons'] ) ) : ?>
		<?php
		get_template_part(
			'template-parts/offer/pros-cons-hot',
			null,
			array( 'block' => $offer['pros_cons'] )
		);
		?>
	<?php endif; ?>

	<?php if ( ! empty( $offer['sections'] ) ) : ?>
		<section class="section hot-tours-accordions bg-milky">
			<div class="container container--narrow">
				<?php foreach ( $offer['sections'] as $section ) : ?>
					<details class="dest-prose-accordion">
						<summary class="dest-prose-accordion__summary"><?php echo esc_html( $section['h2'] ); ?></summary>
						<div class="dest-prose-accordion__body">
							<?php if ( ! empty( $section['lead'] ) ) : ?>
								<p class="lead"><?php echo esc_html( $section['lead'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $section['paragraphs'] ) ) : ?>
								<?php foreach ( $section['paragraphs'] as $para ) : ?>
									<p><?php echo wp_kses_post( $para ); ?></p>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="section dest-landing-faq">
		<div class="container container--narrow">
			<h2 class="section-title"><?php esc_html_e( 'Частые вопросы', 'zhiraf-ufa' ); ?></h2>
			<div class="faq-home__list">
				<?php foreach ( $offer['faq'] as $item ) : ?>
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

	<section class="section dest-landing-cta hot-lead-section" id="hot-lead">
		<div class="container container--narrow dest-landing-cta__inner">
			<h2><?php esc_html_e( 'Поймать горящий тур под ваши даты', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Оставьте контакты — поставим на мониторинг вылетов из Уфы и пришлём варианты, как только появится спеццена.', 'zhiraf-ufa' ); ?></p>
			<form class="zhiraf-form hot-lead-form dest-landing-lead-form" data-action="zhiraf_lead" data-form-context="hot-tours">
				<div class="dest-landing-lead-form__fields hot-lead-form__fields">
					<label class="form-label">
						<span><?php esc_html_e( 'Куда хотите', 'zhiraf-ufa' ); ?></span>
						<select name="hot_destination" class="form-input" required>
							<option value=""><?php esc_html_e( 'Выберите направление', 'zhiraf-ufa' ); ?></option>
							<?php foreach ( $dest_options as $dest ) : ?>
								<option value="<?php echo esc_attr( $dest['name'] ); ?>"><?php echo esc_html( $dest['name'] ); ?></option>
							<?php endforeach; ?>
							<option value="<?php esc_attr_e( 'Любое море / не важно', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Любое море / не важно', 'zhiraf-ufa' ); ?></option>
						</select>
					</label>
					<label class="form-label">
						<span><?php esc_html_e( 'Бюджет на двоих', 'zhiraf-ufa' ); ?></span>
						<select name="hot_budget" class="form-input" required>
							<option value=""><?php esc_html_e( 'Ориентир по бюджету', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'до 100 000 ₽', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'до 100 000 ₽', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( '100 000 – 150 000 ₽', 'zhiraf-ufa' ); ?>"><?php esc_html_e( '100 000 – 150 000 ₽', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( '150 000 – 200 000 ₽', 'zhiraf-ufa' ); ?>"><?php esc_html_e( '150 000 – 200 000 ₽', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'выше 200 000 ₽', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'выше 200 000 ₽', 'zhiraf-ufa' ); ?></option>
						</select>
					</label>
					<input type="text" name="name" placeholder="<?php esc_attr_e( 'Ваше имя', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="name" />
					<input type="tel" name="phone" placeholder="<?php esc_attr_e( 'Телефон / WhatsApp', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" />
					<label class="form-label dest-landing-lead-form__channel-label">
						<span class="dest-landing-lead-form__channel-text"><?php esc_html_e( 'Куда отправить горящие', 'zhiraf-ufa' ); ?></span>
						<select name="contact_channel" class="form-input">
							<option value="<?php esc_attr_e( 'Telegram', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Telegram', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'WhatsApp', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Звонок', 'zhiraf-ufa' ); ?>" selected><?php esc_html_e( 'Звонок', 'zhiraf-ufa' ); ?></option>
						</select>
					</label>
					<input type="hidden" name="tour" value="<?php echo esc_attr( $offer['quiz_prefill'] ); ?>" />
					<input type="hidden" name="message" id="hot-lead-message" value="" />
					<?php zhiraf_ufa_consent_fields( 'hot-tours' ); ?>
					<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Поймать горящий тур', 'zhiraf-ufa' ); ?></button>
				</div>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
			<p class="dest-landing-cta__meta">
				<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Каталог направлений', 'zhiraf-ufa' ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</section>
</main>
