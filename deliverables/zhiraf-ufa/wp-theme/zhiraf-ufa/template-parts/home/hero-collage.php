<?php
/**
 * Hero collage: Remotion Player + static fallback.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$photos = zhiraf_ufa_hero_orbit_photo_urls();
$use_remotion = zhiraf_ufa_hero_remotion_player_available();
$collage_class = 'hero-premium__collage reveal reveal-delay-1';
if ( $use_remotion ) {
	$collage_class .= ' hero-premium__collage--remotion';
} else {
	$collage_class .= ' hero-premium__collage--orbit';
}
?>
<div class="<?php echo esc_attr( $collage_class ); ?>" data-three-canvas="hero-ambient"<?php echo $use_remotion ? '' : ' data-hero-orbit'; ?>>
	<div class="hero-ambient-canvas" aria-hidden="true"></div>

	<?php if ( $use_remotion ) : ?>
		<div id="hero-remotion-root" class="hero-remotion-player">
			<div class="hero-remotion-player__fallback" aria-hidden="false">
				<?php
				foreach ( array_slice( $photos, 0, 3 ) as $i => $url ) :
					$cls = 'hero-collage__img hero-collage__img--' . ( $i + 1 );
					?>
					<div class="<?php echo esc_attr( $cls ); ?>">
						<img src="<?php echo esc_url( $url ); ?>" alt="" width="500" height="625" decoding="async" loading="eager" />
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php else : ?>
		<div class="hero-collage__img hero-collage__img--1 collage-tile">
			<?php zhiraf_ufa_img( 'hero-uae.jpg', __( 'ОАЭ', 'zhiraf-ufa' ), '', false, true ); ?>
		</div>
		<div class="hero-collage__img hero-collage__img--2 collage-tile">
			<?php zhiraf_ufa_img( 'hero-turkey.jpg', __( 'Турция', 'zhiraf-ufa' ), '', false, true ); ?>
		</div>
		<div class="hero-collage__img hero-collage__img--3 collage-tile">
			<?php zhiraf_ufa_img( 'hero-egypt.jpg', __( 'Египет', 'zhiraf-ufa' ), '', false, true ); ?>
		</div>
	<?php endif; ?>

	<svg class="hero-collage__flight-path" data-motion="flight-path" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
		<path d="M10,180 Q100,50 380,80" stroke="var(--color-primary)" stroke-width="2" stroke-dasharray="6,6" fill="none"/>
		<path d="M370,75 L385,81 L370,87 Z" fill="var(--color-primary)" />
	</svg>
</div>
