<?php
/**
 * Resort landing v2 — JSON-driven (/tour/{country}/{resort}/).
 *
 * @package Zhiraf_Ufa
 * @var array $resort_data
 */

if ( ! defined( 'ABSPATH' ) || empty( $resort_data ) ) {
	return;
}

$country_slug = sanitize_title( (string) ( $resort_data['country'] ?? '' ) );
$resort_slug  = sanitize_title( (string) ( $resort_data['resort'] ?? '' ) );
$hub_at_country = ! empty( $resort_data['hub_at_country'] );
$country_url    = ! empty( $resort_data['public_url'] ) ? (string) $resort_data['public_url'] : home_url( '/tour/' . $country_slug . '/' );
$tour_hub_url   = home_url( '/tour/' );
$hero         = ! empty( $resort_data['hero'] ) && is_array( $resort_data['hero'] ) ? $resort_data['hero'] : array();
$districts    = ! empty( $resort_data['districts'] ) && is_array( $resort_data['districts'] ) ? $resort_data['districts'] : array();
$seasons      = ! empty( $resort_data['seasons'] ) && is_array( $resort_data['seasons'] ) ? $resort_data['seasons'] : array();
$local        = ! empty( $resort_data['local_guide'] ) && is_array( $resort_data['local_guide'] ) ? $resort_data['local_guide'] : array();
$final        = ! empty( $resort_data['final_cta'] ) && is_array( $resort_data['final_cta'] ) ? $resort_data['final_cta'] : array();
$faq          = ! empty( $resort_data['faq'] ) && is_array( $resort_data['faq'] ) ? $resort_data['faq'] : array();
$resort_name  = ! empty( $resort_data['resort_name'] ) ? (string) $resort_data['resort_name'] : $resort_slug;
$country_name = ! empty( $resort_data['country_name'] ) ? (string) $resort_data['country_name'] : $country_slug;
$quiz         = ! empty( $resort_data['quiz_prefill'] ) ? (string) $resort_data['quiz_prefill'] : $resort_name;
$image        = ! empty( $resort_data['image'] ) ? (string) $resort_data['image'] : 'dest-uae.jpg';
$image_alt    = ! empty( $resort_data['image_alt'] ) ? (string) $resort_data['image_alt'] : $resort_name;
?>
<main id="primary" class="site-main site-main--resort site-main--resort-v2">
	<section class="section dest-landing-hero resort-v2-hero">
		<div class="container dest-landing-hero__grid">
			<div class="dest-landing-hero__copy">
				<nav class="resort-v2-breadcrumb" aria-label="<?php esc_attr_e( 'Хлебные крошки', 'zhiraf-ufa' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Главная', 'zhiraf-ufa' ); ?></a>
					<span aria-hidden="true">→</span>
					<?php if ( $hub_at_country ) : ?>
						<a href="<?php echo esc_url( $tour_hub_url ); ?>"><?php esc_html_e( 'Направления', 'zhiraf-ufa' ); ?></a>
						<span aria-hidden="true">→</span>
						<span aria-current="page"><?php echo esc_html( ! empty( $resort_data['hub_nav_label'] ) ? (string) $resort_data['hub_nav_label'] : sprintf( '%s (%s)', $country_name, $resort_name ) ); ?></span>
					<?php else : ?>
						<a href="<?php echo esc_url( $country_url ); ?>"><?php echo esc_html( $country_name ); ?></a>
						<span aria-hidden="true">→</span>
						<span aria-current="page"><?php echo esc_html( $resort_name ); ?></span>
					<?php endif; ?>
				</nav>
				<h1><?php echo esc_html( $resort_data['h1'] ?? '' ); ?></h1>
				<?php if ( ! empty( $hero['lead'] ) ) : ?>
					<p class="lead dest-landing-hero__lead"><?php echo esc_html( $hero['lead'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $hero['badges'] ) && is_array( $hero['badges'] ) ) : ?>
					<ul class="dest-landing-hero__badges" aria-label="<?php esc_attr_e( 'Ключевые условия поездки', 'zhiraf-ufa' ); ?>">
						<?php foreach ( $hero['badges'] as $badge ) : ?>
							<li class="dest-landing-hero__badge">
								<?php
								if ( function_exists( 'zhiraf_ufa_render_hero_badge_icon' ) ) {
									$icon_markup = zhiraf_ufa_render_hero_badge_icon( $badge );
									if ( '' !== $icon_markup ) {
										echo $icon_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}
								}
								?>
								<span><?php echo esc_html( $badge['text'] ?? '' ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<div class="dest-landing-hero__actions">
					<button
						type="button"
						class="btn btn--primary btn--lg rounded-xl"
						data-turciya-lead-open
						data-lead-title="<?php echo esc_attr( $hero['modal_title'] ?? __( 'Подбор отеля', 'zhiraf-ufa' ) ); ?>"
						data-lead-subtitle="<?php echo esc_attr( $hero['modal_lead'] ?? '' ); ?>"
						data-lead-message="<?php echo esc_attr( $hero['modal_message'] ?? '' ); ?>"
						data-lead-tour="<?php echo esc_attr( $quiz ); ?>"
					>
						<?php echo esc_html( $hero['cta_label'] ?? __( 'Подобрать отель', 'zhiraf-ufa' ) ); ?>
					</button>
				</div>
				<?php if ( ! empty( $hero['trust_lines'] ) && is_array( $hero['trust_lines'] ) ) : ?>
					<ul class="resort-v2-trust">
						<?php foreach ( $hero['trust_lines'] as $line ) : ?>
							<li class="resort-v2-trust__item">
								<?php
								if ( function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
									echo zhiraf_ufa_lucide_icon( 'shield-check', 18, 'resort-v2-trust__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
								<span><?php echo esc_html( $line ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $image, $image_alt, 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $districts['cards'] ) ) : ?>
	<section class="section resort-v2-districts bg-milky">
		<div class="container">
			<h2 class="section-title"><?php echo esc_html( $districts['title'] ?? '' ); ?></h2>
			<?php
			$districts_grid_class = 'resort-v2-districts__grid';
			if ( ! empty( $districts['grid_columns'] ) && 3 === (int) $districts['grid_columns'] ) {
				$districts_grid_class .= ' resort-v2-districts__grid--3';
			}
			?>
			<div class="<?php echo esc_attr( $districts_grid_class ); ?>">
				<?php foreach ( $districts['cards'] as $card ) : ?>
					<?php
					$district_title = ! empty( $card['title'] ) ? (string) $card['title'] : '';
					$district_tag   = ! empty( $card['tag'] ) ? (string) $card['tag'] : '';
					$district_photo = ! empty( $card['photo'] ) ? (string) $card['photo'] : '';
					$district_alt   = ! empty( $card['photo_alt'] ) ? (string) $card['photo_alt'] : $district_title;
					$badge_label    = '' !== $district_tag ? $district_tag : $district_title;
					$has_photo      = '' !== $district_photo;
					?>
					<article class="resort-v2-district-card<?php echo $has_photo ? ' resort-v2-district-card--photo' : ''; ?>">
						<?php if ( $has_photo ) : ?>
							<div class="resort-v2-district-card__media">
								<?php
								if ( function_exists( 'zhiraf_ufa_resort_content_img' ) ) {
									zhiraf_ufa_resort_content_img( $district_photo, $district_alt, 'resort-v2-district-card__img' );
								}
								?>
								<?php if ( '' !== $badge_label ) : ?>
									<span class="resort-v2-district-card__badge"><?php echo esc_html( $badge_label ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="resort-v2-district-card__body">
							<?php if ( '' !== $district_title && ( ! $has_photo || '' !== $district_tag ) ) : ?>
								<h3 class="resort-v2-district-card__title"><?php echo esc_html( $district_title ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $card['rows'] ) && is_array( $card['rows'] ) ) : ?>
								<dl class="resort-v2-district-card__rows">
									<?php foreach ( $card['rows'] as $row ) : ?>
										<?php
										$row_label = ! empty( $row['label'] ) ? (string) $row['label'] : '';
										$is_nuance = ( '' !== $row_label && false !== mb_stripos( $row_label, 'нюанс', 0, 'UTF-8' ) );
										$row_class = 'resort-v2-district-card__row' . ( $is_nuance ? ' resort-v2-district-card__row--nuance' : '' );
										?>
										<div class="<?php echo esc_attr( $row_class ); ?>">
											<dt class="<?php echo $is_nuance ? 'resort-v2-district-card__dt--nuance' : 'resort-v2-district-card__dt--meta'; ?>">
												<?php if ( $is_nuance && function_exists( 'zhiraf_ufa_lucide_icon' ) ) : ?>
													<?php echo zhiraf_ufa_lucide_icon( 'info', 14, 'resort-v2-district-card__nuance-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
												<?php endif; ?>
												<span><?php echo esc_html( $row_label ); ?></span>
											</dt>
											<dd><?php echo esc_html( $row['text'] ?? '' ); ?></dd>
										</div>
									<?php endforeach; ?>
								</dl>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/resort/resort-catalog', null, array( 'resort_data' => $resort_data ) ); ?>

	<?php if ( ! empty( $seasons['cards'] ) ) : ?>
	<section class="section resort-v2-seasons">
		<div class="container">
			<h2 class="section-title"><?php echo esc_html( $seasons['title'] ?? '' ); ?></h2>
			<div class="resort-v2-seasons__grid">
				<?php foreach ( $seasons['cards'] as $card ) : ?>
					<article class="resort-v2-season-card">
						<?php if ( ! empty( $card['icon'] ) && function_exists( 'zhiraf_ufa_lucide_icon' ) ) : ?>
							<div class="resort-v2-season-card__icon" aria-hidden="true">
								<?php echo zhiraf_ufa_lucide_icon( sanitize_key( (string) $card['icon'] ), 22, 'resort-v2-season-card__icon-svg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>
						<h3 class="resort-v2-season-card__title"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
						<?php if ( ! empty( $card['subtitle'] ) ) : ?>
							<p class="resort-v2-season-card__sub"><?php echo esc_html( $card['subtitle'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $card['pills'] ) && is_array( $card['pills'] ) ) : ?>
							<ul class="resort-v2-season-card__pills" aria-label="<?php esc_attr_e( 'Температура', 'zhiraf-ufa' ); ?>">
								<?php foreach ( $card['pills'] as $pill ) : ?>
									<li class="resort-v2-season-card__pill"><?php echo esc_html( (string) $pill ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<p class="resort-v2-season-card__text"><?php echo esc_html( $card['text'] ?? '' ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $local ) : ?>
	<section class="section resort-v2-local bg-milky">
		<div class="container">
			<h2 class="section-title"><?php echo esc_html( $local['title'] ?? '' ); ?></h2>
			<div class="resort-v2-local__stack">
				<?php if ( ! empty( $local['landmarks'] ) && is_array( $local['landmarks'] ) ) : ?>
					<div class="resort-v2-local__landmarks">
						<h3 class="resort-v2-local__h3"><?php echo esc_html( $local['landmarks_title'] ?? '' ); ?></h3>
						<div class="resort-v2-landmarks__grid">
							<?php foreach ( $local['landmarks'] as $raw_landmark ) : ?>
								<?php
								$landmark = function_exists( 'zhiraf_ufa_resort_landmark_row' )
									? zhiraf_ufa_resort_landmark_row( $raw_landmark )
									: array( 'title' => '', 'text' => (string) $raw_landmark, 'photo' => '', 'photo_alt' => '' );
								$lm_title = $landmark['title'];
								$lm_text  = $landmark['text'];
								if ( '' === $lm_title && '' === $lm_text ) {
									continue;
								}
								if ( '' === $lm_title && '' !== $lm_text ) {
									$lm_title = mb_substr( $lm_text, 0, 48, 'UTF-8' );
								}
								?>
								<article class="resort-v2-landmark-card">
									<?php if ( ! empty( $landmark['photo'] ) && function_exists( 'zhiraf_ufa_resort_content_img' ) ) : ?>
										<div class="resort-v2-landmark-card__media">
											<?php zhiraf_ufa_resort_content_img( $landmark['photo'], $landmark['photo_alt'], 'resort-v2-landmark-card__img' ); ?>
										</div>
									<?php endif; ?>
									<div class="resort-v2-landmark-card__body">
										<?php if ( '' !== $lm_title ) : ?>
											<h4 class="resort-v2-landmark-card__title"><?php echo esc_html( $lm_title ); ?></h4>
										<?php endif; ?>
										<?php if ( '' !== $lm_text ) : ?>
											<p class="resort-v2-landmark-card__text"><?php echo esc_html( $lm_text ); ?></p>
										<?php endif; ?>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $local['tips'] ) && is_array( $local['tips'] ) ) : ?>
					<div class="resort-v2-local__tips">
						<h3 class="resort-v2-local__h3"><?php echo esc_html( $local['tips_title'] ?? '' ); ?></h3>
						<div class="resort-v2-tips-panel">
							<div class="resort-v2-tips-panel__grid">
								<?php
								$tip_icons = array( 'receipt', 'credit-card', 'shopping-bag' );
								$tip_i     = 0;
								foreach ( $local['tips'] as $raw_tip ) :
									$fallback_icon = $tip_icons[ min( $tip_i, count( $tip_icons ) - 1 ) ];
									$tip           = function_exists( 'zhiraf_ufa_resort_tip_row' )
										? zhiraf_ufa_resort_tip_row( $raw_tip, $fallback_icon )
										: array( 'icon' => $fallback_icon, 'title' => '', 'text' => (string) $raw_tip );
									++$tip_i;
									?>
									<div class="resort-v2-tips-panel__col">
										<?php
										if ( function_exists( 'zhiraf_ufa_lucide_icon' ) ) {
											echo zhiraf_ufa_lucide_icon( $tip['icon'], 22, 'resort-v2-tips-panel__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
										?>
										<?php if ( '' !== $tip['title'] ) : ?>
											<p class="resort-v2-tips-panel__title"><?php echo esc_html( $tip['title'] ); ?></p>
										<?php endif; ?>
										<p class="resort-v2-tips-panel__text"><?php echo esc_html( $tip['text'] ); ?></p>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $faq ) : ?>
	<section class="section resort-v2-faq">
		<div class="container container--narrow">
			<div class="resort-v2-faq__panel">
				<h2 class="section-title"><?php esc_html_e( 'Частые вопросы', 'zhiraf-ufa' ); ?></h2>
				<div class="faq-home__list faq-home__list--panel">
					<?php foreach ( $faq as $item ) : ?>
						<details class="faq-home__item">
							<summary class="faq-home__question"><?php echo esc_html( $item['q'] ?? '' ); ?></summary>
							<div class="faq-home__answer"><p><?php echo esc_html( $item['a'] ?? '' ); ?></p></div>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section class="section resort-v2-final-cta<?php echo ! empty( $final['panel'] ) ? ' resort-v2-final-cta--panel' : ''; ?>" id="dest-lead">
		<div class="container container--narrow resort-v2-final-cta__inner<?php echo ! empty( $final['panel'] ) ? ' resort-v2-final-cta__panel' : ''; ?>">
			<h2 class="section-title"><?php echo esc_html( $final['title'] ?? '' ); ?></h2>
			<div class="unified-lead-form-panel">
				<?php
				if ( function_exists( 'zhiraf_ufa_render_unified_lead_form' ) ) {
					zhiraf_ufa_render_unified_lead_form(
						array(
							'form_class'          => 'resort-v2-final-cta__form',
							'form_context'        => 'resort-' . $country_slug . '-' . $resort_slug,
							'consent_id'          => 'resort-v2-' . $resort_slug,
							'messenger_variant'   => 'quiz',
							'message'             => $hero['modal_message'] ?? '',
							'message_placeholder' => $final['message_placeholder'] ?? __( 'Даты, состав, бюджет', 'zhiraf-ufa' ),
							'tour'                => $quiz,
							'destination'         => $country_slug . '/' . $resort_slug,
							'lead_tour_attr'      => true,
						)
					);
				}
				?>
			</div>
			<?php if ( ! empty( $final['peer_links'] ) ) : ?>
				<div class="resort-v2-peer-links">
					<?php foreach ( $final['peer_links'] as $link ) : ?>
						<a class="resort-v2-peer-chip" href="<?php echo esc_url( home_url( $link['url'] ?? '/' ) ); ?>"><?php echo esc_html( $link['label'] ?? '' ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_template_part(
	'template-parts/destination/turciya-lead-modal',
	null,
	array(
		'dest_slug'     => $country_slug,
		'modal_title'   => $hero['modal_title'] ?? '',
		'modal_lead'    => $hero['modal_lead'] ?? '',
		'modal_message' => $hero['modal_message'] ?? '',
		'modal_tour'    => $quiz,
	)
);
get_template_part(
	'template-parts/destination/sticky-mobile-bar',
	null,
	array(
		'wa_text'      => sprintf( __( 'Здравствуйте! Нужен подбор: %s', 'zhiraf-ufa' ), $quiz ),
		'quiz_prefill' => $quiz,
	)
);