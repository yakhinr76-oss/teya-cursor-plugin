<?php
/**
 * Homepage: two mood images from the internal Pexels picks (attribution).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = zhiraf_ufa_home_pexels_moodboard_items();
?>
<section class="section section--home-pexels-moodboard bg-milky" id="home-pexels-moodboard" aria-labelledby="home-pexels-moodboard-title">
	<div class="container">
		<div class="section-head reveal">
			<h2 class="section-title" id="home-pexels-moodboard-title">
				<?php esc_html_e( 'Настроение отдыха', 'zhiraf-ufa' ); ?>
			</h2>
			<p class="lead home-pexels-moodboard__lead">
				<?php esc_html_e( 'Два кадра из нашей внутренней подборки референсов — то, к чему стремится подбор направлений.', 'zhiraf-ufa' ); ?>
			</p>
		</div>
		<div class="home-pexels-moodboard__grid">
			<?php foreach ( $items as $row ) : ?>
				<figure class="home-pexels-moodboard__card reveal">
					<?php zhiraf_ufa_img( $row['file'], $row['alt'], 'home-pexels-moodboard__img', true, false ); ?>
					<figcaption class="home-pexels-moodboard__caption">
						<span class="home-pexels-moodboard__label"><?php echo esc_html( $row['label'] ); ?></span>
						<span class="home-pexels-moodboard__credit">
							<?php
							echo esc_html(
								sprintf(
									/* translators: %s: photographer name on Pexels */
									__( 'Фото: %s на ', 'zhiraf-ufa' ),
									$row['photographer']
								)
							);
							?>
							<a href="<?php echo esc_url( $row['credit_url'] ); ?>" rel="noopener noreferrer" target="_blank"><?php esc_html_e( 'Pexels', 'zhiraf-ufa' ); ?></a>
						</span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
