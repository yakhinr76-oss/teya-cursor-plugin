<?php
/**
 * Resort VS battle block (central module).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $args ) || ! is_array( $args ) ) {
	$args = array();
}
$title   = ! empty( $args['title'] ) ? (string) $args['title'] : __( 'Сравнение курортов', 'zhiraf-ufa' );
$lead    = ! empty( $args['lead'] ) ? (string) $args['lead'] : __( 'Два главных формата отдыха — выберите свой, мы подберём отели под критерии.', 'zhiraf-ufa' );
$columns = ! empty( $args['columns'] ) && is_array( $args['columns'] ) ? $args['columns'] : array();
$wa_text = ! empty( $args['wa_text'] ) ? (string) $args['wa_text'] : __( 'Здравствуйте! Помогите выбрать курорт', 'zhiraf-ufa' );
if ( count( $columns ) < 2 ) {
	return;
}
?>
<section class="section section--dest-module section--dest-battle bg-milky" id="dest-module">
	<div class="container">
		<div class="section-head reveal">
			<p class="dest-battle__eyebrow"><?php esc_html_e( 'Гид турагентства «Жираф»', 'zhiraf-ufa' ); ?></p>
			<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
			<?php if ( '' !== $lead ) : ?>
				<p class="lead dest-battle__lead"><?php echo esc_html( $lead ); ?></p>
			<?php endif; ?>
		</div>
		<div class="dest-resort-compare dest-resort-compare--module dest-resort-compare--battle">
			<span class="dest-battle__vs" aria-hidden="true">VS</span>
			<?php foreach ( $columns as $col ) : ?>
				<article class="dest-resort-compare__col reveal">
					<?php if ( ! empty( $col['image'] ) ) : ?>
						<?php zhiraf_ufa_img( $col['image'], $col['title'] ?? '', 'dest-resort-compare__img', true ); ?>
					<?php endif; ?>
					<div class="dest-resort-compare__body">
						<div class="dest-battle__col-head">
							<?php if ( ! empty( $col['tag'] ) ) : ?>
								<p class="dest-resort-compare__tag"><?php echo esc_html( $col['tag'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $col['meta'] ) ) : ?>
								<p class="dest-battle__meta"><?php echo esc_html( $col['meta'] ); ?></p>
							<?php endif; ?>
						</div>
						<h3 class="dest-resort-compare__title"><?php echo esc_html( $col['title'] ?? '' ); ?></h3>
						<?php if ( ! empty( $col['subtitle'] ) ) : ?>
							<p class="dest-battle__subtitle"><?php echo esc_html( $col['subtitle'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $col['rows'] ) && is_array( $col['rows'] ) ) : ?>
							<dl class="dest-resort-compare__rows">
								<?php foreach ( $col['rows'] as $row ) : ?>
									<div>
										<dt><?php echo esc_html( $row['label'] ?? '' ); ?></dt>
										<dd><?php echo esc_html( $row['value'] ?? '' ); ?></dd>
									</div>
								<?php endforeach; ?>
							</dl>
						<?php endif; ?>
						<?php if ( ! empty( $col['cta_label'] ) && ! empty( $col['cta_href'] ) ) : ?>
							<a class="btn btn--primary btn--sm dest-battle__cta" href="<?php echo esc_url( $col['cta_href'] ); ?>">
								<?php echo esc_html( $col['cta_label'] ); ?>
							</a>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="dest-battle__hint">
			<?php
			printf(
				wp_kses_post( __( 'Сомневаетесь? <a href="%1$s" data-track="messenger">Напишите в WhatsApp</a> — подскажем отели с проверенным питанием и пляжем.', 'zhiraf-ufa' ) ),
				esc_url( 'https://wa.me/79018105018?text=' . rawurlencode( $wa_text ) )
			);
			?>
		</p>
	</div>
</section>
