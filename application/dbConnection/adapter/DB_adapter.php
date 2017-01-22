<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolàs
 * Date: 31/10/16
 * Time: 2:28
 */
class DB_adapter {

    private $servername = "localhost";
    private $username = "administrator";
    private $password = "damMagrathea042";
    private $database = "magrathea";

    private $connection;


    /**
     * DB_adapter constructor.
     */
    public function __construct() {
        $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->database);

        mysqli_set_charset($this->connection, "utf8");

        return $this->connection;
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() {}

    /**
     * @return mysqli
     */
    public function getConnection() {

        return $this->connection;
    }

    /**
     * @return bool
     */
    public function closeConnection() {
        $closeResult = mysqli_close($this->connection);
        if(!$closeResult) {
            echo "Error at database disconnection";
        }

        return $closeResult;
    }
}