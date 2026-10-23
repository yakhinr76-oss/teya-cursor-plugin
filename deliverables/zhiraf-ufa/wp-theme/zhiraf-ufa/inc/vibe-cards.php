<?php
/**
 * «Выбор по вайбу и формату» — defaults, resolution, admin meta.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Allowed accent colors for vibe cards.
 *
 * @return string[]
 */
function zhiraf_ufa_vibe_color_keys() {
	return array( 'teal', 'coral', 'indigo', 'amber' );
}

/**
 * Sanitize one vibe card from admin/config.
 *
 * @param array<string,mixed> $card Raw card.
 * @return array<string,mixed>|null
 */
function zhiraf_ufa_sanitize_vibe_card( $card ) {
	if ( ! is_array( $card ) ) {
		return null;
	}
	if ( isset( $card['enabled'] ) && ! $card['enabled'] ) {
		return null;
	}
	$title = isset( $card['vibe_title'] ) ? sanitize_text_field( (string) $card['vibe_title'] ) : '';
	if ( '' === $title ) {
		return null;
	}
	$colors = zhiraf_ufa_vibe_color_keys();
	$color  = isset( $card['vibe_color'] ) ? sanitize_key( (string) $card['vibe_color'] ) : 'teal';
	if ( ! in_array( $color, $colors, true ) ) {
		$color = 'teal';
	}
	$slug = isset( $card['vibe_slug'] ) ? sanitize_key( (string) $card['vibe_slug'] ) : '';
	if ( '' === $slug ) {
		$slug = sanitize_title( $title );
	}

	return array(
		'vibe_title'    => $title,
		'vibe_subtitle' => isset( $card['vibe_subtitle'] ) ? sanitize_textarea_field( (string) $card['vibe_subtitle'] ) : '',
		'vibe_image'    => isset( $card['vibe_image'] ) ? sanitize_file_name( (string) $card['vibe_image'] ) : '',
		'vibe_image_id' => ! empty( $card['vibe_image_id'] ) ? absint( $card['vibe_image_id'] ) : 0,
		'vibe_tag'      => isset( $card['vibe_tag'] ) ? sanitize_text_field( (string) $card['vibe_tag'] ) : '',
		'vibe_color'    => $color,
		'vibe_slug'     => $slug,
	);
}

/**
 * Sanitize vibe section payload.
 *
 * @param array<string,mixed> $section Raw section.
 * @return array<string,mixed>
 */
function zhiraf_ufa_sanitize_vibe_section( $section ) {
	if ( ! is_array( $section ) ) {
		return array();
	}
	$cards = array();
	if ( ! empty( $section['cards'] ) && is_array( $section['cards'] ) ) {
		foreach ( $section['cards'] as $raw ) {
			$clean = zhiraf_ufa_sanitize_vibe_card( $raw );
			if ( $clean ) {
				$cards[] = $clean;
			}
			if ( count( $cards ) >= 4 ) {
				break;
			}
		}
	}
	if ( empty( $cards ) ) {
		return array();
	}
	$title = isset( $section['title'] ) ? sanitize_text_field( (string) $section['title'] ) : '';
	if ( '' === $title ) {
		$title = __( 'Выбор по вайбу и формату', 'zhiraf-ufa' );
	}
	$subtitle = isset( $section['subtitle'] ) ? sanitize_textarea_field( (string) $section['subtitle'] ) : '';
	if ( '' === $subtitle ) {
		$subtitle = __( 'Выберите сценарий — подберём проверенные отели под ваш ритм', 'zhiraf-ufa' );
	}

	return array(
		'title'    => $title,
		'subtitle' => $subtitle,
		'cards'    => $cards,
	);
}

/**
 * Default card templates (shared copy; images vary by destination).
 *
 * @return array<int,array<string,string>>
 */
function zhiraf_ufa_vibe_card_blueprint() {
	return array(
		array(
			'vibe_title'    => __( 'Семья и дети', 'zhiraf-ufa' ),
			'vibe_subtitle' => __( 'Пологий вход в море, детские клубы, подогреваемые бассейны и спокойная атмосфера после 22:00.', 'zhiraf-ufa' ),
			'vibe_tag'      => __( 'Детские клубы', 'zhiraf-ufa' ),
			'vibe_color'    => 'teal',
			'vibe_slug'     => 'family',
			'vibe_image'    => 'team-travel.jpg',
		),
		array(
			'vibe_title'    => __( 'Романтика и пары', 'zhiraf-ufa' ),
			'vibe_subtitle' => __( 'Adults only, закаты, ужины у воды, спа и тихие пляжные зоны без детских шумов.', 'zhiraf-ufa' ),
			'vibe_tag'      => __( 'Adults only', 'zhiraf-ufa' ),
			'vibe_color'    => 'coral',
			'vibe_slug'     => 'couple',
			'vibe_image'    => 'hero-collage-2.jpg',
		),
		array(
			'vibe_title'    => __( 'Шопинг и движ', 'zhiraf-ufa' ),
			'vibe_subtitle' => __( 'Отели у моллов, набережных и транспортных узлов — удобно гулять, ужинать и ловить события.', 'zhiraf-ufa' ),
			'vibe_tag'      => __( 'Центр и ритм', 'zhiraf-ufa' ),
			'vibe_color'    => 'indigo',
			'vibe_slug'     => 'city',
			'vibe_image'    => 'dest-uae-new.jpg',
		),
		array(
			'vibe_title'    => __( 'Исследователи', 'zhiraf-ufa' ),
			'vibe_subtitle' => __( 'Экскурсии, каньоны, пустыни, храмы и острова — отдых с выездами, а не только у бассейна.', 'zhiraf-ufa' ),
			'vibe_tag'      => __( 'Экскурсии', 'zhiraf-ufa' ),
			'vibe_color'    => 'amber',
			'vibe_slug'     => 'active',
			'vibe_image'    => 'travel-passport.jpg',
		),
	);
}

/**
 * Per-destination image overrides for vibe slugs.
 *
 * @param string $slug Destination slug.
 * @return array<string,string>
 */
function zhiraf_ufa_vibe_image_map_for_slug( $slug ) {
	$maps = array(
		'oae'     => array(
			'family' => 'resort-abu-dabi.jpg',
			'couple' => 'hero-uae.jpg',
			'city'   => 'dest-uae-new.jpg',
			'active' => 'travel-passport.jpg',
		),
		'vietnam' => array(
			'family' => 'resort-vietnam-nha-trang-2.jpg',
			'couple' => 'resort-vietnam-phu-quoc-2.jpg',
			'city'   => 'resort-vietnam-cam-ranh-2.jpg',
			'active' => 'resort-vietnam-phan-thiet-2.jpg',
		),
		'turciya' => array(
			'family' => 'resort-turciya-alanya-2.jpg',
			'couple' => 'resort-turciya-belek-1.jpg',
			'city'   => 'resort-turciya-kemer-2.jpg',
			'active' => 'resort-turciya-antalya-2.jpg',
		),
		'egipet'  => array(
			'family' => 'resort-egipet-hurghada-2.jpg',
			'couple' => 'resort-egipet-sharm-2.jpg',
			'city'   => 'resort-egipet-el-gouna-1.jpg',
			'active' => 'resort-egipet-marsa-alam-2.jpg',
		),
		'tailand' => array(
			'family' => 'country-tailand.jpg',
			'couple' => 'dest-thailand.jpg',
			'city'   => 'dest-thailand-new.jpg',
			'active' => 'hero-thailand.jpg',
		),
		'rossiya' => array(
			'family' => 'hero-collage-1.jpg',
			'couple' => 'hero-collage-2.jpg',
			'city'   => 'dest-uae-new.jpg',
			'active' => 'travel-passport.jpg',
		),
		'abkhaziya' => array(
			'family' => 'alt-armeniya-2.jpg',
			'couple' => 'country-armeniya.jpg',
			'city'   => 'alt-armeniya-3.jpg',
			'active' => 'travel-passport.jpg',
		),
		'armeniya' => array(
			'family' => 'alt-armeniya-sevan.jpg',
			'couple' => 'alt-armeniya-yerevan.jpg',
			'city'   => 'alt-armeniya-2.jpg',
			'active' => 'country-armeniya.jpg',
		),
		'kitaj'   => array(
			'family' => 'country-kitaj.jpg',
			'couple' => 'resort-sanya-2.jpg',
			'city'   => 'resort-sanya-3.jpg',
			'active' => 'travel-passport.jpg',
		),
		'indiya'  => array(
			'family' => 'alt-indiya-beach-1.jpg',
			'couple' => 'alt-indiya-beach-2.jpg',
			'city'   => 'resort-severnyj-goa.jpg',
			'active' => 'country-indiya.jpg',
		),
		'indoneziya' => array(
			'family' => 'alt-indoneziya-beach-1.jpg',
			'couple' => 'alt-indoneziya-beach-2.jpg',
			'city'   => 'alt-indoneziya-2.jpg',
			'active' => 'alt-indoneziya-3.jpg',
		),
		'maldivy' => array(
			'family' => 'resort-maldivy-atoll-1.jpg',
			'couple' => 'resort-maldivy-atoll-2.jpg',
			'city'   => 'resort-maldivy-atoll-1.jpg',
			'active' => 'resort-maldivy-atoll-2.jpg',
		),
		'sejshely' => array(
			'family' => 'alt-sejshely-2.jpg',
			'couple' => 'country-sejshely.jpg',
			'city'   => 'alt-sejshely-3.jpg',
			'active' => 'alt-sejshely-2.jpg',
		),
		'shri-lanka' => array(
			'family' => 'alt-shri-lanka-beach-1.jpg',
			'couple' => 'alt-shri-lanka-beach-2.jpg',
			'city'   => 'country-shri-lanka.jpg',
			'active' => 'alt-shri-lanka-3.jpg',
		),
		'uzbekistan' => array(
			'family' => 'resort-samarkand.jpg',
			'couple' => 'country-uzbekistan.jpg',
			'city'   => 'alt-uzbekistan-3.jpg',
			'active' => 'alt-uzbekistan-2.jpg',
		),
		'vse-vklyucheno' => array(
			'family' => 'resort-turciya-alanya-2.jpg',
			'couple' => 'resort-egipet-sharm-1.jpg',
			'city'   => 'hero-thailand.jpg',
			'active' => 'resort-phuket.jpg',
		),
	);
	return isset( $maps[ $slug ] ) ? $maps[ $slug ] : array();
}

/**
 * Ensure vibe cards always have a theme image (fixes empty admin meta → pink placeholders).
 *
 * @param array<string,mixed> $section Sanitized section.
 * @param string              $slug    Destination slug.
 * @return array<string,mixed>
 */
function zhiraf_ufa_enrich_vibe_section_images( $section, $slug ) {
	if ( empty( $section['cards'] ) || ! is_array( $section['cards'] ) ) {
		return $section;
	}
	$map        = zhiraf_ufa_vibe_image_map_for_slug( $slug );
	$blueprints = array();
	foreach ( zhiraf_ufa_vibe_card_blueprint() as $bp ) {
		if ( ! empty( $bp['vibe_slug'] ) ) {
			$blueprints[ $bp['vibe_slug'] ] = $bp;
		}
	}
	foreach ( $section['cards'] as $i => $card ) {
		if ( ! is_array( $card ) ) {
			continue;
		}
		if ( ! empty( $card['vibe_image_id'] ) ) {
			continue;
		}
		$vibe_slug = ! empty( $card['vibe_slug'] ) ? (string) $card['vibe_slug'] : '';
		$file      = ! empty( $card['vibe_image'] ) ? (string) $card['vibe_image'] : '';
		$path      = $file ? ZHIRAF_UFA_DIR . '/assets/images/' . ltrim( $file, '/' ) : '';
		if ( $file && is_readable( $path ) ) {
			continue;
		}
		if ( $vibe_slug && ! empty( $map[ $vibe_slug ] ) ) {
			$section['cards'][ $i ]['vibe_image'] = $map[ $vibe_slug ];
		} elseif ( $vibe_slug && ! empty( $blueprints[ $vibe_slug ]['vibe_image'] ) ) {
			$section['cards'][ $i ]['vibe_image'] = $blueprints[ $vibe_slug ]['vibe_image'];
		}
	}
	return $section;
}

/**
 * Build default vibe section for a destination landing.
 *
 * @param string              $slug Destination slug.
 * @param array<string,mixed> $dest Landing row.
 * @return array<string,mixed>
 */
function zhiraf_ufa_default_vibe_section( $slug, $dest ) {
	$name = ! empty( $dest['name'] ) ? $dest['name'] : $slug;
	if ( 'vse-vklyucheno' === $slug ) {
		$title    = __( 'Какой отдых All Inclusive подходит именно вам?', 'zhiraf-ufa' );
		$subtitle = __( 'Выберите сценарий — подберём отели с AI/UAI под ваш состав', 'zhiraf-ufa' );
	} else {
		/* translators: %s: country name */
		$title    = sprintf( __( 'Какой отдых в %s подходит именно вам?', 'zhiraf-ufa' ), $name );
		$subtitle = __( 'Выберите сценарий — подберём проверенные отели под ваш ритм', 'zhiraf-ufa' );
	}

	$images = zhiraf_ufa_vibe_image_map_for_slug( $slug );
	$cards  = array();
	foreach ( zhiraf_ufa_vibe_card_blueprint() as $blueprint ) {
		$row = $blueprint;
		if ( ! empty( $images[ $blueprint['vibe_slug'] ] ) ) {
			$row['vibe_image'] = $images[ $blueprint['vibe_slug'] ];
		}
		$cards[] = $row;
	}

	if ( 'vse-vklyucheno' === $slug && ! empty( $cards[3] ) ) {
		$cards[3]['vibe_title']    = __( 'Релакс у бассейна', 'zhiraf-ufa' );
		$cards[3]['vibe_subtitle'] = __( 'Шезлонги у моря, спа, тихие зоны и формат «всё включено» без суеты.', 'zhiraf-ufa' );
		$cards[3]['vibe_tag']      = __( 'Spa & пляж', 'zhiraf-ufa' );
	}

	return array(
		'title'    => $title,
		'subtitle' => $subtitle,
		'cards'    => $cards,
	);
}

/**
 * Resolve vibe section: post meta → landing config → defaults.
 *
 * @param string              $slug Destination slug.
 * @param array<string,mixed> $dest Landing row.
 * @param int                 $page_id Optional page ID.
 * @return array<string,mixed>|null Null when section disabled or empty.
 */
function zhiraf_ufa_get_vibe_section( $slug, $dest, $page_id = 0 ) {
	if ( isset( $dest['vibe_section'] ) && false === $dest['vibe_section'] ) {
		return null;
	}
	if ( ! $page_id ) {
		$page_id = get_queried_object_id();
	}
	$section = null;
	if ( $page_id ) {
		$meta = get_post_meta( $page_id, '_zhiraf_vibe_section', true );
		if ( is_array( $meta ) && ! empty( $meta['cards'] ) ) {
			$section = zhiraf_ufa_sanitize_vibe_section( $meta );
		}
	}
	if ( empty( $section ) && ! empty( $dest['vibe_section'] ) && is_array( $dest['vibe_section'] ) ) {
		$section = zhiraf_ufa_sanitize_vibe_section( $dest['vibe_section'] );
	}
	if ( empty( $section ) ) {
		$section = zhiraf_ufa_sanitize_vibe_section( zhiraf_ufa_default_vibe_section( $slug, $dest ) );
	}
	if ( empty( $section ) ) {
		return null;
	}
	/**
	 * Filter resolved vibe section before render.
	 *
	 * @param array<string,mixed> $section Sanitized section.
	 * @param string              $slug    Destination slug.
	 * @param array<string,mixed> $dest    Landing config.
	 */
	$section = apply_filters( 'zhiraf_ufa_vibe_section', $section, $slug, $dest );
	$section = zhiraf_ufa_enrich_vibe_section_images( $section, $slug );
	if ( empty( $section['cards'] ) ) {
		return null;
	}
	return $section;
}

/**
 * Human-readable vibe label for CRM from slug.
 *
 * @param string $slug Vibe slug.
 * @return string
 */
function zhiraf_ufa_vibe_slug_label( $slug ) {
	$labels = array(
		'family' => __( 'Семья и дети', 'zhiraf-ufa' ),
		'couple' => __( 'Романтика и пары', 'zhiraf-ufa' ),
		'city'   => __( 'Шопинг и движ', 'zhiraf-ufa' ),
		'party'  => __( 'Шопинг и движ', 'zhiraf-ufa' ),
		'active' => __( 'Исследователи', 'zhiraf-ufa' ),
	);
	return isset( $labels[ $slug ] ) ? $labels[ $slug ] : $slug;
}

/**
 * Register post meta for vibe section (REST-ready for future editors).
 */
function zhiraf_ufa_register_vibe_meta() {
	register_post_meta(
		'page',
		'_zhiraf_vibe_section',
		array(
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => false,
			'auth_callback'     => function () {
				return current_user_can( 'edit_pages' );
			},
			'sanitize_callback' => 'zhiraf_ufa_sanitize_vibe_section',
		)
	);
}
add_action( 'init', 'zhiraf_ufa_register_vibe_meta' );

/**
 * Whether the post is a destination landing page.
 *
 * @param WP_Post|null $post Post.
 * @return bool
 */
function zhiraf_ufa_is_destination_landing_page( $post ) {
	if ( ! $post || 'page' !== $post->post_type ) {
		return false;
	}
	if ( ! function_exists( 'zhiraf_ufa_destination_landing_data' ) ) {
		return false;
	}
	$slugs = array_keys( zhiraf_ufa_destination_landing_data() );
	return in_array( $post->post_name, $slugs, true );
}

/**
 * Admin meta box: vibe section repeater (up to 4 cards).
 */
function zhiraf_ufa_vibe_meta_box() {
	add_meta_box(
		'zhiraf-vibe-section',
		__( 'Выбор по вайбу и формату', 'zhiraf-ufa' ),
		'zhiraf_ufa_render_vibe_meta_box',
		'page',
		'normal',
		'default'
	);
}

/**
 * @param WP_Post $post Post.
 */
function zhiraf_ufa_render_vibe_meta_box( $post ) {
	if ( ! zhiraf_ufa_is_destination_landing_page( $post ) ) {
		echo '<p class="description">' . esc_html__( 'Метабокс доступен для страниц направлений (tour/oae, tour/vietnam и т.д.).', 'zhiraf-ufa' ) . '</p>';
		return;
	}
	wp_nonce_field( 'zhiraf_vibe_section_save', 'zhiraf_vibe_section_nonce' );
	$stored = get_post_meta( $post->ID, '_zhiraf_vibe_section', true );
	$dest   = zhiraf_ufa_get_destination_landing( $post->post_name );
	if ( ! is_array( $stored ) || empty( $stored['cards'] ) ) {
		$stored = zhiraf_ufa_default_vibe_section( $post->post_name, is_array( $dest ) ? $dest : array( 'name' => $post->post_title ) );
	}
	$title    = isset( $stored['title'] ) ? $stored['title'] : '';
	$subtitle = isset( $stored['subtitle'] ) ? $stored['subtitle'] : '';
	$cards    = ! empty( $stored['cards'] ) && is_array( $stored['cards'] ) ? $stored['cards'] : array();
	while ( count( $cards ) < 4 ) {
		$cards[] = array(
			'enabled'       => false,
			'vibe_title'    => '',
			'vibe_subtitle' => '',
			'vibe_image'    => '',
			'vibe_tag'      => '',
			'vibe_color'    => 'teal',
			'vibe_slug'     => '',
		);
	}
	$colors = zhiraf_ufa_vibe_color_keys();
	?>
	<p>
		<label for="zhiraf_vibe_title"><strong><? esc_html_e( 'Заголовок секции', 'zhiraf-ufa' ); ?></strong></label><br />
		<input type="text" class="widefat" id="zhiraf_vibe_title" name="zhiraf_vibe_section[title]" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<p>
		<label for="zhiraf_vibe_subtitle"><strong><? esc_html_e( 'Подзаголовок', 'zhiraf-ufa' ); ?></strong></label><br />
		<input type="text" class="widefat" id="zhiraf_vibe_subtitle" name="zhiraf_vibe_section[subtitle]" value="<?php echo esc_attr( $subtitle ); ?>" />
	</p>
	<p class="description"><? esc_html_e( 'До 4 карточек. Пустой заголовок карточки = карточка скрыта. Изображение — файл из assets/images темы или ID вложения.', 'zhiraf-ufa' ); ?></p>
	<?php
	for ( $i = 0; $i < 4; $i++ ) {
		$c = isset( $cards[ $i ] ) ? $cards[ $i ] : array();
		?>
		<fieldset style="border:1px solid #ccd0d4;padding:12px;margin:12px 0;">
			<legend><strong><?php echo esc_html( sprintf( __( 'Карточка %d', 'zhiraf-ufa' ), $i + 1 ) ); ?></strong></legend>
			<p>
				<label>
					<input type="checkbox" name="zhiraf_vibe_section[cards][<?php echo (int) $i; ?>][enabled]" value="1" <?php checked( ! isset( $c['enabled'] ) || $c['enabled'] || ! empty( $c['vibe_title'] ) ); ?> />
					<? esc_html_e( 'Показывать', 'zhiraf-ufa' ); ?>
				</label>
			</p>
			<p>
				<label><? esc_html_e( 'Название сценария', 'zhiraf-ufa' ); ?><br />
					<input type="text" class="widefat" name="zhiraf_vibe_section[cards][<?php echo (int) $i; ?>][vibe_title]" value="<?php echo esc_attr( isset( $c['vibe_title'] ) ? $c['vibe_title'] : '' ); ?>" />
				</label>
			</p>
			<p>
				<label><? esc_html_e( 'Пояснение', 'zhiraf-ufa' ); ?><br />
					<textarea class="widefat" rows="2" name="zhiraf_vibe_section[cards][<?php echo (int) $i; ?>][vibe_subtitle]"><?php echo esc_textarea( isset( $c['vibe_subtitle'] ) ? $c['vibe_subtitle'] : '' ); ?></textarea>
				</label>
			</p>
			<p>
				<label><? esc_html_e( 'Файл изображения (assets/images)', 'zhiraf-ufa' ); ?><br />
					<input type="text" class="widefat" name="zhiraf_vibe_section[cards][<?php echo (int) $i; ?>][vibe_image]" value="<?php echo esc_attr( isset( $c['vibe_image'] ) ? $c['vibe_image'] : '' ); ?>" placeholder="resort-example.jpg" />
				</label>
			</p>
			<p>
				<label><? esc_html_e( 'ID вложения (необязательно)', 'zhiraf-ufa' ); ?><br />
					<input type="number" min="0" name="zhiraf_vibe_section[cards][<?php echo (int) $i; ?>][vibe_image_id]" value="<?php echo esc_attr( ! empty( $c['vibe_image_id'] ) ? (int) $c['vibe_image_id'] : '' ); ?>" />
				</label>
			</p>
			<p>
				<label><? esc_html_e( 'Микро-бейдж', 'zhiraf-ufa' ); ?><br />
					<input type="text" class="widefat" name="zhiraf_vibe_section[cards][<?php echo (int) $i; ?>][vibe_tag]" value="<?php echo esc_attr( isset( $c['vibe_tag'] ) ? $c['vibe_tag'] : '' ); ?>" />
				</label>
			</p>
			<p>
				<label><? esc_html_e( 'Акцент', 'zhiraf-ufa' ); ?><br />
					<select name="zhiraf_vibe_section[cards][<?php echo (int) $i; ?>][vibe_color]">
						<?php foreach ( $colors as $color_key ) : ?>
							<option value="<?php echo esc_attr( $color_key ); ?>" <?php selected( isset( $c['vibe_color'] ) ? $c['vibe_color'] : 'teal', $color_key ); ?>><?php echo esc_html( $color_key ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</p>
			<p>
				<label><? esc_html_e( 'vibe_slug (family, couple, city, active)', 'zhiraf-ufa' ); ?><br />
					<input type="text" class="widefat" name="zhiraf_vibe_section[cards][<?php echo (int) $i; ?>][vibe_slug]" value="<?php echo esc_attr( isset( $c['vibe_slug'] ) ? $c['vibe_slug'] : '' ); ?>" />
				</label>
			</p>
		</fieldset>
		<?php
	}
}

/**
 * Save vibe meta box.
 *
 * @param int $post_id Post ID.
 */
function zhiraf_ufa_save_vibe_meta_box( $post_id ) {
	if ( ! isset( $_POST['zhiraf_vibe_section_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zhiraf_vibe_section_nonce'] ) ), 'zhiraf_vibe_section_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}
	$post = get_post( $post_id );
	if ( ! zhiraf_ufa_is_destination_landing_page( $post ) ) {
		return;
	}
	if ( empty( $_POST['zhiraf_vibe_section'] ) || ! is_array( $_POST['zhiraf_vibe_section'] ) ) {
		delete_post_meta( $post_id, '_zhiraf_vibe_section' );
		return;
	}
	$raw = wp_unslash( $_POST['zhiraf_vibe_section'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	if ( ! empty( $raw['cards'] ) && is_array( $raw['cards'] ) ) {
		foreach ( $raw['cards'] as $idx => $card ) {
			if ( empty( $card['enabled'] ) ) {
				unset( $raw['cards'][ $idx ] );
			}
		}
	}
	$section = zhiraf_ufa_sanitize_vibe_section( $raw );
	if ( empty( $section ) ) {
		delete_post_meta( $post_id, '_zhiraf_vibe_section' );
		return;
	}
	update_post_meta( $post_id, '_zhiraf_vibe_section', $section );
}
add_action( 'add_meta_boxes', 'zhiraf_ufa_vibe_meta_box' );
add_action( 'save_post_page', 'zhiraf_ufa_save_vibe_meta_box' );
