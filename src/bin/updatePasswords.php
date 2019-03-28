<?php
/**
 * @brief     This file updates the passwords for the users in the config file.
 * @file      /src/bin/makeApp.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2018-11-21 10:23:32
 * @version   1.0.0
 * ## Change Log
 * - v1.0.0 - initial version                                      - 2018-11-21 wer
 */
namespace Ritc;

use PDO;
use Ritc\Library\Exceptions\FactoryException;
use Ritc\Library\Exceptions\ModelException;
use Ritc\Library\Exceptions\ServiceException;
use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Models\PeopleModel;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

if (strpos(__DIR__, 'Library') !== false) {
    die('Please Run this script from the src/bin directory');
}
$base_path = str_replace('/src/bin', '', __DIR__);
define('DEVELOPER_MODE', true);
define('BASE_PATH', $base_path);
define('PUBLIC_PATH', $base_path . '/public');

require_once BASE_PATH . '/src/config/constants.php';

if (!file_exists(APPS_PATH . '/Ritc/Library')) {
    die("You must clone the Ritc/Library in the apps dir first and any other desired apps.\n");
}

/* allows a custom file to be created. Still must be in src/config dir */
$people_config = SRC_CONFIG_PATH . '/people_config.php';
if (isset($argv[1])) {
    $people_config = SRC_CONFIG_PATH . '/' . $argv[1];
}
if (!file_exists($people_config)) {
    die('You must create the install_configs configuration file in ' . SRC_CONFIG_PATH . "The default name for the file is install_config.php. You may name it anything but it must then be specified on the command line.\n");
}
$db_config_file = isset($argv[2])
    ? SRC_CONFIG_PATH . '/' . $argv[2]
    : SRC_CONFIG_PATH . '/db_config.php';
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
$o_loader = require VENDOR_PATH . '/autoload.php';

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
    /** @var PDO $o_pdo */
    $o_pdo = PdoFactory::start($db_config_file, 'rw', $o_di);
}
catch (FactoryException $e) {
    die('Unable to start the PdoFactory. ' . $e->errorMessage());
}

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);
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

$o_people = new PeopleModel($o_db);
$o_people->setupElog($o_di);
$a_people = require $people_config;
foreach ($a_people as $login_id => $password) {
    $password = $o_people->hashPass($password);
    try {
        $a_result = $o_people->readByLoginId($login_id);
        $people_id = $a_result[0]['people_id'];
    }
    catch (ModelException $e) {
        die('Could not read the person id: ' . $e->getMessage() . "\n");
    }
    try {
        $o_people->updatePassword($people_id, $password);
    }
    catch (ModelException $e) {
        die('Could not update the password: ' . $e->getMessage() . "\n");
    }
}
