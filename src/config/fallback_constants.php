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

define('ADMIN_DIR_NAME', 'manager');
define('ADMIN_THEME_NAME', '');
define('ASSETS_DIR_NAME', 'assets');
define('COPYRIGHT_DATE', '2001-2017');
define('CSS_DIR_NAME', 'css');
define('DISPLAY_DATE_FORMAT', 'm/d/Y');
define('DISPLAY_PHONE_FORMAT', 'XXX-XXX-XXXX');
define('EMAIL_DOMAIN', 'replaceme.com');
define('EMAIL_FORM_TO', 'me@replaceme.com');
define('ERROR_EMAIL_ADDRESS', 'webmaster@revealitconsulting.com');
define('FILES_DIR_NAME', 'files');
define('HTML_DIR_NAME', 'html');
define('IMAGE_DIR_NAME', 'images');
define('JS_DIR_NAME', 'js');
define('LIB_TWIG_PREFIX', 'lib_');
define('PAGE_TEMPLATE', 'index.twig');
define('PRIVATE_DIR_NAME', 'private');
define('RIGHTS_HOLDER', 'Reveal IT Consulting');
define('SESSION_IDLE_TIME', 1800);
define('THEMES_DIR', '');
define('THEME_NAME', '');
define('TMP_DIR_NAME', 'tmp');
define('TWIG_PREFIX', 'site_');
if (!defined('DEVELOPER_MODE')) {
    define('DEVELOPER_MODE', true);
}