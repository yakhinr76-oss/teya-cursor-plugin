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
 * Offer hubs linked from resort landings.
 *
 * @return array<int,array{label:string,url:string}>
 */
function zhiraf_ufa_resort_offer_hub_links() {
	return array(
		array(
			'label' => __( 'Горящие туры', 'zhiraf-ufa' ),
			'url'   => '/goryashchie-tury/',
		),
		array(
			'label' => __( 'Всё включено', 'zhiraf-ufa' ),
			'url'   => '/vse-vklyucheno/',
		),
	);
}

/**
 * Sibling resort URLs for cross-linking (path without domain).
 *
 * @return array<string,array<int,array{label:string,url:string}>>
 */
function zhiraf_ufa_resort_sibling_link_map() {
	return array(
		'turciya/antalya'    => array(
			array( 'label' => __( 'Кемер', 'zhiraf-ufa' ), 'url' => '/tour/turciya/kemer/' ),
			array( 'label' => __( 'Аланья', 'zhiraf-ufa' ), 'url' => '/tour/turciya/alanya/' ),
			array( 'label' => __( 'Сиде', 'zhiraf-ufa' ), 'url' => '/tour/turciya/side/' ),
			array( 'label' => __( 'Белек', 'zhiraf-ufa' ), 'url' => '/tour/turciya/belek/' ),
		),
		'turciya/kemer'      => array(
			array( 'label' => __( 'Анталья', 'zhiraf-ufa' ), 'url' => '/tour/turciya/antalya/' ),
			array( 'label' => __( 'Сиде', 'zhiraf-ufa' ), 'url' => '/tour/turciya/side/' ),
			array( 'label' => __( 'Белек', 'zhiraf-ufa' ), 'url' => '/tour/turciya/belek/' ),
		),
		'turciya/alanya'     => array(
			array( 'label' => __( 'Сиде', 'zhiraf-ufa' ), 'url' => '/tour/turciya/side/' ),
			array( 'label' => __( 'Анталья', 'zhiraf-ufa' ), 'url' => '/tour/turciya/antalya/' ),
		),
		'turciya/side'       => array(
			array( 'label' => __( 'Аланья', 'zhiraf-ufa' ), 'url' => '/tour/turciya/alanya/' ),
			array( 'label' => __( 'Белек', 'zhiraf-ufa' ), 'url' => '/tour/turciya/belek/' ),
		),
		'turciya/belek'      => array(
			array( 'label' => __( 'Анталья', 'zhiraf-ufa' ), 'url' => '/tour/turciya/antalya/' ),
			array( 'label' => __( 'Сиде', 'zhiraf-ufa' ), 'url' => '/tour/turciya/side/' ),
		),
		'egipet/hurgada'     => array(
			array( 'label' => __( 'Макади-Бей', 'zhiraf-ufa' ), 'url' => '/tour/egipet/makadi-bay/' ),
			array( 'label' => __( 'Сахл-Хашиш', 'zhiraf-ufa' ), 'url' => '/tour/egipet/sahl-hasheesh/' ),
			array( 'label' => __( 'Шарм-эль-Шейх', 'zhiraf-ufa' ), 'url' => '/tour/egipet/sharm-el-sheyh/' ),
		),
		'egipet/sharm-el-sheyh' => array(
			array( 'label' => __( 'Хургада', 'zhiraf-ufa' ), 'url' => '/tour/egipet/hurgada/' ),
		),
		'egipet/makadi-bay'  => array(
			array( 'label' => __( 'Хургада', 'zhiraf-ufa' ), 'url' => '/tour/egipet/hurgada/' ),
			array( 'label' => __( 'Сахл-Хашиш', 'zhiraf-ufa' ), 'url' => '/tour/egipet/sahl-hasheesh/' ),
		),
		'egipet/sahl-hasheesh' => array(
			array( 'label' => __( 'Хургада', 'zhiraf-ufa' ), 'url' => '/tour/egipet/hurgada/' ),
			array( 'label' => __( 'Макади-Бей', 'zhiraf-ufa' ), 'url' => '/tour/egipet/makadi-bay/' ),
		),
		'oae/dubai'          => array(
			array( 'label' => __( 'Шарджа', 'zhiraf-ufa' ), 'url' => '/tour/oae/sharjah/' ),
		),
		'oae/sharjah'        => array(
			array( 'label' => __( 'Дубай', 'zhiraf-ufa' ), 'url' => '/tour/oae/dubai/' ),
		),
		'tailand/phuket'     => array(
			array( 'label' => __( 'Паттайя', 'zhiraf-ufa' ), 'url' => '/tour/tailand/pattaya/' ),
		),
		'tailand/pattaya'    => array(
			array( 'label' => __( 'Пхукет', 'zhiraf-ufa' ), 'url' => '/tour/tailand/phuket/' ),
		),
		'vietnam/nyachang'   => array(
			array( 'label' => __( 'Фукуок', 'zhiraf-ufa' ), 'url' => '/tour/vietnam/fukuok/' ),
		),
		'vietnam/fukuok'     => array(
			array( 'label' => __( 'Нячанг', 'zhiraf-ufa' ), 'url' => '/tour/vietnam/nyachang/' ),
		),
	);
}

/**
 * Merges sibling + offer-hub links into resort final_cta.peer_links (deduped by path).
 *
 * @param array<string,mixed> $resort_data Resort row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_resort_apply_interlinking( array $resort_data ) {
	$country = sanitize_title( (string) ( $resort_data['country'] ?? '' ) );
	$resort  = sanitize_title( (string) ( $resort_data['resort'] ?? '' ) );
	if ( '' === $country || '' === $resort ) {
		return $resort_data;
	}
	$key = $country . '/' . $resort;

	if ( empty( $resort_data['final_cta'] ) || ! is_array( $resort_data['final_cta'] ) ) {
		$resort_data['final_cta'] = array(
			'title'                => '',
			'message_placeholder'  => '',
			'peer_links'           => array(),
		);
	}
	if ( empty( $resort_data['final_cta']['peer_links'] ) || ! is_array( $resort_data['final_cta']['peer_links'] ) ) {
		$resort_data['final_cta']['peer_links'] = array();
	}

	$seen = array();
	foreach ( $resort_data['final_cta']['peer_links'] as $link ) {
		$path = zhiraf_ufa_resort_hub_normalize_path( (string) ( $link['url'] ?? '' ) );
		if ( '' !== $path ) {
			$seen[ $path ] = true;
		}
	}

	$map     = zhiraf_ufa_resort_sibling_link_map();
	$extras  = isset( $map[ $key ] ) ? $map[ $key ] : array();
	$extras  = array_merge( $extras, zhiraf_ufa_resort_offer_hub_links() );
	$country_url = zhiraf_ufa_resort_hub_normalize_path( home_url( '/tour/' . $country . '/' ) );
	if ( '' !== $country_url && empty( $seen[ $country_url ] ) ) {
		array_unshift(
			$extras,
			array(
				'label' => sprintf(
					/* translators: %s: country name */
					__( 'Все туры: %s', 'zhiraf-ufa' ),
					! empty( $resort_data['country_name'] ) ? (string) $resort_data['country_name'] : $country
				),
				'url'   => $country_url,
			)
		);
	}

	foreach ( $extras as $link ) {
		$path = zhiraf_ufa_resort_hub_normalize_path( (string) ( $link['url'] ?? '' ) );
		if ( '' === $path || ! empty( $seen[ $path ] ) ) {
			continue;
		}
		$resort_data['final_cta']['peer_links'][] = array(
			'label' => (string) ( $link['label'] ?? '' ),
			'url'   => $path,
		);
		$seen[ $path ] = true;
	}

	return $resort_data;
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