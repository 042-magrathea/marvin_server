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

<<<<<<< HEAD
    /**
     * Get all entries from a table in database that matches all parameters specified, this method has to be used
     * to execute custom requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments);

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
    public function getIdValue(array $filterFields, array $filterArguments);

    /**
     * Insert al specified fields of an item with the specified values into the table TOURNAMENT
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
=======
    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments);
    public function getAllEntries();
    public function getParseEntries();
    public function getParseEntry($itemId);
    public function getIdValue(array $filterFields, array $filterArguments);
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
    public function insertItem(array $fields, array $values);
}