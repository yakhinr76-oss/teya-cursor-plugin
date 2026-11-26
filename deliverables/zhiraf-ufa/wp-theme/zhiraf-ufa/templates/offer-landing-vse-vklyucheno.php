<?php
/**
 * All Inclusive offer landing (/vse-vklyucheno/).
 *
 * @package Zhiraf_Ufa
 * @var array $offer Offer config.
 */

if ( ! defined( 'ABSPATH' ) || empty( $offer ) ) {
	return;
}

$ai_tours   = function_exists( 'zhiraf_ufa_get_all_inclusive_showcase_tours' ) ? zhiraf_ufa_get_all_inclusive_showcase_tours( 6 ) : array();
$min_price  = function_exists( 'zhiraf_ufa_all_inclusive_min_price' ) ? zhiraf_ufa_all_inclusive_min_price() : null;
$ai_tabs    = function_exists( 'zhiraf_ufa_all_inclusive_destination_tabs' ) ? zhiraf_ufa_all_inclusive_destination_tabs() : array();
$dest_stub  = array(
	'slug'         => 'vse-vklyucheno',
	'name'         => __( 'All Inclusive', 'zhiraf-ufa' ),
	'quiz_prefill' => ! empty( $offer['quiz_prefill'] ) ? $offer['quiz_prefill'] : '',
);
$vibe_section = function_exists( 'zhiraf_ufa_get_vibe_section' ) ? zhiraf_ufa_get_vibe_section( 'vse-vklyucheno', $dest_stub ) : null;
?>
<main id="primary" class="site-main site-main--offer site-main--all-inclusive">
	<section class="dest-landing-hero offer-landing-hero offer-landing-hero--ai">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<?php if ( ! empty( $offer['eyebrow'] ) ) : ?>
					<p class="dest-landing-hero__eyebrow"><?php echo esc_html( $offer['eyebrow'] ); ?></p>
				<?php endif; ?>
				<h1><?php echo esc_html( $offer['h1'] ); ?></h1>
				<p class="lead dest-landing-hero__lead"><?php echo esc_html( $offer['hero_lead'] ); ?></p>
				<?php if ( $min_price ) : ?>
					<p class="ai-hero-price">
						<?php
						printf(
							esc_html__( 'Туры AI/UAI от %s ₽ за двоих по данным «Библио-Глобус»', 'zhiraf-ufa' ),
							esc_html( $min_price['formatted'] )
						);
						?>
					</p>
				<?php endif; ?>
				<?php if ( ! empty( $offer['hero_badges'] ) && is_array( $offer['hero_badges'] ) ) : ?>
					<ul class="dest-landing-hero__badges">
						<?php foreach ( $offer['hero_badges'] as $badge ) : ?>
							<li class="dest-landing-hero__badge">
								<?php
								if ( function_exists( 'zhiraf_ufa_render_hero_badge_icon' ) ) {
									$icon_markup = zhiraf_ufa_render_hero_badge_icon( $badge );
									if ( '' !== $icon_markup ) {
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										echo $icon_markup;
									}
								}
								?>
								<span><?php echo esc_html( $badge['text'] ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<div class="dest-landing-hero__actions">
					<a class="btn btn--primary btn--lg" href="#ai-tours-grid"><?php esc_html_e( 'Смотреть отели AI/UAI', 'zhiraf-ufa' ); ?></a>
					<a class="btn btn--outline btn--lg" href="#ai-lead"><?php esc_html_e( 'Подбор семейного отеля', 'zhiraf-ufa' ); ?></a>
				</div>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $offer['image'], $offer['image_alt'], 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $ai_tours ) ) : ?>
		<section class="section section--dest-tours section--ai-tours bg-sand" id="ai-tours-grid">
			<div class="container">
				<div class="section-head reveal">
					<div>
						<h2 class="section-title"><?php esc_html_e( 'Отели с питанием AI и Ultra AI', 'zhiraf-ufa' ); ?></h2>
						<p class="lead ai-tours-intro">
							<?php esc_html_e( 'Реальные карточки туроператора: отель, звёзды, питание, вылет и цена за двоих. Код AI/UAI указан на карточке.', 'zhiraf-ufa' ); ?>
						</p>
					</div>
					<?php if ( ! empty( $ai_tabs ) ) : ?>
						<div class="section-tabs ai-meal-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Фильтр направления', 'zhiraf-ufa' ); ?>">
							<?php foreach ( $ai_tabs as $i => $tab ) : ?>
								<button type="button" class="tab-btn<?php echo 0 === $i ? ' is-active' : ''; ?>" data-filter="<?php echo esc_attr( $tab['id'] ); ?>">
									<?php echo esc_html( $tab['label'] ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="tour-price-grid tour-price-grid--dest tour-price-grid--ai">
					<?php foreach ( $ai_tours as $tour_item ) : ?>
						<?php
						get_template_part(
							'template-parts/cards/card-tour-price',
							null,
							array(
								'tour'             => $tour_item,
								'emphasize_flight' => true,
							)
						);
						?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	get_template_part(
		'template-parts/offer/meal-ai-compare',
		null,
		array(
			'lead'       => ! empty( $offer['meal_compare_lead'] ) ? $offer['meal_compare_lead'] : '',
			'highlights' => ! empty( $offer['meal_highlights'] ) ? $offer['meal_highlights'] : array(),
			'rows'       => ! empty( $offer['meal_compare'] ) ? $offer['meal_compare'] : array(),
		)
	);
	get_template_part( 'template-parts/offer/ai-destination-cards' );
	?>

	<?php if ( $vibe_section ) : ?>
		<?php
		get_template_part(
			'template-parts/destination/vibe-cards',
			null,
			array(
				'section'      => $vibe_section,
				'quiz_prefill' => ! empty( $offer['quiz_prefill'] ) ? $offer['quiz_prefill'] : '',
			)
		);
		?>
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

	<section class="section dest-landing-cta" id="ai-lead">
		<div class="container container--narrow dest-landing-cta__inner">
			<h2><?php esc_html_e( 'Подбор семейного отеля All Inclusive', 'zhiraf-ufa' ); ?></h2>
			<p><?php esc_html_e( 'Укажите состав, месяц поездки и телефон — отправим подборку AI/UAI в WhatsApp или перезвоним.', 'zhiraf-ufa' ); ?></p>
			<div class="unified-lead-form-panel">
				<?php
				$ai_prefill = function_exists( 'zhiraf_ufa_hub_messenger_prefill_text' )
					? zhiraf_ufa_hub_messenger_prefill_text( 'ai' )
					: __( 'Здравствуйте! Подберите отель «всё включено» с вылетом из Уфы — состав, даты и бюджет в сообщении.', 'zhiraf-ufa' );
				if ( function_exists( 'zhiraf_ufa_render_unified_lead_form' ) ) {
					zhiraf_ufa_render_unified_lead_form(
						array(
							'form_context'      => 'all-inclusive',
							'consent_id'        => 'all-inclusive',
							'messenger_variant' => 'quiz',
							'message'           => $ai_prefill,
							'tour'              => $offer['quiz_prefill'],
						)
					);
				}
				?>
			</div>
			<p class="dest-landing-cta__meta">
				<a href="tel:+79018105018" data-track="phone"><?php esc_html_e( '+7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Каталог направлений', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</section>
</main>
