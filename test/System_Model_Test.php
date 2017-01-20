<?php
require_once "application/dbConnection/model/System_Model.php";
require_once "application/dbConnection/adapter/DB_adapter.php";
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 19/01/17
 * Time: 16:27
 */
class System_Model_Test extends PHPUnit_Framework_TestCase {
    public $adapter;
    public $connection;
    public $queryTest;

    public $tableEntriesNumber = 9;

    public $userTableFields = array("idUSER", "publicName", "name", "phone", "eMail", "ads", "privateDes", "publicDes",
        "administrator", "editor", "language", "datePassword", "password", "markedForDeletion", "clearRequestData",
        "memberSince");

    public $parseFields = array("idUser", "publicName", "name", "phone", "eMail", "administrator", "editor", "language",
        "privateDes", "publicDes");

    public $userTableDummyValues = array(
        array("1", "Dent", "Arthur Dent", "912345678", "earth-survival@z-z-plural-z-alpha.sec", "1", "Strange human", "He wants to know a lot of things", "1", "0", "english", "2016-10-28", "dentHuman", "0", null, "2016-04-30"),
        array('2', 'Zaphod', 'Zaphod Beeblebrox', '932345680', 'the_n1@the-galaxy.com', '1', 'Has two heads', 'President of the Galaxy', '0', '0', 'english', '2016-10-28', 'ImTheN1', '0', null, '2016-07-14'),
        array('3', 'Tricia', 'Tricia McMillan', '942345681', 'madagascar-88@desires.uk', '0', 'She is very smart', 'AKA Trillian', '0', '0', 'spanish', '2016-10-28', 'loveArthur', '0', null, '2016-07-15'),
        array('4', 'Marvin', 'Marvin the Android', '942345682', 'dont.speak.about@the-life.pls', '1', 'Not a funny Robot', 'He is always depressed', '0', '0', 'catalan', '2016-10-28', 'paranoidandroid', '1', '2016-07-16 00:00:00', '2016-07-15'),
        array('5', 'Slatirbarfast', 'Slatirbarfast from Magrathea', '952345683', 'guide@the-planet-factory.mg', '1', 'He likes his work', 'He wants to restart the Earth', '0', '0', 'spanish', '2016-07-16', 'norwayCoastLine', '0', null, '2016-10-30'),
        array('6', 'LProsser', 'Mister L. Prosser', '962345684', 'demolition-man@the-uk.uk', '0', 'He is a descendant of Gengis Khan', 'Yellow Squad', '0', '0', 'spanish', '2016-10-28', 'godSaveTheQueen', '0', null, '2016-07-17'),
        array('7', 'ProstecnicVogonJeltz', 'Prostecnic Vogon Jeltz', '972345685', 'demolition-man@the-galaxy.co', '1', 'Dont read his poetry', 'Vogon', '0', '0', 'english', '2016-10-28', 'beesOdas', '0', null, '2016-07-18'),
        array('8', 'Eddie', 'GoldHearth IA', '982345686', 'eddie@spaceship.tk', '1', 'He is an IA', 'Always funny', '0', '0', 'catalan', '2016-10-28', 'omg-omg', '0', null, '2016-07-23'),
        array('9', 'Prefect', 'Ford Prefect', '922345679', 'towel.power@hitchhikers.com', '1', 'Not an actor, not from Sassex', 'Actor from Sassex', '0', '1', 'english', '2016-10-28', 'Ford007', '0', null, '2016-06-25')
    );


    public function setUp() {

        $this->adapter = new DB_adapter();
        $this->queryTest = new System_Model($this->adapter->getConnection());
    }

}
