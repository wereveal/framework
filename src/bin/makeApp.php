<?php
/**
 * @brief     This file sets up standard stuff for the Framework.
 * @details   This creates the database config, some standard directories,
 *            and some standard files needed, e.g. index.php and MainController.
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
 * - v3.0.0 - Changed to use DbInstallerModel and NewAppHelper     - 2017-12-15 wer
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
use Ritc\Library\Exceptions\ServiceException;
use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Helper\NewAppHelper;
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
    die("You must create the install_configs configuration file in " . SRC_CONFIG_PATH . "The default name for the file is install_config.php. You may name it anything but it must then be specified on the command line.\n");
}
$a_install = require_once $install_config;
$a_required_keys = [
    'app_name',
    'namespace',
    'db_file',
    'db_host',
    'db_type',
    'db_port',
    'db_name',
    'db_user',
    'db_pass',
    'db_persist',
    'db_errmode',
    'db_prefix',
    'lib_db_prefix'
];
foreach ($a_required_keys as $key) {
    if (empty($a_install[$key])) {
        die("The install config file does not have required values");
    }
}
$a_needed_keys = [
    'author',
    'short_author',
    'email',
    'twig_prefix',
    'lib_twig_prefix',
    'app_twig_prefix',
    'loader',
    'superadmin',
    'admin',
    'manager',
    'developer_mode',
    'public_path',
    'base_path',
    'http_host',
    'domain',
    'tld',
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
if (!is_object($o_cm)) {
    die("Could not instance AutoloadMapper");
}
$o_cm->generateMapFiles();

$app_path = APPS_PATH . '/' . $a_install['namespace'] . '/' . $a_install['app_name'];
### Setup the database ###
$db_config_file = $a_install['db_file'];
$db_config_file_text =<<<EOT
<?php
return [
    'driver'     => '{$a_install['db_type']}',
    'host'       => '{$a_install['db_host']}',
    'port'       => '{$a_install['db_port']}',
    'name'       => '{$a_install['db_name']}',
    'user'       => '{$a_install['db_user']}',
    'password'   => '{$a_install['db_pass']}',
    'userro'     => '{$a_install['db_user']}',
    'passro'     => '{$a_install['db_pass']}',
    'persist'    => {$a_install['db_persist']},
    'prefix'     => '{$a_install['db_prefix']}',
    'errmode'    => '{$a_install['db_errmode']}',
    'db_prefix'  => '{$a_install['db_prefix']}',
    'lib_prefix' => '{$a_install['lib_db_prefix']}'
];
EOT;

file_put_contents(SRC_CONFIG_PATH . '/' . $db_config_file, $db_config_file_text);

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

try {
    $o_elog = Elog::start();
    $o_elog->write("Test\n", LOG_OFF);
    $o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
}
catch (ServiceException $e) {
    die("Unable to start Elog" . $e->errorMessage());
}

$o_di = new Di();
$o_di->set('elog', $o_elog);
try {
    /** @var \PDO $o_pdo */
    $o_pdo = PdoFactory::start($db_config_file, 'rw', $o_di);
}
catch (FactoryException $e) {
    die("Unable to start the PdoFactory. " . $e->errorMessage());
}

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);
    if (!$o_db instanceof DbModel) {
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

switch ($a_install['db_type']) {
    case 'pgsql':
        $a_sql = require $install_files_path .  '/default_pgsql_create.php';
        break;
    case 'sqlite':
        $a_sql = array();
        break;
    case 'mysql':
    default:
        $a_sql = require $install_files_path .  '/default_mysql_create.php';
}
$a_data = require $install_files_path .  '/default_data.php';

$o_di->setVar('a_sql', $a_sql);
$o_di->setVar('a_data', $a_data);
$o_di->setVar('a_install_config', $a_install);
$o_di->setVar('app_path', $app_path);

### New App Stuff
print "\nSetting up the app\n";
$o_new_app_helper = new NewAppHelper($o_di);
print "Creating twig db records";
$results = $o_new_app_helper->createDbRecords();
if ($results !== true) {
    die("\n". $results);
}
print "success\n";

print "\nCreating the directories for the new app\n";
if ($o_new_app_helper->createDirectories()) {
    print "\nCreating default files.\n";
    $o_new_app_helper->createDefaultFiles();
}

### Regenerate Autoload Map files
$o_cm->generateMapFiles();

