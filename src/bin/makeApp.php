<?php
/**
 * @brief     This file sets up a skeleton app.
 * @details   This creates some standard directories, and some standard files needed, e.g. index.php and MainController.
 *            This should be run from the cli in the /src/bin directory of the site.
 *            Copy the src/config/install_files/app_config.php.txt to /src/config/app_config.php.
 *            The app_config.php file may have any name you want as long as it is in the src/config directory but you
 *            have to then name it at the cli when you call this script, e.g. php makeApp.php myApp_config.php
 * @file      /src/bin/makeApp.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2017-05-25 15:25:26
 * @version   1.0.0
 * @note   <b>Change Log</b>
 * - v1.0.0 - initial version                                      - 2017-05-25 wer
 */
namespace Ritc;

use Ritc\Library\Helper\AutoloadMapper;

if (strpos(__DIR__, 'Library') !== false) {
    die("Please Run this script from the src/bin directory");
}
$base_path = str_replace('/src/bin', '', __DIR__);
define('DEVELOPER_MODE', true);
define('BASE_PATH', $base_path);
define('PUBLIC_PATH', $base_path . '/public');

require_once BASE_PATH . '/src/config/constants.php';

if (!file_exists(APPS_PATH . '/Ritc/Library')) {
    die("You must clone the Ritc/Library in the apps dir first and any other desired apps.\n");
}

$install_files_path = SRC_CONFIG_PATH . '/install_files';

/* allows a custom file to be created. Still must be in src/config dir */
$install_config = SRC_CONFIG_PATH . '/app_config.php';
if (isset($argv[1])) {
    $install_config = SRC_CONFIG_PATH . '/' . $argv[1];
}
if (!file_exists($install_config)) {
    die("You must create the install_files configuration file in " . SRC_CONFIG_PATH . "The default name for the file is install_config.php. You may name it anything but it must then be specified on the command line.\n");
}
$a_install = require_once $install_config;

### Create the directories for the new app ###
$app_path = APPS_PATH . '/' . $a_install['namespace'] . '/' . $a_install['app_name'];

if (!file_exists($app_path)) {
    print "\nCreating the directories for the new app\n";
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
    $a_find = [
        '{NAMESPACE}',
        '{APPNAME}',
        '{namespace}',
        '{app_name}',
        '{controller_name}',
        '{controller_method}',
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
        '',
        '',
        $a_install['author'],
        $a_install['short_author'],
        $a_install['email'],
        date('Y-m-d H:i:s'),
        date('Y-m-d'),
        $a_install['twig_prefix']
    ];

### Create the main controller for the app ###
    print "Creating the main controller for the app\n";
    $a_replace[4] = 'Main';
    $a_replace[5] = file_get_contents($install_files_path . '/main_controller.snippet');
    $controller_text = file_get_contents($install_files_path . '/controller.php.txt');
    $controller_text = str_replace($a_find, $a_replace, $controller_text);
    file_put_contents($app_path . "/Controllers/MainController.php", $controller_text);

### Create the home controller for the app ###
    print "Creating the home controller for the app\n";
    $a_replace[4] = 'Home';
    $a_replace[5] = file_get_contents($install_files_path . '/home_controller.snippet');
    $controller_text = file_get_contents($install_files_path . '/controller.php.txt');
    $controller_text = str_replace($a_find, $a_replace, $controller_text);
    file_put_contents($app_path . "/Controllers/HomeController.php", $controller_text);

### Create the manager controller for the app ###
    print "Creating the manager controller for the app\n";
    $a_replace[4] = 'Manager';
    $a_replace[5] = '';
    $controller_text = file_get_contents($install_files_path . '/ManagerController.php.txt');
    $controller_text = str_replace($a_find, $a_replace, $controller_text);
    file_put_contents($app_path . "/Controllers/ManagerController.php", $controller_text);

### Create the home view for the app ###
    print "Creating the home view for the app\n";
    $view_text = file_get_contents($install_files_path . '/HomeView.php.txt');
    $view_text = str_replace($a_find, $a_replace, $view_text);
    file_put_contents($app_path . "/Views/HomeView.php", $view_text);

### Create the manager view for the app ###
    print "Creating the manager view for the app\n";
    $view_text = file_get_contents($install_files_path . '/ManagerView.php.txt');
    $view_text = str_replace($a_find, $a_replace, $view_text);
    file_put_contents($app_path . "/Views/ManagerView.php", $view_text);

### Create the doxygen config for the app ###
    print "Creating the doxy config for the app\n";
    $doxy_text = file_get_contents($install_files_path . '/doxygen_config.php.txt');
    $doxy_text = str_replace($a_find, $a_replace, $doxy_text);
    file_put_contents($app_path . '/resources/config/doxygen_config.php', $doxy_text);

### Create the twig_config file ###
    print "Creating the twig config file for app\n";
    $twig_file = file_get_contents($install_files_path . '/twig_config.php.txt');
    $new_twig_file = str_replace($a_find, $a_replace, $twig_file);
    file_put_contents($app_path . '/resources/config/twig_config.php', $new_twig_file);

### Copy two main twig files ###
    print "Copying twig files\n";
    $base_twig = '/templates/default/base.twig';
    $resource_path = $app_path . '/resources';
    $twig_text = file_get_contents(SRC_PATH . $base_twig);
    file_put_contents($resource_path . $base_twig, $twig_text);
    $default_templates_path = SRC_PATH . '/templates/pages/';
    $a_default_files = scandir($default_templates_path);
    $pages_path = $resource_path . '/templates/pages/';
    foreach ($a_default_files as $this_file) {
        if ($this_file != '.' && $this_file != '..') {
            $twig_text = file_get_contents($default_templates_path . $this_file);
            file_put_contents($pages_path . $this_file, $twig_text);
        }
    }
} // end creating new directories and files
else {
    print "App at {$app_path} Exists\n";
}

### Regenerate Autoload Map files
require APPS_PATH . '/Ritc/Library/Helper/AutoloadMapper.php';
$a_dirs = [
    'src_path'   => SRC_PATH,
    'config_path' => SRC_CONFIG_PATH,
    'apps_path'   => APPS_PATH];
$o_cm = new AutoloadMapper($a_dirs);
if (!is_object($o_cm)) {
    die("Could not instance AutoloadMapper");
}
$o_cm->generateMapFiles();
?>
