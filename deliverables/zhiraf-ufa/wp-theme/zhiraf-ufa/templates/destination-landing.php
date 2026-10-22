<?php
/**
 * Destination landing layout (included via page-{slug}.php).
 *
 * @package Zhiraf_Ufa
 * @var array $dest Landing data from zhiraf_ufa_get_destination_landing().
 */

if ( ! defined( 'ABSPATH' ) || empty( $dest ) ) {
	return;
}

$dest_slug           = ! empty( $dest['slug'] ) ? $dest['slug'] : ( isset( $slug ) ? $slug : '' );
if ( empty( $dest_slug ) ) {
	$dest_slug = get_post_field( 'post_name', get_post() );
}
$tour_limit          = ! empty( $dest['tour_card_limit'] ) ? (int) $dest['tour_card_limit'] : 3;
$tour_collapsed_after = ! empty( $dest['tour_collapsed_after'] ) ? (int) $dest['tour_collapsed_after'] : 0;
$dest_tours          = ! empty( $dest_slug ) ? zhiraf_ufa_get_country_top_tours( $dest_slug, $tour_limit ) : array();
?>
<main id="primary" class="site-main site-main--destination">
	<section class="dest-landing-hero">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<p class="dest-landing-hero__eyebrow">
					<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?></a>
				</p>
				<h1><?php echo esc_html( $dest['h1'] ); ?></h1>
				<p class="lead dest-landing-hero__lead"><?php echo esc_html( $dest['hero_lead'] ); ?></p>
				<div class="dest-landing-hero__actions">
					<button type="button" class="btn btn--primary btn--lg" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>">
						<?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?>
					</button>
					<?php if ( ! empty( $dest_tours ) ) : ?>
						<a class="btn btn--outline btn--lg" href="#dest-tours"><?php esc_html_e( 'Смотреть цены', 'zhiraf-ufa' ); ?></a>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $dest['hero_cta_hint'] ) ) : ?>
					<p class="dest-landing-hero__cta-hint"><?php echo esc_html( $dest['hero_cta_hint'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="dest-landing-hero__visual">
				<?php if ( ! empty( $dest['image'] ) ) : ?>
					<?php zhiraf_ufa_img( $dest['image'], $dest['image_alt'], 'dest-landing-hero__img', true ); ?>
				<?php else : ?>
					<div class="dest-landing-hero__gradient dest-grad dest-grad--<?php echo esc_attr( $dest['slug'] ); ?>" role="img" aria-label="<?php echo esc_attr( $dest['image_alt'] ); ?>"><span><?php echo esc_html( $dest['name'] ); ?></span></div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $dest_tours ) ) : ?>
		<section class="section section--dest-tours section--dest-tours-top bg-sand" id="dest-tours">
			<div class="container">
				<div class="section-head reveal">
					<div>
						<h2 class="section-title">
							<?php
							if ( ! empty( $dest['departure'] ) ) {
								esc_html_e( 'Самые выгодные отели с вылетом из Москвы', 'zhiraf-ufa' );
							} else {
								/* translators: %s: country name in prepositional */
								printf( esc_html__( 'Актуальные туры %s из Уфы', 'zhiraf-ufa' ), esc_html( zhiraf_ufa_country_prepositional( $dest_slug ) ) );
							}
							?>
						</h2>
						<p class="lead" style="margin-top:8px;margin-bottom:0;color:var(--color-muted);">
							<?php esc_html_e( 'Цены туроператора «Библио-Глобус» за 2 взрослых (перелёт и отель). Итог фиксируем в договоре перед оплатой. Обновление каждые 6 часов.', 'zhiraf-ufa' ); ?>
						</p>
					</div>
					<?php if ( ! empty( $dest['tour_filters'] ) && is_array( $dest['tour_filters'] ) ) : ?>
						<div class="section-tabs dest-tour-tabs tour-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Фильтр по эмиратам', 'zhiraf-ufa' ); ?>">
							<?php foreach ( $dest['tour_filters'] as $i => $filter ) : ?>
								<button type="button" class="tab-btn<?php echo 0 === $i ? ' is-active' : ''; ?>" data-tour-filter="<?php echo esc_attr( $filter['id'] ); ?>">
									<?php echo esc_html( $filter['label'] ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="tour-price-grid tour-price-grid--dest tour-price-grid--top" id="dest-tour-grid">
					<?php
					foreach ( $dest_tours as $i => $tour_item ) :
						$collapsed_class = ( $tour_collapsed_after > 0 && $i >= $tour_collapsed_after ) ? ' is-collapsed' : '';
						?>
						<div class="dest-tour-card-wrap<?php echo esc_attr( $collapsed_class ); ?>">
							<?php get_template_part( 'template-parts/cards/card-tour-price', null, array( 'tour' => $tour_item ) ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( $tour_collapsed_after > 0 && count( $dest_tours ) > $tour_collapsed_after ) : ?>
					<p class="text-center reveal" style="margin-top:24px;">
						<button type="button" class="btn btn--outline" data-tour-show-more data-target="#dest-tour-grid">
							<?php esc_html_e( 'Показать ещё отели', 'zhiraf-ufa' ); ?>
						</button>
					</p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $dest['geo_answer'] ) ) : ?>
		<section class="section geo-answer-block bg-milky">
			<div class="container container--narrow">
				<p class="geo-answer-block__text"><?php echo esc_html( $dest['geo_answer'] ); ?></p>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $dest['for_who'] ) || ! empty( $dest['extra_sections'] ) || ! empty( $dest['emirate_cards'] ) || ! empty( $dest['climate_seasons'] ) ) : ?>
	<section class="section dest-landing-prose bg-milky"<?php echo ! empty( $dest['needs_review'] ) ? ' data-needs-review="1"' : ''; ?>>
		<?php if ( ! empty( $dest['needs_review'] ) ) : ?><!-- ТЕКСТ НА ПРОВЕРКУ: редакторская проверка перед публикацией --><?php endif; ?>
		<div class="container container--narrow">
			<?php if ( ! empty( $dest['hero_lead_extended'] ) ) : ?>
				<p class="dest-landing-prose__intro"><?php echo esc_html( $dest['hero_lead_extended'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $dest['for_who'] ) ) : ?>
				<h2><?php esc_html_e( 'Для кого подойдёт', 'zhiraf-ufa' ); ?></h2>
				<p><?php echo esc_html( $dest['for_who'] ); ?></p>
				<h2><?php esc_html_e( 'Что уточняем при подборе', 'zhiraf-ufa' ); ?></h2>
				<p><?php echo esc_html( $dest['we_clarify'] ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $dest['climate_seasons'] ) && is_array( $dest['climate_seasons'] ) ) : ?>
				<h2><?php esc_html_e( 'Сезон и климат', 'zhiraf-ufa' ); ?></h2>
				<div class="dest-climate-grid">
					<?php foreach ( $dest['climate_seasons'] as $season_row ) : ?>
						<article class="dest-climate-card">
							<p class="dest-climate-card__label"><?php echo esc_html( $season_row['label'] ); ?> <span class="dest-climate-card__months"><?php echo esc_html( $season_row['months'] ); ?></span></p>
							<dl class="dest-climate-card__stats">
								<div><dt><?php esc_html_e( 'Воздух', 'zhiraf-ufa' ); ?></dt><dd><?php echo esc_html( $season_row['air'] ); ?></dd></div>
								<div><dt><?php esc_html_e( 'Вода', 'zhiraf-ufa' ); ?></dt><dd><?php echo esc_html( $season_row['water'] ); ?></dd></div>
							</dl>
							<?php if ( ! empty( $season_row['tip'] ) ) : ?>
								<p class="dest-climate-card__tip"><?php echo esc_html( $season_row['tip'] ); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php elseif ( ! empty( $dest['season'] ) ) : ?>
				<h2><?php esc_html_e( 'Сезон и климат', 'zhiraf-ufa' ); ?></h2>
				<p><?php echo esc_html( $dest['season'] ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $dest['emirate_cards'] ) && is_array( $dest['emirate_cards'] ) ) : ?>
				<h2><?php esc_html_e( 'Какой эмират выбрать для отдыха из Уфы', 'zhiraf-ufa' ); ?></h2>
				<div class="dest-emirate-grid">
					<?php foreach ( $dest['emirate_cards'] as $card ) : ?>
						<article class="dest-emirate-card">
							<?php if ( ! empty( $card['image'] ) ) : ?>
								<?php zhiraf_ufa_img( $card['image'], $card['title'], 'dest-emirate-card__img', true ); ?>
							<?php endif; ?>
							<div class="dest-emirate-card__body">
								<?php if ( ! empty( $card['tag'] ) ) : ?>
									<p class="dest-emirate-card__tag"><?php echo esc_html( $card['tag'] ); ?></p>
								<?php endif; ?>
								<h3 class="dest-emirate-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
								<p class="dest-emirate-card__text"><?php echo esc_html( $card['text'] ); ?></p>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $dest['extra_sections'] ) ) : ?>
				<?php foreach ( $dest['extra_sections'] as $section ) : ?>
					<details class="dest-prose-accordion">
						<summary class="dest-prose-accordion__summary"><?php echo esc_html( $section['h2'] ); ?></summary>
						<div class="dest-prose-accordion__body">
							<?php if ( ! empty( $section['lead'] ) ) : ?>
								<p><?php echo esc_html( $section['lead'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $section['paragraphs'] ) ) : ?>
								<?php foreach ( $section['paragraphs'] as $para ) : ?>
									<p><?php echo wp_kses_post( $para ); ?></p>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</details>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( ! empty( $dest['resort_links'] ) ) : ?>
				<h2><?php esc_html_e( 'Курорты направления', 'zhiraf-ufa' ); ?></h2>
				<ul class="resort-link-list">
					<?php foreach ( $dest['resort_links'] as $link ) : ?>
						<li>
							<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
							<?php if ( ! empty( $link['desc'] ) ) : ?>
								<span class="resort-link-list__desc"><?php echo esc_html( $link['desc'] ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( ! empty( $dest['blog_links'] ) ) : ?>
				<p class="dest-landing-links">
					<?php
					foreach ( $dest['blog_links'] as $link ) {
						printf(
							'<a href="%s">%s</a>',
							esc_url( $link['url'] ),
							esc_html( $link['label'] )
						);
					}
					?>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( ! empty( $dest['faq'] ) ) : ?>
	<section class="section dest-landing-faq">
		<div class="container container--narrow">
			<h2 class="section-title"><?php esc_html_e( 'Вопросы по направлению', 'zhiraf-ufa' ); ?></h2>
			<div class="faq-home__list">
				<?php foreach ( $dest['faq'] as $item ) : ?>
					<details class="faq-home__item">
						<summary class="faq-home__question"><?php echo esc_html( $item['q'] ); ?></summary>
						<div class="faq-home__answer">
							<p><?php echo esc_html( $item['a'] ); ?></p>
							<?php if ( ! empty( $item['link'] ) ) : ?>
								<p><a href="<?php echo esc_url( $item['link'] ); ?>"><?php esc_html_e( 'Подробнее в блоге', 'zhiraf-ufa' ); ?></a></p>
							<?php endif; ?>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php endif; ?>

	<section class="section dest-landing-cta" id="dest-lead">
		<div class="container container--narrow dest-landing-cta__inner">
			<h2><?php esc_html_e( 'Готовы обсудить поездку?', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Оставьте контакты — перезвоним в рабочее время и предложим 2–4 варианта под ваш бюджет.', 'zhiraf-ufa' ); ?></p>
			<form class="zhiraf-form dest-landing-lead-form" data-action="zhiraf_lead" data-form-context="dest-<?php echo esc_attr( $dest_slug ); ?>">
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
					<input type="hidden" name="tour" value="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>" />
					<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Отправить заявку', 'zhiraf-ufa' ); ?></button>
				</div>
				<?php zhiraf_ufa_consent_fields( 'dest-lead-' . $dest_slug ); ?>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
			<p class="dest-landing-cta__alt">
				<button type="button" class="btn btn--outline btn--sm" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>">
					<?php esc_html_e( 'Или пройти квиз', 'zhiraf-ufa' ); ?></button>
			</p>
			<p class="dest-landing-cta__meta">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'zhiraf-ufa' ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты', 'zhiraf-ufa' ); ?></a>
				·
				<a href="tel:+79018105018" data-track="phone"><?php esc_html_e( '+7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</section>
</main>
