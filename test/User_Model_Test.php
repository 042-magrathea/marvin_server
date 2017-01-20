<?php

require_once "application/dbConnection/model/User_Model.php";
require_once "application/dbConnection/adapter/DB_adapter.php";
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 19/01/17
 * Time: 1:00
 */
class User_Model_Test extends PHPUnit_Framework_TestCase {

    public $adapter;
    public $connection;
    public $queryTest;

    public $tableEntriesNumber = 9;

    public $userTableFields = array("idUSER", "publicName", "name", "phone", "eMail", "ads", "privateDes", "publicDes",
        "administrator", "editor", "language", "datePassword", "password", "markedForDeletion", "clearRequestData",
        "memberSince");

    public $parseFields = array("idUSER", "publicName", "name", "phone", "eMail", "administrator", "editor", "language",
        "privateDes", "publicDes");

    public $userTableDummyValues = array(
        array("1", "Dent", "Arthur Dent", "912345678", "earth-survival@z-z-plural-z-alpha.sec", "1", "Strange human",
            "He wants to know a lot of things", "1", "0", "english", "2016-10-28", "dentHuman", "0", null, "2016-04-30"),
        array('2', 'Zaphod', 'Zaphod Beeblebrox', '932345680', 'the_n1@the-galaxy.com', '1', 'Has two heads',
            'President of the Galaxy', '0', '0', 'english', '2016-10-28', 'ImTheN1', '0', null, '2016-07-14'),
        array('3', 'Tricia', 'Tricia McMillan', '942345681', 'madagascar-88@desires.uk', '0', 'She is very smart',
            'AKA Trillian', '0', '0', 'spanish', '2016-10-28', 'loveArthur', '0', null, '2016-07-15'),
        array('4', 'Marvin', 'Marvin the Android', '942345682', 'dont.speak.about@the-life.pls', '1', 'Not a funny Robot',
            'He is always depressed', '0', '0', 'catalan', '2016-10-28', 'paranoidandroid', '1', '2016-07-16 00:00:00', '2016-07-15'),
        array('5', 'Slatirbarfast', 'Slatirbarfast from Magrathea', '952345683', 'guide@the-planet-factory.mg', '1', 'He likes his work',
            'He wants to restart the Earth', '0', '0', 'spanish', '2016-07-16', 'norwayCoastLine', '0', null, '2016-10-30'),
        array('6', 'LProsser', 'Mister L. Prosser', '962345684', 'demolition-man@the-uk.uk', '0', 'He is a descendant of Gengis Khan',
            'Yellow Squad', '0', '0', 'spanish', '2016-10-28', 'godSaveTheQueen', '0', null, '2016-07-17'),
        array('7', 'ProstecnicVogonJeltz', 'Prostecnic Vogon Jeltz', '972345685', 'demolition-man@the-galaxy.co', '1', 'Dont read his poetry',
            'Vogon', '0', '0', 'english', '2016-10-28', 'beesOdas', '0', null, '2016-07-18'),
        array('8', 'Eddie', 'GoldHearth IA', '982345686', 'eddie@spaceship.tk', '1', 'He is an IA',
            'Always funny', '0', '0', 'catalan', '2016-10-28', 'omg-omg', '0', null, '2016-07-23'),
        array('9', 'Prefect', 'Ford Prefect', '922345679', 'towel.power@hitchhikers.com', '1', 'Not an actor, not from Sassex',
            'Actor from Sassex', '0', '1', 'english', '2016-10-28', 'Ford007', '0', null, '2016-06-25')
    );


    public function setUp() {

        $this->adapter = new DB_adapter();
        $this->queryTest = new User_Model($this->adapter->getConnection());
    }

    public function testGetCustomEntries() {

        echo "-----------getCustomEntries() TESTS START-----------". "\r\n";

        $j = 0;
        //walk through usserTableFields array using them as 1st parameter of the function
        foreach ($this->userTableFields as $field) {

            $userRandomizer = random_int(0,8);

            //tests with one single filter



            for ($i = 0; $i < count($this->userTableFields); $i++) {
                $filterFields = array($this->userTableFields[$i]);
                $filterArguments = array($this->userTableDummyValues[$userRandomizer][$i]);

                $result = $this->queryTest->getCustomEntries(array($field), $filterFields, $filterArguments);

                echo "Check results array structure". "\r\n";

                $this->assertTrue(is_array($result));
                $this->assertTrue(is_array($result[0]));

                echo "Check fields number returned". "\r\n";

                $this->assertTrue(count($result[0]) == 1);

                echo "Check returned fields". "\r\n";

                $this->assertTrue(array_key_exists($field, $result[0]));
            }

            //tests with two filters

            for ($i = 1; $i < (count($this->userTableFields)-2); $i++) {
                $filterFields = array($this->userTableFields[$i], $this->userTableFields[$i-1]);
                $filterArguments = array($this->userTableDummyValues[$userRandomizer][$i], $this->userTableDummyValues[$userRandomizer][$i-1]);

                $result = $this->queryTest->getCustomEntries(array($field), $filterFields, $filterArguments);

                echo "Check results array structure". "\r\n";

                $this->assertTrue(is_array($result));
                $this->assertTrue(is_array($result[0]));

                echo "Check fields number returned". "\r\n";

                $this->assertTrue(count($result[0]) == 1);

                echo "Check returned fields". "\r\n";

                $this->assertTrue(array_key_exists($field, $result[0]));
            }
            $j++;
        }

        echo "-----------getCustomEntries() TESTS END-----------". "\r\n";

    }

    public function testGetAllEntries() {

        echo "-----------getAllEntries() TESTS START-----------". "\r\n";

        $result = $this->queryTest->getAllEntries();

        echo "Check results array structure". "\r\n";
        
        $this->assertTrue(is_array($result));
        $this->assertTrue(is_array($result[0]));

        echo "Check fields number returned". "\r\n";

        $this->assertTrue(count($result[0]) == 16);

        foreach($result as $entry) {
            foreach ($this->userTableFields as $field) {

                echo "Check existing field in results: " . $field . "\r\n";

                $this->assertTrue(array_key_exists($field, $result[0]));
            }
        }


        echo "-----------getAllEntries() TESTS ENDS-----------". "\r\n";
    }

    public function testGetParseEntries() {

        echo "-----------getParseEntries() TESTS START-----------". "\r\n";

        $result = $this->queryTest->getParseEntries();

        echo "Check results array structure". "\r\n";

        $this->assertTrue(is_array($result));
        $this->assertTrue(is_array($result[0]));

        echo "Check fields number returned". "\r\n";

        $this->assertTrue(count($result[0]) == 10);

        foreach($result as $entry) {
            foreach ($this->parseFields as $field) {

                echo "Check existing field in results: " . $field . "\r\n";
                
                $this->assertTrue(array_key_exists($field, $entry));
            }
        }

        echo "-----------getParseEntries() TESTS ENDS-----------". "\r\n";
    }

    public function testGetParseEntry() {

        echo "-----------getParseEntry() TESTS START-----------". "\r\n";

        $tableEntriesNumber = $this->tableEntriesNumber;

        for ($i = 1; $i <= $tableEntriesNumber; $i++) {

            $result = $this->queryTest->getParseEntries($i);

            echo "Check results array structure". "\r\n";

            $this->assertTrue(is_array($result));
            $this->assertTrue(is_array($result[0]));

            echo "Check fields number returned". "\r\n";

            $this->assertTrue(count($result[0]) == 10);

            foreach($result as $entry) {
                foreach ($this->parseFields as $field) {

                    echo "Check existing field in results: " . $field . "\r\n";

                    $this->assertTrue(array_key_exists($field, $entry));
                }
            }

        }

        echo "-----------getParseEntry() TESTS ENDS-----------". "\r\n";
    }

    public function testGetIdValue() {

        echo "-----------getIdValue() TESTS START-----------". "\r\n";


        foreach ($this->userTableDummyValues as $dummyValues) {
            for ($i = 0; $i < count($this->userTableFields); $i++) {
                for ($j = 0; $j < count($this->userTableFields); $j++) {
                    if ($i != $j && $this->checkUniqueValues($this->userTableFields[$i], $this->userTableFields[$j])) {

                        $filterFields = array($this->userTableFields[$i], $this->userTableFields[$j]);
                        $filterArguments = array($dummyValues[$i], $dummyValues[$j]);

                        $result = $this->queryTest->getIdValue($filterFields, $filterArguments);

                        echo "Check results array structure". "\r\n";

                        $this->assertTrue(is_array($result));
                        $this->assertTrue(is_array($result[0]));

                        echo "Check fields number returned". "\r\n";

                        $this->assertTrue(count($result[0]) == 1);

                        foreach($result as $entry) {
                            echo "Check existing field in results: idUser" . "\r\n";

                            $this->assertTrue(array_key_exists("idUSER", $entry));

                            echo "Check result value (" . $entry["idUSER"] . " == " . $dummyValues[0] . ")\r\n";

                            $this->assertTrue($entry["idUSER"] == $dummyValues[0]);
                        }
                    }

                }
            }

        }

        echo "-----------getIdValue() TESTS ENDS-----------". "\r\n";

    }

    public function testInsertItem() {

        echo "-----------testInsertItem() TESTS START-----------". "\r\n";

        //user insertion data
        $newUserFields= array("publicName", "name ","phone", "eMail", "ads", "privateDes", "publicDes", "userRole",
            "language", "datePassword", "password", "memberSince");
        $validUserValues=array("JonDoe", "Jon Doe", "999899999", "jondoe@gmail.com", "true", "lorem ipsum", "lorem ipsum",
            "editor", "Català", "2016-11-18", "password1", "2016-11-18");
        $invalidUserValues=array("JonDoe", "Jon Doe", "999899999", "jondoe@gmail.com", "true", "lorem ipsum", "lorem ipsum",
            "editor", "Català", "2016-11-18", "password1", "2016-11-18");

        //first insertion
        $result = $this->queryTest->insertItem($newUserFields, $validUserValues);

        echo "Check insertion result (SUCCESS)". "\r\n";

        //insertion must success
        $this->assertTrue($result[0]["insertionId"] > 0);

        //second insertion
        $result = $this->queryTest->insertItem($newUserFields, $invalidUserValues);

        echo "Check insertion result (FAIL)". "\r\n";


        //insertion must fail
        $this->assertTrue($result[0]["insertionId"] == 0);


        echo "-----------testInsertItem() TESTS ENDS-----------". "\r\n";

    }

    public function testModifyItem() {
        echo "-----------testModifyItem() TESTS START-----------". "\r\n";

        //user modification data
        $newUserFields= array("publicName", "name ","phone", "eMail", "ads", "privateDes", "publicDes", "userRole",
            "language", "datePassword", "password", "memberSince");
        $newUserValues=array("JaneDoe", "Jane Doe", "928374584", "janedoe@gmail.com", "true", "lorem ipsum", "lorem ipsum",
            "administrator", "English", "2015-11-18", "marvin42", "2015-11-18");

        //first modification
        $result = $this->queryTest->modifyItem(10, $newUserFields, $newUserValues);

        echo "Check modification result (SUCCESS)". "\r\n";

        //modification must success
        $this->assertTrue($result[0]["modifiedRowsNum"] > 0);

        //second modification
        $result = $this->queryTest->modifyItem(300, $newUserFields, $newUserValues);

        echo "Check modification result (FAIL)". "\r\n";

        //modification must fail
        $this->assertTrue($result[0]["modifiedRowsNum"] == 0);

        echo "-----------testModifyItem() TESTS ENDS-----------". "\r\n";

    }

    public function testDeleteItem() {
        echo "-----------testDeleteItem() TESTS START-----------". "\r\n";

        //user deletion data
        $itemId = 10;

        //first deletion
        $result = $this->queryTest->deleteItem($itemId);

        echo "Check deletion result (SUCCESS)". "\r\n";

        //deletion must success
        $this->assertTrue($result[0]["deletedRowsNum"] > 0);

        //second deletion
        $result = $this->queryTest->deleteItem($itemId);

        echo "Check deletion result (FAIL)". "\r\n";

        //deletion must fail
        $this->assertTrue($result[0]["deletedRowsNum"] == 0);

        echo "-----------testDeleteItem() TESTS ENDS-----------". "\r\n";

    }

    public function testValueExists() {

        //check existing values in table USER from database

        $field = "publicName";
        $existingValues = array("Dent", "Zaphod", "Tricia", "Marvin", "Slatirbarfast", "LProsser", "ProstecnicVogonJeltz", "Eddie", "Prefect");

        foreach ($existingValues as $value) {
            $result = $this->queryTest->valueExists($field, $value);

            echo "Check result: " . $field . "\r\n";

            $this->assertTrue($result[0]["result"] == true);
        }

        //using names of existing values in table USER from database but with spelling errors

        $field = "publicName";
        $existingValues = array("dent", "Saphod", "Tr1cia", "Mrvin", "SlatirVArfast", "LPrser", "ProstecnicJeltz", "eddie", "Prefecto");

        foreach ($existingValues as $value) {
            $result = $this->queryTest->valueExists($field, $value);

            echo "Check result: " . $field . "\r\n";

            $this->assertTrue($result[0]["result"] == false);
        }


    }
   private function checkUniqueValues($value1, $value2) {
       return ($value1 == "publicName" || $value2 == "publicName" || $value1 == "name" || $value2 == "name"
           || $value1 == "phone" || $value2 == "phone" ||$value1 == "eMail" || $value2 == "eMail");
   }


}
