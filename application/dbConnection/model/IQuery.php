<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 30/10/16
 * Time: 19:51
 */

/**
 * Interface IQuery
 */
interface IQuery {

    /**
     * Get all entries from a table in database that matches all parameters specified, this method has to be used
     * to execute custom requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getCustomEntries($fields, $filterFields, $filterArguments);

    /**
     * Get all fields from all entries of a table
     *
     * @return array
     */
    public function getAllEntries();


    /**
     * Builds an array with all required data for parsing an item at any client
     *
     * @return array
     */
    public function getParseEntries();

    /**
     * Get parse entry by id
     *
     * @param $itemId
     * @return mixed|void
     */
    public function getParseEntry($itemId);

    /**
     * Get the id of the tournament that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue($filterFields, $filterArguments);

    /**
     * Insert al specified fields of an item with the specified values into the model table
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
    public function insertItem($fields, $values);

    /**
     * Modify al specified fields of an item with the specified values into the model table
     *
     * @param $itemNId
     * @param array $fields must contain all fields to be modified in the new entry
     * @param array $values must contain the values of the fields to be modified, the value position must match the position
     * of the corresponding field at $fields array
     * @return mixed
     */
    public function modifyItem($itemNId, $fields, $values);

    /**
     * Deletes the id given item from the table, returns number of rows deleted from the table
     *
     * @param $itemId id from the item to be deleted
     * @return mixed number of rows deleted
     */
    public function deleteItem($itemId);
}