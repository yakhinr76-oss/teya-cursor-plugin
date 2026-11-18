<?php
/**
 * Полезные гиды — журнальная сетка (2 или 3 статьи).
 *
 * @package Zhiraf_Ufa
 * @var array $args { blog_links?: array }.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_links = ! empty( $args['blog_links'] ) && is_array( $args['blog_links'] ) ? $args['blog_links'] : array();
if ( empty( $blog_links ) || ! function_exists( 'zhiraf_ufa_prepare_useful_guides' ) ) {
	return;
}

$guides = zhiraf_ufa_prepare_useful_guides( $blog_links );
if ( empty( $guides ) ) {
	return;
}

$count = count( $guides );
?>
<div class="useful-guides" role="navigation" aria-label="<?php esc_attr_e( 'Полезные гиды', 'zhiraf-ufa' ); ?>">
	<p class="useful-guides__eyebrow"><?php esc_html_e( 'Полезные гиды', 'zhiraf-ufa' ); ?></p>

	<?php if ( $count >= 3 ) : ?>
		<?php
		$featured  = $guides[0];
		$side_list = array_slice( $guides, 1, 2 );
		?>
		<div class="useful-guides__grid useful-guides__grid--magazine">
			<a href="<?php echo esc_url( $featured['url'] ); ?>" class="useful-guides__card useful-guides__card--featured">
				<div class="useful-guides__media useful-guides__media--hero">
					<img
						src="<?php echo esc_url( $featured['image'] ); ?>"
						alt="<?php echo esc_attr( $featured['title'] ); ?>"
						loading="lazy"
						class="useful-guides__img"
					/>
				</div>
				<div class="useful-guides__body">
					<p class="useful-guides__meta">
						<span class="useful-guides__tag"><?php echo esc_html( $featured['tag'] ); ?></span>
						<span class="useful-guides__meta-sep" aria-hidden="true">•</span>
						<span class="useful-guides__read"><?php echo esc_html( $featured['read_time'] ); ?></span>
					</p>
					<h3 class="useful-guides__title"><?php echo esc_html( $featured['title'] ); ?></h3>
				</div>
				<p class="useful-guides__cta">
					<span><?php esc_html_e( 'Читать разбор', 'zhiraf-ufa' ); ?></span>
					<span class="useful-guides__cta-arrow" aria-hidden="true">→</span>
				</p>
			</a>

			<div class="useful-guides__side">
				<?php foreach ( $side_list as $guide ) : ?>
					<a href="<?php echo esc_url( $guide['url'] ); ?>" class="useful-guides__card useful-guides__card--compact">
						<div class="useful-guides__media useful-guides__media--thumb">
							<img
								src="<?php echo esc_url( $guide['image'] ); ?>"
								alt="<?php echo esc_attr( $guide['title'] ); ?>"
								loading="lazy"
								class="useful-guides__img"
							/>
						</div>
						<div class="useful-guides__body">
							<p class="useful-guides__meta useful-guides__meta--compact">
								<span class="useful-guides__tag"><?php echo esc_html( $guide['tag'] ); ?></span>
								<span class="useful-guides__meta-sep" aria-hidden="true">•</span>
								<span class="useful-guides__read"><?php echo esc_html( $guide['read_time'] ); ?></span>
							</p>
							<h3 class="useful-guides__title useful-guides__title--compact"><?php echo esc_html( $guide['title'] ); ?></h3>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php else : ?>
		<div class="useful-guides__grid useful-guides__grid--pair">
			<?php foreach ( $guides as $guide ) : ?>
				<a href="<?php echo esc_url( $guide['url'] ); ?>" class="useful-guides__card useful-guides__card--compact">
					<div class="useful-guides__media useful-guides__media--thumb">
						<img
							src="<?php echo esc_url( $guide['image'] ); ?>"
							alt="<?php echo esc_attr( $guide['title'] ); ?>"
							loading="lazy"
							class="useful-guides__img"
						/>
					</div>
					<div class="useful-guides__body">
						<p class="useful-guides__meta useful-guides__meta--compact">
							<span class="useful-guides__tag"><?php echo esc_html( $guide['tag'] ); ?></span>
							<span class="useful-guides__meta-sep" aria-hidden="true">•</span>
							<span class="useful-guides__read"><?php echo esc_html( $guide['read_time'] ); ?></span>
						</p>
						<h3 class="useful-guides__title useful-guides__title--compact"><?php echo esc_html( $guide['title'] ); ?></h3>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>