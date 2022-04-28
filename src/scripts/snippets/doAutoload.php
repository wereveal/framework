<?php

$o_loader = require VENDOR_PATH . '/autoload.php';
if ($a_install['loader'] === 'psr0') {
    $my_classmap = require SRC_CONFIG_PATH . '/autoload_classmap.php';
    $o_loader->addClassMap($my_classmap);
}
else {
    $my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
    foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
        $o_loader->addPsr4($psr4_prefix, $psr0_paths);
    }
}
