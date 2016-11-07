<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 30/10/16
 * Time: 19:56
 */
include_once "DB_adapter.php";
include_once "Query.php";

class UserQuery extends Query {

    private $adapter;
    protected $connection;

    /**
     * UserQuery constructor.
     */
    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();

    }

    //---------------------------------------------------------------------------------------------------------------//

    //////////////////////////
    // USED BY THE PROTOTYPE//
    //////////////////////////

    /**
     * Builds an array with all required data for parsing an user at any client
     *
     * @return array
     */
    public function getParseEntries() {
        //build the query statement
        $sql = "SELECT idUser, publicName, password, eMail, administrator FROM USER;";

        //execute query
        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    //---------------------------------------------------------------------------------------------------------------//

    //////////////////////////////
    // NOT USED BY THE PROTOTYPE//
    //////////////////////////////

    /**
     * Builds an array with all required data for parsing the user stored in databasethat matches with the given id at
     * any client
     *
     * @param $itemId id from the item to search in database
     * @return array
     */
    public function getParseEntry($itemId) {
        //build the query statement
        $sql = "SELECT idUser, publicName, password, eMail, administrator FROM USER WHERE idUser LIKE ".$itemId;

        //execute query
        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get all entries from the 'USER' table in database that matches all parameters specified, this method has to be used
     * to do execute requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments) {

        $sql = $this->buildQuery('USER', $fields, $filterFields, $filterArguments);

        $result = $this->getArraySQL($sql, $this->connection);

        $this->adapter->closeConnection();

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
        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

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
    public function insertItem(array $fields, array $values) {
        //build the insert statement
        $sql = $this->buildInsert('USER', $fields, $values);

        //executes query
        $result = $this->connection->query($sql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $id = mysqli_insert_id($this->connection);

        $this->adapter->closeConnection();

        //converts the array to JSON friendly format
        $rawData = getJsonFriendlyArray("insertionId",$id);

        return $rawData;
    }


    /**
     * Get the idUSER field for the entry that matches the filter parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getIdValue(array $filterFields, array $filterArguments) {
        //build the query statement
        $sql = $this->buildQuery('USER', array("idUser"), $filterFields, $filterArguments);

        //execute query
        $result = $this->getArraySQL($sql, $this->connection);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Check if the pair $userPublicName $userPassword, matches with any item existing in the USER table.
     *
     * @param $userPublicName
     * @param $userPassword
     * @return bool
     */
    public function checkLogIn($userPublicName, $userPassword) {
        //build the query statement
        $sql = "SELECT password FROM USER WHERE publicName LIKE ".$userPublicName;

        //execute query
        $result = $this->getArraySQL($sql, $this->connection);

        $this->adapter->closeConnection();

        if ($result[0]["password"] == $userPassword) {
            //creates a JSON friendly array
            $rawData = getJsonFriendlyArray("loginResult",true);
        } else {
            //creates a JSON friendly array
            $rawData = getJsonFriendlyArray("loginResult",false);
        }
        return $rawData;
    }
}