<?php
/**
 * Homepage: directions hub (Ufa / Moscow tabs).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ufa_cards = zhiraf_ufa_home_destinations_hub_cards( 'ufa' );
$mow_cards = zhiraf_ufa_home_destinations_hub_cards( 'mow' );
?>
<section class="section section--destinations-hub" id="destinations-hub">
	<div class="container destinations-hub">
		<div class="destinations-hub__head reveal">
			<div class="destinations-hub__intro">
				<p class="destinations-hub__eyebrow"><?php esc_html_e( 'Актуальные предложения', 'zhiraf-ufa' ); ?></p>
				<h2 class="destinations-hub__title"><?php esc_html_e( 'Туры и путевки с вылетом из Уфы и Москвы', 'zhiraf-ufa' ); ?></h2>
				<p class="destinations-hub__lead">
					<?php esc_html_e( 'Минимальные цены туроператора на двоих на 7 ночей с перелётом, проживанием и страховкой.', 'zhiraf-ufa' ); ?>
				</p>
			</div>
			<div class="destinations-hub__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Город вылета', 'zhiraf-ufa' ); ?>">
				<button
					type="button"
					class="destinations-hub__tab is-active"
					role="tab"
					id="dest-hub-tab-ufa"
					aria-selected="true"
					aria-controls="dest-hub-grid-ufa"
					data-departure-tab="ufa"
				><?php esc_html_e( 'Вылет из Уфы', 'zhiraf-ufa' ); ?></button>
				<button
					type="button"
					class="destinations-hub__tab"
					role="tab"
					id="dest-hub-tab-mow"
					aria-selected="false"
					aria-controls="dest-hub-grid-mow"
					data-departure-tab="mow"
				><?php esc_html_e( 'Вылет из Москвы', 'zhiraf-ufa' ); ?></button>
			</div>
		</div>

		<div
			id="dest-hub-grid-ufa"
			class="destinations-hub__grid is-active"
			role="tabpanel"
			aria-labelledby="dest-hub-tab-ufa"
			data-departure-grid="ufa"
		>
			<?php
			foreach ( $ufa_cards as $card ) {
				get_template_part(
					'template-parts/home/destinations-hub-card',
					null,
					array( 'card' => $card )
				);
			}
			?>
		</div>

		<div
			id="dest-hub-grid-mow"
			class="destinations-hub__grid"
			role="tabpanel"
			aria-labelledby="dest-hub-tab-mow"
			data-departure-grid="mow"
			hidden
		>
			<?php
			foreach ( $mow_cards as $card ) {
				get_template_part(
					'template-parts/home/destinations-hub-card',
					null,
					array( 'card' => $card )
				);
			}
			?>
		</div>
	</div>
</section>
