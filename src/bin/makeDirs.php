<?php
/**
 * @brief     This file creates a skeleton of directories and files for an app.
 * @file      /src/bin/makeDirs.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2017-04-18 14:04:15
 * @version   1.0.0
 * @note   <b>Change Log</b>
 * - v1.0.0 - initial version                                      - 2017-04-18 wer
 */
namespace Ritc;

if (strpos(__DIR__, 'Library') !== false) {
    die("Please Run this script from the src/bin directory");
}

$base_path = str_replace('/src/bin', '', __DIR__);
define('DEVELOPER_MODE', true);
define('BASE_PATH', $base_path);
define('PUBLIC_PATH', $base_path . '/public');

echo 'Base Path: ' . BASE_PATH . "\n";
echo 'Public Path: ' . PUBLIC_PATH . "\n";

require_once BASE_PATH . '/src/config/constants.php';

if (!file_exists(APPS_PATH . '/Ritc/Library')) {
    die("You must clone the Ritc/Library in the apps dir first and any other desired apps.\n");
}

/* allows a custom file to be created. Still must be in src/config/install_files dir */
$install_file = SRC_CONFIG_PATH . '/install_config.php';
if (isset($argv[1])) {
    $install_file = SRC_CONFIG_PATH . '/' . $argv[1];
}
if (!file_exists($install_file)) {
    die("You must create the install_files configuration file in " . SRC_CONFIG_PATH . "The default name for the file is install_config.php. You may name it anything but it must then be specified on the command line.\n");
}
$a_install = require_once $install_file;

### Create the directories for the new app ###
print "\nCreateing the directories for the new app\n";
$app_path = APPS_PATH . '/' . $a_install['namespace'] . '/' . $a_install['app_name'];
$a_new_dirs = [
    'Abstracts',
    'Controllers',
    'Entities',
    'Interfaces',
    'Models',
    'Tests',
    'Traits',
    'Views',
    'resources',
    'resources/config',
    'resources/sql',
    'resources/templates',
    'resources/templates/default',
    'resources/templates/elements',
    'resources/templates/pages',
    'resources/templates/forms',
    'resources/templates/snippets',
    'resources/templates/tests'
];

$htaccess_text =<<<EOF
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
    Order deny,allow
    Deny from all
</IfModule>
EOF;

$keep_me_text =<<<EOF
Place Holder
EOF;

$tpl_text = "<h3>An Error Has Occurred</h3>";

if (!file_exists($app_path)) {
    mkdir($app_path, 0755, true);
    file_put_contents($app_path . '/.htaccess', $htaccess_text);
    foreach($a_new_dirs as $dir) {
        $new_dir = $app_path . '/' . $dir;
        $new_file = $new_dir . '/.keepme';
        $new_tpl = $new_dir . '/no_file.twig';
        mkdir($app_path . '/' . $dir, 0755, true);
        if (strpos($dir, 'templates') !== false) {
            file_put_contents($new_tpl, $tpl_text);
        }
        else {
            file_put_contents($new_file, $keep_me_text);
        }
    }
}

$a_find = [
    '{NAMESPACE}',
    '{APPNAME}',
    '{namespace}',
    '{app_name}',
    '{controller_name}',
    '{author}',
    '{sauthor}',
    '{email}',
    '{idate}',
    '{sdate}',
    '{twig_prefix}'
];
$a_replace = [
    $a_install['namespace'],
    $a_install['app_name'],
    strtolower($a_install['namespace']),
    strtolower($a_install['app_name']),
    $a_install['app_name'],
    $a_install['author'],
    $a_install['short_author'],
    $a_install['email'],
    date('Y-m-d H:i:s'),
    date('Y-m-d'),
    $a_install['twig_prefix']
];

### Create the main controller for the app ###
print "Creating the main controller for the app\n";
$controller_text = file_get_contents(SRC_CONFIG_PATH . '/install_files/controller.php.txt');
$controller_text = str_replace($a_find, $a_replace, $controller_text);
file_put_contents($app_path . "/Controllers/{$a_install['app_name']}Controller.php", $controller_text);

### Create the home controller for the app ###
print "Creating the home controller for the app\n";
$a_replace[4] = 'Home';
$controller_text = file_get_contents(SRC_CONFIG_PATH . '/install_files/controller.php.txt');
$controller_text = str_replace($a_find, $a_replace, $controller_text);
file_put_contents($app_path . "/Controllers/HomeController.php", $controller_text);

### Create the main view for the app ###
print "Creating the main view for the app\n";
$view_text = file_get_contents(SRC_CONFIG_PATH . '/install_files/home_view.txt');
$view_text = str_replace($a_find, $a_replace, $view_text);
file_put_contents($app_path . "/Views/{$a_install['app_name']}View.php", $view_text);

### Create the doxygen config for the app ###
print "Creating the doxy config for the app\n";
$doxy_text = file_get_contents(SRC_CONFIG_PATH . '/install_files/doxygen_config.php.txt');
$doxy_text = str_replace($a_find, $a_replace, $doxy_text);
file_put_contents($app_path . '/resources/config/doxygen_config.php', $doxy_text);

### Create the twig_config file ###
print "Creating the twig config file for app\n";
$twig_file = file_get_contents(SRC_CONFIG_PATH . '/install_files/twig_config.php.txt');
$new_twig_file = str_replace($a_find, $a_replace, $twig_file);
file_put_contents($app_path . '/resources/config/twig_config.php', $new_twig_file);

### Copy two main twig files ###
print "Copying twig files\n";
$first_file = '/resources/templates/default/base.twig';
$second_file = '/resources/templates/pages/index.twig';
$twig_text = file_get_contents(LIBRARY_PATH . $first_file);
file_put_contents($app_path . $first_file, $twig_text);
$twig_text = file_get_contents(LIBRARY_PATH . $second_file);
file_put_contents($app_path . $second_file, $twig_text);

### Create the index.php file ###
print "Creating the index.php file for app\n";
$index_text = file_get_contents(SRC_CONFIG_PATH . '/install_files/index.php.txt');
$index_text = str_replace($a_find, $a_replace, $index_text);
file_put_contents(PUBLIC_PATH . '/index.php', $index_text);

?>
