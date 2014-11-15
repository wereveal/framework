<?php
namespace Example\App\Views;

use Ritc\Library\Abstracts\Base;
use Ritc\Library\Core\Tpl;

class HomeView extends Base
{
    private $o_tpl;

    public function __construct(DbModel $o_db)
    {
        $o_tpl        = new Tpl('twig_config.php');
        $this->o_tpl = $o_tpl->getTwig();
    }
}
