<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:23
 */
include_once "application/dbConnection/adapter/DB_adapter.php";
include_once "Query.php";

/**
 * Class Prize_Model
 */
class Prize_Model extends Query {

    private $adapter;
    private static $prizeKinds = array(
        "SINGLE" => 0,
        "DISCOUNT" => 1,
        "MERCHANT" => 2
    );


    /**
     * Prize_Model constructor.
     */
    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();
        $this->connection->query("SET NAMES 'utf8'");

    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                              COMMON METHODS                                                    //

    //----------------------------------------------------------------------------------------------------------------//

    /**
     * Get all entries from the 'PRIZE' table in database that matches all parameters specified, this method has to be used
     * to do execute requests to the specified table
     *
     * @param array $fields contains the fields names of the table to be shown in the request response
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return array
     */
    public function getCustomEntries($fields, $filterFields, $filterArguments) {

        $sql = $this->buildQuerySql('PRIZE', $fields, $filterFields, $filterArguments);

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Get all fields from all entries of the table PRIZE from the database
     *
     * @return array
     */
    public function getAllEntries() {
        $sql = "SELECT * FROM PRIZE;";

        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Builds an array with all required data for parsing a prize at any client
     *
     * @return array
     */
    public function getParseEntries() {

        $sql = "SELECT PRIZE.idPRIZE, PRIZE.name, PRIZE.description, PRIZE.image, USER.publicName as 'userName', ".
            "TOURNAMENT.name as 'tournamentName', PRIZE.TEMPLATE_idTEMPLATE, PRIZE.tournamentPosition ".
            "FROM PRIZE LEFT JOIN USER ON PRIZE.USER_idUSER=USER.idUSER ".
            "LEFT JOIN TOURNAMENT ON PRIZE.TOURNAMENT_idTOURNAMENT=TOURNAMENT.idTOURNAMENT ".
            "WHERE PRIZE.idPrize NOT IN (SELECT PRIZE_idPRIZE FROM PRIZE_MERCHANT) ".
            "AND PRIZE.idPrize NOT IN (SELECT PRIZE_idPRIZE FROM PRIZE_DISCOUNT);";

        $resultSinglePrizes = $this->getResultArray($sql);

        $sql = "SELECT PRIZE.idPRIZE, PRIZE.name, PRIZE.description, PRIZE.image, USER.publicName as 'userName', ".
            "TOURNAMENT.name as 'tournamentName', PRIZE.TEMPLATE_idTEMPLATE, PRIZE_MERCHANT.claimed, " .
            "PRIZE_MERCHANT.expirationDate ".
            "FROM PRIZE RIGHT JOIN PRIZE_MERCHANT ON PRIZE.idPRIZE=PRIZE_MERCHANT.PRIZE_idPRIZE ".
            "LEFT JOIN USER ON PRIZE.USER_idUSER=USER.idUSER ".
            "LEFT JOIN TOURNAMENT ON PRIZE.TOURNAMENT_idTOURNAMENT=TOURNAMENT.idTOURNAMENT ".
            "WHERE PRIZE.idPrize IN (SELECT PRIZE_idPRIZE FROM PRIZE_MERCHANT);";

        $resultDiscountPrizes = $this->getResultArray($sql);

        $sql = "SELECT PRIZE.idPRIZE, PRIZE.name, PRIZE.description, PRIZE.image, USER.publicName as 'userName', ".
            "TOURNAMENT.name as 'tournamentName', PRIZE.TEMPLATE_idTEMPLATE, PRIZE_DISCOUNT.disc, " .
            "PRIZE_DISCOUNT.expirationDate ".
            "FROM PRIZE RIGHT JOIN PRIZE_DISCOUNT ON PRIZE.idPRIZE=PRIZE_DISCOUNT.PRIZE_idPRIZE ".
            "LEFT JOIN USER ON PRIZE.USER_idUSER=USER.idUSER ".
            "LEFT JOIN TOURNAMENT ON PRIZE.TOURNAMENT_idTOURNAMENT=TOURNAMENT.idTOURNAMENT ".
            "WHERE PRIZE.idPrize IN (SELECT PRIZE_idPRIZE FROM PRIZE_DISCOUNT);";

        $resultMerchantPrizes = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        $result = array_merge($resultSinglePrizes, $resultDiscountPrizes, $resultMerchantPrizes);

        return $result;
    }

    /**
     * Get parse entry by id
     *
     * @param $itemId
     * @return mixed|void
     */
    public function getParseEntry($itemId)
    {
        $sql = "SELECT PRIZE.idPRIZE, PRIZE.name, PRIZE.description, PRIZE.image, USER.publicName as 'userName', ".
            "TOURNAMENT.name as 'tournamentName', PRIZE.TEMPLATE_idTEMPLATE, PRIZE.tournamentPosition ".
            "FROM PRIZE LEFT JOIN USER ON PRIZE.USER_idUSER=USER.idUSER ".
            "LEFT JOIN TOURNAMENT ON PRIZE.TOURNAMENT_idTOURNAMENT=TOURNAMENT.idTOURNAMENT ".
            "WHERE PRIZE.idPrize NOT IN (SELECT PRIZE_idPRIZE FROM PRIZE_MERCHANT) ".
            "AND PRIZE.idPrize NOT IN (SELECT PRIZE_idPRIZE FROM PRIZE_DISCOUNT) ".
            "AND PRIZE.idPrize LIKE '" . $itemId . "'';";

        $resultSinglePrizes = $this->getResultArray($sql);

        $sql = "SELECT PRIZE.idPRIZE, PRIZE.name, PRIZE.description, PRIZE.image, USER.publicName as 'userName', ".
            "TOURNAMENT.name as 'tournamentName', PRIZE.TEMPLATE_idTEMPLATE, PRIZE_MERCHANT.claimed, " .
            "PRIZE_MERCHANT.expirationDate ".
            "FROM PRIZE RIGHT JOIN PRIZE_MERCHANT ON PRIZE.idPRIZE=PRIZE_MERCHANT.PRIZE_idPRIZE ".
            "LEFT JOIN USER ON PRIZE.USER_idUSER=USER.idUSER ".
            "LEFT JOIN TOURNAMENT ON PRIZE.TOURNAMENT_idTOURNAMENT=TOURNAMENT.idTOURNAMENT ".
            "WHERE PRIZE.idPrize IN (SELECT PRIZE_idPRIZE FROM PRIZE_MERCHANT);";

        $resultDiscountPrizes = $this->getResultArray($sql);

        $sql = "SELECT PRIZE.idPRIZE, PRIZE.name, PRIZE.description, PRIZE.image, USER.publicName as 'userName', ".
            "TOURNAMENT.name as 'tournamentName', PRIZE.TEMPLATE_idTEMPLATE, PRIZE_DISCOUNT.disc, " .
            "PRIZE_DISCOUNT.expirationDate ".
            "FROM PRIZE RIGHT JOIN PRIZE_DISCOUNT ON PRIZE.idPRIZE=PRIZE_DISCOUNT.PRIZE_idPRIZE ".
            "LEFT JOIN USER ON PRIZE.USER_idUSER=USER.idUSER ".
            "LEFT JOIN TOURNAMENT ON PRIZE.TOURNAMENT_idTOURNAMENT=TOURNAMENT.idTOURNAMENT ".
            "WHERE PRIZE.idPrize IN (SELECT PRIZE_idPRIZE FROM PRIZE_DISCOUNT);";

        $resultMerchantPrizes = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        $result = array_merge($resultSinglePrizes, $resultDiscountPrizes, $resultMerchantPrizes);

        return $result;
    }

    /**
     * Get the id of the prize that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue($filterFields, $filterArguments) {
        //build the query statement
        $sql = $this->buildQuerySql('PRIZE', array("idPRIZE"), $filterFields, $filterArguments);

        //execute query
        $result = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        return $result;
    }

    /**
     * Checks the kind of the prize to store and insert all specified fields with the specified values into the proper
     * tables (PRIZE, PRIZE_DISCOUNT, PRIZE_MERCHANT)
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
    public function insertItem($fields, $values) {
        $prizeKind = $this->getPrizeKind($fields);

        if ( $prizeKind == $this::prizeKinds["DISCOUNT"] ) {
            $result = $this->writePrizeDiscount($fields, $values);
        } else if ( $prizeKind == $this::prizeKinds["MERCHANT"] ) {
            $result = $this->writePrizeMerchant($fields, $values);
        } else {
            $result = $this->writeSinglePrize($fields, $values);
        }

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionId",$result);

        $this->adapter->closeConnection();

        return $rawData;
    }

    /**
     * Modify al specified fields of a prize with the specified values into the PRIZE table and his childs
     *
     * @param $itemId string with the user's id
     * @param $fields string or string array must contain all fields to be modified in the new entry
     * @param $values string or string array must contain the values of the fields to be modified, the value position
     * must match the position of the corresponding field at $fields array
     * @return mixed
     */
    public function modifyItem($itemId, $fields, $values) {

        $prizeKind = $this->getPrizeKind($fields);

        if ( $prizeKind == $this::prizeKinds["DISCOUNT"] ) {
            $result = $this->writePrizeDiscount($fields, $values);

        } else if ( $prizeKind == $this::prizeKinds["MERCHANT"] ) {
            $result = $this->writePrizeMerchant($fields, $values);
        } else {
            $result = $this->writeSinglePrize($fields, $values);
        }

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionId",$result);

        $this->adapter->closeConnection();

        return $rawData;
    }

    /**
     * Deletes the id given item from the PRIZE table and his children tables, returns number of rows deleted from the
     * PRIZE table
     *
     * @param $itemId id from the item to be deleted
     * @return mixed number of rows deleted from the PRIZE table
     */
    public function deleteItem($itemId) {
        //build query statement
        $discountPrizeSQL = $this->buildDeletionSql("PRIZE_DISCOUNT", array("idPRIZE"), $itemId);
        $merchantPrizeSQL = $this->buildDeletionSql("PRIZE_MERCHANT", array("idPRIZE"), $itemId);
        $singlePrizesSQL= $this->buildDeletionSql("PRIZE", array("idPRIZE"), $itemId);

        try {
            //start transaction
            $this->connection->begin_transaction();


            //execute deletions
            $queryResult1 = $this->connection->query($discountPrizeSQL);
            $queryResult2 = $this->connection->query($merchantPrizeSQL);
            $queryResult3 = $this->connection->query($singlePrizesSQL);

            //check query success
            if ($queryResult1 && $queryResult2 && $queryResult3) {
                $this->connection->commit();
            } else {
                throw new mysqli_sql_exception();
            }

            //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
            $affectedRows = $this->connection->affected_rows;

            $this->adapter->closeConnection();

            //converts the array to JSON friendly format
            $rawData = $this->getJsonFriendlyArray("deletedRowsNum",$affectedRows);

            return $rawData;

        } catch (mysqli_sql_exception $e) {

            $this->connection->rollback();

            $this->adapter->closeConnection();

            //converts the array to JSON friendly format
            $rawData = $this->getJsonFriendlyArray("deletedRowsNum",0);

            return $rawData;
        }

    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                           END OF COMMON METHODS                                                //

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //                                            PRIZE QUERIES METHODS                                               //

    //----------------------------------------------------------------------------------------------------------------//

    /**
     * Insert fields and values of the single prize into the PRIZE table
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return integer position of the stored item at PRIZE table
     */
    private function writeSinglePrize($fields, $values) {
        //build the insert statement
        $sql = $this->buildInsertSql('PRIZE', $fields, $values);

        //executes query
        $result = $this->connection->query($sql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $id = mysqli_insert_id($this->connection);



        return $id;
    }

    /**
     * Insert fields and values of the merchant prize into the PRIZE_MERCHANT table
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return integer position of the stored item at PRIZE_MERCHANT table
     */
    private function writePrizeMerchant($fields, $values) {

        //build the new fields and values arrays for insertion in PRIZE
        $singlePrize = $this->extractSinglePrizeArray($fields, $values);
        $singlePrizeFields = $singlePrize[0];
        $singlePrizeValues = $singlePrize[1];


        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $idPrize = $this->insertSinglePrize($singlePrizeFields, $singlePrizeValues);

        //build the new fields and values arrays for insertion in PRICE_MERCHANT
        $merchantPrize = $this->extractMerchantPrizeArray($idPrize, $fields, $values);
        $merchantPrizeFields = $merchantPrize[0];
        $merchantPrizeValues = $merchantPrize[1];

        //build the insert statement
        $merchantPrizeSql = $this->buildInsertSql('PRIZE_MERCHANT', $merchantPrizeFields, $merchantPrizeValues);

        //executes query
        $this->connection->query($merchantPrizeSql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        //$id = mysqli_insert_id($this->connection);

        return $idPrize;
    }

    /**
     * Insert fields and values of the discount prize into the PRIZE_DISCOUNT table
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return integer position of the stored item at PRIZE_DISCOUNT table
     */
    private function writePrizeDiscount($fields, $values) {

        //build the new fields and values arrays for insertion in PRIZE
        $singlePrize = $this->extractSinglePrizeArray($fields, $values);
        $singlePrizeFields = $singlePrize[0];
        $singlePrizeValues = $singlePrize[1];

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $idPrize = $this->insertSinglePrize($singlePrizeFields, $singlePrizeValues);


        //build the new fields and values arrays for insertion in PRICE_DISCOUNT
        $discountPrize = $this->extractDiscountPrizeArray($idPrize, $fields, $values);
        $discountPrizeFields = $discountPrize[0];
        $discountPrizeValues = $discountPrize[1];

        //build the insert statement
        $discountPrizeSql = $this->buildInsertSql('PRIZE_DISCOUNT', $discountPrizeFields, $discountPrizeValues);

        //executes query
        $this->connection->query($discountPrizeSql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        //$id = mysqli_insert_id($this->connection);

        return $idPrize;
    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                        END OF PRIZE QUERIES METHODS                                            //

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //----------------------------------------------------------------------------------------------------------------//

    //                                              AUXILIAR METHODS                                                  //

    //----------------------------------------------------------------------------------------------------------------//

    /**
     * Builds two arrays with the fields for an insertion at PRIZE table and returns them joined in a double array
     *
     * @param array $fields original fields array to be transformed
     * @param array $values original values array to be transformed
     * @return array containing two arrays, the array stored at position 0 contains the $fields array needed for an
     * insertion at PRIZE table and the array stored at position 1 contains the $values array needed for an
     * insertion at PRIZE table
     */
    private function extractSinglePrizeArray($fields, $values) {

        $singlePrizeFields = array();
        $singlePrizeValues = array();

        for ($i = 0; $i < count($fields); $i++) {
            if ( $fields[$i] != "claimed" && $fields[$i] != "expirationDate" && $fields[$i] != "disc" ) {
                array_push($singlePrizeFields, $fields[$i]);
                array_push($singlePrizeValues, $values[$i]);
            }
        }

        return array($singlePrizeFields, $singlePrizeValues);
    }

    /**
     * Builds two arrays with the fields for an insertion at PRIZE_MERCHANT table and returns them joined in a double array
     *
     * @param $prizeId value of the idPRIZE value in the PRIZE table for the prize to be stored
     * @param array $fields original fields array to be transformed
     * @param array $values original values array to be transformed
     * @return array containing two arrays, the array stored at position 0 contains the $fields array needed for an
     * insertion at PRIZE_MERCHANT table and the array stored at position 1 contains the $values array needed for an
     * insertion at PRIZE_MERCHANT table
     */
    private function extractMerchantPrizeArray($prizeId, $fields, $values) {

        $merchantPrizeFields = array();
        $merchantPrizeValues = array();

        array_push($merchantPrizeFields, "PRIZE_idPRIZE");
        array_push($merchantPrizeValues, $prizeId);

        for ($i = 0; $i < count($fields); $i++) {
            if ( $fields[$i] == "claimed" || $fields[$i] == "expirationDate" ) {
                array_push($merchantPrizeFields, $fields[$i]);
                array_push($merchantPrizeValues, $values[$i]);
            }
        }

        return array($merchantPrizeFields, $merchantPrizeValues);

    }

    /**
     * Builds two arrays with the fields for an insertion at PRIZE_DISCOUNT table and returns them joined in a double array
     *
     * @param $prizeId value of the idPRIZE value in the PRIZE table for the prize to be stored
     * @param array $fields original fields array to be transformed
     * @param array $values original values array to be transformed
     * @return array containing two arrays, the array stored at position 0 contains the $fields array needed for an
     * insertion at PRIZE_DISCOUNT table and the array stored at position 1 contains the $values array needed for an
     * insertion at PRIZE_DISCOUNT table
     */
    private function extractDiscountPrizeArray($prizeId, $fields, $values) {

        $discountPrizeFields = array();
        $discountPrizeValues = array();

        array_push($discountPrizeFields, "PRIZE_idPRIZE");
        array_push($discountPrizeValues, $prizeId);

        for ($i = 0; $i < count($fields); $i++) {
            if ( $fields[$i] == "disc" || $fields[$i] == "expirationDate" ) {
                array_push($discountPrizeFields, $fields[$i]);
                array_push($discountPrizeValues, $values[$i]);
            }
        }

        $resultArray = array($discountPrizeFields, $discountPrizeValues);

        return $resultArray;

    }

    /**
     * Gets prize kind from an array containing all hi fields
     *
     * @param $fields string array that must contain all data from a prize wathever the kind of the prize
     * @return int the prize's kind
     */
    private function getPrizeKind($fields) {
        if ( array_search("disc", $fields) != 0 ) {
            return $this::prizeKinds["DISCOUNT"];
        } else if ( array_search("claimed", $fields) ) {
            return $this::prizeKinds["MERCHANT"];
        } else {
            return $this::prizeKinds["SINGLE"];
        }
    }

    //----------------------------------------------------------------------------------------------------------------//

    //                                            END OF AUXILIAR METHODS                                             //

    //----------------------------------------------------------------------------------------------------------------//

}
