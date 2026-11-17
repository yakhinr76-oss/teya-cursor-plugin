#!/usr/bin/env php
<?php
/**
 * One-time import: russia_hotels_50.json → CPT hotels.
 *
 * @package Zhiraf_Ufa
 */
array_splice( $argv, 1, 0, array( '--country=russia' ) );
require __DIR__ . '/import-hotels.php';
