<?php
/** @noinspection PhpUndefinedVariableInspection
 * @noinspection PhpUndefinedNamespaceInspection
 * @noinspection PhpUndefinedClassInspection
 */
ob_start();
/**
 * Couple possible ways of doing this:
 *
 * ## Safe site configuration as shown below ##
 * - See /public/example_setup.php file for examples for the $_SERVER['DOCUMENT_ROOT'] . '/setup.php' file.
 * - Most frequent use of this is with development, test and production sites.
 *
 * ## Site using a non-default layout ##
 * - replace the two require_once lines with the following
 * define('PUBLIC_PATH', /path/to/public_name);
 * define('PUBLIC_DIR', 'public_name if not / which would be empty');
 * define('BASE_PATH', '/path/to/dir/containing_public');
 * require_once BASE_PATH . '/src/setup.php';
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/setup.php';
require_once BASE_PATH . '/src/setup.php';

$o_main_controller = new Example\App\Controllers\MainController($o_di); // $o_di set in setup.php
$o_di->set('WebController', $o_main_controller);
$html     = $o_main_controller->renderPage();
$flotsam = ob_get_clean();
ob_start();
    print $html;
    if (DEVELOPER_MODE && !empty(trim($flotsam))) {
        print '<pre>';
        print $flotsam;
        print '</pre>';
    }
ob_end_flush();
