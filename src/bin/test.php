<?php
namespace Ritc;

use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Factories\TwigFactory;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

$base_path = str_replace('/src/bin', '', __DIR__);
define('DEVELOPER_MODE', true);
define('BASE_PATH', $base_path);
define('PUBLIC_PATH', $base_path . '/public');

echo 'Base Path: ' . BASE_PATH . "\n";
echo 'Site Path: ' . PUBLIC_PATH . "\n";

require_once BASE_PATH . '/src/config/constants.php';

if (!file_exists(APPS_PATH . '/Ritc/Library')) {
    die("You must clone the Ritc/Library in the apps dir first and any other desired apps.\n");
}

$o_loader = require_once VENDOR_PATH . '/autoload.php';

$my_namespaces = require_once SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}

$o_elog = Elog::start();
$o_elog->write("Test\n", LOG_OFF);
$o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
$o_di = new Di();
$o_di->set('elog', $o_elog);

$db_config_file = SRC_CONFIG_PATH . '/db_config_local.php';
$o_pdo = PdoFactory::start($db_config_file, 'rw', $o_di);

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);
    if (!is_object($o_db)) {
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

$file_contents = file_get_contents(SRC_CONFIG_PATH . '/twig_config.php');
print $file_contents . "\n";
$my_string =<<<EOT
    'FtpAdmin' => [
        'path'   => APPS_PATH . '/Ritc/FtpAdmin/resources/templates',
        'prefix' => 'ftp_'
    ],
    ### PlaceHolder ###
EOT;
$file_contents = str_replace('    ### PlaceHolder ###', $my_string, $file_contents);
print $file_contents . "\n";
file_put_contents(SRC_CONFIG_PATH . '/twig_config.php', $file_contents);
?>
