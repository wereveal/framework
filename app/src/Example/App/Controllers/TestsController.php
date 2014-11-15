<?php
/**
 *  @brief Controller for Tests.
 *  @file TestsController.php
 *  @ingroup ftpadmin controllers
 *  @namespace Ritc/LibraryTester/Controllers
 *  @class TestsController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1
 *  @date 2013-12-14 17:35:05
 *  @note A file in LibraryTester v1 app
 *  @note <pre><b>Change Log</b>
 *      v0.1 - Initial version 2013-12-14
 *  </pre>
**/
namespace Example\App\Controllers;

use Ritc\Library\Services\TwigFactory as Twig;
use Ritc\Library\Services\Elog;

class TestsController implements ControllerInterface
{
    private $o_elog;
    private $o_tpl;

    public function __construct()
    {
        $this->o_tpl  = Twig::start('twig_config.php');
        $this->o_elog = Elog::start();
    }

    /**
     *  Router for the controller.
     *  @param array $a_actions optional, the actions derived from the URL/Form
     *  @param array $a_values optional, the values from a form
     *  @return string html
    **/
    public function renderPage(array $a_actions = array(), array $a_values = array())
    {
        $main_action = isset($a_actions['action2']) ? $a_actions['action2'] : '';
        $this->o_elog->write("The Actions:\n" . var_export($a_actions, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
        switch ($main_action) {
            case 'users':
                return $this->userTestPage();
            case 'config':
                return $this->appConfigTestPage();
            default:
                return $this->defaultPage();
        }
    }
    /**
     *  Returns the default page for testing.
     *  @param none
     *  @return string
    **/
    public function defaultPage()
    {
        return $this->o_tpl->render('@tests/home.twig', array());
    }
    public function appConfigTestPage()
    {
        $show_test_names = true;
        $a_test_values = include dirname(__DIR__) . '/config/config_test_values.php';
        $this->o_elog->write('' . var_export($a_test_values, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
        $o_configTests = new AppConfigTests;
        $o_configTests->setTestValues($a_test_values);
        $o_configTests->setTestOrder($a_test_values['test_order']);
        $o_configTests->runTests();
        $a_twig_values = $o_configTests->returnTestResults($show_test_names);
        $this->o_elog->write('Twig Values: ' . var_export($a_twig_values, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
        return $this->o_tpl->render('@tests/results.twig', $a_twig_values);
    }
    public function userTestPage()
    {
        $a_test_values = include dirname(__DIR__) . '/config/user_test_values.php';
        $o_userTests = new UserTests();
        $o_userTests->setTestOrder($a_test_values['test_order']);
        $o_userTests->setTestValues($a_test_values);
        $o_userTests->runTests();
        $show_test_names = true;
        $a_twig_values = $o_userTests->returnTestResults($show_test_names);
        $this->o_elog->write('Twig Values: ' . var_export($a_twig_values, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
        return $this->o_tpl->render('@tests/results.twig', $a_twig_values);
    }

}
