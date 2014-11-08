<?php
/**
 * Created by PhpStorm.
 * User: wer
 * Date: 11/8/14
 * Time: 12:36 PM
 */

namespace Ritc\LibraryTester\Models;

use Ritc\Library\Interfaces\ModelInterface;

class TestModel implements ModelInterface
{
    /**
     * Generic create a record using the values provided.
     * Assumes a single table
     * @param array $a_values
     * @return bool
     */
    public function create(array $a_values)
    {
        return false;
    }

    /**
     * Returns an array of records based on the search params provided.
     * @param array $a_search_values
     * @return array
     */
    public function read(array $a_search_values)
    {
        return array();
    }

    /**
     * Generic update for a record using the values provided.
     * @param array $a_values
     * @return bool
     */
    public function update(array $a_values)
    {
        return false;
    }

    /**
     * Generic deletes a record based on the id provided.
     * @param string $id
     * @return bool
     */
    public function delete($id = '')
    {
        return false;
    }
} 