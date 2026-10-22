<?php
/**
 * Homepage team teaser (2–3 experts).
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$members = zhiraf_ufa_home_team_teaser();
if ( empty( $members ) || ! is_array( $members ) ) {
	return;
}
?>
<section class="section section--home-team bg-milky" id="home-team">
	<div class="container">
		<div class="section-head reveal">
			<h2 class="section-title"><?php esc_html_e( 'Кто подберёт ваш тур', 'zhiraf-ufa' ); ?></h2>
			<p class="lead"><?php esc_html_e( 'Семейное агентство в Уфе с 2011 года — живые менеджеры, а не безликий поиск.', 'zhiraf-ufa' ); ?></p>
		</div>
		<div class="home-team-grid">
			<?php foreach ( $members as $member ) : ?>
				<article class="home-team-card reveal">
					<?php
					$photo = ! empty( $member['image'] ) ? (string) $member['image'] : '';
					$label = ! empty( $member['name'] ) ? (string) $member['name'] : ( ! empty( $member['role'] ) ? (string) $member['role'] : __( 'Менеджер', 'zhiraf-ufa' ) );
					?>
					<div class="home-team-card__photo">
						<?php if ( '' !== $photo ) : ?>
							<?php zhiraf_ufa_img( $photo, $label, 'home-team-card__img', true ); ?>
						<?php else : ?>
							<div class="home-team-card__placeholder" aria-hidden="true"></div>
						<?php endif; ?>
					</div>
					<div class="home-team-card__body">
						<?php if ( ! empty( $member['name'] ) ) : ?>
							<h3 class="home-team-card__name"><?php echo esc_html( $member['name'] ); ?></h3>
							<?php if ( ! empty( $member['role'] ) ) : ?>
								<p class="home-team-card__role"><?php echo esc_html( $member['role'] ); ?></p>
							<?php endif; ?>
						<?php elseif ( ! empty( $member['role'] ) ) : ?>
							<h3 class="home-team-card__name"><?php echo esc_html( $member['role'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $member['desc'] ) ) : ?>
							<p class="home-team-card__desc"><?php echo esc_html( $member['desc'] ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="text-center reveal" style="margin-top:24px;">
			<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/o-nas/' ) ); ?>"><?php esc_html_e( 'Вся команда', 'zhiraf-ufa' ); ?></a>
		</p>
	</div>
</section>
