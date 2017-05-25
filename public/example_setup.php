<?php
/**
 * PUBLIC_PATH is where all the public web site files are
 * BASE_PATH is the directory where everything else is
 */
switch ($_SERVER['HTTP_HOST']) { // Allows for different development and production environments. If only the default is used,
    case 'localhost':                                     // shows most commonly used configuration options, see /src/config/constants.php for other possible settings.
        define('PUBLIC_PATH', $_SERVER['DOCUMENT_ROOT']); // required, this is the normal default
        define('BASE_PATH', dirname(PUBLIC_PATH));        // required, this is the normal default
        define('PUBLIC_DIR', '/');                        // optional, this is the default, see in.a.subdirectory.example below for when it would be used.
        define('DEVELOPER_MODE', true);                   // optional, defaults to false, see /src/config/constants.php
        $db_config_file = 'db_config_local.php';          // optional, defaults to db_config.php
        $twig_config    = 'db';                           // optional, defaults to db (which specifies a db config).
        $twig_use_cache = false;                          // optional, defaults to the opposite of DEVELOPER_MODE
        /* If twig_config.php files are to be used instead of database based configuration it should look like this
         * $twig_config = [
         *    'instance_name' => 'main',
         *    'use_default'   => true,
         *    'twig_files'    => [
         *        [
         *            'name'          => 'twig_config.php',
         *            'namespace'     => 'Ritc\Blog',
         *        ],
         *        ...additional files with namespace as needed
         *    ]
         * ];
         */
        break;
    case 'base.in.public.directory.not.secure.option': // base code is inside the public directory
        define('PUBLIC_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('BASE_PATH', PUBLIC_PATH . '/base_directory_name_like_framework');
        break;
    case 'in.a.subdirectory.example': // public dir is a subdirectory of a larger site but the rest is outside
        define('PUBLIC_PATH', $_SERVER['DOCUMENT_ROOT'] . '/the_subdirectory');
        define('BASE_PATH', dirname(dirname(PUBLIC_PATH)) . '/where.the.base.is'); // in this example, outside the public directory
        define('PUBLIC_DIR', '/the_subdirectory');
        $db_config_file = 'db_config_silly.php'; // has a special db_config file
        break;
    case 'test.mysite.com': // test server
        define('DEVELOPER_MODE', true);
        define('PUBLIC_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('BASE_PATH', dirname(PUBLIC_PATH));
        $db_config_file = 'db_config_test.php';
        break;
    default: // simple setup and this could be the only two lines needed in this file.
        define('PUBLIC_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('BASE_PATH', dirname(PUBLIC_PATH));
}
