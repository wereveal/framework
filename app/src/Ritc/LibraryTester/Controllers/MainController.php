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

use Ritc\Library\Abstracts\Base;
use Ritc\Library\Core\DbModel;
use Ritc\Library\Core\Router;
use Ritc\Library\Core\Session;
use Ritc\Library\Interfaces\ControllerInterface;
use Ritc\LibraryTester\Views\MainView;

class MainController extends Base implements ControllerInterface
{
    protected $o_db;
    protected $o_session;

    public function __construct(Session $o_session, DbModel $o_db)
    {
        $this->setPrivateProperties();
        $this->o_session = $o_session;
        $this->o_db      = $o_db;
    }

    /**
     *  Main Router and Puker outer (more descriptive method name).
     *  Turns over the hard work to the specific controllers.
     *  @param none
     *  @return string $html
     **/
    public function render()
    {
        $o_router = new Router($this->o_db);
        $o_router->setElog($this->o_elog);
        $this->logIt("Does this work?", LOG_OFF, __METHOD__ . '.' . __LINE__);
        $a_actions = $o_router->action();
        $this->logIt(var_export($a_actions, true), LOG_OFF, __METHOD__ . '.' . __LINE__);
        if ($a_actions['route_class'] == 'MainController') {
            $o_view = new MainView($this->o_db);
            switch ($a_actions['route_method']) {
                case '':
                default:
                    return $o_view->renderMain();
            }
        }
        else {
            $o_controller = new $a_actions['route_class']($this->o_session, $this->o_db);
            return $o_controller->render($a_actions);
        }
    }

    ### GETTERs and SETTERs ###
    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->o_session;
    }
    /**
     * @param Session $o_session
     */
    public function setSession(Session $o_session)
    {
        $this->o_session = $o_session;
    }

    /* From Base Abstract
    public function getElog()
    public function setElog(Elog $o_elog)
    public function logIt($message = '', $log_type = LOG_OFF, $location = '')
    */
}
