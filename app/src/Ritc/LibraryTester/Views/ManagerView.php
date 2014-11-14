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
use Ritc\Library\Core\DbModel;
use Ritc\Library\Core\Tpl;
use Ritc\Library\Helper\ViewHelper;

class ManagerView extends Base
{
    private $o_db;
    private $o_twig;

    public function __construct(DbModel $o_db)
    {
        $this->setPrivateProperties();
        $o_tpl        = new Tpl('twig_config.php');
        $this->o_twig = $o_tpl->getTwig();
        $this->o_db   = $o_db;
    }
    public function renderLandingPage()
    {
        $message = ViewHelper::messageProperties(array());
        $a_values = [
            'description'   => 'This is the Tester Manager Page',
            'public_dir'    => '',
            'title'         => 'This is the Main Manager Test Page',
            'message'       => $message,
            'body_text'     => 'Hello',
            'site_url'      => SITE_URL,
            'rights_holder' => RIGHTS_HOLDER
        ];
        return $this->o_twig->render('@main/index.twig', $a_values);
    }
    /**
     * Temp method to test stuff
     * @param array $a_args
     * @return mixed
     */
    public function renderTempPage(array $a_args)
    {
        $message = '';
        if (is_array($a_args)) {
            $body_text = "it of course was an array";
        }
        else {
            $body_text =  "something seriously is wrong.
                The array in in the definition should have prevented this from happening.";
        }
        $a_values = [
            'description'   => 'This is the Tester Page',
            'public_dir'    => '',
            'title'         => 'This is the Main Tester Page',
            'message'       => $message,
            'body_text'     => $body_text,
            'site_url'      => SITE_URL,
            'rights_holder' => RIGHTS_HOLDER
        ];
        return $this->o_twig->render('@main/index.twig', $a_values);
    }
}
