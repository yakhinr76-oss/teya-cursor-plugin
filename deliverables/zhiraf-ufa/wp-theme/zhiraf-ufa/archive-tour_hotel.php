<?php
/**
 * Tours archive hub — filters by ?country= & ?vibe=.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$country = function_exists( 'zhiraf_ufa_hub_normalize_country_slug' )
	? zhiraf_ufa_hub_normalize_country_slug( (string) get_query_var( 'country' ) )
	: sanitize_title( (string) get_query_var( 'country' ) );
$vibe    = sanitize_key( (string) get_query_var( 'vibe' ) );

get_header();
?>
<main id="primary" class="site-main site-main--tours-hub">
	<section class="section bg-milky">
		<div class="container">
			<h1 class="section-title"><?php esc_html_e( 'База отелей и туров', 'zhiraf-ufa' ); ?></h1>
			<?php if ( $country || $vibe ) : ?>
				<p class="lead">
					<?php
					if ( $country && function_exists( 'zhiraf_ufa_tour_country_name' ) ) {
						echo esc_html( zhiraf_ufa_tour_country_name( $country ) );
					}
					if ( $vibe ) {
						echo $country ? ' · ' : '';
						echo esc_html( $vibe );
					}
					?>
				</p>
			<?php endif; ?>
			<p>
				<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Все направления', 'zhiraf-ufa' ); ?></a>
			</p>
		</div>
	</section>
</main>
<?php
get_footer();
