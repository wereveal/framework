<?php
ob_start();
/**
 * Three possible ways of doing this:
 *
 * ## Multi-site configuration as shown below ##
 * - See /public/example_setup.php file for examples for the $_SERVER['DOCUMENT_ROOT'] . '/setup.php' file.
 * - Most frequent use of this is with development, test and production sites.
 *
 * ## Single Site using a non-default layout ##
 * - replace the two require_once lines with the following
 * define('PUBLIC_PATH', $_SERVER['DOCUMENT_ROOT'] . '/subdirectory/public);
 * define('PUBLIC_DIR', '/subdirectory/public');
 * define('BASE_PATH', '/subdirectory');
 * require_once BASE_PATH . '/src/setup.php';
 *
 * ## Single site using default layout ##
 * - replace the two require_once lines with the following
 * require_once $_SERVER['DOCUMENT_ROOT'] . '/../site/setup.php';
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/setup.php';
require_once BASE_PATH . '/src/setup.php';

$o_main_controller = new Example\App\Controllers\MainController($o_di);
$html     = $o_main_controller->renderPage();
$flotsam = ob_get_clean();
ob_start();
    print $html;
    if (DEVELOPER_MODE) {
        print $flotsam;
    }
ob_end_flush();
