<?php
namespace Ritc\Library\Services;

ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/setup.php';
require_once BASE_PATH . '/src/setup.php';

$file_name = BASE_PATH . '/tmp/elog.log';
$o_main_controller = new Tail($file_name);
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
/** @var \Twig_Environment $o_twig */
$html = $o_twig->render('@pages/tail.twig', $a_values);
$any_junk = ob_get_clean();
ob_start();
print $html;
if (defined('DEVELOPER_MODE') && DEVELOPER_MODE) {
    print $any_junk;
}
ob_end_flush();
?>
