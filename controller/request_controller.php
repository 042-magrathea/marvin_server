<?php

/**
 * Created by PhpStorm.
 * User: Arnau Biosca Nicolas
 * Date: 29/10/16
 * Time: 16:55
 */

include_once 'model/User_Model.php';
include_once 'model/Tournament_Model.php';
include_once 'model/Prize_Model.php';
include_once 'model/Host_Model.php';
include_once 'model/Ranking_Model.php';
include_once  'model/Query.php';

//request kinds
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
define("VALUE_CHECK", "valueExists");
//funcio cerca per enums de l'escriptorio, ha de retornar objecte

/**
 * Class request_controller
 */
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
     * @param $post
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

                // NOT IMPLEMENTED IN PROTOTYPE
//                $results = $this->model->getAllEntries();
                break;
            case INSERT_ITEM:

                // NOT IMPLEMENTED IN PROTOTYPE
                $fields = $this->post['fields'];
                $values = $this->post['values'];
                return $this->model->insertItem(json_decode($fields), json_decode($values));
                break;
            case MODIFY_VALUE:

                // NOT IMPLEMENTED IN PROTOTYPE
//                $results = $this->model->getAllEntries();
                break;
            case ITEM_VALUES:
                $idItem = $this->post['idItem'];
                return $this->model->getParseEntry($idItem);
                break;
            case SEARCH_ID:
                $fields = $this->post['filterFields'];
                $arguments = $this->post['filterArguments'];
                return $this->model->getIdValue(json_decode($fields), json_decode($arguments));
                break;
            case CUSTOM_SEARCH:
                return $this->model->getCustomEntries($this->fields, $this->filtersFields, $this->filtersArguments);
                break;
            case USER_LOGIN:
                $userPublicName = $this->post["userPublicName"];
                $userPassword = $this->post["userPassword"];
                return $this->model->checkLogIn($userPublicName, $userPassword);
                break;
            case VALUE_CHECK:
                $field = $this->post["field"];
                $value = $this->post["value"];
/*                $field = "[\"publicName\"]";
                $value =  "[\"arnaubsdfdiosca@gmail.com\"]";*/
                return $this->model->valueExists($field, $value);
                break;
            DEFAULT:
                throw new Exception("Unknow request name");

        }
    }


}