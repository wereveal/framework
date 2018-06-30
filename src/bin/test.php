<?php
namespace Ritc;

use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Factories\TwigFactory;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

$base_path = str_replace('/src/bin', '', __DIR__);
\define('DEVELOPER_MODE', true);
\define('BASE_PATH', $base_path);
\define('PUBLIC_PATH', $base_path . '/public');

echo 'Base Path: ' . BASE_PATH . "\n";
echo 'Site Path: ' . PUBLIC_PATH . "\n";

require_once BASE_PATH . '/src/config/constants.php';

if (!file_exists(APPS_PATH . '/Ritc/Library')) {
    die("You must clone the Ritc/Library in the apps dir first and any other desired apps.\n");
}

$o_loader = require VENDOR_PATH . '/autoload.php';

$my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}

try {
    $o_elog = Elog::start();
}
catch (Library\Exceptions\ServiceException $e) {
    $o_elog = false;
}
if ($o_elog) {
    $o_elog->write("Test\n", LOG_OFF);
    $o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
}
$o_di = new Di();
$o_di->set('elog', $o_elog);

$db_config_file = SRC_CONFIG_PATH . '/db_config.php';
try {
    $o_pdo = PdoFactory::start($db_config_file, 'rw', $o_di);
}
catch (Library\Exceptions\FactoryException $e) {
    $o_pdo = false;
}

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);
    if (!\is_object($o_db)) {
        $o_elog->write("Could not create a new DbModel\n", LOG_ALWAYS);
        die("Could not get the database to work\n");
    }

    $o_di->set('db', $o_db);
}
else {
    $o_elog->write("Couldn't connect to database\n", LOG_ALWAYS);
    die("Could not connect to the database\n");
}

try {
    $o_twig = TwigFactory::getTwig($o_di, false);
}
catch (Library\Exceptions\FactoryException $e) {
    $o_twig = false;
}
if ($o_twig instanceof \Twig_Environment) {
    print "Yes!\n";
}
else {
    print "Oh NO!\n";
}

