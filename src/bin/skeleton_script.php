<?php
/**
 * @brief     This is a skeleton file for running cli scripts.
 * @file      /src/bin/skeleton.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2017-02-08 08:40:47
 * @version   1.0.0
 * @note      Change Log
 * - v1.1.0 - updated for exceptions
 * - v1.0.0 - initial version                                      - 2017-02-08 wer
 */
namespace Ritc;

use Ritc\Library\Exceptions\FactoryException;
use Ritc\Library\Exceptions\ServiceException;
use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

ini_set('date.timezone', 'America/Chicago');

if (str_contains(__DIR__, '/src/bin')) {
    die('Please Run this script from the /src/bin directory');
}
$base_path = str_replace('/src/bin', '', __DIR__);
define('DEVELOPER_MODE', true);
define('BASE_PATH', $base_path);
define('PUBLIC_PATH', $base_path . '/public');

require_once BASE_PATH . '/src/config/constants.php';

if (!file_exists(APPS_PATH . '/Ritc/Library')) {
    die("You must clone the Ritc/Library in the apps dir first and any other desired apps.\n");
}

if (!file_exists(SRC_CONFIG_PATH . '/autoload_namespaces.php')) {
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

}
$o_loader = require VENDOR_PATH . '/autoload.php';

$my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}

try {
    $o_elog = Elog::start();
    $o_elog->setErrorHandler(E_USER_WARNING | E_USER_NOTICE | E_USER_ERROR);
    $o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
    $o_elog->write("Test\n", LOG_OFF);
}
catch (ServiceException $e) {
    die('Unable to start Elog' . $e->errorMessage());
}

$o_di = new Di();
$o_di->set('elog', $o_elog);

$db_config_file = SRC_CONFIG_PATH . '/db_config_local.php';
try {
    $o_pdo = PdoFactory::start($db_config_file, 'rw');
}
catch (FactoryException $e) {
    die('Unable to start the PdoFactory. ' . $e->errorMessage());
}

$o_db = new DbModel($o_pdo, $db_config_file);
$o_di->set('db', $o_db);


