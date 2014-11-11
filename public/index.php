<?php
ob_start();
$rodb      = false;
$allow_get = false;
$app_in    = 'external';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/app/setup.php';
$o_control   = new Ritc\LibraryTester\Controllers\MainController($o_session, $o_db);
$o_control->setElog($o_elog);
$html      = $o_control->render();
$any_junk  = ob_get_clean();
ob_start();
    print $html;
ob_end_flush();
