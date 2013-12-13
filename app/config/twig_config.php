<?php
$library_twig = APP_PATH . '/Library/resources/templates';
return array(
    'default_path'        => $library_twig,
    'additional_paths'    => array(
        'ritc_default'  => $library_twig . '/default',
        'ritc_elements' => $library_twig . '/elements',
        'ritc_main'     => $library_twig . '/main',
        'ritc_pages'    => $library_twig . '/pages',
        'ritc_snippets' => $library_twig . '/snippets',
        'ritc_tests'    => $library_twig . '/tests'
    ),
    'environment_options' => array(
        'cache' => APP_PATH . '/twig_cache',
    )
);
