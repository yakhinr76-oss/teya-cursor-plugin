<?php
/**
 * Visible GEO answer block (question H2 + body).
 *
 * @package Zhiraf_Ufa
 * @var array $args { geo_answer: string }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$geo_answer = isset( $args['geo_answer'] ) ? trim( (string) $args['geo_answer'] ) : '';
if ( '' === $geo_answer || ! function_exists( 'zhiraf_ufa_geo_answer_parts' ) ) {
	return;
}

$parts = zhiraf_ufa_geo_answer_parts( $geo_answer );
if ( '' === $parts['body'] && '' === $parts['title'] ) {
	return;
}
?>
<section class="section geo-answer-block bg-milky">
	<div class="container container--narrow">
		<?php if ( ! empty( $parts['title'] ) ) : ?>
			<h2 class="section-title geo-answer-block__title"><?php echo esc_html( $parts['title'] ); ?></h2>
		<?php endif; ?>
		<p class="geo-answer-block__text"><?php echo esc_html( $parts['body'] ); ?></p>
	</div>
</section>