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


    public function getTableInfo(array $fields, array $filtersFields, array $filtersArguments) {

        $sql = $this->buildQuery('USER', $fields, $filtersFields, $filtersArguments);

        $result = $this->getArraySQL($sql, $this->connection);

        $this->adapter->closeConnection();

        return $result;
    }

    public function getParseEntries() {
        $sql = "SELECT idUser, publicName, password, 'e-mail', administrator FROM USER;";

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
}