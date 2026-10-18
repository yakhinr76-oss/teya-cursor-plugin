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
 * Latest posts for homepage stories block.
 *
 * @return WP_Post[]
 */
function zhiraf_ufa_home_blog_posts() {
	$q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 4,
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
	$slug = 'kak-kupit-tur-v-ufe';
	$post = get_page_by_path( $slug, OBJECT, 'post' );
	if ( $post && 'publish' === $post->post_status ) {
		$excerpt = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 28 );
		return array(
			'title'    => get_the_title( $post ),
			'excerpt'  => $excerpt,
			'url'      => get_permalink( $post ),
			'category' => zhiraf_ufa_story_category_label( $post->ID ),
			'image'    => '',
			'post_id'  => $post->ID,
		);
	}
	return array(
		'title'    => __( 'Как купить тур в Уфе — пошаговая инструкция', 'zhiraf-ufa' ),
		'excerpt'  => __( 'Разбираем этапы: консультация, выбор тура, договор и оплата. Турагентство «Жираф», вылет из Уфы.', 'zhiraf-ufa' ),
		'url'      => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
		'category' => __( 'Гид', 'zhiraf-ufa' ),
		'image'    => 'blog-cover-01.jpg',
		'post_id'  => 0,
	);
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
			'q' => __( 'Как купить тур через «Жираф»?', 'zhiraf-ufa' ),
			'a' => __( 'Оставьте заявку или пройдите квиз — менеджер свяжется, предложит 2–4 варианта, поможет с договором и оплатой по правилам туроператора.', 'zhiraf-ufa' ),
			'link' => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
		),
		array(
			'q' => __( 'Сколько стоит тур из Уфы?', 'zhiraf-ufa' ),
			'a' => __( 'Стоимость зависит от дат, отеля и оператора. Мы считаем пакет под ваш запрос — без фиктивных цен «от» на сайте.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Чем агентство отличается от агрегатора?', 'zhiraf-ufa' ),
			'a' => __( 'Мы подбираем среди операторов и сопровождаем в Уфе: офис, договор, ответы по документам. Вы не сравниваете десятки вкладок в одиночку.', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Что входит в «всё включено»?', 'zhiraf-ufa' ),
			'a' => __( 'Обычно перелёт, проживание и питание по коду отеля (AI/UAI и др.) — детали в договоре. Разбираем в статье без обещаний «как на картинке».', 'zhiraf-ufa' ),
			'link' => home_url( '/blog/chto-vhodit-v-tur-vse-vklyucheno/' ),
		),
		array(
			'q' => __( 'Какие документы нужны для поездки?', 'zhiraf-ufa' ),
			'a' => __( 'Зависит от страны и срока поездки. Даём чек-лист в блоге; это не юридическая консультация — уточняйте у менеджера.', 'zhiraf-ufa' ),
			'link' => home_url( '/blog/dokumenty-dlya-zagranicy-iz-ufy/' ),
		),
		array(
			'q' => __( 'Будут ли навязчивые звонки?', 'zhiraf-ufa' ),
			'a' => __( 'Связываемся по вашей заявке. Маркетинговые рассылки и звонки — только с отдельного согласия (38-ФЗ).', 'zhiraf-ufa' ),
		),
		array(
			'q' => __( 'Где офис и часы работы?', 'zhiraf-ufa' ),
			'a' => __( 'ул. Юрия Гагарина, 74/1, офис 3, Уфа. Пн–Пт 10:00–19:00, Сб 11:00–15:00.', 'zhiraf-ufa' ),
			'link' => home_url( '/kontakty/' ),
		),
		array(
			'q' => __( 'Кому мы не подойдём?', 'zhiraf-ufa' ),
			'a' => __( 'Если нужен мгновенный прайс-лист без диалога с менеджером — лучше агрегатор. Мы работаем через подбор и договор.', 'zhiraf-ufa' ),
		),
	);
}

function zhiraf_ufa_blog_fallback_stories() {
	return array(
		array(
			'title'    => __( 'Как купить тур в Уфе: от заявки до вылета', 'zhiraf-ufa' ),
			'category' => __( 'Гид', 'zhiraf-ufa' ),
			'excerpt'  => __( 'Разбираем этапы: консультация, выбор тура, договор и оплата. Турагентство «Жираф», вылет из Уфы.', 'zhiraf-ufa' ),
			'date'     => '',
			'url'      => home_url( '/blog/kak-kupit-tur-v-ufe/' ),
			'image'    => 'blog-featured.jpg',
		),
		array(
			'title'    => __( 'Что входит в тур «всё включено»', 'zhiraf-ufa' ),
			'category' => __( 'Питание', 'zhiraf-ufa' ),
			'date'     => '',
			'url'      => home_url( '/blog/' ),
			'image'    => 'blog-thumb-1.jpg',
		),
		array(
			'title'    => __( 'Документы для поездки за границу', 'zhiraf-ufa' ),
			'category' => __( 'Документы', 'zhiraf-ufa' ),
			'date'     => '',
			'url'      => home_url( '/blog/' ),
			'image'    => 'blog-thumb-2.jpg',
		),
		array(
			'title'    => __( 'Туры с детьми из Уфы', 'zhiraf-ufa' ),
			'category' => __( 'Семья', 'zhiraf-ufa' ),
			'date'     => '',
			'url'      => home_url( '/blog/' ),
			'image'    => 'blog-thumb-3.jpg',
		),
	);
}
