<?php
ob_start();
/**
 * Two possible ways of doing this:
 *
 * Complex Configuration.
 * The setup file creates needed vars based on $_SERVER['HTTP_HOST'] value.
 * See setup file for examples.
 *
 * require_once $_SERVER['DOCUMENT_ROOT'] . '/setup.php';
 * require_once BASE_PATH . '/app/setup.php';
 *
 * Simple Configuration.
 * Assumes a standard layout of files and locations.
 *
 * require_once $_SERVER['DOCUMENT_ROOT'] . '/../app/setup.php';
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/setup.php';
require_once BASE_PATH . '/app/setup.php';

$o_main_controller = new Example\App\Controllers\MainController($o_di);
$html     = $o_main_controller->renderPage();
$any_junk = ob_get_clean();
ob_start();
    print $html;
    if (DEVELOPER_MODE) {
        print $any_junk;
    }
ob_end_flush();
