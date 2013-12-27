<?php
$library_twig = SRC_PATH . '/Ritc/Library/resources/templates';
$example_twig = SRC_PATH . '/Ritc/ExampleApp/resources/templates';
return array(
    'default_path'      => $example_twig,
    'additional_paths'  => array(
        $library_twig . '/default'  => 'library_default',
        $library_twig . '/elements' => 'library_elements',
        $library_twig . '/main'     => 'library_main',
        $library_twig . '/pages'    => 'library_pages',
        $library_twig . '/snippets' => 'library_snippets',
        $library_twig . '/tests'    => 'library_tests',
        $example_twig . '/default'  => 'default',
        $example_twig . '/elements' => 'elements',
        $example_twig . '/main'     => 'main',
        $example_twig . '/pages'    => 'pages',
        $example_twig . '/snippets' => 'snippets',
        $example_twig . '/tests'    => 'tests',
    ),
    'environment_options' => array(
        'cache' => APP_PATH . '/twig_cache',
    )
);
