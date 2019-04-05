<?php
/**
 * Generates the autoload_classmap.php file that sits in the /src/config directory.
 * This file should sit in the /src/bin directory run from there, e.g., php makeAutoloadFiles.php.
 */
namespace Ritc\Library\Helper;

ini_set('date.timezone', 'America/Chicago');
if (strpos(__DIR__, '/src/bin') === false) {
    die("Please Run this script from the /src/bin directory\n");
}
$base_path = str_replace('/src/bin', '', __DIR__);
$src_path    = $base_path . '/src';
$apps_path   = $src_path . '/apps';
$config_path = $src_path . '/config';
require $apps_path . '/Ritc/Library/Helper/AutoloadMapper.php';

$a_dirs = [
    'src_path'    => $src_path,
    'config_path' => $config_path,
    'apps_path'   => $apps_path];
$o_cm = new AutoloadMapper($a_dirs);
if (!is_object($o_cm)) {
    die('Could not instance AutoloadMapper');
}
// print $o_cm->getAppPath() . "\n";
// print $o_cm->getConfigPath() . "\n";
// print $o_cm->getAppsPath() . "\n";
$o_cm->generateMapFiles();

