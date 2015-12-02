<?php
/**
 *  @brief The main Controller for the whole site.
 *  @file MainController.php
 *  @ingroup main_app_name controllers
 *  @namespace Example/App/Controllers
 *  @class MainController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 1.0.0
 *  @date 2015-12-01 18:14:54
 *  @note <pre><b>Change Log</b>
 *      v1.0.0 - Initial version                    - 12/01/2015 wer
 *  </pre>
**/
namespace Example\App\Controllers;

use Ritc\Library\Controllers\ManagerController;
use Ritc\Library\Interfaces\PageControllerInterface;
use Ritc\Library\Services\Di;
use Ritc\Library\Traits\LogitTraits;

class MainController implements PageControllerInterface
{
    use LogitTraits;

    private $o_di;

    public function __construct(Di $o_di)
    {
        $this->o_di = $o_di;
        if (defined('DEVELOPER_MODE') && DEVELOPER_MODE) {
            $this->o_elog = $o_di->get('elog');
        }
    }
    /**
     *  Main Router and Puker outer (more descriptive method name).
     *  Turns over the hard work to the specific controllers specified by the router.
     *  @return string
    **/
    public function renderPage()
    {
        $o_router = $this->o_di->get('router');
        $a_route_parts = $o_router->getRouterParts();
        $this->logIt("Route Parts: " . var_export($a_route_parts, true), LOG_OFF, __METHOD__);
        $route_class  = $a_route_parts['route_class'];
        $route_method = $a_route_parts['route_method'];

        switch ($route_class) {
            case 'ThatController':
                $o_that = new ThatController($this->o_di);
                return $o_that->$route_method();
            case 'HomeController':
            default:
                $o_controller = new HomeController($this->o_di);
                return $o_controller->render();
        }
        /* Optional method instead of switch
            $o_that = new $new_class($this->o_di);
            return $o_that->$route_method();
        */
    }
}
