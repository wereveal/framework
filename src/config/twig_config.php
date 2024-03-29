<?php
$a_default_dirs = [
    'elements',
    'forms',
    'pages',
    'snippets',
    'tests',
    'themes'
];

$src_twig_prefix = 'site_';
if (!defined('LIB_TWIG_PREFIX')) {
    define('LIB_TWIG_PREFIX', 'lib_');
}
if (!defined('TWIG_PREFIX')) {
    define('TWIG_PREFIX', 'site_');
}

$a_places = [
    'Site' => [
        'path'   => SRC_PATH . '/templates',
        'prefix' => TWIG_PREFIX
    ],
    'Library' => [
        'path'   => LIBRARY_PATH . '/resources/templates',
        'prefix' => LIB_TWIG_PREFIX
    ]
];

$a_additional_paths = [];
foreach ($a_places as $name => $a_place) {
    foreach ($a_default_dirs as $dir) {
        $a_additional_paths[$a_place['path'] . '/' . $dir] = $a_place['prefix'] . $dir;
    }
}

return [
    'default_path'      => $a_places['Site']['path'],
    'additional_paths'  => $a_additional_paths,
    'environment_options' => [
        // for 'cache' SRC_PATH . '/twig_cache' and false are most common values
        # 'cache'       => SRC_PATH . '/twig_cache',
        'cache'       => false,
        'auto_reload' => true,
        'debug'       => true
    ]
];
