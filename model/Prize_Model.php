<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:23
 */
include_once "DB_adapter.php";
include_once "Query.php";

/**
 * Class Prize_Model
 */
class Prize_Model extends Query {

    private $adapter;


    /**
     * Prize_Model constructor.
     */
    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();

    }

    //---------------------------------------------------------------------------------------------------------------//

    //////////////////////////
    // USED BY THE PROTOTYPE//
    //////////////////////////

    /**
     * Builds an array with all required data for parsing a prize at any client
     *
     * @return array
     */
    public function getParseEntries() {
        $sql = "SELECT idPRIZE, name FROM PRIZE;";

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    //---------------------------------------------------------------------------------------------------------------//

    //////////////////////////////
    // NOT USED BY THE PROTOTYPE//
    //////////////////////////////

    /**
     * Get all entries from the 'PRIZE' table in database that matches all parameters specified, this method has to be used
     * to do execute requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments) {

        $sql = $this->buildQuery('PRIZE', $fields, $filterFields, $filterArguments);

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get all fields from all entries of the table PRIZE from the database
     *
     * @return array
     */
    public function getAllEntries() {
        $sql = "SELECT * FROM PRIZE;";

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Insert al specified fields of an item with the specified values into the table PRIZE
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
    public function insertItem(array $fields, array $values)
    {
        // TODO: Implement insertItem() method.
    }

    /**
     * Get parse entry by id
     *
     * @param $itemId
     * @return mixed|void
     */
    public function getParseEntry($itemId)
    {
        // TODO: Implement getParseEntry() method.
    }

    /**
     * Get the id of the prize that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue(array $filterFields, array $filterArguments)
    {
        // TODO: Implement getIdValue() method.
    }
}