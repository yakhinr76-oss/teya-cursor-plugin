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
$country_url  = home_url( '/tour/' . $country_slug . '/' );
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
					<a href="<?php echo esc_url( $country_url ); ?>"><?php echo esc_html( $country_name ); ?></a>
					<span aria-hidden="true">→</span>
					<span aria-current="page"><?php echo esc_html( $resort_name ); ?></span>
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
			<div class="resort-v2-districts__grid">
				<?php foreach ( $districts['cards'] as $card ) : ?>
					<article class="resort-v2-district-card">
						<h3 class="resort-v2-district-card__title"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
						<?php if ( ! empty( $card['rows'] ) && is_array( $card['rows'] ) ) : ?>
							<dl class="resort-v2-district-card__rows">
								<?php foreach ( $card['rows'] as $row ) : ?>
									<div class="resort-v2-district-card__row">
										<dt><?php echo esc_html( $row['label'] ?? '' ); ?></dt>
										<dd><?php echo esc_html( $row['text'] ?? '' ); ?></dd>
									</div>
								<?php endforeach; ?>
							</dl>
						<?php endif; ?>
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
						<h3 class="resort-v2-season-card__title"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
						<?php if ( ! empty( $card['subtitle'] ) ) : ?>
							<p class="resort-v2-season-card__sub"><?php echo esc_html( $card['subtitle'] ); ?></p>
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
			<div class="resort-v2-local__grid">
				<div class="resort-v2-local__col">
					<h3 class="resort-v2-local__h3"><?php echo esc_html( $local['landmarks_title'] ?? '' ); ?></h3>
					<ul class="resort-v2-local__list">
						<?php foreach ( (array) ( $local['landmarks'] ?? array() ) as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="resort-v2-local__col">
					<h3 class="resort-v2-local__h3"><?php echo esc_html( $local['tips_title'] ?? '' ); ?></h3>
					<ul class="resort-v2-local__list">
						<?php foreach ( (array) ( $local['tips'] ?? array() ) as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
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

	<section class="section resort-v2-final-cta" id="dest-lead">
		<div class="container container--narrow resort-v2-final-cta__inner">
			<h2 class="section-title"><?php echo esc_html( $final['title'] ?? '' ); ?></h2>
			<form class="zhiraf-form dest-landing-lead-form resort-v2-final-cta__form" data-action="zhiraf_lead" data-form-context="resort-<?php echo esc_attr( $country_slug . '-' . $resort_slug ); ?>">
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
				<textarea name="message" class="form-input dest-landing-lead-form__message" rows="3" placeholder="<?php echo esc_attr( $final['message_placeholder'] ?? '' ); ?>"><?php echo esc_textarea( $hero['modal_message'] ?? '' ); ?></textarea>
				<input type="hidden" name="tour" value="<?php echo esc_attr( $quiz ); ?>" data-lead-tour />
				<input type="hidden" name="destination" value="<?php echo esc_attr( $country_slug . '/' . $resort_slug ); ?>" />
				<?php zhiraf_ufa_consent_fields( 'resort-v2-' . $resort_slug ); ?>
				<button type="submit" class="btn btn--primary btn--lg btn--block rounded-xl" data-messenger-submit-label><?php esc_html_e( 'Отправить →', 'zhiraf-ufa' ); ?></button>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
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