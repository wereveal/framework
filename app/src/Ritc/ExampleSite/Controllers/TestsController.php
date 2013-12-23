<?php
/**
 *  @brief Controller for Tests.
 *  @file TestsController.php
 *  @ingroup example_app controllers
 *  @namespace Ritc/ExampleApp/Controllers
 *  @class TestsController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1
 *  @date 2013-12-14 17:35:05
 *  @note A file in ExampleApp v1 app
 *  @note <pre><b>Change Log</b>
 *      v0.1 - Initial version 2013-12-14
 *  </pre>
**/
namespace Ritc\ExampleApp\Controllers;

use Ritc\ExampleApp\Tests\UserTests;
use Ritc\Library\Core\TwigFactory as Twig;

class TestsController implements PageControllerInterface
{
    protected $a_actions;
    protected $a_values;
    protected $o_twig;

    public function __construct(array $a_actions = array(), array $a_values = array())
    {
        $this->o_twig = Twig::start('twig_config.php');
        $this->a_actions = $a_actions;
        $this->a_values  = $a_values;
    }

    /**
     *  Main Router and Puker outer.
     *  In this case, a stub for the router required by the interface
     *  @param array $a_actions optional, the actions passed from the MainController
     *  @param array $a_values optional, values to act upon
     *  @return str $html
    **/
    public function renderPage()
    {
        return $this->router($this->a_actions, $this->a_values);
    }
    /**
     *  Main Routing Method, used by renderPage method.
     *  @param array $a_actions optional, the actions derived from the URL/Form
     *  @param array $a_values optional, the values from a form
     *  @return str html
    **/
    public function router(array $a_actions = array(), array $a_values = array())
    {
        $main_action = isset($a_actions['action1']) ? $a_actions['action1'] : '';
        switch ($main_action) {
            case 'users':
                return $this->userTestPage();
            default:
                return $this->defaultPage();
        }
    }
    /**
     *  Returns the default page for testing.
     *  @param none
     *  @return str
    **/
    public function defaultPage()
    {
        return $this->o_twig->render('@tests/home.twig', array());
    }
    /**
     * @param array $a_actions
     * @param array $a_values
    **/
    public function userTestPage(array $a_actions = array(), array $a_values = array())
    {
        $o_userTests = new UserTests($a_test_order);
        $o_userTests->setTestValues($a_test_values);
        $results = $o_userTests->runTests("Users", $a_test_order);
    }

    ### SETTERs ###
    public function setActions(array $a_actions = array())
    {
        $this->a_actions = $a_actions;
    }
    public function setValues(array $a_values = array())
    {
        $this->a_values = $a_values;
    }
    public function setActionsValues(array $a_actions = array(), array $a_values = array())
    {
        $this->a_actions = $a_actions;
        $this->a_values  = $a_values;
    }
    ### GETTERs ###
    public function getActions()
    {
        return $this->a_actions;
    }
    public function getValues()
    {
        return $this->a_values;
    }
}