<?php
/**
 *  @brief Creates a entity object.
 *  @file AppConfigEntity.php
 *  @ingroup ftpadmin models
 *  @namespace Ritc/LibraryTester/Models
 *  @class AppConfigEntity
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.1.0
 *  @date 2013-12-12 13:21:16
 *  @note A file in the Ritc LibraryTester version 1.0
 *  @note <b>SQL for table<b>
 *  <pre>
 *  CREATE TABLE `app_config` (
 *    `config_id` int(11) NOT NULL AUTO_INCREMENT,
 *    `config_name` varchar(64) NOT NULL,
 *    `config_value` varchar(64) NOT NULL,
 *    PRIMARY KEY (`config_id`),
 *    UNIQUE KEY `config_name` (`config_name`)
 *  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4
 *  </pre>
 *  <pre>
 *  CREATE TABLE app_config (
 *      config_id integer DEFAULT nextval('app_config_id_seq'::regclass) NOT NULL,
 *      config_name character varying(64) NOT NULL,
 *      config_value character varying(64) NOT NULL
 *  )
 *  </pre>
 *  <pre>
 *  ALTER TABLE ONLY app_config
 *      ADD CONSTRAINT app_config_pkey PRIMARY KEY (config_id);
 *  </pre>
 *  @note <pre><b>Change Log</b>
 *      v0.1.0 - Initial version 12/12/2013</pre>
 *  @todo add this to the ritc framework example app or maybe to the Library
**/
namespace Ritc\LibraryTester\Models;

class AppConfigEntity implements namespace\EntityInterface
{
    private $config_id;
    private $config_name;
    private $config_value;

    public function setId($value = '')
    {
        $this->config_id = $value;
    }
    public function setKey($value = '')
    {
        $this->config_name = $value;
    }
    public function setValue($value = '')
    {
        $this->config_value = $value;
    }
    public function getId()
    {
        return $this->config_id;
    }
    public function getKey()
    {
        return $this->config_name;
    }
    public function getValue()
    {
        return $this->config_value;
    }
    /**
     *  returns an array of the properties
     *  @return array
    **/
    public function getAllProperties()
    {
        return array(
            'config_id'    => $this->config_id,
            'config_name'  => $this->config_name,
            'config_value' => $this->config_value
        );
    }
    /**
     *  Sets the values of all the entity properties.
     *  @param array $a_entity e.g., array('config_id'=>'', 'config_name'=>'', 'config_value'=>'')
     *  @return bool success or failure
    **/
    public function setAllProperties(array $a_entity = array())
    {
        $this->config_id    = isset($a_entity['config_id'])    ? $a_entity['config_id']    : '';
        $this->config_name  = isset($a_entity['config_name'])  ? $a_entity['config_name']  : '';
        $this->config_value = isset($a_entity['config_value']) ? $a_entity['config_value'] : '';
    }
}
