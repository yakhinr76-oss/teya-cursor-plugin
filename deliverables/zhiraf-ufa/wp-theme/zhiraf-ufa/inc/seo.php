<?php
/**
 * SEO meta and JSON-LD.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Canonical public host (see teya-memory/site.inv → project.public_site_url). */
define( 'ZHIRAF_UFA_PUBLIC_SITE_URL', 'https://zhiraf-ufa.ru' );

/**
 * Whether the current request is on staging (b2b-tour.ru).
 *
 * @return bool
 */
function zhiraf_ufa_is_staging_host() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( (string) $_SERVER['HTTP_HOST'] ) : '';
	return ( '' !== $host && false !== strpos( $host, 'b2b-tour.ru' ) );
}

/**
 * Map a staging URL to the public canonical host (path + query preserved).
 *
 * @param string $url Full URL.
 * @return string
 */
function zhiraf_ufa_canonical_public_url( $url ) {
	if ( ! zhiraf_ufa_is_staging_host() || ! $url ) {
		return $url;
	}
	$public = trailingslashit( ZHIRAF_UFA_PUBLIC_SITE_URL );
	$path   = wp_parse_url( $url, PHP_URL_PATH );
	$query  = wp_parse_url( $url, PHP_URL_QUERY );
	if ( ! is_string( $path ) ) {
		$path = '/';
	}
	$out = $public . ltrim( $path, '/' );
	if ( $query ) {
		$out .= '?' . $query;
	}
	return $out;
}

/**
 * Staging: discourage all crawlers (meta + header + robots.txt).
 */
function zhiraf_ufa_staging_noindex_headers() {
	if ( ! zhiraf_ufa_is_staging_host() || headers_sent() ) {
		return;
	}
	header( 'X-Robots-Tag: noindex, nofollow', true );
}
add_action( 'send_headers', 'zhiraf_ufa_staging_noindex_headers', 0 );

/**
 * Staging robots.txt — block entire site.
 *
 * @param string $output Default robots output.
 * @param bool   $public Whether blog_public is true.
 * @return string
 */
function zhiraf_ufa_robots_txt( $output, $public ) {
	if ( zhiraf_ufa_is_staging_host() ) {
		return "User-agent: *\nDisallow: /\n";
	}

	$lines = array(
		'User-agent: *',
		'Allow: /wp-content/uploads/',
		'Allow: /wp-content/themes/zhiraf-ufa/assets/',
		'Disallow: /wp-admin/',
		'Allow: /wp-admin/admin-ajax.php',
		'Disallow: /wp-json/',
		'Disallow: /?s=',
		'Disallow: /search/',
		'',
		'User-agent: Googlebot',
		'Allow: /',
		'',
		'User-agent: Yandex',
		'Allow: /',
		'',
		'User-agent: Bingbot',
		'Allow: /',
		'',
		'User-agent: GPTBot',
		'Allow: /',
		'',
		'User-agent: OAI-SearchBot',
		'Allow: /',
		'',
		'User-agent: ChatGPT-User',
		'Allow: /',
		'',
		'User-agent: ClaudeBot',
		'Allow: /',
		'',
		'User-agent: PerplexityBot',
		'Allow: /',
		'',
		'User-agent: Google-Extended',
		'Allow: /',
		'',
		'User-agent: CCBot',
		'Allow: /',
		'',
		'Sitemap: ' . trailingslashit( ZHIRAF_UFA_PUBLIC_SITE_URL ) . 'sitemap_index.xml',
		'Host: zhiraf-ufa.ru',
	);

	return implode( "\n", $lines ) . "\n";
}
add_filter( 'robots_txt', 'zhiraf_ufa_robots_txt', 10, 2 );

/**
 * Meta descriptions map.
 *
 * @return array<string,string>
 */
function zhiraf_ufa_meta_descriptions() {
	return array(
		'home'     => 'Пакетные туры с вылетом из Уфы: подбор 2–4 вариантов, офис на Гагарина. Квиз за 2 минуты. Турагентство «Жираф» с 2011 года.',
		'o-nas'    => 'История семейного турагентства с 2011 года: команда, ценности, реестр ТА 0012822.',
		'kontakty' => 'Телефон +7 901 810-50-18, email office@zhiraf-ufa.ru, часы работы, карта, реквизиты ООО «Жираф».',
		'tour'              => 'Турция, ОАЭ, Египет, Таиланд и Вьетнам — пакетные туры из Уфы. Подбор без прайс-борда на сайте. Квиз за 2 минуты.',
		'blog'              => 'Как купить тур, документы, «всё включено», отдых с детьми — статьи «Жираф» без рекламных цен «от».',
		'goryashchie-tury'  => 'Актуальные горящие пакеты с вылетом из Уфы — без фиктивных цен «от», заявка и квиз.',
		'vse-vklyucheno'    => 'Пакеты AI/UAI с вылетом из Уфы: что входит в тур — честно, в договоре.',
	);
}

/**
 * SEO meta for current request (destination, offer, resort).
 *
 * @return array{title?:string,description?:string}|null
 */
function zhiraf_ufa_current_landing_seo() {
	$ctx = zhiraf_ufa_current_resort_context();
	if ( $ctx ) {
		$resort = zhiraf_ufa_get_resort_landing( $ctx['country'], $ctx['resort'] );
		if ( $resort ) {
			return array(
				'title'       => $resort['title'],
				'description' => $resort['description'],
			);
		}
	}
	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		$offer = zhiraf_ufa_get_offer_landing( $slug );
		if ( $offer ) {
			return array(
				'title'       => $offer['title'],
				'description' => $offer['description'],
			);
		}
		$dest = zhiraf_ufa_get_destination_landing( $slug );
		if ( $dest && ! empty( $dest['title'] ) ) {
			return array(
				'title'       => $dest['title'],
				'description' => $dest['description'],
			);
		}
	}
	return null;
}

/**
 * Open Graph image URL for the current request.
 *
 * @return string
 */
function zhiraf_ufa_og_image_url() {
	$default = ZHIRAF_UFA_URI . '/assets/images/hero-turkey.jpg';

	$ctx = zhiraf_ufa_current_resort_context();
	if ( $ctx ) {
		$resort = zhiraf_ufa_get_resort_landing( $ctx['country'], $ctx['resort'] );
		if ( $resort && ! empty( $resort['image'] ) ) {
			return ZHIRAF_UFA_URI . '/assets/images/' . ltrim( (string) $resort['image'], '/' );
		}
	}

	if ( is_page() ) {
		$slug  = get_post_field( 'post_name', get_queried_object_id() );
		$offer = zhiraf_ufa_get_offer_landing( $slug );
		if ( $offer && ! empty( $offer['image'] ) ) {
			return ZHIRAF_UFA_URI . '/assets/images/' . ltrim( (string) $offer['image'], '/' );
		}
		$dest = zhiraf_ufa_get_destination_landing( $slug );
		if ( $dest && ! empty( $dest['image'] ) ) {
			return ZHIRAF_UFA_URI . '/assets/images/' . ltrim( (string) $dest['image'], '/' );
		}
	}

	return $default;
}

/**
 * Document title filter.
 *
 * @param string $title Title.
 * @return string
 */
function zhiraf_ufa_document_title( $title ) {
	if ( is_front_page() ) {
		return 'Туры из Уфы — подбор пакетных туров | «Жираф»';
	}
	if ( is_page( 'o-nas' ) ) {
		return 'О турагентстве «Жираф» в Уфе — с 2011 года';
	}
	if ( is_page( 'kontakty' ) ) {
		return 'Контакты «Жираф» — офис в Уфе, Гагарина 74/1';
	}
	if ( is_page( 'tour' ) ) {
		return 'Направления отдыха с вылетом из Уфы | «Жираф»';
	}
	if ( is_page( 'politika-konfidenczialnosti' ) ) {
		return 'Политика конфиденциальности | «Жираф»';
	}
	if ( is_page( 'politika-cookies' ) ) {
		return 'Политика cookies | «Жираф»';
	}
	$landing_seo = zhiraf_ufa_current_landing_seo();
	if ( $landing_seo && ! empty( $landing_seo['title'] ) ) {
		return $landing_seo['title'];
	}
	if ( is_home() && ! is_front_page() ) {
		return 'Блог о путешествиях из Уфы | «Жираф»';
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'zhiraf_ufa_document_title', 20 );

/**
 * Remove default canonical.
 */
function zhiraf_ufa_remove_wp_canonical() {
	remove_action( 'wp_head', 'rel_canonical' );
}
add_action( 'init', 'zhiraf_ufa_remove_wp_canonical' );

/**
 * Output meta tags and schema.
 */
function zhiraf_ufa_head_meta() {
	$desc = '';
	if ( is_front_page() ) {
		$desc = zhiraf_ufa_meta_descriptions()['home'];
	} elseif ( is_page() || zhiraf_ufa_current_resort_context() ) {
		$landing_seo = zhiraf_ufa_current_landing_seo();
		if ( $landing_seo && ! empty( $landing_seo['description'] ) ) {
			$desc = $landing_seo['description'];
		} elseif ( is_page() ) {
			$slug = get_post_field( 'post_name', get_queried_object_id() );
			$map  = zhiraf_ufa_meta_descriptions();
			$desc = isset( $map[ $slug ] ) ? $map[ $slug ] : wp_strip_all_tags( get_the_excerpt() );
		}
	} elseif ( is_singular( 'post' ) ) {
		$desc = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 30 );
	} elseif ( is_search() || is_404() ) {
		$desc = '';
	} elseif ( is_home() ) {
		$desc = zhiraf_ufa_meta_descriptions()['blog'];
	}

	$canonical = is_singular() ? get_permalink() : ( is_front_page() ? home_url( '/' ) : ( is_home() ? home_url( '/blog/' ) : '' ) );
	$resort_ctx  = zhiraf_ufa_current_resort_context();
	if ( $resort_ctx ) {
		$canonical = zhiraf_ufa_resort_url( $resort_ctx['country'], $resort_ctx['resort'] );
	}
	$canonical = zhiraf_ufa_canonical_public_url( $canonical );
	$is_staging = zhiraf_ufa_is_staging_host();

	if ( $is_staging ) {
		echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
	} elseif ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex, follow" />' . "\n";
	} else {
		echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />' . "\n";
	}

	if ( $desc ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( wp_strip_all_tags( $desc ) ) );
	}
	if ( $canonical ) {
		printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $canonical ) );
	}

	$og_title = wp_get_document_title();
	$og_image = zhiraf_ufa_og_image_url();
	?>
	<meta property="og:type" content="<?php echo is_singular( 'post' ) ? 'article' : 'website'; ?>" />
	<meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>" />
	<?php if ( $desc ) : ?>
	<meta property="og:description" content="<?php echo esc_attr( wp_strip_all_tags( $desc ) ); ?>" />
	<?php endif; ?>
	<meta property="og:url" content="<?php echo esc_url( $canonical ? $canonical : zhiraf_ufa_canonical_public_url( home_url( '/' ) ) ); ?>" />
	<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	<meta property="og:locale" content="ru_RU" />
	<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>" />
	<meta name="twitter:card" content="summary_large_image" />
	<?php
	$verification = get_theme_mod( 'zhiraf_yandex_verification', '' );
	if ( $verification ) {
		printf( '<meta name="yandex-verification" content="%s" />' . "\n", esc_attr( $verification ) );
	}
}
add_action( 'wp_head', 'zhiraf_ufa_head_meta', 1 );

/**
 * JSON-LD Organization + WebSite.
 */
function zhiraf_ufa_json_ld() {
	$site_url = zhiraf_ufa_canonical_public_url( home_url( '/' ) );
	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type' => 'TravelAgency',
				'@id'   => trailingslashit( $site_url ) . '#organization',
				'name'  => 'Турагентство «Жираф»',
				'legalName' => 'ООО «Жираф»',
				'url'   => $site_url,
				'email' => 'office@zhiraf-ufa.ru',
				'telephone' => '+7-901-810-50-18',
				'address' => array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => 'ул. Юрия Гагарина, 74/1, офис 3',
					'addressLocality' => 'Уфа',
					'postalCode'      => '450068',
					'addressCountry'  => 'RU',
				),
				'openingHoursSpecification' => array(
					array(
						'@type'     => 'OpeningHoursSpecification',
						'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
						'opens'     => '10:00',
						'closes'    => '19:00',
					),
					array(
						'@type'     => 'OpeningHoursSpecification',
						'dayOfWeek' => 'Saturday',
						'opens'     => '11:00',
						'closes'    => '15:00',
					),
				),
				'sameAs' => array( 'https://vk.com/zhiraf_ufa' ),
			),
			array(
				'@type' => 'WebSite',
				'@id'   => trailingslashit( $site_url ) . '#website',
				'url'   => $site_url,
				'name'  => get_bloginfo( 'name' ),
				'publisher' => array( '@id' => trailingslashit( $site_url ) . '#organization' ),
				'potentialAction' => array(
					'@type'       => 'SearchAction',
					'target'      => trailingslashit( $site_url ) . '?s={search_term_string}',
					'query-input' => 'required name=search_term_string',
				),
			),
		),
	);

	if ( is_singular( 'post' ) ) {
		$data['@graph'][] = array(
			'@type'            => 'Article',
			'headline'         => get_the_title(),
			'datePublished'    => get_the_date( 'c' ),
			'dateModified'     => get_the_modified_date( 'c' ),
			'mainEntityOfPage' => zhiraf_ufa_canonical_public_url( get_permalink() ),
			'author'           => array( '@type' => 'Organization', 'name' => 'Турагентство «Жираф»' ),
			'publisher'        => array( '@id' => trailingslashit( $site_url ) . '#organization' ),
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'zhiraf_ufa_json_ld', 5 );

/**
 * BreadcrumbList JSON-LD only (no visible UI).
 */
function zhiraf_ufa_breadcrumb_json_ld() {
	if ( is_front_page() ) {
		return;
	}
	$items = array();
	$pos   = 1;
	$items[] = array(
		'@type'    => 'ListItem',
		'position' => $pos++,
		'name'     => 'Главная',
		'item'     => zhiraf_ufa_canonical_public_url( home_url( '/' ) ),
	);
	$resort_ctx = zhiraf_ufa_current_resort_context();
	if ( $resort_ctx ) {
		$resort = zhiraf_ufa_get_resort_landing( $resort_ctx['country'], $resort_ctx['resort'] );
		if ( $resort ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $resort['country_name'],
				'item'     => zhiraf_ufa_canonical_public_url( home_url( '/tour/' . $resort_ctx['country'] . '/' ) ),
			);
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $resort['h1'],
				'item'     => zhiraf_ufa_canonical_public_url( zhiraf_ufa_resort_url( $resort_ctx['country'], $resort_ctx['resort'] ) ),
			);
		}
	} elseif ( is_page( 'tour' ) ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $pos++,
			'name'     => __( 'Направления отдыха с вылетом из Уфы', 'zhiraf-ufa' ),
			'item'     => zhiraf_ufa_canonical_public_url( home_url( '/tour/' ) ),
		);
	} elseif ( is_page() ) {
		$slug  = get_post_field( 'post_name', get_queried_object_id() );
		$dest  = zhiraf_ufa_get_destination_landing( $slug );
		$offer = zhiraf_ufa_get_offer_landing( $slug );
		if ( $dest ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => __( 'Каталог направлений', 'zhiraf-ufa' ),
				'item'     => zhiraf_ufa_canonical_public_url( home_url( '/tour/' ) ),
			);
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $dest['h1'],
				'item'     => zhiraf_ufa_canonical_public_url( get_permalink() ),
			);
		} elseif ( $offer ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $offer['h1'],
				'item'     => zhiraf_ufa_canonical_public_url( get_permalink() ),
			);
		} else {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => get_the_title(),
				'item'     => zhiraf_ufa_canonical_public_url( get_permalink() ),
			);
		}
	} elseif ( is_singular() ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $pos++,
			'name'     => get_the_title(),
			'item'     => zhiraf_ufa_canonical_public_url( get_permalink() ),
		);
	} elseif ( is_home() ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $pos++,
			'name'     => __( 'Блог о путешествиях из Уфы', 'zhiraf-ufa' ),
			'item'     => zhiraf_ufa_canonical_public_url( home_url( '/blog/' ) ),
		);
	}
	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'zhiraf_ufa_breadcrumb_json_ld', 6 );

/**
 * FAQPage JSON-LD schema for pages with FAQ.
 */
function zhiraf_ufa_faq_json_ld() {
	$faq_items = array();

	if ( is_front_page() ) {
		$faq_items = zhiraf_ufa_home_faq_items();
	} elseif ( zhiraf_ufa_current_resort_context() ) {
		$resort_ctx  = zhiraf_ufa_current_resort_context();
		$resort_data = zhiraf_ufa_get_resort_landing( $resort_ctx['country'], $resort_ctx['resort'] );
		if ( $resort_data && ! empty( $resort_data['faq'] ) ) {
			$faq_items = $resort_data['faq'];
		}
	} elseif ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( 'o-nas' === $slug ) {
			$faq_items = zhiraf_ufa_about_faq_items();
		} elseif ( 'tour' === $slug ) {
			$faq_items = zhiraf_ufa_catalog_faq_items();
		} else {
			$dest = zhiraf_ufa_get_destination_landing( $slug );
			if ( $dest && ! empty( $dest['faq'] ) ) {
				$faq_items = $dest['faq'];
			} else {
				$offer = zhiraf_ufa_get_offer_landing( $slug );
				if ( $offer && ! empty( $offer['faq'] ) ) {
					$faq_items = $offer['faq'];
				}
			}
		}
	}

	if ( empty( $faq_items ) || ! is_array( $faq_items ) ) {
		return;
	}

	$main_entities = array();
	foreach ( $faq_items as $item ) {
		if ( empty( $item['q'] ) || empty( $item['a'] ) ) {
			continue;
		}
		$main_entities[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $item['q'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $item['a'] ),
			),
		);
	}

	if ( empty( $main_entities ) ) {
		return;
	}

	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $main_entities,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'zhiraf_ufa_faq_json_ld', 7 );

