<?php
/**
 * @brief     Main Controller for Main.
 * @ingroup   main_controllers
 * @file      Ritc/Main/Controllers/MainController.php
 * @namespace Ritc\Main\Controllers
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @version   1.0.0-alpha.0
 * @date      2017-01-26 17:19:18
 * @note Change Log
 * - v1.0.0-alpha.0 - Initial version        - 2017-01-26 wer
 * @todo Ritc/Main/Controllers/MainController.php - Everything
 */
namespace Ritc\Main\Controllers;

use Ritc\FtpAdmin\Controllers\FtpController;
use Ritc\Library\Controllers\LibraryController;
use Ritc\Library\Interfaces\ControllerInterface;
use Ritc\Library\Services\Di;
use Ritc\Library\Traits\ControllerTraits;
use Ritc\Library\Traits\LogitTraits;

/**
 * Class MainController.
 * @class   MainController
 * @package Ritc\Main
 */
class MainController implements ControllerInterface
{
    use ControllerTraits, LogitTraits;

    /**
     * MainController constructor.
     * @param \Ritc\Library\Services\Di $o_di
     */
    public function __construct(Di $o_di)
    {
        $this->setupController($o_di);
        $this->setupElog($o_di);
    }

    /**
     * Main Router and Puker outer (more descriptive method name).
     * Turns over the hard work to the specific controllers specified by the router.
     * @return string
     */
    public function route()
    {
        $meth = __METHOD__ . '.';
        if (!isset($_SESSION['token'])) {
            $this->o_session->setSessionVars();
        }
        $log_message = 'Session: ' . var_export($_SESSION, TRUE);
        $this->logIt($log_message, LOG_OFF, $meth . __LINE__);

        $a_router_parts = $this->o_router->getRouteParts();
        $log_message = 'Router stuff ' . var_export($this->a_router_parts, TRUE);
        $this->logIt($log_message, LOG_OFF, $meth . __LINE__);

        $route_class = $a_router_parts['route_class'] != ''
            ? $a_router_parts['route_class']
            : 'HomeController';
        $route_method = $a_router_parts['route_method'] != ''
            ? $a_router_parts['route_method']
            : 'route';

        switch ($route_class) {
            case 'FtpController':
                $o_controller = new FtpController($this->o_di);
                break;
            case 'LibraryController':
                $o_controller = new LibraryController($this->o_di);
                break;
            case 'ManagerController':
            case 'HomeController':
            default:
                $o_controller = new HomeController($this->o_di);
        }
        return $o_controller->$route_method();
    }
}