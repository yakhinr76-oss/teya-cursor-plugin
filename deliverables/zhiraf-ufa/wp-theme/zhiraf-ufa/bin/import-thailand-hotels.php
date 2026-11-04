#!/usr/bin/env php
<?php
/**
 * One-time import: thailand_hotels_25.json → CPT hotels.
 *
 * @package Zhiraf_Ufa
 */
array_splice( $argv, 1, 0, array( '--country=thailand' ) );
require __DIR__ . '/import-hotels.php';
