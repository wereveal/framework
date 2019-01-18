<?php /** @noinspection PhpIncludeInspection */
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
 * @date      2017-05-25 15:28:28
 * @version   2.5.0
 * @note   <b>Change Log</b>
 * - v3.0.0 - Changed to use DbCreator and NewAppHelper     - 2017-12-15 wer
 * - v2.5.0 - Added several files to be created in app.            - 2017-05-25 wer
 * - v2.4.0 - changed several settings, defaults, and actions      - 2017-05-11 wer
 * - v2.3.0 - fix to install_files setup.php in public dir         - 2017-05-08 wer
 * - v2.2.0 - bug fixes to get postgresql working                  - 2017-04-18 wer
 * - v2.1.0 - lots of bug fixes and additions                      - 2017-01-24 wer
 * - v2.0.0 - bug fixes and rewrite of the database insert stuff   - 2017-01-13 wer
 * - v1.0.0 - initial version                                      - 2015-11-27 wer
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

if (strpos(__DIR__, 'Library') !== false) {
    die('Please Run this script from the src/bin directory');
}
$base_path = str_replace('/src/bin', '', __DIR__);
print "Starting the php script.\n";

/**
 * Switch to use the elog
 *
 * @var bool DEVELOPER_MODE
 */
\define('DEVELOPER_MODE', true);
/**
 * Server path to the base of the code.
 *
 * @var string BASE_PATH
 */
\define('BASE_PATH', $base_path);
/**
 * Server path to the root of the public website files.
 *
 * @var string PUBLIC_PATH
 */
\define('PUBLIC_PATH', $base_path . '/public');

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
    die('You must create the install_configs configuration file in ' . SRC_CONFIG_PATH . "The default name for the file is install_config.php. You may name it anything but it must then be specified on the command line.\n");
}
$a_install = require $install_config;
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
    if (empty($a_install[$key]) || $a_install[$key] === 'REQUIRED' ) {
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
    'http_host',
    'specific_host'
];
foreach ($a_needed_keys as $key) {
    if (!isset($a_install[$key])) {
        $a_install[$key] = '';
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
if (!\is_object($o_cm)) {
    die('Could not instance AutoloadMapper');
}
$o_cm->generateMapFiles();
$app_path = APPS_PATH . '/' . $a_install['namespace'] . '/' . $a_install['app_name'];
### Setup the database ###
$install_host   = $a_install['install_host'] ?? 'default';
$db_file_name   = $a_install['db_file'] ?? 'db_config';
$db_config_file = $db_file_name . '.php';
$db_local_file  = $db_file_name . '_local.php';

$a_install['db_port'] = $a_install['db_port'] ?? $a_install['db_type'] === 'mysql' ? '3306' : '5432';
if (empty($a_install['db_ro_pass']) || empty($a_install['db_ro_user'])) {
    $a_install['db_ro_user'] = $a_install['db_user'];
    $a_install['db_ro_pass'] = $a_install['db_pass'];
}

$db_config_file_text =<<<EOT
<?php
return [
    'driver'     => '{$a_install['db_type']}',
    'host'       => '{$a_install['db_host']}',
    'port'       => '{$a_install['db_port']}',
    'name'       => '{$a_install['db_name']}',
    'user'       => '{$a_install['db_user']}',
    'password'   => '{$a_install['db_pass']}',
    'userro'     => '{$a_install['db_ro_user']}',
    'passro'     => '{$a_install['db_ro_pass']}',
    'persist'    => {$a_install['db_persist']},
    'prefix'     => '{$a_install['db_prefix']}',
    'errmode'    => '{$a_install['db_errmode']}',
    'db_prefix'  => '{$a_install['db_prefix']}',
    'lib_prefix' => '{$a_install['lib_db_prefix']}'
];
EOT;
file_put_contents(SRC_CONFIG_PATH . '/' . $db_config_file, $db_config_file_text);

$db_local_type = $a_install['db_local_type'] ?? $a_install['db_type'];
$db_local_host = $a_install['db_local_host'] ?? 'localhost';
$db_local_port = $a_install['db_local_port'] ?? $db_local_type === 'mysql'
    ? '3306'
    : '5432';
$db_local_name = $a_install['db_local_name'] ?? $a_install['db_name'];
$db_local_user = $a_install['db_local_user'] ?? $a_install['db_user'];
$db_local_pass = $a_install['db_local_pass'] ?? $a_install['db_pass'];
$db_config_file_text =<<<EOT
<?php
return [
    'driver'     => '{$db_local_type}',
    'host'       => '{$db_local_host}',
    'port'       => '{$db_local_port}',
    'name'       => '{$db_local_name}',
    'user'       => '{$db_local_user}',
    'password'   => '{$db_local_pass}',
    'userro'     => '{$a_install['db_ro_user']}',
    'passro'     => '{$a_install['db_ro_pass']}',
    'persist'    => {$a_install['db_persist']},
    'prefix'     => '{$a_install['db_prefix']}',
    'errmode'    => '{$a_install['db_errmode']}',
    'db_prefix'  => '{$a_install['db_prefix']}',
    'lib_prefix' => '{$a_install['lib_db_prefix']}'
];
EOT;
file_put_contents(SRC_CONFIG_PATH . '/' . $db_local_file, $db_config_file_text);

$specific_host = $a_install['specific_host'] ?? 'test';
$db_site_file = $db_file_name . '_' . $specific_host . '.php';
$db_site_type = $a_install['db_site_type'] ?? $a_install['db_type'];
$db_site_host = $a_install['db_site_host'] ?? $a_install['db_host'];
$port_maybe   = $a_install['db_site_type'] === 'mysql'
    ? '3306'
    : '5432';
$db_site_port = $a_install['db_site_port'] ?? $port_maybe;
$db_site_name = $a_install['db_site_name'] ?? $a_install['db_name'];
$db_site_user = $a_install['db_site_user'] ?? $a_install['db_user'];
$db_site_pass = $a_install['db_site_pass'] ?? $a_install['db_pass'];

$db_config_file_text =<<<EOT
<?php
return [
    'driver'     => '{$db_site_type}',
    'host'       => '{$db_site_host}',
    'port'       => '{$db_site_port}',
    'name'       => '{$db_site_name}',
    'user'       => '{$db_site_user}',
    'password'   => '{$db_site_pass}',
    'userro'     => '{$a_install['db_ro_user']}',
    'passro'     => '{$a_install['db_ro_pass']}',
    'persist'    => {$a_install['db_persist']},
    'prefix'     => '{$a_install['db_prefix']}',
    'errmode'    => '{$a_install['db_errmode']}',
    'db_prefix'  => '{$a_install['db_prefix']}',
    'lib_prefix' => '{$a_install['lib_db_prefix']}'
];
EOT;
file_put_contents(SRC_CONFIG_PATH . '/' . $db_site_file, $db_config_file_text);

switch ($install_host) {
    case 'localhost':
        $the_db_config_file  = $db_local_file;
        $db_type             = $db_local_type;
        break;
    case $specific_host:
        $the_db_config_file = $db_site_file;
        $db_type            = $db_site_type;
        break;
    default:
        $the_db_config_file = $db_config_file;
        $db_type            = $a_install['db_type'];
}

$o_loader = require VENDOR_PATH . '/autoload.php';
$my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}
$my_classmap = require SRC_CONFIG_PATH . '/autoload_classmap.php';
$o_loader->addClassMap($my_classmap);

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
    /** @var \PDO $o_pdo */
    $o_pdo = PdoFactory::start($the_db_config_file, 'rw', $o_di);
    if (! $o_pdo instanceof \PDO) {
        die('Unable to create the Pdo instance.');
    }
    $o_di->set('pdo', $o_pdo);
}
catch (FactoryException $e) {
    die('Unable to start the PdoFactory. ' . $e->errorMessage());
}

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $the_db_config_file);
    if (!$o_db instanceof DbModel) {
        $o_elog->write("Could not create a new DbModel\n", LOG_ALWAYS);
        die("Could not get the database to work\n");
    }

    $o_di->set('db', $o_db);
}
else {
    $o_elog->write("Couldn't connect to database\n", LOG_ALWAYS);
    die("Could not connect to the database\n");
}
$using_mysql = false;
switch ($db_type) {
    case 'pgsql':
        $a_sql = require $install_files_path .  '/default_pgsql_create.php';
        break;
    case 'sqlite':
        $a_sql = array();
        break;
    case 'mysql':
    default:
        $using_mysql = true;
        $a_sql = require $install_files_path .  '/default_mysql_create.php';
}
$a_data = require $install_files_path .  '/default_data.php';

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
function createStrings(array $a_records = []) {
    $a_record = array_shift($a_records);
    $fields = '';
    $values = '';
    foreach ($a_record as $key => $a_value) {
        $fields .= empty($fields) ? $key : ', ' . $key;
        $values .= empty($values) ? ':' . $key : ', :' . $key;
    }
    return [
        'fields' => $fields,
        'values' => $values
    ];
}

/**
 * Reorganizes the array.
 *
 * @param array $a_org_values
 * @return array
 */
function reorgArray(array $a_org_values = []) {
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
 * @param string $message
 * @param bool $using_mysql
 */
function failIt(DbModel $o_db, $message = '', $using_mysql = false) {
    if ($using_mysql) {
        try {
            $o_db->rollbackTransaction();
        }
        catch (ModelException $e) {
            print 'Could not rollback transaction: ' . $e->errorMessage() . "\n";
        }
    }
    die("\nFAIL!\n{$message}\n");
}

if ($using_mysql) {
    try {
        $o_db->startTransaction();
    }
    catch (ModelException $e) {
        die('Could not start transaction: ' . $e->errorMessage());
    }
}
$o_db_creator = new DbCreator($o_di);
print 'Creating Databases: ';
if (!$o_db_creator->createTables()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter Constants
print 'Entering Constants Data: ';
if (!$o_db_creator->insertConstants()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter Groups
print 'Entering Groups Data; ';
if (!$o_db_creator->insertGroups()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'urls'
print 'Entering URLs Data; ';
if (!$o_db_creator->insertUrls()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'people'
print 'Entering People Data; ';
if (!$o_db_creator->insertPeople()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'navgroups',
print 'Entering NavGroups Data; ';
if (!$o_db_creator->insertNavgroups()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'people_group_map',
print 'Entering people_group_map Data; ';
if (!$o_db_creator->insertPGM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'routes'
print 'Entering Routes Data; ';
if (!$o_db_creator->insertRoutes()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'routes_group_map'
print 'Entering routes_group_map Data; ';
if (!$o_db_creator->insertRGM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'navigation',
print 'Entering Navigation Data; ';
if (!$o_db_creator->insertNavigation()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'nav_ng_map'
print 'Entering nav_ng_map Data; ';
if (!$o_db_creator->insertNNM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Twig tables data ###
print "Starting the Twig db stuff. \n";
print "Updating data for app specific\n";
$o_db_creator->createTwigAppConfig();

### Enter twig prefixes into database ###
print 'Entering Twig Prefixes Data; ';
if (!$o_db_creator->insertTwigPrefixes()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter twig directories into database ###
print 'Entering twig directories Data; ';
if (!$o_db_creator->insertTwigDirs()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter twig templates into database ###
print 'Entering twig templates Data; ';
if (!$o_db_creator->insertTwigTemplates()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'page' ###
print 'Entering Page Data; ';
if (!$o_db_creator->insertPage()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'blocks' ###
print 'Entering Blocks Data; ';
if (!$o_db_creator->insertBlocks()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'Page blocks' ###
print 'Entering Page Blocks Map Data; ';
if (!$o_db_creator->insertPBM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### Enter 'content' ###
print 'Entering Content Data; ';
if (!$o_db_creator->insertContent()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $using_mysql);
}
print "success\n";

### New App Stuff
print "\nSetting up the app\n";
$o_new_app_helper = new NewAppHelper($o_di);
print 'Creating twig db records';
$results = $o_new_app_helper->createTwigDbRecords();
if (\is_string($results)) {
    failIt($o_db, $results, $using_mysql);
    failIt($o_db, $results, $using_mysql);
}
print "success\n";
if ($using_mysql) {
    try {
        $o_db->commitTransaction();
        print "Data Insert Complete.\n";
    }
    catch (ModelException $e) {
        failIt($o_db, 'Could not commit the transaction.', $using_mysql);
    }
}

if ($a_install['master_twig'] === 'true') {
    print "Changing the home page template: ";
    try {
        $o_new_app_helper->changeHomePageTpl();
        print "Success\n";
    }
    catch (ModelException $e) {
        failIt($o_db, 'Could not change the home page template.', $using_mysql);
    }
}

print "\nCreating the directories for the new app\n";
if ($o_new_app_helper->createDirectories()) {
    print "\nCreating default files.\n";
    $o_new_app_helper->createDefaultFiles(true);
}

### Regenerate Autoload Map files
$o_cm->generateMapFiles();

