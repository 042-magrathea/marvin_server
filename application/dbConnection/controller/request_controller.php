<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 29/10/16
 * Time: 16:55
 */

header("Content-Type: text/html;charset=utf-8");

include_once 'application/dbConnection/model/Image_Model.php';
include_once 'application/dbConnection/model/User_Model.php';
include_once 'application/dbConnection/model/Tournament_Model.php';
include_once 'application/dbConnection/model/Prize_Model.php';
include_once 'application/dbConnection/model/Host_Model.php';
include_once 'application/dbConnection/model/Ranking_Model.php';
include_once 'application/dbConnection/model/Game_Model.php';
include_once 'application/dbConnection/model/System_Model.php';
include_once 'application/dbConnection/model/Query.php';

//request kinds
define("USERS_QUERY", "users");
define("PRIZES_QUERY", "prizes");
define("TOURNAMENTS_QUERY", "tournaments");
define("HOSTS_QUERY", "hosts");
define("RANKINGS_QUERY", "rankings");
define("GAMES_QUERY", "games");
define("SYSTEMS_QUERY", "system");
define("IMAGES_OPERATION", "images");

//request names
define("ALL_VALUES", "allValues");
define("USERS_TOURNAMENT", "usersAtTournament");
define("DELETE_ITEM", "deleteItem");
define("INSERT_ITEM", "insertItem");
define("MODIFY_ITEM", "modifyItem");
define("ITEM_VALUES", "itemValues");
define("SEARCH_ID", "searchIdByField");
define("CUSTOM_SEARCH", "customSearch");
define("USER_LOGIN", "userLogin");
define("VALUE_CHECK", "valueExists");
define("ADD_USER_TOURNAMENT", "addUserToTournament");
define("DELETE_USER_TOURNAMENT", "deleteUserFromTournament");
define("TOURNAMENT_HAS_USER", "tournamentHasUser");
define("COUNT_TOURNAMENT_USERS", "countTournamentUsers");
define("USER_IS_UMPIRE", "userIsUmpire");
//funcio cerca per enums de l'escriptorio, ha de retornar objecte

/**
 * Class request_controller
 */
class request_controller {

    public $model;
    private $post = null;
    private $files = null;
    private $requestName = null;


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
     * @param $post
     */
    public function __construct2(String $queryMode, $post) {
        $this->setModel($queryMode);
        $this->post = $post;
    }

    /**
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
     * Calls the correct request depending on the "requestName", exists diferent kinds of request.
     * The standard request to be called if not specific request has been called will return the hole data necessary for
     * the clients to model an object of the request kind
     *
     * @return mixed
     */
    public function invoke() {

        if ($this->post == null && $this->files == null) {
            //if request has no post call the standard query
            $results = $this->model->getParseEntries();

        } else if ($this->post == null) {
            //if request has no post call the standard query
            $results = $this->model->storeImage($this->files['uploadedfile']['name'], $_FILES['uploadedfile']['tmp_name']);

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
                break;
            case GAMES_QUERY:
                $this->model = new Game_Model();
                break;
            case SYSTEMS_QUERY:
                $this->model = new System_Model();
                break;
            case IMAGES_OPERATION:
                $this->model = new Image_Model();
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

            //AVAILABLE FOR ANY MODEL
            //----------------------------------------------------------------------------------------------------------

            case ALL_VALUES:
                return $this->model->getAllEntries();
                break;
            case DELETE_ITEM:
                $itemId = $this->decodeJson($this->post['itemId']);
                return $this->model->deleteItem($itemId);
                break;
            case INSERT_ITEM:
                $fields = $this->decodeJson($this->post['fields']);
                $values = $this->decodeJson($this->post['values']);
                return $this->model->insertItem($fields, $values);
                break;
            case MODIFY_ITEM:
                $itemId = $this->post['itemId'];
                $fields = $this->post['fields'];
                $values = $this->post['values'];
                return $this->model->modifyItem(json_decode($itemId), json_decode($fields), json_decode($values));
                break;
            case ITEM_VALUES:
                $itemId = $this->post['itemId'];
                return $this->model->getParseEntry($itemId);
                break;
            case SEARCH_ID:
                $fields = $this->post['filterFields'];
                $arguments = $this->post['filterArguments'];
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
                break;
            case USER_LOGIN:
                $userPublicName = $this->post["userPublicName"];
                $userPassword = $this->post["userPassword"];
                return $this->model->checkLogIn($userPublicName, $userPassword);
                break;
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

            case ADD_USER_TOURNAMENT:
                $tournamentId = $this->decodeJson($this->post['tournamentId']);
                $userId = $this->decodeJson($this->post['userId']);

                return $this->model->addUserToTournament($tournamentId, $userId);
                break;

            case DELETE_USER_TOURNAMENT:
                $tournamentId = $this->decodeJson($this->post['tournamentId']);
                $userId = $this->decodeJson($this->post['userId']);

                return $this->model->deleteUserFromTournament($tournamentId, $userId);
                break;


            case TOURNAMENT_HAS_USER:
                $tournamentId = $this->post['tournamentId'];
                $userId = $this->post['userId'];
                return $this->model->tournamentHasUser($tournamentId, $userId);

            case COUNT_TOURNAMENT_USERS:
                $tournamentId = $this->post['tournamentId'];
                return $this->model->countTournamentUsers($tournamentId);

            case USER_IS_UMPIRE:
                $tournamentId = $this->post['tournamentId'];
                $userId = $this->post['userId'];
                return $this->model->userIsUmpire($tournamentId, $userId);

            DEFAULT:
                throw new Exception("Unknow request name");

        }
    }

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


}