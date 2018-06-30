<?php
namespace Ritc\Library\Services;

ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/setup.php';
require_once BASE_PATH . '/src/setup.php';

$file_name = BASE_PATH . '/logs/elog.log';
/** @var Di $o_di */
$o_di->setVar('file_name', $file_name);
$o_main_controller = new Tail($o_di);
$o_main_controller->setNewestFirst(false);
$o_main_controller->setNumberOfLines(40);
$log_output = $o_main_controller->output();
$a_values = [
    'lang'        => 'en',
    'charset'     => 'utf8',
    'title'       => 'Tail Log File',
    'description' => 'Tails a log file.',
    'public_dir'  => PUBLIC_DIR,
    'content'     => $log_output
];
try {
    /** @var \Twig_Environment $o_twig */
    $html = $o_twig->render('@pages/tail.twig', $a_values);
}
catch (\Twig_Error_Loader $e) {
    $html = $e->getMessage();
}
catch (\Twig_Error_Runtime $e) {
    $html = $e->getMessage();
}
catch (\Twig_Error_Syntax $e) {
    $html = $e->getMessage();
}
$any_junk = ob_get_clean();
ob_start();
print $html;
if (\defined('DEVELOPER_MODE') && DEVELOPER_MODE) {
    print $any_junk;
}
ob_end_flush();
