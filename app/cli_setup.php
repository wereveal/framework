<?php
/**
 *  This file sets up the Ritc Framework when doing things at the command line
 *  Required to get the entire framework to work.
 *  @file cli_setup.php
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
 *  @defgroup main_app_name
 *  @{
 *      @version 1.0
 *      @defgroup controllers controller files
 *      @ingroup main_app_name
 *      @defgroup views classes that create views
 *      @ingroup main_app_name
 *      @defgroup forms files that define and create forms
 *      @ingroup views
 *      @defgroup model files that do database operations
 *      @ingroup main_app_name
 *      @defgroup tests unitTesting
 *      @ingroup main_app_name
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

if (!defined('SITE_PATH')) {
    define('SITE_PATH', __DIR__);
}
require_once SITE_PATH . '/../app/config/constants.php';

$loader = require_once VENDOR_PATH . '/autoload.php';
$my_classmap = require_once CONFIG_PATH . '/autoload_classmap.php';
$loader->addClassMap($my_classmap);

$o_elog = Elog::start();
$o_default_dbf = DbFactory::start();
$o_default_pdo = $o_default_dbf->connect('db_example_config.php');

if ($o_default_pdo !== false) {
    $o_default_db = new DbModel($o_default_pdo, 'db_example_config.php');
    if (!Config::start($o_default_db)) {
        $o_elog->write("Couldn't create the constants\n", LOG_ALWAYS);
        require_once CONFIG_PATH . '/fallback_constants.php';
    }
}
else {
    $o_elog->write("Couldn't connect to database\n", LOG_ALWAYS);
    require_once CONFIG_PATH . '/fallback_constants.php';
}
