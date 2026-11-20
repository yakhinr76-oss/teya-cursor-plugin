<?php
/**
 * Curated catalog — filter toolbar (starter + optional emirates + needs).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : 'turciya';

$filters = function_exists( 'zhiraf_ufa_curated_catalog_filters' )
	? zhiraf_ufa_curated_catalog_filters( $dest_slug )
	: ( function_exists( 'zhiraf_ufa_turciya_catalog_filters' ) ? zhiraf_ufa_turciya_catalog_filters() : array() );
$copy    = function_exists( 'zhiraf_ufa_curated_catalog_default_copy' )
	? zhiraf_ufa_curated_catalog_default_copy( $dest_slug )
	: ( function_exists( 'zhiraf_ufa_turciya_catalog_default_copy' ) ? zhiraf_ufa_turciya_catalog_default_copy() : array() );

if ( empty( $filters ) ) {
	return;
}

$starter = null;
foreach ( $filters as $filter ) {
	if ( ! is_array( $filter ) ) {
		continue;
	}
	$group = ! empty( $filter['group'] ) ? (string) $filter['group'] : '';
	$fid   = ! empty( $filter['id'] ) ? (string) $filter['id'] : '';
	if ( 'default' === $group || 'starter' === $fid ) {
		$starter = $filter;
		break;
	}
}
if ( ! $starter ) {
	foreach ( $filters as $filter ) {
		if ( is_array( $filter ) && ! empty( $filter['id'] ) && ! empty( $filter['label'] ) ) {
			$starter = $filter;
			break;
		}
	}
}
if ( ! $starter ) {
	return;
}
$resorts = array();
$needs   = array();
$hide_resort_tabs = function_exists( 'zhiraf_ufa_curated_has_resort_pick_cards' )
	&& zhiraf_ufa_curated_has_resort_pick_cards( $dest_slug );

foreach ( $filters as $filter ) {
	$group = ! empty( $filter['group'] ) ? (string) $filter['group'] : '';
	if ( 'resort' === $group ) {
		if ( ! $hide_resort_tabs ) {
			$resorts[] = $filter;
		}
	} elseif ( 'need' === $group ) {
		$needs[] = $filter;
	}
}
?>
<div class="turciya-catalog-toolbar" data-turciya-catalog-toolbar>
	<div class="turciya-catalog-toolbar__head">
		<div class="turciya-catalog-toolbar__intro">
			<p class="turciya-catalog-toolbar__eyebrow" data-turciya-catalog-eyebrow>
				<?php echo esc_html( $copy['eyebrow'] ?? '' ); ?>
			</p>
			<h2 class="section-title turciya-catalog-toolbar__title" id="turciya-catalog-title" data-turciya-catalog-title>
				<?php echo esc_html( $copy['title'] ?? '' ); ?>
			</h2>
			<p class="turciya-catalog-toolbar__lead" id="turciya-catalog-desc" data-turciya-catalog-desc>
				<?php echo esc_html( $copy['desc'] ?? '' ); ?>
			</p>
			<?php if ( 'turciya' === $dest_slug ) : ?>
				<p class="turciya-catalog-toolbar__guide" data-turciya-catalog-guide hidden>
					<a href="<?php echo esc_url( home_url( '/tour/turciya/antalya/' ) ); ?>">
						<?php esc_html_e( 'Подробный гид по Анталье →', 'zhiraf-ufa' ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>
		<button
			type="button"
			class="turciya-catalog-toolbar__reset"
			data-turciya-catalog-reset
			hidden
		>
			<?php esc_html_e( '✕ Сбросить к общему топу', 'zhiraf-ufa' ); ?>
		</button>
	</div>

	<div class="turciya-catalog-toolbar__panel" role="toolbar" aria-label="<?php esc_attr_e( 'Фильтр витрины отелей', 'zhiraf-ufa' ); ?>">
		<?php if ( ! empty( $starter ) ) : ?>
			<button
				type="button"
				class="turciya-catalog-tab turciya-catalog-tab--active"
				data-turciya-catalog-filter="<?php echo esc_attr( (string) ( $starter['id'] ?? 'starter' ) ); ?>"
				data-catalog-title="<?php echo esc_attr( (string) ( $starter['title'] ?? '' ) ); ?>"
				data-catalog-desc="<?php echo esc_attr( (string) ( $starter['desc'] ?? '' ) ); ?>"
				aria-pressed="true"
			>
				<?php echo esc_html( (string) ( $starter['label'] ?? '' ) ); ?>
			</button>
		<?php endif; ?>

		<?php if ( $resorts ) : ?>
			<span class="turciya-catalog-toolbar__divider" aria-hidden="true"></span>
			<span class="turciya-catalog-toolbar__group-label">
				<?php echo 'oae' === $dest_slug ? esc_html__( 'Эмират:', 'zhiraf-ufa' ) : esc_html__( 'Курорт:', 'zhiraf-ufa' ); ?>
			</span>
			<?php foreach ( $resorts as $filter ) : ?>
				<button
					type="button"
					class="turciya-catalog-tab"
					data-turciya-catalog-filter="<?php echo esc_attr( (string) ( $filter['id'] ?? '' ) ); ?>"
					data-catalog-title="<?php echo esc_attr( (string) ( $filter['title'] ?? '' ) ); ?>"
					data-catalog-desc="<?php echo esc_attr( (string) ( $filter['desc'] ?? '' ) ); ?>"
					aria-pressed="false"
				>
					<?php echo esc_html( (string) ( $filter['label'] ?? '' ) ); ?>
				</button>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( $needs ) : ?>
			<span class="turciya-catalog-toolbar__divider" aria-hidden="true"></span>
			<span class="turciya-catalog-toolbar__group-label"><?php esc_html_e( 'Потребность:', 'zhiraf-ufa' ); ?></span>
			<?php foreach ( $needs as $filter ) : ?>
				<button
					type="button"
					class="turciya-catalog-tab"
					data-turciya-catalog-filter="<?php echo esc_attr( (string) ( $filter['id'] ?? '' ) ); ?>"
					data-catalog-title="<?php echo esc_attr( (string) ( $filter['title'] ?? '' ) ); ?>"
					data-catalog-desc="<?php echo esc_attr( (string) ( $filter['desc'] ?? '' ) ); ?>"
					aria-pressed="false"
				>
					<?php echo esc_html( (string) ( $filter['label'] ?? '' ) ); ?>
				</button>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>