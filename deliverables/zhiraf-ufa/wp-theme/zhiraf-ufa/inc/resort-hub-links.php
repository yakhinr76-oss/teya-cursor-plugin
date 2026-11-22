<?php
/**
 * «Курорты направления» — утверждённые resort URL (06-url-map) и рендер карточек.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Пути курортных лендингов (без home_url), синхронно с teya-memory …/06-url-map.csv.
 *
 * @return string[]
 */
function zhiraf_ufa_approved_resort_landing_paths() {
	static $paths = null;
	if ( null !== $paths ) {
		return $paths;
	}
	$paths = array();
	if ( function_exists( 'zhiraf_ufa_resort_landing_index' ) ) {
		foreach ( zhiraf_ufa_resort_landing_index() as $row ) {
			if ( empty( $row['country'] ) || empty( $row['resort'] ) ) {
				continue;
			}
			$paths[] = '/tour/' . sanitize_title( (string) $row['country'] ) . '/' . sanitize_title( (string) $row['resort'] ) . '/';
		}
	}
	$paths = array_values( array_unique( $paths ) );

	return $paths;
}

/**
 * Retired resort paths (301 → country hub). Blocked in peer_links / cards.
 *
 * @return string[]
 */
function zhiraf_ufa_retired_resort_landing_paths() {
	static $paths = null;
	if ( null !== $paths ) {
		return $paths;
	}
	if ( ! function_exists( 'zhiraf_ufa_retired_resort_landing_redirects' ) ) {
		return array();
	}
	$paths = array();
	foreach ( array_keys( zhiraf_ufa_retired_resort_landing_redirects() ) as $from ) {
		$norm = zhiraf_ufa_resort_hub_normalize_path( $from );
		if ( '' !== $norm ) {
			$paths[] = $norm;
		}
	}

	return $paths;
}

/**
 * Нормализует URL до path с ведущим и замыкающим слэшем.
 *
 * @param string $url Full or relative URL.
 * @return string
 */
function zhiraf_ufa_resort_hub_normalize_path( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	if ( str_starts_with( $url, '#' ) ) {
		return '';
	}
	$path = wp_parse_url( $url, PHP_URL_PATH );
	if ( ! is_string( $path ) || '' === $path ) {
		return '';
	}
	if ( ! str_starts_with( $path, '/' ) ) {
		$path = '/' . $path;
	}
	if ( ! str_ends_with( $path, '/' ) ) {
		$path .= '/';
	}

	return $path;
}

/**
 * @param string $url Landing URL.
 * @return bool
 */
function zhiraf_ufa_resort_hub_url_is_approved_landing( $url ) {
	$path = zhiraf_ufa_resort_hub_normalize_path( $url );
	if ( '' === $path ) {
		return false;
	}

	return in_array( $path, zhiraf_ufa_approved_resort_landing_paths(), true );
}

/**
 * Strips links to retired resort URLs from JSON v2 peer_links.
 *
 * @param array<string,mixed> $resort_data Resort row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_resort_apply_interlinking( array $resort_data ) {
	$blocked = array_flip( zhiraf_ufa_retired_resort_landing_paths() );
	if ( empty( $resort_data['final_cta']['peer_links'] ) || ! is_array( $resort_data['final_cta']['peer_links'] ) ) {
		return $resort_data;
	}
	$clean = array();
	foreach ( $resort_data['final_cta']['peer_links'] as $link ) {
		if ( ! is_array( $link ) ) {
			continue;
		}
		$path = zhiraf_ufa_resort_hub_normalize_path( (string) ( $link['url'] ?? '' ) );
		if ( '' === $path || isset( $blocked[ $path ] ) ) {
			continue;
		}
		$clean[] = $link;
	}
	$resort_data['final_cta']['peer_links'] = $clean;

	return $resort_data;
}

/**
 * Разбирает карточку hub: landing | filter | static.
 *
 * @param array<string,mixed> $link    label, url?, desc?, catalog_filter?.
 * @param string              $dest_slug Country slug (turciya, oae, …).
 * @return array{type:string,url:string,filter:string,title:string,desc:string}
 */
function zhiraf_ufa_resort_hub_card_resolve( array $link, $dest_slug ) {
	unset( $dest_slug );

	$label = (string) ( $link['label'] ?? '' );
	$title = $label;
	if ( preg_match( '/^Туры (?:в|на) (.+) из Уфы$/u', $label, $m ) ) {
		$title = $m[1];
	}

	$desc   = (string) ( $link['desc'] ?? '' );
	$filter = sanitize_key( (string) ( $link['catalog_filter'] ?? '' ) );
	$url    = ! empty( $link['url'] ) ? (string) $link['url'] : '';

	if ( $url && zhiraf_ufa_resort_hub_url_is_approved_landing( $url ) ) {
		return array(
			'type'   => 'landing',
			'url'    => $url,
			'filter' => '',
			'title'  => $title,
			'desc'   => $desc,
		);
	}

	if ( '' !== $filter ) {
		return array(
			'type'   => 'filter',
			'url'    => '',
			'filter' => $filter,
			'title'  => $title,
			'desc'   => $desc,
		);
	}

	// Старые hash-якоря на каталог — фильтр без отдельного URL.
	if ( $url && false !== strpos( $url, '#dest-curated-hotels' ) ) {
		return array(
			'type'   => 'static',
			'url'    => '',
			'filter' => '',
			'title'  => $title,
			'desc'   => $desc,
		);
	}

	return array(
		'type'   => 'static',
		'url'    => '',
		'filter' => '',
		'title'  => $title,
		'desc'   => $desc,
	);
}

/**
 * Печатает одну карточку «Курорты направления».
 *
 * @param array<string,mixed> $link Resort link row.
 * @param string              $dest_slug Destination slug.
 */
/**
 * Parent country hubs that show the prominent v2 resort nav block.
 *
 * @return string[]
 */
function zhiraf_ufa_resort_v2_parent_country_slugs() {
	return array( 'turciya', 'oae', 'egipet', 'tailand', 'vietnam', 'kitaj' );
}

/**
 * Short label for hub / footer (e.g. «Дубай (ОАЭ)»).
 *
 * @param string $country Country slug.
 * @param string $resort  Resort slug.
 * @return string
 */
function zhiraf_ufa_resort_hub_short_label( $country, $resort ) {
	$country = sanitize_key( (string) $country );
	$resort  = sanitize_key( (string) $resort );
	if ( '' === $country || '' === $resort ) {
		return '';
	}
	if ( function_exists( 'zhiraf_ufa_get_resort_landing' ) ) {
		$row = zhiraf_ufa_get_resort_landing( $country, $resort );
		if ( is_array( $row ) && ! empty( $row['resort_name'] ) ) {
			$name = (string) $row['resort_name'];
			$cn   = ! empty( $row['country_name'] ) ? (string) $row['country_name'] : '';
			if ( '' !== $cn && 'Турция' !== $cn ) {
				return sprintf( '%s (%s)', $name, $cn );
			}
			if ( 'turciya' === $country && 'antalya' === $resort ) {
				return __( 'Анталья (Турция)', 'zhiraf-ufa' );
			}

			return $name;
		}
	}

	return $resort;
}

/**
 * Hub links for catalog / footer — all approved JSON v2 resort landings.
 *
 * @return array<int,array{label:string,url:string,country:string,resort:string}>
 */
function zhiraf_ufa_resort_hub_links() {
	static $links = null;
	if ( null !== $links ) {
		return $links;
	}
	$links = array();
	if ( ! function_exists( 'zhiraf_ufa_resort_landing_index' ) || ! function_exists( 'zhiraf_ufa_resort_url' ) ) {
		return $links;
	}
	foreach ( zhiraf_ufa_resort_landing_index() as $row ) {
		if ( empty( $row['country'] ) || empty( $row['resort'] ) ) {
			continue;
		}
		$country = sanitize_key( (string) $row['country'] );
		$resort  = sanitize_key( (string) $row['resort'] );
		$links[] = array(
			'label'   => zhiraf_ufa_resort_hub_short_label( $country, $resort ),
			'url'     => zhiraf_ufa_resort_url( $country, $resort ),
			'country' => $country,
			'resort'  => $resort,
		);
	}

	return $links;
}

/**
 * @param string $dest_slug Country landing slug.
 * @return bool
 */
function zhiraf_ufa_destination_show_prominent_resort_nav( $dest_slug ) {
	$dest_slug = sanitize_key( (string) $dest_slug );

	return in_array( $dest_slug, zhiraf_ufa_resort_v2_parent_country_slugs(), true );
}

/**
 * Render prominent resort nav on country landings (under hero).
 *
 * @param string              $dest_slug Country slug.
 * @param array<string,mixed> $dest      Landing row.
 */
function zhiraf_ufa_render_destination_prominent_resort_nav( $dest_slug, array $dest ) {
	if ( ! zhiraf_ufa_destination_show_prominent_resort_nav( $dest_slug ) ) {
		return;
	}
	if ( empty( $dest['resort_links'] ) || ! is_array( $dest['resort_links'] ) ) {
		return;
	}
	get_template_part(
		'template-parts/destination/destination-resort-nav-prominent',
		null,
		array(
			'dest_slug' => $dest_slug,
			'links'     => $dest['resort_links'],
		)
	);
}

function zhiraf_ufa_render_resort_hub_card( array $link, $dest_slug ) {
	$card = zhiraf_ufa_resort_hub_card_resolve( $link, $dest_slug );

	$title_html = sprintf(
		/* translators: %s: resort name */
		esc_html__( 'Туры в %s из Уфы', 'zhiraf-ufa' ),
		esc_html( $card['title'] )
	);

	if ( 'landing' === $card['type'] ) {
		?>
		<a
			class="dest-resort-link-card dest-resort-link-card--landing"
			href="<?php echo esc_url( $card['url'] ); ?>"
			role="listitem"
		>
			<span class="dest-resort-link-card__body">
				<span class="dest-resort-link-card__title"><?php echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php if ( '' !== $card['desc'] ) : ?>
					<span class="dest-resort-link-card__subtitle"><?php echo esc_html( $card['desc'] ); ?></span>
				<?php endif; ?>
			</span>
			<span class="dest-resort-link-card__arrow" aria-hidden="true">→</span>
		</a>
		<?php
		return;
	}

	if ( 'filter' === $card['type'] ) {
		?>
		<button
			type="button"
			class="dest-resort-link-card dest-resort-link-card--filter"
			role="listitem"
			data-resort-hub-filter="<?php echo esc_attr( $card['filter'] ); ?>"
		>
			<span class="dest-resort-link-card__body">
				<span class="dest-resort-link-card__title"><?php echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php if ( '' !== $card['desc'] ) : ?>
					<span class="dest-resort-link-card__subtitle"><?php echo esc_html( $card['desc'] ); ?></span>
				<?php endif; ?>
			</span>
		</button>
		<?php
		return;
	}

	?>
	<div class="dest-resort-link-card dest-resort-link-card--static" role="listitem">
		<span class="dest-resort-link-card__body">
			<span class="dest-resort-link-card__title"><?php echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<?php if ( '' !== $card['desc'] ) : ?>
				<span class="dest-resort-link-card__subtitle"><?php echo esc_html( $card['desc'] ); ?></span>
			<?php endif; ?>
		</span>
	</div>
	<?php
}