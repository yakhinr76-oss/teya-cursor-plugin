<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$blog_posts = zhiraf_ufa_home_blog_posts();
$dest_all   = zhiraf_ufa_destinations();
?>
<main id="primary" class="site-main site-main--home">

	<!-- Premium Hero -->
	<section class="hero-premium">
		<div class="hero-premium__bg">
			<?php zhiraf_ufa_img( 'hero-uae.jpg', 'Hero background', 'hero-premium__img', false, true ); ?>
			<div class="hero-premium__overlay"></div>
		</div>
		<div class="container hero-premium__content">
			<h1 class="hero-premium__title reveal">Открывайте мир <br>вместе с нами</h1>
			<p class="hero-premium__subtitle reveal reveal-delay-1">Индивидуальные туры из Уфы. Безупречный сервис и внимание к каждой детали вашего отдыха.</p>
			<div class="hero-premium__actions reveal reveal-delay-2">
				<button class="btn btn--primary btn--lg" data-quiz-open><?php esc_html_e('Подобрать тур', 'zhiraf-ufa'); ?></button>
			</div>
		</div>
	</section>

	<!-- Destinations -->
	<section class="section section--destinations bg-milky">
		<div class="container">
			<div class="section-head section-head--center reveal">
				<h2 class="section-title">Популярные направления</h2>
				<p class="section-subtitle">Отдохните там, где вас ждут идеальные условия</p>
			</div>
			
			<div class="dest-premium-grid">
				<?php foreach ( $dest_all as $index => $dest ) : ?>
					<a href="<?php echo esc_url( $dest['url'] ); ?>" class="dest-premium-card reveal">
						<div class="dest-premium-card__bg">
							<?php zhiraf_ufa_img( $dest['image'], $dest['alt'], 'dest-premium-card__img', true ); ?>
						</div>
						<div class="dest-premium-card__content">
							<h3 class="dest-premium-card__title"><?php echo esc_html( $dest['name'] ); ?></h3>
							<span class="dest-premium-card__cta">Подробнее &rarr;</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Editorial Blog/Articles -->
	<section class="section section--editorial">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title">Журнал о путешествиях</h2>
			</div>
			<div class="editorial-grid">
				<?php 
				if ( ! empty( $blog_posts ) ) {
					foreach ( $blog_posts as $i => $post ) : 
						$thumb_id = get_post_thumbnail_id( $post );
						?>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="editorial-card reveal">
							<div class="editorial-card__img-wrap">
								<?php 
								if ( $thumb_id ) {
									echo wp_get_attachment_image( $thumb_id, 'large', false, array( 'class' => 'editorial-card__img' ) );
								} else {
									$fallback = array('blog-featured.jpg', 'blog-thumb-1.jpg', 'blog-thumb-2.jpg', 'blog-thumb-3.jpg');
									zhiraf_ufa_img( $fallback[ $i % 4 ], get_the_title( $post ), 'editorial-card__img', true );
								}
								?>
							</div>
							<div class="editorial-card__meta">
								<span class="editorial-card__cat"><?php echo esc_html( zhiraf_ufa_story_category_label( $post->ID ) ); ?></span>
								<time class="editorial-card__date"><?php echo get_the_date( '', $post ); ?></time>
							</div>
							<h3 class="editorial-card__title"><?php echo esc_html( get_the_title( $post ) ); ?></h3>
						</a>
					<?php endforeach; 
				} else {
					$fallback = zhiraf_ufa_blog_fallback_stories();
					foreach ( $fallback as $i => $story ) : ?>
						<a href="<?php echo esc_url( $story['url'] ); ?>" class="editorial-card reveal">
							<div class="editorial-card__img-wrap">
								<?php zhiraf_ufa_img( $story['image'], $story['title'], 'editorial-card__img', true ); ?>
							</div>
							<div class="editorial-card__meta">
								<span class="editorial-card__cat"><?php echo esc_html( $story['category'] ); ?></span>
							</div>
							<h3 class="editorial-card__title"><?php echo esc_html( $story['title'] ); ?></h3>
						</a>
					<?php endforeach;
				}
				?>
			</div>
		</div>
	</section>

	<!-- Form / Subscribe section (Minimal) -->
	<section class="section section--cta bg-milky">
		<div class="container reveal">
			<div class="cta-premium">
				<div class="cta-premium__content">
					<h2>Индивидуальный подбор</h2>
					<p>Оставьте контакты, и мы подготовим для вас персональные варианты отдыха.</p>
				</div>
				<div class="cta-premium__form">
					<button class="btn btn--primary btn--lg btn--block" data-quiz-open>Начать подбор</button>
				</div>
			</div>
		</div>
	</section>

</main>
<?php
get_footer();
