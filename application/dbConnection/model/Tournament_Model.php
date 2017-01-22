<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:37
 */
include_once "application/dbConnection/adapter/DB_adapter.php";
include_once "Query.php";

/**
 * Class Tournament_Model
 */
class Tournament_Model extends Query {


    /**
     * Tournament_Model constructor.
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
     * Get all entries from the 'TOURNAMENT' table in database that matches all parameters specified, this method has to be used
     * to execute custom requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array the results array
     */
    public function getCustomEntries($fields, $filterFields, $filterArguments) {

        $sql = $this->buildQuerySql('TOURNAMENT', $fields, $filterFields, $filterArguments);

        $result = $this->getResultArray($sql);

        return $result;
    }

    /**
     * Get all fields from all entries of the table TOURNAMENT from the database
     *
     * @return array the results array
     */
    public function getAllEntries() {
        $sql = "SELECT * FROM TOURNAMENT;";

        $result = $this->getResultArray($sql);

        return $result;
    }

    /**
     * Builds an array with all required data for parsing a tournament at any client
     *
     * Array structure:
     * tournaments {
     *      tournament {
     *          idTOURNAMENT
     *          name
     *          publicDes
     *          privateDes
     *          date
     *          TOURNAMENT_HOST_idTOURNAMENT_HOST
     *          prizes{
     *              prize{
     *                  idPRIZE
     *                  name
     *                  position
     *              }
     *              ...
     *          }
     *          users{
     *              user{
     *                  userId
     *                  publicName
     *              }
     *              ...
     *          }
     *      }
     *      ...
     *  }
     *
     *
     * @return array the results array
     */
    public function getParseEntries() {

        $tournamentssql = "SELECT idTOURNAMENT, name, publicDes, privateDes, date, TOURNAMENT_HOST_idTournamentHost, ".
            "SYSTEM_idSYSTEM, maxPlayers, minPlayers, image FROM TOURNAMENT";
        //get all tournaments data from DB
        $tournaments = $this->getResultArray($tournamentssql);

        for ($i = 0; $i < count($tournaments); $i++) {
            //store de actual torunament Id
            $tournamentId = $tournaments[$i]["idTOURNAMENT"];

            $tournaments[$i]["status"] = $this->calculateTournamentStatus($tournamentId);

            $prizesIdsSql = "SELECT idPRIZE, tournamentPosition FROM PRIZE WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;
            //get id's from all prizes for actual tournament
            $prizesIds = $this->getResultArray($prizesIdsSql);

            //walk through all prizes id array
            $j = count($prizesIds);
            $prizes = array();
            while ($j > 0) {
                $prizesSql = "SELECT idPRIZE, name FROM PRIZE WHERE idPRIZE = ".$prizesIds[$j-1]["idPRIZE"];
                //get prize details for actual prize id
                $res = $this->getResultArray($prizesSql);
                //add position to prize info
                array_push($res[0], $prizesIds[$j-1]["tournamentPosition"]);
                //add actual prize to prizes array
                array_push($prizes, $res[0]);

                //change the field key from numeric to his name
                $pos = $prizes[count($prizes)-1][0]; //store de position value
                unset($prizes[count($prizes)-1][0]); //erase de position field with numeric key
                $prizes[count($prizes)-1]["tournamentPosition"] = $pos; //add position with correct kay name
                $j--;
            }

            $usersIdsSql = "SELECT USER_idUSER FROM TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;
            $usersIds = $this->getResultArray($usersIdsSql);

            //walk through all users id array
            $j = count($usersIds);
            $users = array();
            while ($j > 0) {
                $usersSql = "SELECT idUSER, publicName FROM USER WHERE idUSER = ".$usersIds[$j-1]["USER_idUSER"];
                //get user detail for actual user id
                $res = $this->getResultArray($usersSql);
                //add actual user to users array
                array_push($users, $res[0]);
                $j--;
            }

            //add prizes array to tournaments array
            $tournament = $this->mergeArrays($tournaments, $i, $prizes, "prizes");
            $tournaments[$i] = $tournament;

            //add users array to tournaments array
            $tournament = $this->mergeArrays($tournaments, $i, $users, "users");
            $tournaments[$i] = $tournament;
        }

        return $tournaments;
    }

    /**
     * Builds an array with all required data for parsing the trounament stored in database that matches with the given id at
     * any client
     *
     * @param $itemId the tournament's id
     * @return array the results array
     */
    public function getParseEntry($itemId)
    {
        $tournamentssql = "SELECT idTOURNAMENT, name, publicDes, privateDes, image, date, TOURNAMENT_HOST_idTournamentHost, ".
            "SYSTEM_idSYSTEM, maxPlayers, minPlayers, image FROM TOURNAMENT WHERE idTOURNAMENT LIKE '". $itemId[0] . "'";
        //get all tournaments data from DB
        $tournaments = $this->getResultArray($tournamentssql);

        $tournaments[0]["status"] = $this->calculateTournamentStatus($itemId);

        $prizesIdsSql = "SELECT idPRIZE, tournamentPosition FROM PRIZE WHERE TOURNAMENT_idTOURNAMENT = ".$itemId[0];
        //get id's from all prizes for actual tournament
        $prizesIds = $this->getResultArray($prizesIdsSql);

        //walk through all prizes id array
        $prizeCounter = count($prizesIds);
        $prizes = array();
        while ($prizeCounter > 0) {
            $prizesSql = "SELECT idPRIZE, name FROM PRIZE WHERE idPRIZE = ".$prizesIds[$prizeCounter-1]["idPRIZE"];
            //get prize details for actual prize id
            $res = $this->getResultArray($prizesSql);
            //add position to prize info
            array_push($res[0], $prizesIds[$prizeCounter-1]["tournamentPosition"]);
            //add actual prize to prizes array
            array_push($prizes, $res[0]);

            //change the field key from numeric to his name
            $pos = $prizes[count($prizes)-1][0]; //store de position value
            unset($prizes[count($prizes)-1][0]); //erase de position field with numeric key
            $prizes[count($prizes)-1]["tournamentPosition"] = $pos; //add position with correct kay name
            $prizeCounter--;
        }

        $usersIdsSql = "SELECT USER_idUSER FROM TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT = ".$itemId[0];
        $usersIds = $this->getResultArray($usersIdsSql);

        //walk through all users id array
        $userCounter = count($usersIds);
        $users = array();
        while ($userCounter > 0) {
            $usersSql = "SELECT * FROM USER WHERE idUSER = ".$usersIds[$userCounter-1]["USER_idUSER"];
            //get user detail for actual user id
            $user = $this->getResultArray($usersSql);
            //add actual user to users array
            array_push($users, $user[0]);
            $userCounter--;
        }

        //the system id
        $systemId = $tournaments[0]["SYSTEM_idSYSTEM"];

        $systemSql = "SELECT * FROM SYSTEM WHERE idSYSTEM LIKE " . $systemId;
        //get system detail
        $system = $this->getResultArray($systemSql);

        $gameId = $system[0]["GAME_idGAME"];

        $gameSql = "SELECT * FROM GAME WHERE idGAME LIKE " . $gameId;
        //get system detail
        $game = $this->getResultArray($gameSql);

        $hostId = $tournaments[0]["TOURNAMENT_HOST_idTournamentHost"];

        $hostSql = "SELECT * FROM TOURNAMENT_HOST WHERE idTournamentHost LIKE " . $hostId;
        //get system detail
        $host = $this->getResultArray($hostSql);

        //add prizes array to tournaments array
        $tournament = $this->mergeArrays($tournaments, 0, $prizes, "prizes");
        $tournaments[0] = $tournament;

        //add users array to tournaments array
        $tournament = $this->mergeArrays($tournaments, 0, $users, "users");
        $tournaments[0] = $tournament;


        //add system array to tournaments array
        $tournament = $this->mergeArrays($tournaments, 0, $system[0], "system");
        $tournaments[0] = $tournament;
        unset($tournaments[0]["SYSTEM_idSYSTEM"]);

        //add game array to tournaments array
        $tournament = $this->mergeArrays($tournaments, 0, $game[0], "game");
        $tournaments[0] = $tournament;
        unset($tournaments[0]["system"]["GAME_idGAME"]);

        //add system array to tournaments array
        $tournament = $this->mergeArrays($tournaments, 0, $host[0], "host");
        $tournaments[0] = $tournament;
        unset($tournaments[0]["TOURNAMENT_HOST_idTournamentHost"]);

        return $tournaments;
    }

    /**
     * Get the id of the tournament that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array the results array
     */
    public function getIdValue($filterFields, $filterArguments) {
        //build the query statement
        $sql = $this->buildQuerySql('TOURNAMENT', array("idTOURNAMENT"), $filterFields, $filterArguments);

        //execute query
        $result = $this->getResultArray($sql);

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
    public function insertItem($fields, $values)
    {
        $sql = $this->buildInsertSql('TOURNAMENT', $fields, $values);

        echo $sql;
        $this->connection->query($sql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $id = mysqli_insert_id($this->connection);

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionId",$id);

        return $rawData;

    }

    /**
     * Modify al specified fields of a tournament with the specified values into the TOURNAMENT table
     *
     * @param $itemId
     * @param array $fields must contain all fields to be modified in the new entry
     * @param array $values must contain the values of the fields to be modified, the value position must match the position
     * of the corresponding field at $fields array
     * @return mixed
     */
    public function modifyItem($itemId, $fields, $values) {
        //build query statement
        $sql = $this->buildUpdateSql('TOURNAMENT', $fields, $values, array("idTOURNAMENT"), array($itemId));

        //execute query
        if(!$this->connection->query($sql)) die();

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $affectedRows = $this->connection->affected_rows;

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("modifiedRowsNum",$affectedRows);

        return $rawData;
    }

    /**
     * Deletes the id given TOURNAMENT from the table and his associated entries at TORUNAMENT_has_USER table,
     * returns number of rows deleted from the TOURNAMENT table
     *
     * @param $itemId id from the item to be deleted
     * @return mixed number of rows deleted
     */
    public function deleteItem($itemId) {

        //extract al prizes id's from the tournament before transaction begins
        $findPrizesIdsSQL = $this->buildQuerySql('PRIZE', array("idPRIZE"), array("TOURNAMENT_idTOURNAMENT"), $itemId);
        $prizeIds = $this->getResultArray($findPrizesIdsSQL);

        //Build SQL statements for transaction
        $usersFromTournamentSQL = $this->buildDeletionSql("TOURNAMENT_has_USER", array("TOURNAMENT_idTOURNAMENT"), $itemId);
        $umpiresFromTournamentSQL = $this->buildDeletionSql("UMPIRE", array("TOURNAMENT_idTOURNAMENT"), $itemId);
        $tournamentSQL = $this->buildDeletionSql("TOURNAMENT", array("idTOURNAMENT"), $itemId);

        try {
            //start transaction
            $this->connection->begin_transaction();

            //execute deletions
            $queryResult1 = $this->connection->query($usersFromTournamentSQL);
            $queryResult2 = $this->connection->query($umpiresFromTournamentSQL);
            $queryResult3 = $this->cleanPrizesFromTournament($prizeIds);
            $queryResult4 = $this->connection->query($tournamentSQL);

            //get last deletion result 0 = no deletion, 1 = rows deleted
            $affectedRows = $this->connection->affected_rows;

            //check query success
            if ($queryResult1 && $queryResult2 && $queryResult3 && $queryResult4) {
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

    //----------------------------------------------------------------------------------------------------------------//

    //                                           END OF COMMON METHODS                                                //

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //                                          TOURNAMENT QUERY METHODS                                              //

    //----------------------------------------------------------------------------------------------------------------//


    //------------------------------------------ ---------USERS ------------------------------------------------------//
    /**
     * Get all entries at TOURNAMENT_has_USER table that matches with $tournamentId at field TORUNAMENT_idTOURNAMENT
     *
     * @param $tournamentId the id of the tournament
     * @return array result of the query
     */
    public function usersAtTournament($tournamentId) {

        $result = array();

        $sql = $this->buildQuerySql("TOURNAMENT_has_USER", array("USER_idUSER"), array("TOURNAMENT_idTOURNAMENT"), $tournamentId);

        $usersAtTournament = $this->getResultArray($sql);

        foreach ($usersAtTournament as $user) {
            $userId = $user["USER_idUSER"];
            $umpireSql = $this->buildQuerySql("UMPIRE", array("approved"), array("TOURNAMENT_idTOURNAMENT"), $tournamentId);
            $umpireSql = $umpireSql . " AND USER_idUSER LIKE '" . $userId . "'";

            echo $umpireSql;
            $umpireResult = $this->getResultArray($umpireSql);

            if (count($umpireResult) == 0) {
                $user["umpire"] = "not requested";
            } elseif ((boolean)$umpireResult[0]["approved"]) {
                $user["umpire"] = "approved";
            } else {
                $user["umpire"] = "requested";
            }
            array_push($result, $user);

        }

        return $result;
    }

    /**
     * Checks if an user is signed up in a tornament, checking his existence in TOURNAMENT_HAS_USER table
     *
     * @param $tournamentId the tournament's id
     * @param $userId the user's id
     * @return array single boolean value array
     */
    public function tournamentHasUser($tournamentId, $userId) {
        $sql = "SELECT COUNT(*) FROM magrathea.TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT=" . $tournamentId .
            " AND USER_idUSER=" . $userId . ";";

        $result = $this->getResultArray($sql);

        $result[0]["tournamentHasUser"] = (boolean) $result[0]["COUNT(*)"];
        unset($result[0]["COUNT(*)"]);

        return $result;
    }

    /**
     * Inserts an entry at TOURNAMENT_has_USER table
     *
     * @param $tournamentId string containing the id of the tournament
     * @param $userId string containing the user's id
     * @return array result of the deletion
     */
    public function addUserToTournament($tournamentId, $userId) {
        $fields = array("TOURNAMENT_idTOURNAMENT", "USER_idUSER");
        $values = array_merge($tournamentId, $userId);

        $sql = $this->buildInsertSql('TOURNAMENT_has_USER', $fields, $values);

        $this->connection->query($sql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $affectedRows = mysqli_affected_rows($this->connection);


        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionSuccess",(boolean)$affectedRows);

        return $rawData;
    }

    /**
     * Deletes an entry of the TOURNAMENT_has_USER table
     *
     * @param $tournamentId the id of the tournament
     * @param $userId the user's id
     * @return array result of the deletion
     */
    public function deleteUserFromTournament($tournamentId, $userId) {

        $filterFields = array("TOURNAMENT_idTOURNAMENT", "USER_idUSER");
        $filterArguments = array_merge($tournamentId, $userId);

        $sql = $this->buildDeletionSql("TOURNAMENT_has_USER", $filterFields, $filterArguments);

        $this->connection->query($sql);

        $affectedRows = mysqli_affected_rows($this->connection);

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("deletionSuccess",(boolean)$affectedRows);

        return $rawData;

    }

    /**
     * Counts all entries for the given tournamentId exists at TOURNAMENT_has_USER
     *
     * @param $tournamentId the tournament's id
     * @return array result of the count
     */
    public function countTournamentUsers($tournamentId) {
        $sql = "SELECT COUNT(*) FROM magrathea.TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT=" . $tournamentId . ";";

        $result = $this->getResultArray($sql);

        $result[0]["usersAtTournament"] = $result[0]["COUNT(*)"];
        unset($result[0]["COUNT(*)"]);

        return $result;
    }

    /**
     * Checks if an user is an autorized umpire for a tournament
     *
     * @param $userId the user's id
     * @param $tournamentId the tournament's id
     * @return array an array with an unique boolean value
     */
    public function userIsUmpire($userId, $tournamentId) {
        $isUmpireSql = "SELECT approved FROM UMPIRE WHERE USER_idUSER LIKE ".$userId." AND TOURNAMENT_idTOURNAMENT LIKE ".$tournamentId;

        $result = $this->getResultArray($isUmpireSql);

        if (count($result) <= 0) {
            $result[0]["isUmpire"] = false;
        } else {
            $result[0]["isUmpire"] = (boolean)$result[0]["approved"];
            unset($result[0]["approved"]);
        }

        return $result;
    }
    //----------------------------------------------------------------------------------------------------------------//

    //                                      END OF TOURNAMENT QUERY METHODS                                           //

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //                                                AUXILIAR METHODS                                                //

    //----------------------------------------------------------------------------------------------------------------//

    /**
     * Checks all tournament status values from DB (open, started, finished, cancelled) and returns a string with the
     * status value
     *
     * @param $tournamentId the tournament's id
     * @return string the tournament status (created, published, beggined, interrupted, closed, cancelled or finished)
     */
    private function calculateTournamentStatus($tournamentId) {
        //get state values from DB
        $sql = "SELECT open, started, finished, cancelled FROM TOURNAMENT WHERE idTOURNAMENT LIKE " . $tournamentId[0];

        $result = $this->getResultArray($sql);

/*
        foreach ($result as $key => $value) {
            $value = (boolean)$value;
        }

        echo "open " . $open;
        echo "started " . $started;
        echo "finshed " . $finished;
        echo "cancelled " . $cancelled;
*/
        $open = $result[0]["open"]? true : false;
        $started = $result[0]["started"]? true : false;
        $finished = $result[0]["finished"]? true : false;
        $cancelled = $result[0]["cancelled"]? true : false;

        //switch and assign status string
        if ($open ==true) {
            if ($cancelled == true) {
                $status = "closed";
            } else {
                $status = "published";
            }
        } elseif ($started ==true) {
            if ($cancelled == true) {
                $status = "interrupted";
            } else {
                $status = "beggined";
            }
        } elseif ($finished ==true) {
            $status = "finished";
        } elseif ($cancelled ==true) {
            if ($open == true) {
                $status = "closed";
            } elseif ($started == true) {
                $status = "interrupted";
            } else {
                $status = "cancelled";
            }
        } else {
            $status = "created";
        }

        return $status;
    }

    /**
     * Erases all prizes form a tournament, it requires an open connection to database and an open transaction if you
     * want it to be axecuted as a single operation. Allthoug, changes has to be committed or rolledback as is necessary.
     * Result of queries will be returned as a boolean
     *
     * @param $prizeIds array containing all prize id's as value pairs ("idPRIZE" => <prize value>)
     * @return boolean result of prizes deletions
     */
    private function cleanPrizesFromTournament($prizeIds) {

        //walk through all prizeIds
        foreach ($prizeIds as $prize) {
            //get prizeId
            $prizeId = $prize["idPRIZE"];

            //build all statements
            $discountPrizeSQL = $this->buildDeletionSql("PRIZE_DISCOUNT", array("PRIZE_idPRIZE"), $prizeId);
            $merchantPrizeSQL = $this->buildDeletionSql("PRIZE_MERCHANT", array("PRIZE_idPRIZE"), $prizeId);
            $singlePrizesSQL= $this->buildDeletionSql("PRIZE", array("idPRIZE"), $prizeId);

            //execute queries
            $queryResult1 = $this->connection->query($discountPrizeSQL);
            $queryResult2 = $this->connection->query($merchantPrizeSQL);
            $queryResult3 = $this->connection->query($singlePrizesSQL);

            //check query success
            if (!$queryResult1 || !$queryResult2 || !$queryResult3) {
                throw new mysqli_sql_exception();
            }
        }

        return true;
    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                            END OF AUXILIAR METHODS                                             //

    //----------------------------------------------------------------------------------------------------------------//
}