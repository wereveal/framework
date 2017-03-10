<?php
/**
 * @brief     Home Controller for Main.
 * @ingroup   main_controllers
 * @file      Ritc/Main/Controllers/HomeController.php
 * @namespace Ritc\Main\Controllers
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @version   1.0.0-alpha.0
 * @date      2017-01-26 17:19:18
 * @note Change Log
 * - v1.0.0-alpha.0 - Initial version        - 2017-01-26 wer
 * @todo Ritc/Main/Controllers/HomeController.php - Everything
 */
namespace Ritc\Main\Controllers;

use Ritc\Library\Helper\AuthHelper;
use Ritc\Library\Interfaces\ControllerInterface;
use Ritc\Library\Services\Di;
use Ritc\Library\Traits\LogitTraits;
use Ritc\Library\Traits\ManagerControllerTraits;
use Ritc\Main\Views\HomeView;

/**
 * Class HomeController.
 * @class   HomeController
 * @package Ritc\Main
 */
class HomeController implements ControllerInterface
{
    use ManagerControllerTraits, LogitTraits;

    /** @var \Ritc\Main\Views\HomeView  */
    private $o_view;

    /**
     * HomeController constructor.
     * @param \Ritc\Library\Services\Di $o_di
     */
    public function __construct(Di $o_di)
    {
        $this->setupElog($o_di);
        $this->setupController($o_di);
        $this->setupManagerController($o_di);
        $this->o_view = new HomeView($o_di);
    }

    /**
     * Main Router and Puker outer (more descriptive method name).
     * Turns over the hard work to the specific controllers specified by the router.
     * @return string
     */
    public function route()
    {
        if ($this->route_action == 'logout') {
            $o_auth = new AuthHelper($this->o_di);
            $people_id = $_SESSION['login_id'];
            $o_auth->logout($people_id);
        }
        if ($this->loginValid()) {
            return $this->o_view->render();
        }
        elseif ($this->form_action == 'verifyLogin' || $this->route_action == 'verifyLogin') {
            $a_message = $this->verifyLogin();
            if ($a_message['type'] == 'success') {
                return $this->o_view->render($a_message);
            }
            else {
                $login_id = isset($this->a_post['login_id'])
                    ? $this->a_post['login_id']
                    : '';
                return $this->o_view->renderLogin($login_id, $a_message);
            }
        }
        else {
            return $this->o_view->renderLogin();
        }
    }
}