<?php
/**
 * AI vs UAI comparison table + 3 highlights.
 *
 * @package Zhiraf_Ufa
 * @var array $args { highlights: array, rows: array }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$highlights = ! empty( $args['highlights'] ) && is_array( $args['highlights'] ) ? $args['highlights'] : array();
$rows       = ! empty( $args['rows'] ) && is_array( $args['rows'] ) ? $args['rows'] : array();
?>
<section class="section ai-meal-compare bg-milky">
	<div class="container">
		<h2 class="section-title"><?php esc_html_e( 'AI и Ultra AI — в чём разница', 'zhiraf-ufa' ); ?></h2>
		<?php if ( ! empty( $highlights ) ) : ?>
			<div class="ai-meal-highlights">
				<?php foreach ( $highlights as $item ) : ?>
					<article class="ai-meal-highlight">
						<?php if ( ! empty( $item['icon'] ) ) : ?>
							<span class="ai-meal-highlight__icon" aria-hidden="true"><?php echo esc_html( $item['icon'] ); ?></span>
						<?php endif; ?>
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<p><?php echo esc_html( $item['text'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $rows ) ) : ?>
			<div class="ai-meal-table-wrap">
				<table class="ai-meal-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Что включено', 'zhiraf-ufa' ); ?></th>
							<th scope="col">AI</th>
							<th scope="col">UAI</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $rows as $row ) : ?>
							<tr>
								<th scope="row"><?php echo esc_html( $row['label'] ); ?></th>
								<td><?php echo ! empty( $row['ai'] ) ? '✓' : '—'; ?></td>
								<td><?php echo ! empty( $row['uai'] ) ? '✓' : '—'; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
</section>
