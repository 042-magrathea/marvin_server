<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:01
 */

include_once "application/dbConnection/adapter/DB_adapter.php";
include_once "Query.php";


/**
 * Class Host_Model
 */
class Host_Model extends Query {


    /**
     * Host_Model constructor.
     * @param $connection
     */
    public function __construct($connection) {
        $this->connection = $connection;
        $this->connection->query("SET NAMES 'utf8'");
    }


    //----------------------------------------------------------------------------------------------------------------//

    //                                              COMMON METHODS                                                    //

    //----------------------------------------------------------------------------------------------------------------//

    /**
     * Get all entries from the 'HOST' table in database that matches all parameters specified, this method has to be used
     * to do execute requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getCustomEntries($fields, $filterFields, $filterArguments) {

        $sql = $this->buildQuerySql('TOURNAMENT_HOST', $fields, $filterFields, $filterArguments);

        $result = $this->getResultArray($sql);



        return $result;
    }

    /**
     * Get all fields from all entries of the table TOURNAMENT_HOST from the database
     *
     * @return array
     */
    public function getAllEntries() {
        $sql = "SELECT * FROM TOURNAMENT_HOST;";

        $result = $this->getResultArray($sql);



        return $result;
    }

    /**
     * Builds an array with all required data for parsing a host at any client
     *
     * @return array
     */
    public function getParseEntries() {
        $sql = "SELECT idTournamentHost, name FROM TOURNAMENT_HOST;";

        $result = $this->getResultArray($sql);



        return $result;
    }

    /**
     * Get single parse entry by id
     *
     * @param $itemId
     * @return mixed|void
     */
    public function getParseEntry($itemId) {
        $sql = "SELECT idTournamentHost, name FROM TOURNAMENT_HOST WHERE idHOST LIKE " . $itemId;

        $result = $this->getResultArray($sql);



        return $result;
    }

    /**
     * Get the id of the tournament that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue($filterFields, $filterArguments) {
        //build the query statement
        $sql = $this->buildQuerySql('TOURNAMENT_HOST', array("idTournamentHost"), $filterFields, $filterArguments);

        //execute query
        $result = $this->getResultArray($sql);



        return $result;
    }

    /**
     * Insert all specified fields of an item with the specified values into the table HOST
     *
     * @param $fields string or string array must contain all fields to be stored in the new entry
     * @param $values string or string array must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
    public function insertItem($fields, $values) {
        //build the insert statement
        $sql = $this->buildInsertSql('TOURNAMENT_HOST', $fields, $values);

        //executes query
        $result = $this->connection->query($sql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the GAME table
        $id = mysqli_insert_id($this->connection);



        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionId",$id);

        return $rawData;
    }

    /**
     * Modify al specified fields of a tournament host with the specified values into the TOURNAMENT_HOST table
     *
     * @param $itemId
     * @param array $fields must contain all fields to be modified in the new entry
     * @param array $values must contain the values of the fields to be modified, the value position must match the position
     * of the corresponding field at $fields array
     * @return mixed
     */
    public function modifyItem($itemId, $fields, $values) {
        //build query statement
        $sql = $this->buildUpdateSql('TOURNAMENT_HOST', $fields, $values, array("idTournamentHost"), array($itemId));

        //execute query
        if(!$this->connection->query($sql)) die();

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $affectedRows = mysqli_affected_rows($this->connection);



        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("modifiedRowsNum",$affectedRows);

        return $rawData;
    }

    /**
     * Deletes the id given item from the table, returns number of rows deleted from the table
     *
     * @param $itemId id from the item to be deleted
     * @return mixed number of rows deleted
     */
    public function deleteItem($itemId) {
        //build query statement
        $sql = $this->buildDeletionSql("TOURNAMENT_HOST", array("idTournamentHost"), $itemId);

        //execute query
        if(!$this->connection->query($sql)) die();

        $affectedRows = mysqli_affected_rows($this->connection);



        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("deletedRowsNum",$affectedRows);

        return $rawData;
    }
    //----------------------------------------------------------------------------------------------------------------//

    //                                           END OF COMMON METHODS                                                //

    //----------------------------------------------------------------------------------------------------------------//
}