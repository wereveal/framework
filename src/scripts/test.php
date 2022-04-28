<?php
namespace Ritc;

use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Factories\TwigFactory;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Twig\Environment as TwigEnvironment;

if (!str_contains(__DIR__, '/src/scripts')) {
    die('Please Run this script from the /src/scripts directory');
}
$base_path = str_replace('/src/scripts', '', __DIR__);
define('DEVELOPER_MODE', true);
define('BASE_PATH', $base_path);
define('PUBLIC_PATH', $base_path . '/public');

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

$o_di = new Di();

$db_config_file = SRC_CONFIG_PATH . '/db_config.php';
try {
    $o_pdo = PdoFactory::start($db_config_file, 'rw');
}
catch (Library\Exceptions\FactoryException $e) {
    $o_pdo = false;
}

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);
    if (!is_object($o_db)) {
        die("Could not get the database to work\n");
    }

    $o_di->set('db', $o_db);
}
else {
    die("Could not connect to the database\n");
}

try {
    $o_twig = TwigFactory::getTwig($o_di, false);
}
catch (Library\Exceptions\FactoryException $e) {
    $o_twig = false;
}
if ($o_twig instanceof TwigEnvironment) {
    print "Yes!\n";
}
else {
    print "Oh NO!\n";
}

