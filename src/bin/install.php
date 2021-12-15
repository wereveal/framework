<?php
/**
 * @noinspection DuplicatedCode
 */
/**
 * @brief     This file sets up standard stuff for the Framework.
 * @details   This creates the database config, some standard directories,
 *            and some standard files needed, e.g. index.php and MasterController.
 *            This should be run from the cli in the /src/bin directory of the site.
 *            Copy /src/config/install_files/install_config.php.txt to /src/config/install_config.php.
 *            The copied file may have any name as long as it is in /src/config directory but then it needs to be
 *            called on the cli, e.g. php install.php my_install_config.php
 * @file      /src/bin/install.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2021-12-03 10:30:30
 * @version   5.0.0-alpha.1
 * @change_log
 * - 5.0.0-alpha.1 -                                            - 2021-12-03 wer
 *           Ended experiment of Library being installed
 *           by Composer, moved back to /src/aps/Ritc/Library
 * - 4.0.0 - Moved the Library to be installed vi Composer      - 2020-08-28 wer
 * - 3.0.0 - Changed to use DbCreator and NewAppHelper          - 2017-12-15 wer
 * - 2.5.0 - Added several files to be created in app.          - 2017-05-25 wer
 * - 2.4.0 - changed several settings, defaults, and actions    - 2017-05-11 wer
 * - 2.3.0 - fix to install_files setup.php in public dir       - 2017-05-08 wer
 * - 2.2.0 - bug fixes to get postgresql working                - 2017-04-18 wer
 * - 2.1.0 - lots of bug fixes and additions                    - 2017-01-24 wer
 * - 2.0.0 - bug fixes and rewrite of the database insert stuff - 2017-01-13 wer
 * - 1.0.0 - initial version                                    - 2015-11-27 wer
 */
namespace Ritc;

use PDO;
use Ritc\Library\Exceptions\FactoryException;
use Ritc\Library\Exceptions\ModelException;
use Ritc\Library\Exceptions\ServiceException;
use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Helper\InstallHelper;
use Ritc\Library\Helper\NewAppHelper;
use Ritc\Library\Models\DbCreator;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

if (str_contains(__DIR__, 'Library')) {
    die('Please Run this script from the src/bin directory');
}
$base_path = str_replace('/src/bin', '', __DIR__);

/**
 * Switch to use the elog
 *
 * @var bool DEVELOPER_MODE
 */
define('DEVELOPER_MODE', true);
/**
 * Server path to the base of the code.
 *
 * @var string BASE_PATH
 */
define('BASE_PATH', $base_path);
/**
 * Server path to the root of the public website files.
 *
 * @var string PUBLIC_PATH
 */
define('PUBLIC_PATH', $base_path . '/public');
define('PRIVATE_DIR_NAME', 'private');
define('PRIVATE_PATH', $base_path . '/' . PRIVATE_DIR_NAME);

require_once BASE_PATH . '/src/config/constants.php';

if (!file_exists(LIBRARY_PATH)) {
    die("The ritc/library must be installed via git first.\n");
}

$install_files_path = SRC_CONFIG_PATH . '/install_files';

/* allows a custom file to be created. Still must be in src/config dir */
$install_config = SRC_CONFIG_PATH . '/install_config.php';
if (isset($argv[1])) {
    $install_config = SRC_CONFIG_PATH . '/' . $argv[1];
}
if (!file_exists($install_config)) {
    die('You must create the install_configs configuration file in ' . SRC_CONFIG_PATH . "\nThe default name for the file is install_config.php.\nYou may name it anything but it must then be specified on the command line.\n");
}
$a_install_config = require $install_config;
$a_required_keys = [
    'app_name',
    'namespace',
    'db_file',
    'db_host',
    'db_type',
    'db_name',
    'db_user',
    'db_pass',
    'db_persist',
    'db_errmode',
    'db_prefix',
    'lib_db_prefix',
    'superadmin',
    'admin',
    'manager',
    'domain',
    'tld'
];
foreach ($a_required_keys as $key) {
    if (empty($a_install_config[$key]) || $a_install_config[$key] === 'REQUIRED' ) {
        die('The install config file does not have required values');
    }
}
$a_needed_keys = [
    'author',
    'short_author',
    'email',
    'developer_mode',
    'public_path',
    'base_path',
    'server_http_host',
    'specific_host'
];
foreach ($a_needed_keys as $key) {
    if (!isset($a_install_config[$key])) {
        $a_install_config[$key] = '';
    }
}
$app_namespace = ucfirst(strtolower($a_install_config['namespace']));
$app_name = ucfirst(strtolower($a_install_config['app_name']));
$app_path = APPS_PATH . '/' . $app_namespace . '/' . $app_name;

$a_install_config['app_path'] = $app_path;

$o_loader = require VENDOR_PATH . '/autoload.php';
$o_di = new Di();

$o_installer = new InstallHelper();
$o_installer->setConfig($a_install_config);
$o_installer->createDbConfigFiles();
$the_db_config_file = $o_installer->getTheDbConfigFileName();
$db_type            = $o_installer->getDbType();

try {
    $o_elog = Elog::start();
    $o_elog->write("Test\n", LOG_OFF);
    $o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
    $o_di->set('elog', $o_elog);
}
catch (ServiceException $e) {
    die('Unable to start Elog' . $e->errorMessage());
}

$o_di->set('elog', $o_elog);
try {
    $o_pdo = PdoFactory::start($the_db_config_file);
    $o_di->set('pdo', $o_pdo);
}
catch (FactoryException $e) {
    die('Unable to start the PdoFactory. ' . $e->errorMessage());
}

$o_db = new DbModel($o_pdo, $the_db_config_file);
if (!is_object($o_db)) {
    die('Unable to create a DbModel instance');
}

$o_di->set('db', $o_db);
$use_transactions = true;
$a_sql            = match ($db_type) {
    'pgsql'  => require $install_files_path . '/default_pgsql_create.php',
    'sqlite' => require $install_files_path . '/default_sqlite_create.php',
    default  => require $install_files_path . '/default_mysql_create.php',
};
$a_data = require $install_files_path .  '/default_data.php';

/**
 * Rolls back the transaction and exits the script.
 *
 * @param DbModel $o_db
 * @param string  $message
 * @param bool    $use_transactions
 */
function failIt(DbModel $o_db, string $message = '', bool $use_transactions = false) {
    if ($use_transactions) {
        try {
            $o_db->rollbackTransaction();
        }
        catch (ModelException $e) {
            print 'Could not rollback transaction: ' . $e->errorMessage() . "\n";
        }
    }
    die("\nFAIL!\n{$message}\n");
}

if ($use_transactions) {
    try {
        $o_db->startTransaction();
    }
    catch (ModelException $e) {
        die('Could not start transaction: ' . $e->errorMessage());
    }
}
$o_db_creator = new DbCreator($o_di);
$o_db_creator->setSql($a_sql);
$o_db_creator->setData($a_data);
$o_db_creator->setDbType($db_type);

print 'Creating Databases: ';
if (!$o_db_creator->createTables()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter Constants
print 'Entering Constants Data: ';
if (!$o_db_creator->insertConstants()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter Groups
print 'Entering Groups Data; ';
if (!$o_db_creator->insertGroups()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'urls'
print 'Entering URLs Data; ';
if (!$o_db_creator->insertUrls()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'people'
print 'Entering People Data; ';
if (!$o_db_creator->insertPeople()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'navgroups',
print 'Entering NavGroups Data; ';
if (!$o_db_creator->insertNavgroups()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'people_group_map',
print 'Entering people_group_map Data; ';
if (!$o_db_creator->insertPGM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'routes'
print 'Entering Routes Data; ';
if (!$o_db_creator->insertRoutes()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'routes_group_map'
print 'Entering routes_group_map Data; ';
if (!$o_db_creator->insertRGM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'navigation',
print 'Entering Navigation Data; ';
if (!$o_db_creator->insertNavigation()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'nav_ng_map'
print 'Entering nav_ng_map Data; ';
if (!$o_db_creator->insertNNM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Twig tables data ###
print "Starting the Twig db stuff. \n";
print "Updating data for app specific\n";
$o_db_creator->createTwigAppConfig();

### Enter twig themes into database ###
print 'Entering Twig Themes Data; ';
if (!$o_db_creator->insertTwigThemes()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter twig prefixes into database ###
print 'Entering Twig Prefixes Data; ';
if (!$o_db_creator->insertTwigPrefixes()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter twig directories into database ###
print 'Entering twig directories Data; ';
if (!$o_db_creator->insertTwigDirs()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter twig templates into database ###
print 'Entering twig templates Data; ';
if (!$o_db_creator->insertTwigTemplates()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'page' ###
print 'Entering Page Data; ';
if (!$o_db_creator->insertPage()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'blocks' ###
print 'Entering Blocks Data; ';
if (!$o_db_creator->insertBlocks()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'Page blocks' ###
print 'Entering Page Blocks Map Data; ';
if (!$o_db_creator->insertPBM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'content' ###
print 'Entering Content Data; ';
if (!$o_db_creator->insertContent()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";
if ($use_transactions) {
    try {
        $o_db->commitTransaction();
        print "Data Insert Complete.\n";
    }
    catch (ModelException $e) {
        failIt($o_db, 'Could not commit the transaction.', true);
    }
}

### New App Stuff
print "\nSetting up the app\n";
if ($use_transactions) {
    try {
        $o_db->startTransaction();
    }
    catch (ModelException $e) {
        die('Could not start transaction: ' . $e->errorMessage());
    }
}
$o_new_app_helper = new NewAppHelper($o_di);
$o_new_app_helper->setConfig($a_install_config);
print 'Creating twig db records';
try {
    $results = $o_new_app_helper->createTwigDbRecords();
}
catch (Library\Exceptions\HelperException $e) {
    failIt($o_db, 'Could not create app twig db records.', $use_transactions);
}
print "success\n";

if (!empty($a_install_config['a_groups']) || !empty($a_install_config['a_users'])) {
    try {
        print $o_new_app_helper->createUsers();
    }
    catch (Library\Exceptions\HelperException $e) {
        failIt($o_db, $e->getMessage(), $use_transactions);
    }
}

if ($a_install_config['master_twig'] === 'true') {
    print 'Changing the home page template: ';
    try {
        $o_new_app_helper->changeHomePageTpl();
        print "Success\n";
    }
    catch (ModelException $e) {
        failIt($o_db, 'Could not change the home page template.', $use_transactions);
    }
}
print "\nCreating the directories for the new app\n";
if ($o_new_app_helper->createDirectories()) {
    print "\nCreating default files.\n";
    $results = ($o_new_app_helper->createDefaultFiles(true) === true)
        ? 'Success!'
        : 'Opps, could not create default files';
    print $results;
}
if ($use_transactions) {
    try {
        $o_db->commitTransaction();
        print "Data Insert Complete.\n";
    }
    catch (ModelException $e) {
        failIt($o_db, 'Could not commit the transaction.', true);
    }
}
### generate files for autoloader ###
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

$my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}
$my_classmap = require SRC_CONFIG_PATH . '/autoload_classmap.php';
$o_loader->addClassMap($my_classmap);

