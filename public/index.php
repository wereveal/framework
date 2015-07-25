<?php
ob_start();
define('DEVELOPER_MODE', true);
error_log("HTTP_HOST: " . $_SERVER['HTTP_HOST']);
switch ($_SERVER['HTTP_HOST']) {
    case 'framework':
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('PUBLIC_DIR', '');
        $app_in = dirname(SITE_PATH);
        break;
    case 'fw.qca.net':
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('PUBLIC_DIR', '/');
        $app_in = dirname(SITE_PATH);
        break;
    default:
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('PUBLIC_DIR', '');
        $app_in = SITE_PATH;
}
require_once $app_in . '/app/setup.php';

$o_main_controller = new Example\App\Controllers\MainController($o_di);
$html     = $o_main_controller->renderPage();
$any_junk = ob_get_clean();
ob_start();
    print $html;
ob_end_flush();
