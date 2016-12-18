<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:01
 */

<<<<<<< HEAD
include_once "persistence/DB_adapter.php";
=======
include_once "DB_adapter.php";
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
include_once "Query.php";


/**
 * Class Host_Model
 */
class Host_Model extends Query {

    private $adapter;


    /**
     * Host_Model constructor.
     */
    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();
<<<<<<< HEAD
        $this->connection->query("SET NAMES 'utf8'");
=======

>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
    }

    /**
     * Get all entries from the 'HOST' table in database that matches all parameters specified, this method has to be used
     * to do execute requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
<<<<<<< HEAD
    public function getCustomEntries($fields, $filterFields, $filterArguments) {

        $sql = $this->buildQuerySql('TOURNAMENT_HOST', $fields, $filterFields, $filterArguments);

        $result = $this->getResultArray($sql);
=======
    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments) {

        $sql = $this->buildQuery('TOURNAMENT_HOST', $fields, $filterFields, $filterArguments);

        $result = $this->getArraySQL($sql);
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Builds an array with all required data for parsing a host at any client
     *
     * @return array
     */
    public function getParseEntries() {
        $sql = "SELECT idTournamentHost, name FROM TOURNAMENT_HOST;";

<<<<<<< HEAD
        $result = $this->getResultArray($sql);
=======
        $result = $this->getArraySQL($sql);
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get all fields from all entries of the table TOURNAMENT_HOST from the database
     *
     * @return array
     */
    public function getAllEntries() {
        $sql = "SELECT * FROM TOURNAMENT_HOST;";

<<<<<<< HEAD
        $result = $this->getResultArray($sql);
=======
        $result = $this->getArraySQL($sql);
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Insert al specified fields of an item with the specified values into the table TOURNAMENT
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
     * Get the id of the tournament that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue(array $filterFields, array $filterArguments)
    {
        // TODO: Implement getIdValue() method.
    }
<<<<<<< HEAD

    public function modifyItem($itemNId, $fields, $values)
    {
        // TODO: Implement modifyItem() method.
    }

    public function deleteItem($itemId)
    {
        // TODO: Implement deleteItem() method.
    }
=======
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
}