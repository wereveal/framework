<?php
/**
 *  @brief View for the Main page.
 *  @file MainView.php
 *  @ingroup LibraryTester views
 *  @namespace Ritc/LibraryTester/Views
 *  @class MainView
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 1.0.0ß
 *  @date 2014-11-08 12:32:46
 *  @note A file in Ritc LibraryTester
 *  @note <pre><b>Change Log</b>
 *      v1.0.0ß - Initial version 11/08/2014 wer
 *  </pre>
 **/
namespace Ritc\LibraryTester\Views;

use Ritc\Library\Abstracts\Base;
use Zend\ServiceManager\ServiceManager;

class MainView extends Base
{
    private $a_route_parts = array();
    private $o_tpl;

    public function __construct(ServiceManager $o_di)
    {
        $this->o_tpl = $o_di->get('tpl');
        $o_route     = $o_di->get('route');
        $this->a_route_parts = $o_route->getRouteParts();
    }
    public function renderMain()
    {
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
