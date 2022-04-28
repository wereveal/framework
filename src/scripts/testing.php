<?php
/**
 * @noinspection DuplicatedCode
 * @noinspection PhpUndefinedVariableInspection
 */

use Ritc\Library\Exceptions\ModelException;
use Ritc\Library\Models\DbCreator;
use function Ritc\failIt;

if (!str_contains(__DIR__, '/src/scripts')) {
    die('Please Run this script from the /src/scripts directory');
}
$me = str_replace(__DIR__ . '/', '', __FILE__);
include 'setup.php';

$use_transactions = true;

$a_sql  = match ($db_type) {
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

