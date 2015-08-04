<?php
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $app_in . '/app/setup.php';

$o_main_controller = new Example\App\Controllers\MainController($o_di);
$html     = $o_main_controller->renderPage();
$any_junk = ob_get_clean();
ob_start();
    print $html;
ob_end_flush();
