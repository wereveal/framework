<?php /** @noinspection PhpUnusedLocalVariableInspection */
/** @noinspection PhpUndefinedVariableInspection */
/**
 * Basic setup used/included by several scripts
 */
namespace Ritc;

use Ritc\Library\Exceptions\FactoryException;
use Ritc\Library\Exceptions\ModelException;
use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\InstallHelper;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;

if (!str_contains(__DIR__, '/src/scripts')) {
    die('Please Run this script from the src/scripts directory');
}
$base_path = str_replace('/src/scripts', '', __DIR__);

/** @var bool DEVELOPER_MODE */
define('DEVELOPER_MODE', true);
/** Server path to the base of the code.
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
    $message =<<<MESSAGE
    You must create the install_configs configuration file in SRC_CONFIG_PATH
    The default name for the file is install_config.php.
    
    You may name it anything but it must then be specified on the command line.
    MESSAGE;
    die($message  . "\n\n\n");
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
function failIt(DbModel $o_db, string $message = '', bool $use_transactions = false): void
{
    if ($use_transactions) {
        try {
            $o_db->rollbackTransaction();
        }
        catch (ModelException $e) {
            print 'Could not rollback transaction: ' . $e->errorMessage() . "\n";
        }
    }
    die("\nFAIL!\n$message\n");
}
