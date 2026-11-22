<?php
/**
 * Prominent resort nav — under hero on parent country landings.
 *
 * @package Zhiraf_Ufa
 * @var array $args { dest_slug: string, links: array<int,array<string,mixed>> }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest_slug = ! empty( $args['dest_slug'] ) ? sanitize_key( (string) $args['dest_slug'] ) : '';
$links     = ! empty( $args['links'] ) && is_array( $args['links'] ) ? $args['links'] : array();
if ( '' === $dest_slug || empty( $links ) ) {
	return;
}
?>
<section class="section dest-resort-nav-prominent" aria-labelledby="dest-resort-nav-<?php echo esc_attr( $dest_slug ); ?>">
	<div class="container">
		<h2 class="dest-resort-nav-prominent__title" id="dest-resort-nav-<?php echo esc_attr( $dest_slug ); ?>">
			<?php esc_html_e( 'Популярные курорты направления', 'zhiraf-ufa' ); ?>
		</h2>
		<div class="dest-resort-link-grid dest-resort-nav-prominent__grid" role="list">
			<?php
			foreach ( $links as $link ) {
				if ( ! is_array( $link ) ) {
					continue;
				}
				if ( function_exists( 'zhiraf_ufa_render_resort_hub_card' ) ) {
					zhiraf_ufa_render_resort_hub_card( $link, $dest_slug );
				}
			}
			?>
		</div>
	</div>
</section>