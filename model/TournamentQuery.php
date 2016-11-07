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


    public function getCustomEntries(array $fields, array $filterFields, array $filterArguments) {

        $sql = $this->buildQuery('TOURNAMENT', $fields, $filterFields, $filterArguments);

        $result = $this->getArraySQL($sql, $this->connection);

        $this->adapter->closeConnection();

        return $result;
    }

    public function getParseEntries() {

        $tournamentssql = "SELECT idTOURNAMENT, name, publicDes, privateDes, date, TOURNAMENT_HOST_idTournamentHost FROM TOURNAMENT";
        //get all tournaments data from DB
        $tournaments = $this->getArraySQL($tournamentssql);

        //walk through all tournaments array
        for ($i = 0; $i < count($tournaments); $i++) {
            //store de actual torunament Id
            $tournamentId = $tournaments[$i]["idTOURNAMENT"];

            $prizesIdsSql = "SELECT PRIZE_idPRIZE, position FROM TOURNAMENT_has_PRIZE WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;
            //get id's from all prizes for actual tournament
            $prizesIds = $this->getArraySQL($prizesIdsSql);

            //walk through all prizes id array
            $j = count($prizesIds);
            $prizes = array();
            while ($j > 0) {
                $prizesSql = "SELECT idPRIZE, name FROM PRIZE WHERE idPRIZE = ".$prizesIds[$j-1]["PRIZE_idPRIZE"];
                //get prize details for actual prize id
                $res = $this->getArraySQL($prizesSql);
                //add position to prize info
                array_push($res[0], $prizesIds[$j-1]["position"]);
                //add actual prize to prizes array
                array_push($prizes, $res[0]);

                //change the field key from numeric to his name
                $pos = $prizes[count($prizes)-1][0]; //store de position value
                unset($prizes[count($prizes)-1][0]); //erase de position field with numeric key
                $prizes[count($prizes)-1]["position"] = $pos; //add position with correct kay name
                $j--;
            }

            $usersIdsSql = "SELECT USER_idUSER FROM TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;
            $usersIds = $this->getArraySQL($usersIdsSql);


            //walk through all users id array
            $j = count($usersIds);
            $users = array();
            while ($j > 0) {
                $usersSql = "SELECT idUSER, publicName FROM USER WHERE idUSER = ".$usersIds[$j-1]["USER_idUSER"];
                //get user detail for actual user id
                $res = $this->getArraySQL($usersSql);
                //add actual user to users array
                array_push($users, $res[0]);
//                unset($users[count($users)-1][0]);
                $j--;
            }

            //add prizes array to tournaments array
            $tournament = $this->mergeArrays($tournaments, $i, $prizes, "prizes");
            $tournaments[$i] = $tournament;

            //add users array to tournaments array
            $tournament = $this->mergeArrays($tournaments, $i, $users, "users");
            $tournaments[$i] = $tournament;
        }

        $this->adapter->closeConnection();

        return $tournaments;
    }

    public function getAllEntries() {
        $sql = "SELECT * FROM TOURNAMENT_HOST;";

        $result = $this->getArraySQL($sql);

        $this->adapter->closeConnection();

        return $result;
    }



    public function getParseEntry($itemId)
    {
        // TODO: Implement getParseEntry() method.
    }

    public function getIdValue(array $filterFields, array $filterArguments)
    {
        // TODO: Implement getIdValue() method.
    }

    public function insertItem(array $fields, array $values)
    {
        // TODO: Implement insertItem() method.
    }
}