<?php
/**
 * Homepage: unified «Популярные направления» + tour catalog.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tabs  = zhiraf_ufa_home_unified_catalog_tabs();
$tours = zhiraf_ufa_home_unified_catalog_tours();
$tabs_json = wp_json_encode(
	array_map(
		function ( $tab ) {
			return array(
				'id'    => $tab['id'],
				'label' => $tab['label'],
				'url'   => zhiraf_ufa_home_catalog_tab_landing_url( $tab['id'] ),
				'cta'   => zhiraf_ufa_home_catalog_tab_landing_label( $tab['id'] ),
			);
		},
		$tabs
	)
);
$wa    = function_exists( 'zhiraf_ufa_whatsapp_url_with_text' )
	? zhiraf_ufa_whatsapp_url_with_text(
		zhiraf_ufa_brand_whatsapp_url(),
		__( 'Здравствуйте! Нужен подбор тура из Уфы — напишите параметры, пожалуйста, 3 варианта под наш бюджет.', 'zhiraf-ufa' )
	)
	: 'https://wa.me/79018105018';
?>
<section class="section section--unified-catalog" id="tour-catalog">
	<div class="container unified-catalog">
		<header class="unified-catalog__intro reveal">
			<p class="catalog-eyebrow">
				<?php esc_html_e( 'Прямые рейсы из аэропорта Уфа (UFA)', 'zhiraf-ufa' ); ?>
			</p>
			<h2 class="unified-catalog__title"><?php esc_html_e( 'Популярные туры с прямым вылетом из Уфы', 'zhiraf-ufa' ); ?></h2>
			<p class="unified-catalog__lead">
				<?php esc_html_e( 'Стоимость указана на двоих взрослых с перелётом, трансфером, страховкой и проживанием. Данные синхронизируются напрямую с туроператорами.', 'zhiraf-ufa' ); ?>
			</p>
		</header>

		<div
			id="tour-tabs"
			class="catalog-pills unified-catalog__tabs reveal"
			role="tablist"
			aria-label="<?php esc_attr_e( 'Фильтр направлений', 'zhiraf-ufa' ); ?>"
		>
			<?php foreach ( $tabs as $i => $tab ) : ?>
				<button
					type="button"
					class="catalog-pills__btn tab-btn<?php echo 0 === $i ? ' is-active' : ''; ?>"
					role="tab"
					data-filter="<?php echo esc_attr( $tab['id'] ); ?>"
					aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
				><?php echo esc_html( $tab['label'] ); ?></button>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $tours ) ) : ?>
			<div
				id="tours-grid"
				class="tour-price-grid tour-price-grid--catalog unified-catalog__grid"
				data-visible-limit="<?php echo (int) ZHIRAF_UFA_HOME_CATALOG_VISIBLE; ?>"
				data-more-step="<?php echo (int) ZHIRAF_UFA_HOME_CATALOG_MORE_STEP; ?>"
				data-max-visible="9"
				data-tab-links="<?php echo esc_attr( $tabs_json ); ?>"
			>
				<?php
				foreach ( $tours as $i => $tour_item ) {
					get_template_part(
						'template-parts/cards/card-tour-catalog',
						null,
						array(
							'tour'       => $tour_item,
							'card_index' => $i,
						)
					);
				}
				?>
			</div>
			<div class="unified-catalog__footer reveal">
				<button
					type="button"
					class="btn btn--outline unified-catalog__more"
					id="catalog-show-more"
					hidden
				>
					<?php
					printf(
						/* translators: %d: number of cards */
						esc_html__( 'Показать ещё %d отелей', 'zhiraf-ufa' ),
						(int) ZHIRAF_UFA_HOME_CATALOG_MORE_STEP
					);
					?>
				</button>
				<a
					class="unified-catalog__country-link"
					id="catalog-country-link"
					href="<?php echo esc_url( zhiraf_ufa_home_catalog_tab_landing_url( 'all' ) ); ?>"
				>
					<span id="catalog-country-link-text"><?php echo esc_html( zhiraf_ufa_home_catalog_tab_landing_label( 'all' ) ); ?></span>
					<span aria-hidden="true">→</span>
				</a>
			</div>
		<?php else : ?>
			<p class="unified-catalog__empty reveal"><?php esc_html_e( 'Сейчас обновляем цены — напишите нам, подберём актуальные варианты из базы операторов.', 'zhiraf-ufa' ); ?></p>
		<?php endif; ?>

		<div class="catalog-rescue reveal">
			<div class="catalog-rescue__copy">
				<h3 class="catalog-rescue__title"><?php esc_html_e( 'Не нашли подходящий отель или нужны другие даты?', 'zhiraf-ufa' ); ?></h3>
				<p class="catalog-rescue__text">
					<?php esc_html_e( 'В базе более 3 500 вариантов из Уфы. Напишите нам параметры — отберём 3 отеля под ваш бюджет за 20 минут.', 'zhiraf-ufa' ); ?>
				</p>
			</div>
			<div class="catalog-rescue__actions">
				<a class="btn btn--whatsapp catalog-rescue__wa" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener noreferrer" data-track="messenger" data-channel="whatsapp">
					<?php esc_html_e( 'Написать в WhatsApp', 'zhiraf-ufa' ); ?>
				</a>
				<button type="button" class="btn btn--primary catalog-rescue__quiz" data-quiz-open data-cta-context="home_catalog_rescue_quiz">
					<?php esc_html_e( 'Подобрать в квизе', 'zhiraf-ufa' ); ?>
				</button>
			</div>
		</div>
	</div>
</section>
