<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$blog_posts = zhiraf_ufa_home_blog_posts();
$dest_all   = zhiraf_ufa_destinations();
?>
<main id="primary" class="site-main site-main--home">

	<!-- Premium Hero with Collage -->
	<section class="hero-premium">
		<div class="container hero-premium__inner">
			<div class="hero-premium__content reveal">
				<h1 class="hero-premium__title">
					Discover the World's <br><span class="hero-premium__title-highlight">Hidden</span> Wonders
				</h1>
				<p class="hero-premium__subtitle">Find the unique moments and hidden gems that ignite unforgettable experiences. From rare encounters to remarkable destinations, we help you uncover the spark that turns every trip into a cherished story.</p>
				<div class="hero-premium__actions">
					<button class="btn btn--primary btn--lg" data-quiz-open><?php esc_html_e('Plan your trip', 'zhiraf-ufa'); ?></button>
				</div>
			</div>
			
			<div class="hero-premium__collage reveal reveal-delay-1">
				<div class="hero-collage__img hero-collage__img--1">
					<?php zhiraf_ufa_img( 'hero-turkey.jpg', 'Turkey', '', false, true ); ?>
				</div>
				<div class="hero-collage__img hero-collage__img--2">
					<?php zhiraf_ufa_img( 'hero-uae.jpg', 'UAE', '', false, true ); ?>
				</div>
				<div class="hero-collage__img hero-collage__img--3">
					<?php zhiraf_ufa_img( 'hero-egypt.jpg', 'Egypt', '', false, true ); ?>
				</div>
				
				<!-- Airplane Trajectory SVG -->
				<svg class="hero-collage__flight-path" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10,180 Q100,50 380,80" stroke="var(--color-primary)" stroke-width="2" stroke-dasharray="6,6" fill="none"/>
					<path d="M370,75 L385,81 L370,87 Z" fill="var(--color-primary)" />
				</svg>
			</div>
		</div>
	</section>

	<!-- Destinations with Tabs -->
	<section class="section section--destinations bg-milky">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title">Top Destinations</h2>
				<div class="section-tabs">
					<button class="tab-btn active">Popular</button>
					<button class="tab-btn">Asia</button>
					<button class="tab-btn">Europe</button>
					<button class="tab-btn">Africa & Middle East</button>
					<a href="#" class="tab-link-all">Explore all destinations</a>
				</div>
			</div>
			
			<div class="dest-premium-row">
				<?php foreach ( $dest_all as $index => $dest ) : ?>
					<a href="<?php echo esc_url( $dest['url'] ); ?>" class="dest-premium-card reveal">
						<div class="dest-premium-card__bg">
							<?php zhiraf_ufa_img( $dest['image'], $dest['alt'], 'dest-premium-card__img', true ); ?>
						</div>
						<div class="dest-premium-card__content">
							<h3 class="dest-premium-card__title"><?php echo esc_html( $dest['name'] ); ?></h3>
							<p class="dest-premium-card__subtitle"><?php echo esc_html( $dest['name'] ); ?> · Tours</p>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Editorial Blog/Articles 1 Big + List -->
	<section class="section section--editorial">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title">Latest Stories</h2>
				<a href="#" class="btn btn--outline btn--sm">Read more articles</a>
			</div>
			<div class="editorial-layout">
				<?php 
				$main_post = null;
				$list_posts = [];
				if ( ! empty( $blog_posts ) ) {
					$main_post = $blog_posts[0];
					$list_posts = array_slice($blog_posts, 1, 3);
				} else {
					$fallback = zhiraf_ufa_blog_fallback_stories();
					$main_post = $fallback[0];
					$list_posts = array_slice($fallback, 1, 3);
				}
				?>
				
				<?php if ( $main_post ) : ?>
					<!-- MAIN ARTICLE -->
					<div class="editorial-main reveal">
						<a href="<?php echo is_array($main_post) ? esc_url($main_post['url']) : esc_url( get_permalink( $main_post ) ); ?>" class="editorial-main__link">
							<div class="editorial-main__img-wrap">
								<?php 
								if ( is_array($main_post) ) {
									zhiraf_ufa_img( 'article-main.jpg', $main_post['title'], 'editorial-main__img', true );
								} else {
									$thumb_id = get_post_thumbnail_id( $main_post );
									if ( $thumb_id ) {
										echo wp_get_attachment_image( $thumb_id, 'large', false, array( 'class' => 'editorial-main__img' ) );
									} else {
										zhiraf_ufa_img( 'article-main.jpg', get_the_title( $main_post ), 'editorial-main__img', true );
									}
								}
								?>
							</div>
							<div class="editorial-main__content">
								<span class="editorial-cat">
									<?php echo is_array($main_post) ? esc_html($main_post['category']) : esc_html( zhiraf_ufa_story_category_label( $main_post->ID ) ); ?>
								</span>
								<h3 class="editorial-main__title">
									<?php echo is_array($main_post) ? esc_html($main_post['title']) : esc_html( get_the_title( $main_post ) ); ?>
								</h3>
								<time class="editorial-date">
									<?php echo is_array($main_post) ? 'Aug 12, 2024' : get_the_date( '', $main_post ); ?> · 4 min read
								</time>
								<p class="editorial-main__excerpt">
									It seems that in California, almost any problem can be solved with a combination of avocados, yoga, and dogs. After all, food, health, and...
								</p>
							</div>
						</a>
					</div>
				<?php endif; ?>

				<!-- LIST ARTICLES -->
				<div class="editorial-list reveal reveal-delay-1">
					<?php foreach ( $list_posts as $i => $post ) : ?>
						<a href="<?php echo is_array($post) ? esc_url($post['url']) : esc_url( get_permalink( $post ) ); ?>" class="editorial-list-item">
							<div class="editorial-list-item__img-wrap">
								<?php 
								$fallback_imgs = ['article-1.jpg', 'article-2.jpg', 'article-3.jpg'];
								if ( is_array($post) ) {
									zhiraf_ufa_img( $fallback_imgs[$i % 3], $post['title'], 'editorial-list-item__img', true );
								} else {
									$thumb_id = get_post_thumbnail_id( $post );
									if ( $thumb_id ) {
										echo wp_get_attachment_image( $thumb_id, 'thumbnail', false, array( 'class' => 'editorial-list-item__img' ) );
									} else {
										zhiraf_ufa_img( $fallback_imgs[$i % 3], get_the_title( $post ), 'editorial-list-item__img', true );
									}
								}
								?>
							</div>
							<div class="editorial-list-item__content">
								<span class="editorial-cat">
									<?php echo is_array($post) ? esc_html($post['category']) : esc_html( zhiraf_ufa_story_category_label( $post->ID ) ); ?>
								</span>
								<h4 class="editorial-list-item__title">
									<?php echo is_array($post) ? esc_html($post['title']) : esc_html( get_the_title( $post ) ); ?>
								</h4>
								<time class="editorial-date">
									<?php echo is_array($post) ? 'Aug '.(15+$i).', 2024' : get_the_date( '', $post ); ?> · 5 min read
								</time>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- Subscribe section on Photo -->
	<section class="section section--subscribe">
		<div class="subscribe-premium__bg">
			<?php zhiraf_ufa_img( 'subscribe-bg.jpg', 'Subscribe', 'subscribe-premium__img', false, true ); ?>
			<div class="subscribe-premium__overlay"></div>
		</div>
		<div class="container reveal">
			<div class="subscribe-premium__content">
				<h2>Get Your Travel Inspiration<br>Straight to Your Inbox</h2>
				<form class="subscribe-premium__form">
					<input type="email" placeholder="Your email address" required class="subscribe-input" />
					<button type="submit" class="btn btn--primary">Subscribe</button>
				</form>
				<p class="subscribe-premium__note">
					<label class="subscribe-agree">
						<input type="checkbox" required checked>
						Согласен с <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'politika-konfidenczialnosti' ) ) ); ?>">политикой конфиденциальности</a> (ФЗ-152)
					</label>
				</p>
			</div>
		</div>
	</section>

</main>
<?php
get_footer();
