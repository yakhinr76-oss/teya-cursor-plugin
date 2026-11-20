<?php
/**
 * Excursion curated landing layout.
 *
 * @package Zhiraf_Ufa
 * @var array<string,mixed>              $dest
 * @var array<string,mixed>              $excursion_pack
 * @var array<int,array<string,mixed>>   $excursion_programs
 */

if ( ! defined( 'ABSPATH' ) || empty( $dest ) || empty( $excursion_pack ) ) {
	return;
}

$dest_slug    = ! empty( $dest['slug'] ) ? $dest['slug'] : ( ! empty( $excursion_pack['slug'] ) ? $excursion_pack['slug'] : 'uzbekistan' );
$program_cnt  = count( $excursion_programs );
$trust_rows   = ! empty( $excursion_pack['hero_trust'] ) ? $excursion_pack['hero_trust'] : array();
$filter_mode  = ! empty( $excursion_pack['catalog_filter_mode'] ) ? (string) $excursion_pack['catalog_filter_mode'] : 'days';
$convert_card = ! empty( $excursion_pack['convert_card'] ) && is_array( $excursion_pack['convert_card'] ) ? $excursion_pack['convert_card'] : array();
$day_filters  = function_exists( 'zhiraf_ufa_excursion_catalog_day_filters' )
	? zhiraf_ufa_excursion_catalog_day_filters( $excursion_pack )
	: array();
$day_ranges   = function_exists( 'zhiraf_ufa_excursion_catalog_day_range_map' )
	? zhiraf_ufa_excursion_catalog_day_range_map( $excursion_pack )
	: array();
?>
<main id="primary" class="site-main site-main--destination site-main--excursion-curated" data-excursion-landing data-excursion-filter-mode="<?php echo esc_attr( $filter_mode ); ?>" data-excursion-day-ranges="<?php echo esc_attr( wp_json_encode( $day_ranges ) ); ?>">
	<section class="dest-landing-hero excursion-hero">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<p class="dest-landing-hero__eyebrow">
					<a class="dest-landing-hero__eyebrow-link" href="<?php echo esc_url( home_url( '/tour/' ) ); ?>">
						<span class="dest-landing-hero__eyebrow-back" aria-hidden="true">← </span><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?>
					</a>
				</p>
				<h1><?php echo esc_html( $excursion_pack['h1'] ); ?></h1>
				<p class="lead dest-landing-hero__lead"><?php echo esc_html( $excursion_pack['hero_lead'] ); ?></p>
				<?php if ( ! empty( $excursion_pack['hero_badges'] ) ) : ?>
					<ul class="dest-landing-hero__badges" aria-label="<?php esc_attr_e( 'Ключевые условия поездки', 'zhiraf-ufa' ); ?>">
						<?php foreach ( $excursion_pack['hero_badges'] as $badge ) : ?>
							<li class="dest-landing-hero__badge">
								<span><?php echo esc_html( $badge['text'] ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<div class="dest-landing-hero__actions">
					<button type="button" class="btn btn--primary btn--lg rounded-xl" data-turciya-lead-open data-curated-lead-open>
						<?php esc_html_e( 'Подобрать маршрут', 'zhiraf-ufa' ); ?>
					</button>
				</div>
				<?php if ( $trust_rows ) : ?>
					<ul class="dest-landing-hero__trust excursion-hero__trust">
						<?php foreach ( $trust_rows as $trust_row ) : ?>
							<li>
								<?php
								if ( ! empty( $trust_row['icon'] ) && function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
									echo zhiraf_ufa_lucide_icon( (string) $trust_row['icon'], 18, 'excursion-trust-icon' );
								}
								?>
								<span><?php echo esc_html( $trust_row['text'] ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<div class="dest-landing-hero__visual">
				<?php
				if ( ! empty( $excursion_pack['image'] ) ) {
					zhiraf_ufa_img( $excursion_pack['image'], $excursion_pack['image_alt'], 'dest-landing-hero__img', true );
				}
				?>
			</div>
		</div>
	</section>

	<section class="section excursion-formats" aria-labelledby="excursion-formats-title">
		<div class="container">
			<h2 id="excursion-formats-title" class="section-title section-title--sm"><?php esc_html_e( 'Выберите формат поездки', 'zhiraf-ufa' ); ?></h2>
			<div class="excursion-formats__grid" role="tablist" aria-label="<?php esc_attr_e( 'Фильтр форматов', 'zhiraf-ufa' ); ?>">
				<?php foreach ( $excursion_pack['format_cards'] as $card ) : ?>
					<button
						type="button"
						class="excursion-format-card"
						data-excursion-format="<?php echo esc_attr( $card['key'] ); ?>"
						role="tab"
						aria-selected="false"
					>
						<?php
						if ( ! empty( $card['icon'] ) && function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo zhiraf_ufa_lucide_icon( (string) $card['icon'], 22, 'excursion-format-card__icon' );
						}
						?>
						<span class="excursion-format-card__title"><?php echo esc_html( $card['title'] ); ?></span>
						<span class="excursion-format-card__desc"><?php echo esc_html( $card['desc'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section excursion-catalog" id="excursion-programs">
		<div class="container">
			<?php
			$catalog_aria = 'transport' === $filter_mode
				? __( 'Фильтр по типу транспорта', 'zhiraf-ufa' )
				: __( 'Фильтр по длительности', 'zhiraf-ufa' );
			?>
			<div class="excursion-catalog__filters" role="tablist" aria-label="<?php echo esc_attr( $catalog_aria ); ?>">
				<button type="button" class="excursion-filter-chip is-active" data-excursion-days="all" data-excursion-transport="all" aria-selected="true">
					<?php
					printf(
						/* translators: %d: program count */
						esc_html__( 'Все программы (%d)', 'zhiraf-ufa' ),
						(int) $program_cnt
					);
					?>
				</button>
				<?php if ( 'transport' === $filter_mode ) : ?>
					<button type="button" class="excursion-filter-chip" data-excursion-transport="avia" aria-selected="false"><?php esc_html_e( 'Авиа из Уфы', 'zhiraf-ufa' ); ?></button>
					<button type="button" class="excursion-filter-chip" data-excursion-transport="bus" aria-selected="false"><?php esc_html_e( 'Автобус из Уфы', 'zhiraf-ufa' ); ?></button>
				<?php else : ?>
					<?php foreach ( $day_filters as $day_filter ) : ?>
						<button type="button" class="excursion-filter-chip" data-excursion-days="<?php echo esc_attr( $day_filter['key'] ); ?>" aria-selected="false">
							<?php echo esc_html( $day_filter['label'] ); ?>
						</button>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<div class="excursion-program-grid">
				<?php foreach ( $excursion_programs as $program ) : ?>
					<?php
					$prog_id    = (string) $program['id'];
					$days       = (int) ( $program['days'] ?? 0 );
					$price_lbl  = function_exists( 'zhiraf_ufa_excursion_format_price' )
						? zhiraf_ufa_excursion_format_price( (int) $program['price_from'] )
						: '';
					$lead_msg   = zhiraf_ufa_excursion_program_lead_message( $program );
					$lead_title = $program['title'];
					?>
					<article
						class="excursion-program-card"
						data-excursion-program
						data-program-format="<?php echo esc_attr( $program['format'] ); ?>"
						data-program-days="<?php echo esc_attr( (string) $days ); ?>"
						data-program-transport="<?php echo esc_attr( ! empty( $program['transport_mode'] ) ? (string) $program['transport_mode'] : 'avia' ); ?>"
					>
						<p class="excursion-program-card__id"><?php echo esc_html( $prog_id ); ?></p>
						<h3 class="excursion-program-card__title"><?php echo esc_html( $program['title'] ); ?></h3>
						<p class="excursion-program-card__route"><?php echo esc_html( $program['route'] ); ?></p>
						<ul class="excursion-program-card__meta">
							<li><?php echo esc_html( $program['duration'] ); ?></li>
							<li><?php echo esc_html( $program['transport'] ); ?></li>
						</ul>
						<p class="excursion-program-card__highlights"><?php echo esc_html( $program['highlights'] ); ?></p>
						<?php if ( ! empty( $program['caveat'] ) ) : ?>
							<p class="excursion-program-card__caveat" role="note">
								<span class="excursion-program-card__caveat-label"><?php esc_html_e( 'Нюанс:', 'zhiraf-ufa' ); ?></span>
								<?php echo esc_html( $program['caveat'] ); ?>
							</p>
						<?php endif; ?>
						<div class="excursion-program-card__footer">
							<div class="excursion-program-card__price">
								<span class="excursion-program-card__price-value"><?php echo esc_html( $price_lbl ); ?></span>
								<span class="excursion-program-card__price-note"><?php echo esc_html( $excursion_pack['price_footnote'] ); ?></span>
							</div>
							<button
								type="button"
								class="btn btn--ghost excursion-program-card__cta"
								data-turciya-lead-open
								data-uon-program-id="<?php echo esc_attr( $prog_id ); ?>"
								data-lead-title="<?php echo esc_attr( $lead_title ); ?>"
								data-lead-subtitle="<?php echo esc_attr( $excursion_pack['modal_default_lead'] ); ?>"
								data-lead-message="<?php echo esc_attr( $lead_msg ); ?>"
								data-lead-tour="<?php echo esc_attr( $prog_id . ' — ' . $lead_title ); ?>"
							>
								<?php esc_html_e( 'Получить программу по дням →', 'zhiraf-ufa' ); ?>
							</button>
						</div>
					</article>
				<?php endforeach; ?>
				<article class="excursion-program-card excursion-program-card--convert">
					<h3 class="excursion-program-card__title">
						<?php
						echo esc_html(
							! empty( $convert_card['title'] )
								? $convert_card['title']
								: __( 'Нужен другой формат?', 'zhiraf-ufa' )
						);
						?>
					</h3>
					<p class="excursion-program-card__highlights">
						<?php
						echo esc_html(
							! empty( $convert_card['text'] )
								? $convert_card['text']
								: __( 'Соберём индивидуальный маршрут под ваши даты и состав.', 'zhiraf-ufa' )
						);
						?>
					</p>
					<div class="excursion-program-card__footer">
						<button type="button" class="btn btn--primary btn--lg rounded-xl" data-turciya-lead-open data-curated-lead-open>
							<?php
							echo esc_html(
								! empty( $convert_card['cta'] )
									? $convert_card['cta']
									: __( 'Описать задачу', 'zhiraf-ufa' )
							);
							?>
						</button>
					</div>
				</article>
			</div>
		</div>
	</section>

	<section class="section excursion-included bg-sand">
		<div class="container excursion-included__grid">
			<div class="excursion-included__copy">
				<h2 class="section-title section-title--sm"><?php echo esc_html( $excursion_pack['included']['title'] ); ?></h2>
				<h3 class="excursion-included__sub"><?php esc_html_e( 'Обычно в цене', 'zhiraf-ufa' ); ?></h3>
				<ul class="excursion-checklist">
					<?php foreach ( $excursion_pack['included']['in_price'] as $line ) : ?>
						<li>
							<?php
							if ( function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo zhiraf_ufa_lucide_icon( 'shield-check', 18, 'excursion-checklist__icon' );
							}
							?>
							<span><?php echo esc_html( $line ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<h3 class="excursion-included__sub"><?php esc_html_e( 'Уточняем заранее', 'zhiraf-ufa' ); ?></h3>
				<ul class="excursion-checklist excursion-checklist--muted">
					<?php foreach ( $excursion_pack['included']['extra'] as $line ) : ?>
						<li>
							<?php
							if ( function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo zhiraf_ufa_lucide_icon( 'tag', 18, 'excursion-checklist__icon' );
							}
							?>
							<span><?php echo esc_html( $line ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="excursion-included__cta-card">
				<h3 class="excursion-included__cta-title"><?php esc_html_e( 'PDF с программой по дням', 'zhiraf-ufa' ); ?></h3>
				<p><?php esc_html_e( 'Выберите мессенджер в модалке — отправим структуру тура и ориентир по бюджету.', 'zhiraf-ufa' ); ?></p>
				<button type="button" class="btn btn--primary btn--lg rounded-xl" data-turciya-lead-open data-curated-lead-open>
					<?php esc_html_e( 'Получить PDF-программу в мессенджер', 'zhiraf-ufa' ); ?>
				</button>
			</div>
		</div>
	</section>

	<section class="section excursion-know">
		<div class="container">
			<h2 class="section-title"><?php echo esc_html( $excursion_pack['know_title'] ); ?></h2>
			<div class="excursion-know__grid">
				<?php foreach ( $excursion_pack['know_cards'] as $card ) : ?>
					<div class="excursion-know-card">
						<?php
						if ( ! empty( $card['icon'] ) && function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo zhiraf_ufa_lucide_icon( (string) $card['icon'], 24, 'excursion-know-card__icon' );
						}
						?>
						<h3 class="excursion-know-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
						<p><?php echo esc_html( $card['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section dest-landing-faq dest-landing-faq--panel">
		<div class="container container--narrow">
			<h2 class="section-title"><?php esc_html_e( 'Частые вопросы', 'zhiraf-ufa' ); ?></h2>
			<div class="dest-landing-faq__panel excursion-faq__panel rounded-3xl">
				<div class="faq-home__list faq-home__list--panel">
					<?php foreach ( $excursion_pack['faq'] as $item ) : ?>
						<details class="faq-home__item">
							<summary class="faq-home__question"><?php echo esc_html( $item['q'] ); ?></summary>
							<div class="faq-home__answer">
								<p><?php echo esc_html( $item['a'] ); ?></p>
							</div>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="section excursion-seo-tail">
		<div class="container container--narrow">
			<?php foreach ( $excursion_pack['seo_details'] as $block ) : ?>
				<details class="excursion-seo-tail__item">
					<summary><?php echo esc_html( $block['title'] ); ?></summary>
					<div class="excursion-seo-tail__body">
						<?php echo wp_kses_post( $block['html'] ); ?>
					</div>
				</details>
			<?php endforeach; ?>
			<p class="excursion-seo-tail__chips-label"><?php esc_html_e( 'Другие экскурсионные направления', 'zhiraf-ufa' ); ?></p>
			<div class="excursion-seo-tail__chips">
				<?php foreach ( $excursion_pack['excursion_chips'] as $chip ) : ?>
					<a class="excursion-chip-link" href="<?php echo esc_url( home_url( $chip['url'] ) ); ?>"><?php echo esc_html( $chip['label'] ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section excursion-final-cta" id="dest-lead">
		<div class="container container--narrow excursion-final-cta__inner">
			<h2 class="section-title"><?php echo esc_html( $excursion_pack['final_cta_title'] ); ?></h2>
			<form class="zhiraf-form dest-landing-lead-form excursion-final-cta__form" data-action="zhiraf_lead" data-form-context="dest-<?php echo esc_attr( $dest_slug ); ?>-excursion">
				<?php
				if ( function_exists( 'zhiraf_ufa_render_messenger_picker' ) ) {
					zhiraf_ufa_render_messenger_picker(
						array(
							'name'    => 'lead_messenger',
							'default' => 'WhatsApp',
							'label'   => __( 'Куда отправить подборку:', 'zhiraf-ufa' ),
						)
					);
				}
				?>
				<label class="form-label dest-landing-lead-form__field">
					<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span>
					<input type="tel" name="phone" data-phone-mask placeholder="<?php esc_attr_e( '+7 …', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" inputmode="tel" />
				</label>
				<textarea name="message" class="form-input dest-landing-lead-form__message" rows="3" placeholder="<?php esc_attr_e( 'Города, даты, состав, темп', 'zhiraf-ufa' ); ?>"><?php
					echo esc_textarea(
						sprintf(
							/* translators: %s: destination name */
							__( 'Здравствуйте! Подберите экскурсионный тур в %s из Уфы: города, даты и состав.', 'zhiraf-ufa' ),
							$excursion_pack['name']
						)
					);
				?></textarea>
				<div class="zhiraf-hp" aria-hidden="true">
					<input type="hidden" name="tour" value="<?php echo esc_attr( $excursion_pack['quiz_prefill'] ); ?>" data-lead-tour />
					<input type="hidden" name="destination" value="<?php echo esc_attr( $excursion_pack['name'] ); ?>" />
					<input type="hidden" name="destination_slug" value="<?php echo esc_attr( $dest_slug ); ?>" />
					<input type="hidden" name="uon_hotel_id" value="" />
				</div>
				<?php zhiraf_ufa_consent_fields( 'dest-excursion-' . $dest_slug ); ?>
				<button type="submit" class="btn btn--primary btn--lg btn--block rounded-xl" data-messenger-submit-label>
					<?php esc_html_e( 'Отправить →', 'zhiraf-ufa' ); ?>
				</button>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
		</div>
	</section>
</main>
<?php
get_template_part(
	'template-parts/destination/turciya-lead-modal',
	null,
	array(
		'dest'                     => $dest,
		'dest_slug'                => $dest_slug,
		'modal_title'              => $excursion_pack['modal_default_title'],
		'modal_lead'               => $excursion_pack['modal_default_lead'],
		'modal_message'            => sprintf(
			/* translators: %s: destination name */
			__( 'Здравствуйте! Нужна программа экскурсионного тура в %s из Уфы — укажу даты и состав в переписке.', 'zhiraf-ufa' ),
			$excursion_pack['name']
		),
	)
);
get_template_part(
	'template-parts/destination/sticky-mobile-bar',
	null,
	array(
		'dest'              => $dest,
		'dest_slug'         => $dest_slug,
		'use_turciya_modal' => true,
		'quiz_prefill'      => $excursion_pack['quiz_prefill'],
		'wa_text'           => __( 'Здравствуйте! Подберите экскурсионный тур в Узбекистан из Уфы.', 'zhiraf-ufa' ),
	)
);