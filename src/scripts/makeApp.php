<?php
/**
 * @brief     This file sets up standard stuff for a single app.
 * @details   This creates the database config, some standard directories,
 *            and some standard files needed, e.g. index.php and WebController.
 *            This should be run from the cli in the /src/bin directory of the site.
 *            Copy /src/config/install_files/app_config.php.txt to /src/config/app_config.php.
 *            The copied file may have any name as long as it is in /src/config directory, but then it needs to be
 *            called on the cli, e.g. php makeApp.php my_install_config.php
 * @file      /src/bin/makeApp.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2021-12-04 17:01:25
 * @version   4.0.0-alpha.1
 * @todo      Test makeApp.php
 * @change_log
 * - 4.0.0-alpha.1                                                  - 2021-12-04 wer
 *         - updated for php8 and other changes
 * - 3.0.0 - Changed to use DbCreator and NewAppHelper              - 2017-12-15 wer
 * - 2.5.0 - Added several files to be created in app.              - 2017-05-25 wer
 * - 2.4.0 - changed several settings, defaults, and actions        - 2017-05-11 wer
 * - 2.3.0 - fix to install_files setup.php in public dir           - 2017-05-08 wer
 * - 2.2.0 - bug fixes to get postgresql working                    - 2017-04-18 wer
 * - 2.1.0 - lots of bug fixes and additions                        - 2017-01-24 wer
 * - 2.0.0 - bug fixes and rewrite of the database insert stuff     - 2017-01-13 wer
 * - 1.0.0 - initial version                                        - 2015-11-27 wer
 */
namespace Ritc;

use Ritc\Library\Exceptions\FactoryException;
use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Helper\NewAppHelper;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;

if (str_contains(__DIR__, 'Library') || !str_contains(__DIR__, '/src/scripts')) {
    die('Please Run this script from the src/scripts directory');
}
$base_path = str_replace('/src/scripts', '', __DIR__);
define('DEVELOPER_MODE', true);
define('BASE_PATH', $base_path);
define('PUBLIC_PATH', $base_path . '/public');
define('PRIVATE_DIR_NAME', 'private');
define('PRIVATE_PATH', $base_path . '/' . PRIVATE_DIR_NAME);

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
    die('You must create the install_configs configuration file in ' . SRC_CONFIG_PATH . "The default name for the file is install_config.php. You may name it anything but it must then be specified on the command line.\n");
}
$a_install = require $install_config;
$a_required_keys = [
    'app_name',
    'namespace',
    'db_file',
];
foreach ($a_required_keys as $key) {
    if (empty($a_install[$key])) {
        die('The install config file does not have required values');
    }
}
$a_needed_keys = [
    'main_app',
    'author',
    'short_author',
    'email',
    'main_twig',
    'app_twig_prefix',
    'app_theme_name',
    'app_db_prefix'
];
foreach ($a_needed_keys as $key) {
    if (!isset($a_install[$key])) {
        $a_install[$key] = '';
    }
}
$main_app = $a_install['main_app'] === 'true';
### generate files for autoloader ###
require APPS_PATH . '/Ritc/Library/Helper/AutoloadMapper.php';
$a_dirs = [
    'src_path'    => SRC_PATH,
    'config_path' => SRC_CONFIG_PATH,
    'apps_path'   => APPS_PATH
];
$o_cm = new AutoloadMapper($a_dirs);
if (!is_object($o_cm)) {
    die('Could not instance AutoloadMapper');
}
$app_namespace = ucfirst(strtolower($a_install['namespace']));
$app_name = ucfirst(strtolower($a_install['app_name']));
$app_path = APPS_PATH . '/' . $app_namespace . '/' . $app_name;
$a_install['app_path'] = $app_path;
### Setup the database ###
$db_config_file = $a_install['db_file'];
$a_db_config = require SRC_CONFIG_PATH . '/' . $db_config_file;
$array_key_values = '';
foreach ($a_db_config as $key => $value) {
    $array_key_values .= '    ' . $key . ' => ' . $value . ",\n";
}
$array_key_values .= '    ' .
$db_config_file_text =<<<EOT
<?php
return [
    'driver'     => '{$a_db_config['db_type']}',
    'host'       => '{$a_db_config['db_host']}',
    'port'       => '{$a_db_config['db_port']}',
    'name'       => '{$a_db_config['db_name']}',
    'user'       => '{$a_db_config['db_user']}',
    'password'   => '{$a_db_config['db_pass']}',
    'userro'     => '{$a_db_config['db_user']}',
    'passro'     => '{$a_db_config['db_pass']}',
    'persist'    => {$a_db_config['db_persist']},
    'prefix'     => '{$a_db_config['db_prefix']}',
    'errmode'    => '{$a_db_config['db_errmode']}',
    'db_prefix'  => '{$a_db_config['db_prefix']}',
    'lib_prefix' => '{$a_db_config['lib_db_prefix']}'
];
EOT;

file_put_contents(SRC_CONFIG_PATH . '/' . $db_config_file, $db_config_file_text);

$o_loader = require VENDOR_PATH . '/autoload.php';
if ($a_install['loader'] === 'psr0') {
    $my_classmap = require SRC_CONFIG_PATH . '/autoload_classmap.php';
    $o_loader->addClassMap($my_classmap);
}
else {
    $my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
    foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
        $o_loader->addPsr4($psr4_prefix, $psr0_paths);
    }
}

$o_di = new Di();
try {
    $o_pdo = PdoFactory::start($db_config_file);
}
catch (FactoryException $e) {
    die('Unable to start the PdoFactory. ' . $e->errorMessage());
}

$o_db = new DbModel($o_pdo, $db_config_file);
$o_di->set('db', $o_db);

$a_sql  = match ($a_install['db_type']) {
    'pgsql'  => require $install_files_path . '/default_pgsql_create.php',
    'sqlite' => array(),
    default  => require $install_files_path . '/default_mysql_create.php',
};
$a_data = require $install_files_path .  '/default_data.php';

$o_di->setVar('a_sql', $a_sql);
$o_di->setVar('a_data', $a_data);
$o_di->setVar('a_install_config', $a_install);
$o_di->setVar('app_path', $app_path);

### New App Stuff
print "\nSetting up the app\n";
$o_new_app_helper = new NewAppHelper($o_di);
print 'Creating twig db records';
try {
    $o_new_app_helper->createTwigDbRecords();
}
catch (Library\Exceptions\HelperException $e) {
    die("\n". $e->getMessage());
}
print "New Twig records: success\n";
if (!empty($a_install['a_groups']) || !empty($a_install['a_users'])) {
    print "Creating new user records: \n";
    try {
        $o_new_app_helper->createUsers();
    }
    catch (Library\Exceptions\HelperException $e) {
        die("\n" . $e->getMessage());
    }
    print "New User records: success\n";
}

print "\nCreating the directories for the new app\n";
if ($o_new_app_helper->createDirectories()) {
    print "\nCreating default files.\n";
    $o_new_app_helper->createDefaultFiles($main_app);
}

### Regenerate Autoload Map files
$o_cm->generateMapFiles();

