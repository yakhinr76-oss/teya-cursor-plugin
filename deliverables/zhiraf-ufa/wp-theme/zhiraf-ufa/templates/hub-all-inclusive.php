<?php
/**
 * Hub: all inclusive (/vse-vklyucheno/).
 *
 * @package Zhiraf_Ufa
 * @var array $offer
 */

if ( ! defined( 'ABSPATH' ) || empty( $offer ) ) {
	return;
}

$ai_tours = function_exists( 'zhiraf_ufa_get_all_inclusive_showcase_tours' )
	? zhiraf_ufa_get_all_inclusive_showcase_tours( 24 )
	: array();
$hub_ai_initial_visible = 9;
$dest_cards             = zhiraf_ufa_ai_hub_destination_cards();
$tabs       = zhiraf_ufa_ai_hub_catalog_tabs();
$columns    = zhiraf_ufa_ai_hub_included_columns();
$faq        = zhiraf_ufa_ai_hub_faq();
$badges     = array(
	array(
		'icon_svg' => 'hotel',
		'text'     => __( 'Турция · Египет · ОАЭ', 'zhiraf-ufa' ),
	),
	array(
		'icon_svg' => 'shield-check',
		'text'     => __( 'AI и Ultra AI в договоре', 'zhiraf-ufa' ),
	),
	array(
		'icon_svg' => 'plane',
		'text'     => __( 'Вылет из Уфы', 'zhiraf-ufa' ),
	),
);
?>
<main id="primary" class="site-main site-main--offer site-main--hub-ai">
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
						data-lead-title="<?php esc_attr_e( 'Подбор отеля Всё включено', 'zhiraf-ufa' ); ?>"
						data-lead-tour="<?php echo esc_attr( $offer['quiz_prefill'] ?? '' ); ?>"
					>
						<?php esc_html_e( 'Подобрать отель Всё включено', 'zhiraf-ufa' ); ?>
					</button>
				</div>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $offer['image'], $offer['image_alt'], 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<section class="section hub-offer-destinations bg-sand">
		<div class="container">
			<h2 class="section-title"><?php esc_html_e( 'Где лучше выбирать All Inclusive', 'zhiraf-ufa' ); ?></h2>
			<div class="hub-offer-expert-grid">
				<?php foreach ( $dest_cards as $card ) : ?>
					<div class="hub-offer-card rounded-3xl hub-offer-card--expert">
						<div class="hub-offer-card__thumb-wrap">
							<?php zhiraf_ufa_img( $card['image'], $card['title'], 'hub-offer-card__thumb', true ); ?>
						</div>
						<h3 class="hub-offer-card__title">
							<a href="<?php echo esc_url( $card['url'] ); ?>"><?php echo esc_html( $card['title'] ); ?></a>
						</h3>
						<p class="hub-offer-card__text"><?php echo esc_html( $card['text'] ); ?></p>
						<div class="hub-offer-card__actions">
							<?php if ( ! empty( $card['url2'] ) ) : ?>
								<a class="btn btn--ghost btn--sm rounded-xl" href="<?php echo esc_url( $card['url'] ); ?>"><?php esc_html_e( 'Вьетнам', 'zhiraf-ufa' ); ?></a>
								<a class="btn btn--ghost btn--sm rounded-xl" href="<?php echo esc_url( $card['url2'] ); ?>"><?php esc_html_e( 'Китай', 'zhiraf-ufa' ); ?></a>
							<?php else : ?>
								<a class="btn btn--ghost btn--sm rounded-xl" href="<?php echo esc_url( $card['url'] ); ?>"><?php esc_html_e( 'Страница направления', 'zhiraf-ufa' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section hub-offer-catalog bg-milky" id="ai-hub-catalog" data-hub-offer-root data-hub-offer="ai">
		<div class="container">
			<div class="section-head">
				<h2 class="section-title"><?php esc_html_e( 'Каталог отелей All Inclusive', 'zhiraf-ufa' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Питание AI / UAI, вылет из Уфы. Код питания указан на карточке.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="hub-offer-tabs" role="tablist">
				<?php foreach ( $tabs as $i => $tab ) : ?>
					<button
						type="button"
						class="hub-offer-tabs__btn<?php echo 0 === $i ? ' is-active' : ''; ?>"
						data-hub-ai-filter="<?php echo esc_attr( $tab['filter'] ); ?>"
						role="tab"
					>
						<?php echo esc_html( $tab['label'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>
			<?php
			$hub_ai_reserve_images = array();
			if ( ! empty( $offer['image'] ) ) {
				$hub_ai_reserve_images[] = (string) $offer['image'];
			}
			foreach ( $dest_cards as $dest_card ) {
				if ( ! empty( $dest_card['image'] ) ) {
					$hub_ai_reserve_images[] = (string) $dest_card['image'];
				}
			}
			if ( function_exists( 'zhiraf_ufa_tour_image_hub_context' ) ) {
				zhiraf_ufa_tour_image_hub_context( $hub_ai_reserve_images, true );
			}
			?>
			<div
				class="tour-price-grid tour-price-grid--hub"
				data-hub-tour-grid
				data-hub-initial-visible="<?php echo (int) $hub_ai_initial_visible; ?>"
			>
				<?php foreach ( $ai_tours as $hub_tour_index => $tour_item ) : ?>
					<div
						class="hub-tour-wrap<?php echo $hub_tour_index >= $hub_ai_initial_visible ? ' is-hub-collapsed' : ''; ?>"
						data-hub-filters="<?php echo esc_attr( zhiraf_ufa_ai_hub_tour_filter_tokens( $tour_item ) ); ?>"
					>
						<?php
						get_template_part(
							'template-parts/cards/card-tour-price',
							null,
							array(
								'tour'            => $tour_item,
								'card_index'      => $hub_tour_index,
								'cta_class'       => 'btn btn--ghost tour-card__cta',
								'hub_calm_badges' => true,
							)
						);
						?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( count( $ai_tours ) > $hub_ai_initial_visible ) : ?>
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
			<?php
			if ( function_exists( 'zhiraf_ufa_tour_image_hub_context_clear' ) ) {
				zhiraf_ufa_tour_image_hub_context_clear();
			}
			?>
		</div>
	</section>

	<section class="section hub-offer-included bg-sand">
		<div class="container container--narrow">
			<div class="hub-offer-card rounded-3xl hub-offer-card--included">
				<h2 class="section-title"><?php esc_html_e( 'Что входит в All Inclusive, а за что доплачивают', 'zhiraf-ufa' ); ?></h2>
				<div class="hub-offer-included-grid">
					<?php foreach ( $columns as $col ) : ?>
						<div class="hub-offer-included-col">
							<?php
							if ( function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
								echo zhiraf_ufa_lucide_icon( $col['icon'], 26, 'hub-offer-card__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
							<h3><?php echo esc_html( $col['title'] ); ?></h3>
							<p><?php echo esc_html( $col['text'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
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

	<section class="section hub-offer-final bg-milky" id="ai-hub-cta">
		<div class="container container--narrow">
			<div class="hub-offer-card rounded-3xl">
				<h2><?php esc_html_e( 'Подберём отель All Inclusive под ваш бюджет', 'zhiraf-ufa' ); ?></h2>
				<?php
				if ( function_exists( 'zhiraf_ufa_render_messenger_picker' ) ) {
					zhiraf_ufa_render_messenger_picker(
						array(
							'context'      => 'ai-hub-final',
							'quiz_prefill' => $offer['quiz_prefill'] ?? '',
						)
					);
				}
				?>
				<nav class="hub-offer-chip-links" aria-label="<?php esc_attr_e( 'Популярные направления', 'zhiraf-ufa' ); ?>">
					<a class="hub-offer-chip" href="<?php echo esc_url( home_url( '/tour/turciya/' ) ); ?>"><?php esc_html_e( 'Турция', 'zhiraf-ufa' ); ?></a>
					<a class="hub-offer-chip" href="<?php echo esc_url( home_url( '/tour/egipet/' ) ); ?>"><?php esc_html_e( 'Египет', 'zhiraf-ufa' ); ?></a>
					<a class="hub-offer-chip" href="<?php echo esc_url( home_url( '/tour/oae/' ) ); ?>"><?php esc_html_e( 'ОАЭ', 'zhiraf-ufa' ); ?></a>
				</nav>
			</div>
		</div>
	</section>
</main>