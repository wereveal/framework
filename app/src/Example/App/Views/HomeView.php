<?php
namespace Example\App\Views;

use Ritc\Library\Abstracts\Base;
use Ritc\Library\Services\Di;

class HomeView extends Base
{
    private $o_db;
    private $o_tpl;

    public function __construct(Di $o_di)
    {
        $this->o_tpl = $o_di->get('tpl');
        $this->o_db  = $o_di->get('db');
    }
}
