<?php
/**
 * @brief     This file sets up the site.
 * @details   Required to get the entire site to work.
 * @file      setup.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2017-05-13 16:01:41
 * @version   2.0.0
 * @note NOTE:
 * - _path and _PATH indicates a full server path
 * - _dir and _DIR indicates the path in the site (URI)
 * - Both paths do not end with a slash
*/
namespace Ritc;

use Ritc\Library\Exceptions\FactoryException;
use Ritc\Library\Exceptions\ModelException;
use Ritc\Library\Exceptions\ServiceException;
use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Factories\TwigFactory;
use Ritc\Library\Models\ConstantsCreator;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;
use Ritc\Library\Services\Router;
use Ritc\Library\Services\Session;

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', $_SERVER['DOCUMENT_ROOT']);
}
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)));
}

require_once BASE_PATH . '/src/config/constants.php';

if (!isset($db_config_file)) {
    $db_config_file = 'db_config.php';
}
if (!isset($twig_config)) {
    $twig_config = 'db';
}
if (!isset($psr_loader)) {
    $psr_loader = 'psr4';
}

$o_loader = require_once VENDOR_PATH . '/autoload.php';

if ($psr_loader == 'psr0') {
    ### PSR-0 autoload method
    $my_classmap = require_once SRC_CONFIG_PATH . '/autoload_classmap.php';
    $o_loader->addClassMap($my_classmap);
}
else {
    ### PSR-4 autoload method
    $my_namespaces = require_once SRC_CONFIG_PATH . '/autoload_namespaces.php';
    foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
        $o_loader->addPsr4($psr4_prefix, $psr0_paths);
    }
}
try {
    $o_elog = Elog::start();
}
catch (ServiceException $e) {
    error_log($e->errorMessage(), 0);
    die($e->errorMessage());
}
$o_elog->setIgnoreLogOff(false); // turns on logging globally ignoring LOG_OFF when set to true
$o_elog->setErrorHandler(E_USER_WARNING | E_USER_NOTICE | E_USER_ERROR | E_USER_DEPRECATED); // Elog only handles user errors
$o_elog->write("Testing the elog at the very start.\n", LOG_OFF);

try {
    $o_session = Session::start();
}
catch (ServiceException $e) {
    $o_elog->write($e->errorMessage(), LOG_ALWAYS);
    die('Unable to start the session.');
}

try {
    $o_di = new Di();
}
catch (\Error $e) {
    $o_elog->write($e->getMessage(), LOG_ALWAYS);
    die('Unable to start Di');
}
$o_di->set('elog', $o_elog);
$o_di->set('session', $o_session);
$o_di->setVar('dbConfig', $db_config_file);

try {
    $o_pdo = PdoFactory::start($db_config_file, 'rw', $o_di);
}
catch (FactoryException $e) {
    die($e->errorMessage());

}
try {
    $o_db = new DbModel($o_pdo, $db_config_file);
}
catch (\Error $e) {
    die("Could not get the database to work: " . $e->getMessage());
}

$o_db->setElog($o_elog);
$o_di->set('db', $o_db);
if (RODB) {
    try {
        $o_pdo_ro = PdoFactory::start($db_config_file, 'ro', $o_di);
    }
    catch (FactoryException $e) {
        die("Could not start the RO PDOFactory: " . $e->errorMessage());
    }
    try {
        $o_db_ro = new DbModel($o_pdo_ro, $db_config_file);
    }
    catch (\Error $e) {
        die("Could not get the ro database to work: " . $e->getMessage());
    }
    $o_di->set('rodb', $o_db_ro);
}

try {
    ConstantsCreator::start($o_di);
}
catch (ModelException $e) {
    $o_elog->write("Couldn't create the constants\n", LOG_ALWAYS);
    require_once SRC_CONFIG_PATH . '/fallback_constants.php';
}
catch (\Error $e) {
    $o_elog->write("Couldn't create the constants\n", LOG_ALWAYS);
    require_once SRC_CONFIG_PATH . '/fallback_constants.php';
}
$o_session->setIdleTime(SESSION_IDLE_TIME); // has to be here since it relies on the constant being set.
try {
    $o_router = new Router($o_di);
}
catch (\Error $e) {
    $o_elog->write("Could not create new instance of Router: " . $e->getMessage());
    die("A fatal error has occured. Please try again");
}
$o_di->set('router',  $o_router);

if ($twig_config == 'db') {
    if (!isset($twig_use_cache)) {
        $twig_use_cache = defined('DEVELOPER_MODE') && DEVELOPER_MODE
            ? false
            : true;
    }
    try {
        $o_twig = TwigFactory::getTwig($o_di, $twig_use_cache);
    }
    catch (FactoryException $e) {

    }
}
else {
    try {

    }
    catch (FactoryException $e) {

    }
    $o_twig = TwigFactory::getTwigByFile($twig_config);
}
if (!$o_twig instanceof \Twig_Environment) {
    die("Could not create a new TwigEnviornment");
}
$o_di->set('twig', $o_twig);
$o_di->setVar('twigConfig', $twig_config);
