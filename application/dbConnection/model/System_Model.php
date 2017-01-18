<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 16/01/17
 * Time: 20:18
 */
include_once "application/dbConnection/adapter/DB_adapter.php";
include_once "Query.php";

class System_Model extends Query {

    private $adapter;


    /**
     * Host_Model constructor.
     */
    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();
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

        $sql = $this->buildQuerySql('SYSTEM', $fields, $filterFields, $filterArguments);

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get all fields from all entries of the table SYSTEM from the database
     *
     * @return array
     */
    public function getAllEntries() {
        $sql = "SELECT * FROM SYSTEM;";

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Builds an array with all required data for parsing a system at any client
     *
     * @return array
     */
    public function getParseEntries() {
        $systemsSql = "SELECT idSYSTEM, name, nRounds, nPlayoffs, umpirePoints, goldPoints, silverPoints, bronzePoints,".
            " ironPoints, matchPlayers, maxTeamPlayer, GAME_idGAME FROM SYSTEM;";

        $systems = $this->getResultArray($systemsSql);

        $result = array();

        foreach ($systems as $system){
            $gameId = $system["GAME_idGAME"];

            $gamesSql = "SELECT idGame, name, description, image FROM GAME WHERE idGame LIKE '".$gameId . "'";

            $game = $this->getResultArray($gamesSql);

            $system["GAME"] = $game;
            unset($system["GAME_idGAME"]);

            array_push($result, $system);
        }

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get single parse entry by id
     *
     * @param $itemId
     * @return mixed|void
     */
    public function getParseEntry($itemId) {
        $systemsSql = "SELECT idSYSTEM, name, nRounds, nPlayoffs, umpirePoints, goldPoints, silverPoints, bronzePoints,".
            " ironPoints, matchPlayers, maxTeamPlayer, GAME_idGAME FROM SYSTEM WHERE idSYSTEM LIKE '" . $itemId . "';";

        $systems = $this->getResultArray($systemsSql);

        $result = array();

        foreach ($systems as $system){
            $gameId = $system["GAME_idGAME"];

            $gamesSql = "SELECT idGame, name, description, image FROM GAME WHERE idGame LIKE '".$gameId . "'";

            $game = $this->getResultArray($gamesSql);

            $system["GAME"] = $game;
            unset($system["GAME_idGAME"]);

            array_push($result, $system);
        }

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
    public function getIdValue($filterFields, $filterArguments) {
        //build the query statement
        $sql = $this->buildQuerySql('SYSTEM', array("idPRIZE"), $filterFields, $filterArguments);

        //execute query
        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

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
        $sql = $this->buildInsertSql('SYSTEM', $fields, $values);

        //executes query
        $result = $this->connection->query($sql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the GAME table
        $id = mysqli_insert_id($this->connection);

        $this->adapter->closeConnection();

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
        $sql = $this->buildUpdateSql('SYSTEM', $fields, $values, array("idSYSTEM"), array($itemId));

        //execute query
        if(!$this->connection->query($sql)) die();

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $affectedRows = mysqli_affected_rows($this->connection);

        $this->adapter->closeConnection();

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
        $sql = $this->buildDeletionSql("SYSTEM", array("idSYSTEM"), $itemId);

        //execute query
        if(!$this->connection->query($sql)) die();

        $affectedRows = mysqli_affected_rows($this->connection);

        $this->adapter->closeConnection();

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("deletedRowsNum",$affectedRows);

        return $rawData;
    }
    //----------------------------------------------------------------------------------------------------------------//

    //                                           END OF COMMON METHODS                                                //

    //----------------------------------------------------------------------------------------------------------------//
}