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
if ( empty( $members ) ) {
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
					<div class="home-team-card__photo">
						<?php zhiraf_ufa_img( $member['image'], $member['name'], 'home-team-card__img', true ); ?>
					</div>
					<div class="home-team-card__body">
						<h3 class="home-team-card__name"><?php echo esc_html( $member['name'] ); ?></h3>
						<p class="home-team-card__role"><?php echo esc_html( $member['role'] ); ?></p>
						<p class="home-team-card__desc"><?php echo esc_html( $member['desc'] ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="text-center reveal" style="margin-top:24px;">
			<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/o-nas/' ) ); ?>"><?php esc_html_e( 'Вся команда', 'zhiraf-ufa' ); ?></a>
		</p>
	</div>
</section>
