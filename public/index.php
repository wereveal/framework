<?php
ob_start();
$rodb      = false;
$allow_get = false;
$app_in    = 'external';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/app/setup.php';
$o_module   = new Ritc\LibraryTester\Controllers\MainController;
$html      = $o_module->renderPage();
$any_junk  = ob_get_clean();
ob_start();
    print $html;
ob_end_flush();
