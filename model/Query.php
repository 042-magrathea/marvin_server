<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 30/10/16
 * Time: 19:37
 */
include_once "IQuery.php";

/**
 * Class Query
 */
abstract class Query implements IQuery {

    protected $queryResult;
<<<<<<< HEAD
    protected $connection;
=======
    public $aux = "hola";
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0

    /**
     * Executes a Mysql query and returns an array containing the query results
     *
     * @param $sql query to be executed
     * @return array results of the query
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
<<<<<<< HEAD

            foreach ($entry as $key => $value) {
                if (!is_numeric($key)) {
                     $croppedArray[$i][$key] = $value;

                }
            }
            $i++;
        }

=======

            foreach ($entry as $key => $value) {
                if (!is_numeric($key)) {
                     $croppedArray[$i][$key] = $value;

                }
            }
            $i++;
        }

>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
        return $croppedArray;

    }

    /**
     * Builds a SQL query with the input data
     *
<<<<<<< HEAD
     * @param array $fields fields to be inserted
     * @param array $filterFields fields to be included in WHERE clause of the query
     * @param array $filterArguments values that has to match the searched item/s
     * @return string SQL statement
     */
    protected function buildQuery($tableName, array $fields, array $filterFields, array $filterArguments) {
=======
     * @param $tableName
     * @param array $fields
     * @param array $filterFields
     * @param array $filterArguments
     * @return string
     */
    public function buildQuery($tableName, array $fields, array $filterFields, array $filterArguments) {
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
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
<<<<<<< HEAD
                $sql = $sql.$filterFields[$i]." LIKE '".$filterArguments[$i];
                if ($i < ($arrayLength - 1)) {
                    $sql = $sql.", ";
                } else {
                    $sql = $sql."'";
=======
                $sql = $sql.$filterFields[$i]." LIKE ".$filterArguments[$i];
                if ($i < ($arrayLength - 1)) {
                    $sql = $sql.", ";
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
                }
                $i++;
            }
        }
        return $sql;
    }

<<<<<<< HEAD
    /**
     * Builda an insertion SQL statement
     *
     * @param $tableName table to insert the item in
     * @param array $fields fields to be inserted
     * @param array $values values to be inserted in specified fields
     * @return string SQL statement
     */
=======
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
    public function buildInsert($tableName, array $fields, array $values) {

        $arrays = $this->reformatUserRoleValue($fields, $values);

        $fields = $arrays[0];
        $values = $arrays[1];

        $sql = "INSERT INTO ".$tableName." (";
        $arrayLength = count($fields);
        $i = 0;
        while($i < ($arrayLength)) {
            $sql = $sql.$fields[$i];
            if ($i < ($arrayLength - 1)) {
                $sql = $sql.", ";
            }
            $i++;
        }
        $sql = $sql. ") VALUES (";
<<<<<<< HEAD

        if ($values != null) {

=======

        if ($values != null) {

>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
            $arrayLength = count($values);
            $i = 0;
            while($i < ($arrayLength)) {
                if ($values[$i] == "true" || $values[$i] == "false") {
                    $sql = $sql.$values[$i];

                } else {
                    $sql = $sql."'".$values[$i]."'";
                }

                if ($i < ($arrayLength - 1)) {
                    $sql = $sql.", ";
                }
                $i++;
            }
        }
        $sql = $sql." )";
        return $sql;
    }

<<<<<<< HEAD
    /**
     * Get id from the item in the specified table
     *
     * @param $table table to search in
     * @param $field field to search for
     * @param $value value that has to match
     * @return array contains the item Id
     */
=======
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
    protected function getItemId($table, $field, $value) {
        $sql = "SELECT idUSER FROM ".$table." WHERE ".$field." LIKE '".$value."'";
        $result = $this->getArraySQL($sql);
        return $result;
    }

<<<<<<< HEAD
    /**
     * Converts the value contining user kind (regular, admin or editor) to a DB friendly format, this is an auxiliar
     * function used in user insertions
     *
     * @param array $fields fields to be inserted in the user DB entry
     * @param array $values values to be inserted in the users DB entry
     * @return array contains the two reformatted arrays into a multidimensional array
     */
=======
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
    private function reformatUserRoleValue(array $fields, array $values) {
        $pos = array_search("userRole", $fields);

        if ($values[$pos] == "administrator") {
            $fields[$pos] = "administrator";
            $values[$pos] = "true";
            array_push($fields, "editor");
            array_push($values, "false");

<<<<<<< HEAD

=======
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
        } else if ($values[$pos] == "editor") {
            $fields[$pos] = "editor";
            $values[$pos] = "true";
            array_push($fields, "administrator");
            array_push($values, "false");

        } else if ($values[$pos] == "user") {
<<<<<<< HEAD
            $fields[$pos] = "administrator";
            $values[$pos] = "false";
=======
            unset($fields[$pos]);
            unset($values[$pos]);
            array_push($fields, "administrator");
            array_push($values, "false");
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
            array_push($fields, "editor");
            array_push($values, "false");
        }

        return array($fields, $values);

    }

    /**
     * Converts an array resulting from a SQL query to UTF-8 format
     *
<<<<<<< HEAD
     * @param $array a String array
     * @return array modified array
=======
     * @param $array
     * @return mixed
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
     */
    public static function convertArrayUtf8($array) {
        array_walk_recursive($array, function(&$value) {
            if (is_string($value)) {
                $value = iconv('windows-1252', 'utf-8', $value);
            }
        });

        return $array;
    }

    /**
<<<<<<< HEAD
     * Merge two arrays, inserting a new array in the quiven position and assigns it the guiven key name
     *
     * @param $originalArray the original array
     * @param $insertionPosition the insertion position of the new array
     * @param $arrayToAdd the array to insert
     * @param $newArrayKey the key name of the new array inside the original array
     * @return array the brand new array
     */
    public static function mergeArrays($originalArray, $insertionPosition, $arrayToAdd, $newArrayKey) {
        $childArray = $originalArray[$insertionPosition];
        $childArraySlice1 = array_slice($childArray, 0, 1);
        $childArraySlice2 = array_slice($childArray, 1, count($childArray));
        $tournament = array_merge($childArraySlice1, array($arrayToAdd), $childArraySlice2);
        $tournament[$newArrayKey] = $tournament[0];
=======
     * Merge two array
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
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
        unset($tournament[0]);

        return $tournament;
    }

    /**
<<<<<<< HEAD
     * Calculates an user total earned points in all played tournaments and the played tournaments number
     *
     * @param $userId id from user
     * @return array array containing the total played tournaments and the total earned points
=======
     *
     * @param $userId
     * @return array
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
     */
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
            //stores th system used in tournament
            $tournamentSystem = $this->getGameSystem($tournamentsIds[$i]["TOURNAMENT_idTOURNAMENT"]);
            //calculates earned points in this tournament
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
<<<<<<< HEAD

        }*/

        return array(count($tournamentsIds), $userTotalPoints);
    }

    /**
     * Calculate earned points by an user rank in a tournament with an specific tournamentSystem
     *
     * @param $userRank rank of the user at the tournament
     * @param array $tournamentSystem an array containing the points for any position of the tournament
     * @return int earned points by the user
     */
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

    /**
     * Returns the game system used in a tournament
     *
     * @param $tournamentId the tournaments Id
     * @return array contains the game system information
     */
    protected function getGameSystem($tournamentId) {
        $tournamentSystemIdSql = "SELECT SYSTEM_idSYSTEM FROM TOURNAMENT WHERE idTOURNAMENT LIKE ".$tournamentId;
        $tournamentSystemId = $this->getArraySQL($tournamentSystemIdSql);
        $systemPoints = "SELECT umpirePoints, goldPoints, silverPoints, bronzePoints, ironPoints FROM SYSTEM WHERE idSYSTEM LIKE ". $tournamentSystemId[0]["SYSTEM_idSYSTEM"];
        return $this->getArraySQL($systemPoints);
    }

   /**
     * Converts any individual value to a JSON friendly array
     *
     * @param $key
     * @param $value
     * @return array
     */
   protected function getJsonFriendlyArray($key, $value) {
         //creates a JSON friendly array
         $rawData = array(array($key => $value));

         return $rawData;
=======

        }*/

        return array(count($tournamentsIds), $userTotalPoints);
    }

    /**
     * Calculate earned points by an user rank in a tournament with an specific tournamentSystem
     *
     * @param $userRank rank of the user at the tournament
     * @param array $tournamentSystem an array containing the points for any position of the tournament
     * @return int earned points by the user
     */
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

    /**
     * Returns the game system used in a tournament
     *
     * @param $tournamentId
     * @return array
     */
    protected function getGameSystem($tournamentId) {
        $tournamentSystemIdSql = "SELECT SYSTEM_idSYSTEM FROM TOURNAMENT WHERE idTOURNAMENT LIKE ".$tournamentId;
        $tournamentSystemId = $this->getArraySQL($tournamentSystemIdSql);
        $systemPoints = "SELECT umpirePoints, goldPoints, silverPoints, bronzePoints, ironPoints FROM SYSTEM WHERE idSYSTEM LIKE ". $tournamentSystemId[0]["SYSTEM_idSYSTEM"];
        return $this->getArraySQL($systemPoints);
    }

    /**
     * Converts any individual value to a JSON friendly array
     *
     * @param $key
     * @param $value
     * @return array
     */
    protected function getJsonFriendlyArray($key, $value) {
        //creates a JSON friendly array
        $rawData = array(array($key => false));

        return $rawData;
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
    }
}