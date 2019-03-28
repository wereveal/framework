<?php
return [
    'namespace'       => 'Ritc',
    'app_name'        => 'App',
    'master_app'      => 'true',
    'author'          => 'William E Reveal',
    'short_author'    => 'wer',
    'email'           => '<bill@revealitconsulting.com>',
    'db_file'         => 'db_config',
    'db_host'         => 'localhost',
    'db_type'         => 'mysql',
    'db_port'         => '3306',
    'db_name'         => 'testsite',
    'db_user'         => 'wereveal',
    'db_pass'         => 'tL1m5iln.DB',
    'db_local_name'   => '',
    'db_local_host'   => '',
    'db_local_type'   => '',
    'db_local_port'   => '',
    'db_local_user'   => '',
    'db_local_pass'   => '',
    'db_site_name'    => '',
    'db_site_host'    => '',
    'db_site_type'    => '',
    'db_site_port'    => '',
    'db_site_user'    => '',
    'db_site_pass'    => '',
    'db_ro_user'      => '',
    'db_ro_pass'      => '',
    'db_persist'      => 'false',
    'db_errmode'      => 'exception',
    'db_prefix'       => 'qca_',
    'lib_db_prefix'   => 'lib_',
    'superadmin'      => 'letGSAin',
    'admin'           => 'letADMin',
    'manager'         => 'letMANin',
    'developer_mode'  => 'true',
    'public_path'     => '',
    'base_path'       => '',
    'server_http_host' => 'test',
	'domain'          => 'qca',
	'tld'             => 'net',
	'specific_host'   => '',
	'install_host'    => 'test',
    'master_twig'     => 'true',
    'app_twig_prefix' => 'qca_',
    'app_db_prefix'   => 'qca_',
    'a_groups'        => [
        [
            'group_name'        => 'group_name',  // required
            'group_description' => 'description', // optional
            'group_auth_lvl'    => '5',           // optional
            'group_immutable'   => 'false'        // optional
        ]
    ],
    'a_users'         => [
       [
           'login_id'    => 'new_user',     // Required, person login id
           'real_name'   => 'Real Name',    // Required, Display name for person
           'password'    => 'new_password', // Required, password for user
           'short_name'  => 'RN',           // Optional, initials normally'
           'description' => 'description',  // Optional
           'active'      => 'true',         // Active user
           'immutable'   => 'false',        // Can't be deleted
           'group_name'  => 'group_name',   // Optional, name of group to be assigned, defaults to Registered, can also be an array
       ]
    ]
];
### Brief ######################################################################
# see install_config.commented.txt for details
# main thing is the db_local_* data is to create a unique db_config_local.php
# file and db_site_* creates a db_config_{specific_host}.php file which can
# be used for different locations (e.g., local workstation, test site, and
# production site (the default))
################################################################################
