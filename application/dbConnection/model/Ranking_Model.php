<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 4/11/16
 * Time: 13:41
 */

include_once "application/dbConnection/adapter/DB_adapter.php";
include_once "Query.php";


/**
 * Class Ranking_Model
 */
class Ranking_Model extends Query {


    /**
     * Ranking_Model constructor.
     * @param $connection
     */
    public function __construct($connection) {
        $this->connection = $connection;
        $this->connection->query("SET NAMES 'utf8'");
    }


    //---------------------------------------------------------------------------------------------------------------//

    //////////////////////////
    // USED BY THE PROTOTYPE//
    //////////////////////////

     /**
     * Builds an array with all required data for parsing a tournament ranking at any client
     *
     * Array structure:
     *  array {
     *      tournaments {
     *          tournamentId
     *          tournamentHost
     *          gameSystem
     *          users{
     *              user{
     *                  userId
     *                  publicName
     *                  totalPlayedTournaments
     *                  totalUserPoints
     *                  positionAtTournament
     *                  earnedPoints
     *                  achievements{
     *                      achievement{
     *                          achievementName
     *                      }
     *                      ...
     *                  }
     *              }
     *              ...
     *          }
     *      }
     *      ...
     * }
     *
     * @return array
     */
    public function getParseEntries() {

        //get all tournaments data from DB
        $tournamentssql = "SELECT idTOURNAMENT, TOURNAMENT_HOST_idTournamentHost, SYSTEM_idSYSTEM FROM TOURNAMENT";
        $tournaments = $this->getResultArray($tournamentssql);

        /////////////////////////
        //build tournament list//
        /////////////////////////

        //walk through all tournaments array
        for ($i = 0; $i < count($tournaments); $i++) {
            //store de actual tournament Id
            $tournamentId = $tournaments[$i]["idTOURNAMENT"];

            ///////////////////
            //build user list//
            ///////////////////

            //get all
            $usersIdsSql = "SELECT USER_idUSER, rank FROM TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;
            $usersIds = $this->getResultArray($usersIdsSql);


            //walk through all users ids array
            for ($j = 0; $j < count($usersIds); $j++) {
                //get user Id
                $userId = $usersIds[$j]["USER_idUSER"];

                //get data from user in DB
                $userSql = "SELECT idUSER, publicName FROM USER WHERE idUSER = ".$userId;
                $user = $this->getResultArray($userSql)[0];

                //calculate user total points and played tournaments
                $pointsAndTournamentNr = $this->getUserTotalPoints($userId);

                //updates user array with aditional values
                $user["totalTournaments"] = $pointsAndTournamentNr[0];
                $user["totalUserPoints"] = $pointsAndTournamentNr[1];
                $user["positionAtTournament"] = $usersIds[$j]["rank"];
                $user["earnedPoints"] = $this->calculatePointsBySystem($usersIds[$j]["rank"], $this->getGameSystem($tournamentId));

                ///////////////////////////
                //build achievements list//
                ///////////////////////////

                $achievements = array();

                //get all achievement id's earned by the user
                $achievementsIdsSQL = "SELECT ACHIEVEMENT_idACHIEVEMENT FROM USER_has_ACHIEVEMENT WHERE USER_idUSER LIKE ".$userId;
                $achievementsIds = $this->getResultArray($achievementsIdsSQL);

                //walk through all achievements ids array
                for ($k = 0; $k < count($achievementsIds); $k++) {
                    //get the achievement's data
                    $achievementSQL = "SELECT name FROM ACHIEVEMENT WHERE idACHIEVEMENT LIKE ".$achievementsIds[$k]["ACHIEVEMENT_idACHIEVEMENT"];
                    $achievement = $this->getResultArray($achievementSQL);

                    //eliminates one level from the array
                    $achievement = $achievement[0];

                    //add the achievement details array to achievements array
                    $achievements[$k] = $achievement;
                }
                //add achievements array to user array
                $user["achievements"] = $achievements;

                //add actual user to users array
                $users[$j] = $user;

            }

            //add users array to tournaments array
            $tournament = Query::mergeArrays($tournaments, $i, $users, "users");
            $tournaments[$i] = $tournament;
        }



        return $tournaments;
    }

    /**
     * Get parse entry by id
     *
     * @param $itemId
     * @return mixed|void
     */
    public function getParseEntry($itemId) {
        //get all tournaments data from DB
        /*$tournamentssql = "SELECT idTOURNAMENT, TOURNAMENT_HOST_idTournamentHost, SYSTEM_idSYSTEM FROM TOURNAMENT WHERE" .
            " idTOURNAMENT LIKE '". $itemId . "'";*/

        $tournamentsSql = $this->buildQuerySql('TOURNAMENT', array("idTOURNAMENT", "TOURNAMENT_HOST_idTournamentHost", "SYSTEM_idSYSTEM"),
            array("idTOURNAMENT"), array($itemId));

        $tournaments = $this->getResultArray($tournamentsSql);

        /////////////////////////
        //build tournament list//
        /////////////////////////

        //walk through all tournaments array
        for ($i = 0; $i < count($tournaments); $i++) {
            //store de actual tournament Id
            $tournamentId = $tournaments[$i]["idTOURNAMENT"];

            ///////////////////
            //build user list//
            ///////////////////
            $users = array();

            //get all
            $usersIdsSql = "SELECT USER_idUSER, rank FROM TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;
            $usersIds = $this->getResultArray($usersIdsSql);


            //walk through all users ids array
            for ($j = 0; $j < count($usersIds); $j++) {
                //get user Id
                $userId = $usersIds[$j]["USER_idUSER"];

                //get data from user in DB
                $userSql = "SELECT idUSER, publicName FROM USER WHERE idUSER = ".$userId;
                $user = $this->getResultArray($userSql)[0];

                //calculate user total points and played tournaments
                $pointsAndTournamentNr = $this->getUserTotalPoints($userId);

                //updates user array with aditional values
                $user["totalTournaments"] = $pointsAndTournamentNr[0];
                $user["totalUserPoints"] = $pointsAndTournamentNr[1];
                $user["positionAtTournament"] = $usersIds[$j]["rank"];
                $user["earnedPoints"] = $this->calculatePointsBySystem($usersIds[$j]["rank"], $this->getGameSystem($tournamentId));

                ///////////////////////////
                //build achievements list//
                ///////////////////////////

                $achievements = array();

                //get all achievement id's earned by the user
                $achievementsIdsSQL = "SELECT ACHIEVEMENT_idACHIEVEMENT FROM USER_has_ACHIEVEMENT WHERE USER_idUSER LIKE ".$userId;
                $achievementsIds = $this->getResultArray($achievementsIdsSQL);

                //walk through all achievements ids array
                for ($k = 0; $k < count($achievementsIds); $k++) {
                    //get the achievement's data
                    $achievementSQL = "SELECT name FROM ACHIEVEMENT WHERE idACHIEVEMENT LIKE ".$achievementsIds[$k]["ACHIEVEMENT_idACHIEVEMENT"];
                    $achievement = $this->getResultArray($achievementSQL);

                    //eliminates one level from the array
                    $achievement = $achievement[0];

                    //add the achievement details array to achievements array
                    $achievements[$k] = $achievement;
                }
                //add achievements array to user array
                $user["achievements"] = $achievements;

                //add actual user to users array
                $users[$j] = $user;

            }

            //add users array to tournaments array
            $tournament = Query::mergeArrays($tournaments, $i, $users, "users");
            $tournaments[$i] = $tournament;
        }



        return $tournaments;
    }

    //---------------------------------------------------------------------------------------------------------------//

    //////////////////////////////
    // NOT USED BY THE PROTOTYPE//
    //////////////////////////////

    /**
     * Builds an array with all required data for parsing a tournament ranking at any client containing all entries from
     * the implied tables into the database that matches all parameters specified, this method has to be used to do execute
     * requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getCustomEntries($fields, $filterFields, $filterArguments)
    {
        // TODO: Implement getCustomEntries() method.
    }

    /**
     * Get all fields from all entries of the table TOURNAMENT_HOST from the database
     *
     * @return array
     */
    public function getAllEntries() {

        $result = $this->getParseEntries();


        return $result;
    }


    /**
     * Insert a ranking
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
    public function insertItem($fields, $values)
    {
        // TODO: Implement insertItem() method.
    }



    /**
     * Get the id of the ranking that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue($filterFields, $filterArguments)
    {
        // TODO: Implement getIdValue() method.
    }

    public function modifyItem($itemNId, $fields, $values)
    {
        // TODO: Implement modifyItem() method.
    }

    public function deleteItem($itemId)
    {
        // TODO: Implement deleteItem() method.
    }
}