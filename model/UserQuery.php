<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 30/10/16
 * Time: 19:56
 */
include_once "DB_adapter.php";
include_once "Query.php";

class UserQuery extends Query {

    private $adapter;
    protected $connection;

    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();

    }


    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments) {

        $sql = $this->buildQuery('USER', $fields, $filterFields, $filterArguments);

        $result = $this->getArraySQL($sql, $this->connection);

        $this->adapter->closeConnection();

        return $result;
    }

    public function getParseEntries() {
        $sql = "SELECT idUser, publicName, password, eMail, administrator FROM USER;";

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    public function getParseEntry($itemId) {
        $sql = "SELECT idUser, publicName, password, eMail, administrator FROM USER WHERE idUser LIKE ".$itemId;

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }    

    public function getAllEntries() {
        $sql = "SELECT * FROM USER;";

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    public function insertItem(array $fields, array $values) {
        $sql = $this->buildInsert('USER', $fields, $values);

        $result = $this->connection->query($sql);

        $id = mysqli_insert_id($this->connection);

        $this->adapter->closeConnection();

        $rawData = array(array("insertionId" => $id));

        return $rawData;
    }


    public function getIdValue(array $filterFields, array $filterArguments)
    {
        $sql = $this->buildQuery('USER', array("idUser"), $filterFields, $filterArguments);

        $result = $this->getArraySQL($sql, $this->connection);

        $this->adapter->closeConnection();

        return $result;
    }
}