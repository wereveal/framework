<?php /** @noinspection PhpIncludeInspection */
/**
 * This file sets up the site.
 * Required to get the entire site to work.
 *
 * @package   Ritc_Framework
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2021-12-17 13:41:17
 * @version   2.2.0
 * @change_log
 * - 2.2.0 - updated to php8 standards and features
 * - 2.1.0 - Modification to ConstantsCreator reflected here    - 2018-04-03 wer
 * - 2.0.0 - Next gen start script                              - 2017-05-12 wer
 * @note
 * - _path and _PATH indicates a full server path
 * - _dir and _DIR indicates the path in the site (URI)
 * - Both path and dir do not end with a slash
*/
namespace Ritc;

use Error;
use PDO;
use Ritc\Library\Exceptions\FactoryException;
use Ritc\Library\Exceptions\ModelException;
use Ritc\Library\Exceptions\ServiceException;
use Ritc\Library\Factories\CacheFactory;
use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Factories\TwigFactory;
use Ritc\Library\Helper\CacheHelperSymfony;
use Ritc\Library\Models\ConstantsCreator;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;
use Ritc\Library\Services\Router;
use Ritc\Library\Services\Session;
use Twig\Environment as TwigEnvironment;

## Define Various Constants and Variables
if (!defined('PUBLIC_PATH')) {
    /**
     * Website document root
     *
     * @var string PUBLIC_PATH
     */
    define('PUBLIC_PATH', $_SERVER['DOCUMENT_ROOT']);
}
if (!defined('BASE_PATH')) {
    /**
     * Path to the root of the code.
     *
     * @var string BASE_PATH
     */
    define('BASE_PATH', dirname(__FILE__, 2));
}
require_once BASE_PATH . '/src/config/constants.php';

if (!isset($db_config_file)) {
    $db_config_file = 'db_config.php';
}
if (!isset($twig_config)) {
    $twig_config = 'db';
}

## Autoload Stuff
$o_loader = require VENDOR_PATH . '/autoload.php';

### PSR-4 autoload method
$my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}
### PSR-0 autoload method
$my_classmap = require SRC_CONFIG_PATH . '/autoload_classmap.php';
$o_loader->addClassMap($my_classmap);

try {
    $o_elog = Elog::start();
}
catch (ServiceException $e) {
    die($e->errorMessage());
}
$o_elog->setIgnoreLogOff(false); // turns on logging globally ignoring LOG_OFF when set to true
$o_elog->setErrorHandler(E_USER_WARNING | E_USER_NOTICE | E_USER_ERROR | E_USER_DEPRECATED); // Elog only handles user errors
$o_elog->write("Testing the elog at the very start.\n", LOG_OFF);

try {
    $o_session = Session::start();
}
catch (ServiceException $e) {
    $o_elog->write($e->errorMessage(), LOG_ALWAYS);
    die('Unable to start the session.');
}

try {
    $o_di = new Di();
}
catch (Error $e) {
    $o_elog->write($e->getMessage(), LOG_ALWAYS);
    die('Unable to start Di');
}
$o_di->set('elog', $o_elog);
$o_di->set('session', $o_session);
$o_di->setVar('dbConfig', $db_config_file);

try {
    /** @noinspection PhpRedundantOptionalArgumentInspection */
    $o_pdo = PdoFactory::start($db_config_file, 'rw');
    $o_di->set('pdo', $o_pdo);
}
catch (FactoryException $e) {
    die($e->errorMessage());

}
try {
    $o_db = new DbModel($o_pdo, $db_config_file);
}
catch (Error $e) {
    die('Could not get the database to work: ' . $e->getMessage());
}

$o_db->setElog($o_elog);
$o_di->set('db', $o_db);
if (RODB) {
    try {
        $o_pdo_ro = PdoFactory::start($db_config_file, 'ro');
        $o_di->set('pdo_ro', $o_pdo_ro);
    }
    catch (FactoryException $e) {
        die('Could not start the RO PDOFactory: ' . $e->errorMessage());
    }
    try {
        $o_db_ro = new DbModel($o_pdo_ro, $db_config_file);
    }
    catch (Error $e) {
        die('Could not get the ro database to work: ' . $e->getMessage());
    }
    $o_di->set('rodb', $o_db_ro);
}

try {
    $o_const_creator = ConstantsCreator::start($o_di);
    try {
        $o_const_creator->defineConstants();
    }
    catch (ModelException $e) {
        $o_elog->write("Couldn't create the constants\n", LOG_ALWAYS);
        require_once SRC_CONFIG_PATH . '/fallback_constants.php';
    }
}
catch (ModelException|Error $e) {
    $o_elog->write("Couldn't create the constants\n", LOG_ALWAYS);
    require_once SRC_CONFIG_PATH . '/fallback_constants.php';
}
unset($o_const_creator); // probably unneeded but just in case something gets heavy along the way
                         // php garbage collection will take care of it.

// $a_constants = get_defined_constants(true);
// print '<pre>';
// print_r($a_constants['user']);
// print '<pre>';
// die();
// $o_elog->write(var_export($a_constants['user'], true), LOG_ON);

if (USE_CACHE) {
    $cache_type = defined('CACHE_TYPE')
        ? CACHE_TYPE
        : 'Db';
    $cache_ttl = defined('CACHE_TTL')
        ? CACHE_TTL
        : 604800; // 7 days
    $cache_directory = defined('CACHE_PATH')
        ? CACHE_PATH
        : BASE_PATH . '/cache';
    $a_cache_config = [
        'cache_type' => $cache_type,
        'lifetime'   => $cache_ttl,
        'directory'  => $cache_directory
    ];
    try {
        $o_cache = CacheFactory::start($o_di, $a_cache_config);
    }
    catch (FactoryException $e) {
        die($e->getMessage());
    }
    if (is_object($o_cache)) {
        $o_di->set('cache', $o_cache);
    }
    else {
        die('Unable to create the Cache instance.');
    }
}

$o_session->setIdleTime(SESSION_IDLE_TIME); // has to be here since it relies on the constant being set.
try {
    $o_router = new Router($o_di);
}
catch (Error $e) {
    $message = 'Could not create new instance of Router: ' . $e->getMessage();
    $o_elog->write($message);
    die($message);
}
$o_di->set('router',  $o_router);

if ($twig_config === 'db') {
    if (empty($twig_use_cache)) {
        $twig_use_cache = !defined('DEVELOPER_MODE') || !DEVELOPER_MODE;
    }
    try {
        $o_twig = TwigFactory::getTwig($o_di, $twig_use_cache);
    }
    catch (FactoryException $e) {
        die("Couldn't create twig instance from database. " . $e->errorMessage());
    }
}
else {
    try {
        $o_twig = TwigFactory::getTwig('twig_config.php');
        $o_di->set('twig', $o_twig);
        $o_di->setVar('twigConfig', $twig_config);
    }
    catch (FactoryException $e) {
        die("Couldn't create twig instance from file. " . $e->errorMessage());
    }
}
