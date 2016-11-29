<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolàs
 * Date: 31/10/16
 * Time: 2:28
 */
class DB_adapter {

    private $servername = "192.168.0.5";
    private $username = "admin";
    private $password = "damMagrathea42";
    private $database = "magrathea";
    private $connection;


    /**
     * DB_adapter constructor.
     */
    public function __construct() {
        $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->database);

        //error handling
        if (mysqli_connect_error()) {
            trigger_error("Failed to connect to MySQL: " . mysqli_connect_error(), E_USER_ERROR);
        }

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