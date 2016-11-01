<?php

/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 1/11/16
 * Time: 20:37
 */
include_once "DB_adapter.php";
include_once "Query.php";

class TournamentQuery extends Query {

    private $adapter;
    protected $connection;

    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();

    }


    public function getCustomEntries(array $fields, array $filtersFields, array $filtersArguments) {

        $sql = $this->buildQuery('TOURNAMENT', $fields, $filtersFields, $filtersArguments);

        $result = $this->getArraySQL($sql, $this->connection);

        $this->adapter->closeConnection();

        return $result;
    }

    public function getParseEntries() {
        $sql = "SELECT idTOURNAMENT, TOURNAMENT_HOST_idTournamentHost FROM TOURNAMENT";

        $result = $this->getArraySQL($sql);

        $tournamentId = $result[0][0];

        $prizesSql = "SELECT PRIZE_idPRIZE FORM TOURNAMENT_has_PRIZE WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;

        //consulta ids premis
        //

        $this->adapter->closeConnection();

        return $result;
    }

    public function getAllEntries() {
        $sql = "SELECT * FROM TOURNAMENT_HOST;";

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }

}