<?php
/**
 *  @brief The main Controller for the whole site.
 *  @file MainController.php
 *  @ingroup example_app controllers
 *  @namespace Ritc/LibraryTester/Controllers
 *  @class MainController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1
 *  @date 2013-12-12 13:21:16
 *  @note A file in LibraryTester v1 app
 *  @note <pre><b>Change Log</b>
 *      v0.1 - Initial version 12/12/2013
 *  </pre>
**/
namespace Ritc\LibraryTester\Controllers;

use Ritc\Library\Core\Elog;
use Ritc\Library\Core\Session;
use Ritc\Library\Core\Actions;
use Ritc\Library\Interfaces\PageControllerInterface as PCI;

class MainController implements PCI
{
    private $o_actions;
    private $o_elog;
    private $o_sess;

    public function __construct()
    {
        $this->o_elog    = Elog::start();
        $this->o_sess    = Session::start();
        $this->o_actions = new Actions;
    }

    /**
     *  Main Router and Puker outer (more descriptive method name).
     *  Turns over the hard work to the specific controllers through the router.
     *  @param none
     *  @return string $html
     **/
    public function renderPage()
    {
        $this->o_actions->setUriActions();
        $a_actions   = $this->o_actions->getUriActions();
        $form_action = $this->o_actions->getFormAction();
        $a_post      = $this->o_actions->getCleanPost();
        $a_get       = $this->o_actions->getCleanGet();
        $a_values    = array('form_action'=>$form_action);
        $a_values    = array_merge($a_values, $a_get, $a_post);
        return $this->router($a_actions, $a_values);
    }
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
        $o_tests = new TestsController($a_actions, $a_values);
        return $o_tests->renderPage();
    }

    ### GETTERs and SETTERs ###
}
