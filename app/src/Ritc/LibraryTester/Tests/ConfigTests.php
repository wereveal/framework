<?php
namespace Ritc\LibraryTester\Tests;

use Ritc\Library\Basic\Tester;
use Ritc\Library\Services\Config;
use Ritc\Library\Services\DbFactory;
use Ritc\Library\Services\DbModel;
use Ritc\Library\Services\Elog;

class ConfigTests extends Tester
{
    protected $a_test_order;
    protected $a_test_values = array();
    protected $failed_subtests;
    protected $failed_test_names = array();
    protected $failed_tests = 0;
    protected $new_id;
    protected $num_o_tests = 0;
    protected $passed_subtests;
    protected $passed_test_names  = array();
    protected $passed_tests = 0;
    private $o_config;
    private $o_db;
    private $o_elog;

    public function __construct(array $a_test_order = array())
    {
        $this->a_test_order = $a_test_order;
        $this->o_elog = Elog::start();
        $o_dbf = DbFactory::start('db_config.php', 'rw');
        $o_pdo = $o_dbf->connect();
        if ($o_pdo !== false) {
            $this->o_db = new DbModel($o_pdo);
        }
        else {
            $this->o_elog->write('Could not connect to the database', LOG_ALWAYS, __METHOD__ . '.' . __LINE__);
        }
        $this->o_config = Config::start($this->o_db);
    }

    /**
     *  Runs a single test to see if the constants have been set.
     *  @return int $failed_tests
     **/
    public function runTests()
    {
        if ($this->o_config->getSuccess()) {
            $this->passed_tests++;
            $this->passed_test_names[] = 'Created Configs';
        }
        else {
            $this->failed_tests++;
            $this->failed_test_names[] = 'Created Configs';
        }
        $sql = '
            SELECT config_name, config_value
            FROM app_config
            ORDER BY config_name
        ';
        $a_configs = $this->o_db->search($sql);
        foreach ($a_configs as $config_name => $config_value) {
            if (!defined($config_name)) {
                $this->failed_tests++;
                $this->failed_test_names[] = $config_name;
            }
            elseif (constant($confg_name) !== $config_value) {
                $this->failed_tests++;
                $this->failed_test_names[] = $config_name;
            }
            else {
                $this->passed_tests++;
                $this->passed_test_names[] = $config_name;
            }
            $this->num_o_tests++;
        }
        return $this->failed_tests;

    }
    ### Utility ###
    /**
     *  Checks to see if a method is public.
     *  Fixes method names that end in Tester.
     *  Overriding method from abstact Tester class.
     *  @param string $class_name required defaults to ''
     *  @param string $method_name required defaults to ''
     *  @return bool true or false
    **/
    public function isPublicMethod($class_name = '', $method_name = '')
    {
        if ($class_name == '' || $method_name == '') { return false; }
        if (substr($method_name, -6) == 'Tester') {
            $method_name = $this->shortenName($method_name);
        }
        $o_ref = new \ReflectionClass('Ritc\LibraryTester\Models\AppConfig');
        $o_method = $o_ref->getMethod($method_name);
        return $o_method->IsPublic();
    }

}
