<?php
/**
 *  @brief Controller for the Home page.
 *  @file HomeController.php
 *  @ingroup example_app controllers
 *  @namespace Ritc/ExampleApp/Controllers
 *  @class HomeController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1
 *  @date 2013-12-12 13:04:04
 *  @note A file in ExampleApp v1 app
 *  @note <pre><b>Change Log</b>
 *      v0.1 - Initial version 2013-12-12
 *  </pre>
**/
namespace Ritc\ExampleApp\Controllers;

use Ritc\Library\Core\TwigFactory as Twig;

class HomeController implements PageControllerInterface
{
    protected $a_actions;
    protected $a_values;
    protected $o_twig;

    public function __construct(array $a_actions = array(), array $a_values = array())
    {
        $this->o_twig    = Twig::start('twig_config.php');
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
     *  Routes the code to the appropriate methods and returns a string.
     *  @param array $a_actions optional, the actions derived from the URL/Form
     *  @param array $a_values optional, the values from a form
     *  @return str html to be displayed.
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
        $a_home_values = array();
        return $this->o_twig->render('@pages/index.twig', $a_home_values);
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