<?php
switch ($_SERVER['HTTP_HOST']) {
    case 'not.secure.here':
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('PUBLIC_DIR', '');
        $app_in = SITE_PATH;
        break;
    default:
        define('SITE_PATH', $_SERVER['DOCUMENT_ROOT']);
        define('PUBLIC_DIR', '');
        $app_in = dirname(SITE_PATH);
}
define('DEVELOPER_MODE', true);