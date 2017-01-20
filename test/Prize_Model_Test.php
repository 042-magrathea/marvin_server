<?php
require_once "application/dbConnection/model/Prize_Model.php";
require_once "application/dbConnection/adapter/DB_adapter.php";
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 19/01/17
 * Time: 16:11
 */
class Prize_Model_Test extends PHPUnit_Framework_TestCase {
    public $adapter;
    public $connection;
    public $queryTest;

    public $tableEntriesNumber = 9;

    public $prizeTableFields = array("idUSER", "publicName", "name", "phone", "eMail", "ads", "privateDes", "publicDes",
        "administrator", "editor", "language", "datePassword", "password", "markedForDeletion", "clearRequestData",
        "memberSince");

    public $parseFields = array("idUser", "publicName", "name", "phone", "eMail", "administrator", "editor", "language",
        "privateDes", "publicDes");

    public $prizeTableDummyValues = array(
        array(),
        array()
    );


    public function setUp() {

        $this->adapter = new DB_adapter();
        $this->queryTest = new Prize_Model($this->adapter->getConnection());
    }

}
