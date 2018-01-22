<?php
return [
    'app_name'        => 'Test',                          // specify the primary app to which generates the home page
    'namespace'       => 'Ritc',                          // specify the root namespace the app will be in
    'author'          => 'William E Reveal',              // specify the author of the app
    'short_author'    => 'wer',                           // abbreviation for the author
    'email'           => '<bill@revealitconsulting.com>', // email of the author
    'db_file'         => 'db_config.php',                 // all db_ keys will be used to create the db_file
    'db_host'         => 'localhost',
    'db_type'         => 'pgsql',
    'db_port'         => '5432',
    'db_name'         => 'wereveal_test',
    'db_user'         => 'wereveal',
    'db_pass'         => 'tL1m5iln.DB',
    'db_persist'      => 'false',                         // normally leave this alone
    'db_errmode'      => 'exception',                     // normally leave this alone
    'db_prefix'       => 'test_',                         // change to be desired prefix or make it empty
    'lib_db_prefix'   => 'lib_',                          // normally leave this alone
    'twig_prefix'     => '',                              // normally leave this alone (defaults to site_)
    'lib_twig_prefix' => '',                              // normally leave this alone (defaults to lib_)
    'app_twig_prefix' => 'test_',                         // change this for app specific twig templates
    'loader'          => 'psr4',                          // normally leave this alone
    'superadmin'      => 'letGSAin',                      // change this
    'admin'           => 'letADMin',                      // change this
    'manager'         => 'letMANin',                      // change this
    'developer_mode'  => 'false',                         // or 'true' when in development
    'public_path'     => '',                              // leave blank for default setting
    'base_path'       => '',                              // leave blank for default setting
    'http_host'       => '',                              // $_SERVER['HTTP_HOST'] results or leave blank for default
	'domain'          => 'wereveal',                      // domain name of site
	'tld'             => 'com'                            // top level domain, e.g., com, net, org
];
