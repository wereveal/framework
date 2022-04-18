<?php /** @noinspection PhpUndefinedVariableInspection */
/**
 * Basic setup used/included by several scripts
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
$install_config_file_name = $argv[1] ?? 'install_config.php';
$install_config = SRC_CONFIG_PATH . '/' . $install_config_file_name;
if (!file_exists($install_config)) {
    die('You must create the install_configs configuration file in ' . SRC_CONFIG_PATH . "\nThe default name for the file is install_config.php.\nYou may name it anything but it must then be specified on the command line.\n");
}
$a_install_config = require $install_config;
$a_required_keys  = match ($me) {
    'makeDb.php', 'makeApp.php' => [
        'app_name',
        'namespace',
        'db_file',
    ],
    default                     => [
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
    ],
};
foreach ($a_required_keys as $key) {
    if (empty($a_install_config[$key]) || $a_install_config[$key] === 'REQUIRED' ) {
        die('The install config file does not have required values');
    }
}
$a_needed_keys = [
        'main_app',
        'author',
        'short_author',
        'email',
        'main_twig',
        'developer_mode',
        'public_path',
        'base_path',
        'server_http_host',
        'specific_host',
        'app_twig_prefix',
        'app_theme_name',
        'app_db_prefix'
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
try {
    $o_elog = Elog::start();
    $o_elog->write("Test\n", LOG_OFF);
    $o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
    $o_di->set('elog', $o_elog);
}
catch (ServiceException $e) {
    die('Unable to start Elog' . $e->errorMessage());
}

if ($me !== 'newApp.php') {
    $o_installer = new InstallHelper();
    $o_installer->setConfig($a_install_config);
    $o_installer->createDbConfigFiles();
    $the_db_config_file = $o_installer->getTheDbConfigFileName();
    $db_type            = $o_installer->getDbType();
}
else {
    $the_db_config_file = $a_install_config['db_file'] . '.php';
    $db_type            = $a_install_config['db_type'];
}
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
