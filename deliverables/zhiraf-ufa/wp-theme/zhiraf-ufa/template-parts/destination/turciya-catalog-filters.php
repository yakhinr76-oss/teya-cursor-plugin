<?php
/**
 * Turkey catalog — unified filter toolbar (resort + need).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$filters = function_exists( 'zhiraf_ufa_turciya_catalog_filters' ) ? zhiraf_ufa_turciya_catalog_filters() : array();
$copy    = function_exists( 'zhiraf_ufa_turciya_catalog_default_copy' ) ? zhiraf_ufa_turciya_catalog_default_copy() : array();

if ( empty( $filters ) ) {
	return;
}

$starter = $filters[0];
$resorts = array();
$needs   = array();
foreach ( $filters as $filter ) {
	$group = ! empty( $filter['group'] ) ? (string) $filter['group'] : '';
	if ( 'resort' === $group ) {
		$resorts[] = $filter;
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
			<p class="turciya-catalog-toolbar__guide" data-turciya-catalog-guide hidden>
				<a href="<?php echo esc_url( home_url( '/tour/turciya/antalya/' ) ); ?>">
					<?php esc_html_e( 'Подробный гид по Анталье (Лара, Коньяалты, Калеичи) →', 'zhiraf-ufa' ); ?>
				</a>
			</p>
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
			<span class="turciya-catalog-toolbar__group-label"><?php esc_html_e( 'Курорт:', 'zhiraf-ufa' ); ?></span>
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
