<?php
define('DEVELOPER_MODE', true);
switch ($_SERVER['HTTP_HOST']) {
    case 'localhost':
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('PUBLIC_DIR', '');
        $app_in = dirname(SITE_PATH);
        $db_config_file = 'db_local_config.php';
        break;
    case 'not.secure.here':
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('PUBLIC_DIR', '');
        $app_in = SITE_PATH;
        $db_config_file = 'db_config.php';
        break;
    default:
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('PUBLIC_DIR', '');
        $app_in = dirname(SITE_PATH);
        $db_config_file = 'db_config.php';
}
