<?php
/**
 * SEO interlink cloud above footer legal.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$links = function_exists( 'zhiraf_ufa_hub_seo_footer_links' ) ? zhiraf_ufa_hub_seo_footer_links() : array();
if ( ! $links ) {
	return;
}
?>
<div class="footer-seo-cloud">
	<div class="container">
		<p class="footer-seo-cloud__title"><?php esc_html_e( 'Популярные направления из Уфы:', 'zhiraf-ufa' ); ?></p>
		<div class="footer-seo-cloud__links">
			<?php foreach ( $links as $link ) : ?>
				<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
