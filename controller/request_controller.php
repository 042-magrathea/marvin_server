<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 29/10/16
 * Time: 16:55
 */

include_once 'model/UserQuery.php';
include_once 'model/TournamentQuery.php';
include_once 'model/PrizeQuery.php';
include_once 'model/HostQuery.php';
include_once 'model/RankingQuery.php';
include_once  'model/Query.php';


define("USERS_QUERY", "users");
define("PRIZES_QUERY", "prizes");
define("TOURNAMENTS_QUERY", "tournaments");
define("HOSTS_QUERY", "hosts");
define("RANKINGS_QUERY", "rankings");

//request names
define("ALL_VALUES", "allValues");
define("DELETE_ITEM", "deleteItem");
define("INSERT_ITEM", "insertItem");
define("MODIFY_VALUE", "modifyValue");
define("ITEM_VALUES", "itemValues");
define("SEARCH_ID", "searchIdByField");
define("CUSTOM_SEARCH", "customSearch");
define("USER_LOGIN", "userLogin");

class request_controller {

    public $model;
    private $post = null;
    private $requestName = null;
    private $fields = null;
    private $filterFields = null;
    private $filterArguments = null;

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
     */
    public function __construct2(String $queryMode, $post) {
        $this->setModel($queryMode);
        $this->post = $post;
    }

    /**
     * Calls the correct request depending on the "requestName", exists diferent kinds of request.
     * The standard request to be called if not specific request has been called will return the hole data necessary for
     * the clients to model an object of the request kind
     *
     * @return mixed
     */
    public function invoke() {

        if ($this->post == null) {
            //if request has no post call the standard query
            $results = $this->model->getParseEntries();
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
            case ALL_VALUES:
                return $this->model->getAllEntries();
                break;
            case DELETE_ITEM:
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
                break;
            case CUSTOM_SEARCH:
                return $this->model->getCustomEntries($this->fields, $this->filtersFields, $this->filtersArguments);
                break;
            case USER_LOGIN:
                $userPublicName = $this->post["userPublicName"];
                $userPassword = $this->post["userPassword"];
                return $this->model->checkLogIn($userPublicName, $userPassword);
                break;
            DEFAULT:
                throw new Exception("Unknow request name");

        }
    }


}