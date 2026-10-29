#!/usr/bin/env php
<?php
/**
 * @deprecated Use bin/import-hotels.php --country=turkey
 *
 * @package Zhiraf_Ufa
 */
array_splice( $argv, 1, 0, array( '--country=turkey' ) );
require __DIR__ . '/import-hotels.php';
