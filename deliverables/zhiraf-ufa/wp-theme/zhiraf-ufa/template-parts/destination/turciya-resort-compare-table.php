<?php
/**
 * Turkey — four-resort comparison table (before curated showcase).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$table = function_exists( 'zhiraf_ufa_turciya_resort_compare_table' )
	? zhiraf_ufa_turciya_resort_compare_table()
	: array();

if ( empty( $table['columns'] ) || empty( $table['rows'] ) ) {
	return;
}

$columns = $table['columns'];
$rows    = $table['rows'];
?>
<section class="section turciya-resort-table" id="dest-resort-compare" aria-labelledby="turciya-resort-table-title">
	<div class="container">
		<div class="turciya-resort-table__head">
			<p class="turciya-resort-table__eyebrow"><?php esc_html_e( 'Шпаргалка туриста', 'zhiraf-ufa' ); ?></p>
			<h2 class="section-title turciya-resort-table__title" id="turciya-resort-table-title">
				<?php esc_html_e( 'Какой курорт Турции выбрать именно вам?', 'zhiraf-ufa' ); ?>
			</h2>
			<p class="turciya-resort-table__lead">
				<?php esc_html_e( 'Честное сравнение по пляжу, детской инфраструктуре и времени трансфера от рейса из Уфы.', 'zhiraf-ufa' ); ?>
			</p>
		</div>

		<div class="turciya-resort-table__scroll" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Таблица сравнения курортов', 'zhiraf-ufa' ); ?>">
			<table class="turciya-resort-table__grid">
				<thead>
					<tr>
						<th scope="col" class="turciya-resort-table__corner"><?php esc_html_e( 'Критерий', 'zhiraf-ufa' ); ?></th>
						<?php foreach ( $columns as $col ) : ?>
							<?php
							$col_id    = ! empty( $col['id'] ) ? sanitize_html_class( (string) $col['id'] ) : '';
							$featured  = ! empty( $col['featured'] );
							$tag_mod   = ! empty( $col['tag_mod'] ) ? sanitize_html_class( (string) $col['tag_mod'] ) : '';
							$th_class  = 'turciya-resort-table__col-head';
							if ( $featured ) {
								$th_class .= ' turciya-resort-table__col-head--featured';
							}
							?>
							<th scope="col" class="<?php echo esc_attr( $th_class ); ?>">
								<?php if ( ! empty( $col['tag'] ) ) : ?>
									<span class="turciya-resort-table__tag turciya-resort-table__tag--<?php echo esc_attr( $tag_mod ); ?>">
										<?php echo esc_html( (string) $col['tag'] ); ?>
									</span>
								<?php endif; ?>
								<span class="turciya-resort-table__col-name"><?php echo esc_html( (string) ( $col['name'] ?? '' ) ); ?></span>
								<?php if ( ! empty( $col['subtitle'] ) ) : ?>
									<span class="turciya-resort-table__col-sub"><?php echo esc_html( (string) $col['subtitle'] ); ?></span>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $rows as $row ) : ?>
						<tr>
							<th scope="row" class="turciya-resort-table__row-label"><?php echo esc_html( (string) ( $row['label'] ?? '' ) ); ?></th>
							<?php foreach ( $columns as $col ) : ?>
								<?php
								$col_id = ! empty( $col['id'] ) ? (string) $col['id'] : '';
								$raw    = isset( $row['cells'][ $col_id ] ) ? $row['cells'][ $col_id ] : '';
								$cell   = function_exists( 'zhiraf_ufa_turciya_resort_compare_cell' )
									? zhiraf_ufa_turciya_resort_compare_cell( $raw )
									: array( 'text' => is_string( $raw ) ? $raw : '', 'mod' => '' );
								$td_class = 'turciya-resort-table__cell';
								if ( ! empty( $col['featured'] ) ) {
									$td_class .= ' turciya-resort-table__cell--featured';
								}
								if ( ! empty( $cell['mod'] ) ) {
									$td_class .= ' turciya-resort-table__cell--' . esc_attr( $cell['mod'] );
								}
								?>
								<td class="<?php echo esc_attr( $td_class ); ?>"><?php echo esc_html( $cell['text'] ); ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					<tr class="turciya-resort-table__cta-row">
						<td class="turciya-resort-table__row-label" aria-hidden="true"></td>
						<?php foreach ( $columns as $col ) : ?>
							<?php
							$col_id   = ! empty( $col['id'] ) ? sanitize_html_class( (string) $col['id'] ) : '';
							$featured = ! empty( $col['featured'] );
							$td_class = 'turciya-resort-table__cell turciya-resort-table__cell--cta';
							if ( $featured ) {
								$td_class .= ' turciya-resort-table__cell--featured';
							}
							$btn_class = 'turciya-resort-table__filter-btn';
							if ( $featured ) {
								$btn_class .= ' btn btn--primary';
							} else {
								$btn_class .= ' turciya-resort-table__filter-btn--ghost';
							}
							?>
							<td class="<?php echo esc_attr( $td_class ); ?>">
								<button
									type="button"
									class="<?php echo esc_attr( $btn_class ); ?>"
									data-resort-filter="<?php echo esc_attr( $col_id ); ?>"
									data-turciya-goto-showcase
								>
									<?php echo esc_html( (string) ( $col['cta'] ?? __( 'Смотреть отели →', 'zhiraf-ufa' ) ) ); ?>
								</button>
							</td>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</section>
