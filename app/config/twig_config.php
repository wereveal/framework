<?php
$library_twig = SRC_PATH . '/Ritc/Library/resources/templates';
$example_twig = SRC_PATH . '/Ritc/ExampleApp/resources/templates';
$tester_twig  = SRC_PATH . '/Ritc/LibraryTester/resources/templates';
return array(
    'default_path'      => $example_twig,
    'additional_paths'  => array(
        $library_twig . '/default'  => 'default',
        $library_twig . '/elements' => 'elements',
        $library_twig . '/main'     => 'main',
        $library_twig . '/pages'    => 'pages',
        $library_twig . '/snippets' => 'snippets',
        $library_twig . '/tests'    => 'tests',
        $example_twig . '/default'  => 'example_default',
        $example_twig . '/elements' => 'example_elements',
        $example_twig . '/main'     => 'example_main',
        $example_twig . '/pages'    => 'example_pages',
        $example_twig . '/snippets' => 'example_snippets',
        $example_twig . '/tests'    => 'example_tests',
        $tester_twig  . '/default'  => 'tester_default',
        $tester_twig  . '/elements' => 'tester_elements',
        $tester_twig  . '/main'     => 'tester_main',
        $tester_twig  . '/pages'    => 'tester_pages',
        $tester_twig  . '/snippets' => 'tester_snippets',
        $tester_twig  . '/tests'    => 'tester_tests',
    ),
    'environment_options' => array(
        'cache' => APP_PATH . '/twig_cache',
    )
);
