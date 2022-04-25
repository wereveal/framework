<?php
namespace Ritc;
use Ritc\Library\Helper\FileHelper;
include '../../setup.php';
require_once BASE_PATH . '/src/config/constants.php';
## Autoload Stuff
$o_loader = require VENDOR_PATH . '/autoload.php';

### PSR-4 autoload method
$my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}
### PSR-0 autoload method
$my_classmap = require SRC_CONFIG_PATH . '/autoload_classmap.php';
$o_loader->addClassMap($my_classmap);

$a_stuff = FileHelper::fileInfoByPathRecursive(CACHE_PATH, '', 'php');
print "<pre>";
print_r($a_stuff);
print "</pre>";

