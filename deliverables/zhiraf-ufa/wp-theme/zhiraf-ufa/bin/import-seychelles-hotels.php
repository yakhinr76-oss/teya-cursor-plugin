#!/usr/bin/env php
<?php
/**
 * One-time import: seychelles_hotels_25.json → CPT hotels.
 *
 * @package Zhiraf_Ufa
 */
array_splice( $argv, 1, 0, array( '--country=seychelles' ) );
require __DIR__ . '/import-hotels.php';
