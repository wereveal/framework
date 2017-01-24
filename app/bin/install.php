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
 * @todo /app/bin/install.php - need to add the twig_config.php file creation.
 * </pre>
 */
namespace Ritc;

use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

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
$install_files_path = APP_CONFIG_PATH . '/install';

/* allows a custom file to be created. Still must be in app/config/install dir */
if (isset($argv[1])) {
    $require_this = $install_files_path . '/' . $argv[1];
}
else {
    $require_this = $install_files_path . '/install_config.php';
}
$a_install = require_once $require_this;

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
$db_config_file = $a_install['db_file'];
$db_config_file_text =<<<EOT
<?php
return [
    'driver'     => '{$a_install['db_type']}',
    'host'       => '{$a_install['db_host']}',
    'port'       => '{$a_install['db_port']}',
    'name'       => '{$a_install['db_name']}',
    'user'       => '{$a_install['db_user']}',
    'password'   => '{$a_install['db_pass']}',
    'userro'     => '{$a_install['db_user']}',
    'passro'     => '{$a_install['db_pass']}',
    'persist'    => {$a_install['db_persist']},
    'prefix'     => '{$a_install['db_prefix']}',
    'db_prefix'  => '{$a_install['db_prefix']}',
    'lib_prefix' => '{$a_install['lib_db_prefix']}'
];
EOT;

file_put_contents(APP_CONFIG_PATH . '/' . $db_config_file, $db_config_file_text);

$o_loader = require_once VENDOR_PATH . '/autoload.php';

if ($a_install['loader'] == 'psr0') {
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

switch ($a_install['db_type']) {
    case 'pgsql':
        $a_sql = require $install_files_path .  '/default_psql_create.php';
        break;
    case 'sqlite':
        $a_sql = array();
        break;
    case 'mysql':
    default:
        $a_sql = require $install_files_path .  '/default_mysql_create.php';
}

function createStrings($a_records = []) {
    $a_record = array_shift($a_records);
    $fields = '';
    $values = '';
    foreach ($a_record as $key => $a_value) {
        $fields .= $fields == '' ? $key : ', ' . $key;
        $values .= $values == '' ? ':' . $key : ', :' . $key;
    }
    return [
        'fields' => $fields,
        'values' => $values
    ];
}

function reorgArray($a_org_values = []) {
    $a_values = [];
    foreach ($a_org_values as $a_value) {
        $a_values[] = $a_value;
    }
    return $a_values;
}

$a_data = require $install_files_path .  '/default_data.php';

$o_db->startTransaction();
foreach ($a_sql as $sql) {
    $sql = str_replace('{dbPrefix}', $a_install['lib_db_prefix'], $sql);
    if ($o_db->rawExec($sql) === false) {
        $error_message = $o_db->getSqlErrorMessage();
        $o_db->rollbackTransaction();
        die("Database failure\n" . var_export($o_pdo->errorInfo(), true) . " other: " . $error_message . "\n");
    }
}

### Enter Constants
print "Entering Constants Data: ";
$a_constants = $a_data['constants'];
$a_strings   = createStrings($a_constants);
$sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}constants
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;
$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'constants',
    'column_name' => 'const_name'
];

if (isset($a_install['twig_prefix']) && isset($a_constants['twig_prefix']['const_value'])) {
    $a_constants['twig_prefix']['const_value'] = $a_install['twig_prefix'];
}
foreach ($a_constants as $key => $a_values) {
    $results = $o_db->insert($sql, $a_values, $a_table_info);
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("Could not insert contants data\n");
    }
    else {
        print "c";
    }
}
print "\n\n";

### Enter Groups
print "Create Groups: ";
$a_groups  = $a_data['groups'];
$a_strings = createStrings($a_groups);

$sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}groups
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;
$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'groups',
    'column_name' => 'group_id'
];
foreach ($a_groups as $key => $a_values) {
    $results = $o_db->insert($sql, $a_values, $a_table_info);
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("Could not insert groups data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_groups[$key]['group_id'] = $ids[0];
        print "g";
    }
}
print "\n\n";

### Enter 'urls'
print "Create URLs: ";
$a_urls    = $a_data['urls'];
$a_strings = createStrings($a_urls);

$sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}urls
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;
$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'urls',
    'column_name' => 'url_id'
];

foreach ($a_urls as $key => $a_record) {
    $results = $o_db->insert($sql, $a_record, $a_table_info);
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("Could not insert url data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_urls[$key]['url_id'] = $ids[0];
        print "u";
    }
}
print "\n\n";

### Enter 'people'
print "Creating People: ";
$a_people  = $a_data['people'];
$a_strings = createStrings($a_people);

$sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}people
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'people',
    'column_name' => 'people_id'
];

foreach ($a_people as $key => $a_person) {
    if (isset($a_install[$key])) {
        $a_person['password'] = $a_install[$key];
    }
    $a_person['password'] = password_hash($a_person['password'], PASSWORD_DEFAULT);
    $results = $o_db->insert($sql, $a_person, $a_table_info);
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("Could not insert people data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_people[$key]['people_id'] = $ids[0];
        print "p";
    }
}
print "\n\n";

### Enter 'navgroups',
print "Creating NavGroups: ";
$a_navgroups = $a_data['navgroups'];
$a_strings   = createStrings($a_navgroups);

$sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}navgroups
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'navgroups',
    'column_name' => 'ng_id'
];
foreach ($a_navgroups as $key => $a_nav_group) {
    $results = $o_db->insert($sql, $a_nav_group, $a_table_info);
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("Could not insert navgroups data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_navgroups[$key]['ng_id'] = $ids[0];
        print "n";
    }
}
print "\n\n";
print_r($a_navgroups);
print "\n\n";

### Enter 'people_group_map',
print "Creating people_group_map: ";
$a_pgm = $a_data['people_group_map'];
$a_strings = createStrings($a_pgm);

$pgm_sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}people_group_map
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

foreach ($a_pgm as $key => $a_raw_data) {
    $people_id = $a_people[$a_raw_data['people_id']]['people_id'];
    $group_id = $a_groups[$a_raw_data['group_id']]['group_id'];
    $a_values = [':people_id' => $people_id, ':group_id' => $group_id];
    $results = $o_db->insert($pgm_sql, $a_values);
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("Could not insert people_group_map data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_pgm[$key]['pgm_id'] = $ids[0];
        print '+';
    }
}
print "\n\n";

### Enter 'routes'
print "Creating Routes: ";
$a_routes  = $a_data['routes'];
$a_strings = createStrings($a_routes);

$routes_sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}routes
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'routes',
    'column_name' => 'route_id'
];

foreach ($a_routes as $key => $a_record) {
    $a_record['url_id'] = $a_urls[$a_record['url_id']]['url_id'];
    $results = $o_db->insert($routes_sql, $a_record, $a_table_info);
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("Could not insert routes data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_routes[$key]['route_id'] = $ids[0];
        print "r";
    }
}
print "\n\n";

### Enter 'routes_group_map'
print "Creating routes_group_map: ";
$a_rgm     = $a_data['routes_group_map'];
$a_strings = createStrings($a_rgm);

$rgm_sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}routes_group_map
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'routes_group_map',
    'column_name' => 'route_id'
];

foreach ($a_rgm as $key => $a_record) {
    $a_record['route_id'] = $a_routes[$a_record['route_id']]['route_id'];
    $a_record['group_id'] = $a_groups[$a_record['group_id']]['group_id'];
    $results = $o_db->insert($rgm_sql, $a_record, $a_table_info);
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("\nCould not insert route_group_map data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_rrgm[$key]['rgm_id'] = $ids[0];
        print "+";
    }
}
print "\n\n";

### Enter 'navigation',
print "Creating Navigation: ";
$a_navigation = $a_data['navigation'];
$a_strings    = createStrings($a_navigation);

$nav_sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}navigation
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'navigation',
    'column_name' => 'route_id'
];

foreach ($a_navigation as $key => $a_record) {
    $a_record['url_id'] = $a_urls[$a_record['url_id']]['url_id'];
    $results = $o_db->insert($nav_sql, $a_record, $a_table_info);
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("\nCould not insert navigation data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_navigation[$key]['nav_id'] = $ids[0];
        print "+";
    }
}
print "\n\n";

### Enter 'nav_ng_map'
print "Creating nav_ng_map: ";
$a_nnm     = $a_data['nav_ng_map'];
$a_strings = createStrings($a_nnm);

$nnm_sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}nav_ng_map
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'nav_ng_map',
    'column_name' => 'route_id'
];

foreach ($a_nnm as $key => $a_record) {
    $a_record['ng_id']  = $a_navgroups[$a_record['ng_id']]['ng_id'];
    $a_record['nav_id'] = $a_navigation[$a_record['nav_id']]['nav_id'];
    $results = $o_db->insert($nnm_sql, $a_record, $a_table_info);
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("\nCould not insert nav_ng_map data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_nnm[$key]['nnm_id'] = $ids[0];
        print "+";
    }
}
print "\n\n";

### Enter 'page',
print "Creating Page: ";
$a_page    = $a_data['page'];
$a_strings = createStrings($a_page);

$page_sql =<<<SQL
INSERT INTO {$a_install['lib_db_prefix']}page
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $a_install['lib_db_prefix'] . 'page',
    'column_name' => 'route_id'
];

foreach ($a_page as $key => $a_record) {
    $a_record['url_id']  = $a_urls[$a_record['url_id']]['url_id'];
    $results = $o_db->insert($page_sql, $a_record, $a_table_info);
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        $o_db->rollbackTransaction();
        die("\nCould not insert page data\n");
    }
    else {
        $ids = $o_db->getNewIds();
        $a_page[$key]['page_id'] = $ids[0];
        print "+";
    }
}
print "\n\n";

if ($o_db->commitTransaction()) {
    print "Data Insert Complete.\n";
}
else {
    die("Could not commit the transaction.\n");
}

### Create the directories for the new app ###
$app_path = SRC_PATH . '/' . $a_install['namespace'] . '/' . $a_install['app_name'];
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

$twig_file = file_get_contents(APP_CONFIG_PATH . '/install/twig_config.php');
$new_twig_file = str_replace('REPLACE_ME',  "{$a_install['namespace']}/{$a_install['app_name']}/resources/templates", $twig_file);
file_put_contents(APP_CONFIG_PATH . '/twig_config.php', $new_twig_file);
?>
