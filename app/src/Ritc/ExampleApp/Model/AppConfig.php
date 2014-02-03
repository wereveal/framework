<?php
/**
 *  @brief The CRUD for the application configuration.
 *  @file AppConfig.php
 *  @ingroup ftpadmin models
 *  @namespace Ritc/LibraryTester/Models
 *  @class AppConfig
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1.0
 *  @date 2013-12-12 13:21:16
 *  @note A file in Ritc LibraryTester version 1.0
 *  @note <pre><b>Change Log</b>
 *      v0.1.0 - Initial version 12/12/2013
 *  </pre>
 *  @todo add this to the ritc_framework example app... maybe even to the Library
**/
namespace Ritc\LibraryTester\Models;

use Ritc\Library\Core\Arrays;
use Ritc\Library\Core\DbFactory;
use Ritc\Library\Core\DbModel;
use Ritc\Library\Core\Elog;
use Ritc\Library\Core\Strings;

class AppConfig
{
    protected $o_arrays;
    protected $o_db;
    protected $o_elog;
    protected $o_strings;

    public function __construct()
    {
        $this->o_elog = Elog::start();
        $o_dbf = DbFactory::start('db_config.php', 'rw');
        $o_pdo = $o_dbf->connect();
        if ($o_pdo !== false) {
            $this->o_db = new DbModel($o_pdo);
        }
        else {
            $this->o_elog->write('Could not connect to the database', LOG_ALWAYS, __METHOD__ . '.' . __LINE__);
        }
        $this->o_arrays = new Arrays;
        $this->o_strings = new Strings;
    }

    ### CRUD ###
    /**
     *  Creates a new config record.
     *  @param array $a_config values to save
     *  @return bool
    **/
    public function createConfig(array $a_config = array()) {
        $required_keys = array('config_name', 'config_value');
        $this->o_elog->write('a config' . var_export($a_config, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
        if ($this->o_arrays->hasRequiredKeys($required_keys, $a_config) === false) {
            return false;
        }
        $a_config['config_name'] = $this->makeValidName($a_config);
        $sql = "
            INSERT INTO app_config (config_name, config_value)
            VALUES (:config_name, :config_value)
        ";
        if ($this->o_db->insert($sql, $a_config, 'app_config')) {
            $a_ids = $this->o_db->getNewIds();
            return $a_ids[0];
        }
        else {
            return false;
        }
    }
    /**
     *  Returns array of config key=>value pairs or value of a key
     *  @param string $config_identifier optional, if not specified, returns all configs
     *  @return array $a_config = array('config_id'=>'', 'config_key'=>'', 'config_value'=>'')
    **/
    public function readConfig($config_identifier = '')
    {
        if ($config_identifier != '') {
            $first_char = substr($config_identifier, 0, 1);
            if (preg_match('/[0-9]/', $first_char) === 1) {
                $sql = "
                    SELECT config_id, config_name, config_value
                    FROM app_config
                    WHERE config_id = :config_id
                ";
                $search_array = array(':config_id' => $config_identifier);
            }
            else {
                $sql = "
                    SELECT config_id, config_name, config_value
                    FROM app_config
                    WHERE config_name = :config_name
                ";
                $search_array = array(':config_name' => $config_identifier);
            }
            $a_results = $this->o_db->search($sql, $search_array);
            if ($a_results !== false && count($a_results) > 0) {
                return $a_results[0];
            }
            else {
                return false;
            }
        }
        else {
            $sql = "
                SELECT config_id, config_name, config_value
                FROM app_config
                ORDER BY config_id
            ";
            return $this->o_db->search($sql);
        }
    }
    /**
     *  Updates the specified config record.
     *  @param array $a_config values of the configuration
     *  @return bool success and failure
    **/
    public function updateConfig(array $a_config = array()) {
        if ($this->o_arrays->hasRequiredKeys(array('config_id', 'config_value'), $a_config) === false) {
            return false;
        }
        $sql_set = $this->o_db->buildSqlSet($a_config, array('config_id'));
        $sql = "
            UPDATE app_config
            {$sql_set}
            WHERE config_id  = :config_id
        ";
        return $this->o_db->update($sql, $a_config, true);
    }
    /**
     *  Deletes the configuration record specified.
     *  @param string $config_id
     *  @return bool success or failure
    **/
    public function deleteConfig($config_id = '') {
        if ($config_id == '') {
            return false;
        }
        if ($this->readConfig($config_id) === false) {
            return false; // config doesn't exist
        }
        $sql = "
            DELETE FROM app_config
            WHERE config_id = :config_id
        ";
        return $this->o_db->delete($sql, array('config_id' => $config_id), true);
    }
    public function makeValidName(array $a_config = array())
    {
        if (!isset($a_config['config_name'])) { return $a_config; }
        $config_name = $this->o_strings->removeTags($a_config['config_name']);
        $config_name = preg_replace("/[^a-zA-Z_ ]/", '', $config_name);
        $config_name = preg_replace('/(\s+)/i', '_', $config_name);
        return strtoupper($config_name);
    }
}
