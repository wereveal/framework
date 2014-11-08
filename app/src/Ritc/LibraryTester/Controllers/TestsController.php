<?php
/**
 *  @brief Controller for Tests.
 *  @file TestsController.php
 *  @ingroup example_app controllers
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
namespace Ritc\LibraryTester\Controllers;

use Ritc\Library\Core\TwigFactory as Twig;
use Ritc\Library\Helper\ViewHelper;
use Ritc\Library\Interfaces\ControllerInterface;
use Ritc\LibraryTester\Tests\ConfigTests;

class TestsController implements ControllerInterface
{
    protected $a_actions;
    protected $a_values;
    private $o_twig;

    public function __construct(array $a_actions = array(), array $a_values = array())
    {
        $this->o_twig = Twig::start('twig_config.php');
        $this->a_actions = $a_actions;
        $this->a_values  = $a_values;
    }

    /**
     *  Main Router and Puker outer.
     *  @param array $a_actions optional, the actions derived from the URL/Form
     *  @param array $a_values optional, the values from a form
     *  @return string html
    **/
    public function render(array $a_actions = array(), array $a_values = array())
    {
        $main_action = isset($a_actions['action1']) ? $a_actions['action1'] : '';
        switch ($main_action) {
            case 'access':
            case 'actions':
            case 'arrays':
            case 'datestimes':
            case 'dbfactory':
            case 'dbmodel':
            case 'elog':
            case 'files':
            case 'html':
            case 'session':
            case 'strings':
            case 'tail':
            case 'twigfactory':
                return $this->defaultPage('The test you have requested has not been written yet.');
            case 'config':
                return $this->configPage();
            case 'home':
            default:
                return $this->defaultPage();
        }
    }
    /**
     *  Returns the default page for testing.
     *  @param string $message defaults to empty.
     *  @return string
    **/
    public function defaultPage($message = '')
    {
        $a_message = array('message' => '');
        if ($message != '') {
            $o_view_helper = new ViewHelper();
            $a_message = $o_view_helper->messageProperties(array('message' => $message, 'type' => 'warning'));
        }
        return $this->o_twig->render('@tester_tests/list.twig', $a_message);
    }
    private function configPage()
    {
        $o_config_tester = new ConfigTests();
        $o_config_tester->runTests();
        $a_results = $o_config_tester->returnTestResults();
        return $this->o_twig->render('@tests/results.twig', $a_results);
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
}
