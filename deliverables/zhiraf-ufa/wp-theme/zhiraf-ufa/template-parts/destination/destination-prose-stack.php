<?php
/**
 * Destination landing SEO / prose stack.
 *
 * @package Zhiraf_Ufa
 * @var array $args Passed via get_template_part().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dest = isset( $args['dest'] ) ? $args['dest'] : array();
$central_module = isset( $args['central_module'] ) ? $args['central_module'] : 'battle';
$dest_show_resort_compare_in_prose = ! empty( $args['dest_show_resort_compare_in_prose'] );
if ( empty( $dest ) ) {
	return;
}
?>
	<section class="section dest-landing-prose bg-milky"<?php echo ! empty( $dest['needs_review'] ) ? ' data-needs-review="1"' : ''; ?>>
		<?php if ( ! empty( $dest['needs_review'] ) ) : ?><!-- ТЕКСТ НА ПРОВЕРКУ: редакторская проверка перед публикацией --><?php endif; ?>
		<div class="container container--narrow">
			<?php if ( ! empty( $dest['season_at_a_glance'] ) && is_array( $dest['season_at_a_glance'] ) && ! empty( $dest['season_at_a_glance']['rows'] ) ) : ?>
				<h2 class="section-title dest-season-glance__title"><?php echo esc_html( ! empty( $dest['season_at_a_glance']['title'] ) ? $dest['season_at_a_glance']['title'] : __( 'Когда куда лететь', 'zhiraf-ufa' ) ); ?></h2>
				<table class="dest-season-glance">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Курорт', 'zhiraf-ufa' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Лучший сезон для купания', 'zhiraf-ufa' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $dest['season_at_a_glance']['rows'] as $row ) : ?>
							<tr>
								<td><?php echo esc_html( $row['resort'] ?? '' ); ?></td>
								<td><?php echo esc_html( $row['when'] ?? '' ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<?php if ( ! empty( $dest['seo_prose_accordions'] ) ) : ?>
				<details class="dest-prose-accordion dest-prose-accordion--seo">
					<summary class="dest-prose-accordion__summary">
						<?php
						echo esc_html(
							! empty( $dest['prose_accordion_title'] )
								? $dest['prose_accordion_title']
								: __( 'Подробнее о регионах, сезоне и пакете', 'zhiraf-ufa' )
						);
						?>
					</summary>
					<div class="dest-prose-accordion__body">
						<?php if ( ! empty( $dest['hero_lead_extended'] ) ) : ?>
							<p><?php echo esc_html( $dest['hero_lead_extended'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['geo_answer'] ) ) : ?>
							<p><?php echo esc_html( $dest['geo_answer'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['for_who'] ) ) : ?>
							<h3><?php esc_html_e( 'Для кого подойдёт', 'zhiraf-ufa' ); ?></h3>
							<p><?php echo esc_html( $dest['for_who'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['we_clarify'] ) ) : ?>
							<h3><?php esc_html_e( 'Что уточняем при подборе', 'zhiraf-ufa' ); ?></h3>
							<p><?php echo esc_html( $dest['we_clarify'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['season'] ) ) : ?>
							<h3><?php esc_html_e( 'Пляжный сезон', 'zhiraf-ufa' ); ?></h3>
							<p><?php echo esc_html( $dest['season'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $dest['climate_seasons'] ) && is_array( $dest['climate_seasons'] ) ) : ?>
							<h3><?php esc_html_e( 'Сезон и климат по месяцам', 'zhiraf-ufa' ); ?></h3>
							<div class="dest-climate-grid dest-climate-grid--accordion">
								<?php foreach ( $dest['climate_seasons'] as $season_row ) : ?>
									<article class="dest-climate-card">
										<p class="dest-climate-card__label"><?php echo esc_html( $season_row['label'] ); ?> <span class="dest-climate-card__months"><?php echo esc_html( $season_row['months'] ); ?></span></p>
										<dl class="dest-climate-card__stats">
											<div><dt><?php esc_html_e( 'Воздух', 'zhiraf-ufa' ); ?></dt><dd><?php echo esc_html( $season_row['air'] ); ?></dd></div>
											<div><dt><?php esc_html_e( 'Вода', 'zhiraf-ufa' ); ?></dt><dd><?php echo esc_html( $season_row['water'] ); ?></dd></div>
										</dl>
										<?php if ( ! empty( $season_row['tip'] ) ) : ?>
											<p class="dest-climate-card__tip"><?php echo esc_html( $season_row['tip'] ); ?></p>
										<?php endif; ?>
									</article>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</details>
			<?php else : ?>
				<?php if ( ! empty( $dest['hero_lead_extended'] ) ) : ?>
					<p class="dest-landing-prose__intro"><?php echo esc_html( $dest['hero_lead_extended'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $dest['for_who'] ) ) : ?>
					<h2><?php esc_html_e( 'Для кого подойдёт', 'zhiraf-ufa' ); ?></h2>
					<p><?php echo esc_html( $dest['for_who'] ); ?></p>
					<h2><?php esc_html_e( 'Что уточняем при подборе', 'zhiraf-ufa' ); ?></h2>
					<p><?php echo esc_html( $dest['we_clarify'] ); ?></p>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( empty( $dest['seo_prose_accordions'] ) && 'season' !== $central_module && ! empty( $dest['climate_seasons'] ) && is_array( $dest['climate_seasons'] ) ) : ?>
				<h2><?php esc_html_e( 'Сезон и климат', 'zhiraf-ufa' ); ?></h2>
				<div class="dest-climate-grid">
					<?php foreach ( $dest['climate_seasons'] as $season_row ) : ?>
						<article class="dest-climate-card">
							<p class="dest-climate-card__label"><?php echo esc_html( $season_row['label'] ); ?> <span class="dest-climate-card__months"><?php echo esc_html( $season_row['months'] ); ?></span></p>
							<dl class="dest-climate-card__stats">
								<div><dt><?php esc_html_e( 'Воздух', 'zhiraf-ufa' ); ?></dt><dd><?php echo esc_html( $season_row['air'] ); ?></dd></div>
								<div><dt><?php esc_html_e( 'Вода', 'zhiraf-ufa' ); ?></dt><dd><?php echo esc_html( $season_row['water'] ); ?></dd></div>
							</dl>
							<?php if ( ! empty( $season_row['tip'] ) ) : ?>
								<p class="dest-climate-card__tip"><?php echo esc_html( $season_row['tip'] ); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php elseif ( empty( $dest['seo_prose_accordions'] ) && ! empty( $dest['season'] ) ) : ?>
				<h2><?php esc_html_e( 'Сезон и климат', 'zhiraf-ufa' ); ?></h2>
				<p><?php echo esc_html( $dest['season'] ); ?></p>
			<?php endif; ?>

			<?php if ( $dest_show_resort_compare_in_prose && is_array( $dest['resort_compare'] ) ) : ?>
				<h2><?php echo esc_html( ! empty( $dest['resort_compare_title'] ) ? $dest['resort_compare_title'] : __( 'Нячанг или Фукуок: что выбрать', 'zhiraf-ufa' ) ); ?></h2>
				<div class="dest-resort-compare">
					<?php foreach ( $dest['resort_compare'] as $col ) : ?>
						<article class="dest-resort-compare__col">
							<?php if ( ! empty( $col['image'] ) ) : ?>
								<?php zhiraf_ufa_img( $col['image'], $col['title'], 'dest-resort-compare__img', true ); ?>
							<?php endif; ?>
							<div class="dest-resort-compare__body">
								<?php if ( ! empty( $col['tag'] ) ) : ?>
									<p class="dest-resort-compare__tag"><?php echo esc_html( $col['tag'] ); ?></p>
								<?php endif; ?>
								<h3 class="dest-resort-compare__title"><?php echo esc_html( $col['title'] ); ?></h3>
								<?php if ( ! empty( $col['rows'] ) && is_array( $col['rows'] ) ) : ?>
									<dl class="dest-resort-compare__rows">
										<?php foreach ( $col['rows'] as $row ) : ?>
											<div>
												<dt><?php echo esc_html( $row['label'] ); ?></dt>
												<dd><?php echo esc_html( $row['value'] ); ?></dd>
											</div>
										<?php endforeach; ?>
									</dl>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! in_array( $central_module, array( 'vibe', 'battle' ), true ) && ! empty( $dest['emirate_cards'] ) && is_array( $dest['emirate_cards'] ) ) : ?>
				<h2><?php echo esc_html( ! empty( $dest['emirate_cards_title'] ) ? $dest['emirate_cards_title'] : __( 'Какой эмират выбрать для отдыха из Уфы', 'zhiraf-ufa' ) ); ?></h2>
				<div class="dest-emirate-grid">
					<?php foreach ( $dest['emirate_cards'] as $card ) : ?>
						<article class="dest-emirate-card">
							<?php if ( ! empty( $card['image'] ) ) : ?>
								<?php zhiraf_ufa_img( $card['image'], $card['title'], 'dest-emirate-card__img', true ); ?>
							<?php endif; ?>
							<div class="dest-emirate-card__body">
								<?php if ( ! empty( $card['tag'] ) ) : ?>
									<p class="dest-emirate-card__tag"><?php echo esc_html( $card['tag'] ); ?></p>
								<?php endif; ?>
								<h3 class="dest-emirate-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
								<p class="dest-emirate-card__text"><?php echo esc_html( $card['text'] ); ?></p>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $dest['extra_sections'] ) ) : ?>
				<?php foreach ( $dest['extra_sections'] as $section ) : ?>
					<details class="dest-prose-accordion">
						<summary class="dest-prose-accordion__summary"><?php echo esc_html( $section['h2'] ); ?></summary>
						<div class="dest-prose-accordion__body">
							<?php if ( ! empty( $section['lead'] ) ) : ?>
								<p><?php echo esc_html( $section['lead'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $section['paragraphs'] ) ) : ?>
								<?php foreach ( $section['paragraphs'] as $para ) : ?>
									<p><?php echo wp_kses_post( $para ); ?></p>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</details>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( ! empty( $dest['resort_links'] ) ) : ?>
				<h2><?php esc_html_e( 'Курорты направления', 'zhiraf-ufa' ); ?></h2>
				<ul class="resort-link-list">
					<?php foreach ( $dest['resort_links'] as $link ) : ?>
						<li>
							<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
							<?php if ( ! empty( $link['desc'] ) ) : ?>
								<span class="resort-link-list__desc"><?php echo esc_html( $link['desc'] ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( ! empty( $dest['blog_links'] ) ) : ?>
				<p class="dest-landing-links">
					<?php
					foreach ( $dest['blog_links'] as $link ) {
						printf(
							'<a href="%s">%s</a>',
							esc_url( $link['url'] ),
							esc_html( $link['label'] )
						);
					}
					?>
				</p>
			<?php endif; ?>
		</div>
	</section>
