<?php
/**
 *  This file sets up the Ritc Framework.
 *  Required to get the entire framework to work.
 *  @file setup.php
 *  @namespace Ritc
 *  @defgroup ritc_framework
 *  @{
 *      Previously named sitemanager. Was cut down to be just a basic framework
 *      @version 4.0
 *      @defgroup configs Configuration files
 *      @ingroup ritc_framework
 *      @defgroup core The core framework files
 *      @ingroup ritc_framework
 *  }
 *  @defgroup example_app
 *  @{
 *      @version 1.0
 *      @defgroup controllers controller files
 *      @ingroup example_app
 *      @defgroup views classes that create views
 *      @ingroup example_app
 *      @defgroup forms files that define and create forms
 *      @ingroup views
 *      @defgroup model files that do database operations
 *      @ingroup example_app
 *      @defgroup tests unitTesting
 *      @ingroup example_app
 *  }
 *  @note <pre>
 *  NOTE: _path and _PATH indicates a full server path
 *        _dir and _DIR indicates the path in the site (URI)
 *        Both do not end with a slash
 *  </pre>
*/
namespace Ritc;

use Ritc\Library\Core\Config;
use Ritc\Library\Core\DbFactory;
use Ritc\Library\Core\Elog;

if (!defined('SITE_PATH')) {
    define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once SITE_PATH . '/../app/config/constants.php';

$loader = require_once VENDOR_PATH . '/autoload.php';
$my_classmap = require_once APP_PATH . '/config/autoload_classmap.php';
$loader->addClassMap($my_classmap);

$o_elog = Elog::start();
$o_default_dbf = DbFactory::start('db_example_config.php', 'rw');
$o_default_pdo = $o_default_dbf->connect();

if ($o_default_pdo !== false) {
    $o_default_db = new Database($o_default_pdo);
    if (!Config::start($o_default_db)) {
        $o_elog->write("Couldn't create the constants\n", LOG_ALWAYS);
        require_once APP_PATH . '/config/fallback_constants.php';
    }
}
else {
    $o_elog->write("Couldn't connect to database\n", LOG_ALWAYS);
    require_once APP_PATH . '/config/fallback_constants.php';
}
