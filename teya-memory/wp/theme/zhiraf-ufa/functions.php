<?php
/**
 * Zhiraf Ufa theme bootstrap.
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZHIRAF_UFA_VERSION', '1.0.0' );
define( 'ZHIRAF_UFA_DIR', get_template_directory() );
define( 'ZHIRAF_UFA_URI', get_template_directory_uri() );

require_once ZHIRAF_UFA_DIR . '/inc/setup.php';
require_once ZHIRAF_UFA_DIR . '/inc/logo.php';
require_once ZHIRAF_UFA_DIR . '/inc/enqueues.php';
require_once ZHIRAF_UFA_DIR . '/inc/assets.php';
require_once ZHIRAF_UFA_DIR . '/inc/destination-landings.php';
require_once ZHIRAF_UFA_DIR . '/inc/seo.php';
require_once ZHIRAF_UFA_DIR . '/inc/customizer.php';
require_once ZHIRAF_UFA_DIR . '/inc/breadcrumbs.php';
require_once ZHIRAF_UFA_DIR . '/inc/security.php';
require_once ZHIRAF_UFA_DIR . '/inc/forms.php';
require_once ZHIRAF_UFA_DIR . '/inc/redirects.php';
require_once ZHIRAF_UFA_DIR . '/inc/class-uon-crm.php';
