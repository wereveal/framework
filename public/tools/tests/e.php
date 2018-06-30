<?php
namespace Ritc;

use Ritc\Library\Services\Elog;

if (!\defined('PUBLIC_PATH')) {
    \define('PUBLIC_PATH', $_SERVER['DOCUMENT_ROOT']);
}
if (!\defined('BASE_PATH')) {
    \define('BASE_PATH', \dirname(PUBLIC_PATH));
}
echo PUBLIC_PATH . "\n";
echo BASE_PATH . "\n";
require_once BASE_PATH . '/src/config/constants.php';
$o_loader = require VENDOR_PATH . '/autoload.php';
$my_classmap = require SRC_CONFIG_PATH . '/autoload_classmap.php';
$o_loader->addClassMap($my_classmap);

try {
    $o_elog = Elog::start();
    $o_elog->setIgnoreLogOff(false); // turns on logging globally ignoring LOG_OFF when set to true
    $o_elog->setErrorHandler(E_USER_WARNING | E_USER_NOTICE | E_USER_ERROR);
    $o_elog->write('Test', LOG_JSON, 'This is the Location');
    $o_elog->write('Test', LOG_ON);
    $o_elog->write('Test Two', LOG_ON, 'this is the from');
    $o_elog->write('Test Three', LOG_ON);
    // $o_elog->write("Test", LOG_PHP);
    print 'Yup';
}
catch (Library\Exceptions\ServiceException $e) {
    print 'Nope!';
}
