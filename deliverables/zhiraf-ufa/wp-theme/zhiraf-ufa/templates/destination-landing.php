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
$use_turciya_curated = ( 'turciya' === $dest_slug ) && ! empty( $dest['curated_landing_v1'] );
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
								<?php if ( ! empty( $badge['icon'] ) ) : ?>
									<span class="dest-landing-hero__badge-icon" aria-hidden="true"><?php echo esc_html( $badge['icon'] ); ?></span>
								<?php endif; ?>
								<span><?php echo esc_html( $badge['text'] ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( $use_turciya_curated ) : ?>
					<?php
					get_template_part(
						'template-parts/destination/turciya-resort-chips',
						null,
						array( 'context' => 'hero' )
					);
					?>
				<?php endif; ?>
				<div class="dest-landing-hero__actions">
					<?php if ( $use_turciya_curated ) : ?>
						<a class="btn btn--primary btn--lg" href="#dest-curated-hotels"><?php esc_html_e( 'Смотреть 6 отелей', 'zhiraf-ufa' ); ?></a>
						<?php
						$hero_compare_btn = function_exists( 'get_field' ) ? get_field( 'resort_compare_btn_text', get_queried_object() ) : '';
						$hero_compare_btn = ! empty( $hero_compare_btn ) ? $hero_compare_btn : __( 'Сравнить Кемер и Сиде', 'zhiraf-ufa' );
						?>
						<button type="button" class="btn btn--outline btn--lg" data-turciya-lead-open><?php echo esc_html( $hero_compare_btn ); ?></button>
					<?php else : ?>
						<a class="btn btn--primary btn--lg" href="#dest-express-pick"><?php esc_html_e( 'Подбор за 10 секунд', 'zhiraf-ufa' ); ?></a>
						<a class="btn btn--outline btn--lg" href="#dest-price-tiers"><?php esc_html_e( 'Ценовые ориентиры', 'zhiraf-ufa' ); ?></a>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $dest['hero_cta_hint'] ) ) : ?>
					<p class="dest-landing-hero__cta-hint"><?php echo esc_html( $dest['hero_cta_hint'] ); ?></p>
				<?php endif; ?>
				<?php if ( $use_turciya_curated ) : ?>
					<ul class="dest-landing-hero__trust">
						<li><span aria-hidden="true">✈️</span> <?php esc_html_e( 'Прямой рейс из Уфы (в пути ~4.5–5 часов)', 'zhiraf-ufa' ); ?></li>
						<li><span aria-hidden="true">📅</span> <?php esc_html_e( 'Вылеты каждый день (подберем под ваш отпуск)', 'zhiraf-ufa' ); ?></li>
						<li><span aria-hidden="true">🛡️</span> <?php esc_html_e( 'Честные цены (включая топливные сборы и страховку)', 'zhiraf-ufa' ); ?></li>
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

	<?php if ( $use_turciya_curated ) : ?>
		<?php get_template_part( 'template-parts/destination/turciya-curated-showcase' ); ?>
		<?php get_template_part( 'template-parts/sections/kids-turkey' ); ?>
		<?php get_template_part( 'template-parts/destination/turciya-expert-nuances' ); ?>
	<?php else : ?>
	<?php
	if ( function_exists( 'zhiraf_ufa_render_landing_conversion_stack' ) ) {
		zhiraf_ufa_render_landing_conversion_stack( $dest_slug, $dest, 'destination' );
	}
	?>
	<?php endif; ?>

	<?php
	if ( ! $use_turciya_curated ) {
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
	<section class="section dest-landing-faq<?php echo $use_turciya_curated ? ' dest-landing-faq--panel' : ''; ?>">
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
				<div class="faq-home__list<?php echo $use_turciya_curated ? ' faq-home__list--panel' : ''; ?>">
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

	<?php if ( $use_turciya_curated ) : ?>
		<?php get_template_part( 'template-parts/destination/turciya-resort-compare-lead' ); ?>
	<?php endif; ?>

	<?php if ( ! $use_turciya_curated ) : ?>
	<section class="section dest-landing-cta" id="dest-lead">
		<div class="container container--narrow dest-landing-cta__inner">
			<h2><?php esc_html_e( 'Готовы обсудить поездку?', 'zhiraf-ufa' ); ?></h2>
			<p><?php echo esc_html( ! empty( $dest['cta_lead'] ) ? $dest['cta_lead'] : __( 'Оставьте контакты — перезвоним в рабочее время и предложим 2–4 варианта под ваш бюджет.', 'zhiraf-ufa' ) ); ?></p>
			<form class="zhiraf-form dest-landing-lead-form" data-action="zhiraf_lead" data-form-context="dest-<?php echo esc_attr( $dest_slug ); ?>">
				<div class="dest-landing-lead-form__fields">
					<label class="form-label dest-landing-lead-form__field">
						<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Ваше имя', 'zhiraf-ufa' ); ?></span>
						<input type="text" name="name" placeholder="<?php esc_attr_e( 'Как к вам обращаться', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="name" />
					</label>
					<label class="form-label dest-landing-lead-form__field">
						<span class="dest-landing-lead-form__field-text"><?php esc_html_e( 'Телефон', 'zhiraf-ufa' ); ?></span>
						<input type="tel" name="phone" placeholder="<?php esc_attr_e( '+7 …', 'zhiraf-ufa' ); ?>" required class="form-input" autocomplete="tel" inputmode="tel" />
					</label>
					<?php if ( ! empty( $dest['cta_message_placeholder'] ) ) : ?>
						<textarea name="message" class="form-input dest-landing-lead-form__message" rows="3" placeholder="<?php echo esc_attr( $dest['cta_message_placeholder'] ); ?>"></textarea>
					<?php endif; ?>
					<label class="form-label dest-landing-lead-form__channel-label">
						<span class="dest-landing-lead-form__channel-text"><?php esc_html_e( 'Куда отправить подборку', 'zhiraf-ufa' ); ?></span>
						<select name="contact_channel" class="form-input">
							<option value="<?php esc_attr_e( 'WhatsApp', 'zhiraf-ufa' ); ?>" selected><?php esc_html_e( 'WhatsApp', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Telegram', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Telegram', 'zhiraf-ufa' ); ?></option>
							<option value="<?php esc_attr_e( 'Звонок', 'zhiraf-ufa' ); ?>"><?php esc_html_e( 'Звонок', 'zhiraf-ufa' ); ?></option>
						</select>
					</label>
					<div class="zhiraf-hp" aria-hidden="true">
						<input type="hidden" name="tour" value="<?php echo esc_attr( $dest['quiz_prefill'] ); ?>" data-lead-tour />
						<input type="hidden" name="destination" value="<?php echo esc_attr( ! empty( $dest['name'] ) ? $dest['name'] : $dest_slug ); ?>" />
						<input type="hidden" name="destination_slug" value="<?php echo esc_attr( $dest_slug ); ?>" />
						<input type="hidden" name="express_pick" value="" data-lead-express />
					</div>
					<?php zhiraf_ufa_consent_fields( 'dest-lead-' . $dest_slug ); ?>
					<button type="submit" class="btn btn--primary btn--lg"><?php esc_html_e( 'Отправить заявку', 'zhiraf-ufa' ); ?></button>
				</div>
				<p class="form-status" role="status" aria-live="polite"></p>
			</form>
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
		'use_turciya_modal' => $use_turciya_curated,
	)
);
?>
