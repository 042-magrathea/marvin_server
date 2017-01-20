<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 30/10/16
 * Time: 19:56
 */
include_once "application/dbConnection/adapter/DB_adapter.php";
include_once "Query.php";

/**
 * Class User_Model
 */
class User_Model extends Query {


    /**
     * User_Model constructor.
     */
    public function __construct($connection) {
        $this->connection = $connection;

    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                              COMMON METHODS                                                    //

    //----------------------------------------------------------------------------------------------------------------//


    /**
     * Get all entries from the 'USER' table in database that matches all parameters specified, this method has to be used
     * to do execute requests to the specified table
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
     * Get all fields from all entries of the table USER from the database
     *
     * @return array
     */
    public function getAllEntries() {
        //build the query statement
        $sql = "SELECT * FROM USER;";

        //excute query
        $result = $this->getResultArray($sql);

        

        return $result;
    }

    /**
     * Builds an array with all required data for parsing all users od USER table
     *
     * @return array
     */
    public function getParseEntries() {
        //build the query statement
        $sql = "SELECT idUSER, publicName, name, phone, eMail, administrator, editor, language, privateDes, publicDes FROM USER WHERE eMail IS NOT NULL;";

        //execute query
        $result = $this->getResultArray($sql);

        for($i = 0; $i<count($result); $i++) {
            if(array_key_exists("editor", $result[$i])) {
                $result[$i]["editor"] = (boolean) $result[$i]["editor"];

            }
            if (array_key_exists("administrator", $result[$i])) {
                $result[$i]["administrator"] = (boolean) $result[$i]["administrator"];
            }

        }
        
        return $result;
    }

    /**
     * Builds an array with all required data for parsing the user stored in databaset hat matches with the given id at
     * any client
     *
     * @param $itemId id from the item to search in database
     * @return array
     */
    public function getParseEntry($itemId) {
        //build the query statement
        $sql = "SELECT idUSER, publicName, name, phone, eMail, administrator, editor, language, privateDes, publicDes FROM USER WHERE idUser LIKE '".$itemId . "'";

        //execute query
        $result = $this->getResultArray($sql);

        for($i = 0; $i<count($result); $i++) {
            if(array_key_exists("editor", $result[$i])) {
                $result[$i]["editor"] = (boolean) $result[$i]["editor"];

            }
            if (array_key_exists("administrator", $result[$i])) {
                $result[$i]["administrator"] = (boolean) $result[$i]["administrator"];
            }

        }

        

        return $result;
    }

    /**
     * Get the idUSER field for the entry that matches the filter parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getIdValue($filterFields, $filterArguments) {
        //build the query statement
        $sql = $this->buildQuerySql('USER', array("idUSER"), $filterFields, $filterArguments);

        //execute query
        $result = $this->getResultArray($sql);

        

        return $result;
    }

    /**
     * Insert al specified fields of an item with the specified values into the table USER
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
    public function insertItem($fields, $values) {
        //build the insert statement
        $sql = $this->buildInsertSql('USER', $fields, $values);

        //executes query
        $this->connection->query($sql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $id = mysqli_insert_id($this->connection);

        

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionId",$id);

        return $rawData;
    }

    /**
     * Modify al specified fields of an user with the specified values into the USER table
     *
     * @param $itemId string with the user's id
     * @param $fields string or string array must contain all fields to be modified in the new entry
     * @param $values string or string array must contain the values of the fields to be modified, the value position
     * must match the position of the corresponding field at $fields array
     * @return mixed
     */
    public function modifyItem($itemId, $fields, $values) {
        //build query statement
        $sql = $this->buildUpdateSql('USER', $fields, $values, array('idUSER'), array($itemId));

        //execute query
        if(!$this->connection->query($sql)) die();

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $affectedRows = $this->connection->affected_rows;

        

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("modifiedRowsNum",$affectedRows);

        return $rawData;
    }

    /**
     * Deletes the id given item from the USER table, returns number of rows deleted from the table
     *
     * @param $itemId id from the item to be deleted
     * @return mixed number of rows deleted
     */
    public function deleteItem($itemId) {
        //build query statement
        $sql = "UPDATE USER SET name=null, phone=null, eMail=null, ads=null, privateDes=null, publicDes=null, " .
            "administrator=null, editor=null, language=null, datePassword=null, password=null, markedForDeletion=null, " .
            "clearRequestData=null, memberSince=null WHERE idUSER =" . $itemId;
        //execute query
        if(!$this->connection->query($sql)) die();

        $affectedRows = $this->connection->affected_rows;

        

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("deletedRowsNum",$affectedRows);

        return $rawData;
    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                            END OF COMMON METHODS                                               //

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //                                              USER QUERY METHODS                                                //

    //----------------------------------------------------------------------------------------------------------------//

    /**
     * Check if the pair $userPublicName $userPassword, matches with any item existing in the USER table.
     *
     * @param $userPublicName
     * @param $userPassword
     * @return bool
     */
    public function checkLogIn($userPublicName, $userPassword) {
        //build the query statement
        $sql = "SELECT password, administrator, editor FROM USER WHERE publicName LIKE '".$userPublicName . "'";


        //execute query
        $result = $this->getResultArray($sql);

        

        if (count($result) > 0) {
            if ( $result[0]["password"] == $userPassword ) {
                if ($result[0]["administrator"] == 1) {
                    //creates a JSON friendly array
                    $rawData = $this->getJsonFriendlyArray("loginResult",3);
                } else if (array_key_exists("editor", $result[0]) && $result[0]["editor"] == 1) {

                    //creates a JSON friendly array
                    $rawData = $this->getJsonFriendlyArray("loginResult",2);
                } else {
                    //creates a JSON friendly array
                    $rawData = $this->getJsonFriendlyArray("loginResult",1);
                }

            } else {
                //creates a JSON friendly array
                $rawData = $this->getJsonFriendlyArray("loginResult",0);
            }
        } else {
            //creates a JSON friendly array
            $rawData = $this->getJsonFriendlyArray("loginResult",0);
        }

        return $rawData;
    }

    public function valueExists($field, $value) {

        //build the query statement
        $sql = "SELECT EXISTS(SELECT idUser FROM USER WHERE BINARY " . $field . " LIKE '" . $value . "') AS result";

        //execute query
        $result = $this->getResultArray($sql);

        $result[0]["result"] = (boolean)intval($result[0]["result"]);

        return $result;
    }



}