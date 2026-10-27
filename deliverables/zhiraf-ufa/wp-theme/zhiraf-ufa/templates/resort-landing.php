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
					<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?></a>
					·
					<a href="<?php echo esc_url( $country_url ); ?>"><?php echo esc_html( $resort_data['country_name'] ); ?></a>
				</p>
				<h1><?php echo esc_html( $resort_data['h1'] ); ?></h1>
				<p class="lead dest-landing-hero__lead"><?php echo esc_html( $resort_data['hero_lead'] ); ?></p>
				<?php if ( ! empty( $resort_data['hero_badges'] ) && is_array( $resort_data['hero_badges'] ) ) : ?>
					<ul class="dest-landing-hero__badges" aria-label="<?php esc_attr_e( 'Ключевые условия поездки', 'zhiraf-ufa' ); ?>">
						<?php foreach ( $resort_data['hero_badges'] as $badge ) : ?>
							<li class="dest-landing-hero__badge">
								<?php if ( ! empty( $badge['icon'] ) ) : ?>
									<span class="dest-landing-hero__badge-icon" aria-hidden="true"><?php echo esc_html( $badge['icon'] ); ?></span>
								<?php endif; ?>
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
			<form class="zhiraf-form dest-landing-lead-form" data-action="zhiraf_lead" data-form-context="resort-<?php echo esc_attr( $resort_lead_id ); ?>">
				<div class="dest-landing-lead-form__fields">
					<label class="form-label dest-landing-lead-form__field">
						<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Ваше имя', 'zhiraf-ufa' ); ?></span>
						<input type="text" name="name" placeholder="<?php esc_attr_e( 'Как к вам обращаться', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="name" />
					</label>
					<label class="form-label dest-landing-lead-form__field">
						<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span>
						<input type="tel" name="phone" placeholder="<?php esc_attr_e( '+7 …', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" inputmode="tel" />
					</label>
					<?php if ( ! empty( $resort_data['cta_message_placeholder'] ) ) : ?>
						<textarea name="message" class="form-input dest-landing-lead-form__message" rows="3" placeholder="<?php echo esc_attr( $resort_data['cta_message_placeholder'] ); ?>"></textarea>
					<?php endif; ?>
					<label class="form-label dest-landing-lead-form__channel-label">
						<span class="dest-landing-lead-form__channel-text"><?php esc_html_e( 'Куда отправить подборку', 'zhiraf-ufa' ); ?></span>
						<select name="contact_channel" class="form-input">
							<option value="<?php esc_attr_e( 'WhatsApp', 'zhiraf-ufa' ); ?>" selected><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Telegram', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Telegram', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Звонок', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Звонок', 'zhiraf-ufa' ); ?></option>
						</select>
					</label>
					<input type="hidden" name="tour" value="<?php echo esc_attr( $resort_data['quiz_prefill'] ); ?>" data-lead-tour />
					<?php if ( '' !== $resort_lead_id ) : ?>
						<input type="hidden" name="destination" value="<?php echo esc_attr( $resort_lead_id ); ?>" />
					<?php endif; ?>
					<input type="hidden" name="express_pick" value="" data-lead-express />
					<?php zhiraf_ufa_consent_fields( 'resort-lead-' . $resort_lead_id ); ?>
					<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Отправить заявку', 'zhiraf-ufa' ); ?></button>
				</div>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
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
