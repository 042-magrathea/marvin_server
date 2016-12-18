<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 11/12/16
 * Time: 19:40
 */

include_once "persistence/DB_adapter.php";
include_once "Query.php";

class Game_Model extends Query {

    private $adapter;

    /**
     * User_Model constructor.
     */
    public function __construct() {
        $this->adapter = new DB_adapter();
        $this->connection = $this->adapter->getConnection();
        $this->connection->query("SET NAMES 'utf8'");
    }

    /**
     * Get all entries from a table in database that matches all parameters specified, this method has to be used
     * to execute custom requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getCustomEntries($fields, $filterFields, $filterArguments) {

        $sql = $this->buildQuerySql('GAME', $fields, $filterFields, $filterArguments);

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get all fields from all entries of a table
     *
     * @return array
     */
    public function getAllEntries() {

        //build the query statement
        $sql = "SELECT * FROM GAME;";

        //excute query
        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Builds an array with all required data for parsing an item at any client
     *
     * @return array
     */
    public function getParseEntries() {
        //build the query statement
        $sql = "SELECT idGame, name, description, image FROM GAME;";

        //execute query
        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get parse entry by id
     *
     * @param $itemId
     * @return mixed|void
     */
    public function getParseEntry($itemId) {
        //build the query statement
        $sql = "SELECT idGame, name, description, image FROM USER WHERE idGame LIKE '".$itemId . "'";

        //execute query
        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get the id of the tournament that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue(array $filterFields, array $filterArguments) {
        //build the query statement
        $sql = $this->buildQuerySql('GAME', array("idGame"), $filterFields, $filterArguments);

        //execute query
        $result = $this->getResultArray($sql);

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
    public function insertItem(array $fields, array $values) {
        //build the insert statement
        $sql = $this->buildInsertSql('GAME', $fields, $values);

        //executes query
        $result = $this->connection->query($sql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the GAME table
        $id = mysqli_insert_id($this->connection);

        $this->adapter->closeConnection();

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionId",$id);

        return $rawData;
    }

    public function modifyItem($itemNId, $fields, $values)
    {
        // TODO: Implement modifyItem() method.
    }

    /**
     * Deletes the id given item from the table, returns number of rows deleted from the table
     *
     * @param $itemId id from the item to be deleted
     * @return mixed number of rows deleted
     */
    public function deleteItem($itemId) {
        //build query statement
        $sql = "DELETE FROM GAME WHERE idGame = " . $itemId;
        //execute query
        if(!$this->connection->query($sql)) die();

        $affectedRows = $this->connection->affected_rows;

        $this->adapter->closeConnection();

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("deletedRowsNum",$affectedRows);

        return $rawData;
    }
}