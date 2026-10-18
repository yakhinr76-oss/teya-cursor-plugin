<?php
/**
 * Theme asset helpers and media map.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns theme image URL from assets/images or media map.
 *
 * @param string $filename Basename in assets/images.
 * @param string $alt      Alt text default.
 * @return array{url:string,alt:string,width:int,height:int}
 */
function zhiraf_ufa_theme_image( $filename, $alt = '' ) {
	$path = ZHIRAF_UFA_DIR . '/assets/images/' . ltrim( $filename, '/' );
	$url  = ZHIRAF_UFA_URI . '/assets/images/' . ltrim( $filename, '/' );

	$width  = 800;
	$height = 600;
	if ( is_readable( $path ) ) {
		$size = @getimagesize( $path );
		if ( is_array( $size ) ) {
			$width  = (int) $size[0];
			$height = (int) $size[1];
		}
	}

	return array(
		'url'    => $url,
		'alt'    => $alt,
		'width'  => $width,
		'height' => $height,
	);
}

/**
 * Echo responsive img tag for theme asset.
 *
 * @param string $filename Filename.
 * @param string $alt      Alt.
 * @param string $class    CSS class.
 * @param bool   $lazy     Lazy load.
 * @param bool   $priority High priority LCP.
 */
function zhiraf_ufa_img( $filename, $alt, $class = '', $lazy = true, $priority = false ) {
	$img = zhiraf_ufa_theme_image( $filename, $alt );
	$attrs = array(
		'src'    => $img['url'],
		'alt'    => $img['alt'],
		'width'  => $img['width'],
		'height' => $img['height'],
		'class'  => $class,
	);
	if ( $lazy && ! $priority ) {
		$attrs['loading'] = 'lazy';
	}
	if ( $priority ) {
		$attrs['loading']       = 'eager';
		$attrs['fetchpriority'] = 'high';
	}
	$out = '<img';
	foreach ( $attrs as $k => $v ) {
		if ( '' === $v && 'class' === $k ) {
			continue;
		}
		$out .= sprintf( ' %s="%s"', esc_attr( $k ), esc_attr( $v ) );
	}
	$out .= ' />';
	echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
}

/**
 * Destination cards data.
 *
 * @return array<int,array<string,string>>
 */
/**
 * Offer hub links.
 *
 * @return array<int,array{label:string,url:string}>
 */
function zhiraf_ufa_offer_hub_links() {
	return array(
		array(
			'label' => __( 'Горящие туры', 'zhiraf-ufa' ),
			'url'   => home_url( '/goryashchie-tury/' ),
		),
		array(
			'label' => __( 'Всё включено', 'zhiraf-ufa' ),
			'url'   => home_url( '/vse-vklyucheno/' ),
		),
	);
}

function zhiraf_ufa_destinations() {
	return array(
		array(
			'slug'  => 'turciya',
			'name'  => __( 'Турция', 'zhiraf-ufa' ),
			'image' => 'dest-turkey.jpg',
			'alt'   => __( 'Турция — отдых на море', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/turciya/' ),
		),
		array(
			'slug'  => 'oae',
			'name'  => __( 'ОАЭ', 'zhiraf-ufa' ),
			'image' => 'dest-uae.jpg',
			'alt'   => __( 'ОАЭ — город и пляж', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/oae/' ),
		),
		array(
			'slug'  => 'egipet',
			'name'  => __( 'Египет', 'zhiraf-ufa' ),
			'image' => 'dest-egypt.jpg',
			'alt'   => __( 'Египет — курорты Красного моря', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/egipet/' ),
		),
		array(
			'slug'  => 'tailand',
			'name'  => __( 'Таиланд', 'zhiraf-ufa' ),
			'image' => 'dest-thailand.jpg',
			'alt'   => __( 'Таиланд — тропический отдых', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/tailand/' ),
		),
		array(
			'slug'  => 'vietnam',
			'name'  => __( 'Вьетнам', 'zhiraf-ufa' ),
			'image' => 'dest-vietnam.jpg',
			'alt'   => __( 'Вьетнам — пляжи и природа', 'zhiraf-ufa' ),
			'url'   => home_url( '/tour/vietnam/' ),
		),
	);
}

/**
 * Reading time for posts.
 *
 * @param int|null $post_id Post ID.
 * @return string
 */
function zhiraf_ufa_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
	$min     = max( 1, (int) ceil( $words / 200 ) );
	return sprintf(
		/* translators: %d: minutes */
		_n( '%d мин чтения', '%d мин чтения', $min, 'zhiraf-ufa' ),
		$min
	);
}

/**
 * Whether to show verified reviews block (off until client provides sources).
 *
 * @return bool
 */
function zhiraf_ufa_show_reviews_block() {
	return (bool) get_theme_mod( 'zhiraf_show_reviews', false );
}

/**
 * Path to Excalibur articles in repo deliverables (dev); empty on production FTP-only.
 *
 * @return string
 */
function zhiraf_ufa_excalibur_articles_base() {
	$path = dirname( ZHIRAF_UFA_DIR, 2 ) . '/excalibur-articles/articles';
	return is_dir( $path ) ? $path : '';
}

/**
 * Static editorial fields per BT article (fallback when JSON not on disk).
 *
 * @return array<string,array<string,mixed>>
 */
function zhiraf_ufa_excalibur_article_static_catalog() {
	return array(
		'kak-kupit-tur-v-ufe'              => array(
			'topic_id'   => 'BT-01',
			'category'   => __( 'Гид', 'zhiraf-ufa' ),
			'country'    => '',
			'char_count' => 8925,
			'title'      => __( 'Как купить тур в Уфе — пошаговая инструкция', 'zhiraf-ufa' ),
			'excerpt'    => __( 'Разбираем этапы: консультация, выбор тура, договор и оплата. Турагентство «Жираф», вылет из Уфы.', 'zhiraf-ufa' ),
			'image'      => 'blog-cover-01.jpg',
		),
		'chto-vhodit-v-tur-vse-vklyucheno' => array(
			'topic_id'   => 'BT-02',
			'category'   => __( 'Питание', 'zhiraf-ufa' ),
			'country'    => '',
			'char_count' => 6033,
			'title'      => __( 'Что входит в «всё включено» в туре', 'zhiraf-ufa' ),
			'excerpt'    => __( 'Питание, перелёт, трансфер и отель — что обычно входит в пакетный тур из Уфы и на что смотреть при выборе.', 'zhiraf-ufa' ),
			'image'      => 'blog-cover-02.jpg',
		),
		'dokumenty-dlya-zagranicy-iz-ufy'  => array(
			'topic_id'   => 'BT-03',
			'category'   => __( 'Документы', 'zhiraf-ufa' ),
			'country'    => '',
			'char_count' => 5794,
			'title'      => __( 'Документы для заграницы — чек-лист', 'zhiraf-ufa' ),
			'excerpt'    => __( 'Загранпаспорт, страховка, билеты и дети — базовый список перед туром с вылетом из Уфы. Не юридическая консультация.', 'zhiraf-ufa' ),
			'image'      => 'blog-cover-03.jpg',
		),
		'tury-s-detmi-iz-ufy'              => array(
			'topic_id'   => 'BT-04',
			'category'   => __( 'Семья', 'zhiraf-ufa' ),
			'country'    => __( 'Турция', 'zhiraf-ufa' ),
			'char_count' => 4957,
			'title'      => __( 'Туры с детьми из Уфы — советы по отелю', 'zhiraf-ufa' ),
			'excerpt'    => __( 'Перелёт, питание, детская инфраструктура и тихий отдых — критерии подбора для семей из Уфы.', 'zhiraf-ufa' ),
			'image'      => 'blog-cover-04.jpg',
		),
		'strahovka-i-rassrochka-na-tur'    => array(
			'topic_id'   => 'BT-05',
			'category'   => __( 'Страхование', 'zhiraf-ufa' ),
			'country'    => '',
			'char_count' => 4749,
			'title'      => __( 'Страховка и рассрочка на тур', 'zhiraf-ufa' ),
			'excerpt'    => __( 'Какие вопросы задать про полис и оплату частями перед покупкой тура из Уфы. Партнёры — только после подтверждения заказчиком.', 'zhiraf-ufa' ),
			'image'      => 'blog-cover-05.jpg',
		),
		'turciya-ili-egipet-iz-ufy'        => array(
			'topic_id'   => 'BT-06',
			'category'   => __( 'Направления', 'zhiraf-ufa' ),
			'country'    => __( 'Турция · Египет', 'zhiraf-ufa' ),
			'char_count' => 5066,
			'title'      => __( 'Турция или Египет из Уфы — сравнение', 'zhiraf-ufa' ),
			'excerpt'    => __( 'Сравниваем форматы отдыха без цен «от»: перелёт, курорты, сезон и для кого подходит каждое направление.', 'zhiraf-ufa' ),
			'image'      => 'blog-cover-06.jpg',
		),
	);
}

/**
 * Ordered slugs for six Excalibur BT articles.
 *
 * @return string[]
 */
function zhiraf_ufa_excalibur_article_slugs() {
	return array(
		'kak-kupit-tur-v-ufe',
		'chto-vhodit-v-tur-vse-vklyucheno',
		'dokumenty-dlya-zagranicy-iz-ufy',
		'tury-s-detmi-iz-ufy',
		'strahovka-i-rassrochka-na-tur',
		'turciya-ili-egipet-iz-ufy',
	);
}

/**
 * Normalize SEO title from article.meta.json.
 *
 * @param string $title_seo Raw title_seo.
 * @return string
 */
function zhiraf_ufa_excalibur_clean_title( $title_seo ) {
	$title = preg_replace( '/\s*\|\s*Блог.*$/u', '', (string) $title_seo );
	return trim( $title );
}

/**
 * Read minutes from Excalibur char_count.
 *
 * @param int $char_count Characters from meta.
 * @return int
 */
function zhiraf_ufa_read_minutes_from_chars( $char_count ) {
	return max( 1, (int) ceil( (int) $char_count / 1500 ) );
}

/**
 * Human-readable read time label.
 *
 * @param int $minutes Minutes.
 * @return string
 */
function zhiraf_ufa_read_minutes_label( $minutes ) {
	return sprintf(
		/* translators: %d: minutes */
		_n( '%d мин чтения', '%d мин чтения', (int) $minutes, 'zhiraf-ufa' ),
		(int) $minutes
	);
}

/**
 * Six Excalibur demo posts (meta + excerpt + theme cover).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_excalibur_demo_posts() {
	$base    = zhiraf_ufa_excalibur_articles_base();
	$static  = zhiraf_ufa_excalibur_article_static_catalog();
	$posts   = array();

	foreach ( zhiraf_ufa_excalibur_article_slugs() as $slug ) {
		$row = $static[ $slug ];
		$dir = $base ? $base . '/' . $slug : '';

		if ( $dir && is_readable( $dir . '/article.meta.json' ) ) {
			$meta_raw = file_get_contents( $dir . '/article.meta.json' );
			$meta     = json_decode( (string) $meta_raw, true );
			if ( is_array( $meta ) ) {
				if ( ! empty( $meta['topic_id'] ) ) {
					$row['topic_id'] = (string) $meta['topic_id'];
				}
				if ( ! empty( $meta['char_count'] ) ) {
					$row['char_count'] = (int) $meta['char_count'];
				}
				if ( ! empty( $meta['meta_ab']['title_seo'] ) ) {
					$row['title'] = zhiraf_ufa_excalibur_clean_title( $meta['meta_ab']['title_seo'] );
				}
				if ( ! empty( $meta['meta_ab']['description_seo'] ) ) {
					$row['excerpt'] = (string) $meta['meta_ab']['description_seo'];
				}
			}
		}

		if ( $dir && is_readable( $dir . '/excerpt.md' ) ) {
			$excerpt_file = trim( (string) file_get_contents( $dir . '/excerpt.md' ) );
			if ( '' !== $excerpt_file ) {
				$row['excerpt'] = $excerpt_file;
			}
		}

		$wp_post = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $wp_post && 'publish' === $wp_post->post_status ) {
			$row['post_id'] = $wp_post->ID;
			$row['title']   = get_the_title( $wp_post );
			$row['url']     = get_permalink( $wp_post );
			if ( has_excerpt( $wp_post ) ) {
				$row['excerpt'] = get_the_excerpt( $wp_post );
			}
			$row['category'] = zhiraf_ufa_story_category_label( $wp_post->ID );
		} else {
			$row['post_id'] = 0;
			$row['url']     = home_url( '/blog/' . $slug . '/' );
		}

		$row['slug']         = $slug;
		$row['read_minutes'] = zhiraf_ufa_read_minutes_from_chars( (int) $row['char_count'] );
		$posts[]             = $row;
	}

	return $posts;
}

/**
 * Blog card from published WP post.
 *
 * @param WP_Post $post Post.
 * @return array<string,mixed>
 */
function zhiraf_ufa_blog_card_from_post( WP_Post $post ) {
	$slug    = $post->post_name;
	$covers  = zhiraf_ufa_excalibur_article_static_catalog();
	$image   = isset( $covers[ $slug ]['image'] ) ? $covers[ $slug ]['image'] : 'blog-cover-01.jpg';
	$country = isset( $covers[ $slug ]['country'] ) ? $covers[ $slug ]['country'] : '';
	$minutes = max( 1, (int) ceil( str_word_count( wp_strip_all_tags( $post->post_content ) ) / 200 ) );

	return array(
		'slug'          => $slug,
		'title'         => get_the_title( $post ),
		'excerpt'       => has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 28 ),
		'url'           => get_permalink( $post ),
		'category'      => zhiraf_ufa_story_category_label( $post->ID ),
		'country'       => $country,
		'read_minutes'  => $minutes,
		'image'         => $image,
		'post_id'       => $post->ID,
		'topic_id'      => isset( $covers[ $slug ]['topic_id'] ) ? $covers[ $slug ]['topic_id'] : '',
	);
}

/**
 * Merge latest WP posts with Excalibur demo to fill six slots.
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_home_blog_cards_six() {
	$q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 6,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	$cards = array();
	$seen  = array();
	foreach ( $q->posts as $post ) {
		$cards[]           = zhiraf_ufa_blog_card_from_post( $post );
		$seen[ $post->post_name ] = true;
	}

	foreach ( zhiraf_ufa_excalibur_demo_posts() as $demo ) {
		if ( count( $cards ) >= 6 ) {
			break;
		}
		if ( isset( $seen[ $demo['slug'] ] ) ) {
			continue;
		}
		$cards[] = $demo;
	}

	return array_slice( $cards, 0, 6 );
}

/**
 * Homepage Globe Trekker bundle: 1 featured + 3 stack + 2 row2.
 *
 * @return array{featured:array<string,mixed>,stack:array<int,array<string,mixed>>,row2:array<int,array<string,mixed>>}
 */
function zhiraf_ufa_home_globe_blog_bundle() {
	$all     = zhiraf_ufa_home_blog_cards_six();
	$featured = $all[0] ?? zhiraf_ufa_excalibur_demo_posts()[0];
	$rest    = array_slice( $all, 1 );
	$stack   = array_slice( $rest, 0, 3 );
	$row2    = array_slice( $rest, 3, 2 );

	while ( count( $stack ) < 3 ) {
		$stack[] = array(
			'slug'         => '',
			'title'        => '',
			'excerpt'      => '',
			'url'          => home_url( '/blog/' ),
			'category'     => '',
			'country'      => '',
			'read_minutes' => 1,
			'image'        => 'blog-cover-01.jpg',
			'post_id'      => 0,
		);
	}

	return array(
		'featured' => $featured,
		'stack'    => $stack,
		'row2'     => $row2,
	);
}

/**
 * Render blog card image (attachment or theme asset).
 *
 * @param array<string,mixed> $card     Card data.
 * @param string              $class    Img class.
 * @param bool                $lazy     Lazy load.
 * @param bool                $priority LCP priority.
 */
function zhiraf_ufa_blog_card_image( $card, $class, $lazy = true, $priority = false ) {
	if ( ! empty( $card['post_id'] ) ) {
		$thumb_id = get_post_thumbnail_id( (int) $card['post_id'] );
		if ( $thumb_id ) {
			$attrs = array(
				'class' => $class,
			);
			if ( $lazy && ! $priority ) {
				$attrs['loading'] = 'lazy';
			}
			if ( $priority ) {
				$attrs['loading']       = 'eager';
				$attrs['fetchpriority'] = 'high';
			}
			echo wp_get_attachment_image( $thumb_id, 'large', false, $attrs );
			return;
		}
	}
	zhiraf_ufa_img(
		! empty( $card['image'] ) ? $card['image'] : 'blog-cover-01.jpg',
		! empty( $card['title'] ) ? $card['title'] : '',
		$class,
		$lazy,
		$priority
	);
}

/**
 * Blog meta row markup.
 *
 * @param array<string,mixed> $card    Card.
 * @param array<string,mixed> $args    compact, show_date.
 */
function zhiraf_ufa_blog_meta_row( $card, $args = array() ) {
	$compact  = ! empty( $args['compact'] );
	$show_date = ! empty( $args['show_date'] );
	$classes  = 'blog-meta-row' . ( $compact ? ' blog-meta-row--compact' : '' );

	$parts = array();
	if ( ! empty( $card['category'] ) ) {
		$parts[] = '<span class="blog-meta__rubrika">' . esc_html( $card['category'] ) . '</span>';
	}
	if ( ! $compact && ! empty( $card['country'] ) ) {
		$parts[] = '<span class="blog-meta__country">' . esc_html( $card['country'] ) . '</span>';
	}
	if ( ! empty( $card['read_minutes'] ) ) {
		$parts[] = '<span class="blog-meta__read-time">' . esc_html( zhiraf_ufa_read_minutes_label( (int) $card['read_minutes'] ) ) . '</span>';
	}
	if ( $show_date && ! empty( $card['date'] ) ) {
		$parts[] = '<span class="blog-meta__date">' . esc_html( $card['date'] ) . '</span>';
	}

	if ( empty( $parts ) ) {
		return;
	}

	echo '<p class="' . esc_attr( $classes ) . '">';
	$count = count( $parts );
	foreach ( $parts as $i => $part ) {
		echo $part; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in part.
		if ( $i < $count - 1 ) {
			echo '<span class="blog-meta__sep" aria-hidden="true">·</span>';
		}
	}
	echo '</p>';
}

/**
 * Latest posts for homepage stories block.
 *
 * @return WP_Post[]
 */
function zhiraf_ufa_home_blog_posts() {
	$q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 6,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	return $q->posts;
}

/**
 * Category label for story card (first category or default).
 *
 * @param int $post_id Post ID.
 * @return string
 */
function zhiraf_ufa_story_category_label( $post_id ) {
	$cats = get_the_category( $post_id );
	if ( ! empty( $cats ) ) {
		return $cats[0]->name;
	}
	return __( 'Советы', 'zhiraf-ufa' );
}

/**
 * Fallback stories when no WP posts yet.
 *
 * @return array<int,array<string,string>>
 */
/**
 * Homepage blog teaser BT-01 (Excalibur meta + WP post when published).
 *
 * @return array{title:string,excerpt:string,url:string,category:string,image:string}
 */
function zhiraf_ufa_blog_teaser_bt01() {
	$demo = zhiraf_ufa_excalibur_demo_posts();
	return $demo[0];
}

/**
 * Audience segments for homepage tabs (conversion-funnel-map).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_audience_segments() {
	return array(
		array(
			'id'    => 'family',
			'label' => __( 'Семья с детьми', 'zhiraf-ufa' ),
			'cta'   => __( 'Подобрать семейный тур', 'zhiraf-ufa' ),
			'link'  => home_url( '/blog/tury-s-detmi-iz-ufy/' ),
			'items' => array(
				__( 'Подберём отель с детской инфраструктурой и удобным перелётом из Уфы', 'zhiraf-ufa' ),
				__( 'Объясним, что входит в пакет — без сюрпризов по питанию и трансферу', 'zhiraf-ufa' ),
				__( 'Менеджер на связи до вылета и в поездке', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'    => 'couple',
			'label' => __( 'Пара', 'zhiraf-ufa' ),
			'cta'   => __( 'Подобрать тур для двоих', 'zhiraf-ufa' ),
			'link'  => home_url( '/tour/turciya/' ),
			'items' => array(
				__( 'Сравним 2–4 варианта вместо десятков вкладок на агрегаторах', 'zhiraf-ufa' ),
				__( 'Честно расскажем о доплатах и условиях отмены до договора', 'zhiraf-ufa' ),
				__( 'Подберём даты и отель под ваш ритм отдыха', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'    => 'cautious',
			'label' => __( 'Первая заграница', 'zhiraf-ufa' ),
			'cta'   => __( 'Разобраться с менеджером', 'zhiraf-ufa' ),
			'link'  => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
			'items' => array(
				__( 'Пошагово объясним документы и сроки — без юридических гарантий', 'zhiraf-ufa' ),
				__( 'Можно оформить договор в офисе на Гагарина, 74/1', 'zhiraf-ufa' ),
				__( 'Не давим звонками — согласие на маркетинг отдельным чекбоксом', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'    => 'experienced',
			'label' => __( 'Опытные путешественники', 'zhiraf-ufa' ),
			'cta'   => __( 'Описать пожелания в квизе', 'zhiraf-ufa' ),
			'link'  => home_url( '/o-nas/' ),
			'items' => array(
				__( 'Учтём нестандартные маршруты и пожелания по отелю', 'zhiraf-ufa' ),
				__( 'Работаем с проверенными туроператорами — уточним список под запрос', 'zhiraf-ufa' ),
				__( 'Команда в Уфе с 2011 года — можно обсудить детали лично', 'zhiraf-ufa' ),
			),
		),
	);
}

/**
 * Homepage FAQ items (dossier §10).
 *
 * @return array<int,array{q:string,a:string,link?:string}>
 */
function zhiraf_ufa_home_faq_items() {
	return array(
		array(
			'q' => __( 'Как заказать подбор тура в агентстве «Жираф» в Уфе?', 'zhiraf-ufa' ),
			'a' => __( 'Пройдите короткий 4-шаговый квиз на сайте или оставьте контактный телефон. Менеджер уточнит состав семьи, предпочтения по перелёту и курорту, после чего подготовит персональную подборку из 2–4 проверенных вариантов с актуальными ценами.', 'zhiraf-ufa' ),
			'link' => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
		),
		array(
			'q' => __( 'Почему у вас нет фиктивных цен «от 15 000 ₽» на сайте?', 'zhiraf-ufa' ),
			'a' => __( 'Цены на авиабилеты из Уфы и номера в отелях меняются туроператорами в режиме реального времени. Мы принципиально не вводим клиентов в заблуждение нереалистичными суммами «от». Вы получаете точный расчёт с окончательной стоимостью по официальному договору без скрытых доплат.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Чем турагентство «Жираф» отличается от онлайн-агрегаторов?', 'zhiraf-ufa' ),
			'a' => __( 'Агрегаторы оставляют вас один на один со сложными тарифами, невозвратными билетами и скрытыми доплатами за багаж. Мы берём на себя экспертную проверку рейсов из Уфы, помогаем выбрать отель под ваш бюджет, оформляем официальный договор и остаёмся на связи в экстренных ситуациях.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Что входит в классический тур «всё включено»?', 'zhiraf-ufa' ),
			'a' => __( 'Пакетный тур включает авиаперелёт из Уфы и обратно с включённым багажом, групповой трансфер аэропорт — отель — аэропорт, проживание в выбранном номере, трёхразовое питание со снэками и напитками (AI/UAI) и медицинскую страховку.', 'zhiraf-ufa' ),
			'link' => home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ),
		),
		array(
			'q' => __( 'Какие документы требуются для выезда за границу из Уфы?', 'zhiraf-ufa' ),
			'a' => __( 'Главный документ — действующий заграничный паспорт (срок действия от 120 дней для Турции и от 6 месяцев для ОАЭ, Египта, Таиланда и Вьетнама). Для детей до 18 лет требуется собственный загранпаспорт и оригинал свидетельства о рождении.', 'zhiraf-ufa' ),
			'link' => home_url( '/blog/dokumenty-dlya-zagranicy-iz-ufy/' ),
		),
		array(
			'q' => __( 'Будут ли мне звонить с навязчивой рекламой после заявки?', 'zhiraf-ufa' ),
			'a' => __( 'Нет. Мы связываемся только по существу вашей заявки для обсуждения параметров поездки. Мы строго соблюдаем законы 152-ФЗ «О персональных данных» и 38-ФЗ «О рекламе» — никаких спам-рассылок и навязчивых предложений.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Где находится ваш офис в Уфе и как он работает?', 'zhiraf-ufa' ),
			'a' => __( 'Наш офис расположен по адресу: г. Уфа, ул. Юрия Гагарина, 74/1, офис 3. График работы: Понедельник–Пятница с 10:00 до 19:00, Суббота с 11:00 до 15:00, Воскресенье — выходной. Будем рады видеть вас на чашку чая!', 'zhiraf-ufa' ),
			'link' => home_url( '/kontakty/' ),
		),
		array(
			'q' => __( 'Кому не подходит формат работы нашего агентства?', 'zhiraf-ufa' ),
			'a' => __( 'Если вам нужен мгновенный самостоятельный выкуп билетов на чартер без общения со специалистом или консультации по отелю, удобнее воспользоваться агрегаторами. Мы ориентированы на туристов, ценящих персональный подбор, безопасность, юридическую чистоту и поддержку.', 'zhiraf-ufa' ),
		),
	);
}

function zhiraf_ufa_blog_fallback_stories() {
	return zhiraf_ufa_excalibur_demo_posts();
}

/**
 * Editorial plan topics (legacy empty-state helper).
 *
 * @return array<int,array{title:string,note:string,topic:string}>
 */
function zhiraf_ufa_blog_topics() {
	$topics = array();
	foreach ( zhiraf_ufa_excalibur_demo_posts() as $post ) {
		$topics[] = array(
			'title' => $post['title'],
			'note'  => $post['excerpt'],
			'topic' => $post['topic_id'],
		);
	}
	return $topics;
}
