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
$use_curated_landing = function_exists( 'zhiraf_ufa_destination_uses_curated_landing' )
	? zhiraf_ufa_destination_uses_curated_landing( $dest )
	: ! empty( $dest['curated_landing_v1'] );
$curated_has_catalog = $use_curated_landing
	&& function_exists( 'zhiraf_ufa_curated_catalog_hotels' )
	&& ! empty( zhiraf_ufa_curated_catalog_hotels( $dest_slug ) );
$dest_faq_items      = function_exists( 'zhiraf_ufa_merge_faq_lists' )
	? zhiraf_ufa_merge_faq_lists( ! empty( $dest['faq'] ) ? $dest['faq'] : array() )
	: ( ! empty( $dest['faq'] ) ? $dest['faq'] : array() );
?>
<main id="primary" class="site-main site-main--destination">
	<section class="dest-landing-hero">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<p class="dest-landing-hero__eyebrow">
					<a class="dest-landing-hero__eyebrow-link" href="<?php echo esc_url( home_url( '/tour/' ) ); ?>">
						<span class="dest-landing-hero__eyebrow-back" aria-hidden="true">← </span><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?>
					</a>
				</p>
				<h1><?php echo esc_html( $dest['h1'] ); ?></h1>
				<p class="lead dest-landing-hero__lead"><?php echo esc_html( $dest['hero_lead'] ); ?></p>
				<?php if ( ! empty( $dest['hero_badges'] ) && is_array( $dest['hero_badges'] ) ) : ?>
					<ul class="dest-landing-hero__badges" aria-label="<?php esc_attr_e( 'Ключевые условия поездки', 'zhiraf-ufa' ); ?>">
						<?php foreach ( $dest['hero_badges'] as $badge ) : ?>
							<li class="dest-landing-hero__badge">
								<?php
								if ( function_exists( 'zhiraf_ufa_render_hero_badge_icon' ) ) {
									$icon_markup = zhiraf_ufa_render_hero_badge_icon( $badge );
									if ( '' !== $icon_markup ) {
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG/icons escaped in helper.
										echo $icon_markup;
									}
								} elseif ( ! empty( $badge['icon'] ) ) {
									?>
									<span class="dest-landing-hero__badge-icon" aria-hidden="true"><?php echo esc_html( $badge['icon'] ); ?></span>
									<?php
								}
								?>
								<span><?php echo esc_html( $badge['text'] ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( $use_curated_landing && ! empty( $dest['curated_resort_chips'] ) ) : ?>
					<?php
					get_template_part(
						'template-parts/destination/turciya-resort-chips',
						null,
						array(
							'context'   => 'hero',
							'dest_slug' => $dest_slug,
						)
					);
					?>
				<?php endif; ?>
				<div class="dest-landing-hero__actions">
					<?php if ( $use_curated_landing ) : ?>
						<?php if ( $curated_has_catalog ) : ?>
							<?php
							$hero_catalog_cta = ! empty( $dest['curated_hero_catalog_cta'] )
								? (string) $dest['curated_hero_catalog_cta']
								: __( 'Смотреть 6 отелей', 'zhiraf-ufa' );
							?>
							<a class="btn btn--primary btn--lg" href="#dest-curated-hotels"><?php echo esc_html( $hero_catalog_cta ); ?></a>
						<?php endif; ?>
						<?php
						$hero_compare_btn = function_exists( 'get_field' ) ? get_field( 'resort_compare_btn_text', get_queried_object() ) : '';
						if ( empty( $hero_compare_btn ) && ! empty( $dest['curated_compare_btn_default'] ) ) {
							$hero_compare_btn = (string) $dest['curated_compare_btn_default'];
						}
						$show_hero_compare = ! empty( $hero_compare_btn );
						if ( isset( $dest['curated_hero_show_compare'] ) ) {
							$show_hero_compare = ! empty( $hero_compare_btn ) && ! empty( $dest['curated_hero_show_compare'] );
						}
						if ( $show_hero_compare ) :
							?>
							<button type="button" class="btn btn--outline btn--lg" data-turciya-lead-open data-curated-lead-open><?php echo esc_html( $hero_compare_btn ); ?></button>
						<?php endif; ?>
					<?php else : ?>
						<a class="btn btn--primary btn--lg" href="#dest-express-pick"><?php esc_html_e( 'Подбор за 10 секунд', 'zhiraf-ufa' ); ?></a>
						<a class="btn btn--outline btn--lg" href="#dest-price-tiers"><?php esc_html_e( 'Ценовые ориентиры', 'zhiraf-ufa' ); ?></a>
					<?php endif; ?>
				</div>
				<?php if ( ! $use_curated_landing && ! empty( $dest['hero_cta_hint'] ) ) : ?>
					<p class="dest-landing-hero__cta-hint"><?php echo esc_html( $dest['hero_cta_hint'] ); ?></p>
				<?php endif; ?>
				<?php
				$show_hero_trust = $use_curated_landing
					&& ( ! isset( $dest['curated_hero_show_trust'] ) || ! empty( $dest['curated_hero_show_trust'] ) );
				if ( $show_hero_trust ) :
				?>
					<?php
					$curated_trust = ! empty( $dest['curated_hero_trust'] ) && is_array( $dest['curated_hero_trust'] )
						? $dest['curated_hero_trust']
						: array(
							array(
								'icon' => '📅',
								'text' => __( 'Вылеты подберём под ваши даты отпуска', 'zhiraf-ufa' ),
							),
							array(
								'icon' => '🛡️',
								'text' => __( 'Честные цены: сборы и страховка в расчёте', 'zhiraf-ufa' ),
							),
							array(
								'icon' => '💬',
								'text' => __( 'Ответим в WhatsApp за 15 минут в рабочее время', 'zhiraf-ufa' ),
							),
						);
					?>
					<ul class="dest-landing-hero__trust">
						<?php foreach ( $curated_trust as $trust_row ) : ?>
							<?php if ( empty( $trust_row['text'] ) ) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<li>
								<?php if ( ! empty( $trust_row['icon'] ) ) : ?>
									<span aria-hidden="true"><?php echo esc_html( $trust_row['icon'] ); ?></span>
								<?php endif; ?>
								<?php echo esc_html( $trust_row['text'] ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
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

	<?php
	if ( function_exists( 'zhiraf_ufa_render_destination_prominent_resort_nav' ) ) {
		zhiraf_ufa_render_destination_prominent_resort_nav( $dest_slug, $dest );
	}
	?>

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

	<?php if ( $use_curated_landing ) : ?>
		<?php
		if ( $curated_has_catalog ) {
			get_template_part(
				'template-parts/destination/turciya-curated-showcase',
				null,
				array(
					'dest_slug' => $dest_slug,
					'dest'      => $dest,
				)
			);
		}
		if ( ! empty( $dest['intent_block'] ) || ! empty( $dest['curated_kids_section'] ) ) {
			$intent_block = zhiraf_ufa_resolve_intent_block( $dest_slug, $dest );
			if ( $intent_block ) {
				get_template_part(
					'template-parts/sections/intent-checklist',
					null,
					array(
						'dest_slug' => $dest_slug,
						'block'     => $intent_block,
					)
				);
			}
		}
		if ( ! empty( $dest['curated_expert_section'] ) ) {
			get_template_part(
				'template-parts/destination/turciya-expert-nuances',
				null,
				array( 'dest_slug' => $dest_slug )
			);
		}
		?>
	<?php else : ?>
	<?php
	if ( function_exists( 'zhiraf_ufa_render_landing_conversion_stack' ) ) {
		zhiraf_ufa_render_landing_conversion_stack( $dest_slug, $dest, 'destination' );
	}
	?>
	<?php endif; ?>

	<?php
	if ( ! $use_curated_landing ) {
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
				'emirate' => $showcase['emirate'] ?? '',
				'lead'    => $showcase['lead'] ?? '',
			)
		);
	endif;
	}
	?>

	<?php
	$dest_show_prose_block = empty( $dest['hide_landing_prose'] )
		&& ( ! empty( $dest['for_who'] ) || ! empty( $dest['extra_sections'] ) || ! empty( $dest['emirate_cards'] ) || ! empty( $dest['climate_seasons'] ) || $dest_show_resort_compare_in_prose || ! empty( $dest['seo_prose_accordions'] ) || ! empty( $dest['season_at_a_glance'] ) );
	$seo_prose_after_faq   = ! empty( $dest['seo_prose_after_faq'] );
	$dest_prose_args       = array(
		'dest'                            => $dest,
		'central_module'                  => $central_module,
		'dest_show_resort_compare_in_prose' => $dest_show_resort_compare_in_prose,
	);
	?>
	<?php if ( $dest_show_prose_block && ! $seo_prose_after_faq ) : ?>
		<?php get_template_part( 'template-parts/destination/destination-prose-stack', null, $dest_prose_args ); ?>
	<?php endif; ?>

	<?php if ( ! empty( $dest_faq_items ) ) : ?>
	<section class="section dest-landing-faq<?php echo $use_curated_landing ? ' dest-landing-faq--panel' : ''; ?>">
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
			<div class="dest-landing-faq__panel">
				<div class="faq-home__list<?php echo $use_curated_landing ? ' faq-home__list--panel' : ''; ?>">
					<?php foreach ( $dest_faq_items as $item ) : ?>
						<details class="faq-home__item">
							<summary class="faq-home__question"><?php echo esc_html( $item['q'] ); ?></summary>
							<div class="faq-home__answer">
								<p><?php echo wp_kses_post( $item['a'] ); ?></p>
								<?php if ( ! empty( $item['link'] ) ) : ?>
									<p><a href="<?php echo esc_url( $item['link'] ); ?>"><?php esc_html_e( 'Подробнее в блоге', 'zhiraf-ufa' ); ?></a></p>
								<?php endif; ?>
							</div>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<?php endif; ?>

	<?php if ( $dest_show_prose_block && $seo_prose_after_faq ) : ?>
		<?php get_template_part( 'template-parts/destination/destination-prose-stack', null, $dest_prose_args ); ?>
	<?php endif; ?>

	<?php if ( $use_curated_landing ) : ?>
		<?php if ( ! empty( $dest['curated_dest_lead_form'] ) ) : ?>
			<?php
			get_template_part(
				'template-parts/destination/destination-curated-lead',
				null,
				array(
					'dest'      => $dest,
					'dest_slug' => $dest_slug,
				)
			);
			get_template_part(
				'template-parts/destination/turciya-lead-modal',
				null,
				array(
					'dest'      => $dest,
					'dest_slug' => $dest_slug,
				)
			);
			?>
		<?php else : ?>
			<?php
			get_template_part(
				'template-parts/destination/turciya-resort-compare-lead',
				null,
				array(
					'dest'      => $dest,
					'dest_slug' => $dest_slug,
				)
			);
			?>
		<?php endif; ?>
	<?php elseif ( ! $use_curated_landing ) : ?>
	<section class="section dest-landing-cta" id="dest-lead">
		<div class="container container--narrow dest-landing-cta__inner">
			<h2><?php esc_html_e( 'Готовы обсудить поездку?', 'zhiraf-ufa' ); ?></h2>
			<p>
				<?php
				$cta_lead = ! empty( $dest['cta_lead'] ) ? $dest['cta_lead'] : __( 'Оставьте контакты — перезвоним в рабочее время и предложим 2–4 варианта под ваш бюджет.', 'zhiraf-ufa' );
				if ( strpos( $cta_lead, 'Быстрее ответить в WhatsApp' ) !== false ) {
					$cta_lead = __( 'Нужен ответ прямо сейчас? Нажмите кнопку WhatsApp сразу под каталогом отелей выше.', 'zhiraf-ufa' );
				}
				echo esc_html( $cta_lead );
				?>
			</p>
			<?php
			$dest_name_cta = ! empty( $dest['name'] ) ? (string) $dest['name'] : $dest_slug;
			$cta_message   = sprintf(
				/* translators: %s: destination name */
				__( 'Здравствуйте! Помогите выбрать отель по направлению «%s» — вылет из Уфы, даты и состав в сообщении.', 'zhiraf-ufa' ),
				$dest_name_cta
			);
			?>
			<div class="unified-lead-form-panel">
				<?php
				if ( function_exists( 'zhiraf_ufa_render_unified_lead_form' ) ) {
					zhiraf_ufa_render_unified_lead_form(
						array(
							'form_context'        => 'dest-' . $dest_slug,
							'consent_id'          => 'dest-lead-' . $dest_slug,
							'messenger_variant'   => 'quiz',
							'message'             => $cta_message,
							'message_placeholder' => ! empty( $dest['cta_message_placeholder'] ) ? (string) $dest['cta_message_placeholder'] : __( 'Даты, состав, бюджет', 'zhiraf-ufa' ),
							'tour'                => ! empty( $dest['quiz_prefill'] ) ? (string) $dest['quiz_prefill'] : '',
							'destination'         => $dest_name_cta,
							'destination_slug'    => $dest_slug,
							'lead_tour_attr'      => true,
							'express_pick'        => '',
						)
					);
				}
				?>
			</div>
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
	<?php endif; ?>
</main>
<?php
get_template_part(
	'template-parts/destination/sticky-mobile-bar',
	null,
	array(
		'wa_text'           => sprintf( __( 'Здравствуйте! Нужен подбор тура: %s', 'zhiraf-ufa' ), $dest['quiz_prefill'] ?? $dest_slug ),
		'quiz_prefill'      => ! empty( $dest['quiz_prefill'] ) ? $dest['quiz_prefill'] : '',
		'use_curated_modal'   => $use_curated_landing,
		'use_turciya_modal'   => $use_curated_landing,
	)
);
?>
