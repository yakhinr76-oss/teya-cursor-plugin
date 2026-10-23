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
				<p class="lead"><?php echo esc_html( $resort_data['hero_lead'] ); ?></p>
				<button type="button" class="btn btn--primary btn--lg" data-quiz-open data-quiz-prefill="<?php echo esc_attr( $resort_data['quiz_prefill'] ); ?>">
					<?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?>
				</button>
			</div>
			<div class="dest-landing-hero__visual">
				<?php zhiraf_ufa_img( $resort_data['image'], $resort_data['image_alt'], 'dest-landing-hero__img', true ); ?>
			</div>
		</div>
	</section>

	<?php
	$zhiraf_cards = function_exists( 'zhiraf_ufa_get_resort_top_tours' )
		? zhiraf_ufa_get_resort_top_tours( $resort_data, 3 )
		: ( ! empty( $resort_data['tours_rx'] ) ? zhiraf_ufa_get_country_top_tours( $resort_data['country'], 3, $resort_data['tours_rx'] ) : array() );
	?>
	<?php if ( ! empty( $zhiraf_cards ) ) : ?>
		<section class="section section--dest-tours section--dest-tours-top bg-sand" id="dest-tours">
			<div class="container">
				<div class="section-head reveal">
					<div>
						<h2 class="section-title"><?php echo esc_html( __( 'Самые выгодные отели курорта', 'zhiraf-ufa' ) ); ?></h2>
						<p class="lead" style="margin-top:8px;margin-bottom:0;color:var(--color-muted);"><?php echo esc_html( __( 'Цены туроператора Библио-Глобус для 2 взрослых: перелёт и отель, 6–10 ночей (если таких нет — от 5 ночей). Обновление каждые 6 часов.', 'zhiraf-ufa' ) ); ?></p>
					</div>
				</div>
				<div class="tour-price-grid tour-price-grid--dest tour-price-grid--top">
					<?php foreach ( $zhiraf_cards as $tour_item ) : ?>
						<?php get_template_part( 'template-parts/cards/card-tour-price', null, array( 'tour' => $tour_item ) ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $resort_data['geo_answer'] ) ) : ?>
		<section class="section geo-answer-block bg-milky">
			<div class="container container--narrow">
				<p class="geo-answer-block__text"><?php echo esc_html( $resort_data['geo_answer'] ); ?></p>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$show_battle = ( ! empty( $resort_data['landing_module'] ) && 'battle' === $resort_data['landing_module'] )
		|| ! empty( $resort_data['resort_compare'] );
	if ( $show_battle && ! empty( $resort_data['resort_compare'] ) && count( $resort_data['resort_compare'] ) >= 2 ) :
		get_template_part(
			'template-parts/destination/module-resort-battle',
			null,
			array(
				'title'   => ! empty( $resort_data['resort_compare_title'] ) ? (string) $resort_data['resort_compare_title'] : '',
				'lead'    => ! empty( $resort_data['resort_compare_lead'] ) ? (string) $resort_data['resort_compare_lead'] : '',
				'columns' => $resort_data['resort_compare'],
			)
		);
	endif;
	?>

	<?php if ( ! empty( $resort_data['details_sections'] ) ) : ?>
		<section class="section dest-landing-faq dest-landing-faq--compact">
			<div class="container container--narrow">
				<div class="faq-home__list">
					<?php foreach ( $resort_data['details_sections'] as $item ) : ?>
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
	<?php endif; ?>

	<?php if ( ! empty( $resort_data['sections'] ) ) : ?>
		<section class="section dest-landing-prose">
			<div class="container container--narrow">
				<?php foreach ( $resort_data['sections'] as $section ) : ?>
					<?php if ( ! empty( $section['h2'] ) ) : ?>
						<h2><?php echo esc_html( $section['h2'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['paragraphs'] ) ) : ?>
						<?php foreach ( $section['paragraphs'] as $para ) : ?>
							<p><?php echo wp_kses_post( $para ); ?></p>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="section dest-landing-faq">
		<div class="container container--narrow">
			<h2 class="section-title"><?php esc_html_e( 'Вопросы по курорту', 'zhiraf-ufa' ); ?></h2>
			<div class="faq-home__list">
				<?php foreach ( $resort_data['faq'] as $item ) : ?>
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
					<input type="hidden" name="tour" value="<?php echo esc_attr( $resort_data['quiz_prefill'] ); ?>" />
					<?php if ( '' !== $resort_lead_id ) : ?>
						<input type="hidden" name="destination" value="<?php echo esc_attr( $resort_lead_id ); ?>" />
					<?php endif; ?>
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
