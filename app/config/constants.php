<?php
/**
 *  @brief Define Constants that will be used throughout the website.
 *  @file constants.php
 *  @note <pre>
 *      _PATH = Full server path
 *      _DIR  = Path in web site (URI)
 *      _NAME = Name of item without any path information
 *  @ingroup ritc_framework configs
**/
namespace Ritc;

if (!defined('SITE_PATH')) exit('This file cannot be called directly');

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(SITE_PATH));
}
if (!defined('APP_PATH')) {
    define('APP_PATH', BASE_PATH . '/app');
}
if (!defined('SRC_PATH')) {
    define('SRC_PATH', APP_PATH . '/src');
}
if (!defined('CONF_PATH')) {
    define('CONFIG_PATH', APP_PATH . '/config')
}
if (!defined('VENDOR_PATH')) {
    define('VENDOR_PATH', BASE_PATH . '/vendor');
}
if (!defined('SRC_PATH')) {
    define('SRC_PATH', APP_PATH . '/src');
}
if (!defined('APP_CONFIG_PATH')) {
    define('APP_CONFIG_PATH', APP_PATH . 'config');
}

if (!isset($allow_get) || $allow_get === false) {
    $_GET = array();
}
// Empty some global vars we don't use and don't want to have values in
$_REQUEST = array();

define('PRIVATE_DIR_NAME', 'private');
define('TMP_DIR_NAME', 'tmp');
if (isset($_SERVER['HTTP_HOST'])) {
    define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);
}
else {
    define('SITE_URL', 'localhost');
}

$private_w_path = BASE_PATH . '/' . PRIVATE_DIR_NAME;
$tmp_w_path = BASE_PATH . '/' . TMP_DIR_NAME;
if (file_exists($tmp_w_path)) {
    define('TMP_PATH', $tmp_w_path);
}
else {
    define('TMP_PATH', '/tmp');
}
if (file_exists($private_w_path)) {
    define('PRIVATE_PATH', $private_w_path);
}
else {
    define('PRIVATE_PATH', '');
}


/**
 * Variables used by the classes Elog and Show_Global_Vars.
 * For Production Sites, only USE_PHP_LOG could be true
 * but it can slow things a bit. The class Elog has a
 * method that allows temporary overrides of these global
 * settings in the class (not the constants themselves of course).
**/
define('USE_PHP_LOG',  true);
define('USE_TEXT_LOG', false);
define('LOG_OFF', 0);
define('LOG_ON', 1);
define('LOG_PHP', 1);
define('LOG_BOTH', 2);
define('LOG_EMAIL', 3);
define('LOG_ALWAYS', 4);
define('USE_DEBUG_SGV',  false);
