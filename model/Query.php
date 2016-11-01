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

/*                    $index = array_search($key, $entry);
                    echo "index: ".$key;
                    $queryArray = array_splice($queryArray, $key, 1);*/
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
     * @param array $filtersFields
     * @param array $filtersArguments
     * @return string
     */
    public function buildQuery($tableName, array $fields, array $filtersFields, array $filtersArguments) {
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
        $sql = $sql. " FROM ".$tableName." WHERE ";
        $arrayLength = count($filtersFields);
        $i = 0;
        while($i < ($arrayLength)) {
            $sql = $sql.$filtersFields[$i]." LIKE ".$filtersArguments[$i];
            if ($i < ($arrayLength - 1)) {
                $sql = $sql.", ";
            }
            $i++;
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


}