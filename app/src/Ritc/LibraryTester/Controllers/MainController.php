<?php
/**
 *  @brief The main Controller for the whole site.
 *  @file MainController.php
 *  @ingroup example_app controllers
 *  @namespace Ritc/LibraryTester/Controllers
 *  @class MainController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 1.1.0ß
 *  @date 2013-12-12 13:21:16
 *  @note A file in LibraryTester v1 app
 *  @note <pre><b>Change Log</b>
 *      v1.1.0ß - changed to use Zend's ServiceManager for IOC
 *      v0.1 - Initial version 12/12/2013
 *  </pre>
**/
namespace Ritc\LibraryTester\Controllers;

use Ritc\Library\Abstracts\Base;
use Ritc\Library\Interfaces\ControllerInterface;
use Ritc\LibraryTester\Views\MainView;
use Zend\ServiceManager\ServiceManager;

class MainController extends Base implements ControllerInterface
{
    protected $o_di;

    public function __construct(ServiceManager $o_di)
    {
        $this->setPrivateProperties();
        $this->o_di = $o_di;
    }

    /**
     *  Main Router and Puker outer (more descriptive method name).
     *  Turns over the hard work to the specific controllers.
     *  @param none
     *  @return string $html
     **/
    public function render()
    {
        $o_router = $this->o_di->get('router');
        $this->logIt("Does this work?", LOG_OFF, __METHOD__ . '.' . __LINE__);
        $a_route_parts = $o_router->getRouteParts();
        $this->logIt(var_export($a_route_parts, true), LOG_OFF, __METHOD__ . '.' . __LINE__);
        if ($a_route_parts['route_class'] == 'MainController') {
            $o_view = new MainView($this->o_di);
            switch ($a_route_parts['route_method']) {
                case 'renderMain':
                case '':
                default:
                    return $o_view->renderMain();
            }
        }
        else {
            $o_controller = new $a_route_parts['route_class']($this->o_di);
            return $o_controller->$a_route_parts['route_method']($a_route_parts);
        }
    }

    /* From Base Abstract
    public function getElog()
    public function setElog(Elog $o_elog)
    public function logIt($message = '', $log_type = LOG_OFF, $location = '')
    */
}
