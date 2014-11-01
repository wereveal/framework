<?php
namespace Example\App\Views;

use Ritc\Library\Abstracts\Base;
use Ritc\Library\Core\Tpl;

class HomeView extends Base
{
    private $o_twig;
    protected $o_elog;

    public function __construct(DbModel $o_db)
    {
        $o_tpl        = new Tpl('twig_config.php');
        $this->o_twig = $o_tpl->getTwig();
    }
}
