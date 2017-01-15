<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 1/11/16
 * Time: 20:23
 */
include_once "persistence/DB_adapter.php";
include_once "Query.php";

/**
 * Class Prize_Model
 */
class Prize_Model extends Query {

    private $adapter;


    /**
     * Prize_Model constructor.
     */
    public function __construct() {
        $this->adapter = new DB_adapter();

        $this->connection = $this->adapter->getConnection();
        $this->connection->query("SET NAMES 'utf8'");

    }

    //---------------------------------------------------------------------------------------------------------------//

    //////////////////////////
    // USED BY THE PROTOTYPE//
    //////////////////////////

    /**
     * Builds an array with all required data for parsing a prize at any client
     *
     * @return array
     */
    public function getParseEntries() {

        /*$sql = "SELECT * FROM PRIZE p WHERE p.idPrize NOT IN (SELECT PRIZE_idPRIZE FROM PRIZE_MERCHANT) AND p.idPrize " .
            "NOT IN (SELECT PRIZE_idPRIZE FROM PRIZE_DISCOUNT);";

        $resultSinglePrizes = $this->getResultArray($sql);

        $sql = "SELECT PRIZE.idPRIZE, PRIZE.name, PRIZE.description, PRIZE.image, PRIZE.USER_idUSER, " .
            "PRIZE.TOURNAMENT_idTOURNAMENT, PRIZE.TEMPLATE_idTEMPLATE, PRIZE_MERCHANT.claimed, " .
            "PRIZE_MERCHANT.expirationDate FROM PRIZE RIGHT JOIN PRIZE_MERCHANT ON PRIZE.idPRIZE=PRIZE_MERCHANT.PRIZE_idPRIZE;";

        $resultDiscountPrizes = $this->getResultArray($sql);

        $sql = "SELECT PRIZE.idPRIZE, PRIZE.name, PRIZE.description, PRIZE.image, PRIZE.USER_idUSER, " .
            "PRIZE.TOURNAMENT_idTOURNAMENT, PRIZE.TEMPLATE_idTEMPLATE, PRIZE_DISCOUNT.disc, " .
            "PRIZE_DISCOUNT.expirationDate FROM PRIZE RIGHT JOIN PRIZE_DISCOUNT ON PRIZE.idPRIZE=PRIZE_DISCOUNT.PRIZE_idPRIZE ;";

        $resultMerchantPrizes = $this->getResultArray($sql);

        $this->adapter->closeConnection();

        $result = array_merge($resultSinglePrizes, $resultDiscountPrizes, $resultMerchantPrizes);*/

        $sql = "SELECT PRIZE.idPRIZE, PRIZE.name, PRIZE.description, PRIZE.image, USER.publicName as 'userName', ".
            "TOURNAMENT.name as 'tournamentName', PRIZE.TEMPLATE_idTEMPLATE FROM PRIZE LEFT JOIN USER ON ".
            "PRIZE.USER_idUSER=USER.idUSER LEFT JOIN TOURNAMENT ON PRIZE.TOURNAMENT_idTOURNAMENT=TOURNAMENT.idTOURNAMENT;";

        $result = $this->getResultArray($sql);

        return $result;
    }

    //---------------------------------------------------------------------------------------------------------------//

    //////////////////////////////
    // NOT USED BY THE PROTOTYPE//
    //////////////////////////////

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
     * Insert al specified fields of an item with the specified values into the table PRIZE
     *
     * @param array $fields must contain all fields to be stored in the new entry
     * @param array $values must contain the values of the fields to be stored, the value position must match the position
     * of the corresponding field at $fields array
     * @return array
     */
    public function insertItem(array $fields, array $values) {
        $prizeKind = $this->getPrizeKind($fields);

        if ( $prizeKind == PrizeKinds::DISCOUNT ) {
            $result = $this->writePrizeDiscount($fields, $values);
        } else if ( $prizeKind == PrizeKinds::MERCHANT ) {
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
     * Get the id of the prize that matches the given parameters
     *
     * @param array $filterFields contains the fields names that will be used in the query to filter its results
     * @param array $filterArguments contains the values that the specified fields will have to match
     * @return mixed|void
     */
    public function getIdValue(array $filterFields, array $filterArguments)
    {
        // TODO: Implement getIdValue() method.
    }

    public function modifyItem($itemNId, $fields, $values) {

        $prizeKind = $this->getPrizeKind($fields);

        if ( $prizeKind == PrizeKinds::DISCOUNT ) {
            $result = $this->writePrizeDiscount($fields, $values);
        } else if ( $prizeKind == PrizeKinds::MERCHANT ) {
            $result = $this->writePrizeMerchant($fields, $values);
        } else {
            $result = $this->writeSinglePrize($fields, $values);
        }

        //converts the array to JSON friendly format
        $rawData = $this->getJsonFriendlyArray("insertionId",$result);

        $this->adapter->closeConnection();

        return $rawData;
    }

    public function deleteItem($itemId)
    {
        // TODO: Implement deleteItem() method.
    }

//---------------------------------------------------------------------------------------------------------------//

    ///////////////////////////////
    // INSERTION AUXILIAR METHODS//
    ///////////////////////////////

    private function writeSinglePrize(array $fields, array $values) {
        //build the insert statement
        $sql = $this->buildInsertSql('PRIZE', $fields, $values);

        //executes query
        $result = $this->connection->query($sql);

        //get last insertion result 0 = no insertion, >0 = insertion position at the USER table
        $id = mysqli_insert_id($this->connection);



        return $id;
    }

    private function writePrizeMerchant(array $fields, array $values) {

        //build the new fields and values arrays for insertion in PRIZE
        $singlePrize = $this->extractSinglePrize($fields, $values);
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

    private function writePrizeDiscount(array $fields, array $values) {

        //build the new fields and values arrays for insertion in PRIZE
        $singlePrize = $this->extractSinglePrize($fields, $values);
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

    private function extractSinglePrize(array $fields, array $values) {

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

    private function extractMerchantPrizeArray($prizeId, array $fields, array $values) {

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

    private function extractDiscountPrizeArray($prizeId, array $fields, array $values) {

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

        return array($discountPrizeFields, $discountPrizeValues);

    }

    private function getPrizeKind(array $fields) {
        if ( array_search("disc", $fields) != 0 ) {
            return PrizeKinds::DISCOUNT;
        } else if ( array_search("claimed", $fields) ) {
            return PrizeKinds::MERCHANT;
        } else {
            return PrizeKinds::SINGLE;
        }
    }

}
abstract class PrizeKinds {
    const SINGLE = 0;
    const DISCOUNT = 1;
    const MERCHANT = 2;

}