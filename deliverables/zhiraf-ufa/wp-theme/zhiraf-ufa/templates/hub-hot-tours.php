<?php
/**
 * Hub: hot tours (/goryashchie-tury/).
 *
 * @package Zhiraf_Ufa
 * @var array $offer
 */

if ( ! defined( 'ABSPATH' ) || empty( $offer ) ) {
	return;
}

$hot_tours               = zhiraf_ufa_get_hot_hub_catalog_tours( 24 );
$hub_hot_initial_visible = 9;
$tabs                    = zhiraf_ufa_hot_hub_country_tabs( $hot_tours );
$visa      = zhiraf_ufa_hot_hub_visa_free_cards();
$how       = zhiraf_ufa_hot_hub_how_cards();
$faq       = zhiraf_ufa_hot_hub_faq();
$badges    = array(
	array(
		'icon_svg' => 'plane',
		'text'     => __( 'Вылеты из Уфы', 'zhiraf-ufa' ),
	),
	array(
		'icon_svg' => 'receipt',
		'text'     => __( 'Актуальные цены с топливным сбором', 'zhiraf-ufa' ),
	),
	array(
		'icon_svg' => 'shield-check',
		'text'     => __( 'Быстрый вылет без визы', 'zhiraf-ufa' ),
	),
);
?>
<main id="primary" class="site-main site-main--offer site-main--hub-hot">
	<section class="section dest-landing-hero resort-v2-hero hub-offer-hero bg-milky">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<h1><?php echo esc_html( $offer['h1'] ); ?></h1>
				<p class="lead dest-landing-hero__lead"><?php echo esc_html( $offer['hero_lead'] ); ?></p>
				<ul class="dest-landing-hero__badges hub-offer-badges">
					<?php foreach ( $badges as $badge ) : ?>
						<li class="dest-landing-hero__badge">
							<?php
							if ( ! empty( $badge['icon_svg'] ) && function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
								echo zhiraf_ufa_lucide_icon( (string) $badge['icon_svg'], 18, 'dest-landing-hero__badge-svg hub-offer-badge__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
							<span><?php echo esc_html( $badge['text'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="dest-landing-hero__actions">
					<button
						type="button"
						class="btn btn--primary btn--lg rounded-xl"
						data-turciya-lead-open
						data-lead-title="<?php esc_attr_e( 'Горящие на ваши даты', 'zhiraf-ufa' ); ?>"
						data-lead-tour="<?php echo esc_attr( $offer['quiz_prefill'] ?? '' ); ?>"
					>
						<?php esc_html_e( 'Прислать горящие на мои даты', 'zhiraf-ufa' ); ?>
					</button>
				</div>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $offer['image'], $offer['image_alt'], 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<section class="section hub-offer-catalog bg-sand" id="hot-hub-catalog" data-hub-offer-root data-hub-offer="hot">
		<div class="container">
			<div class="section-head">
				<h2 class="section-title"><?php esc_html_e( 'Витрина горящих туров', 'zhiraf-ufa' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Сортировка по ближайшим датам вылета и минимальной цене за двоих.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="hub-offer-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Страна', 'zhiraf-ufa' ); ?>">
				<?php foreach ( $tabs as $i => $tab ) : ?>
					<button
						type="button"
						class="hub-offer-tabs__btn<?php echo 0 === $i ? ' is-active' : ''; ?>"
						data-hub-country-filter="<?php echo esc_attr( $tab['id'] ); ?>"
						role="tab"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
					>
						<?php echo esc_html( $tab['label'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>
			<div
				class="tour-price-grid tour-price-grid--hub"
				data-hub-tour-grid
				data-hub-initial-visible="<?php echo (int) $hub_hot_initial_visible; ?>"
			>
				<?php foreach ( $hot_tours as $hub_tour_index => $tour_item ) : ?>
					<div
						class="hub-tour-wrap<?php echo $hub_tour_index >= $hub_hot_initial_visible ? ' is-hub-collapsed' : ''; ?>"
						data-hub-country="<?php echo esc_attr( (string) ( $tour_item['country_slug'] ?? '' ) ); ?>"
					>
						<?php
						get_template_part(
							'template-parts/cards/card-tour-price',
							null,
							array(
								'tour'                    => $tour_item,
								'card_index'              => $hub_tour_index,
								'emphasize_hot_departure' => true,
								'emphasize_flight'        => true,
								'hub_calm_badges'         => true,
								'cta_class'               => 'btn btn--ghost tour-card__cta',
							)
						);
						?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( count( $hot_tours ) > $hub_hot_initial_visible ) : ?>
				<div class="hub-offer-catalog-more" data-hub-tour-more-wrap>
					<button
						type="button"
						class="btn btn--outline rounded-xl"
						data-hub-tour-more
						aria-expanded="false"
						data-label-more="<?php esc_attr_e( 'Показать ещё', 'zhiraf-ufa' ); ?>"
						data-label-collapse="<?php esc_attr_e( 'Свернуть', 'zhiraf-ufa' ); ?>"
					>
						<?php esc_html_e( 'Показать ещё', 'zhiraf-ufa' ); ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="section hub-offer-visa bg-milky">
		<div class="container">
			<h2 class="section-title"><?php esc_html_e( 'Куда реально улететь завтра (без визы)', 'zhiraf-ufa' ); ?></h2>
			<div class="hub-offer-country-grid">
				<?php foreach ( $visa as $card ) : ?>
					<?php
					$visa_name    = ! empty( $card['name'] ) ? (string) $card['name'] : '';
					$visa_tagline = ! empty( $card['tagline'] ) ? (string) $card['tagline'] : '';
					if ( '' === $visa_tagline && ! empty( $card['flight'] ) ) {
						$visa_tagline = trim( (string) $card['flight'] . ( ! empty( $card['visa'] ) ? ' · ' . (string) $card['visa'] : '' ) );
					}
					$visa_url     = ! empty( $card['url'] ) ? (string) $card['url'] : '';
					$visa_image   = ! empty( $card['image'] ) ? (string) $card['image'] : '';
					$visa_label   = $visa_name . ( '' !== $visa_tagline ? ': ' . $visa_tagline : '' );
					?>
					<a
						class="hub-offer-country-card rounded-3xl"
						href="<?php echo esc_url( $visa_url ); ?>"
						aria-label="<?php echo esc_attr( $visa_label ); ?>"
					>
						<div class="hub-offer-country-card__media">
							<?php zhiraf_ufa_img( $visa_image, $visa_name, 'hub-offer-country-card__img', true ); ?>
						</div>
						<div class="hub-offer-country-card__body">
							<h3 class="hub-offer-country-card__title"><?php echo esc_html( $visa_name ); ?></h3>
							<?php if ( '' !== $visa_tagline ) : ?>
								<p class="hub-offer-country-card__meta"><?php echo esc_html( $visa_tagline ); ?></p>
							<?php endif; ?>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section hub-offer-how bg-sand">
		<div class="container">
			<h2 class="section-title"><?php esc_html_e( 'Как работают горящие туры из Уфы', 'zhiraf-ufa' ); ?></h2>
			<div class="hub-offer-info-grid">
				<?php foreach ( $how as $card ) : ?>
					<div class="hub-offer-card rounded-3xl">
						<?php
						if ( function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
							echo zhiraf_ufa_lucide_icon( $card['icon'], 28, 'hub-offer-card__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
						<h3><?php echo esc_html( $card['title'] ); ?></h3>
						<p><?php echo esc_html( $card['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section hub-offer-faq">
		<div class="container container--narrow">
			<div class="hub-offer-card rounded-3xl hub-offer-card--faq">
				<h2 class="section-title"><?php esc_html_e( 'Частые вопросы', 'zhiraf-ufa' ); ?></h2>
				<div class="faq-home__list">
					<?php foreach ( $faq as $item ) : ?>
						<details class="faq-home__item">
							<summary class="faq-home__question"><?php echo esc_html( $item['q'] ); ?></summary>
							<div class="faq-home__answer"><p><?php echo esc_html( $item['a'] ); ?></p></div>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="section hub-offer-final bg-milky" id="hot-hub-cta">
		<div class="container container--narrow">
			<div class="hub-offer-card rounded-3xl">
				<h2><?php esc_html_e( 'Горящий или отказной тур в мессенджер', 'zhiraf-ufa' ); ?></h2>
				<?php
				if ( function_exists( 'zhiraf_ufa_render_hub_messenger_cta' ) ) {
					zhiraf_ufa_render_hub_messenger_cta(
						array(
							'hub'             => 'hot',
							'lead'            => __( 'Напишите менеджеру — подберём варианты с вылетом из Уфы, даты и бюджет уточним в чате.', 'zhiraf-ufa' ),
							'messenger_label' => __( 'Где вам удобнее написать:', 'zhiraf-ufa' ),
						)
					);
				}
				?>
			</div>
		</div>
	</section>
</main>