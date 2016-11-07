<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 4/11/16
 * Time: 13:41
 */

include_once "DB_adapter.php";
include_once "Query.php";


class RankingQuery extends Query {

    private $adapter;
    protected $connection;

    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();

    }

    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments)
    {
        // TODO: Implement getCustomEntries() method.
    }

    /**
     * Builds an array with all required data for parsing a tournament ranking at any client
     *
     * Array structure:
     *  array {
     *      tournamens {
     *          tournamentId
     *          tournamentHost
     *          gameSystem
     *          users{
     *              userId
     *              publicName
     *              totalPlayedTournaments
     *              totalUserPoints
     *              positionAtTournament
     *              earnedPoints
     *              achievements{
     *                  achievementName
     *              }
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
        $tournaments = $this->getArraySQL($tournamentssql);

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
            $usersIds = $this->getArraySQL($usersIdsSql);


            //walk through all users id array
            for ($j = 0; $j < count($usersIds); $j++) {
                //get user Id
                $userId = $usersIds[$j]["USER_idUSER"];

                //get data from user in DB
                $userSql = "SELECT idUSER, publicName FROM USER WHERE idUSER = ".$userId;
                $user = $this->getArraySQL($userSql)[0];

                $pointsAndTournamentNr = $this->getUserTotalPoints($userId);
                $user["totalTournaments"] = $pointsAndTournamentNr[0];
                $user["totalUserPoints"] = $pointsAndTournamentNr[1];
                $user["positionAtTournament"] = $usersIds[$j]["rank"];
                $user["earnedPoints"] = $this->calculatePointsBySystem($usersIds[$j]["rank"], $this->getGameSystem($tournamentId));

                ///////////////////////////
                //build achievements list//
                ///////////////////////////

                $achievementsIdsSQL = "SELECT ACHIEVEMENT_idACHIEVEMENT FROM USER_has_ACHIEVEMENT WHERE USER_idUSER LIKE ".$userId;
                $achievementsIds = $this->getArraySQL($achievementsIdsSQL);

                $achievements = array();
                for ($k = 0; $k < count($achievementsIds); $k++) {
                    $achievementSQL = "SELECT name FROM ACHIEVEMENT WHERE idACHIEVEMENT LIKE ".$achievementsIds[$k]["ACHIEVEMENT_idACHIEVEMENT"];
                    $achievement = $this->getArraySQL($achievementSQL);

                    $achievement = $achievement[0];

                    $achievements[$k] = $achievement;
                }

                $user["achievements"] = $achievements;

                //add actual user to users array
                $users[$j] = $user;

            }

            //add users array to tournaments array
            $tournament = Query::mergeArrays($tournaments, $i, $users, "users");
            $tournaments[$i] = $tournament;
        }





        $this->adapter->closeConnection();


        return $tournaments;
    }

    public function getAllEntries() {
        $sql = "SELECT * FROM TOURNAMENT_HOST;";

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }


    public function insertItem(array $fields, array $values)
    {
        // TODO: Implement insertItem() method.
    }

    public function getParseEntry($itemId)
    {
        // TODO: Implement getParseEntry() method.
    }

    public function getIdValue(array $filterFields, array $filterArguments)
    {
        // TODO: Implement getIdValue() method.
    }
}