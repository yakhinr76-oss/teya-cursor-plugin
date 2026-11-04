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
			'slug'    => 'turciya',
			'name'    => __( 'Турция', 'zhiraf-ufa' ),
			'image'   => 'country-turciya.jpg',
			'alt'     => __( 'Турция — отдых на море', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/turciya/' ),
			'teaser'  => __( 'Анталья и Алания: отели 4–5★, All Inclusive, семейные курорты.', 'zhiraf-ufa' ),
			'catalog' => true,
		),
		array(
			'slug'    => 'oae',
			'name'    => __( 'ОАЭ', 'zhiraf-ufa' ),
			'image'   => 'hero-uae.jpg',
			'alt'     => __( 'ОАЭ — пляж и Дубай', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/oae/' ),
			'teaser'  => __( 'Дубай, Шарджа, Фуджейра: пляж, шопинг и комфорт круглый год.', 'zhiraf-ufa' ),
			'catalog' => true,
		),
		array(
			'slug'    => 'egipet',
			'name'    => __( 'Египет', 'zhiraf-ufa' ),
			'image'   => 'country-egipet.jpg',
			'alt'     => __( 'Египет — курорты Красного моря', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/egipet/' ),
			'teaser'  => __( 'Хургада и Шарм-эль-Шейх: тёплое море, рифы, отели с аквапарками.', 'zhiraf-ufa' ),
			'catalog' => true,
		),
		array(
			'slug'    => 'tailand',
			'name'    => __( 'Таиланд', 'zhiraf-ufa' ),
			'image'   => 'country-tailand.jpg',
			'alt'     => __( 'Таиланд — тропический отдых', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/tailand/' ),
			'teaser'  => __( 'Пхукет и Паттайя: тропики, экскурсии, отдых на двоих и с детьми.', 'zhiraf-ufa' ),
			'catalog' => true,
		),
		array(
			'slug'    => 'vietnam',
			'name'    => __( 'Вьетнам', 'zhiraf-ufa' ),
			'image'   => 'resort-vietnam-phu-quoc-2.jpg',
			'alt'     => __( 'Вьетнам — пляж Фукуок', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/vietnam/' ),
			'teaser'  => __( 'Нячанг и Фукуок: без визы до 45 дней, пляж и SPA-курорты.', 'zhiraf-ufa' ),
			'catalog' => true,
		),
		array(
			'slug'    => 'kitaj',
			'name'    => __( 'Китай (Хайнань)', 'zhiraf-ufa' ),
			'image'   => 'country-kitaj.jpg',
			'alt'     => __( 'Китай — пляжный отдых на Хайнане', 'zhiraf-ufa' ),
			'url'     => home_url( '/tour/kitaj/' ),
			'teaser'  => __( 'Остров Санья: белый песок, тропики и курорты у Южно-Китайского моря.', 'zhiraf-ufa' ),
			'catalog' => true,
		),
	);
}

/**
 * Russia & Abkhazia — domestic directions (no BG «from» price on hub card).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_domestic_catalog_destinations() {
	if ( ! function_exists( 'zhiraf_ufa_get_destination_landing' ) ) {
		return array();
	}
	$rows = array(
		array(
			'slug'         => 'rossiya',
			'image'        => 'hero-collage-1.jpg',
			'teaser'       => __( 'Сочи, Красная Поляна, Кавминводы и Калининград — пакет с перелётом или только отель.', 'zhiraf-ufa' ),
			'flight_label' => __( 'паспорт РФ', 'zhiraf-ufa' ),
		),
		array(
			'slug'         => 'abkhaziya',
			'image'        => 'alt-armeniya-3.jpg',
			'teaser'       => __( 'Гагра и Пицунда: рубли, въезд по паспорту РФ, трансфер из аэропорта Адлер.', 'zhiraf-ufa' ),
			'flight_label' => __( 'трансфер из Адлера', 'zhiraf-ufa' ),
		),
	);
	$out = array();
	foreach ( $rows as $row ) {
		$landing = zhiraf_ufa_get_destination_landing( $row['slug'] );
		if ( ! $landing ) {
			continue;
		}
		$out[] = array(
			'slug'         => $row['slug'],
			'name'         => ! empty( $landing['name'] ) ? $landing['name'] : $row['slug'],
			'image'        => $row['image'],
			'alt'          => ! empty( $landing['image_alt'] ) ? $landing['image_alt'] : ( ! empty( $landing['name'] ) ? $landing['name'] : $row['slug'] ),
			'url'          => home_url( '/tour/' . $row['slug'] . '/' ),
			'teaser'       => $row['teaser'],
			'catalog'      => true,
			'domestic'     => true,
			'flight_label' => $row['flight_label'],
		);
	}
	return $out;
}

/**
 * Destinations shown on /tour/ catalog hub (priced cards).
 *
 * @return array<int,array<string,mixed>>
 */
function zhiraf_ufa_catalog_hub_destinations() {
	$items = array();
	foreach ( zhiraf_ufa_destinations() as $dest ) {
		if ( empty( $dest['catalog'] ) ) {
			continue;
		}
		$items[] = $dest;
	}
	return array_merge( $items, zhiraf_ufa_domestic_catalog_destinations() );
}

/**
 * Hero collage tiles on /tour/ with sell badges (price + flight).
 *
 * @return array<int,array{slug:string,image:string,alt:string,price_badge:string,flight_badge:string}>
 */
function zhiraf_ufa_catalog_hero_collage_items() {
	$slugs = array( 'oae', 'turciya', 'tailand' );
	$by_slug = array();
	foreach ( zhiraf_ufa_destinations() as $dest ) {
		$by_slug[ $dest['slug'] ] = $dest;
	}
	$image_map = array(
		'oae'     => array( 'hero-uae.jpg', __( 'ОАЭ — пляжный отдых', 'zhiraf-ufa' ) ),
		'turciya' => array( 'hero-turkey.jpg', __( 'Турция — море и All Inclusive', 'zhiraf-ufa' ) ),
		'tailand' => array( 'hero-thailand.jpg', __( 'Таиланд — тропики', 'zhiraf-ufa' ) ),
	);
	$items     = array();
	$modifiers = array( '--1', '--2', '--3' );
	foreach ( $slugs as $i => $slug ) {
		if ( empty( $image_map[ $slug ] ) ) {
			continue;
		}
		$dest_row = isset( $by_slug[ $slug ] ) ? $by_slug[ $slug ] : array( 'name' => $slug );
		$name     = ! empty( $dest_row['name'] ) ? (string) $dest_row['name'] : $slug;
		$min      = function_exists( 'zhiraf_ufa_get_country_min_tour' ) ? zhiraf_ufa_get_country_min_tour( $slug ) : null;
		$flight   = function_exists( 'zhiraf_ufa_tour_flight_info' )
			? zhiraf_ufa_tour_flight_info( $min, 'Уфа' )
			: array( 'label' => __( 'вылет из Уфы', 'zhiraf-ufa' ) );
		$price_badge = '';
		if ( $min && ! empty( $min['price_rur'] ) ) {
			$thousands = (int) round( (int) $min['price_rur'] / 1000 );
			$price_badge = sprintf(
				/* translators: 1: country name, 2: price in thousands */
				__( '%1$s от %2$s т.р.', 'zhiraf-ufa' ),
				$name,
				number_format( $thousands, 0, '', ' ' )
			);
		}
		$flight_badge = ! empty( $flight['label'] ) ? (string) $flight['label'] : __( 'Прямой рейс из Уфы', 'zhiraf-ufa' );
		if ( false === mb_stripos( $flight_badge, 'уф', 0, 'UTF-8' ) ) {
			$flight_badge = __( 'Прямой рейс из Уфы', 'zhiraf-ufa' );
		} elseif ( 'вылет из Уфы' === $flight_badge ) {
			$flight_badge = __( 'Прямой рейс из Уфы', 'zhiraf-ufa' );
		}
		$items[] = array(
			'slug'          => $slug,
			'image'         => $image_map[ $slug ][0],
			'alt'           => $image_map[ $slug ][1],
			'price_badge'   => $price_badge,
			'flight_badge'  => $flight_badge,
			'modifier'      => isset( $modifiers[ $i ] ) ? $modifiers[ $i ] : '',
		);
	}
	return $items;
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
				__( 'Отели с детскими клубами и удобным перелётом из Уфы', 'zhiraf-ufa' ),
				__( 'Прозрачный состав пакета без сюрпризов по питанию и трансферу', 'zhiraf-ufa' ),
				__( 'Менеджер на связи до вылета и в поездке', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'    => 'couple',
			'label' => __( 'Пара', 'zhiraf-ufa' ),
			'cta'   => __( 'Подобрать тур для двоих', 'zhiraf-ufa' ),
			'link'  => home_url( '/tour/turciya/' ),
			'items' => array(
				__( 'Сравним 2–4 варианта вместо десятков вкладок', 'zhiraf-ufa' ),
				__( 'Честно расскажем об условиях отмены до договора', 'zhiraf-ufa' ),
				__( 'Подберём даты и отель под ваш ритм отдыха', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'    => 'cautious',
			'label' => __( 'Первая заграница', 'zhiraf-ufa' ),
			'cta'   => __( 'Разобраться с менеджером', 'zhiraf-ufa' ),
			'link'  => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
			'items' => array(
				__( 'Пошагово объясним документы и сроки оформления', 'zhiraf-ufa' ),
				__( 'Оформление договора в офисе на Гагарина, 74/1', 'zhiraf-ufa' ),
				__( 'Связь только по вашей заявке — без лишних обзвонов', 'zhiraf-ufa' ),
			),
		),
		array(
			'id'    => 'experienced',
			'label' => __( 'Опытные путешественники', 'zhiraf-ufa' ),
			'cta'   => __( 'Рассчитать сложный маршрут', 'zhiraf-ufa' ),
			'link'  => home_url( '/o-nas/' ),
			'items' => array(
				__( 'Учтём нестандартные маршруты и пожелания по отелю', 'zhiraf-ufa' ),
				__( 'Работаем с проверенными туроператорами', 'zhiraf-ufa' ),
				__( 'Команда в Уфе с 2011 года — детали обсудим лично', 'zhiraf-ufa' ),
			),
		),
	);
}

/**
 * Team roster (homepage teaser + «О нас»).
 *
 * @return array<int,array{name:string,role:string,specialty:string,desc:string,desc_teaser:string,image:string,whatsapp_prefill?:string,messenger_label?:string,telegram_url?:string}>
 */
function zhiraf_ufa_team_roster() {
	$members = array(
		array(
			'name'              => __( 'Рафаэль', 'zhiraf-ufa' ),
			'role'              => __( 'Директор', 'zhiraf-ufa' ),
			'specialty'         => __( 'Договоры по 132-ФЗ, партнёры-туроператоры, личный контроль сервиса', 'zhiraf-ufa' ),
			'desc_teaser'       => __( 'Контроль качества сервиса, договоры и партнёрство с туроператорами.', 'zhiraf-ufa' ),
			'desc'              => __( 'Организация работы агентства, контроль качества сервиса, юридическое сопровождение договоров и стратегическое партнёрство с туроператорами.', 'zhiraf-ufa' ),
			'image'             => 'team-rafael.jpg',
			'whatsapp_prefill'  => __( 'Здравствуйте! Хочу задать вопрос руководителю турагентства «Жираф».', 'zhiraf-ufa' ),
			'messenger_label'   => __( 'WhatsApp руководителю', 'zhiraf-ufa' ),
		),
		array(
			'name'              => __( 'Лиана', 'zhiraf-ufa' ),
			'role'              => __( 'Менеджер по туризму', 'zhiraf-ufa' ),
			'specialty'         => __( 'Турция и Египет, All Inclusive, семейный отдых с детьми', 'zhiraf-ufa' ),
			'desc_teaser'       => __( 'Турция и Египет, All Inclusive, семейный отдых с детьми.', 'zhiraf-ufa' ),
			'desc'              => __( 'Специализируется на пляжных направлениях Средиземного и Красного морей (Турция, Египет), подборе отелей All Inclusive и турах для семей с детьми.', 'zhiraf-ufa' ),
			'image'             => 'team-liana.jpg',
			'whatsapp_prefill'  => __( 'Здравствуйте! Хочу подобрать тур с Лианой (Турция / Египет).', 'zhiraf-ufa' ),
			'messenger_label'   => __( 'Написать в WhatsApp', 'zhiraf-ufa' ),
			'hide_telegram'     => true,
		),
		array(
			'name'              => __( 'Алина', 'zhiraf-ufa' ),
			'role'              => __( 'Менеджер по туризму', 'zhiraf-ufa' ),
			'specialty'         => __( 'ОАЭ, Таиланд и Вьетнам — стыковки из Уфы, визы, инспекции отелей', 'zhiraf-ufa' ),
			'desc_teaser'       => __( 'Таиланд, Вьетнам, ОАЭ, стыковочные перелёты и визовые правила.', 'zhiraf-ufa' ),
			'desc'              => __( 'Эксперт по направлениям Азии и Ближнего Востока (Таиланд, Вьетнам, ОАЭ), визовым правилам, стыковочным авиаперелётам и экскурсионным турам.', 'zhiraf-ufa' ),
			'image'             => 'team-alina.jpg',
			'whatsapp_prefill'  => __( 'Здравствуйте! Хочу подобрать тур с Алиной (ОАЭ / Азия).', 'zhiraf-ufa' ),
			'messenger_label'   => __( 'Написать в WhatsApp', 'zhiraf-ufa' ),
			'hide_telegram'     => true,
		),
	);

	return apply_filters( 'zhiraf_ufa_team_roster', $members );
}

/**
 * Homepage team teaser (2–3 experts).
 *
 * @return array<int,array{name:string,role:string,desc:string,image:string}>
 */
function zhiraf_ufa_home_team_teaser() {
	$teaser = array();
	foreach ( zhiraf_ufa_team_roster() as $member ) {
		$teaser[] = array(
			'name'  => $member['name'],
			'role'  => $member['role'],
			'desc'  => $member['desc_teaser'],
			'image' => $member['image'],
		);
	}

	return apply_filters( 'zhiraf_ufa_home_team_teaser', $teaser );
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
			'a' => __( 'Пройдите короткий 4-шаговый квиз на сайте или оставьте телефон. Менеджер уточнит состав семьи, предпочтения по перелёту и курорту и подготовит 2–4 проверенных варианта с актуальными ценами.', 'zhiraf-ufa' ),
			'link' => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
		),
		array(
			'q' => __( 'Откуда на сайте цены «от»?', 'zhiraf-ufa' ),
			'a' => __( 'Это минимальные цены туроператора «Библио-Глобус» за 2 взрослых на дату, указанную рядом с ценой. Тарифы и места меняются в реальном времени, поэтому окончательную стоимость фиксируем в официальном договоре.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Есть ли прямые рейсы из Уфы?', 'zhiraf-ufa' ),
			'a' => __( 'По данным туроператора «Библио-Глобус», в Турцию, Египет и ОАЭ в зависимости от даты есть туры как с прямым рейсом, так и со стыковкой в Москве. Тип перелёта указан на каждой карточке тура.', 'zhiraf-ufa' ),
			'link' => home_url( '/tour/' ),
		),
		array(
			'q' => __( 'Как организован перелёт в Таиланд и Вьетнам из Уфы?', 'zhiraf-ufa' ),
			'a' => __( 'Сейчас туроператор «Библио-Глобус» формирует маршруты на Пхукет, в Паттайю, Нячанг и на Фукуок с комфортной пересадкой в Москве. Перелёт входит в пакетный тур — поможем подобрать рейсы с удобным временем стыковки.', 'zhiraf-ufa' ),
			'link' => home_url( '/tour/tailand/' ),
		),
		array(
			'q' => __( 'Что входит в классический тур «всё включено»?', 'zhiraf-ufa' ),
			'a' => __( 'Пакетный тур включает авиаперелёт (багаж — в зависимости от тарифа, указано на карточке), групповой трансфер, проживание, питание по системе AI/UAI и медицинскую страховку.', 'zhiraf-ufa' ),
			'link' => home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ),
		),
		array(
			'q' => __( 'Как оформляется официальный договор и оплата тура?', 'zhiraf-ufa' ),
			'a' => __( 'Заключаем официальный договор по 132-ФЗ в офисе на ул. Гагарина, 74/1 или онлайн. Оплата принимается по безналичному расчёту или эквайрингу с выдачей фискального чека.', 'zhiraf-ufa' ),
			'link' => home_url( '/kontakty/' ),
		),
		array(
			'q' => __( 'Какие документы требуются для выезда за границу из Уфы?', 'zhiraf-ufa' ),
			'a' => __( 'Действующий загранпаспорт (от 120 дней для Турции и от 6 месяцев для ОАЭ, Египта, Таиланда и Вьетнама). Для детей до 18 лет нужен свой загранпаспорт и свидетельство о рождении.', 'zhiraf-ufa' ),
			'link' => home_url( '/blog/dokumenty-dlya-zagranicy-iz-ufy/' ),
		),
		array(
			'q' => __( 'Будут ли мне звонить с навязчивой рекламой после заявки?', 'zhiraf-ufa' ),
			'a' => __( 'Нет. Мы связываемся только по вашей заявке. Строго соблюдаем законы 152-ФЗ и 38-ФЗ — никаких спам-рассылок и навязчивых предложений.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * About page FAQ items (dossier §10).
 *
 * @return array<int,array{q:string,a:string}>
 */
function zhiraf_ufa_about_faq_items() {
	return array(
		array(
			'q' => __( 'Где находится офис «Жираф» в Уфе?', 'zhiraf-ufa' ),
			'a' => __( 'Мы в офисе 3 по адресу ул. Юрия Гагарина, 74/1 (450068). Можно заранее договориться о визите — обсудим тур за чашкой кофе или оформим договор на месте.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Как давно работает турагентство «Жираф» в Уфе?', 'zhiraf-ufa' ),
			'a' => __( 'ООО «Жираф» непрерывно работает на туристическом рынке Уфы с апреля 2011 года. Наше агентство внесено в Единый федеральный реестр турагентств под номером РТА 0012822.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Безопасна ли онлайн-оплата тура?', 'zhiraf-ufa' ),
			'a' => __( 'Да. Сначала заключаем официальный договор по 132-ФЗ, затем оплата по защищённой банковской ссылке или в офисе с выдачей фискального чека. Никаких переводов «на карту физлица» без документов.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Чем покупка в «Жираф» отличается от самостоятельного поиска на агрегаторах?', 'zhiraf-ufa' ),
			'a' => __( 'На онлайн-агрегаторах предварительные цены нередко меняются при переходе к оплате из-за скрытых доплат за багаж или топливных сборов. Мы проводим ручную верификацию каждого тура, проверяем реальный статус рейсов из Уфы, предупреждаем о нюансах отеля и оформляем официальный договор с защитой прав туриста.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Берёте ли вы комиссию за подбор тура?', 'zhiraf-ufa' ),
			'a' => __( 'Консультация и подбор тура в нашем агентстве бесплатны. Стоимость пакетного тура для туриста строго соответствует официальным тарифам туроператоров без скрытых наценок.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Можно ли оформить тур дистанционно без визита в офис?', 'zhiraf-ufa' ),
			'a' => __( 'Да, мы работаем как в уютном офисе на ул. Гагарина, 74/1, так и полностью дистанционно. Договор заключается в электронном виде, оплата производится по защищённой банковской ссылке, а билеты, ваучеры и страховки отправляются в мессенджер и на email.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'С какими туроператорами сотрудничает ООО «Жираф»?', 'zhiraf-ufa' ),
			'a' => __( 'Мы сотрудничаем только с надёжными туроператорами, включёнными в Единый федеральный реестр туроператоров РФ и имеющими подтверждённое финансовое обеспечение и фонды персональной ответственности в ассоциации «Турпомощь».', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Что делать, если во время отдыха возникли непредвиденные вопросы?', 'zhiraf-ufa' ),
			'a' => __( 'Ваш персональный менеджер остаётся на связи на протяжении всего путешествия. Мы помогаем решать вопросы с отельным гидом, трансфером, страховой компанией и авиаперевозчиком.', 'zhiraf-ufa' ),
		),
	);
}

/**
 * Catalog page FAQ items (dossier §10).
 *
 * @return array<int,array{q:string,a:string}>
 */
function zhiraf_ufa_catalog_faq_items() {
	return array(
		array(
			'q' => __( 'Есть ли вылет из Уфы во все страны каталога?', 'zhiraf-ufa' ),
			'a' => __( 'Нет. Турция, Египет, ОАЭ, Таиланд и Вьетнам — с вылетом из Уфы (прямой рейс или стыковка в Москве, указано на карточке тура). Китай, Индия, Шри-Ланка, Индонезия, Мальдивы, Сейшелы, Узбекистан и Армения — цены с вылетом из Москвы, перелёт Уфа — Москва в них не входит.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Можно ли комбинировать отель и перелёт?', 'zhiraf-ufa' ),
			'a' => __( 'Цены на сайте — пакетные туры туроператора «Библио-Глобус», включающие авиаперелёт, проживание, трансфер и страховку. Если вам требуется нестандартный маршрут или индивидуальная комбинация, мы рассчитаем её по запросу.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Публикуете ли вы горящие туры?', 'zhiraf-ufa' ),
			'a' => __( 'Да, в специальном разделе горящих туров публикуем минимальные цены «от» туроператора «Библио-Глобус» с датой, на которую они актуальны. Наличие мест менеджер проверяет в реальном времени при бронировании.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Куда чаще всего летают на отдых с детьми из Уфы?', 'zhiraf-ufa' ),
			'a' => __( 'Наиболее популярные семейные направления с вылетом из Уфы — Турция (Анталья, Сиде, Белек с песчаными пляжами и All Inclusive), ОАЭ (Дубай и Рас-эль-Хайма) и Египет (Хургада с пологим песчаным входом в море).', 'zhiraf-ufa' ),
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
