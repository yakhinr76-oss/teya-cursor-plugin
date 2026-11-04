<?php
/**
 * No content partial.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="no-results">
	<h1><?php esc_html_e( 'Ничего не найдено', 'zhiraf-ufa' ); ?></h1>
	<p><?php esc_html_e( 'Попробуйте изменить запрос или вернитесь на главную.', 'zhiraf-ufa' ); ?></p>
	<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'zhiraf-ufa' ); ?></a>
</section>
