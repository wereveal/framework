<?php
return [
    'namespace'        => 'REQUIRED',
    'app_name'         => 'REQUIRED',
    'master_app'       => 'true',
    'author'           => 'REQUIRED',
    'short_author'     => 'REQUIRED',
    'email'            => 'REQUIRED',
    'db_file'          => 'db_config',
    'db_host'          => 'localhost',
    'db_type'          => 'mysql',
    'db_port'          => '3306',
    'db_name'          => 'REQUIRED',
    'db_user'          => 'REQUIRED',
    'db_pass'          => 'REQUIRED',
    'db_local_name'    => '',
    'db_local_host'    => '',
    'db_local_type'    => '',
    'db_local_port'    => '',
    'db_local_user'    => '',
    'db_local_pass'    => '',
    'db_site_name'     => '',
    'db_site_host'     => '',
    'db_site_type'     => '',
    'db_site_port'     => '',
    'db_site_user'     => '',
    'db_site_pass'     => '',
    'db_ro_user'       => '',
    'db_ro_pass'       => '',
    'db_persist'       => 'false',
    'db_errmode'       => 'exception',
    'db_prefix'        => 'REQUIRED',
    'lib_db_prefix'    => 'lib_',
    'superadmin'       => 'REQUIRED',
    'admin'            => 'REQUIRED',
    'manager'          => 'REQUIRED',
    'developer_mode'   => 'false',
    'public_path'      => '',
    'base_path'        => '',
    'server_http_host' => '',
	'domain'           => 'REQUIRED',
	'tld'              => 'REQUIRED',
	'specific_host'    => '',
	'install_host'     => '',
    'master_twig'      => 'true',
    'app_twig_prefix'  => '',
    'app_theme_name'   => 'base_fluid',
    'app_db_prefix'    => '',
    'a_groups'         => [], // see install_config.commented.txt!
    'a_users'          => []  // see install_config.commented.txt!
];
### Brief ######################################################################
# see install_config.commented.txt for details
# main thing is the db_local_* data is to create a unique db_config_local.php
# file and db_site_* creates a db_config_{specific_host}.php file which can
# be used for different locations (e.g., local workstation, test site, and
# production site (the default))
################################################################################
