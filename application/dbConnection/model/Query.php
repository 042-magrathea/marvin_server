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
    protected $connection;


    //----------------------------------------------------------------------------------------------------------------//

    //                                                COMMON QUERIES                                                  //

    //----------------------------------------------------------------------------------------------------------------//

    /**
     * Returns the game system used in a tournament
     *
     * @param $tournamentId the tournaments Id
     * @return array contains the game system information
     */
    protected function getGameSystem($tournamentId) {
        $tournamentSystemIdSql = "SELECT SYSTEM_idSYSTEM FROM TOURNAMENT WHERE idTOURNAMENT LIKE ".$tournamentId;
        $tournamentSystemId = $this->getResultArray($tournamentSystemIdSql);
        $systemPoints = "SELECT umpirePoints, goldPoints, silverPoints, bronzePoints, ironPoints FROM SYSTEM WHERE idSYSTEM LIKE ". $tournamentSystemId[0]["SYSTEM_idSYSTEM"];
        return $this->getResultArray($systemPoints);
    }


    /**
     * Calculates an user total earned points in all played tournaments and the played tournaments number
     *
     * @param $userId id from user
     * @return array array containing the total played tournaments and the total earned points
     */
    protected function getUserTotalPoints($userId) {
        $userTotalPoints = 0;

        /////////////////////////////////
        //check single user tournaments//
        /////////////////////////////////

        $tournamentsIdsSql = "SELECT TOURNAMENT_idTOURNAMENT, rank FROM TOURNAMENT_has_USER WHERE USER_idUSER LIKE ".$userId;

        $tournamentsIds = $this->getResultArray($tournamentsIdsSql);
        for ($i = 0; $i < count($tournamentsIds); $i++) {


            $isUmpireSql = "SELECT approved FROM UMPIRE WHERE USER_idUSER LIKE ".$userId." AND TOURNAMENT_idTOURNAMENT LIKE ".$tournamentsIds[$i]["TOURNAMENT_idTOURNAMENT"];
            $isUmpire = $this->getResultArray($isUmpireSql);

            if (isset($isUmpire[0]) && $isUmpire[0]["approved"] != null && $isUmpire[0]["approved"] == 1) {
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
        $teamIds = $this->getResultArray($teamIdsSQL);
        $tournamentsIdsSql = "SELECT TOURNAMENT_idTOURNAMENT, rank FROM TOURNAMENT_has_TEAM WHERE TEAM_idTEAM LIKE ".$teamIds[0]["TEAM_idTEAM"];
        $tournamentsIds = $this->getResultArray($tournamentsIdsSql);


        for ($j = 0; $j < count($teamIds); $j++) {

            $tournamentSystemIdSql = "SELECT SYSTEM_idSYSTEM FROM TOURNAMENT WHERE idTOURNAMENT LIKE ".$tournamentsIds[$j]["TOURNAMENT_idTOURNAMENT"];
            $tournamentSystemId = $this->getResultArray($tournamentSystemIdSql);
            $systemPoints = "SELECT umpirePoints, goldPoints, silverPoints, bronzePoints, ironPoints FROM SYSTEM WHERE idSYSTEM LIKE ". $tournamentSystemId[0]["SYSTEM_idSYSTEM"];
            $tournamentSystem = $this->getResultArray($systemPoints);
            $teamRank = $tournamentsIds[$j]["rank"];
            $userTotalPoints = $userTotalPoints + $this->calculatePointsBySystem($teamRank, $tournamentSystem);

        }*/

        return array(count($tournamentsIds), $userTotalPoints);
    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                             END OF COMMON QUERIES                                              //

    //----------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------//

    //                                       COMMON QUERIES AUXILIAR METHODS                                          //

    //----------------------------------------------------------------------------------------------------------------//
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

    //----------------------------------------------------------------------------------------------------------------//

    //                                    END OF COMMON QUERIES AUXILIAR METHODS                                      //

    //----------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------//

    //                                           SQL STATEMENT BUILDERS                                               //

    //----------------------------------------------------------------------------------------------------------------//

    /**
     * Builds an SQL query statement
     *
     * @param $tableName table to query at
     * @param $fields array containing all the fields to be retruned by the statement
     * @param $filterFields array containing all the fields used in the "WHERE" clause of the statement.
     * @param $filterArguments array containing all the values used in the "WHERE" clause of the statement
     * @return string SQL statement
     */
    protected function buildQuerySql($tableName, array $fields, array $filterFields, array $filterArguments) {
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
                $sql = $sql.$filterFields[$i]." LIKE '".$filterArguments[$i];
                if ($i < ($arrayLength - 1)) {
                    $sql = $sql.", ";
                } else {
                    $sql = $sql."'";
                }
                $i++;
            }
        }

        /*if (is_array($fields)) {
            $arrayLength = count($fields);
            $i = 0;
            while($i < ($arrayLength)) {
                $sql = $sql.$fields[$i];
                if ($i < ($arrayLength - 1)) {
                    $sql = $sql.", ";
                }
                $i++;
            }
        } else {
            $sql = $sql . $fields;
        }

        $sql = $sql. " FROM ".$tableName;

        if (is_array($filterFields)) {
            if ($filterFields != null) {

                $sql = $sql. " WHERE ";
                $arrayLength = count($filterFields);
                $i = 0;
                while($i < ($arrayLength)) {
                    $sql = $sql.$filterFields[$i]." LIKE '".$filterArguments[$i];
                    if ($i < ($arrayLength - 1)) {
                        $sql = $sql.", ";
                    } else {
                        $sql = $sql."'";
                    }
                    $i++;
                }
            }
        } else {
            $sql = $sql . " WHERE " . $filterFields . " LIKE " . $filterArguments;
        }*/

        return $sql;
    }

    /**
     * Build an SQL statement for insert a new row into a table. The statement structure will be:
     * INSERT INTO <$tableName> ($fields[0], ... $fields[n]) VALUES ($values[0], ... $values[n])
     *
     * @param $tableName string with the table name to be accessed
     * @param $fields array containing all the fields to be INSERTED by the staement
     * @param $values array containing all the new values for the fields specified at $fields
     * @return string the SQL insertion statement
     */
    protected function buildInsertSql($tableName, array $fields, array $values) {

        $insertionArrays = $this->reformatUserRoleValue($fields, $values);

        $fields = $insertionArrays[0];
        $values = $insertionArrays[1];

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

        if ($values != null) {
            $arrayLength = count($values);
            $i = 0;
            while($i < ($arrayLength)) {
                $sql = $sql.$this->formatBooleanValue($values[$i]);

                if ($i < ($arrayLength - 1)) {
                    $sql = $sql.", ";
                }
                $i++;
            }

        }
        $sql = $sql." )";
        return $sql;
    }

    /**
     * Build an SQL statement for updating data of a table row. The statement structure will be:
     *
     * UPDATE <$tableName> SET $fields[0]=$values[0], ... $fields[n]=$values[n]
     * WHERE $filterFields[0] LIKE $filterArguments[0] AND ... WHERE $filterFields[n] LIKE $filterArguments[n]
     *
     * @param $tableName string with the table name to be accessed
     * @param $filterFields array containing all the fields used in the "WHERE" clause of the statement.
     * @param $filterArguments  array containing all the values used in the "WHERE" clause of the statement
     * @param $fields array containing all the fields to be modified by the staement
     * @param $values array containing all the new values for the fields specified at $fields
     * @return string the SQL update statement
     */
    protected function buildUpdateSql($tableName, array $fields, array $values, array $filterFields, array $filterArguments) {

        //just useful for user updates
        $insertionArrays = $this->reformatUserRoleValue($fields, $values);
        $fields = $insertionArrays[0];
        $values = $insertionArrays[1];

        $sql = "UPDATE " . $tableName . " SET ";

        $arrayLength = count($fields);
        $i = 0;
        while ($i < $arrayLength) {
            $sql = $sql . $fields[$i] . "=";

            $values[$i] = $this->formatBooleanValue($values[$i]);

            $sql = $sql.$values[$i];

            if ($i < ($arrayLength - 1)) {
                $sql = $sql.", ";
            }
            $i++;

        }

        $sql = $sql . " WHERE ";
        $arrayLength = count($filterFields);
        $i = 0;
        while ($i < $arrayLength) {

            $filterArguments[$i] = $this->formatBooleanValue($filterArguments[$i]);

            if(is_array($filterFields)) {

                $sql = $sql . $filterFields[$i] . " LIKE " . $filterArguments[$i];

            } else {
                $sql = $sql . $filterFields . " LIKE " . $filterArguments[$i];
            }
            $i++;
        }

        /*if (is_array($values)) {
            $arrayLength = count($fields);
            $i = 0;
            while ($i < $arrayLength) {
                $sql = $sql . $fields[$i] . "=";

                $values[$i] = $this->formatBooleanValue($values[$i]);

                $sql = $sql.$values[$i];

                if ($i < ($arrayLength - 1)) {
                    $sql = $sql.", ";
                }
                $i++;

            }
        } else {
            $sql = $sql . $fields . "=";

            $values = $this->formatBooleanValue($values);

            $sql = $sql.$values;
        }

        if (is_array($filterArguments)) {

            $sql = $sql . " WHERE ";
            $arrayLength = count($filterFields);
            $i = 0;
            while ($i < $arrayLength) {

                $filterArguments[$i] = $this->formatBooleanValue($filterArguments[$i]);

                if(is_array($filterFields)) {

                    $sql = $sql . $filterFields[$i] . " LIKE " . $filterArguments[$i];

                } else {
                    $sql = $sql . $filterFields . " LIKE " . $filterArguments[$i];
                }
                $i++;
            }

        } else {
            $filterArguments = $this->formatBooleanValue($filterArguments);

            $sql = $sql . " WHERE " . $filterFields . " LIKE " . $filterArguments;
        }*/

        return $sql;
    }

    /**
     * Builds an insertion SQL statement with the given parameters. The statement structure will be:
     * DELETE FROM <$tableName> WHERE $filterFields[0] LIKE $filterArguments[0] AND ... WHERE $filterFields[n] LIKE $filterArguments[n]
     *
     * @param $tableName string with the table name to be accessed
     * @param $filterFields array containing all the fields used in the "WHERE" clause of the statement.
     * @param $filterArguments array containing all the values used in the "WHERE" clause of the statement
     * @return string the SQL deletion statement
     */
    protected function buildDeletionSql($tableName, array $filterFields, array $filterArguments) {

        $sql = "DELETE FROM " . $tableName . " WHERE ";

        $arrayLength = count($filterFields);
        $i = 0;
        while ($i < $arrayLength) {
            $sql = $sql . $filterFields[$i] . "=";

            $filterArguments[$i] = $this->formatBooleanValue($filterArguments[$i]);

            $sql = $sql . $filterArguments[$i];

            if ($i < ($arrayLength - 1)) {
                $sql = $sql . " AND ";
            }
            $i++;

        }

        /*if ( is_array($filterFields) ) {
            $arrayLength = count($filterFields);
            $i = 0;
            while ($i < $arrayLength) {
                $sql = $sql . $filterFields[$i] . "=";

                $filterArguments[$i] = $this->formatBooleanValue($filterArguments[$i]);

                $sql = $sql . $filterArguments[$i];

                if ($i < ($arrayLength - 1)) {
                    $sql = $sql . " AND ";
                }
                $i++;

            }
        } else {
            $sql = $sql . $filterFields . "=" ;

            $filterArguments = $this->formatBooleanValue($filterArguments);

            $sql = $sql . $filterArguments;
        }*/

        $sql = $sql . ";";

        return $sql;
    }

    /**
     * Checks if a value is a boolean and if it's not, refformats string to be inserted into a SQL statement
     *
     * @param $value value to be checked
     * @return string $value refomatted
     */
    private function formatBooleanValue($value) {
        if (is_bool($value)) {
            return $value;
        } else {
            return "'".$value."'";
        }
    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                       END OF SQL STATEMENT BUILDERS                                            //

    //----------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------//

    //                                     STATEMENT BUILDERS AUXILIAR METHODS                                        //

    //----------------------------------------------------------------------------------------------------------------//
    /**
     * Converts the value containing user kind (regular, admin or editor) to a DB friendly format, this is an auxiliar
     * function used in user insertions
     *
     * @param array $fields fields to be inserted in the user DB entry
     * @param array $values values to be inserted in the users DB entry
     * @return array contains the two reformatted arrays into a multidimensional array
     */
    private function reformatUserRoleValue($fields, $values) {
        if (is_array($fields)) {
            $pos = array_search("userRole", $fields);

            if ($values[$pos] == "administrator") {
                $fields[$pos] = "administrator";
                $values[$pos] = "true";
                array_push($fields, "editor");
                array_push($values, "false");


            } else if ($values[$pos] == "editor") {
                $fields[$pos] = "editor";
                $values[$pos] = "true";
                array_push($fields, "administrator");
                array_push($values, "false");

            } else if ($values[$pos] == "user") {
                $fields[$pos] = "administrator";
                $values[$pos] = "false";
                array_push($fields, "editor");
                array_push($values, "false");
            }

        } else {
            if ($fields == "userRole") {
                $fields = array();
                $values = array();
                if ($values == "administrator") {
                    array_push($fields, "administrator");
                    array_push($values, "true");
                    array_push($fields, "editor");
                    array_push($values, "false");


                } else if ($values == "editor") {
                    array_push($fields, "editor");
                    array_push($values, "true");
                    array_push($fields, "administrator");
                    array_push($values, "false");

                } else if ($values == "user") {
                    array_push($fields, "administrator");
                    array_push($values, "false");
                    array_push($fields, "editor");
                    array_push($values, "false");
                }
            }
        }
        return array($fields, $values);
    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                  END OF STATEMENT BUILDERS AUXILIAR METHODS                                    //

    //----------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------//

    //                                           COMMON AUXILIAR METHODS                                              //

    //----------------------------------------------------------------------------------------------------------------//
    /**
     * Executes a Mysql query and returns an array containing the query results
     *
     * @param $sql query to be executed
     * @return array results of the query
     */
    protected function getResultArray($sql) {

        if(!$this->queryResult = $this->connection->query($sql)) die();

        $resultArray = array();

        //write results to resultArray
        $i = 0;
        while($row = mysqli_fetch_array($this->queryResult)) {
            $resultArray[$i] = $row;
            $i++;
        }

        //reformat array
        $formattedArray = array();
        $i = 0;
        foreach ($resultArray as $entry) {

            foreach ($entry as $key => $value) {
                if (!is_numeric($key)) {
                    $formattedArray[$i][$key] = $value;

                }
            }
            $i++;
        }

        return $formattedArray;

    }

    /**
     * Merge two arrays, inserting a new array in the quiven position and assigns it the guiven key name
     *
     * @param $originalArray the original array
     * @param $insertionPosition the insertion position of the new array
     * @param $arrayToAdd the array to insert
     * @param $newArrayKey the key name of the new array inside the original array
     * @return array the brand new array
     */
    protected static function mergeArrays($originalArray, $insertionPosition, $arrayToAdd, $newArrayKey) {
        $childArray = $originalArray[$insertionPosition];
        $childArraySlice1 = array_slice($childArray, 0, 1);
        $childArraySlice2 = array_slice($childArray, 1, count($childArray));
        $tournament = array_merge($childArraySlice1, array($arrayToAdd), $childArraySlice2);
        $tournament[$newArrayKey] = $tournament[0];
        unset($tournament[0]);

        return $tournament;
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
    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                        END OF COMMON AUXILIAR METHODS                                          //

    //----------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------------------------------------------------------------------//

    /**
     * Converts an array resulting from a SQL query to UTF-8 format
     *
     * @param $array a String array
     * @return array modified array
     */
/*    public static function convertArrayUtf8($array) {
        array_walk_recursive($array, function(&$value) {
            if (is_string($value)) {
                $value = iconv('windows-1252', 'utf-8', $value);
            }
        });

        return $array;
    }*/

    /**
     * Get id from the item in the specified table
     *
     * @param $table table to search in
     * @param $field field to search for
     * @param $value value that has to match
     * @return array contains the item Id
     */
    /*protected function getItemId($table, $field, $value) {
        $sql = "SELECT idUSER FROM ".$table." WHERE ".$field." LIKE '".$value."'";
        $result = $this->getResultArray($sql);
        return $result;
    }*/









}