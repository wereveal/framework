<?php
/**
 * @brief     This file sets up standard stuff for the Framework.
 * @details   This creates the database config and some standard directories.
 *            This should be run from the /src/bin directory of the site.
 * @file      makeDb.php
 * @namespace Ritc
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2017-01-13 09:48:15
 * @version   2.0.0
 * @note   <b>Change Log</b><pre>
 *  v2.0.0 - bug fixes and rewrite of the database insert stuff   - 2017-01-13 wer
 *  v1.0.0 - initial version                                      - 2015-11-27 wer
 * </pre>
 * @todo /src/bin/makeDb.php - need to rewrite to use the files in the /src/install_files directory instead of cli args.
 */
namespace Ritc;

use Ritc\Library\Factories\PdoFactory;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Di;
use Ritc\Library\Services\Elog;

$short_opts = 'h:t:d:u:p:f:r:';
$long_opts  = [
    'dbhost:',
    'dbtype:',
    'dbname:',
    'dbuser:',
    'dbpass:',
    'dbprefix:',
    'libprefix:'
];

$a_options = getopt($short_opts, $long_opts);

$db_host    = 'localhost';
$db_type    = 'mysql';
$db_name    = '';
$db_user    = '';
$db_pass    = '';
$db_prefix  = '';
$lib_prefix = 'ritc_';

foreach ($a_options as $option => $value) {
    switch ($option) {
        case 'h':
        case 'dbhost':
            $db_host = $value;
            break;
        case 't':
        case 'dbtype':
            $db_type = $value;
            break;
        case 'd':
        case 'dbname':
            $db_name = $value;
            break;
        case 'u':
        case 'dbuser':
            $db_user = $value;
            break;
        case 'p':
        case 'dbpass':
            $db_pass = $value;
            break;
        case 'f':
        case 'dbprefix':
            $db_prefix = $value;
            break;
        case 'r':
        case 'libprefix':
            $lib_prefix = $value;

    }
}

$missing_params = '';

if ($db_name == '') {
    $missing_params .= $missing_params == '' ? 'DB Name (-d or --dbname=)' : ', DB Name  (-d or --dbname=)';
}
if ($db_user == '') {
    $missing_params .= $missing_params == '' ? 'DB User (-u or --dbuser=)' : ', DB User (-u or --dbuser=)';
}
if ($db_pass == '') {
    $missing_params .= $missing_params == '' ? 'DB Password (-p or --dbpass=)' : ', DB Password (-p or --dbpass=)';
}

if ($missing_params != '') {
    $missing_params .= "\nOther arguments available: -h/--dbhost, -t/--dbtype, -f/--dbprefix, -r/--libprefix";
    die("Missing argument(s): {$missing_params}\n");
}

if (strpos(__DIR__, 'Library') !== false) {
    die('Please Run this script from the src/bin directory');
}
$base_path = str_replace('/src/bin', '', __DIR__);
/**
 *
 */
\define('DEVELOPER_MODE', true);
/**
 *
 */
\define('BASE_PATH', $base_path);
/**
 *
 */
\define('PUBLIC_PATH', $base_path . '/public');

require_once BASE_PATH . '/src/config/constants.php';

if (!file_exists(APPS_PATH . '/Ritc/Library')) {
    die("You must clone the Ritc/Library in the apps dir first and any other desired apps.\n");
}

### Setup the database ###
$db_config_file = 'db_config_setup.php';
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

file_put_contents(SRC_CONFIG_PATH . '/' . $db_config_file, $db_config_file_text);

$o_loader = require_once VENDOR_PATH . '/autoload.php';
$my_namespaces = require_once SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}

try {
    $o_elog = Elog::start();
}
catch (Library\Exceptions\ServiceException $e) {
}
$o_elog->write("Test\n", LOG_OFF);
$o_elog->setIgnoreLogOff(true); // turns on logging globally ignoring LOG_OFF when set to true
$o_di = new Di();
$o_di->set('elog', $o_elog);

try {
    $o_pdo = PdoFactory::start($db_config_file, 'rw');
}
catch (Library\Exceptions\FactoryException $e) {
}

if ($o_pdo !== false) {
    $o_db = new DbModel($o_pdo, $db_config_file);
    if (!\is_object($o_db)) {
        $o_elog->write("Could not create a new DbModel\n", LOG_ALWAYS);
        die("Could not get the database to work\n");
    }

    $o_di->set('db', $o_db);
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

/**
 * @param array $a_records
 * @return array
 */
function createStrings(array $a_records = []) {
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

/**
 * @param array $a_org_values
 * @return array
 */
function reorgArray(array $a_org_values = []) {
    $a_values = [];
    foreach ($a_org_values as $a_value) {
        $a_values[] = $a_value;
    }
    return $a_values;
}

$a_data = include LIBRARY_PATH . '/resources/sql/default_data.php';

try {
    $o_db->startTransaction();
}
catch (Library\Exceptions\ModelException $e) {
}
foreach ($a_sql as $sql) {
    $sql = str_replace('{dbPrefix}', $lib_prefix, $sql);
    try {
        if ($o_db->rawExec($sql) === false) {
            $error_message = $o_db->getSqlErrorMessage();
            try {
                $o_db->rollbackTransaction();
            }
            catch (Library\Exceptions\ModelException $e) {
            }
            die("Database failure\n" . var_export($o_pdo->errorInfo(), true) . " other: " . $error_message . "\n");
        }
    }
    catch (Library\Exceptions\ModelException $e) {
    }
}

### Enter Constants
$a_values    = $a_data['constants'];
$a_constants = reorgArray($a_values);
$a_strings   = createStrings($a_values);

$sql =<<<SQL
INSERT INTO {$lib_prefix}constants
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;
$a_table_info = [
    'table_name'  => $lib_prefix . 'constants',
    'column_name' => 'const_name'
];

try {
    $results = $o_db->insert($sql, $a_constants, $a_table_info);
}
catch (Library\Exceptions\ModelException $e) {
}
if ($results === false) {
    print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
    try {
        $o_db->rollbackTransaction();
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    die("Could not insert constants data\n");
}

print "Constants Entered.\n\n";

### Enter Groups
print 'Create Groups: ';
$a_groups  = $a_data['groups'];
$a_strings = createStrings($a_groups);

$sql =<<<SQL
INSERT INTO {$lib_prefix}groups
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;
$a_table_info = [
    'table_name'  => $lib_prefix . 'groups',
    'column_name' => 'group_id'
];
foreach ($a_groups as $key => $a_values) {
    try {
        $results = $o_db->insert($sql, $a_values, $a_table_info);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("Could not insert groups data\n");
    }

    $ids                        = $o_db->getNewIds();
    $a_groups[$key]['group_id'] = $ids[0];
    print 'g';
}
print "\n\n";

### Enter 'urls'
print 'Create URLs: ';
$a_urls    = $a_data['urls'];
$a_strings = createStrings($a_urls);

$sql =<<<SQL
INSERT INTO {$lib_prefix}urls
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;
$a_table_info = [
    'table_name'  => $lib_prefix . 'urls',
    'column_name' => 'url_id'
];

foreach ($a_urls as $key => $a_record) {
    try {
        $results = $o_db->insert($sql, $a_record, $a_table_info);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("Could not insert url data\n");
    }

    $ids                    = $o_db->getNewIds();
    $a_urls[$key]['url_id'] = $ids[0];
    print 'u';
}
print "\n\n";

### Enter 'people'
print 'Creating People: ';
$a_people  = $a_data['people'];
$a_strings = createStrings($a_people);

$sql =<<<SQL
INSERT INTO {$lib_prefix}people
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'people',
    'column_name' => 'people_id'
];

foreach ($a_people as $key => $a_person) {
    try {
        $results = $o_db->insert($sql, $a_person, $a_table_info);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("Could not insert people data\n");
    }

    $ids                         = $o_db->getNewIds();
    $a_people[$key]['people_id'] = $ids[0];
    print 'p';
}
print "\n\n";

### Enter 'navgroups',
print 'Creating NavGroups: ';
$a_navgroups = $a_data['navgroups'];
$a_strings   = createStrings($a_navgroups);

$sql =<<<SQL
INSERT INTO {$lib_prefix}navgroups
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'navgroups',
    'column_name' => 'ng_id'
];
foreach ($a_navgroups as $key => $a_nav_group) {
    try {
        $results = $o_db->insert($sql, $a_nav_group, $a_table_info);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("Could not insert navgroups data\n");
    }

    $ids                        = $o_db->getNewIds();
    $a_navgroups[$key]['ng_id'] = $ids[0];
    print 'n';
}
print "\n\n";

### Enter 'people_group_map',
print 'Creating people_group_map: ';
$a_pgm = $a_data['people_group_map'];
$a_strings = createStrings($a_pgm);

$pgm_sql =<<<SQL
INSERT INTO {$lib_prefix}people_group_map
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

foreach ($a_pgm as $key => $a_raw_data) {
    $people_id = $a_people[$a_raw_data['people_id']]['people_id'];
    $group_id = $a_groups[$a_raw_data['group_id']]['group_id'];
    $a_values = [':people_id' => $people_id, ':group_id' => $group_id];
    try {
        $results = $o_db->insert($pgm_sql, $a_values);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if (empty($results)) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("Could not insert people_group_map data\n");
    }

    $ids                   = $o_db->getNewIds();
    $a_pgm[$key]['pgm_id'] = $ids[0];
    print '+';
}
print "\n\n";

### Enter 'routes'
print 'Creating Routes: ';
$a_routes  = $a_data['routes'];
$a_strings = createStrings($a_routes);

$routes_sql =<<<SQL
INSERT INTO {$lib_prefix}routes
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'routes',
    'column_name' => 'route_id'
];

foreach ($a_routes as $key => $a_record) {
    $a_record['url_id'] = $a_urls[$a_record['url_id']]['url_id'];
    try {
        $results = $o_db->insert($routes_sql, $a_record, $a_table_info);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("Could not insert routes data\n");
    }

    $ids                        = $o_db->getNewIds();
    $a_routes[$key]['route_id'] = $ids[0];
    print 'r';
}
print "\n\n";

### Enter 'routes_group_map'
print 'Creating routes_group_map: ';
$a_rgm     = $a_data['routes_group_map'];
$a_strings = createStrings($a_rgm);

$rgm_sql =<<<SQL
INSERT INTO {$lib_prefix}routes_group_map
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'routes_group_map',
    'column_name' => 'route_id'
];

foreach ($a_rgm as $key => $a_record) {
    $a_record['route_id'] = $a_routes[$a_record['route_id']]['route_id'];
    $a_record['group_id'] = $a_groups[$a_record['group_id']]['group_id'];
    try {
        $results = $o_db->insert($rgm_sql, $a_record, $a_table_info);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("\nCould not insert route_group_map data\n");
    }

    $ids                    = $o_db->getNewIds();
    $a_rrgm[$key]['rgm_id'] = $ids[0];
    print '+';
}
print "\n\n";

### Enter 'navigation',
print 'Creating Navigation: ';
$a_navigation = $a_data['navigation'];
$a_strings    = createStrings($a_navigation);

$nav_sql =<<<SQL
INSERT INTO {$lib_prefix}navigation
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'navigation',
    'column_name' => 'route_id'
];

foreach ($a_navigation as $key => $a_record) {
    $a_record['url_id'] = $a_urls[$a_record['url_id']]['url_id'];
    try {
        $results = $o_db->insert($nav_sql, $a_record, $a_table_info);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("\nCould not insert navigation data\n");
    }

    $ids                          = $o_db->getNewIds();
    $a_navigation[$key]['nav_id'] = $ids[0];
    print '+';
}
print "\n\n";

### Enter 'nav_ng_map'
print 'Creating nav_ng_map: ';
$a_nnm     = $a_data['nav_ng_map'];
$a_strings = createStrings($a_nnm);

$nnm_sql =<<<SQL
INSERT INTO {$lib_prefix}nav_ng_map
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'nav_ng_map',
    'column_name' => 'route_id'
];

foreach ($a_nnm as $key => $a_record) {
    $a_record['ng_id']  = $a_navgroups[$a_record['ng_id']]['ng_id'];
    $a_record['nav_id'] = $a_navigation[$a_record['nav_id']]['nav_id'];
    try {
        $results = $o_db->insert($nnm_sql, $a_record, $a_table_info);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("\nCould not insert nav_ng_map data\n");
    }

    $ids                   = $o_db->getNewIds();
    $a_nnm[$key]['nnm_id'] = $ids[0];
    print '+';
}
print "\n\n";

### Enter 'page',
print 'Creating Page: ';
$a_page    = $a_data['page'];
$a_strings = createStrings($a_page);

$page_sql =<<<SQL
INSERT INTO {$lib_prefix}page
  ({$a_strings['fields']})
VALUES
  ({$a_strings['values']})
SQL;

$a_table_info = [
    'table_name'  => $lib_prefix . 'page',
    'column_name' => 'route_id'
];

foreach ($a_page as $key => $a_record) {
    $a_record['url_id']  = $a_urls[$a_record['url_id']]['url_id'];
    try {
        $results = $o_db->insert($page_sql, $a_record, $a_table_info);
    }
    catch (Library\Exceptions\ModelException $e) {
    }
    if ($results === false) {
        print $o_db->retrieveFormatedSqlErrorMessage() . "\n";
        try {
            $o_db->rollbackTransaction();
        }
        catch (Library\Exceptions\ModelException $e) {
        }
        die("\nCould not insert page data\n");
    }

    $ids                     = $o_db->getNewIds();
    $a_page[$key]['page_id'] = $ids[0];
    print '+';
}
print "\n\n";

try {
    if ($o_db->commitTransaction()) {
        print "Data Insert Complete.\n";
    }
    else {
        die("Could not commit the transaction.\n");
    }
}
catch (Library\Exceptions\ModelException $e) {
}

