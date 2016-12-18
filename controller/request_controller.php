<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 29/10/16
 * Time: 16:55
 */

<<<<<<< HEAD
header("Content-Type: text/html;charset=utf-8");

include_once 'model/Image_Model.php';
=======
<<<<<<< HEAD
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
include_once 'model/User_Model.php';
include_once 'model/Tournament_Model.php';
include_once 'model/Prize_Model.php';
include_once 'model/Host_Model.php';
include_once 'model/Ranking_Model.php';
include_once  'model/Query.php';

//request kinds
<<<<<<< HEAD
=======
=======
include_once 'model/UserQuery.php';
include_once 'model/TournamentQuery.php';
include_once 'model/PrizeQuery.php';
include_once 'model/HostQuery.php';
include_once 'model/RankingQuery.php';
include_once  'model/Query.php';


>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
define("USERS_QUERY", "users");
define("PRIZES_QUERY", "prizes");
define("TOURNAMENTS_QUERY", "tournaments");
define("HOSTS_QUERY", "hosts");
define("RANKINGS_QUERY", "rankings");
<<<<<<< HEAD
define("GAMES_QUERY", "games");
define("IMAGES_OPERATION", "images");

//request names
define("ALL_VALUES", "allValues");
define("USERS_TOURNAMENT", "usersAtTournament");
define("DELETE_ITEM", "deleteItem");
define("INSERT_ITEM", "insertItem");
define("MODIFY_ITEM", "modifyItem");
=======

//request names
define("ALL_VALUES", "allValues");
define("DELETE_ITEM", "deleteItem");
define("INSERT_ITEM", "insertItem");
define("MODIFY_VALUE", "modifyValue");
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
define("ITEM_VALUES", "itemValues");
define("SEARCH_ID", "searchIdByField");
define("CUSTOM_SEARCH", "customSearch");
define("USER_LOGIN", "userLogin");
<<<<<<< HEAD
define("VALUE_CHECK", "valueExists");
define("DELETE_USER_TOURNAMENT", "deleteUserFromTournament");
define("TOURNAMENT_HAS_USER", "tournamentHasUser");
define("COUNT_TOURNAMENT_USERS", "countTournamentUsers");
=======
<<<<<<< HEAD
define("VALUE_CHECK", "valueExists");
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
//funcio cerca per enums de l'escriptorio, ha de retornar objecte

/**
 * Class request_controller
 */
<<<<<<< HEAD
=======
=======

>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
class request_controller {

    public $model;
    private $post = null;
<<<<<<< HEAD
    private $files = null;
    private $requestName = null;

=======
    private $requestName = null;
    private $fields = null;
    private $filterFields = null;
    private $filterArguments = null;
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f

    /**
     * Construtor fo the class, this will check the parameters passed at his call and call the correct subconstructor
     * emulating the multiple constructors functionality from another languages
     *
     * request_controller constructor.
     */
    public function __construct() {
        //store the parameters passed to the constructor at his call
        $params = func_get_args();
        //check the parameters number to decide wich constructor to use
        $paramsNum = func_num_args();
        //build the constructor name to use
        $constructor = '__construct'.$paramsNum;
        //check if constructor exists
        if(method_exists($this, $constructor)) {
            //call the constructor
            call_user_func_array(array($this, $constructor), $params);
        }

    }

    /**
     * Subconstructor called by main constructor when it has been called with one parameter
     *
     * @param String $queryMode
     */
    public function __construct1(String $queryMode) {
        $this->setModel($queryMode);
    }

    /**
     * Subconstructor called by main constructor when it has been called with two parameters
     *
     * @param String $queryMode
<<<<<<< HEAD
     * @param $post
=======
<<<<<<< HEAD
     * @param $post
=======
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
     */
    public function __construct2(String $queryMode, $post) {
        $this->setModel($queryMode);
        $this->post = $post;
    }

    /**
<<<<<<< HEAD
     * Subconstructor called by main constructor when it has been called with two parameters
     *
     * @param String $queryMode
     * @param $post
     */
    public function __construct3(String $queryMode, $post, $file) {
        $this->setModel($queryMode);
        $this->files = $file;
    }

    /**
=======
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
     * Calls the correct request depending on the "requestName", exists diferent kinds of request.
     * The standard request to be called if not specific request has been called will return the hole data necessary for
     * the clients to model an object of the request kind
     *
     * @return mixed
     */
    public function invoke() {

<<<<<<< HEAD
        if ($this->post == null && $this->files == null) {
            //if request has no post call the standard query
            $results = $this->model->getParseEntries();

        } else if ($this->post == null) {
            //if request has no post call the standard query
            $results = $this->model->storeImage($this->files['uploadedfile']['name'], $_FILES['uploadedfile']['tmp_name']);

=======
        if ($this->post == null) {
            //if request has no post call the standard query
            $results = $this->model->getParseEntries();
<<<<<<< HEAD

=======
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
        } else {

            $results = $this->launchRequest();
        }

        return $results;
    }

    /**
     * Calls the correct model depending on the "queryMode" of the request
     *
     * @param String $queryMode
     */
    private function setModel(String $queryMode) {
        switch ($queryMode) {
            case USERS_QUERY:
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
                $this->model = new User_Model();
                break;
            case PRIZES_QUERY:
                $this->model = new Prize_Model();
                break;
            case TOURNAMENTS_QUERY:
                $this->model = new Tournament_Model();
                break;
            case HOSTS_QUERY:
                $this->model = new Host_Model();
                break;
            case RANKINGS_QUERY:
                $this->model = new Ranking_Model();
<<<<<<< HEAD
                break;
            case GAMES_QUERY:
                $this->model = new Game_Model();
                break;
            case IMAGES_OPERATION:
                $this->model = new Image_Model();
=======
=======
                $this->model = new UserQuery();
                break;
            case PRIZES_QUERY:
                $this->model = new PrizeQuery();
                break;
            case TOURNAMENTS_QUERY:
                $this->model = new TournamentQuery();
                break;
            case HOSTS_QUERY:
                $this->model = new HostQuery();
                break;
            case RANKINGS_QUERY:
                $this->model = new RankingQuery();
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
                break;
        }
    }

    /**
     * Launch the specific query specified by the "requestName" inserted in the POST request
     * @return mixed
     * @throws Exception
     */
    private function launchRequest() {
        $this->requestName = $this->post["requestName"];

        $results = null;

        //checks the requested query name and returns the specific data for thisrequest
        switch ($this->requestName) {
<<<<<<< HEAD

            //AVAILABLE FOR ANY MODEL
            //----------------------------------------------------------------------------------------------------------

=======
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
            case ALL_VALUES:
                return $this->model->getAllEntries();
                break;
            case DELETE_ITEM:
<<<<<<< HEAD
                $itemId = $this->post['itemId'];
                return $this->model->deleteItem($itemId);
                break;
            case INSERT_ITEM:
=======
<<<<<<< HEAD

                // NOT IMPLEMENTED IN PROTOTYPE
//                $results = $this->model->getAllEntries();
                break;
            case INSERT_ITEM:

                // NOT IMPLEMENTED IN PROTOTYPE
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
                $fields = $this->post['fields'];
                $values = $this->post['values'];
                return $this->model->insertItem(json_decode($fields), json_decode($values));
                break;
<<<<<<< HEAD
            case MODIFY_ITEM:
                $itemId = $this->post['itemId'];
                $fields = $this->post['fields'];
                $values = $this->post['values'];
                return $this->model->modifyItem($itemId, json_decode($fields), json_decode($values));
                break;
            case ITEM_VALUES:
                $itemId = $this->post['itemId'];
                return $this->model->getParseEntry($itemId);
=======
            case MODIFY_VALUE:

                // NOT IMPLEMENTED IN PROTOTYPE
//                $results = $this->model->getAllEntries();
                break;
            case ITEM_VALUES:
                $idItem = $this->post['idItem'];
                return $this->model->getParseEntry($idItem);
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
                break;
            case SEARCH_ID:
                $fields = $this->post['filterFields'];
                $arguments = $this->post['filterArguments'];
<<<<<<< HEAD
                return $this->model->getIdValue($this->decodeJson($fields), $this->decodeJson($arguments));
                break;
            case CUSTOM_SEARCH:
                $fields = $this->decodeJson($this->post['fields']);
                $filterFields = null;
                $filterArguments = null;
                if (isset($this->post['filterFields'])) {
                    $filterFields = $this->decodeJson($this->post['filterFields']);
                    $filterArguments = $this->decodeJson($this->post['filterArguments']);
                }
                return $this->model->getCustomEntries($fields, $filterFields, $filterArguments);
=======
                return $this->model->getIdValue(json_decode($fields), json_decode($arguments));
=======
//                $results = $this->model->getAllEntries();
                break;
            case INSERT_ITEM:
//                $fields = array("publicName", "name", "phone", "eMail", "ads", "privateDes", "publicDes", "userRole", "language", "datePassword", "password", "memberSince");
//                $values = array("tricoman", "arnau biosca", "670087387", "arnaubiosca@gmail.com", "true", "es molt bona gent", "no le dejes dinero", "editor", "catala", "2016-10-10", "cacota", "2016-10-10");
                $fields = $this->post['fields'];
                $values = $this->post['values'];
                return $this->model->insertItem($fields, $values);

                break;
            case MODIFY_VALUE:
//                $results = $this->model->getAllEntries();
                break;
            case ITEM_VALUES:
                return $this->model->getParseEntry();
                break;
            case SEARCH_ID:
                return $this->model->getCustomEntries();
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
                break;
            case CUSTOM_SEARCH:
                return $this->model->getCustomEntries($this->fields, $this->filtersFields, $this->filtersArguments);
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
                break;
            case USER_LOGIN:
                $userPublicName = $this->post["userPublicName"];
                $userPassword = $this->post["userPassword"];
                return $this->model->checkLogIn($userPublicName, $userPassword);
                break;
<<<<<<< HEAD
            case VALUE_CHECK:
                $field = $this->post["field"];
                $value = $this->post["value"];
                return $this->model->valueExists($field, $value);
                break;

            //AVAILABLE ONLY FOR Tournament_Model
            //----------------------------------------------------------------------------------------------------------

            case USERS_TOURNAMENT:
                $tournamentId = $this->post["tournamentId"];
                return $this->model->usersAtTournament($tournamentId);
                break;

            case DELETE_USER_TOURNAMENT:
                $tournamentId = $this->post['tournamentId'];
                $userId = $this->post['userId'];

                return $this->model->deleteUserFromTournament($tournamentId, $userId);
                break;

            case TOURNAMENT_HAS_USER:
                $tournamentId = $this->post['tournamentId'];
                $userId = $this->post['userId'];
                return $this->model->tournamentHasUser($tournamentId, $userId);

            case COUNT_TOURNAMENT_USERS:
                $tournamentId = $this->post['tournamentId'];
                return $this->model->countTournamentUsers($tournamentId);
=======
<<<<<<< HEAD
            case VALUE_CHECK:
                $field = $this->post["field"];
                $value = $this->post["value"];
/*                $field = "[\"publicName\"]";
                $value =  "[\"arnaubsdfdiosca@gmail.com\"]";*/
                return $this->model->valueExists($field, $value);
                break;
=======
>>>>>>> f7fc3bef3b6f3be22aed07ec831da1a27a6ff2f0
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f
            DEFAULT:
                throw new Exception("Unknow request name");

        }
    }

<<<<<<< HEAD
    private function decodeJson($resultToDecode) {

        if (strpos($resultToDecode, ",")) {
            $decodedResult = json_decode($resultToDecode);
        } else {
            $resultToDecode = $this->resultCleaner($resultToDecode);
            $decodedResult  = array($resultToDecode);
        }

        return $decodedResult;

    }

    private function resultCleaner($toClean) {
        $toClean = str_replace("[", "", $toClean);
        $toClean = str_replace("]", "", $toClean);
        $toClean = str_replace("'", "", $toClean);
        $cleaned = str_replace("\"", "", $toClean);

        return $cleaned;
    }

=======
>>>>>>> 885c18023b035df0ab7f4dc5ef791a5cbb07537f

}