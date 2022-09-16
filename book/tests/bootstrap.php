<?php
$_tests_dir = getenv('WP_TESTS_DIR');

if (! $_tests_dir) {
    $_tests_dir = 'C:\wamp64\tmp\wordpress-tests-lib';
}

if (! file_exists($_tests_dir . '/includes/functions.php')) {
    echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL;
    exit(1);
}

/* PHPUNIT requires polyfills from now on */
defined('WP_TESTS_PHPUNIT_POLYFILLS_PATH') || define('WP_TESTS_PHPUNIT_POLYFILLS_PATH', rtrim($_tests_dir, '\\/') . '/libs/PHPUnit-Polyfills-1.0.2/');

define('TVE_DEBUG', true);

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';
require "{$_tests_dir}/includes/bootstrap.php";
