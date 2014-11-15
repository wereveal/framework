<?php
/**
 *  This file sets up the Ritc Framework.
 *  Required to get the entire framework to work.
 *  @file setup.php
 *  @namespace Ritc
 *  @defgroup ritc_library
 *  @{
 *      @version 5.0
 *      @defgroup abstracts
 *      @ingroup ritc_library
 *      @defgroup configs
 *      @ingroup ritc_library
 *      @defgroup controllers
 *      @ingroup ritc_library
 *      @defgroup entities 
 *      @ingroup ritc_library
 *      @defgroup helper classes that do helper things
 *      @ingroup ritc_library
 *      @defgroup interfaces
 *      @ingroup ritc_library
 *      @defgroup models
 *      @ingroup ritc_library
 *      @defgroup services
 *      @ingroup ritc_library
 *      @defgroup views
 *      @ingroup ritc_library
 *  }
 *  @defgroup example_app
 *  @{
 *      @version 1.0
 *      @defgroup controllers controller files
 *      @ingroup example_app
 *      @defgroup views classes that create views
 *      @ingroup example_app
 *      @defgroup models files that do database operations
 *      @ingroup example_app
 *      @defgroup tests unit Testing
 *      @ingroup example_app
 *  }
 *  @note <pre>
 *  NOTE: _path and _PATH indicates a full server path
 *        _dir and _DIR indicates the path in the site (URI)
 *        Both do not end with a slash
 *  </pre>
 */
namespace Ritc;

use Ritc\Library\Services\Config;
use Ritc\Library\Services\DbFactory;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Elog;
use Ritc\Library\Services\Router;
use Ritc\Library\Services\Session;
use Ritc\Library\Services\TwigFactory;
use Zend\ServiceManager\ServiceManager;

if (!defined('SITE_PATH')) {
    define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
}
if (!defined('BASE_PATH')) {
    if (!isset($app_in)) {
        $app_in = 'external';
    }
    if ($app_in == 'site' || $app_in == 'htdocs' || $app_in == 'html') {
        define('BASE_PATH', SITE_PATH);
    }
    else {
        define('BASE_PATH', dirname(dirname(__FILE__)));
    }
}
require_once BASE_PATH . '/app/config/constants.php';

$o_loader = require_once VENDOR_PATH . '/autoload.php';
$my_classmap = require_once APP_CONFIG_PATH . '/autoload_classmap.php';
$o_loader->addClassMap($my_classmap);

$o_elog    = Elog::start();
$o_session = Session::start();

if ($_SERVER['SERVER_NAME'] == 'example.qca.net') {
    $db_config_file = 'db_config.php';
}
else {
    $db_config_file = 'db_example_config.php';
}
$o_dbf = DbFactory::start($db_config_file, 'rw');
$o_dbf->setElog($o_elog);
$o_elog->setIgnoreLogOff(false); // turns on logging globally ignoring LOG_OFF when set to true

$o_pdo = $o_dbf->connect();

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);
    if (!is_object($o_db)) {
        $o_elog->write("Could not create a new DbModel\n", LOG_ALWAYS);
        die("Could not get the database to work");
    }
    else {
        if (!Config::start($o_db)) {
            $o_elog->write("Couldn't create the constants\n", LOG_ALWAYS);
            require_once APP_CONFIG_PATH . '/fallback_constants.php';
        }
        $o_router = new Router($o_db);
        $o_di     = new ServiceManager();
        $o_tf     = TwigFactory::create('twig_config.php');
        $o_tpl   = $o_tf->getTwig();
        $o_di->setService('elog',    $o_elog);
        $o_di->setService('db',      $o_db);
        $o_di->setService('session', $o_session);
        $o_di->setService('route',   $o_router);
        $o_di->setService('tpl',     $o_tpl);
    }
}
else {
    $o_elog->write("Couldn't connect to database\n", LOG_ALWAYS);
    die("Could not connect to the database");
}

