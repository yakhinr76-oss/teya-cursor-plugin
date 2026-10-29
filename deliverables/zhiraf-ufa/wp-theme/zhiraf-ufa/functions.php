<?php
/**
 * Zhiraf Ufa theme bootstrap.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZHIRAF_UFA_VERSION', '1.0.13-staging' );
define( 'ZHIRAF_UFA_DIR', get_template_directory() );
define( 'ZHIRAF_UFA_URI', get_template_directory_uri() );

require_once ZHIRAF_UFA_DIR . '/inc/setup.php';
require_once ZHIRAF_UFA_DIR . '/inc/logo.php';
require_once ZHIRAF_UFA_DIR . '/inc/enqueues.php';
require_once ZHIRAF_UFA_DIR . '/inc/assets.php';
require_once ZHIRAF_UFA_DIR . '/inc/destination-landings-moscow-content.php';
require_once ZHIRAF_UFA_DIR . '/inc/destination-landings-moscow.php';
require_once ZHIRAF_UFA_DIR . '/inc/destination-landings.php';
require_once ZHIRAF_UFA_DIR . '/inc/vibe-cards.php';
require_once ZHIRAF_UFA_DIR . '/inc/destination-landing-config.php';
require_once ZHIRAF_UFA_DIR . '/inc/destination-landing-standard.php';
require_once ZHIRAF_UFA_DIR . '/inc/activation.php';
require_once ZHIRAF_UFA_DIR . '/inc/tour-routing.php';
require_once ZHIRAF_UFA_DIR . '/inc/resort-landings.php';
require_once ZHIRAF_UFA_DIR . '/inc/offer-landings.php';
require_once ZHIRAF_UFA_DIR . '/inc/hot-tours-landing.php';
require_once ZHIRAF_UFA_DIR . '/inc/all-inclusive-landing.php';
require_once ZHIRAF_UFA_DIR . '/inc/about-page.php';
require_once ZHIRAF_UFA_DIR . '/inc/social-icons.php';
require_once ZHIRAF_UFA_DIR . '/inc/seo.php';
require_once ZHIRAF_UFA_DIR . '/inc/customizer.php';
require_once ZHIRAF_UFA_DIR . '/inc/breadcrumbs.php';
require_once ZHIRAF_UFA_DIR . '/inc/security.php';
require_once ZHIRAF_UFA_DIR . '/inc/forms.php';
require_once ZHIRAF_UFA_DIR . '/inc/redirects.php';
require_once ZHIRAF_UFA_DIR . '/inc/class-uon-crm.php';
require_once ZHIRAF_UFA_DIR . '/inc/bg-api.php';
require_once ZHIRAF_UFA_DIR . '/inc/ta-api-client.php';
require_once ZHIRAF_UFA_DIR . '/inc/cpt-hotels.php';
require_once ZHIRAF_UFA_DIR . '/inc/tours.php';
