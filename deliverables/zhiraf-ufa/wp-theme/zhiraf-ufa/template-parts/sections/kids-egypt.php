<?php
/**
 * @deprecated Use template-parts/sections/intent-checklist.php
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$block = zhiraf_ufa_resolve_intent_block( 'egipet', array() );
if ( $block ) {
	get_template_part( 'template-parts/sections/intent-checklist', null, array( 'dest_slug' => 'egipet', 'block' => $block ) );
}
