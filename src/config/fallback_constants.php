<?php
/**
 *  Defines some required constants.
 *  Used only if the class Constants could not create them from the database.
 *  @file fallback_constants.php
 *  @ingroup ritc configs
 *  _PATH = Full server path
 *  _DIR = Path in web site (URI)
 *  _NAME = Name of item without any path information
**/
namespace Ritc;

if (!defined('ADMIN_DIR_NAME')) {
    define('ADMIN_DIR_NAME', 'manager');
}
if (!defined('ASSETS_DIR_NAME')) {
    define('ASSETS_DIR_NAME', 'assets');
}
if (!defined('CACHE_TYPE')) {
    define('CACHE_TYPE', 'SimplePhpFiles');
}
if (!defined('CACHE_TTL')) {
    define('CACHE_TTL', 604800);
}
if (!defined('COPYRIGHT_DATE')) {
    define('COPYRIGHT_DATE', '2001-2017');
}
if (!defined('CSS_DIR_NAME')) {
    define('CSS_DIR_NAME', 'css');
}
if (!defined('DISPLAY_DATE_FORMAT')) {
    define('DISPLAY_DATE_FORMAT', 'D, M j, Y');
}
if (!defined('DISPLAY_PHONE_FORMAT')) {
    define('DISPLAY_PHONE_FORMAT', 'XXX-XXX-XXXX');
}
if (!defined('EMAIL_DOMAIN')) {
    define('EMAIL_DOMAIN', 'replaceme.com');
}
if (!defined('EMAIL_FORM_TO')) {
    define('EMAIL_FORM_TO', 'me@replaceme.com');
}
if (!defined('ERROR_EMAIL_ADDRESS')) {
    define('ERROR_EMAIL_ADDRESS', 'webmaster@revealitconsulting.com');
}
if (!defined('FILES_DIR_NAME')) {
    define('FILES_DIR_NAME', 'files');
}
if (!defined('FONTS_DIR_NAME')) {
    define('FONTS_DIR_NAME', 'fonts');
}
if (!defined('HTML_DIR_NAME')) {
    define('HTML_DIR_NAME', 'html');
}
if (!defined('IMAGE_DIR_NAME')) {
    define('IMAGE_DIR_NAME', 'images');
}
if (!defined('JS_DIR_NAME')) {
    define('JS_DIR_NAME', 'js');
}
if (!defined('PAGE_TEMPLATE')) {
    define('PAGE_TEMPLATE', 'index.twig');
}
if (!defined('PRIVATE_DIR_NAME')) {
    define('PRIVATE_DIR_NAME', 'private');
}
if (!defined('RIGHTS_HOLDER')) {
    define('RIGHTS_HOLDER', 'Reveal IT Consulting');
}
if (!defined('SESSION_IDLE_TIME')) {
    define('SESSION_IDLE_TIME', 1800);
}
if (!defined('SCSS_DIR_NAME')) {
    define('SCSS_DIR_NAME', 'scss');
}
if (!defined('TMP_DIR_NAME')) {
    define('TMP_DIR_NAME', 'tmp');
}
if (!defined('USE_CACHE')) {
    define('USE_CACHE', true);
}
if (!defined('VENDOR_DIR_NAME')) {
    define('VENDOR_DIR_NAME', 'vendor');
}
if (!defined('DEVELOPER_MODE')) {
    define('DEVELOPER_MODE', false);
}
