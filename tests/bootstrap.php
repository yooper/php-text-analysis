<?php

/**
 * @author yooper
 * Bootstrap file for running unit tests
 */
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);

define('DS', DIRECTORY_SEPARATOR);

// test data files
define('TEST_DATA_DIR',__DIR__.'/data');

defined('TESTS_PATH')
    || define('TESTS_PATH', realpath(dirname(__FILE__)).DS);

error_reporting(E_ALL);
ini_set('display_startup_errors', 1);

define('VENDOR_DIR', 'vendor/');

require_once('vendor/autoload.php');
