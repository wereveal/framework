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

use Ritc\Library\Core\Config;

if (!defined('SITE_PATH')) {
    define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
}
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(SITE_PATH));
}
if (!defined('APP_PATH')) {
    define('APP_PATH', BASE_PATH . '/app');
}
if (!defined('VENDOR_PATH')) {
    define('VENDOR_PATH', BASE_PATH . '/vendor');
}

$loader = require_once VENDOR_PATH . '/autoload.php';
$my_classmap = require_once APP_PATH . '/config/autoload_classmap.php';
$loader->addClassMap($my_classmap);

require_once APP_PATH . '/config/constants.php';
if (!Config::start()) {
    error_log("Couldn't create the constants\n\n");
    require_once APP_PATH . '/config/fallback_constants.php';
}
