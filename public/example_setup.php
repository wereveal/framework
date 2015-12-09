<?php
/**
 * SITE_PATH is where all the public web site files are
 * BASE_PATH is the directory where everything else is
 */
switch ($_SERVER['HTTP_HOST']) { // allows for differing development environment and production environment
    case 'localhost': // uses a special db config file and in Developer mode
        define('DEVELOPER_MODE', true);
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('BASE_PATH', dirname(SITE_PATH));
        $db_config_file = 'db_config_local.php';
        break;
    case 'base.in.public.directory.not.secure.option': // base code is inside the public directory
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('BASE_PATH', SITE_PATH . '/base_directory_name_like_framework');
        break;
    case 'in.a.subdirectory.example': // app is a subdirectory of a larger site
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/the_subdirectory');
        define('BASE_PATH', dirname(dirname(SITE_PATH)) . '/where.the.base.is'); // in this example, outside the public directory
        define('PUBLIC_DIR', '/the_subdirectory');
        $db_config_file = 'db_config_silly.php'; // has a special db_config file
        break;
    case 'test.mysite.com': // test server
        define('DEVELOPER_MODE', true);
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('BASE_PATH', dirname(SITE_PATH));
        $db_config_file = 'db_config_test.php';
        break;
    default: // simple setup and this could be the only two lines needed in this file. 
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('BASE_PATH', dirname(SITE_PATH));
}
