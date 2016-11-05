<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 30/10/16
 * Time: 19:37
 */
include_once "IQuery.php";

abstract class Query implements IQuery {

    protected $queryResult;
    public $aux = "hola";

    /**
     * Executes a Mysql query and returns an array containing the query results
     *
     * @param $sql query to be executed
     * @return array
     */
    protected function getArraySQL($sql) {
        if(!$this->queryResult = $this->connection->query($sql)) die();


        $queryArray = array();

        $i = 0;

        while($row = mysqli_fetch_array($this->queryResult)) {
            $queryArray[$i] = $row;
            $i++;
        }

        $queryArray = $this::convertArrayUtf8($queryArray);

        $croppedArray = array();


        $i = 0;
        foreach ($queryArray as $entry) {

            foreach ($entry as $key => $value) {
                if (!is_numeric($key)) {
                     $croppedArray[$i][$key] = $value;

                }
            }
            $i++;
        }

        return $croppedArray;

    }

    /**
     * Builds a SQL query with the input data
     *
     * @param $tableName
     * @param array $fields
     * @param array $filterFields
     * @param array $filterArguments
     * @return string
     */
    public function buildQuery($tableName, array $fields, array $filterFields, array $filterArguments) {
        $sql = "SELECT ";
        $arrayLength = count($fields);
        $i = 0;
        while($i < ($arrayLength)) {
            $sql = $sql.$fields[$i];
            if ($i < ($arrayLength - 1)) {
                $sql = $sql.", ";
            }
            $i++;
        }
        $sql = $sql. " FROM ".$tableName;

        if ($filterFields != null) {

            $sql = $sql. " WHERE ";
            $arrayLength = count($filterFields);
            $i = 0;
            while($i < ($arrayLength)) {
                $sql = $sql.$filterFields[$i]." LIKE ".$filterArguments[$i];
                if ($i < ($arrayLength - 1)) {
                    $sql = $sql.", ";
                }
                $i++;
            }
        }


        return $sql;

    }

    /**
     * Converts an array resulting from a SQL query to UTF-8 format
     *
     * @param $array
     * @return mixed
     */
    public static function convertArrayUtf8($array) {
        array_walk_recursive($array, function(&$value, $key) {
            if (is_string($value)) {
                $value = iconv('windows-1252', 'utf-8', $value);
            }
        });

        return $array;
    }

    /**
     *
     *
     * @param $tournaments
     * @param $tournamentPosition
     * @param $arrayToAdd
     * @param $newArrayName
     * @return array
     */
    public static function mergeArrays($tournaments, $tournamentPosition, $arrayToAdd, $newArrayName) {
        $tournament = $tournaments[$tournamentPosition];
        $tournamentPart1 = array_slice($tournament, 0, 1);
        $tournamentPart2 = array_slice($tournament, 1, count($tournament));
        $tournament = array_merge($tournamentPart1, array($arrayToAdd), $tournamentPart2);
        $tournament[$newArrayName] = $tournament[0];
        unset($tournament[0]);

        return $tournament;
    }

    protected function getUserTotalPoints($userId) {
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

    protected function calculatePointsBySystem($userRank, array $tournamentSystem) {
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

    protected function getGameSystem($tournamentId) {
        $tournamentSystemIdSql = "SELECT SYSTEM_idSYSTEM FROM TOURNAMENT WHERE idTOURNAMENT LIKE ".$tournamentId;
        $tournamentSystemId = $this->getArraySQL($tournamentSystemIdSql);
        $systemPoints = "SELECT umpirePoints, goldPoints, silverPoints, bronzePoints, ironPoints FROM SYSTEM WHERE idSYSTEM LIKE ". $tournamentSystemId[0]["SYSTEM_idSYSTEM"];
        return $this->getArraySQL($systemPoints);
    }


}