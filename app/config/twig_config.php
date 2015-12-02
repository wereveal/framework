<?php
$example_path = SRC_PATH . '/Example/App/resources/templates';
$library_path = SRC_PATH . '/Ritc/Library/resources/templates';
return array(
    'default_path'      => $example_twig,
    'additional_paths'  => array(
        $library_path . '/default'  => 'default',
        $library_path . '/elements' => 'elements',
        $library_path . '/forms'    => 'forms',
        $library_path . '/main'     => 'main',
        $library_path . '/pages'    => 'pages',
        $library_path . '/snippets' => 'snippets',
        $library_path . '/tests'    => 'tests',
        $example_path . '/default'  => 'example_default',
        $example_path . '/elements' => 'example_elements',
        $example_path . '/main'     => 'example_main',
        $example_path . '/pages'    => 'example_pages',
        $example_path . '/snippets' => 'example_snippets',
        $example_path . '/tests'    => 'example_tests'
    ),
    'environment_options' => [
        'cache'       => APP_PATH . '/twig_cache', // APP_PATH . '/twig_cache', or false are most common
        'auto_reload' => true,
        'debug'       => true
    ]
);
