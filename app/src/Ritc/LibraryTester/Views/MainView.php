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

class MainView extends Base
{
    private $o_twig;
    protected $o_elog;

    public function __construct(DbModel $o_db)
    {
        $o_tpl                = new Tpl('twig_config.php');
        $this->o_twig         = $o_tpl->getTwig();
    }
    public function renderMain()
    {
        $a_values = [
            'description'   => 'This is the Tester Page',
            'public_dir'    => '',
            'title'         => 'This is the Main Tester Page',
            'body_text'     => 'Hello',
            'site_url'      => SITE_URL,
            'rights_holder' => RIGHTS_HOLDER
        ];
        return $this->o_twig->render('@main/index.twig', $a_values);
    }
}
