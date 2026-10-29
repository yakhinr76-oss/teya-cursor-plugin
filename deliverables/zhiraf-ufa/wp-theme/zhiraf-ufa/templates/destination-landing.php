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
$central_module      = 'battle';
$dest_show_resort_compare_in_prose = false;
$dest_faq_items      = function_exists( 'zhiraf_ufa_merge_faq_lists' )
	? zhiraf_ufa_merge_faq_lists( ! empty( $dest['faq'] ) ? $dest['faq'] : array() )
	: ( ! empty( $dest['faq'] ) ? $dest['faq'] : array() );
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
				<?php if ( ! empty( $dest['hero_badges'] ) && is_array( $dest['hero_badges'] ) ) : ?>
					<ul class="dest-landing-hero__badges" aria-label="<?php esc_attr_e( 'Ключевые условия поездки', 'zhiraf-ufa' ); ?>">
						<?php foreach ( $dest['hero_badges'] as $badge ) : ?>
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
					<a class="btn btn--primary btn--lg" href="#dest-express-pick"><?php esc_html_e( 'Подбор за 10 секунд', 'zhiraf-ufa' ); ?></a>
					<a class="btn btn--outline btn--lg" href="#dest-price-tiers"><?php esc_html_e( 'Ценовые ориентиры', 'zhiraf-ufa' ); ?></a>
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

	<?php if ( ! empty( $dest['booking_modes'] ) && is_array( $dest['booking_modes'] ) ) : ?>
		<section class="section section--domestic-modes bg-sand">
			<div class="container container--narrow">
				<h2 class="section-title" style="font-size:1.5rem;"><?php esc_html_e( 'Как можно забронировать', 'zhiraf-ufa' ); ?></h2>
				<ul class="dest-domestic-modes">
					<?php foreach ( $dest['booking_modes'] as $mode ) : ?>
						<li><?php echo esc_html( $mode ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php
	if ( function_exists( 'zhiraf_ufa_render_landing_conversion_stack' ) ) {
		zhiraf_ufa_render_landing_conversion_stack( $dest_slug, $dest, 'destination' );
	}
	?>

	<?php
	$hotels_showcases = function_exists( 'zhiraf_ufa_hotels_showcase_for_destinations' )
		? zhiraf_ufa_hotels_showcase_for_destinations()
		: array();
	if ( ! empty( $hotels_showcases[ $dest_slug ] ) ) :
		$showcase = $hotels_showcases[ $dest_slug ];
		get_template_part(
			'template-parts/hotel/section-hotels-showcase',
			null,
			array(
				'country' => $showcase['country'],
				'title'   => $showcase['title'],
				'region'  => $showcase['region'] ?? '',
				'lead'    => $showcase['lead'] ?? '',
			)
		);
	endif;
	?>

	<?php
	$dest_show_prose_block = empty( $dest['hide_landing_prose'] )
		&& ( ! empty( $dest['for_who'] ) || ! empty( $dest['extra_sections'] ) || ! empty( $dest['emirate_cards'] ) || ! empty( $dest['climate_seasons'] ) || $dest_show_resort_compare_in_prose || ! empty( $dest['seo_prose_accordions'] ) || ! empty( $dest['season_at_a_glance'] ) );
	?>
	<?php if ( $dest_show_prose_block ) : ?>
	<section class="section dest-landing-prose bg-milky"<?php echo ! empty( $dest['needs_review'] ) ? ' data-needs-review="1"' : ''; ?>>
		<?php if ( ! empty( $dest['needs_review'] ) ) : ?><!-- ТЕКСТ НА ПРОВЕРКУ: редакторская проверка перед публикацией --><?php endif; ?>
		<div class="container container--narrow">
			<?php if ( ! empty( $dest['season_at_a_glance'] ) && is_array( $dest['season_at_a_glance'] ) && ! empty( $dest['season_at_a_glance']['rows'] ) ) : ?>
				<h2 class="section-title dest-season-glance__title"><?php echo esc_html( ! empty( $dest['season_at_a_glance']['title'] ) ? $dest['season_at_a_glance']['title'] : __( 'Когда куда лететь', 'zhiraf-ufa' ) ); ?></h2>
				<table class="dest-season-glance">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Курорт', 'zhiraf-ufa' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Лучший сезон для купания', 'zhiraf-ufa' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $dest['season_at_a_glance']['rows'] as $row ) : ?>
							<tr>
								<td><?php echo esc_html( $row['resort'] ?? '' ); ?></td>
								<td><?php echo esc_html( $row['when'] ?? '' ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<?php if ( ! empty( $dest['seo_prose_accordions'] ) ) : ?>
				<details class="dest-prose-accordion dest-prose-accordion--seo">
					<summary class="dest-prose-accordion__summary">
						<?php
						echo esc_html(
							! empty( $dest['prose_accordion_title'] )
								? $dest['prose_accordion_title']
								: __( 'Подробнее о регионах, сезоне и пакете', 'zhiraf-ufa' )
						);
						?>
					</summary>
					<div class="dest-prose-accordion__body">
						<?php if ( ! empty( $dest['hero_lead_extended'] ) ) : ?>
							<p><?php echo esc_html( $dest['hero_lead_extended'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['geo_answer'] ) ) : ?>
							<p><?php echo esc_html( $dest['geo_answer'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['for_who'] ) ) : ?>
							<h3><?php esc_html_e( 'Для кого подойдёт', 'zhiraf-ufa' ); ?></h3>
							<p><?php echo esc_html( $dest['for_who'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['we_clarify'] ) ) : ?>
							<h3><?php esc_html_e( 'Что уточняем при подборе', 'zhiraf-ufa' ); ?></h3>
							<p><?php echo esc_html( $dest['we_clarify'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['season'] ) ) : ?>
							<h3><?php esc_html_e( 'Пляжный сезон', 'zhiraf-ufa' ); ?></h3>
							<p><?php echo esc_html( $dest['season'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['climate_seasons'] ) && is_array( $dest['climate_seasons'] ) ) : ?>
							<h3><?php esc_html_e( 'Сезон и климат по месяцам', 'zhiraf-ufa' ); ?></h3>
							<div class="dest-climate-grid dest-climate-grid--accordion">
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
						<?php endif; ?>
					</div>
				</details>
			<?php else : ?>
				<?php if ( ! empty( $dest['hero_lead_extended'] ) ) : ?>
					<p class="dest-landing-prose__intro"><?php echo esc_html( $dest['hero_lead_extended'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $dest['for_who'] ) ) : ?>
					<h2><?php esc_html_e( 'Для кого подойдёт', 'zhiraf-ufa' ); ?></h2>
					<p><?php echo esc_html( $dest['for_who'] ); ?></p>
					<h2><?php esc_html_e( 'Что уточняем при подборе', 'zhiraf-ufa' ); ?></h2>
					<p><?php echo esc_html( $dest['we_clarify'] ); ?></p>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( empty( $dest['seo_prose_accordions'] ) && 'season' !== $central_module && ! empty( $dest['climate_seasons'] ) && is_array( $dest['climate_seasons'] ) ) : ?>
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
			<?php elseif ( empty( $dest['seo_prose_accordions'] ) && ! empty( $dest['season'] ) ) : ?>
				<h2><?php esc_html_e( 'Сезон и климат', 'zhiraf-ufa' ); ?></h2>
				<p><?php echo esc_html( $dest['season'] ); ?></p>
			<?php endif; ?>

			<?php if ( $dest_show_resort_compare_in_prose && is_array( $dest['resort_compare'] ) ) : ?>
				<h2><?php echo esc_html( ! empty( $dest['resort_compare_title'] ) ? $dest['resort_compare_title'] : __( 'Нячанг или Фукуок: что выбрать', 'zhiraf-ufa' ) ); ?></h2>
				<div class="dest-resort-compare">
					<?php foreach ( $dest['resort_compare'] as $col ) : ?>
						<article class="dest-resort-compare__col">
							<?php if ( ! empty( $col['image'] ) ) : ?>
								<?php zhiraf_ufa_img( $col['image'], $col['title'], 'dest-resort-compare__img', true ); ?>
							<?php endif; ?>
							<div class="dest-resort-compare__body">
								<?php if ( ! empty( $col['tag'] ) ) : ?>
									<p class="dest-resort-compare__tag"><?php echo esc_html( $col['tag'] ); ?></p>
								<?php endif; ?>
								<h3 class="dest-resort-compare__title"><?php echo esc_html( $col['title'] ); ?></h3>
								<?php if ( ! empty( $col['rows'] ) && is_array( $col['rows'] ) ) : ?>
									<dl class="dest-resort-compare__rows">
										<?php foreach ( $col['rows'] as $row ) : ?>
											<div>
												<dt><?php echo esc_html( $row['label'] ); ?></dt>
												<dd><?php echo esc_html( $row['value'] ); ?></dd>
											</div>
										<?php endforeach; ?>
									</dl>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! in_array( $central_module, array( 'vibe', 'battle' ), true ) && ! empty( $dest['emirate_cards'] ) && is_array( $dest['emirate_cards'] ) ) : ?>
				<h2><?php echo esc_html( ! empty( $dest['emirate_cards_title'] ) ? $dest['emirate_cards_title'] : __( 'Какой эмират выбрать для отдыха из Уфы', 'zhiraf-ufa' ) ); ?></h2>
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

	<?php if ( ! empty( $dest_faq_items ) ) : ?>
	<section class="section dest-landing-faq">
		<div class="container container--narrow">
			<h2 class="section-title">
				<?php
				echo esc_html(
					! empty( $dest['faq_title'] )
						? $dest['faq_title']
						: __( 'Вопросы по направлению', 'zhiraf-ufa' )
				);
				?>
			</h2>
			<div class="faq-home__list">
				<?php foreach ( $dest_faq_items as $item ) : ?>
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
			<p><?php echo esc_html( ! empty( $dest['cta_lead'] ) ? $dest['cta_lead'] : __( 'Оставьте контакты — перезвоним в рабочее время и предложим 2–4 варианта под ваш бюджет.', 'zhiraf-ufa' ) ); ?></p>
			<form class="zhiraf-form dest-landing-lead-form" data-action="zhiraf_lead" data-form-context="dest-<?php echo esc_attr( $dest_slug ); ?>">
				<div class="dest-landing-lead-form__fields">
					<label class="form-label dest-landing-lead-form__field">
						<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Ваше имя', 'zhiraf-ufa' ); ?></span>
						<input type="text" name="name" placeholder="<?php esc_attr_e( 'Как к вам обращаться', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="name" />
					</label>
					<label class="form-label dest-landing-lead-form__field">
						<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span>
						<input type="tel" name="phone" placeholder="<?php esc_attr_e( '+7 …', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" inputmode="tel" />
					</label>
					<?php if ( ! empty( $dest['cta_message_placeholder'] ) ) : ?>
						<textarea name="message" class="form-input dest-landing-lead-form__message" rows="3" placeholder="<?php echo esc_attr( $dest['cta_message_placeholder'] ); ?>"></textarea>
					<?php endif; ?>
					<label class="form-label dest-landing-lead-form__channel-label">
						<span class="dest-landing-lead-form__channel-text"><?php esc_html_e( 'Куда отправить подборку', 'zhiraf-ufa' ); ?></span>
						<select name="contact_channel" class="form-input">
							<option value="<?php esc_attr_e( 'WhatsApp', 'zhiraf-ufa' ); ?>" selected><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Telegram', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Telegram', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Звонок', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Звонок', 'zhiraf-ufa' ); ?></option>
						</select>
					</label>
					<div class="zhiraf-hp" aria-hidden="true">
						<input type="hidden" name="tour" value="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>" data-lead-tour />
						<input type="hidden" name="destination" value="<?php echo esc_attr( ! empty( $dest['name'] ) ? $dest['name'] : $dest_slug ); ?>" />
						<input type="hidden" name="destination_slug" value="<?php echo esc_attr( $dest_slug ); ?>" />
						<input type="hidden" name="express_pick" value="" data-lead-express />
					</div>
					<?php zhiraf_ufa_consent_fields( 'dest-lead-' . $dest_slug ); ?>
					<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Отправить заявку', 'zhiraf-ufa' ); ?></button>
				</div>
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
<?php
get_template_part(
	'template-parts/destination/sticky-mobile-bar',
	null,
	array(
		'wa_text'      => sprintf( __( 'Здравствуйте! Нужен подбор тура: %s', 'zhiraf-ufa' ), $dest['quiz_prefill'] ?? $dest_slug ),
		'quiz_prefill' => ! empty( $dest['quiz_prefill'] ) ? $dest['quiz_prefill'] : '',
	)
);
?>
