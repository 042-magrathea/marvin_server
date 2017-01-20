<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 20/01/17
 * Time: 1:18
 */
include_once "application/dbConnection/adapter/DB_adapter.php";
include_once "Query.php";


class Match_Model extends Query {


    private $matchKinds = array(
        "SINGLE_MATCH" => 0,
        "USER_MATCH" => 1,
        "TEAM_MATCH" => 2
    );

    /**
     * Match_Model constructor.
     * @param $connection
     */
    public function __construct($connection) {
        $this->connection = $connection;
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

        $sql = $this->buildQuerySql('USER', $fields, $filterFields, $filterArguments);

        $result = $this->getResultArray($sql);

        return $result;
    }

    /**
     * Get all fields from all entries of a table
     *
     * @return array
     */
    public function getAllEntries() {
        //build the query statement
        $sql = "SELECT * FROM MATCH;";

        //excute query
        $result = $this->getResultArray($sql);

        return $result;
    }

    /**
     * Builds an array with all required data for parsing an item at any client
     *
     * @return array
     */
    public function getParseEntries() {
        //build the query statement
        $matchesSql = "SELECT * FROM magrathea.MATCH;";

        //execute query
        $matches = $this->getResultArray($matchesSql);

        for ($i = 0; $i < count($matches); $i++) {

            $matches[$i]["finished"] = (boolean)$matches[$i]["finished"];

            $matchId = $matches[$i]["idMATCH"];

            $usersAtMatch = $this->getUsersAtMatch($matchId);

            if ( count($usersAtMatch) > 0) {
                $match[0]["usersAtMatch"] = $usersAtMatch;
            }

            $teamsAtMatch = $this->getTeamsAtMatch($matchId);

            if ( count($teamsAtMatch) > 0) {
                $match[0]["teamsAtMatch"] = $teamsAtMatch;
            }

        }

        return $matches;
    }

    /**
     * Get parse entry by id
     *
     * @param $itemId
     * @return mixed|void
     */
    public function getParseEntry($itemId) {

        //build the query statement
        $matchesSql = "SELECT * FROM magrathea.MATCH WHERE idMATCH LIKE '" . $itemId[0] . "'" ;

        //execute query
        $match = $this->getResultArray($matchesSql);

        $match[0]["finished"] = (boolean)$match[0]["finished"];

        $usersAtMatch = $this->getUsersAtMatch($itemId[0]);

        if ( count($usersAtMatch) > 0) {
            $match[0]["usersAtMatch"] = $usersAtMatch;
        }

        $teamsAtMatch = $this->getTeamsAtMatch($itemId[0]);

        if ( count($teamsAtMatch) > 0) {
            $match[0]["teamsAtMatch"] = $teamsAtMatch;
        }

        return $match;
    }

    /**
     * Get the id of the tournament that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue($filterFields, $filterArguments) {

        //build the insert statement
        $matchKind = $this->matchKinds["SINGLE_MATCH"];

        if (!is_array($filterFields)) {
            throw new mysqli_sql_exception;
        }

        //set match kind
        foreach ($filterFields as $field) {
            switch ($field) {
                case "USER_idUSER":
                    $matchKind = $this->matchKinds["USER_MATCH"];

                    //build USER_MATCH array
                    $userMatchFields = array();
                    $userMatchArguments = array();

                    $fieldCounter = 0;
                    foreach ($filterFields as $field) {
                        switch ($field) {
                            case "MATCH_idMATCH":
                                array_push($userMatchFields, $field);
                                array_push($userMatchArguments, $filterArguments[$fieldCounter]);
                                break;
                            case "USER_idUSER":
                                array_push($userMatchFields, $field);
                                array_push($userMatchArguments, $filterArguments[$fieldCounter]);
                                break;
                            case "points":
                                array_push($userMatchFields, $field);
                                array_push($userMatchArguments, $filterArguments[$fieldCounter]);
                                break;
                        }
                        $fieldCounter++;
                    }

                    break;
                case "TEAM_idTEAM":
                    $matchKind = $this->matchKinds["TEAM_MATCH"];

                    //build TEAM_MATCH array
                    $teamMatchFields = array();
                    $teamMatchArguments = array();

                    $fieldCounter = 0;
                    foreach ($filterFields as $field) {
                        switch ($field) {
                            case "idMATCH":
                                array_push($teamMatchFields, $field);
                                array_push($teamMatchArguments, $filterArguments[$fieldCounter]);
                                break;
                            case "TOURNAMENT_idTOURNAMENT":
                                array_push($teamMatchFields, $field);
                                array_push($teamMatchArguments, $filterArguments[$fieldCounter]);
                                break;
                            case "round":
                                array_push($teamMatchFields, $field);
                                array_push($teamMatchArguments, $filterArguments[$fieldCounter]);
                                break;
                        }
                        $fieldCounter++;
                    }

                    break;
            }
        }

        //build SINGLE_MATCH array
        $singleMatchFields = array();
        $singleMatchArguments = array();

        $fieldCounter = 0;
        foreach ($filterFields as $field) {
            switch ($field) {
                case "idMATCH":
                    array_push($singleMatchFields, $field);
                    array_push($singleMatchArguments, $filterArguments[$fieldCounter]);
                    break;
                case "TOURNAMENT_idTOURNAMENT":
                    array_push($singleMatchFields, $field);
                    array_push($singleMatchArguments, $filterArguments[$fieldCounter]);
                    break;
                case "round":
                    array_push($singleMatchFields, $field);
                    array_push($singleMatchArguments, $filterArguments[$fieldCounter]);
                    break;
                case "type":
                    array_push($singleMatchFields, $field);
                    array_push($singleMatchArguments, $filterArguments[$fieldCounter]);
                    break;
                case "finished":
                    array_push($singleMatchFields, $field);
                    array_push($singleMatchArguments, $filterArguments[$fieldCounter]);
                    break;
            }
            $fieldCounter++;
        }

        //build the query statement
        $singleMatchSql = $sql = $this->buildQuerySql('magrathea.MATCH', array("idMATCH"), $singleMatchFields, $singleMatchArguments);


        //execute query
        $rawData = $this->getResultArray($singleMatchSql);

        if ($matchKind == $this->matchKinds["USER_MATCH"]) {
            //build the query statement
            $userMatchSql = $sql = $this->buildQuerySql('magrathea.MATCH_has_USER', array("MATCH_idMATCH as idMATCH"), $userMatchFields, $userMatchArguments);

            //execute query
            $rawData = $this->getResultArray($userMatchSql);
        }

        if ($matchKind == $this->matchKinds["TEAM_MATCH"]) {
            //build the query statement
            $teamMatchSql = $sql = $this->buildQuerySql('magrathea.MATCH_has_USER', array("MATCH_idMATCH as idMATCH"), $teamMatchFields, $teamMatchArguments);

            //execute query
            $rawData = $this->getResultArray($teamMatchSql);
        }

        return $rawData;
    }

    /**
     * Insert al specified fields of an item with the specified values into the model table
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
    public function insertItem($fields, $values) {

        array_push($fields, "finished");
        array_push($values, false);

        //build the query statement
        $singleMatchSql = $this->buildInsertSql("magrathea.MATCH", $fields, $values);
        echo  $singleMatchSql;
        //execute query
        $this->connection->query($singleMatchSql);

        //get insertion id

        $insertionId = mysqli_insert_id($this->connection);

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionId",$insertionId);

        return $rawData;
    }

    public function insertUserAtMatch($matchId, $userId) {
        $fields = array("MATCH_idMATCH", "USER_idUSER");
        $values = array($matchId[0], $userId[0]);

        //build the query statement
        $userMatchSql = $this->buildInsertSql('MATCH_has_USER', $fields, $values);

        //execute query
        $this->connection->query($userMatchSql);

        if (mysqli_affected_rows($this->connection) > 0 ) {
            $insertionResult = true;
        } else {
            $insertionResult = false;
        }


        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionResult",$insertionResult);

        return $rawData;
    }

    public function insertTeamAtMatch($matchId, $teamId) {
        $fields = array("MATCH_idMATCH", "TEAM_idTEAM");
        $values = array($matchId, $teamId);

        //build the query statement
        $teamMatchSql = $this->buildInsertSql('MATCH_has_TEAM', $fields, $values);

        //execute query
        $this->connection->query($teamMatchSql);

        if (mysqli_affected_rows($this->connection) > 0 ) {
            $insertionResult = true;
        } else {
            $insertionResult = false;
        }


        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionResult",$insertionResult);

        return $rawData;
    }

    /**
     * Modify al specified fields of an item with the specified values into the model table
     *
     * @param $itemId
     * @param array $fields must contain all fields to be modified in the new entry
     * @param array $values must contain the values of the fields to be modified, the value position must match the position
     * of the corresponding field at $fields array
     * @return mixed
     * @internal param $itemNId
     */
    public function modifyItem($itemId, $fields, $values) {
        //build query statement
        $sql = $this->buildUpdateSql('magrathea.MATCH', $fields, $values, array('idMATCH'), array($itemId));

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
    public function deleteItem($itemId)
    {
        //build query statement
        $userMatchSQL = $this->buildDeletionSql("MATCH_has_USER", array("MATCH_idMATCH"), $itemId);
        $teamMatchSQL = $this->buildDeletionSql("MATCH_has_TEAM", array("MATCH_idMATCH"), $itemId);
        $singleMatchSQL= $this->buildDeletionSql("magrathea.MATCH", array("idMATCH"), $itemId);

        try {
            //start transaction
            $this->connection->begin_transaction();


            //execute deletions
            $queryResult1 = $this->connection->query($userMatchSQL);
            $queryResult2 = $this->connection->query($teamMatchSQL);
            $queryResult3 = $this->connection->query($singleMatchSQL);
            //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
            $affectedRows = mysqli_affected_rows($this->connection);

            //check query success
            if ($queryResult1 && ($queryResult2 || $queryResult3)) {
                $this->connection->commit();
            } else {
                throw new mysqli_sql_exception();
            }



            //converts the array to JSON friendly format
            $rawData = $this->getJsonFriendlyArray("deletedRowsNum",$affectedRows);

            return $rawData;

        } catch (mysqli_sql_exception $e) {

            $this->connection->rollback();


            //converts the array to JSON friendly format
            $rawData = $this->getJsonFriendlyArray("deletedRowsNum",0);

            return $rawData;
        }

    }

    public function getUsersAtMatch($matchId) {
        $usersAtMatchSql = "SELECT USER_idUSER, points FROM MATCH_has_USER WHERE MATCH_idMATCH LIKE " . $matchId;

        $usersAtMatch = $this->getResultArray($usersAtMatchSql);

        return $usersAtMatch;

    }

    public function getTeamsAtMatch($matchId) {
        $teamsAtMatchSql = "SELECT TEAM_idTEAM, points FROM MATCH_has_TEAM WHERE MATCH_idMATCH LIKE " . $matchId;

        $teamsAtMatch = $this->getResultArray($teamsAtMatchSql);

        return $teamsAtMatch;

    }

}