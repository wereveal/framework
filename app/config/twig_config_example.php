<?php
$app_path     = SRC_PATH . '/Example/App/resources/templates';
$library_path = SRC_PATH . '/Ritc/Library/resources/templates';
if (!defined('LIB_TWIG_PREFIX')) {
    define('LIB_TWIG_PREFIX', 'lib_');
}
if (!defined('TWIG_PREFIX')) {
    define('TWIG_PREFIX', 'app_');
}
return array(
    'default_path'      => $app_path,
    'additional_paths'  => array(
        $library_path . '/default'  => LIB_TWIG_PREFIX . 'default',
        $library_path . '/elements' => LIB_TWIG_PREFIX . 'elements',
        $library_path . '/forms'    => LIB_TWIG_PREFIX . 'forms',
        $library_path . '/pages'    => LIB_TWIG_PREFIX . 'pages',
        $library_path . '/snippets' => LIB_TWIG_PREFIX . 'snippets',
        $library_path . '/tests'    => LIB_TWIG_PREFIX . 'tests',
        $app_path . '/default'      => TWIG_PREFIX . 'default',
        $app_path . '/elements'     => TWIG_PREFIX . 'elements',
        $app_path . '/forms'        => TWIG_PREFIX . 'forms',
        $app_path . '/pages'        => TWIG_PREFIX . 'pages',
        $app_path . '/snippets'     => TWIG_PREFIX . 'snippets',
        $app_path . '/tests'        => TWIG_PREFIX . 'tests'
    ),
    'environment_options' => [
        'cache'       => APP_PATH . '/twig_cache', // APP_PATH . '/twig_cache', or false are most common
        'auto_reload' => true,
        'debug'       => true
    ]
);