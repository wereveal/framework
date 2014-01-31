<?php
/**
 *  @brief Controller for the Configuration page.
 *  @file ConfigAdminController.php
 *  @ingroup ftpadmin controllers
 *  @namespace Ritc/LibraryTester/Controllers
 *  @class ConfigAdminController
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1
 *  @date 2013-12-12 13:04:04
 *  @note A file in LibraryTester v1 app
 *  @note <pre><b>Change Log</b>
 *      v0.1 - Initial version 2013-12-12
 *  </pre>
 *  @todo Add the session validation setup
 *  @todo add to ritc framework example app or maybe even to the Library as an abstract or interface.
**/
namespace Ritc\LibraryTester\Controllers;

use Ritc\Library\Core\Elog;
use Ritc\Library\Core\TwigFactory as Twig;
use Ritc\Library\Helper\ViewHelper;
use Ritc\LibraryTester\Models\AppConfig;

class ConfigAdminController implements ControllerInterface
{
    protected $a_actions;
    protected $a_values;
    private $o_elog;
    private $o_config;
    private $o_twig;
    private $o_vhelp;

    public function __construct(array $a_actions = array(), array $a_values = array())
    {
        $this->o_elog    = Elog::start();
        $this->o_config  = new AppConfig;
        $this->o_twig    = Twig::start('twig_config.php');
        $this->o_vhelp   = new ViewHelper();
        $this->a_actions = $a_actions;
        $this->a_values  = $a_values;
    }
    /**
     *  Routes the code to the appropriate methods and returns a string.
     *  @param array $a_actions optional, the actions derived from the URL/Form
     *  @param array $a_values optional, the values from a form
     *  @return string html to be displayed.
    **/
    public function renderPage(array $a_actions = array(), array $a_values = array())
    {
        $main_action = isset($a_actions['action2']) ? $a_actions['action2'] : '';
        $form_action = isset($a_values['form_action']) ? $a_values['form_action'] : '';
        // Make sure only valid routes are followed
            switch ($main_action) {
                case 'modify':
                    switch ($form_action) {
                        case 'verify':
                            $main_action = 'verify';
                            break;
                        case 'update':
                            $main_action = 'update';
                            break;
                        default:
                            $main_action = '';
                    }
                    break;
                case 'save':
                    if ($form_action == 'save_new') {
                        $main_action = 'save_new';
                    }
                    else {
                        $main_action = '';
                    }
                    break;
                case 'delete':
                    if ($form_action == 'delete') {
                        $main_action = 'delete';
                    }
                    else {
                        $main_action = '';
                    }
                    break;
                default:
                    $main_action = '';
            }

        switch ($main_action) {
            case 'save_new':
                // save the record
                $a_config = $a_values['config'];
                $this->o_elog->write('config values before create config' . var_export($a_config, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
                $results = $this->o_config->createConfig($a_config);
                if ($results === false) {
                    $a_message = array('type' => 'failure', 'message' => 'Could not save the configuration values.');
                }
                else {
                    $a_message = array('type' => 'success', 'message' => 'Success!');
                }
                return $this->renderConfigs($a_message);
            case 'update':
                // save the record
                $a_config = $a_values['config'];
                $results = $this->o_config->updateConfig($a_config);
                if ($results === false) {
                    $a_message = array('type' => 'failure', 'message' => 'Could not update the configuration.');
                }
                else {
                    $a_message = array('type' => 'success', 'message' => 'Success!');
                }
                return $this->renderConfigs($a_message);
            case 'verify':
                return $this->o_twig->render('@pages/verify_delete_config.twig', $a_values);
            case 'delete':
                // delete the record
                $results = $this->o_config->deleteConfig($a_values['config_id']);
                // $results = false;
                if ($results === false) {
                    $a_message = array('type' => 'failure', 'message' => 'Could not delete the configuration.');
                }
                else {
                    $a_message = array('type' => 'success', 'message' => 'Success!');
                }
                return $this->renderConfigs($a_message);
            default:
                return $this->renderConfigs();
        }
    }

    /**
     *  Returns the list of configs in html.
     *  @param array $a_message
     *  @return string
    **/
    protected function renderConfigs(array $a_message = array()){
        $a_values = array(
            'a_message' => array(),
            'a_configs' => array(
                array(
                    'config_id'    => '',
                    'config_name'  => '',
                    'config_value' => ''
                )
            ),
            'tolken'  => $_SESSION['token'],
            'form_ts' => $_SESSION['idle_timestamp'],
            'hobbit'  => ''
        );
        if ($a_message != array()) {
            $a_values['a_message'] = $this->o_vhelp->messageProperties($a_message);
        }
        else {
            $a_values['a_message'] = $this->o_vhelp->messageProperties(
                array(
                    'message'       => 'Changing configuration values can result in unexpected results. If you are not sure, do not do it.',
                    'type'          => 'warning'
                )
            );
        }
        $a_configs = $this->o_config->readConfig();
        $this->o_elog->write('a_configs: ' . var_export($a_configs, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
        if ($a_configs !== false && count($a_configs) > 0) {
            $a_values['a_configs'] = $a_configs;
        }
        return $this->o_twig->render('@pages/app_config.twig', $a_values);
    }
}
