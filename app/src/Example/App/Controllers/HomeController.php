<?php
/**
 *  @brief Controller for the Home page.
 *  @file HomeController.php
 *  @ingroup main_app_name controllers
 *  @namespace Example/App/Controllers
 *  @class HomeController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 1.0.0
 *  @date 2015-12-01 18:24:10
 *  @note <pre><b>Change Log</b>
 *      v1.0.0 - Initial Version                        - 12/01/2015 wer
 *  </pre>
 *  @todo Get the advanced users update working.
 *  @TODO refactor to FtpLogin instead of FtpUser to reflect changes in Model
**/
namespace Ritc\FtpAdmin\Controllers;

use Ritc\Library\Helper\AuthHelper;
use Ritc\Library\Helper\Strings;
use Ritc\Library\Helper\ViewHelper;
use Ritc\Library\Interfaces\ControllerInterface;
use Ritc\Library\Services\Di;
use Ritc\Library\Traits\LogitTraits;
use Example\App\Models\AppModel;
use Example\App\Views\HomeView;

class HomeController implements ControllerInterface
{
    use LogitTraits;
    private $o_auth;
    private $o_di;
    private $o_model;
    private $o_router;
    private $o_session;

    public function __construct(Di $o_di)
    {
        $this->o_di      = $o_di;
        $this->o_session = $o_di->get('session');
        $this->o_router  = $o_di->get('router');
        $this->o_model   = new FtpLoginModel($o_di);
        $this->o_auth    = new AuthHelper($o_di);
        if (DEVELOPER_MODE) {
            $this->o_elog = $o_di->get('elog');
            $this->o_model->setElog($o_elog);
        }
    }

    /**
     * Renders the Home page.
     * @return string html to be precise.
     */
    public function render()
    {
        $o_home_view     = new HomeView($this->o_di);
        $a_route_parts   = $this->o_router->getRouterParts();
        $a_posted_values = $a_route_parts['post'];
        $main_action     = $a_route_parts['route_action'];

        switch ($main_action) {
            case 'save':
                $results = $this->o_model->create();
                if ($results) {
                    $message = ViewHelper::successMessage();
                }
                else {
                    $message = ViewHelper::failureMessage();
                }
            case 'home':
            default:
                return $o_home_view->renderList($message);
        }
    }
}
