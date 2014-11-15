<?php
/**
 *  @brief The main Controller for the manager.
 *  @file ManagerController.php
 *  @ingroup librarytester controllers
 *  @namespace Ritc/LibraryTester/Controllers
 *  @class ManagerController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 1.0.0ß
 *  @date 2014-11-14 06:54:53
 *  @note A file in LibraryTester v1 app
 *  @note <pre><b>Change Log</b>
 *      v1.0.0ß - Initial version - 11/14/2014 wer
 *  </pre>
 **/
namespace Ritc\LibraryTester\Controllers;

use Ritc\Library\Abstracts\Base;
use Ritc\Library\Interfaces\ControllerInterface;
use Ritc\Library\Views\ManagerView;
use Zend\ServiceManager\ServiceManager;

class ManagerController extends Base implements ControllerInterface
{
    private   $a_action;
    protected $o_db;
    protected $o_session;

    public function __construct(ServiceManager $o_di)
    {
        $this->setPrivateProperties();
        $this->o_di      = $o_di;
        $this->o_session = $o_di->get('session');
        $this->o_db      = $o_di->get('db');
        $o_router        = $o_di->get('router');
        $this->a_action  = $o_router->action();
    }

    public function render()
    {
        $o_view = new ManagerView($this->o_di);
        $route_method = $this->a_action['route_method'];
        $route_action = $this->a_action['route_action'];
        $a_route_args = $this->a_action['args'];
        switch ($route_method)
        {
            case 'temp':
                switch ($route_action) {
                    default:
                        $html = $o_view->renderTempPage($a_route_args);
                }
                break;
            case '':
            default:
                $html = $o_view->renderLandingPage();
        }
        return $html;
    }
}