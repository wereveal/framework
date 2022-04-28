<?php
/**
 * @noinspection DuplicatedCode
 * @noinspection PhpUndefinedVariableInspection
 */
/**
 * @brief     This file sets up standard stuff for the Framework.
 * @details   This creates the database config, some standard directories,
 *            and some standard files needed, e.g. index.php and WebController.
 *            This should be run from the cli in the /src/bin directory of the site.
 *            Copy /src/config/install_files/install_config.php.txt to /src/config/install_config.php.
 *            The copied file may have any name as long as it is in /src/config directory, but then it needs to be
 *            called on the cli, e.g. php install.php my_install_config.php
 * @file      /src/bin/install.php
 * @namespace Ritc
 * @package   Ritc_Framework
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @date      2021-12-03 10:30:30
 * @version   5.0.0-alpha.1
 * @change_log
 * - 5.0.0-alpha.1 -                                            - 2021-12-03 wer
 *           Ended experiment of Library being installed
 *           by Composer, moved back to /src/aps/Ritc/Library
 * - 4.0.0 - Moved the Library to be installed vi Composer      - 2020-08-28 wer
 * - 3.0.0 - Changed to use DbCreator and NewAppHelper          - 2017-12-15 wer
 * - 2.5.0 - Added several files to be created in app.          - 2017-05-25 wer
 * - 2.4.0 - changed several settings, defaults, and actions    - 2017-05-11 wer
 * - 2.3.0 - fix to install_files setup.php in public dir       - 2017-05-08 wer
 * - 2.2.0 - bug fixes to get postgresql working                - 2017-04-18 wer
 * - 2.1.0 - lots of bug fixes and additions                    - 2017-01-24 wer
 * - 2.0.0 - bug fixes and rewrite of the database insert stuff - 2017-01-13 wer
 * - 1.0.0 - initial version                                    - 2015-11-27 wer
 */
namespace Ritc;

use Ritc\Library\Exceptions\HelperException;
use Ritc\Library\Exceptions\ModelException;
use Ritc\Library\Helper\AutoloadMapper;
use Ritc\Library\Helper\NewAppHelper;
use Ritc\Library\Models\DbCreator;

$me = str_replace(__DIR__ . '/', '', __FILE__);
include 'setup.php';

$use_transactions = true;
$a_sql            = match ($db_type) {
    'pgsql'  => require $install_files_path . '/default_pgsql_create.php',
    'sqlite' => require $install_files_path . '/default_sqlite_create.php',
    default  => require $install_files_path . '/default_mysql_create.php',
};
$a_data = require $install_files_path .  '/default_data.php';

if ($use_transactions) {
    try {
        $o_db->startTransaction();
    }
    catch (ModelException $e) {
        die('Could not start transaction: ' . $e->errorMessage());
    }
}
$o_db_creator = new DbCreator($o_di);
$o_db_creator->setSql($a_sql);
$o_db_creator->setData($a_data);
$o_db_creator->setDbType($db_type);

print 'Creating Databases: ';
if (!$o_db_creator->createTables()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter Constants
print 'Entering Constants Data: ';
if (!$o_db_creator->insertConstants()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter Groups
print 'Entering Groups Data; ';
if (!$o_db_creator->insertGroups()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'urls'
print 'Entering URLs Data; ';
if (!$o_db_creator->insertUrls()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'people'
print 'Entering People Data; ';
if (!$o_db_creator->insertPeople()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'navgroups',
print 'Entering NavGroups Data; ';
if (!$o_db_creator->insertNavgroups()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'people_group_map',
print 'Entering people_group_map Data; ';
if (!$o_db_creator->insertPGM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'routes'
print 'Entering Routes Data; ';
if (!$o_db_creator->insertRoutes()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'routes_group_map'
print 'Entering routes_group_map Data; ';
if (!$o_db_creator->insertRGM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'navigation',
print 'Entering Navigation Data; ';
if (!$o_db_creator->insertNavigation()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'nav_ng_map'
print 'Entering nav_ng_map Data; ';
if (!$o_db_creator->insertNNM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Twig tables data ###
print "Starting the Twig db stuff. \n";
print "Updating data for app specific\n";
$o_db_creator->createTwigAppConfig();

### Enter twig themes into database ###
print 'Entering Twig Themes Data; ';
if (!$o_db_creator->insertTwigThemes()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter twig prefixes into database ###
print 'Entering Twig Prefixes Data; ';
if (!$o_db_creator->insertTwigPrefixes()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter twig directories into database ###
print 'Entering twig directories Data; ';
if (!$o_db_creator->insertTwigDirs()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter twig templates into database ###
print 'Entering twig templates Data; ';
if (!$o_db_creator->insertTwigTemplates()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'page' ###
print 'Entering Page Data; ';
if (!$o_db_creator->insertPage()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'blocks' ###
print 'Entering Blocks Data; ';
if (!$o_db_creator->insertBlocks()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'Page blocks' ###
print 'Entering Page Blocks Map Data; ';
if (!$o_db_creator->insertPBM()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";

### Enter 'content' ###
print 'Entering Content Data; ';
if (!$o_db_creator->insertContent()) {
    failIt($o_db, $o_db_creator->getErrorMessage(), $use_transactions);
}
print "success\n";
if ($use_transactions) {
    try {
        $o_db->commitTransaction();
        print "Base Data Insert Complete.\n";
    }
    catch (ModelException $e) {
        failIt($o_db, 'Could not commit the transaction.', true);
    }
}

### New App Stuff
print "\nSetting up the app\n";
$o_new_app_helper = new NewAppHelper($o_di);
try {
    $o_new_app_helper->setupProperties($a_install_config);
}
catch (HelperException $e) {
    failIt($o_db, 'Could not set the configuration in the NewAppHelper.', $use_transactions);
}
print "Creating twig db records\n";
try {
    if ($use_transactions) {
        try {
            $o_db->startTransaction();
        }
        catch (ModelException $e) {
            die('Could not start transaction: ' . $e->errorMessage());
        }
    }
    $results = $o_new_app_helper->createTwigDbRecords();
    // need to commit the transaction for subsequent actions
    if ($use_transactions) {
        try {
            $o_db->commitTransaction();
            print "App Twig Data Insert Complete.\n";
        }
        catch (ModelException $e) {
            failIt($o_db, 'Could not commit the transaction.', true);
        }
    }
}
catch (HelperException $e) {
    failIt($o_db, 'Could not create app twig db records.', $use_transactions);
}
print "success\n";

if (!empty($a_install_config['a_groups']) || !empty($a_install_config['a_users'])) {
    try {
        if ($use_transactions) {
            try {
                $o_db->startTransaction();
            }
            catch (ModelException $e) {
                die('Could not start transaction: ' . $e->errorMessage());
            }
        }
        print $o_new_app_helper->createUsers();
        if ($use_transactions) {
            try {
                $o_db->commitTransaction();
                print "App User Data Insert Complete.\n";
            }
            catch (ModelException $e) {
                failIt($o_db, 'Could not commit the transaction.', true);
            }
        }
    }
    catch (HelperException $e) {
        failIt($o_db, $e->getMessage(), $use_transactions);
    }
}

if ($a_install_config['main_twig'] === 'true') {
    print 'Changing the home page template: ';
    try {
        if ($use_transactions) {
            try {
                $o_db->startTransaction();
            }
            catch (ModelException $e) {
                die('Could not start transaction: ' . $e->errorMessage());
            }
        }
        $o_new_app_helper->changeHomePageTpl();
        if ($use_transactions) {
            try {
                $o_db->commitTransaction();
                print "App Home Page Update Complete.\n";
            }
            catch (ModelException $e) {
                failIt($o_db, 'Could not commit the transaction.', true);
            }
        }
        print "Success\n";
    }
    catch (ModelException $e) {
        failIt($o_db, 'Could not change the home page template.', $use_transactions);
    }
}
print "\nCreating the directories for the new app\n";
if ($o_new_app_helper->createDirectories()) {
    print "\nCreating default files.\n";
    $results = ($o_new_app_helper->createDefaultFiles(true) === true)
        ? 'Success!'
        : 'Opps, could not create default files';
    print $results;
}
### generate files for autoloader ###
$a_dirs = [
    'src_path'    => SRC_PATH,
    'config_path' => SRC_CONFIG_PATH,
    'apps_path'   => APPS_PATH
];
$o_cm = new AutoloadMapper($a_dirs);
if (!is_object($o_cm)) {
    die('Could not instance AutoloadMapper');
}
$o_cm->generateMapFiles();

$my_namespaces = require SRC_CONFIG_PATH . '/autoload_namespaces.php';
foreach ($my_namespaces as $psr4_prefix => $psr0_paths) {
    $o_loader->addPsr4($psr4_prefix, $psr0_paths);
}
$my_classmap = require SRC_CONFIG_PATH . '/autoload_classmap.php';
$o_loader->addClassMap($my_classmap);

