<?php
/**
 * @brief     This file sets up the database.
 * @details   This creates the database tables and inserts default data.
 *            This should be run from the cli in the /src/bin directory of the site.
 *            Copy /src/config/install_files/install_config.php.txt to /src/config/install_config.php.
 *            The copied file may have any name as long as it is in /src/config directory but then it needs to be
 *            called on the cli, e.g. php makeDb.php my_install_config.php
 * @file      /src/bin/makeDb.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2021-12-04 17:05:44
 * @version   4.0.0-alpha.1
 * @todo      Test makeDb.php
 * @change_log
 * - 4.0.0-alpha.1                                                  - 2021-12-04 wer
 *          - updated for php 8 and other changes
 * - 3.1.0 - Lot of bug fixes and minor changes to get it to work   - 2019-01-08 wer
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
use Ritc\Library\Exceptions\ModelException;
use Ritc\Library\Exceptions\ServiceException;
use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Helper\NewAppHelper;
use Ritc\Library\Models\DbCreator;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

if (str_contains(__DIR__, 'Library')) {
    die('Please Run this script from the src/bin directory');
}
$base_path = str_replace('/src/bin', '', __DIR__);
define('DEVELOPER_MODE', true);
define('BASE_PATH', $base_path);
define('PUBLIC_PATH', $base_path . '/public');

require_once BASE_PATH . '/src/config/constants.php';
$install_files_path = SRC_CONFIG_PATH . '/install_files';

if (!file_exists(APPS_PATH . '/Ritc/Library')) {
    die("You must clone the Ritc/Library in the apps dir first and any other desired apps.\n");
}
$db_config_file = 'db_config.php';
/* allows a custom file to be used. Still must be in src/config dir */
if (isset($argv[1])) {
    $db_config_file = match ($argv[1]) {
        'test'  => 'db_config_test.php',
        'local' => 'db_config_local.php',
        default => $argv[1],
    };
}
if (!file_exists(SRC_CONFIG_PATH . '/' . $db_config_file)) {
    die('You must create the db_config configuration file in '
        . SRC_CONFIG_PATH
        . "The default name for the file is db_config.php.
        You may name it anything but it must then be specified on the command line.\n"
    );
}
$a_install_file = SRC_CONFIG_PATH . '/install_config.php';
if (!file_exists($a_install_file)) {
    die('You must create the install_config.php configuration file in '
        . SRC_CONFIG_PATH
        . ".\n"
    );
}

$a_db_config     = require SRC_CONFIG_PATH . '/' . $db_config_file;
$a_install       = require $a_install_file;
$a_required_keys = [
    'driver',
    'host',
    'port',
    'name',
    'user',
    'password',
    'persist',
    'errmode',
    'prefix',
    'db_prefix',
    'lib_prefix'
];
foreach ($a_required_keys as $key) {
    if ($a_db_config[$key] === '') {
        die('The db config file does not have required values');
    }
}

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
$o_cm->generateMapFiles();
### Setup the database ###

$o_loader = require VENDOR_PATH . '/autoload.php';
$my_classmap = require SRC_CONFIG_PATH . '/autoload_classmap.php';
$o_loader->addClassMap($my_classmap);
$my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}

try {
    $o_elog = Elog::start();
    $o_elog->write("Test\n", LOG_OFF);
    $o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
}
catch (ServiceException $e) {
    die('Unable to start Elog' . $e->errorMessage());
}

$o_di = new Di();
$o_di->set('elog', $o_elog);
try {
    $o_pdo = PdoFactory::start($db_config_file, 'rw', $o_di);
}
catch (FactoryException $e) {
    die('Unable to start the PdoFactory. ' . $e->errorMessage());
}

$o_di->set('pdo', $o_pdo);
$o_db = new DbModel($o_pdo, $db_config_file);
$o_di->set('db', $o_db);
$a_sql    = match ($a_db_config['driver']) {
    'pgsql'  => require $install_files_path . '/default_pgsql_create.php',
    'sqlite' => array(),
    default  => require $install_files_path . '/default_mysql_create.php',
};
$a_data = require $install_files_path .  '/default_data.php';
$app_path = SRC_PATH . '/apps/' . $a_install['namespace'] . '/' . $a_install['app_name'];
$o_di->setVar('a_sql', $a_sql);
$o_di->setVar('a_data', $a_data);
$o_di->setVar('a_install_config', $a_install);
$o_di->setVar('app_path', $app_path);

/**
 * Creates the strings needed for sql.
 *
 * @param array $a_records
 * @return array
 */
function createStrings(array $a_records = []): array
{
    $a_record = array_shift($a_records);
    $fields = '';
    $values = '';
    foreach ($a_record as $key => $a_value) {
        $fields .= $fields === '' ? $key : ', ' . $key;
        $values .= $values === '' ? ':' . $key : ', :' . $key;
    }
    return [
        'fields' => $fields,
        'values' => $values
    ];
}

/**
 * Reorganizes the array.
 * @param array $a_org_values
 * @return array
 */
function reorgArray(array $a_org_values = []): array
{
    $a_values = [];
    foreach ($a_org_values as $a_value) {
        $a_values[] = $a_value;
    }
    return $a_values;
}

/**
 * Rolls back the transaction and exits the script.
 *
 * @param DbModel $o_db
 * @param string  $message
 * @param bool    $rollback
 */
function failIt(DbModel $o_db, string $message = '', bool $rollback = true) {
    if ($rollback) {
        try {
            $o_db->rollbackTransaction();
        }
        catch (ModelException $e) {
            print 'Could not rollback transaction: ' . $e->errorMessage() . "\n";
        }
    }
    die("FAIL: {$message}\n");
}
$using_mysql = false;
if ($a_db_config['driver'] === 'mysql') {
    $using_mysql = true;
    try {
        $o_db->startTransaction();
    }
    catch (ModelException $e) {
        print 'Could not start transaction: ' . $e->errorMessage() . "\n";
    }
}
$o_installer_model = new DbCreator($o_di);
print 'Creating Databases: ';
if (!$o_installer_model->createTables()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $using_mysql);
}
print "success\n";

if ($using_mysql) {
    try {
        $o_db->commitTransaction();
    }
    catch (ModelException $e) {
        print 'Could not start transaction: ' . $e->errorMessage() . "\n";
    }
}

$rollback = false;
if ($using_mysql) {
    try {
        $o_db->startTransaction();
    }
    catch (ModelException $e) {
        $message = 'Could not start transaction: ' . $e->errorMessage() . "\n";
        die($message);
    }
    $rollback = true;
}

### Enter Constants
print 'Entering Constants Data: ';
if (!$o_installer_model->insertConstants()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter Groups
print 'Create Groups: ';
if (!$o_installer_model->insertGroups()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'urls'
print 'Create URLs: ';
if (!$o_installer_model->insertUrls()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'people'
print 'Creating People: ';
if (!$o_installer_model->insertPeople()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'navgroups',
print 'Creating NavGroups: ';
if (!$o_installer_model->insertNavgroups()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'people_group_map',
print 'Creating people_group_map: ';
if (!$o_installer_model->insertPGM()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'routes'
print 'Creating Routes: ';
if (!$o_installer_model->insertRoutes()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'routes_group_map'
print 'Creating routes_group_map: ';
if (!$o_installer_model->insertRGM()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'navigation',
print 'Creating Navigation: ';
if (!$o_installer_model->insertNavigation()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'nav_ng_map'
print 'Creating nav_ng_map: ';
if (!$o_installer_model->insertNNM()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}

### Twig tables data ###
print "Starting the Twig db stuff. \n";
print "Updating data for app specific\n";
$o_installer_model->createTwigAppConfig();

### Enter twig themes into database ###
print 'Creating Twig Themes: ';
if (!$o_installer_model->insertTwigThemes()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}

### Enter twig prefixes into database ###
print 'Creating Twig Prefixes: ';
if (!$o_installer_model->insertTwigPrefixes()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter twig directories into database ###
print 'Creating twig directories: ';
if (!$o_installer_model->insertTwigDirs()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter twig templates into database ###
print 'Creating twig templates: ';
if (!$o_installer_model->insertTwigTemplates()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'page',
print 'Creating Page: ';
if (!$o_installer_model->insertPage()) {
    failIt($o_db, $o_installer_model->getErrorMessage(), $rollback);
}
print "success\n";

### Enter 'blocks' ###
print 'Entering Blocks Data; ';
if (!$o_installer_model->insertBlocks()) {
    failIt($o_db, $o_installer_model->getErrorMessage());
}
print "success\n";

### Enter 'Page blocks' ###
print 'Entering Page Blocks Map Data; ';
if (!$o_installer_model->insertPBM()) {
    failIt($o_db, $o_installer_model->getErrorMessage());
}
print "success\n";

### Enter 'content' ###
print 'Entering Content Data; ';
if (!$o_installer_model->insertContent()) {
    failIt($o_db, $o_installer_model->getErrorMessage());
}
print "success\n";

print "\nSetting up the app\n";
$o_new_app_helper = new NewAppHelper($o_di);
print 'Creating twig db records: ';
$results = $o_new_app_helper->createTwigDbRecords();
if (is_string($results)) {
    failIt($o_db, $results);
    failIt($o_db, $results);
}
print "success\n";

if ($using_mysql) {
    try {
        $o_db->commitTransaction();
        print "Data Insert Complete.\n";
    }
    catch (ModelException $e) {
        failIt($o_db, 'Could not commit the transaction.', $rollback);
    }
}

print 'Changing the Home Page Tpl: ';
if ($a_install['master_twig'] === 'true') {
    try {
        $o_new_app_helper->changeHomePageTpl();
        print "Success\n";
    }
    catch (ModelException $e) {
        print "Could not change the home page template.\n";
    }
}
print "\n";
