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


    public function getUserTotalPoints($userId) {
        $userTotalPoints = 0;

        /////////////////////////////////
        //check single user tournaments//
        /////////////////////////////////

        $tournamentsIdsSql = "SELECT TOURNAMENT_idTOURNAMENT, rank FROM TOURNAMENT_has_USER WHERE USER_idUSER LIKE ".$userId;

        $tournamentsIds = $this->getArraySQL($tournamentsIdsSql);
        for ($i = 0; $i < count($tournamentsIds); $i++) {


            $isUmpireSql = "SELECT approved FROM UMPIRE WHERE USER_idUSER LIKE ".$userId." AND TOURNAMENT_idTOURNAMENT LIKE ".$tournamentsIds[$i]["TOURNAMENT_idTOURNAMENT"];
            $isUmpire = $this->getArraySQL($isUmpireSql);

            if ($isUmpire[0]["approved"] != null && $isUmpire[0]["approved"] == 1) {
                $userRank = 0;
            } else {
                $userRank = $tournamentsIds[$i]["rank"];
            }
/*            $tournamentSystemIdSql = "SELECT SYSTEM_idSYSTEM FROM TOURNAMENT WHERE idTOURNAMENT LIKE ".$tournamentsIds[$i]["TOURNAMENT_idTOURNAMENT"];
            $tournamentSystemId = $this->getArraySQL($tournamentSystemIdSql);
            $systemPoints = "SELECT umpirePoints, goldPoints, silverPoints, bronzePoints, ironPoints FROM SYSTEM WHERE idSYSTEM LIKE ". $tournamentSystemId[0]["SYSTEM_idSYSTEM"];
            $tournamentSystem = $this->getArraySQL($systemPoints);*/

            $tournamentSystem = $this->getGameSystem($tournamentsIds[$i]["TOURNAMENT_idTOURNAMENT"]);

            $userTotalPoints = $userTotalPoints + $this->calculatePointsBySystem($userRank, $tournamentSystem);
        }

        /*//////////////////////////
        //check team tournaments//
        //////////////////////////

        $teamIdsSQL = "SELECT TEAM_idTEAM FROM TEAM_has_USER WHERE USER_idUSER LIKE ".$userId;
        $teamIds = $this->getArraySQL($teamIdsSQL);
        $tournamentsIdsSql = "SELECT TOURNAMENT_idTOURNAMENT, rank FROM TOURNAMENT_has_TEAM WHERE TEAM_idTEAM LIKE ".$teamIds[0]["TEAM_idTEAM"];
        $tournamentsIds = $this->getArraySQL($tournamentsIdsSql);


        for ($j = 0; $j < count($teamIds); $j++) {

            $tournamentSystemIdSql = "SELECT SYSTEM_idSYSTEM FROM TOURNAMENT WHERE idTOURNAMENT LIKE ".$tournamentsIds[$j]["TOURNAMENT_idTOURNAMENT"];
            $tournamentSystemId = $this->getArraySQL($tournamentSystemIdSql);
            $systemPoints = "SELECT umpirePoints, goldPoints, silverPoints, bronzePoints, ironPoints FROM SYSTEM WHERE idSYSTEM LIKE ". $tournamentSystemId[0]["SYSTEM_idSYSTEM"];
            $tournamentSystem = $this->getArraySQL($systemPoints);
            $teamRank = $tournamentsIds[$j]["rank"];
            $userTotalPoints = $userTotalPoints + $this->calculatePointsBySystem($teamRank, $tournamentSystem);

        }*/

        return array(count($tournamentsIds), $userTotalPoints);
    }

    private function calculatePointsBySystem($userRank, array $tournamentSystem) {
        switch ($userRank) {
            case 0:
                return $tournamentSystem[0]["umpirePoints"];
            case 1:
                return $tournamentSystem[0]["goldPoints"];
            case 2:
                return $tournamentSystem[0]["silverPoints"];
            case 3:
                return $tournamentSystem[0]["bronzePoints"];
            case null:
                return 0;
            default:
                return $tournamentSystem[0]["ironPoints"];
        }
    }

    private function getGameSystem($tournamentId) {
        $tournamentSystemIdSql = "SELECT SYSTEM_idSYSTEM FROM TOURNAMENT WHERE idTOURNAMENT LIKE ".$tournamentId;
        $tournamentSystemId = $this->getArraySQL($tournamentSystemIdSql);
        $systemPoints = "SELECT umpirePoints, goldPoints, silverPoints, bronzePoints, ironPoints FROM SYSTEM WHERE idSYSTEM LIKE ". $tournamentSystemId[0]["SYSTEM_idSYSTEM"];
        return $this->getArraySQL($systemPoints);
    }

}