<?php
/**
 * Generates the autoload_classmap.php file that sits in the /app/config directory.
 * This file should sit in the /app/config directory too and run from there.
 */
namespace Ritc\Library\Helper;

ini_set('date.timezone', 'America/Chicago');
$config_path = getcwd();
$app_path    = str_replace('/config', '', $config_path);
$src_path    = $app_path . '/src';
require $src_path . '/Ritc/Library/Helper/ClassMapper.php';

$a_dirs = ['app_path' => $app_path, 'config_path' => $config_path, 'src_path' => $src_path];
$o_cm = new ClassMapper($a_dirs);
$o_cm->generateClassMap();
?>
