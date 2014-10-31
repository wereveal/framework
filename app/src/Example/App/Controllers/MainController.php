<?php
/**
 *  @brief The main Controller for the whole site.
 *  @file MainController.php
 *  @ingroup example_app controllers
 *  @namespace Ritc/ExampleApp/Controllers
 *  @class MainController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1
 *  @date 2013-12-12 13:21:16
 *  @note A file in ExampleApp v1 app
 *  @note <pre><b>Change Log</b>
 *      v0.1 - Initial version 12/12/2013
 *  </pre>
**/
namespace Ritc\ExampleApp\Controllers;

use Ritc\Library\Core\Elog;
use Ritc\Library\Core\Session;
use Ritc\Library\Core\Actions;

class MainController extends PageControllerAbstract
{
    protected $o_actions;
    protected $o_elog;
    protected $o_sess;

    public function __construct()
    {
        $this->o_elog    = Elog::start();
        $this->o_sess    = Session::start();
        $this->o_actions = new Actions;
    }

    /* public function renderPage() defaults to PageControllerAbstract, no duplication needed */
    /**
     *  Routes the code to the appropriate sub controllers and returns a string.
     *  As much as I have been looking at putting the actual route pairs somewhere else
     *  it feels like the routes are so specific to the controller, they might as well
     *  be in the controller.
     *  @param array $a_actions optional, the actions derived from the URL/Form
     *  @param array $a_values optional, the values from a form
     *  @return string normally html to be displayed.
    **/
    public function router(array $a_actions = array(), array $a_values = array())
    {
        $main_action = isset($a_actions['action1']) ? $a_actions['action1'] : '';
        switch ($main_action) {
            case 'tests':
                $o_tests = new TestsController($a_actions, $a_values);
                return $o_tests->renderPage();
            case 'verify':
            case 'home':
            default:
                $o_home = new HomeController($a_actions, $a_values);
                return $o_home->renderPage();
        }
    }

    ### GETTERs and SETTERs ###
    /**
     * @return \Ritc\Library\Core\Actions
     */
    public function getOActions()
    {
        return $this->o_actions;
    }

    /**
     * This setter won't be allowed.
     * @param none
     * @return bool
     */
    public function setOActions()
    {
        return false;
    }
}
