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

use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

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
$install_config = SRC_CONFIG_PATH . '/install_config.php';
if (isset($argv[1])) {
    $install_config = SRC_CONFIG_PATH . '/' . $argv[1];
}
if (!file_exists($install_config)) {
    $message = "You must create the install_files configuration file in "
               . SRC_CONFIG_PATH
               . ". The default name for the file is app_config.php. 
               You may name it anything but it must then be specified on the command line.\n";
    die($message);
}
$a_install = require_once $install_config;
$app_path = APPS_PATH . '/' . $a_install['namespace'] . '/' . $a_install['app_name'];


#### Add app twig config to arrays if the prefix is set ####
if (!empty($a_install['app_twig_prefix']) && file_exists(SRC_CONFIG_PATH . '/' . $a_install['db_file'])) {
    $o_loader = require_once VENDOR_PATH . '/autoload.php';

    if ($a_install['loader'] == 'psr0') {
        $my_classmap = require_once SRC_CONFIG_PATH . '/autoload_classmap.php';
        $o_loader->addClassMap($my_classmap);
    }
    else {
        $my_namespaces = require_once SRC_CONFIG_PATH . '/autoload_namespaces.php';
        foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
            $o_loader->addPsr4($psr4_prefix, $psr0_paths);
        }
    }

    $o_elog = Elog::start();
    $o_elog->write("Test\n", LOG_OFF);
    $o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
    $o_di = new Di();
    $o_di->set('elog', $o_elog);

    /** @var \PDO $o_pdo */
    $o_pdo = PdoFactory::start($a_install['db_file'], 'rw', $o_di);

    if ($o_pdo !== false) {
        $o_db = new DbModel($o_pdo, $a_install['db_file']);
        if (!is_object($o_db)) {
            $o_elog->write("Could not create a new DbModel\n", LOG_ALWAYS);
            die("Could not get the database to work\n");
        }
        else {
            $o_di->set('db', $o_db);
        }
    }
    else {
        $o_elog->write("Couldn't connect to database\n", LOG_ALWAYS);
        die("Could not connect to the database\n");
    }

    $prefix = $a_install['app_twig_prefix'];
    $key_name = str_replace('_', '', $prefix);
    $tp_path = '/src/apps/'
               . $a_install['namespace']
               . '/'
               . $a_install['app_name']
               . '/resources/templates';
    $a_tp_prefix[$key_name] = [
        'tp_prefix'  => $prefix,
        'tp_path'    => $tp_path,
        'tp_active'  => 1,
        'tp_default' => 0
    ];

    $a_dir_names = [
        'default',
        'elements',
        'forms',
        'pages',
        'snippets'
    ];
    $a_tp_dirs = [];
    foreach ($a_dir_names as $name) {
        $a_tp_dirs[$prefix . $name] = [
            'tp_id'   => $key_name,
            'td_name' => $name
        ];
    }
    $a_tp_tpls[$prefix . 'index'] = [
        'td_id'         => $prefix . 'pages',
        'tpl_name'      => 'index',
        'tpl_immutable' => 0
    ];

### Enter twig prefix into database ###
    $a_strings = createStrings($a_tp_prefix);
    $twig_prefix_sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}twig_prefix
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

    $a_table_info = [
        'table_name'  => $a_install['lib_db_prefix'] . 'twig_dirs',
        'column_name' => 'tp_id'
    ];
    foreach ($a_tp_prefix as $key => $a_record) {
        $results = $o_db->insert($twig_prefix_sql, $a_record, $a_table_info);
        if ($results === false) {
            print "\n" . $o_db->retrieveFormatedSqlErrorMessage() . "\n";
            $o_db->rollbackTransaction();
            die("\nCould not insert twig prefix data\n");
        }
        else {
            $ids = $o_db->getNewIds();
            $a_tp_prefix[$key]['tp_id'] = $ids[0];
            print '+';
        }
    }
    print "\n";

### Enter twig directories into database ###
    print "Creating twig directories: ";
    $a_strings = createStrings($a_tp_dirs);
    $twig_dirs_sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}twig_dirs
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

    $a_table_info = [
        'table_name'  => $a_install['lib_db_prefix'] . 'twig_dirs',
        'column_name' => 'td_id'
    ];
    foreach ($a_tp_dirs as $key => $a_record) {
        $a_record['tp_id'] = $a_tp_prefix[$a_record['tp_id']]['tp_id'];
        $results = $o_db->insert($twig_dirs_sql, $a_record, $a_table_info);
        if ($results === false) {
            print "\n" . $o_db->retrieveFormatedSqlErrorMessage() . "\n";
            $o_db->rollbackTransaction();
            die("\nCould not insert twig dirs data\n");
        }
        else {
            $ids = $o_db->getNewIds();
            $a_tp_dirs[$key]['td_id'] = $ids[0];
            print '+';
        }
    }
    print "\n";

### Enter twig templates into database ###
    print "Creating twig templates: ";
    $a_strings = createStrings($a_tp_tpls);
    $twig_tpls_sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}twig_templates
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

    $a_table_info = [
        'table_name'  => $a_install['lib_db_prefix'] . 'twig_templates',
        'column_name' => 'tpl_id'
    ];
    foreach ($a_tp_tpls as $key => $a_record) {
        $a_record['td_id'] = $a_tp_dirs[$a_record['td_id']]['td_id'];
        $results = $o_db->insert($twig_tpls_sql, $a_record, $a_table_info);
        if ($results === false) {
            print "\n" . $o_db->retrieveFormatedSqlErrorMessage() . "\n";
            $o_db->rollbackTransaction();
            die("\nCould not insert twig templates data\n");
        }
        else {
            $ids = $o_db->getNewIds();
            $a_tp_tpls[$key]['tpl_id'] = $ids[0];
            print '+';
        }
    }
    print "\n";
}

### Create the directories for the new app ###
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

function createStrings($a_records = []) {
    $a_record = array_shift($a_records);
    $fields = '';
    $values = '';
    foreach ($a_record as $key => $a_value) {
        $fields .= $fields == '' ? $key : ', ' . $key;
        $values .= $values == '' ? ':' . $key : ', :' . $key;
    }
    return [
        'fields' => $fields,
        'values' => $values
    ];
}

function reorgArray($a_org_values = []) {
    $a_values = [];
    foreach ($a_org_values as $a_value) {
        $a_values[] = $a_value;
    }
    return $a_values;
}

?>
