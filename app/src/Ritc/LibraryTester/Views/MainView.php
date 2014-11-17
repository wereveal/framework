<?php
/**
 *  @brief View for the Main page.
 *  @file MainView.php
 *  @ingroup LibraryTester views
 *  @namespace Ritc/LibraryTester/Views
 *  @class MainView
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 1.0.1ß
 *  @date 2014-11-17 14:05:11
 *  @note A file in Ritc LibraryTester
 *  @note <pre><b>Change Log</b>
 *      v1.0.1ß - changed to use the new Di class - 11/17/2014 wer
 *      v1.0.0ß - Initial version                 - 11/08/2014 wer
 *  </pre>
 **/
namespace Ritc\LibraryTester\Views;

use Ritc\Library\Abstracts\Base;
use Ritc\Library\Services\Di;

class MainView extends Base
{
    private $o_route;
    private $o_tpl;

    public function __construct(Di $o_di)
    {
        $this->o_tpl   = $o_di->get('tpl');
        $this->o_route = $o_di->get('route');
    }
    public function renderMain()
    {
        $a_route_parts = $this->o_route->getRouteParts();
        $body_text  = "<pre>Hello: <br>";
        $body_text .= var_export($this->a_route_parts, true);
        $body_text .= "</pre>";
        $a_values = [
            'description'   => 'This is the Tester Page',
            'public_dir'    => '',
            'title'         => 'This is the Main Tester Page',
            'body_text'     => $body_text,
            'site_url'      => SITE_URL,
            'rights_holder' => RIGHTS_HOLDER
        ];
        return $this->o_tpl->render('@main/index.twig', $a_values);
    }
}
