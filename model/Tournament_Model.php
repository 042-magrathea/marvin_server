<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:37
 */
include_once "persistence/DB_adapter.php";
include_once "Query.php";

/**
 * Class Tournament_Model
 */
class Tournament_Model extends Query {

    private $adapter;


    /**
     * Tournament_Model constructor.
     */
    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();
        $this->connection->query("SET NAMES 'utf8'");
    }

    //---------------------------------------------------------------------------------------------------------------//

    /**
     * Builds an array with all required data for parsing a tournament at any client
     *
     * Array structure:
     * tournaments {
     *      tournament {
     *          idTOURNAMENT
     *          name
     *          publicDes
     *          privateDes
     *          date
     *          TOURNAMENT_HOST_idTOURNAMENT_HOST
     *          prizes{
     *              prize{
     *                  idPRIZE
     *                  name
     *                  position
     *              }
     *              ...
     *          }
     *          users{
     *              user{
     *                  userId
     *                  publicName
     *              }
     *              ...
     *          }
     *      }
     *      ...
     *  }
     *
     *
     * @return array
     */
    public function getParseEntries() {

        $tournamentssql = "SELECT idTOURNAMENT, name, publicDes, privateDes, date, TOURNAMENT_HOST_idTournamentHost FROM TOURNAMENT";
        //get all tournaments data from DB
        $tournaments = $this->getResultArray($tournamentssql);

        for ($i = 0; $i < count($tournaments); $i++) {
            //store de actual torunament Id
            $tournamentId = $tournaments[$i]["idTOURNAMENT"];

            $prizesIdsSql = "SELECT idPRIZE, tournamentPosition FROM PRIZE WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;
            //get id's from all prizes for actual tournament
            $prizesIds = $this->getResultArray($prizesIdsSql);

            //walk through all prizes id array
            $j = count($prizesIds);
            $prizes = array();
            while ($j > 0) {
                $prizesSql = "SELECT idPRIZE, name FROM PRIZE WHERE idPRIZE = ".$prizesIds[$j-1]["idPRIZE"];
                //get prize details for actual prize id
                $res = $this->getResultArray($prizesSql);
                //add position to prize info
                array_push($res[0], $prizesIds[$j-1]["tournamentPosition"]);
                //add actual prize to prizes array
                array_push($prizes, $res[0]);

                //change the field key from numeric to his name
                $pos = $prizes[count($prizes)-1][0]; //store de position value
                unset($prizes[count($prizes)-1][0]); //erase de position field with numeric key
                $prizes[count($prizes)-1]["tournamentPosition"] = $pos; //add position with correct kay name
                $j--;
            }

            $usersIdsSql = "SELECT USER_idUSER FROM TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT = ".$tournamentId;
            $usersIds = $this->getResultArray($usersIdsSql);

            //walk through all users id array
            $j = count($usersIds);
            $users = array();
            while ($j > 0) {
                $usersSql = "SELECT idUSER, publicName FROM USER WHERE idUSER = ".$usersIds[$j-1]["USER_idUSER"];
                //get user detail for actual user id
                $res = $this->getResultArray($usersSql);
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


    /**
     * Get all entries from the 'TOURNAMENT' table in database that matches all parameters specified, this method has to be used
     * to execute custom requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getCustomEntries($fields, $filterFields, $filterArguments) {

        $sql = $this->buildQuerySql('TOURNAMENT', $fields, $filterFields, $filterArguments);

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get all fields from all entries of the table TOURNAMENT from the database
     *
     * @return array
     */
    public function getAllEntries() {
        $sql = "SELECT * FROM TOURNAMENT;";

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Insert al specified fields of an item with the specified values into the table TOURNAMENT
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
    public function insertItem(array $fields, array $values)
    {
        // TODO: Implement insertItem() method.
    }

    /**
     * Get parse entry by id
     *
     * @param $itemId
     * @return mixed|void
     */
    public function getParseEntry($itemId)
    {
        // TODO: Implement getParseEntry() method.
    }

    /**
     * Get the id of the tournament that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue(array $filterFields, array $filterArguments)
    {
        // TODO: Implement getIdValue() method.
    }

    public function modifyItem($itemNId, $fields, $values)
    {
        // TODO: Implement modifyItem() method.
    }

    public function deleteItem($itemId)
    {
        // TODO: Implement deleteItem() method.
    }

    /**
     * Get all entries at TOURNAMENT_has_USER table that matches with $tournamentId at field TORUNAMENT_idTOURNAMENT
     *
     * @param $tournamentId the id of the tournament
     * @return array result of the query
     */
    public function usersAtTournament($tournamentId) {
        $sql = "SELECT * FROM magrathea.TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT=" . $tournamentId;

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Checks if an user is signed up in a tornament, checking his existence in TOURNAMENT_HAS_USER table
     *
     * @param $tournamentId the tournament's id
     * @param $userId the user's id
     * @return array single boolean value array
     */
    public function tournamentHasUser($tournamentId, $userId) {
        $sql = "SELECT COUNT(*) FROM magrathea.TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT=" . $tournamentId .
            " AND USER_idUSER=" . $userId . ";";

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        $result[0]["tournamentHasUser"] = (boolean) $result[0]["COUNT(*)"];
        unset($result[0]["COUNT(*)"]);

        return $result;
    }

    /**
     * Deletes an entry of the TORUNAMENT_has_USER table
     *
     * @param $tournamentId the id of the tournament
     * @param $userId the user's id
     * @return array result of the deletion
     */
    public function deleteUserFromTournament($tournamentId, $userId) {

        $filterFields = array("TOURNAMENT_idTOURNAMENT", "USER_idUSER");
        $filterArguments = array($tournamentId, $userId);

        $sql = $this->buildDeletionSql("TOURNAMENT_has_USER", $filterFields, $filterArguments);

        $this->connection->query($sql);

        $affectedRows = mysqli_affected_rows($this->connection);

        $this->adapter->closeConnection();

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("deletedRowsNum",$affectedRows);

        return $rawData;

    }

    /**
     * Counts all entries for the given tournamentId exists at TOURNAMENT_has_USER
     *
     * @param $tournamentId the tournament's id
     * @return array result of the count
     */
    public function countTournamentUsers($tournamentId) {
        $sql = "SELECT COUNT(*) FROM magrathea.TOURNAMENT_has_USER WHERE TOURNAMENT_idTOURNAMENT=" . $tournamentId . ";";

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        $result[0]["usersAtTournament"] = $result[0]["COUNT(*)"];
        unset($result[0]["COUNT(*)"]);

        return $result;
    }
}