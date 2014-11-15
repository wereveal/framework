<?php
/**
 *  @brief Controller for the Home page.
 *  @file HomeController.php
 *  @ingroup example_app controllers
 *  @namespace Example\App\Controllers
 *  @class HomeController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1
 *  @date 2013-12-12 13:04:04
 *  @note A file in ExampleApp v1 app
 *  @note <pre><b>Change Log</b>
 *      v0.1 - Initial version 2013-12-12
 *  </pre>
**/
namespace Example\App\Controllers;

use Ritc\Library\Abstracts\Base;
use Ritc\Library\Interfaces\ControllerInterface;

class HomeController extends Base implements ControllerInterface
{
    protected $a_actions;
    protected $a_values;
    protected $o_elog;
    protected $o_session;

    public function __construct(array $a_actions = array(), array $a_values = array())
    {
        $this->a_actions = $a_actions;
        $this->a_values  = $a_values;
    }
    /**
     *  Main Router and Puker outer.
     *  In this case, a stub for the router required by the interface
     *  @param none
     *  @return string $html
    **/
    public function renderPage()
    {
        return $this->router($this->a_actions, $this->a_values);
    }
    /**
     *  Routes the code to the appropriate methods and returns a string.
     *  @param array $a_actions optional, the actions derived from the URL/Form
     *  @param array $a_values optional, the values from a form
     *  @return string html to be displayed.
    **/
    public function router(array $a_actions = array(), array $a_values = array())
    {
        $main_action = isset($a_actions['action2']) ? $a_actions['action2'] : '';
        if (isset($a_values['form_action'])) {
            $main_action = $a_values['form_action'];
        }
        switch ($main_action) {
            case 'home':
            default:
                return $this->renderHome();
        }
    }
    protected function renderHome()
    {
        $a_home_values = ['top_header_text' => 'This is an example app', 'body_text' => 'You can use this to start a new app.'];
        return $this->o_tpl->render('@pages/index.twig', $a_home_values);
    }

    ### SETTERs ###
    public function setActions(array $a_actions = array())
    {
        $this->a_actions = $a_actions;
    }
    public function setSession(Session $o_session)
    {
        $this->o_session = $o_session;
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
    public function getSession()
    {
        return $this->o_session;
    }

}
