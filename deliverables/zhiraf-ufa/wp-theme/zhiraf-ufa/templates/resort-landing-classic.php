<?php
/**
 * Resort landing layout (/tour/{country}/{resort}/).
 *
 * @package Zhiraf_Ufa
 * @var array $resort_data From zhiraf_ufa_get_resort_landing().
 */

if ( ! defined( 'ABSPATH' ) || empty( $resort_data ) ) {
	return;
}
$country_url    = home_url( '/tour/' . $resort_data['country'] . '/' );
$resort_lead_id = zhiraf_ufa_resort_lead_destination( $resort_data );
$resort_ctx_slug = sanitize_title( (string) $resort_data['country'] ) . '/' . sanitize_title( (string) $resort_data['resort'] );
$resort_faq_items = function_exists( 'zhiraf_ufa_merge_faq_lists' )
	? zhiraf_ufa_merge_faq_lists(
		! empty( $resort_data['faq'] ) ? $resort_data['faq'] : array(),
		! empty( $resort_data['details_sections'] ) ? $resort_data['details_sections'] : array()
	)
	: ( ! empty( $resort_data['faq'] ) ? $resort_data['faq'] : array() );
?>
<main id="primary" class="site-main site-main--resort">
	<section class="dest-landing-hero">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<p class="dest-landing-hero__eyebrow">
					<a class="dest-landing-hero__eyebrow-link" href="<?php echo esc_url( home_url( '/tour/' ) ); ?>">
						<span class="dest-landing-hero__eyebrow-back" aria-hidden="true">← </span><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?>
					</a>
					·
					<a href="<?php echo esc_url( $country_url ); ?>"><?php echo esc_html( $resort_data['country_name'] ); ?></a>
				</p>
				<h1><?php echo esc_html( $resort_data['h1'] ); ?></h1>
				<p class="lead dest-landing-hero__lead"><?php echo esc_html( $resort_data['hero_lead'] ); ?></p>
				<?php if ( ! empty( $resort_data['hero_badges'] ) && is_array( $resort_data['hero_badges'] ) ) : ?>
					<ul class="dest-landing-hero__badges" aria-label="<?php esc_attr_e( 'Ключевые условия поездки', 'zhiraf-ufa' ); ?>">
						<?php foreach ( $resort_data['hero_badges'] as $badge ) : ?>
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
								<span><?php echo esc_html( $badge['text'] ?? '' ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<a class="btn btn--primary btn--lg" href="#dest-express-pick"><?php esc_html_e( 'Подбор за 10 секунд', 'zhiraf-ufa' ); ?></a>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $resort_data['image'], $resort_data['image_alt'], 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<?php
	if ( function_exists( 'zhiraf_ufa_render_landing_conversion_stack' ) ) {
		zhiraf_ufa_render_landing_conversion_stack( $resort_ctx_slug, $resort_data, 'resort' );
	}
	?>

	<?php
	$resort_hotels = function_exists( 'zhiraf_ufa_hotels_showcase_for_resorts' )
		? zhiraf_ufa_hotels_showcase_for_resorts()
		: array();
	if ( ! empty( $resort_hotels[ $resort_ctx_slug ] ) ) :
		$showcase = $resort_hotels[ $resort_ctx_slug ];
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
	?>

	<?php if ( ! empty( $resort_data['sections'] ) ) : ?>
		<section class="section dest-landing-prose bg-milky">
			<div class="container container--narrow">
				<details class="dest-prose-accordion dest-prose-accordion--seo">
					<summary class="dest-prose-accordion__summary"><?php esc_html_e( 'Подробнее о курорте', 'zhiraf-ufa' ); ?></summary>
					<div class="dest-prose-accordion__body">
						<?php if ( ! empty( $resort_data['geo_answer'] ) ) : ?>
							<p><?php echo esc_html( $resort_data['geo_answer'] ); ?></p>
						<?php endif; ?>
						<?php foreach ( $resort_data['sections'] as $section ) : ?>
							<?php if ( ! empty( $section['h2'] ) ) : ?>
								<h3><?php echo esc_html( $section['h2'] ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $section['paragraphs'] ) ) : ?>
								<?php foreach ( $section['paragraphs'] as $para ) : ?>
									<p><?php echo wp_kses_post( $para ); ?></p>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</details>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $resort_faq_items ) ) : ?>
	<section class="section dest-landing-faq">
		<div class="container container--narrow">
			<h2 class="section-title"><?php esc_html_e( 'Вопросы по курорту', 'zhiraf-ufa' ); ?></h2>
			<div class="faq-home__list">
				<?php foreach ( $resort_faq_items as $item ) : ?>
					<details class="faq-home__item">
						<summary class="faq-home__question"><?php echo esc_html( $item['q'] ); ?></summary>
						<div class="faq-home__answer">
							<p><?php echo esc_html( $item['a'] ); ?></p>
							<?php if ( ! empty( $item['link'] ) ) : ?>
								<p><a href="<?php echo esc_url( $item['link'] ); ?>"><?php esc_html_e( 'Подробнее', 'zhiraf-ufa' ); ?></a></p>
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
			<p><?php echo esc_html( ! empty( $resort_data['cta_lead'] ) ? $resort_data['cta_lead'] : __( 'Оставьте контакты — перезвоним в рабочее время и предложим 2–4 варианта под ваш бюджет.', 'zhiraf-ufa' ) ); ?></p>
			<div class="unified-lead-form-panel">
				<?php
				if ( function_exists( 'zhiraf_ufa_render_unified_lead_form' ) ) {
					zhiraf_ufa_render_unified_lead_form(
						array(
							'form_context'        => 'resort-' . $resort_lead_id,
							'consent_id'          => 'resort-lead-' . $resort_lead_id,
							'messenger_variant'   => 'quiz',
							'message'             => ! empty( $resort_data['cta_message_placeholder'] ) ? (string) $resort_data['cta_message_placeholder'] : '',
							'message_placeholder' => __( 'Даты, состав, бюджет', 'zhiraf-ufa' ),
							'tour'                => $resort_data['quiz_prefill'],
							'destination'         => $resort_lead_id,
							'lead_tour_attr'      => true,
							'express_pick'        => '',
						)
					);
				}
				?>
			</div>
			<p class="dest-landing-cta__alt">
				<button type="button" class="btn btn--outline btn--sm" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $resort_data['quiz_prefill'] ); ?>">
					<?php esc_html_e( 'Или пройти квиз', 'zhiraf-ufa' ); ?>
				</button>
			</p>
			<p class="dest-landing-cta__meta">
				<a href="<?php echo esc_url( $country_url ); ?>"><?php echo esc_html( $resort_data['country_name'] ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты', 'zhiraf-ufa' ); ?></a>
				·
				<a href="tel:+79018105018" data-track="phone"><?php esc_html_e( '+7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
			</p>
			<?php
			$peer_links = array();
			if ( ! empty( $resort_data['final_cta']['peer_links'] ) && is_array( $resort_data['final_cta']['peer_links'] ) ) {
				$peer_links = $resort_data['final_cta']['peer_links'];
			}
			if ( $peer_links ) :
				?>
				<div class="resort-v2-peer-links dest-landing-cta__peers">
					<?php foreach ( $peer_links as $link ) : ?>
						<a class="resort-v2-peer-chip" href="<?php echo esc_url( home_url( $link['url'] ?? '/' ) ); ?>"><?php echo esc_html( $link['label'] ?? '' ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_template_part(
	'template-parts/destination/sticky-mobile-bar',
	null,
	array(
		'wa_text'      => sprintf( __( 'Здравствуйте! Нужен подбор: %s', 'zhiraf-ufa' ), $resort_data['quiz_prefill'] ?? '' ),
		'quiz_prefill' => ! empty( $resort_data['quiz_prefill'] ) ? $resort_data['quiz_prefill'] : '',
	)
);
?>
