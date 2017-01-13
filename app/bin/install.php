<?php
/**
 * @brief     This file sets up standard stuff for the Framework.
 * @details   This creates the database config and some standard directories.
 *            This should be run from the /app/bin directory of the site.
 * @file      install.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2017-01-13 09:48:15
 * @version   2.0.0
 * @note   <b>Change Log</b><pre>
 *  v2.0.0 - bug fixes and rewrite of the database insert stuff   - 2017-01-13 wer
 *  v1.0.0 - initial version                                      - 2015-11-27 wer
 * </pre>
 */
namespace Ritc;

use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Factories\TwigFactory;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Helper\ConstantsHelper;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;
use Ritc\Library\Services\Router;
use Ritc\Library\Services\Session;

$short_opts = "a:n:h:t:d:u:p:f:l:r:";
$long_opts  = [
    "appname:",
    "namespace:",
    "dbhost:",
    "dbtype:",
    "dbname:",
    "dbuser:",
    "dbpass:",
    "dbprefix:",
    "loader:",
    "libprefix:"
];

$a_options = getopt($short_opts, $long_opts);

$app_name   = '';
$namespace  = '';
$db_host    = 'localhost';
$db_type    = 'mysql';
$db_name    = '';
$db_user    = '';
$db_pass    = '';
$db_prefix  = '';
$lib_prefix = 'ritc_';
$loader     = 'psr4';

foreach ($a_options as $option => $value) {
    switch ($option) {
        case "a":
        case "appname":
            $app_name = $value;
            break;
        case "n":
        case "namespace":
            $namespace = $value;
            break;
        case "h":
        case "dbhost":
            $db_host = $value;
            break;
        case "t":
        case "dbtype":
            $db_type = $value;
            break;
        case "d":
        case "dbname":
            $db_name = $value;
            break;
        case "u":
        case "dbuser":
            $db_user = $value;
            break;
        case "p":
        case "dbpass":
            $db_pass = $value;
            break;
        case "f":
        case "dbprefix":
            $db_prefix = $value;
            break;
        case "l":
        case "loader":
            $loader = $value == 'psr0' ? 'psr0' : 'psr4';
            break;
        case 'r':
        case 'libprefix':
            $lib_prefix = $value;

    }
}

$missing_params = '';

if ($app_name == '') {
    $missing_params .= $missing_params == '' ? "App Name" : ", App Name";
}
if ($namespace == '') {
    $missing_params .= $missing_params == '' ? "Namespace" : ", Namespace";
}
if ($db_name == '') {
    $missing_params .= $missing_params == '' ? "DB Name" : ", DB Name";
}
if ($db_user == '') {
    $missing_params .= $missing_params == '' ? "DB User" : ", DB User";
}
if ($db_pass == '') {
    $missing_params .= $missing_params == '' ? "DB Password" : ", DB Password";
}

if ($missing_params != '') {
    die("Missing argument(s): {$missing_params}\n");
}

if (strpos(__DIR__, 'Library') !== false) {
    die("Please Run this script from the app/bin directory");
}
$base_path = str_replace('/app/bin', '', __DIR__);
define('DEVELOPER_MODE', true);
define('BASE_PATH', $base_path);
define('SITE_PATH', $base_path . '/public');

echo 'Base Path: ' . BASE_PATH . "\n";
echo 'Site Path: ' . SITE_PATH . "\n";

require_once BASE_PATH . '/app/config/constants.php';

if (!file_exists(SRC_PATH . '/Ritc/Library')) {
    die("You must clone the Ritc/Library in the src dir first and any other desired apps.\n");
}

### generate files for autoloader ###
require SRC_PATH . '/Ritc/Library/Helper/AutoloadMapper.php';
$a_dirs = [
    'app_path'    => APP_PATH,
    'config_path' => APP_CONFIG_PATH,
    'src_path'    => SRC_PATH];
$o_cm = new AutoloadMapper($a_dirs);
if (!is_object($o_cm)) {
    die("Could not instance AutoloadMapper");
}
$o_cm->generateMapFiles();

### Setup the database ###
$db_config_file = "db_config_setup.php";
$db_config_file_text =<<<EOT
<?php
return array(
    'driver'     => '{$db_type}',
    'host'       => '{$db_host}',
    'port'       => '',
    'name'       => '{$db_name}',
    'user'       => '{$db_user}',
    'password'   => '{$db_pass}',
    'userro'     => '{$db_user}',
    'passro'     => '{$db_pass}',
    'persist'    => false,
    'prefix'     => '{$db_prefix}',
    'lib_prefix' => '{$lib_prefix}'
);
EOT;

file_put_contents(APP_CONFIG_PATH . '/' . $db_config_file, $db_config_file_text);

$o_loader = require_once VENDOR_PATH . '/autoload.php';

if ($loader == 'psr0') {
    $my_classmap = require_once APP_CONFIG_PATH . '/autoload_classmap.php';
    $o_loader->addClassMap($my_classmap);
}
else {
    $my_namespaces = require_once APP_CONFIG_PATH . '/autoload_namespaces.php';
    foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
        $o_loader->addPsr4($psr4_prefix, $psr0_paths);
    }
}

$o_elog = Elog::start();
$o_elog->write("Test\n", LOG_OFF);
$o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
$o_di = new Di();
$o_di->set('elog', $o_elog);

$o_pdo = PdoFactory::start($db_config_file, 'rw', $o_di);

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);
    if (!is_object($o_db)) {
        $o_elog->write("Could not create a new DbModel\n", LOG_ALWAYS);
        die("Could not get the database to work\n");
    }
    else {
        $o_di->set('db', $o_db);
    }
}
else {
    $o_elog->write("Couldn't connect to database\n", LOG_ALWAYS);
    die("Could not connect to the database\n");
}

switch ($db_type) {
    case 'pgsql':
        $a_sql = include LIBRARY_PATH . '/resources/sql/default_setup_postgresql.php';
        break;
    case 'sqlite':
        $a_sql = array();
        break;
    case 'mysql':
    default:
        $a_sql = include LIBRARY_PATH . '/resources/sql/default_mysql_create.php';
}

$a_data = include LIBRARY_PATH . '/resources/sql/default_data.php';

$o_db->startTransaction();
foreach ($a_sql as $sql) {
    $sql = str_replace('{dbPrefix}', $lib_prefix, $sql);
    if ($o_db->rawExec($sql) === false) {
        $error_message = $o_db->getSqlErrorMessage();
        $o_db->rollbackTransaction();
        die("Database failure\n" . var_export($o_pdo->errorInfo(), true) . " other: " . $error_message . "\n");
    }
}
/*
$a_table_names = [
    'constants',
    'groups',
    'urls',
    'people',
    'navgroups',
    'people_group_map',
    'routes',
    'routes_group_map',
    'navigation',
    'nav_ng_map',
    'page',
];
*/
function createStuff($a_org_values = []) {
    $a_values = [];
    foreach ($a_org_values as $a_value) {
        $a_values[] = $a_value;
    }
    $field_string = '';
    $value_string = '';
    foreach ($a_values[0] as $key => $a_value) {
        $field_string .= $field_string == '' ? $key : ', ' . $key;
        $value_string .= $value_string == '' ? ':' . $key : ', :' . $key;
    }
    return [
        'a_values'      => $a_values,
        'field_string'  => $field_string,
        'values_string' => $value_string
    ];
}
### Enter Constants
$a_stuff = createStuff($a_data['constants']);
$sql =<<<SQL
INSERT INTO {$lib_prefix}constants 
  ({$a_stuff['field_string']}) 
VALUES 
  ({$a_stuff['values_string']})
SQL;
$a_table_info = [
    'table_name'  => $lib_prefix . 'constants',
    'column_name' => 'const_name'
];

$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert constants data\n");
}
else {
    print "Constants Entered.\n";
}
### Enter Groups
$a_stuff = createStuff($a_data['groups']);

$sql =<<<SQL
INSERT INTO {$lib_prefix}groups 
  ({$a_stuff['field_string']}) 
VALUES 
  ({$a_stuff['values_string']})
SQL;
$a_table_info = [
    'table_name'  => $lib_prefix . 'groups',
    'column_name' => 'group_id'
];
$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert groups data\n");
}
else {
    print "Groups Entered.\n";
}
### Enter 'urls'
$a_stuff = createStuff($a_data['urls']);
$sql =<<<SQL
INSERT INTO {$lib_prefix}urls
  ({$a_stuff['field_string']})
VALUES 
  ({$a_stuff['values_string']})
SQL;
$a_table_info = [
    'table_name'  => $lib_prefix . 'urls',
    'column_name' => 'url_id'
];
$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert url data\n");
}
else {
    print "URLs Entered.\n";
}

### Enter 'people'
$a_stuff = createStuff($a_data['people']);

$sql =<<<SQL
INSERT INTO {$lib_prefix}people
  ({$a_stuff['field_string']})
VALUES
  ({$a_stuff['values_string']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'people',
    'column_name' => 'people_id'
];
$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert people data\n");
}
else {
    print "People Entered.\n";
}

### Enter 'navgroups',
$a_stuff = createStuff($a_data['navgroups']);

$sql =<<<SQL
INSERT INTO {$lib_prefix}navgroups
  ({$a_stuff['field_string']})
VALUES
  ({$a_stuff['values_string']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'navgroups',
    'column_name' => 'ng_id'
];
$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert navgroups data\n");
}
else {
    print "navgroups Entered.\n";
}

### Enter 'people_group_map',
$a_stuff = createStuff($a_data['people_group_map']);

$sql =<<<SQL
INSERT INTO {$lib_prefix}people_group_map
  ({$a_stuff['field_string']})
VALUES
  ({$a_stuff['values_string']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'people_group_map',
    'column_name' => 'pgm_id'
];

die("need to fix the values in the pgm");
$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert people_group_map data\n");
}
else {
    print "people_group_map Entered.\n";
}

### Enter 'routes'
$a_stuff = createStuff($a_data['routes']);

$sql =<<<SQL
INSERT INTO {$lib_prefix}routes
  ({$a_stuff['field_string']})
VALUES
  ({$a_stuff['values_string']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'routes',
    'column_name' => 'route_id'
];

die("Need to fix the values in the routes");

$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert routes data\n");
}
else {
    print "routes Entered.\n";
}

### Enter 'routes_group_map'
$a_stuff = createStuff($a_data['routes_group_map']);

$sql =<<<SQL
INSERT INTO {$lib_prefix}routes_group_map
  ({$a_stuff['field_string']})
VALUES
  ({$a_stuff['values_string']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'routes_group_map',
    'column_name' => 'route_id'
];

die("Need to fix the values in the routes_group_map");

$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert routes_group_map data\n");
}
else {
    print "routes_group_map Entered.\n";
}

### Enter 'navigation',
$a_stuff = createStuff($a_data['navigation']);

$sql =<<<SQL
INSERT INTO {$lib_prefix}navigation
  ({$a_stuff['field_string']})
VALUES
  ({$a_stuff['values_string']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'navigation',
    'column_name' => 'route_id'
];

die("Need to fix the values in the navigation");

$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert navigation data\n");
}
else {
    print "navigation Entered.\n";
}

### Enter 'nav_ng_map',
$a_stuff = createStuff($a_data['nav_ng_map']);

$sql =<<<SQL
INSERT INTO {$lib_prefix}nav_ng_map
  ({$a_stuff['field_string']})
VALUES
  ({$a_stuff['values_string']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'nav_ng_map',
    'column_name' => 'route_id'
];

die("Need to fix the values in the nav_ng_map");

$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert nav_ng_map data\n");
}
else {
    print "nav_ng_map Entered.\n";
}

### Enter 'page',
$a_stuff = createStuff($a_data['page']);

$sql =<<<SQL
INSERT INTO {$lib_prefix}page
  ({$a_stuff['field_string']})
VALUES
  ({$a_stuff['values_string']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'page',
    'column_name' => 'route_id'
];

die("Need to fix the values in the page");

$results = $o_db->insert($sql, $a_stuff['a_values'], $a_table_info);
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    $o_db->rollbackTransaction();
    die("Could not insert page data\n");
}
else {
    print "page Entered.\n";
}


$o_db->commitTransaction();

### Create the directories for the new app ###
$app_path = SRC_PATH . '/' . $namespace. '/' . $app_name;
$a_new_dirs = ['Abstracts', 'Controllers', 'Entities', 'Interfaces', 'Models',
'Tests', 'Traits', 'Views', 'resources', 'resources/config', 'resources/sql',
'resources/templates', 'resources/themes', 'resources/templates/default',
'resources/templates/elements', 'resources/templates/pages',
'resources/templates/snippets', 'resources/templates/tests'];

$htaccess_text =<<<EOF
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
    Order deny,allow
    Deny from all
</IfModule>
EOF;

$keep_me_text =<<<EOF
Place Holder
EOF;

$tpl_text = "<h3>An Error Has Occurred</h3>";

if (!file_exists($app_path)) {
    mkdir($app_path, 0755, true);
    file_put_contents($app_path . '/.htaccess', $htaccess_text);
    foreach($a_new_dirs as $dir) {
        $new_dir = $app_path . '/' . $dir;
        $new_file = $new_dir . '/.keepme';
        $new_tpl = $new_dir . '/no_file.twig';
        mkdir($app_path . '/' . $dir, 0755, true);
        if (strpos($dir, 'templates') !== false) {
            file_put_contents($new_tpl, $tpl_text);
        }
        else {
            file_put_contents($new_file, $keep_me_text);
        }
    }
}

$twig_file = file_get_contents(APP_CONFIG_PATH . '/twig_config_example.php');
$new_twig_file = str_replace('/Example/App/resources/templates',  "/{$namespace}/{$app_name}/resources/templates", $twig_file);
file_put_contents(APP_CONFIG_PATH . '/twig_config.php', $new_twig_file);
?>
