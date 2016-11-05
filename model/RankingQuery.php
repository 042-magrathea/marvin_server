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

    public function getParseEntries() {
        $tournamentssql = "SELECT idTOURNAMENT, TOURNAMENT_HOST_idTournamentHost, SYSTEM_idSYSTEM FROM TOURNAMENT";
        //get all tournaments data from DB
        $tournaments = $this->getArraySQL($tournamentssql);


        //walk through all tournaments array
        for ($i = 0; $i < count($tournaments); $i++) {
            //store de actual tournament Id
            $tournamentId = $tournaments[$i]["idTOURNAMENT"];

            ///////////////////
            //build user list//
            ///////////////////


            $usersIdsSql = "SELECT USER_idUSER, rank FROM TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;
            $usersIds = $this->getArraySQL($usersIdsSql);


            //walk through all users id array

            $users = array();
            for ($j = 0; $j < count($usersIds); $j++) {

                $userId = $usersIds[$j]["USER_idUSER"];
                $userSql = "SELECT idUSER, publicName FROM USER WHERE idUSER = ".$userId;
                //get user detail for actual user id
                $user = $this->getArraySQL($userSql);

                $user = $user[0];

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

            //add prizes array to tournaments array
/*            $tournament = $this->mergeArrays($tournaments, $i, $prizes, "prizes");
            $tournaments[$i] = $tournament;*/

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




}