<?php
/**
 *  @brief The CRUD for users.
 *  @file Users.php
 *  @ingroup ftpadmin models
 *  @namespace Ritc/LibraryTester/Models
 *  @class Users
 *  @author William Reveal  <bill@revealitconsulting.com>
 *  @version 0.2.0
 *  @date 2013-12-30 20:21:16
 *  @note A file in Ritc LibraryTester version 1.0
 *  @note <pre><b>Change Log</b>
 *      v0.2.0 - After first round of testing
 *      v0.1.0 - Initial version 12/12/2013
 *  </pre>
**/
namespace Ritc\LibraryTester\Models;

use Ritc\Library\Core\Arrays;
use Ritc\Library\Core\DbFactory;
use Ritc\Library\Core\DbModel;
use Ritc\Library\Core\Elog;

class Users
{
    private $o_arrays;
    private $o_db;
    private $o_elog;

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
        if (!defined('ENCRYPT_TYPE')) {
            define('ENCRYPT_TYPE', 'sha1'); // see encryptPassword() for why sha1 is used
        }
    }

    ### CREATEs ####
    /**
     *  Creates a new user record.
     *  @param array $a_user
     *  @return bool
    **/
    public function createUser(array $a_user = array()) {
        $needed_keys = array('id', 'password', 'uid', 'gid', 'dir');
        if ($this->o_arrays->hasRequiredKeys($needed_keys, $a_user) === false) {
            $this->o_elog->write('Raw User has missing stuff: ' . var_export($a_user, true), LOG_OFF, __METHOD__ . '.' . __LINE__);
            return false;
        }
        $encrypted_password = $this->encryptPassword($a_user, ENCRYPT_TYPE);
        $a_user['password'] = $encrypted_password;
        $sql = "
            INSERT INTO users (id, password, uid, gid, dir)
            VALUES (:id, :password, :uid, :gid, :dir)
        ";
        $this->o_elog->write('User: ' . var_export($a_user, true) . '  SQL: ' . $sql, LOG_OFF, __METHOD__ . '.' . __LINE__);
        return $this->o_db->insert($sql, $a_user, 'users');
    }

    ### READs ####
    /**
     *  Returns all users in an array.
     *  @param none
     *  @return array $a_users
    **/
    public function findAll()
    {
        $sql = "
            SELECT id, password, uid, gid, dir
            FROM users
            ORDER BY id
        ";
        return $this->o_db->search($sql);
    }
    /**
     *  Returns a user in an array.
     *  @param int $user_id required
     *  @return array or false
    **/
    public function findUserById($user_id = 0)
    {
        if ($user_id == '') { return false; }
        $sql = "
            SELECT id, password, uid, gid, dir
            FROM users
            WHERE id = :id
        ";
        $a_results = $this->o_db->search($sql, array(':id' => $user_id));
        $this->o_elog->write('user results: ' . var_export($a_results, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
        if ($a_results !== false && count($a_results) > 0) {
            return $a_results[0];
        }
        return false;
    }

    ### UPDATEs ###
    /**
     *  Updates the specified user.
     *  @param array $a_user values of the user
     *  @return bool success and failure
    **/
    public function updateUser(array $a_user = array()) {
        if ($this->o_arrays->hasRequiredKeys(array('id'), $a_user) === false) {
            $this->o_elog->write('User without required key: ' . var_export($a_user, TRUE), LOG_OFF, __METHOD__ . '.' . __LINE__);
            return false;
        }
        if (isset($a_user['password']) && $a_user['password'] != '') {
            $encrypted_password = $this->encryptPassword($a_user, ENCRYPT_TYPE);
            if ($encrypted_password === false) {
                $this->o_elog->write('Could not encrypt password.', LOG_OFF, __METHOD__ . '.' . __LINE__);
                return false;
            }
            else {
                $a_user['password'] = $encrypted_password;
                $this->o_elog->write("password: {$encrypted_password}", LOG_OFF, __METHOD__ . '.' . __LINE__);
            }
        }
        elseif (isset($a_user['password']) && $a_user['password'] == '') {
            unset($a_user['password']);
        }
        $a_one_of_these = array('password', 'uid', 'gid', 'dir');
        $continue = false;
        foreach ($a_one_of_these as $key) {
            if(array_key_exists($key, $a_user)) {
                $continue = true;
                break;
            }
        }
        if ($continue === false) {
            $this->o_elog->write('Array did not have needed values: ' . var_export($a_user, true), LOG_OFF, __METHOD__ . '.' . __LINE__);
            return false;
        }
        $sql_set = $this->o_db->buildSqlSet($a_user, array('id'));
        $sql = "
            UPDATE users
            {$sql_set}
            WHERE id = :id
        ";
        $this->o_elog->write("sql: {$sql}", LOG_OFF, __METHOD__ . '.' . __LINE__);
        return $this->o_db->update($sql, $a_user);
    }
    /**
     *  Updates a user directory.
     *  @param array $a_user values of the user, required array('id', 'dir')
     *  @return bool
    **/
    public function updateUserDir(array $a_user = array())
    {
        $needed_keys = array('id', 'dir');
        if (!$this->o_arrays->hasRequiredKeys($needed_keys, $a_user)) {
            return false;
        }
        $a_user = $this->o_arrays->stripUnspecifiedValues($a_user, $needed_keys);
        $sql = "UPDATE users SET dir = :dir WHERE id = :id";
        return $this->o_db->update($sql, $a_user);
    }
    /**
     *  Updates a user with a new password
     *  @param array $a_user required, must have user id and password
     *  @return bool
    **/
    public function updateUserPassword(array $a_user = array())
    {
        $needed_keys = array('id', 'password');
        if (!$this->o_arrays->hasRequiredKeys($needed_keys, $a_user)) {
            return false;
        }
        $encrypted_password = $this->encryptPassword($a_user, ENCRYPT_TYPE);
        if ($encrypted_password !== false) {
            $a_user['password'] = $encrypted_password;
        }
        else {
            return false;
        }
        $a_user = $this->o_arrays->stripUnspecifiedValues($a_user, $needed_keys);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        return $this->o_db->update($sql, $a_user);
    }

    ### DELETEs ###
    /**
     *  Deletes Specified User.
     *  @param string $user_id
     *  @return bool success or failure
    **/
    public function deleteUser($user_id = '') {
        if ($user_id == '') {
            return false;
        }
        if ($this->findUserById($user_id) === false) {
            return false; // user doesn't exist
        }
        $sql = "
            DELETE FROM users
            WHERE id = :id
        ";
        return $this->o_db->delete($sql, array('id' => $user_id), true);
    }

    ### Utility ###
    /**
     *  Hashes a password using variables in a user's record.
     *  This turns out to be less secure as desired since pure-ftpd only allows md5 and sha1 encryption.
     *  I left code in to allow sha512 and salted sha512 passwords but pure-ftpd can't use it.
     *  @param array $a_user required array('id', 'password', 'uid', 'gid', 'dir')
     *  @param string $hash_type optional default to sha1
     *      Can be salted_sha512, sha512, md5, sha1, and cleartext
     *  @return string the hashed password
     **/
    private function encryptPassword(array $a_user = array(), $hash_type ='sha1')
    {
        if ($a_user == array()) { return false; }
        $this->o_elog->write("a_user: " . var_export($a_user, true), LOG_OFF, __METHOD__ . '.' . __LINE__);
        switch ($hash_type) {
            case 'salted_sha512':
                $salt = substr(hash('sha512', $a_user['id'], false), 16, 32);
                $encrypted_password = hash('sha512', $salt . $a_user['password'], false);
                break;
            case 'sha512':
                $encrypted_password = hash('sha512', $a_user['password'], false);
                break;
            case 'md5':
                $encrypted_password = hash('md5', $a_user['password'], false);
                break;
            case 'cleartext':
                // lol, doesn't encrypt the password
                $encrypted_password = $a_user['password'];
                break;
            case 'sha1':
            default:
                $encrypted_password = hash('sha1', $a_user['password'], false);
        }
        $this->o_elog->write("hash: {$encrypted_password}", LOG_OFF, __METHOD__ . '.' . __LINE__);
        return $encrypted_password;
    }
}
