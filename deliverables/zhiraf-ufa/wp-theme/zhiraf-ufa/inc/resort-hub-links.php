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
	$paths = array(
		'/tour/oae/dubai/',
		'/tour/oae/sharjah/',
		'/tour/egipet/hurgada/',
		'/tour/egipet/sharm-el-sheyh/',
		'/tour/vietnam/fukuok/',
		'/tour/vietnam/nyachang/',
		'/tour/tailand/phuket/',
		'/tour/tailand/pattaya/',
		'/tour/kitaj/sanya/',
		'/tour/turciya/antalya/',
	);

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