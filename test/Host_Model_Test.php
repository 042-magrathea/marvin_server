<?php
require_once "application/dbConnection/model/Host_Model.php";
require_once "application/dbConnection/adapter/DB_adapter.php";
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 19/01/17
 * Time: 16:02
 */
class Host_Model_Test extends PHPUnit_Framework_TestCase {
    public $adapter;
    public $connection;
    public $queryTest;

    public $tableEntriesNumber = 9;

    public $hostTableFields = array("idTournamentHost", "name", "latitude", "longitude", "phone", "adress", "eMail");

    public $parseFields = array("idTournamentHost", "name");

    public $hostTableDummyValues = array(
        array('1', 'Tienda Magrateha Barcelona', '41.391', '2.18', '937654321', 'Arco del Triunfo', 'magrathea-bcn@magrathea.cat')
     );


    public function setUp() {

        $this->adapter = new DB_adapter();
        $this->queryTest = new Host_Model($this->adapter->getConnection());
    }
}
