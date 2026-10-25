<?php
/**
 * About page expert card (portrait + messenger).
 *
 * @package Zhiraf_Ufa
 *
 * @var array<string,mixed> $member Team roster row.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $member ) && ! empty( $args['member'] ) && is_array( $args['member'] ) ) {
	$member = $args['member'];
}
if ( empty( $member ) || ! is_array( $member ) ) {
	return;
}

$portrait = zhiraf_ufa_team_member_portrait( $member );
$name     = ! empty( $member['name'] ) ? (string) $member['name'] : '';
$role     = ! empty( $member['role'] ) ? (string) $member['role'] : '';
$special  = ! empty( $member['specialty'] ) ? (string) $member['specialty'] : '';
$desc     = ! empty( $member['desc'] ) ? (string) $member['desc'] : '';
$wa_url   = zhiraf_ufa_team_member_whatsapp_url( $member );
$wa_label = ! empty( $member['messenger_label'] ) ? (string) $member['messenger_label'] : __( 'WhatsApp', 'zhiraf-ufa' );
$tg_url   = ! empty( $member['telegram_url'] ) ? (string) $member['telegram_url'] : trim( (string) get_theme_mod( 'zhiraf_telegram_url', '' ) );
?>
<article class="about-team-card reveal">
	<div class="about-team-card__photo">
		<?php if ( '' !== $portrait ) : ?>
			<?php zhiraf_ufa_img( $portrait, $name, 'about-team-card__img', true ); ?>
		<?php else : ?>
			<div class="about-team-card__avatar" aria-hidden="true">
				<span><?php echo esc_html( zhiraf_ufa_team_member_initials( $name ) ); ?></span>
			</div>
		<?php endif; ?>
	</div>
	<div class="about-team-card__body">
		<?php if ( '' !== $name ) : ?>
			<h3 class="about-team-card__name"><?php echo esc_html( $name ); ?></h3>
		<?php endif; ?>
		<?php if ( '' !== $role ) : ?>
			<p class="about-team-card__role"><?php echo esc_html( $role ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $special ) : ?>
			<p class="about-team-card__specialty"><?php echo esc_html( $special ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $desc ) : ?>
			<p class="about-team-card__desc"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== $wa_url || '' !== $tg_url ) : ?>
			<div class="about-team-card__actions">
				<?php
				$allowed_svg = array(
					'svg'  => array(
						'class'       => true,
						'width'       => true,
						'height'      => true,
						'viewbox'     => true,
						'aria-hidden' => true,
						'focusable'   => true,
					),
					'path' => array( 'fill' => true, 'd' => true ),
				);
				?>
				<?php if ( '' !== $wa_url ) : ?>
					<a class="about-team-card__wa zh-social-link zh-social-link--whatsapp" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" data-track="messenger" data-channel="whatsapp">
						<span class="zh-social-link__icon"><?php echo wp_kses( zhiraf_ufa_social_icon_svg( 'whatsapp', 18 ), $allowed_svg ); ?></span>
						<span class="zh-social-link__label"><?php echo esc_html( $wa_label ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( '' !== $tg_url && empty( $member['hide_telegram'] ) ) : ?>
					<a class="about-team-card__tg zh-social-link zh-social-link--telegram" href="<?php echo esc_url( $tg_url ); ?>" target="_blank" rel="noopener noreferrer" data-track="messenger" data-channel="telegram">
						<span class="zh-social-link__icon"><?php echo wp_kses( zhiraf_ufa_social_icon_svg( 'telegram', 18 ), $allowed_svg ); ?></span>
						<span class="zh-social-link__label"><?php esc_html_e( 'Telegram', 'zhiraf-ufa' ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</article>
