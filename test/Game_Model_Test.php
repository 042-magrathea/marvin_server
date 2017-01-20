<?php
require_once "application/dbConnection/model/Game_Model.php";
require_once "application/dbConnection/adapter/DB_adapter.php";
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 19/01/17
 * Time: 16:00
 */
class Game_Model_Test extends PHPUnit_Framework_TestCase {
    public $adapter;
    public $connection;
    public $queryTest;

    public $tableEntriesNumber = 9;

    public $gameTableFields = array("idGAME", "name", "description", "image");

    public $parseFields = array("idGAME", "name", "description", "image");

    public $gameTableDummyValues = array(
        array('1', 'Magic The Gatherin', 'Juego de cartas coleccionables', 'http://www.allcsgaming.com/wp-content/uploads'),
        array('2', 'Pokèmon TGC', 'Juego de cartas coleccionables', 'http://www.arkadian.vg/wp-content/uploads/201'),
        array('3', 'Aquelarre 2ª edición', 'Juego de rol', 'http://3.bp.blogspot.com/_M7qnJE4I_pA/TL2DVT7')
    );


    public function setUp() {

        $this->adapter = new DB_adapter();
        $this->queryTest = new Game_Model($this->adapter->getConnection());
    }

}
