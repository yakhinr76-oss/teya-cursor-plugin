<?php
/**
 * Telegram subscribe banner for hot tours grid.
 *
 * @package Zhiraf_Ufa
 *
 * @var array $args { banner: array, url: string }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$banner = ! empty( $args['banner'] ) && is_array( $args['banner'] ) ? $args['banner'] : array();
$url    = ! empty( $args['url'] ) ? (string) $args['url'] : '';
if ( empty( $banner ) ) {
	return;
}
?>
<aside class="hot-telegram-banner tour-price-grid__banner" aria-labelledby="hot-telegram-banner-title">
	<div class="hot-telegram-banner__inner">
		<p class="hot-telegram-banner__kicker"><?php esc_html_e( 'Telegram · горящие из Уфы', 'zhiraf-ufa' ); ?></p>
		<h3 class="hot-telegram-banner__title" id="hot-telegram-banner-title">
			<?php echo esc_html( ! empty( $banner['title'] ) ? $banner['title'] : __( 'Горящие из Уфы в Telegram', 'zhiraf-ufa' ) ); ?>
		</h3>
		<p class="hot-telegram-banner__text">
			<?php echo esc_html( ! empty( $banner['text'] ) ? $banner['text'] : '' ); ?>
		</p>
		<?php if ( '' !== $url ) : ?>
			<a class="btn btn--primary hot-telegram-banner__cta" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" data-track="messenger" data-channel="telegram">
				<?php echo esc_html( ! empty( $banner['cta'] ) ? $banner['cta'] : __( 'Подписаться', 'zhiraf-ufa' ) ); ?>
			</a>
		<?php else : ?>
			<p class="hot-telegram-banner__fallback"><?php echo esc_html( ! empty( $banner['fallback'] ) ? $banner['fallback'] : '' ); ?></p>
		<?php endif; ?>
	</div>
</aside>
