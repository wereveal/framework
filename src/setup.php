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

use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Factories\TwigFactory;
use Ritc\Library\Helper\ConstantsHelper;
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

$o_elog = Elog::start();
$o_elog->setIgnoreLogOff(false); // turns on logging globally ignoring LOG_OFF when set to true
$o_elog->setErrorHandler(E_USER_WARNING | E_USER_NOTICE | E_USER_ERROR);

$o_elog->write("Testing the elog at the very start.\n", LOG_OFF);

$o_session = Session::start();

$o_di = new Di();
$o_di->set('elog', $o_elog);
$o_di->set('session', $o_session);
$o_di->setVar('dbConfig', $db_config_file);

$o_pdo = PdoFactory::start($db_config_file, 'rw', $o_di);
if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);

    if (!is_object($o_db)) {
        $o_elog->write("Could not create a new DbModel\n", LOG_ALWAYS);
        die("Could not get the database to work");
    }
    else {
        $o_db->setElog($o_elog);
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
            require_once SRC_CONFIG_PATH . '/fallback_constants.php';
        }
        $o_session->setIdleTime(SESSION_IDLE_TIME);
        $o_router = new Router($o_di);
        if (!is_object($o_router)) {
            die("Could not create a new Router");
        }
        $o_di->set('router',  $o_router);

        if ($twig_config == 'db') {
            if (!isset($twig_use_cache)) {
                $twig_use_cache = defined('DEVELOPER_MODE') && DEVELOPER_MODE
                    ? false
                    : true;
            }
            $o_twig = TwigFactory::getTwig($o_di, $twig_use_cache);
        }
        else {
            $o_twig = TwigFactory::getTwigByFile($twig_config);
        }
        if (!$o_twig instanceof \Twig_Environment) {
            die("Could not create a new TwigEnviornment");
        }
        $o_di->set('twig', $o_twig);
        $o_di->setVar('twigConfig', $twig_config);
    }
}
else {
    $o_elog->write("Couldn't connect to database\n", LOG_ALWAYS);
    die("Could not connect to the database");
}
