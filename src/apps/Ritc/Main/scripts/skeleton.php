<?php
namespace Ritc\Main;

use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\ConstantsHelper;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

ini_set('date.timezone', 'America/Chicago');
if (!defined('BASE_PATH')) {
    $i_am_here = '/src/apps/Ritc/Main/scripts';
    $current_path = dirname(__FILE__);
    $base_path = str_replace($i_am_here, '', $current_path);
    define('BASE_PATH', $base_path);
}
if (!defined('SITE_PATH')) {
    define('SITE_PATH', BASE_PATH . '/public');
}

if (!defined('DEVELOPER_MODE')) {
    define('DEVELOPER_MODE', true);
}
if (isset($argv[1])) {
    switch ($argv[1]) {
        case 'wer':
            $db_config_file  = 'db_config_wer.php';
            break;
        case 'localhost':
            $db_config_file  = 'db_config_local.php';
            break;
        case 'test':
            $db_config_file  = 'db_config_test.php';
            break;
        default:
            $db_config_file  = 'db_config.php';
    }
}
else {
    $db_config_file  = 'db_config.php';
}

require_once BASE_PATH . '/app/config/constants.php';

$o_loader = require_once VENDOR_PATH . '/autoload.php';

### PSR-4 autoload method
$my_namespaces = require_once APP_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}
$o_elog = Elog::start();
$o_elog->setIgnoreLogOff(false); // turns on logging globally ignoring LOG_OFF when set to true
$o_elog->setErrorHandler(E_USER_WARNING | E_USER_NOTICE | E_USER_ERROR);

$o_elog->write("Testing the elog\n", LOG_OFF);
$o_di = new Di();
$o_di->set('elog',    $o_elog);

$o_pdo = PdoFactory::start($db_config_file, 'rw', $o_di);

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);
    $o_db->setElog($o_elog);

    if (!is_object($o_db)) {
        $o_elog->write("Could not create a new DbModel\n", LOG_ALWAYS);
        die("Could not get the database to work");
    }
    else {
        $o_di->set('db', $o_db);
        if (RODB) {
            $o_pdo_ro = PdoFactory::start($db_config_file, 'ro', $o_di);
            if ($o_pdo_ro !== false) {
                $o_db_ro = new DbModel($o_pdo_ro, $db_config_file);
                if (!is_object($o_db_ro)) {
                    $o_elog->write("Could not create a new DbModel for read only\n", LOG_ALWAYS);
                    die("Could not get the database to work");
                }
                $o_di->set('rodb', $o_db_ro);
            }
        }

        if (!ConstantsHelper::start($o_di)) {
            $o_elog->write("Couldn't create the constants\n", LOG_ALWAYS);
            require_once APP_CONFIG_PATH . '/fallback_constants.php';
        }
    }
}
else {
    $o_elog->write("Couldn't connect to database\n", LOG_ALWAYS);
    die("Could not connect to the database");
}

