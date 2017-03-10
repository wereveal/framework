<?php
/**
 * @brief     Main View for Main.
 * @ingroup   main_views
 * @file      Ritc/Main/Views/HomeView.php
 * @namespace Ritc\Main\Views
 * @author    William E Reveal <bill@revealitconsulting.com>
 * @version   1.0.0-alpha.0
 * @date      2017-01-26 17:19:18
 * @note Change Log
 * - v1.0.0-alpha.0 - Initial version        - 2017-01-26 wer
 * @todo Ritc/Main/Views/HomeView .php - Everything
 */
namespace Ritc\Main\Views;

use Ritc\Library\Interfaces\ViewInterface;
use Ritc\Library\Services\Di;
use Ritc\Library\Traits\LogitTraits;
use Ritc\Library\Traits\ManagerViewTraits;

/**
 * Class HomeView
 * @class   HomeView
 * @package Ritc\Main\Views
 */
class HomeView implements ViewInterface
{
    use ManagerViewTraits, LogitTraits;

    public function __construct(Di $o_di)
    {
        $this->setupView($o_di);
        $this->setupElog($o_di);
    }

    public function render(array $a_message = [])
    {
        $meth = __METHOD__ . '.';
        $a_twig_values = $this->createDefaultTwigValues($a_message);
        $log_message = 'Twig Values:  ' . var_export($a_twig_values, TRUE);
        $this->logIt($log_message, LOG_OFF, $meth . __LINE__);

        $a_twig_values['debug_text'] = var_export($a_twig_values, true);
        $tpl = '@' . $a_twig_values['twig_prefix'] . 'pages/index.twig';
        return $this->o_twig->render($tpl, $a_twig_values);
    }
}
