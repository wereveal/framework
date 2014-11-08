<?php
/**
 *  @note <b>SQL for table<b>
 *  For MySQL
 *  <pre>
 *  CREATE TABLE IF NOT EXISTS `users` (
 *  `id` varchar(32) NOT NULL,
 *  `password` tinytext NOT NULL,
 *  `uid` int(11) NOT NULL,
 *  `gid` int(11) NOT NULL,
 *  `dir` varchar(255) NOT NULL,
 *  PRIMARY KEY (`id`)
 *  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *  </pre>
 *  For PostgreSQL
 *  <pre>
 *  CREATE TABLE users (
 *      id character varying(32) NOT NULL,
 *      password text NOT NULL,
 *      uid integer NOT NULL,
 *      gid integer NOT MULL,
 *      dir character varying(255) NOT NULL
 *  )
 *  </pre>
 */
namespace Example\App\Models;

use Ritc\Library\Interfaces\EntityInterface;

class UsersEntity implements EntityInterface
{
    /**
     *  @var string $id
    **/
    private $id;
    /**
     *  @var string $password
    **/
    private $password;
    /**
     *  @var integer $uid
    **/
    private $uid;
    /**
     *  @var integer $gid
    **/
    private $gid;
    /**
     *  @var string $dir
    **/
    private $dir;
    /**
     *  Set id
     *  @param string $id
    **/
    public function setId( $id = '' ) {
        if ( $id != '' ) {
            $this->id = $id;
        }
    }
    /**
     *  Get id
     *  @return string
    **/
    public function getId() {
        return $this->id;
    }
    /**
     *  Set password
     *  @param string $password
    **/
    public function setPassword( $password = '' ) {
        if ( $password != '' ) {
            $this->password = $password;
        }
    }
    /**
     *  Get password
     *  @return string
    **/
    public function getPassword() {
        return $this->password;
    }

    /**
     *  Set uid
     *  @param int|string $uid
     */
    public function setUid( $uid = '' ) {
        if ( $uid != '' ) {
            $this->uid = $uid;
        }
    }
    /**
     *  Get uid
     *  @return integer
    **/
    public function getUid() {
        return $this->uid;
    }

    /**
     *  Set gid
     *  @param int|string $gid
     */
    public function setGid($gid = '') {
        if ( $gid != '' ) {
            $this->gid = $gid;
        }
    }
    /**
     *  Get gid
     *  @return integer
    **/
    public function getGid() {
        return $this->gid;
    }
    /**
     *  Set dir
     *  @param string $dir
    **/
    public function setDir( $dir = '' ) {
        if ( $dir != '' ) {
            $this->dir = $dir;
        }
    }
    /**
     *  Get dir
     *  @return string
    **/
    public function getDir() {
        return $this->dir;
    }
    /**
     *  returns an array of the properties
     *  @return array
    **/
    public function getAllProperties() {
        return array(
            'id'       => $this->id,
            'password' => $this->password,
            'uid'      => $this->uid,
            'gid'      => $this->gid,
            'dir'      => $this->dir
        );
    }
    /**
     *  Sets the values of all the main user properties.
     *  @param array $a_entity e.g., array( 'id'=>'', 'password'=>'', 'uid'=>'', 'gid'=>'', 'dir'=>'' )
     *  @return bool success or failure
    **/
    public function setAllProperties(array $a_entity = array()) {
        if ($a_entity == array()) {
            $a_entity = array(
                'id'       => '',
                'password' => '',
                'uid'      => '',
                'gid'      => '',
                'dir'      => ''
            );
        }
        $this->setID($a_entity['id']);
        $this->setPassword($a_entity['password']);
        $this->setUid($a_entity['uid']);
        $this->setGid($a_entity['gid']);
        $this->setDir($a_entity['dir']);
        return true;
    }

}
