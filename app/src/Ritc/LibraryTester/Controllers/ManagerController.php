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
use Ritc\Library\Core\DbModel;
use Ritc\Library\Core\Session;
use Ritc\Library\Interfaces\ControllerInterface;
use Ritc\LibraryTester\Views\ManagerView;

class ManagerController extends Base implements ControllerInterface
{
    private   $a_actions;
    protected $o_db;
    protected $o_session;

    public function __construct(Session $o_session, DbModel $o_db, array $a_actions)
    {
        $this->setPrivateProperties();
        $this->o_session = $o_session;
        $this->o_db      = $o_db;
        $this->a_actions = $a_actions;
    }

    public function render()
    {
        $o_view = new ManagerView($this->o_db);
        $route_method = $this->a_actions['route_method'];
        $route_action = $this->a_actions['route_action'];
        $a_route_args = $this->a_actions['args'];
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
    public function setSession(Session $o_session)
    {
        return null;
    }
    public function getSession()
    {
        return $this->o_session;
    }
}