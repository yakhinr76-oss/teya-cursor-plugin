<?php
/**
 * Homepage: directions hub (Ufa / Moscow / Excursion tabs).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ufa_cards       = zhiraf_ufa_home_destinations_hub_cards( 'ufa' );
$mow_cards       = zhiraf_ufa_home_destinations_hub_cards( 'mow' );
$excursion_cards = zhiraf_ufa_home_excursion_hub_cards();

$hub_tabs = array(
	array(
		'id'       => 'ufa',
		'controls' => 'dest-hub-grid-ufa',
		'active'   => true,
		'label'    => sprintf(
			/* translators: %d: number of destination cards */
			__( 'Прямой рейс из UFA (%d)', 'zhiraf-ufa' ),
			count( $ufa_cards )
		),
		'icon'     => 'plane',
	),
	array(
		'id'       => 'mow',
		'controls' => 'dest-hub-grid-mow',
		'active'   => false,
		'label'    => sprintf(
			/* translators: %d: number of destination cards */
			__( 'Вылет из Москвы (%d)', 'zhiraf-ufa' ),
			count( $mow_cards )
		),
		'icon'     => 'map',
	),
	array(
		'id'       => 'excursion',
		'controls' => 'dest-hub-grid-excursion',
		'active'   => false,
		'label'    => sprintf(
			/* translators: %d: number of destination cards */
			__( 'Экскурсионные туры (%d)', 'zhiraf-ufa' ),
			count( $excursion_cards )
		),
		'icon'     => 'compass',
	),
);

$hub_panels = array(
	array(
		'id'     => 'dest-hub-grid-ufa',
		'tab'    => 'ufa',
		'active' => true,
		'cards'  => $ufa_cards,
	),
	array(
		'id'     => 'dest-hub-grid-mow',
		'tab'    => 'mow',
		'active' => false,
		'cards'  => $mow_cards,
	),
	array(
		'id'     => 'dest-hub-grid-excursion',
		'tab'    => 'excursion',
		'active' => false,
		'cards'  => $excursion_cards,
		'grid_class' => 'destinations-hub__grid destinations-hub__grid--excursion',
	),
);
?>
<section class="section section--destinations-hub" id="destinations-hub">
	<div class="container destinations-hub">
		<div class="destinations-hub__head reveal">
			<div class="destinations-hub__intro">
				<p class="destinations-hub__eyebrow"><?php esc_html_e( 'Без пересадок', 'zhiraf-ufa' ); ?></p>
				<h2 class="destinations-hub__title"><?php esc_html_e( 'Популярные направления с вылетом из Уфы', 'zhiraf-ufa' ); ?></h2>
				<p class="destinations-hub__lead">
					<?php esc_html_e( 'Минимальные цены туроператора на двоих на 7 ночей с перелётом, проживанием и страховкой.', 'zhiraf-ufa' ); ?>
				</p>
			</div>
			<div class="destinations-hub__tabs-scroll">
				<div class="destinations-hub__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Город вылета', 'zhiraf-ufa' ); ?>">
					<?php foreach ( $hub_tabs as $tab ) : ?>
						<button
							type="button"
							class="destinations-hub__tab<?php echo ! empty( $tab['active'] ) ? ' is-active' : ''; ?>"
							role="tab"
							id="dest-hub-tab-<?php echo esc_attr( $tab['id'] ); ?>"
							aria-selected="<?php echo ! empty( $tab['active'] ) ? 'true' : 'false'; ?>"
							aria-controls="<?php echo esc_attr( $tab['controls'] ); ?>"
							data-departure-tab="<?php echo esc_attr( $tab['id'] ); ?>"
						>
							<?php if ( function_exists( 'zhiraf_ufa_lucide_icon' ) && ! empty( $tab['icon'] ) ) : ?>
								<span class="destinations-hub__tab-icon"><?php echo zhiraf_ufa_lucide_icon( (string) $tab['icon'], 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php endif; ?>
							<span class="destinations-hub__tab-text"><?php echo esc_html( $tab['label'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<?php foreach ( $hub_panels as $panel ) : ?>
			<div
				id="<?php echo esc_attr( $panel['id'] ); ?>"
				class="<?php echo esc_attr( ! empty( $panel['grid_class'] ) ? $panel['grid_class'] : 'destinations-hub__grid' ); ?><?php echo ! empty( $panel['active'] ) ? ' is-active' : ''; ?>"
				role="tabpanel"
				aria-labelledby="dest-hub-tab-<?php echo esc_attr( $panel['tab'] ); ?>"
				data-departure-grid="<?php echo esc_attr( $panel['tab'] ); ?>"
				<?php echo empty( $panel['active'] ) ? ' hidden' : ''; ?>
			>
				<?php
				foreach ( $panel['cards'] as $card ) {
					get_template_part(
						'template-parts/home/destinations-hub-card',
						null,
						array( 'card' => $card )
					);
				}
				?>
			</div>
		<?php endforeach; ?>
	</div>
</section>