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

$ai_tours   = function_exists( 'zhiraf_ufa_get_all_inclusive_showcase_tours' ) ? zhiraf_ufa_get_all_inclusive_showcase_tours( 9 ) : array();
$min_price  = function_exists( 'zhiraf_ufa_all_inclusive_min_price' ) ? zhiraf_ufa_all_inclusive_min_price() : null;
$ai_tabs    = function_exists( 'zhiraf_ufa_all_inclusive_destination_tabs' ) ? zhiraf_ufa_all_inclusive_destination_tabs() : array();
$dest_stub  = array(
	'slug'         => 'vse-vklyucheno',
	'name'         => __( 'All Inclusive', 'zhiraf-ufa' ),
	'quiz_prefill' => ! empty( $offer['quiz_prefill'] ) ? $offer['quiz_prefill'] : '',
	'vibe_section' => array(
		'title'    => __( 'Какой формат «всё включено» вам ближе?', 'zhiraf-ufa' ),
		'subtitle' => __( 'Выберите сценарий — подберём отели с AI/UAI под ваш состав', 'zhiraf-ufa' ),
	),
);
$vibe_section = function_exists( 'zhiraf_ufa_get_vibe_section' ) ? zhiraf_ufa_get_vibe_section( 'turciya', $dest_stub ) : null;
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
								<?php if ( ! empty( $badge['icon'] ) ) : ?>
									<span class="dest-landing-hero__badge-icon" aria-hidden="true"><?php echo esc_html( $badge['icon'] ); ?></span>
								<?php endif; ?>
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

	<?php if ( ! empty( $offer['geo_answer'] ) ) : ?>
		<section class="section geo-answer-block bg-milky">
			<div class="container container--narrow">
				<p class="geo-answer-block__text"><?php echo esc_html( $offer['geo_answer'] ); ?></p>
			</div>
		</section>
	<?php endif; ?>

	<?php
	get_template_part(
		'template-parts/offer/meal-ai-compare',
		null,
		array(
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
			<form class="zhiraf-form ai-lead-form dest-landing-lead-form" data-action="zhiraf_lead" data-form-context="all-inclusive">
				<div class="dest-landing-lead-form__fields ai-lead-form__fields">
					<label class="form-label">
						<span><?php esc_html_e( 'Состав (взрослые и дети)', 'zhiraf-ufa' ); ?></span>
						<select name="ai_travelers" class="form-input" required>
							<option value=""><?php esc_html_e( 'Кто едет', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( '2 взрослых', 'zhiraf-ufa' ); ?>"><?php esc_html_e( '2 взрослых', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( '2 взрослых + 1 ребёнок', 'zhiraf-ufa' ); ?>"><?php esc_html_e( '2 взрослых + 1 ребёнок', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( '2 взрослых + 2 детей', 'zhiraf-ufa' ); ?>"><?php esc_html_e( '2 взрослых + 2 детей', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Другой состав', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Другой состав', 'zhiraf-ufa' ); ?></option>
						</select>
					</label>
					<label class="form-label">
						<span><?php esc_html_e( 'Желаемый месяц', 'zhiraf-ufa' ); ?></span>
						<select name="ai_month" class="form-input" required>
							<option value=""><?php esc_html_e( 'Месяц поездки', 'zhiraf-ufa' ); ?></option>
							<?php
							$months = array( 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' );
							foreach ( $months as $m ) {
								printf( '<option value="%1$s">%1$s</option>', esc_attr( $m ) );
							}
							?>
						</select>
					</label>
					<input type="text" name="name" placeholder="<?php esc_attr_e( 'Ваше имя', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="name" />
					<input type="tel" name="phone" placeholder="<?php esc_attr_e( 'Телефон', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" />
					<label class="form-label dest-landing-lead-form__channel-label">
						<span class="dest-landing-lead-form__channel-text"><?php esc_html_e( 'Куда отправить подборку', 'zhiraf-ufa' ); ?></span>
						<select name="contact_channel" class="form-input">
							<option value="<?php esc_attr_e( 'WhatsApp', 'zhiraf-ufa' ); ?>" selected><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Telegram', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Telegram', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Звонок', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Звонок', 'zhiraf-ufa' ); ?></option>
						</select>
					</label>
					<input type="hidden" name="tour" value="<?php echo esc_attr( $offer['quiz_prefill'] ); ?>" />
					<input type="hidden" name="message" value="" />
					<?php zhiraf_ufa_consent_fields( 'all-inclusive' ); ?>
					<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Получить подборку AI/UAI', 'zhiraf-ufa' ); ?></button>
				</div>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
			<p class="dest-landing-cta__meta">
				<a href="tel:+79018105018" data-track="phone"><?php esc_html_e( '+7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
				·
				<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Каталог направлений', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</section>
</main>
