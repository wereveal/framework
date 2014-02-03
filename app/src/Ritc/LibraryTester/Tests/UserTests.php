<?php
namespace Ritc\LibraryTester\Tests;

use Ritc\Library\Abstracts\Tester;
use Ritc\Library\Core\Elog;
use Ritc\LibraryTester\Models\Users;

class UserTests extends Tester
{
    protected $a_test_order;
    protected $a_test_values = array();
    protected $failed_subtests;
    protected $failed_test_names = array();
    protected $failed_tests = 0;
    protected $num_o_tests = 0;
    protected $passed_subtests;
    protected $passed_test_names  = array();
    protected $passed_tests = 0;
    private $o_elog;
    private $o_users;

    public function __construct(array $a_test_order = array())
    {
        $this->a_test_order = $a_test_order;
        $this->o_elog = Elog::start();
        $this->o_users = new Users;
    }

    /**
     *  Runs tests where method ends in Tester.
     *  Extends the runTests method in abstract Tester.
     *  @param string $class_name name of the class to be tested
     *  @param array $a_test_order optional, if provided it ignores
     *      the class property $a_test_order and won't try to build one
     *      from the class methods.
     *  @return int $failed_tests
     **/
    public function runTests($class_name = 'Users', array $a_test_order = array())
    {
        if (count($a_test_order) === 0) {
            if (count($this->a_test_order) === 0) {
                $o_ref = new \ReflectionClass($class_name);
                $a_methods = $o_ref->getMethods(\ReflectionMethod::IS_PUBLIC);
                foreach ($a_methods as $a_method) {
                    switch($a_method->name) {
                        case '__construct':
                        case '__set':
                        case '__get':
                        case '__isset':
                        case '__unset':
                        case '__clone':
                            break;
                        default:
                            if (substr($a_method->name, -6) == 'Tester') {
                                $a_test_order[] = $a_method->name;
                            }
                    }
                }
            }
            else {
                $a_test_order = $this->a_test_order;
            }
        }
        $this->o_elog->write(
            "Before -- num_o_tests: '{$this->num_o_tests}' passed tests: '{$this->passed_tests}' failed tests: '{$this->failed_tests}' test names: "
            . var_export($this->failed_test_names, TRUE),
            LOG_OFF,
            __METHOD__ . '.' . __LINE__
        );
        $failed_tests = 0;
        foreach ($a_test_order as $method_name) {
            if (substr($method_name, -6) == 'Tester') {
                $tester_name = $method_name;
                $method_name = $this->shortenName($method_name);
            } else {
                $tester_name = $method_name . 'Tester';
            }
            if ($this->isPublicMethod($class_name, $tester_name)) {
                if ($this->$tester_name()) {
                    $this->passed_tests++;
                    $this->passed_test_names[] = $method_name;
                } else {
                    $failed_tests++;
                    $this->failed_tests++;
                    $this->failed_test_names[] = $method_name;
                }
                $this->num_o_tests++;
            }
        }
        $this->o_elog->write("num_o_tests: {$this->num_o_tests} passed tests: {$this->passed_tests} failed tests: {$this->failed_tests} test names: "
            . var_export($this->failed_test_names, true),
            LOG_OFF,
            __METHOD__ . '.' . __LINE__
        );
        return $failed_tests;
    }

    ### READ ###
    public function findAllTester()
    {
        $results = $this->o_users->findAll();
        if ($this->compareArrays($this->a_test_values['all_users'], $results)) {
            return true;
        }
        else {
            return false;
        }
    }
    public function findUserByIdTester()
    {
        $bad_results = false;
        $results1 = $this->o_users->findUserById($this->a_test_values['single_user']['id']);
        if ($this->compareArrays($this->a_test_values['single_user'], $results1) === false) {
            $this->setSubfailure('findUserById', 'valid_id');
            $bad_results = true;
        }

        $results2 = $this->o_users->findUserById('');
        if ($results2 !== false) {
            $this->setSubfailure('findUserById', 'blank_user');
            $bad_results = true;
        }

        $results3 = $this->o_users->findUserById('not_a_user');
        if ($results3 !== false) {
            $this->setSubfailure('findUserById', 'invalid_user');
            $bad_results = true;
        }

        if ($bad_results) {
            return false;
        }

        return true;
    }

    ### CREATE ###
    public function createUserTester()
    {
        $bad_results = false;
        $results1 = $this->o_users->createUser($this->a_test_values['new_user']);
        $results2 = $this->o_users->createUser();
        $results3 = $this->o_users->createUser(array('bad_stuff' => 'bad_stuff'));
        $results4 = $this->o_users->createUser($this->a_test_values['new_user']);
        if ($results1 === false) {
            $bad_results = true;
            $this->setSubfailure('createUser', 'new user added');
        }
        else {
            $returned = $this->o_users->findUserById($this->a_test_values['new_user']['id']);
            if ($this->compareArrays($this->a_test_values['new_user_hashed'], $returned) === false) {
                $bad_results = true;
                $this->setSubfailure('createUser', 'modified user is invalid');
            }
        }
        if ($results2 !== false) {
            $bad_results = true;
            $this->setSubfailure('createUser', 'no user');
        }
        if ($results3 !== false) {
            $bad_results = true;
            $this->setSubfailure('createUser', 'bad user');
        }
        if ($results4 !== false) {
            $bad_results = true;
            $this->setSubfailure('createUser', 'duplicate user');
        }
        if ($bad_results) {
            return false;
        }
        return true;
    }

    ### UPDATEs ###
    public function updateUserTester()
    {
        $bad_results = false;
        $a_user = $this->a_test_values['modified_user'];
        $results1 = $this->o_users->updateUser($a_user);
        if ($results1 === false) {
            $bad_results = true;
            $this->setSubfailure('updateUser', 'modify user was false');
        }
        else {
            $return1 = $this->o_users->findUserById($a_user['id']);
            $this->o_elog->write(var_export($return1, true), LOG_OFF, __METHOD__ . '.' . __LINE__);
            if ($return1['password'] !== $this->a_test_values['modified_user_hashed']['password']) {
                $bad_results = true;
                $this->setSubfailure('updateUser', 'modify user not modified');
            }
        }
        $results2 = $this->o_users->updateUser();
        if ($results2 !== false) {
            $bad_results = true;
            $this->setSubfailure('updateUser', 'no user returned true');
        }
        $results3 = $this->o_users->updateUser(array('bad_user_stuff' => 'bad_user_stuff'));
        if ($results3 !== false) {
            $bad_results = true;
            $this->setSubfailure('updateUser', 'bad user info returned true');
        }
        if ($bad_results) {
            return false;
        }
        return true;
    }
    public function updateUserDirTester()
    {
        $bad_results = false;
        $a_user = $this->a_test_values['modified_user_dir'];
        $results1 = $this->o_users->updateUserDir($a_user);
        if ($results1 === false) {
            $bad_results = true;
            $this->setSubfailure('updateUserDir', 'valid dir');
        }
        else {
            $returned = $this->o_users->findUserById($a_user['id']);
            if ($a_user['dir'] !== $returned['dir']) {
                $bad_results = true;
                $this->setSubfailure('updateUserDir', 'valid dir was not updated');
            }
        }
        $results2 = $this->o_users->updateUserDir();
        if ($results2 !== false) {
            $bad_results = true;
            $this->setSubfailure('updateUserDir', 'empty array');
        }
        $results3 = $this->o_users->updateUserDir(array('not_valid' => 'not_valid'));
        if ($results3 !== false) {
            $bad_results = true;
            $this->setSubfailure('updateUserDir', 'invalid array');
        }
        if ($bad_results) {
            return false;
        }
        return true;
    }
    public function updateUserPasswordTester()
    {
        $bad_results = false;
        $a_user = array(
            'id'       => $this->a_test_values['new_user']['id'],
            'password' => $this->a_test_values['new_user']['password']
        );
        $results1 = $this->o_users->updateUserPassword($a_user);
        if ($results1 === false) {
            $bad_results = true;
            $this->setSubfailure('updateUserPassword', 'modify user password');
        }
        else {
            $returned = $this->o_users->findUserById($a_user['id']);
            if ($returned['password'] !== $this->a_test_values['new_user_hashed']['password']) {
                $bad_results = true;
                $this->setSubfailure('updateUserPassword', 'modify user password did not match');
            }
        }
        $results2 = $this->o_users->updateUserPassword();
        if ($results2 !== false) {
            $bad_results = true;
            $this->setSubfailure('updateUserDir', 'empty array');
        }
        $results3 = $this->o_users->updateUserPassword(array('not_valid' => 'not_valid'));
        if ($results3 !== false) {
            $bad_results = true;
            $this->setSubfailure('updateUserDir', 'invalid array');
        }
        if ($bad_results) {
            return false;
        }
        return true;
    }

    ### DELETEs ###
    public function deleteUserTester()
    {
        $user_id = $this->a_test_values['new_user']['id'];
        $results1 = $this->o_users->deleteUser($user_id);
        $results2 = $this->o_users->deleteUser();
        $results3 = $this->o_users->deleteUser(100);
        $bad_results = false;
        if ($results1 === false) {
            $this->setSubfailure('deleteUser', 'valid user was not deleted');
            $bad_results = true;
        }
        if ($results2 !== false) {
            $bad_results = true;
            $this->setSubfailure('deleteUser', 'blank user was deleted');
        }
        if ($results3 !== false) {
            $bad_results = true;
            $this->setSubfailure('deleteUser', 'invalid user was deleted');
        }
        if ($bad_results) {
            return false;
        }
        return true;
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
        $o_ref = new \ReflectionClass('Ritc\LibraryTester\Models\Users');
        $o_method = $o_ref->getMethod($method_name);
        return $o_method->IsPublic();
    }

    ### From Tester ###
    /*
     * public function addMethodToTestOrder($method_name = '')
     * public function addTestValue($key = '', $value = '')
     * public function getFailedTestNames
     * public function getFailedTests
     * public function getNumOTests
     * public function getPassedTests
     * public function getTestOrder
     * public function returnTestResults($show_test_names = true)
     * public function runTests($class_name = '', array $a_test_order = array())
     * public function shortenName($method_name = 'Tester')
     * public function setFailures($method_name = '')
     * public function setSubfailure($method_name = '', $test_name = '')
     * public function setTestOrder($a_test_order = '')
     * public function setTestValues($a_test_values = '')
     * public function getTestValues()
     * public function compareArrays($a_good_values = '', $a_check_values = '')
     * public function isPublicMethod($class_name = '', $method_name = '')
     */
}
